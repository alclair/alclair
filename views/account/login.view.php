<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>

<div ng-controller="accountLoginCtrl" style="margin-top:20px;margin-bottom:250px;">
    <!-- Main Container Starts -->
    <div id="main-container" class="container">
		<div class="row">
			<div class="col-sm-12">
				<form name="frmWaterworks" role="form" >
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label  class="control-label"> Username:</label><br/>
								<input type="text" name="username"  ng-model="vm.username" value="" class='form-control' placeholder='Please input your username'> 
							</div>
							<div class="form-group">
								<label  class="control-label"> Password:</label><br/>
								<input type="password" name="password"  ng-model="vm.password" value="" class='form-control' placeholder='Please input your password'> 
							</div>                         
						</div>                    
					</div><!-- end row -->
					<div class="row">
						<div class="col-md-12">
							<button type="button" class="btn btn-primary" ng-click="Login()">
                                <i class="fa fa-save"></i> Login 
                            </button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
	if ( $rootScope["SWDCustomer"] != "lng"  ) {
?>	
	<script src="<?=$rootScope["RootUrl"]?>/includes/app/accountCtrl.js"></script>
<?php } else { ?>
	<script src="<?=$rootScope["RootUrl"]?>/includes/app/accountCtrl_lng.js"></script>
<?php } ?>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
