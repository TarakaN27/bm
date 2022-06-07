<?php
include("db_connect.php");
date_default_timezone_set('Asia/Almaty');
include("b_func.php");
set_time_limit(1200);

$res = mysql_query("select user_login from m4 order by id asc");
if (mysql_num_rows($res) > 0) {
	while ($row = mysql_fetch_array($res)) {
		$res_y = mysql_query("select user_login from m5 where user_login='".$row['user_login']."'");
		if (mysql_num_rows($res_y) == 0) check_binary_4($row['user_login']);
	}
}

/*$res_x = mysql_query("select id from m1 where sponsor_login='".$row['sponsor_login']."' order by id asc");
		if (mysql_num_rows($res_x) > 0) {
			$k = 0;
			while ($row_x = mysql_fetch_array($res_x)) {
				$k++;
				mysql_query("update m1 set type=".$k." where id=".$row_x['id']);
			}
		}*/

//$result = mysql_query("select * from m4 order by id asc");
//while ($row = mysql_fetch_array($result)) {
//    mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row['sponsor_login']."', 5000, '".$row['user_login']."', 'Этап 4', '".date('Y-m-d H:i:s')."')");
    /*$result_x = mysql_query("select * from m2 where user_login='".$row['sponsor_login']."'");
    if (mysql_num_rows($result_x) > 0) {
    	$row_x = mysql_fetch_array($result_x);
		mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_x['sponsor_login']."', 1000, '".$row['user_login']."', 'Этап 2', '".date('Y-m-d H:i:s')."')");
    }*/
//}

?>