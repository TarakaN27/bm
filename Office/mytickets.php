<?php
session_start();

include("db_data.php");
include("db_connect.php");
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_database);
$mysqli->set_charset("utf8");

if (mysqli_connect_errno()) {
    printf("Ошибка подключения: %s\n", mysqli_connect_error());
    exit();
}

function find( $sql ) {
	global $mysqli;
	if ($result = $mysqli->query($sql)) {
		$rows = array();		
		while($row = $result->fetch_assoc())
			$rows[] = $row;					
		return $rows;
	} else {
		return $mysqli->errno . ". " . $mysqli->error;
		exit;
	}
	$result->free();
}


function findOne($sql) {
	$tempArr = find( $sql." LIMIT 1" );
	if(isset($tempArr[0])) {
		return $tempArr[0];
	} else {
		return false;
	}
}


function save($sql, $id=false, $msg=false, $type=false, $temp_val=NULL) {
	global $mysqli;
	$r = false;
	$result = $mysqli->query($sql);
	if ($result) {
		$r = $mysqli->insert_id;
		if($id){
			$sql = htmlspecialchars(str_replace(array('(',')', '`', "'"),array('[',']', '', ''),$sql));
			$mysqli->query("INSERT INTO `sklad_history` (`from_id`, `to_id`, `text`, `msg`, `date`, `type`, `temp_val`) VALUES ('".$_SESSION["id"]."', '".$id."', '".$sql."', '".$msg."', '".date("Y-m-d H:i:s")."', '".$type."', '".$temp_val."')");
		}
	} else {
		$r = $mysqli->error;
	}
	
	return $r;
}

$gifts = find("SELECT * FROM `gifts`");

$coupons = find("SELECT * FROM `coupons`");

$result = findOne("select * from users where login='".$_SESSION['login']."'");

if (isset($result["id"])) {
	$my_fio = findOne("select * from users where login='".$_SESSION['login']."'");
	$fio = $result["fio"];
	date_default_timezone_set('Asia/Aqtau');
	
	$tickets = find("SELECT buy_tickets.*, coupons.name, coupons.status FROM buy_tickets LEFT JOIN coupons ON coupons.id=buy_tickets.coupon_id WHERE user_id='".$_SESSION["id"]."' ORDER BY id DESC");

	if(isset($_GET["action"]) && $_GET["action"]=="got-gift" && isset($_GET["id"])){
		save("UPDATE buy_tickets SET got='1' WHERE id='".$_GET["id"]."' AND user_id='".$_SESSION["id"]."'");
		header("Location: /Office/mytickets.php");
	}

	if(isset($_GET["action"]) && $_GET["action"]=="open-all"){
		foreach($tickets as $ticket){
			$coupon_id = $tickets[array_search($ticket["id"], array_column($tickets,"id"))]["coupon_id"];
			$coupon_status = $coupons[array_search($coupon_id, array_column($coupons, "id"))]["status"];
			if($coupon_status == 0) {
				save("UPDATE buy_tickets SET open='1' WHERE id='".$ticket["id"]."' AND user_id='".$_SESSION["id"]."'");
			}
		}
		header("Location: /Office/mytickets.php");
	}

	if(isset($_GET["action"]) && $_GET["action"]=="open-ticket" && isset($_GET["id"])){
		$coupon_id = $tickets[array_search($_GET["id"], array_column($tickets,"id"))]["coupon_id"];
		$coupon_status = $coupons[array_search($coupon_id, array_column($coupons, "id"))]["status"];
		if($coupon_status == 0) {
			save("UPDATE buy_tickets SET open='1' WHERE id='".$_GET["id"]."' AND user_id='".$_SESSION["id"]."'");
		}
		header("Location: /Office/mytickets.php");
	}
	
}
else {
	header("Location: index.php");
	die();
}

include('header.php');

?>
	<div class="breadcrumbs">
		<div class="col-sm-4">
			<div class="page-header float-left">
				<div class="page-title">
					<h1>Билеты</h1>
				</div>
			</div>
		</div>
	</div>

