<?php
include("db_connect.php");
include("b_func.php");

if (isset($_POST['sub_btn'])) {
	$stage = intval($_POST['stage']);	
	$user_login = mysql_escape_string($_POST['login']);
	$user_id = mysql_result(mysql_query("select id from users where login='".$user_login."'"), 0);
	$sponsor_login = mysql_escape_string($_POST['sponsor']);
	switch ($stage) {
		case 1:
			$result = mysql_query("select user_id from m1 where user_login='".$user_login."'");
			if (mysql_num_rows($result) > 0) {
				mysql_query("update m1 set sponsor_login='".$sponsor_login."' where user_login='".$user_login."'");
				$sponsor = mysql_result(mysql_query("select sponsor_login from m1 where user_login='".$sponsor_login."'"),0);
				check_binary_1($sponsor);
			}
		case 2: 
			$result = mysql_query("select user_id from m2 where user_login='".$user_login."'");
			if (mysql_num_rows($result) > 0) {
				mysql_query("update m2 set sponsor_login='".$sponsor_login."' where user_login='".$user_login."'");
				$sponsor = mysql_result(mysql_query("select sponsor_login from m2 where user_login='".$sponsor_login."'"),0);
				check_binary_2($sponsor);
			}
			else add_binary_2($user_id, $user_login, $sponsor_login);
			break;
		case 3: 
			$result = mysql_query("select user_id from m3 where user_login='".$user_login."'");
			if (mysql_num_rows($result) > 0) {
				mysql_query("update m3 set sponsor_login='".$sponsor_login."' where user_login='".$user_login."'");
				$sponsor = mysql_result(mysql_query("select sponsor_login from m3 where user_login='".$sponsor_login."'"),0);
				check_binary_3($sponsor);
			}
			else add_binary_3($user_id, $user_login, $sponsor_login);
			break;
		case 4: 
			$result = mysql_query("select user_id from m4 where user_login='".$user_login."'");
			if (mysql_num_rows($result) > 0) {
				mysql_query("update m4 set sponsor_login='".$sponsor_login."' where user_login='".$user_login."'");
				$sponsor = mysql_result(mysql_query("select sponsor_login from m4 where user_login='".$sponsor_login."'"),0);
				check_binary_4($sponsor);
			}
			else add_binary_4($user_id, $user_login, $sponsor_login);
			break;
		case 5: 
			$result = mysql_query("select user_id from m5 where user_login='".$user_login."'");
			if (mysql_num_rows($result) > 0) {
				mysql_query("update m5 set sponsor_login='".$sponsor_login."' where user_login='".$user_login."'");
				$sponsor = mysql_result(mysql_query("select sponsor_login from m5 where user_login='".$sponsor_login."'"),0);
				check_binary_5($sponsor);
			}
			else add_binary_5($user_id, $user_login, $sponsor_login);
			break;
		case 6: 
			$result = mysql_query("select user_id from m6 where user_login='".$user_login."'");
			if (mysql_num_rows($result) > 0) {
				mysql_query("update m6 set sponsor_login='".$sponsor_login."' where user_login='".$user_login."'");
				$sponsor = mysql_result(mysql_query("select sponsor_login from m6 where user_login='".$sponsor_login."'"),0);
				check_binary_6($sponsor);
			}
			else add_binary_6($user_id, $user_login, $sponsor_login);
			break;
		case 7: 
			$result = mysql_query("select user_id from m7 where user_login='".$user_login."'");
			if (mysql_num_rows($result) > 0) {
				mysql_query("update m7 set sponsor_login='".$sponsor_login."' where user_login='".$user_login."'");
				$sponsor = mysql_result(mysql_query("select sponsor_login from m7 where user_login='".$sponsor_login."'"),0);
				check_binary_7($sponsor);
			}
			else add_binary_7($user_id, $user_login, $sponsor_login);
			break;
		case 8: 
			$result = mysql_query("select user_id from m8 where user_login='".$user_login."'");
			if (mysql_num_rows($result) > 0) {
				mysql_query("update m8 set sponsor_login='".$sponsor_login."' where user_login='".$user_login."'");
				$sponsor = mysql_result(mysql_query("select sponsor_login from m8 where user_login='".$sponsor_login."'"),0);
				//check_binary_8($sponsor);
			}
			else add_binary_8($user_id, $user_login, $sponsor_login);
			break;
		default:
			break;
	}
	$message2 = "<div class='alert alert-success'>Пользователь успешно добавлен.</div>";
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
                        <h1>Добавить в бинар</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Личный кабинет</a></li>
                            <li><a href="#">Операции</a></li>
                            <li class="active">Добавить в бинар</li>
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
                                <strong class="card-title">Добавить в бинар</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <form action="add_to_binary.php" method="post">
											<div class="form-group">
                                                <label for="cc-payment" class="control-label mb-1">Этап</label>
                                                <input name="stage" type="number" class="form-control" min="1" placeholder="Введите этап" onkeypress="return isNumberKey(event)">
                                            </div>
                                                <div class="form-group has-success">
                                                    <label for="cc-name" class="control-label mb-1">Логин</label>
                                                    <input name="login" type="text" class="form-control" placeholder="Введите логин">
                                                </div>
												<div class="form-group has-success">
                                                    <label for="cc-name" class="control-label mb-1">Логин спонсора</label>
                                                    <input name="sponsor" type="text" class="form-control" placeholder="Введите логин спонсора">
                                                </div>
                                                <div>
                                                    <input id="payment-button" name="sub_btn" type="submit" class="btn btn-lg btn-success btn-block" value="Добавить">
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

