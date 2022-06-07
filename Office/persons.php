<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
include("b_func.php");

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result)!=0 && ($_SESSION['login']=='BoomMarket' || $_SESSION['admin_login']=='BoomMarket')) {
	$row = mysql_fetch_array($result);
	$_SESSION["id"] = $row["id"];
	if (isset($_POST['sub_btn'])) $st = $_POST['f_search']; else $st = "";
}
else {
	header("Location: ../index.php");
	die();
}

if($_GET["action"]=="inlogin" && $_GET["edit-id"]) {
	$result = mysql_query("select * from users where id='".$_GET["edit-id"]."'");
	$login = mysql_fetch_array($result);
	if(mysql_num_rows($result)>0){
		$_SESSION['admin_login'] = $_SESSION['login'];
		$_SESSION['login'] = $login["login"];
		$_SESSION['id'] = $login["id"];
		echo '<script>window.location.href="/Office/"</script>';
	}
}


if($_GET["action"]=="clear-history" && $_GET["id"]) {
	$get_info = mysql_query("select * from users where BINARY id='".$_GET["id"]."'");
	$get_info = mysql_fetch_assoc($get_info);
	mysql_query("DELETE FROM `konkurs` WHERE `user_id`='".$_GET["id"]."'");
	mysql_query("DELETE FROM `int_transfer` WHERE `sender`='".$get_info["login"]."' OR `receiver`='".$get_info["login"]."'");
	mysql_query("DELETE FROM `transfer` WHERE `login`='".$get_info["login"]."' OR `user_login`='".$get_info["login"]."'");
	mysql_query("DELETE FROM `vyvod` WHERE `login`='".$get_info["login"]."'");
	
	mysql_query("INSERT INTO `admin_history` (`admin_id`, `msg`, `date`, `type`, `args`) VALUES ('".$_SESSION["id"]."', 'Очистка истории аккаунта @".$get_info["login"]."', '".date("Y-m-d H:i:s")."', 'clear-history-profile', '0')");
}

if($_GET["change-fio"] && $_GET["change-psw"] && $_GET["change-balans"]>= 0 && $_GET["change-phone"]) {
	if($_GET["block_bonus"] == 1) {
		$block_bonus = 1;
	} else {
		$block_bonus = 0;
	}
	
	$get_info = mysql_query("select * from users where BINARY id='".$_GET["change-profile"]."'");
	$get_info = mysql_fetch_assoc($get_info);
	
	$query_change = mysql_query("UPDATE `users` SET `fio`='".$_GET["change-fio"]."', `pass`='".$_GET["change-psw"]."', `akwa`='".$_GET["change-balans"]."', `phone`='".$_GET["change-phone"]."', `block_bonus`='".$block_bonus."' WHERE `id`='".$_GET["change-profile"]."'");
	
	$change_info = [];
	if($get_info["fio"]!=$_GET["change-fio"]) $change_info[] = "имя";
	if($get_info["pass"]!=$_GET["change-psw"]) $change_info[] = "пароль";
	if($get_info["akwa"]!=$_GET["change-balans"]) $change_info[] = "баланс";
	if($get_info["phone"]!=$_GET["change-phone"]) $change_info[] = "телефон";
	if($get_info["block_bonus"]!=$block_bonus) $change_info[] = "блок.бонусов";
	
	$change_info = implode(", ", $change_info);
	mysql_query("INSERT INTO `admin_history` (`admin_id`, `msg`, `date`, `type`, `args`) VALUES ('".$_SESSION["id"]."', 'Изменение информации @".$get_info["login"]." (".$change_info.")', '".date("Y-m-d H:i:s")."', 'clear-history-profile', '0')");
	
	echo '<script>window.location.href = "?action=edit&edit-id='.$_GET["change-profile"].'"</script>';
}

