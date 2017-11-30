

	  
			<div class="social-card social-card-container">
				
				<div class="panel panel-primary">
				  <div class="panel-heading text-center">
				  Login to enjoy the benefits of Auto9ja.
				  </div>
				</div>	
				
				<div class="row">
				
					<div class="col-md-7">
						
						<div class="well well-lg">
							
							<p class="text-center">If you are not registered, you can <strong><a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>register/'" title="Sign Up">Sign Up</a></strong> for free or login using your registered email below:<p>
							
							<br/><br/>
							
							<?php	
									$emailError = '';
									$passError = '';
									
									echo form_open('login/validation');
									if(form_error('email_address')){
										$emailError = 'input-error';
									}
									if(form_error('password')){
										$passError = 'input-error';
									}
				
							?>	
							
							<p>
								<?php echo form_error('email_address');?>
								<?php echo form_error('password');?>
							</p>
							
							<div class="row">
								<div class="col-lg-8 col-lg-offset-2">
									
									<div class="form-group">
										
										<input title="Please your registered email address" type="text" name="email_address" class="<?php echo $emailError;?> form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"value="<?php echo set_value('email_address');?>" placeholder="Email Address" >
									</div>
									<br/>
									
									
									<div class="form-group">
									
										<input title="Please enter your account password" type="password" name="password" id="upass" class="<?php echo $passError;?> form-control" value="<?php echo set_value('password');?>" placeholder="Password" >
									</div>
									<br/>
									
									<p align="left">
										<input title="Show or hide password" id="toggleBtn" type="checkbox" onclick="togglePassword()"> Show Password
									</p>
									<p>
										<button type="submit" class="btn btn-primary btn-block" >Log in</button>
									</p>	
								</div>
							</div>		
				
							<?php	
									
									echo form_close();
							?>			
							
							<p  class="col-lg-8 col-lg-offset-2" style="text-align:right"><strong><a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>password/reset/'">Forgot Password?</a></strong><p>
						
						</div>
					
					</div>
					
					
					
					<div class="col-md-5">
					
						<div class="well well-lg">
						
							<h4 class="dotted-bottom-border text-center">You can login using your favorite social service</h4>
							
							
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
			
       </div><!-- .custom-container -->
	   
	</div><!-- .container-->