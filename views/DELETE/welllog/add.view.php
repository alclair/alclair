<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>

<div style="margin-top:20px;margin-bottom:20px;">
    <!-- Main Container Starts -->
    <div id="main-container" class="container" ng-controller="wellLogAddCtrl">
		<div class="row">
			<div class="col-sm-12">
				<form  name="frmEdit" id="frmEdit">
					<div class="row">
						<div class="col-md-12">
                            <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4);padding-bottom: 15px;margin-bottom: 25px;">
                                <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/welllog/index">All well logs</a> - {{vm.title}}</b>
                            </div>

							<div class="form-group col-md-6">
								<label  class="control-label"> Disposal well*:</label><br/>
								<select class='form-control' required ng-model='welllog.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList" ng-change="getLastLog()">									                         
								</select>								
							</div>							
							<div class="form-group col-md-6">
								<label  class="control-label"> Log date*:</label><br/>								
                                <div class="input-group">
                                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="welllog.date_logged" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of skim tank #1:</label><br/>								
                                <div class="input-group">
                                    <input type="text" name="level_skim_tank_1_ft"  ng-model="welllog.level_skim_tank_1_ft" value="" class='form-control'> 
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">ft</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of skim tank #2:</label><br/>								
                                <div class="input-group">
                                    <input type="text" name="level_skim_tank_2_ft"  ng-model="welllog.level_skim_tank_2_ft" value="" class='form-control'> 
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">ft</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of oil tank #1:</label><br/>								
                                <div class="input-group">
                                    <input type="text" name="level_oil_tank_1_ft"  ng-model="welllog.level_oil_tank_1_ft" value="" class='form-control'> 
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">ft</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of oil tank #2:</label><br/>								
                                <div class="input-group">
                                    <input type="text" name="level_oil_tank_2_ft"  ng-model="welllog.level_oil_tank_2_ft" value="" class='form-control'> 
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">ft</button>
                                    </span>
                                </div>
							</div>
													
							<div class="form-group col-md-6">
								<label  class="control-label"> Level of oil gun barrel:</label><br/>								
                                <div class="input-group">
                                    <input type="text" name="level_gun_ft"  ng-model="welllog.level_gun_ft" value="" class='form-control'> 
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">ft</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Flowmeter reading:</label><br/>								
                                <div class="input-group">
                                    <input type="text" name="flowmeter_barrels"  ng-model="welllog.flowmeter_barrels" value="" class='form-control'> 
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">bbl</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Injection rate:</label><br/>								
                                <div class="input-group">
                                    <input type="number" name="injection_rate"  ng-model="welllog.injection_rate" value="" class='form-control'> 
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">bbl/day</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Injection pressure:</label><br/>								
                                <div class="input-group">
                                    <input type="number" name="injection_pressure"  ng-model="welllog.injection_pressure" value="" class='form-control'> 
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">psi</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Oil sold:</label><br/>								
                                <div class="input-group">
                                    <input type="number" name="oil_sold_barrels"  ng-model="welllog.oil_sold_barrels" value="" class='form-control'>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">bbl</button>
                                    </span>
                                </div>
							</div>	
							<div class="form-group col-md-6">
								<label  class="control-label"> Pipeline #1 Starting Total:</label><br/>								
                                <div class="input-group">
                                    <input type="number" name="pipeline1_starting_total"  ng-model="welllog.pipeline1_starting_total" value="" class='form-control'>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">bbl</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Pipeline #1 Ending Total:</label><br/>								
                                <div class="input-group">
                                    <input type="number" name="pipeline1_ending_total"  ng-model="welllog.pipeline1_ending_total" value="" class='form-control'>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">bbl</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Pipeline #2 Starting Total:</label><br/>								
                                <div class="input-group">
                                    <input type="number" name="pipeline2_starting_total"  ng-model="welllog.pipeline2_starting_total" value="" class='form-control'>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">bbl</button>
                                    </span>
                                </div>
							</div>
							<div class="form-group col-md-6">
								<label  class="control-label"> Pipeline #2 Ending Total:</label><br/>								
                                <div class="input-group">
                                    <input type="number" name="pipeline2_ending_total"  ng-model="welllog.pipeline2_ending_total" value="" class='form-control'>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" style="background-color: #333;">bbl</button>
                                    </span>
                                </div>
							</div>							
                           						
							<div class="form-group col-md-6">
								<label  class="control-label"> Notes:</label><br/>
								<textarea type="text" name="notes"  ng-model="welllog.notes" value="" class='form-control' rows='5'></textarea>
							</div> 
							<div class="form-group col-md-6">
							    <button type="button" class="btn btn-primary" ng-disabled="welllog.disposal_well_id==''" ng-click="add()" ng-if="action=='add'">
                                    <i class="fa fa-save"></i> Save 
                                </button>	
                                
                                <button type="button" class="btn btn-primary" ng-click="edit()" ng-if="action=='edit'">
                                    <i class="fa fa-save"></i> Update                            
                                </button>                                
                                <a href="<?=$rootScope["RootUrl"]?>/welllog/view/<?=$rootScope['Id']?>" style="margin-left:10px;" class="btn btn-default" ng-if="action=='edit'">
                                    <i class="fa fa-search"></i>View Well Log</a>		
                                
                               <a class="btn btn-default" href="<?=$rootScope["RootUrl"]?>/welllog/index" style="margin-left:10px;">
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