if($_GET["change-id"] && (array_key_exists($_GET["table"], $levels) || array_key_exists($_GET["table"], $multi))) {
	$table = $_GET["table"];
	$user_id = $_GET["change-id"];
	$rows = mysql_query("select * from users where id='".$_GET["change-id"]."'");
	$rows = mysql_fetch_array($rows);
	$sponsor_login = $rows['sponsor'];	
	$s_result = mysql_query("select * from users where login='".$sponsor_login."'");
	$s_res = mysql_fetch_array($s_result);	
	$sponsor_id = $s_res["id"];
	$ids = [];
	function getSponsor($s_login){
		global $table;
		$sponsor_count_query = mysql_query("select * from `".$table."` WHERE sponsor_login='".$s_login."'");
		$sponsor_count = mysql_num_rows($sponsor_count_query);
		if($sponsor_count == 0) {
			$s_result = mysql_query("select sponsor from users where login='".$s_login."'");
			$s_res = mysql_fetch_array($s_result);
			getSponsor($s_res["sponsor"]);
		} else {
			return $s_login;
		}
	}
	$sponsor_out = getSponsor($sponsor_login);
	$tempSponsors = [];
	$tempSponsors[] = "'".$sponsor_out."'";
	$arr = [];
	function getAdd(){
		global $tempSponsors;
		global $table;
		global $arr;
		$name = implode(",", $tempSponsors);
		$tempSponsors = [];
		$sponsor_query = mysql_query("select * from `".$table."` WHERE `sponsor_login` IN (".$name.")");	
		while($row = mysql_fetch_array($sponsor_query)){
			$tempSponsors[] = "'".$row["user_login"]."'";
			$check_query = mysql_query("select * from `".$table."` WHERE `sponsor_login`='".$row["user_login"]."'");
			
			if(mysql_num_rows($sponsor_query) < 2) {
				$arr[] = ["name"=>$row["sponsor_login"], "count"=>mysql_num_rows($sponsor_query)];
				return $arr;
			} else {
				if(mysql_num_rows($check_query) < 2) {
					$arr[] = ["name"=>$row["user_login"], "count"=>mysql_num_rows($check_query)];
					return $arr;
				}
			}
		}
		getAdd();
	}
	$sponsor_add = getAdd();
	$check_query = mysql_query("select * from `".$table."` WHERE BINARY `user_login`='".$rows["login"]."'");
	if(mysql_num_rows($check_query) == 0) {
		mysql_query("insert into ".$table." (user_id, user_login, sponsor_login, type, post_time, pay) values (".$rows['id'].", '".$rows['login']."', '".$arr[0]["name"]."', ".($arr[0]["count"]+1).", '".date('Y-m-d H:i:s')."', '2')");	
		
		mysql_query("INSERT INTO `admin_history` (`admin_id`, `msg`, `date`, `type`, `args`) VALUES ('".$_SESSION["id"]."', 'Добавление уровня для @".$rows['login']." (".$table.")', '".date("Y-m-d H:i:s")."', 'add-level', '0')");
		
		echo '<script>window.location.href = "?action=edit&edit-id='.$_GET["change-id"].'"</script>';		
	} else {
		echo '<script>alert("Этот человек уже есть на данном уровне");</script>';
	}
}


if($_GET["change-id"] && array_key_exists($_GET["table"], $infinity)) {	
	$change_id = $_GET["change-id"];
	$teacher = isset($_GET["teacher"]) ? $_GET["teacher"]: "";
	$leader = isset($_GET["leader"]) ? $_GET["leader"]: "";
	$package = substr($_GET["table"], -1);
	$hash = md5("BoomMarket");
	$back_url = $_GET["back-url"];
	
	header("Location: /Office/infinity/admin-add-infinity.php?change-id=".$change_id."&teacher=".$teacher."&leader=".$leader."&package=".$package."&hash=".$hash."&back-url2=".$back_url);
}

if($_GET["change-id"] && array_key_exists($_GET["table"], $turbo_levels)) {	
	$change_id = $_GET["change-id"];
	$level = $_GET["table"];
	$hash = md5("BoomMarket");
	$back_url = $_GET["back-url"];
	
	header("Location: /Office/turboboom/admin-add-turbo.php?change-id=".$change_id."&level=".$level."&hash=".$hash."&back-url2=".$back_url);
}

$pay_type = ["Переход", "Куплен", "Активирован"];

