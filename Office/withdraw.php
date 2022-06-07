<style>
	.alert {
		margin-top: 10px;
	}
	td {
		font-size: 13px;
	}
</style>
<?php
session_start();
include("db_connect.php");

$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	date_default_timezone_set('Asia/Aqtau');
	if ($row['status'] >= 0) {
		if (isset($_POST['sub_btn']) && isset($_POST['amount']) && intval($_POST['amount'])>=100 && isset($_POST['nomer']) && $_POST['nomer']!="" && $_POST['iin']!="" && $_POST['phone']!="" && $_POST['card_name']!="") {
			$amount = abs(intval($_POST["amount"]));
			$nomer = mysql_escape_string($_POST["nomer"]);
			$card_name = mysql_escape_string($_POST["card_name"]);
			$phone = mysql_escape_string($_POST["phone"]);
			$iin = mysql_escape_string($_POST["iin"]);
			if($amount <= $general_options["max_balans_vyvod"] && $amount >= $general_options["min_balans_vyvod"]) {
				if ($row['akwa'] - $amount >= 0) {
					mysql_query("update users set akwa=akwa-".$amount." where login='".$row['login']."'");
					mysql_query("insert into vyvod ( login, amount, karta, iin, phone, post_time, card_name) values ( '".$row['login']."', ".$amount.", '".$nomer."', '".$iin."', '".$phone."', '".date('Y-m-d H:i:s')."', '".$card_name."')");
					$message2 = '<div class="alert alert-success" role="alert">
													Запрос успешно отправлен.<br>Деньги поступят в ближайщее время.
												</div>';
				}
				else {
					$message2 = '<div class="alert alert-danger" role="alert">
													Недостаточно средств.
												</div>';
				}
			} else {
				$message2 = '<div class="alert alert-danger" role="alert">
													Сумма должна быть от '.$general_options["min_balans_vyvod"].' до '.$general_options["max_balans_vyvod"].' Тг
												</div>';
			}
		}
	}
}
else {
	header("Location: ../index.php");
	die();
}

include("header.php");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
<?php
	if ($_SESSION['login'] == 'BoomMarket') {
?>

$.noConflict();
jQuery( document ).ready(function( $ ) {
	$(".withdr").on('click',function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: "POST",
			url: "confirm_withdraw.php",
			data: { 
				vyvod_id: id
			},
			success: function(result) {
				$('#'+id+'btn').attr("disabled","true");
				$('#'+id+'btn').html("Выполнен&nbsp<i class='fa fa-check-circle'></i>");
			},
			error: function(result) {
				alert( id+'error');
			}
		});
	});	  
	$(".canc").on('click',function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: "POST",
			url: "cancel_withdraw.php",
			data: { 
				vyvod_id: id
			},
			success: function(result) {
				$('#'+id+'btn').attr("disabled","true");
				$('#'+id+'btn2').attr("disabled","true");
				$('#'+id+'btn2').html("Возврат сделан&nbsp<i class='fa fa-check-circle'></i>");
			},
			error: function(result) {
				alert( id+'error');
			}
		});
	});	  
});
<?php } ?>
</script>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Вывод средств</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">

