

			<section class="container">
			   
				<div class="social-card social-card-container" >
					<div class="panel panel-primary">
					  <div class="panel-heading text-center">
					  Register and enjoy the benefits of Auto9ja.
					  </div>
					</div>	
					<div class="row">
					
						<div class="col-md-7">
						
							<p align="center"><strong>Signup with the form below or <a href="javascript:void(0)" onclick="location.href='<?php echo base_url()."login" ;?>'">Log in</a>, if you are already registered</strong><p>
							 
							 
						
						<div class="row">
							
							<nav>
								<ol class="cd-multi-steps text-center">
									<li class="current"><a href="javascript:void(0)"><em>Registration</em></a></li>
									<li><a href="javascript:void(0)">Confirmation</a></li>
									<li><a href="javascript:void(0)">Ready to bid</a></li>
								</ol>
							</nav>
						</div>
						 <br/> 
							 
							<div class="row">
								<div class="col-lg-8 col-lg-offset-2">
									<?php	
											$fnError = '';
											$lnError = '';
											$emailError = '';
											$passError = '';
											$cpassError = '';
											
											if(form_error('first_name')){
												$fnError = 'input-error';
											}
											if(form_error('last_name')){
												$lnError = 'input-error';
											}
											if(form_error('email_address')){
												$emailError = 'input-error';
											}
											
											if(form_error('password')){
												$passError = 'input-error';
											}
											if(form_error('confirm_password')){
												$cpassError = 'input-error';
											}
										$attributes = array('role' => 'form');	
										echo form_open('signup/validation',$attributes);
											
									?>	
									<p align="center">
									<?php echo validation_errors();?>
									<p>
									
									<div class="form-group">
										
										  <input type="text" name="first_name" id="" class="<?php echo $fnError;?> form-control" pattern="[A-Za-z]+" title="Please enter your first name"  value="<?php echo set_value('first_name');?>" placeholder="First Name" required>
										
											<?php echo form_error('first_name');?>
									</div>
									<div class="form-group">
										
										
										  <input title="Please enter your last name" type="text" name="last_name" id="" class="<?php echo $lnError;?> form-control" pattern="[A-Za-z]+" value="<?php echo set_value('last_name');?>" placeholder="Last Name" >
											<?php echo form_error('last_name');?>
									</div>
									
									<div class="form-group">
										
										  <input title="Please enter a valid email address" type="email" name="email_address" class="<?php echo $emailError;?> form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"  value="<?php echo set_value('email_address');?>" placeholder="Email Address" >
											<?php echo form_error('email_address');?>
									</div>
									
									
									<div class="form-group">
										
										  <input title="Please enter a strong password" type="password" name="password" id="upass" class="<?php echo $passError;?> form-control" value="<?php echo set_value('password');?>" placeholder="Password" >
										
											<?php echo form_error('password');?>
										
									</div>
									
									<div class="form-group">
										
										  <input title="Please re-enter the password" type="password" name="confirm_password" class="<?php echo $cpassError;?> form-control" value="<?php echo set_value('confirm_password');?>" placeholder="Re-enter Password" >
										  
											<?php echo form_error('confirm_password');?>
										
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-block">Create account</button>
									</div>
								
							<?php echo br(1); ?>
								</div>
							</div>	
					
							<?php	
								echo form_close();
							?>
							
						</div>
						
						
						
						<div class="col-md-5">
						
							<div class="well well-lg">
							
								<h4 class="dotted-bottom-border text-center">You can register with us by using your favorite social service</h4>
								
								
								<a title="Login using your Facebook account" href="javascript:void(0)" onclick="location.href='<?php echo $fblogin ; ?>'" class="btn btn-primary btn-block"><i class="fa fa-fw fa-facebook"></i> Facebook</a>
								
								<a title="Login using your Google account"  href="javascript:void(0)" onclick="location.href='<?php echo $googlelogin ; ?>'" class="btn btn-danger btn-block"><i class="fa fa-fw fa-google"></i> Google</a>
							</div>
							
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
	