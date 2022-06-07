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


<div class="container">
	<div class="main-body">
		<div class="row">
			<div class="col-lg-4">
				<div class="card">
					<div class="card-body">
						<div class="d-flex flex-column align-items-center text-center">
							<img src="https://via.placeholder.com/110x110" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
							<div class="mt-3">
								<h4><?= $show_row['fio'] ?></h4>
								<p class="mb-1"></p>
								<p class="font-size-sm"></p>
								<a href="profile.php" class="btn btn-light">Изменить профиль</a>
							</div>
						</div>
						<hr class="my-4">
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
									Дата активации
								</h6>
								<span class="text-white"><?=$show_row['reg_time']?></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github me-2 icon-inline"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
									Номер телефона
								</h6>
								<span class="text-white"><?=$show_row['phone']?></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter me-2 icon-inline"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
									Ваш лидер
								</h6>
								<span class="text-white"><?=$show_row['sponsor']?></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
									Местоположение
								</h6>
								<span class="text-white"><?=$show_row['city']?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center flex-wrap">
						<h6>Баланс</h6>
						<button type="button" class="btn btn-light" onclick="">Пополнить</button>
					</div>
					<div class="card-body">
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="col-lg-4">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
									Текущий баланс
								</h6>
								<span class="text-white"><?= $show_row['akwa'] ?> Тг.</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="col-lg-4">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
									Бонусный баланс
								</h6>
								<span class="text-white"><?= $show_row['balans_turbo'] ?> Тг.</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h6>Операции</h6>
					</div>
					<div class="card-body">
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="col-lg-4">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
									Режим инкогнито
								</h6>
								<form method="post" class="col-lg-8 w-100">
									<div class="input-group">
										<select class="form-select form-control" name="show">
											<option <?=$hide_data[0]?> value="0">Включить</option>
											<option <?=$hide_data[1]?> value="1">Отключить</option>
										</select>
										<button type="submit" class="btn btn-light" name="action" value="show">Сохранить</button>
									</div>
								</form>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="col-lg-4">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
									Реферальнная ссылка
								</h6>
								<div class="block col-lg-8 d-flex">
									<input type="text" class="form-control pull-left" id="myInput" disabled value="http://bm-market.kz/Office/register.php?rel=<?= base64_encode($row['id']) ?>" />
									<button type="button" class="btn btn-light" onclick="myFunction()">Скопировать</button>
								</div>
							</li>
							
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<? include("footer.php"); ?>
