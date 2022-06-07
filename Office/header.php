<?php
//$cities = array('Выберите страну','Казахстан','Узбекистан','Россия', 'Монголия');
$liders = array('Olzha888', 'Millionersha777', 'Bankir888', 'Admin1', 'sholpan81', 'Umigold', '€€88', 'Magnat789');
include($_SERVER["DOCUMENT_ROOT"]."/Office/global.php");

$my_fio = mysql_query("SELECT fio, role FROM users WHERE login='".$_SESSION["login"]."'");
$my_fio = mysql_fetch_array($my_fio);
if(!$my_fio){
	$my_fio = findOne("SELECT fio, role FROM users WHERE login='".$_SESSION["login"]."'");
}

?>

<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="/Office/assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="/Office/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="/Office/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="/Office/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="/Office/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="/Office/assets/css/pace.min.css" rel="stylesheet" />
	<script src="/Office/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="/Office/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/Office/assets/css/app.css" rel="stylesheet">
	<link href="/Office/assets/css/icons.css" rel="stylesheet">
	<title>Boom Market</title>
	<meta name="description" content="Маркетинг план. Натуральные продукции">
	<meta name="keywords" content="маркетинг, натуральные продукции, Казахстан, Алматы, Атырау, Нур-Султан, Шымкент, Караганды, Павлодар, Актобе, Актау, Тараз, Уральск, Кокшетау, Семей, Оскемен">
    
</head>

