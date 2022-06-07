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

$general_options_query = mysql_query("select * from options");
$general_options = [];
while(($row = mysql_fetch_assoc($general_options_query))) {
    $general_options[$row["name"]] = $row["value"];
}

function refresh(){
	echo '<script>window.location.href=""</script>';
}

?>