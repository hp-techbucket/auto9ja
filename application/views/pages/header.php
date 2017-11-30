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
    <title>Auto9ja | <?php echo $pageTitle; ?></title>

    <!-- Bootstrap core CSS -->
	<?php echo link_tag('assets/css/bootstrap.min.css'); ?>
	<?php echo link_tag('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'); ?>
	
	<!-- JQUERY UI style -->
	<?php echo link_tag('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css'); ?> 
	
	<!-- Font Awesome style -->
	<?php echo link_tag('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'); ?>

	<!-- Datatables -->
    <link href="<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    
    <link href="<?php echo base_url(); ?>assets/css/responsive.bootstrap.min.css" rel="stylesheet">
   
	
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		
	<!-- TAGS INPUT style -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.3/jquery.tagsinput.css">
	
	<!-- JASNY BOOTSTRAP style -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
	
    <!-- Datatables -->
	<link href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet">	
	<link href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet">
	
	
	<!-- Animate.css style -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
	
	<!-- Custom CSS -->
	<?php echo link_tag('assets/css/simple-sidebar.css'); ?>

	<?php echo link_tag('assets/css/breadcrumb.css'); ?>
	
	<!-- Custom Theme Style -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css?<?php echo time(); ?>" media="all"/>
	
	
	<!-- Slick.css style -->
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>


	
	<script src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
	<script type="text/javascript">var baseurl = "<?php echo base_url(); ?>";</script>
	
  </head>

  <body id="<?php echo $pageID; ?>">
 
    <div class="navbar-wrapper">
		<nav class="navbar custom-navbar navbar-default navbar-fixed-top navbar-xs">
			<div class="container-fluid">
				<div class="navbar-header">
				  <!-- <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".login-navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>-->
				  
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".login-navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<i class="fa fa-sign-in" aria-hidden="true"></i>
				  </button>
				</div>
				<div class="navbar-collapse collapse login-navbar">
				  <ul class="nav navbar-nav custom-navbar-nav">
					<?php   
						if(!empty($users))
						{
							foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
							{	
								$dashboard = base_url('account/');
								$messages_page = base_url('account/messages/');
								$u_logout = base_url('account/logout/');
								if($this->session->userdata('trader_logged_in')){
									$dashboard = base_url('trader/');
									$messages_page = base_url('trader/messages/');
									$u_logout = base_url('trader/logout/');
								}
					?>
					<li class="dropdown">
						
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?php echo nbs(2); ?><i class="fa fa-user"></i> Hi, <?php echo $user->first_name ; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
								<a href="javascript:void(0)" onclick="location.href='<?php echo $dashboard;?>'"><i class="fa fa-fw fa-user"></i> My Account</a>
							</li>
							<li>
								<a href="javascript:void(0)" onclick="location.href='<?php echo $messages_page;?>'"><i class="fa fa-fw fa-envelope"></i> Private Messages <span class="badge"><?php echo $messages_unread ;?></span></a>
							</li>
							 <li>
								<a title="Log Out" href="javascript:void(0)" onclick="location.href='<?php echo $u_logout;?>'"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
							</li>
						</ul>
					</li>
					<?php   
								
							}
						}else{								
					?>
					<li>
						
					   <p><?php echo nbs(2); ?>Hi, Guest</p>
					</li>
					
					<?php   
						}								
					?>
				  </ul>
				  <ul class="nav navbar-nav navbar-right">
					
					<li>
						<a title="<?php echo $cart_count ;?> items" href="javascript:void(0)" onclick="location.href='<?php echo base_url('home/listing/');?>'">Listing: <span class="cart-count"><?php echo $cart_count ;?></span></a>
					</li>
					<?php   
						if(!empty($users))
						{
							foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
							{	
								$private_messages = base_url('messages/inbox');
								$dashbrd = base_url('account/');
								$logout = base_url('account/logout/');
								if($this->session->userdata('trader_logged_in')){
									$private_messages = base_url('messages/trader_inbox');
									$dashbrd = base_url('trader/');
									$logout = base_url('trader/logout/');
								}
					?>
					<li>
						<a title="<?php echo $messages_unread ;?> messages" href="javascript:void(0)" onclick="location.href='<?php echo $private_messages;?>'"><i class="fa fa-envelope"></i> <span class="badge"><?php echo $messages_unread ;?></span></a>
					</li>
					
					<li>
						<a title="My Account" href="javascript:void(0)" onclick="location.href='<?php echo $dashbrd;?>'"><i class="fa fa-fw fa-dashboard"></i> My Account</a>
					</li>
					<li>
						<a title="Log Out" href="javascript:void(0)" onclick="location.href='<?php echo $logout;?>'"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
					</li>
					<li><?php echo nbs(2); ?></li>
				  <?php   
							}
						}else{								
					?>
					<li>
						<a title="Register" href="javascript:void(0)" onclick="location.href='<?php echo base_url('register/');?>'"><i class="fa fa-fw fa-user"></i> Register</a>
					</li>
					<li>
						<a title="Login" href="javascript:void(0)" onclick="location.href='<?php echo base_url('login/');?>'"><i class="fa fa-fw fa-sign-in"></i> Login</a>
					</li>
					<li><?php echo nbs(2); ?></li>
					<?php   
						}								
					?>
				  
				  </ul>
				</div>
			</div>
        </nav>
	</div>	
		<br/><br/>
		<div class="container logo-container">
			<div class="row">
				<div class="col-lg-2 col-sm-12 col-xs-12" align="center">
					
					<img alt="Auto9ja" class="padding-10" src="<?php echo base_url('assets/images/logo/logo2.png');?>" width="130" height="60">
					
				</div>
				<div class="col-lg-6 padding-10 col-sm-12 col-xs-12" align="center">
					
					<?php
						form_open();
					?>
					<form action="<?php echo base_url('vehicles/search');?>" method="get">
						<div class="input-group">
						
							<input type="text" name="keywords" id="keywords" class="form-control" pattern="[^'\x22]+" placeholder="example: honda civic red or LOT number or VIN">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit"><i class="fa fa-search"></i> Search</button>
								
							</span>
						</div>
						
					</form>
					<?php form_close(); ?>	
				</div>
				<div class="col-lg-4 col-sm-12 col-xs-12" align="center">
					<div class="col-xs-8 nopadding">
						<h4>+234 818 891 2819</h4>
						<p class="small">Monday - Friday (6am - 5pm WST)</p>
					</div>
					<div class="col-xs-4 nopadding">
					
					<img alt="Call" src="<?php echo base_url('assets/images/img/tell-bl.png');?>" width="70" height="70">
					
					</div>
				</div>
			</div>
		</div>
		<div class="container">
				<nav class="navbar navbar-default navbar-custom">
					
					<div class="container">
					
						<div class="navbar-header">
						  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".main-navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						  </button>
						  
						</div>
						<div id="navbar2" class="navbar-collapse collapse main-navbar">
							<ul id="custom-inline" class="nav navbar-nav">
									
							<?php   
							$default_tab = '<a title="CONTACT" href="javascript:void(0)" onclick="location.href=\''.base_url('contact_us/').'\'">CONTACT</a>';
							
							if(!empty($users))
							{
								foreach($users as $user) 
								{	
									
									$default_tab = '<a title="MY ACCOUNT" href="javascript:void(0)" onclick="location.href=\''.base_url('account/').'\'">MY ACCOUNT</a>';
									
									$messages_tab = '<a title="MESSAGES" href="javascript:void(0)" onclick="location.href=\''.base_url('account/messages/').'\'">MESSAGES ('.$messages_unread.')</a>';
									
									if($this->session->userdata('trader_logged_in')){
										
										$default_tab = '<a title="MY ACCOUNT" href="javascript:void(0)" onclick="location.href=\''.base_url('trader/').'\'">MY ACCOUNT</a>';
										$messages_tab = '<a title="MESSAGES" href="javascript:void(0)" onclick="location.href=\''.base_url('trader/messages/').'\'">MESSAGES ('.$messages_unread.')</a>';
									}
									
							
						?>
								<li><a title="HOME" href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>'">HOME</a></li>
								<li><a title="ABOUT" href="javascript:void(0)" onclick="location.href='<?php echo base_url('about/');?>'">ABOUT</a></li>
								
								<li><a title="FAQ" href="javascript:void(0)" onclick="location.href='<?php echo base_url('faq/');?>'">FAQ</a></li>
								<li>
									<a title="VEHICLE FINDER" href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>vehicles/'">VEHICLE FINDER</a>
								</li>
								<li><?php echo $default_tab; ?></li>
								<li><?php echo $messages_tab; ?></li>
							<?php
								}
							}else{
							
							?>
							<li><a title="HOME" href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>'">HOME</a></li>
								<li><a title="ABOUT" href="javascript:void(0)" onclick="location.href='<?php echo base_url('about/');?>'">ABOUT</a></li>
								<li><a title="BECOME A SELLER" href="javascript:void(0)" onclick="location.href='<?php echo base_url('become-a-seller/');?>'">BECOME A SELLER</a></li>
								<li><a title="FAQ" href="javascript:void(0)" onclick="location.href='<?php echo base_url('faq/');?>'">FAQ</a></li>
								<li>
									<a title="VEHICLE FINDER" href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>vehicles/'">VEHICLE FINDER</a>
								</li>
								<li><a title="CONTACT" href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>contact-us/'">CONTACT</a></li>
							
							<?php
							}
							?>
								
							</ul>
						  
						</div><!-- #navbar-->
					</div><!-- .container-->
				</nav><!-- .navbar .navbar-default .navbar-custom-->
        </div><!-- .container-->

	 
       
	
	