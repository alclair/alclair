<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>
<div ng-controller="adminDefaultRateCtrl" style="margin-top: 20px; margin-bottom: 20px;" ng-show="entityName!=undefined">
    <!-- Main Container Starts -->
    <div id="main-container" class="container">
		<div class="row">
			<div class="col-md-6">
				<table class="table">
					<thead>
						<tr><th>Disposal Well</th><th>Water Type</th><th>Default Rate</th><th></th></tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in defaultRates">
							<td>{{item.disposal_well_name}}</td>
							<td>{{item.water_type_name}}</td>
							<td>{{item.barrel_rate}}</td>
							<td><button type="button" class="btn btn-danger btn-sm" ng-click="deleteRecord(item.id)"><i class="fa fa-trash-o"></i> Delete</button></td>
						</tr>
					</tbody>
				</table>
			</div> 
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Define Default Rate</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<label>Disposal Well:</label>
								<select class="form-control" ng-model="recordEdit.disposal_well_id" >
								<option ng-repeat="item in disposal_well_list"  value="{{item.id}}">{{item.common_name}}</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label>Water Type:</label>
								<select  class="form-control" ng-model="recordEdit.water_type_id" >
								<option ng-repeat="item in water_type_list" value="{{item.id}}">{{item.type}}</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label>Default Rate:</label>
								<input type="text" class="form-control" ng-model="recordEdit.barrel_rate" class="form-control" />
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-primary" ng-click="updateRecordEdit()"><i class="fa fa-save"></i> Save</button>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

   window.cfg.water_type_list = <?=$viewScope["water_type_list"]?>; 
    
    window.cfg.disposal_well_list = <?=$viewScope["disposal_well_list"]?>;  
   
</script>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminDefaultRateCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>