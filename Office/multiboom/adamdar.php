<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
$liders = array('Olzha888', 'Millionersha777', 'Bankir888', 'Admin1', 'sholpan81', 'Umigold', 'Jaksa', 'Magnat789');
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

if (mysql_num_rows($result) != 0 && ($_SESSION['login']=='BoomMarket' || in_array($_SESSION['login'], $liders))) {
	$row = mysql_fetch_array($result);
	if (isset($_POST['sub_btn'])) $st = $_POST['f_search']; else $st = "";
}
else {
	header("Location: ../index.php");
	die();
}

function is_in_binary($st, $m) {
	$arr = array();
	$arr[0] = $st;
	$i = 0; $n = 1; $flag_s = false;
	while ($arr[$i] != $_SESSION['login'] && $arr[$i] != 'BoomMarket' && $i < $n && !$flag_s) {
		$result2 = mysql_query("select sponsor_login from ".$m." where user_login='".$arr[$i]."'");

		if (mysql_num_rows($result2) > 0) {
			while ($row2 = mysql_fetch_array($result2)) {
				$arr[$n] = trim($row2['sponsor_login']);
				//echo $arr[$n].'<br>';
				if ($arr[$n] == $_SESSION['login']) {
					$flag_s = true;
					break;
				}
				$n++;
			}
		}
		$i++;
	}
	return $flag_s;
}

include("header.php");
?>
<script>
		$('table').dataTable({bFilter: false, bInfo: false});	
	</script>

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Отчеты</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Личный кабинет</a></li>
                            <li><a href="#">Операции</a></li>
                            <li class="active">Отчеты</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        
						 <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Поиск пользователя</strong>
                            </div>
                            <div class="card-body">
								<form method="post" action="adamdar.php">
								<input type="text" class="form-control" name="f_search" placeholder="Введите логин партнера" value="<?= $st ?>">
								<input type="submit" name="sub_btn" value="Поиск">
								</form>
								<?= ($message2!="")?$message2:""; ?>
								<table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>											
											<th>Логин</th>
                                            <th>Пароль</th>
											<th>ФИО</th>
                                            <th>Телефон</th>
											<th>Дата</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                
																			<?php
										$flag_s = false;
										if ($_SESSION['login'] == 'BoomMarket') {
										?>
										
										<?php
																			if (strlen($st) > 0) $result2 = mysql_query("select * from users where id<>1 and login like '".$st."%' order by id asc");

																			if (mysql_num_rows($result2)>0) {

																				while ($row2 = mysql_fetch_array($result2)) {
																					echo '<tr>';
																					echo '<td>'.$row2['id'].'</td>';
																					echo '<td>'.$row2['login'].'</td>';
																					echo '<td>'.$row2['pass'].'</td>';
																					echo '<td>'.$row2['fio'].'</td>';
																					echo '<td>'.$row2['phone'].'</td>';
																					echo '<td>'.$row2['reg_time'].'</td>';
																					
																					
																					echo '</tr>';
																				}

																			}
																			else echo '<tr><td colspan="6">Нет пользователя</td></tr>';
										?>
								<?php
										}
									else if (in_array($_SESSION['login'], $liders) && strlen($st) > 0) {
										$flag_s = is_in_binary($st, 'm1');
										if (!$flag_s) $flag_s = is_in_binary($st, 'm2');
										if (!$flag_s) $flag_s = is_in_binary($st, 'm3');
										if (!$flag_s) $flag_s = is_in_binary($st, 'm4');
										if (!$flag_s) $flag_s = is_in_binary($st, 'm5');
									}
								if ($flag_s) $result3 = mysql_query("select * from users where id<>1 and login = '".$st."' order by ");

																			if (mysql_num_rows($result3)>0) {

																				while ($row3 = mysql_fetch_array($result3)) {
																					echo '<tr>';
																					echo '<td>'.$row3['id'].'</td>';
																					echo '<td>'.$row3['login'].'</td>';
																					echo '<td>'.$row3['pass'].'</td>';
																					echo '<td>'.$row3['fio'].'</td>';
																					echo '<td>'.$row3['phone'].'</td>';
																					
																					echo '</tr>';
																				}

																			}
																			else echo '<tr><td colspan="5">Нет пользователя</td></tr>';
										?>
                                    </tbody>
                                </table>
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
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
	<script>
		$('table').dataTable({bFilter: false, bInfo: false});	
	</script>

    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="vendors/jszip/dist/jszip.min.js"></script>
    <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="assets/js/init-scripts/data-table/datatables-init.js"></script>


</body>

</html>
