<?php
session_start();
include("../db_connect.php");
include "../smsc_api.php";
include("../b_func.php");

function cmp($a, $b) {
    if ($a["float"] == $b["float"]) {
        return 0;
    }
    return ($a["float"] < $b["float"]) ? -1 : 1;
}
include("functions.php");
include("inf-functions.php");

date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	$fio = $row["fio"];
	if($row["infinity_package"]==0){
		echo '<script>window.location.href="/Office/infinity/buy.php"</script>';
	}
	
	$_GET["package"] = isset($_GET["package"]) ? $_GET["package"]: 1;
	$package = "infinity".$_GET["package"];
	
	$get_me_package = mysql_query("select * from ".$package." where user_id='".$row['id']."'");
	$get_me_package = mysql_fetch_array($get_me_package);
	
	$count_refs = checkCountRef($row['id'], $package);
	
	if($count_refs>=2) {
		$block_change_float = false;
		$my_package = ["left"=>"badge-secondary","right"=>"badge-secondary"];
		$my_package[$get_me_package["pos_float"]] = "badge-primary";
	} else {
		$block_change_float = true;
		$my_package = ["left"=>"badge-secondary disabled","right"=>"badge-secondary disabled"];
	}

	if(isset($_GET["action"]) && $_GET["action"] == "change-float" && isset($_GET["float"]) && in_array($_GET["float"], ["left", "right"])) {
		if($block_change_float === false){
			$query = mysql_query("UPDATE `".$package."` SET pos_float='".$_GET["float"]."' WHERE user_id='".$row["id"]."'");
		}
		#debug("UPDATE `".$package."` SET pos_float='".$_GET["float"]."' WHERE user_id='".$row["id"]."'");
		echo '<script>window.location.href="/Office/infinity/index.php?package='.$_GET["package"].'"</script>';
	}
	
	if (isset($_POST['sub_search'])) {
		$login = mysql_escape_string($_POST['partner']);
		$result_s = mysql_query("select * from users where login='".$login."' and id>".$row['id']);
		if (mysql_num_rows($result_s) > 0 && $login != 'admin') {
			$row = mysql_fetch_array($result_s);	
		}
	}
	
	if(isset($_GET["partner"])) {
		$result = mysql_query("select * from users where id='".$_GET["partner"]."'");
		if (mysql_num_rows($result) != 0) {
			$row = mysql_fetch_array($result);
		}
	}
	
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
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <!--<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Пакет <?=$packages[$_GET["package"]-1]["name"]?></h1>
                    </div>
                </div>
            </div>
        </div>-->
<script src="../assets/js/raphael.js"></script>
<script src="../assets/js/Treant.js"></script>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/jquery.easing.js"></script>
        <div class="content infinity mt-3" style="background: #fff">
            <div class="animated fadeIn">

                <div class="row">
				<div class="col-lg-12" style="text-align: center">
					
					<div class="col-lg-12 package" style="text-align: center">
						<div class="col-lg-6 col-sm-12 float-none my-10 mx-auto row">
							<div class="head mb-5 w-100 d-flex flex-row text-left">
								<p class="col-lg-6 col-sm-12">Пакет: <?=$packages[$row["infinity_package"]-1]["name"]?></p>
								<? if($row["infinity_package"]): ?>
									<? $disabled = $row["infinity_package"] == 4 ? "disabled": ""; ?>
									<a href="buy.php" class="body btn btn-primary col-lg-6 col-sm-12 <?=$disabled?>">Улучшить</a>
								<? else: ?>
									<a href="buy.php" class="body btn btn-primary col-lg-6 col-sm-12">Купить</a>
								<? endif; ?>
							</div>
						</div>
					</div>
					
					<div class="col-lg-12" style="text-align: center">
					<form method="post">
						<input type="text" placeholder="Найти партнера" name="partner">
						<input type="submit" value="Поиск" name="sub_search" class="btn btn-success">
					</form>
							<span class="badge badge-red">Внимание, покупка уровня доступна раз в 5 минут!</span>
					</div>
					
						<a href="?action=change-float&float=left&package=<?=$_GET["package"]?>" class="badge <?=$my_package["left"]?> mr-10">Слева</a>
						<span class="badge badge-success">Пакет <?=$packages[$row["infinity_package"]-1]["name"]?></span>
						<a href="?action=change-float&float=right&package=<?=$_GET["package"]?>" class="badge <?=$my_package["right"]?> ml-10">Справа</a>
					
