
						<div class="row">
							<div class="col-md-12">
							<?php echo $activation ;?>
							</div>
						</div>
						
						
						<div class="row">
							<!-- center left-->	
							<div class="col-md-6">
							  
							  <div class="panel panel-default">
								  <div class="panel-heading"><h4>Private Messages <span class="badge pull-right"><?php echo $messages_unread ;?></span></h4></div>
								  <div class="panel-body">
									
									<?php
									//check messages array for messages to display			
									if(!empty($header_messages_array)){			
										//obtain each row of message
										foreach ($header_messages_array as $message){
										
											$thumbnail = '';
								
											//obtain senders thumbnail from the db using sender email
											$query = $this->db->get_where('traders', array('email_address' => $message->sender_email));
											$query2 = $this->db->get_where('admin_users', array('admin_username' => $message->sender_email));
											
											if($query){
												
												foreach ($query->result() as $row)
												{
													$filename = FCPATH.'uploads/users/s/'.$row->id.'/'.$row->profile_photo;
													if($row->profile_photo == '' || !file_exists($filename)){
														$thumbnail = '<img class="media-object" src="'.base_url().'assets/images/icons/avatar.jpg" width="15" height="15" alt="">';
													}else{
														$thumbnail = '<img class="media-object" src="'.base_url().'uploads/users/s/'.$row->id.'/'.$row->profile_photo.'" width="15" height="15" alt="">';
													}	
												}							
											}
											if($query2){
												
												foreach ($query2->result() as $row)
												{
													$filename = FCPATH.'uploads/admins/'.$row->id.'/'.$row->profile_photo;
													if($row->profile_photo == '' || !file_exists($filename)){
														$thumbnail = '<img class="media-object" src="'.base_url().'assets/images/icons/avatar.jpg" width="15" height="15" alt="">';
													}else{
														$thumbnail = '<img class="media-object" src="'.base_url().'uploads/admins/'.$row->id.'/'.$row->profile_photo.'" width="15" height="15" alt="">';
													}	
												}							
											}
								?>
								  
									<a data-toggle="modal" data-target="#myModal" class="detail-message" id="<?php echo $message->message_id;?>">
										<div class="media">
											<span class="pull-left">
												<?php echo $thumbnail;?>
											</span>
											<div class="media-body">
												<h5 class="media-heading"><strong><?php echo $message->sender_name;?></strong>
												</h5>
												<p class="small text-muted"><i class="fa fa-clock-o"></i> <?php echo date("F j, Y", strtotime($message->date_sent));?></p>
												<p class="ellipsis"><?php echo $message->message_details;?></p>
											</div>
										</div>
									
									</a>
									<hr>
									
									<?php
										}	
										echo '<span class="pull-left"><a href="javascript:void(0)" onclick="location.href=\''.base_url('account/messages/').'\'" title="Show all messages"><i class="fa fa-search" aria-hidden="true"></i> Show all messages</a></span>';	
									}else{
										//	close the message form
									?>
										<div class="media">
											<p class="small text-muted">You have no new messages.</p>
										</div>
									<?php
									}
									?>
								  </div>
								</div>
							  <hr>
						      
							  <!--Payments Summary-->
							  <div class="panel panel-default">
								  <div class="panel-heading">
								  <h4>Payments</h4>
								  </div>
								  <div class="panel-body">
									<table class="table table-striped">
									  <thead>
										<tr>
											<th>Ref</th>
											<th>Description</th>
											<th>Date</th>
										</tr>
									  </thead>
									  <tbody>
									<?php
									//check messages array for messages to display			
									if(!empty($pending_payments_array)){			
										//obtain each row of message
										foreach ($pending_payments_array as $payment){	
									?>
									<tr>
										<td><?php echo $payment->reference_id ; ?></td>
										<td><?php echo $payment->description ; ?></td>
										<td><p class="small text-muted"><i class="fa fa-clock-o"></i><?php echo date('F j, Y', strtotime($payment->invoice_date)) ; ?></p></td>
									</tr>
								  
									<?php
										}	
										
										echo '<span class="pull-left"><a href="javascript:void(0)" onclick="location.href=\''.base_url('account/payments/').'\'" title="Show all payments"><i class="fa fa-search" aria-hidden="true"></i> Show all payments</a></span>';
										
									}else{
										//	close the message form
									?>
										<tr>
											<td colspan="3" align="center">
											<span class="small text-muted">You do not have any current payment due.</span>
											</td>
											
										</tr>
									<?php
									}
									?>
										</tbody>
									</table>
									
								  </div>
								</div>
								<!--End Payments Summary-->
								
								<!--Watchlist Summary-->
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4>Watchlists (<?php echo $watchlist_count; ?>)</h4>
									</div>
									<div class="panel-body">
									
										<span class="small text-muted pull-left">
											Lot
										</span> 
										<span class="small text-muted pull-right">
											Current Price
										</span>
										<hr>
									<?php
									//check messages array for messages to display			
									if(!empty($watchlist_array)){			
										//obtain each row of message
										foreach ($watchlist_array as $watchlist){	
										
										$vehicle_thumbnail = '';
										$title = '';
										$lot_number = '';
										$price = '';
										
											//obtain vehicles thumbnail from the db using vehicle id
											$query3 = $this->db->get_where('vehicles', array('id' => $watchlist->vehicle_id));
											
											if($query3){
												
												foreach ($query3->result() as $row)
												{
													$title = $row->year_of_manufacture.' '.$row->vehicle_make.' '.$row->vehicle_model;
													
													$link = '<a title="'.$title.'" href="javascript:void(0)" onclick="location.href='.base_url('vehicle-finder/').''.$vehicle->id.'">'.$title.'</a>';
													
													$lot_number = $row->vehicle_lot_number;
													$price = $row->vehicle_price;
										
													$filename = FCPATH.'uploads/vehicles/'.$row->id.'/'.$row->image_1;
													if($row->image_1 == '' || !file_exists($filename)){
														$vehicle_thumbnail = '<img class="media-object" src="'.base_url().'assets/images/icons/NoImage_small.png" width="15" height="15" alt="">';
													}else{
														$vehicle_thumbnail = '<img class="media-object" src="'.base_url().'uploads/vehicles/'.$row->id.'/'.$row->image_1.'" width="15" height="15" alt="">';
													}	
												}							
											}
									?>
									<div class="row">
									
										<div class="col-md-2">
										<?php echo $vehicle_thumbnail ; ?>
										</div>
										<div class="col-md-8">
											<span class="ellipsis">
											<?php echo $link ; ?>
											</span><br/>
											<?php echo $lot_number; ?>
										</div>
										<div class="col-md-2">
											<h4><?php echo $price; ?></h4>
											<button type="button" class="btn btn-sm btn-info">BUY NOW</button>
										</div>
										
									</div>
									
									<hr class="dotted-bottom-border">
									
									<?php
										}	
										
										echo '<span class="pull-left"><a href="javascript:void(0)" onclick="location.href=\''.base_url('vehicles/watchlist/').'\'" title="Show all watchlist"><i class="fa fa-search" aria-hidden="true"></i> Show all watchlist</a></span>';
										
									}else{
										//	close the message form
									?>
										<div class="row">
											<div class="col-md-12" align="center">
											<p class="small text-muted">You do not have any items on your watchlist.</p>
											</div>
										</div>
									<?php
									}
									?>
										
								  </div>
								</div>
								<!--End Watchlist Summary-->
							
							  
							  <hr>
							 
				   
							</div><!--/col-->
							<div class="col-md-6">
							
								<!--Shipping Status-->
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4>Shipping Status (<?php echo $shipping_count; ?>)</h4>
									</div>
									<div class="panel-body">
									
									<?php
									//check messages array for messages to display			
									if(!empty($shipping_array)){			
										//obtain each row of message
										foreach ($shipping_array as $shipping){	
		
											$vehicle_name = '';
											
											$shipping_link = '<a title="'.$shipping->shipping_details.'" href="javascript:void(0)" onclick="location.href='.base_url('vehicles/shipping-details/').''.$shipping->id.'">'.$shipping->shipping_details.'</a>';
													
											//obtain vehicles details from the db using vehicle id
											//$vehicle_name = $row->year_of_manufacture.' '.$row->vehicle_make.' '.$row->vehicle_model;
									?>
									<div class="row">
										<div class="col-md-12">
											<strong><?php echo $shipping->vehicle_name ; ?></strong>
											<span class="ellipsis">
											<?php echo $shipping_link ; ?>
											</span><br/>
											
										</div>
									</div>
									
									<hr class="dotted-bottom-border">
									
									
									<?php
										}	
										
										echo '<span class="pull-left"><a href="javascript:void(0)" onclick="location.href=\''.base_url('vehicles/shipping/').'\'" title="Show all shipping"><i class="fa fa-search" aria-hidden="true"></i> Show all shipping</a></span>';
										
									}else{
										//	close the message form
									?>
										<div class="row">
											<div class="col-md-12" align="center">
											<p class="small text-muted">You do not have any shipped vehicles.</p>
											</div>
										</div>
									<?php
									}
									?>
										
								  </div>
								</div>
								<!--End Shipping Status-->
								
								<!--Profile Status-->
									<div class="container-fluid text-info">
										<?php echo $profile_completion; ?>	
									</div>
								<!--Profile Status-->
								
								<div class="panel panel-default">
								  <div class="panel-heading"><div class="panel-title"><h4>Engagement</h4></div></div>
								  <div class="panel-body">	
									<div class="col-xs-4 text-center"><img src="http://placehold.it/80/BBBBBB/FFF" class="img-circle img-responsive"></div>
									<div class="col-xs-4 text-center"><img src="http://placehold.it/80/EFEFEF/555" class="img-circle img-responsive"></div>
									<div class="col-xs-4 text-center"><img src="http://placehold.it/80/EEEEEE/222" class="img-circle img-responsive"></div>
								  </div>
							   </div><!--/panel-->
							  
							</div><!--/col-span-6-->
					 
					  </div><!--/row-->
					  
					
					</div><!--/col-span-9-->
				</div><!--.row-->
		</div><!--.custom-container-->
    </div><!--.container-->



	  
	