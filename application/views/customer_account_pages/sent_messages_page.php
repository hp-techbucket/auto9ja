
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>

<div>
<?php
	//handles deleted message display
		$deleted = '';
		if($this->session->flashdata('message_deleted') != ''){
			$deleted = $this->session->flashdata('message_deleted');
		}
		echo $deleted;
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
		
		$hidden = array('table' => 'sent_messages',);	
		echo form_hidden($hidden);	
				
									
	?>		
			<div class="container">
				<div class="row">
					<div class="col-xs-3">
						<?php echo img('assets/images/icons/crookedArrow.png');?>
						<a class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" id="delButton" ><i class="fa fa-trash-o"></i> Delete</a>
					</div>
				</div>
			</div>	
			
				<div class="row">
					<div class="col-lg-12">
					<div class="table-responsive">
                        <table frame="box" class="table table-hover table-striped custom-table-header" >
                            <thead>
					
                                <tr>
									<th width="4%" align="left"><?php echo form_checkbox($data);?></th>
									<th width="4%" align="left"></th>
									<th width="38%" align="left">To</th>
									<th width="34%" align="left">Subject</th>
									<th width="20%" align="left">Date Sent</th>
                                </tr>
                            </thead>					
							<tbody>		
<?php
					
						//check messages array for messages to display			
						if(!empty($sent_messages)){
							
							//obtain each row of message
							foreach ($sent_messages as $message){			

								//check if message has been read
								$textWeight = 'msgRead';

								//message replied
								$replied = '<i class="fa fa-reply" aria-hidden="true"></i>';

								//set the message checkbox properties
								$data_checkbox = array(
									'name' => 'cb[]',
									'id'   => 'cb',
									'value' => $message->id,
									'checked' => false,	
									'style' => 'margin:10px',
								);
								
?>		

						<tr>
							<td width="4%" align="left"><?php echo form_checkbox($data_checkbox) ; ?></td>
							<td width="4%" align="left"><?php echo $replied; ?></td>
							<td width="38%" align="left"><?php echo $message->receiver_name; ?></td>
							<td width="34%" align="left"><span class="subjectToggle" style="padding:3px;">
										<a class="<?php echo $textWeight; ?>" id="subj_line_<?php echo $message->id; ?>" onclick="markAsRead(<?php echo $message->id; ?>); " style="cursor:pointer; "><?php echo stripslashes($message->message_subject); ?></a>
										</span>		
										<div class="hiddenMessage"><br/><?php echo 
											stripslashes(wordwrap(nl2br($message->message_details), 54, "\n", true)); ?>
											<br/><br/><br/>
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
									<span class="sr-only"></span> No sent messages!</div>
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
			
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
					
					<input type="submit" class="btn btn-primary" name="deleteSnt" value="OK">
				  </div>
				</div>
			  </div>
			</div>					
<?php 
					
				//	close the message form
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
