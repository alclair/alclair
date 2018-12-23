<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminUserEditCtrl" style="margin-top:20px;margin-bottom:20px;">
    <!-- Main Container Starts -->
    <div id="main-container" class="container">
		<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> >> <a href="<?=$rootScope['RootUrl']?>/admin/users">Users</a> >> Edit User</b>
		
       <form role="form">

        <div class="row">

            <div class="form-group col-md-6">

                <label class="control-label">User Name:</label><br />

                <input type="text" id="username"   value="" ng-model="vm.username" class='form-control'>

            </div>
         </div>
          <div class="row">

            <div class="form-group col-md-6">

                <label class="control-label">First Name:</label><br />

                <input type="text" id="first_name"   value="" ng-model="vm.first_name" class='form-control'>

            </div>
         </div>
          <div class="row">

            <div class="form-group col-md-6">

                <label class="control-label">Last Name:</label><br />

                <input type="text" id="last_name"   value="" ng-model="vm.last_name" class='form-control'>

            </div>
         </div>
          <div class="row">

            <div class="form-group col-md-6">

                <label class="control-label">Email:</label><br />

                <input type="email" id="email"   value="" ng-model="vm.email" class='form-control'>

            </div>
         </div>
         <div class="row">

            <div class="form-group col-md-6">
								<label  class="control-label"> Password:</label><br/>
								<input type="password" name="password"  ng-model="vm.password" value="" class='form-control' > 
							</div>  
           </div>  
         <div class="row">

            <div class="form-group col-md-6">

                <button type="button" class="btn btn-primary" ng-click="SaveData()">
                    <i class="fa fa-save"></i>Save 
                </button>
              

                <a href="<?=$rootScope["RootUrl"]?>/admin/users" style="margin-left:10px;"  class="btn btn-default"><i class="fa fa-backward"></i>Back</a>

            </div>

        </div>

    </form>

		
	</div>
</div>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
