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
				<h3 align="center">Confirm Security Information</h3>
				<p align="center">Please enter the answer to your security question below to reset your password</p>
				
				<?php	
						echo form_open('account/security_validation');
					
				?>	
				<span class="input-xlarge uneditable-input"><?php echo $security_question;?>	</span>	
				
				<div class="form-group">
					<input type="text" name="security_answer" value="<?php echo set_value('security_answer'); ?>" class="form-control " placeholder="Enter Security Answer" required autofocus>	
				<br/>
					<?php echo form_error('security_answer');?>						      
				</div>	
				
				<button name="login_button" class="btn btn-primary btn-block" type="submit">Confirm</button>
				
				<?php	
						echo form_close();
				?>			
				
				<?php echo br(1); ?>
			
			</div>
       </section>
	   
</body>
</html>	   