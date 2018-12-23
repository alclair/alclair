<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<div class="container" ng-controller="cronCtrl">
	<h3>Manage Cron Tasks</h3>
	<div class="row">
		<div class="well col-md-4" style="border:1px solid #ccc;">
			<label>Daily Email Recipients:</label>
			<label><i>Scheduled to run at 6am every day.</i></label>
			<textarea class="form-control" style="min-height:200px;" ng-model="vm.daily_emails"></textarea>
			<br>
			<button class="btn btn-primary" ng-click="SendEmailNow('daily')"><i class="fa fa-send"></i> Send Now</button>
		</div>
		<div class="well col-md-4" style="border:1px solid #ccc;">
			<label>Weekly Email Recipients:</label>
			<label><i>Scheduled to run at 6:15am every Monday.</i></label>
			<textarea class="form-control" style="min-height:200px;" ng-model="vm.weekly_emails"></textarea>
			<br>
			<button class="btn btn-primary" ng-click="SendEmailNow('weekly')"><i class="fa fa-send"></i> Send Now</button>
			
		</div>
		<div class="well col-md-4" style="border:1px solid #ccc;">
			<label>Monthly Email Recipients:</label>
			<label><i>Scheduled to run at 6:30am first day of every month.</i></label>
			<textarea class="form-control" style="min-height:200px;" ng-model="vm.monthly_emails"></textarea>
			
			<br>
			<button class="btn btn-primary" ng-click="SendEmailNow('monthly')"><i class="fa fa-send"></i> Send Now</button>
		</div>
	</div>
    <div class="alert alert-info"><i>You can put emails in separate lines or use ,; to separate multiple emails.</i></div>
	<div class="row">
		<div class="col-md-8">
			<button class="btn btn-primary" ng-click="SaveData()"><i class="fa fa-save"></i> Save All Settings</button>
		</div>
		<?php
			if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "test" || $rootScope["SWDCustomer"] == "horizon" ) {
				//echo $rootScope["RootPath"]."data/export/".$list["data"];
		?>
		<div class="well col-md-4" style="border:1px solid #ccc;">
			<label>Daily Disposal Total from MBI Energy Services:</label>
			<label><i>Scheduled to run at 6am every day.</i></label>
			<textarea class="form-control" style="min-height:160px;" ng-model="vm.daily_disposal_total"></textarea>
			<br>
			<button class="btn btn-primary" ng-click="SendEmailNow('daily')"><i class="fa fa-send"></i> Send Now</button>
		</div>
		<?php
	    	} 
		?>
	</div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/cronCtrl.js">
</script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>