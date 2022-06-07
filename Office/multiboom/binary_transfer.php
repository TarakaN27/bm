<?php
session_start();

include('db_connect.php');

$flag = false;
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");
if (mysql_num_rows($result) != 0 && ($_SESSION['login']=='admin' || $_SESSION['login']=='ambassador')) {
	$row = mysql_fetch_array($result);	
	date_default_timezone_set('Asia/Aqtau');
	$date = date('Y-m-d H:i:s');
	$akwa = $row['akwa'];
	if (isset($_POST['sub_btn'])) {
	$now = time(); // or your date as well
	
	$amount = $_POST['amount'];
	$login = mysql_escape_string($_POST['login']);

			$res = mysql_query("select * from users where login='".$login."'");
			$row_s = mysql_fetch_array($res);
			if ($row_s['akwa'] == 0 && $row_s['status'] == 0) {
				mysql_query("update users set akwa = akwa+".$amount." where login='".$login."'");
				mysql_query("insert into balance (sender, receiver, amount, sent_time) values ('admin', '".$login."', ".$amount.", '".date('Y-m-d H:i:s')."')");
				$message2 = 'Вы успешно отправили средства';
				
				//header("Location: withdrawn.php");
				//die();

			} 
			else $message2 = 'Пользователь уже активен.';

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
                        <h1>Пополнить баланс</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Личный кабинет</a></li>
                            <li><a href="#">Операции</a></li>
                            <li class="active">Пополнить баланс</li>
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
                                <strong class="card-title">Пополнить баланс</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <form action="binary_transfer.php" method="post">
                                            <div class="form-group">
                                                <label for="cc-payment" class="control-label mb-1">Сумма</label>
                                                <input name="amount" type="number" class="form-control" min="10" placeholder="Введите сумму" onkeypress="return isNumberKey(event)">
                                            </div>
                                                <div class="form-group has-success">
                                                    <label for="cc-name" class="control-label mb-1">Логин</label>
                                                    <input name="login" type="text" class="form-control" placeholder="Введите логин">
                                                </div>
                                                <div>
                                                    <input id="payment-button" name="sub_btn" type="submit" class="btn btn-lg btn-success btn-block" value="Отправить">
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