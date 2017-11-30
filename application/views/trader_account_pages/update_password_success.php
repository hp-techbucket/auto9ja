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
				<div class="alert alert-success" align="center">
							<h3>Password Updated!</h3>
							
							<p>Your password has been updated. You can <a href="<?php echo base_url(); ?>trader/login/" title="Log In">Login Here</a>.</p>
				</div>

			</div>
       </section>
	   
</body>
</html>	   