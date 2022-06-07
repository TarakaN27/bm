<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
include("b_func.php");

$back_url = urlencode("/Office/history_buy_level.php?table=m2&action=level"); #"/Office/history_buy_level.php?table=m2&action=level";

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result)!=0 && ($_SESSION['login']=='BoomMarket' || $_SESSION['admin_login']=='BoomMarket')) {
	$row = mysql_fetch_array($result);
	$fio = $row["fio"];
	if (isset($_GET['sub_btn'])) $st = $_GET['f_search']; else $st = "";
}
else {
	header("Location: ../index.php");
	die();
}

if($_GET["action"]=="level" && $_GET["table"]) {
	$type_table = array_search($_GET["table"], array_column($turbo_levels, "table"))>=0 ? "turbo": "";
	$type_table = isset($levels[$_GET["table"]]) ? "m": $type_table;
	$type_table = isset($multi[$_GET["table"]]) ? "qq": $type_table;
	$type_table = isset($infinity[$_GET["table"]]) ? "infinity": $type_table;
	
	$title_level = isset($levels[$_GET["table"]]) ? "Основной маркетинг: ".$levels[$_GET["table"]]: "";
	$title_level = isset($multi[$_GET["table"]]) ? "MultiBoom: ".$multi[$_GET["table"]]: $title_level;
	$title_level = isset($infinity[$_GET["table"]]) ? "Infinity: ".$infinity[$_GET["table"]]: $title_level;
	$title_level = array_search($_GET["table"],array_column($turbo_levels, "table"))>0 ? "TurboBoom: ".array_search($_GET["table"],array_column($turbo_levels, "table")): $title_level;
	
	if($type_table != "infinity" && $type_table != "turbo") {
		if(strlen($st)>0){
			$result_table = mysql_query("
				select t.*, u.fio from `".$_GET["table"]."` as t
				LEFT JOIN `users` as u ON t.user_id=u.id
				WHERE u.login LIKE '%".$st."%'
				ORDER BY id DESC
			");
		} else {
			$result_table = mysql_query("
				select t.*, u.fio from `".$_GET["table"]."` as t
				LEFT JOIN `users` as u ON t.user_id=u.id
				ORDER BY t.id DESC
			");
		}
	} elseif($type_table == "infinity") {
		if(strlen($st)>0){
			$result_table = mysql_query("
				select t.*, u.fio, u.login, l.login as leader, teach.login as teacher from `".$_GET["table"]."` as t
				LEFT JOIN `users` as u ON t.user_id=u.id
				LEFT JOIN `users` as l ON l.id=t.leader
				LEFT JOIN `users` as teach ON teach.id=t.teacher
				WHERE u.login LIKE '%".$st."%'
				ORDER BY id DESC
			");
		} else {
			$result_table = mysql_query("
				select t.*, u.fio, u.login, l.login as leader, teach.login as teacher from `".$_GET["table"]."` as t
				LEFT JOIN `users` as u ON t.user_id=u.id
				LEFT JOIN `users` as l ON l.id=t.leader
				LEFT JOIN `users` as teach ON teach.id=t.teacher
				ORDER BY t.id DESC
			");
		}
	} elseif($type_table == "turbo") {
		if(strlen($st)>0){
			$result_table = mysql_query("
				select t.*, u.fio, u.login as user_login, c.login as sponsor_login from `".$_GET["table"]."` as t
				LEFT JOIN `users` as u ON t.user_id=u.id
				LEFT JOIN `users` as c ON c.id=t.parent_id
				WHERE u.login LIKE '%".$st."%' AND t.level='".$_GET["level"]."'
				ORDER BY id DESC
			");
		} else {
			$result_table = mysql_query("
				select t.*, u.fio, u.login as user_login, c.login as sponsor_login from `".$_GET["table"]."` as t
				LEFT JOIN `users` as u ON t.user_id=u.id
				LEFT JOIN `users` as c ON c.id=t.parent_id
				WHERE t.level='".$_GET["level"]."'
				ORDER BY t.id DESC
			");
		}
	}
	
}

$pay_type = ["Переход", "Куплен", "Активирован"];

include("header.php");
?>
<div class="content mt-3">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-custom">
					<div class="card-header">
						<strong class="card-title"><?=$title_level?></strong>
						<a href="/Office/persons.php" class="btn btn-light">Назад</a>
					</div>
					<div class="card-body">
						<? if(isset($_GET["table"])): ?>
						<form method="get">
							<div class="input-group">
								<input type="text" name="f_search" class="form-control" palceholder="Введите логин пользователя" value="<?=$st?>">
								<input type="hidden" name="table" value="<?=$_GET["table"]?>">
								<input type="hidden" name="action" value="level">
								<? if(isset($_GET["level"])):?>
									<input type="hidden" name="level" value="<?=$_GET["level"]?>">
								<? endif; ?>
								<input type="submit" name="sub_btn" value="Поиск" class="btn btn-light">
							</div>
						</form>
						<? endif; ?>
						<div class="table-scroll">
							<table class="table table-hover table-head-custom mw-380">
								<thead>
									<? if($type_table != "infinity" && $type_table != "turbo"): ?>
										<th>ID</th>
										<th>Логин</th>
										<th>ФИО</th>
										<th>Спонсор</th>
										<th>Дата</th>
										<th>Тип</th>
									<? elseif($type_table == "turbo"): ?>
										<th>ID</th>
										<th>Логин</th>
										<th>ФИО</th>
										<th>Спонсор</th>
										<th>Дата</th>
									<? else: ?>
										<th>ID</th>
										<th>Логин</th>
										<th>ФИО</th>
										<th>Лидер</th>
										<th>Учитель</th>
										<th>Тип</th>
									<? endif; ?>
								</thead>
								<tbody>
									<? if(count($result_table)>0): ?>
									<? while($row = mysql_fetch_assoc($result_table)): ?>
									<?
										foreach($row as $name=>$value){
											$row[$name] = $value == NULL ? "Не указано": $value;
										}
									?>
									<tr class="href" data-href="/Office/persons.php?action=edit&edit-id=<?=$row['user_id']?>&back-url=<?=$back_url?>">
										<? if($type_table != "infinity" && $type_table != "turbo"): ?>
											<td><?=$row["id"]?></td>
											<td><?=$row["user_login"]?></td>
											<td><?=$row["fio"]?></td>
											<td><?=$row["sponsor_login"]?></td>
											<td><?=$row["post_time"]?></td>
											<td><?=$pay_type[$row["pay"]]?></td>
										<? elseif($type_table == "turbo"): ?>
											<td><?=$row["id"]?></td>
											<td><?=$row["user_login"]?></td>
											<td><?=$row["fio"]?></td>
											<td><?=$row["sponsor_login"]?></td>
											<td><?=$row["date"]?></td>
										<? else: ?>
											<td><?=$row["id"]?></td>
											<td><?=$row["login"]?></td>
											<td><?=$row["fio"]?></td>
											<td><?=$row["leader"]?></td>
											<td><?=$row["teacher"]?></td>
											<td><?=$pay_type[$row["pay"]]?></td>
										<? endif; ?>
									</tr>
									<? endwhile; ?>
									<? else: ?>
									<tr>
										<td colspan="4">Нет результатов.</td>
									</tr>
									<? endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- .animated -->
</div><!-- .content -->

<? include("footer.php"); ?>
	<script>
		$(".href").on("click", function(){
			var href=$(this).attr("data-href");
			window.location.href = href;
		})
	</script>
