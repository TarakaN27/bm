<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
date_default_timezone_set('Asia/Almaty');

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
    $fio = $row["fio"];
    if (isset($_GET['sub_m1'])) {
		$amount = 5000;
		$user_id = intval($row['login']);
		$sponsor_login = intval(mysql_escape_string($_POST['sponsor_login']));
		$sponsor = array();
		//$index = array();
		//$r = 0;
		$flag_m = false;
		//while (!$flag_m) {
		//	$r++;
			$result_s = mysql_query("select * from m1 where sponsor_login='".$sponsor_login."'");
			if (mysql_num_rows($result_s) > 0) {
				
				$pay_sum = $_GET["pay_type"]=="balans"? $row['akwa']: $row["balans_turbo"];
					if (($pay_sum - $amount) >= 0) {
					$row_s = mysql_fetch_array($result_s);
					$i = 0; 
					$n = 1; 
					$sponsor[0] = $sponsor_login;
					//$index[0] = $row_s['id'];
					while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
						$result_x = mysql_query("select * from m1 where sponsor_login='".$sponsor[$i]."'");
						if (mysql_num_rows($result_x)<2) {
							mysql_query("update users set akwa=akwa+500 where login='".$sponsor[$i]."'");	
							if($_GET["pay_type"]=="balans")	{
								mysql_query("update users set akwa=akwa-5000, m1=1 where login='".$row['login']."'");
							} else {
								mysql_query("update users set balans_turbo=balans_turbo-5000, m1=1 where login='".$row['login']."'");
							}
							
							mysql_query("insert into m1 (user_id, user_login, sponsor_login, type, pay) values (".$row['id'].", '".$row['login']."', '".$sponsor[$i]."', 0, '1')");
							$flag_m = true;
							echo mysql_error();
						}
						else {
							while ($row_x = mysql_fetch_array($result_x)) {
								$sponsor[] = $row_x['user_login'];
								//$index[] = $row_x['id'];
								$n++;
							}
						}
						$i++;
					}
				}
				else $message = '<div class="alert alert-danger" role="alert">
                                Недостаточно средств. Пожалуйста, проверьте свой счёт.
                            </div>';
			}
			
		//}
	}
	if (isset($_GET['partner'])) {
		$login = mysql_escape_string($_GET['partner']);
		$result_s = mysql_query("select * from users where login='".$login."' and id>".$row['id']);
		if (mysql_num_rows($result_s) > 0) {
			$row = mysql_fetch_array($result_s);	
		}
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
	#my_avatar1 {
		border-image: url("images/insta_border_sm.png");
		border-image-slice:27 27 27 27;
		border-image-width:27px 27px 27px 27px;
		border-image-outset:0px 0px 0px 0px;
		border-image-repeat:stretch stretch;
	}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Структура</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Личный кабинет</a></li>
                            <li><a href="#">Операции</a></li>
                            <li class="active">Структура</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
<script src="assets/js/raphael.js"></script>
<script src="assets/js/Treant.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.easing.js"></script>
        <div class="content mt-3" style="background: #c70a0d">
            <div class="animated fadeIn">

                <div class="row">
				<div class="col-lg-12" style="text-align: center">
					
					<div class="col-lg-12" style="text-align: center">
					<span class="badge badge-success">Этап 8</span>
					<div class="chart01" id="collapsable-example1" style="color: #111;"></div>

