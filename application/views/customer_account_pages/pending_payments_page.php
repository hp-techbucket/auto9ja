
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
											  <span class="sr-only"></span> No pending payments!</div>
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

<?php   
		}
	}								
?>

					</div><!--/col-span-9-->
				</div><!--.row-->
		</div><!--.custom-container-->
    </div><!--.container-->

