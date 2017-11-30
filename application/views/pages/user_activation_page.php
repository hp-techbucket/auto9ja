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
	<?php echo link_tag('assets/images/logo/favicon.ico', 'shortcut icon', 'image/ico'); ?>
	<?php echo link_tag('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'); ?>

     <title>Avenue 1-OH-1 | <?php echo $pageTitle; ?></title>

    <!-- Bootstrap core CSS -->
	<?php echo link_tag('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'); ?>
	<?php echo link_tag('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css'); ?> 
	<?php echo link_tag('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'); ?>
	<?php echo link_tag('assets/css/style.css'); ?>
	
	<script src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
	
  </head>

  <body class="custom-wrapper">

  
	  <section class="container" align="center">
       
	   <div class="social-card social-card-container" >
		
			
				<div class="logo-container text-center">
					<a href="<?php echo base_url();?>" title="Avenue 1-OH-1"><img src="<?php echo base_url();?>assets/images/logo/Avenue101-logo.JPG" class="img-responsive" alt="Logo" width="200" height="200"></a>
				</div>
				
				 <p>Please enter your activation code below:</p>
			
				<?php	
						$activationError = '';

						
						if(form_error('activation_code')){
							$fnError = 'inputError';
						}

					echo form_open('main/activation_validation');
						
				?>	
					
				<p align="left">
					<label for="activation_code">Activation Code</label>
					<input type="text" name="activation_code" id="" class="<?php echo $activationError;?>" value="<?php echo set_value('activation_code');?>">
					<br/>
					<?php echo form_error('activation_code');?>
				</p>

				<p><input type="submit" name="activation_button" class="btn btn-primary btn-block" value="Activate!"></p>
				<?php	
					echo form_close();
				?>
			<?php echo br(3); ?>
		</div>

					
       </section>

	   
	<!-- JQuery scripts
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="http://code.jquery.com/jquery-1.12.0.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		
	
	<!-- My custom scripts
    ================================================== -->
	<script src="<?php echo base_url('assets/js/script.js'); ?>" type="text/javascript"></script>
		   
	   
</body>
</html>	   