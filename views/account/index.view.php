<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>

<div ng-controller="accountCtrl" style="margin-top:20px;margin-bottom:20px;">
    <!-- Main Container Starts -->
    <div id="main-container" class="container">
		<div class="row">
			<div class="col-sm-12">
				<form  role="form" name="frmSWD">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label  class="control-label"> Username:</label><br/>
								<input type="text" name="username" required ng-model="vm.username" class='form-control'> 
							</div>
                            <div class="form-group">
								<label  class="control-label"> First Name:</label><br/>
								<input type="text" name="firstname" required ng-model="vm.first_name" class='form-control'> 
							</div>
                            <div class="form-group">
								<label  class="control-label"> Last Name:</label><br/>
								<input type="text" name="lastname" required ng-model="vm.last_name" class='form-control'> 
							</div>
                            <div class="form-group">
								<label  class="control-label"> Email:</label><br/>
								<input type="text" name="email" required  ng-model="vm.email" class='form-control'> 
							</div>   
                            <div class="form-group">
								<label  class="control-label"> New Password:</label><br/>
								<input type="password" name="newpassword"  ng-model="vm.newpassword" class='form-control'> 
							</div>     
                            <div class="form-group">
								<label  class="control-label"> Confirm Password:</label><br/>
								<input type="password" name="confirmpassword"  ng-model="vm.confirmpassword" class='form-control'> 
							</div>                      
						</div>                    
					</div><!-- end row -->
					<div class="row">
						<div class="col-md-12">
							<button type="button" class="btn btn-primary"  ng-click="save()" ng-disabled="!frmSWD.$valid||vm.newpassword!=vm.confirmpassword">
                                <i class="fa fa-save"></i> Save 
                            </button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/accountCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
