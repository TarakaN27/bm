<?php
session_start();
include('db_connect.php');
include "smsc_api.php";

$flag = false;
$cities = array('Выберите город','Алматы','Нур-Султан','Шымкент','Тараз','Атырау','Кызылорда','Актобе','Караганда','Павлодар','Талдыкорган','Актау','Уральск','Жезказган','Кокшетау','Петропавловск','Усть-Каменогорск','Семей');
if (isset($_POST['sub_sms']) && isset($_POST['r_fio']) && $_POST['r_phone']!="" && $_POST['r_city']!="Выберите город") {

$fio = mysql_escape_string($_POST["r_fio"]);
$phone = mysql_escape_string($_POST["r_phone"]);
$city = mysql_escape_string($_POST["r_city"]);

$code = rand(1000,9999);
$_SESSION['code'] = $code;
$sms_body = 'Код активации: '.$code.' на https://sanalibrend.com';
$ph = '7'.substr($phone,1,strlen($phone)-1);
list($sms_id, $sms_cnt, $cost, $balance) = send_sms($ph, $sms_body, 0, 0, 0, 0, false, "maxsms=3");
$flag = true;

}

if (isset($_POST['sub_post'])) {
	$flag = true;
	if ($_POST['r_code'] == $_SESSION['code']) {
		$fio = mysql_escape_string($_POST["r_fio"]);
		$phone = mysql_escape_string($_POST["r_phone"]);
		$city = mysql_escape_string($_POST["r_city"]);
		$code = intval($_POST["r_code"]);
		$lot_no = intval($_POST["r_lot_no"]);
		date_default_timezone_set('Asia/Aqtau');
		$result = mysql_query("select * from konkurs where lot_no=".$lot_no." and status=1");
		if (mysql_num_rows($result)>0) {
			$message2 = "Билет с данным номером уже активирован.";
			//unset($_SESSION['code']);
		}
		else {
			$result = mysql_query("select * from konkurs where lot_no=".$lot_no." and fio='".$fio."'");
			if (mysql_num_rows($result)>0) {
			mysql_query("update konkurs set code=".$code.", phone='".$phone."', city='".$city."', status=1, upd_time='".date('Y-m-d H:i:s')."' where lot_no=".$lot_no." and fio='".$fio."'", $con);
		
			$message2 = "Билет успешно активирован.";
			unset($_SESSION['code']);
			$fio = "";
			$phone = "";
			$city = "";
			}
			else {
				$message2 = "Ваши данные не совпадают.";
			}
		}
	}
	else {
		$fio = mysql_escape_string($_POST["r_fio"]);
		$phone = mysql_escape_string($_POST["r_phone"]);
		$city = mysql_escape_string($_POST["r_city"]);
		$lot_no = mysql_escape_string($_POST["r_lot_no"]);
		$message2 = "Код не совпадает.";
	}
}
mysql_close($con);
include('header.php');
?>
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Подтвердить билет</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Личный кабинет</a></li>
                            <li><a href="#">Операции</a></li>
                            <li class="active">Подтвердить билет</li>
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
                                <strong class="card-title">Подтвердить билет</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
          <form action="konkurs.php" method="post">
            <div class="form-group">
              <input placeholder="ФИО" class="form-control" minlength="5" name="r_fio" value="<?= $fio ?>">
            </div>
			<div class="form-group">
              <input placeholder="Телефон (в формате 87xxxxxxxxx)" class="form-control" minlength="11" maxlength="11" name="r_phone" value="<?= $phone ?>">
            </div>
			<div class="form-group">
              <select class="form-control" name="r_city">
				  <?php
				  foreach ($cities as $gorod) {
				  ?>
				  <option value="<?= $gorod?>" <?php if ($gorod==$city) echo ' selected="selected"'; ?>><?= $gorod?></option>
				  <? } ?>
			  </select>
            </div>
			<?php
			if (isset($_SESSION['code'])) {
			?>
			<div class="form-group">
              <input placeholder="Код активации" class="form-control" minlength="4" maxlength="4" name="r_code">
            </div>
			<div class="form-group">
              <input placeholder="Номер билета" class="form-control" type="number" min="1" max="20000" name="r_lot_no" value="<?= $lot_no ?>">
            </div>
			<div class="loginbox">
                <input type="submit" class="btn signin-btn" name="sub_post" value="Активировать">
				<?php
				if ($message2 != "") echo '<p><font color="red">'.$message2.'</font></p>';
			    ?>
			</div>
			<?php
			}
			else {
			?>
            <div class="loginbox">
              <input type="submit" class="btn btn-warning" name="sub_sms" value="Получить код">	
				<?php
				if ($message2 != "") echo '<p><font color="red">'.$message2.'</font></p>';
			    ?>
            </div>
			<? } ?>
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