<?php
$m = "m8";
$msg_phone = "Номер скрыт";
$nodeStructure = [];
$i = 0;
$master = $row['login'];
$flag = ['','',''];
$result2 = mysql_query("select t.*, u.phone, u.hide_data from ".$m." as t LEFT JOIN users as u ON u.id=t.user_id where user_login='".$master."' order by id asc");
if (mysql_num_rows($result2) != 0) {
	$row2 = mysql_fetch_array($result2);
	if($row2["hide_data"] == 0 && $_SESSION["login"] != "BoomMarket") {
		$row2["phone"] = $msg_phone;
	}
	if (is_file('images/avatar/'.$row2['user_id'].'.jpg')) {
		$avatar = 'images/avatar/'.$row2['user_id'].'.jpg'; 
	} else {
		$avatar='images/ava3.png';
	}
	$color="gray";
	$nodeStructure[$master] = [
		"image"=>$avatar,
		"contact"=>$row2["phone"],
		"color"=>$color
	];
	
	$result3 = mysql_query("select t.*, u.phone from ".$m." as t LEFT JOIN users as u ON u.id=t.user_id where sponsor_login='".$master."' order by id asc");
	$r=2;
	if (mysql_num_rows($result3) != 0) {
		while ($row3 = mysql_fetch_array($result3)) {
			$i++;
			if($row3["hide_data"] == 0 && $_SESSION["login"] != "BoomMarket") {
				$row3["phone"] = $msg_phone;
			}
			
			if (is_file('images/avatar/'.$row3['user_id'].'.jpg')) {
				$avatar = 'images/avatar/'.$row3['user_id'].'.jpg';
			} else {
				$avatar='images/ava3.png';
			}
			$master1 = $row3['user_login'];
			$color="gray";
			$nodeStructure[$master]["children"][$master1] = [
				"image"=>$avatar,
				"contact"=>$row3['phone'],
				"color"=>$color
			];
			
			$result4 = mysql_query("select t.*, u.phone from ".$m." as t LEFT JOIN users as u ON u.id=t.user_id where sponsor_login='".$master1."' order by id asc");
			$z = 2;
			if (mysql_num_rows($result4) != 0) {
				while ($row4 = mysql_fetch_array($result4)) {
					$i++;
					
					if($row4["hide_data"] == 0 && $_SESSION["login"] != "BoomMarket") {
						$row4["phone"] = $msg_phone;
					}
					
					if (is_file('images/avatar/'.$row4['user_id'].'.jpg')){
						$avatar = 'images/avatar/'.$row4['user_id'].'.jpg';
					} else {
						$avatar='images/ava3.png';
					}
					$color="gray";
					$nodeStructure[$master]["children"][$master1]["children"][$row4['user_login']] = [
						"image"=>$avatar,
						"contact"=>$row4['phone'],
						"color"=>$color
					];
					$z--;
				}
			}
			for($x=0;$x<$z;$x++){
				$color="dsbl";
				$nodeStructure[$master]["children"][$master1]["children"]["NULL_".$x] = [
					"image"=>'images/ava3.png',
					"contact"=>"",
					"color"=>$color
				];
			}
			$r--;
		}
	}
	for($x=0;$x<$r;$x++){
		$color="dsbl";
		$nodeStructure[$master]["children"]["NULL_".$x] = [
			"image"=>'images/ava3.png',
			"contact"=>"",
			"color"=>$color
		];
		for($c=0;$c<2;$c++){
			$color="dsbl";
			$nodeStructure[$master]["children"]["NULL_".$x]["children"]["NULL_".$c] = [
				"image"=>'images/ava3.png',
				"contact"=>"",
				"color"=>$color
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
					<div class="node-name"><?=$name?></div>
					<div class="node-contact"><?=$val["contact"]?></div>
				</a>
			</td>
		</tr>
		<tr>
			<? foreach($val["children"] as $child_name=>$child_val): ?>
				<td colspan="2">
					<? if(stripos($child_name, "NULL_") === 0): ?>
						<div class="node-content <?=$child_val['color']?>">
							<div class="img max-h-40px"><img src="<?=$url?>/<?=$child_val['image']?>"></div>
							<div class="node-name"> </div>
						</div>
					<? else: ?>
						<a href="?partner=<?=$child_name?>" class="node-content <?=$child_val['color']?>">
							<div class="img"><img src="<?=$url?>/<?=$child_val['image']?>"></div>
							<div class="node-name"><?=$child_name?></div>
							<div class="node-contact"><?=$child_val["contact"]?></div>
						</a>
					<? endif; ?>
				</td>
			<? endforeach; ?>
		</tr>
		<tr>
			<? foreach($val["children"] as $child_name=>$child_val): ?>
				<? foreach($child_val["children"] as $two_child_name=>$two_child_val): ?>
					<td>
						<? if(stripos($two_child_name, "NULL_") === 0): ?>
							<div class="node-content <?=$child_val['color']?>">
								<div class="img max-h-40px"><img src="<?=$url?>/<?=$child_val['image']?>"></div>
								<div class="node-name"> </div>
							</div>
						<? else: ?>
							<a href="?partner=<?=$two_child_name?>" class="node-content <?=$two_child_val['color']?>">
								<div class="img"><img src="<?=$url?>/<?=$two_child_val['image']?>"></div>
								<div class="node-name"><?=$two_child_name?></div>
								<div class="node-contact"><?=$two_child_val["contact"]?></div>
							</a>
						<? endif; ?>
					</td>
				<? endforeach; ?>
			<? endforeach; ?>
		</tr>
	<? endforeach; ?>
</table>
<a href="all_sponsors.php?username=<?=$row['login']?>&table=<?=$m?>" class="badge badge-success sponsors">Просмотр всего дерева</a>
				</div>
			</div>
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
