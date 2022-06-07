<?php

session_start();
include('db_connect.php');
include "smsc_api.php";
$result = mysql_query("select * from users where phone='".$_SESSION['phone']."'");
$flag = false; $message = "";
date_default_timezone_set('Asia/Aqtau');

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	
	if (isset($_POST['sub_btn'])) {
		if ($row['akwa'] >= 1500) {
			mysql_query("update users set akwa=akwa-1500, status=1 where phone='".$_SESSION['phone']."'");
			$flag = true;
			$row['akwa'] = $row['akwa'] - 1500;
			$row['status'] = 1;
		}
		else $message = '<div class="alert alert-danger" role="alert">
                                        Недостаточно средств.
                                    </div>';
	}
	
	if (isset($_POST['sub_btn2'])) {
		if ($row['akwa2'] >= 15000) {
			mysql_query("update users set akwa2=akwa2-15000, status2=1 where phone='".$_SESSION['phone']."'");
			$flag2 = true;
			$row['akwa2'] = $row['akwa2'] - 15000;
			$row['status2'] = 1;
		}
		else $message2 = '<div class="alert alert-danger" role="alert">
                                        Недостаточно средств.
                                    </div>';
	}
}
else {
	header("Location: ../index.php");
	die();
}

if ($flag) {
	$i = 1;
	$sponsor = $row['sponsor'];
	while ($sponsor>0 && $i <= 7) {
		$result_x = mysql_query("select sponsor, phone from users where user_id=".$sponsor);
		if (mysql_num_rows($result_x)>0) {
			$row_x = mysql_fetch_array($result_x);			
			if ($i == 1) $amount = 500; else $amount = 100;
			mysql_query("update users set akwa=akwa+".$amount." where user_id=".$sponsor);
			mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$sponsor.", ".$amount.", ".$i.", ".$row["user_id"].", 0, 0, '".date('Y-m-d H:i:s')."') ");
			echo mysql_error();
			if ($sponsor>7) {
				$sms_body = 'Вы получили '.$amount.' тг от ID '.$row['user_id'].' из '.$i.'-ой линий на сайте cashber . kz';
				$ph = '7'.substr($row_x['phone'],1,strlen($row_x['phone'])-1);
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");
			}
			$sponsor = $row_x['sponsor'];
		}
		$i++;
	}
	mysql_query("update users set akwa=akwa+400 where id=0");
	$message = '<div class="alert alert-success" role="alert">
                                        Вы успешно активированы.
                                    </div>';
}

if ($flag2) {
	$i = 1;
	$sponsor = $row['sponsor'];
	while ($sponsor>0 && $i <= 7) {
		$result_x = mysql_query("select sponsor, phone from users where user_id=".$sponsor);
		if (mysql_num_rows($result_x)>0) {
			$row_x = mysql_fetch_array($result_x);			
			if ($i == 1) {
				$amount = 5000;
				mysql_query("update users set akwa2=akwa2+".$amount." where user_id=".$sponsor);
				mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$sponsor.", ".$amount.", ".$i.", ".$row["user_id"].", 2, 2, '".date('Y-m-d H:i:s')."') ");
			}
			else {
				$amount = 500;
				mysql_query("update users set akwa2=akwa2+".$amount." where user_id=".$sponsor);
				mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$sponsor.", ".$amount.", ".$i.", ".$row["user_id"].", 2, 2, '".date('Y-m-d H:i:s')."') ");
				mysql_query("update users set bonus=bonus+".$amount." where user_id=".$sponsor);
				mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$sponsor.", ".$amount.", ".$i.", ".$row["user_id"].", 1, 2, '".date('Y-m-d H:i:s')."') ");
			}
			echo mysql_error();
			if ($sponsor>7) {
				$sms_body = 'Вы получили '.$amount.' тг от ID '.$row['user_id'].' из '.$i.'-ой линий на сайте cashber . kz';
				$ph = '7'.substr($row_x['phone'],1,strlen($row_x['phone'])-1);
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");
			}
			$sponsor = $row_x['sponsor'];
		}
		$i++;
	}
	mysql_query("update users set akwa2=akwa2+4000 where id=0");
	$message2 = '<div class="alert alert-success" role="alert">
                                        Вы успешно активированы.
                                    </div>';
}

include("header.php");

