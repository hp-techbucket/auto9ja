
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>

<div class="container-fluid border-container">
	<div class="update-alert">	
		<?php 
			$message = '';
			if($this->session->flashdata('security_info_updated') != ''){
				$message = $this->session->flashdata('security_info_updated');
				
				//$message .= '';
			}	
			if($this->session->flashdata('password_updated') != ''){
				$message = $this->session->flashdata('password_updated');
			}	
			
			if($this->session->flashdata('error') != ''){
				$message = $this->session->flashdata('error');
			}
			echo $message;						
		?>	
	</div>	

		<h3 align="center">You can change your password and/or security information below:</h3>
		<br/>
		
		<div class="row">
			<div class="col-lg-6 text-right">
				<a class="btn btn-primary btn-responsive change-password" ><i class="fa fa-fw fa-lock"></i> Change Password</a>
			
			</div>
			<div class="col-lg-6 text-left">
				<a class="btn btn-success btn-responsive change-security" ><i class="fa fa-fw fa-unlock"></i> Change Security Info</a>
			</div>
		</div>
	<?php 
							$update_password = 'update-password';
							$change_security = 'update-security';
							
							if(form_error('old_password') || form_error('new_password') || form_error('confirm_new_password')){
								echo '<div class="alert alert-danger text-danger text-center">Please correct the errors below!</div>';
								$update_password = 'password-security';
							}
							if(form_error('security_question') || form_error('security_answer')){
								echo '<div class="alert alert-danger text-danger text-center">Please correct the errors below!</div>';
								$change_security = 'password-security';
							}

						?>
	<br/>
	
		<div class="row">
			<div class="col-lg-6 col-lg-offset-3">
					<div class="<?php echo $update_password; ?>">	
								<?php

									$old_password_error = '';
									$new_password_error = '';
									$confirm_error = '';
										
									if(form_error('old_password')){
										$old_password_error = 'input-error';
									}
									if(form_error('new_password')){
										$new_password_error = 'input-error';
									}		
									if(form_error('confirm_new_password')){
										$confirm_error = 'input-error';
									}	
									echo form_open('password/update');				
										
								?>
									<br/>
									<h4 align="center">Change Password</h4>
									
									<p>	
										<div class="row">
											<div class="col-xs-12">
												<strong><?php echo form_label('Old Password', 'old_password'); ?></strong><br/>
												<input type="password" name="old_password" value="<?php echo set_value('old_password'); ?>" class="<?php echo $old_password_error; ?>" id="old_password" placeholder="Enter your old password" />
												<?php echo form_error('old_password'); ?>
											</div>
										</div>
									</p>
									<p>
										<div class="row">
											<div class="col-xs-12">
												<strong><?php echo form_label('New Password', 'new_password'); ?></strong><br/>
												<input type="password" name="new_password" value="<?php echo set_value('new_password'); ?>" class="<?php echo $new_password_error; ?>" id="new_password" placeholder="Enter a new password" />
												<?php echo form_error('new_password'); ?>
											</div>
										</div>
									</p>
									<p>
										<div class="row">
											<div class="col-xs-12">
												<strong><?php echo form_label('Confirm New Password', 'confirm_new_password'); ?></strong><br/>
												<input type="password" name="confirm_new_password" value="<?php echo set_value('confirm_new_password'); ?>" class="<?php echo $confirm_error; ?>" id="confirm_new_password" placeholder="Confirm your new password" />
												<?php echo form_error('confirm_new_password'); ?>
											</div>
										</div>
									</p>
									<p>
										<div class="row">
											<div class="col-xs-12">
												<button type="submit" class="btn btn-primary btn-block">Update Password</button>
											
											</div>
										</div>
									</p>	
									<?php echo form_close(); ?>		
								
									
										<br/>
							</div>	
							<div class="<?php echo $change_security; ?>">	
								<?php
									$question_error = '';
									$answer_error = '';
										
									if(form_error('security_question')){
										$question_error = 'input-error';
									}
									if(form_error('security_answer')){
										$answer_error = 'input-error';
									}
										
									echo form_open('security/update');
								?>
								<br/>
								<h4 align="center">Change Security Information</h4>
								
								<p>		
									<div class="row">
										<div class="col-xs-12">
											<?php echo form_label('Security Question', 'security_question'); ?><br/>
										
											<select name="security_question" class="<?php echo $question_error; ?>">
											<?php 
												//foreach($list_of_questions as $question){
													//<option value=" $question; "> $question; </option>
											?>
												
											<?php 
												echo $security_questions;
												//}
											?>										
											</select>

											<?php form_dropdown('security_question', $list_of_questions, 'Select A Question'); ?><br/>
											<?php echo form_error('security_question'); ?>
										</div>
									</div>
								</p>
								<p>
									<div class="row">
										<div class="col-xs-12">
											<strong><?php echo form_label('Security Answer', 'security_answer'); ?></strong><br/>
											<input type="text" name="security_answer" value="<?php echo $security_answer; ?>" class="<?php echo $answer_error; ?>" id="upass" placeholder="Confirm security answer" />
											<input id="toggleBtn" type="checkbox" onclick="togglePassword()"> Hide Answer
											<?php echo form_error('security_answer'); ?>
											
										</div>
									</div>
								</p>
								<p>
									<div class="row">
										<div class="col-xs-12">
											<button type="submit" class="btn btn-primary btn-block">Update Security Information</button>
											
										</div>
									</div>
								</p>
								<?php echo form_close(); ?>	

							</div>
								
								
			</div>
		</div>
	
</div>

<?php echo br(1); ?>


<?php   
		}
	}								
?>

					</div><!--/col-span-9-->
				</div><!--.row-->
		</div><!--.custom-container-->
    </div><!--.container-->

