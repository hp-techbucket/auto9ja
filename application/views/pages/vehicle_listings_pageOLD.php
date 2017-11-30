<div class="container-fluid">
	<div class="container">
		<!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <br/> 
                <ol class="breadcrumb">
                    <li>
                        <a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>'"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                    </li>						
                    <li class="active">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $pageTitle;?>
                    </li>											
                </ol>
				<hr/>
            </div>
        </div>
        <!-- /.row -->
	</div>			
	<div class="container-fluid">	
		<div class="row">
			<div class="col-lg-12">
				<h1>Salvage Cars for Auctions</h1>			
			</div>
		</div>
		
		<br/><br/> 
		<div class="row">
			<div class="col-md-3">
				
				<ul id="side-menu">
					<li>
						<div><i class="fa fa-search" aria-hidden="true"></i> SEARCH FORM <span class="pull-right"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i> </span></div>
						<div></div>
					</li>
					<li>
						<div><i class="fa fa-filter" aria-hidden="true"></i> FILTERS <span class="pull-right"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i> </span></div>
						<div></div>
					</li>
					<li>
						<div>Year Range <span class="pull-right"><i class="fa fa-caret-right" aria-hidden="true"></i> </span></div>
						<div></div>
					</li>
					<li>
						<div>Location <span class="pull-right"><i class="fa fa-caret-right" aria-hidden="true"></i> </span></div>
						<div></div>
					</li>

				</ul>
			</div>
			<div class="col-md-9">
				
						<div class="table-responsive" align="center">
							<table id="vehicles-table" class="table table-hover table-striped" cellspacing="0" width="100%">
								<thead>
									<tr>
										
										<th>Description</th>
										<th>Location</th>
										<th>Shipping</th>
										<th>Price</th>
										
									</tr>
								</thead>
								<tbody>
	<?php
							if($vehicles_array){
								
								foreach($vehicles_array as $vehicle){
									
									$photo = '';
									$thumbnail = '';
									
									//path to user photo folder				
									$photoPath = FCPATH.'uploads/vehicles/'.$vehicle->id.'/'.$vehicle->image_1;

									//check for record of user photo in db
									if($vehicle->image_1 == '' || $vehicle->image_1 == null || !file_exists($photoPath)){
										//no record in db, diplay default avatar
										$photo = '<img src="'.base_url().'assets/images/icons/NoImage_small.png" class="img-rounded" width="80" height="80" />';
										$thumbnail = '<a href="javascript:void(0)" onclick="location.href='.base_url('vehicle-finder/').''.$vehicle->id.'">'.$photo.'</a>';
									}else{
										$photo = '<img src="'.base_url('uploads/vehicles/').''.$vehicle->id.'/'.$vehicle->image_1.'" class="img-rounded" width="80" height="80" />';
										$thumbnail = '<a href="javascript:void(0)" onclick="location.href='.base_url('vehicle-finder/').''.$vehicle->id.'">'.$photo.'</a>';
									}	
									$photo = '';
									$filename = FCPATH.'uploads/vehicles/'.$vehicle->id.'/'.$vehicle->vehicle_image;
											
									if($vehicle->vehicle_image == '' || $vehicle->vehicle_image == null || !file_exists($filename)){
						
										$result = $this->db->select('*, MIN(id) as min_id', false)->from('vehicle_images')->where('vehicle_id', $vehicle->id)->get()->row();
											
										if(!empty($result)){
													
											$photo = '<img src="'.base_url().'uploads/vehicles/'.$result->vehicle_id.'/'.$result->image_name.'" class="img-responsive"/>';
													
										}else{
											$photo = '<img src="'.base_url().'assets/images/img/no-default-thumbnail.png" class="img-responsive"/>';
										}
												
									}
									else{
										$photo = '<img src="'.base_url().'uploads/vehicles/'.$vehicle->id.'/'.$vehicle->vehicle_image.'" class="img-responsive "/>';
									}	
									
									$thumbnail = '<a href="javascript:void(0)" onclick="location.href='.base_url('vehicles/view_vehicle').''.$vehicle->id.'">'.$photo.'</a>';
									
									$title = $vehicle->year_of_manufacture.' '.$vehicle->vehicle_make.' '.$vehicle->vehicle_model;
									$title = strtoupper($title);
									//$url_title = url_title(strtolower($title))
									$link = '<a title="'.$title.'" href="javascript:void(0)" onclick="location.href='.base_url('vehicle-finder/').''.$vehicle->id.'">'.$title.'</a>';
	?>								
	
									<tr>
										<td>
											<div class="container">
												<div class="row">
													<div class="col-xs-4">
														<?php echo $thumbnail; ?>
													</div>
													<div class="col-xs-8">
														<span class="ellipsis"><?php echo $link?></span><br/>
														<?php echo $vehicle->vehicle_vin ;?><br/>
														<?php echo $vehicle->vehicle_odometer;?><br/>
														
													</div>
												</div>
											</div>
										</td>
										<td>
											<div class="vehicle-location">
												<?php echo strtoupper($vehicle->vehicle_location);?><br/>
											</div>
										</td>
										<td>
											SHIPPING
										</td>
										<td>
											<div class="vehicle_price">
												N<?php echo strtoupper($vehicle->vehicle_price);?><br/>
												<a class="btn btn-warning btn-xs">
													<i class="fa fa-shopping-cart" aria-hidden="true"></i>
													BUY NOW
												</a>
											</div>
										</td>
										
									</tr>						
	<?php
								}
							}else {
?>
									<tr>
										<td colspan="4" align="center">
											<div class="alert alert-danger" role="alert">
												<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
												<span class="sr-only"></span> No record! 
											</div>
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
	
	<?php echo br(5); ?>			
    </div>


	  
	