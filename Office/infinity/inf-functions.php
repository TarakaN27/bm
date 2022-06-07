<? 

$packages = [
		0=>["name"=>"Старт","price"=>"33000", "pv"=>"0.25", "ref"=>"3000", "leader"=>"1000"],
		1=>["name"=>"Бизнес","price"=>"66000", "pv"=>"0.5", "ref"=>"6000", "leader"=>"2000"],
		2=>["name"=>"Премиум","price"=>"264000", "pv"=>"2", "ref"=>"24000", "leader"=>"8000"],
		3=>["name"=>"Акционер","price"=>"1188000", "pv"=>"9", "ref"=>"108000", "leader"=>"36000"]
	];

function debug($var){
	echo "<pre style='text-align:left'>";
	var_dump($var);
	echo "</pre>";
}

function checkMyTable($id){
	$tables_arr = ["infinity3","infinity2","infinity1"];
	$tables = "";
	foreach($tables_arr as $table){
		if(isset(getPeopleOne($id, $table)["user_id"])) {
			$tables = $table;
			break;
		}
	}
	return $tables;
}

function bonusRef($sponsor_id, $pack, $old_pack = false){
	global $packages;
	$get_my = getUserDataByID($sponsor_id);
	$sum = $packages[$pack-1]["ref"];
	if($old_pack>0){
		$sum_old = $packages[$old_pack-1]["ref"];
		$sum = $sum-$sum_old;
	}
	if($get_my["block_bonus"] == 0){
		save("UPDATE `users` SET `akwa`=`akwa`+'".$sum."' WHERE `id`='".$sponsor_id."'", $sponsor_id, "Реферальный бонус", "bonus-ref");
		return $sum;
	}
}


function bonusFastStart($id, $pack, $old_pack = false){
	global $options;
	
	$sum_bonus = [2000, 4000, 16000, 72000];
	
	if($old_pack>0){
		$sum_bonus[$pack-1] -= $sum_bonus[$old_pack-1];
	}
	
	$get_my = getUserDataByID($id);
	$get_my_pack = $get_my["infinity_package"];
	if($options["bonus-faststart"] == 1 && $get_my["block_bonus"] == 0) {
		
		$date_start = date('Y-m-d H:i:s', strtotime($get_my["reg_infinity"]));
		$date_end = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s", strtotime($date_start)).'+6 day'));
		
		$get_my_refs = find("SELECT `id` FROM `users` WHERE `sponsor`='".$get_my["login"]."'");
		$my_refs = [];
		foreach($get_my_refs as $row){
			$my_refs[] = $row["id"];
		}

		$ref_ids = implode(", ", $my_refs);
		$get_refs_table = find("SELECT * FROM `history` WHERE `type`='add-inf' AND `user_id` IN (".$ref_ids.") AND `date` between '".$date_start."' and '".$date_end."'");
		$count = count($get_refs_table);

		$sum = 0;
		if(strtotime($date_start) <= strtotime(date("Y-m-d H:i:s")) && strtotime(date("Y-m-d H:i:s")) <= strtotime($date_end) && $get_my_pack == 1 && $count>=5) { $sum = $sum_bonus[$pack-1]; }
		if(strtotime($date_start) <= strtotime(date("Y-m-d H:i:s")) && strtotime(date("Y-m-d H:i:s")) <= strtotime($date_end) && $get_my_pack == 2 && $count>=5) { $sum = $sum_bonus[$pack-1]; }
		if($get_my_pack == 3) { $sum = $sum_bonus[$pack-1]; }
		if($get_my_pack == 4) { $sum = $sum_bonus[$pack-1]; }
		if($sum>0){
			save("UPDATE `users` SET `akwa`=`akwa`+'".$sum."' WHERE `id`='".$id."'", $id, "Быстрый Старт Бонус", "bonus-faststart");
		}
		return 1;
		
	}
	
	
}

