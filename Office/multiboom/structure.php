<?php

session_start();
include('db_connect.php');
$result = mysql_query("select * from users where phone='".$_SESSION['phone']."'");

if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
}
else {
	header("Location: index.php");
	die();
}

include("header.php");

$i = 1;
$sponsor = $row['user_id'];
while ($sponsor>0 && $i <= 8) {
	$result_x = mysql_query("select user_id, insta, sponsor from users where user_id=".$sponsor);
	if (mysql_num_rows($result_x)>0) {
		$row_x = mysql_fetch_array($result_x);
		$sponsor = $row_x['sponsor'];
		$data[] = $row_x;
	}
	$i++;
}
//print_r($data);
?>
<link rel="stylesheet" href="assets/css/Treant.css">
<link rel="stylesheet" href="assets/css/collapsable.css">
<style>
	#my_avatar1 {
		border-image: url("images/insta_border_sm.png");
		border-image-slice:27 27 27 27;
		border-image-width:27px 27px 27px 27px;
		border-image-outset:0px 0px 0px 0px;
		border-image-repeat:stretch stretch;
	}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
/*	var token = '6790446600.eae7038.5846f7c711204511aa133139a5fcea61',
    userid = 1362124742, // rudrastyh - my username :)
    num_photos = 4;
$.noConflict();
  jQuery( document ).ready(function( $ ) {
	$.ajax({ // the first ajax request returns the ID of user rudrastyh
		url: 'https://api.instagram.com/v1/users/self/media/recent',
		dataType: 'jsonp',
		type: 'GET',
		data: {access_token: token, q: userid}, // actually it is just the search by username
		success: function(data){
				console.log(data);
				$("#my_avatar").prop("src",data.data[0].id);
			},
		error: function(data){
			console.log(data);
		}
	});
  });*/
</script>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Структура</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Обзор->Структура</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
		<div class="animated fadeIn">
            <div class="row">
				<div class="col-md-12" style="text-align: center">
                    <div class="chart01" id="collapsable-example1" style="color: #111;"></div>

<script src="assets/js/raphael.js"></script>
<script src="assets/js/Treant.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.easing.js"></script>

