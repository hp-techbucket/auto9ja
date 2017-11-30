$(document).ready(function() {
	
	$("#home a:contains('HOME')").parent().addClass('active');
	$("#about a:contains('ABOUT')").parent().addClass('active');
	$("#become_a_seller a:contains('BECOME A SELLER')").parent().addClass('active');
	$("#faq a:contains('FAQ')").parent().addClass('active');
	$("#vehicles a:contains('VEHICLE FINDER')").parent().addClass('active');
	$("#contact_us a:contains('CONTACT')").parent().addClass('active');
	$("#dashboard a:contains('MY ACCOUNT')").parent().addClass('active');
	$("#dashboard a:contains('My Dashboard')").parent().addClass('active');
	$("#customer_profile a:contains('Profile')").parent().addClass('active');
	$("#settings a:contains('Profile')").parent().addClass('active');
	$("#billing_methods a:contains('Profile')").parent().addClass('active');
	
	$("#inbox a:contains('MESSAGES')").parent().addClass('active');
	$("#inbox a:contains('Private Messages')").parent().addClass('active');
	$("#sent a:contains('MESSAGES')").parent().addClass('active');
	$("#sent a:contains('Private Messages')").parent().addClass('active');
	$("#watchlist a:contains('Watch list ')").parent().addClass('active');
	$("#statements a:contains('Statements')").parent().addClass('active');
	$("#pending_payments a:contains('Pending Payments')").parent().addClass('active');
	$("#payments a:contains('Payment History')").parent().addClass('active');
	
	$("#shipping a:contains('Shipping Status')").parent().addClass('active');
	
	$( "#load" ).hide();
	
		$('#uploadPhoto').change(function() {
			if($(this).val()){
				$('#uploading').show();
				setTimeout(function() { 
					$('#userProfileForm').submit();
				}, 2000);
			}
		});
		
	//function to display image name on select
		$('input[type=file]').change(function() {
					
			var img_name = $(this).val().replace(/C:\\fakepath\\/i, '');
			
			var span = $('span[for="'+$(this).attr('id')+'"]');
			//$(this).parent().after('<span>'+img_name+'</span>');
			//$('<span>').html(img_name).insertAfter($(this));
			span.html(img_name);
		});		
		
	$(".user-profile").hover(function(){
		$(".edit-profile",this).fadeToggle();
	});
		
	$(".edit-profile").click(function(){
		$(this).parent().next().slideToggle();
		$('.save-profile-btn').show();
	});
	
		$(".change-password").click(function () { 
			if($('.update-security').is(":hidden") == false){
				$('.update-security').hide(600);
			}
			$('.update-password').slideToggle(600);			
		});	
			
		$(".change-security").click(function () { 
			if($('.update-password').is(":hidden") == false){
				$('.update-password').hide(600);
			}
			$('.update-security').slideToggle(600);			
		});		
	
		
		$('#vehicle_make').on('change', function() {
			
			//alert( this.value );
			
			//$( "#load" ).show();

			var dataString = { 
				id : this.value
			};	
			
			$('#vehicle_model').find("option:eq(0)").html("Please wait..");
			
			$.ajax({
				
				type: "POST",
				url: baseurl+"vehicles/get_models",
				data: dataString,
				dataType: "json",
				cache : false,
				success: function(data){
					
					//$( "#load" ).hide();
					
					if(data.success == true){
						$("#vehicle_model").html(data.options);
					} 
									  
				},error: function(xhr, status, error) {
						alert(error);
				},

			});		
		});													
	

		
		$('#customer_country').on('change', function() {
			
			//alert( this.value );
			
			//$( "#load" ).show();

			var dataString = { 
				id : this.value
			};	
			
			$('#customer_state').find("option:eq(0)").html("Please wait..");
			
			$.ajax({
				
				type: "POST",
				url: baseurl+"location/get_states",
				data: dataString,
				dataType: "json",
				cache : false,
				success: function(data){
					
					$( "#load" ).hide();
					
					if(data.success == true){
						$("#customer_state").html(data.options);
					} 
									  
				},error: function(xhr, status, error) {
						alert(error);
				},

			});		
		});													

	
		$('#customer_state').on('change', function() {
			//alert( this.value );
			
			//$( "#load" ).show();

			var dataString = { 
				id : this.value
			};	

			$('#customer_city').find("option:eq(0)").html("Please wait..");
						
			$.ajax({
				
				type: "POST",
				url: baseurl+"location/get_cities",
				data: dataString,
				dataType: "json",
				cache : false,
				success: function(data){

					$( "#load" ).hide();
					
					if(data.success == true){
						$("#customer_city").html(data.options);
					} 			  
				},error: function(xhr, status, error) {
						alert(error);
				},
			});		
		});		
		
		
		$('.other_city').on('click', function(e) {
			e.preventDefault();
			//$('#city').replaceWith('');
			if($('#other_city').is(":hidden") == false){
				$('#other_city').hide();
				$('#customer_city').show();
			}else{
				$('#other_city').show();
				$('#customer_city').hide();
			}	
		});		
	
		$("#header_banner").hover(function(){
			$('#edit_icon').fadeToggle();
		});
	
		$("#headerBanner").click(function(){
			$('#bannerUpload').click();
		});
   
		$(".changeBanner").click(function(){
			$('#bannerUpload').click();
		});

		$('#bannerUpload').change(function() {
			if($('#bannerUpload').val()){
				$('#bannerForm').submit();
			}
		});
	
		$('.menu-collapse, .custom-collapse').on('click', function(e) {
			e.preventDefault();
		});
		
		$(".addBankAccountButton").click(function () { 
			$("i",this).toggleClass("glyphicon-plus-sign glyphicon-minus-sign");
			$('.add_bank_account_form').slideToggle(600);			
		});
		
		$(".addPayPalButton").click(function () { 
			$("i",this).toggleClass("glyphicon-plus-sign glyphicon-minus-sign");
			$('.add_PayPal_form').slideToggle(600);	
			$("html, body").animate({ scrollTop: $('body').height() }, 2000);
		});
				
			$("#paypal-accounts").click(function () { 
				$('#paypal-accounts-list').slideToggle(600);
				$("i",this).toggleClass("fa-angle-double-down fa-angle-double-up");
			});
			
			$("#bank-accounts").click(function () { 
				$('#bank-accounts-list').slideToggle(600);
				$("i",this).toggleClass("fa-angle-double-down fa-angle-double-up");
			});
			
			$(".bank-account-number").hover(function () { 
				$(".hiddenIcons",this).fadeToggle();
			
			});
			$("#paypal-accounts-list").hover(function () { 
				$(".hiddenIcons",this).fadeToggle();
			
			});
					
		//displays message when deposit
		//box is clicked
		$(".newDeposit").click(function (){
			depositAlert(); 
		});
		
		
		$(".side-menu-button").click(function (){
			$("#side-menu").slideToggle(); 
		});
	
		
		jssor_1_slider_init();
	
	
	});	
	
		jssor_1_slider_init = function() {

            var jssor_1_options = {
              $AutoPlay: true,
              $SlideWidth: 370,
              $Cols: 3,
              $Align: 100,
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*responsive code begin*/
            /*you can remove responsive code if you don't want the slider scales while window resizing*/
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 820);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();
            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*responsive code end*/
        };

	
				
		function togglePassword() {
			var upass = document.getElementById('upass');
			var toggleBtn = document.getElementById('toggleBtn');
			if(upass.type == "password"){
				upass.type = "text";
				toggleBtn.value = "Hide";
			} else {
				upass.type = "password";
				toggleBtn.value = "Show";
			}
		}
		
		function toggleAnswer() {
			var upass = document.getElementById('security_answer');
			var toggleBtn = document.getElementById('toggleBtn');
			if(upass.type == "password"){
				upass.type = "text";
				toggleBtn.value = "Hide";
			} else {
				upass.type = "password";
				toggleBtn.value = "Show";
			}
		} 		
		
	
	//function to handle submit contact us form
	function contactMessage() { 
	
		var error = '';
		var isFormValid = true; 
		$( "#load" ).show();
		
		//validate form before submit
		$("#contact_us_name,#contact_us_telephone,#contact_us_email,#contact_us_subject,#contact_us_message,textarea").change(function() {

			if ($(this).val().trim() === '') {
			        	
				$(this).css('border-color','red');     
			    isFormValid = false;
				
			}else{
				$(this).css('border-color','#B2B2B2');
			}
		});
		
		if(!isFormValid){
				$(".error-message").show();
				$(".error-message").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <i class="fa fa-exclamation-triangle"></i> All fields must be filled!');
				$(".error-message").append('<br/>');
				$(".error-message").append(error);
				$( "#load" ).hide();
				return isFormValid;
		} 
		
		var url = $(".contact_us_form").attr('action');
		
		var dataString = { 
			contact_us_name : $("#contact_us_name").val(),
			contact_us_telephone : $("#contact_us_telephone").val(),
			contact_us_email : $("#contact_us_email").val(),
			contact_us_subject : $("#contact_us_subject").val(),
			contact_us_message : $("#contact_us_message").val()
		};
		
		$.ajax({
			type: "POST",
			url: baseurl+url,
			data: dataString,
			dataType: "json",
			cache : false,
			success: function(data){

				if(data.success == true){
					
					$("#contact_us_name").val('');
					$("#contact_us_telephone").val('');
					$("#contact_us_email").val('');
					$("#contact_us_subject").val('');
					$("#contact_us_message").val('');
					$( "#load" ).hide();
					
					$("#response-message").html(data.notif);
					
				}else if(data.success == false){
					$( "#load" ).hide();
					$(".error-message").hide();
					$("#response-message").html(data.notif);
					//$("#response-errors").html(data.errors);
					$("#response-message").append(data.errors);
				}
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
				//alert(error);
				//location.reload();
			},
		});
	
	}	


	
	//function to handle add bank account
	function addBankAccount() { 
		
		
		var url = $('#addBankAccountForm').attr('action');
		
		var dataString = { 
			bank_name : $("#bank_name").val(),
			bank_location : $("#bank_location").val(),
			account_name : $("#account_name").val(),
			account_number : $("#account_number").val(),
			sort_code : $("#sort_code").val(),
			swift_bic : $("#swift_bic").val(),
			
		};
		
		$.ajax({
			type: "POST",
			url: baseurl+'payment/add_bank_account',
			data: dataString,
			dataType: "json",
			success: function(data){
				
				$( "#load" ).hide();
				
				if(data.success == true){
				
					$("#bank_name").val('');
					$("#bank_location").val('');
					$("#account_name").val('');
					$("#account_number").val('');
					$("#sort_code").val('');
					$("#swift_bic").val('');
					
					//$(".addCCDiv").hide();
					$('.add_bank_account_form').addClass('addForm');
					$('.add_bank_account_form').removeClass('add_bank_account_form');
					
					//window.location.reload(true);		  
					//$("#notif").html(data.notif);
					$("#success").html(data.notif);
					
					setTimeout(function() { 
						
						$("#success").html(data.notif).fadeOut(600); 
						window.location.reload(true);
					}, 2000); 
					//window.location.reload(true);
			
				}else if(data.success == false){
					$("#success").html(data.notif);
					//$("#").addClass('inputError');
				}
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
				// alert('Error: ' + status + ' ' + error);
			},
		});
		return false;
	}	

	
	//function to handle edit card
	function editBankAccount() { 
		
		$( "#load" ).show();
		var accountNo = $("#accountNo").val();
		if(!accountNo.match(/^\d+$/)){
			accountNo = $("#account_n").val();
		}
		var url = $('#editBankAccountForm').attr('action');
		//var form = new FormData(document.getElementById('editCardForm'));
		var dataString = { 
			id : $("#bankID").val(),
			bank_name : $("#bankName").val(),
			bank_location : $("#bankLocation").val(),
			account_name : $("#accountName").val(),
			account_number : accountNo,
			sort_code : $("#sortCode").val(),
			swift_bic : $("#swiftCode").val(),
			
		};
		$.ajax({
			type: "POST",
			url: baseurl+"payment/update_bank_details",
			data: dataString,
			//data: form,
			dataType: "json",
			cache : false,
			//contentType: false,
			//processData: false,
			success: function(data){

				$( "#load" ).hide();
				
				$("#editbankModal").modal('hide');
				
				$("#bankID").val('');
				$("#bankName").val('');
				$("#bankLocation").val('');
				$("#accountName").val('');
				$("#accountNo").val('');
				$("#sortCode").val('');
				$("#swiftCode").val('');

				if(data.success == true){
							  
					$("#notif").html(data.notif);
					$("#errors").html(data.upload_error);
					setTimeout(function() { 
						$("#notif").html(data.notif).fadeOut(600); 
						$("#errors").html(data.upload_error).fadeOut(600);
						window.location.reload(true);
					}, 2000);

				}else if(data.success == false){
							  
					//window.location.reload(true);
					//$("#notif").html(data.notif);
					$("#notif").html(data.notif);
					setTimeout(function() { 
						$("#notif").html(data.notif).fadeOut(600); 
						window.location.reload(true);
					}, 2000);
					//window.location.reload(true);
				}
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
			},
		});
	
	}
				
	
	//function to remove card
	function removeBank() { 
		
		$( "#load" ).show();
		var dataString = { 
			id : $("#bdID").val(),
			model : $("#bank_model").val()
		};

		$.ajax({
			type: "POST",
			url: baseurl+"payment/remove",
			data: dataString,
			dataType: "json",
			cache : false,
			success: function(data){

				$( "#load" ).hide();
				$("#removebankModal").modal('hide');

				$("#bdID").val('');
				$("#bank_model").val('');

				if(data.success == true){
					window.location.reload(true);		  
					setTimeout(function() { 
						$("#notif").html(data.notif).fadeOut(600); 
					}, 2000);
				}else if(data.success == false){

					$("#notif").html(data.notif);
					setTimeout(function() { 
						$("#notif").html(data.notif).fadeOut(600); 
						window.location.reload(true);
					}, 2000);
				}
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
				//alert(error);
				//location.reload();
			},
		});
	
	}	
	
	
	
	//function to handle add PayPal
	function addPayPal() { 

		$( "#load" ).show();
		var dataString = { 
			paypal_email : $("#paypal_email").val(),
		};
		
		$.ajax({
			type: "POST",
			url: baseurl+"payment/add_paypal",
			data: dataString,
			dataType: "json",
			success: function(data){
				
				$( "#load" ).hide();

				if(data.success == true){
					$("#paypal_email").val('');
					$("#notif").html(data.notif);
					$("#success").html(data.notif);
					
					setTimeout(function() { 
						
						window.location.reload(true);
					}, 2000); 
					//$(".addPayPalDiv").hide();
					
					$('.add_PayPal_form').addClass('addForm');
					$('.add_PayPal_form').removeClass('add_PayPal_form');
					
					//window.location.reload(true);	
					
				}else if(data.success == false){
					
					$("#success").html(data.notif);
					$("#paypal_email").addClass('input-error');
				}
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
				//alert('Error: ' + status + ' ' + error);
			},
		});
		return false;
	}	

	//function to handle Paypal Deposit
	function paypalDepositOld() {

		var url = $('#paypalDepositForm').attr('action');
		var form = new FormData(document.getElementById('paypalDepositForm'));

		$.ajax({
			type: "POST",
			url: baseurl+url,
			data: form,
			cache : false,
			contentType: false,
            processData: false,
			success: function(data){

				$( "#load" ).hide();

				$(".newDeposit").val('');
				$("#paypal_id").val('');
				
				$("#paypaldepositModal").modal('hide'); 
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
				//alert(error);
				//location.reload();
			},
		});
		return false;
	}	

	//function to handle Paypal Deposit
	function paypal_deposit() {
		
		$( "#load" ).show();
		
		var url = $('#paypalDepositForm').attr('action');
		
		//validate form before submit
		var isFormValid = true; 
		if ($('.newDeposit').val().trim() === '') {
			        	
			$('.newDeposit').css('border-color','red');     
			isFormValid = false;
				
		}else{
			$('.newDeposit').css('border-color','#B2B2B2');
		}
		
		if ($('.newDeposit').val().trim() < 5) {
			        	
			$('.newDeposit').css('border-color','red');     
			isFormValid = false;
				
		}else{
			$('.newDeposit').css('border-color','#B2B2B2');
		}
		
		if(!isFormValid){
				$(".form_errors").html('<div class="alert alert-danger text-danger text-center"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <i class="fa fa-exclamation-triangle"></i> Please enter a deposit amount no less than $5!</div>');
				$( "#load" ).hide();
				return isFormValid;
		} 
		
		$('#paypalDepositForm').submit();
	}	

	//delay paypal deposit onload
	//redirect
	function paypalRedirect(){
		
		setTimeout(function() { 	
			document.forms['paypal_auto_form'].submit();
		}, 300);	
	}	
	
	//function to handle edit paypal
	function editPayPal() { 
		
		$( "#load" ).show();
		
		var url = $('#editPayPalForm').attr('action');
		var form = new FormData(document.getElementById('editPayPalForm'));
		var paypal = $("#masked_paypal_email").val();
		//validate new paypal entry
		if( !isValidEmailAddress(paypal) ) { 
			return;
		}
		var dataString = { 
			id : $("#paypalID").val(),
			paypal_email : paypal,
		};
		
		$.ajax({
			type: "POST",
			url: baseurl+'payment/update_paypal',
			//data: form,
			data: dataString,
			dataType: "json",
			success: function(data){

				
				if(data.success == true){
					$( "#load" ).hide();
				
					$("#editPayPalModal").modal('hide');
				
					$("#masked_paypal_email").val('');
					$("#paypalID").val('');		 
					
					$("#notif").html(data.notif);
					
					setTimeout(function() { 
						
						window.location.reload(true);
					}, 2000);

				}else if(data.success == false){
						$( "#load" ).hide();
					  
					//window.location.reload(true);
					//$("#notif").html(data.notif);
					$("#alert-message").html(data.notif);
					
				}
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
			},
		});
	
	}	
	
	
	//function to remove PayPal
	function removePayPal() { 
		
		$( "#load" ).show();
		var dataString = { 
			id : $("#paypID").val(),
			model : 'paypal_accounts'
		};

		$.ajax({
			type: "POST",
			url: baseurl+"payment/remove",
			data: dataString,
			dataType: "json",
			cache : false,
			success: function(data){

				if(data.success == true){
					
					$( "#load" ).hide();
					$("#notif").html(data.notif);
					$("#removePayPalModal").modal('hide');

					$("#paypID").val('');
					$("#model").val('');
		  
					setTimeout(function() { 
						window.location.reload(true); 
					}, 2000);
				}else if(data.success == false){
					$( "#load" ).hide();
					$("#notif").html(data.notif);
					setTimeout(function() { 
						
						window.location.reload(true);
					}, 2000);
				}
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
				//alert(error);
				//location.reload();
			},
		});
	
	}		
			

	function isValidEmailAddress(emailAddress) {
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		return pattern.test(emailAddress);
	};
		
	//prevent user from entering anything
	//other than numbers
	function allowNumbersOnly(e) {
		
	  var charCode = (e.which) ? e.which : e.keyCode;
	  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		e.preventDefault();
	  }
	}
	
	
	//delay update profile
	// 
	function update_profile(){
		$( "#load" ).show();
		setTimeout(function() { 	
			$('#userProfileForm').submit();
		}, 1000);	
	}	
	
	
	//function to display message
	//when input text is clicked
	function depositAlert(){
		$('.depositNote').addClass("customAlert");
		$('.depositNote').text('Please enter a deposit of more than $10.00!');		
	}
			

