<?php
include("../db_data.php");

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_database);
$mysqli->set_charset("utf8");

if (mysqli_connect_errno()) {
    printf("Ошибка подключения: %s\n", mysqli_connect_error());
    exit();
}

function find( $sql ) {
	global $mysqli;
	if ($result = $mysqli->query($sql)) {
		$rows = array();		
		while($row = $result->fetch_assoc())
			$rows[] = $row;					
		return $rows;
	} else {
		return $mysqli->errno . ". " . $mysqli->error;
		exit;
	}
	$result->free();
}


function findOne($sql) {
	$tempArr = find( $sql." LIMIT 1" );
	return $tempArr[0];
}


function save($sql, $id=false, $msg=false, $type=false, $temp_val=NULL) {
	global $mysqli;
	$r = false;
	$result = $mysqli->query($sql);
	if ($result) {
		$r = $mysqli->insert_id;
		if($id){
			$sql = htmlspecialchars(str_replace(array('(',')', '`', "'"),array('[',']', '', ''),$sql));
			$mysqli->query("INSERT INTO `history` (`from`, `user_id`, `text`, `msg`, `date`, `type`, `temp_val`) VALUES ('".$_SESSION["id"]."', '".$id."', '".$sql."', '".$msg."', '".date("Y-m-d H:i:s")."', '".$type."', '".$temp_val."')");
		}
	} else {
		$r = $mysqli->error;
	}
	
	return $r;
}

$options_query = find("SELECT * FROM `options`");
$options = [];
foreach($options_query as $row){
	$options[$row["name"]] = $row["value"];
}

?>