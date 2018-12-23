<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>

<div ng-controller="waterworksCtrl" style="margin-top:20px;margin-bottom:20px;">
    <!-- Main Container Starts -->
    <div id="main-container" class="container">
		<div class="row">
			<div class="col-sm-6">
				<b style='font-size:20px;'>Site administration</b>
				
				<!--authentication-->
				<div class="panel panel-success" style="margin-top: 10px;">
                    <div class="panel-heading">
						<h3 class="panel-title" style="font-weight: bold;">Authentication and Authorization</h3>                                
                    </div>
                    <div class="panel-body">
						<a href='<?=$rootScope['RootUrl']?>/admin/groups'>Groups</a>
						<br/>
						<a href='<?=$rootScope['RootUrl']?>/admin/users'>Users</a>
                    </div>
                </div>
				
				<!--sites-->
				<div class="panel panel-success" style="margin-top: 10px;">
                    <div class="panel-heading">
						<h3 class="panel-title" style="font-weight: bold;">Sites</h3>                                
                    </div>
                    <div class="panel-body">
						<a href='<?=$rootScope['RootUrl']?>/admin/sites'>Sites</a>
                    </div>
                </div>

				<!--ticket tracker-->
				<div class="panel panel-success" style="margin-top: 10px;">
                    <div class="panel-heading">
						<h3 class="panel-title" style="font-weight: bold;">Ticket Tracker</h3>                                
                    </div>
                    <div class="panel-body">
						<a href='<?=$rootScope['RootUrl']?>/admin/disposalwells'>Disposal wells</a>
						<br/>
						<a href='<?=$rootScope['RootUrl']?>/admin/tickets'>Tickets</a>
						<br/>
						<a href='<?=$rootScope['RootUrl']?>/admin/truckingcompanies'>Trucking companies</a>
                    </div>
                </div>				
				
			</div>
		</div>
	</div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/waterworksCtrl.js">
</script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
