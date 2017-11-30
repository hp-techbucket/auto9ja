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
	<?php echo link_tag('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css'); ?> 
	<?php echo link_tag('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'); ?>
	 <!-- Custom CSS -->
	<?php echo link_tag('assets/css/simple-sidebar.css'); ?>
	<?php echo link_tag('assets/css/style.css'); ?>
	<?php echo link_tag('assets/css/breadcrumb.css'); ?>
	
	<script src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
	<script type="text/javascript">var baseurl = "<?php echo base_url(); ?>";</script>
	
  </head>

  <body id="<?php echo $pageID; ?>">
		
		<div id="load">Please wait ...</div>
 
 
		<audio id="notif_audio"><source src="<?php echo base_url('assets/sounds/notify.ogg');?>" type="audio/ogg"><source src="<?php echo base_url('assets/sounds/notify.mp3');?>" type="audio/mpeg"><source src="<?php echo base_url('assets/sounds/notify.wav');?>" type="audio/wav"><embed hidden="true" autostart="true" loop="false" src="<?php echo base_url('assets/sounds/notify.mp3');?>" /></audio>
		
		
    
		<nav class="navbar navbar-default navbar-static-top navbar-xs">
          <div class="container">
			<div class="navbar-header">
			  <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".login-navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  
			</div>
            <div id="navbar" class="navbar custom-navbar navbar-default navbar-fixed-top navbar-collapse collapse">
              <ul class="nav navbar-nav custom-navbar-nav">
                <?php  
					$fullname = '';
					if(!empty($users))
					{
						foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
						{	
							$dashboard = base_url('account/');
							$messages_page = base_url('account/messages/');
							$u_logout = base_url('account/logout/');
							if($this->session->userdata('trader_logged_in')){
								$dashboard = base_url('trade_account/');
								$messages_page = base_url('trade_account/messages/');
								$u_logout = base_url('trade_account/logout/');
							}
							//get users full name
							$fullname = $user->first_name.' '.$user->last_name;
							
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
					<a title="<?php echo $cart_count ;?> items" href="javascript:void(0)" onclick="location.href='<?php echo base_url('home/cart/');?>'">Listing: <span class="cart-count"><?php echo $cart_count ;?></span></a>
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
								$dashbrd = base_url('trade_account/');
								$logout = base_url('trade_account/logout/');
							}
				?>
				<li>
					<a title="<?php echo $messages_unread ;?> messages" href="javascript:void(0)" onclick="location.href='<?php echo $private_messages;?>'"><i class="fa fa-envelope"></i> <span class="badge"><?php echo $messages_unread ;?></span></a>
				</li>
				<li>
                      <a href="javascript:void(0)" onclick="location.href='<?php echo base_url('account/billing');?>'">Bal: <strong><u>$<?php echo number_format($user->account_balance, 2); ?></u></strong></a>
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
		<div class="container logo-container">
			<div class="row">
				<div class="col-lg-2">
					
					<img alt="Brand" class="padding-10" src="<?php echo base_url('assets/images/logo/logo2.png');?>" width="130" height="60">
					
				</div>
				<div class="col-lg-6 padding-10">
					
					<?php
						form_open();
					?>
					<form action="<?php echo base_url('vehicles/search');?>" method="get">
						<div class="col-xs-9 nopadding">
							<div class="form-group" >
								<input type="text" name="search" class="form-control" pattern="[^'\x22]+" placeholder="example: honda civic red or LOT number or VIN">
							</div>
						</div>
						<div class="col-xs-3 nopadding">
							<button type="submit" class="btn btn-default" ><i class="fa fa-search"></i> Search</button>
						</div>
					</form>
					<?php form_close(); ?>	
				</div>
				<div class="col-lg-4">
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
		
		<div class="custom-container2">
		
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
					
					<div id="navbar" class="navbar-collapse collapse main-navbar">
						<ul class="nav navbar-nav">
								
						<?php   
						$default_tab = '<a title="CONTACT" href="javascript:void(0)" onclick="location.href=\''.base_url('contact_us/').'\'">CONTACT</a>';
						
						if(!empty($users))
						{
							foreach($users as $user) 
							{	
								
								$default_tab = '<a title="MY ACCOUNT" href="javascript:void(0)" onclick="location.href=\''.base_url('account/').'\'">MY ACCOUNT</a>';
								
								$messages_tab = '<a title="MESSAGES" href="javascript:void(0)" onclick="location.href=\''.base_url('account/messages/').'\'">MESSAGES ('.$messages_unread.')</a>';
								
								if($this->session->userdata('trader_logged_in')){
									
									$default_tab = '<a title="MY ACCOUNT" href="javascript:void(0)" onclick="location.href=\''.base_url('trade_account/').'\'">MY ACCOUNT</a>';
									$messages_tab = '<a title="MESSAGES" href="javascript:void(0)" onclick="location.href=\''.base_url('trade_account/messages/').'\'">MESSAGES ('.$messages_unread.')</a>';
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
							<li><a title="BECOME A SELLER" href="javascript:void(0)" onclick="location.href='<?php echo base_url('become_a_seller/');?>'">BECOME A SELLER</a></li>
							<li><a title="FAQ" href="javascript:void(0)" onclick="location.href='<?php echo base_url('faq/');?>'">FAQ</a></li>
							<li>
								<a title="VEHICLE FINDER" href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>vehicles/'">VEHICLE FINDER</a>
							</li>
							<li><a title="CONTACT" href="javascript:void(0)" onclick="location.href='.base_url('contact_us/').'">CONTACT</a></li>
						
						<?php
						}
						?>
							
						</ul>
					  
					</div><!-- #navbar-->
					
				</div><!-- .container-->
				
			</nav><!-- .navbar .navbar-default .navbar-custom-->
			
  
		<!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <br/> 
                        <ol class="breadcrumb dotted-bottom-border">
                            <li>
                                <a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>'"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                            </li>						
                            <li class="active">
                                <?php echo $page_breadcrumb;?>
                            </li>
							
                                <?php echo $breadcrumb;?>
                           		
                        </ol>
						
                    </div>
                </div>
                <!-- /.row -->

				<div class="row">
					<div class="col-md-3">

						<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
						<div class="collapse navbar-collapse navbar-ex1-collapse">
						
							<ul class="side-menu">
								
								<li><a href="javascript:void(0)" onclick="location.href='<?php echo base_url('account/dashboard');?>'">My Dashboard</a></li>
								
								<li>
									<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('account/messages');?>'">Private Messages (<?php echo $messages_unread;?>)</a>
								</li>
								
								<li>
									<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('account/watchlist');?>'">Watch list (<?php echo $watchlist_count;?>)</a>
								</li>
								
								<li>
									<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>account/payments/'" title="Payment History" > Payment History (<?php echo $payments_count;?>)</a>
								</li>
								<li>
									<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>account/pending_payments/'" title="Pending Payments" > Pending Payments (<?php echo $pending_payments_count;?>)</a>
								</li>
								<li>
									<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>account/statements/'" title="Statements" > Statements</a>
								</li>
								<li><a href="javascript:void(0)" onclick="location.href='<?php echo base_url('account/shipping');?>'">Shipping Status (<?php echo $shipping_count;?>)</a></li>
								
								<li class="custom-collapse">
									<a class="menu-collapse" href="#" data-toggle="collapse" data-target="#menu2">Profile <span class="pull-right"> <b class="caret"></b></span></a>
									
										<ul id="menu2" class="collapse">
											<li>
												<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>account/profile/'" title="Profile" > Profile</a>
											</li>
											<li>
												<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>account/billing/'" title="Billing Methods" > Billing Methods</a>
											</li>
											<li>
												<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>account/settings/'" title="Login and Password" > Login and Password</a>
											</li>
										</ul>
									
								</li>
								<li><a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>account/logout/'" title="Log Out">Log Out</a></li>
							</ul>
							
						</div>
						<!-- /.navbar-collapse -->
						
						<br/>
						
					</div><!-- /col-3 -->
					
					<!-- column 2 -->	
					<div class="col-md-9">
						<ul class="list-inline pull-right">
							<li>
								<a title="View and make changes to your account and personal information" href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>account/settings/'"><i class="glyphicon glyphicon-cog"></i></a>
							
							</li>
						<li>
							<a title="Send Message to Customer Support" data-toggle="modal" data-target="#newMessageModal" style="cursor:pointer; "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						</li>
						 
					  </ul>
					  <p><strong><?php echo $page_breadcrumb;?></strong></p>  
					  
						<hr>


						<div id="notif"></div>	

	
	<div class="modal fade" id="newMessageModal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="<?php echo base_url('message/support_message_validation');?>" id="newMessageForm" name="newMessageForm" class="form form-vertical" method="post" enctype="multipart/form-data">
				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Send Message to Customer Support</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="sender_name" name="sender_name" value="<?php echo $fullname; ?>">
					<div id="message-errors"></div>
					<div class="control-group">
                        <label for="message_subject">Subject</label>
                        <div class="controls">
                             <select name="message_subject" id="message_subject" class="form-control">
							 <?php
									//display support categories
									//as dropdown
									$this->db->from('support_categories');
									$this->db->order_by('id');
									$result = $this->db->get();
									if($result->num_rows() > 0) {
										foreach($result->result_array() as $row){
											echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';			
										}
									}
							 ?>
								
							 </select>
                        </div>
                    </div>
					<div class="control-group">
                        <label for="message_details">Message</label>
                        <div class="controls">
							<textarea name="message_details" id="message_details" class="form-control customTextArea" ></textarea>
                        </div>
                    </div> 
				</div>
				<div class="modal-footer">
					<a href="#" data-dismiss="modal" class="btn">Close</a>
					<button type="button" class="btn btn-primary" onclick="javascript:newMessage();" >Send</button>
				</div>
				
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dalog -->
	</div><!-- /.modal -->
	
	