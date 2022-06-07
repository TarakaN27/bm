<? 

#ini_set('error_reporting', E_ALL);
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);

include($_SERVER["DOCUMENT_ROOT"]."/Office/global.php");

$count_users_wood = 30;
$count_users_column = 10;
$max_tables = 7;

function debug($var){
	echo "<pre style='text-align:left'>";
	var_dump($var);
	echo "</pre>";
}

function bonusRef($sponsor_id, $level, $admin=false){
	$get_my = findOne("SELECT * FROM users WHERE id='".$sponsor_id."'");
	$ref_bonus_for_3 = findOne("SELECT * FROM `options` WHERE `name`='ref_bonus_3'")["value"];
	$sum = 0;
	if($admin==1 && $level==3) $sum = $ref_bonus_for_3;
	if($level==1) $sum = 6000;
	if($sum>0 && $get_my["block_bonus"]==0){
		save("UPDATE users SET akwa=akwa+'".$sum."' WHERE id='".$sponsor_id."'", $sponsor_id, "Реферальный бонус", "turbo-bonus-ref", $level);
	}
	return true;
}

function bonusSponsor($sponsor_id, $level){
	$get_my = findOne("SELECT * FROM users WHERE id='".$sponsor_id."'");
	$bonus_sum = [0, 6000, 0, 50000, 0, 500000, 0, 5000000, 0, 0, 0, 0, 0];
	$sum = $bonus_sum[$level-1];
	if($sum>0 && $get_my["block_bonus"]==0){
		save("UPDATE users SET akwa=akwa+'".$sum."' WHERE id='".$sponsor_id."'", $sponsor_id, "Спонсор бонус", "turbo-bonus-sponsor", $level);
	}
	return true;
}

function bonusCloseLevel($user_id, $level){
	$get_my = findOne("SELECT * FROM users WHERE id='".$user_id."'");
	$bonus_sum = [0, 70000, 300000, 250000, 1500000, 2500000, 11500000, 25000000, 50000000, 0, 0, 0, 0];
	$sum = $bonus_sum[$level-1];
	$bonus_name = [
		2 => "Закрытие - за ранг 1",
		3 => "ТУРБО БОНУС",
		4 => "Закрытие - за ранг 2",
		5 => "ТУРБО БОНУС",
		6 => "Закрытие - за ранг 3",
		7 => "ТУРБО БОНУС",
		8 => "Закрытие - за ранг 4",
		9 => "ТУРБО БОНУС",
	];
	if($sum>0 && $get_my["block_bonus"]==0){
		save("UPDATE users SET akwa=akwa+'".$sum."' WHERE id='".$user_id."'", $user_id, $bonus_name[$level], "turbo-bonus-close", $level);
	}
	return true;
}

function bonusMarketing($user_id, $level){
	$get_my = findOne("SELECT * FROM users WHERE id='".$user_id."'");
	$bonus_sum = [0, 0, 0, 100000, 0, 300000, 0, 2000000, 0, 0, 0, 0, 0];
	$sum = $bonus_sum[$level-1];
	if($level>1){
		if($sum>0 && $get_my["block_bonus"]==0){
			save("UPDATE users SET balans_turbo=balans_turbo+'".$sum."' WHERE id='".$user_id."'", $user_id, "Маркетинг бонус", "turbo-bonus-marketing", $level);
		}
	}
	return true;
}

function getRefs($id, $table, $level){
	$sponsor_name = findOne("SELECT * FROM users WHERE id='".$id."'")["login"];
	$sponsor_ref = find("SELECT id FROM users WHERE sponsor='".$sponsor_name."'");
	$sponsor_ids = [];
	foreach($sponsor_ref as $row){
		$sponsor_ids[] = $row["id"];
	}
	$sponsor_ref_ids = implode(", ", $sponsor_ids);
	$refs = find("SELECT t.*, u.login FROM ".$table." as t LEFT JOIN users as u ON u.id=t.user_id WHERE user_id IN (".$sponsor_ref_ids.") AND level='".$level."'");
	return $refs;
}

function array_unshift_assoc(&$arr, $key, $val)
{
    $arr = array_reverse($arr, true);
    $arr[$key] = $val;
    $arr = array_reverse($arr, true);
    return count($arr);
}

function getStructure($user_id, $table, $level){
	$returnIds = [];
	$tempIds = [];
	$stop = false;
	
	while(!$stop){
		if(count($returnIds)==0){
			$tempIds[] = $user_id;
		}
		$sponsor_ref_ids = implode(", ", $tempIds);
		$refs = find("SELECT t.*, u.login, u.fio FROM ".$table." as t LEFT JOIN users as u ON u.id=t.user_id WHERE parent_id IN (".$sponsor_ref_ids.") AND level='".$level."'");
		$tempIds = [];
		if(count($refs)>0) {
			foreach($refs as $row){
				$tempIds[] = $row["user_id"];
				$returnIds[$row["user_id"]] = $row;
			}
		} else {
			$stop = true;
		}
	}
	
	$my = findOne("SELECT t.*, u.login, u.fio FROM ".$table." as t LEFT JOIN users as u ON u.id=t.user_id WHERE user_id='".$user_id."' AND level='".$level."'");
	array_unshift_assoc($returnIds, $my["user_id"], $my);
	
	return $returnIds;
}

