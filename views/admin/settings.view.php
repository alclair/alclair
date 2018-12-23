<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>
<div ng-controller="adminSettingsCtrl" class="container">
	<div class="row well">
		<div class="col-md-12">
			<label>Site Name: </label>
			<input type="text" class="form-control" ng-model="vm.site_name" />
		</div>
	</div>
	<div class="row well">
		<div class="col-md-12">
			<label>Minimum Barrel Warning: </label>
			<input type="number" class="form-control" ng-model="vm.minimum_barrel_warning" />
		</div>
	</div>
	<div class="row well">
		<div class="col-md-12">
			<label>Maximun Barrel Warning: </label>
			<input type="number" class="form-control" ng-model="vm.maximum_barrel_warning" />
		</div>
	</div>
	<?php
		if ( $rootScope["SWDCustomer"] != "trd" || $rootScope["SWDCustomer"] != "wwl"  ) {
	?>
	<div class="row well">
		<div class="col-md-4">
			<div class="text-center">
				<label>Require an image with every ticket?: &nbsp</label><br />
				<div class="btn-group" data-toggle="buttons">						
					<label ng-if="vm.test3==1" class="btn btn-tyler_2 center-block active" ng-model="vm.image_with_ticket" ng-click="vm.image_with_ticket=1" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.test3==1"class="btn btn-tyler_2 center-block" ng-model="vm.image_with_ticket" ng-click="vm.image_with_ticket=0" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.test3==0" class="btn btn-tyler_2 center-block" ng-model="vm.image_with_ticket" ng-click="vm.image_with_ticket=1" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.test3==0"class="btn btn-tyler_2 center-block active" ng-model="vm.image_with_ticket" ng-click="vm.image_with_ticket=0" ng-value="0">
						<input type="radio" value="No">No</label>
				</div>		
			</div>
		</div>
	
		<div class="col-md-4">
			<div class="text-center">
				<label>Allow duplicate tickets?: &nbsp</label><br />
				<div class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.test4==1" class="btn btn-tyler_2 center-block active" ng-model="vm.allow_duplicate_tickets" ng-click="vm.allow_duplicate_tickets=1" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.test4==1"class="btn btn-tyler_2 center-block" ng-model="vm.allow_duplicate_tickets" ng-click="vm.allow_duplicate_tickets=0" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.test4==0" class="btn btn-tyler_2 center-block" ng-model="vm.allow_duplicate_tickets" ng-click="vm.allow_duplicate_tickets=1" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.test4==0"class="btn btn-tyler_2 center-block active" ng-model="vm.allow_duplicate_tickets" ng-click="vm.allow_duplicate_tickets=0" ng-value="0">
						<input type="radio" value="No">No</label>
				</div>
			</div>
		</div>
	</div>
	
			
	<?php } ?>
	<div class="row">
		<div class="col-md-12">
			<button type="button" ng-click="SaveData()" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
		</div>
	</div>
</div>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminSettingsCtrl.js"></script> <!-- testing -->
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>