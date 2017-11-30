
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
										<th>
											Date
										</th>
										<th>
										Description
										</th>
										
									</tr>
								</thead>
								<tbody>
								<?php
									if($shipping_array){				
										foreach($shipping_array as $shipping){
											$shipping_link = '<a title="'.$shipping->shipping_details.'" href="javascript:void(0)" onclick="location.href='.base_url('vehicles/shipping-details/').''.$shipping->id.'">'.$shipping->shipping_details.'</a>';
													
								?>							
											<tr>
												<td>
													<?php echo date("F j, Y", strtotime($shipping->date)); ?>
												</td>
												<td>
													
													<strong><?php echo $shipping->vehicle_name ; ?></strong>
													<span class="ellipsis">
													<?php echo $shipping_link ; ?>
													</span>
												</td>
												
											</tr>			
								<?php
										}
									}else {
								  ?>	
              
										  <tr id="no-message-notif">
											<td colspan="3" align="center"><div class="alert alert-danger" role="alert">
											  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
											  <span class="sr-only"></span> You do not have any shipped vehicles!</div>
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
