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
	<?php echo link_tag('assets/images/favicon.ico', 'shortcut icon', 'image/ico'); ?>
    <title>Auto9ja | <?php echo $pageTitle; ?></title>

    <!-- Bootstrap core CSS -->
	<?php echo link_tag('assets/css/bootstrap.min.css'); ?> 
	<?php echo link_tag('assets/css/style.css'); ?>
	<script src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
	
	
  </head>

  <body id="<?php echo $pageID; ?>">

  
	  <section class="container" align="center">
	  
		  <div class="container social_container" >
			  
				<a href="<?php echo base_url();?>" title="Auto9ja"><img src="<?php echo base_url();?>assets/images/logo/logo2.png" class="img-responsive" alt="Logo"></a>
			
			</div>
			<div class="container social_div">
				<h3 align="center">Enter New Password</h3>
				<p align="center">Please enter a new password below:</p>
				
				<?php	
						echo form_open('trader/new_password_validation');
					
				?>	
				<div class="form-group">
					<input type="text" name="password" value="<?php echo set_value('password'); ?>" class="form-control " title="Enter password" placeholder="Enter password">	
					<br/>
					<?php echo form_error('password');?>						      
				</div>	
				
				
				<div class="form-group">
					<input type="text" name="confirm_password" value="<?php echo set_value('confirm_password'); ?>" class="form-control" title="Confirm password" placeholder="Confirm password">	
					<br/>
					<?php echo form_error('confirm_password');?>						      
				</div>	
				
				<button class="btn btn-primary btn-block" type="submit">Confirm</button>
				
				<?php	
						echo form_close();
				?>			
				
				<?php echo br(1); ?>
			
			</div>
       </section>
	   
</body>
</html>	   