function getWood($user_id, $table, $level){
	$returnIds = [];
	$tempIds = [];
	$stop = false;
	while(!$stop){
		if(count($returnIds)==0){
			$tempIds[] = $user_id;
		}
		$sponsor_ref_ids = implode(", ", $tempIds);
		$refs = find("SELECT * FROM ".$table." WHERE parent_id IN (".$sponsor_ref_ids.") AND level='".$level."'");
		$tempIds = [];
		if(count($refs)>0) {
			foreach($refs as $row){
				$tempIds[] = $row["user_id"];
				$returnIds[] = $row["user_id"];
			}
		} else {
			$stop = true;
		}
	}
	return $returnIds;
}

function getSponsor($user_id, $table, $level){
	if(count(find("SELECT * FROM `".$table."` WHERE `level`='".$level."'"))==0) {
		return NULL;
	}
	$my = findOne("SELECT s.id as sponsor_id FROM users as u LEFT JOIN users as s ON u.sponsor=s.login WHERE u.id='".$user_id."'");
	$sponsor_id = $my["sponsor_id"];
	if(empty($sponsor_id)) return NULL;
	$true = false;
	while(!$true) {
		$check_sponsor = findOne("SELECT * FROM `".$table."` WHERE `user_id`='".$sponsor_id."' AND `level`='".$level."'");
		if(!empty($check_sponsor["id"])) {
			$true = true;
			return $sponsor_id;
		} else {
			$sponsor_id = findOne("SELECT s.id FROM users as u LEFT JOIN users as s ON u.sponsor=s.login WHERE u.id='".$sponsor_id."'")["id"];
			if(isset($sponsor_id) && $sponsor_id == 1) {
				$true = true;
				return 1;
			}
		}
	}
}

function getCuratorWood($user_id, $table, $level){
	if(count(find("SELECT * FROM `".$table."` WHERE `level`='".$level."'"))==0) {
		return NULL;
	}
	if(empty($user_id)) return NULL;
	$ids = [$user_id];
	$true = false;
	while($true == false){
		$tempID = [];
		foreach($ids as $var_id){
			$check_curator = find("SELECT * FROM `".$table."` WHERE `parent_id`='".$var_id."' AND `level`='".$level."'");
			if(empty($check_curator) || count($check_curator)<2) {
				$true = true;
				return $var_id;
			} else {
				array_push($tempID, array_column($check_curator, "user_id"));
			}
		}
		$ids = [];
		foreach($tempID as $rows){
			foreach($rows as $row){
				$ids[] = $row;
			}
		}
	}
}

function getCuratorColumn($user_id, $table, $level){
	if(count(find("SELECT * FROM `".$table."`"))==0) {
		return NULL;
	}
	if(empty($user_id)) return NULL;
	$ids = [$user_id];
	$true = false;
	while($true == false){
		$tempID = [];
		foreach($ids as $var_id){
			$check_curator = find("SELECT * FROM `".$table."` WHERE `parent_id`='".$var_id."' AND `level`='".$level."'");
			if(empty($check_curator) || count($check_curator)<1) {
				$true = true;
				return $var_id;
			} else {
				array_push($tempID, array_column($check_curator, "user_id"));
			}
		}
		$ids = [];
		foreach($tempID as $rows){
			foreach($rows as $row){
				$ids[] = $row;
			}
		}
	}
}

function buildTreeArray($arItems, $section_id = 'parent_id', $element_id = 'user_id') {
    $childs = array();
    if(!is_array($arItems) || empty($arItems)) {
        return array();
    }
    foreach($arItems as &$item) {
        if(!$item[$section_id]) {
            $item[$section_id] = 0;
        }
        $childs[$item[$section_id]][] = &$item;
    }
    unset($item);
    foreach($arItems as &$item) {
        if (isset($childs[$item[$element_id]])) {
            $item['childs'] = $childs[$item[$element_id]];
        }
    }
	$keys = array_keys($childs);
    return $childs[$keys[0]];
}

function getWoodDebug($user_id, $table, $level){
	$returnIds = [];
	$tempIds = [];
	$get_my = findOne("SELECT * FROM ".$table." WHERE user_id='".$user_id."' AND level='".$level."'");
	$returnIds[] = ["user_id"=>$get_my["user_id"], "parent_id"=>$get_my["parent_id"]];
	$stop = false;
	while(!$stop){
		if(count($returnIds)==1){
			$tempIds[] = $user_id;
		}
		$sponsor_ref_ids = implode(", ", $tempIds);
		$refs = find("SELECT * FROM ".$table." WHERE parent_id IN (".$sponsor_ref_ids.") AND level='".$level."'");
		$tempIds = [];
		if(count($refs)>0) {
			foreach($refs as $row){
				$tempIds[] = $row["user_id"];
				$returnIds[] = ["user_id"=>$row["user_id"], "parent_id"=>$row["parent_id"]];
			}
		} else {
			$stop = true;
		}
	}
	return $returnIds;
}

