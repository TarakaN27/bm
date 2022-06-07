<?php
session_start();
include('db_connect.php');
include "smsc_api.php";

$flag = false;
$cities = array('Выберите город','Алматы','Нур-Султан','Шымкент','Тараз','Атырау','Кызылорда','Актобе','Караганда','Павлодар','Талдыкорган','Актау','Уральск','Жезказган','Кокшетау','Петропавловск','Усть-Каменогорск','Семей');

if ($_SESSION['login'] != 'admin') {
	header("Location: index.php");
	die();
}

if (isset($_POST['sub_post']) && isset($_POST['r_fio'])  && isset($_POST['r_lot_no']) && $_POST['r_city']!="Выберите город") {
	$flag = true;
		$fio = mysql_escape_string($_POST["r_fio"]);
		$city = mysql_escape_string($_POST["r_city"]);
		$lot_no = intval($_POST["r_lot_no"]);
		date_default_timezone_set('Asia/Aqtau');
		$result = mysql_query("select * from konkurs where lot_no=".$lot_no);
		if (mysql_num_rows($result)>0) {
			$message2 = "Билет с данным номером уже добавлен.";
			//unset($_SESSION['code']);
		}
		else {
			mysql_query("insert into konkurs ( fio, lot_no, code, phone, city, status, post_time, upd_time ) values ( '".$fio."', ".$lot_no.", 0, '', '".$city."', 0, '".date('Y-m-d H:i:s')."', '0000-00-00 00:00:00' )", $con);
			echo mysql_error();
			$message2 = "Билет успешно добавлен.";
			$fio = "";
			$city = "";
		}
}
mysql_close($con);
include('header.php');
?>

<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Добавить билет</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Личный кабинет</a></li>
                            <li><a href="#">Операции</a></li>
                            <li class="active">Добавить билет</li>
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
                                <strong class="card-title">Добавить билет</strong>
                            </div>
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
          <form action="add_konkurs.php" method="post">
            <div class="form-group">
              <input placeholder="ФИО" class="form-control" minlength="5" name="r_fio" value="<?= $fio ?>">
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
			<div class="form-group">
              <input placeholder="Номер билета" class="form-control" type="number" min="1" max="20000" name="r_lot_no" value="<?= $lot_no ?>">
            </div>
			<div class="loginbox">
                <input type="submit" class="btn btn-warning" name="sub_post" value="Добавить">
				<?php
				if ($message2 != "") echo '<p><font color="red">'.$message2.'</font></p>';
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