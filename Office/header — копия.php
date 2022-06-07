<?php
//$cities = array('Выберите страну','Казахстан','Узбекистан','Россия', 'Монголия');
$liders = array('Olzha888', 'Millionersha777', 'Bankir888', 'Admin1', 'sholpan81', 'Umigold', '€€88', 'Magnat789');
include($_SERVER["DOCUMENT_ROOT"]."/Office/global.php");
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="ru">
<!--<![endif]-->

<head>
	<script>
	//window.replainSettings = { id: '0243b914-bcad-4902-9b4b-1768f3ee111b' };
	//(function(u){var s=document.createElement('script');s.type='text/javascript';s.async=true;s.src=u;
	//var x=document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);
	//})('https://widget.replain.cc/dist/client.js');
	</script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Boom Market</title>
    <meta name="description" content="Маркетинг план. Натуральные продукции">
  <meta name="keywords" content="маркетинг, натуральные продукции, Казахстан, Алматы, Атырау, Нур-Султан, Шымкент, Караганды, Павлодар, Актобе, Актау, Тараз, Уральск, Кокшетау, Семей, Оскемен">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
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
	<!-- Theme Style CSS -->
	<link rel="/Office/stylesheet" href="assets/css/dark-theme.css" />
	<link rel="/Office/stylesheet" href="assets/css/semi-dark.css" />
	<link rel="/Office/stylesheet" href="assets/css/header-colors.css" />


</head>

