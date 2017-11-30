
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>
			<div class="container-fluid">
       
                <div class="row">
                    <div class="col-lg-12" align="center">

						<div class="table-responsive" >
							<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th>Invoice</th>
										<th>Description</th>
										<th>Amount</th>
										<th>Date</th>
										
									</tr>
								</thead>
								<tbody>
								<?php
									if($my_payments_array){				
										foreach($my_payments_array as $payment){										
								?>							
											<tr>
												<td>
													<?php echo $payment->invoice_ref; ?>
												</td>
												<td>
													<?php echo $payment->description; ?>
												</td>
												<td>
													<?php echo $payment->amount; ?>
												</td>
												<td>
													<?php echo $payment->invoice_date; ?>
												</td>
											</tr>			
								<?php
										}
									}else {
								  ?>	
              
										  <tr id="no-message-notif">
											<td colspan="4" align="center"><div class="alert alert-danger" role="alert">
											  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
											  <span class="sr-only"></span> No payment history!</div>
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
                <!-- /.row -->
			
				<div class="row">
					<div class="col-md-12 text-center">
						<?php echo $pagination; ?>
					</div>
				</div>


			</div>
            <!-- /.container-fluid -->
			
			  <!--tabs-->
              <div class="container">
                <div class="col-md-4">
                <ul class="nav nav-tabs" id="myTab">
                  <li class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
                  <li><a href="#messages" data-toggle="tab">Messages</a></li>
                  <li><a href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
                
                <div class="tab-content">
                  <div class="tab-pane active" id="profile">
                    <h4><i class="glyphicon glyphicon-user"></i></h4>
                    Lorem profile dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
                    <p>Quisque mauris augue, molestie tincidunt condimentum vitae, gravida a libero. Aenean sit amet felis 
                      dolor, in sagittis nisi.</p>
                  </div>
                  <div class="tab-pane" id="messages">
                    <h4><i class="glyphicon glyphicon-comment"></i></h4>
                    Message ipsum dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
                    <p>Quisque mauris augu.</p>
                  </div>
                  <div class="tab-pane" id="settings">
                    <h4><i class="glyphicon glyphicon-cog"></i></h4>
                    Lorem settings dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
                    <p>Quisque mauris augue, molestie.</p>
                  </div>
                </div>
              	</div>
              </div>  
               
              <!--/tabs-->
              
<?php   
		}
	}								
?>

					</div><!--/col-span-9-->
				</div><!--.row-->
		</div><!--.custom-container-->
    </div><!--.container-->