<?php
	if ($_SESSION['login'] != 'BoomMarket') {
?>
                <div class="row">

                     <div class="col-lg-6">
                        <div class="card card-custom">
                            <div class="card-header">
                                <strong class="card-title">Запрос на вывод</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <form action="withdraw.php" method="post">
                                            <div class="form-group" style="text-align: center">
                                                <label for="cc-payment" class="control-label mb-1">Сумма</label>
                                                <input name="amount" required type="number" class="form-control" min="<?=$general_options["min_balans_vyvod"]?>" max="<?=$general_options["max_balans_vyvod"]?>" placeholder="Введите сумму (мин. <?=$general_options["min_balans_vyvod"]?>)" onkeypress="return isNumberKey(event)">
                                            </div>
											<div class="form-group" style="text-align: center">
                                                <label for="cc-payment" class="control-label mb-1">ИИН</label>
                                                <input name="iin" required type="text" class="form-control" placeholder="Введите свой ИИН" onkeypress="return isNumberKey(event)">
                                            </div>
											<div class="form-group" style="text-align: center">
                                                <label for="cc-payment" class="control-label mb-1">Телефон</label>
                                                <input name="phone" required type="text" class="form-control" placeholder="Введите телефон">
                                            </div>
                                                <div class="form-group has-success" style="text-align: center">
                                                    <label for="cc-name" class="control-label mb-1">Номер карты</label>
                                                    <input name="nomer" required type="text" class="form-control" placeholder="Введите номер карты" onkeypress="return isNumberKey(event)">
                                                </div>
												<div class="form-group has-success" style="text-align: center">
                                                    <label for="cc-name" class="control-label mb-1">Имя держателя карты</label>
                                                    <input name="card_name" required type="text" class="form-control" placeholder="Введите имя">
                                                </div>
                                                <div>
                                                    <input id="payment-button" name="sub_btn" type="submit" class="btn btn-lg btn-info btn-block" value="Отправить">
													
													<?php
													if ($message2!="") echo $message2;
													?>
                                                </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- .card -->
						</div>
					</div>
			<?php
				$result2 = mysql_query("select * from vyvod where login='".$row['login']."'");
			}
			else {
				$result2 = mysql_query("select * from vyvod order by id desc");
			}
			?>
				<div class="row">
						<div class="col-md-12">
							<div class="card card-custom">
								<div class="card-header">
									<strong class="card-title">Выводы</strong>
								</div>
								<div class="card-body">
									<table class="table table-hover table-head-custom mw-380">
										<thead>
											<tr>
												<th>Логин</th>
												<th>Сумма</th>
												<th>Телефон</th>
												<th>Номер карты</th>
												<th>Держатель карты</th>
												<th>ИИН</th>
												<th>Время</th>
												<th>Статус</th>
												<?php if ($_SESSION['login'] == 'BoomMarket') echo '<th>Действия</th>'; ?>
											</tr>
										</thead>
										<tbody>
											<?php
											$statuses = ['В обработке','Завершено','Возвращен'];
											if (mysql_num_rows($result2)>0) {
												
												while ($row2 = mysql_fetch_array($result2)) {
													
													if($row2["hide_data"] == 0 && $_SESSION["login"] != "BoomMarket") {
														$row2["amount"] = $row2["phone"] = $row2["karta"] = $row2["card_name"] = $row2["iin"] = $row2["post_time"] = "******";
													}
													
													echo '<tr>';
													echo '<td>'.$row2['login'].'</td>';
													echo '<td>'.$row2['amount'].'</td>';
													echo '<td>'.$row2['phone'].'</td>';
													echo '<td>'.$row2['karta'].'</td>';
													echo '<td>'.$row2['card_name'].'</td>';
													echo '<td>'.$row2['iin'].'</td>';
													echo '<td>'.$row2['post_time'].'</td>';
													echo '<td>'.($statuses[$row2['status']]).'</td>';
													if ($_SESSION['login'] == 'BoomMarket') echo '<td><button type="submit" class="btn btn-warning withdr" id="'.$row2['id'].'btn" data-id="'.$row2['id'].'" '.(($row2['status']>=1)?'disabled':'').'>Выдача</button>&nbsp;&nbsp;<button type="submit" class="btn btn-danger canc" id="'.$row2['id'].'btn2" data-id="'.$row2['id'].'" '.(($row2['status']==2)?'disabled':'').'>Возврат</button></td>';
													echo '</tr>';
												}
												
											}
											else echo '<tr><td colspan="6">Нет запросов</td></tr>';
											?>
										</tbody>
									</table>
								</div>
							</div>
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
</body>
</html>
