<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="displayMaintenanceLogsCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <b style='font-size:20px;'>Total # of logs (Total: {{TotalRecords}})</b>
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
                <!--<input type="text" ng-model="SearchUserLogTypes" value="{{item.id}}" class='form-control'> -->
            </div>

			<div class="col-md-2">
                <select ng-model="SearchUserLogTypes" ng-change="Search()" class="form-control">
                    <option selected value="0">---select ticket type---</option>
                    <option ng-repeat="item in UserLogTypes" value="{{item.id}}" ng-selected="item.id==UserLogTypes">{{item.common_name}}</option>
                </select>

			</div>

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

							<input type="text"  class="form-control" placeholder="Search by name (first or last)" ng-model="SearchText" ng-enter="Search()"/>
												
						  <a class="form-control-feedback form-control-clear" ng-click="ClearSearch()" style="pointer-events: auto; text-decoration: none;cursor: pointer;margin-top:10px;"></a>
						   <span class="input-group-btn">
						  <button class="btn btn-primary" ng-click="Search();">
							<span class="glyphicon glyphicon-search"></span> Search
						</button>
						</span>
					</div>
            </div>
			
		</div>
        
		<table>		
			<thead>
				<tr>
					<th>Employee</th>
					<th>Log Type</th>
					<th>Date Opened</th>
					<th>Date Closed</th>
					<!--<th>Rate</th>
					<th>Water Type</th>
					<th>Disposal Well</th>
                    <th>Producer's site</th>
                    <th>Well File#</th>					
                    <th>Options</th>-->
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='ticket in ticketList'>
					
					<td ng-if="ticket.type=='Maintenance'" data-title="Employee"><a href="<?=$rootScope['RootUrl']?>/user_logs/maintenance_admin/{{ticket.id}}">{{ticket.first_name}} {{ticket.last_name}}</a></td>
					<td ng-if="ticket.type=='Inventory'" data-title="Employee"><a href="<?=$rootScope['RootUrl']?>/user_logs/inventory_admin/{{ticket.id}}">{{ticket.first_name}} {{ticket.last_name}}</a></td>
					
					<!--<td data-title="Employee">{{ticket.first_name}} {{ticket.last_name}}</td>-->
					
					<td data-title="Log Type">{{ticket.type}}</td>
					
					<td data-title="Date Opened">{{ticket.date_created}}</td>
					<td data-title="Date Closed">{{ticket.date_closed}}</td>
					<!--<td data-title="Barrels">${{ticket.barrel_rate}}</td>
					<td data-title="Water Type">{{ticket.water_type_name}}</td>
					<td data-title="Disposal Well">{{ticket.disposal_well_name}}</td>					
                    <td data-title="Producers site">{{ticket.source_well_name}}</td>
                    <td data-title="Well File#">{{ticket.source_well_file_number}}</td>	-->		
                   <!-- <td data-title="Options">
                        <a class="glyphicon glyphicon-check" style="cursor: pointer;" title="View ticket" href="<?=$rootScope['RootUrl']?>/ticket/view/{{ticket.id}}"></a>
                        <a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit ticket" href="<?=$rootScope['RootUrl']?>/ticket/edit/{{ticket.id}}"></a>      
     
                        <a class="glyphicon glyphicon-check" style="cursor: pointer;" title="View ticket"></a>
                        <a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit ticket"></a>     
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteTicket(ticket.id);"></a>
                    </td>-->
				</tr>
			</tbody>
		</table>
  
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


<script src="<?=$rootScope["RootUrl"]?>/includes/app/user_logsCtrl.js"></script>

   <?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>