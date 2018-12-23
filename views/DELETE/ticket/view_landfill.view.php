<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ticketViewCtrl">
    <!-- Main Container Starts -->

    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index_outgoing">All outgoing tickets</a> - View Ticket</b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label class="control-label">Ticket was created at:</label>
                {{ticket.date_created}}
            </div>
			<div class="form-group col-md-4">
                <label class="control-label">Test Date:</label>
                {{ticket.date_delivered}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label class="control-label">Test Report #:</label><br />
                {{ticket.ticket_number}}
            </div>
            <div class="form-group col-md-4">
				<label class="control-label">Radium 226:</label><br />
                {{ticket.radium_226}}
            </div>
			<div class="form-group col-md-4">
				<label class="control-label">Radium 228:</label><br />
                {{ticket.radium_228}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label class="control-label">Bill of Lading #:</label><br />
               {{ticket.bill_lading_number}}
			</div>
			<div class="form-group col-md-3">
                <label class="control-label">Ship Date:</label><br />
                {{ticket.ship_date}}
            </div>
           <div class="form-group col-md-3">
                <label class="control-label">Trucking Company:</label><br />
                {{ticket.trucking_company}}
            </div>
			<div class="form-group col-md-3">
                <label class="control-label">Landfill Disposal Site:</label><br />
                {{ticket.landfill_site}}
            </div>
         </div>
         <div class="row">    
			 <div class="form-group col-md-3">
                <label class="control-label">Tank:</label><br />
                {{ticket.tank}}
            </div>
			<div class="form-group col-md-3">
                <label class="control-label">Fluid Type:</label><br />
                {{ticket.tank}}
            </div>
			<div class="form-group col-md-3">
                <label class="control-label">Barrels Delivered:</label><br />
                {{ticket.barrels_delivered}}
            </div>
			<div class="form-group col-md-3">
                <label class="control-label">Yards:</label><br />
                {{ticket.tank}}
            </div>
         </div>
         <div class="row">    
			 <div class="form-group col-md-3">
                <label class="control-label">Landfill Ticket #:</label><br />
                {{ticket.landfill_ticket_number}}
            </div>
			<div class="form-group col-md-3">
                <label class="control-label">Tare Weight:</label><br />
                {{ticket.tare_weight}} lbs.
            </div>
			<div class="form-group col-md-3">
                <label class="control-label">Loaded Weight:</label><br />
                {{ticket.loaded_weight}} lbs.
            </div>
			<div class="form-group col-md-3">
                <label class="control-label">Tons:</label><br />
                {{ticket.tons}} tons
            </div>
         </div>
         <div class="row">
			 <div class="form-group col-md-6">
                <label class="control-label">Landfill Disposal Fees:</label><br />
                ${{ticket.freight_fee -- ticket.tipping_fee}}
            </div>
			<div class="form-group col-md-6">
                <label class="control-label">Total $:</label><br />
                <label>${{ticket.total_dollars}}</label>
            </div>
         </div>
        
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Notes:</label><br />
                {{ticket.notes}}
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
    </form>
        <!-- end row -->

    <div class="row">
        <div class="col-md-12">
	            <?php
                    if($_SESSION["IsAdmin"] == 1) {
	              ?>
            <a class="btn btn-primary" href="<?=$rootScope["RootUrl"]?>/ticket/edit_landfill/<?=$rootScope["Id"]?>"><i class="fa fa-edit"></i>Edit Ticket</a>
            &nbsp;&nbsp;
            <?php
	            }
	         ?>
            <a class="btn btn-primary" ng-click="exportPDF('Solids');"><i class="fa fa-file-pdf-o"></i>Export PDF</a>
            &nbsp;&nbsp;
            <a class="btn btn-default" href="<?=$rootScope["RootUrl"]?>/ticket/index_outgoing"><i class="fa fa-backward"></i>Back</a>
        </div>
    </div>
</div>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_outgoing.js"></script>
 	
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>