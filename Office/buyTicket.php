<?php
session_start();

include("db_data.php");
include("db_connect.php");
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_database);
$mysqli->set_charset("utf8");

if (mysqli_connect_errno()) {
    printf("Ошибка подключения: %s\n", mysqli_connect_error());
    exit();
}

function find( $sql ) {
	global $mysqli;
	if ($result = $mysqli->query($sql)) {
		$rows = array();		
		while($row = $result->fetch_assoc())
			$rows[] = $row;					
		return $rows;
	} else {
		return $mysqli->errno . ". " . $mysqli->error;
		exit;
	}
	$result->free();
}


function findOne($sql) {
	$tempArr = find( $sql." LIMIT 1" );
	if(isset($tempArr[0])) {
		return $tempArr[0];
	} else {
		return false;
	}
}


function save($sql, $id=false, $msg=false, $type=false, $temp_val=NULL) {
	global $mysqli;
	$r = false;
	$result = $mysqli->query($sql);
	if ($result) {
		$r = $mysqli->insert_id;
		if($id){
			$sql = htmlspecialchars(str_replace(array('(',')', '`', "'"),array('[',']', '', ''),$sql));
			$mysqli->query("INSERT INTO `sklad_history` (`from_id`, `to_id`, `text`, `msg`, `date`, `type`, `temp_val`) VALUES ('".$_SESSION["id"]."', '".$id."', '".$sql."', '".$msg."', '".date("Y-m-d H:i:s")."', '".$type."', '".$temp_val."')");
		}
	} else {
		$r = $mysqli->error;
	}
	
	return $r;
}

$gifts = find("SELECT * FROM `gifts`");

$coupons = find("SELECT * FROM `coupons`");

$img_preview = findOne("SELECT * FROM options WHERE name='preview_coupons'")["value"];

$result = findOne("select * from users where login='".$_SESSION['login']."'");
if (count($result) != 0) {
	$fio = $result["fio"];
	date_default_timezone_set('Asia/Aqtau');
	
	$coupons_starts = find("SELECT * FROM coupons WHERE status=1");
	$storekeeper_list = find("SELECT * FROM users WHERE role=2");

	if(isset($coupons_starts[0])) {
		foreach($coupons_starts as $this_id=>$this_coupon){
			$coupon_gifts = json_decode($this_coupon["gifts"],true);
			$sold_gifts_query = find("SELECT * FROM buy_tickets WHERE coupon_id='".$this_coupon["id"]."'");
			$sold_gifts = [];
			foreach($sold_gifts_query as $sold){
				if(isset($sold_gifts[$sold["gifts"]])) {
					$sold_gifts[$sold["gifts"]] += 1;
				} else {
					$sold_gifts[$sold["gifts"]] = 1;
				}
			}
			foreach($coupon_gifts as $id=>$count){
				if(!isset($sold_gifts[$id])) continue;
				$coupon_gifts[$id] -= $sold_gifts[$id];
				if($coupon_gifts[$id] <= 0) {
					unset($coupon_gifts[$id]);
				}
			}
			if(count($coupon_gifts)==0){
				unset($coupons_starts[$this_id]);
			}
		}
	}
	
}
else {
	header("Location: index.php");
	die();
}

include('header.php');
?>
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
				<h1>Подарки компании</h1>
			</div>
		</div>
	</div>
</div>

<div class="content mt-3">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-sm-6 m-auto mb-4">
			<div class="card">
				<div class="card-body px-0 col-sm-10 m-auto">
					<? if(count($coupons_starts)>0): ?>
					<form method="POST" class="buy-coupon">
						<input type="hidden" name="id" value="<?=$_SESSION["id"]?>">
						<p class="mb-0">Выберите розыгрыш:</p>
						<div class="input-group input-group-outline mb-2">
							<select name="coupons" class="form-control">
								<option>Выберите розыгрыш</option>
								<? foreach($coupons_starts as $coupon): ?>
									<option value="<?=$coupon["id"]?>"><?=$coupon["name"]?></option>
								<? endforeach; ?>
							</select>
						</div>
						<img id="img-banner" class="my-2 m-auto w-100" src="https://sklad.bm-market.kz/uploads/<?=$img_preview?>">
						<div style="display:none" class="ticket-info">
							<p class="my-4">Призы:
								<span class="gifts"></span>
							</p>
							<p>Цена за билет: <span class="coupon-price"></span> Тг.</p>
							<div class="input-group input-group-outline mb-2 d-flex flex-column">
								<label class="form-label">Выберите кладовщика</label>
								<input type="text" id="storekeeper" name="storekeeper" class="form-control w-100">
								<input type="hidden" name="storekeeper_id">
							</div>
							<div class="input-group input-group-outline d-flex flex-column mb-4">
								<label class="form-label">Введите количество билетов</label>
								<input type="number" max="" name="count" class="form-control w-100">
							</div>
							<div class="input-group w-100">
								<input type="submit" class="btn btn-primary m-auto mt-4" value="Купить">
							</div>
						</div>
					</form>
					<? else: ?>
						<h6>Нет доступных розыгрышей</h6>
					<? endif; ?>
				</div>
			</div>
		</div>
		</div>
	</div><!-- .animated -->
</div><!-- .content -->

<? include("footer.php"); ?>

<script src="https://kraaden.github.io/autocomplete/autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="https://kraaden.github.io/autocomplete/autocomplete.css">
<script>
	
var countries = [
	<? foreach($storekeeper_list as $storekeeper): ?>
    	{ label: 'Логин: <?=$storekeeper["login"]?> ФИО: <?=$storekeeper["fio"]?> Город: <?=$storekeeper["city"]?>', value: '<?=$storekeeper["login"]?>' },
	<? endforeach; ?>
];

autocomplete({
  input: document.getElementById('storekeeper'),
  minLength: 1,
  onSelect: function (item, inputfield) {
    inputfield.value = item.value
	$("input[name='storekeeper_id']").val(item.value);
  },
  fetch: function (text, callback) {
    var match = text.toLowerCase();
    callback(countries.filter(function(n) { return n.label.toLowerCase().indexOf(match) !== -1; }));
  },
  emptyMsg: "Нет результатов"
})
</script>

<script>
$("form.buy-coupon select[name='coupons']").on("change", function(){
		var coupon_id = $(this).val();
		$.ajax({
			type: 'POST',
			url: 'https://sklad.bm-market.kz/actions/getCoupon.php',
			data: {"coupon_id": coupon_id},
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data["success"]==1){
					$("form.buy-coupon input[name='count']").attr("max", data["gift_max"]);
					$("form.buy-coupon .coupon-price").text(data["coupon"]["cost"]);
					$("form.buy-coupon .gifts").text(data["gifts"]);
					$("form.buy-coupon #img-banner").attr("src", "https://sklad.bm-market.kz"+data["coupon"]["image"]);
					$(".ticket-info").show();
				}
			}
		})
	})
	
	$("form.buy-coupon").submit(function(e){
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'https://sklad.bm-market.kz/actions/buyTicket.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data["success"]==1){
					swal.fire({
						text: "Вы успешно купили билет!<br>",
						html:
						'Вы успешно купили билет!<br>' + data["ticket"],
						imageUrl: 'https://sklad.bm-market.kz'+data["img_ticket"],
						buttonsStyling: false,
						confirmButtonText: "Понятно!",
						customClass: {
							confirmButton: "btn font-weight-bold btn-primary"
						}
					}).then(function() {
						location.reload();
					});
				}
			}
		})
	});	
</script>