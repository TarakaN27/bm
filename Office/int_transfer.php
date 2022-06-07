<?php

#ini_set('error_reporting', E_ALL);
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);

session_start();

include("db_connect.php");
include "smsc_api.php";
include("b_func.php");


$flag = false;
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");
if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);	
	$fio = $row["fio"];
	date_default_timezone_set('Asia/Almaty');
	
	if($_GET["action"]=="inlogin" && $_GET["edit-id"]) {
		$result = mysql_query("select * from users where id='".$_GET["edit-id"]."'");
		$login = mysql_fetch_array($result);
		if(mysql_num_rows($result)>0){
			$_SESSION['admin_login'] = $_SESSION['login'];
			$_SESSION['login'] = $login["login"];
			$_SESSION['id'] = $login["id"];
			$_SESSION['back_url'] = "/Office/int_transfer.php";
			echo '<script>window.location.href="/Office/"</script>';
		}
	}
	
}
else {
	header("Location: index.php");
	die();
}

include('header.php');
?>
<script>
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Внутренний перевод</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">
				
				<div class="row">
					<div class="col-lg-6">
						<div class="card">
							<div class="card-header">
								<strong class="card-title">Мой баланс: <?=$row["akwa"]?> Тг.</strong>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-6">
						<div class="card">
							<div class="card-header">
								<strong class="card-title">Внутренний перевод</strong>
							</div>
							<div class="card-body">
								<!-- Credit Card -->
								<div id="pay-invoice">
									<div id="perevody">
										<div id="perevod_block">				
											<div class="input-group mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">Логин</span>
												</div>
												<input id="username-perevod" type="text" class="form-control" placeholder="Введите логин">
												<div class="input-group-append">
													<button class="btn btn-light copyfn" id="perevod_username_check" type="button">Проверить</button>
												</div>
											</div>
											<div id="perevod_username_check_msg" class=""></div>
											<div class="input-group mt-4 mb-1">
												<div class="input-group-prepend">
													<span class="input-group-text">Сумма</span>
												</div>
												<input id="summa-perevod" type="number" class="form-control" placeholder="Введите сумму" min="1" max="<?php echo $_SESSION['user']['balans']; ?>" step="1">
												<div class="input-group-append">
													<span class="input-group-text">Тг.</span>
												</div>
											</div>
											<div id="perevod_summa_msg" class="text-danger"></div>
											<div id="perevod_finish" class="mt-4">
												<input type="button" id="perevesti" disabled class="btn btn-light" value="Продолжить">
											</div>
										</div>
									</div>
								</div>						

							</div>
						</div> <!-- .card -->
					</div>
				</div>
				
				
