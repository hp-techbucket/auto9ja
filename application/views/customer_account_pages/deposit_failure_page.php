
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>
	<div class="container-fluid">
	
        <script type="text/javascript" language="javascript">
			//var delay = 50000; //Your delay in milliseconds
			//setTimeout(function(){ 
			//	window.location = "<?php echo base_url();?>account/billing/"; 
			//}, delay);
		</script>   
		
	<?php
		//check if flashdata is set
		$payment_data = '';
		$payment_error = '';
		$get_tx = '';
		if($this->session->flashdata('payment_data') != ''){
			$payment_data = $this->session->flashdata('payment_data');
		}
		if($this->session->flashdata('error') != ''){
			$payment_error = $this->session->flashdata('error');
		}
		if($this->session->flashdata('tx') != ''){
			$get_tx = $this->session->flashdata('tx');
		}
	?>				
        
		<div class="row">
            <div class="col-lg-12" align="center">
				<div class="alert alert-danger">
					<h3><i class="fa fa-exclamation-triangle"></i> Your deposit failed!</h3>
					<?php echo br(1); ?>
					<p><?php echo $payment_error; ?></p>
					<p>TX ID: <?php echo $get_tx; ?></p>
					<?php echo br(1); ?>
					<pre>PDT: <?php print_r($payment_data) ?></pre>
					
					<?php
					if(!empty($payment_data)){
						printf('Your payment of '.$payment_data["payment_gross"].' failed!');
						$amount = $payment_data["payment_gross"];
						echo 'Amount paid is $'.$amount;
					}else{
						echo 'No Payment data!';
					}

					?>							
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

