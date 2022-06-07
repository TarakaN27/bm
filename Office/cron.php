<?php
include_once "db_data.php";

// Бонус пользователю за свою активность (за переход на новый уровень)
$bonusToUserForCloseLevel = [
    'm0' => 0,    // после регистрации (нет уровня)
    'm1' => 0,    // за переход с 0 на 1 (попадание в m1)
    'm2' => 0, // за переход с 1 на 2 (попадание в m2)
    'm3' => 8000, // за переход с 2 на 3 (попадание в m3)
    'm4' => 40000,// за переход с 3 на 4 (попадание в m4)
    'm5' => 100000,
    'm6' => 672000,
    'm7' => 2180000,
    'm8' => 6000000
];

// Бонус спонсору за активность реферала - "закрытие уровня" через набор группы (за переход на новый уровень)
$bonusToSponsorForReferalClosedLevel = [
    'm0' => 0, // за регистрацию (новый пользователь)
    'm1' => 0, // с 0 на 1 можно перейти только через покупку - массив ниже (попадание в m1)
    'm2' => 2000, // за переход с 1 на 2 (попадание в m2)
    'm3' => 2000,
    'm4' => 0,
    'm5' => 0,
    'm6' => 0,
    'm7' => 0
];

// Бонус спонсору за активность реферала - покупка уровня за деньги (за переход на новый уровень)
$bonusToSponsorForReferalPaydLevel = [
    'm0' => 500, // за регистрацию (новый пользователь)
    'm1' => 1000, // за покупку 1 уровня (попадание в m1)
    'm2' => 4000, // за покупку 2 уровня (попадание в m2)
    'm3' => 11000, // за покупку 3 уровня (попадание в m3)
    'm4' => 30000,
    'm5' => 50000,
    'm6' => 100000,
    'm7' => 200000
];

myconnect($mysqli);

$readyUsers = getReadyUsers();
myecho("*** Найдено " . count($readyUsers) . " пользователей с измененными уровнями");

foreach ($readyUsers as $user) {
myecho("Старт обработки пользователя " . $user['login']);

    $sponsor = trim($user['sponsor']);
    $status = $user['status'];
myecho("sponsor: " . $sponsor);	
myecho("Текущий уровень (новый): " . $status-1);
myecho("Старый уровень: " . $user['statusOld']);
myecho("isStatusPaid: " . $user['isStatusPaid']);
	if( $user['isStatusPaid'] > 0) {
		myecho("Тип перехода на уровень: Покупка");
	}else{
		myecho("Тип перехода на уровень: Закрытие приглашениями");
	}

    $level = "m".($status-1);
myecho("level: " . $level);	
    if(!isValidKeys($level)) {
        echo "Not valid key: " . $level . "\n";
        die;
    }
myecho(". . .");	
    $bonus1 = $bonusToUserForCloseLevel[$level];
myecho("bonusToUserForCloseLevel[$level] (bonus1): " . $bonus1);
	$disp = $status - $user['statusOld']; // разница между уровнями
	if ($disp >= 2) {
myecho("Разрыв между старым и новым статусом превышает 1, а значит бонус пользователю не положен");		
	}
	if ($status <= 1) {
myecho("Если первый уровень, то бонус не положен, т.к. он купил его");		
	}	
    if ($bonus1 > 0 && $disp < 2 && $status > 1) {
myecho("Начисление бонуса пользователю за свою активность (за переход на новый уровень): " . $bonus1);
        addLevelBonus($bonus1, $user, "закрытие уровня");
    }
 
    // Определим КАК был закрыт уровень - покупкой или набором группы
    // Признак покупки users.isStatusPaid=1 

myecho(". . .");	
    $bonus2Type = "";
    if ($user["isStatusPaid"] > 0) {
myecho("isStatusPaid > 0");
        $bonus2 = $bonusToSponsorForReferalPaydLevel[$level];
        $bonus2Type = "REF BONUS".($status-1)."ур.";
    } else {
myecho("isStatusPaid = 0");		
        $bonus2 = $bonusToSponsorForReferalClosedLevel[$level];
        $bonus2Type = "RE-BONUS".($status-1)."ур.";
    }
myecho("bonus2: " . $bonus2);
myecho("bonus2Type: " . $bonus2Type);    
    if ($bonus2 > 0 && strlen($sponsor)>0) {
		if ( $status == 4 && $user["isStatusPaid"] == 0) {
myecho("Спонсор НЕ получает бонус, т.к. ->m4 и isStatusPaid=0 (Закрытие приглашениями)");
			// Доп. условие по ТЗ:
			// Спонсор НЕ получает бонус, в случае если ->m4 и isStatusPaid=0 (Закрытие приглашениями)			
		}else{
myecho("Бонус спонсору за активность реферала ($bonus2Type): " . $bonus2);			
			addReferalBonus($bonus2, $user, $sponsor, $bonus2Type);
		}
    }
myecho(". . .");	
myecho("Обновление isStatusPaid = 0, statusOld = " . $status);
    updateStatusesById($status, $user['id']);
    //break;
myecho(" - - - ");
}

