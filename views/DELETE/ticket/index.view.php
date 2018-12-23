<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="ticketCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <b style='font-size:20px;'>Disposal Tickets (Total: {{TotalRecords}})</b>
            </div>
            <div class="col-md-8" style="text-align:right;">
                 <nav ng-show="TotalPages > 1" style="margin-top:-20px;text-align:right;">
                     <ul class="pagination">
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(1)" title="Go to page 1">&laquo;&laquo;</a></li>
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(PageIndex-1)" title="Go to preious page">&laquo;</a></li>
                        <li ng-class="{active: pn == PageIndex}" ng-repeat="pn in PageRange"><a href="javascript:void(0);" ng-click="GoToPage(pn)">{{pn}} </a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(PageIndex+1)" title="Go to next page">&raquo;</a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(TotalPages)" title="Go to last page">&raquo;&raquo;</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        
		<div class="row alert" style='background-color:#ddd;'>
            <div class="col-md-2">
                <a type="button" class="btn btn-primary" href="<?=$rootScope['RootUrl']?>/ticket/add">
				<span class="glyphicon glyphicon-plus"></span> Add new ticket
			</a>
            </div>
            	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>    
			<div class="col-md-2">
                <select ng-model="SearchDisposalWell" ng-change="Search()" class="form-control">
                    <option selected value="0">---select site---</option>
                    <option ng-repeat="item in DisposalWells" value="{{item.id}}" ng-selected="item.id==SearchDisposalWell">{{item.common_name}}</option>
                </select>
			</div>
			<?php } else { ?>
				<div class="col-md-2">
		
				</div>
			<?php } ?>
			<div class="col-md-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchStartDate" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>				
			</div>
			<div class="col-md-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchEndDate" is-open="openedEnd" datepicker-options="dateOptions"  ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openEnd($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
			</div>
            <div class="col-md-4">
                    <div class="input-group">
	                    <?php if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) { ?>   
							<input type="text"  class="form-control" placeholder="Search by ticket # or producer's site" ng-model="SearchText" ng-enter="Search()"/>
						<?php } else { ?>
							<input type="text"  class="form-control" placeholder="Search by BOL/ticket # or producer's site" ng-model="SearchText" ng-enter="Search()"/>		
						<?php } ?>
						
						
						  <a class="form-control-feedback form-control-clear" ng-click="ClearSearch()" style="pointer-events: auto; text-decoration: none;cursor: pointer;margin-top:10px;"></a>
						   <span class="input-group-btn">
						  <button class="btn btn-primary" ng-click="Search();">
							<span class="glyphicon glyphicon-search"></span> Search
						</button>
						</span>
					</div>
            </div>
			
		</div>
        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>       
		<table>		
			<thead>
				<tr>
					<th>Ticket Number</th>
					<th>Date Delivered</th>
					<th>Barrels</th>
					<th>Rate</th>
					<th>Water Type</th>
					<th>Disposal Well</th>
                    <th>Producer's site</th>
                    <th>Well File#</th>					
                    <th>Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='ticket in ticketList'>
					<td data-title="Ticket Number"><a href="<?=$rootScope['RootUrl']?>/ticket/view/{{ticket.id}}">{{ticket.ticket_number}}</a></td>
					<td data-title="Date Delivered">{{ticket.date_delivered}}</td>
					<td data-title="Barrels">{{ticket.barrels_delivered}}</td>
					<td data-title="Barrels">${{ticket.barrel_rate}}</td>
					<td data-title="Water Type">{{ticket.water_type_name}}</td>
					<td data-title="Disposal Well">{{ticket.disposal_well_name}}</td>					
                    <td data-title="Producers site">{{ticket.source_well_name}}</td>
                    <td data-title="Well File#">{{ticket.source_well_file_number}}</td>			
                    <td data-title="Options">
	                    <div ng-if = "ticket.is_admin == 1 || ticket.created_by_id == ticket.users_id">  
                        	<a class="glyphicon glyphicon-eye-open" style="cursor: pointer;" title="View ticket" href="<?=$rootScope['RootUrl']?>/ticket/view/{{ticket.id}}"></a>
	                        <a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit ticket" href="<?=$rootScope['RootUrl']?>/ticket/edit/{{ticket.id}}"></a>
	                        <a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteTicket(ticket.id);"></a>
						</div>
						<div ng-if = "ticket.is_admin != 1 && ticket.created_by_id != ticket.users_id">  
                        	 <a class="glyphicon glyphicon-eye-open" style="cursor: pointer;" title="View ticket" href="<?=$rootScope['RootUrl']?>/ticket/view/{{ticket.id}}"></a>
						</div>    
                    </td>
				</tr>
			</tbody>
		</table>
    <?php
	    // FORM FOR TRD COMPANY
	    } elseif( $rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl") {   
	?>
	<table>		
			<thead>
				<tr>
					<th>BOL/Ticket</th>
					<th>Date Delivered</th>
					<th>Producer's site</th>
					<th>Producer (Oil Company)</th>
					<th>Trucking Company</th>
					<th>BBLs Delivered</th>
					<th style="text-align:center;">$/BBL -> Total $</th>
					<th>Fluid Type</th>
                    <th>Percent Solid</th>
					<th>Percent H2O</th>
					<th>Percent Interphase</th>					
					<th>Percent Oil</th>
					<th>Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='ticket in ticketList'>
					<td data-title="BOL/Ticket"><a href="<?=$rootScope['RootUrl']?>/ticket/view/{{ticket.id}}">{{ticket.ticket_number}}</a></td>
					<td data-title="Date Delivered">{{ticket.date_delivered}}</td>
					<td data-title="Producer's site">{{ticket.source_well_name}}</td>
					<td data-title="Producer (Oil Company)">{{ticket.source_well_operator_name}}</td>
					<td data-title="Trucking Company">{{ticket.company_name}}</td>
					<td style="text-align:center;" data-title="BBLs Delivered">{{ticket.barrels_delivered}}</td>
					<td width="150" style="text-align:center;"  data-title="$ per BBL -> Total $">${{ticket.barrel_rate}} -> ${{ticket.barrel_rate*ticket.barrels_delivered}}	
					<td data-title="Fluid Type">{{ticket.fluid_type}}</td>					
                    <td style="text-align:center;" data-title="Percent Solid">{{ticket.percent_solid}}</td>	
                    <td style="text-align:center;" data-title="Percent H2O">{{ticket.percent_h2o}}</td>
                    <td style="text-align:center;" data-title="Percent Interphase">{{ticket.percent_interphase}}</td>
                    <td style="text-align:center;" data-title="Percent Oil">{{ticket.percent_oil}}</td>		
                    <td data-title="Options">
                        <a class="glyphicon glyphicon-eye-open" style="cursor: pointer;" title="View ticket" href="<?=$rootScope['RootUrl']?>/ticket/view/{{ticket.id}}"></a>
                           <?php
                        if($_SESSION["IsAdmin"] == 1) {
	                        ?>
                        <a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit ticket" href="<?=$rootScope['RootUrl']?>/ticket/edit/{{ticket.id}}"></a>                      
                        <?php
	                        }
	                      ?>
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteTicket(ticket.id);"></a>
                    </td>
				</tr>
			</tbody>
		</table>
 <?php
 	} 
 ?>	 
        <div class="row" ng-show="TotalPages > 1">
            <div class="col-lg-12">
                 <nav>
                     <ul class="pagination">
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(1)" title="Go to page 1">&laquo;&laquo;</a></li>
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(PageIndex-1)" title="Go to preious page">&laquo;</a></li>
                        <li ng-class="{active: pn == PageIndex}" ng-repeat="pn in PageRange"><a href="javascript:void(0);" ng-click="GoToPage(pn)">{{pn}} </a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(PageIndex+1)" title="Go to next page">&raquo;</a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(TotalPages)" title="Go to last page">&raquo;&raquo;</a></li>
                    </ul>
                </nav>
            </div>
        </div>


	</div>
</div>
<script text="javascript">
window.cfg.disposal_well_id="<?=$rootScope["disposal_well_id"]?>";
</script>
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
    <?php
	    } elseif( $rootScope["SWDCustomer"] == "trd" ) {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_TRD.js"></script>
	<?php
	    } elseif( $rootScope["SWDCustomer"] == "wwl" ) {   
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