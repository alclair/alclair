<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminWellCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
		<div class="row">
			<div class="col-md-4">
				<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}} Management (Total: {{TotalRecords}})</b>	
			</div>
			<div class="col-md-8" style="text-align:right;">
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
	        <div class="col-md-2">
		        <a type="button" class="btn btn-primary"  ng-click="loadWellsList();">
					<span class="glyphicon glyphicon-resize-horizontal fa-lg"></span> Search New {{entityName}}
				</a>
                <!--<a type="button"  class="btn btn-primary" href="/admin/dual_box">
					<span class="glyphicon glyphicon-resize-horizontal fa-lg"></span> Search New Well
				</a>-->
            </div>
	        
            <div class="col-md-2">
                <a type="button" class="btn btn-primary"  ng-click="loadRecordAddModal();">
					<span class="glyphicon glyphicon-plus"></span> Create New {{entityName}}
				</a>
            </div>
            
            <div class="col-md-6">
				<div class="input-group">                
					<div style="float:right;">
						<input type="text" class="form-control" style="min-width:400px;" ng-model="SearchText" placeholder="Search by name, county and field name" ng-enter="Search()" /><br />
						
					</div>   
					<span class="input-group-btn">						
						<button class="btn btn-primary" ng-click="Search();">
							<span class="glyphicon glyphicon-search"></span>Search               
						</button>	
					</span>
				</div>
            </div>
        </div>
		<table class="table table-striped" style="border:1px solid #ddd;">			
			<thead>				
				<tr bgcolor="#cccccc">	
					<th>File Number</th>	                    
					<th>API Number</th>	                    									
                    <th>Name</th>	                    
                    <th>Producer Name</th>
                    <th>Field</th>
                    <th>County</th>
                    <th>Township</th>
                    <th>Range</th>
                    <th>Section</th>
                    <th>Quarter</th>
                    <th>options</th>			
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList'> <!-- track by record.id'>-->			
					<td>{{record.file_number}}</td>   
					<td>{{record.api_number}}</td>   		
                    <td>{{record.name}}</td>                    
                    <td>{{record.operator_name}}</td>
                    <td>{{record.field_name}}</td>
                    <td>{{record.county_name}}</td>
                    <td>{{record.township}}</td>
                    <td>{{record.range}}</td>
                    <td>{{record.section}}</td>
					<td>{{record.qq}}</td>                    
					<td>
						<a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit Producer" ng-click="loadRecordEdit(record.id);"></a>                      
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete Producer" ng-click="deleteRecord(record.id);"></a>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="row">
			<div class="col-md-12">
				<nav ng-show="TotalPages > 1">
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


    <!--Add Popup Window-->
    <div class="modal fade modal-wide" id="modalWellsList" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmWellsList">
	            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add a New {{entityName}}</h4>
                    </div>
					 <div class="modal-body">
						 <div class="form-group col-md-12">
						 	<label class="control-label">Producer's site:</label><br />
						 	<div class="input-group">
				 				<input type="text" ng-model="ticket.well_info" placeholder="Search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:16"  typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control" required />
						 	
								<span class="input-group-btn">
									<a type="button" class="btn btn-default" href="/admin/dual_box">Unlisted?</a>
								</span>
				 			</div>
				 			<span>*search by well name, producer or file number</span>
                		</div>
					 </div>
					 <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="addRecord()" ng-disabled="!frmWellsList.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default"   ng-click="closeAddRecord()" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!--Add Popup Window-->
    <div class="modal fade modal-wide" id="modalAddRecord" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmAddRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add a New {{entityName}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">File Number*:</label><br />
                                <input type="number"  ng-model="recordAdd.file_number" class='form-control' required/>                    
                            </div>  
                            <div class="form-group col-md-6">
                                <label class="control-label">API Number*:</label><br />
                                <input type="number" class="form-control" ng-model="recordAdd.api_number" required/>                             
                            </div>  
                            <div class="form-group col-md-6">
                                <label class="control-label">Producer Site*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.name" class='form-control' required />
                            </div>     
                            <div class="form-group col-md-6">
                                <label class="control-label">Producer*:</label><br />
                                <input type="text" ng-model="recordAdd.operator_name" placeholder="search for an producer" uib-typeahead="operator as operator.name for operator in operators| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="OperatorSelected($item.id)" typeahead-editable="false" class="form-control" />                                                                           
                            </div>  
                            
                            <div class="form-group col-md-6">
								<label class="control-label">Field Name:</label><br />
								<!--<div class="input-group">-->
									<input type="text" ng-model="recordAdd.field_name" placeholder="Search for a field name" uib-typeahead="fieldname as fieldname.name for fieldname in fields| filter:{name:$viewValue}| limitTo:12"  typeahead-on-select="FieldNameSelected($item.name)" typeahead-editable="false" class="form-control">
				 				<!--</div>-->
							</div>
<!--"recordAdd.field_name=$item.field_name"-->
                           <!-- <div class="form-group col-md-6">
                                <label class="control-label">Field Name*: <input type="text" ng-model="recordAdd.searchField" placeholder="type to search fields" /></label><br />
                                <select class="form-control" ng-model="recordAdd.field_name">                                    
                                    <option ng-repeat="item in fields|filter:recordAdd.searchField" value="{{item.name}}" >{{item.name}}</option>
                                </select>  
								<i>100/{{fields.length}} are displayed, type to search more.</i>		
                            </div>  -->
                            
							<div class="form-group col-md-6">
								<label class="control-label">County:</label><br />
								<!--<div class="input-group">-->
									<input type="text" ng-model="recordAdd.county_name" placeholder="Search for a county" uib-typeahead="county as county.name for county in countylist| filter:{name:$viewValue}| limitTo:12"  typeahead-on-select="CountyNameSelected($item.name)" typeahead-editable="false" class="form-control">
				 				<!--</div>-->
                			</div>
   
                            <!--<div class="form-group col-md-6">
                                <label class="control-label">County: <input type="text" ng-model="recordAdd.searchCounty" placeholder="type to search county" /></label><br />
                                <select class="form-control" ng-model="recordAdd.county_name">                                    
                                    <option ng-repeat="item in countylist|filter:recordAdd.searchCounty" value="{{item.name}}">{{item.name}}</option>
                                </select>  
								<i>100/{{countylist.length}} are displayed, type to search more.</i>									
                            </div>  -->
                            
                            <div class="form-group col-md-6">
                                <label class="control-label">Township*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.township" class='form-control' required />
                            </div>  
                            <div class="form-group col-md-6">
                                <label class="control-label">Range*:</label><br />
                                <input type="number" class="form-control" ng-model="recordAdd.range" name="newwell_range" min="46" max="107" required />
                                <span>* Range must be between 46 and 107</span>                                
                            </div>  
                            <div class="form-group col-md-6">
                                <label class="control-label">Section*:</label><br />
                                <input type="number" class="form-control" ng-model="recordAdd.section" name="newwell_section" min="1" max="36" required />
                                <span>* Section can only be between 1 and 36</span>                                
                            </div>  
                            <div class="form-group col-md-6">
                               <label class="control-label">Quarter*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.quarter" class='form-control' required />                     
                            </div>                                                                                                   
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="uploadAddRecord()" ng-disabled="!frmAddRecord.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Add Popup Window End-->


    <!--Edit Popup Window-->
    <div class="modal fade modal-wide" id="modalEditRecord" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmEditRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit {{entityName}}</h4>
                    </div>
                    <div class="modal-body">                        
                        <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">File Number*:</label><br />
                                <input type="number"  ng-model="recordEdit.file_number" class='form-control' required/>                    
                            </div>  
                            <div class="form-group col-md-6">
                                <label class="control-label">API Number*:</label><br />
                                <input type="number" class="form-control" ng-model="recordEdit.api_number" required/>                             
                            </div>  
                            <div class="form-group col-md-6">
                                <label class="control-label">Producer Site*:</label><br />
                                <input type="text" value="" ng-model="recordEdit.name" class='form-control' required />
                            </div>     
                            <div class="form-group col-md-6">
                                <label class="control-label">Producer*:</label><br />
                                <input type="text" ng-model="recordEdit.operator_name" placeholder="search for a producer" uib-typeahead="operator as operator.name for operator in operators| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="OperatorSelected($item.id)" typeahead-editable="false" class="form-control" />                                                                           
                            </div>                              
                            <div class="form-group col-md-6">
								<label class="control-label">Field Name:</label><br />
								<!--<div class="input-group">-->
									<input type="text" ng-model="recordEdit.field_name" placeholder="Search for a field name" uib-typeahead="fieldname as fieldname.name for fieldname in fields| filter:{name:$viewValue}| limitTo:12"  typeahead-on-select="FieldNameSelected($item.name)"  typeahead-editable="false" class="form-control">
				 				<!--</div>-->
							</div>
<!--"recordEdit.field_name=$item.field_name"-->
                            
                            
                            <!--<div class="form-group col-md-6">
                                <label class="control-label">Field Name*: <input type="text" ng-model="recordEdit.searchField" placeholder="type to search fields" /></label><br />
                                <select class="form-control" ng-model="recordEdit.field_name" >                                    
                                    <option ng-repeat="item in fields |filter:recordEdit.searchField" value="{{item.name}}">{{item.name}}</option>
                                </select>                               
								<i>100/{{fields.length}} are displayed, type to search more.</i>								
                            </div>  -->
                            
                            <div class="form-group col-md-6">
								<label class="control-label">County:</label><br />
								<!--<div class="input-group">-->
									<input type="text" ng-model="recordEdit.county_name" placeholder="Search for a county" uib-typeahead="county as county.name for county in countylist| filter:{name:$viewValue}| limitTo:12"  typeahead-on-select="CountyNameSelected($item.name)" typeahead-editable="false" class="form-control">
				 				<!--</div>-->
                			</div>

                            
                            <!--<div class="form-group col-md-6">
                                <label class="control-label">County: <input type="text" ng-model="recordEdit.searchCounty" placeholder="type to search county" /></label><br />
                                <select class="form-control" ng-model="recordEdit.county_name" >                                    
                                    <option ng-repeat="item in countylist |filter:recordEdit.searchCounty" value="{{item.name}}">{{item.name}}</option>
                                </select>  
								<i>100/{{countylist.length}} are displayed, type to search more.</i>								
                            </div>  -->

                            <div class="form-group col-md-6">
                                <label class="control-label">Township*:</label><br />
                                <input type="text" value="" ng-model="recordEdit.township" class='form-control' required />
                            </div>  
                            <div class="form-group col-md-6">
                                <label class="control-label">Range*:</label><br />
                                <input type="number" class="form-control" ng-model="recordEdit.range" name="newwell_range" min="46" max="107" required />
                                <span>* Range must be between 46 and 107</span>                                
                            </div>  
                            <div class="form-group col-md-6">
                                <label class="control-label">Section*:</label><br />
                                <input type="number" class="form-control" ng-model="recordEdit.section" name="newwell_section" min="1" max="36" required />
                                <span>* Section can only be between 1 and 36</span>                                
                            </div>    
                             <div class="form-group col-md-6">
                               <label class="control-label">Quarter*:</label><br />
                                <input type="text" value="" ng-model="recordEdit.qq" class='form-control' required />                     
                            </div>                                                                                                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="updateRecordEdit()" ng-disabled="!frmEditRecord.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Edit Popup Window End-->
</div>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminWellCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
