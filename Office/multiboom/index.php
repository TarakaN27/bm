<?php

session_start();
include('db_connect.php');
include "smsc_api.php";
$result = mysql_query("select * from users where login='".$_SESSION['login']."'");
$flag = false; $message = "";
date_default_timezone_set('Asia/Almaty');



if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_array($result);
	
	if (isset($_POST['sub_btn'])) {
		if ($row['akwa'] >= 1500) {
			mysql_query("update users set akwa=akwa-1500, status=1 where phone='".$_SESSION['phone']."'");
			$flag = true;
			$row['akwa'] = $row['akwa'] - 1500;
			$row['status'] = 1;
		}
		else $message = '<div class="alert alert-danger" role="alert">
                                        Недостаточно средств.
                                    </div>';
	}
	
	if (isset($_POST['sub_btn2'])) {
		if ($row['akwa2'] >= 15000) {
			mysql_query("update users set akwa2=akwa2-15000, status2=1 where phone='".$_SESSION['phone']."'");
			$flag2 = true;
			$row['akwa2'] = $row['akwa2'] - 15000;
			$row['status2'] = 1;
		}
		else $message2 = '<div class="alert alert-danger" role="alert">
                                        Недостаточно средств.
                                    </div>';
	}
	
	if (isset($_POST['sub_tovar'])) {
		mysql_query("update users set tovar=1 where login='".$row['login']."'");
		$row['tovar'] = 1;
	}
	
	$id = mysql_result(mysql_query("select id from matrix_1 where user_id=".$row['id']." order by id asc limit 0, 1"), 0);
}
else {
	header("Location: ../index.php");
	die();
}

include("header.php");
?>
<style>
	#my_avatar1 {
		border-image: url("images/insta_border_sm.png");
		border-image-slice:27 27 27 27;
		border-image-width:27px 27px 27px 27px;
		border-image-outset:0px 0px 0px 0px;
		border-image-repeat:stretch stretch;
	}
	.media a {
		color: #fff;
	}
	.modal-backdrop {
    /* bug fix - no overlay */    
    display: none;    
}
</style>






<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
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
$.noConflict();
jQuery( document ).ready(function( $ ) {
	$('.pop').on('click', function() {
			//$('.imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal({show: true, focus: true});   
		});	
	$(".subscr").on('click',function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: "POST",
			url: "subscribe.php",
			data: { 
				id: <?= $row['id'] ?>, // < note use of 'this' here
				subs_id: id
			},
			success: function(result) {
				//alert('ok');
				$('#'+id+'btn').attr("disabled","true");
				$('#'+id+'btn').html("Подписки&nbsp<i class='fa fa-check-circle'></i>");
			},
			error: function(result) {
				alert( id+'error');
			}
		});
		window.location.href = $(this).data('target');
	});	  
});

