<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ticketViewCtrl_water">
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
            <div class="form-group col-md-12">
                <label class="control-label">Ticket was created at:</label>
                {{ticket.date_created}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Ticket number:</label><br />
                {{ticket.ticket_number}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Source well:</label><br />
                {{ticket.disposal_well_name}}
            </div>
        </div>
        <div class="row">
	        <div class="form-group col-md-6">
                <label class="control-label">Tank:</label><br />
               {{ticket.tank_name}}
            </div>
			<div class="form-group col-md-6">
                <label class="control-label">Fluid Type:</label><br />
               {{ticket.fluid_type}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Barrels delivered:</label><br />
               {{ticket.barrels_delivered}}
            </div>
            <div class="form-group col-md-6">
				<label class="control-label">Barrel Rate:</label><br />
                ${{ticket.barrel_rate}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Date delivered:</label><br />
                {{ticket.date_delivered}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Water type:</label><br />
                {{ticket.water_type_name}}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Disposal well Name:</label><br />
                {{ticket.source_well_name}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Disposal well Operator:</label><br />
                {{ticket.source_well_operator_name}}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Disposal Well File Number:</label><br />
                {{ticket.source_well_file_number}}
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Trucking company:</label><br />
                {{ticket.company_name}}
            </div>
        </div>
        
        <div class="row">
	         <div class="form-group col-md-6">
                <label class="control-label">Total Cost:</label><br />
                ${{ticket.barrels_delivered * ticket.barrel_rate}}
            </div>
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

    <div class="row">
        <div class="col-md-12">
	            <?php
                    if($_SESSION["IsAdmin"] == 1) {
	              ?>
            <a class="btn btn-primary" href="<?=$rootScope["RootUrl"]?>/ticket/edit_water/<?=$rootScope["Id"]?>"><i class="fa fa-edit"></i>Edit Ticket</a>
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

<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_outgoing.js"></script>

 	
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>