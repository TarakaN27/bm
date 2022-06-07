<style>
	.result{
	max-width: unset !important;
    overflow: auto !important;
    max-height: unset !important;
    user-select: auto !important;}
</style>
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
}
else {
	header("Location: ../index.php");
	die();
}
include("header.php");
?>
<div class="content mt-3 forma">
	<!--<form>
		<? if(isset($_GET['username']) && isset($_GET['table'])) {
			$table = $_GET['table'];
			$name = trim($_GET['username']);
			$first_name = mysql_query("select user_id from `".$table."` WHERE BINARY user_login='".$name."'");
			$first_name = mysql_fetch_array($first_name);
			if(!$first_name["user_id"] && !is_array($first_name)) {
				echo '<p class="text-danger"><b>'.$_GET['username'].':</b> Неверный логин партнера</p>';
			} else {
				echo '<p>Введите логин партнера</p>';
			}
	
			$selected[$_GET["table"]] = "selected";
	
		} else {
			echo '<p>Введите логин партнера</p>';
			$selected["m1"] = "selected";
		}
		?>
		<div class="row input-group">
			<input type="text" name="username" placeholder="Логин партнера" value="<?=$_GET['username']?>" class="form-control">
			<select name="table" required class="form-control">
				<option disabled class="bold">Уровни</option>
				<? foreach($levels as $id=>$level) :?>
						<option <? if($_GET["table"] == $id) echo "selected"; ?> value="<?=$id?>"><?=$level?></option>
				<? endforeach; ?>
				<option disabled class="bold">Multi-Boom</option>
				<? foreach($multi as $id=>$level) :?>
					<option <? if($_GET["table"] == $id) echo "selected"; ?> value="<?=$id?>"><?=$level?></option>
				<? endforeach; ?>

			</select>
			<input type="submit" class="btn btn-primary" value="Показать">
		</div>
	</form>-->
	<? if(isset($_GET['username']) && isset($_GET['table'])) {
	$table = $_GET['table'];
	$name = trim($_GET['username']);
	$first_name = mysql_query("select user_id from `".$table."` WHERE BINARY user_login='".$name."'");
	$first_name = mysql_fetch_array($first_name);
	if($first_name["user_id"] && is_array($first_name)) {
		$ids[$first_name["user_id"]] = [
			"id"=>$first_name["user_id"],
			"username"=>$name,
			"sponsor"=>""
		];				
		function getIds($id){
			global $ids;
			global $table;
			$temp_ids = [];
			if(is_array($id)){
				$id = implode(",",$id);
			}
			$name = mysql_query("select user_login from `".$table."` WHERE user_id IN (".$id.")");
			$arr_name = [];

			while($row=mysql_fetch_array($name)){
				$arr_name[] = "'".$row["user_login"]."'";
			}
			$name = implode(", ", $arr_name);
			$result = mysql_query("select user_id, user_login, sponsor_login from `".$table."` WHERE BINARY sponsor_login IN (".$name.")");
			while($row = mysql_fetch_array($result)){
				$ids[$row["user_id"]] = [
					"id"=>$row['user_id'],
					"username"=>trim($row['user_login']),
					"sponsor"=>trim($row['sponsor_login'])
				];
				$temp_ids[] = $row["user_id"];
			}
			if(count($temp_ids)>0){
				getIds($temp_ids);
			}
		}
		getIds($first_name["user_id"]);

	?>
	<div class="card card-custom">
		<div class="card-body">
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
								echo '["'.$temp["username"].'","'.$temp["sponsor"].'", ""]';
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

				<div id="chart_div"></div>
			</div>
		</div>
	</div>
				<?php }		
				}
				?>
</div>
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>

    <script src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="vendors/jquery-validation-unobtrusive/dist/jquery.validate.unobtrusive.min.js"></script>

    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
        <!--  Chart js -->
    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="assets/js/widgets.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
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
		$(".google-visualization-orgchart-node").on("click", function(){
			var name = $(this).text();
			var level = $.urlParam('table');
			var path = window.location.origin+"/Office/b_partners.php?partner="+name+"&table="+level;
			window.open(path);
		});
	});
</script>

</body>
</html>