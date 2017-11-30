
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>
	<div class="container-fluid">
		<div class="row">
            <div class="col-lg-12" align="center">
				<div class="alert alert-success">
					<h4>
						Hello <?php echo $user->first_name; ?>, your account has been credited with <span class="medium">$<?php echo number_format($payment_data["payment_gross"], 2); ?></span>
						
					</h4>
					<h4>
						Your account balance is now <span class="medium">$<?php echo number_format($user->account_balance, 2); ?></span>
					</h4>
					<h4>
						Transaction Reference: <span class="medium"><?php echo $payment_data["txn_id"]; ?></span>
					</h4>
					<div align="center">
						<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('account/dashboard');?>'" class="btn btn-success">Continue</a>
					</div>
        
				</div>
            </div>
        </div>
        <!-- /.row -->

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

