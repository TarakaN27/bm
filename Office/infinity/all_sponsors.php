<?php

session_start();
include("../db_connect.php");
include "../smsc_api.php";
include("../b_func.php");
date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);  
	$fio = $row["fio"];
}
else {
	header("Location: ../index.php");
	die();
}
include("../header.php");
?>

<div class="content mt-3 forma">
	<? if(isset($_GET['id']) && isset($_GET['table'])) {
	$table = $_GET['table'];
	$first_name = mysql_query("select user_id, login from `".$table."` as t LEFT JOIN users as u ON u.id=t.user_id WHERE user_id='".$_GET['id']."'");
	$first_name = mysql_fetch_array($first_name);
	
	if($first_name["user_id"] && is_array($first_name)) {
		$ids[$first_name["user_id"]] = [
			"id"=>$first_name["user_id"],
			"username"=>strtoupper($first_name["login"]),
			"sponsor"=>"",
			"float"=>1
		];				
		function getIds($id){
			global $ids;
			global $table;
			$temp_ids = [];
			if(is_array($id)){
				$id = implode(",",$id);
			}
			$result = mysql_query("select user_id, u.login, tu.login as tulogin from `".$table."` as t LEFT JOIN users as u ON u.id=t.user_id LEFT JOIN users as tu ON tu.id=t.teacher WHERE teacher IN (".$id.")");
			while($row = mysql_fetch_assoc($result)){
				$float = mysql_query("SELECT left_partner, right_partner FROM `".$table."` as t LEFT JOIN users as u ON u.id=t.user_id WHERE u.login='".$row['tulogin']."'");
				$float = mysql_fetch_array($float);
				$float_str = 3;
				if($row["user_id"] == $float["left_partner"]) $float_str = 1;
				if($row["user_id"] == $float["right_partner"]) $float_str = 2;
				
				$ids[$row["user_id"]] = [
					"id"=>$row['user_id'],
					"username"=>trim(strtoupper($row['login'])),
					"sponsor"=>strtoupper($row['tulogin']),
					"float"=>$float_str
				];
				$temp_ids[] = $row["user_id"];

			}
			if(count($temp_ids)>0){
				getIds($temp_ids);
			}
		}
		getIds($first_name["user_id"]);
		
		if(!isset($_GET["view"]) && $_GET["view"]!="full"){
			$ids = array_slice($ids, 0, ceil(count($ids)/2), true);
		}
		
		uasort($ids, function($a,$b){
			return ($a['float']-$b['float']);
		});
		
		
	?>
	<div class="card card-custom">
		<div class="card-body">
			<div class="zoom">
				<span class="zoom_plus badge badge-success">Увеличить</span>
				<? if(isset($_GET["view"]) && $_GET["view"]=="full"): ?>
					<a href="?id=<?=$_GET["id"]?>&table=<?=$_GET["table"]?>" class="badge badge-success">Сократить</a>
				<? else: ?>
					<a href="?id=<?=$_GET["id"]?>&table=<?=$_GET["table"]?>&view=full" class="badge badge-success">Весь список</a>
				<? endif; ?>
				<span class="zoom_minus badge badge-success">Уменьшить</span>
			</div>
			<div class="result">
				<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
				<script type="text/javascript">
					google.charts.load('current', {packages:["orgchart"]});
					google.charts.setOnLoadCallback(drawChart);

					function drawChart() {
						var data = new google.visualization.DataTable();
						data.addColumn('string', 'Name');
						data.addColumn('string', 'Manager');
						data.addColumn('string', 'ToolTip');

						// For each orgchart box, provide the name, manager, and tooltip to show.
						data.addRows([<?php 
							$i=0;
							foreach($ids as $temp) {
								echo '["'.trim($temp["username"]).'","'.trim($temp["sponsor"]).'", ""]';
								if(++$i<count($ids))
									echo ",";
							}
							?>]);

						// Create the chart.
						var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
						// Draw the chart, setting the allowHtml option to true for the tooltips.
						chart.draw(data, {'allowHtml':true});
					}
				</script>

				<div id="chart_div" class="chart_div"></div>
			</div>
		</div>
	</div>
				<?php }		
				}
				?>
</div>

<? include("../footer.php"); ?>
<script type="text/javascript">
    window.onload = function () {
        var scr = $(".result");
        scr.mousedown(function () {
            var startX = this.scrollLeft + event.pageX;
            var startY = this.scrollTop + event.pageY;
            scr.mousemove(function () {
                this.scrollLeft = startX - event.pageX;
                this.scrollTop = startY - event.pageY;
                return false;
            });
        });
        $(window).mouseup(function () {
            scr.off("mousemove"); 
        });
    }
</script>
<script>
	$.urlParam = function(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (results==null) {
		   return null;
		}
		return decodeURI(results[1]) || 0;
	}
	
	$("#chart_div").bind("DOMSubtreeModified",function(){		
		var l = [['0.1', '-450'],
			['0.2', '-200'],
			['0.3', '-115'],
			['0.4', '-74'],
			['0.5', '-49'],
			['0.6', '-33'],
			['0.7', '-21'],
			['0.8', '-12'],
			['0.9', '-5'],
			['1', '0']];
	
		var zoom = 9;
		$(".zoom_plus").on("click", function(){
			if(zoom < 9){zoom += 1;}
			console.log("transform", "scale("+l[zoom][0]+") translate("+l[zoom][1]+"%, "+l[zoom][1]+"%)");
			$(".chart_div").css("transform", "scale("+l[zoom][0]+") translate("+l[zoom][1]+"%, "+l[zoom][1]+"%)");
		})
		$(".zoom_minus").on("click", function(){
			if(zoom > 0){zoom -= 1;}
			console.log("transform", "scale("+l[zoom][0]+") translate("+l[zoom][1]+"%, "+l[zoom][1]+"%)");
			$(".chart_div").css("transform", "scale("+l[zoom][0]+") translate("+l[zoom][1]+"%, "+l[zoom][1]+"%)");
		})
	});
	
	
	
	$(".result").scroll((e) => {
			let percentScrolled = Math.round($(".result").scrollTop() / $(".result").innerHeight()*10);
			//console.log(percentScrolled);
			//console.log("scale("+l[percentScrolled][0]+") translate("+l[percentScrolled][1]+"%)");
			$("#chart_div").css("transrofm", "scale(0.7)");
	});
	
	
</script>