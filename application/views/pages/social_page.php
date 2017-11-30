
<body class="custom-wrapper">

<section class="container" align="center">
		
		
		<div class="social-card social-card-container">
				
				
				<div class="logo-container" align="center">
					<a href="<?php echo base_url();?>" title="Auto9ja"><img src="<?php echo base_url();?>assets/images/logo/logo2.png" class="img-responsive" alt="Logo" width="120" height="120"></a>
				</div>
				
				
					<div class="row">
						<div class="col-lg-12">
							<a title="Login using your Facebook account" href="javascript:void(0)" onclick="location.href='<?php echo $fblogin ; ?>'" class="btn btn-primary btn-block"><i class="fa fa-fw fa-facebook"></i> Facebook</a>
						</div>
					</div>
					
					<br/>
					
					<div class="row">
						<div class="col-lg-12">
							<a title="Login using your Google account"  href="javascript:void(0)" onclick="location.href='<?php echo $googlelogin ; ?>'" class="btn btn-danger btn-block"><i class="fa fa-fw fa-google"></i> Google</a>
						</div>
					</div>
					
					<br/>
					
					<div class="row">
						<div class="col-lg-12">
							<p class="text-center"><strong>or</strong></p>
							<p class="text-center"><strong>Log in or sign up with email</strong></p>
						</div>
					</div>
					
					<br/>
					
					<div class="row">
						<div class="col-lg-6">
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>login/'" class="btn btn-default btn-block" title="Log in to your account" ><i class="fa fa-fw fa-sign-in"></i> Log in</a>
						</div>
						<div class="col-lg-6">
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>signup/'"  title="Create an account" class="btn btn-default btn-block"><i class="fa fa-fw fa-user"></i> Sign up</a>
						</div>
					</div>
					
					<br/>
					
					<div class="row">
						<div class="col-lg-12">
						<p>By signing up you agree to our 
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>home/terms_of_use/'" title="Terms of Use">Terms of Use</a> 
				and <a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>home/privacy_policy/'" title="Privacy Policy">Privacy Policy</a>
				</p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
						
						</div>
					</div>
				
				
				
				<?php echo br(2); ?>
				
		</div>
		
</section>