function getCountWood($result){
	$count_all = 0;
	foreach($result as &$row_1) {
		$count_all++;
		foreach($row_1["childs"] as &$row_2) {
			$count_all++;
			foreach($row_2["childs"] as &$row_3) {
				$count_all++;
				foreach($row_3["childs"] as &$row_4) {
					$count_all++;
					foreach($row_4["childs"] as &$row_5) {
						$count_all++;
						unset($row_5["childs"]);
					}
				}
			}
		}
	}
	return $count_all;
}

function getAllWood($result){
	$resultWood = [];
	foreach($result as &$row_1) {
		$resultWood[] = ["user_id"=>$row_1["user_id"],"parent_id"=>$row_1["parent_id"], "login"=>$row_1["login"], "fio"=>$row_1["fio"]];
		foreach($row_1["childs"] as &$row_2) {
			$resultWood[] = ["user_id"=>$row_2["user_id"],"parent_id"=>$row_2["parent_id"], "login"=>$row_2["login"], "fio"=>$row_2["fio"]];
			foreach($row_2["childs"] as &$row_3) {
				$resultWood[] = ["user_id"=>$row_3["user_id"],"parent_id"=>$row_3["parent_id"], "login"=>$row_3["login"], "fio"=>$row_3["fio"]];
				foreach($row_3["childs"] as &$row_4) {
					$resultWood[] = ["user_id"=>$row_4["user_id"],"parent_id"=>$row_4["parent_id"], "login"=>$row_4["login"], "fio"=>$row_4["fio"]];
					foreach($row_4["childs"] as &$row_5) {
						$resultWood[] = ["user_id"=>$row_5["user_id"],"parent_id"=>$row_5["parent_id"], "login"=>$row_5["login"], "fio"=>$row_5["fio"]];
						unset($row_5["childs"]);
					}
				}
			}
		}
	}
	return $resultWood;
}

function checkCurator($user_id, $table, $level){
	global $count_users_wood;
	global $count_users_column;
	global $max_tables;
	global $turbo_forward;
	
	if($table == "turbo_wood") {
		$get_wood = getCountWood(buildTreeArray(getWoodDebug($user_id, $table, $level)))-1;
		$check_count_wood = $get_wood >= $count_users_wood ? true: false; ## Проверка на количество людей под нами
		if($check_count_wood) { # Если условие соблюдено

			#### Column
			$table_new = "turbo_column";
			$level += 1;
			$check_isset = find("SELECT * FROM `".$table_new."` WHERE user_id='".$user_id."' AND level='".$level."'");
			if(count($check_isset)==0){
				$get_my = findOne("SELECT * FROM users WHERE id='".$user_id."'");
				$get_mysponsor_id = findOne("SELECT * FROM users WHERE login='".$get_my["sponsor"]."'")["id"];
				bonusSponsor($get_mysponsor_id, $turbo_forward[$table][$level-1]); ## Спонсорский бонус
				bonusCloseLevel($user_id, $turbo_forward[$table][$level-1]); ## Бонус закрытия уровня
				bonusMarketing($user_id, $turbo_forward[$table][$level-1]); ## Бонус маркетинга
			}
			if($level<=$max_tables && count($check_isset)==0){
				$curator_id = getCuratorColumn(5436, $table_new, $level);
				save("INSERT INTO `".$table_new."` (user_id, parent_id, date, level) VALUES ('".$user_id."','".$curator_id."','".date("Y-m-d H:i:s")."','".$level."')", $user_id, "Переход на следущий стол", "change-turbo", $table_new."-".$level);
			}

			#### Wood
			$curator_id = getCuratorWood(5436, $table, $level);
			$check_isset = find("SELECT * FROM `".$table."` WHERE user_id='".$user_id."' AND level='".$level."'");
			if(count($check_isset)==0){
				$get_my = findOne("SELECT * FROM users WHERE id='".$user_id."'");
				$get_mysponsor_id = findOne("SELECT * FROM users WHERE login='".$get_my["sponsor"]."'")["id"];
				bonusCloseLevel($user_id, $turbo_forward[$table][$level]); ## Бонус закрытия уровня
			}
			if(count($check_isset)==0 && $level<7){
				save("INSERT INTO `".$table."` (user_id, parent_id, date, level) VALUES ('".$user_id."','".$curator_id."','".date("Y-m-d H:i:s")."','".$level."')", $user_id, "Переход на следущий стол", "change-turbo", $table."-".$level);			
			}

		}

		$get_curator_id = findOne("SELECT * FROM `".$table."` WHERE user_id='".$user_id."' AND level='".$level."'");
		if(isset($get_curator_id["parent_id"]) && !empty($get_curator_id["parent_id"])) {
			checkCurator($get_curator_id["parent_id"], $table, $level);
		}
	}
}
?>