<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/checkbox-custom.css"/>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/checkbox-custom v2.css"/>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/checkbox-custom v3.css"/>

<br />
<div id="main-container" class="container" ng-controller="InventoryCtrl">
 	<! -- CHECKBOXES -->
 	<div class="container" style="margin-top:-20px">
 		<input type="checkbox" ng-model="sound_off" ng-click="flipSound()" ng-true-value="1" ng-false-value="0" style=" transform: scale(1.5);margin-left:30px" ><span style="font-size:20px;margin-left:12px;margin-top:0px;" >SOUND OFF</span>
	 	<div class="checkbox" style="display: inline-block;">
			<label>
				<input type="checkbox"  ng-model="sealed"  ng-click="click_SEALED()"  ng-true-value="1" ng-false-value="0" value=""  checked>
				<span style="margin-top:5px"; class="cr" ><i class="cr-icon glyphicon glyphicon-ok"></i></span>	
				<span style="font-size:20px;margin-top:0px;" >SEALED</span>
			</label>
		</div>
		<div class="checkbox" style="display: inline-block;">
			<label>
				<input type="checkbox"  ng-model="demo"  ng-click="click_DEMO()"  ng-true-value="1" ng-false-value="0" value=""  checked>
				<span style="margin-top:5px"; class="cr" ><i class="cr-icon glyphicon glyphicon-ok"></i></span>	
				<span style="font-size:20px;margin-top:0px;" >DEMO</span>
			</label>
		</div>
		<div class="checkbox" style="display: inline-block;">
			<label>
				<input type="checkbox"  ng-model="faulty"  ng-click="click_FAULTY()"  ng-true-value="1" ng-false-value="0" value=""  checked>
				<span style="margin-top:5px"; class="cr" ><i class="cr-icon glyphicon glyphicon-ok"></i></span>	
				<span style="font-size:20px;margin-top:0px;" >FAULTY</span>
			</label>
		</div>
		<div ng-if="us_inventory==1" class="checkbox_v3" style="display: inline-block;" >
			<label style="margin-left:30px">	
				<input  type="checkbox" ng-model="us_inventory" ng-click="click_US()"  ng-true-value="1" ng-false-value="0" style=" transform: scale(1.5);margin-left:30px" >
				<span style="margin-top:5px;"; class="cr" > <img class="img-responsive2"  src="http://dev.ifi-net.co.uk/includes/American_Flag.png" ></span>
				<span style="font-size:20px;margin-left:10px;margin-top:0px;">US INVENTORY</span> <!-- disabled="us_inventory==0"-->
			</label>
		</div>
		<div ng-if="uk_inventory==1" class="checkbox_v2" style="display: inline-block;" >
			<label style="margin-left:30px">	
				<input type="checkbox" ng-model="uk_inventory" ng-click="click_UK()"  ng-true-value="1" ng-false-value="0" style=" transform: scale(1.5);margin-left:30px" >
				<span style="margin-top:5px;"; class="cr" ><img class="img-responsive3"  src="http://dev.ifi-net.co.uk/includes/UK_Flag.png" ></span>
				<span  style="font-size:20px;margin-left:10px;margin-top:0px;" >UK INVENTORY</span>
			</label>
		</div>
 	</div> <!-- CLOSE CHECKBOXES -->
 
  <form role="form" >
		<div class="container">
	  		<div class="row"  >
		  		<div class="form-group col-md-5">
   					<h2 style="color: red;" ng-click="clickText()">Add to <b>{{the_region}} {{the_warehouse}}</b> Inventory  <span ng-if="total_SNs" style="color: black;">
   						<br/><font size="5">Total SNs {{total_SNs}}</font></span>
	   				</h2>
		  		</div>
		  		<div class="form-group col-md-2">
   					<button   ng-show="sealed_or_demo == 1" style="font-weight: 600; border-radius:50%; margin-top:20px" type="button" class="btn btn-danger" ng-click="ContactPage()" >
                       CONTACT PAGE
				</button>
		  		</div>
		  		<div class="form-group col-md-3">
			  		<input type="checkbox" ng-model="sts_contact" ng-click="stsContact()" ng-true-value="1" ng-false-value="0" style=" transform: scale(1.5);margin-left:0px;margin-top:35px" >
		  			<span style="font-size:20px;margin-left:12px;margin-top:0px;color:blue" >STS CONTACT</span>			
		  		</div>
	  		</div>
	  		
	  		<div class="row">
   				<div class="form-group col-md-6" ng-show="sts_contact==0">
	               <label class="control-label"  style="font-size: large;color: #007FFF">Search For Contact:</label><br />        
					<input type="text"  ng-model="shipped_from" placeholder="Search contact by company, name or address"  
					uib-typeahead="shipto as shipto.whole_name for shipto in ShipTos| filter:{whole_name:$viewValue}| limitTo:8"  typeahead-on-select="SearchText=$item.contact_address_link_id" typeahead-editable="true" class="form-control" ng-blur="LoadAddress2(SearchText);"  autofocus>
             </div>              
             <div class="form-group col-md-3" ng-show="sts_contact==1">
	               <label class="control-label" >First Name:</label><br />        
	               <input ng-model="inventory.firstname" placeholder=""  class="form-control"> 
             </div>
	         <div class="form-group col-md-3" ng-show="sts_contact==1">
	               <label class="control-label" >Last Name:</label><br />       
	               <input type="text" ng-model="inventory.lastname" placeholder=""  class="form-control"> 
             </div>   		
           	<div class="form-group col-md-3" ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0)" > <!-- ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0)" -->
					<label class="control-label">Order Type:</label><br />
					<select id="focushere" class='form-control' ng-model='inventory.order_type_id' ng-options="ordertype.order_type_id as ordertype.order_type_ot for ordertype in orderTypesList">
						<option value="">Choose an order type</option>
					</select>
				</div>	
				<div class="form-group col-md-3" ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0)"><!-- ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0)" -->
					<label class="control-label">Sender Type:</label><br />
					<select id="focushere2" class='form-control' ng-model='inventory.sendertype_id' ng-options="sendertype.id as sendertype.customer_type for sendertype in senderTypesList">
						<option value="">Choose a sender type</option>
					</select>
				</div>	
	  		</div>
	  		<br />

	  		<!-- SERIAL NUMBER BOX -->
	  		<div class="row">
		  		<div class="form-group col-md-6"  ng-show="!inventory.power_standard_id >= 1"> <! -- ng-show="( (inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0) ) && copypaste==0" -->  	
			  		<br /> <br /> <br /> <br /> <br /> <br /> 
			  		<label class="control-label" style="font-size: 19px ;color: #FFFFFF">TEST </label> <!-- RANDOM CODE TO MAKE SPACING WORK -->
		  		</div>
	         	<div class="form-group col-md-6" ng-show="inventory.power_standard_id >= 1">  <!-- ng-show="( (inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0) ) && copypaste==1" -->
					<label class="control-label" style="font-size: large;color: #007FFF">{{the_warehouse}} </label>
					<span ng-if="total_SNs" style="color: black;"><font size="4"> # of SNs {{total_SNs}}</font></span><br />
					<div class="press-enter" style="width:99%;margin-left:10px;" >
						<textarea type="text"  id="start_1" name="notes" ng-model="inventory.serial_numbers" value="" class='form-control' rows='5' ></textarea>
					</div>
						<!--<textarea type="text" name="notes" ng-model="inventory.serial_numbers" value="" class='form-control' rows='5' my-enter="doSomething()"></textarea>-->
           	</div>
               <div class="form-group col-md-2"  ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0)">
                  <label ng-If="inventory.order_type_id!=3" class="control-label">Order Number:</label>
                  <label ng-If="inventory.order_type_id==3" class="control-label">Ticket Number:</label><br />
					<input type="text" ng-model="inventory.order_number_lm" placeholder=""  class="form-control"> 
                </div>
                
                <div class="form-group col-md-3" ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0)">
				  	<label class="control-label" style="color:red">Power Standard:</label><br />
				  	<select class='form-control' ng-model='inventory.power_standard_id' ng-options="powerstandard.power_standard_id as powerstandard.power_standard for powerstandard in powerStandardList">
						<option value="">Choose a power stadard</option>
					</select>
				</div>	
					
        	</div>
        	
        	<div class="row" style="margin-top:20px">
		  		<div class="form-group col-md-6">
	  			</div>
	  			<div class="form-group col-md-6" style="margin-top:-115px" ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0)">
					<label class="control-label">Notes:</label><br />
					<!--<div class="press-enter">-->
						<textarea type="text" name="notes" ng-model="inventory.log_note_lm" value="" class='form-control' rows='2' ></textarea>
					<!--</div>-->
            	</div>
	  		</div>
	  								
			<div class="row" ng-show="sts_contact==0">
	        	<p style="font-style: italic;font-weight: 900;font-size: larger">
		        	<span ng-if="contact.company">{{contact.title}}<br/></span>
					<span ng-if="contact.firstname">{{contact.firstname}}</span> <span ng-if="contact.lastname">{{contact.lastname}}<br/></span>
					<span ng-if="contact.address_1">{{contact.address_1}}<br/></span>
					<span ng-if="contact.address_2">{{contact.address_2}}<br/></span>
					<span ng-if="contact.city">{{contact.city}},</span> <span ng-if="contact.state">{{contact.state}} </span><span ng-if="contact.zipcode">{{contact.zipcode}}</span>
					<br/><span ng-if="contact.country">{{contact.country}}</span>
					<br/><span ng-if="contact.Contact_link_id">{{contact.contact_address_link_id}}</span>
	        	</p>
	        </div>	        
		</div>
    </form>
    
    <div class="row" style="margin-left:40px">
	    <!--
	    <div class="form-group col-md-2" ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0) && inventory.power_standard_id >= 0" >
			<button style="background-color: #5D9451;font-weight: bold;color: white; margin-left:-10px; margin-top:10px; border-radius:4px" class="btn btn-secondary" ng-click="newRow2($event)" >USE SCANNER</button> 
	    </div>
	    <div class="form-group col-md-2" ng-show="(inventory.firstname && inventory.lastname && sts_contact==1) || (SearchText && shipped_from && sts_contact == 0) && inventory.power_standard_id >= 0">
			<button style="background-color: #5D9451;font-weight: bold;color: white; margin-left:-10px; margin-top:10px; border-radius:4px" class="btn btn-secondary" ng-click="CopyPaste()" >COPY & PASTE</button> 
	    </div>
	    -->
	    <div class="form-group col-md-6" ng-show="inventory.power_standard_id >= 1">
	    	<h3 style="margin-top:15px; font-weight:600">Number of SNs Scanned {{Num_SNs_Scanned}}</h3>
	    </div>
    </div>
    

	<form name="rowsForm" ng-submit="save()" ng-show="inventory.power_standard_id >= 1" > <!-- ng-show="copypaste != 1" -->
		<div ng-repeat="(key, row) in rows">
			<div class="row">
				<div class="form-group col-md-4">
					<input id="id{{key}}" type="text" ng-model="row.serial_number" placeholder="Serial Number"  class="form-control"   focus-me="focusInput" my-enter="doSomething('1')" ng-disabled="row.product_name && row.product != ' '  ">  <!-- focus-me="focusInput" -->
				</div>				
				<div class="form-group col-md-3">
					<input style="font-weight:bold" type="text" ng-model="row.product_name" placeholder="Product Name"  class="form-control"  focus-me="focusInput" disabled> 
				</div>	
				<div class="form-group col-md-2">
			  		<select class='form-control' ng-model='row.power_standard_id' ng-options="powerstandard.power_standard_id as powerstandard.power_standard for powerstandard in powerStandardList"  my-enter="doSomething('1')"> <!--  ng-show="row.serial_number" -->
						<option value="">Power standard</option>
					</select>
				</div>		
				<!--
				<div class="form-group col-md-2" >
					<input style="font-weight:bold; text-align: center" type="text" ng-model="row.mode" placeholder="Mode Type"  class="form-control"  disabled > 
				</div>	
				-->	
				<div class="form-group col-md-2" ng-show="n_rows!=1 && row.product_name">
					<button  style="font-weight: 600; border-radius:8%; background-color:#000000; border: none; color:#FFFFFF" type="button" class="btn btn-info" ng-click="DELETE($event, key)"> &nbsp;** DELETE **</button>
        		</div>
			</div>
		</div>
	</form>
	
	
	<br/><br/>
	<div class="row"  ng-show="Num_SNs_Scanned >= 1"><!-- ng-show="scanner != 0 || copypaste != 0" -->
		<div class="form-group col-md-2">
			<div class="text-left">
				<!--<button   ng-show="copypaste == 1" style="font-weight: 600" type="button" class="btn btn-primary" ng-click="Import()">
                &nbsp; RECEIVE PASTE {{the_warehouse}}                           
				</button>-->
				<button  style="font-weight: 600" type="button" class="btn btn-primary" ng-click="Import_scanner($event)"> <!--  ng-show="scanner == 1" -->
                &nbsp; RECEIVE INTO {{the_warehouse}}                            
				</button>
        	</div>
		</div>
		<div class="form-group col-md-3">
        	<div class="text-left">
				<button  style="font-weight: 600" type="button" class="btn btn-info" ng-click="loadRecordAddModal()">
                	<i class="fa fa-plus"></i> &nbsp; ADD (non-prefix) SNs                           
				</button>
        	</div>
		</div>
	</div> 
	
	<!--
	<div class="row" ng-show="copypaste==1">
	    <table >		
			<thead>
				<tr>
					<th style="text-align:center;">Total</th>
					<th style="text-align:center;">Category</th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Power Standard</th>
					<th style="text-align:center;">Product ID</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat="(key3, product) in ProductList">
					<td  width="20%" style="text-align:center;" data-title="Category">{{product.count}}</td>
					<td  width="20%" style="text-align:center;" data-title="Category">{{product.category_name}}</td>
					<td  width="20%" style="text-align:center;" data-title="Product">{{product.product_name}}</td>
					
					<td width="20%"  style="text-align:center;" data-title="Power Standard">
						<select class='form-control' ng-model='product.power_standard_id' ng-options="powerstandard.power_standard_id as powerstandard.power_standard for powerstandard in powerStandardList">
							<option value="">Choose a power standard</option>
						</select>
					</td>
					<td  width="20%" style="text-align:center;" data-title="Product ID">{{product.products_id}}</td>
				</tr>
			</tbody>
		</table>
    </div>
    -->
    <div class="row" ng-show="Num_SNs_Scanned >= 1"> <!--ng-show="scanner==1" -->
	    <table >		
			<thead>
				<tr>
					<th style="text-align:center;">Total</th>
					<th style="text-align:center;">Category</th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Power Standard</th>
					<th style="text-align:center;">Product ID</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat="(key2, product2) in ProductList2">
					<td  width="20%" style="text-align:center;" data-title="Total">{{product2.count}}</td>
					<td  width="20%" style="text-align:center;" data-title="Category">{{product2.category_name}}</td>
					<td  width="20%" style="text-align:center;" data-title="Product">{{product2.product_name}}</td>
					<td  width="20%" style="text-align:center;" data-title="Power Std.">{{product2.power_standard}}</td>
					<td  width="20%" style="text-align:center;" data-title="Product ID">{{product2.products_id}}</td>
				</tr>
			</tbody>
		</table>
    </div>

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
                                <select class='form-control' ng-model='recordAdd.product_categories_id' ng-options="categorytype.product_categories_id as categorytype.categories_pc for categorytype in categoryTypeList" ng-blur="update_products(recordAdd.product_categories_id)"  required>
									  <option value="">Select a category</option>
									</select>
                            </div>
                            <div ng-if="recordAdd.product_categories_id" class="form-group col-md-6">
                                <label class="control-label">Product Category:</label><br />
                                <select class='form-control' ng-model='recordAdd.products_id' ng-options="productName.products_id as productName.product_name_p for productName in productsInCategoryList" required>
										<option value="">Select a product</option>
									</select>
                            </div>             
                        </div>
                        <div ng-if="recordAdd.products_id" class="row">
                            <div class="form-group col-md-6">
									<label class="control-label" style="font-size: large;color: #007FFF">Add to <b>{{the_region}} {{the_warehouse}}</b> Inventory</label><br />
									<textarea type="text" name="notes" ng-model="recordAdd.serial_numbers" value="" class='form-control' rows='5'></textarea>
            					</div>                        
							</div>     
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="addWeirdSNs(inventory.order_type_id, inventory.sendertype_id, inventory.log_note_lm, inventory.order_number_lm)" ng-disabled="!frmAddRecord.$valid"><i class="fa fa-plane"></i> Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</div> <!-- CLOSE MAIN CONTAINER -->
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script> 
 <?php
 		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
 	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/Receiving.js"></script>
<?php  } 	?>	
 
 <?php