function addReferalBonus($amount, $user, $sponsor_login, $comment) {
    global $mysqli;
    if(!updateBalanceBylogin($amount, $sponsor_login)){
        logSqlErr($mysqli);
        return false;
    }
    if(!addTransfer($sponsor_login, $amount, $user['login'], $comment)){
        logSqlErr($mysqli);
        return false;
    }    
    return true;
}

function addLevelBonus($amount, $user, $comment) {
    global $mysqli;
    if(!updateBalanceBylogin($amount, $user['login'])){
        logSqlErr($mysqli);
        return false;
    }
    if(!addTransfer($user['login'], $amount, $user['login'], $comment)){
        logSqlErr($mysqli);
        return false;
    }    
    return true;
}

function updateBalanceBylogin($sum, $login) {
    global $mysqli;
    $sql = "UPDATE users SET akwa=akwa+$sum WHERE login='$login'";
    $res = $mysqli->query($sql);
    return $res;
}

function updateStatusesById($status, $id) {
    global $mysqli;
    $sql = "UPDATE users SET statusOld=$status, isStatusPaid=0 WHERE id='$id'";
    if(!$mysqli->query($sql)) {
        logSqlErr($mysqli);
        return false;
    }
    return true;
}

/**
 * Получить юзеров у которых status != statusOld
*/
function getReadyUsers() {
    global $mysqli;
    //$sql = "SELECT * FROM users WHERE `status` != statusOld";
    $sql = "SELECT id, login, sponsor, `status`, statusOld, isStatusPaid FROM users WHERE `status` != statusOld";
    $readyUsers = [];
    if ($result = $mysqli->query($sql)) {
        while($row = $result->fetch_array()){
            $readyUsers[] = $row;
        }
    }
    $result->close();
    return $readyUsers;
}




function addTransfer($login, $amount, $user_login, $product) {
    global $mysqli;
myecho("Добавление записи в лог транзакций (transfer) login='$login', amount = '$amount'");
    $datetime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO transfer (login, amount, user_login, product, sent_time) VALUES ('$login', '$amount', '$user_login', '$product', '$datetime');";
    $res = $mysqli->query($sql);
    return $res;
}

function myconnect(&$mysqli) {
    global $db_host, $db_user, $db_pass, $db_database;
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_database);
    if ($mysqli->connect_error) {
        /* Используйте предпочитаемый вами метод регистрации ошибок */
        error_log('Ошибка при подключении: ' . $mysqli->connect_error);
    }
}

function logErr($code, $message, $type = "php") {
    echo "$type: [$code] - $message\n";
}

function logSqlErr($mysqli) {
    logErr($mysqli->errno, $mysqli->error, "sql");
}

function isValidKeys($key) {
    global $bonusToUserForCloseLevel, $bonusToSponsorForReferalClosedLevel, $bonusToSponsorForReferalPaydLevel;
    if (!isset($bonusToUserForCloseLevel[$key])) {
        logErr(100, "Not found key: " . $key . " for bonusToUserForCloseLevel");
        return false;
    }
    
    if (!isset($bonusToSponsorForReferalClosedLevel[$key])) {
        logErr(101, "Not found key: " . $key . " for bonusToSponsorForReferalClosedLevel");
        return false;
    }

    if (!isset($bonusToSponsorForReferalPaydLevel[$key])) {
        logErr(102, "Not found key: " . $key . " for bonusToSponsorForReferalPaydLevel");
        return false;
    }    
    return true;
}

function myecho($message) {
    echo $message. "   <br>\n";
}