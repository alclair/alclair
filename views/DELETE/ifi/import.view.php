<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="addInventoryCtrl">
	<!--
		New, Open Box, Demo, PRC
		-->
	<!--
		Purchase Orders?  Sales Order (SO), Market Order (MO), Request for Parts (RFP)
		-->
<!-- Main Container Starts -->
 
 	<?php
 		// FORM FOR DELOPMENT PAGE
 		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
 	?>
	<input type="checkbox" ng-model="import_entry_type" ng-true-value="1" ng-false-value="0" style=" transform: scale(1.5);" ><span style="font-size:20px;margin-left:12px;margin-top:40px;" >Return Unit Page</span>
	<input type="checkbox" ng-model="sound_off" ng-true-value="1" ng-false-value="0" style=" transform: scale(1.5);margin-left:30px" ><span style="font-size:20px;margin-left:12px;margin-top:40px;" >SOUND OFF</span>
	
	<!-- FORM 1 -->
    <form role="form" ng-show="import_entry_type === undefined || import_entry_type == 0">
		<div class="container">
	  		<div class="row"  >
   				<h1 style="color: red;" ng-click="clickText()">Add to Inventory  <span ng-if="total_sns" style="color: black;"><font size="6">Total SNs {{total_sns}}</font></span></h1>
               	<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">NEW  </label><span ng-if="total_new_sns" style="color: black;"><font size="4"> # of SNs {{total_new_sns}}</font></span><br />
					<textarea type="text"  id="start_1" name="notes" ng-model="inventory.sealed" value="" class='form-control' rows='5'></textarea>
            	</div>
				<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">AMAZON  </label><span ng-if="total_amazon_sns" style="color: black;"><font size="4"> # of SNs {{total_amazon_sns}}</font></span><br />
					<textarea type="text"  id="start_2" name="notes" ng-model="inventory.amazon" value="" class='form-control' rows='5'></textarea>
            	</div>
        	</div>
			<br />
        
			<div class="row">
               	<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">DEMO  </label><span ng-if="total_demo_sns" style="color: black;"><font size="4"> # of SNs {{total_demo_sns}}</font></span><br />
					<textarea type="text"  id="start_3" name="notes" ng-model="inventory.demo" value="" class='form-control' rows='5'></textarea>
            	</div>
				<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">FAULTY  </label><span ng-if="total_faulty_sns" style="color: black;"><font size="4"> # of SNs {{total_faulty_sns}}</font></span><br />
					<textarea type="text"  id="start_4" name="notes" ng-model="inventory.faulty" value="" class='form-control' rows='5'></textarea>
            	</div>
        	</div>
         	<div class="row">
        		<div class="form-group col-md-3">
                    <label class="control-label">Received From:</label><br />
					<input type="text" ng-model="inventory.received_from" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Original Order #:</label><br />
					<input type="text" ng-model="inventory.original_order_number" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-2">
	                 <label class="control-label">Import Type:</label><br />
                     <select class='form-control' ng-model="inventory.import_type">
						<option value="Return">Return</option>
						<option value="Replenish">Replenish</option>
					</select>
                </div>                
        	</div>


			<br />
			
			<div class="row">
	        	<div class="form-group col-md-2">
		        	<div class="text-left">
						<button  style="font-weight: 600" type="button" class="btn btn-primary" ng-click="Import()">
                        	<i class="fa fa-envelope-o"></i> &nbsp; SUBMIT                           
						</button>
		        	</div>
				</div>
				<div class="form-group col-md-3">
		        	<div class="text-left">
						<button  style="font-weight: 600" type="button" class="btn btn-danger" ng-click="loadRecordAddModal()">
                        	<i class="fa fa-plus"></i> &nbsp; ADD (non-prefix) SNs                           
						</button>
		        	</div>
				</div>
				<!--<div class="form-group col-md-3">
		        	<div class="text-left">
						<button  style="font-weight: 600" type="button" class="btn btn-danger" ng-click="TESTING ()">
                        	<i class="fa fa-plus"></i> &nbsp; TESTING                     
						</button>
		        	</div>
				</div>-->
        	</div>
        </div>
    </form>

    
	<!-- FORM 2 -->
    <form role="form" ng-show="import_entry_type ==1">
	    <div class="container">
   			<div class="row"  >
   				<h1 style="color: red;">Return to Inventory  <span ng-if="total_sns" style="color: black;"><font size="6">Total SNs {{total_sns}}</font></span></h1>
               	<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">NEW  </label><span ng-if="total_new_sns" style="color: black;"><font size="4"> # of SNs {{total_new_sns}}</font></span><br />
					<textarea type="text"  id="start_1" name="notes" ng-model="inventory.sealed" value="" class='form-control' rows='5'></textarea>
            	</div>
				<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">AMAZON  </label><span ng-if="total_amazon_sns" style="color: black;"><font size="4"> # of SNs {{total_amazon_sns}}</font></span><br />
					<textarea type="text"  id="start_2" name="notes" ng-model="inventory.amazon" value="" class='form-control' rows='5'></textarea>
            	</div>
        	</div>
			<br />
        
			<div class="row">
               	<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">DEMO  </label><span ng-if="total_demo_sns" style="color: black;"><font size="4"> # of SNs {{total_demo_sns}}</font></span><br />
					<textarea type="text"  id="start_3" name="notes" ng-model="inventory.demo" value="" class='form-control' rows='5'></textarea>
            	</div>
				<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">FAULTY  </label><span ng-if="total_faulty_sns" style="color: black;"><font size="4"> # of SNs {{total_faulty_sns}}</font></span><br />
					<textarea type="text"  id="start_4" name="notes" ng-model="inventory.faulty" value="" class='form-control' rows='5'></textarea>
         	</div>
         	</div>
        	<div class="row">
        		<div class="form-group col-md-3">
                    <label class="control-label">Received From:</label><br />
					<input type="text" ng-model="inventory.received_from" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Original Order #:</label><br />
					<input type="text" ng-model="inventory.original_order_number" placeholder=""  class="form-control">
                </div>
                <div class="form-group col-md-2">
	                 <label class="control-label">Import Type:</label><br />
                     <select class='form-control' ng-model="inventory.import_type">
						<option value="Return">Return</option>
						<option value="Replenish">Replenish</option>
					</select>
                </div>                
        	</div>


			<br />
			
			<div class="row">
	        	<div class="form-group col-md-2">
		        	<div class="text-left">
						<button  style="font-weight: 600" type="button" class="btn btn-primary" ng-click="Import_return()">
                        	<i class="fa fa-envelope-o"></i> &nbsp; SUBMIT                           
						</button>
		        	</div>
				</div>
				<!--<div class="form-group col-md-3">
		        	<div class="text-left">
						<button  style="font-weight: 600" type="button" class="btn btn-danger" ng-click="loadRecordAddModal ()">
                        	<i class="fa fa-plus"></i> &nbsp; ADD (non-prefix) SNs                           
						</button>
		        	</div>
				</div>-->
				<!--<div class="form-group col-md-3">
		        	<div class="text-left">
						<button  style="font-weight: 600" type="button" class="btn btn-danger" ng-click="TESTING ()">
                        	<i class="fa fa-plus"></i> &nbsp; TESTING                     
						</button>
		        	</div>
				</div>-->
        	</div>
        	
	    </div>   
	  </form>
  <?php
  	} 
  ?>	 
 
 <!--Add Popup Window-->
    <div class="modal fade modal-wide" id="modalAddRecord" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmAddRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add Serial Numbers</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
	                         <div class="form-group col-md-6">
                                <label class="control-label">Product Category:</label><br />
                                <select class='form-control' ng-model='recordAdd.category_id' ng-options="categorytype.id as categorytype.name for categorytype in categoryTypeList" ng-blur="get_products(recordAdd.category_id)" required>
									  <option value="">Select a category</option>
								</select>
                            </div>
                            <div ng-if="recordAdd.category_id" class="form-group col-md-6">
                                <label class="control-label">Product Name:</label><br />
                                <select class='form-control' ng-model='recordAdd.product_id' ng-options="product.id as product.name for product in productList2" required>
									  <option value="">Select a product</option>
								</select>
                            </div>             
                        </div>
                        <div ng-if="recordAdd.product_id" class="row">
                            <div class="form-group col-md-6">
								<label class="control-label" style="font-size: large;color: #007FFF">NEW</label><br />
								<textarea type="text" name="notes" ng-model="recordAdd.sealed" value="" class='form-control' rows='5'></textarea>
            				</div>
                            <div class="form-group col-md-6">
								<label class="control-label" style="font-size: large;color: #007FFF">AMAZON</label><br />
								<textarea type="text" name="notes" ng-model="recordAdd.amazon" value="" class='form-control' rows='5'></textarea>
							</div>                        
						</div>     
                        <div ng-if="recordAdd.product_id" class="row">             
                            <div class="form-group col-md-6">
								<label class="control-label" style="font-size: large;color: #007FFF">DEMO</label><br />
								<textarea type="text" name="notes" ng-model="recordAdd.demo" value="" class='form-control' rows='5'></textarea>
            				</div>
							<div class="form-group col-md-6">
								<label class="control-label" style="font-size: large;color: #007FFF">FAULTY</label><br />
								<textarea type="text" name="notes" ng-model="recordAdd.faulty" value="" class='form-control' rows='5'></textarea>
							</div>
						</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="addWeirdSNs()" ng-disabled="!frmAddRecord.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

 
         
 </div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>


 <?php
 		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
 	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ifiCtrl.js"></script>
<?php  } 	?>	
 
 <?php
