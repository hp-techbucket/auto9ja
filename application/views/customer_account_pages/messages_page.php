
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>
                
			
<div id="notif">
<?php
	//handles success message display
		$flashdata = '';
		if($this->session->flashdata('message_sent') != ''){
			$flashdata = $this->session->flashdata('message_sent');
		}
		
		//handles deleted message display
		if($this->session->flashdata('message_deleted') != ''){
			$flashdata = $this->session->flashdata('message_deleted');
		}
		
		//handles error message display
		if($this->session->flashdata('message_error') != ''){
			$flashdata = $this->session->flashdata('message_error');
		}
		echo $flashdata;
		
	
?>
</div>						
					
					
	<?php 


		//define form attributes
		$attributes = array('name' => 'myform');
							
		//start message form
		echo form_open('message/multi_delete', $attributes);
						
		//Title bar checkbox property setting
		$data = array(
			'name' => 'toggleAll',
			'id' => 'toggleAll',
			'value' => 'accept',
			'checked' => false,
			'onClick' => 'checkAll(this.checked)',
			'style' => 'margin-left:30%',
		);
		
		$hidden = array('table' => 'messages',);	
		echo form_hidden($hidden);	
									
	?>		
          
			<div class="container">
				<div class="row">
					<div class="col-xs-3">
						<?php echo img('assets/images/icons/crookedArrow.png');?>
						<a class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" id="delButton" ><i class="fa fa-trash-o"></i> Delete</a>
						<a class="btn btn-primary"  title="Send Message to Customer Support" data-toggle="modal" data-target="#newMessageModal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> New Message</a>
					</div>
				</div>
			</div>	
			
				<div class="row">
					<div class="col-lg-12">
						<div class="table-responsive">
							<table frame="box" class="table table-hover table-striped custom-table-header" >
								<thead>
						
									<tr>
										<th width="5%" align="left"><?php echo form_checkbox($data);?></th>
										<th width="10%" align="left"></th>
										<th width="40%" align="left">From</th>
										<th width="25%" align="center">Subject</th>
										<th width="20%" align="left">Date</th>
									</tr>
								</thead>					
								<tbody>		
	<?php
						
							$messageTable='';
							
							//check messages array for messages to display			
							if(!empty($messages_array)){
								
								//obtain each row of message
								foreach ($messages_array as $message){			
								
									//$my_email = $message->receiver_name;
									///$from_email = $message->sender_name; 
									
									//check if message has been read
									if($message->opened == "0"){		
										$textWeight = 'msgDefault';		
									}else{	
										$textWeight = 'msgRead';
									}			

									//check if message has been replied
									if($message->replied == "1"){
										$replied = '<i class="fa fa-reply" aria-hidden="true"></i>';		
									}else{		
										$replied = '';
									}	

									//set the message checkbox properties
									$data_checkbox = array(
									
										'name' => 'cb[]',
										'id'   => 'cb',
										'value' => $message->id,
										'checked' => false,
										'onClick' => 'eDelete(this)',
										'style' => 'margin:10px',
									);
									$thisRandNum = md5(uniqid());

									//create link for reply function
									//$link2 = anchor('admin/reply/'.$message->message_id.'/'.$thisRandNum.'/', 'REPLY', array('title' => 'Reply Message', 'id' => 'reply_message'));
									//$link2 = '<a href="javascript:void(0)" onclick="location.href='.base_url().'message/reply/'.$message->message_id.'/'.$thisRandNum.'" title="Reply Message" class="btn btn-default" ><i class="fa fa-reply"></i> Reply</a>';
									
									$sender_photo = '';
									
									
									if($message->sender_name == 'Admin'){
										$sender_photo = '<img class="media-object" src="http://placehold.it/50x50" width="40" height="40" alt="">';
										
									}

									
	?>		
								<tr>
									<td width="5%" align="left"><?php echo form_checkbox($data_checkbox) ; ?></td>
									<td width="10%" align="left"><?php echo $replied; ?></td>
									<td width="40%" align="left"><span class="pull-left"><?php echo $sender_photo; ?></span> <?php echo nbs(2).' '.$message->sender_name; ?></td>
									<td width="25%" align="left"><span class="subjectToggle" style="padding:3px;">
												<a class="<?php echo $textWeight; ?>" id="subj_line_<?php echo $message->id; ?>" onclick="markAsRead(<?php echo $message->id; ?>); " style="cursor:pointer; "><?php echo stripslashes($message->message_subject); ?></a>
												</span>		
												<div class="hiddenMessage"><br/><?php echo 
													stripslashes(wordwrap(nl2br($message->message_details), 54, "\n", true)); ?>
													<br/><br/>
													<strong> <a data-toggle="modal" data-target="#replyModal" class="btn btn-default reply_message"  id="<?php echo $message->id;?>"><i class="fa fa-reply"></i> Reply</a></strong>
												<br/>
												</div></td>
									<td width="20%" align="left"><?php echo date("F j, Y", strtotime($message->date_sent)); ?></td>
										
								</tr>
						<?php 
								}
							}else {
							?>	
				  
								<tr id="no-message-notif">
									<td colspan="5" align="center"><div class="alert alert-danger" role="alert">
										<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
										<span class="sr-only"></span> No messages!</div>
									</td>
								</tr>
								
							<?php
							}
							?>

								</tbody>
							</table>
						</div>
					</div>	
				</div>
			<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Delete Message
				  </div>
				  <div class="modal-body">
					<strong>Are you sure you want to permanently delete the selected message(s)?</strong>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					
					<input type="submit" class="btn btn-primary" name="deleteBtn" value="OK">
				  </div>
				</div>
			  </div>
			</div>	
			
<?php 			
	//close the message form
	echo form_close();			
 ?>
				

    <div class="row">
        <div class="col-md-12 text-center">
            <?php echo $pagination; ?>
        </div>
    </div>






<?php   
		}
	}								
?>

					</div><!--/col-span-9-->
				</div><!--.row-->
		</div><!--.custom-container-->
    </div><!--.container-->


		<form action="javascript:submitReply();" id="reply_form" name="replyForm" method="post">
			<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4><span id="replying_to"></span></h4>
				  </div>
				  <div class="modal-body">
						<p>
							
							<input type="hidden" id="message_id" name="message_id" >
							<input type="hidden" id="sender_name" name="sender_name" >
							<input type="hidden" id="sender_username" name="sender_username" >
							<input type="hidden" id="receiver_name" name="receiver_name" >
							<input type="hidden" id="receiver_username" name="receiver_username" >							
						</p>
						<p>
							<label for="message_subject">Subject</label><br/>
							<input type="text" id="message_subject" name="message_subject"/>
							<span id="subject_error"></span>						
						</p>
						<p>
							<label for="message_details">Message</label><br/>
							<textarea name="message_details" class="customTextArea" ></textarea>
							<span id="message_error"></span>						
						</p>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-primary" name="reply" value="submit">
					<input type="button" class="btn btn-primary" name="reply" onclick="javascript:submitReply();" id="replyBtn" value="Send Reply">
				  </div>
				</div>
			  </div>
			</div>	
		</form>