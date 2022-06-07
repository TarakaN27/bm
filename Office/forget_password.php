<?php
session_start();
include('db_connect.php');
include "smsc_api.php";

if (isset($_POST['sub_btn'])) {
	//echo 'test';
	$login = mysql_escape_string($_POST['login']);
	$result = mysql_query("select login, pass, phone from users where login='".$login."'");
	if (mysql_num_rows($result) > 0) {
		//echo $login;
		$row = mysql_fetch_array($result);
		$phone = preg_replace('/\s+/', '', $row['phone']);
		$phone = str_replace('+7', '7', $phone);
		$ph = '7'.substr($phone,1,strlen($phone)-1);
		$res = mysql_query("select * from reset_password where login='".$row['login']."' and date(post_time)=date(now())");
		//echo $ph;
		if (mysql_num_rows($res) <= 1) {
			mysql_query("insert into reset_password (login, phone, post_time) values ('".$row['login']."', '".$ph."', '".date('Y-m-d H:i:s')."')");
			if (strlen($ph) == 11) {
				$sms_body = "Ваш пароль: ".$row['pass'];
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=1");
				//list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 1);
				$message2 = "<div class='alert alert-success'>Пароль отправлен. Вы должны получить пароль по СМС.</div>";
			}
			else $message2 = "<div class='alert alert-danger'>Ваш номер указан неправильно. Обращайтесь к администратору.</div>";
		}
		else $message2 = "<div class='alert alert-danger'>Вы превысили лимит восстановления пароля.</div>";
	}
	else $message2 = "<div class='alert alert-danger'>Такого логина не существует.</div>";
}

?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GS Matrix</title>
    <meta name="description" content="SanaliBrend - Маркетинг">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="img/fav_baxar.png">


    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>


</head>

<body style="background: rgba(3, 3, 3, 1);">


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="../index.php">
                        <img class="align-content" src="../images/logo_b1.png" alt="" style="width: 150px;">
                    </a>
                </div>
                <div class="login-form">
                    <form action="forget_password.php" method="post">
                        <div class="form-group">
                            <label>Логин</label>
                            <input type="text" name="login" class="form-control" placeholder="Ваш логин">
                        </div>
                           
                                <input type="submit" class="btn btn-danger btn-flat m-b-30 m-t-30" value="Восстановить пароль" name="sub_btn">
					<?php
						if ($message2!="") echo $message2;
					?>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>


</body>

</html>
