<?php
session_start();
include("db_connect.php");
include "smsc_api.php";
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");


if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	
	$products_query = mysql_query("SELECT * FROM products");
	$products = [];
	while($row = mysql_fetch_assoc($products_query)){
		$products[$row["id"]] = $row;
	}
	
	$packages_query = mysql_query("SELECT * FROM packages");
	$packages = [];
	while($row = mysql_fetch_assoc($packages_query)){
		$packages[] = $row;
	}

	$history = mysql_query("SELECT p.id, p.products, p.date, f.login as from_login, t.login as to_login, sh.temp_val as temp_val FROM `products_transfer` as p LEFT JOIN `users` as f ON f.id=p.from_id LEFT JOIN `users` as t ON t.id=p.to_id LEFT JOIN sklad_history as sh ON sh.to_id=p.to_id AND sh.date=p.date WHERE p.to_id='".$_SESSION["id"]."' AND sh.type='give-user'");
}
else {
	header("Location: ../index.php");
	die();
}

include("header.php");
?>
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
				<h1>История</h1>
			</div>
		</div>
	</div>
</div>

<div class="content mt-3">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-custom">
					<div class="card-header">
						<strong class="card-title"><span class="card-icon">
							<i class="fa fa-history text-primary"></i>
							</span>История получения товаров</strong>
					</div>
					<div class="card-body">
						<table class="table table-hover table-head-custom mw-380">
							<thead>
								<tr>
									<th>ID</th>
									<th>От кого</th>
									<th>Продукты</th>  
									<th>Пакет</th>
									<th>Дата</th>
								</tr>
							</thead>
							<tbody>
								<? while($row = mysql_fetch_array($history)): ?>
									<tr>
										<td><?=$row["id"]?></td>
										<td><?=$row["from_login"]?></td>
										<td>
											<? 
												$prods = json_decode($row["products"], true);
												foreach($prods as $id=>$count){
													echo "<span>".$products[$id]["name"]." - ".$count." шт.</span>";
												}
												$package = json_decode($row["temp_val"],true);
											?>
										</td>
										<td><?=$packages[array_search($package["package"], array_column($packages, "id"))]["name"]?></td>
										<td><?=$row["date"]?></td>
									</tr>
									<? endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div><!-- .animated -->
</div><!-- .content -->

<? include("footer.php"); ?>