<script>

		var chart_config1 = {
        chart: {
            container: "#collapsable-example1",

            animateOnInit: true,

            node: {
                collapsable: true
            },
            animation: {
                nodeAnimation: "easeOutBounce",
                nodeSpeed: 700,
                connectorsAnimation: "bounce",
                connectorsSpeed: 700
            }
        },
<?php
$i = 0;
$master = $row['user_id'];
$flag = ['','',''];
$result3 = mysql_query("select user_id, phone, status, status2 from users where sponsor='".$master."' order by id asc");
if (mysql_num_rows($result3) != 0) {
        ?>nodeStructure: {
			<?php
			if (is_file('images/avatar/'.$row['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row['user_id'].'.jpg'; else $avatar='images/user.png';
			?>
            image: "<?= $avatar ?>",
			text: {
				name: "<?= $master ?>",
				<?php
				if ($row['status']==1) echo 'active: "Активный(1500)",';
				else echo 'inactive: "Неактивный(1500)",';
				if ($row['status2']==1) echo 'active2: "Активный(15000)",';
				else echo 'inactive2: "Неактивный(15000)",';
				?>
				contact: "<?= $row['phone'] ?>"
			},

	<?php

	$result3 = mysql_query("select user_id, phone, status, status2 from users where sponsor='".$master."' order by id asc");
	if (mysql_num_rows($result3) != 0) {

		?>
		children: [

		<?php
		while ($row3 = mysql_fetch_array($result3)) {
			$i++;
			?>
			{
			<?php
			if (is_file('images/avatar/'.$row3['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row3['user_id'].'.jpg'; else $avatar='images/user.png';
			?>
            image: "<?= $avatar ?>",
			text: {
				name: "<?= $row3['user_id'] ?>",
				<?php
				if ($row3['status']==1) echo 'active: "Активный(1500)",';
				else echo 'inactive: "Неактивный(1500)",';
				if ($row3['status2']==1) echo 'active2: "Активный(15000)",';
				else echo 'inactive2: "Неактивный(15000)",';
				?>
				contact: "<?= $row3['phone'] ?>"
			},
			<?php
			$master1 = $row3['user_id'];
			$result4 = mysql_query("select user_id, phone, status, status2 from users where sponsor='".$master1."' order by id asc");
			if (mysql_num_rows($result4) != 0) {

			?>
			children: [

			<?php
			while ($row4 = mysql_fetch_array($result4)) {
				$i++;
				?>
				{
				<?php
				if (is_file('images/avatar/'.$row4['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row4['user_id'].'.jpg'; else $avatar='images/user.png';
				?>
				image: "<?= $avatar ?>",
				text: {
					name: "<?= $row4['user_id'] ?>",
				<?php
				if ($row4['status']==1) echo 'active: "Активный(1500)",';
				else echo 'inactive: "Неактивный(1500)",';
				if ($row4['status2']==1) echo 'active2: "Активный(15000)",';
				else echo 'inactive2: "Неактивный(15000)",';
				?>
				contact: "<?= $row4['phone'] ?>"
				},
				<?php
				$master1 = $row4['user_id'];
				$result5 = mysql_query("select user_id, phone, status, status2 from users where sponsor='".$master1."' order by id asc");
				if (mysql_num_rows($result5) != 0) {

				?>

				children: [

				<?php
				while ($row5 = mysql_fetch_array($result5)) {
					$i++;
					?>
					{
					<?php
					if (is_file('images/avatar/'.$row5['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row5['user_id'].'.jpg'; else $avatar='images/user.png';
					?>
					image: "<?= $avatar ?>",
					text: {
						name: "<?= $row5['user_id'] ?>",
						<?php
						if ($row5['status']==1) echo 'active: "Активный(1500)",';
						else echo 'inactive: "Неактивный(1500)",';
						if ($row5['status2']==1) echo 'active2: "Активный(15000)",';
						else echo 'inactive2: "Неактивный(15000)",';
						?>
						contact: "<?= $row5['phone'] ?>"
					},
					<?php
					$master1 = $row5['user_id'];
					$result6 = mysql_query("select user_id, phone, status, status2 from users where sponsor='".$master1."' order by id asc");
					if (mysql_num_rows($result6) != 0) {

					?>
					children: [

					<?php
					while ($row6 = mysql_fetch_array($result6)) {
						$i++;
						?>
						{
						<?php
						if (is_file('images/avatar/'.$row6['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row6['user_id'].'.jpg'; else $avatar='images/user.png';
						?>
						image: "<?= $avatar ?>",
						text: {
							name: "<?= $row6['user_id'] ?>",
							<?php
							if ($row6['status']==1) echo 'active: "Активный(1500)",';
							else echo 'inactive: "Неактивный(1500)",';
							if ($row6['status2']==1) echo 'active2: "Активный(15000)",';
							else echo 'inactive2: "Неактивный(15000)",';
							?>
							contact: "<?= $row6['phone'] ?>"
						},
						<?php
						$master1 = $row6['user_id'];
						$result7 = mysql_query("select user_id, phone, status, status2 from users where sponsor='".$master1."' order by id asc");
						if (mysql_num_rows($result7) != 0) {

						?>
						children: [

					<?php
					while ($row7 = mysql_fetch_array($result7)) {
						$i++;
						?>
						{
						<?php
						if (is_file('images/avatar/'.$row7['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row7['user_id'].'.jpg'; else $avatar='images/user.png';
						?>
						image: "<?= $avatar ?>",
						text: {
							name: "<?= $row7['user_id'] ?>",
							<?php
							if ($row7['status']==1) echo 'active: "Активный(1500)",';
							else echo 'inactive: "Неактивный(1500)",';
							if ($row7['status2']==1) echo 'active2: "Активный(15000)",';
							else echo 'inactive2: "Неактивный(15000)",';
							?>
							contact: "<?= $row7['phone'] ?>"
						},
						<?php
						$master1 = $row7['user_id'];
						$result8 = mysql_query("select user_id, phone, status, status2 from users where sponsor='".$master1."' order by id asc");
						if (mysql_num_rows($result8) != 0) {

						?>
							children: [

					<?php
					while ($row8 = mysql_fetch_array($result8)) {
						$i++;
						?>
						{
						<?php
						if (is_file('images/avatar/'.$row8['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row8['user_id'].'.jpg'; else $avatar='images/user.png';
						?>
						image: "<?= $avatar ?>",
						text: {
							name: "<?= $row8['user_id'] ?>",
							<?php
							if ($row8['status']==1) echo 'active: "Активный(1500)",';
							else echo 'inactive: "Неактивный(1500)",';
							if ($row8['status2']==1) echo 'active2: "Активный(15000)",';
							else echo 'inactive2: "Неактивный(15000)",';
							?>
							contact: "<?= $row8['phone'] ?>"
						},
						<?php
						$master1 = $row8['user_id'];
						$result9 = mysql_query("select user_id, phone, status, status2 from users where sponsor='".$master1."' order by id asc");
						if (mysql_num_rows($result9) != 0) {

						?>
						children: [
						<?php
						while ($row9 = mysql_fetch_array($result9)) {
							$i++;
						?>
							{
							<?php
							if (is_file('images/avatar/'.$row9['user_id'].'.jpg')) $avatar = 'images/avatar/'.$row9['user_id'].'.jpg'; else $avatar='images/user.png';
							?>
							image: "<?= $avatar ?>",
							text: {
								name: "<?= $row9['user_id'] ?>",
								<?php
								if ($row9['status']==1) echo 'active: "Активный(1500)",';
								else echo 'inactive: "Неактивный(1500)",';
								if ($row9['status2']==1) echo 'active2: "Активный(15000)",';
								else echo 'inactive2: "Неактивный(15000)",';
								?>
								contact: "<?= $row9['phone'] ?>"
							}
							},
							<?php
						} ?>
						]
					   <?php } ?>
						},
						<?php }  ?>
						]
					   <?php } ?>
						},
						<?php }  ?>
						]
					   <?php } ?>
						},
						<?php }  ?>
					]
						<?php } ?>
				},
				<?php }  ?>
			]
					<?php } ?>
			},
			<?php }  ?>
		]
				<?php } ?>
			},
			<?php }  ?>
		]
	<?php } ?>

	}
<?php } ?>
    };
tree = new Treant( chart_config1 );
</script>
				<div style="position: absolute; top: 100px;z-index:999">Партнеров в структуре: <?= $i ?></div>
            	</div>
			</div>
		</div>

        </div> <!-- .content -->
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!--  Chart js -->
    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
	<script src="assets/js/widgets.js"></script>

</body>

</html>
