<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");
$row = mysql_fetch_array($result);
$fio = $row["fio"];

include("header.php");
?>

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Рефералы</h1>
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

                    <div class="col-md-12">
                        
						 <div class="card card-custom">
                            <div class="card-header">
                                <strong class="card-title">Рефералы</strong>
                            </div>
                            <div class="card-body">
								<?= ($message2!="")?$message2:""; ?>
                                <table class="table table-hover table-head-custom mw-380">
                                    <thead>
                                        <tr>
                                           
											<th>Логин</th>
											<th>Спонсор</th>
											<th>Уровень</th>
											<th>Дата регистрации</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
										$result2 = mysql_query("SELECT * FROM `users` WHERE `sponsor` LIKE '".$_SESSION['login']."' ORDER BY `sponsor` ASC");

										if (mysql_num_rows($result2)>0) {

											while ($row2 = mysql_fetch_array($result2)) {
												
												/*if($row2["hide_data"] == 0 && $_SESSION["login"] != "BoomMarket") {
													$row2["sponsor"] = "******";
													$row2["status"] = "******";
												}*/
												
												echo '<tr>';
												
												echo '<td>'.$row2['login'].'</td>';
												echo '<td>'.$row2['sponsor'].'</td>';
												echo '<td>'.$row2['status'].'</td>';
												echo '<td>'.$row2['reg_time'].'</td>';
												
												echo '</tr>';
											}

										}
										else echo '<tr><td colspan="5">Никого нет</td></tr>';
										?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div><!-- .animated -->
        </div><!-- .content -->

<? include("footer.php"); ?>