 
<div class="black-bg-o footer-links">
	<div class="container" align="center">
		<div class="row footer-row">
			<div class="col-md-3 col-xs-6">
				<div class="footer-sec">
					<h4>
						Vehicle Finder
					</h4>
					<ul>
						<li>
							<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('vehicles/');?>'"></a>
						</li>
								
					</ul>
				</div>
			</div>
			
			<div class="col-md-3 col-xs-6">
				<div class="footer-sec">
					<h4>
						Auctions
					</h4>
					<ul>
						<li>
							<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('auctions/');?>'">Auctions</a>
						</li>
						
											
					</ul>
				</div>
			</div>
			
			<div class="col-md-3 col-xs-6">
				<div class="footer-sec">
					<h4>
						Support
					</h4>
					<ul>
						<li>
							<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('contact-us/');?>'">Contact Us</a>
						</li>
						
						<li class="phone first">0816 876 2455</li>
						<li class="phone">info[@]gialovela.com</li>					
					</ul>
				</div>
			</div>
			
			<div class="col-md-3 col-xs-6">
				<div class="footer-sec">
					<h4>
						Company Information
					</h4>
					<ul>
						<li>
							<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('about/');?>'">About Us</a>
						</li>
						
						<li>
							<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('news/');?>'">News</a>
						</li>					
					</ul>
				</div>
			</div>
			
		</div>	
		
	</div>	
		
	<div class="container">
	
		<div class="row">
			<div class="col-md-12">
				<ul id="top-menu" class="footer-menu"> 
					<li>
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url();?>'"> HOME </a> 
					</li>
					<li>
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('about/');?>'">ABOUT US</a> 
					</li>
					<li>
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('become-a-seller/');?>'" >BECOME A SELLER</a> 
					</li>
					<li>
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('faq/');?>'">FAQ</a> 
					</li>
					<li>
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('vehicles/');?>'">VEHICLE FINDER</a> 
					</li>
					
					<li>
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('contact-us/');?>'">CONTACT US</a>
					</li> 
					<li><a href="#">0816 876 2455</a></li> 
					<li><a href="#">info[@]auto9ja.com</a> </li>
				</ul>
			</div>
		</div>
	</div>
</div>


<div class="grey-bg-o">
	<div class="container" align="center">
		<div class="row">
			<div class="col-md-5">
				<h4>STAY CONNECTED VIA EMAIL</h4>
				<p>Subscribe for Money Saving Updates:</p>
			</div>
			<div class="col-md-4">
			<br/><br/>
			<?php 	
				$attributes = array('class' => 'subscription_form', 'role' => 'form');
				echo form_open('subscription/validation',$attributes); 					
			?>
				<div class="input-group">
					<input type="email" class="form-control" name="email" placeholder="Enter your Email Address">
							<span class="input-group-btn">
								<button class="btn btn-custom" name="subscribe" value="Subscribe" type="submit">Subscribe</button>
								
							</span>
				</div>
			<?php 
				echo form_close();					
			?>
			</div>
			<div class="col-md-3">
				<h4>CONNECT WITH US ON:</h4>
				
				<div class="social-icons">
					
					<a target="_blank" href="https://www.instagram.com/auto9ja">
						<img src="<?php echo base_url('assets/images/icons/instagram.png');?>" width="30" height="30">
					</a>
					<a class="" target="_blank" href="https://www.facebook.com/Auto9ja">
						<img src="<?php echo base_url('assets/images/icons/facebook.png');?>" width="30" height="30">
					</a>
					<a target="_blank" href="https://www.twitter.com/auto9ja">
						<img src="<?php echo base_url('assets/images/icons/twitter.png');?>" width="30" height="30">
					</a>
					<a target="_blank" href="https://plus.google.com/b/12121/+auto9ja">
						<img src="<?php echo base_url('assets/images/icons/google-plus.png');?>" width="30" height="30">
					</a>
					<a target="_blank" href="https://www.linkedin.com/company/auto9ja">
						<img src="<?php echo base_url('assets/images/icons/linkedin.png');?>" width="30" height="30">
					</a>
					<a target="_blank" href="https://www.auto9ja.com/blog">
						<img src="<?php echo base_url('assets/images/icons/bloggr.png');?>" width="30" height="30">
					</a>
				</div>
			</div>
		</div>

		
	</div>	
</div>


<div class="black-bg-o">
	<div class="container" align="center">
		<div class="row small">
			<div class="col-lg-5">
				<span class="text-muted">Head Office</span>
				<span class="">No. </span>
			
			</div>
			<div class="col-lg-7">
				<span class="text-muted">Copyright &copy; 2016 Auto9ja. All rights reserved </span>
				<span class="">
					<a href="<?php echo base_url('privacy/');?>" >Privacy</a> &middot; 
					<a href="<?php echo base_url('terms/');?>" >Terms</a>
				</span>
			</div>
		</div>
	</div>
</div>


	<a title="Go to top" href="#" class="back-to-top"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
	
      <!-- Social Media and Website Tracking Scripts -->
		
		
		
    <!-- JQuery scripts
    ================================================== -->
     <!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	<!-- Include Bootstrap jasny -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
	
	<!-- Include Bootstrap Validator -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrapValidator.min.js"></script>
		
	<!-- Include Bootstrap Wizard -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap-wizard/1.2/jquery.bootstrap.wizard.min.js"></script>
	
	<!-- FastClick -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/fastclick.js"></script>
			
			
	<!-- jQuery Tags Input -->
	<script src="<?php echo base_url('assets/js/jquery.tagsinput.js'); ?>"></script>
		
	<!-- Select2 -->
	<script src="<?php echo base_url('assets/js/select2.full.min.js'); ?>"></script>
	
	<!-- DataTables -->
	<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
		
	<!-- Slick -->
	<script src="<?php echo base_url(); ?>assets/js/slick.js?<?php echo time(); ?>" type="text/javascript"></script>	
	
		
	<!-- jssor.slider -->
	<script src="<?php echo base_url(); ?>assets/js/jssor.slider-22.0.15.min.js?<?php echo time(); ?>" type="text/javascript"></script>	
	
	<!-- Facebook JavaScript
    ================================================== -->
	<script src="//connect.facebook.net/en_US/sdk.js"></script>
	
	<!-- My custom scripts
    ================================================== -->
	
	<!-- Custom Datatable Script -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/vDataTables.js?<?php echo time();?>"></script>
	
	<script src="<?php echo base_url(); ?>assets/js/script.js?<?php echo time(); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/fb.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('assets/js/messaging.js'); ?>" type="text/javascript"></script>

	
</body>
</html>	 