include("header.php");
?>
        <div class="content mt-3 person">
            <div class="animated fadeIn">
                <div class="row">
					
                    <div class="col-md-12">
                        <? if($_GET["action"] && $_GET["action"] == "edit" && $_GET["edit-id"]): ?>
							<?
								$person_query = mysql_query("select * from users where id='".$_GET["edit-id"]."'");
								$person = mysql_fetch_array($person_query);
						
								$back_url = isset($_GET["back-url"]) ? urldecode($_GET["back-url"]): "/Office/persons.php";
							?>
						
							<div class="card card-custom">
								<div class="card-header">
									<strong class="card-title">Редактирование <?=$person["login"]?></strong>
									<div>
										<a href="<?=$back_url?>" class="btn btn-light inlogin">Назад</a>
										<a href="?action=inlogin&edit-id=<?=$_GET["edit-id"]?>" class="btn btn-light inlogin">Войти под логином</a>
									</div>
								</div>
								<div class="card-body">
									<form method="get">
										<div class="row-group mt-2">
											<label>ФИО</label>
											<input type="text" name="change-fio" class="form-control" value="<?=$person['fio']?>">
										</div>
										<div class="row-group mt-2">
											<label>Пароль</label>
											<input type="text" name="change-psw" class="form-control" value="<?=$person['pass']?>">
										</div>
										<div class="row-group mt-2">
											<label>Баланс</label>
											<input type="text" name="change-balans" class="form-control" value="<?=$person['akwa']?>">
										</div>
										<div class="row-group mt-2">
											<label>Телефон</label>
											<input type="text" name="change-phone" class="form-control" value="<?=$person['phone']?>">
										</div>
										<div class="row-group check mt-5 mb-5">
											<label>Заблокировать бонусы</label>
											<input type="checkbox" name="block_bonus" class="input-checkbox" <?if($person["block_bonus"] == 1){echo 'checked';}?> value="1">
										</div>
										<div class="input-group mt-2">
											<input type="hidden" name="change-profile" class="form-control" value="<?=$_GET["edit-id"]?>">
											<input type="submit" name="sub_btn" value="Изменить" class="btn btn-light mr-2">
											<a href="?action=clear-history&id=<?=$_GET["edit-id"]?>" class="btn btn-danger">Очистить историю</a>
										</div>
									</form>
									<p>Добавить уровень</p>
									<form method="get" id="change-level">
										<div class="input-group row">
											<select name="table" required class="form-control">
												<option disabled class="bold">Основной маркетинг</option>
												<? foreach($levels as $id=>$level) :?>
												<?
												$query_count = mysql_query("select id from ".$id." WHERE user_id='".$_GET["edit-id"]."'");
												$count = mysql_num_rows($query_count);
												?>
														<option <?if($count>0){echo "disabled class='false'";}?> value="<?=$id?>"><?=$level?></option>
												<? endforeach; ?>
												<option disabled class="bold">Multi-Boom</option>
												<? foreach($multi as $id=>$level) :?>
												<?
												$query_count = mysql_query("select id from ".$id." WHERE user_id='".$_GET["edit-id"]."'");
												$count = mysql_num_rows($query_count);
												?>
													<option <?if($count>0){echo "disabled class='false'";}?> value="<?=$id?>"><?=$level?></option>
												<? endforeach; ?>
												
												<option disabled class="bold">Infinity</option>
												<? foreach($infinity as $id=>$level) :?>
												<?
												$query_count = mysql_query("select id from ".$id." WHERE user_id='".$_GET["edit-id"]."'");
												$count = mysql_num_rows($query_count);
												?>
													<option <?if($count>0){echo "disabled class='false'";}?> value="<?=$id?>"><?=$level?></option>
												<? endforeach; ?>
												
												<option disabled class="bold">Turbo Boom</option>
												<? foreach($turbo_levels as $level=>$row) :?>
														<?
														$query_count = mysql_query("select id from ".$row["table"]." WHERE user_id='".$_GET["edit-id"]."' AND level='".$row["level"]."'");
														$count = mysql_num_rows($query_count);
														?>
															<option <?if($count>0){echo "disabled class='false'";}?> value="<?=$level?>">Level <?=$level?></option>
												<? endforeach; ?>

											</select>
											<input type="hidden" name="change-id" class="form-control" value="<?=$_GET["edit-id"]?>">
											<input type="hidden" name="back-url" class="form-control" value="<?=$back_url?>">
											<input type="submit" name="sub_btn" value="Добавить" class="btn btn-light">
										</div>
										
										<div class="input-group row teacher mt-2 d-none">
											<input type="text" name="leader" value="" placeholder="Лидер" class="form-control">
											<button class="btn btn-light" id="leader_username_check" type="button">Проверить</button>
										</div>
										<div class="msg-leader mb-3"></div>
										<div class="input-group row leader mt-2 d-none">
											<input type="text" name="teacher" value="" placeholder="Наставник" class="form-control">
											<button class="btn btn-light" id="teacher_username_check" type="button">Проверить</button>
										</div>
										<div class="msg-teacher mb-3"></div>
									</form>
								</div>
							</div>	
						
							<h1 class="title">Основной маркетинг:</h1>
							<div class="row levels">
								<? foreach($levels as $level=>$text): ?>
									<?
										$query_count = mysql_query("select * from ".$level." WHERE user_id='".$_GET["edit-id"]."'");
										$count = mysql_num_rows($query_count);
										$query = mysql_fetch_assoc($query_count);
									?>
									<div class="col-xl-3" style="text-align: center">
										<div class="card card-custom card_counts <?if($count>0){echo "badge-success";} else {echo "badge-danger";}?>">
											<div class="card-header d-flex flex-wrap justify-content-between">
												<strong class="card-title"><?=$text?></strong>
												<div>
													<p class="card-title"><?=$query["post_time"]?></p>
													<p class="card-title"><?=$pay_type[$query["pay"]]?></p>
												</div>
												<?/*if($count>0){
													echo "<a class='but' title='Удалить' href='?action=remove&remove-id=".$_GET["edit-id"]."'><i class='fa fa-times'></i></a>";
												} else {
													echo "<a class='but' title='Добавить' href=''><i class='fa fa-check'></i></a>";
												}*/?>
											</div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
							<h1 class="title">Multi Boom:</h1>
							<div class="row levels">
								<? foreach($multi as $level=>$text): ?>
									<?
										$query_count = mysql_query("select * from ".$level." WHERE user_id='".$_GET["edit-id"]."'");
										$count = mysql_num_rows($query_count);
										$query = mysql_fetch_assoc($query_count);
									?>
									<div class="col-xl-3" style="text-align: center">
										<div class="card card-custom card_counts <?if($count>0){echo "badge-success";} else {echo "badge-danger";}?>">
											<div class="card-header d-flex flex-wrap justify-content-between">
												<strong class="card-title"><?=$text?></strong>
												<div>
													<p class="card-title"><?=$query["post_time"]?></p>
													<p class="card-title"><?=$pay_type[$query["pay"]]?></p>
												</div>
												<? /*if($count>0){
													echo "<a class='but' title='Удалить' href='?action=remove&remove-id=".$_GET["edit-id"]."'><i class='fa fa-times'></i></a>";
												} else {
													echo "<a class='but' title='Добавить' href=''><i class='fa fa-check'></i></a>";
												} */?>
											</div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
						
							<h1 class="title">Infinity:</h1>
							<div class="row levels">
								<? foreach($infinity as $level=>$text): ?>
									<?
										$query_count = mysql_query("select * from ".$level." WHERE user_id='".$_GET["edit-id"]."'");
										$count = mysql_num_rows($query_count);
										$query = mysql_fetch_assoc($query_count);
									?>
									<div class="col-xl-3" style="text-align: center">
										<div class="card card-custom card_counts <?if($count>0){echo "badge-success";} else {echo "badge-danger";}?>">
											<div class="card-header d-flex flex-wrap justify-content-between">
												<strong class="card-title"><?=$text?></strong>
												<div>
													<p class="card-title"><?=$query["post_time"]?></p>
													<p class="card-title"><?=$pay_type[$query["pay"]]?></p>
												</div>
												<? /*if($count>0){
													echo "<a class='but' title='Удалить' href='?action=remove&remove-id=".$_GET["edit-id"]."'><i class='fa fa-times'></i></a>";
												} else {
													echo "<a class='but' title='Добавить' href=''><i class='fa fa-check'></i></a>";
												} */?>
											</div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
						
							<h1 class="title">Turbo Boom:</h1>
							<div class="row levels">
								<? foreach($turbo_levels as $level=>$row): ?>
									<?
										$query_count = mysql_query("select * from ".$row["table"]." WHERE user_id='".$_GET["edit-id"]."' AND level='".$row["level"]."'");
										$count = mysql_num_rows($query_count);
										$query = mysql_fetch_assoc($query_count);
									?>
									<div class="col-xl-3" style="text-align: center">
										<div class="card card-custom card_counts <?if($count>0){echo "badge-success";} else {echo "badge-danger";}?>">
											<div class="card-header d-flex flex-wrap justify-content-between">
												<strong class="card-title">Level <?=$level?></strong>
												<div>
													<p class="card-title"><?=$query["date"]?></p>
												</div>
											</div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
						<? endif;?>
						</div>
						
						<? if(!$_GET["action"]): ?>
						<div class="col-md-12">
						 <div class="card card-custom">
                            <div class="card-header">
                                <strong class="card-title">Поиск пользователя</strong>
                            </div>
                            <div class="card-body">
								<form method="post">
									<div class="input-group">
										<input type="text" name="f_search" class="form-control" palceholder="Введите логин пользователя" value="<?=$st?>">
										<input type="submit" name="sub_btn" value="Поиск" class="btn btn-light">
									</div>
								</form>
								<?= ($message2!="")?$message2:""; ?>
								<div class="table-scroll">
									<table class="table table-hover table-head-custom mw-380">
										<thead>
											<tr>
												<th>ID</th>											
												<th>Логин</th>
												<th>Пароль</th>
												<th>Спонсор</th>
												<th>ФИО</th>
												<th>Телефон</th>
												<th>Дата</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php
											if (strlen($st) > 0) {
												$result2 = mysql_query("select * from users where id<>1 and login like '".$st."%' order by id asc");
												if (mysql_num_rows($result2)>0) {
													while ($row2 = mysql_fetch_array($result2)) {
														echo '<tr class="href" data-href="?action=edit&edit-id='.$row2['id'].'">';
														echo '<td>'.$row2['id'].'</td>';
														echo '<td>'.$row2['login'].'</td>';
														echo '<td>'.$row2['pass'].'</td>';
														echo '<td>'.$row2['sponsor'].'</td>';
														echo '<td>'.$row2['fio'].'</td>';
														echo '<td>'.$row2['phone'].'</td>';
														echo '<td>'.$row2['reg_time'].'</td>';
														echo '</tr>';
													}

												} else { echo '<tr><td colspan="7">Нет пользователя</td></tr>'; }
											}
											?>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
							</div>
						<? endif;?>
                    
					
					<? if(!$_GET): ?>
						<div class="col-lg-12" style="text-align: center">
							<h1 class="title">Основной маркетинг:</h1>
							<div class="row center">
								<? foreach($levels as $level=>$text): ?>
									<?
										$query_count = mysql_query("select id from ".$level);
										$count = mysql_num_rows($query_count);
									?>
									<div style="text-align: center;margin: 0 5px;width: 130px;">
										<div class="card card-custom card_count">
											<div class="card-header">
												<strong class="card-title"><?=$text?></strong>
											</div>
											<div class="card-body">
												<p class="count"><?=$count?></p>
												<a href="/Office/history_buy_level.php?action=level&table=<?=$level?>" class="btn btn-light details">Просмотр</a>
											</div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
							<h1 class="title">Multi Boom:</h1>
							<div class="row center">
								<? foreach($multi as $level=>$text): ?>
									<?
										$query_count = mysql_query("select id from ".$level);
										$count = mysql_num_rows($query_count);
									?>
									<div style="text-align: center;margin: 0 5px;width: 130px;">
										<div class="card card-custom card_count">
											<div class="card-header">
												<strong class="card-title"><?=$text?></strong>
											</div>
											<div class="card-body">
												<p class="count"><?=$count?></p>
												<a href="/Office/history_buy_level.php?action=level&table=<?=$level?>" class="btn btn-light details">Просмотр</a>
											</div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
							<h1 class="title">Infinity:</h1>
							<div class="row center">
								<? foreach($infinity as $level=>$text): ?>
									<?
										$query_count = mysql_query("select id from ".$level);
										$count = mysql_num_rows($query_count);
									?>
									<div style="text-align: center;margin: 0 5px;width: 130px;">
										<div class="card card-custom card_count">
											<div class="card-header">
												<strong class="card-title"><?=$text?></strong>
											</div>
											<div class="card-body">
												<p class="count"><?=$count?></p>
												<a href="/Office/history_buy_level.php?action=level&table=<?=$level?>" class="btn btn-light details">Просмотр</a>
											</div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
							<h1 class="title">Turbo Boom:</h1>
							<div class="row center">
								<? foreach($turbo_levels as $level=>$row): ?>
									<?
										$query_count = mysql_query("select id from `".$row["table"]."` WHERE `level`='".$row['level']."'");
										$count = mysql_num_rows($query_count);
									?>
									<div style="text-align: center;margin: 0 5px;width: 130px;">
										<div class="card card-custom card_count">
											<div class="card-header">
												<strong class="card-title">Level <?=$level?></strong>
											</div>
											<div class="card-body">
												<p class="count"><?=$count?></p>
												<a href="/Office/history_buy_level.php?action=level&table=<?=$row['table']?>&level=<?=$row["level"]?>" class="btn btn-light details">Просмотр</a>
											</div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
						</div>

						<? endif;?>
					


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

