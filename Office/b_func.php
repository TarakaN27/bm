<?php

date_default_timezone_set('Asia/Almaty');

include($_SERVER["DOCUMENT_ROOT"]."/Office/global.php");

$levels = [
	"m0"=>"Стартовый",
	"m1"=>"SOCIAL Level 1",
	"m2"=>"START Level 2",
	"m3"=>"START Level 3",
	"m4"=>"BRONZE Level 4",
	"m5"=>"SILVER Level 5",
	"m6"=>"GOLD Level 6",
	"m7"=>"DIAMOND Level 7"
];
$multi = [
	"qq1"=>"Level 1",
	"qq2"=>"Level 2",
	"qq3"=>"Level 3",
	"qq4"=>"Level 4",
	"qq5"=>"Level 5",
	"qq6"=>"Level 6"
];

$infinity = [
	"infinity1"=>"Level 1",
	"infinity2"=>"Level 2",
	"infinity3"=>"Level 3"
];

$levels_merge = array_merge($levels, $multi, $infinity, $turbo_levels);

function check_binary_0($sponsor_login) {
	$res = mysql_query("select * from m0 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i==0 && !$flag_m) {
		$result_x = mysql_query("select * from m0 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		$i++;
	}
	
	if (!$flag_m) {
		add_binary_1($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_1($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m1 where user_login='".$sponsor_login."'");
		if (mysql_num_rows($res) > 0) {
			$flag = true;
		}
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m1 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from m1 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m1 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status=2 where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Start level bonus 1', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m1 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+1000 where login='".$row_m['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 2000, '".$user_login."', 'Start level bonus 1', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_1($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
}

function check_binary_1($sponsor_login) {
	$res = mysql_query("select * from m1 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_m) {
		$result_x = mysql_query("select * from m1 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	
	if (!$flag_m) {
		add_binary_2($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_2($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from m1 where user_login='".$user_login."'"), 0);
	while ($flag == false) {
		$res = mysql_query("select * from m2 where user_login='".$sponsor_login."'");
		//$res_m = mysql_query("select * from m2 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) {
			$flag = true;
		}
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m2 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m2 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from m2 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m2 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status=3 where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Start level bonus 2', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m2 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+2000 where login='".$row_m['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 2000, '".$user_login."', 'Start level bonus 2', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_2($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

function check_binary_2($sponsor_login) {
	$res = mysql_query("select * from m2 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_m) {
		$result_x = mysql_query("select * from m2 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	
	/*$sponsor_p[0] = $row['user_login'];
	$flag_p = false;
	$p = 0; $n = 0;
	while ($sponsor_p[$p]!="" && $p<=2 && !$flag_p) {
		$result_x = mysql_query("select * from m2 where sponsor_login='".$sponsor[$i]."' and post_time>'2020-09-16 10:00:00'");
		if (mysql_num_rows($result_x)<2) {
			$flag_p = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor_p[] = $row_x['user_login'];
				$n++;
			}
		}
		$p++;
	}
	if (!$flag_p) {
		mysql_query("update users set promote=promote+3 where user_id='".$row['user_id']."'");
	}*/
	
	if (!$flag_m) {
		add_binary_3($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_3($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from m2 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m3 where user_login='".$sponsor_login."'");
		//$res_m = mysql_query("select * from m3 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m3 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m3 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from m3 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m3 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status=4 where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Start level bonus 3', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m3 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+4000 where login='".$row_m['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 4000, '".$user_login."', 'Start level bonus 3', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_3($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

function check_binary_3($sponsor_login) {
	$res = mysql_query("select * from m3 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_m) {
		$result_x = mysql_query("select * from m3 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	
	/*$sponsor_p[0] = $row['user_login'];
	$flag_p = false;
	$p = 0; $n = 0;
	while ($sponsor_p[$p]!="" && $p<=2 && !$flag_p) {
		$result_x = mysql_query("select * from m3 where sponsor_login='".$sponsor[$i]."' and post_time>'2020-09-16 10:00:00'");
		if (mysql_num_rows($result_x)<2) {
			$flag_p = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor_p[] = $row_x['user_login'];
				$n++;
			}
		}
		$p++;
	}
	if (!$flag_p) {
		mysql_query("update users set promote=promote+4 where user_id='".$row['user_id']."'");
	}*/
	
	if (!$flag_m) {
		add_binary_4($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_4($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from m3 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m4 where user_login='".$sponsor_login."'");
		//$res_m = mysql_query("select * from m4 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m4 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m4 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from m4 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m4 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status=5, rang='Директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Bronze level bonus 4', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m4 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+8000 where login='".$row_m['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 8000, '".$user_login."', 'Bronze level bonus 4', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_4($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

function check_binary_4($sponsor_login) {
	$res = mysql_query("select * from m4 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_m) {
		$result_x = mysql_query("select * from m4 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	
	/*$sponsor_p[0] = $row['user_login'];
	$flag_p = false;
	$p = 0; $n = 0;
	while ($sponsor_p[$p]!="" && $p<=2 && !$flag_p) {
		$result_x = mysql_query("select * from m4 where sponsor_login='".$sponsor[$i]."' and post_time>'2020-09-16 10:00:00'");
		if (mysql_num_rows($result_x)<2) {
			$flag_p = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor_p[] = $row_x['user_login'];
				$n++;
			}
		}
		$p++;
	}
	if (!$flag_p) {
		mysql_query("update users set promote=promote+5 where user_id='".$row['user_id']."'");
	}*/
	
	if (!$flag_m) {
		add_binary_5($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_5($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from m4 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m5 where user_login='".$sponsor_login."'");
		//$res_m = mysql_query("select * from m5 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m5 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m5 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from m5 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m5 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status=6, rang='Бронзовый директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Silver level bonus 5', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m5 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+20000 where login='".$row_m['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 20000, '".$user_login."', 'Silver level bonus 5', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_5($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

function check_binary_5($sponsor_login) {
	$res = mysql_query("select * from m5 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_m) {
		$result_x = mysql_query("select * from m5 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	if (!$flag_m) {
		add_binary_6($row['user_id'], $sponsor_login, $row['user_login']);
	}	
}

function add_binary_6($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from m5 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m6 where user_login='".$sponsor_login."'");
		//$res_m = mysql_query("select * from m6 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m6 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m6 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {		
			$res_xyz = mysql_query("select user_id from m6 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m6 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status=7, rang='Серебрянный директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Gold level bonus 6', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m6 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+80000 where login='".$row_m['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 80000, '".$user_login."', 'Gold level bonus 6', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_6($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

function check_binary_6($sponsor_login) {
	$res = mysql_query("select * from m6 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_m) {
		$result_x = mysql_query("select * from m6 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	if (!$flag_m) {
		add_binary_7($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_7($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from m6 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m7 where user_login='".$sponsor_login."'");
		//$res_m = mysql_query("select * from m7 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m7 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m7 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {		
			$res_xyz = mysql_query("select user_id from m7 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m7 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status=8, rang='Золотой директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Diamond level bonus 7', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m7 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+500000 where login='".$row_m['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 500000, '".$user_login."', 'Diamond level bonus 7', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_7($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

function check_binary_7($sponsor_login) {
	$res = mysql_query("select * from m7 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_m) {
		$result_x = mysql_query("select * from m7 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	if (!$flag_m) {
		add_binary_8($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_8($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from m7 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m8 where user_login='".$sponsor_login."'");
		//$res_m = mysql_query("select * from m8 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {	
			$res_xyz = mysql_query("select user_id from m8 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m8 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status=9, rang='Бриллиантовый директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 00, '".$user_login."', 'Ступень 8', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m8 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				$check_block = mysql_query("select block_bonus from users where login='".$row_m['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+00 where login='".$row_m['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 00, '".$user_login."', 'Ступень 8', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_8($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

function check_binary_8($sponsor_login) {
	$res = mysql_query("select * from m8 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_m = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_m) {
		$result_x = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_m = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	if (!$flag_m) {
		add_binary_9($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_9($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from m7 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m9 where user_login='".$sponsor_login."'");
		//$res_m = mysql_query("select * from m8 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_m) {
		$result_x = mysql_query("select * from m9 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {	
			$res_xyz = mysql_query("select user_id from m9 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m9 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			//mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								//mysql_query("update users set status=10, rang='Бриллиантовый директор' where login='".$user_login."'");
			//mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Ступень 8', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m8 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				//mysql_query("update users set akwa=akwa+0 where login='".$row_m['sponsor_login']."'");
				//mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 0, '".$user_login."', 'Ступень 8', '".date('Y-m-d H:i:s')."')");
				//check_binary_8($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

?>