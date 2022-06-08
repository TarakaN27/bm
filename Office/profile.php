<?php
session_start();
include("db_connect.php");
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	
	if (isset($_POST['sub_btn']) && $_POST['r_fio']!="" && $_POST["r_phone"]!= "") {
		$fio = mysql_escape_string($_POST["r_fio"]);
		$city = mysql_escape_string($_POST["r_city"]);
		$phone = mysql_escape_string($_POST["r_phone"]);
		mysql_query("update users set fio='".$fio."', city='".$city."', phone='".$phone."' where login='".$row['login']."'",$con);
		$row['fio'] = $fio;
		$row['city'] = $city;
		$row['phone'] = $phone;
		
		$uploaddir = './images/avatar/';
		$uploadfile = $uploaddir.$row['id'].'.jpg';

		if (move_uploaded_file($_FILES['file-input']['tmp_name'], $uploadfile)) {
			//echo "Файл корректен и был успешно загружен.\n";
		} else {
			//echo "Возможная атака с помощью файловой загрузки!\n";
		}

	}
	
	if (isset($_POST['sub_btn2']) && $_POST['r_password']!="") {
		$pass = mysql_escape_string($_POST["r_password"]);
		mysql_query("update users set pass='".$pass."' where login='".$row['login']."'",$con);
		$row['pass'] = $pass;
	}
	
}
else {
	header("Location: ../index.php");
	die();
}

include("header.php");
?>
<script>
function hide() {
  var x = document.getElementById("pass");
  x.type = "password";
}
function show() {
  var x = document.getElementById("pass");
  x.type = "text";
}
</script>
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
                <div class="row">
					<div class="col-lg-6">
						<div class="card">
							<div class="card-header">
								<strong>Профиль</strong>
							</div>
							<form action="profile.php" method="post" enctype="multipart/form-data" class="form-horizontal">
							<div class="card-body card-block">
								<div class="row form-group my-2">
									<div class="col col-md-4"><label for="text-input" class=" form-control-label">Полное имя</label></div>
									<div class="col-12 col-md-8"><input type="text" id="text-input" name="r_fio" value="<?= $row['fio'] ?>" placeholder="ФИО" class="form-control"></div>
								</div>
								<div class="row form-group my-2">
									<div class="col col-md-4"><label class=" form-control-label">Номер телефона </label></div>
									<div class="col-12 col-md-8">
										<div class="col-14 col-md-14"><input type="text" id="text-input" name="r_phone" value="<?= $row['phone'] ?>" placeholder="Телефон формат 8700-000-00-00" class="form-control"></div>
									</div>
								</div>
								<div class="row form-group my-2">
									<div class="col col-md-4"><label for="file-input" class=" form-control-label">Фото профиля</label></div>
									<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
									<div class="col-12 col-md-8"><input type="file" id="file-input" name="file-input" class="form-control"></div>
								</div>
							</div>
							<div class="card-footer">
								<input type="submit" class="btn btn-light btn-sm" name="sub_btn"  value="Сохранить">
							</div>
							<div class="card-body card-block">
								<div class="row form-group">
									<div class="col col-md-4"><label for="password-input" class=" form-control-label">Пароль</label></div>
									<div class="col-12 col-md-8 input-group">
										<input type="password" id="pass" name="r_password" placeholder="Пароль" value="<?= $row['pass'] ?>" class="form-control">
										<small class="help-block form-text"></small>
										<button type="button" class="btn btn-light" onmousedown='show()' onmouseup='hide()' ontouchstart="show()" ontouchend="hide()">Показать пароль</button>
										</div>
								</div>														
							</div>
							<div class="card-footer">
								<input type="submit" class="btn btn-light btn-sm" name="sub_btn2"  value="Сохранить">
							</div>
							</form>
						</div>
					</div>

				</div><!-- .animated -->
			</div><!-- .content -->
		</div><!-- /#right-panel -->
		<!-- Right Panel -->

<? include("footer.php"); ?>

