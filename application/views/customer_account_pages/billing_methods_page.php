
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
				
				if($this->session->flashdata('paypal_error') != ''){
					$message = $this->session->flashdata('paypal_error');
				}
				if($this->session->flashdata('deposit_message') != ''){
					$message = $this->session->flashdata('deposit_message');
				}
				if($this->session->flashdata('bank_added') != ''){
					$message = $this->session->flashdata('bank_added');
				}	
				if($this->session->flashdata('paypal_added') != ''){
					$message = $this->session->flashdata('paypal_added');
				}		
				if($this->session->flashdata('paypal_updated') != ''){
					$message = $this->session->flashdata('paypal_updated');
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
					
				<div class="text-center">
					<a class="btn btn-default active">Make Payments</a> 
					<a class="btn btn-success addPayPalButton"><i class="glyphicon glyphicon-plus-sign"></i> Add PayPal</a> 
				</div>	
				<?php echo br(1); ?>
				
				<p>
				When you are ready to make purchases, you will be required to add a billing method to Auto9ja. All payments will be held securely until the vehicle delivery is completed and then Auto9ja will release the funds to the seller.
				</p>
		
				<hr/>
		
				<div id="paypal-accounts-list">
				
					<div class="row ">
						<div class="col-xs-2">
							<span class="pull-left">
								<img src="<?php echo base_url(); ?>assets/images/icons/paypal.png" />
							</span>
						</div>
						<div class="col-xs-4">
							<span class="">
								<strong><?php echo $masked_paypal; ?></strong>
							</span>
						</div>
						<div class="col-xs-4">
							
								<span class="hiddenIcons pull-left">
									<a data-toggle="modal" data-target="#paypaldepositModal" class="btn btn-primary btn-responsive paypal_deposit btn-sm"  id="<?php echo html_escape($paypal_id);?>" title="PayPal Deposit"><i class="fa fa-paypal"></i> Deposit</a>
												
									<a href="#" data-toggle="modal" data-target="#editPayPalModal" class="edit_paypal"  id="<?php echo html_escape($paypal_id);?>" title="Edit PayPal Account"><i class="fa fa-pencil"></i> Edit</a>
												
									<?php echo nbs(2); ?>
												
									<a href="#" data-toggle="modal" data-target="#removePayPalModal" class="remove_paypal"  id="<?php echo html_escape($paypal_id);?>" title="Remove PayPal Account"><i class="fa fa-trash-o"></i> Remove</a>
								</span>	

						</div>
								
					</div>
					<!-- /.row -->	
				</div>
				<!-- /.paypal-accounts-list -->
			</div>
			<!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->		
		
		<div class="row">
            <div class="col-lg-12" >
			
				
            </div>
			<!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->		
		
		<hr/>
		
		<div class="row">
            <div class="col-lg-12" >
		
		<?php 
						
			$add_PayPal_form = 'add_PayPal_form';
						
			if(validation_errors()){
				echo '<div class="alert alert-error">Please correct the errors below!</div>';
							
				$add_PayPal_form = 'addForm';
			}

		?>			
		
		<div id="success"></div>
		
		<div id="notif"></div>

		<div class="error-message alert alert-danger alert-dismissable text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> </div>
			
		
		
					
				<div class="<?php echo $add_PayPal_form; ?>">	
						<?php
					
						$paypal_error = '';
						if(form_error('paypal_email')){
							$paypal_error = 'input-error';
						}
						
						$attr = array(
							'name' => 'paypalForm',
							'id' => 'addpaypalForm',
							'class' => 'addpaypalForm',
						);
						
						echo form_open('payment/add_paypal',$attr);				
						
						?>
						
						<h4 class="text-center"></h4>
						<br/>
						
						<div class="row">
							<div class="col-xs-2">
								<span class="pull-right"><img src="<?php echo base_url(); ?>assets/images/icons/paypal.png" /></span>
							</div>
							<div class="col-xs-6">
								<input type="text" name="paypal_email" value="<?php echo set_value('paypal_email'); ?>" class="<?php echo $paypal_error; ?>" id="paypal_email" placeholder="Enter your a new PayPal Email" title="Enter a new PayPal Email address"/>
							</div>
							
							<div class="col-xs-2">
								<button type="button" class="btn custom-button4" onclick="javascript:addPayPal();">Add</button>
								
							</div>
						</div>
						
						
					<?php echo form_close(); ?>		
				
				</div>					
			</div>
		</div>
		
		<hr/>
						
		<p>Account Balance: <span class="medium">$<?php echo number_format($user->account_balance, 0);?></span></p>	
			
			
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



		<!-- Deposit via PayPal -->
		<div class="modal fade" id="paypaldepositModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<?php 
				$attributes = array(
					'name' => 'paypalDepositForm',
					'class' => 'form-inline',
					'id' => 'paypalDepositForm',
				);			
				//start form
				echo form_open('payment/paypal_process', $attributes);	
				$hidden = array('id' => 'paypal_id',);	
				echo form_hidden($hidden);
			?>
			<div class="modal-dialog" role="document">
				<div class="modal-content" align="center">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 align="center">Deposit via <img src="<?php echo base_url('assets/images/icons/paypal.png'); ?>" /></h3>
				  </div>
				  <div class="modal-body">
				  <div class="form_errors"></div>
					
						<div class="form-group pull-left" >
							
							<div class="input-group">
							  <div class="input-group-addon">$</div>
							  <input type="text" name="amount" class="form-control newDeposit" onkeypress="return allowNumbersOnly(event)" placeholder="Amount" title="Please enter a deposit amount" required>
							  <div class="input-group-addon">.00</div>
							  
							</div>
							<span class="depositNote">Please enter your deposit!</span>
						</div>
						<br/>
					<div id="alert-msg"></div>
				  </div>
				  <div class="modal-footer">
					<span class="pull-left">
						
						<input type="button" class="btn btn-primary" onclick="javascript:paypal_deposit();" value="Deposit">
					</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				  </div>
				</div>
			  </div>
			  <?php echo form_close(); ?>	
			</div>	
		<!-- Deposit via PayPal -->
		
		

		<!-- Edit PayPal -->
			<div class="modal fade" id="editPayPalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <?php 
				$edit_attr = array(
					'name' => 'editPayPalForm',
					'id' => 'editPayPalForm',
				);			
				//start form
				echo form_open('payment/update_paypal', $edit_attr);	
				
			?>
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 align="center">Edit PayPal</h3>
					<div id="alert-message"></div>
				  </div>
				  <div class="modal-body">
						<div class="form-group" >
							<?php echo form_label('PayPal', 'paypal_email'); ?>
							<div class="input-group">
							  <div class="input-group-addon"><i class="fa fa-paypal"></i></div>
							  <input type="text" class="form-control" name="paypal_email" id="masked_paypal_email" value="<?php echo $masked_paypal ; ?>">
							  <input type="hidden" name="id" id="paypalID" value="<?php echo $paypal_id; ?>">
							</div>
						</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					
					<input type="button" class="btn btn-primary" onclick="javascript:editPayPal();" value="Update">
				  </div>
				</div>
			  </div>
			  <?php echo form_close(); ?>
			</div>	
		<!-- /Edit PayPal -->
		
		
		<!-- Remove Paypal -->
		<div class="modal fade" id="removePayPalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<form action="javascript:removePayPal();" id="removePayPal_form" name="removePayPalForm" method="post">  
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 align="center" >Remove?</h3>
				  </div>
				  <div class="modal-body text-center">
						<p>
						Are you sure you want to remove this account (<?php echo $masked_paypal ; ?>)?
						<input type="hidden" name="paypEmail" id="paypEmail" value="<?php echo $masked_paypal ; ?>"/>
						<input type="hidden" name="id" id="paypID" value="<?php echo $paypal_id; ?>">
						</p>

				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					
					<input type="button" class="btn btn-danger" onclick="javascript:removePayPal();" value="Remove PayPal">
				  </div>
				</div>
			  </div>
			  </form>	
			</div>	
	<!-- /Remove Paypal -->
		
	
	