function bonusBoom($pv, $pv_two, $my_package, $id, $package){	
	$history = findOne("SELECT * FROM history WHERE user_id='".$id."' AND type='bonus-boom' ORDER BY id DESC");	
	$old = empty($history)? 0: $history["temp_val"];
	$sum = 0;
	$pv_new = 0;
	
	$sum_arr = [
		"infinity1"=>[2=>10000,4=>20000,8=>40000,16=>50000,32=>100000,64=>200000],
		"infinity2"=>[2=>250000,4=>500000,8=>1000000,16=>2000000,32=>4000000,64=>8000000],
		"infinity3"=>[2=>20000000,4=>40000000,8=>50000000,16=>60000000,32=>80000000,64=>140000000]
	];
	
	if($pv>=2 && $pv<4 && $old<2) {$pv_new=2;}
	if($pv>=4 && $pv<8 && $old<4) {$pv_new=4;}
	if($pv>=8 && $pv<16 && $old<8) {$pv_new=8;}
	if($pv>=16 && $pv<32 && $old<16) {$pv_new=16;}
	if($pv>=32 && $pv<64 && $old<32) {$pv_new=32;}
	if($pv>=64 && $old<64) {$pv_new=64;}
	
	$get_my = getUserDataByID($id);
	
	$label_arr = [
		"infinity1"=>"G",
		"infinity2"=>"BD",
		"infinity3"=>"A"
	];
	
	for($i=$old+1; $i<=$pv_new; $i++){
		$sum = isset($sum_arr[$package][$i]) && $sum_arr[$package][$i]>0 ? $sum_arr[$package][$i]: 0;
		if($my_package == 1) {$sum = $sum/2;}
		
		$true = $pv_two >= $i ? true: false;
		
		if($sum > 0 && $get_my["block_bonus"] == 0 && $pv_new>0 && $true===true) {			
			save("UPDATE `users` SET `akwa`=`akwa`+'".$sum."' WHERE `id`='".$id."'", $id, "Boom бонус(".$label_arr[$package].")", "bonus-boom", $i);
		}
	}	
	return 1;
}

function bonusBinar($pv, $pv_two, $my_package, $id, $package){	
	$history = findOne("SELECT * FROM history WHERE user_id='".$id."' AND type='bonus-binar' ORDER BY id DESC");	
	$old = empty($history)? 0: $history["temp_val"];
	$sum = 0;
	$pv_new = 0;
	
	$sum_arr = [
		"infinity1"=>[2=>10000,4=>20000,8=>40000,16=>50000,32=>100000,64=>200000],
		"infinity2"=>[2=>250000,4=>500000,8=>1000000,16=>2000000,32=>4000000,64=>8000000],
		"infinity3"=>[2=>20000000,4=>40000000,8=>50000000,16=>60000000,32=>80000000,64=>140000000]
	];
	
	if($pv>=2 && $pv<4 && $old<2) {$pv_new=2;}
	if($pv>=4 && $pv<8 && $old<4) {$pv_new=4;}
	if($pv>=8 && $pv<16 && $old<8) {$pv_new=8;}
	if($pv>=16 && $pv<32 && $old<16) {$pv_new=16;}
	if($pv>=32 && $pv<64 && $old<32) {$pv_new=32;}
	if($pv>=64 && $old<64) {$pv_new=64;}
	
	$get_my = getUserDataByID($id);
	
	$label_arr = [
		"infinity1"=>"G",
		"infinity2"=>"BD",
		"infinity3"=>"A"
	];
	
	for($i=$old+1; $i<=$pv_new; $i++){
		$sum = isset($sum_arr[$package][$i]) && $sum_arr[$package][$i]>0 ? $sum_arr[$package][$i]: 0;
		if($my_package == 1) {$sum = $sum/2;}
		
		$true = $pv_two >= $i ? true: false;
		
		if($sum > 0 && $get_my["block_bonus"] == 0 && $pv_new>0 && $true===true) {
			save("UPDATE `users` SET `akwa`=`akwa`+'".$sum."' WHERE `id`='".$id."'", $id, "Binar бонус(".$label_arr[$package].")", "bonus-binar", $i);
		}
	}	
	return 1;
}

function bonusStatus($id, $pv_left, $pv_right, $package){
	$count = 64;
	$sum_arr = [
		"infinity1"=>200000,
		"infinity2"=>8000000,
		"infinity3"=>150000000
	];
	$sum = $pv_left >= $count && $pv_right >= $count ? $sum_arr[$package]: 0;
	
	$get_history = findOne("SELECT * FROM `history` WHERE `type`='bonus-status' AND `user_id`='".$id."' AND `temp_val`='".$package."'");
	
	$label_arr = [
		"infinity1"=>"За ранг GOLD",
		"infinity2"=>"За ранг Black Diamond",
		"infinity3"=>"За ранг Ambasador"
	];
	
	if($sum > 0 && (!isset($get_history) || count($get_history) == 0) && getUserDataByID($id)["block_bonus"] == 0) {
		save("UPDATE `users` SET `akwa`=`akwa`+'".$sum."' WHERE `id`='".$id."'", $id, $label_arr[$package], "bonus-status", $package);
	}
	return 1; 
}

