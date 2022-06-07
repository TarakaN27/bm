<?php
session_start();
include("functions.php");
include("t-functions.php");

date_default_timezone_set('Asia/Almaty');

$price = "66000";
$table = "turbo_column";
$table_new = "turbo_wood";

#$people_arr = [1,82,83,3972,3973,3974,3975,3976,3977,3978,3979,3980,3981,3982,3984,3985,3986,3987,3988,3989,3990,3991,3992,3993,3994];
#foreach($people_arr as $people_id){

if(isset($_SESSION["id"])){
	
	$buy_user_id = $_SESSION["id"];
	#$buy_user_id = $people_id;
	
	$get_user = findOne("SELECT * FROM users WHERE id='".$buy_user_id."'");
	if(isset($get_user["id"])){
		if($get_user["akwa"] >= $price) {
			$get_table = findOne("SELECT * FROM `".$table."` WHERE user_id='".$buy_user_id."'");
			if(!isset($get_table["id"])){
				$level = 1;
				$curator_id = getCuratorColumn(1, $table, $level);
				
				save("INSERT INTO ".$table." (user_id, parent_id, date, level) VALUES (".$buy_user_id.", '".$curator_id."', '".date("Y-m-d H:i:s")."', ".$level.")", $buy_user_id, "Покупка TurboColumn", "buy-turbo");
				
				$promotion = findOne("SELECT * FROM `options` WHERE `name`='promotion'")["value"];
				save("INSERT INTO `buy_packages` SET `user_id`='".$buy_user_id."', `package`='14', `date`='".date("Y-m-d H:i:s")."', `promotion`='".$promotion."'");
				
				save("UPDATE users SET akwa=akwa-'".$price."' WHERE id='".$buy_user_id."'");
				
				$curator_id = getCuratorWood(5436, $table_new, $level);
				save("INSERT INTO ".$table_new." (user_id, parent_id, date, level) VALUES (".$buy_user_id.", '".$curator_id."', '".date("Y-m-d H:i:s")."', ".$level.")", $buy_user_id, "Покупка TurboWood", "buy-turbo");
				
				$get_mysponsor_id = findOne("SELECT * FROM users WHERE login='".$get_user["sponsor"]."'")["id"];
				
				bonusRef($get_mysponsor_id, $turbo_forward[$table][$level]); ## Реферальный бонус
				
				if($curator_id>0){
					checkCurator($curator_id, $table_new, $level);
				}
				
				echo json_encode(["msg"=>'ok']);
			} else {
				echo json_encode(["msg"=>'Вы уже купили этот пакет']);
			}
		} else {
			echo json_encode(["msg"=>'Недостаточно средств на балансе']);
		}			
	} else {
		echo json_encode(["msg"=>'Такого пользователя не существует']);
	}
} else {
	echo json_encode(["msg"=>'Вы не авторизаваны']);
}
	
#}
?>