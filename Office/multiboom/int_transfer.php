<?php
session_start();

include('db_connect.php');

$flag = false;
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");
if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);	
	date_default_timezone_set('Asia/Almaty');
	
	if (isset($_POST['sub_btn']) && $_POST['amount'] > 100 && $_POST['login'] != "") {	
		$amount = abs(intval($_POST['amount']));
		$login = mysql_escape_string($_POST['login']);
		if ($row['akwa'] - $amount >= 0) {
			$res = mysql_query("select * from users where login='".$login."'");
			if (mysql_num_rows($res) > 0) {
				mysql_query("update users set akwa = akwa-".$amount." where login='".$_SESSION['login']."'");
				mysql_query("update users set akwa = akwa+".$amount." where login='".$login."'");
				mysql_query("insert into int_transfer (sender, receiver, amount, sent_time) values ('".$_SESSION['login']."', '".$login."', ".$amount.", '".date('Y-m-d H:i:s')."')");
				$message2 = '<div class="alert alert-success">Вы успешно отправили средства</div>';
				$row['akwa'] = $row['akwa'] - $amount;
				header("Location: int_transfer.php");
				die();
			}
			else $message2 = '<div class="alert alert-danger">Пользователя с таким логином не существует</div>';
		}
		else $message2 = '<div class="alert alert-danger">У вас недостаточно средств.</div>';
	}
}
else {
	header("Location: index.php");
	die();
}

mysql_close();
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


                <div class="row">

                     <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Внутренний перевод</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <form action="int_transfer.php" method="post">
                                            <div class="form-group">
                                                <label for="cc-payment" class="control-label mb-1">Сумма</label>
                                                <input name="amount" type="number" class="form-control" min="1000" placeholder="Введите сумму" onkeypress="return isNumberKey(event)">
                                            </div>
                                                <div class="form-group has-info">
                                                    <label for="cc-name" class="control-label mb-1">Логин</label>
                                                    <input name="login" type="text" class="form-control" placeholder="Введите логин">
                                                </div>
                                                <div>
                                                    <input id="payment-button" name="sub_btn" type="submit" class="btn btn-lg btn-secondary btn-block" value="Отправить">
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