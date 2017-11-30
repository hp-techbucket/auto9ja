
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
										<span class="small pull-left">
											Lot
										</span> 
										</th>
										<th>
										
										</th>
										<th>
										<span class="small pull-right">
											Current Price
										</span>
										</th>
										
									</tr>
								</thead>
								<tbody>
								<?php
									if($watchlist_array){				
										foreach($watchlist_array as $watchlist){
											
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
											<tr>
												<td>
													<?php echo $vehicle_thumbnail ; ?>
												</td>
												<td>
													<span class="ellipsis">
													<?php echo $link ; ?>
													</span><br/>
													<?php echo $lot_number; ?>
												</td>
												<td>
													<h4><?php echo $price; ?></h4>
													<button type="button" class="btn btn-sm btn-info">BUY NOW</button>
												</td>
												
											</tr>			
								<?php
										}
									}else {
								  ?>	
              
										  <tr id="no-message-notif">
											<td colspan="3" align="center"><div class="alert alert-danger" role="alert">
											  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
											  <span class="sr-only"></span> No items on your watchlist!</div>
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

