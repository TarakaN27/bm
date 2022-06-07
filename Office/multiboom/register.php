<?php
session_start();
include('db_connect.php');
//include "smsc_api.php";

$rel = ''; $message2 = '';
if (isset($_GET['rel'])) $rel = $_GET['rel'];
if ($rel!="") $rel = base64_decode($rel);
if (isset($_POST['sub_reg']) && isset($_POST['r_fio']) && isset($_POST['r_login']) && $_POST['r_login']!="" && isset($_POST['r_rel']) && isset($_POST['r_pass'])) {
if ($_POST['r_pass'] == $_POST['r_pass2'] && $_POST['r_pass']!="") {
    $rel = mysql_escape_string($_POST['r_rel']);
    $fio = mysql_escape_string($_POST["r_fio"]);
    $login = mysql_escape_string($_POST["r_login"]);
    $password = mysql_escape_string($_POST["r_pass"]);
    $phone = mysql_escape_string($_POST["r_phone"]);
    //if (isset($_POST['valid'])) $valid = 1; else $valid = 0;
    date_default_timezone_set('Asia/Almaty');

        $result = mysql_query("select login from users where login='".$login."'");
        if (mysql_num_rows($result) == 0) {
            $result = mysql_query("select login from users where login='".$rel."'");
            if (mysql_num_rows($result) > 0) {
                //$password = rand(100000,999999);
                
                mysql_query("insert into users ( fio, login, pass, sponsor, phone, aktsiya, reg_time ) values ('".$fio."', '".$login."', '".$password."', '".$rel."', '".$phone."', 0, '".date('Y-m-d H:i:s')."')");
                echo mysql_error();
                //$sms_body = 'Ваш код активации: '.$password;
                //$ph = '7'.substr($phone,1,strlen($phone)-1);
                //list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");
                //echo mysql_error();
                $message2 = '<div class="alert alert-success" role="alert">
                                                Вы успешно зарегистрировались.
                                            </div>';
            }
            else {
                $message2 = '<div class="alert alert-danger" role="alert">
                                                Спонсора под таким логином не существует.
                                            </div>';
            }
        }
        else {
            $message2 = '<div class="alert alert-danger" role="alert">
                                            Этот логин уже существует.
                                        </div>';
        }
    }
    else {
            $message2 = '<div class="alert alert-danger" role="alert">
                                            Пароли не совпадают.
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
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
	<script>
	//window.replainSettings = { id: '0243b914-bcad-4902-9b4b-1768f3ee111b' };
	//(function(u){var s=document.createElement('script');s.type='text/javascript';s.async=true;s.src=u;
	//var x=document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);
	//})('https://widget.replain.cc/dist/client.js');
	</script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-150392055-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-150392055-1');
	</script>
	<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(55847452, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/55847452" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Boom Market</title>
    <meta name="description" content="Маркетинг план">
    <meta name="keywords" content="маркетинг, маркетинг план, Казахстан, Алматы, Атырау, Нур-Султан, Шымкент, Караганды, Павлодар, Актобе, Актау, Тараз, Уральск, Кокшетау, Семей, Оскемен">
    <meta name="author" content="bm-market.kz">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="images/logos.png">


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
                        <img class="align-content" src="../office/images/logo_white_2.png" alt="" style="width: 150px">
                    </a>
                </div>
                <div class="login-form">
                    <form action="register.php" method="post">
                        <div class="form-group">
                                <label>ФИО</label>
                                <input type="text" name="r_fio" class="form-control" placeholder="Фамилия Имя Отчество">
                        </div>
                        <div class="form-group">
                                <label>Логин (на латинском)</label>
                                <input type="text" name="r_login" class="form-control" placeholder="Введите логин">
                        </div>
						<div class="form-group">
                                <label>Пароль</label>
                                <input type="password" name="r_pass" class="form-control" placeholder="Введите пароль">
                        </div>
						<div class="form-group">
                                <label>Подтверждение пароля</label>
                                <input type="password" name="r_pass2" class="form-control" placeholder="Повторите пароль">
                        </div>
                        <div class="form-group">
                                <label>Телефон</label>
                                <input type="text" name="r_phone" class="form-control" placeholder="Номер телефона">
                        </div>
						<div class="form-group">
                                <label>Логин спонсора</label>
                                <input type="text" name="r_rel" class="form-control" placeholder="Логин пригласившего партнера" value="<?= $rel ?>">
                        </div>
                                    <div class="checkbox">
                            <label>
                                <input type="checkbox"> Согласие с правилами платформы
                            </label>
                                    </div>
                                    <input type="submit" class="btn btn-info btn-flat m-b-30 m-t-30" value="Регистрация" name="sub_reg">

                                    <div class="register-link m-t-15 text-center">
                                        <p>Уже есть аккаунт ? <a href="login.php"> Войти</a></p>
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
