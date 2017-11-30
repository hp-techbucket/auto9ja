
	<section class="container-fluid social-page">
			<div class="social-card social-card-container">

				<div class="panel panel-primary">
				  <div class="panel-heading text-center">
				  Set your Security Information.
				  </div>
				</div>	
				
				<div class="row">
				
					<div class="col-md-7">
							
						<div class="well well-lg">

								<p class="text-center"><strong>Please set your security information below</strong></p>
								
								<?php echo br(1); ?>
								
								<?php
									echo form_open('security/validation'); 
									
									$ansError = '';
									
									if(form_error('security_answer')){
										$ansError = 'input-error';
									}			
										
								?>
								<div class="row">
								<div class="col-lg-10 col-lg-offset-1">
									
									<div class="form-group">
										<label for="security_question">Security Question: </label>
										<?php echo $security_questions; ?>
									</div>
									<br/>
									<?php echo form_error('security_question');?>
									
									<div class="form-group">
									<label for="security_question">Security Answer: </label>
									
										<input type="text" name="security_answer" id="security_answer" value="<?php echo set_value('security_answer'); ?>" class="<?php echo $ansError; ?> form-control" placeholder="Please enter your answer" required>
									</div>
									
									<p align="left">
										<input type="checkbox" id="toggleBtn" onclick="toggleAnswer()"> Show Answer<br/>
									<?php echo form_error('security_answer'); ?>
									</p>
									<p>
										<input type="submit" name="set_memorable_info_submit" class="btn btn-primary btn-block" value="Update">
									</p>	
									<?php echo form_close(); ?>
								</div>
							</div>		
							
						</div>
					</div>
					
					<div class="col-md-5">

						<div class="well well-lg">
							<h3 class="text-center">
								<i class="fa fa-lock" aria-hidden="true"></i>
								Your account is secure
							</h3>
							<p>We use maximum encryption to ensure the safety and security of your personal information.</p>
							<div class="text-center">
							<a href="javascript:void(0)"><img src="<?php echo base_url('assets/images/logo/23.gif');?>" class="grayscale"></a>
							<a href="javascript:void(0)"><img src="<?php echo base_url('assets/images/logo/logos_verisign.gif');?>" class="grayscale"></a>
							</div>
						</div>
					
					</div>
				</div>	
			<?php echo br(1); ?>
			
			</div>	
	</section>
	

