<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<div id="main-container" class="container" ng-controller="Edit_Reviewer">
	
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
                    	<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/alclair/qc_list">Reviewer's Information</a> - Input</b>
                	</div>
            	</div>
            	
                <!--<div class="form-group col-md-3">
	                <label class="control-label" style="font-size: large;color: #007FFF">Category:</label><br />                    
                    <select class='form-control' ng-model='add.category_id' ng-options="categorytype.id as categorytype.name for categorytype in categoryTypeList"  ng-blur="update_products();">
						<option value="">Select a product type</option>
					</select>
                </div>
                <div ng-if="add.category_id >= 0" class="form-group col-md-3">
	                 <label class="control-label" style="font-size: large;color: #007FFF">Unit requested:</label><br />                    
					 <select class='form-control' ng-model='add.product_id' ng-options="product.id as product.name for product in productList">
						<option value="">Pick a unit</option>
					</select>
                </div>   -->
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
					<select class='form-control' ng-model='reviewer.tag_id' ng-options="tag.id as tag.ifi_tag for tag in tagList"></select>
	            </div>
	            <div class="form-group col-md-2">
		            <label style="font-size: large;color: #007FFF" class="control-label">iClub:</label><br />
					<select class='form-control' ng-model='reviewer.iclub_id' ng-options="club.id as club.iclub for club in iclubList"></select>
	            </div>
	            <div class="form-group col-md-2">
		            <label style="font-size: large;color: #007FFF" class="control-label">Status:</label><br />
					<select class='form-control' ng-model='reviewer.status_id' ng-options="stat.id as stat.status for stat in statusList"></select>
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
	            <div class="form-group col-md-2">
		             <label style="font-size: large;color: #007FFF" class="control-label">Review Agreement:</label><br />
		            <center><input style="margin-left:-12px" type="checkbox" ng-model="reviewer.ra_sent" ng-true-value="1" ng-false-value="0"><label style="color: #FF0000; display:inline"> &nbsp; SENT</label></center>
					<center><input type="checkbox" ng-model="reviewer.ra_signed" ng-true-value="1" ng-false-value="0"><label style="color: #FF0000;display:inline-block"> &nbsp; SIGNED</label></center><br />
	            </div>
	            <div class="form-group col-md-2">
		             <label style="font-size: large;color: #007FFF; margin-left:52px" class="control-label">NDA:</label><br />
		            <center><input style="margin-left:-12px" type="checkbox" ng-model="reviewer.nda_sent" ng-true-value="1" ng-false-value="0"><label style="color: #FF0000; display:inline"> &nbsp; SENT</label></center>
					<center><input type="checkbox" ng-model="reviewer.nda_signed" ng-true-value="1" ng-false-value="0"><label style="color: #FF0000;display:inline-block"> &nbsp; SIGNED</label></center>
	            </div>
	            <div class="form-group col-md-8">
					<label style="font-size: large" class="control-label">Notes:</label><br />
					<textarea type="text" name="notes" ng-model="reviewer.notes" value="" class='form-control' rows='6'></textarea>
                </div>

        	</div>

        	<h3 style="color: #006400"><b>JOBS</b></h3>
			<form name="jobsForm" ng-submit="save()">
				<div ng-repeat="job in jobs">
					<div class="row">
						<div class="form-group col-md-3">
							<label style="font-size: large" class="control-label">Title:</label><br />
							<input type="text" ng-model="job.title" placeholder=""  class="form-control"> 
                		</div>
						<div class="form-group col-md-3">
							<label style="font-size: large" class="control-label">Company:</label><br />
							<input type="text" ng-model="job.company" placeholder=""  class="form-control"> 
                		</div>
						<div class="form-group col-md-3">
							 <label style="font-size: large;color: #007FFF" class="control-label">Sector:</label><br />
							 <select class='form-control' ng-model='job.sector_id'  ng-options="sec.id as sec.sector for sec in sectorList"></select>
						</div>
					</div>
					<!--<label>Name:</label>
					<input type="text" ng-model="user.name" required />
					<br/>
					<label>Email:</label>
					<input type="email" ng-model="user.email" required />
					<hr/>-->
      			</div>
	  			<button class="btn btn-success" ng-click="newJob($event)">Add Job</button>
	  			<button class="btn btn-danger" ng-click="removeJob($event)">Remove Job</button>

	  			<!--<input type="submit" value="Save" ng-disabled="usersForm.$invalid || !users.length"  ng-click="save($event)"/>-->
	  		</form>
			<br />
			
			<h3 style="color: #006400"><b>USERNAMES</b></h3>
			<form name="usernamesForm" ng-submit="save()">
				<div ng-repeat="username in usernames">
					<div class="row">
						<div class="form-group col-md-3">
							<label style="font-size: large" class="control-label">Username:</label><br />
							<input type="text" ng-model="username.username" placeholder=""  class="form-control"> 
                		</div>
						<div class="form-group col-md-3">
							<label style="font-size: large" class="control-label">Forum:</label><br />
							<input type="text" ng-model="username.forum" placeholder=""  class="form-control"> 
                		</div>
						<div class="form-group col-md-3">
							 <label style="font-size: large;color: #007FFF" class="control-label">Sector:</label><br />
							 <select class='form-control' ng-model='username.sector_id'  ng-options="sec.id as sec.sector for sec in sectorList"></select>
						</div>      			
					</div>
				</div>
	  			<button class="btn btn-success" ng-click="newUsername($event)">Add Username</button>
	  			<button class="btn btn-danger" ng-click="removeUsername($event)">Remove Username</button>
	  		</form>
			<br />
			
			<div class="row">
	        	<div class="form-group col-md-3">
		        	<div class="text-left">
						<button  style="font-weight: 600; border-radius: 4px;" type="button" class="btn btn-primary" ng-click="Update($event)">
                        	<i class="fa fa-envelope-o"></i> &nbsp; UPDATE                        
						</button>
		        	</div>
				</div>
        	</div>
        	
	    </div>        
    </form>
    
     <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Date</th>
					<th style="text-align:center;">First Name</th>
					<th style="text-align:center;">Last Name</th>
					<th style="text-align:center;">Address</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='request in shippingRequest'>
					<td style="text-align:center;">{{request.date}}  &nbsp;&nbsp; {{log_entry.date_to_show_hours}} </a></td>
					<td style="text-align:center;">{{request.firstname}}</a></td>
					<td style="text-align:center;">{{request.lastname}} &nbsp; {{log_entry.last_name}}</a></td>
					<td style="text-align:center;">{{request.address_1}}</a></td>
				</tr>					
			</tbody>
		</table>

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