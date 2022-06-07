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
	<div class="col-sm-8">
		<div class="page-header float-right">
			<div class="page-title">
				<ol class="breadcrumb text-right">

				</ol>
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


    </div><!-- /#right-panel -->

    <!-- Right Panel -->


    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="vendors/jszip/dist/jszip.min.js"></script>
    <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="assets/js/init-scripts/data-table/datatables-init.js"></script>

</body>
</html>