<div class="content mt-3">
	<div class="animated fadeIn">
		<div class="card mb-4">
				<div class="card-header pb-0">
					<div class="row">
						<div class="col-12 d-flex justify-content-between">
							<h6><?=$current_page["name"]?></h6>
							<a href="?action=open-all" class="btn btn-light mb-2 px-3 py-1 align-self-center">Открыть все сразу</a>
						</div>
					</div>
				</div>
				<div class="card-body px-0 pb-2">
					<div class="table-responsive">
						<table class="table align-items-center mb-0">
							<thead>
								<tr>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">ID билета</th>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">Название розыгрыша</th>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">Дата покупки</th>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">Действие</th>
								</tr>
							</thead>
							<tbody>
								<? $count = 0; ?>
								<? foreach($tickets as $row): ?>
								<? if($row["status"]==0 && $row["open"]==0):?>
									<? $count++; ?>
									<tr>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["id"]?></span></td>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["name"]?></span></td>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["date"]?></span></td>
										<td class="text-center">
											<span class="px-3 py-1 text-xs font-weight-bold login">
												<a href="?action=open-ticket&id=<?=$row['id']?>" class="btn btn-success mb-0 px-3 py-1">Открыть</a>
											</span>
										</td>
									</tr>
								<? elseif($row["status"]==1 && $row["open"]==0): ?>
									<? $count++; ?>
									<tr>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["id"]?></span></td>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["name"]?></span></td>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["date"]?></span></td>
										<td class="text-center">
											<span class="px-3 py-1 text-xs font-weight-bold login">
												<span class="btn btn-primary mb-0 px-3 py-1">Открыть</span>
											</span>
										</td>
									</tr>
								<? endif; ?>
								<? endforeach; ?>
								<? if($count==0): ?>
									<tr>
										<td class="text-center" colspan="4"><span class="px-3 py-1 text-xs font-weight-bold login">Нет доступных билетов</span></td>
									</tr>
								<? endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<div class="card">
				<div class="card-header pb-0">
					<div class="row">
						<div class="col-12 d-flex justify-content-between">
							<h6>Открытые билеты</h6>
						</div>
					</div>
				</div>
				<div class="card-body px-0 pb-2">
					<div class="table-responsive">
						<table class="table align-items-center mb-0">
							<thead>
								<tr>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">ID билета</th>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">Название розыгрыша</th>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">Дата покупки</th>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">Приз</th>
									<th class="text-center text-uppercase text-light text-xxs font-weight-bolder">Действие</th>
								</tr>
							</thead>
							<tbody>
								<? $count = 0; ?>
								<? foreach($tickets as $row): ?>
								<? if($row["open"]==1):?>
									<? $count++; ?>
									<?
									$got = [$row["gifts"]=>1];
									if(!empty($row["bonus-products"])){
										$bonus = json_decode($row["bonus-products"], true);
										if(count($bonus)>0) {
											foreach($bonus as $id=>$count){
												if(isset($got[$id])) {
													$got[$id] = $got[$id] + $count;
												} else {
													$got[$id] = $count;
												}
											}
										}
									}
									?>
									<tr>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["id"]?></span></td>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["name"]?></span></td>
										<td class="text-center"><span class="px-3 py-1 text-xs font-weight-bold login"><?=$row["date"]?></span></td>
										<td class="text-center">
											<?
												foreach($got as $id=>$count) {									
													echo '<span class="text-xs font-weight-bold d-flex flex-column">'.$gifts[array_search($id, array_column($gifts, "id"))]["name"].' - '.$count.' шт.</span>';
												}
											?>
										</td>
										<td class="text-center">
											<span class="px-3 py-1 text-xs font-weight-bold login">
												<? if($row["got"]==0 && $row["storekeeper_sent"]==0): ?>
													<span class="btn btn-secondary mb-0 px-3 py-1">В обработке</span>
												<? elseif($row["got"]==0 && $row["storekeeper_sent"]==1): ?>
													<a href="?action=got-gift&id=<?=$row['id']?>" class="btn btn-success mb-0 px-3 py-1">Получил</a>
												<? else: ?>
													<span class="btn btn-primary mb-0 px-3 py-1">Получено</span>
												<? endif; ?>
											</span>
										</td>
									</tr>
								<? endif; ?>
								<? endforeach; ?>
								<? if($count==0): ?>
									<tr>
										<td class="text-center" colspan="5"><span class="px-3 py-1 text-xs font-weight-bold login">Нет доступных призов</span></td>
									</tr>
								<? endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
	</div><!-- .animated -->
</div><!-- .content -->

<? include("footer.php"); ?>