
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>

<div class="container-fluid profile-container">
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
			if($this->session->flashdata('profile_updated') != ''){
				$message = $this->session->flashdata('profile_updated');
			}
			if($this->session->flashdata('error') != ''){
				$message = $this->session->flashdata('error');
			}
			echo $message;						
		?>	
	</div>	
		
		<?php echo validation_errors(); ?>
		
		<?php 			

				$form_attributes = array('id' => 'bannerForm');
				$hidden = array(
					'user_id'  => $user->id,
					'email_address' => $user->email_address,
					'banner_photo' => $user->banner_photo,
				);
				echo form_open_multipart('account/banner_upload', $form_attributes);
				echo form_hidden($hidden);	
	
		?>	
		
	<div class="table-responsive ">					
		<table class="table table-striped ">
			<tbody>	
				<tr>
					<td>
						<div id="bannerMain" >
							<?php echo $banner; ?>
						</div>		
						<div class="changeBanner" align="center">
							<span class="btn-file banner-change-icon" >
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
								</span>
								<input type="file" name="banner_upload" id="bannerUpload" title="Change Profile Header">
							</span>
						</div>	

						<?php echo form_close(); ?>
					</td>
				</tr>	
			<?php 			

				$attributes = array('id' => 'userProfileForm');
				$hide = array(
					'user_id'  => $user->id,
					'email_address' => $user->email_address,
				);
				echo form_open_multipart('profile/update', $attributes);
				echo form_hidden($hide);	
	
		?>	
		
				<tr>
					<td>
					<?php echo $thumbnail; ?>
							<span class="btn btn-primary btn-responsive btn-file">
								CHANGE PHOTO <input type="file" name="upload_photo" id="uploadPhoto">
							</span>
							<span for="uploadPhoto" >
							</span>
							<span id="uploading" >
								<img src="<?php echo base_url(); ?>assets/images/gif/loading.gif" alt="Uploading"/>
							</span>
					</td>
				</tr>
					
				<tr>
					<td>
						<div class="user-profile">
							<strong>Tagline:</strong>
							<?php echo $user->tagline; ?>
							<a class="edit-profile" href="javascript:void(0)" ><i class="fa fa-pencil"></i> Edit</a>
						</div>
						
						<div class="update-profile">
							<div class="row">
								<div class="col-xs-6">
									<input type="text" id="tagline" name="tagline" value="<?php echo $user->tagline; ?>" placeholder="Enter a new tagline">
								</div>
								<div class="col-xs-2">
									<input type="button" name="save" class="btn btn-default save-profile" onclick="update_profile();" value="Save"/>
								</div>
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="user-profile">
							<strong>Name:</strong>
							<?php echo $user->first_name; ?> <?php echo $user->last_name; ?>
							
						</div>
					</td>
				</tr>	
					
					
				<tr>
					<td>
						<div class="user-profile">
							<strong>Location:</strong>
							<?php 
								$location = '';
								if($user->address == ''){
									$location = '';
								}else{
									$location = $user->address .', '.$user->city.' '.$user->postcode.', '.$user->state.', '.$user->country;
								}
							echo $location; ?>
							<a href="javascript:void(0)" class="edit-profile"><i class="fa fa-pencil"></i> Edit</a>
						</div>
						
						<div class="update-profile">
							<div class="row">
								<div class="col-xs-6">
									<input type="text" id="address" name="address" value="<?php echo $user->address; ?>" placeholder="No. 7 Castle road"/>
									
								</div>
								
								<div class="col-xs-3">
									<input type="text" id="postcode" name="postcode" value="<?php echo $user->postcode; ?>" placeholder="Postcode"/>
								</div>
							</div>
							<br/>
							
							<div class="row">
								<div class="col-xs-3">
									<?php echo $country_options; ?>
								</div>
								<div class="col-xs-3">
									<?php echo $state_options; ?>
								</div>
								
								<div class="col-xs-3">
									<?php echo $city_options; ?>
									<input type="text" id="other_city" name="other_city">
								</div>
								<div class="col-xs-2 custom-gutter1">
									<a href="#" title="Enter City manually" class="small other_city">Enter City manually</a>
								</div>
								<div class="col-xs-1 custom-gutter1">
									<button type="submit" class="btn btn-default save-profile" onclick="update_profile();" > Save</button>
									
								</div>
							</div>
						</div>
					</td>
				</tr>	
				
				
				
				<tr>
					<td>
						<div class="user-profile">
							<strong>Email:</strong>
							<?php echo $user->email_address; ?>
							
						</div>
						
						
					</td>
				</tr>
				<tr>
					<td>
						<div class="user-profile">
							<strong>Mobile:</strong>
							<?php echo $user->mobile; ?>
							<a href="javascript:void(0)" class="edit-profile"><i class="fa fa-pencil"></i> Edit</a>
						</div>
						
						<div class="update-profile">
							<div class="row">
								<div class="col-xs-6">
									<input type="text" id="mobile" name="mobile" value="<?php echo $user->mobile; ?>" placeholder="Enter a new mobile number">
								</div>
								<div class="col-xs-2">
									<button type="submit" class="btn btn-default save-profile" onclick="update_profile();" > Save</button>
								</div>
							</div>
						</div>
						
					</td>
				</tr>
				<tr>
					<td>
						<div class="user-profile">
							<strong>Birthday:</strong>
							
							<?php if($user->birthday == '0000-00-00') { ?>
								<a class="edit-profile" href="javascript:void(0)" title="Please enter your birthday" >Please enter your birthday</a>
							<?php } else { 
									echo  date('F j, Y',strtotime($user->birthday));
								}
							?>
			
							 <a href="javascript:void(0)" class="edit-profile"><i class="fa fa-pencil"></i> Edit</a>
						</div>
						
						
						<div class="update-profile">
							<div class="row">
								<div class="col-xs-3">
									
										<?php echo $birth_day ?>
								</div>
								
								<div class="col-xs-3">
									<?php echo $birth_month ?>
								</div>
								
								<div class="col-xs-3">
									<?php echo $birth_year ?>
								</div>
								<div class="col-xs-3">
									<button type="submit" class="btn btn-default save-profile" onclick="update_profile();" > Save</button>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="user-profile">
							<strong>Profile:</strong>
							<a href="javascript:void(0)" class="edit-profile"><i class="fa fa-pencil"></i> Edit</a>
							<div>
							<?php echo stripslashes(wordwrap(nl2br($user->profile_description), 54, "\n", true)); ?>
							</div>	
							
						</div>
						
						<div class="update-profile">
							<div class="row">
								<div class="col-xs-6">
									<textarea id="description" name="description" placeholder="Enter a new profile description"><?php echo $user->profile_description; ?></textarea>
								</div>
								<div class="col-xs-2">
									<button type="submit" class="btn btn-default save-profile" onclick="update_profile();" > Save</button>
								</div>
								
							</div>
						</div>
								
					</td>
				</tr>
				<?php echo form_close(); ?>
				<tr>
					<td><strong>Last Login:</strong>
					<?php echo $last_login; ?>
					
					</td>
				</tr>		
				<tr>
					<td><strong>Joined:</strong>
					<?php echo date('F j, Y',strtotime($user->date_created)); ?>
					</td>
				</tr>	
					
				<tr>
					<td>
					
					</td>
				</tr>						
			</tbody>
		</table>
	</div>
	
			
	<div class="container-fluid text-info">
			<?php echo $profile_completion; ?>	
	</div>
		
<?php echo br(1); ?>

</div>


<?php   
		}
	}								
?>

					</div><!--/col-span-9-->
				</div><!--.row-->
		</div><!--.custom-container-->
    </div><!--.container-->
