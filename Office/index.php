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
							<div class="user-img-profile">
								<img src="<?=$avatar?>" alt="Person">
							</div>
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
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar text-white"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
									Дата активации
								</h6>
								<span class="text-white"><?=$show_row['reg_time']?></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone text-white"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
									Номер телефона
								</h6>
								<span class="text-white"><?=$show_row['phone']?></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users text-white"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
									Ваш лидер
								</h6>
								<span class="text-white"><?=$show_row['sponsor']?></span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map text-white"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
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
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card text-white"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
									Текущий баланс
								</h6>
								<span class="text-white"><?= $show_row['akwa'] ?> Тг.</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="col-lg-4">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card text-white"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
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
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield text-white"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
									Режим инкогнито
								</h6>
								<form method="post" class="col-lg-8">
									<div class="input-group col-12">
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
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link text-white"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
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
