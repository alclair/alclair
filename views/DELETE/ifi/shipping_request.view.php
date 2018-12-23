<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ifi_ShippingRequest">
	
    <!-- Main Container Starts -->

	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
	?>
    <form role="form">
	    <div class="container">
        	<div class="row">
            	<div class="col-md-12">
                	<div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    	<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/alclair/qc_list">Shipping Request</a> - New Form</b>
                    	<span style="font-size: 30px;color:blue;margin-left:100px"><b>Hello <?php echo $_SESSION["UserName"] ?>!</b></span>
                	</div>
            	</div>
                <div class="form-group col-md-3">
	                <label class="control-label" style="font-size: large;color: #007FFF">Ship Product To:</label><br />        
	                <input type="text" ng-model="reviewer.their_name" placeholder="Search for a person" uib-typeahead="person as person.name for person in persons| filter:{name:$viewValue}| limitTo:8"  typeahead-on-select="reviewer.info=$item.id" typeahead-editable="false" class="form-control" ng-blur="getReviewerInfo();" autofocus="autofocus">
                    <!--<span>*Search by name</span>-->    
                </div>
                 <div class="form-group col-md-1">
                 </div>
                <div class="form-group col-md-1" ng-if="reviewer.status">
                    <label class="control-label" style="font-size: large;color: #007FFF">Status: <br />
                    <span  style="font-size: 24px;color: #000000"> {{reviewer.status}}</span></label>    
                </div>
                <div class="form-group col-md-1" ng-if="reviewer.ifi_tag">
                    <label class="control-label" style="font-size: large;color: #007FFF;white-space: nowrap;">iFi Tag: <br />
                    <span  style="font-size: 24px;color: #000000"> {{reviewer.ifi_tag}}</span></label>    
                </div>
                <div class="form-group col-md-1" ng-if="reviewer.iclub">
                    <label class="control-label" style="font-size: large;color: #007FFF;margin-left:40px">iClub: <br />
                    <span  style="font-size: 24px;color: #000000"> {{reviewer.iclub}}</span></label>    
                </div>
                <div class="form-group col-md-2" ng-if="reviewer.their_name && reviewer.country">
	                <label class="control-label" style="font-size: large;color: #007FFF;margin-left:40px;white-space: nowrap;">NDA: <br />	 
						<span  ng-if="reviewer.nda_sent && !reviewer.nda_signed" style="font-size: 24px;color: #000000"> SENT & NOT SIGNED </span>
						<span  ng-if="!reviewer.nda_sent && !reviewer.nda_signed" style="font-size: 24px;color: #000000"> NOT SENT </span>
						<span  ng-if="reviewer.nda_sent && reviewer.nda_signed" style="font-size: 24px;color: #000000"> SENT & SIGNED </span>
						<span  ng-if="!reviewer.nda_sent && reviewer.nda_signed" style="font-size: 24px;color: #000000"> NOT SENT BUT SIGNED </span>
                    </label>    
                </div>
                <div class="form-group col-md-2" ng-if="reviewer.their_name && reviewer.country">
	                <label class="control-label" style="font-size: large;color: #007FFF;margin-left:80px;white-space: nowrap;">Agreement: <br />	 
						<span  ng-if="reviewer.ra_sent && !reviewer.ra_signed" style="font-size: 24px;color: #000000"> SENT & NOT SIGNED </span>
						<span  ng-if="!reviewer.ra_sent && !reviewer.ra_signed" style="font-size: 24px;color: #000000"> NOT SENT </span>
						<span  ng-if="reviewer.ra_sent && reviewer.ra_signed" style="font-size: 24px;color: #000000"> SENT & SIGNED </span>
						<span  ng-if="!reviewer.ra_sent && reviewer.ra_signed" style="font-size: 24px;color: #000000"> NOT SENT BUT SIGNED </span>
                    </label>    
                </div>
            </div>
            
            <div class="row">
	            <div class="form-group col-md-1">
		            <label style="font-size: large;color: #007FFF" class="control-label">Title:</label><br />
					<select class='form-control' ng-model='reviewer.title_id' ng-options="tit.id as tit.title for tit in titleList"></select>
	            </div>
	            <div class="form-group col-md-2">
                    <label style="font-size: large" class="control-label">First Name:</label><br />
					<input type="text" ng-model="reviewer.firstname" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-2">
                    <label style="font-size:large" class="control-label">Last Name:</label><br />
					<input type="text" ng-model="reviewer.lastname" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-2">
		            <label style="font-size: large;color: #007FFF" class="control-label">iFi Tag:</label><br />
					<select class='form-control' ng-model='reviewer.tag_id' ng-options="tag.id as tag.ifi_tag for tag in tagList" disabled></select>
	            </div>
	            <div class="form-group col-md-2">
		            <label style="font-size: large;color: #007FFF" class="control-label">iClub:</label><br />
					<select class='form-control' ng-model='reviewer.iclub_id' ng-options="club.id as club.iclub for club in iclubList" disabled></select>
	            </div>
	            <div class="form-group col-md-2">
		            <label style="font-size: large;color: #007FFF" class="control-label">Status:</label><br />
					<select class='form-control' ng-model='reviewer.status_id' ng-options="stat.id as stat.status for stat in statusList" disabled></select>
	            </div>
        	 </div>
        	 
        	 <div class="row">
	        	 <div class="form-group col-md-3">
                    <label style="font-size: large" class="control-label">Address 1:</label><br />
					<input type="text" ng-model="reviewer.address_1" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size: large" class="control-label">Address 2:</label><br />
					<input type="text" ng-model="reviewer.address_2" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size: large" class="control-label">Address 3:</label><br />
					<input type="text" ng-model="reviewer.address_3" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size: large" class="control-label">Address 4:</label><br />
					<input type="text" ng-model="reviewer.address_4" placeholder=""  class="form-control"> 
                </div>
        	 </div>
        	 
        	 <div class="row">
	        	 <div class="form-group col-md-3">
                    <label style="font-size: large" class="control-label">City:</label><br />
					<input type="text" ng-model="reviewer.city" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size: large" class="control-label">State/County:</label><br />
					<input type="text" ng-model="reviewer.state" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size: large" class="control-label">Zip/Postcode:</label><br />
					<input type="text" ng-model="reviewer.zip" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size: large;color: #007FFF" class="control-label">Country:</label><br />
					<select class='form-control' ng-model='reviewer.country_id' ng-options="count.id as count.country for count in countryList"></select>                </div>
        	 </div>
        	 <div class="row">
               	<div class="form-group col-md-3">
					<label class="control-label" style="font-size: large;color: #007FFF">Reason for request:</label><br />
					<select class='form-control' ng-model='reviewer.reason_id' ng-options="rea.id as rea.reason for rea in reasonList"></select>
            	</div>
            		<div class="form-group col-md-3">
					<label class="control-label" style="font-size: large;color: #007FFF">Unit Type:</label><br />
					<select class='form-control' ng-model='reviewer.unit_type_id' ng-options="unit.id as unit.unit_type for unit in unitTypeList"></select>
            	</div>
            	<div class="form-group col-md-6">
					<label class="control-label" style="font-size: large;color: #007FFF">Comments about reviewer </label><br />
					<textarea type="text" name="notes" ng-model="reviewer.notes" value="" class='form-control' rows='5'></textarea>
            	</div>
        	</div>
			<br />
			
			
            
            <div ng-if="reviewer.firstname" class="row">
	            <div class="form-group col-md-12">
                <form name="usersForm" ng-submit="save()">
					<div  id="{{$index}}" ng-repeat="(key, user) in users"> <!--id="{{$index}}"-->
						<div class="row">
							<div class="form-group col-md-2">
								<label style="font-size: large;color: #007FFF" class="control-label">Quantity:</label><br />
								<input style="text-align:center" type="number" ng-model="user.quantity" placeholder=""  class="form-control"> 
							</div>
							<div ng-if="user.quantity" class="form-group col-md-3">   
								<label style="font-size: large;color: #007FFF" class="control-label">Category:</label><br />               
								<select class='form-control' ng-model='user.category_id' ng-options="categorytype.id as categorytype.name for categorytype in categoryTypeList"  ng-blur="LoadData($index);">
									<option value="">Select a category</option>
								</select>
            				</div>
            				 <div ng-if="user.category_id" class="form-group col-md-3">
	            				 <label style="font-size: large;color: #007FFF" class="control-label">Product:</label><br />
							 	<select class='form-control' ng-model='user.product_id' ng-options="productName.id as productName.name for productName in user.productsInCategoryList">
							 		<option value="">Select a product</option>
								</select>
							</div>
							<div ng-if="user.product_id" class="form-group col-md-3" name="selValue" class="selectpicker">
	            				 <label style="font-size: large;color: #007FFF" class="control-label">New/Demo:</label><br />
							 	<select class='form-control' ng-model='user.new_or_demo' ng-options="newOrDemo.id as newOrDemo.new_or_demo for newOrDemo in newOrDemoList">
								</select>
							</div>
							
							<!--<div ng-if="user.discount" class="form-group col-md-6">
								<label style="font-size: large;color: #007FFF" class="control-label">Products:</label><br />
								<select class='form-control' ng-model='user.id_of_product' ng-options="prod.id as prod.product for prod in productList">
									<option value="">Choose a product</option>
								</select>
							</div>	-->		
						</div>
					</div>
					
					<button style="font-weight: 600" type="button" class="btn btn-success" ng-click="newUser($event)" >Add Product</button>
					<button style="font-weight: 600" type="button" class="btn btn-danger" ng-click="removeUser($event)">Remove Product</button>		
					<br /><br />
					<div class="row">
						<div class="form-group col-md-3">
							<div class="text-left">
								<button  style="font-weight: 600" type="button" class="btn btn-primary" ng-disabled="!users[0].product_id"  ng-click="save($event)"> 
									<!-- COMMENT BELOW IS WHAT WAS IN THE THE NG-DISABLED FIELD -->
									<!--usersForm.$invalid || !users.length && -->
									<i class="fa fa-envelope-o"></i> &nbsp; SUBMIT                           
								</button>
							</div>
						</div>
        			</div>
				</form>
	            </div>
            </div>
        	
	    </div>        
    </form>
 <?php
 	} 
 ?>	 
        
</div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>
<!-- -->
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ifiCtrl_Review.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>