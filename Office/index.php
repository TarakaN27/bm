<?php
ini_set('session.cookie_domain', '.bm-market.kz' );
session_start();
include('db_connect.php');
include "smsc_api.php";
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");
$flag = false; $message = "";
date_default_timezone_set('Asia/Almaty');

$check_shareholder_query = mysql_query("SELECT `date` FROM `history` WHERE `type`='cash-shareholder' ORDER BY id DESC LIMIT 1");
$check_shareholder = mysql_fetch_assoc($check_shareholder_query);
$last_cash_shareholder = date("Y-m-d H:i:s", strtotime($check_shareholder["date"]));
$date_ot = date('Y-m-d H:i:s',strtotime(date('Y-m-01 00:00:00',strtotime($last_cash_shareholder)).'+1 month'));
$date_end = date('Y-m-d H:i:s');

$date_start_check = date('Y-m-t 23:59:59',strtotime(date('Y-m-01 00:00:00',strtotime($last_cash_shareholder)).'+1 month'));
if(strtotime($date_start_check) < strtotime($date_end)) {
	$shareholders = mysql_query("SELECT * FROM `users` WHERE `infinity_package`=4");
	$ids = [];
	$shareholders_arr = [];
	while($row = mysql_fetch_array($shareholders)){
		$ids[] = $row["id"];
		$shareholders_arr[$row["id"]] = $row;
	}
	$ids_str = implode(", ", $ids);
	foreach($shareholders_arr as $row){
		$date_start = date('Y-m-d H:i:s',strtotime($row["reg_infinity"])+1);
		$date_end = date('Y-m-t 23:59:59',strtotime($date_ot));
		if(strtotime($date_start)<strtotime($date_ot)) {$date_start = $date_ot;}
		$history_pv_query = mysql_query("SELECT * FROM `history` WHERE `type`='add-inf-pv' AND `user_id` IN (".$ids_str.") AND `date` between '".$date_start."' and '".$date_end."' GROUP BY date ORDER BY id DESC");
		$history_pv = 0;
		while($rowz = mysql_fetch_array($history_pv_query)){
			$count_query = mysql_query("SELECT * FROM `users` WHERE `infinity_package`=4 AND `reg_infinity`<='".date('Y-m-d H:i:s',strtotime($rowz["date"])-1)."'");
			$history_pv += $rowz["temp_val"]/mysql_num_rows($count_query);
		}
		$history_pv = bcdiv($history_pv, 1, 2);
		$count_one = floor($history_pv*8000);
		$sql = "UPDATE users SET akwa=akwa+".$count_one." WHERE id=".$row["id"];
		mysql_query($sql);
		mysql_query("INSERT INTO `history` (`text`,`msg`,`user_id`,`from`,`date`,`type`) VALUES ('".$sql."','Выдача суммы акционеру','".$row["id"]."','0','".date("Y-m-d H:i:s", strtotime($date_end))."','cash-shareholder')");
	}
}

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	$fio = $row["fio"];
	$show_row = [];
	if($row["hide_data"] == 9990 && $_SESSION["login"] != "BoomMarket") {
		foreach($row as $key=>$str){
			$show_row[$key] = $key == "login" ? $str: "******";
		}
	} else {
		$show_row = $row;
	}
	
	
	$hide_data = ["",""];
	$hide_data[$row["hide_data"]] = "selected";
	
	if (isset($_POST['sub_btn'])) {
		if ($row['akwa'] >= 1500) {
			mysql_query("update users set akwa=akwa-1500, status=0 where phone='".$_SESSION['phone']."'");
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
	
	if (isset($_POST['sub_tovar'])) {
		mysql_query("update users set tovar=1 where login='".$row['login']."'");
		$row['tovar'] = 1;
	}
	
}
else {
	header("Location: ../index.php");
	die();
}

include("header.php");


if(isset($_POST["action"]) && $_POST["action"] == "show" && in_array($_POST["show"], ["0","1"])) {
	$save = mysql_query("UPDATE users SET hide_data='".$_POST["show"]."' WHERE login='".$_SESSION["login"]."'");
	refresh();
}

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

    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
$.noConflict();
jQuery( document ).ready(function( $ ) {
	$('.pop').on('click', function() {
			//$('.imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal({show: true, focus: true});   
		});	
	$(".subscr").on('click',function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: "POST",
			url: "subscribe.php",
			data: { 
				id: <?= $row['id'] ?>, // < note use of 'this' here
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
                        <h1>Профиль</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active"></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
		<div class="animated fadeIn">
                <div class="row">
		<div class="col-lg-6 col-md-12">
        <aside class="profile-nav alt">
            <section class="card">
                <div class="card-header user-header alt bg-fffff">
                    <div class="media">
                        
                        <div class="media-body">
							<h6 class="text-black display-6">Добро пожаловать!</h6>
                            <h3 class="text-black display-6"><?= $show_row['fio'] ?></h3>
                        </div>
						
						<div class="media-body">
							<h6 class="text-black display-6">Режим инкогнито</h6>
							<form method="post">
								<div class="input-group">
									<select class="form-select form-control" name="show">
										<option <?=$hide_data[0]?> value="0">Включить</option>
										<option <?=$hide_data[1]?> value="1">Отключить</option>
									</select>
									<button type="submit" class="btn btn-primary" name="action" value="show">Сохранить</button>
								</div>
							</form>
                        </div>
						
                    </div>
                </div>


                
					
					
					<li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-calendar"style="font-size:18px;color:white"></i> <font color="White"> Дата активации </font> <span class="badge badge-secondary pull-right"><?= $show_row['reg_time'] ?></span>
                    </li>
                   <li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-phone"style="font-size:18px;color:white"></i> <font color="White"> Номер телефона </font><span class="badge badge-secondary pull-right"><?= $show_row['phone'] ?></span>
                    </li>
					<li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-star"style="font-size:18px;color:white"></i> <font color="White">Ваш Лидер </font><span class="badge badge-secondary pull-right"><?= $show_row['sponsor'] ?></span>
                    </li>
					<li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-link"style="font-size:18px;color:white"></i> <font color="White"> Реферальнная ссылка </font> <input type="text" class="form-control pull-left" id="myInput" disabled value="http://bm-market.kz/Office/register.php?rel=<?= base64_encode($row['id']) ?>" />
						<button type="button" class="btn btn-secondary" onclick="myFunction()"> Скопировать реферальную ссылку </button>
						<br/>
						
						<script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="https://yastatic.net/share2/share.js"></script>

                    </li>
                    <li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-location-arrow"style="font-size:18px;color:white"></i> <font color="White"> Местоположение </font> <span class="badge badge-secondary pull-right"><?= $show_row['city'] ?></span>
                    </li>
                    <li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <a href="profile.php" style="color: black"> <i class="fa fa-cog"style="font-size:18px;color:white"></i> <font color="White"> Изменить профиль </font></a>
						
                    </li>
                </ul>

            </section>
        </aside>
    </div>
<?php
	$res_d = mysql_query("select * from m1 where sponsor_login='".$_SESSION['login']."'");
	if ($row['login'] == 'drakula') {
?>
			<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
				<div class="card" style="background: #55c912; color: white">
                    <div class="card-body">
						<h3>Компания Boom Market</h3><br>
		<p style="color: white">УРА! УРА! УРА! УРА! УРА!</p>
		<p style="color: white">ПРОМОУШЕН НА ЦИСТАНХЕ</p>
		<p style="color: white">УВАЖАЕМЫЕ ПАРТНЕРЫ 🔥🔥🔥🔥🔥🔥🔥🔥🔥 СУПЕР ПРОМОУШЕН ДЛЯ НОВИЧКОВ И ДЛЯ ТЕХ ЛЮДЕЙ У КОГО НЕТУ НИ ОДНОГО ПАРТНЕРА</p>				
		<p style="color: white">Кто закроет 1 ЭТАП( В ПОДАРОК  ПОЛУЧИТЕ ПРОДУКЦИЮ ЦИСТАНХЕ СРОК ПРОМОУШЕНА С 19.07.2020  14:00 часов ПО 20.07.2020 до 00:00 ВКЛЮЧИТЕЛЬНО</p>
		<p style="color: white">С уважением администрация Boom Market</p>
		<p style="color: white">( ВСЕГО 1 ДЕНЬ! ПРОДЛЕВАНИЕ НЕ БУДЕТ! ПРЕДУПРЕЖДАЕМ!)</p>
					</div>
				</div>
			</div>
<?php } ?>

			<div class="col-lg-6 col-md-12">
				<div class="card" style="background: #fffff; text-align: center">
					<div class="card-body">
						<div class="stat-widget-one mb-2">
							<div><i class=" text-success border-success"></i></div>
							<div class="stat-content dib">
								<div class="stat-text text-black"> </div>
								<button type="button" class="btn btn-success" onclick=""> Пополнить баланс </button>
								<div class="stat-digit text-black">Текущий баланс <?= $show_row['akwa'] ?></div>
								<div class="stat-digit text-black">Бонусный баланс <?= $show_row['balans_turbo'] ?></div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			
		</div>

<? include("footer.php"); ?>
