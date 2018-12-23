<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminOperatorAddCtrl" style="margin-top:20px;margin-bottom:20px;">
    <!-- Main Container Starts -->
    <div id="main-container" class="container">
		<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> >> <a href="<?=$rootScope['RootUrl']?>/admin/operators">Operators</a> >> Add Operator</b>
       <form role="form">

     
        <div class="row">

            <div class="form-group col-md-6">

                <label class="control-label">Operator Name:</label><br />

                <input type="text" id="operator_name"   value="" ng-model="vm.name" class='form-control'>

            </div>
         </div>
         <div class="row">

            <div class="form-group col-md-6">

                <button type="button" class="btn btn-primary" ng-click="SubmitData()">
                    <i class="fa fa-save"></i>Submit
                </button>
              

                <a href="<?=$rootScope["RootUrl"]?>/admin/operators" style="margin-left:10px;"  class="btn btn-default"><i class="fa fa-backward"></i>Back</a>

            </div>

        </div>

    </form>

		
	</div>
</div>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
