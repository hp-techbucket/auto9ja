 <div class="container">
		
	<div class="custom-container">
			
		<div class="container-fluid filter-container">
			<div class="container-fluid padded-container">
				<div class="row">
					<div class="col-lg-7 col-sm-12 col-xs-12">
						<form action="<?php echo base_url('vehicles/search');?>" method="get">
							<div class="filter-box effect3">
								<div class="row">
									<div class="col-lg-4 col-sm-6 col-xs-12">
										<label for="vehicle_type" class="custom-label">Vehicle type</label>
										<div class="form-group" >
											<?php echo $vehicle_type; ?>
										</div>
									</div>
									<div class="col-lg-4 col-sm-6 col-xs-12">
										<label for="vehicle_make" class="custom-label">Make</label>
										<div class="form-group" >
											<?php echo $vehicle_make; ?>
										</div>
									</div>
									
								</div>
								<div class="row">
									<div class="col-lg-4 col-sm-6 col-xs-12">
										<label for="vehicle_model" class="custom-label">Model</label>
										<div class="form-group" >
											<select name="vehicle_model" class="form-control custom-select" id="vehicle_model">
												<option value="0">All Models</option>
											</select>
										</div>
									</div>
									<div class="col-lg-2 col-sm-4 col-xs-6">
										
										<label for="year_from" class="custom-label">Year from</label>
										<div class="form-group" >
											<select name="year_from" class="form-control custom-select" id="year_from">
												<?php
												for($i=date("Y")-50;$i<=date("Y");$i++) {
													$sel = ($i == date('Y') - 5) ? 'selected' : '';
													echo "<option value=".$i." ".$sel.">".$i."</option>";  // here I have changed      
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-2 col-sm-4 col-xs-6">
										<label for="year_to" class="custom-label">Year to</label>
										<div class="form-group" >
											<select name="year_to" class="form-control custom-select" id="year_to">
												<?php
												for($i=date("Y")-50;$i<=date("Y");$i++) {
													$sel = ($i == date('Y')) ? 'selected' : '';
													echo "<option value=".$i." ".$sel.">".$i."&nbsp;&nbsp;&nbsp;&nbsp;</option>";  // here I have changed      
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-2 col-sm-4 col-xs-6">
										
										<div class="search-button" >
											<button type="submit" class="btn btn-default btn-custom"><i class="fa fa-search"></i> SEARCH</button>
										</div>
										<?php 
											echo form_close();					
										?>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-lg-5 col-sm-12 col-xs-12">
						<h2>BUY & SELL VEHICLES</h2>
						<p class="small">It's EASY to buy vehicles from our store. Bid live on over 119,881 cars, trucks, SUVs, boats, motorcycles available now. With this many choices you are just <p>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container-fluid">	
			<div class="row">
				<div class="col-md-3">
					<div class="container-fluid">
						<div>
							<h3>Quick Picks
								<span class="pull-right">
									<button type="button" class="side-menu-button" aria-expanded="false">
										<span class="sr-only">Toggle navigation</span>
										<i class="fa fa-link" aria-hidden="true"></i>
									</button>
								</span>
							</h3>
							
						</div>
						
						
						
							
							<ul id="side-menu">
									<?php
										if(!empty($vehicle_types))
										{
											foreach($vehicle_types as $type) 
											{
												$count_type = $this->Vehicle_types->count_vehicle_types(strtolower($type->name));
												if($count_type == '' || $count_type == null){
													$count_type = 0;
												}
												//url_title()
									?>
									<li>
										<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>vehicles/<?php echo strtolower($type->name);?>/'">
											<i class="fa fa-caret-right" aria-hidden="true"></i>
											<?php echo $type->name;?>
										</a>
										<span class="vehicle-count">(<?php echo strtolower($count_type);?>)</span>
									</li>
									<?php
											}
										}
									?>
							</ul>
							
					</div>
				</div>
				<div class="col-md-9">
				
				<div class="container-fluid">
					<h3>Most <span>Popular Now</span></h3>
			
			
					<!-- Carousel Slider
					================================================== -->
					<div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 820px; height: 300px; overflow: hidden;  visibility: hidden;">
							<!-- Loading Screen -->
							<div id="slider-loading" data-u="loading" style="position: absolute; top: 0px; left: 0px;">
								<div id="slider-loading-1" style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
								<div id="slider-loading-2" style="position:absolute;display:block;background:url('img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
							</div>
							<div class="u-slides pull-right" data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 800px; height: 300px; overflow: hidden;">
								<div class="slide-images" data-p="112.50">
									<img class="" src="<?php echo base_url('assets/images/pix/35451744s.jpg');?>" alt="Sixth slide">
									
									<div class="u-caption" align="center">
										<h1>What you waiting for?</h1>
										<p>
											<a class="btn btn-lg btn-primary" title="Find a vehicle" href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>vehicles/'" role="button"><i class="fa fa-zoom" aria-hidden="true"></i> Find a vehicle</a>
										</p>			
									</div>
								</div>
								<div class="slide-images" data-p="112.50" style="display:none;">
									<img class="" src="<?php echo base_url('assets/images/pix/27144124s.jpg');?>" alt="First slide">
								</div>
								<div class="slide-images" data-p="112.50" style="display:none;">
									<img class="" src="<?php echo base_url('assets/images/pix/30133214s.jpg');?>" alt="Second slide">
								</div>
								<div class="slide-images" data-p="112.50" style="display:none;">
									<img class="" src="<?php echo base_url('assets/images/pix/29884574s.jpg');?>" alt="Third slide">
								</div>
								<div class="slide-images" data-p="112.50" style="display:none;">
									<img class="" src="<?php echo base_url('assets/images/pix/33427854s.jpg');?>" alt="Fourth slide">
								</div>
								<div class="slide-images" data-p="112.50" style="display:none;">
									<img class="" src="<?php echo base_url('assets/images/pix/34306354s.jpg');?>" alt="Fifth slide">
								</div>
								<div class="slide-images" data-p="112.50" style="display:none;">
									<img class="" src="<?php echo base_url('assets/images/pix/35451744s.jpg');?>" alt="Sixth slide">
								</div>
								<div class="slide-images" data-p="112.50" style="display:none;">
									<img class="" src="<?php echo base_url('assets/images/pix/34306354s.jpg');?>" alt="Fifth slide">
								</div>
							</div>
							<!-- Bullet Navigator -->
							<div data-u="navigator" class="jssorb01" style="bottom:16px;right:16px;" data-autocenter="1">
								<div data-u="prototype" style="width:12px;height:12px;"></div>
							</div>
							<!-- Arrow Navigator -->
							<span data-u="arrowleft" class="jssora13l" style="top:0px;left:30px;width:40px;height:50px;color:#ffffff;" data-autocenter="2"><i class="fa fa-chevron-left fa-2x" aria-hidden="true"></i></span>
							<span data-u="arrowright" class="jssora13r" style="top:0px;right:30px;width:40px;height:50px;color:#ffffff;" data-autocenter="2"><i class="fa fa-chevron-right fa-2x" aria-hidden="true"></i></span>
					</div>
					</div>
				</div>
				<!-- /.col -->
			
				
			</div>
			<!-- /.row -->
			
			
		</div>
	</div>
</div>
 