function bonusSponsor($sponsor_id, $package){
	$sum_arr = [
		"infinity1"=>100000,
		"infinity2"=>1000000,
		"infinity3"=>5000000
	];
	if(getUserDataByID($sponsor_id)["block_bonus"] == 0){
		save("UPDATE `users` SET `akwa`=`akwa`+'".$sum_arr[$package]."' WHERE `id`='".$sponsor_id."'", $sponsor_id, "Спонсорский бонус", "bonus-sponsor");
		return 1;
	}
}

function bonusLeader($id){
	global $packages;
	$count = 64;
	$leader = getUserDataByID($id);
	$pack = $leader["infinity_package"];
	$get = getPeopleOne($id, "infinity1");
	$sum = isset($get["user_id"]) && $get["pv_left"]>=$count && $get["pv_right"]>=$count ? $packages[$pack-1]["leader"]: 0;
	
	if($sum > 0 && $leader["block_bonus"] == 0) {
		save("UPDATE `users` SET `akwa`=`akwa`+'".$sum."' WHERE `id`='".$id."'", $id, "Лидерский бонус", "bonus-leader");
	}
	return 1;	
}

function getUserData($login){
	return findOne("SELECT * FROM users WHERE login='".$login."'");
}
function getUserDataByID($id){
	return findOne("SELECT * FROM users WHERE id='".$id."'");
}

function getPeopleOne($id, $package){
	return findOne("SELECT * FROM ".$package." WHERE user_id='".$id."'");
}

function getPeople($id, $package){
	return find("SELECT * FROM ".$package." WHERE user_id='".$id."'");
}
function getTeacher($id, $package){
	return find("SELECT * FROM ".$package." WHERE teacher='".$id."'");
}
function getLeader($id, $package){
	return find("SELECT * FROM ".$package." WHERE leader='".$id."'");
}

function checkCountRef($id, $package){
	$sponsor_name = getUserDataByID($id)["login"];
	$sponsor_ref = find("SELECT id FROM users WHERE sponsor='".$sponsor_name."'");
	$sponsor_ids = [];
	foreach($sponsor_ref as $row){
		$sponsor_ids[] = $row["id"];
	}
	$sponsor_ref_ids = implode(", ", $sponsor_ids);
	$check_count = count(find("SELECT * FROM ".$package." WHERE user_id IN (".$sponsor_ref_ids.")"));
	return $check_count;
}

function getRefs($id, $package){
	$sponsor_name = getUserDataByID($id)["login"];
	$sponsor_ref = find("SELECT id FROM users WHERE sponsor='".$sponsor_name."'");
	$sponsor_ids = [];
	foreach($sponsor_ref as $row){
		$sponsor_ids[] = $row["id"];
	}
	$sponsor_ref_ids = implode(", ", $sponsor_ids);
	$refs = find("SELECT * FROM ".$package." WHERE user_id IN (".$sponsor_ref_ids.")");
	return $refs;
}

function getFloat($sponsor, $package, $find_id){
	$res = getStructure($find_id, $package);
	return $res[$sponsor];
}

function findPlaceCentral($id, $package){
	$ids = is_array($id) ? $id: [$id];
	$ids_str = implode(", ", $ids);
	$query = find("SELECT * FROM ".$package." WHERE user_id IN (".$ids_str.")");
	$ids = [];
	foreach($query as $row){
		if($row["left_partner"]==0 || $row["right_partner"]==0) {
			$pos_float = $row["left_partner"] == 0 ? "left": "right";
			return ["id"=>$row["user_id"], "pos_float"=>$pos_float];
		} else {
			$ids[] = $row["left_partner"];
			$ids[] = $row["right_partner"];
			return findPlaceRight($ids, $package);
		}
	}		
}

function findPlaceRight($id, $package){
	$ids = is_array($id) ? $id: [$id];
	$ids_str = implode(", ", $ids);
	$query = find("SELECT * FROM ".$package." WHERE user_id IN (".$ids_str.")");
	$ids = [];
	foreach($query as $row){
		if($row["right_partner"]==0) {
			return ["id"=>$row["user_id"], "pos_float"=>"right"];
		} else {
			$ids[] = $row["right_partner"];
			return findPlaceRight($ids, $package);
		}
	}		
}

function findPlaceLeft($id, $package){
	$ids = is_array($id) ? $id: [$id];
	$ids_str = implode(", ", $ids);
	$query = find("SELECT * FROM ".$package." WHERE user_id IN (".$ids_str.")");
	$ids = [];
	foreach($query as $row){
		if($row["left_partner"]==0) {
			return ["id"=>$row["user_id"], "pos_float"=>"left"];
		} else {
			$ids[] = $row["left_partner"];
			return findPlaceLeft($ids, $package);
		}
	}		
}

