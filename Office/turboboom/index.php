<?php
session_start();
include("../db_connect.php");
include("../b_func.php");

include("functions.php");
include("t-functions.php");


date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");


if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	$fio = $row["fio"];	
	
	if (isset($_GET['sub_search'])) {
		$login = mysql_escape_string($_GET['partner']);
		$result_s = mysql_query("SELECT * FROM users WHERE login='".$login."' AND id!=".$row['id']);
		if (mysql_num_rows($result_s) > 0) {
			$row = mysql_fetch_array($result_s);	
		}
	}

	
	$get_level = isset($_GET["level"]) ? $_GET["level"]: 1;
	$turbo_table = $turbo_levels[$get_level]["table"];
	$turbo_level = $turbo_levels[$get_level]["level"];
	$turbo_name = $turbo_levels[$get_level]["name"];
	
	if($options["turbo_level_1"] == 1 && $turbo_level >= 10) {
		header("Location: ../index.php");
	}
	if($options["turbo_level_2"] == 1 && $turbo_level >= 12) {
		header("Location: ../index.php");
	}
	
	$get_max_wood = mysql_result(mysql_query("SELECT * FROM turbo_wood WHERE user_id='".$row["id"]."' ORDER BY level DESC"), 0, "level");
	$get_max_column = mysql_result(mysql_query("SELECT * FROM turbo_column WHERE user_id='".$row["id"]."' ORDER BY level DESC"),0, "level");
	
	if(isset($_GET["action"]) && $_GET["action"]=="show-level-1"){
		mysql_query("UPDATE options SET value='".$_GET["value"]."' WHERE name='turbo_level_1'");
	}
	if(isset($_GET["action"]) && $_GET["action"]=="show-level-2"){
		mysql_query("UPDATE options SET value='".$_GET["value"]."' WHERE name='turbo_level_2'");
	}
	
	$check_buy = mysql_query("SELECT * FROM `".$turbo_table."` WHERE user_id='".$row["id"]."' AND level='".$turbo_level."'");
	if(mysql_num_rows($check_buy)==0) {
		header("Location: ../index.php");
	}
	
	$structure = array_values(getStructure($row["id"], $turbo_table, $turbo_level));
	
	if($turbo_table == "turbo_column"){
		$structure = array_slice($structure, 0, 11);
	} else {
		$structure = getAllWood(buildTreeArray($structure));
	}
	#var_dump($structure);

}
else {
	header("Location: ../index.php");
	die();
}
$color = ['#ffc7a8', '#9fe88b', '#99e8e7'];
include("../header.php");
?>
<link rel="stylesheet" href="../assets/css/Treant.css">
<link rel="stylesheet" href="../assets/css/collapsable.css">
<style>
	#my_avatar1 {
		border-image: url("../images/insta_border_sm.png");
		border-image-slice:27 27 27 27;
		border-image-width:27px 27px 27px 27px;
		border-image-outset:0px 0px 0px 0px;
		border-image-repeat:stretch stretch;
	}
	
	
	.structure .btn.btn-secondary, .structure .btn.btn-primary {
		border-color: #87878a;
		min-height: 60px;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<script src="../assets/js/raphael.js"></script>
<script src="../assets/js/Treant.js"></script>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/jquery.easing.js"></script>
<div class="content mt-3" style="background: #fff">
	<div class="animated fadeIn">
		<? if($_SESSION["login"] == "BoomMarket"): ?>
		<div class="row">
			<div class="card w-100">
				<div class="card-body">
					<table class="table w-100">
						<tr><td><h4>Отображение уровней 10-11</h4>
							<? if($options["turbo_level_1"]==1): ?>
								<td><a href="?action=show-level-1&value=0" class="btn btn-danger">Отключить</a>
							<? else: ?>
								<td><a href="?action=show-level-1&value=1" class="btn btn-primary">Включить</a>
							<? endif; ?>
						<tr><td><h4>Отображение уровней 12-13</h4>
							<? if($options["turbo_level_2"]==1): ?>
								<td><a href="?action=show-level-2&value=0" class="btn btn-danger">Отключить</a>
							<? else: ?>
								<td><a href="?action=show-level-2&value=1" class="btn btn-primary">Включить</a>
							<? endif; ?>
					</table>
				</div>
			</div>
		</div>
		<? endif; ?>
		<div class="row">
			<div class="col-lg-12" style="text-align: center">
				<form method="get" class="col-md-3 input-group mx-auto my-4" <?if($_SESSION["id"]!=5436){echo 'style="display:none"';}?>>
					<input type="text" class="form-control" placeholder="Найти партнера" name="partner">
					<input type="hidden" name="level" value="<?=$get_level?>">
					<input type="submit" value="Поиск" name="sub_search" class="btn btn-success">
				</form>
				<span class="badge badge-success mx-1"><?=$turbo_name?></span>
				<a href="/Office/turboboom/wood.php?level=<?=$get_level?>" class="badge badge-success mx-1">Вся структура</a>
				<div style="width:100%; height:700px;" id="tree"/>
			</div>
		</div>
	</div><!-- .animated -->
</div><!-- .content -->
</div><!-- /#right-panel -->
<!-- Right Panel -->


    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>

    <script src="../vendors/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="../vendors/jquery-validation-unobtrusive/dist/jquery.validate.unobtrusive.min.js"></script>

    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js?ver=4"></script>
        <!--  Chart js -->
    <script src="../vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="../assets/js/widgets.js"></script>
	<script src="/Office/assets/js/orgchart.js"></script>
	<script>
		var chart = new OrgChart(document.getElementById("tree"), {
			template: "isla",
			enableSearch: false,
			mouseScrool: OrgChart.action.zoom,
			nodeMouseClick: OrgChart.action.none,
			nodeBinding: {
				field_0: "name",
				field_1: "title"
			},
			nodes: [
				<? foreach($structure as $row): ?>
					{ id: <?=$row["user_id"]?>, pid: <?=$row["parent_id"]?>, name: '<?=$row["login"]?>', title: '<?=$row["fio"]?>'},
				<? endforeach; ?>
			]
		});
	</script> 
</body>
</html>
