<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
include("b_func.php");
date_default_timezone_set('Asia/Almaty');

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	$fio = $row["fio"];
	if (isset($_GET['sub_m1'])) {
		$amount = 1100000;
		$user_id = $row['login'];
		$sponsor_login = $row['sponsor'];
		$sponsor = array();
		//$index = array();
		//$r = 0;
		$flag_m = false;
		$flag = false;
		//while (!$flag_m) {
		//	$r++;
			while ($flag == false) {
				$res = mysql_query("select * from m5 where user_login='".$sponsor_login."'");
				//$res_m = mysql_query("select * from m2 where sponsor_login='".$sponsor_login."' and type=".$k);
				if (mysql_num_rows($res) > 0) {
					$flag = true;
				}
				else {
					$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
					$sponsor_login = mysql_result($res_x, 0);
				}
			}
				
				$pay_sum = $_GET["pay_type"]=="balans"? $row['akwa']: $row["balans_turbo"];
					if (($pay_sum - $amount) >= 0) {
					$row_s = mysql_fetch_array($result_s);
					$i = 0; 
					$n = 1; 
					$sponsor[0] = $sponsor_login;
					//$index[0] = $row_s['id'];
					while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
						$result_x = mysql_query("select * from m5 where sponsor_login='".$sponsor[$i]."'");
						//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
						if (mysql_num_rows($result_x)<2) {	
							$res_xyz = mysql_query("select user_id from m5 where user_login='".$row['login']."'");
							if (mysql_num_rows($res_xyz) == 0) {
							mysql_query("insert into m5 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$row['id'].", '".$row['login']."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '1')");
								
							$buy_package = 6;
							$promotion = mysql_result(mysql_query("SELECT * FROM `options` WHERE `name`='promotion'"), 0, "value");			
							mysql_query("INSERT INTO `buy_packages` SET `user_id`='".$row['id']."', `package`='".$buy_package."', `date`='".date("Y-m-d H:i:s")."', `promotion`='".$promotion."'");
								
							mysql_query("update users set akwa=akwa+00000 where login='".$sponsor[$i]."'");	
							if($_GET["pay_type"]=="balans")	{
	mysql_query("update users set akwa=akwa-1100000, status=6, isStatusPaid=1 where login='".$row['login']."'");
} else {
	mysql_query("update users set balans_turbo=balans_turbo-1100000, status=6, isStatusPaid=1 where login='".$row['login']."'");
}
							mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 00000, '".$row['login']."', 'Silver Level 5', '".date('Y-m-d H:i:s')."')");
							$flag_m = true;
							$res_m = mysql_query("select * from m5 where user_login='".$sponsor[$i]."'");
							if (mysql_num_rows($res_m)>0) {
								$row_m = mysql_fetch_array($res_m);
								$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
								$check_block = mysql_fetch_array($check_block);
								if($check_block['block_bonus'] == 0) {
									mysql_query("update users set akwa=akwa+0000 where login='".$row_m['sponsor_login']."'");
									mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 0000, '".$row['login']."', 'SILVER Level 5', '".date('Y-m-d H:i:s')."')");
									
								}
								check_binary_5($row_m['sponsor_login']);
							}							
							echo mysql_error();
							for ($i=0;$i<50;$i++) {
							$flag_k = false;
							$lot_no = 0;
							while (!$flag_k) {
								$lot_no = rand(1000000, 9999999);
								$res_k = mysql_query("select user_id from konkurs where lot_no=".$lot_no);
								if (mysql_num_rows($res_k) == 0) $flag_k = true;
							}
							mysql_query("insert into konkurs ( user_id, lot_no, status, post_time, upd_time ) values ( ".$row['id'].", ".$lot_no.", 0, '".date('Y-m-d H:i:s')."', '0000-00-00 00:00:00' )");
							}
							}
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
                                ???????????????????????? ??????????????. ????????????????????, ?????????????????? ???????? ????????.
                            </div>';
			
		//}
	}

    if (isset($_POST['sub_search'])) {
		$login = mysql_escape_string($_POST['partner']);
		$result_s = mysql_query("select * from users where login='".$login."' and id>".$row['id']);
		if (mysql_num_rows($result_s) > 0 && $login != 'admin') {
			$row = mysql_fetch_array($result_s);	
		}
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
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>????????????</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">			
					
					<div class="col-lg-12" style="text-align: center">
						<?php
					if ($row['status'] <= 5) {
					?>
					<a href="#" data-bs-toggle="modal" data-bs-target="#buyTypeMarketing" class="btn btn-light"> <i class="menu-icon fa fa-sitemap " style="font-size:18px;"></i>???????? 1100000 ????</a><br><br>

					<?php } ?>
					<div class="col-lg-4 m-auto" style="text-align: center">
						<form method="post" action="b_5.php">
							<div class="input-group">
								<input type="text" class="form-control" placeholder="?????????? ????????????????" name="partner">
								<input type="submit" class="form-control" value="??????????" name="sub_search" class="btn btn-success">
							</div>
						</form>	
					</div>
					
					
					<span class="badge badge-success mt-2">SILVER Level 5</span>
					<div class="chart01" id="collapsable-example1" style="color: #111;"></div>

<?php
$m = "m5";
$msg_phone = "?????????? ??????????";
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
							<div class="node-name">??</div>
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
								<div class="node-name">??</div>
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
<a href="all_sponsors.php?username=<?=$row['login']?>&table=<?=$m?>" class="badge badge-success sponsors">????????????????????????????</a>
					</div>
				</div>
			</div>
		</div><!-- .animated -->
	</div><!-- .content -->
	
<? include("footer.php"); ?>