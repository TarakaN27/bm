<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
date_default_timezone_set('Asia/Aqtau');

$result = mysql_query("select * from users where phone='".$_SESSION['phone']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	if (isset($_POST['sub_btn2']) ) {
		$amount = abs(intval(mysql_escape_string($_POST['cash_amount'])));
		$user_id = intval(mysql_escape_string($_POST['cash_user_id']));
		$result_s = mysql_query("select * from users where user_id=".$user_id." and magazin=1 and user_id<>".$row['user_id']);
		if (mysql_num_rows($result_s)>0 && $user_id != $row['user_id'] && $row['status'] == 1) {
			$row_s = mysql_fetch_array($result_s);
			if ($row_s['cashback']>=1 && $row_s['cb1']>=1 && $row_s['cb2']>=1 && $row_s['cb3']>=1 && $row_s['cb4']>=1 && $row_s['cb5']>=1 && $row_s['cb6']>=1 && $row_s['cb7']>=1) {
				mysql_query("insert into cash_transfer ( user_id, phone, shop_id, amount, status, post_time ) values ( ".$row['user_id'].", '".$row['phone']."', ".$user_id.", ".$amount.", 0, '".date('Y-m-d H:i:s')."') ");
				$sms_body = 'ID '.$row['user_id'].' отправил Вам '.$amount.' тг наличными на сайте cashber . kz. Пожайлуйста, подтвердите в личном кабинете';
				$ph = '7'.substr($row_s['phone'],1,strlen($row_s['phone'])-1);
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");
				$message2 = '<div class="alert alert-success" role="alert">
												Перевод отправлен успешно. Кэшбэк будет начислен после подтверждения магазина/ресторана.
											</div>';
			}
			else $message2 = '<div class="alert alert-danger" role="alert">
												Данному магазину/ресторану перевод не доступен. Владелец должен настроить рефералы.
											</div>';
		}
	}
	if (isset($_POST['sub_btn'])) {
		$amount = abs(intval(mysql_escape_string($_POST['amount'])));
		$user_id = intval(mysql_escape_string($_POST['user_id']));
		$account = mysql_escape_string($_POST['account']);
		$result_s = mysql_query("select * from users where user_id=".$user_id." and magazin=1");

		if (mysql_num_rows($result_s) > 0 && $user_id != $row['user_id'] && $row['status'] == 1) {
			$row_s = mysql_fetch_array($result_s);
			if ($row_s['cashback']>=1 && $row_s['cb1']>=1 && $row_s['cb2']>=1 && $row_s['cb3']>=1 && $row_s['cb4']>=1 && $row_s['cb5']>=1 && $row_s['cb6']>=1 && $row_s['cb7']>=1) {
			if ($account == 'main') $akwa = $row['akwa']; else $akwa = $row['bonus'];
			if (($akwa - $amount) >= 0) {

				$i = 1;
				$sponsor = $user_id;
				$diff = 0;
				while ($sponsor>0 && $i <= 7) {
					//echo $sponsor.'<br>';
					$result_x = mysql_query("select phone, sponsor from users where user_id=".$sponsor);
					if (mysql_num_rows($result_x)>0) {
						$row_x = mysql_fetch_array($result_x);
						$sponsor = $row_x['sponsor'];
						switch ($i) {
							case 1:
								$akwa1 = abs(round($amount*$row_s['cb1']/100/2));
								$bonus1 = abs(round($amount*$row_s['cb1']/100/2));
								break;
							case 2:
								$akwa1 = abs(round($amount*$row_s['cb2']/100/2));
								$bonus1 = abs(round($amount*$row_s['cb2']/100/2));
								break;
							case 3:
								$akwa1 = abs(round($amount*$row_s['cb3']/100/2));
								$bonus1 = abs(round($amount*$row_s['cb3']/100/2));
								break;
							case 4:
								$akwa1 = abs(round($amount*$row_s['cb4']/100/2));
								$bonus1 = abs(round($amount*$row_s['cb4']/100/2));
								break;
							case 5:
								$akwa1 = abs(round($amount*$row_s['cb5']/100/2));
								$bonus1 = abs(round($amount*$row_s['cb5']/100/2));
								break;
							case 6:
								$akwa1 = abs(round($amount*$row_s['cb6']/100/2));
								$bonus1 = abs(round($amount*$row_s['cb6']/100/2));
								break;
							case 7:
								$akwa1 = abs(round($amount*$row_s['cb7']/100/2));
								$bonus1 = abs(round($amount*$row_s['cb7']/100/2));
								break;
						}
						mysql_query("update users set akwa=akwa+".$akwa1.", bonus=bonus+".$bonus1." where user_id=".$sponsor);
						$diff = $diff + $akwa1 + $bonus1;
						mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$sponsor.", ".$akwa1.", ".$i.", ".$row_s["user_id"].", 0, 1, '".date('Y-m-d H:i:s')."') ");
						mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$sponsor.", ".$bonus1.", ".$i.", ".$row_s["user_id"].", 1, 1, '".date('Y-m-d H:i:s')."') ");
						echo mysql_error();
						if ($sponsor>7) {
							/*$sms_body = 'Вы получили '.($akwa1+$bonus1).' тг от магазина ID '.$row_s['user_id'].' из '.$i.'-й линий на сайте cashber . kz';
							$ph = '7'.substr($row_x['phone'],1,strlen($row_x['phone'])-1);
							list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");*/
						}
					}
					//echo $akwa1.' - '.$bonus1.'<br>';
					$i++;
				}
				$diff = $diff + abs(round($amount/100));
				$akwa1 = abs(round($amount*$row_s['cashback']/100/2));
				$bonus1 = abs(round($amount*$row_s['cashback']/100/2));
				mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$row['user_id'].", ".$akwa1.", ".$i.", ".$row_s["user_id"].", 0, 1, '".date('Y-m-d H:i:s')."') ");
				mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$row['user_id'].", ".$bonus1.", ".$i.", ".$row_s["user_id"].", 1, 1, '".date('Y-m-d H:i:s')."') ");
				/*$sms_body = 'Вы получили кэшбэк '.($akwa1+$bonus1).' тг от магазина ID '.$row_s['user_id'].' на сайте cashber . kz';
				$ph = '7'.substr($row_x['phone'],1,strlen($row_x['phone'])-1);
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");*/
				$diff = $diff + $akwa1 + $bonus1;
				mysql_query("update users set akwa=akwa+".abs(round($amount/100))." where user_id=0");
				mysql_query("update users set akwa=akwa+".abs($amount-$diff)." where user_id=".$user_id);
				if ($account == 'main') mysql_query("update users set akwa=akwa-".abs($amount)."+".abs($akwa1).", bonus=bonus+".abs($bonus1)." where user_id=".$row['user_id']);
				else mysql_query("update users set akwa=akwa+".abs($akwa1).", bonus=bonus-".abs($amount)."+".abs($bonus1)." where user_id=".$row['user_id']);
				mysql_query("insert into transfer ( u_id, amount, line, user_id, type, product, sent_time ) values ( ".$row_s['user_id'].", ".$amount.", ".$i.", ".$row["user_id"].", 0, 2, '".date('Y-m-d H:i:s')."') ");
				/*$sms_body = 'Вы отправили '.($amount).' тг от ID '.$row['user_id'].' за покупку на сайте cashber . kz';
				$ph = '7'.substr($row_s['phone'],1,strlen($row_s['phone'])-1);
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");*/
				$sms_body = 'ID '.$row['user_id'].' отправил Вам '.$amount.' тг на сайте cashber . kz';
				$ph = '7'.substr($row_s['phone'],1,strlen($row_s['phone'])-1);
				list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");
				$message = '<div class="alert alert-danger" role="alert">
											Перевод произведен успешно.
										</div>';
			}
			else $message = '<div class="alert alert-danger" role="alert">
											Недостаточно средств. Пожалуйста, проверьте свои счета.
										</div>';
		}
		else $message = '<div class="alert alert-danger" role="alert">
											Пользователь с таким ID не существует.
										</div>';
		}
		else $message = '<div class="alert alert-danger" role="alert">
											Данному магазину перевод не доступен. Магазин должен настроить рефералы.
										</div>';
	}
	
}
else {
	header("Location: ../index.php");
	die();
}

