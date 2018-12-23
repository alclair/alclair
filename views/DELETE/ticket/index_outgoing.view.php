<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="ticketCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <b style='font-size:20px;'>Outgoing Tickets (Total: {{TotalRecords}})</b>
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
                <?php
						if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl") {
					?>
                <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
	                <span class="glyphicon glyphicon-plus"></span> Add new ticket
		                <!--href="<?=$rootScope['RootUrl']?>/ticket/add">-->
				</a>
				<?php
						}
				?>
        	</div>
			<!-- Modal -->
			<div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">What kind of ticket would you like to add?</h4>
        				</div>
						<!--<div cla	ss="modal-body">
							<p>This is a small modal.</p>
        				</div>-->
						<div class="modal-footer">
							<a type="button" class="btn btn-primary" href="<?=$rootScope['RootUrl']?>/ticket/outgoinglandfill"> Solids Disposal </a>
							<a type="button" class="btn btn-primary" href="<?=$rootScope['RootUrl']?>/ticket/oilsale"> Oil Sale </a>
							<a type="button" class="btn btn-primary" href="<?=$rootScope['RootUrl']?>/ticket/outgoingwater"> Salt Water Disposal </a>
							<!--<button type="button" class="btn btn-default"  href="<?=$rootScope['RootUrl']?>/ticket/add" data-dismiss="modal">Solids Disposal</button>
							<button type="button" class="btn btn-default"  href="<?=$rootScope['RootUrl']?>/ticket/add" data-dismiss="modal">Oil Sale</button>-->
        				</div>
					</div>
    			</div>
  			</div>
            
			<div class="col-md-2">
					<?php
						if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl") {
					?>
                <select ng-model="SearchOutgoingTicketTypes" ng-change="Search()" class="form-control">
                    <option selected value="0">---select ticket type---</option>
                    <option ng-repeat="item in OutgoingTicketTypes" value="{{item.id}}" ng-selected="item.id==SearchOutgoingTicketTypes">{{item.common_name}}</option>
                </select>
                <?php
	                }
	            ?>
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
						<input type="text"  class="form-control" placeholder="Search by ticket #" ng-model="SearchText" ng-enter="Search()"/>
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
					<th>Date Delivered</th>
					<th>Ticket #</th>
					<th>Ticket Type</th>
					<th>Barrels</th>
					<th>Tons</th>
					<th>Total Price ($)</th>
					<th>Options</th>

				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='ticket in ticketList'>
					<td data-title="Date Delivered">{{ticket.date_delivered}}</td>
					
					<td ng-if="ticket.type == 'Oil'" data-title="Ticket #"><a href="<?=$rootScope['RootUrl']?>/ticket/view_oil/{{ticket.id}}">{{ticket.ticket_number}}</a></td>
					<td ng-if="ticket.type == 'Solids'" data-title="Ticket #"><a href="<?=$rootScope['RootUrl']?>/ticket/view_landfill/{{ticket.id}}">{{ticket.ticket_number}}</a></td>
					<td ng-if="ticket.type == 'Water'" data-title="Ticket #"><a href="<?=$rootScope['RootUrl']?>/ticket/view_water/{{ticket.id}}">{{ticket.ticket_number}}</a></td>
					
					<td data-title="Ticket Type">{{ticket.type}}</td>
					<td data-title="Barrels">{{ticket.barrels_delivered}}</td>
					<td data-title="Tons">{{ticket.tons}}</td>
					<td data-title="Total Price ($)">${{ticket.total_dollars}}</td>	
                    <td data-title="Options">
                        
<a ng-if="ticket.type == 'Oil'" class="glyphicon glyphicon-check" style="cursor: pointer;" title="View ticket" href="<?=$rootScope['RootUrl']?>/ticket/view_oil/{{ticket.id}}"></a>      
<a ng-if="ticket.type == 'Solids'" class="glyphicon glyphicon-check" style="cursor: pointer;" title="View ticket" href="<?=$rootScope['RootUrl']?>/ticket/view_landfill/{{ticket.id}}"></a>  
<a ng-if="ticket.type == 'Water'" class="glyphicon glyphicon-check" style="cursor: pointer;" title="View ticket" href="<?=$rootScope['RootUrl']?>/ticket/view_water/{{ticket.id}}"></a>  
                        
                           <?php
                        if($_SESSION["IsAdmin"] == 1) {
	                        ?>

<a ng-if="ticket.type == 'Oil'" class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit ticket" href="<?=$rootScope['RootUrl']?>/ticket/edit_oil/{{ticket.id}}"></a>      
<a ng-if="ticket.type == 'Solids'" class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit ticket" href="<?=$rootScope['RootUrl']?>/ticket/edit_landfill/{{ticket.id}}"></a>      
<a ng-if="ticket.type == 'Water'" class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit ticket" href="<?=$rootScope['RootUrl']?>/ticket/edit_water/{{ticket.id}}"></a>      
                   		
                        <?php
	                        }
	                      ?>

<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteTicket(ticket.id,ticket.type);"></a>     
<!--<a ng-if="ticket.type == 'Solids'" class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" href="deleteTicket(ticket.id, ticket.type);"></a>  -->

                    </td>
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
<script text="javascript">
window.cfg.disposal_well_id="<?=$rootScope["disposal_well_id"]?>";
</script>

<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_outgoing.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>