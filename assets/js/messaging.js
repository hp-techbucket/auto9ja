$(document).ready(function(){
		
		//function to preview message from the header
		$('.detail-message').click(function() {
					
			$( "#load" ).show();

			var dataString = { 
				id : $(this).attr('id')
			};				
			$.ajax({
				
				type: "POST",
				url: baseurl+"message/detail",
				data: dataString,
				dataType: "json",
				cache : false,
				success: function(data){

					$( "#load" ).hide();

					if(data.success == true){

						$("#show_name").html(data.name);
						$("#show_email").html(data.email);
						$("#show_subject").html(data.subject);
						$("#show_message").html(data.message);
						$("#replyButton").html('<a data-toggle="modal" data-target="#replyModal1" class="btn btn-default quick_reply"  id="'+data.id+'"><i class="fa fa-reply"></i> Reply</a>');
						$("#show_date").html(data.date_sent);
						$("#unread_messages").html(data.count_unread);				

					} 
						  
				},error: function(xhr, status, error) {
						alert(error);
				},

			});					
		});	
	
		//function to send reply
		$(".reply_message").click(function () {
			
			$( "#load" ).show();
			//$("#replyModal").modal('show');
			
			var dataString = { 
				id : $(this).attr('id')
				//id : message_id
			};				
			$.ajax({
				
				type: "POST",
				url: baseurl+"message/detail",
				data: dataString,
				dataType: "json",
				cache : false,
				success: function(data){

					$( "#load" ).hide();

					if(data.success == true){
						
						$("#headerTitle").html(data.headerTitle);
						
						$("#replying_to").html(data.replying_to);
						
						//populate the hidden fields
						document.replyForm.message_id.value = data.message_id;
						document.replyForm.sender_name.value = data.sender_name;
						document.replyForm.sender_email.value = data.sender_email;
						document.replyForm.receiver_name.value = data.receiver_name;
						document.replyForm.receiver_email.value = data.receiver_email;

						//$("#message_subject").html(data.message_subject);
						$("#message_subject").val(data.message_subject);
						$(".customTextArea").html(data.message_details);

					} 	  
				},error: function(xhr, status, error) {
					//	alert(error);
				},
			});	
		});

		
		//function to send new message
		$(".send_message").click(function () {
			
			$( "#load" ).show();
			
			var dataString = { 
				email : $(this).attr('id')
			};				
			$.ajax({
				
				type: "POST",
				url: baseurl+"message/new_message_detail",
				data: dataString,
				dataType: "json",
				cache : false,
				success: function(data){

					$( "#load" ).hide();

					if(data.success == true){
						
						$("#headerTitle").html(data.headerTitle);
						
						$("#to").html(data.to);
						
						//populate the hidden fields
						document.messageForm.model.value = data.model;
						document.messageForm.sender_name.value = data.sender_name;
						document.messageForm.sender_email.value = data.sender_email;
						document.messageForm.receiver_name.value = data.receiver_name;
						document.messageForm.receiver_email.value = data.receiver_email;

					} 	  
				},error: function(xhr, status, error) {
					//	alert(error);
				},
			});	
		});			
		
		//function to send new message
		$(".send_message").click(function () {
			
			$( "#load" ).show();
			
			var dataString = { 
				email : $(this).attr('id')
			};				
			$.ajax({
				
				type: "POST",
				url: baseurl+"message/new_message_detail",
				data: dataString,
				dataType: "json",
				cache : false,
				success: function(data){

					$( "#load" ).hide();

					if(data.success == true){
						
						$("#headerTitle").html(data.headerTitle);
						
						$("#to").html(data.to);
						
						//populate the hidden fields
						document.messageForm.model.value = data.model;
						document.messageForm.sender_name.value = data.sender_name;
						document.messageForm.sender_email.value = data.sender_email;
						document.messageForm.receiver_name.value = data.receiver_name;
						document.messageForm.receiver_email.value = data.receiver_email;

					} 	  
				},error: function(xhr, status, error) {
					//	alert(error);
				},
			});	
		});	
});


	//function to get users email
	function checkEmail(email) {

		var dataString = { 
			emailAddress : email
		};				
		$.ajax({
				
			type: "POST",
			url: baseurl+"message/get_unread",
			data: dataString,
			dataType: "json",
			cache : false,
			success: function(data){
				if(data.success == true){	
					$("#unread_messages").html(data.unread_messages);
				} 	  
			},error: function(xhr, status, error) {
				//alert(error);
			},
		});	
	}		
		
	
	//function to handle submit reply
	function submitReply() { 
		
		$( "#load" ).show();
		
		var messageID = $("#message_id").val();
		var senderName = $("#sender_name").val();
		var senderEmail = $("#sender_email").val();		
		var receiverName = $("#receiver_name").val();
		var receiverEmail = $("#receiver_email").val();
		var messageSubject = $("#message_subject").val();
		var messageDetails = $(".customTextArea").val();

		
		var dataString = { 
			message_id : messageID,
			sender_name : senderName,
			sender_email : senderEmail,
			receiver_name : receiverName,
			receiver_email : receiverEmail,
			message_subject : messageSubject,
			message_details : messageDetails
		};

		$.ajax({
			type: "POST",
			url: baseurl+"message/send_message_validation",
			data: dataString,
			dataType: "json",
			cache : false,
			success: function(data){

				$( "#load" ).hide();
				
				$("#replyModal").modal('hide');
				
				$("#message_id").val('');
				$("#sender_name").val('');
				$("#sender_email").val('');
				$("#receiver_name").val('');
				$("#receiver_email").val('');
				$("#message_subject").val('');
				$(".customTextArea").val('');

				if(data.success == true){
							  
					$("#notif").html(data.notif);
					setTimeout(function() { 
						$("#notif").html(data.notif).fadeOut(600); 
						window.location.reload(true);
					}, 2000);
					//window.location.reload(true);
					
					var socket = io('http://localhost:8080');
					//var socket = io.connect( 'http://'+window.location.hostname+':3000' );
					
					socket.emit('new_count_message', { 
						new_count_message: data.new_count_message
					});

					//socket.emit('test', { 
					//	test: 'This is just a test!'
					//});					

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
				//alert(error);
				//location.reload();
			},
		});
	
	}		


	
	//function to handle submit new message
	function newMessage() { 
		
		$( "#load" ).show();
		//get values from form
		var dataString = { 
			sender_name : $("#sender_name").val(),
			message_subject : $("#message_subject").val(),
			message_details : $("#message_details").val()
		};
		
		//get url
		var url = $('#newMessageForm').attr('action');
		
		$.ajax({
			type: "POST",
			url: url,
			data: dataString,
			dataType: "json",
			cache : false,
			success: function(data){

				$( "#load" ).hide();
				
				if(data.success == true){
					
					$("#newMessageModal").modal('hide');
					$("#sender_name").val('');
					$("#message_subject").val('');
					$("#message_details").val('');
		  
					$("#notif").html(data.notif);
					setTimeout(function() { 
					//	window.location.reload(true);
					$("#notif").fadeOut(600);
					}, 900);

				}else if(data.success == false){

					$("#message-errors").html(data.notif);
					//setTimeout(function() { 
					//	$("#notif").html(data.notif).fadeOut(600); 
					//	window.location.reload(true);
					//}, 2000);
					//window.location.reload(true);
				}
					
			},error: function(xhr, status, error) {
				$( "#load" ).hide();
				//alert(error);
				//location.reload();
			},
		});
	
	}	
	
	//function to handle submit reply
	function submitMessage() { 
		
		$( "#load" ).show();

		var dataString = { 
			model : $("#model").val(),
			sender_name : $("#sender_name").val(),
			sender_email : $("#sender_email").val(),
			receiver_name : $("#receiver_name").val(),
			receiver_email : $("#receiver_email").val(),
			message_subject : $("#messageSubject").val(),
			message_details : $("#messageDetails").val()
		};
		
		$.ajax({
			type: "POST",
			url: baseurl+"admin/send_message_validation",
			data: dataString,
			dataType: "json",
			cache : false,
			success: function(data){

				$( "#load" ).hide();
				
				$("#messageModal").modal('hide');
				
				$("#message_id").val('');
				$("#sender_name").val('');
				$("#sender_email").val('');
				$("#receiver_name").val('');
				$("#receiver_email").val('');
				$("#messageSubject").val('');
				$("#messageDetails").val('');

				if(data.success == true){
							  
					$("#notif").html(data.notif).fadeOut(300);
					setTimeout(function() { 
						 
						window.location.reload(true);
					}, 100);
					//window.location.reload(true);
					
					var socket = io('http://localhost:8080');
					//var socket = io.connect( 'http://'+window.location.hostname+':3000' );
					
					socket.emit('new_count_message', { 
						new_count_message: data.new_count_message
					});
				

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
				//alert(error);
				//location.reload();
			},
		});
	
	}	
	
	//function to populate the hidden message values
	//before reply form submit
	function replyBoxHidden(id,sName,sEmail,rName,rEmail) {
		document.reForm.message_id.value = id;
		document.reForm.sender_name.value = sName;
		document.reForm.sender_email.value = sEmail;
		document.reForm.receiver_name.value = rName;
		document.reForm.receiver_email.value = rEmail;
	}

		function markAsRead(msgID) {

		  $(".subjectToggle").click(function () {

				$('#subj_line_'+msgID).addClass('msgRead');
				var id = msgID;
				if(id === '')
				  return;
				jQuery.ajax(
					{
					 type: "POST",
					 url: baseurl+"message/mark_as_read/"+id,
					 dataType: 'json',
					 data: {message_id: id},
					 success: function(data){
					 $('.tags_found').html(data);
					}
				});
						
			});
		}	
		
				
	
	$(document).ready(function() { 
		$(".subjectToggle").click(function () { 
			if ($(this).next().is(":hidden")) {
				$(".hiddenDiv").hide();
				$(this).next().slideDown("fast"); 
				//$(this).next().show(600);
			} else { 
				$(this).next().hide(); 
			} 
		});
		$(".messageToggle").click(function () { 
			if ($(this).next().is(":hidden")) {
				$(".hiddenDiv").hide();
				$(this).next().slideDown("fast"); 
				//$(this).next().show(600);
			} else { 
				$(this).next().hide(); 
			} 
		});
	});	
	