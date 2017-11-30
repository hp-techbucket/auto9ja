
  <body class="custom-wrapper">

	<section class="container">
       
		<div class="social-card social-card-container" >
		
				<div class="logo-container" align="center">
					<a href="<?php echo base_url();?>" title="Auto9ja"><img src="<?php echo base_url();?>assets/images/logo/logo2.png" class="img-responsive" alt="Logo" width="120" height="120"></a>
				</div>
				
				 <p align="center">Signup with the form below or <strong><a href="javascript:void(0)" onclick="location.href='<?php echo base_url()."login" ;?>'">Log in, if you are already registered</a></strong><p>
				 <br/>
			
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
						
					echo form_open('signup/validation');
						
				?>	
				<div class="row">
					<div class="col-xs-6">
						
						<div class="form-group">
							<input title="Please enter your first name" type="text" name="first_name" id="" class="<?php echo $fnError;?> form-control" value="<?php echo set_value('first_name');?>" placeholder="First Name" >
						</div>
						<br/>
						<?php echo form_error('first_name');?>
					</div>
					<div class="col-xs-6">
						
						<div class="form-group">
							<input title="Please enter your last name" type="text" name="last_name" id="" class="<?php echo $lnError;?> form-control" value="<?php echo set_value('last_name');?>" placeholder="Surname" >
						</div>
						<br/>
						<?php echo form_error('last_name');?>
					</div>
				</div>
				
					
		
				<div class="row">
					<div class="col-xs-12">
						
						<div class="form-group">
							<input title="Please enter a valid email address" type="text" name="email_address" class="<?php echo $emailError;?> form-control" value="<?php echo set_value('email_address');?>" placeholder="Email Address" >
						</div>
						<br/>
						<?php echo form_error('email_address');?>
					</div>
				</div>			

				<div class="row">
					<div class="col-xs-6">
						
						<div class="form-group">
							<input title="Please enter a strong 8-character password made up of at least one number, one upper and lower case letters" type="password" name="password" id="upass" class="<?php echo $passError;?> form-control" value="<?php echo set_value('password');?>" placeholder="Password" >
						</div>
						<br/>
						<?php echo form_error('password');?>
					</div>
					<div class="col-xs-6">
						
						<div class="form-group">
							<input title="Please re-enter the password" type="password" name="confirm_password" class="<?php echo $cpassError;?> form-control" value="<?php echo set_value('confirm_password');?>" placeholder="Re-enter Password" >
						</div>
						<br/>
						<?php echo form_error('confirm_password');?>
					</div>
				</div>							
				
				
				<div class="row">
					<div class="col-md-12">
						<button type="submit" class="btn btn-primary btn-block">Create account</button>
					</div>
				</div>	

				
				<?php	
					echo form_close();
				?>
			<?php echo br(1); ?>
		</div>
		
	</section>