function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.disabled = false;
  copyText.select();

  /* Copy the text inside the text field */
  document.execCommand("copy");
  copyText.disabled = true;

}
</script>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Профиль</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active"></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
		<div class="animated fadeIn">
                <div class="row">
		<div class="col-lg-6 col-md-12">
        <aside class="profile-nav alt">
            <section class="card">
                <div class="card-header user-header alt bg-fffff">
                    <div class="media">
                        
                        <div class="media-body">
							<h6 class="text-black display-6">Добро пожаловать!</h6>
                            <h3 class="text-black display-6"><?= $row['fio'] ?></h3>
                        </div>
                    </div>
                </div>


                
					
					
					<li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-calendar"style="font-size:18px;color:white"></i> <font color="White"> Дата активации </font> <span class="badge badge-secondary pull-right"><?= $row['reg_time'] ?></span>
                    </li>
                   <li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-phone"style="font-size:18px;color:white"></i> <font color="White"> Номер телефона </font><span class="badge badge-secondary pull-right"><?= $row['phone'] ?></span>
                    </li>
					<li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-star"style="font-size:18px;color:white"></i> <font color="White">Ваш Лидер </font><span class="badge badge-secondary pull-right"><?= $row['sponsor'] ?></span>
                    </li>
					<li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-link"style="font-size:18px;color:white"></i> <font color="White"> Реферальнная ссылка </font> <input type="text" class="form-control pull-left" id="myInput" disabled value="http://bm-market.kz/Office/register.php?rel=<?= base64_encode($row['login']) ?>" />
						<button type="button" class="btn btn-secondary" onclick="myFunction()"> Скопировать реферальную ссылку </button>
						<br/>
						
						<script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="https://yastatic.net/share2/share.js"></script>

                    </li>
                    <li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <i class="fa fa-location-arrow"style="font-size:18px;color:white"></i> <font color="White"> Местоположение </font> <span class="badge badge-secondary pull-right"><?= $row['city'] ?></span>
                    </li>
                    <li class="list-group-item" style="background: rgba(40, 167, 69, 1);">
                        <a href="profile.php" style="color: black"> <i class="fa fa-cog"style="font-size:18px;color:white"></i> <font color="White"> Изменить профиль </font></a>
						
                    </li>
                </ul>

            </section>
        </aside>
    </div>
<?php
	$res_d = mysql_query("select * from m1 where sponsor_login='".$_SESSION['login']."'");
	if ($row['login'] == 'drakula') {
?>
			<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
				<div class="card" style="background: #55c912; color: white">
                    <div class="card-body">
						<h3>Компания Boom Market</h3><br>
		<p style="color: white">УРА! УРА! УРА! УРА! УРА!</p>
		<p style="color: white">ПРОМОУШЕН НА ЦИСТАНХЕ</p>
		<p style="color: white">УВАЖАЕМЫЕ ПАРТНЕРЫ 🔥🔥🔥🔥🔥🔥🔥🔥🔥 СУПЕР ПРОМОУШЕН ДЛЯ НОВИЧКОВ И ДЛЯ ТЕХ ЛЮДЕЙ У КОГО НЕТУ НИ ОДНОГО ПАРТНЕРА</p>				
		<p style="color: white">Кто закроет 1 ЭТАП( В ПОДАРОК  ПОЛУЧИТЕ ПРОДУКЦИЮ ЦИСТАНХЕ СРОК ПРОМОУШЕНА С 19.07.2020  14:00 часов ПО 20.07.2020 до 00:00 ВКЛЮЧИТЕЛЬНО</p>
		<p style="color: white">С уважением администрация Boom Market</p>
		<p style="color: white">( ВСЕГО 1 ДЕНЬ! ПРОДЛЕВАНИЕ НЕ БУДЕТ! ПРЕДУПРЕЖДАЕМ!)</p>
					</div>
				</div>
			</div>
<?php } ?>

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
				<div class="card" style="background: rgba(3, 3, 3, 0);">
                    <div class="card-body">
                        <div class="stat-widget-one text-center">
							
							
							<div class="content mt-3">
            <div class="animated fadeIn">


                <div class="row">

                     <div class="col-lg-12">
                        
						 <div class="card" style="background: #fffff; text-align: center">
                    <div class="card-body">
                        <div class="stat-widget-one mb-2">
                            <div><i class=" text-success border-success"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text text-black"> </div>
								<button type="button" class="btn btn-success" onclick=""> Пополнить баланс </button>
								
                                <div class="stat-digit text-black">Текущий баланс <?= $row['akwa'] ?></div>
							</div>
						</div>
					</div>
				</div>
						 
						 
                    </div>
                    </div>
				
				<!-- .animated -->
			</div>
							
							
							
				
							
                        </div>
                    </div>
				</div>
				
				
            </div>
					
			
		</div>
		</div>

        </div> <!-- .content -->
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!--  Chart js -->
    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
	<script src="assets/js/widgets.js"></script>

</body>

</html>
