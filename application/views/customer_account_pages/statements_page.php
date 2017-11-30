
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
										<th >Ref</th>
										<th >Amount</th>
										<th >Description</th>
										<th >Date</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if($statements_array){				
										foreach($statements_array as $statement){								
								?>							
											<tr>
												<td><?php echo $statement->reference; ?></td>
												<td><?php echo $statement->amount ; ?></td>
												<td><?php echo $statement->note ; ?> (<?php echo $statement->transaction ; ?>)</td>
												<td><?php echo date("F j, Y, H:i s", strtotime($statement->date)); ?></td>
											</tr>			
								<?php
										}
									}else {
								  ?>	
              
										  <tr id="no-message-notif">
											<td colspan="4" align="center"><div class="alert alert-danger" role="alert">
											  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
											  <span class="sr-only"></span> No transactions yet!</div>
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
