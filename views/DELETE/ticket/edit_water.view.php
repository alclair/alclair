<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<style class="text/css">
    .modal.modal-wide .modal-dialog {
        width: 90%;
    }
    .modal-wide .modal-body {
        overflow-y: auto;
    }
    .modal.modal-medium .modal-dialog {
        width: 80%;
    }
    .modal-medium .modal-body {
        overflow-y: auto;
    }
    .red-border{
        border:1px solid #f00;
    }
    .zero-border{
        border:1px solid #000;
    }
    .active-button{
        border-left:4px solid #999 !important;
        border-top:4px solid #999 !important;
        border-bottom:4px solid #333 !important;
        border-right:4px solid #333 !important;
    }
</style>
<div id="main-container" class="container" ng-controller="ticketEditCtrl_water">

    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index_outgoing">Water Ticket</a> - Edit Ticket</b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Ticket number:</label><br />
                <input type="text" name="ticket_number" ng-model="ticket.ticket_number" value="" class='form-control'>
            </div>
             <div class="form-group col-md-6">
                    <label class="control-label">Producer's site:</label><br />
                    <select class='form-control'  ng-model='ticket.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList">
                        <option value="">Select a disposal well</option>
                    </select>
                </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Barrels delivered:</label><br />
                <div class="input-group">
                    <input type="number" name="barrels_delivered" ng-model="ticket.barrels_delivered" value="" class='form-control' ng-blur="barrels_warning();" disabled>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default">bbl</button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-6">
	         	<label class="control-label">Disposal well:</label><br />
				<input type="text" ng-model="ticket.source_well_name"  uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control">
				<i ng-show="loadingWells" class="glyphicon glyphicon-refresh"></i>
				<span>*search by well name, producer or file number</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Date delivered:</label><br />
                <div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ticket.date_delivered" is-open="opened" datepicker-options="dateOptions"  ng-inputmask="99/99/9999" ng-required="true" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Water type:</label><br />
                <select class='form-control' ng-model='ticket.water_type_id' ng-options="watertype.id as watertype.type for watertype in waterTypeList">
					<option value="">Select a water type</option>
                </select>
            </div>
        </div>
        <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Trucking Company:</label><br />
					<select class='form-control' ng-model='ticket.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
						<option value="">Select a trucking company</option>
					</select>
                </div>       
			<div class="form-group col-md-6">
                <label class="control-label">Barrel Rate:</label><br />
				<div class="input-group">
					<input type="text" name="barrel_rate" ng-model="ticket.barrel_rate" value="" class='form-control'>      
					<span class="input-group-btn">
                    	<button type="button" class="btn btn-default">$/bbl</button>
					</span>
                </div>
        	</div>    
        </div>
         <div class="row">
	            <div class="form-group col-md-6">
                	<label class="control-label">Tank:</label><br />
					<select class='form-control' ng-model='ticket.tank_id' ng-options="tank.id as tank.name for tank in tankList" ng-blur="loadOtherFluidList(ticket.tank_id);" disabled>
						<option value="">Select a tank</option>
					</select>
            	</div>
            	<div class="form-group col-md-6">
					<label class="control-label">Fluid Type:</label><br />
					<select class='form-control' ng-model='ticket.fluid_type_id' ng-options="fluidType.id as fluidType.type for fluidType in fluidTypeList" disabled>
						<option value="">Select a fluid type</option>
					</select>
                </div>	
            </div>
        <div class="row">
			<div class="form-group col-md-6">
                <label class="control-label">Notes:</label><br />
                <textarea type="text" name="notes" ng-model="ticket.notes" value="" class='form-control' rows='2'></textarea>
            </div>
            <!--<div class="form-group col-md-6">
                <label class="control-label">TESTING:</label><br />
                <input type="text" name="notes" value="{{ticket.source_well_id}}" class='form-control'>
            </div>-->
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label" style="font-weight: bold;">Add Document/Image:</label><br />

                <input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />

                <label ng-if="fileList.length == 0">No document</label>
                <div ng-if="fileList.length > 0">
                    <hr />
                    <b>Existing Documents/Images:</b>
                    <table class="table" style="width: 60%;">
                        <tr ng-repeat="file in fileList">
                            <td>
							<span ng-hide="file.filepath.indexOf('.pdf')!=-1">
					<a href="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-lightbox="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}">
					{{file.filepath}}
					</a>
					</span>
					<span ng-show="file.filepath.indexOf('.pdf')>=0">
					<a href="javascript:void(0);" ng-click="OpenWindow(file.filepath)">
					{{file.filepath}}
					</a>
					</span>
							
							</td>
                            <td><a title="Delete document" ng-click="deleteDocument(file.id);"><i class="fa fa-trash"></i></a></td>

                        </tr>
                    </table>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <button type="button" class="btn btn-primary" ng-click="SaveData()">
                    <i class="fa fa-save"></i>Save                            
                               
                </button>
                <a href="<?=$rootScope["RootUrl"]?>/ticket/view_water/<?=$rootScope['Id']?>" style="margin-left:10px;" class="btn btn-default" ng-show="ticket.id!=undefined"><i class="fa fa-search"></i>View Ticket</a>
                <a href="<?=$rootScope["RootUrl"]?>/ticket" style="margin-left:10px;" ng-show="ticket.id==undefined" class="btn btn-default"><i class="fa fa-backward"></i>Back</a>
            </div>
        </div>

    </form>
    
    <div class="modal fade modal-wide" id="SearchWellModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form name="frmNewWell">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Find or Create a Well</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            Search for a well by entering some (or all) of the information requested below. If no well is found, a new, temporary well can be created for future use.
                        <button class="btn btn-default" type="button" ng-click="ClearSearchFilters()">Clear Filters</button>
                        </p>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Producer</label>
                                 <input type="text" ng-model="newwell.operator_name" placeholder="search for an operator" uib-typeahead="operator as operator.name for operator in operator_list| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="OperatorSelected($item.id)" typeahead-editable="false" class="form-control" />
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-md-6" id="divFields">
                                <label>Field Name</label>
                                <select class="form-control" ng-model="newwell.field_name" ng-change="SearchWells()" id="lstFields">
                                    
                                    <option ng-repeat="item in field_list" value="{{item.name}}">{{item.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="divCounty">
                                <label>County</label>
                                <select class="form-control" ng-model="newwell.county_name" ng-change="SearchWells()" id="lstCounty">
                                    
                                    <option ng-repeat="item in county_list" value="{{item.name}}">{{item.name}}</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Township</label>
                                <input type="text" class="form-control" ng-model="newwell.township" />
                                
                            </div>
                            <div class="col-md-4">
                                <label>Range</label>
                                <input type="number" class="form-control" ng-model="newwell.range" name="newwell_range" min="46" max="107" />
                                <div class="alert alert-error" ng-show="frmNewWell.newwell_range.$error.number">Range must be between 46 and 107</div>
                            </div>
                            <div class="col-md-4">
                                <label>Section</label>
                                <input type="number" class="form-control" ng-model="newwell.section" name="newwell_section" min="1" max="36" />
                                <div class="alert alert-error" ng-show="frmNewWell.newwell_section.$error.number">Section can only be between 1 and 36</div>
                            </div>
                        </div>
                        <p>
                            To create a new well, a temporary well name must be entered below.
                        </p>
                        <div class="row">
                            <div class="col-md-12">
                                <label>New Well Name</label>
                                <input type="text" class="form-control" ng-model="newwell.name" required />
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-6" >
                        <div class="row">
                            <div class="col-md-12">
                                 {{searchingwells.length}} matching wells were found.
                            </div>
                        </div>
                        <div style="max-height:460px;overflow-y:auto;border:1px solid #ccc;">
                            <table class="table" id="tblWells" >
                            <thead>
                                <tr><th>Well</th><th>Producer</th><th>File Number</th></tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in searchingwells">
                                    <td><a href="#" ng-click="WellSelected(item)" >{{item.source_well_name}}</a></td>
                                    <td>{{item.source_well_operator_name}}</td>
                                    <td>{{item.source_well_file_number}}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
                <button id="btnCreateWell" type="button" class="btn btn-primary" ng-click="CreateWell()" ng-disabled="!frmNewWell.$valid"><i class="fa fa-plane"></i> Create new well</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
            </div>
        </div>
            </form>
    </div>        
</div>


<div class="modal fade modal-wide" id="divBarrelWarning" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="width:600px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Warning - Barrels Delivered</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-weight: bold;">{{ticket.barrels_delivered}} bbl </span><span>{{warning_message}}</span>
                                <br />
                                <p>Correct the amount and then click the "Change it" button to try again.</p>
                                <br />
                                <div class="input-group">
                                    <input type="number" class="form-control ng-pristine ng-valid ng-touched" ng-model="new_barrel_delivery">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default">bbl</button>
                                        <button type="button" class="btn btn-primary" ng-click="changeit();">Change it</button>
                                    </span>
                                </div>
                                <br />
                                <div style="text-align: center;">
                                    <span style="font-weight: bold; color: red;">OR</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span>If this is the correct amount, click "Keep it"</span>
                        <span>&nbsp;&nbsp;&nbsp;</span>
                        <button type="button" class="btn btn-primary" ng-click="keepit()">Keep it</button>
                    </div>
                </div>
            </div>
        </div>

</div>
<script type="text/javascript">
    window.cfg.county_list = <?=$viewScope["county_list"]?>;  
    window.cfg.field_list = <?=$viewScope["field_list"]?>;  
    window.cfg.operator_list = <?=$viewScope["operator_list"]?>;
</script>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_outgoing.js"></script>


<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>