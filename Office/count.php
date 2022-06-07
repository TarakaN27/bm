<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
include("b_func.php");
date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: login.php");
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	$fio = $row["fio"];
	if($row['login'] !== "BoomMarket") {
		header("Location: ../index.php");
		die();
	}
}
else {
	header("Location: ../index.php");
	die();
}
$color = ['#ffc7a8', '#9fe88b', '#99e8e7'];
include("header.php");
?>
<link rel="stylesheet" href="assets/css/Treant.css">
<link rel="stylesheet" href="assets/css/collapsable.css">
<style>
	body {
		background: #f1f2f7;
	}
	#my_avatar1 {
		border-image: url("images/insta_border_sm.png");
		border-image-slice:27 27 27 27;
		border-image-width:27px 27px 27px 27px;
		border-image-outset:0px 0px 0px 0px;
		border-image-repeat:stretch stretch;
	}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	
<script src="assets/js/raphael.js"></script>
<script src="assets/js/Treant.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.easing.js"></script>
	
	<style>
		.card_count {
		
		}
		.card_count .card-title {
			text-align:center;	
		}
		.card_count .card-body .label_count {
			
		}
		.card_count .card-body p {
			font-size: 6rem;
			line-height: 1;
		}
		h1.title {
			font-size: 2rem;
			margin-bottom: 10px;
		}
	</style>

	<div class="content mt-3">
		<div class="animated fadeIn">
			<h1 class="title">Уровни:</h1>
			<div class="row">
				<? foreach($levels as $level=>$text): ?>
					<?
						$query_count = mysql_query("select id from ".$level);
						$count = mysql_num_rows($query_count);
					?>
					<div class="col-lg-3" style="text-align: center">
						<div class="card card-custom card_count">
							<div class="card-header">
								<strong class="card-title"><?=$text?></strong>
							</div>
							<div class="card-body">
								<span class="label_count">Количество пользователей:</span>
								<p class="count"><?=$count?></p>
							</div>
						</div>
					</div>
				<? endforeach; ?>
			</div>
			<h1 class="title">Multi Boom:</h1>
			<div class="row">
				<? foreach($multi as $level=>$text): ?>
					<?
						$query_count = mysql_query("select id from ".$level);
						$count = mysql_num_rows($query_count);
					?>
					<div class="col-lg-3" style="text-align: center">
						<div class="card card-custom card_count">
							<div class="card-header">
								<strong class="card-title"><?=$text?></strong>
							</div>
							<div class="card-body">
								<span class="label_count">Количество пользователей:</span>
								<p class="count"><?=$count?></p>
							</div>
						</div>
					</div>
				<? endforeach; ?>
			</div>
		</div><!-- .animated -->
	</div><!-- .content -->
</div><!-- /#right-panel -->
<!-- Right Panel -->


    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>

    <script src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="vendors/jquery-validation-unobtrusive/dist/jquery.validate.unobtrusive.min.js"></script>

    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
        <!--  Chart js -->
    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="assets/js/widgets.js"></script>
</body>
</html>
