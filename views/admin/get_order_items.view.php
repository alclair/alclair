<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="SearchWoo">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <!--
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Orders</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"> {{STARTED}} AND {{ENDED}} Printed</b><span style="color: #FF0000">  {{traveler.order_id}}</span></a>
                -->
            </div>
        </div>            
		<div class="row alert" style='background-color:#ddd;'>
            <div class="form-group col-sm-4">                  
            </div>
			

			<div class="col-sm-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchStartDate" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>				
			</div>
			<div class="col-sm-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchEndDate" is-open="openedEnd" datepicker-options="dateOptions"  ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openEnd($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
			</div>			
			<div class="col-sm-2">
				<span class="input-group-btn">
					<button class="btn btn-primary js-new pull-right" ng-click="Search();">
						<span class="glyphicon glyphicon-search"></span> SEARCH
					</button>
				</span>
			</div>
		</div>
        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Quantity</th>
					<!--<th style="text-align:center;">Order ID</th>-->
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
									
					<td  style="text-align:center;" data-title="Product">{{order.product_name}}</td>
					
					<td  style="text-align:center;" data-title="Quantity">{{order.product_count}}</td>					
                    
				</tr>
			</tbody>
		</table>
    <?php
	    } 
	?>
	</div>
	
	<!--Edit Popup Window-->
    <div class="modal fade modal-wide" id="SelectDateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmEditRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Select a date for when the order was shipped</h4>
                    </div>
                    <div class="modal-body">
                    	<div class="row">    
	                    	<div class="form-group col-md-2">
	                    	</div>
							<div class="form-group col-md-6">
								<div class="text-left">
									<label class="control-label" style="font-size: large;color: #007FFF">DONE DATE</label><br />
		 						</div>
		 						<div class="input-group">
		 							<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="done_date" is-open="openedDone" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
		 							<span class="input-group-btn">
		 								<button type="button" class="btn btn-default" ng-click="openDone($event)"><i class="fa fa-calendar"></i></button>
		 							</span>
                				</div>			
                			</div>
                    	</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="DONE()" ng-disabled="!frmEditRecord.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Edit Popup Window End-->
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/WooCommerce.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>