<?php
include("db_connect.php");
$vyvod_id = intval($_POST['vyvod_id']);
mysql_query("update vyvod set status=1 where id=".$vyvod_id);
?>