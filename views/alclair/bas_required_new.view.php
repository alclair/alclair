<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="BAs_Required">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Orders</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"> </b><span style="color: #FF0000">  {{traveler.order_id}}</span></a>
            </div>
            
			<div class="col-md-2">
			</div>
            <div class="col-md-2">
                <input type="checkbox" ng-model="rush_or_not" ng-true-value="1" ng-false-value="0" style="margin-top:16px"> &nbsp; <b style="font-size:14px;color:gray">RUSH ORDERS ONLY</b><br />
            </div>
            <div class="col-md-3">
                <input type="checkbox" ng-model="remove_hearing_protection" ng-true-value="1" ng-false-value="0" style="margin-top:16px"> &nbsp; <b style="font-size:14px;color:gray">REMOVE HEARING PROTECTION</b><br />
            </div>
            
            <!--<div class="col-md-2" style="margin-top:-10px;padding-bottom:10px;text-align:left;	" >
                <a type="button" class="btn btn-danger" href="<?=$rootScope['RootUrl']?>/alclair/qc_form">
					<span class="glyphicon glyphicon-pencil"></span> &nbsp;&nbsp;QC FORM
				</a>
            </div>-->
            <!--
            <div class="col-md-6" style="text-align:right;">
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
            -->
        </div>
        
		<!--<div class="row alert" style='background-color:#ddd;'>-->
            
            
		<div class="row alert" style='background-color:#ddd;'>
			<!--
            <div class="form-group col-sm-3">
                <select class='form-control' ng-model='today_or_next_week' ng-options="day.value as day.label for day in TODAY_OR_NEXT_WEEK" ng-blur="Search();">
                </select>
            </div>	
            -->
            
            <div class="col-sm-2">
			<label  class="control-label" style="font-size: large;color: #007FFF">Start Date:</label><br />
			<div class="input-group">
				<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchStartDate" is-open="openedStartDay" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
				<span class="input-group-btn">
					<button type="button" class="btn btn-default" ng-click="openStartDay($event)"><i class="fa fa-calendar"></i></button>
				</span>
			</div>				
		</div>
		<div class="col-sm-2">
			<label  class="control-label" style="font-size: large;color: #007FFF">End Date:</label><br />
			<div class="input-group">
				<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchEndDate" is-open="openedEndDay" datepicker-options="dateOptions"  ng-inputmask="99/99/9999" close-text="Close" />
				<span class="input-group-btn">
					<button type="button" class="btn btn-default" ng-click="openEndDay($event)"><i class="fa fa-calendar"></i></button>
				</span>
			</div>
		</div>
		<div class="col-sm-2">
		<br />
			<button style="border-radius: 4px; border-color: blue; margin-top:10px" class="btn btn-warning" ng-click="Search($event)">RUN</button>
			<label  class="control-label" style="font-size: large;color: #000000">&nbsp;&nbsp; Total orders:  {{DailyListCount}}</label><br />
		</div>
<!--
            <div class="form-group col-sm-1">       
				<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="Search()">
					<i class="fa fa-search"></i> &nbsp; SEARCH
				</button>
            </div>
-->
	</div>
        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Monitor</th>
					<th style="text-align:center;">Casing & Before</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td  style="text-align:center;" data-title="Monitor">{{order.monitors}}</td>
					<td  ng-if="order.casing_count>0" style="text-align:center;" data-title="Casing & before"><a href="<?=$rootScope['RootUrl']?>/alclair/orders_from_bas_required/monitor-{{order.monitors}}start_{{SearchStartDate}}end_{{SearchEndDate}}">{{order.casing_count}}</a></td>
					
					<td  ng-if="!order.casing_count >= 1" style="text-align:center;" data-title="Casing & before">{{order.casing_count}}</td>
					
					<!--<td  style="text-align:center;" data-title="Casing">{{order.casing_count}}</td>-->
				</tr>
			</tbody>
		</table>
		
		</br>
		
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Balanced Armature</th>
					<th style="text-align:center;">Casing & Before</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='the_part in PartsList'>
					<td  style="text-align:center;" data-title="Monitor">{{the_part.part}}</td>
					<td  style="text-align:center;" data-title="Casing">{{the_part.casing_quantity}}</td>
				</tr>
			</tbody>
		</table>
    <?php
	    // FORM FOR TRD COMPANY
	    } 
	?>

<!--		 
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
-->

	</div>
	

    <!--Edit Popup Window End-->
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_TAT_new.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>