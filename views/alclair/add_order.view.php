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
<div id="main-container" class="container" ng-controller="add_Order">

    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px; ">
	                <div class="form-group col-md-3">
                    	<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/alclair/orders">Add Order</a> </b>
                	</div>
					<div class="form-group col-md-3">
						<input type="checkbox" ng-model="traveler.nashville_order" ng-true-value="1" ng-false-value="0" style="width:20px;height:20px;"> &nbsp; <b style="vertical-align:bottom;font-size: 20px "> NASHVILLE ORDER </b><br />
            		</div>
            		<div class="form-group col-md-3">
						<input type="checkbox" ng-model="traveler.fit_adjustment" ng-true-value="1" ng-false-value="0" style="width:20px;height:20px;"> &nbsp; <b style="vertical-align:bottom;font-size: 20px "> FIT ADJUSTMENT </b><br />
            		</div>
            		<br/>
        		</div>
            </div>
        </div>
        <div class="row">
			<div class="form-group col-md-3"
				<label class="control-label col-md-1" style="font-size: large;color: #FF0000; "><b>ORDER ID</b></label>
				<b><input style="font-size: large; color: #007FFF; " type="text" ng-model="traveler.order_id" placeholder=""  class="form-control"> </b>
					
					<!-- LABEL AND INPUT NEXT TO ONE ANOTHER -->
					<!--<label class="control-label col-md-1" style="font-size: 17px;color: #FF0000; float:left !important;">ORDER #</label>
					<div class="col-sm-2">
						<b><input style="font-size: 17px; color: #007FFF; " type="text" ng-model="traveler.order_id" placeholder="Order # is?"  class="form-control"> </b>
					</div>-->
			</div>
			<div class="form-group col-md-2">
	            <label class="control-label">Order Type:</label><br />
                <select class='form-control' ng-model='traveler.customer_type' ng-options="customertype.value as customertype.label for customertype in CustomerTypeList">
				</select>
            </div>
        </div>
        <div class="row">
           <div class="form-group col-md-3">
		   		<label class="control-label">Designed For:</label><br />
		   		<input type="text" ng-model="traveler.designed_for" placeholder=""  class="form-control"> 
			</div>      
			<!--<div class="form-group col-md-3">
		   		<label class="control-label">Email:</label><br />
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
				<select class='form-control' ng-model='traveler.model' ng-options="IEM.name as IEM.name for IEM in monitorList">
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
				    
		</div>
        <div class="row">
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

            <div class="form-group col-md-4">
				<label class="control-label">Notes</label><br />
					<textarea type="text" name="notes" ng-model="traveler.notes" value="" class='form-control' rows='3'></textarea>
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
			
            			
            
            <div class="form-group col-md-2">
		   		<label class="control-label">Left <span style="color:red">ALCLAIR</span> Logo:</label><br />
		   		<input type="text" ng-model="traveler.left_alclair_logo"  class="form-control"> 
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
			<div class="form-group col-md-3">
		   		<label class="control-label">Email:</label><br />
		   		<input type="text" ng-model="traveler.email" placeholder=""  class="form-control"> 
			</div>      
			
        </div>
        <div class="row">
	        <div class="form-group col-md-3">
				<input type="checkbox" ng-model="traveler.additional_items" ng-true-value="1" ng-false-value="0"> &nbsp; ADD-ONS WITH ORDER<br />
				<!--<input type="checkbox" ng-model="traveler.consult_highrise" ng-true-value="1" ng-false-value="0"> &nbsp; REVIEW HIGHRISE FOR NOTES<br />-->
				<input type="checkbox" ng-model="traveler.rush_process" ng-true-value="1" ng-false-value="0"> &nbsp; RUSH ORDER<br />
				<input type="checkbox" ng-model="traveler.international" ng-true-value="1" ng-false-value="0"> &nbsp; INTERNATIONAL SHIPMENT<br />
				<input type="checkbox" ng-model="traveler.bite_block_not_used" ng-true-value="1" ng-false-value="0"> &nbsp; BITE BLOCK NOT USED<br />
			</div>
			<div class="form-group col-md-3">
				<input type="checkbox" ng-model="traveler.pickup" ng-true-value="1" ng-false-value="0"> &nbsp; CUSTOMER PICKUP<br />
				<input type="checkbox" ng-model="traveler.hearing_protection" ng-true-value="1" ng-false-value="0"> &nbsp; HEARING PROTECTION INCLUDED<br />
				<span ng-if="traveler.model == 'Musicians Plugs'">
					<input type="checkbox" ng-model="traveler.musicians_plugs" ng-true-value="1" ng-false-value="0"> &nbsp; MUSICIAN'S PLUGS INCLUDED<br />
					
					<input ng-if="traveler.musicians_plugs" type="checkbox" ng-model="traveler.musicians_plugs_9db" ng-true-value="1" ng-false-value="0"> &nbsp; 
					<span  ng-if="traveler.musicians_plugs">10 dB</span>
					
					<input style="margin-left:12px" ng-if="traveler.musicians_plugs" type="checkbox" ng-model="traveler.musicians_plugs_15db" ng-true-value="1" ng-false-value="0"> &nbsp; 
					<span  ng-if="traveler.musicians_plugs">15 dB</span>
					
					<input style="margin-left:12px" ng-if="traveler.musicians_plugs" type="checkbox" ng-model="traveler.musicians_plugs_25db" ng-true-value="1" ng-false-value="0"> &nbsp; 
					<span  ng-if="traveler.musicians_plugs">25 dB</span>
				</span>
			</div>
			<div class="form-group col-md-3">	
				<span ng-if="traveler.model == 'Full Ear HP'">
					<label class="control-label">Full Ear HP Filter</label><br />
					<select class='form-control' ng-model='traveler.full_ear_filter' ng-options="filter.value as filter.label for filter in FullEarFilterList" style="height:40px"><option value="" disabled selected>Pick a Filter</option>
					</select>
				</span>
				<span ng-if="traveler.model == 'Canal Fit HP'">
					<label class="control-label">Canal Fit HP Filter</label><br />
					<select class='form-control' ng-model='traveler.canal_fit_filter' ng-options="filter.value as filter.label for filter in CanalFitFilterList" style="height:40px"><option value="" disabled selected>Pick a Filter</option>
					</select>
				</span>
				<br />

				
				
			</div>
        </div>
        <div class="row">
			<div class="form-group col-md-2">
				<div class="text-left">
			        <br/>
					<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="Save()">
                        <i class="fa fa-save"></i> &nbsp; SAVE                            
                    </button>
				</div>
			</div>
		    <div class="form-group col-md-1">
			    <div class="text-left">
			        <br/>
					<button style="font-weight: 600; margin-left:-60px" type="button" class="btn btn-danger" ng-click="Print_Traveler()">
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
                   	<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="traveler.estimated_ship_date" is-open="openedShip" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
					<span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openShip($event)"><i class="fa fa-calendar"></i></button>
					</span>
                </div>			
            </div>
        </div>
            
    </form>    
</div>

<!--<script type="text/javascript">
    window.cfg.county_list = <?=$viewScope["county_list"]?>;  
    window.cfg.field_list = <?=$viewScope["field_list"]?>;  
    window.cfg.operator_list = <?=$viewScope["operator_list"]?>;
</script>-->

<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>