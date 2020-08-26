<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Orders">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Roster</a> (# of players: {{TotalRecords}})</b>&nbsp;&nbsp;</a>
            </div>
        </div>
        
		<!--<div class="row alert" style='background-color:#ddd;'>-->
            
            
		<div class="row alert" style='background-color:#ddd;'>
            <div class="form-group col-sm-1">                  
                <!--<select class='form-control' ng-model='build_type_id' ng-options="buildType.id as buildType.type for buildType in buildTypeList">
					<option value="">Select a build type</option>
				</select>-->
            </div>
			<div class="form-group col-sm-2">
                <select class='form-control' ng-model='team_id' ng-options="team.id as team.team_name for team in teamList">
	                <option value="">-- Teams --</option>
                </select>
            </div>	

			<div class="col-sm-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchEndDate" is-open="openedEnd" datepicker-options="dateOptions"  ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openEnd($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
			</div>
           <div class="form-group col-sm-1">       
				<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="refresh_page()">
					 &nbsp; LOAD
				</button>
            </div>		
             <div class="form-group col-sm-4">       	
				 <span style="font-size:40px; color:blue; vertical-align: middle;">{{TEAM_NAME}}</span>
             </div>
		</div>
        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Absent?</th>
					<th style="text-align:center;">Name</th>
					<th style="text-align:center;">Key</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='(key, person) in personnelList'>
										
					<td  style="text-align:center;" data-title="Absent?">{{person.person_id}}</td>
					<td  style="text-align:center;" data-title="Name">{{person.first_name}} {{person.last_name}}</td>
					<td  style="text-align:center;" data-title="Key">{{key}}</td>
				</tr>
			</tbody>
		</table>
    <?php
	    // FORM FOR TRD COMPANY
	    } 
	?>
		 
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


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/zfootball.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>