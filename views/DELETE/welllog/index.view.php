<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="wellLogCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
		<div class="row">
			<div class="col-md-4">
				<b style='font-size:20px;'>Well Logs (Total: {{TotalRecords}})</b>		
				
			</div>
			<div class="col-md-8"> 
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
		</div>


        <div class="row alert" style="background-color:#ddd;">
			<div class="col-md-8">
				<a type="button" class="btn btn-primary" href="<?=$rootScope['RootUrl']?>/welllog/add">
					<span class="glyphicon glyphicon-plus"></span> Add new well log
				</a>
			</div>
            
			<div class="col-md-4">
				<div class="input-group">
                    <input type="text"  ng-model="SearchText" class="form-control" ng-enter="Search()" placeholder="Search by disposal well" /> 
					<span class="input-group-btn">		
						<button class="btn btn-primary" ng-click="Search();">
						<span class="glyphicon glyphicon-search"></span> Search
						</button>  
					</span>
				</div>
            </div>            
        </div>
		<table class="table table-striped" style="border:1px solid #ccc;">			
			<thead>
				<tr bgcolor="#cccccc">
					<th>Disposal Well</th>
					<th>Date Logged</th>
					
					<th>Oil Sold</th>
					<th>Injection Rate</th>
					<th>Injection Presure</th>
					<th>Flowmeter Reading</th>
					<th>Skim Tank 1</th>
					<th>Skim Tank 2</th>
					<th>Oil Tank 1</th>					
					<th>Oil Tank 2</th>					
					<th>Gun Barrel</th>
					<th>Pipeline#1 Starting</th>
					<th>Pipeline#1 Ending</th>
					<th>Pipeline#2 Starting</th>
					<th>Pipeline#2 Ending</th>
					<th>Notes</th>
                    <th>Options</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='welllog in wellLogList'>
					<td>{{welllog.disposal_well_name}}</td>			
					<td>{{welllog.date_logged}}</td>			
						
					<td>{{welllog.oil_sold_barrels}}</td>			
					<td>{{welllog.injection_rate}}</td>			
					<td>{{welllog.injection_pressure}}</td>			
					<td>{{welllog.flowmeter_barrels}}</td>			
					<td>{{welllog.level_skim_tank_1_ft}}</td>			
					<td>{{welllog.level_skim_tank_2_ft}}</td>
					<td>{{welllog.level_oil_tank_1_ft}}</td>
					<td>{{welllog.level_oil_tank_2_ft}}</td>
						
					<td>{{welllog.level_gun_ft}}</td>	
					<td>{{welllog.pipeline1_starting_total}}</td>
					<td>{{welllog.pipeline1_ending_total}}</td>
					<td>{{welllog.pipeline2_starting_total}}</td>
					<td>{{welllog.pipeline2_ending_total}}</td>
					<td>{{welllog.notes}}</td>
                    <td>
                        <a class="glyphicon glyphicon-check" style="cursor: pointer;" title="View welllog" href="<?=$rootScope['RootUrl']?>/welllog/view/{{welllog.id}}"></a>
                        <a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit welllog" href="<?=$rootScope['RootUrl']?>/welllog/edit/{{welllog.id}}"></a>                      
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete welllog" ng-click="deleteWellLog(welllog.id);"></a>
                    </td>			
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
</div>
<script text="javascript">
window.cfg.disposal_well_id="<?=$rootScope["disposal_well_id"]?>";
</script>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/wellLogCtrl.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
