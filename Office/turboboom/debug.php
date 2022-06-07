<?php
#ini_set('error_reporting', E_ALL);
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
ini_set('session.cookie_domain', '.bm-market.kz' );
session_start();

include("../db_connect.php");
include("../b_func.php");

include("functions.php");
include("t-functions.php");

if(isset($_GET["login"])) {
	$query = findOne("SELECT * FROM users WHERE login='".$_GET["login"]."'");
	$_SESSION["login"] = $query["login"];
	$_SESSION["id"] = $query["id"];
}
debug($_SESSION);

$user_id = 1;
$table = "turbo_column";
$level = 3;
$get_wood = getWood($user_id, $table, $level);
debug($get_wood);

?>

<form method="GET">
	<input type="text" name="login">
	<input type="submit" value="Ок">
</form>