<? if($_SESSION["login"] === "BoomMarket"): ?>
	<?
		if($_GET["save_options"]) {
			$min_balans_vyvod = mysql_query("UPDATE options SET value='".$_GET["option_min_balans_vyvod"]."' WHERE name='min_balans_vyvod'");
			$max_balans_vyvod = mysql_query("UPDATE options SET value='".$_GET["option_max_balans_vyvod"]."' WHERE name='max_balans_vyvod'");
			mysql_query("INSERT INTO `admin_history` (`admin_id`, `msg`, `date`, `type`, `args`) VALUES ('".$_SESSION["id"]."', 'Изменение лимитов на вывод средств', '".date("Y-m-d H:i:s")."', 'change-limit-vyvod', '0')");
			echo '<script>window.location.href="/Office/int_transfer.php"</script>';
		}
				
		if(isset($_POST["profile"]) && $_POST["action"] = "add-profile") {
			$login = $_POST["profile"];
			mysql_query("UPDATE users SET favorite=1 WHERE login='".$login."'");
			echo "<script>window.location.href='/Office/int_transfer.php'</script>";
		}
				
		if(isset($_GET["profile"]) && $_GET["action"] = "remove-profile") {
			$id = $_GET["profile"];
			mysql_query("UPDATE users SET favorite=0 WHERE id='".$id."'");
			echo "<script>window.location.href='/Office/int_transfer.php'</script>";
		}
				
		if(isset($_POST["akwa"]) && $_POST["action"] = "save-balans" && isset($_POST["edit-id"])) {
			mysql_query("UPDATE users SET akwa='".$_POST["akwa"]."' WHERE id='".$_POST["edit-id"]."'");
			$query = mysql_query("SELECT * FROM users WHERE id='".$_POST["edit-id"]."'");
			$res = mysql_fetch_assoc($query); 
			mysql_query("INSERT INTO admin_history (admin_id, msg, date, type, args) VALUES ('".$_SESSION["id"]."', 'Изменение баланса для @".$res["login"]." (".$_POST["akwa"].")', '".date("Y-m-d H:i:s")."', 'change-balans', '0')");
			echo "<script>window.location.href='/Office/int_transfer.php'</script>";
		}
		if(isset($_POST["disable-fast-start"]) && in_array($_POST["status"], [0,1])) {
			mysql_query("UPDATE `options` SET value='".$_POST["status"]."' WHERE `name`='bonus-faststart'");
			echo "<script>window.location.href='/Office/int_transfer.php'</script>";
		}
	?>
				<div class="row">
					<div class="col-12" style="text-align: center">
						<div class="card card-custom options" style="text-align: center;">
							<div class="card-header">
								<strong class="card-title">Настройки</strong>
							</div>
							<div class="card-body">
								<h4>Лимиты вывода средств</h4>
								<hr>
								<form method="get">
									<input type="hidden" name="save_options" value="1">
									<div class="form-group">
										<label>Min:</label>
										<input type="number" class="form-control" required min="0" value="<?=$general_options["min_balans_vyvod"]?>" name="option_min_balans_vyvod">
									</div>
									<div class="form-group">
										<label>Max:</label>
										<input type="number" class="form-control" required min="0" value="<?=$general_options["max_balans_vyvod"]?>" name="option_max_balans_vyvod">
									</div>
									<div class="form-group">
										<input type="submit" class="form-control btn btn-light" value="Сохранить">
									</div>
								</form>
								
								<h4>Бонус "Быстрый старт"</h4>
								<hr>
								<form method="post">
									<div class="input-group">
										<?
											$selected = ["",""];
											$selected[$general_options["bonus-faststart"]] = "selected";
										?>
										<select name="status" class="form-control">
											<option <?=$selected[0]?> value="0">Отключено</option>
											<option <?=$selected[1]?> value="1">Включено</option>
										</select>
										<div class="form-group">
											<input type="submit" class="form-control btn btn-light" name="disable-fast-start" value="Сохранить">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="row">
					<div class="col-lg-12">
						<div class="card card-custom options">
							<div class="card-header">
								<strong class="card-title">Избранное</strong>
							</div>
							<div class="card-body table-responsive">
								<?
									$favorite_query = mysql_query("SELECT * FROM `users` WHERE `favorite`='1'");
								?>
								<!--begin: Datatable-->
								<table class="table table-hover table-head-custom">
									<thead>
										<tr>
											<th>ID</th>
											<th>Логин</th>
											<th>Баланс</th>
										</tr>
									</thead>
									<tbody>
										<? while($row = mysql_fetch_assoc($favorite_query)): ?>
										<tr>
											<td><?=$row["id"]?></td>
											<td><a href="?action=inlogin&edit-id=<?=$row["id"]?>"><?=$row["login"]?><a></td>
											<td class="w-50">
												<form class="w-50" method="POST">
													<div class="input-group">
														<input type="hidden" name="action" value="save-balans">
														<input type="hidden" name="edit-id" value="<?=$row["id"]?>">
														<input type="number" name="akwa" class="form-control" value="<?=$row["akwa"]?>" placeholder="0">
														<input type="submit" class="btn btn-light" value="Сохранить">
													</div>
												</form>
											</td>
											<td><a href="?action=remove-profile&profile=<?=$row["id"]?>"><i class="menu-icon fa fa-ban" style="color:red;font-size:18px;"></i></a></td>
										</tr>
										<? endwhile; ?>
									</tbody>
								</table>
								<!--end: Datatable-->
								<form method="post" class="add-person">
									<div class="input-group align-items-end">
										<div class="row-group mb-2 w-50">
											<label>Введите логин:</label>
											<input type="hidden" name="action" value="add-profile">
											<div class="input-group">
												<div class="input-group-prepend" id="check-profile">
													<div class="input-group-text"><div class="parent-icon"><i class="bx bx-search-alt"></i></div></div>
												</div>
												<input type="text" name="profile" class="form-control" placeholder="Логин">
											</div>
										</div>
										<div class="row-group mb-2 w-50">
											<input type="submit" disabled class="btn btn-light w-100" value="Добавить">
										</div>
									</div>
									<div class="msg"></div>
								</form>

								<button id="add-profile" class="btn btn-light">Добавить</button>
							</div>
						</div>
					</div>
				</div>
