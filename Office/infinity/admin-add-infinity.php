<?php

session_start();
include("functions.php");
include("inf-functions.php");
date_default_timezone_set('Asia/Almaty');

if(empty( $_SESSION['login'])) header("Location: ../login.php");
$my = findOne("select * from users where id='".$_GET["change-id"]."'");

if(isset($_GET["back-url2"])) {
	$back_url = $_GET["back-url2"];
}

if (isset($my["id"]) && isset($_GET["package"]) && $_GET["hash"]==md5("BoomMarket")) {
		
		$package = "infinity".$_GET["package"]; # infinity1

		$teacher = isset($_GET["teacher"]) && count(getUserData($_GET["teacher"]))>0 ? getUserData($_GET["teacher"])["id"]: NULL;	 # Ид учителя
	
		if(isset($_GET["leader"]) && count(getUserData($_GET["leader"]))>0){
			$leader = getUserData($_GET["leader"])["id"]; # Ид лидера
			$get_leader = getPeopleOne($leader, "infinity1");
			$leader = isset($get_leader["user_id"]) && $get_leader["pv_left"]>=64 && $get_leader["pv_right"]>=64 ? $leader: NULL;
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

			if(count(getPeople($person, $package)) > 0) { #Проверяем есть ли такой учитель в пакете

				$structure = getStructure($person, $package, $my);
				$count = checkCountRef($person, $package);
				if($count>=2) { #Если кнопки включены
					$pos_float = getPeople($person, $package)[0]["pos_float"];
				} else {  #Если кнопки выключены
					$count = checkCountRef($my_sponsor, $package);
					if($count>=2) { #Если у спонсора кнопки включены
						$pos_float = getPeople($my_sponsor, $package)[0]["pos_float"];
					} else {  #Если у спонсора кнопки выключены
						$temp_sponsor = $my_sponsor;
						$pos_float = "left";
						while(!empty($temp_sponsor)) {
							$sponsor_id = getUserData(getUserDataByID($temp_sponsor)["sponsor"])["id"];
							$count = checkCountRef($sponsor_id, $package);
							if($count>2) {
								$pos_float = getPeople($sponsor_id, $package)[0]["pos_float"];
								$temp_sponsor = "";
							} else {
								$temp_sponsor = $sponsor_id;
							}
						}	
						
						if(empty($pos_float)){
							$get_my = getUserDataByID($my_sponsor);
							$get_my_refs = find("SELECT `id` FROM `users` WHERE `sponsor`='".$get_my["login"]."'");
							$my_refs = [];
							foreach($get_my_refs as $row){
								$my_refs[] = $row["id"];
							}
							$ref_ids = implode(", ", $my_refs);
							$get_refs_table = find("SELECT * FROM `".$package."` WHERE `user_id` IN (".$ref_ids.")");
							if(count($get_refs_table)>=2) {
								$pos_float = "left";
							} else {
								$pos_float = "right";
							}
						}
						
					}
				}
				
				if($pos_float == "right") {
					$position = findPlaceRight($person, $package); #Добавляем нас к этому человеку( ["id"=>1, "pos_float"=>right] )
				} elseif($pos_float == "left") {
					$position = findPlaceLeft($person, $package); #Добавляем нас к этому человеку( ["id"=>1, "pos_float"=>left] )
				}					

				save("UPDATE `users` SET `infinity_package`='".$_GET["package"]."', `reg_infinity`='".date("Y-m-d")."' WHERE `id`='".$my["id"]."'");
				save("INSERT INTO `".$package."` (`user_id`, `leader`, `teacher`, pay) VALUES ('".$my["id"]."', '".$leader."', '".$position["id"]."', '1')", $my["id"], "Вход на стол", "add-inf"); #Добавляем человека на стол
				save("UPDATE `".$package."` SET `".$position["pos_float"]."_partner`='".$my["id"]."' WHERE `user_id`='".$position["id"]."'"); #Ставим у учителя

				//$structure = getStructure($position["id"], $package, $my["id"]); #Получаем всю структуру наверх
				/*if(count($structure)>0){
					foreach($structure as $id=>$p) { #Выдаём всей структуре PV
						if($my["id"] != $id) {
							save("UPDATE `".$package."` SET `pv_".$p["position"]."`=`pv_".$p["position"]."`+'".$packages[$_GET["package"]-1]["pv"]."' WHERE `user_id`='".$id."'", $id, "PV ".$packages[$_GET["package"]-1]["pv"]." получил по структуре", "add-inf-pv", $packages[$_GET["package"]-1]["pv"]);
						}
					}
				}*/
				/*
				$bonusRef = bonusRef($my_sponsor); # Реферальный бонус(sql)
				#+$bonusFastStart = bonusFastStart($bonusRef, $my_package); # Быстрый Старт бонус
				$bonusLeader = 0;
				if(isset($leader) && $leader > 0) {
					$bonusLeader = bonusLeader($leader); # Лидерский бонус(sql)
				}
				*/
				/*$check_structure = checkStructure($structure, $package); #Проверяем количество PV у структуры, и кого нужно переносим на другой стол*/
				save("INSERT INTO `admin_history` (`admin_id`, `msg`, `date`, `type`, `args`) VALUES ('".$_SESSION["id"]."', 'Добавление уровня для @".$my['login']." (".$package.")', '".date("Y-m-d H:i:s")."', 'add-level', '0')");
		
				echo '<script>window.location.href = "/Office/persons.php?action=edit&edit-id='.$_GET["change-id"].'&back-url='.$back_url.'"</script>';

				echo "ok";

			} else {
				echo "Такого человека нет на столе";
			}

		} else {
			echo "Вы уже купили этот пакет";
		}	
	
} else {
	header("Location: ../index.php");
	die();
}

?>
