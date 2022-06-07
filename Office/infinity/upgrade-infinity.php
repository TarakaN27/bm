<?php

session_start();
include("functions.php");
include("inf-functions.php");
date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");
$my = findOne("select * from users where login='".$_SESSION['login']."'");
$_SESSION["id"]=$my["id"];
if (isset($my["id"]) && isset($_GET["package"])) {
	$pack_id = isset($_GET["package"]) ? $_GET["package"]: 1;
	
	$packages_temp = $packages;
	for($i=0; $i<count($packages); $i++) {							
		$packages[$i]["price"] = $i>=$my["infinity_package"] ? $packages_temp[$i]["price"] - $packages_temp[$my["infinity_package"]-1]["price"]: 0;		
	}
	
	if($packages[$pack_id-1]["price"] <= $my["akwa"]) {
		
		$package = checkMyTable($my["id"]); #(str)"infinity1"
		$old_package = $my["infinity_package"];
		$my_person = getPeople($my["id"], $package);
		if(count($my_person) >= 0) { #Если мы купили пакет ранее, то
			save("UPDATE `users` SET `akwa`=`akwa`-'".$packages[$pack_id-1]["price"]."', `infinity_package`='".$pack_id."', `reg_infinity`='".date("Y-m-d H:i:s")."' WHERE `id`='".$my["id"]."'", $my["id"], "Улучшение пакета", "upgrade-package");

			$teacher = getPeople($my_person[0]["teacher"], $package);
			$structure = getStructure($teacher[0]["user_id"], $package, $my["id"]); #Получаем всю структуру наверх
			if(count($structure)>0){
				foreach($structure as $id=>$p) {
					$pv_sum = $packages[$pack_id-1]["pv"] - $packages[$old_package-1]["pv"];
					save("UPDATE `".$package."` SET `pv_".$p["position"]."`=`pv_".$p["position"]."`+'".$pv_sum."' WHERE `user_id`='".$id."'", $id, "PV: ".$pv_sum." для ".$id, "add-inf-pv", $pv_sum);
				}
			}		
			
			$buy_package = $pack_id+9;
			$promotion = findOne("SELECT * FROM `options` WHERE `name`='promotion'")["value"];
			save("INSERT INTO `buy_packages` SET `user_id`='".$my["id"]."', `package`='".$buy_package."', `date`='".date("Y-m-d H:i:s")."', `promotion`='".$promotion."'");
			
			$my_sponsor = getUserData($my["sponsor"])["id"];
			$bonusRef = bonusRef($my_sponsor, $pack_id, $old_package); # Реферальный бонус(sql)
			$bonusFastStart = bonusFastStart($my_sponsor, $pack_id, $old_package); # Быстрый Старт бонус
			
			$check_structure = checkStructure($structure, $package); #Проверяем количество PV у структуры, и кого нужно переносим на другой стол

			echo json_encode(["msg"=>"ok"]);
		} else {
			echo json_encode(["msg"=>"Вы еще не купили пакет"]);
		}
		
	} else {
		echo json_encode(["msg"=>"У вас недостаточно средств на балансе"]);
	}
} else {
	header("Location: ../index.php");
	die();
}

?>
