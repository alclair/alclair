<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
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
<div id="main-container" class="container"  ng-controller="edit_Traveler">

    <form role="form">
        <div class="row">
            <div class="col-md-12"  style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 0px; margin-bottom: 25px;">
                <!--<div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">-->
                <div class="col-md-5">  
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/alclair/orders">ORDER # <span style="color: #FF0000">  {{traveler.order_id}}</span></a> - Edit traveler</b> &nbsp;&nbsp;&nbsp;&nbsp;
                    <!-- LINE BELOW WAS FOR TEXT THAT LINKED TO A NEW PAGE - REQUEST WAS MADE FOR IT TO BE A BUTTON - JAVASCRIPT CODE HAD TO CHANGE TOO-->
                    <!--<b id="qcform" style="font-size: 20px;color:green;cursor: pointer;" >QC FORM</b> -->
                    <button style="font-weight: 600;border-radius: 4px" type="button" class="btn btn-success" ng-click="gotoQC_Form()">
                        <i ></i> &nbsp; QC FORM                         
                    </button>
                </div>
                <div class="col-md-1">  
                	<b style="font-size: 20px;" id="qcform" style="font-size: 20px;color:blue;cursor: pointer;" >Status:</b>  
                </div>
                <div class="col-md-3" style="margin-left:-120px;margin-top:-5px">  
			   		<select class='form-control' ng-model='traveler.order_status_id' ng-options="orderStatus.order_in_manufacturing as orderStatus.status_of_order for orderStatus in OrderStatusList">
			   			<option value="">Select a status</option>
					</select>
					</div>
					<div class="col-md-1">  
						<button ng-if="(manufacturing_screen==0) && (the_user_is == 'Amanda' || the_user_is == 'Marc' || the_user_is == 'admin' || the_user_is == 'Zeeshan')" style="font-weight: 600;border-radius: 4px" type="button" class="btn btn-warning" ng-click="showOnManufacturingScreen()">
                        	<i ></i> &nbsp; URGENT                         
						</button>
						<button ng-if="(manufacturing_screen==1) && (the_user_is == 'Amanda' || the_user_is == 'Marc' || the_user_is == 'admin' || the_user_is == 'Zeeshan')" style="font-weight: 600;border-radius: 4px" type="button" class="btn btn-danger" ng-click="removeFromManufacturingScreen()">
                       		<i ></i> &nbsp; NOT URGENT                         
					   	</button>
					</div>
					<div class="form-group col-sm-3" style="margin-left:20px;padding-top:10px">
						<input type="checkbox" ng-model="traveler.nashville_order" ng-true-value="1" ng-false-value="0" style="width:20px;height:20px;"> &nbsp; <b style="vertical-align:bottom;font-size: 20px" > NASHVILLE ORDER </b>
					</div>
			</div>
			
        </div>
        <div class="row">
           <div class="form-group col-md-3">
		   		<label class="control-label">Designed For:</label><br />
		   		<input type="text" ng-model="traveler.designed_for" placeholder="Designed for?"  class="form-control"> 
			</div>      
			<!--<div class="form-group col-md-3">
		   		<label class="control-label">E-mail:</label><br />
		   		<input type="text" ng-model="traveler.email" class="form-control"> 
			</div>-->
			<div class="form-group col-md-2">
				<label class="control-label">Impression Color:</label><br />                    
				<select class='form-control' ng-model='traveler.impression_color_id' ng-options="impression.id as impression.color for impression in impressionColorList">
					<option value="">Select a color</option>
				</select>
            </div>
            <div class="form-group col-md-2">
				<label class="control-label">Monitor:</label><br />                    
				<select class='form-control' ng-model='traveler.monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Select a monitor</option>
				</select>
            </div>
            <div class="form-group col-md-2">
	            <label class="control-label">Artwork:</label><br />
                <select class='form-control' ng-model='traveler.artwork' ng-options="artworktype.value as artworktype.label for artworktype in ArtworkTypeList">
				</select>
            </div> 
            <div class="form-group col-md-2">
	            <label class="control-label">Cable:</label><br />
                <select class='form-control' ng-model='traveler.cable_color' ng-options="cabletype.value as cabletype.label for cabletype in CableTypeList">
				</select>
            </div>
            

			<!--<div class="form-group col-md-3">
		   		<label class="control-label">Band/Church:</label><br />
		   		<input type="text" ng-model="traveler.band_church"  class="form-control"> 
			</div>-->
		</div>
        <div class="row">
	        <div class="form-group col-md-4">
				<p ng-if="traveler.link_to_design_image!=' '" class="two" style="font-size: large;color: #007FFF"> <b>HOVER TO SEE EARPHONES</b>
					<img src="{{traveler.link_to_design_image}}" alt="Design Image" > <!--style="parent:hover img {display: block}"-->
		        </p>
		        <p ng-if="traveler.link_to_design_image==' '" style="font-size: large;color: #007FFF"> <b>NO EARPHONES TO SEE</b>
		        </p>
            </div> 
                        
             <div class="form-group col-md-2">
		   		<label class="control-label">Left <span style="color:red">TIP</span> Color:</label><br />
		   		<input type="text" ng-model="traveler.left_tip"  class="form-control"> 
			</div>  
			<div class="form-group col-md-2">
		   		<label class="control-label">Right <span style="color:red">TIP</span> Color:</label><br />
		   		<input type="text" ng-model="traveler.right_tip" class="form-control"> 
			</div> 
			<div class="form-group col-md-2">
		   		<label class="control-label">Left <span style="color:red">SHELL</span> Color:</label><br />
		   		<input type="text" ng-model="traveler.left_shell"  class="form-control"> 
			</div>  
			<div class="form-group col-md-2">
		   		<label class="control-label">Right <span style="color:red">SHELL</span> Color:</label><br />
		   		<input type="text" ng-model="traveler.right_shell" class="form-control"> 
			</div> 
        </div>
       
        <div class="row">
			<!--<div class="form-group col-md-2">
				<div ng-if="traveler.model=='Versa'">
					<input type="checkbox" ng-model="traveler.versa" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; VERSA<br />
				</div>
				<div ng-if="traveler.model!='Versa'">
					<input type="checkbox" ng-model="traveler.versa" ng-true-value="1" ng-false-value="0"> &nbsp; VERSA<br />
				</div>
				<div ng-if="traveler.model=='Dual'">
					<input type="checkbox" ng-model="traveler.dual" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; DUAL<br />
				</div>
				<div ng-if="traveler.model!='Dual'">
					<input type="checkbox" ng-model="traveler.dual" ng-true-value="1" ng-false-value="0"> &nbsp; DUAL<br />
				</div>
				<div ng-if="traveler.model=='Dual XB'">
					<input type="checkbox" ng-model="traveler.dual_xb" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; DUAL XB<br />
				</div>
				<div ng-if="traveler.model!='Dual XB'">
					<input type="checkbox" ng-model="traveler.dual_xb" ng-true-value="1" ng-false-value="0"> &nbsp; DUAL XB<br />
				</div>
				<div ng-if="traveler.model=='Reference'">
					<input type="checkbox" ng-model="traveler.reference" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; REFERENCE<br />
				</div>
				<div ng-if="traveler.model!='Reference'">
					<input type="checkbox" ng-model="traveler.reference" ng-true-value="1" ng-false-value="0"> &nbsp; REFERENCE<br />
				</div>
				<div ng-if="traveler.model=='Tour'">
					<input type="checkbox" ng-model="traveler.tour" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; TOUR<br />
				</div>
				<div ng-if="traveler.model!='Tour'">
					<input type="checkbox" ng-model="traveler.tour" ng-true-value="1" ng-false-value="0"> &nbsp; TOUR<br />
				</div>
			</div>

			<div class="form-group col-md-2">
				<div ng-if="traveler.model=='RSM'">
					<input type="checkbox" ng-model="traveler.rsm" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; RSM<br />
				</div>
				<div ng-if="traveler.model!='RSM'">
					<input type="checkbox" ng-model="traveler.rsm" ng-true-value="1" ng-false-value="0"> &nbsp; RSM<br />
				</div>
				<div ng-if="traveler.model=='CMVK'">
					<input type="checkbox" ng-model="traveler.cmvk" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; CMVK<br />
				</div>
				<div ng-if="traveler.model!='CMVK'">
					<input type="checkbox" ng-model="traveler.cmvk" ng-true-value="1" ng-false-value="0"> &nbsp; CMVK<br />
				</div>
				<div ng-if="traveler.model=='Spire'">
					<input type="checkbox" ng-model="traveler.spire" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; SPIRE<br />
				</div>
				<div ng-if="traveler.model!='Spire'">
					<input type="checkbox" ng-model="traveler.spire" ng-true-value="1" ng-false-value="0"> &nbsp; SPIRE<br />
				</div>
				<div ng-if="traveler.model=='Studio4'">
					<input type="checkbox" ng-model="traveler.studio4" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; STUDIO4<br />
				</div>
				<div ng-if="traveler.model!='Studio4'">
					<input type="checkbox" ng-model="traveler.studio4" ng-true-value="1" ng-false-value="0"> &nbsp; STUDIO4<br />
				</div>
				<div ng-if="traveler.model=='Other'">
					<input type="checkbox" ng-model="traveler.other" ng-true-value="1" ng-false-value="0" ng-checked="1"> &nbsp; OTHER<br />
				</div>
				<div ng-if="traveler.model!='Other'">
					<input type="checkbox" ng-model="traveler.other" ng-true-value="1" ng-false-value="0" > &nbsp; OTHER<br />
				</div>
				<input ng-if="traveler.other" type="text" ng-model="traveler.other_is"  class="form-control">
			</div>-->
			
            <div class="form-group col-md-4">
            </div>
            <div class="form-group col-md-2">
		   		<label class="control-label">Left <span style="color:red">ALCLAIR</span> Logo:</label><br />
		   		<input type="text" ng-model="traveler.left_alclair_logo" class="form-control"> 
			</div> 
			<div class="form-group col-md-2">
		   		<label class="control-label">Right <span style="color:red">ALCLAIR</span> Logo:</label><br />
		   		<input type="text" ng-model="traveler.right_alclair_logo" class="form-control"> 
			</div> 
			<div class="form-group col-md-2">
		   		<label class="control-label">Left <span style="color:red">FACEPLATE</span> Color:</label><br />
		   		<input type="text" ng-model="traveler.left_faceplate" class="form-control"> 
			</div>  
			<div class="form-group col-md-2">
		   		<label class="control-label">Right <span style="color:red">FACEPLATE</span> Color:</label><br />
		   		<input type="text" ng-model="traveler.right_faceplate" class="form-control"> 
			</div>
        </div>
        <div class="row">
	        <div class="form-group col-md-3">
				<input type="checkbox" ng-model="traveler.additional_items" ng-true-value="1" ng-false-value="0"> &nbsp; ADD-ONS WITH ORDER<br />
				<input type="checkbox" ng-model="traveler.consult_highrise" ng-true-value="1" ng-false-value="0"> &nbsp; REVIEW HIGHRISE FOR NOTES<br />
				<input type="checkbox" ng-model="traveler.rush_process" ng-true-value="1" ng-false-value="0"> &nbsp; RUSH ORDER<br />
				<input type="checkbox" ng-model="traveler.international" ng-true-value="1" ng-false-value="0"> &nbsp; INTERNATIONAL SHIPMENT<br />
				<input type="checkbox" ng-model="traveler.universals" ng-true-value="1" ng-false-value="0"> &nbsp; UNIVERSALS W/ ORDER<br />
			</div>
			<div class="form-group col-md-3">
				<input type="checkbox" ng-model="traveler.pickup" ng-true-value="1" ng-false-value="0"> &nbsp; CUSTOMER PICKUP<br />
				<input type="checkbox" ng-model="traveler.hearing_protection" ng-true-value="1" ng-false-value="0"> &nbsp; HEARING PROTECTION INCLUDED<br />
				<input type="checkbox" ng-model="traveler.musicians_plugs" ng-true-value="1" ng-false-value="0"> &nbsp; MUSICIAN'S PLUGS INCLUDED<br />
				
				<input ng-if="traveler.musicians_plugs" type="checkbox" ng-model="traveler.musicians_plugs_9db" ng-true-value="1" ng-false-value="0"> &nbsp; 
				<span  ng-if="traveler.musicians_plugs">9 dB</span>
				<input style="margin-left:12px" ng-if="traveler.musicians_plugs" type="checkbox" ng-model="traveler.musicians_plugs_15db" ng-true-value="1" ng-false-value="0"> &nbsp; 
				<span  ng-if="traveler.musicians_plugs">15 dB</span>
				<input style="margin-left:12px" ng-if="traveler.musicians_plugs" type="checkbox" ng-model="traveler.musicians_plugs_25db" ng-true-value="1" ng-false-value="0"> &nbsp; 
				<span  ng-if="traveler.musicians_plugs">25 dB</span>
			</div>
			<div class="form-group col-md-4">
				<!--<label class="control-label">Notes</label><br />-->
					<textarea type="text" name="notes" ng-model="traveler.notes" value="" class='form-control' rows='3' placeholder="NOTES"></textarea>
            </div>
            <div class="form-group col-md-2">
	            <label class="control-label">Order Type:</label><br />
                <select class='form-control' ng-model='traveler.customer_type' ng-options="customertype.value as customertype.label for customertype in CustomerTypeList">
				</select>
            </div>
        </div>
        <div class="row">
			<div class="form-group col-md-2">
		        <div class="text-left">
			        <br/>
					<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="Save()">
                        <i class="fa fa-save"></i> &nbsp; UPDATE                            
                    </button>
		        </div>
			</div> 
			<div class="form-group col-md-1">
		        <div class="text-left">
			        <br/>
					<button style="font-weight: 600; margin-left:-60px" type="button" class="btn btn-danger" ng-click="Print_Traveler(traveler.id)">
                        <i class="fa fa-print"></i> &nbsp; TRAVELER                          
                    </button>
		        </div>
			</div> 
			<div class="form-group col-md-3">
				<label ng-if="traveler.hearing_protection == 1" class="control-label" style="font-size: large;color: #007FFF">HEARING PROTECTION COLOR</label><br />
				<select ng-if="traveler.hearing_protection == 1" class='form-control' ng-model='traveler.hearing_protection_color' ng-options="color.value as color.label for color in HEARING_PROTECTION_COLORS">
				</select>
			</div>
			<div class="form-group col-md-2">
				<div class="text-left">
					<label class="control-label" style="font-size: large;color: #007FFF">ORDERED DATE</label><br />
		 		</div>
       			<div class="input-group">
                   	<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="traveler.date" is-open="openedImpressions" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
					<span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openImpressions($event)"><i class="fa fa-calendar"></i></button>
					</span>
                </div>			
            </div>
			<div class="form-group col-md-2">
				<div class="text-left">
					<label class="control-label" style="font-size: large;color: #007FFF">IMPRESSIONS REC'D</label><br />
		 		</div>
       			<div class="input-group">
                   	<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="traveler.received_date" is-open="openedReceived" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
					<span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openReceived($event)"><i class="fa fa-calendar"></i></button>
					</span>
                </div>			
            </div>
			<div class="form-group col-md-2">
				<div class="text-left">
					<label class="control-label" style="font-size: large;color: #007FFF">ESTIMATED SHIP</label><br />
		 		</div>
       			<div class="input-group">
                   	<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="traveler.estimated_ship_date" is-open="openedShip" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" /> <!--ng-disabled="the_user_is != 'Amanda' && the_user_is != 'Phil' && the_user_is != 'Grace'"-->  
					<span class="input-group-btn" > <!--ng-if="the_user_is == 'Amanda' || the_user_is == 'Phil' || the_user_is == 'Grace'"-->
                        <button type="button" class="btn btn-default" ng-click="openShip($event)"><i class="fa fa-calendar"></i></button>
					</span>
                </div>			
                <!--<input style="margin-left:20px" type="checkbox" ng-model="traveler.override" ng-true-value="1" ng-false-value="0"> &nbsp; OVERRIDE<br />-->
            </div>
        </div>
        <div class="row" ng-if="traveler.order_status_id == 12">
			<div class="form-group col-md-2">
		        <div class="text-left">
			        <br/>
					<button style="font-weight: 600; margin-left:40px" type="button" class="btn btn-success" ng-click="CreateRMA()">
                        <i class="fa fa-wrench"></i> &nbsp; CREATE RMA                           
                    </button>
		        </div>
			</div>
        </div>
    </form>            
    <br/>

		<center><h1><b>ORDER STATUS</b></h1></center>
        <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Date</th>
					<th style="text-align:center;">Status</th>
					<th style="text-align:center;">Person</th>
					<th style="text-align:center;">Notes  <span style="color:yellow">(Click to edit)</span></th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='(key, log_entry) in logList'>
					<td style="text-align:center;">{{log_entry.date_to_show_date}}  &nbsp;&nbsp; {{log_entry.date_to_show_hours}} </a></td>
					<td style="text-align:center;">{{log_entry.status_of_order}}</a></td>
					<td style="text-align:center;">{{log_entry.first_name}} &nbsp; {{log_entry.last_name}}</a></td>
					<td style="text-align:center;" ng-click="EditNotes(key, log_entry.order_id)">{{log_entry.notes}} </td>
				</tr>					
			</tbody>
		</table>
		<br/>
		<center><h1><b>CHANGE LOG</b></h1></center>
		 <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Date</th>
					<th style="text-align:center;">Person</th>
					<th style="text-align:center;">Change </th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='(key, Changelog_entry) in ChangelogList'>
					<td style="text-align:center;">{{Changelog_entry.date_to_show_date}}  &nbsp;&nbsp; {{Changelog_entry.date_to_show_hours}} </a></td>
					<td style="text-align:center;">{{Changelog_entry.first_name}} &nbsp; {{Changelog_entry.last_name}}</a></td>
					<td style="text-align:center;">{{Changelog_entry.change}} </td>
				</tr>					
			</tbody>
		</table>


    <!--Add Popup Window-->
    <div class="modal fade modal-medium" id="modalEditNotes" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmEditNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><b>Edit <span style="color:red;" > {{editNotes.status_of_order}} </span>Notes</b></h4>
                    </div>
                    <div class="modal-body">
                    		<div class="row">
                            <div class="form-group col-md-12">
                                <label class="control-label"><b>Notes:</b></label><br />
									<textarea type="text" value="" ng-model="editNotes.notes" class='form-control' rows='6'></textarea>

                            </div>                  
                        </div>
                		</div>
                		<div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="SaveNotes(editNotes.the_id)" ng-disabled="!frmEditNotes.$valid">SUBMIT</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">EXIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/EditTraveler.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>