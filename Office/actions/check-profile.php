<?
	include('../db_connect.php');

	$profile = mysql_query("SELECT * FROM users WHERE login='".$_POST["login"]."'");
	$profile_assoc = mysql_fetch_assoc($profile);
	if(mysql_num_rows($profile)==1) {
		if($profile_assoc["hide_data"] == 0 && $_SESSION["login"] != "BoomMarket"){
			$profile_assoc["fio"] = "ФИО скрыто пользователем";
		}
		echo json_encode(["success"=>1, "msg"=>$profile_assoc["fio"]]);
	} elseif(mysql_num_rows($profile)>1) {
		echo json_encode(["success"=>0, "msg"=>"Найдено несколько аккаунтов"]);
	} else {
		echo json_encode(["success"=>0, "msg"=>"Такого аккаунта не существует"]);
	}
?>