<?php
$nodeStructure = [];
$i = 0;
$master = $row['id'];
$flag = ['','',''];
$result2 = mysql_query("select p.*, u.login from ".$package." as p LEFT JOIN users as u ON u.id=p.user_id where user_id='".$master."' order by id asc");
if (mysql_num_rows($result2) != 0) {
	$row2 = mysql_fetch_assoc($result2);

	if (is_file('../images/avatar/'.$row2['user_id'].'.jpg')) {
		$avatar = '../images/avatar/'.$row2['user_id'].'.jpg'; 
	} else {
		$avatar='../images/ava3.png';
	}
	$color="gray";
	$nodeStructure[$master] = [
		"image"=>$avatar,
		"contact"=>"",
		"pv_left"=>$row2["pv_left"],
		"pv_right"=>$row2["pv_right"],
		"color"=>$color,
		"login"=>$row2["login"]
	];
	
	$result3 = mysql_query("select p.*, u.login from ".$package." as p LEFT JOIN users as u ON u.id=p.user_id where teacher='".$master."' order by id asc");
	$r=2;
	if (mysql_num_rows($result3) != 0) {
		while ($row3 = mysql_fetch_assoc($result3)) {
			$i++;
			
			if (is_file('../images/avatar/'.$row3['user_id'].'.jpg')) {
				$avatar = '../images/avatar/'.$row3['user_id'].'.jpg';
			} else {
				$avatar='../images/ava3.png';
			}
			$master1 = $row3['user_id'];
			
			$float = $row2["left_partner"] == $master1 ? 1: 3;
				
			$color="gray";
			$nodeStructure[$master]["children"][$master1] = [
				"image"=>$avatar,
				"float"=>$float,
				"contact"=>$row3['phone'],
				"pv_left"=>$row3["pv_left"],
				"pv_right"=>$row3["pv_right"],
				"color"=>$color,
				"login"=>$row3["login"]
			];
			
			$result4 = mysql_query("select p.*, u.login from ".$package." as p LEFT JOIN users as u ON u.id=p.user_id where teacher='".$master1."' order by id asc");
			$z = 2;
			if (mysql_num_rows($result4) != 0) {
				while ($row4 = mysql_fetch_assoc($result4)) {
					$i++;
					if (is_file('../images/avatar/'.$row4['user_id'].'.jpg')){
						$avatar = '../images/avatar/'.$row4['user_id'].'.jpg';
					} else {
						$avatar='../images/ava3.png';
					}
					
					$float = $row3["left_partner"] == $row4['user_id'] ? 1: 3;
					$color="gray";
					$nodeStructure[$master]["children"][$master1]["children"][$row4['user_id']] = [
						"image"=>$avatar,
						"float"=>$float,
						"contact"=>$row4['phone'],
						"pv_left"=>$row4["pv_left"],
						"pv_right"=>$row4["pv_right"],
						"color"=>$color,
						"login"=>$row4["login"]
					];
					$z--;
				}
			}
			for($x=0;$x<$z;$x++){
				$color="dsbl";
				$nodeStructure[$master]["children"][$master1]["children"]["NULL_".$x] = [
					"image"=>'../images/ava3.png',
					"contact"=>"",
					"float"=>2,
					"pv_left"=>"",
					"pv_right"=>"",
					"color"=>$color,
					"login"=>""
				];
			}
			$r--;
		}
	}
	for($x=0;$x<$r;$x++){
		$color="dsbl";
		$nodeStructure[$master]["children"]["NULL_".$x] = [
			"image"=>'../images/ava3.png',
			"contact"=>"",
			"float"=>2,
			"pv_left"=>"",
			"pv_right"=>"",
			"color"=>$color,
			"login"=>""
		];
		for($c=0;$c<2;$c++){
			$color="dsbl";
			$nodeStructure[$master]["children"]["NULL_".$x]["children"]["NULL_".$c] = [
				"image"=>'../images/ava3.png',
				"contact"=>"",
				"float"=>2,
				"pv_left"=>"",
				"pv_right"=>"",
				"color"=>$color,
				"login"=>""
			];
		}
	}
}
$url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
?>
<table class="node">
	<? foreach($nodeStructure as $name=>$val): ?>
		<tr>
			<td colspan="4">
				<a href="?partner=<?=$name?>" class="node-content <?=$val['color']?>">
					<div class="img"><img src="<?=$url?>/<?=$val['image']?>"></div>
					<div class="node-name"><?=$val["login"]?></div>
					<div class="node-contact"><?=$val["contact"]?></div>
					<div class="node-contact"><?=$val["pv_left"]." PV / ".$val["pv_right"]." PV"?></div>
				</a>
			</td>
		</tr>
		<tr>
			<?
				uasort($val["children"], 'cmp');
			?>
			<? foreach($val["children"] as $child_name=>$child_val): ?>
				<td colspan="2">
					<? if(stripos($child_name, "NULL_") === 0): ?>
						<div class="node-content <?=$child_val['color']?>">
							<div class="img max-h-40px"><img src="<?=$url?>/<?=$child_val['image']?>"></div>
							<div class="node-name"> </div>
							<div class="node-contact"> </div>
						</div>
					<? else: ?>
						<a href="?partner=<?=$child_name?>" class="node-content <?=$child_val['color']?>">
							<div class="img"><img src="<?=$url?>/<?=$child_val['image']?>"></div>
							<div class="node-name"><?=$child_val["login"]?></div>
							<div class="node-contact"><?=$child_val["pv_left"]." PV / ".$child_val["pv_right"]." PV"?></div>
						</a>
					<? endif; ?>
				</td>
			<? endforeach; ?>
		</tr>
		<tr>
			<? uasort($val["children"], 'cmp'); ?>
			<? foreach($val["children"] as $child_name=>$child_val): ?>
				<?
					uasort($child_val["children"], 'cmp');
				?>
				<? foreach($child_val["children"] as $two_child_name=>$two_child_val): ?>
					<td>
						<? if(stripos($two_child_name, "NULL_") === 0): ?>
							<div class="node-content <?=$child_val['color']?>">
								<div class="img max-h-40px"><img src="<?=$url?>/<?=$child_val['image']?>"></div>
								<div class="node-name"> </div>
								<div class="node-contact"> </div>
							</div>
						<? else: ?>
							<a href="?partner=<?=$two_child_name?>" class="node-content <?=$two_child_val['color']?>">
								<div class="img"><img src="<?=$url?>/<?=$two_child_val['image']?>"></div>
								<div class="node-name"><?=$two_child_val["login"]?></div>
								<div class="node-contact"><?=$two_child_val["pv_left"]." PV / ".$two_child_val["pv_right"]." PV"?></div>
							</a>
						<? endif; ?>
					</td>
				<? endforeach; ?>
			<? endforeach; ?>
		</tr>
	<? endforeach; ?>
</table>
<a href="all_sponsors.php?id=<?=$row['id']?>&table=<?=$package?>" class="badge badge-success sponsors">Масштабировать</a>
						

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
</body>
</html>
