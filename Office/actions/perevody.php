<?php
session_start();

include('../db_connect.php');

if(isset($_SESSION['login'])) {
	$my_user = mysql_query("SELECT * FROM users WHERE BINARY login='".$_SESSION['login']."'");
	$my_user = mysql_fetch_assoc($my_user);
}
if($_POST['type']=='check' && isset($_POST['username'])) {
	$user = mysql_query("SELECT login, fio, hide_data FROM users WHERE login='".$_POST['username']."'");
	$user = mysql_fetch_assoc($user);
	
	if($user && is_array($user) && $user['login']!=$_SESSION["login"]) {
		if($user["hide_data"] == 0 && $_SESSION["login"] != "BoomMarket"){
			$user["fio"] = "ФИО скрыто пользователем";
		}
		echo $user['fio'];
	} else {
		echo 'err';
	}
	
// PEREVOD
} else if($_POST['type']=='perevod' && isset($_POST['username']) && is_numeric($_POST['summa']) && $_POST['summa']>0 && $_POST['summa']<=$my_user['akwa']) {
	
	$check_query = mysql_query("SELECT `id`, `login`, `akwa` FROM `users` WHERE `login`='".$_POST['username']."'");
	$check_query_two = mysql_query("SELECT `id`, `login`, `akwa` FROM `users` WHERE `login`='".$_SESSION['login']."'");
	
	$check = mysql_fetch_assoc($check_query);
	$check_two = mysql_fetch_assoc($check_query_two);
	
	if($check && is_array($check) && $check_two && is_array($check_two)) {
		
		$partnerID = 0;
		$summa = 0;

		$partnerID = $check['id'];

		if(floatval($check_two['akwa'])>=floatval($_POST['summa'])) {
			$summa = $_POST['summa'];
		}

		if($partnerID && $summa) {

				if(mysql_query("UPDATE users SET akwa=akwa-$summa WHERE id=".$my_user['id'])) {

					if(mysql_query("UPDATE users SET akwa=akwa+$summa WHERE id=".$partnerID)) {

						mysql_query("insert into int_transfer (sender, receiver, amount, sent_time) values ('".$my_user['login']."', '".$check['login']."', ".$summa.", '".date('Y-m-d H:i:s')."')");

						echo 'ok';

					} else
						echo 'Ошибка при поступлении денег.';

				} else
					echo 'Ошибка при переводе денег.';
				
		} else {
			echo 'Данные не найдены';
		}
		
	} else
		echo 'Ошибка данных';

} else
	echo 'err';

?>