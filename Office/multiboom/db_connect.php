<?php

ini_set('session.use_cookies', true);
	ini_set('session.save_path', '/var/lib/php5/');
include_once('db_data.php');
$con = mysql_connect($db_host, $db_user, $db_pass) 
	or die("Ведутся технические работы");

//select a database to work with
mysql_select_db($db_database, $con);

mysql_query("SET GLOBAL time_zone = '+6:00'");
mysql_set_charset("utf8", $con);
?>