$tempId = [];
function getStructure($id, $package, $old_id = false){
	global $tempId;
	$query = find("SELECT * FROM ".$package." WHERE user_id='".$id."'");
	if(count($query)>0) {
		$pos = $query[0]["left_partner"] == $old_id ? "left": "right";
		$tempId[$id] = ["position"=>$pos];
		return getStructure($query[0]["teacher"], $package, $id);
	} else {
		$tempIds = $tempId;
		$tempId = [];
		return $tempIds;
	}
}


function checkStructure($structure, $package) {
	#$structure = [ id=>[position=>left], ]
	$structure = array_reverse($structure, true);
	foreach($structure as $id=>$p) {
		$get_people = getPeopleOne($id, $package);
		if($get_people["pv_left"]>=64 && $get_people["pv_right"]>=64) { #Если набрал 64 с двух сторон
			$package_new_int = substr($package, 8)+1;
			if($package_new_int <= 3){
				$package_new_str = "infinity".$package_new_int;
				if(count(getPeople($get_people["user_id"], $package_new_str)) == 0) { #Проверяем есть ли такой учитель в пакете
					$sponsor_id = getUserData(getUserDataByID($id)["sponsor"])["id"]; #Получаем ид спонсора
					if(count(getPeople($get_people["teacher"], $package_new_str)) > 0) { # Есть ли наш учитель на новом столе
						$person = $get_people["teacher"];
					} elseif(count(getPeople($sponsor_id, $package_new_str)) > 0) { #если учителя нет то идем по спонсору
						$person = $sponsor_id;
						$find = false;
						while($find == false){
							if(count(getPeople($person, $package_new_str))>0){
								$find = true;
							} else {
								$person = getUserData(getUserDataByID($person)["sponsor"])["id"];
							}
						}
					}

					$structure = getStructure($person, $package_new_str, $my);
					$count = checkCountRef($person, $package_new_str);
					if($count>=2) { #Если кнопки включены
						$pos_float = getPeople($person, $package_new_str)[0]["pos_float"];
					} else {  #Если кнопки выключены
						$count = checkCountRef($sponsor_id, $package_new_str);
						if($count>=2) { #Если у спонсора кнопки включены
							$pos_float = getPeople($sponsor_id, $package_new_str)[0]["pos_float"];
							if(count(getPeople($sponsor_id, $package_new_str)) == 0) {
								$pos_float = "left";
							}
						} else {  #Если у спонсора кнопки выключены
							$temp_sponsor = $sponsor_id;
							while(!empty($temp_sponsor)) {
								$sponsor_idz = getUserData(getUserDataByID($temp_sponsor)["sponsor"])["id"];
								$count = checkCountRef($sponsor_idz, $package_new_str);
								if($count>2) {
									$pos_float = getPeople($sponsor_idz, $package_new_str)[0]["pos_float"];
									$temp_sponsor = "";
								} else {
									$temp_sponsor = $sponsor_idz;
								}
							}	
							
							if(empty($pos_float)){
								$get_my = getUserDataByID($sponsor_id);
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
						$position = findPlaceRight($person, $package_new_str); #Добавляем нас к этому человеку( ["id"=>1, "pos_float"=>right] )
					} elseif($pos_float == "left") {
						$position = findPlaceLeft($person, $package_new_str); #Добавляем нас к этому человеку( ["id"=>1, "pos_float"=>left] )
					}

					save("INSERT INTO `".$package_new_str."` (`user_id`, `leader`, `teacher`) VALUES ('".$get_people["user_id"]."', '".$get_people["leader"]."', '".$position["id"]."')", $get_people["user_id"], "Переход на другой стол", "change-inf"); #Добавляем человека на стол
					save("UPDATE `".$package_new_str."` SET `".$position["pos_float"]."_partner`='".$get_people["user_id"]."' WHERE `user_id`='".$position["id"]."'"); #Ставим у учителя

				}

			}
			
			#Бонусы при закрытии стола

			$bonusSponsor = bonusSponsor($sponsor_id, $package); # Спонсорский бонус(sql)
			$bonusStatus = bonusStatus($get_people["user_id"], $get_people["pv_left"], $get_people["pv_right"], $package); # Статусный бонус(sql)
			
		}
		#Если не набрал 64 с двух сторон и остался на столе

		$my_data = getUserDataByID($get_people["user_id"]);
		if(!empty($my_data["infinity_package"])) {
			$my_package = $my_data["infinity_package"];
			$bonusBoom = bonusBoom($get_people["pv_left"], $get_people["pv_right"], $my_package, $get_people["user_id"], $package); # MultiBoom бонус(sql)
			$bonusBinar = bonusBinar($get_people["pv_right"], $get_people["pv_left"], $my_package, $get_people["user_id"], $package); # Binar бонус(sql)
		}
	}
}
?>