<body class="bg-theme bg-theme3">	
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="/Office/assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<h4 class="logo-text">BM-market</h4>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<?php
					if($_GET["action"]=="outlogin") {
						$res = mysql_query("SELECT * FROM users WHERE login='".$_SESSION['login']."'");
						$res = mysql_fetch_array($res);
						$get_admin = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE login='".$_SESSION['admin_login']."'"));
						$_SESSION['id'] = $get_admin["id"];
						$_SESSION['login'] = $_SESSION['admin_login'];
						$bc_url = "/Office/persons.php?action=edit&edit-id=".$res["id"];
						if(isset($_SESSION['back_url'])) {
							$bc_url = $_SESSION['back_url'];
							unset($_SESSION['back_url']);
						}
						unset($_SESSION['admin_login']);
						echo '<script>window.location.href="'.$bc_url.'"</script>';
					}	
					if(isset($_SESSION['admin_login'])):
				?>
				<li>
					<a href="/Office/?action=outlogin" style="background-color: #6da671;border: 2px solid;color: #fff;">
						<div class="parent-icon"><i class="bx bx-cookie"></i></div>
						<div class="menu-title">Вернуться назад</div>
					</a>
				</li>
				<? endif; ?>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-home-circle'></i></div><div class="menu-title">Мое дерево</div>
					</a>
					<ul>
						<li><a href="/Office/b_0.php"><i class="bx bx-right-arrow-alt"></i>Легкий Level 0</a></li>
						<li><a href="/Office/b_1.php"><i class="bx bx-right-arrow-alt"></i>SOCIAL Level 1</a></li>
						<li><a href="/Office/b_2.php"><i class="bx bx-right-arrow-alt"></i>START Level 2</a></li>
						<li><a href="/Office/b_3.php"><i class="bx bx-right-arrow-alt"></i>START Level 3</a></li>
						<li><a href="/Office/b_4.php"><i class="bx bx-right-arrow-alt"></i>BRONZE Level 4</a></li>
						<li><a href="/Office/b_5.php"><i class="bx bx-right-arrow-alt"></i>SILVER Level 5</a></li>
						<li><a href="/Office/b_6.php"><i class="bx bx-right-arrow-alt"></i>GOLD Level 6</a></li>
						<li><a href="/Office/b_7.php"><i class="bx bx-right-arrow-alt"></i>DIAMOND Level 7</a></li>
					</ul>
				</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-home-circle'></i></div><div class="menu-title">Multi Boom</div>
					</a>
					<ul>
						<li><a href="/Office/multiboom/b_1.php"><i class="bx bx-right-arrow-alt"></i>Level 1</a></li>
						<li><a href="/Office/multiboom/b_2.php"><i class="bx bx-right-arrow-alt"></i>Level 2</a></li>
						<li><a href="/Office/multiboom/b_3.php"><i class="bx bx-right-arrow-alt"></i>Level 3</a></li>
						<li><a href="/Office/multiboom/b_4.php"><i class="bx bx-right-arrow-alt"></i>Level 4</a></li>
						<li><a href="/Office/multiboom/b_5.php"><i class="bx bx-right-arrow-alt"></i>Level 5</a></li>
						<li><a href="/Office/multiboom/b_6.php"><i class="bx bx-right-arrow-alt"></i>Level 6</a></li>
					</ul>
				</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-home-circle'></i></div><div class="menu-title">Infinity Boom</div>
					</a>
					<ul>
						<li><a href="/Office/infinity/index.php?package=1"><i class="bx bx-right-arrow-alt"></i>Gold</a></li>
						<li><a href="/Office/infinity/index.php?package=2"><i class="bx bx-right-arrow-alt"></i>Black Diamond</a></li>
						<li><a href="/Office/infinity/index.php?package=3"><i class="bx bx-right-arrow-alt"></i>Ambassador</a></li>
						<li><a href="/Office/infinity/shareholder.php"><i class="bx bx-right-arrow-alt"></i>Акционер</a></li>
					</ul>
				</li>
				<li>
					<?
						$check_turbo = mysql_query("SELECT * FROM turbo_column WHERE user_id='".$_SESSION["id"]."'");
						if(mysql_num_rows($check_turbo)>0):
					?>
						<a href="javascript:;" class="has-arrow">
							<div class="parent-icon"><i class='bx bx-home-circle'></i></div><div class="menu-title">Turbo Boom</div>
						</a>
						<ul>
							<li><a href="/Office/turboboom/index.php?level=1"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[1]["name"]?></a></li>
							<li><a href="/Office/turboboom/index.php?level=2"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[2]["name"]?></a></li>
							<li><a href="/Office/turboboom/index.php?level=3"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[3]["name"]?></a></li>
							<li><a href="/Office/turboboom/index.php?level=4"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[4]["name"]?></a></li>
							<li><a href="/Office/turboboom/index.php?level=5"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[5]["name"]?></a></li>
							<li><a href="/Office/turboboom/index.php?level=6"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[6]["name"]?></a></li>
							<li><a href="/Office/turboboom/index.php?level=7"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[7]["name"]?></a></li>
							<li><a href="/Office/turboboom/index.php?level=8"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[8]["name"]?></a></li>
							<li><a href="/Office/turboboom/index.php?level=9"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[9]["name"]?></a></li>
							<? if($options["turbo_level_1"]==1): ?>
								<li><a href="/Office/turboboom/index.php?level=10"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[10]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=11"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[11]["name"]?></a></li>
							<? endif; ?>
							<? if($options["turbo_level_1"]==1 && $options["turbo_level_2"]==1): ?>
								<li><a href="/Office/turboboom/index.php?level=12"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[12]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=13"><i class="bx bx-right-arrow-alt"></i><?=$turbo_levels[13]["name"]?></a></li>
							<? endif; ?>
						</ul>
					<? else: ?>
						<a href="#" data-toggle="modal" data-target="#buyTurbo">
							<div class="parent-icon"><i class="bx bx-cookie"></i></div>
							<div class="menu-title">Turbo Boom (Купить)</div>
						</a>
					<? endif; ?>
				</li>
				<a href="/Office/aktivnye.php">
					<div class="parent-icon"><i class="bx bx-cookie"></i></div>
					<div class="menu-title">Мои партнеры</div>
				</a>
				<a href="/Office/int_transfer.php">
					<div class="parent-icon"><i class="bx bx-cookie"></i></div>
					<div class="menu-title">Внутренний перевод</div>
				</a>
				<a href="/Office/withdraw.php">
					<div class="parent-icon"><i class="bx bx-cookie"></i></div>
					<div class="menu-title">Вывод Средств</div>
				</a>
				<a href="/Office/report.php">
					<div class="parent-icon"><i class="bx bx-cookie"></i></div>
					<div class="menu-title">История</div>
				</a>
				<a href="/Office/buyTicket.php">
					<div class="parent-icon"><i class="bx bx-cookie"></i></div>
					<div class="menu-title">Подарки компании</div>
				</a>
				<a href="/Office/mytickets.php">
					<div class="parent-icon"><i class="bx bx-cookie"></i></div>
					<div class="menu-title">Мои билеты</div>
				</a>
				<? if($_SESSION["login"] === "BoomMarket"): ?>
					<a href="/Office/persons.php">
						<div class="parent-icon"><i class="bx bx-cookie"></i></div>
						<div class="menu-title">Статистика</div>
					</a>
					<a href="/Office/admin_history.php">
						<div class="parent-icon"><i class="bx bx-cookie"></i></div>
						<div class="menu-title">Операции админов</div>
					</a>
				<? endif; ?>
				<li class="menu-label">Аккаунт</li>
				<a href="/Office/index.php">
					<div class="parent-icon"><i class="bx bx-cookie"></i></div>
					<div class="menu-title">Профиль</div>
				</a>
				<? if($my_fio["role"] == 1): ?>
					<a href="/Office/history_give_products.php">
						<div class="parent-icon"><i class="bx bx-cookie"></i></div>
						<div class="menu-title">Получение товаров</div>
					</a>
				<? elseif($my_fio["role"] == 2 || $my_fio["role"] == 3): ?>
					<a href="https://sklad.bm-market.kz/">
						<div class="parent-icon"><i class="bx bx-cookie"></i></div>
						<div class="menu-title">Склад</div>
					</a>
				<? endif; ?>
				<a href="/Office/logout.php">
					<div class="parent-icon"><i class="bx bx-cookie"></i></div>
					<div class="menu-title">Выйти</div>
				</a>				
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
					<div class="top-menu ms-auto"></div>
					<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link" href="#" role="button">
							<img src="https://via.placeholder.com/110x110" class="user-img" alt="user avatar">
							<div class="user-info ps-3">
								<p class="user-name mb-0"><?=$my_fio["fio"]?></p>
								<p class="designattion mb-0"></p>
							</div>
						</a>
					</div>
				</nav>
			</div>
		</header>
		
		<div class="modal fade" id="buyTurbo" tabindex="-1" role="dialog" aria-labelledby="buyTurboLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="buyTurboLabel">Turbo Boom</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p style='color:#000'>Вы действительно хотите купить пакет?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
						<button type="button" class="button-buyturbo btn btn-primary">Купить</button>
					</div>
				</div>
			</div>
		</div>
	
		<div class="modal fade" id="buyTypeMarketing" tabindex="-1" role="dialog" aria-labelledby="buyTypeMarketingLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="buyTypeMarketingLabel">Вид оплаты</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p style='color:#000'>Как вы хотите оплатить пакет?</p>
						<a href="?sub_m1=Вход&pay_type=balans" class="btn btn-primary mt-4 px-10 py-5">Баланс</a>
						<a href="?sub_m1=Вход&pay_type=bonus" class="btn btn-primary mt-4 px-10 py-5">Бонусы</a>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="buyTypeMulti" tabindex="-1" role="dialog" aria-labelledby="buyTypeMultiLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="buyTypeMultiLabel">Вид оплаты</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p style='color:#000'>Как вы хотите оплатить пакет?</p>
						<a href="?sub_qq1=Вход&pay_type=balans" class="btn btn-primary mt-4 px-10 py-5">Баланс</a>
						<a href="?sub_qq1=Вход&pay_type=bonus" class="btn btn-primary mt-4 px-10 py-5">Бонусы</a>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					</div>
				</div>
			</div>
		</div>
		
		
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">