<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ticketViewCtrl">
    <!-- Main Container Starts -->
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index">All tickets</a> - View Ticket</b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label">Ticket was created at:</label>
                {{ticket.date_created}} by {{ticket.first_name}} {{ticket.last_name}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Ticket number:</label><br />
                {{ticket.ticket_number}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Disposal well:</label><br />
                {{ticket.disposal_well_name}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Barrels delivered:</label><br />
               {{ticket.barrels_delivered}}
				<br>
				<label class="control-label">Barrel Rate:</label><br />
                ${{ticket.barrel_rate}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Water type:</label><br />
                {{ticket.water_type_name}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Date delivered:</label><br />
                {{ticket.date_delivered}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Delivery method:</label><br />
                {{ticket.delivery_method}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Water source type:</label><br />
                <label>{{ticket.water_source_type}}</label>
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Producer's site:</label><br />
                {{ticket.source_well_name}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Producer's site well file #:</label><br />
                {{ticket.source_well_file_number}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Producer:</label><br />
                {{ticket.source_well_operator_name}}
            </div>
        </div>
 <?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>             
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Trucking company:</label><br />
                {{ticket.company_name}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Notes:</label><br />
                {{ticket.notes}}
			</div>
        </div>
	<?php } else { ?>
		<div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Trucking company:</label><br />
                {{ticket.company_name}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Driver's Name:</label><br />
                {{ticket.driver_name}}
            </div>
		</div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Notes:</label><br />
                {{ticket.notes}}
			</div>
        </div>
	<?php } ?>					
						
         
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label">Attached Documents/Images:</label><br />
                <span ng-if="fileList.length == 0">No document</span>
                <div ng-if="fileList.length > 0">
                    <span ng-repeat="file in fileList">
					<span ng-hide="file.filepath.indexOf('.pdf')!=-1">
					<a href="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-lightbox="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-title="{{ticket.ticket_number}}">
					{{file.filepath}}
					</a>
					</span>
					<span ng-show="file.filepath.indexOf('.pdf')>=0">
					<a href="javascript:void(0);" ng-click="OpenWindow(file.filepath)">
					{{file.filepath}}
					</a>
					</span>
					&nbsp;&nbsp;Uploaded at {{file.date_uploaded}}
					<br />
                    </span>
                </div>
            </div>
        </div>
    </form>
        <!-- end row -->

    
    <?php
	    // FORM FOR TRD COMPANY
	    } elseif( $rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl" ) {   
	?>
	<form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index">All tickets</a> - View Ticket</b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Ticket was created at:</label>
                {{ticket.date_created}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Date delivered:</label>
                {{ticket.date_delivered}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">BOL/Ticket #:</label><br />
                {{ticket.ticket_number}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Producer's site:</label><br />
                {{ticket.source_well_name}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Producer (Oil Company):</label><br />
                {{ticket.source_well_operator_name}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Trucking Company:</label><br />
                {{ticket.company_name}}
            </div>
        </div>        
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Barrels delivered:</label><br />
               {{ticket.barrels_delivered}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Fluid Type:</label><br />
                {{ticket.fluid_type}}
            </div>
        </div>
        <div class="row">
	    	<div class="form-group col-md-6">
				<label class="control-label">Barrel Rate:</label><br />
                ${{ticket.barrel_rate}}
            </div>    
            <div class="form-group col-md-6">
				<label class="control-label">Truck Type:</label><br />
                {{ticket.truck_type}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Company Man Name:</label><br />
                {{ticket.company_man_name}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Driver Name:</label><br />
                {{ticket.driver_name}}
            </div>          
        </div>
        <div class="row">
            <div class="form-group col-md-6">
               	<label class="control-label">Company Man Number:</label><br />
            	{{ticket.company_man_number}}
            </div>	      
              <div class="form-group col-md-6">
                <label class="control-label">H2S (Yes/No):</label><br />
                {{ticket.h2s_exists}}
            </div> 
        </div>
        <div class="row">
	    	<div class="form-group col-md-4">
		    	<label class="control-label">AFE/PO (if required):</label><br />
                {{ticket.afepo}}	
	    	</div>
	    	<div class="form-group col-md-4">
		    	<label class="control-label">% Solid:</label><br />
                {{ticket.percent_solid}}	
	    	</div>
	    	<div class="form-group col-md-4">
		    	<label class="control-label">% H2O:</label><br />
                {{ticket.percent_h2o}}
	    	</div>
        </div>    
        <div class="row">
	    	<div class="form-group col-md-4">
		    	<label class="control-label">Rig:</label><br />
                {{ticket.rig_name}}	
	    	</div>
	    	<div class="form-group col-md-4">
		    	<label class="control-label">% Interphase:</label><br />
                {{ticket.percent_interphase}}	
	    	</div>
	    	<div class="form-group col-md-4">
		    	<label class="control-label">% Oil:</label><br />
                {{ticket.percent_oil}} 	
	    	</div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Washout Barrels (Yes/No):</label><br />
                {{ticket.washout}}
            </div>
             <div class="form-group col-md-6">
                <label class="control-label">Picocuries (Yes/No):</label><br />
                {{ticket.picocuries}}
            </div>
        </div>
        <div class="row">
	        <div class="form-group col-md-6">
		    	<label class="control-label">Washout Barrels:</label><br />
	            {{ticket.washout_barrels}}   
	        </div>
	        <div class="form-group col-md-6">
		    	<label class="control-label">TeNORM Picocuries:</label><br />
	            {{ticket.tenorm_picocuries}}   
	        </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Notes:</label><br />
                {{ticket.notes}}
            </div>
			<div class="form-group col-md-6">
                <label class="control-label">Destination Tank:</label><br />
                {{ticket.tank_name}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="control-label">Attached Documents/Images:</label><br />
                <span ng-if="fileList.length == 0">No document</span>
                <div ng-if="fileList.length > 0">
                    <span ng-repeat="file in fileList">
					<span ng-hide="file.filepath.indexOf('.pdf')!=-1">
					<a href="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-lightbox="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-title="{{ticket.ticket_number}}">
					{{file.filepath}}
					</a>
					</span>
					<span ng-show="file.filepath.indexOf('.pdf')>=0">
					<a href="javascript:void(0);" ng-click="OpenWindow(file.filepath)">
					{{file.filepath}}
					</a>
					</span>
					&nbsp;&nbsp;Uploaded at {{file.date_uploaded}}
					<br />
                    </span>
                </div>
            </div>
        </div>

        <!-- end row -->
    </form>
     <?php
 	} 
 ?>	 


    <div class="row">
        <div class="col-md-12">
	            <?php
                    if($_SESSION["IsAdmin"] == 1) {
	              ?>
            <a class="btn btn-primary" href="<?=$rootScope["RootUrl"]?>/ticket/edit/<?=$rootScope["Id"]?>"><i class="fa fa-edit"></i>Edit Ticket</a>
            &nbsp;&nbsp;
            <?php
	            }
	         ?>
            <a class="btn btn-primary" ng-click="exportPDF();"><i class="fa fa-file-pdf-o"></i>Export PDF</a>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="<?=$rootScope["RootUrl"]?>/ticket"><i class="fa fa-backward"></i>Back</a>
        </div>
    </div>
</div>
<!-- -->
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
	<?php
	    } elseif( $rootScope["SWDCustomer"] == "boh" ) {   
	?>		
		<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_BOH.js"></script>
    <?php
	    } elseif( $rootScope["SWDCustomer"] == "trd") {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_TRD.js"></script>
	<?php
	    } elseif( $rootScope["SWDCustomer"] == "wwl") {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_WWL.js"></script>
	<?php
 	} else {
	 ?>
	 		<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
 	<?php
	 	}
 	?>	
 	
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>