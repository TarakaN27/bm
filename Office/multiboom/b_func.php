<?php

date_default_timezone_set('Asia/Almaty');

function check_binary_1($sponsor_login) {
	$res = mysql_query("select * from qq1 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_qq = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_qq) {
		$result_x = mysql_query("select * from qq1 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_qq = true;
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
		$result_p = mysql_query("select * from m1 where sponsor_login='".$sponsor[$i]."' and post_time>'2020-09-16 10:00:00'");
		if (mysql_num_rows($result_p)<2) {
			$flag_p = true;
		}
		else {
			while ($row_p = mysql_fetch_array($result_p)) {
				$sponsor_p[] = $row_p['user_login'];
				$n++;
			}
		}
		$p++;
	}
	if (!$flag_p) {
		mysql_query("update users set promote=promote+2 where login='".$row['user_login']."'");
	}*/
	
	if (!$flag_qq) {
		add_binary_2($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_2($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from qq1 where user_login='".$user_login."'"), 0);
	while ($flag == false) {
		$res = mysql_query("select * from qq2 where user_login='".$sponsor_login."'");
		//$res_qq = mysql_query("select * from qq2 where sponsor_login='".$sponsor_login."' and type=".$k);
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
	while ($sponsor[$i]!="" && $i<$n && !$flag_qq) {
		$result_x = mysql_query("select * from qq2 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from qq2 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from qq2 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into qq2 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status1=2 where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'MB level bonus 2', '".date('Y-m-d H:i:s')."')");
			$flag_qq = true;
			$res_qq = mysql_query("select * from qq2 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_qq)>0) {
				$row_qq = mysql_fetch_array($res_qq);
				$check_block = mysql_query("select block_bonus from users where login='".$row_qq['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+10000 where login='".$row_qq['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_qq['sponsor_login']."', 10000, '".$user_login."', 'level bonus 2', '".date('Y-m-d H:i:s')."')");
					
				}
				check_binary_2($row_qq['sponsor_login']);
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
	$res = mysql_query("select * from qq2 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_qq = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_qq) {
		$result_x = mysql_query("select * from qq2 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_qq = true;
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
		$result_x = mysql_query("select * from qq2 where sponsor_login='".$sponsor[$i]."' and post_time>'2020-09-16 10:00:00'");
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
	
	if (!$flag_qq) {
		add_binary_3($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_3($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from qq2 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from qq3 where user_login='".$sponsor_login."'");
		//$res_qq = mysql_query("select * from qq3 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_qq) {
		$result_x = mysql_query("select * from qq3 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from qq3 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from qq3 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into qq3 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status1=3 where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'level bonus 3', '".date('Y-m-d H:i:s')."')");
			$flag_qq = true;
			$res_qq = mysql_query("select * from qq3 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_qq)>0) {
				$row_qq = mysql_fetch_array($res_qq);
				$check_block = mysql_query("select block_bonus from users where login='".$row_qq['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+50000 where login='".$row_qq['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_qq['sponsor_login']."', 50000, '".$user_login."', 'level bonus 3', '".date('Y-m-d H:i:s')."')");
					
				}
				check_binary_3($row_qq['sponsor_login']);
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
	$res = mysql_query("select * from qq3 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_qq = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_qq) {
		$result_x = mysql_query("select * from qq3 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_qq = true;
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
		$result_x = mysql_query("select * from qq3 where sponsor_login='".$sponsor[$i]."' and post_time>'2020-09-16 10:00:00'");
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
	
	if (!$flag_qq) {
		add_binary_4($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_4($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from qq3 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from qq4 where user_login='".$sponsor_login."'");
		//$res_qq = mysql_query("select * from qq4 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_qq) {
		$result_x = mysql_query("select * from qq4 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from qq4 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from qq4 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into qq4 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status1=4, rang='Директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'level bonus 4', '".date('Y-m-d H:i:s')."')");
			$flag_qq = true;
			$res_qq = mysql_query("select * from qq4 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_qq)>0) {
				$row_qq = mysql_fetch_array($res_qq);
				$check_block = mysql_query("select block_bonus from users where login='".$row_qq['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+100000 where login='".$row_qq['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_qq['sponsor_login']."', 100000, '".$user_login."', 'level bonus 4', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_4($row_qq['sponsor_login']);
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
	$res = mysql_query("select * from qq4 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_qq = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_qq) {
		$result_x = mysql_query("select * from qq4 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_qq = true;
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
		$result_x = mysql_query("select * from qq4 where sponsor_login='".$sponsor[$i]."' and post_time>'2020-09-16 10:00:00'");
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
	
	if (!$flag_qq) {
		add_binary_5($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_5($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from qq4 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from qq5 where user_login='".$sponsor_login."'");
		//$res_qq = mysql_query("select * from qq5 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_qq) {
		$result_x = mysql_query("select * from qq5 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from qq5 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {
			$res_xyz = mysql_query("select user_id from qq5 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into qq5 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status1=5, rang='Бронзовый директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'level bonus 5', '".date('Y-m-d H:i:s')."')");
			$flag_qq = true;
			$res_qq = mysql_query("select * from qq5 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_qq)>0) {
				$row_qq = mysql_fetch_array($res_qq);
				$check_block = mysql_query("select block_bonus from users where login='".$row_qq['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+200000 where login='".$row_qq['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_qq['sponsor_login']."', 200000, '".$user_login."', 'level bonus 5', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_5($row_qq['sponsor_login']);
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
	$res = mysql_query("select * from qq5 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_qq = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_qq) {
		$result_x = mysql_query("select * from qq5 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_qq = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	if (!$flag_qq) {
		add_binary_6($row['user_id'], $sponsor_login, $row['user_login']);
	}	
}

function add_binary_6($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from qq5 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from qq6 where user_login='".$sponsor_login."'");
		//$res_qq = mysql_query("select * from qq6 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_qq) {
		$result_x = mysql_query("select * from qq6 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from qq6 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {		
			$res_xyz = mysql_query("select user_id from qq6 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into qq6 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status1=6, rang='Серебрянный директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'level bonus 6', '".date('Y-m-d H:i:s')."')");
			$flag_qq = true;
			$res_qq = mysql_query("select * from qq6 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_qq)>0) {
				$row_qq = mysql_fetch_array($res_qq);
				$check_block = mysql_query("select block_bonus from users where login='".$row_qq['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+400000 where login='".$row_qq['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_qq['sponsor_login']."', 400000, '".$user_login."', 'level bonus 6', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_6($row_qq['sponsor_login']);
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
	$res = mysql_query("select * from qq6 where user_login='".$sponsor_login."'");
	$row = mysql_fetch_array($res);
	$sponsor[0] = $row['user_login'];
	$flag_qq = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_qq) {
		$result_x = mysql_query("select * from qq6 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_qq = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	if (!$flag_qq) {
		add_binary_7($row['user_id'], $sponsor_login, $row['sponsor_login']);
	}	
}

function add_binary_7($user_id, $user_login, $sponsor_login) {
    $i = 0; 
	$n = 1; 
	$k = 0;
	$flag = false;
	$sponsor_login = mysql_result(mysql_query("select sponsor from users where login='".$user_login."'"), 0);
	//$k = mysql_result(mysql_query("select type from qq6 where user_login='".$user_login."'"), 0);
	while (!$flag && $sponsor_login != "") {
		$res = mysql_query("select * from m7 where user_login='".$sponsor_login."'");
		//$res_qq = mysql_query("select * from m7 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_qq) {
		$result_x = mysql_query("select * from m7 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m7 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {		
			$res_xyz = mysql_query("select user_id from m7 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m7 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status1=7, rang='Золотой директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Diamond level bonus 7', '".date('Y-m-d H:i:s')."')");
			$flag_qq = true;
			$res_qq = mysql_query("select * from m7 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_qq)>0) {
				$row_qq = mysql_fetch_array($res_qq);
				$check_block = mysql_query("select block_bonus from users where login='".$row_qq['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+500000 where login='".$row_qq['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_qq['sponsor_login']."', 500000, '".$user_login."', 'Diamond level bonus 7', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_7($row_qq['sponsor_login']);
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
	$flag_qq = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_qq) {
		$result_x = mysql_query("select * from m7 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_qq = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	if (!$flag_qq) {
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
		//$res_qq = mysql_query("select * from m8 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_qq) {
		$result_x = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {	
			$res_xyz = mysql_query("select user_id from m8 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m8 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								mysql_query("update users set status1=8, rang='Бриллиантовый директор' where login='".$user_login."'");
			mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 00, '".$user_login."', 'Ступень 8', '".date('Y-m-d H:i:s')."')");
			$flag_qq = true;
			$res_qq = mysql_query("select * from m8 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_qq)>0) {
				$row_qq = mysql_fetch_array($res_qq);
				$check_block = mysql_query("select block_bonus from users where login='".$row_qq['sponsor_login']."'");
				$check_block = mysql_fetch_array($check_block);
				if($check_block['block_bonus'] == 0) {
					mysql_query("update users set akwa=akwa+00 where login='".$row_qq['sponsor_login']."'");
					mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_qq['sponsor_login']."', 00, '".$user_login."', 'Ступень 8', '".date('Y-m-d H:i:s')."')");
				}
				check_binary_8($row_qq['sponsor_login']);
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
	$flag_qq = false;
	$i = 0;
	while ($sponsor[$i]!="" && $i<=2 && !$flag_qq) {
		$result_x = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."'");
		if (mysql_num_rows($result_x)<2) {
			$flag_qq = true;
		}
		else {
			while ($row_x = mysql_fetch_array($result_x)) {
				$sponsor[] = $row_x['user_login'];
				$n++;
			}
		}
		$i++;
	}
	if (!$flag_qq) {
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
		//$res_qq = mysql_query("select * from m8 where sponsor_login='".$sponsor_login."' and type=".$k);
		if (mysql_num_rows($res) > 0) $flag = true;
		else {
			$res_x = mysql_query("select sponsor from users where login='".$sponsor_login."'");
			$sponsor_login = mysql_result($res_x, 0);
		}
	}
	$sponsor[0] = $sponsor_login;
	//$index[0] = $row_s['id'];
	while ($sponsor[$i]!="" && $i<$n && !$flag_qq) {
		$result_x = mysql_query("select * from m9 where sponsor_login='".$sponsor[$i]."'");
		//$result_z = mysql_query("select * from users where sponsor='".$sponsor[$i]."'");
		//$result_y = mysql_query("select * from m8 where sponsor_login='".$sponsor[$i]."' and type=".$k);
		if (mysql_num_rows($result_x)<2) {	
			$res_xyz = mysql_query("select user_id from m9 where user_login='".$user_login."'");
			if (mysql_num_rows($res_xyz) == 0) {
			mysql_query("insert into m9 (user_id, user_login, sponsor_login, type, post_time, pay) values (".$user_id.", '".$user_login."', '".$sponsor[$i]."', ".(mysql_num_rows($result_x)+1).", '".date('Y-m-d H:i:s')."', '0')");
			//mysql_query("update users set akwa=akwa+0 where login='".$sponsor[$i]."'");								//mysql_query("update users set status1=8, rang='Бриллиантовый директор' where login='".$user_login."'");
			//mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$sponsor[$i]."', 0, '".$user_login."', 'Ступень 8', '".date('Y-m-d H:i:s')."')");
			$flag_qq = true;
			$res_qq = mysql_query("select * from m8 where user_login='".$sponsor[$i]."'");
			if (mysql_num_rows($res_qq)>0) {
				$row_qq = mysql_fetch_array($res_qq);
				//mysql_query("update users set akwa=akwa+0 where login='".$row_qq['sponsor_login']."'");
				//mysql_query("insert into transfer (login, amount, user_login, product, sent_time) values ('".$row_qq['sponsor_login']."', 0, '".$user_login."', 'Ступень 8', '".date('Y-m-d H:i:s')."')");
				//check_binary_8($row_qq['sponsor_login']);
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