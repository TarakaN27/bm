<?php
#ini_set('error_reporting', E_ALL);
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);

session_start();
include("../db_connect.php");
include "../smsc_api.php";
include("../b_func.php");

include("functions.php");
include("inf-functions.php");

if(isset($_POST["disable-fast-start"]) && in_array($_POST["status"], [0,1])) {
	save("UPDATE `options` SET value='".$_POST["status"]."' WHERE `name`='bonus-faststart'");
	header("Location: #");
}

date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	$fio = $row["fio"];
	$upgrade = false;
	if($row["infinity_package"]>0) {
		$upgrade = true;
		$package = checkMyTable($row["id"]); #(str)"infinity1"
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="../assets/js/raphael.js"></script>
<script src="../assets/js/Treant.js"></script>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/jquery.easing.js"></script>
        <div class="content infinity mt-3">
            <div class="animated fadeIn">

				<div class="col-lg-12" style="text-align: center">
					<h1 class="title">Пожалуйста, выберите пакет...</h1>
					<?php
					
					
					$pack_disabled = array_fill(0, count($packages), "");
					$price_sum = 0;
					if ($upgrade != false){
						$packages_temp = $packages;
						for($i=$row["infinity_package"]-1; $i>=0; $i--) {
							$pack_disabled[$i] = "disabled";
						}
						for($i=0; $i<count($packages); $i++) {							
							$packages[$i]["price"] = $i>=$row["infinity_package"] ? $packages_temp[$i]["price"] - $packages_temp[$row["infinity_package"]-1]["price"]: 0;		
						}
					}
					?>
					<div class="row packages">
					<? foreach($packages as $id=>$pack): ?>
						<div class="card btn btn-success <?=$pack_disabled[$id]?>" data-id="<?=$id+1?>" data-name="<?=$pack["name"]?>" data-price="<?=$pack["price"]?>">
							<div class="card-header">
								<div class="card-title">Пакет: <?=$pack["name"]?></div>
							</div>
							<div class="card-body">
								<p>Стоимость: <?=$pack["price"]?> Тг.</p>
							</div>
						</div>
					<? endforeach; ?>
					</div>
					
					
					<div class="col-lg-6" style="text-align: center;float: none;margin:auto">
						<div class="table package-block d-none">
							<div class="col-6">
								<p class="head">Название пакета</p>
								<p class="body package-name"></p>
							</div>
							<div class="col-6">
								<p class="head">Стоимость</p>
								<p class="body package-price"></p>
							</div>
						
						</div>
						<? if ($upgrade == false): ?>
						<form method="post" class="add-infinity disabled">
							<h1 class="title">Выберите вид оплаты</h1>
							<div class="row-group type-pay">
								<input type="radio" id="type_balans" name="type_pay" value="balans" checked>
								<label for="type-balans">Баланс</label>
							</div>
							<div class="row-group type-pay">
								<input type="radio" id="type_bonus" name="type_pay" value="bonus">
								<label for="type-bonus">Бонусы</label>
							</div>
							<div class="row-group">
								<button type="submit" disabled class="btn btn-primary mb-3 w-100" value="1">Купить пакет</button>
							</div>
							<div class="err-msg mb-3"></div>
							<h1 class="desc-title">Необязательные поля для заполнения</h1>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Лидер *</span>
								</div>
								<input type="text" class="form-control" name="leader">
								<button class="btn btn-primary" id="leader_username_check" type="button">Проверить</button>
							</div>
							<div class="msg-leader mb-3"></div>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Наставник</span>
								</div>
								<input type="text" class="form-control" name="teacher">
								<button class="btn btn-primary" id="teacher_username_check" type="button">Проверить</button>
							</div>
							<div class="msg-teacher mb-3"></div>
						</form>
						
						<? else: ?>
						
						<form method="post" class="upgrade-infinity">
							<div class="row-group">
								<button type="submit" disabled class="btn btn-primary mb-3 w-100" value="1">Улучшить пакет</button>
							</div>
							<div class="err-msg mb-3"></div>
						</form>						
						<? endif; ?>
						
					</div>
					
			</div>
				
				<? if($_SESSION["login"] == "BoomMarket"): ?>
				<div class="col-lg-12 mt-10" style="box-shadow: 0 0 10px 0px #cdcdcd;">
					<div class="card card-custom">
						<div class="card-header">
							<strong class="card-title">
								Настройки
							</strong>
						</div>
						<div class="card-body">
							<form method="post">
								<div class="row-group">
									<label>Бонус "Быстрый старт"</label>
									<div class="input-group">
										<?
											$selected = ["",""];
											$selected[$options["bonus-faststart"]] = "selected";
										?>
										<select name="status" class="form-control">
											<option <?=$selected[0]?> value="0">Отключено</option>
											<option <?=$selected[1]?> value="1">Включено</option>
										</select>
										<input type="submit" class="btn btn-primary" name="disable-fast-start" value="Сохранить">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<? endif; ?>
				
			
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
</body>
</html>
