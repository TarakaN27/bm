<?php
	ini_set('session.cookie_domain', '.bm-market.kz' );
    session_start();
    include('db_connect.php');

    $message2 = '';
    if (isset($_POST['sub_log']) && isset($_POST['login']) && isset($_POST['password'])) {

    $login = mysql_escape_string($_POST["login"]);
    $password = mysql_escape_string($_POST["password"]);

    $result = mysql_query("select login, id from users where login='".$login."' and pass='".$password."'");
    echo mysql_error();
    if (mysql_num_rows($result) != 0) {
        $row = mysql_fetch_array($result);
        $_SESSION['login'] = $row['login'];
		$_SESSION['id'] = $row['id'];
        header("Location: index.php");
        die();
    }
    else {
        $message2 = '<div class="alert alert-danger" role="alert">
                                            Логин или пароль неверен.
                                        </div>';
    }

    }

    mysql_close($con);

?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="ru">
<!--<![endif]-->

<head>
	<script>
	//window.replainSettings = { id: '0243b914-bcad-4902-9b4b-1768f3ee111b' };
	//(function(u){var s=document.createElement('script');s.type='text/javascript';s.async=true;s.src=u;
	//var x=document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);
	//})('https://widget.replain.cc/dist/client.js');
	</script>	
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Boom Market</title>
    <meta name="description" content="Маркетинг план">
    <meta name="keywords" content="маркетинг, маркетинг план, Казахстан, Алматы, Атырау, Нур-Султан, Шымкент, Караганды, Павлодар, Актобе, Актау, Тараз, Уральск, Кокшетау, Семей, Оскемен">
    <meta name="author" content="bm-market.kz">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

<script>
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>
<style type="text/css">
body {
    
    background-image: url(http://bm-market.kz/Office/images/bg.jpg); //картинка
}</style>
</head>

<body >


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="../index.php">
                        <img class="align-content" src="" alt="" style="width: 150px;">
                    </a>
                </div>
				<div class="login-content">
                <div class="login-logo">
				<img class="align-content" src="../Office/images/logos.png" alt="" style="width: 150px;">
                </div>
					<div class="login-form">
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label>Логин</label>
                            <input type="text" name="login" class="form-control" placeholder="Ваш логин">
                        </div>
                            <div class="form-group">
                                <label>Пароль</label>
                                <input type="password" name="password" class="form-control" placeholder="Пароль">
                        </div>
                                <div class="checkbox">
                                    <label>
                                <input type="checkbox"> Запомнить меня
                            </label>
                                    <label class="pull-right">
                                <a href="forget_password.php">Забыли пароль?</a>
                            </label>

                                </div>
                                <input type="submit" class="btn btn-success btn-flat m-b-30 m-t-30" value="Войти" name="sub_log">
                                <div class="register-link m-t-15 text-center">
                                    <p>Нет аккаунта ? <a href="register.php"> Зарегистрируйся</a></p>
                                </div>
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
