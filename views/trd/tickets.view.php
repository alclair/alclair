<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Tickets">
    <!-- Main Container Starts -->
  
        <div class="row">
	        <div class="col-md-6">
	        </div>
            <div class="col-md-3">
               <input type="file" ng-file-select="onFileSelect($files,'pdf')" accept=".pdf" ngf-fix-orientation="true" multiple onclick="this.value = null" />
            </div>
            <div class="col-md-1">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Catalog</a></b>&nbsp;&nbsp;
            </div>
            <div class="col-md-1">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Search</a></b>&nbsp;&nbsp;
            </div>

        </div>
        
		<table class="table table-hover" ng-click="Test123()">		
			<thead>
				<tr>
					<th style="text-align:center;"><input type="checkbox" name="name1" ng-click="Test123()" />&nbsp;</th>
					<th style="text-align:center;">Ticket #</th>
					<th style="text-align:center;">Date</th>
					<th style="text-align:center;">Location Name</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat="(key, order) in TicketList">
					<td  style="text-align:center;" ><input type="checkbox"  id="id_{{key}}" name="checkbox_{{key}}" ng-click="Test456(key)" />&nbsp; </td>
					<td  style="text-align:center;" data-title="Ticket #">{{order.id}}</td>
					<td  style="text-align:center;" data-title="Date">{{order.date_uploaded}}</td>
					<td  style="text-align:center;" data-title="Location Name">{{order.filename}}</td>
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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/TRD_Tickets.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>