$i = 1;
$sponsor = $row['user_id'];
while ($sponsor>0 && $i <= 8) {
	$result_x = mysql_query("select user_id, insta, sponsor from users where user_id=".$sponsor);
	if (mysql_num_rows($result_x)>0) {
		$row_x = mysql_fetch_array($result_x);
		$sponsor = $row_x['sponsor'];
		$data[] = $row_x;
	}
	$i++;
}
//print_r($data);
?>
<style>
	#my_avatar1 {
		border-image: url("images/insta_border_sm.png");
		border-image-slice:27 27 27 27;
		border-image-width:27px 27px 27px 27px;
		border-image-outset:0px 0px 0px 0px;
		border-image-repeat:stretch stretch;
	}
	.media a {
		color: #fff;
	}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
/*	var token = '6790446600.eae7038.5846f7c711204511aa133139a5fcea61',
    userid = 1362124742, // rudrastyh - my username :)
    num_photos = 4;
$.noConflict();
  jQuery( document ).ready(function( $ ) {
	$.ajax({ // the first ajax request returns the ID of user rudrastyh
		url: 'https://api.instagram.com/v1/users/self/media/recent',
		dataType: 'jsonp',
		type: 'GET',
		data: {access_token: token, q: userid}, // actually it is just the search by username
		success: function(data){
				console.log(data);
				$("#my_avatar").prop("src",data.data[0].id);
			},
		error: function(data){
			console.log(data);
		}
	});
  });*/
$.noConflict();
jQuery( document ).ready(function( $ ) {
	$(".subscr").on('click',function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: "POST",
			url: "subscribe.php",
			data: { 
				user_id: <?= $row['user_id'] ?>, // < note use of 'this' here
				subs_id: id
			},
			success: function(result) {
				//alert('ok');
				$('#'+id+'btn').attr("disabled","true");
				$('#'+id+'btn').html("Подписки&nbsp<i class='fa fa-check-circle'></i>");
			},
			error: function(result) {
				alert( id+'error');
			}
		});
		window.location.href = $(this).data('target');
	});	  
});

