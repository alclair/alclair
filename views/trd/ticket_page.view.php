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
<div id="main-container" class="container"  ng-controller="TicketPage">

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
			</div>
			
        </div>
      
        <div class="row">
	        <div class="form-group col-md-6">
		        <p  class="img3" style="font-size: large;color: #007FFF">
					<img src="http://otisdev.alclr.co/trd_data2/1591032656-Axis Energy 0531.jpg" alt="Design Image" >
		        </p>
            </div> 
                        
             <div class="form-group col-md-6">
		   		<label class="control-label">Left <span style="color:red">TIP</span> Color:</label><br />
		   		<input type="text" ng-model="traveler.left_tip"  class="form-control"> 
			</div>  
        </div>
       
       
    </form>            
    <br/>

	
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


    

<script type="text/javascript">
    window.cfg.county_list = <?=$viewScope["county_list"]?>;  
    window.cfg.field_list = <?=$viewScope["field_list"]?>;  
    window.cfg.operator_list = <?=$viewScope["operator_list"]?>;
</script>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/TRD_TicketPage.js"></script>
<script>
	</script>


<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>