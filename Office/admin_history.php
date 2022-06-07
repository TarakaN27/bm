<?php

session_start();
include("db_connect.php");
include "smsc_api.php";
include("b_func.php");
date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: login.php");
$result = mysql_query("select * from users where BINARY login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);    
	$fio = $row["fio"];
}
else {
	header("Location: ../index.php");
	die();
}
include("header.php");
?>

<div class="content mt-3">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<strong class="card-title">Операции администраторов</strong>
					</div>
					<div class="card-body table-responsive">
						<?
						$logs = mysql_query("SELECT u.id, h.msg, h.date, u.login, h.type FROM admin_history as h LEFT JOIN users as u ON h.admin_id=u.id ORDER BY h.id DESC");
						
						?>
						<!--begin: Datatable-->
						<table class="table table-hover table-head-custom">
							<thead>
								<tr>
									<th>ID</th>
									<th>Логин</th>
									<th>Операция</th>
									<th>Тип</th>
									<th>Дата</th>
								</tr>
							</thead>
							<tbody>
								<? while($row = mysql_fetch_assoc($logs)): ?>

								<tr>
									<td><?=$row["id"]?></td>
									<td><?=$row["login"]?></td>
									<td><?=$row["msg"]?></td>
									<td><?=$row["type"]?></td>
									<td><?=$row["date"]?></td>
								</tr>
								<? endwhile; ?>
							</tbody>
						</table>
						<!--end: Datatable-->
					</div>
				</div>
			</div>
		</div>
	</div><!-- .animated -->
</div><!-- .content -->

<? include("footer.php"); ?>