function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.disabled = false;
  copyText.select();

  /* Copy the text inside the text field */
  document.execCommand("copy");
  copyText.disabled = true;

}
</script>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Обзор</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Обзор</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
		<div class="animated fadeIn">
                <div class="row">
		<div class="col-lg-4 col-md-6">
        <aside class="profile-nav alt">
            <section class="card">
                <div class="card-header user-header alt bg-success">
                    <div class="media">
                        <a href="#">
							<?php
							if (is_file('images/avatar/'.$row['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row['user_id'].'.jpg'; else $avatar='images/user.png';
							?>
                           <img class="align-self-center rounded-circle" id="my_avatar" style="width:85px; height:85px;" alt="" src="<?= $avatar?>">
                        </a>
                        <div class="media-body">
							<h6 class="text-light display-6">Добро пожаловать!</h6>
                            <h3 class="text-light display-6"><?= $row['fio'] ?></h3>
                        </div>
                    </div>
                </div>


                <ul class="list-group list-group-flush">
					<li class="list-group-item">
                        <i class="fa fa-id-card"></i> Ваш ID <span class="badge badge-warning pull-right"><?= $row['user_id'] ?></span>
                    </li>
                    <li class="list-group-item">
                        <i class="fa fa-phone"></i> Телефон <span class="badge badge-primary pull-right"><?= $row['phone'] ?></span>
                    </li>
					<li class="list-group-item">
                        <i class="fa fa-instagram"></i><span class="badge badge-danger pull-right">https://instagram.com/<?= $row['insta'] ?></span>
                    </li>
					<li class="list-group-item">
                        <i class="fa fa-arrow-circle-up"></i> ID спонсора <span class="badge badge-info pull-right"><?= $row['sponsor'] ?></span>
                    </li>
					<li class="list-group-item">
                        <i class="fa fa-link"></i> Ссылка для приглашения <input type="text" class="form-control pull-left" id="myInput" disabled value="https://cashber.kz/admin/register.php?rel=<?= base64_encode($row['user_id']) ?>" />
						<button type="button" class="btn btn-secondary" onclick="myFunction()">Скопировать реферальную ссылку</button>
						<script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="https://yastatic.net/share2/share.js"></script>
<div class="ya-share2 mt-1" data-url="https://cashber.kz/admin/register.php?rel=<?= base64_encode($row['user_id']) ?>" data-services="vkontakte,facebook,whatsapp,telegram"></div>
                    </li>
                    <li class="list-group-item">
                        <i class="fa fa-paper-plane"></i> Город <span class="badge badge-success pull-right"><?= $row['city'] ?></span>
                    </li>
                    <li class="list-group-item">
                        <a href="profile.php"> <i class="fa fa-cog"></i> Изменить данные</a>
                    </li>
                </ul>

            </section>
        </aside>
    </div>


            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-money text-success border-success"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Текущий баланс А</div>
                                <div class="stat-digit"><?= $row['akwa'] ?> тг</div>
							</div>
							<div class="mt-3">
								<form action="index.php" method="post">
								<?php
									if ($row['status']!=0) {
										echo '<input type="submit" name="sub_dis" disabled class="btn btn-success" value="Активный">';
										$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']);
										if (mysql_num_rows($res)>=17) echo '<a href="withdraw.php" class="btn btn-warning">Вывод средств</a>';
										else echo '<a onclick="alert(\'Подпишитесь ко всем своим спонсорам на Инстаграм и только после этого можете сделать вывод.\')" class="btn btn-warning">Вывод средств</a>';
										echo '<br><br><img src="images/skidka3.jpg">';
									}
									else {
										echo '<input type="submit" name="sub_btn" class="btn btn-danger" value="Активировать 1500 тг">'.$message;
										echo '<div class="mt-2" style="text-align: center">Реквизиты для активации:<br>
										<span class="text-danger">Kaspi Gold<br>
										5169 4931 8831 9753<br>
										ИИН 850613402292<br>
										+77783038988</span><br>
										В комментарий укажите Ваш номер ID</div>';
									}
								?>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-money text-success border-success"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Текущий баланс Б</div>
                                <div class="stat-digit"><?= $row['akwa2'] ?> тг</div>
							</div>
							<div class="mt-3">
								<form action="index2.php" method="post">
								<?php
									if ($row['status2']!=0) {
										echo '<input type="submit" name="sub_dis" disabled class="btn btn-success" value="Активный">';
										$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']);
										if (mysql_num_rows($res)>=17) echo '<a href="withdraw.php" class="btn btn-warning">Вывод средств</a>';
										else echo '<a onclick="alert(\'Подпишитесь ко всем своим спонсорам на Инстаграм и только после этого можете сделать вывод.\')" class="btn btn-warning">Вывод средств</a>';
										echo '<br><br><img src="images/skidka10.jpg">';
									}
									else {
										echo '<input type="submit" name="sub_btn2" class="btn btn-danger" value="Активировать 15000 тг">'.$message2;
										echo '<div class="mt-2" style="text-align: center">Реквизиты для активации:<br>
										<span class="text-danger">Kaspi Gold<br>
										5169 4931 8831 9753<br>
										ИИН 850613402292<br>
										+77783038988</span><br>
										В комментарий укажите Ваш номер ID<br>
										<span class="text-success">Активируйся и получи возможность продвигать рекламу (баннер) на 7 месяцев бесплатно.</span></div>';
									}
								?>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-gift text-danger border-danger"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Бонус</div>
                                <div class="stat-digit"><?= $row['bonus'] ?> тг</div>
							</div>
                        </div>
                    </div>
                </div>
				<div class="card" style="background: #ebba34; text-align: center">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <a href="banners.php" class="btn pr-2 mb-2" style="background: #333; color: white;">Все баннеры</a>
							<a href="vendors.php" class="btn" style="background: #333; color: white;">Все магазины</a>
                        </div>
                    </div>
                </div>
            </div>
					
			<div class="col-md-4" style="text-align: center">
                        <aside class="profile-nav alt">
                            <section class="card">
                                <div class="card-header user-header alt bg-success">
                                    <div class="media">
                                        <a href="https://instagram.com/gold_way_atyrau" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000001.jpg"><br>А&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000001");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000001btn" id="100000001btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/gold_way_atyrau" data-id="100000001">Подписаться</button>
											<? } ?>
										</a>
                                        <a href="https://instagram.com/svoi_dom_atyrau777" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000002.jpg"><br>Б&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000002");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000002btn" id="100000002btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/svoi_dom_atyrau777" data-id="100000002">Подписаться</button>
											<? } ?>
                                        </a>
										<a href="https://instagram.com/global_trend_company_atyrau" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000003.jpg"><br>В&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000003");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000003btn" id="100000003btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/global_trend_company_atyrau" data-id="100000003">Подписаться</button>
											<? } ?>
                                        </a>
                                    </div>
                                </div>

                            </section>
							<section class="card">
                                <div class="card-header user-header alt bg-success">
                                    <div class="media">                                        
										<a href="https://instagram.com/citysmartclub777" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000004.jpg"><br>Г&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000004");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000004btn" id="100000004btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/citysmartclub777" data-id="100000004">Подписаться</button>
											<? } ?>
                                        </a>
										<a href="https://instagram.com/kupee.kz" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000005.jpg"><br>Д&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000005");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000005btn" id="100000005btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/kupee.kz" data-id="100000005">Подписаться</button>
											<? } ?>
                                        </a>
										<a href="https://instagram.com/korkem_mg_kz7" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000006.jpg"><br>Е&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000006");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000006btn" id="100000006btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/korkem_mg_kz7" data-id="100000006">Подписаться</button>
											<? } ?>
                                        </a>
                                    </div>
                                </div>

                            </section>
							<section class="card">
                                <div class="card-header user-header alt bg-success">
                                    <div class="media">
										<a href="https://instagram.com/total_life_kz" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000007.jpg"><br>Ж&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000007");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000007btn" id="100000007btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/total_life_kz" data-id="100000007">Подписаться</button>
											<? } ?>
                                        </a>
										<a href="https://instagram.com/aiym.8506" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000008.jpg"><br>З&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000008");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000008btn" id="100000008btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/aiym.8506" data-id="100000008">Подписаться</button>
											<? } ?>
                                        </a>
										<a href="https://aviavia.kz" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/avatar/100000009.jpg"><br>И&nbsp;&nbsp;&nbsp;<br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=100000009");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="100000009btn" id="100000009btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/aviaviakz" data-id="100000009">Подписаться</button>
											<? } ?>
                                        </a>
                                    </div>
                                </div>
								

                            </section>
							<section class="card">
                                <div class="card-header user-header alt bg-success">
                                    <div class="media">
										<a href="https://www.youtube.com/channel/UCMSp55xVBHdLxzIhl7y30tw?view_as=subscriber" style="text-align:center">
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="images/image.png"><br><br>
											<?php
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=0");
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="0btn" id="0btn" class="btn btn-primary btn-sm subscr" data-target="https://www.youtube.com/channel/UCMSp55xVBHdLxzIhl7y30tw?view_as=subscriber" data-id="0">Подписаться</button>
											<? } ?>
                                        </a>
										<div class="media-body">
											<h4 class="text-light display-6">Подпишитесь на наш Youtube канал. Оставайтесь в курсе новостей, акции и смотрите видеоуроки</h4>
										</div>
                                    </div>
                                </div>
                            </section>
                        </aside>
                    </div>
			
			<div class="col-md-3">
                    <aside class="profile-nav alt">
                            <section class="card">
                                <div class="card-header user-header alt bg-success">
									<?php
									for ($i=count($data)-1;$i>=0;$i--) {
									?>
                                    <div class="media">
										<a href="https://instagram.com/<?= $data[$i]['insta'] ?>">
											<?php
											if (is_file('images/avatar/'.$data[$i]['user_id'].'.jpg')) $avatar = 'images/avatar/'.$data[$i]['user_id'].'.jpg'; else $avatar='images/user.png';
											?>
                                            <img class="align-self-center rounded-circle mr-3" id="my_avatar1" style="width:85px; height:85px;" alt="" src="<?= $avatar ?>"><br>													</a>
										<div class="media-body">
											<h6 class="text-light display-6"><?= $data[$i]["user_id"]?></h6>											
										</div>
										<div class="media-body">
											<?php
											if ($i>0) {
											$res = mysql_query("select id from subscriptions where user_id=".$row['user_id']." and subs_id=".$data[$i]['user_id']);
											if (mysql_num_rows($res)>0) echo '<button type="submit" class="btn btn-primary btn-sm" disabled>Подписки&nbsp<i class="fa fa-check-circle"></i></button>';														else {											
											?>
											<button type="submit" name="<?= $data[$i]["user_id"]?>btn" id="<?= $data[$i]["user_id"]?>btn" class="btn btn-primary btn-sm subscr" data-target="https://instagram.com/<?= $data[$i]["insta"] ?>" data-id="<?= $data[$i]["user_id"]?>">Подписаться</button>
											<? } } ?>
										</div>
                                    </div>
									<?php } ?>
                                </div>

                            </section>
                        </aside>
            </div>
					
			<div class="col-md-3">
                    <aside class="profile-nav alt">
                            <section class="card">
                                <div class="card-header user-header alt bg-primary text-center">
									<h4 class="text-light pb-3">Последние регистрации</h4>
									<?php
									$result = mysql_query("select user_id, phone from users order by id desc limit 0,5");
									while ($row = mysql_fetch_array($result)) {
									?>
										<div class="media-body">
											<span class="badge badge-warning"><?= $row["user_id"]?></span>											
											<span class="badge badge-danger"><?= $row["phone"]?></span>
										</div>
									<?php } ?>
                                </div>

                            </section>
                        </aside>
            </div>
		</div>
		</div>

        </div> <!-- .content -->
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!--  Chart js -->
    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
	<script src="assets/js/widgets.js"></script>

</body>

</html>