include("header.php");
?>
<style>
video {
  max-width: 100%;
  height: auto;
}
</style>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Обзор</h1>
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

				<form action="send_money.php" method="post">
                <div class="row">					 
                     <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Перевод за покупку</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                            <div class="form-group">
                                                <label for="cc-payment" class="control-label mb-1">Сумма</label>
                                                <input id="cc-pament" name="amount" type="number" class="form-control" aria-required="true" aria-invalid="false" value="">
                                            </div>
											<div class="form-group">
												<select class="form-control" name="account">
													<option value="main">Основной счет</option>
													<option value="bonus">Бонус</option>
												</select>
											</div>
                                                <div class="form-group has-success">
                                                    <label for="cc-name" class="control-label mb-1">Получатель</label>
                                                    <input id="cc-name" name="user_id" type="number" pattern="[0-9]*" class="form-control cc-name valid" data-val="true" data-val-required="Введите ID получателя" placeholder="Введите ID получателя" autocomplete="user_id" aria-required="true" aria-invalid="false" aria-describedby="cc-name-error">
                                                    <span class="help-block field-validation-valid" data-valmsg-for="user_id" data-valmsg-replace="true"></span>
                                                </div>
                                                <div>
                                                    <input id="payment-button" type="submit" name="sub_btn" class="btn btn-lg btn-success btn-block" value="Перевести">
													<?php if ($message!="") echo $message; ?>
                                                </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- .card -->

                    </div>
                                            <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Наличными на руки</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                            <div class="form-group">
                                                <label for="cc-payment" class="control-label mb-1">Сумма</label>
                                                <input id="cc-pament" name="cash_amount" type="number" class="form-control" aria-required="true" aria-invalid="false" value="">
                                            </div>
                                                <div class="form-group has-success">
                                                    <label for="cc-name" class="control-label mb-1">Получатель</label>
                                                    <input id="cc-name" name="cash_user_id" type="number" pattern="[0-9]*" class="form-control cc-name valid" data-val="true" data-val-required="Введите ID получателя" placeholder="Введите ID получателя" autocomplete="cash_user_id" aria-required="true" aria-invalid="false" aria-describedby="cc-name-error">
                                                    <span class="help-block field-validation-valid" data-valmsg-for="cash_user_id" data-valmsg-replace="true"></span>
                                                </div>
                                                <div>
                                                    <input id="payment-button2" type="submit" name="sub_btn2" class="btn btn-lg btn-success btn-block" value="Отправить запрос">
													<?php if ($message2!="") echo $message2; ?>
                                                </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .card -->
                    </div>
					</form>
					<div class="row">
					<div class="col-lg-12 pull-center">
					   <div class="contact-area">
						 <div class="row">
						   <div class="col-lg-11 pull-center" style="text-align:center;">
							<video class="video-fluid z-depth-1" loop controls>
							  <source src="videos/send_money.mp4" type="video/mp4">
							</video>				
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
