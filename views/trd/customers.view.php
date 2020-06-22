<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="Customers">
	
    <!-- Main Container Starts -->
    <form role="form">
	    <div class="container">
		    <div class="row">
				<div class="form-group col-md-3" style="margin-top:10px">
					<b style="font-size: 20px;"> Search Customer</b>
				</div>
				<div class="form-group col-md-8" style="margin-left:-60px">
					<input type="text"  ng-model="the_customer" placeholder="Search for customer"  uib-typeahead="the_customer as the_customer.customer for the_customer in Customers| filter:{customer:$viewValue}| limitTo:8"  typeahead-on-select="SearchText=$item.id" typeahead-editable="true" class="form-control" ng-blur="LoadAddress2(SearchText);" autofocus> <!--ng-blur="LoadAddress2(SearchText);"-->	
				</div>
		    </div>
	    </div>
		<div class="row">
			<div class="col-md-12" >
				<div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
			</div>
		</div>

        	 <div class="row">
	        	 <div class="form-group col-md-2">
                    <label style="font-size: large;color: #252525" class="control-label">Customer:</label><br />
					<input type="text" ng-model="customer.customer" placeholder=""  class="form-control"> 
                </div>
                 <div class="form-group col-md-3">
                    <label style="font-size: large;color: #252525" class="control-label">Contact Name:</label><br />
						<input type="text" ng-model="customer.contact_name" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size: large;color: #252525" class="control-label">Email:</label><br />
						<input type="text" ng-model="customer.email" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size: large;color: #252525" class="control-label">Phone:</label><br />
					<input type="text" ng-model="customer.phone" placeholder=""  class="form-control"> 
                </div>
        	 </div>
        	 
        	 
        	 <div class="row">
	        	 <div class="form-group col-md-4">
                    <label style="font-size: large;color: #252525" class="control-label">Address:</label><br />
						<textarea type="text"  name="notes" rows='6' ng-model="customer.address" placeholder=""  class="form-control"></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label style="font-size: large;color: #252525" class="control-label">Notes:</label><br />
						<textarea type="text"  name="notes" rows='6' ng-model="customer.notes" placeholder=""  class="form-control"></textarea>
                </div>

        	 </div>
        	
        	<div class="row">
				<div class="form-group col-md-3" ng-show="!the_customer.id">
		        	<div class="text-left">
						<button  style="font-weight: 600; border-radius: 4px;" type="button" class="btn btn-primary" ng-click="Customer($event)">
                        	 &nbsp; CREATE NEW CUSTOMER                   
						</button>
		        	</div>
				</div>
				<div ng-if="the_customer.id" class="form-group col-md-3">
		        	<div class="text-left">
						<button  style="font-weight: 600; border-radius: 4px;background-color: #FF5C23" type="button" class="btn btn-warning" ng-click="Customer($event)">
                         &nbsp; UPDATE CUSTOMER            
						</button>
		        	</div>
				</div>
				<div ng-if="the_customer.id" class="form-group col-md-3" ng-show="customer.new_address != 1">
					<button  style="font-weight: 600; border-radius: 4px;background-color: #FF0000" type="button" class="btn btn-danger" ng-click="DeleteCustomer(SearchText)">
						&nbsp; DELETE CUSTOMER
					</button>
				</div>
			</div>

	    </div>        
    </form>

        
</div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>
<!-- -->
<script src="<?=$rootScope["RootUrl"]?>/includes/app/TRD_Customers.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>