<body>

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
		if(isset($_SESSION['admin_login'])) {
	?>
			<div class="admin-panel">
				<a href="/Office/?action=outlogin">Вернуться назад</a>
			</div>
	<? } ?>
	
    <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

			<?
			$my_fio = mysql_query("SELECT fio, role FROM users WHERE login='".$_SESSION["login"]."'");
			$my_fio = mysql_fetch_array($my_fio);
			if(!$my_fio){
				$my_fio = findOne("SELECT fio, role FROM users WHERE login='".$_SESSION["login"]."'");
			}
			?>
			
            <div class="navbar-header">
				<div class="logo-block">
					<a href="#">
						<img alt="Logo" src="/Office/images/mainl.png" style="height: 100%;">
					</a>
					<div class="media-body">
						<center><h6 class="text-white display-6">Добро пожаловать!</h6>
                    	<h4 class="text-white display-6"><?=$my_fio["fio"]?></h4></center>
					</div>															
				</div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    
					

					
					<div class="d-flex flex-column flex-center">
							<!--begin::Symbol-->
							<div class="symbol symbol-12 symbol-circle symbol-success overflow-hidden">
								
							</div>
							<!--end::Symbol-->
							
					
                    <h3 class="menu-title"></h3>
					
						<li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-sitemap " style="font-size:18px;"></i>Мое дерево</a>
                        <ul class="sub-menu children dropdown-menu">
							<li><a href="/Office/b_0.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Легкий Level 0</a></li>
                            <li><a href="/Office/b_1.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>SOCIAL Level 1</a></li>
                            <li><a href="/Office/b_2.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>START Level 2</a></li>
                            <li><a href="/Office/b_3.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>START Level 3</a></li>
							<li><a href="/Office/b_4.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>BRONZE Level 4</a></li>
							<li><a href="/Office/b_5.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>SILVER Level 5</a></li>
							<li><a href="/Office/b_6.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>GOLD Level 6</a></li>
							<li><a href="/Office/b_7.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>DIAMOND Level 7</a></li>
							
                        </ul>
                    </li>
						
						<li class="menu-item-has-children dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-sitemap " style="font-size:18px;"></i>Multi Boom</a>
							<ul class="sub-menu children dropdown-menu">
								<li><a href="/Office/multiboom/b_1.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Level 1</a></li>
								<li><a href="/Office/multiboom/b_2.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Level 2</a></li>
								<li><a href="/Office/multiboom/b_3.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Level 3</a></li>
								<li><a href="/Office/multiboom/b_4.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Level 4</a></li>
								<li><a href="/Office/multiboom/b_5.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Level 5</a></li>
								<li><a href="/Office/multiboom/b_6.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Level 6</a></li>


							</ul>
						</li>
						
						
						<li class="menu-item-has-children dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-sitemap " style="font-size:18px;"></i>Infinity Boom</a>
							<ul class="sub-menu children dropdown-menu">
								
								<li><a href="/Office/infinity/index.php?package=1"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Gold</a></li>
								<li><a href="/Office/infinity/index.php?package=2"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Black Diamond</a></li>
								<li><a href="/Office/infinity/index.php?package=3"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Ambassador</a></li>
								<li><a href="/Office/infinity/shareholder.php"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i>Акционер</a></li>
							</ul>
						</li>
						
						<li>
							<?
							$check_turbo = mysql_query("SELECT * FROM turbo_column WHERE user_id='".$_SESSION["id"]."'");
							if(mysql_num_rows($check_turbo)>0):
							?>
						<li class="menu-item-has-children dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-sitemap " style="font-size:18px;"></i>Turbo Boom</a>
							<ul class="sub-menu children dropdown-menu">

								<li><a href="/Office/turboboom/index.php?level=1"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[1]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=2"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[2]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=3"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[3]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=4"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[4]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=5"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[5]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=6"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[6]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=7"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[7]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=8"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[8]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=9"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[9]["name"]?></a></li>
								<? if($options["turbo_level_1"]==1): ?>
								<li><a href="/Office/turboboom/index.php?level=10"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[10]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=11"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[11]["name"]?></a></li>
								<? endif; ?>
								<? if($options["turbo_level_1"]==1 && $options["turbo_level_2"]==1): ?>
								<li><a href="/Office/turboboom/index.php?level=12"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[12]["name"]?></a></li>
								<li><a href="/Office/turboboom/index.php?level=13"> <i class="menu-icon fa fa-sitemap" style="margin-top: -3px"></i><?=$turbo_levels[13]["name"]?></a></li>
								<? endif; ?>
							</ul>
						</li>
							<? else: ?>
							<a href="#" data-toggle="modal" data-target="#buyTurbo"> <i class="menu-icon fa fa-sitemap " style="font-size:18px;"></i>Turbo Boom (Купить)</a>
							<? endif; ?>
						</li>
						

					<li>
                        <a href="/Office/aktivnye.php"> <i class="menu-icon fa fa-check " style="font-size:18px;"></i>Мои партнеры</a>
                    </li>
					<?php
					if (in_array($_SESSION['login'], $liders)) {
					echo '<li class="menu-item-has-children dropdown">
							<a href="/Office/adamdar.php"> <i class="menu-icon fa fa-search"></i>Поиск партнеров</a>
						</li>';
						
					}
					if ($_SESSION['login']=='BoomMarket' || $_SESSION['login']=='BoomMarket111111') {
					?>
					
				
					
					<?php
					}
					?>
					<li>
                        <a href="/Office/int_transfer.php"> <i class="menu-icon fa fa-exchange " style="font-size:18px;"></i>Внутренний перевод</a>
                    </li>
					<li>
                        <a href="/Office/withdraw.php"> <i class="menu-icon fa fa-credit-card " style="font-size:18px;"></i>Вывод Средств</a>
                    </li>
                    <li>
                        <a href="/Office/report.php"> <i class="menu-icon fa fa-bar-chart " style="font-size:18px;"></i>История</a>
                    </li>
						<li>
                        <a href="/Office/buyTicket.php"> <i class="menu-icon fa fa-exchange " style="font-size:18px;"></i>Подарки компании</a>
                    </li>
						<li>
                        <a href="/Office/mytickets.php"> <i class="menu-icon fa fa-exchange " style="font-size:18px;"></i>Мои билеты</a>
                    </li>
						
					<? if($_SESSION["login"] === "BoomMarket"): ?>
						<li>
                        	<a href="/Office/persons.php"> <i class="menu-icon fa fa-bar-chart " style="font-size:18px;"></i>Статистика</a>
                    	</li>
						<li>
                        	<a href="/Office/admin_history.php"> <i class="menu-icon fa fa-bar-chart " style="font-size:18px;"></i>Операции админов</a>
                    	</li>
					<? endif; ?>
                    <h3 class="menu-title"></h3><!-- /.menu-title -->
					
					<li>
                        <a href="/Office/index.php"> <i class="menu-icon fa fa-vcard " style="font-size:18px;"></i>Профиль </a>
                    </li>
					
						<? if($my_fio["role"] == 1): ?>
						<li>
							<a href="/Office/history_give_products.php"> <i class="menu-icon fa fa-exchange " style="font-size:18px;"></i>Получение товаров</a>
						</li>
						<? elseif($my_fio["role"] == 2 || $my_fio["role"] == 3): ?>
						<li>
							<a href="https://sklad.bm-market.kz/"> <i class="menu-icon fa fa-exchange " style="font-size:18px;"></i>Склад</a>
						</li>
						<? endif; ?>
                 
                    <li>
                        <a href="/Office/logout.php"> <i class="menu-icon fa fa-sign-out " style="font-size:18px;"></i>Выйти</a>
                    </li>

                </ul>
				
				
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->
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
    <!-- Right Panel -->
<script src="/Office/assets/js/swal.js"></script>
    <div id="right-panel" class="right-panel"  background-size: cover">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-12">
                    <div class="user-area dropdown float-right">
                        

                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="profile.php"><i class="fa fa-user"></i> Мой профиль</a>

                            <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Выйти</a>
                        </div>
                    </div>

                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->
																							