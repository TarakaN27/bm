<?php
include("db_connect.php");
$vyvod_id = intval($_POST['vyvod_id']);
$result = mysql_query("select login, amount from vyvod where status<=2 and id=".$vyvod_id);
if (mysql_num_rows($result) > 0) {
	$row = mysql_fetch_array($result);
	mysql_query("update users set akwa=akwa+".$row['amount']." where login='".$row['login']."'");
	mysql_query("update vyvod set status=2 where id=".$vyvod_id);
}
?>