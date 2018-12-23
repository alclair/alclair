<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>

<div style="margin-top:20px;margin-bottom:20px;">
    <!-- Main Container Starts -->
    <div id="main-container" class="container">
		<div class="row">
			<div class="col-sm-12">
				<form role="form" ng-controller="wellLogAddCtrl">
					<div class="row">
						<div class="col-md-12">
                            <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4);padding-bottom: 15px;margin-bottom: 25px;">
                                <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/welllog/index">All well logs</a> - View Well Log</b>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Well Log was created at:</label>
                                {{welllog.date_created}}
                            </div>

							<div class="form-group col-md-6">
								<label  class="control-label"> Disposal well:</label><br/>			
                                {{welllog.disposal_well_name}}							
							</div>							
							<div class="form-group col-md-6">
								<label  class="control-label"> Log date:</label><br/>								
                                {{welllog.date_logged}}
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of skim tank #1(ft):</label><br/>								
                                {{welllog.level_skim_tank_1_ft}}
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of skim tank #2(ft):</label><br/>								
                                {{welllog.level_skim_tank_2_ft}}
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of oil tank #1(ft):</label><br/>								
                                {{welllog.level_oil_tank_1_ft}}
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of oil tank #2(ft):</label><br/>								
                                {{welllog.level_oil_tank_2_ft}}
							</div>
												
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of oil gun barrel(ft):</label><br/>								
                                <label>{{welllog.level_gun_ft}}</label>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Flowmeter reading(bbl):</label><br/>								
                                {{welllog.flowmeter_barrels}}
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Injection rate(bbl/day):</label><br/>								
                                {{welllog.injection_rate}}
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Injection pressure(psi):</label><br/>								
                                {{welllog.injection_pressure}}
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Oil sold(bbl):</label><br/>								
                                {{welllog.oil_sold_barrels}}
							</div>	
                            <div class="form-group col-md-6">
								<label  class="control-label"> Pipeline #1 Starting Total(bbl):</label><br/>								
                                {{welllog.pipeline1_starting_total}}
							</div>	
							<div class="form-group col-md-6">
								<label  class="control-label"> Pipeline #1 Ending Total(bbl):</label><br/>								
                                {{welllog.pipeline1_ending_total}}
							</div>	
							<div class="form-group col-md-6">
								<label  class="control-label"> Pipeline #2 Starting Total(bbl):</label><br/>								
                                {{welllog.pipeline2_starting_total}}
							</div>	
							<div class="form-group col-md-6">
								<label  class="control-label"> Pipeline #2 Ending Total(bbl):</label><br/>								
                                {{welllog.pipeline2_ending_total}}
							</div>	
							<div class="form-group col-md-6">
								<label  class="control-label"> Notes:</label><br/>								
                                <p>{{welllog.notes}}</p>
							</div>   
                            <div class="form-group col-md-12">				
                                <a class="btn btn-primary" href="<?=$rootScope["RootUrl"]?>/welllog/edit/<?=$rootScope["Id"]?>">
                                    <i class="fa fa-edit"></i>Edit Well Log</a>
                                &nbsp;&nbsp;
                                <a class="btn btn-default" href="<?=$rootScope["RootUrl"]?>/welllog/index">
                                    <i class="fa fa-backward"></i>Back</a>						
							</div>                                                
						</div>                    
					</div><!-- end row -->
				</form>
			</div>
		</div>
	</div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/wellLogCtrl.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
