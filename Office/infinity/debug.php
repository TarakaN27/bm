<?php
#ini_set('error_reporting', E_ALL);
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#ini_set('session.cookie_domain', '.bm-market.kz' );
session_start();
include("../db_connect.php");
include "../smsc_api.php";
include("../b_func.php");

include("functions.php");
include("inf-functions.php");

/*if(isset($_POST["action"]) && $_POST["action"] == "clear") {
	save("DELETE FROM `history` WHERE id!=1");
	save("DELETE FROM `infinity1` WHERE user_id!=1 AND user_id!=82 AND user_id!=83");
	save("DELETE FROM `infinity2` WHERE user_id!=1 AND user_id!=82 AND user_id!=83");
	save("DELETE FROM `infinity3` WHERE user_id!=1 AND user_id!=82 AND user_id!=83");
	save("UPDATE infinity1 SET pv_left=0, pv_right=0, left_partner=0, right_partner=0, pos_float='left'");
	save("UPDATE infinity2 SET pv_left=0, pv_right=0, left_partner=0, right_partner=0, pos_float='left'");
	save("UPDATE infinity3 SET pv_left=0, pv_right=0, left_partner=0, right_partner=0, pos_float='left'");
	
	save("UPDATE infinity1 SET left_partner=82, right_partner=83 WHERE user_id=1");
	save("UPDATE infinity2 SET left_partner=82, right_partner=83 WHERE user_id=1");
	save("UPDATE infinity3 SET left_partner=82, right_partner=83 WHERE user_id=1");
	
	save("UPDATE users SET infinity_package=0, reg_infinity='0000-00-00 00:00:00'");
	save("UPDATE users SET infinity_package=1, reg_infinity='2022-03-30 10:20:00' WHERE id=1 OR id=82 OR id=83");
	echo '<script>window.location.href = "/Office/infinity/debug.php"</script>';
}*/

if(isset($_GET["login"])) {
	$query = findOne("SELECT * FROM users WHERE login='".$_GET["login"]."'");
	$_SESSION["login"] = $query["login"];
	$_SESSION["id"] = $query["id"];
}
debug($_SESSION);
?>

<form method="GET">
	<input type="text" name="login">
	<input type="submit" value="Ок">
</form>

<form method="POST">
	<input type="text" name="action" required value="" placeholder="Введите: clear">
	<input type="submit" value="Очистить таблицы Infinity">
</form>


<?

/*
function bonusFastStartDebug($id, $pack, $date){
	global $options;
	
	$sum_bonus = [2000, 4000, 16000, 72000];
	$get_my = getUserDataByID($id);
	if($get_my["block_bonus"] == 0) {
		
		$date_start = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)).'-7 day'));
		$date_end = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)).'+1 day'));

		$get_my_refs = find("SELECT `id` FROM `users` WHERE `sponsor`='".$get_my["login"]."'");
		$my_refs = [];
		foreach($get_my_refs as $row){
			$my_refs[] = $row["id"];
		}
		
		$ref_ids = implode(", ", $my_refs);
		$get_refs_table = find("SELECT * FROM `history` WHERE `type`='bonus-ref' AND `from` IN (".$ref_ids.") AND `date` between '".$date_start."' and '".$date_end."'");
		$count = count($get_refs_table);
		
		$sum = 0;
		if($pack == 1 && $count>=5) { $sum = $sum_bonus[$pack-1]; }
		if($pack == 2 && $count>=5) { $sum = $sum_bonus[$pack-1]; }
		if($pack == 3) { $sum = $sum_bonus[$pack-1]; }
		if($pack == 4) { $sum = $sum_bonus[$pack-1]; }
		
		if($sum>0){
			#save("UPDATE `users` SET `akwa`=`akwa`+'".$sum."' WHERE `id`='".$id."'", $id, "Быстрый Старт Бонус", "bonus-faststart");
			print_r("UPDATE `users` SET `akwa`=`akwa`+'".$sum."' WHERE `id`='".$id."'<br>");
		}
		return 1;
	}
}

$get_history = find("SELECT history.*, u.reg_infinity, u.infinity_package FROM history LEFT JOIN users as u ON u.id=history.from WHERE history.type='bonus-ref'");

foreach($get_history as $row){
	$refBonus = trim(explode(" ",str_replace("UPDATE users SET akwa=akwa+", "", $row["text"]))[0]);
	$id = $row["user_id"];
	$pack = $row["infinity_package"];
	$date_func = $row["date"];
	bonusFastStartDebug($id, $pack, $date_func);
}
*/


$query = find("SELECT * FROM `history` WHERE `type`='cash-shareholder' AND (`date` BETWEEN'2022-05-30 23:59:59' AND '2022-06-01 23:59:59')");
foreach($query as $row){
	preg_match_all("/[\d]+/",$row["text"],$amount);
	#debug($amount[0][0]);
	#print_r("UPDATE `users` SET `akwa`=`akwa`-'".$amount[0][0]."' WHERE `id`='".$row["user_id"]."'<br>");
}
?>

