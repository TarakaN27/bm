<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");

$bonus = [
	"bonus-ref"=>"Реферальный бонус",
	"bonus-faststart"=>"Бонус Быстрый Старт",
	"bonus-boom"=>"Boom бонус",
	"bonus-binar"=>"Binar бонус",
	"bonus-status"=>"Статусный бонус",
	"bonus-sponsor"=>"Спонсорский бонус",
	"bonus-leader"=>"Лидерский бонус",
	"bonus-close-turbo"=>"Бонус закрытия уровня(Turbo)",
	"bonus-ref-turbo"=>"Реферальный бонус(Turbo)",
	"bonus-sponsor-turbo"=>"Спонсорский бонус(Turbo)",
	"bonus-marketing-turbo"=>"Бонус маркетинга(Turbo)",
];

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
}
else {
	header("Location: ../index.php");
	die();
}

include("header.php");
?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Отчеты</h1>
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
                                <strong class="card-title"><span class="card-icon">
				<i class="fa fa-history text-primary"></i>
			</span>Поступления</strong>
                            </div>
                            <div class="card-body">
								<?= ($message2!="")?$message2:""; ?>
                                <table class="bootstrap-data-table-report-4 table table-hover table-head-custom mw-380">
                                    <thead>
                                        <tr>
                                            <th>Сумма</th>
											<th>От кого</th>
                                            <th>Ступень</th>
											<th>Время</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
										$result2 = mysql_query("select * from transfer where login='".$row['login']."' order by id desc");
										$result3= mysql_query("select history.*, u.login as user_login from history LEFT JOIN users as u ON u.id=history.from where user_id='".$row['id']."' AND type LIKE 'bonus%' order by id desc");
										$result4= mysql_query("select history.*, u.login as user_login from history LEFT JOIN users as u ON u.id=history.from where user_id='".$row['id']."' AND type='cash-shareholder' order by id desc");
										$result5= mysql_query("select history.*, u.login as user_login from history LEFT JOIN users as u ON u.id=history.from where user_id='".$row['id']."' AND type LIKE 'turbo%' order by id desc");
										
										if (mysql_num_rows($result2)>0) {
											$result_gen = [];
											while ($row2 = mysql_fetch_array($result2)) {												
												$result_gen[] = [
													"amount"=>$row2["amount"],
													"user_login"=>$row2["user_login"],
													"product"=>$row2["product"],
													"sent_time"=>$row2["sent_time"],
												];
											}
										}
										if (mysql_num_rows($result3)>0) {
											while ($row2 = mysql_fetch_array($result3)) {
												preg_match("/(infinity\d{1})/", $row2["text"], $matches);
												preg_match('/(\d+)/', $row2["text"], $amounts);
												$pv = $row2["type"]=="bonus-boom" || $row2["type"]=="bonus-binar" ? " за ".$row2["temp_val"]." PV": "";
 												$result_gen[] = [
													"amount"=>$amounts[0],
													"user_login"=>$row2["user_login"],
													#"product"=>"Infinity (".$bonus[$row2["type"]].$pv.")",
													"product"=>"Infinity (".$row2["msg"].$pv.")",
													"sent_time"=>$row2["date"],
												];
											}
										}
										if (mysql_num_rows($result4)>0) {
											while ($row2 = mysql_fetch_array($result4)) {	
												preg_match('/(\d+)/', $row2["text"], $amounts);
												$row2["user_login"] = $row2["user_login"]==NULL ? "Система": $row2["user_login"];
												$result_gen[] = [
													"amount"=>$amounts[0],
													"user_login"=>$row2["user_login"],
													"product"=>"Infinity (".$row2["msg"].")",
													"sent_time"=>$row2["date"],
												];
											}
										}
										
										if (mysql_num_rows($result5)>0) {
											while ($row2 = mysql_fetch_array($result5)) {	
												preg_match('/(\d+)/', $row2["text"], $amounts);
												$row2["user_login"] = $row2["user_login"]==NULL ? "Система": $row2["user_login"];
												$result_gen[] = [
													"amount"=>$amounts[0],
													"user_login"=>$row2["user_login"],
													"product"=>"Turbo (".$row2["msg"].")",
													"sent_time"=>$row2["date"],
												];
											}
										}
										
										if (mysql_num_rows($result2)>0 || mysql_num_rows($result3)>0 || mysql_num_rows($result4)>0 || mysql_num_rows($result5)>0) {
											uasort($result_gen, function ($item1, $item2) {
												return strtotime($item2['sent_time']) - strtotime($item1['sent_time']);
											});
											
											foreach($result_gen as $row2){
												echo '<tr>';												
												echo '<td>'.$row2['amount'].'</td>';
												echo '<td>'.$row2['user_login'].'</td>';
												echo '<td>'.$row2['product'].'</td>';
												echo '<td>'.$row2['sent_time'].'</td>';
												echo '</tr>';
											}
										
										}
										else echo '<tr><td colspan="5">Нет транзакции</td></tr>';
										?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					</div>
                </div>
				
				<div class="row">

                    <div class="col-md-12">
                        
						 <div class="card card-custom">
                            <div class="card-header">
                                <strong class="card-title"><span class="card-icon">
				<i class="fa fa-history text-primary"></i>
			</span>Входящие переводы</strong>
                            </div>
                            <div class="card-body">
								<?= ($message2!="")?$message2:""; ?> 
                                <table class="bootstrap-data-table-report-1 table table-hover table-head-custom mw-380">
                                    <thead>
                                        <tr>
											<th>Время</th>
                                            <th>Сумма</th>
											<th>От кого</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
										$result2 = mysql_query("select * from int_transfer where receiver='".$row['login']."' order by id desc");

										if (mysql_num_rows($result2)>0) {

											while ($row2 = mysql_fetch_array($result2)) {
												echo '<tr>';		
												echo '<td>'.$row2['sent_time'].'</td>';
												echo '<td>'.$row2['amount'].'</td>';
												echo '<td>'.$row2['sender'].'</td>';																	echo '</tr>';
											}

										}
										else echo '<tr><td colspan="5">Нет транзакции</td></tr>';
										?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					</div>
                </div>
				
				<div class="row">

                    <div class="col-md-12">
                        
						 <div class="card card-custom">
                            <div class="card-header">
                                <strong class="card-title"><span class="card-icon">
				<i class="fa fa-history text-primary"></i>
			</span>Исходящие переводы</strong>
                            </div>
                            <div class="card-body">
								<?= ($message2!="")?$message2:""; ?>
                                <table class="bootstrap-data-table-report-1 table table-hover table-head-custom mw-380">
                                    <thead>
                                        <tr>
											<th>Время</th>
                                            <th>Сумма</th>
											<th>Кому</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
										$result2 = mysql_query("select * from int_transfer where sender='".$row['login']."' order by id desc");

										if (mysql_num_rows($result2)>0) {

											while ($row2 = mysql_fetch_array($result2)) {
												echo '<tr>';		
												echo '<td>'.$row2['sent_time'].'</td>';
												echo '<td>'.$row2['amount'].'</td>';
												echo '<td>'.$row2['receiver'].'</td>';																	echo '</tr>';
											}

										}
										else echo '<tr><td colspan="5">Нет транзакции</td></tr>';
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