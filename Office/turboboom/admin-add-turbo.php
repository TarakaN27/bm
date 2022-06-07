<?php
session_start();

include($_SERVER["DOCUMENT_ROOT"]."/Office/global.php");
include("functions.php");
include("t-functions.php");

date_default_timezone_set('Asia/Almaty');

if(isset($_GET["change-id"]) && isset($_GET["level"]) && isset($_GET["hash"]) && $_GET["hash"]==md5("BoomMarket")){
	
	$table = $turbo_levels[$_GET["level"]]["table"];
	$level = $turbo_levels[$_GET["level"]]["level"];
	
	$get_user = findOne("SELECT * FROM users WHERE id='".$_GET["change-id"]."'");
	if(isset($get_user["id"])){
			$get_table = findOne("SELECT * FROM `".$table."` WHERE user_id='".$_GET["change-id"]."' AND level='".$level."'");
			if(!isset($get_table["id"])){
				$sponsor_id = getSponsor($_GET["change-id"], $table, $level);
				$curator_id = getCuratorColumn($sponsor_id, $table, $level);

				save("INSERT INTO ".$table." (user_id, parent_id, date, level) VALUES (".$_GET["change-id"].", '".$curator_id."', '".date("Y-m-d H:i:s")."', ".$level.")", $_SESSION["id"], "Выдача TurboColumn", "set-turbo");
				
				if($_GET["level"]==1 || $_GET["level"]==3){
					$package = $_GET["level"]==1 ? 14: 15;
					$promotion = findOne("SELECT * FROM `options` WHERE `name`='promotion'")["value"];
					save("INSERT INTO `buy_packages` SET `user_id`='".$_GET["change-id"]."', `package`='".$package."', `date`='".date("Y-m-d H:i:s")."', `promotion`='".$promotion."'");
				}

				$get_mysponsor_id = findOne("SELECT * FROM users WHERE login='".$get_user["sponsor"]."'")["id"];
				
				if($_GET["level"] == 3){
					$admin = 1;
				}
				
				$admin = $_GET["level"]==3 ? true: false;
				
				bonusRef($get_mysponsor_id, $level, $admin); ## Реферальный бонус
				
				if($curator_id>0){
					checkCurator($curator_id, $table, $level);
				}
				
				echo '<script>window.location.href = "/Office/persons.php?action=edit&edit-id='.$_GET["change-id"].'&back-url='.$back_url.'"</script>';
				#echo json_encode(["msg"=>'ok']);
			} else {
				echo json_encode(["msg"=>'Вы уже купили этот пакет']);
			}		
	} else {
		echo json_encode(["msg"=>'Такого пользователя не существует']);
	}
} else {
	echo json_encode(["msg"=>'Неверные входные данные']);
}
?>