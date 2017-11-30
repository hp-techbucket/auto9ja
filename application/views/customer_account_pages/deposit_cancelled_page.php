
<?php   
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>
	<div class="container-fluid">

             	
                <div class="row">
                    <div class="col-lg-12" align="center">
						<div class="alert alert-warning">
							<h3> 
								<i class="fa fa-ban"></i>
								Your deposit was cancelled!
							</h3>
							<div align="center">
								<a href="javascript:void(0)" onclick="location.href='<?php echo base_url('account/billing');?>'">Try Again?</a>
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

