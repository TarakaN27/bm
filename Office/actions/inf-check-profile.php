<?
	include('../db_connect.php');
	
	$profile = mysql_query("SELECT * FROM users WHERE login='".$_POST["login"]."'");
	$profile_assoc = mysql_fetch_assoc($profile);
	if(mysql_num_rows($profile)==1) {		
		
		if(isset($_POST["type"]) && $_POST["type"] == "leader") {
			$leader_query = mysql_query("SELECT * FROM `infinity1` WHERE user_id='".$profile_assoc["id"]."'");
			$leader = mysql_fetch_assoc($leader_query);
			if(mysql_num_rows($leader_query)>0 && $leader["pv_left"]>=64 && $leader["pv_right"]>=64){
				echo json_encode(["success"=>1, "msg"=>$profile_assoc["login"]]);
			} else {
				echo json_encode(["success"=>0, "msg"=>"Не является лидером"]);
			}
		} else {
			echo json_encode(["success"=>1, "msg"=>$profile_assoc["login"]]);
		}
	} elseif(mysql_num_rows($profile)>1) {
		echo json_encode(["success"=>0, "msg"=>"Найдено несколько аккаунтов"]);
	} else {
		echo json_encode(["success"=>0, "msg"=>"Такого аккаунта не существует"]);
	}
?>