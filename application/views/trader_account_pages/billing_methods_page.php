
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>
	<div class="container-fluid border-container">
	
		<div>
		<?php
			//handles success message display
				$message = '';
				
				if($this->session->flashdata('deposit_message') != ''){
					$message = $this->session->flashdata('deposit_message');
				}
				if($this->session->flashdata('bank_added') != ''){
					$message = $this->session->flashdata('bank_added');
				}	
				if($this->session->flashdata('updated') != ''){
					$message = $this->session->flashdata('updated');
				}
				if($this->session->flashdata('removed') != ''){
					$message = $this->session->flashdata('removed');
				}
				echo $message;
		?>
		</div>
		
		<div class="row">
            <div class="col-lg-12" >
				<div id="notif"></div>		
				<div id="errors"></div>		<br/>		
					
				<div class="text-center"><a class="btn btn-default active">Make Payments</a> </div>
				
				<?php echo br(1); ?>
				
				<div id="bank-accounts">
					<h4 class="text-center"><i class="fa fa-university"></i> Bank Accounts <i class="fa fa-angle-double-down"></i></h4>
				</div>
				
				<?php echo br(1); ?>
				
				<div id="bank-accounts-list">
				<?php
						if($bank_details_array){				
							foreach($bank_details_array as $bank){
												
								//mask the account number
								$account_number = 'XXXX-'.substr($bank->account_number,-4);
												
								if(strlen($bank->account_number) == 10){
									$account_number = 'XXX-XXX-'.substr($bank->account_number,-4);
								}
								if(strlen($bank->account_number) == 6){
									$account_number = 'XXX-'.substr($bank->account_number,-3);
								}
				?>	
					<div class="row">	
						<div class="col-xs-4 bank-account-number">
							<span class="pull-left">
								<img src="<?php echo base_url(); ?>assets/images/icons/bank-icon-5.png" />
							</span>
							<strong><?php echo nbs(2).$account_number; ?></strong>
												
							<span class="hiddenIcons">
								<a data-toggle="modal" data-target="#editbankModal" class="edit_bank"  id="<?php echo html_escape($bank->id);?>" title="Edit Bank Details"><i class="fa fa-pencil"></i> Edit</a>
								<?php echo nbs(2); ?>
								<a data-toggle="modal" data-target="#removebankModal" class="remove_bank"  id="<?php echo html_escape($bank->id);?>" title="Remove Bank Details"><i class="fa fa-trash-o"></i> Remove</a>
												
							</span>
											
						</div>
											
					</div>							
									
				<?php
							}
						}
				?>	
				</div>
            </div>
			<!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->		
		
		<p>When you are ready to make purchases, you will be required to add a billing method to Auto9ja. All payments will be held securely until the vehicle delivery is completed and then Auto9ja will release the funds to the seller.</p>
		
		<hr/>
		
		<div class="row">
            <div class="col-lg-10 col-lg-offset-1" >
		
		<?php 
						
			$add_bank_account_form = 'add_bank_account_form';
						
			if(validation_errors()){
				echo '<div class="alert alert-error">Please correct the errors below!</div>';
							
				$add_bank_account_form = 'addForm';
			}

		?>			
		
		<div id="success"></div>
		
		<div id="notif"></div>

		<div class="error-message alert alert-danger alert-dismissable text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> </div>
			
		<div class="text-center">
			<a class="btn btn-success addBankAccountButton"><i class="glyphicon glyphicon-plus-sign"></i> Add Bank Account</a> 
		</div><br/>
		
					
				<div class="<?php echo $add_bank_account_form; ?>">	
						<?php
					
						
						$bank_name_error = '';
						$bank_location_error = '';
						$account_name_error = '';
						$account_number_error = '';
						$sort_code_error = '';
						$swift_bic_error = '';

							
							if(form_error('bank_name')){
								$bank_name_error = 'input-error';
							}
							if(form_error('bank_location')){
								$bank_location_error = 'input-error';
							}		
							if(form_error('account_name')){
								$account_name_error = 'input-error';
							}	
							if(form_error('account_number')){
								$account_number_error = 'input-error';
							}	
							if(form_error('sort_code')){
								$sort_code_error = 'input-error';
							}	

							if(form_error('swift_bic')){
								$swift_bic_error = 'input-error';
							}
							
						$bankform = array(
							'name' => 'addBankAccountForm',
							'id' => 'addBankAccountForm',
						);
						
						echo form_open('payment/add_bank_account',$bankform);				
						
						?>
						<h4>BANK DETAILS</h4>
						<div class="row">
							<div class="col-xs-6">
								<?php echo form_label('Bank Name', 'bank_name'); ?><br/>
								<input type="text" name="bank_name" class="<?php echo $bank_name_error; ?>" id="bank_name" placeholder="Zenith Bank" required>
						
							</div>
							<div class="col-xs-6">
								<?php echo form_label('Bank Location', 'bank_location'); ?>
								<input type="text" name="bank_location" class="<?php echo $bank_location_error; ?>" id="bank_location" placeholder="Lagos, Nigeria" required>
						
							</div>							
							
						</div>
						
						<br/>
						
						<div class="row">
							<div class="col-xs-3">
								<strong><?php echo form_label('Account Name', 'account_name'); ?></strong><br/>
								<input type="text" name="account_name" class="<?php echo $account_name_error; ?>" id="account_name" placeholder="John Doe" required>
							</div>
							<div class="col-xs-3">
								<strong><?php echo form_label('Account Number', 'account_number'); ?></strong><br/>
								<input type="text" name="account_number" id="account_number" class="<?php echo $account_number_error; ?>" placeholder="12345678" onkeypress="return allowNumbersOnly(event)" required>
							</div>
							<div class="col-xs-3">
								<strong><?php echo form_label('Sort Code', 'sort_code'); ?></strong><br/>
								<input type="text" name="sort_code" id="sort_code" class="<?php echo $sort_code_error; ?>" placeholder="12-34-56">
							</div>
							<div class="col-xs-3">
								<strong><?php echo form_label('SWIFT/BIC', 'swift_bic'); ?></strong><br/>
								<input type="text" name="swift_bic" id="swift_bic" class="<?php echo $swift_bic_error; ?>" placeholder="ZEBNL2A">
							</div>
						</div>						
						
					<br/>
					<div><button type="button" class="btn btn-block custom-button4" onclick="javascript:addBankAccount();">Add Bank Account</button></div>	
					
					<?php echo form_close(); ?>		
				
				</div>					
			</div>
		</div>
		
		<hr/>
						
		<p>Task Credit balance: <span class="medium">$<?php echo number_format($user->account_balance, 0);?></span></p>	
			
			
	</div>
	
	<?php echo br(1); ?>

<?php   
		}
	}								
?>

					</div><!--/col-span-9-->
				</div>
	
    </div>
