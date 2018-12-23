<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<div id="main-container" class="container" ng-controller="Open_Review">
	
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
                
                <!--<div class="form-group col-md-2" ng-if="reviewer.their_name && reviewer.country">
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
            </div>-->
            
            <div class="row">
	            <div class="form-group col-md-1">
		            <label style="font-size: large;color: #007FFF" class="control-label">Title:</label><br />
					<select class='form-control' ng-model='reviewer.title_id' ng-options="tit.id as tit.title for tit in titleList"></select>
	            </div>
	            <div class="form-group col-md-3">
                    <label style="font-size: large" class="control-label">First Name:</label><br />
					<input type="text" ng-model="reviewer.firstname" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label style="font-size:large" class="control-label">Last Name:</label><br />
					<input type="text" ng-model="reviewer.lastname" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-sm-1">
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
					<select class='form-control' ng-model='reviewer.country_id' ng-options="count.id as count.country for count in countryList"></select>                
				</div>
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
	    </div>        
	    
	    <div class="row" ng-if="reviewer.approved == 'NEEDS_APPROVAL'">
			<h2 align="center"> Awaiting Approval </h2>
		</div>
		<div class="row" ng-if="reviewer.approved == 'APPROVED'">
			<h2 align="center"> Approved </h2>
		</div>
		<div class="row" ng-if="reviewer.approved == 'UNAPPROVED'">
			<h2 align="center"> Unapproved </h2>
		</div>
		<div class="row" ng-if="reviewer.unapprove_notes">
			<h2 align="center"> Unapproved because <b> {{reviewer.unapprove_notes}}</b>. </h2>
		</div>
    </form>
    
     <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Quantity</th>
					<th style="text-align:center;">Category</th>
					<th style="text-align:center;">Product Name</th>
					<th style="text-align:center;">Warehouse</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='product in productList'>
					<td style="text-align:center;">{{product.quantity}}</a></td>
					<td style="text-align:center;">{{product.category}}</a></td>
					<td style="text-align:center;">{{product.product_name}}</a></td>
					<td style="text-align:center;">{{product.new_or_demo}}</a></td>
				</tr>					
			</tbody>
		</table>
		
		</br>
		<div class="row" ng-if="the_user_is == 'admin' || the_user_is == 'Vicky'">
			<div class="form-group col-md-2">
				<b>
					<select class='form-control' ng-model='who_gets_it' ng-options="who.value as who.label for who in whoGetsItList"></select>
				</b>
        	</div>
	        <div class="form-group col-md-2">
				<button  style="font-weight: 600; border-radius: 4px;" type="button" class="btn btn-primary" ng-click="Approve(who_gets_it)">
                        <i class="fa fa-thumbs-o-up"></i> &nbsp; APPROVE                       
				</button>
			</div>
		</div>
		<div class="row" ng-if="the_user_is == 'admin' || the_user_is == 'Vicky'">
			<div class="form-group col-md-2">
			</div>
			<div class="form-group col-md-2">
				<button  style="font-weight: 600; border-radius: 4px;" type="button" class="btn btn-danger" ng-click="Unapprove($event)">
                        <i class="fa fa-minus-circle"></i> &nbsp; UNAPPROVE                       
				</button>
		    </div>
		    <div class="form-group col-md-6">
				<textarea type="text" name="notes" ng-model="reviewer.unapprove_notes" value="" class='form-control' rows='3' placeholder="Why are you unapproving the request?"></textarea>
            </div>
		</div>
		

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