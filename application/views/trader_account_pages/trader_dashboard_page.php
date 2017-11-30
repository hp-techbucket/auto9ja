
<?php  
	if(!empty($users))
	{
		foreach($users as $user) // user is a class, because we decided in the model to send the results as a class.
		{	
?>

       <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                             <?php echo $pageTitle;?> <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i>  <?php echo $pageTitle;?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
			<div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-xs-12 text-right">
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<i class="fa fa-clock-o fa-fw"></i>  <strong>Last Login: </strong><?php echo $last_login; ?>
						</div>
                    </div>
                </div>
                <!-- /.row -->
			</div>

				<div class="container-fluid">
					<h3 align="center"><i class="fa fa-info-circle"></i> What do you need done today, <?php echo $user->first_name; ?>?</h3>
                </div>
				
				<?php echo br(2); ?>
                <!-- /.row -->
                
                <div class="container-fluid">
						<div class="row">
							<div class="col-lg-6 col-xs-6">
						
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-bell-o fa-fw"></i> Announcements</h3>
									</div>
									<div class="panel-body">
										
										<div class="list-group">
											<?php echo $activity_group; ?>		
										</div>				
									</div>
								</div>
							</div>

						</div>
                <!-- /.row -->
				</div>
				<?php echo br(2); ?>
			   
				<?php echo br(2); ?>
							
	<?php				


	?>
				<?php echo br(5); ?>
	
<?php echo br(15); ?>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php   
		}
	}								
?>

