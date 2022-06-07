<?php

session_start();
include("functions.php");
include("inf-functions.php");
date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");
$my = findOne("select * from users where login='".$_SESSION['login']."'");
$_SESSION["id"]=$my["id"];


if (isset($my["id"]) && isset($_GET["package"])) {
	$pack_id = !empty($_GET["package"]) ? $_GET["package"]: 1;
	$pay_sum = $_GET["type_pay"]=="balans"? $my["akwa"]: $my["balans_turbo"];
	
	if($packages[$pack_id-1]["price"] <= $pay_sum) {
		
		$package = "infinity1"; # infinity1

		$teacher = !empty($_GET["teacher"]) && count(getUserData($_GET["teacher"]))>0 ? getUserData($_GET["teacher"])["id"]: NULL;	 # Ид учителя
		if(!empty($_GET["leader"]) && count(getUserData($_GET["leader"]))>0){
			$leader = getUserData($_GET["leader"])["id"]; # Ид лидера
			$get_leader = getPeopleOne($leader, "infinity1");
			$leader = !empty($get_leader["user_id"]) && $get_leader["pv_left"]>=64 && $get_leader["pv_right"]>=64 ? $leader: NULL;
		} else {
			$leader = NULL; # Ид лидера
		}
		
		if(count(getPeople($my["id"], $package)) == 0) { #Если мы еще не купили пакет ранее, то

			$my_sponsor = getUserData($my["sponsor"])["id"];

			if(!empty($teacher)) { #Если указали учителя, то работаем по учителю
				$person = $teacher;
				$type = "teacher";
			} else { #Если никого не указали работаем без лидера и наставника
				$person = $my_sponsor;
				$type = "sponsor";
			}
			
			$count_sponsor_ref = checkCountRef($my_sponsor, $package);
			$person = $count_sponsor_ref<=1 ? $my_sponsor: $person;

			if(count(getPeople($person, $package)) == 0) { #Проверяем есть ли такой учитель в пакете
				$find = false;
				$person = $my_sponsor;
				while($find == false){
					if(count(getPeople($person, $package))>0){
						$find = true;
					} else {
						$person = getUserData(getUserDataByID($person)["sponsor"])["id"];
					}
				}
			}

			$structure = getStructure($person, $package, $my);

			if($count_sponsor_ref == 1) {
				$refs = getRefs($my_sponsor, $package);
				$single_ref = getFloat($my_sponsor, $package, $refs[0]["user_id"]);
				$pos_float = $single_ref["position"] == "left" ? "right": "left";
			} else {	
				$count = checkCountRef($person, $package);
				if($count>=2) { #Если кнопки включены
					$pos_float = getPeople($person, $package)[0]["pos_float"];
				} else {  #Если кнопки выключены
					$count = checkCountRef($my_sponsor, $package);
					if($count>=2) { #Если у спонсора кнопки включены
						$pos_float = getPeople($my_sponsor, $package)[0]["pos_float"];
						if(count(getPeople($my_sponsor, $package)) == 0) {
							$pos_float = "left";
						}
					} else {  #Если у спонсора кнопки выключены
						$pos_float = "left";
					}
				}
			}

			if($pos_float == "right") {
				$position = findPlaceRight($person, $package); #Добавляем нас к этому человеку( ["id"=>1, "pos_float"=>right] )
			} elseif($pos_float == "left") {
				$position = findPlaceLeft($person, $package); #Добавляем нас к этому человеку( ["id"=>1, "pos_float"=>left] )
			}					
			
			if($_GET["type_pay"]=="balans")	{
				save("UPDATE `users` SET `akwa`=`akwa`-'".$packages[$pack_id-1]["price"]."', `infinity_package`='".$pack_id."', `reg_infinity`='".date("Y-m-d H:i:s")."' WHERE `id`='".$my["id"]."'");
			} else {
				save("UPDATE `users` SET `balans_turbo`=`balans_turbo`-'".$packages[$pack_id-1]["price"]."', `infinity_package`='".$pack_id."', `reg_infinity`='".date("Y-m-d H:i:s")."' WHERE `id`='".$my["id"]."'");
			}			
			
			save("INSERT INTO `".$package."` (`user_id`, `leader`, `teacher`) VALUES ('".$my["id"]."', '".$leader."', '".$position["id"]."')", $my["id"], "Вход на стол", "add-inf"); #Добавляем человека на стол
			save("UPDATE `".$package."` SET `".$position["pos_float"]."_partner`='".$my["id"]."' WHERE `user_id`='".$position["id"]."'"); #Ставим у учителя
			
			$buy_package = $pack_id+9;
			$promotion = findOne("SELECT * FROM `options` WHERE `name`='promotion'")["value"];
			save("INSERT INTO `buy_packages` SET `user_id`='".$my["id"]."', `package`='".$buy_package."', `date`='".date("Y-m-d H:i:s")."', `promotion`='".$promotion."'");

			$structure2 = getStructure($position["id"], $package, $my["id"]); #Получаем всю структуру наверх
			if(count($structure2)>0){
				foreach($structure2 as $id=>$p) { #Выдаём всей структуре PV
					if($my["id"] != $id) {
						save("UPDATE `".$package."` SET `pv_".$p["position"]."`=`pv_".$p["position"]."`+'".$packages[$pack_id-1]["pv"]."' WHERE `user_id`='".$id."'", $id, "PV ".$packages[$pack_id-1]["pv"]." получил по структуре", "add-inf-pv", $packages[$pack_id-1]["pv"]);
					}
				}
			}

			$bonusRef = bonusRef($my_sponsor, $pack_id); # Реферальный бонус(sql)
			$bonusFastStart = bonusFastStart($my_sponsor, $pack_id); # Быстрый Старт бонус
			$bonusLeader = 0;
			if(isset($leader) && $leader > 0) {
				$bonusLeader = bonusLeader($leader); # Лидерский бонус(sql)
			}

			$check_structure = checkStructure($structure2, $package); #Проверяем количество PV у структуры, и кого нужно переносим на другой стол

			echo json_encode(["msg"=>"ok", "position"=>$position, "bonusRef"=>$bonusRef, "bonusLeader"=>$bonusLeader]);
		} else {
			echo json_encode(["msg"=>"Вы уже купили этот пакет"]);
		}
		
	} else {
		echo json_encode(["msg"=>"У вас недостаточно средств на балансе"]);
	}


	
} else {
	header("Location: ../index.php");
	die();
}

?>
