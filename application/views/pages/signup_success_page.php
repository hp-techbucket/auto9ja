
		<section class="container" align="center">

				<div class="social-card social-card-container">
					
					<div class="panel panel-primary">
					  <div class="panel-heading text-center">
					  Register and enjoy the benefits of Auto9ja.
					  </div>
					</div>	
					
					
					<div class="row">
			
						<div class="col-md-7">
						
							
							<div class="row">
								
								<nav>
									<ol class="cd-multi-steps text-center">
										<li class="visited"><a href="javascript:void(0)">Registration</a></li>
										<li class="current"><a href="javascript:void(0)"><em>Confirmation</em></a></li>
										<li><a href="javascript:void(0)">Ready to bid</a></li>
									</ol>
								</nav>
							</div>
							 <br/> 
							 
							<div class="alert alert-success" align="center">
								<h3>Signup Success!</h3>
								<p>Thank you for signing up. Please check your email and enter the activation code below to activate your account. This expires in 24 hours.</p>
							</div>
										
							 <p>Please enter your activation code below:</p>
						
							<?php	
								$activation_error = '';
								if(form_error('activation_code')){
									$fnError = 'input-error';
								}
								echo form_open('activation/validation');
									
							?>	
							<div class="row">
								<div class="col-lg-8 col-lg-offset-2">
									
									<div class="form-group">
										
										<input title="Please enter your 6-digit activation code" type="text" name="activation_code" class="<?php echo $activation_error;?> form-control"  pattern="^\d{6}$" value="<?php echo set_value('activation_code');?>" placeholder="Activation Code" >
									</div>
									<br/>
									<?php echo form_error('activation_code');?>
									
									<p><input type="submit" name="activation_button" class="btn btn-primary btn-block" value="Activate!"></p>
								</div>
							</div>	
				<?php	
					echo form_close();
				?>
							
							
						</div>
						
						
						
						<div class="col-md-5">
							
							<div class="well well-lg">
								<h3 class="text-center">
									<i class="fa fa-lock" aria-hidden="true"></i>
									Your account is secure
								</h3>
								<p>We use maximum encryption to ensure the safety and security of your personal information.</p>
								<div class="text-center">
								<a href="javascript:void(0)"><img src="<?php echo base_url('assets/images/logo/23.gif');?>" class="grayscale"></a>
								<a href="javascript:void(0)"><img src="<?php echo base_url('assets/images/logo/logos_verisign.gif');?>" class="grayscale"></a>
								</div>
							</div>
						
						</div>
					</div>	
			
					<?php echo br(1); ?>
				
				</div><!-- .social-card-->
				
		</section></div><!-- .container-->
			
       </div><!-- .custom-container -->
	   
	</div><!-- .container-->