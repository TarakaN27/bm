<?php
session_start();
include("../db_connect.php");
include("../b_func.php");

include($_SERVER["DOCUMENT_ROOT"]."/Office/global.php");

include("functions.php");
include("t-functions.php");

date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if($options["turbo_level_1"] == 1 && $turbo_level ==5) {
	header("Location: ../index.php");
}
if($options["turbo_level_2"] == 1 && $turbo_level >=6) {
	header("Location: ../index.php");
}

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	$fio = $row["fio"];	
	
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
	
	$get_max_wood = mysql_result(mysql_query("SELECT * FROM turbo_wood WHERE user_id='".$_SESSION["id"]."' ORDER BY level DESC"), 0, "level");
	$get_max_column = mysql_result(mysql_query("SELECT * FROM turbo_column WHERE user_id='".$_SESSION["id"]."' ORDER BY level DESC"),0, "level");
	
	if(isset($_GET["action"]) && $_GET["action"]=="show-level-1"){
		mysql_query("UPDATE options SET value='".$_GET["value"]."' WHERE name='turbo_level_1'");
	}
	if(isset($_GET["action"]) && $_GET["action"]=="show-level-2"){
		mysql_query("UPDATE options SET value='".$_GET["value"]."' WHERE name='turbo_level_2'");
	}
	
	$check_buy = mysql_query("SELECT * FROM `".$turbo_table."` WHERE user_id='".$_SESSION["id"]."' AND level='".$turbo_level."'");
	if(mysql_num_rows($check_buy)==0) {
		header("Location: ../index.php");
	}
	
	$structure = array_values(getStructure($_SESSION["id"], $turbo_table, $turbo_level));
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
		<div class="row">
			<div class="col-lg-12" style="text-align: center">
				<span class="badge badge-success"><?=$turbo_name?></span>
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
    <script src="../assets/js/main.js"></script>
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
