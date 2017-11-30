<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?> 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="<?php echo $pageTitle; ?>">
    <meta name="author" content="">
	<?php echo link_tag('assets/images/icons/favicon.ico', 'shortcut icon', 'image/ico'); ?>
	<?php echo link_tag('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'); ?>

    <title>Auto9ja | <?php echo $pageTitle; ?></title>

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
			
	<?php echo link_tag('assets/css/style.css'); ?>
	<script src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
	<script type="text/javascript">var baseurl = "<?php echo base_url(); ?>";</script>
  </head>

  <body class="custom-wrapper2">

	  <section class="container">
	  
			<div class="card card-container">
			
				<h3 class="text-center" ><a href="javascript:void(0)" onclick="location.href='<?php echo base_url(); ?>'" title="Home"><img alt="logo" class="padding-10" src="<?php echo base_url('assets/images/logo/logo2.png');?>" width="130" height="60"></a></h3>
				
				
				<h3 class="text-center text-primary" ><i class="fa fa-lock fa-5x"></i></h3>
			
				<?php	
						$email_error = '';
						$password_error = '';
						
						echo form_open('trader/login_validation');
						if(form_error('email_address')){
							$email_error = 'input-error';
						}
						if(form_error('password')){
							$password_error = 'input-error';
						}
					
				?>	
				<p><?php echo form_error('email_address');?></p>
				
				<div class="form-group">
					<input type="text" name="email_address" value="<?php echo set_value('email_address'); ?>" class="form-control <?php echo $email_error; ?>" placeholder="Email Address" required autofocus>	
				<br/>
											      
				</div>
				<div class="form-group">
					<input type="password" id="upass" name="password" value="<?php echo set_value('password'); ?>" class="form-control <?php echo $password_error; ?>" placeholder="Password" required>	
				
				<?php echo form_error('password');?>						      
				</div>
				<div class="form-group">
                        <input id="toggleBtn" type="checkbox" onclick="togglePassword()"> Show Password
                </div>
				<button name="login_button" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Log in</button>
   
				<?php	
						
						echo form_close();
				?>			
				<p class="col-lg-8 col-lg-offset-2" style="text-align:right"><strong><a href="javascript:void(0)" onclick="location.href='<?php echo base_url('trader/password_reset');?>'">Forgot Password?</a></strong><p>
				
			
			</div>
       </section>
	   
    <!-- JQuery scripts
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		
	<!-- Custom scripts
    ================================================== -->
	
	<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>	
	
</body>
</html>	   