<div class="container-fluid contact_us">
		
      
    <section class="card card-container"> 
			<div class="row">
				<div class="col-md-12" align="center" > 
					<image class="img-reponsive" src="<?php echo base_url('assets/images/icons/contact_us.png')?>" width="340" height="100">
					<br/><br/>
					<p>You can send us a message using the form below:</p>
					
				</div>
			</div>
			
				<div id="response-message">
					<?php 
						$message_sent = '';
						if($this->session->flashdata('message_sent') != ''){
							$message_sent = $this->session->flashdata('message_sent');
						}	
						echo $message_sent;	
						echo validation_errors();
					?>	
				</div>
				
				<div class="error-message alert alert-danger alert-dismissable text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> </div>
							
				<br/><br/>
			<?php 
					$attributes = array('class' => 'contact_us_form', 'role' => 'form');
					echo form_open('contact_us/validation',$attributes); 					
			?>
			<div class="row">
				<div class="col-md-12"> 
					<label for="contact_us_name">Name</label>
					<div class="form-group" >
						<input type="text" name="contact_us_name" value="<?php echo set_value('contact_us_name'); ?>" class="form-control" id="contact_us_name" required placeholder="">
					</div>
				</div>
			</div>		
			<div class="row">
				<div class="col-md-12"> 
					<label for="contact_us_telephone">Telephone number</label>
					<div class="form-group" >
						<input type="tel" name="contact_us_telephone" value="<?php echo set_value('contact_us_telephone'); ?>" class="form-control" id="contact_us_telephone" required placeholder="">
					</div>
				</div>
			</div>			
			<div class="row">
				<div class="col-md-12"> 
					<label for="contact_us_email">Email</label>
					<div class="form-group" >
						<input type="text" name="contact_us_email" value="<?php echo set_value('contact_us_email'); ?>" class="form-control" id="contact_us_email" required placeholder="">
					</div>
				</div>
			</div>		
			<div class="row">
				<div class="col-md-12"> 
					<label for="contact_us_subject">Subject</label>
					<div class="form-group" >
						<input type="text" name="contact_us_subject" value="<?php echo set_value('contact_us_subject'); ?>" class="form-control" id="contact_us_subject" required placeholder="">
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-12"> 
					<label for="contact_us_message">Message</label>
					<div class="form-group" >
						<textarea name="contact_us_message" id="contact_us_message" required placeholder=""><?php echo set_value('contact_us_message'); ?></textarea>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-12"> 
					<input type="submit" class="btn btn-primary btn-block" value="Send">
				</div>
			</div>	
		<?php 
					echo form_close();					
		?>
		
		
	</section>
		
						   
</div>	   