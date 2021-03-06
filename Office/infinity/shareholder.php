<?php
session_start();
include("../db_connect.php");
include("functions.php");
include("inf-functions.php");
date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");
$my = findOne("select * from users where login='".$_SESSION['login']."'");
$fio = $my["fio"];
$shareholders = find("SELECT * FROM `users` WHERE `infinity_package`=4");
$count_chareholders = count($shareholders);
if(isset($my["login"]) && $my["infinity_package"]==4){
	$ids = [];
	foreach($shareholders as $row) {
		$ids[] = $row["id"];
	}
	$ids_str = implode(", ", $ids);
	
	$date_start = date('Y-m-d H:i:s',strtotime($my["reg_infinity"])+1);
	
	if(strtotime($date_start)<strtotime(date("Y-m-01 00:00:00"))) {$date_start = date("Y-m-01 00:00:00");}
	
	$date_end = date('Y-m-d H:i:s',strtotime(date('Y-m-01 23:59:59',strtotime('next month')).'-1 day'));
	$history_pv = find("SELECT history.*, u.login, f.login as user_from FROM `history` LEFT JOIN `users` as u ON history.user_id=u.id LEFT JOIN `users` as f ON f.id=history.from WHERE `type`='add-inf-pv' AND `user_id` IN (".$ids_str.") AND `date` between '".$date_start."' and '".$date_end."' GROUP BY history.`date` ORDER BY `id` DESC");

	$count_pv = 0;
	$count_all = 0;
	$arr_shareholder = [];
	foreach($history_pv as $id=>$row){
		$query = find("SELECT id, login FROM `users` WHERE `infinity_package`=4 AND `reg_infinity`<='".date('Y-m-d H:i:s',strtotime($row["date"])-1)."'");
		$count_pv += $row["temp_val"]/count($query);
		$history_pv[$id]["temp_val"] = $row["temp_val"]/count($query);
		
		foreach($query as $value){
			 $history_pv[$id]["shareholders"][] = $value;
		}
		$count_all += $row["temp_val"];
	}
	
	$count_pv = bcdiv($count_pv, 1, 2);	
	$count_one = floor($count_pv*8000);
	
	
	$date_start_all = $_GET["date-start"] ? date('Y-m-d H:i:s',strtotime($_GET["date-start"])) : date('Y-m-d H:i:s',strtotime($my["reg_infinity"])+1);
	$date_end_all = $_GET["date-end"] ? date('Y-m-d H:i:s',strtotime(date("Y-m-d", strtotime($_GET["date-end"])).'+1 day')) : date('Y-m-d H:i:s',strtotime(date('Y-m-01 23:59:59',strtotime('next month')).'-1 day'));
	$history_pv_all = find("SELECT history.*, u.login, f.login as user_from FROM `history` LEFT JOIN `users` as u ON history.user_id=u.id LEFT JOIN `users` as f ON f.id=history.from WHERE `type`='add-inf-pv' AND `user_id` IN (".$ids_str.") AND `date` between '".$date_start_all."' and '".$date_end_all."' GROUP BY history.date ORDER BY id DESC");
	
	$count_pv_all = 0;
	$arr_shareholder = [];
	foreach($history_pv_all as $id=>$row){
		$query = find("SELECT id, login FROM `users` WHERE `infinity_package`=4 AND `reg_infinity`<='".date('Y-m-d H:i:s',strtotime($row["date"])-1)."'");
		$history_pv_all[$id]["temp_val"] = $row["temp_val"]/count($query);
		
		foreach($query as $value){
			 $history_pv_all[$id]["shareholders"][] = $value;
		}
		$count_pv_all += $row["temp_val"];
	}
	
	$count_pv_all = bcdiv($count_pv_all, 1, 2);
	$count_one_all = floor($count_pv_all*8000);	
}
include("../header.php");
?>
	<div class="content mt-5">
			<div class="col-lg-12 row" style="text-align: center">
				<? if(isset($my["login"]) && $my["infinity_package"]==4): ?>
				<div class="col-12">
					<div class="card card-custom card_count">
						<div class="card-header">
							<strong class="card-title" style="font-size: 1.5rem !important;">
								<?=date("d.m.Y", strtotime($date_start))?> - <?=date("d.m.Y", strtotime($date_end))?>
							</strong>
						</div>
					</div>
				</div>
				
				<div class="col-lg-4">
					<div class="card card-custom card_count">
						<div class="card-header">
							<strong class="card-title">
								<span class="card-icon"><i class="fa fa-history text-primary"></i></span>
								???????????????????? ????????????????????:
							</strong>
						</div>
						<div class="card-body">
							<p><?=$count_chareholders?> ??????.</p>
						</div>
					</div>
				</div>
				
				<div class="col-lg-4">
					<div class="card card-custom card_count">
						<div class="card-header">
							<strong class="card-title">
								<span class="card-icon"><i class="fa fa-history text-primary"></i></span>
								?????????????????? ???? ??????????:
							</strong>
						</div>
						<div class="card-body">
							<p><?=$count_pv?> PV</p>
						</div>
					</div>
				</div>
				
				<div class="col-lg-4">
					<div class="card card-custom card_count">
						<div class="card-header">
							<strong class="card-title">
								<span class="card-icon"><i class="fa fa-history text-primary"></i></span>
								?? ??????????????:
							</strong>
						</div>
						<div class="card-body">
							<p><span class="count"><?=$count_one?></span> ????.</p>
						</div>
					</div>
				</div>
				
				<div class="col-12">
					<div class="card card-custom">
						<div class="card-header">
							<strong class="card-title"><span class="card-icon">
								<i class="fa fa-history text-primary"></i>
								</span>????????????????????</strong>
						</div>
						<div class="card-body">
							<table class="bootstrap-data-table table table-hover table-head-custom mw-380">
								<thead>
									<tr>
										<th>??????????</th>
										<th>?? ??????????????</th>
										<th>???? ????????</th>
										<th>????????</th>
										<th>??????????</th>
									</tr>
								</thead>
								<tbody>
									<? $resc = 0; ?>
									<? foreach($history_pv as $row): ?>
											<tr>
												<td><?=$row["temp_val"]?> PV</td>
												<td><?=floor($row["temp_val"]*8000)?> ????</td>
												<td><?=$row["user_from"]?></td>
												<td><?=$_SESSION["login"]?></td>
												<td><?=$row["date"]?></td>
											</tr>
									<? endforeach; ?>
									
								</tbody>
							</table>

						</div>
					</div>
				</div>
				
				<? if($_SESSION["login"] == "BoomMarket"): ?>
				<h1 class="mb-5">??????????</h1>
				<div class="col-lg-4">
					<div class="card card-custom card_count">
						<div class="card-header">
							<strong class="card-title">
								<span class="card-icon"><i class="fa fa-history text-primary"></i></span>
								???????????????????? ????????????????????:
							</strong>
						</div>
						<div class="card-body">
							<p><?=$count_chareholders?> ??????.</p>
						</div>
					</div>
				</div>
				
				<div class="col-lg-4">
					<div class="card card-custom card_count">
						<div class="card-header">
							<strong class="card-title">
								<span class="card-icon"><i class="fa fa-history text-primary"></i></span>
								?????????????????? ???? ??????????:
							</strong>
						</div>
						<div class="card-body">
							<p><?=$count_pv_all?> PV</p>
						</div>
					</div>
				</div>
				
				<div class="col-lg-4">
					<div class="card card-custom card_count">
						<div class="card-header">
							<strong class="card-title">
								<span class="card-icon"><i class="fa fa-history text-primary"></i></span>
								?? ??????????????:
							</strong>
						</div>
						<div class="card-body">
							<p><span class="count"><?=$count_one_all?></span> ????.</p>
						</div>
					</div>
				</div>
				
				<div class="col-12">
					<div class="card card-custom">
						<div class="card-header">
							<strong class="card-title"><span class="card-icon">
								<i class="fa fa-history text-primary"></i>
								</span>???????????????????? ??????????</strong>
						</div>
						<div class="card-body">
								<?
									$date_start = isset($_GET["date-start"]) ? $_GET["date-start"]: date("Y-m-d");
									$date_end = isset($_GET["date-end"]) ? $_GET["date-end"]: date("Y-m-d", strtotime('+1 day'));
								?>
								<form method="get">
									<div class="input-group">
										<div class="form-group col-lg-5 col-md-12">
											<label>?? ???????????? ??????????</label>
											<input type="date" class="form-control" name="date-start" value="<?=$date_start?>">
										</div>
										<div class="form-group col-lg-5 col-md-12">
											<label>???? ???????????? ??????????</label>
											<input type="date" class="form-control" name="date-end" value="<?=$date_end?>">
										</div>
										<div class="form-group col-lg-2 col-md-12 align-self-end">
											<input type="submit" class="btn btn-light w-100" value="??????????????">
										</div>
									</div>
								</form>
							<table class="bootstrap-data-table table table-hover table-head-custom mw-380">
								<thead>
									<tr>
										<th>??????????</th>
										<th>?? ??????????????</th>
										<th>???? ????????</th>
										<th>????????</th>
										<th>??????????</th>
									</tr>
								</thead>
								<tbody>
									<? $resc = 0; ?>
									<? foreach($history_pv_all as $row): ?>
											<? foreach($row["shareholders"] as $shareholder): ?>
												<? $resc = $resc+$row["temp_val"];?>
												<tr>
													<td><?=$row["temp_val"]?> PV</td>
													<td><?=floor($row["temp_val"]*8000)?> ????</td>
													<td><?=$row["user_from"]?></td>
													<td><?=$shareholder["login"]?></td>
													<td><?=$row["date"]?></td>
												</tr>
											<? endforeach; ?>
									<? endforeach; ?>
									
								</tbody>
							</table>

						</div>
					</div>
				</div>
				<? endif; ?>
				
				<? else: ?>
				<div class="col-12">
					<div class="card card-custom">
						<div class="card-body">
							<h1>???????????????????????? ???????????????????? ???????????????????? - <span class="red">100 ??????.</span></h1>
							<h2 class="mt-5">?????? ????????????????????????????????</h2>
							<h2 class="red mt-2"><span class="count"><?=$count_chareholders?></span> ???? 100 ??????.</h2>
						</div>
					</div>
				</div>
				<? endif; ?>
			</div>
	</div><!-- .content -->

<? include("../footer.php"); ?>


