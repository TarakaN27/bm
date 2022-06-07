function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.disabled = false;
  copyText.select();
  document.execCommand("copy");
  copyText.disabled = true;
}

jQuery(document).ready(function($) {

	"use strict";

	[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
		new SelectFx(el);
	} );

	jQuery('.selectpicker').selectpicker;
	
	$('#add-profile').on('click', function(event) {
		event.preventDefault();
		$('form.add-person').toggleClass('active');
		var text = $(this).text();
		$(this).text(text == "Добавить" ? "Закрыть" : "Добавить");
	});
	
	$('#check-profile').on('click', function(event) {
		event.preventDefault();
		var val = $("form.add-person input[name='profile']").val();
		$.ajax({
        	type: "POST",
			url: "/Office/actions/check-profile.php",
			data: {"login": val},
			dataType: "json",
			success: function(data){
				if(data["success"] == 1) {
					$("input[name='profile']").addClass("success");
					$("form.add-person input[type='submit']").prop('disabled', false);
					
					$("input[name='profile']").on('change', function(event) {
						$("input[name='profile']").removeClass("success");
						$("form.add-person input[type='submit']").prop('disabled', true);
					});
					
				} else {
					$(".msg").html("<span style='color:red'>"+data["msg"]+"</span>");
				}
			},
			failure: function(errMsg) {
				console.log(errMsg);
			}
	  });
	});
	
	$('#leader_username_check').on('click', function(event) {
		event.preventDefault();
		var val = $("form input[name='leader']").val();
		$.ajax({
        	type: "POST",
			url: "/Office/actions/inf-check-profile.php",
			data: {"login": val, "type":"leader"},
			dataType: "json",
			success: function(data){
				if(data["success"] == 1) {
					$("input[name='leader']").addClass("success");
					$("form.add-infinity button[type='submit']").prop('disabled', false);
					$(".msg-leader").html("");
					
					$("input[name='leader']").on('change', function(event) {
						$("input[name='leader']").removeClass("success");
						$("form.add-infinity button[type='submit']").prop('disabled', true);
						$(".msg-leader").html("");
					});
					
				} else {
					$(".msg-leader").html("<span style='color:red'>"+data["msg"]+"</span>");
				}
			},
			failure: function(errMsg) {
				console.log(errMsg);
			}
	  });
	});
	
	$('#teacher_username_check').on('click', function(event) {
		event.preventDefault();
		var val = $("form input[name='teacher']").val();
		$.ajax({
        	type: "POST",
			url: "/Office/actions/inf-check-profile.php",
			data: {"login": val},
			dataType: "json",
			success: function(data){
				if(data["success"] == 1) {
					$("input[name='teacher']").addClass("success");
					$("form.add-infinity button[type='submit']").prop('disabled', false);
					$(".msg-teacher").html("");
					
					$("input[name='teacher']").on('change', function(event) {
						$("input[name='teacher']").removeClass("success");
						$("form.add-infinity button[type='submit']").prop('disabled', true);
						$(".msg-teacher").html("");
					});
					
				} else {
					$(".msg-teacher").html("<span style='color:red'>"+data["msg"]+"</span>");
				}
			},
			failure: function(errMsg) {
				console.log(errMsg);
			}
	  });
	});
	
	$('.add-infinity').on('submit', function(event) {
		event.preventDefault();
		var teacher = $("form.add-infinity input[name='teacher']").val();
		var leader = $("form.add-infinity input[name='leader']").val();
		var package_num = $("form.add-infinity button[type='submit']").attr("value");
		$.ajax({
        	type: "GET",
			url: "/Office/infinity/add-infinity.php",
			data: {"teacher": teacher, "leader":leader, "package": package_num},
			dataType: "json",
			success: function(data){
				console.log(data);
				if(data["msg"] == "ok") {
					window.location.href = "/Office/infinity/index.php?package=1";
				} else {
					$(".infinity .err-msg").text(data["msg"]);
				}
			},
			failure: function(errMsg) {
				console.log(errMsg);
			}
	  });
	});

	$('.upgrade-infinity').on('submit', function(event) {
		event.preventDefault();
		var package_num = $("form.upgrade-infinity button[type='submit']").attr("value");
		$.ajax({
        	type: "GET",
			url: "/Office/infinity/upgrade-infinity.php",
			data: {"package": package_num},
			dataType: "json",
			success: function(data){
				console.log(data);
				if(data["msg"] == "ok") {
					window.location.href = "/Office/infinity/";
				} else {
					$(".infinity .err-msg").text(data["msg"]);
				}
			},
			failure: function(errMsg) {
				console.log(errMsg);
			}
	  });
	});
	
	$(".packages .card.btn").on("click", function(){
		var id = $(this).attr("data-id");
		var name = $(this).attr("data-name");
		var price = $(this).attr("data-price");
		$(".packages .card.btn").removeClass("active");
		$(this).addClass("active");
		
		if($("form").hasClass("add-infinity")) {
			$(".infinity h1.title").text("Покупка пакета");
			$(".add-infinity button[type='submit']").attr("value", id);
			$("form.add-infinity button[type='submit']").prop('disabled', false);
			$(".add-infinity").removeClass("disabled");
		}
		if($("form").hasClass("upgrade-infinity")) {
			$(".infinity h1.title").text("Улучшение пакета");
			$(".upgrade-infinity button[type='submit']").attr("value", id);
			$(".upgrade-infinity button[type='submit']").prop('disabled', false);
		}
		
		$(".package-name").text(name);
		$(".package-price").text(price+" Тг.");
		$(".package-block").removeClass("d-none");
		
		
	})
	

	$('#change-level select').on('change', function(event) {
		var table = $(this).val();
		var infinity = ["infinity1", "infinity2", "infinity3"];
		if($.inArray(table, infinity) >= 0) {
			$('#change-level .teacher').removeClass("d-none");
			$('#change-level .leader').removeClass("d-none");
		} else {
			$('#change-level .teacher').addClass("d-none");
			$('#change-level .leader').addClass("d-none");
		}
	})

	$(".button-buyturbo").on("click", function(e){
		e.preventDefault();
		$.ajax({
        	type: "GET",
			url: "/Office/turboboom/buy-turbo.php",
			dataType: "json",
			success: function(data){
				console.log(data);
				if(data["msg"] == "ok") {
					window.location.href = "/Office/turboboom/";
				} else {
					Swal.fire('Покупка Turbo Boom', data["msg"], 'error');
				}
			},
			failure: function(errMsg) {
				console.log(errMsg);
			}
	  });		
	});

});