<? endif; ?>
					
			</div><!-- .animated -->
	</div><!-- .content -->

<? include("footer.php"); ?>

<script>
function showErr(code = false, err = false) {
	var msg = "Произошла ошибка. ";
	if(code)
		msg = msg + " Код: " + code;
	if(err)
		msg = msg + ". " + err;

	swal.fire({
		text: msg,
		icon: "error",
		buttonsStyling: false,
		confirmButtonText: "Понятно!",
		customClass: {
			confirmButton: "btn font-weight-bold btn-light-light"
		}
	});
}	
	
//perevod_username_check
	$('#perevod_username_check').on('click', function (e) {
		e.preventDefault();
		var username = $("#username-perevod").val();
		
		$("#summa-perevod").val("").attr("disabled", false);
		$("#perevod_summa_msg").html("");
		$("#perevod_finish").html('<input type="button" id="perevesti" disabled class="btn btn-light" value="Продолжить">');
		
		if(username.length==0) {
			$("#perevod_username_check_msg").html("Заполните поле.").removeClass("text-success").addClass("text-danger");
			$("#username-perevod").focus();
			$('#perevesti').attr("disabled", true);
		} else {

			$.ajax({
				type:'POST',
				url:'/Office/actions/perevody.php',
				data: {'type': 'check', 'username' : username},
				success: function(data) {

					if(data != 'err') {
						$("#perevod_username_check_msg").html(data).removeClass("text-danger").addClass("text-success");
						$('#perevesti').attr("disabled", false);
					}
					else {
						$("#perevod_username_check_msg").html("Логин не найден").removeClass("text-success").addClass("text-danger");
						$('#perevesti').attr("disabled", true);
					}


				},
				error: function(err) {
					showErr(1);
				}
			});
		}
	});
	var default_form = $('#perevod_finish').html();
	//perevesti
	$('#perevod_finish').on('click', "#perevesti", function (e) {
		e.preventDefault();
		
		var username = $("#username-perevod").val();
		var summa = parseInt($("#summa-perevod").val());
		
		if(summa && summa>0) {

			var maxSum = parseInt($("#summa-perevod").attr("max"));
			if(summa>maxSum)
				$("#perevod_summa_msg").html("Вы не можете перевести больше <b>" + maxSum + " Kzt</b>.");

			else {
				$('#summa-perevod').attr("disabled", true);
				$("#perevod_summa_msg").html("");
				$("#perevod_finish").html('Вы переводите <b>' + summa + ' Kzt</b> пользователю <b>' + username + '</b>.<br><br>' + '<button type="button" class="btn btn-light mb-2" data-bs-dismiss="modal" aria-label="Close">Отменить</button><input type="button" id="perevod_finish_btn" class="btn btn-light mb-2 mx-1" value="Перевести">');
			}
			
			
		} else {
			$("#perevod_summa_msg").html("Заполните поле.");
			$("#summa-perevod").focus();
		}
	});

	//perevesti
	$('#perevod_finish').on('click', "#perevod_finish_btn", function (e) {
		e.preventDefault();

		var username = $("#username-perevod").val();
		var summa = parseInt($("#summa-perevod").val());
		$(this).prop("disabled", true);
		if(username && summa) {
			$.ajax({
				type:'POST',
				url:'/Office/actions/perevody.php',
				data: {'type': 'perevod', 'username' : username, 'summa' : summa},
				success: function(data) {

					if(data == 'ok') {
						swal.fire({
							text: "Перевод успешно выполнен!",
							icon: "success",
							buttonsStyling: false,
							confirmButtonText: "Понятно!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light-primary"
							}
						}).then(function() {
							location.reload();
						});
					
					} else
						showErr(4, data);


				},
				error: function(err) {
					console.log(err);
					showErr(3);
				}
			});
			
		} else
			showErr(2);
	});
	
	$('#perevod_finish').on('click', "button[aria-label='Close']", function (e) {
		e.preventDefault();
		$('#summa-perevod').removeAttr("disabled");
		$('#perevod_finish').html(default_form);
		$('#perevesti').attr("disabled", false);
	});
</script>