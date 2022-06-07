<?php

date_default_timezone_set('Asia/Almaty');

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
	$k = mysql_result(mysql_query("select type from m1 where user_login='".$user_login."'"), 0);
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
		$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		$result_y = mysql_query("select * from m2 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2 && mysql_num_rows($result_y) == 0) {
			$res_xyz = mysql_query("select user_id from m2 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m2 (user_id, user_login, sponsor_login, type, post_time) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".$k.", '".date('Y-m-d H:i:s')."')");
			mysql_query("update users set akwa=akwa+1000 where login='".$sponsor[$i]."'");								mysql_query("update users set status=2 where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 1000, '".$user_login."', 'Этап 2', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m2 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				mysql_query("update users set akwa=akwa+1000 where login='".$row_m['sponsor_login']."'");
				mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 1000, '".$user_login."', 'Этап 2', '".date('Y-m-d H:i:s')."')");
				check_binary_2($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_z)) {
				$sponsor[] = $row_x['login'];
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
	$k = mysql_result(mysql_query("select type from m2 where user_login='".$user_login."'"), 0);
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
		$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		$result_y = mysql_query("select * from m3 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2 && mysql_num_rows($result_y) == 0) {
			$res_xyz = mysql_query("select user_id from m3 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m3 (user_id, user_login, sponsor_login, type, post_time) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".$k.", '".date('Y-m-d H:i:s')."')");
			mysql_query("update users set akwa=akwa+10000 where login='".$sponsor[$i]."'");								mysql_query("update users set status=3 where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 10000, '".$user_login."', 'Этап 3', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m3 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				//mysql_query("update users set akwa=akwa+1000 where login='".$row_m['sponsor_login']."'");
				check_binary_3($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_z)) {
				$sponsor[] = $row_x['login'];
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
	$k = mysql_result(mysql_query("select type from m3 where user_login='".$user_login."'"), 0);
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
		$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		$result_y = mysql_query("select * from m4 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2 && mysql_num_rows($result_y) == 0) {
			$res_xyz = mysql_query("select user_id from m4 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m4 (user_id, user_login, sponsor_login, type, post_time) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".$k.", '".date('Y-m-d H:i:s')."')");
			mysql_query("update users set akwa=akwa+5000 where login='".$sponsor[$i]."'");								mysql_query("update users set status=4, rang='Директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 5000, '".$user_login."', 'Этап 4', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m4 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				mysql_query("update users set akwa=akwa+10000 where login='".$row_m['sponsor_login']."'");
				mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 10000, '".$user_login."', 'Этап 4', '".date('Y-m-d H:i:s')."')");
				check_binary_4($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_z)) {
				$sponsor[] = $row_x['login'];
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
	$k = mysql_result(mysql_query("select type from m4 where user_login='".$user_login."'"), 0);
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
		$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		$result_y = mysql_query("select * from m5 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2 && mysql_num_rows($result_y) == 0) {
			$res_xyz = mysql_query("select user_id from m5 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m5 (user_id, user_login, sponsor_login, type, post_time) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".$k.", '".date('Y-m-d H:i:s')."')");
			mysql_query("update users set akwa=akwa+15000 where login='".$sponsor[$i]."'");								mysql_query("update users set status=5, rang='Бронзовый директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 15000, '".$user_login."', 'Этап 5', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m5 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				mysql_query("update users set akwa=akwa+15000 where login='".$row_m['sponsor_login']."'");
				mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 15000, '".$user_login."', 'Этап 5', '".date('Y-m-d H:i:s')."')");
				check_binary_5($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_z)) {
				$sponsor[] = $row_x['login'];
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
	$k = mysql_result(mysql_query("select type from m5 where user_login='".$user_login."'"), 0);
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
		$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		$result_y = mysql_query("select * from m6 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2 && mysql_num_rows($result_y) == 0) {		
			$res_xyz = mysql_query("select user_id from m6 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m6 (user_id, user_login, sponsor_login, type, post_time) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".$k.", '".date('Y-m-d H:i:s')."')");
			mysql_query("update users set akwa=akwa+40000 where login='".$sponsor[$i]."'");								mysql_query("update users set status=6, rang='Серебрянный директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 40000, '".$user_login."', 'Этап 6', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m6 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				mysql_query("update users set akwa=akwa+40000 where login='".$row_m['sponsor_login']."'");
				mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 40000, '".$user_login."', 'Этап 6', '".date('Y-m-d H:i:s')."')");
				check_binary_6($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_z)) {
				$sponsor[] = $row_x['login'];
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
	$k = mysql_result(mysql_query("select type from m6 where user_login='".$user_login."'"), 0);
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
		$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		$result_y = mysql_query("select * from m7 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2 && mysql_num_rows($result_y) == 0) {		
			$res_xyz = mysql_query("select user_id from m7 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m7 (user_id, user_login, sponsor_login, type, post_time) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".$k.", '".date('Y-m-d H:i:s')."')");
			mysql_query("update users set akwa=akwa+80000 where login='".$sponsor[$i]."'");								mysql_query("update users set status=7, rang='Золотой директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 80000, '".$user_login."', 'Этап 7', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m7 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				mysql_query("update users set akwa=akwa+80000 where login='".$row_m['sponsor_login']."'");
				mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 80000, '".$user_login."', 'Этап 6', '".date('Y-m-d H:i:s')."')");
				check_binary_7($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_z)) {
				$sponsor[] = $row_x['login'];
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
	$k = mysql_result(mysql_query("select type from m7 where user_login='".$user_login."'"), 0);
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
		$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		$result_y = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2 && mysql_num_rows($result_y) == 0) {	
			$res_xyz = mysql_query("select user_id from m8 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m8 (user_id, user_login, sponsor_login, type, post_time) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".$k.", '".date('Y-m-d H:i:s')."')");
			mysql_query("update users set akwa=akwa+1500000 where login='".$sponsor[$i]."'");								mysql_query("update users set status=8, rang='Бриллиантовый директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 1500000, '".$user_login."', 'Этап 8', '".date('Y-m-d H:i:s')."')");
			$flag_m = true;
			$res_m = mysql_query("select * from m8 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_m)>0) {
				$row_m = mysql_fetch_array($res_m);
				mysql_query("update users set akwa=akwa+1500000 where login='".$row_m['sponsor_login']."'");
				mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_m['sponsor_login']."', 1500000, '".$user_login."', 'Этап 8', '".date('Y-m-d H:i:s')."')");
				check_binary_8($row_m['sponsor_login']);
			} 
			echo mysql_error();
			}
		}
		else {
			while ($row_x = mysql_fetch_array($result_z)) {
				$sponsor[] = $row_x['login'];
				//$index[] = $row_x['id'];
				$n++;
			}
		}
		$i++;
	}
}

?>