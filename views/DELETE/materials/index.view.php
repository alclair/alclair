<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="materialsCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <b style='font-size:20px;'>Materials (Total: {{TotalRecords}})</b>
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
       	<div class="row alert" style='background-color:#ddd;'>
			<div class="col-md-2">
                <?php
						if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl") {
					?>
                <!--<a type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMaterial">
	                <span class="glyphicon glyphicon-plus"></span> Add Material
				</a>-->
				<!--<button class="btn btn-primary"  >
					<a class="glyphicon glyphicon-plus" style="color: #ffffff" ng-click="adjustmentCtrl();"> Add Material </a>
				</button>-->
				<button type="button" class="btn btn-primary" ng-click="showAddModal();"><i class="glyphicon glyphicon-plus"></i> Add Material</button>
				<?php
						}
				?>
        	</div>
			<!-- Modal -->
	
	<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="addMaterial" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmAddMaterial">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add Material</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
							<div class="form-group col-md-4">
								<label class="control-label">Material Name:</label><br />
								<input type="text" value="" ng-model="material.name" class='form-control'>
							</div>
							<div class="form-group col-md-4">
								<label ng-if="material.name" class="control-label">Units:</label><br />
								<input ng-if="material.name" type="text" value="" ng-model="material.units" class='form-control'>
							</div>
	                    </div> <!-- END ROW -->
	                    <br />
	                    
	                    <div class="row">
							<div class="form-group col-md-12">
								<label ng-if="material.units" class="control-label" style="font-weight: bold;">Attach Document/Image:</label><br /><br />
								<div ng-if="material.units" class="form-group col-md-12">
									<input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />
                    			</div>
                			</div>
            			</div> <!-- END ROW -->

                
					 	<div class="modal-footer">
                        	<button ng-if="material.units" type="button" class="btn btn-primary" ng-click="addMaterial()" ng-disabled="!frmAddMaterial.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    	</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->            
	
	<div class="modal fade modal-wide" id="editMaterial" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmEditMaterial">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add Material</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
							<div class="form-group col-md-4">
								<label class="control-label">Material Name:</label><br />
								<input type="text" value="" ng-model="edit_material.name" class='form-control'>
							</div>
							<div class="form-group col-md-4">
								<label ng-if="edit_material.name" class="control-label">Units:</label><br />
								<input ng-if="edit_material.name" type="text" value="" ng-model="edit_material.units" class='form-control'>
							</div>
	                    </div> <!-- END ROW -->
	                    <br />
	                    
	                    <div class="row">
							<div class="form-group col-md-12">
								<label ng-if="edit_material.units" class="control-label" style="font-weight: bold;">Attach Document/Image:</label><br /><br />
								<input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />
								<label ng-if="edit_fileList.length == 0">No document</label>

								<div ng-if="edit_fileList.length > 0">
									<hr />
									<b>Existing Documents/Images:</b>
									<table class="table" style="width: 60%;">
										<tr ng-repeat="file in edit_fileList">
											<td>
												<span ng-hide="file.filepath.indexOf('.pdf')!=-1">
													<a href="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-lightbox="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}">
														{{file.filepath}}
													</a>
												</span>
												<span ng-show="file.filepath.indexOf('.pdf')>=0">
													<a href="javascript:void(0);" ng-click="OpenWindow(file.filepath)">
														{{file.filepath}}
													</a>
												</span>
											</td>
							<!-- IF STATEMENT exists because BOH had tickets without images attached
									Could not figure out how it was happening.  The thought here is to prevent anyone from deleting images if they were attached -->
											<?php if($rootScope["SWDCustomer"] != "boh") { ?>
												<td><a title="Delete document" ng-click="deleteDocument(file.id);"><i class="fa fa-trash"></i></a></td>
											<?php } ?>
										</tr>
									</table>
                				</div>
            				</div> 
	                    </div><!-- END ROW -->

                
					 	<div class="modal-footer">
                        	<button ng-if="edit_material.units" type="button" class="btn btn-primary" ng-click="editMaterial()" ng-disabled="!frmEditMaterial.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    	</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->            

	<!--INCREASE/DECREASEQUANTITY MODAL WINDOW -->
    <div class="modal fade modal-wide" id="adjustQuantity" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmadjustQuantity">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Increase Material</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
							<div class="form-group col-md-4">
								<label  class="control-label">Reason:</label><br />
								<select class='form-control' ng-model='adjust.Reason' ng-options="adjust.value as adjust.label for adjust in Reason"></select>
							</div>
	                    </div> <!-- END ROW -->
	                    
						<div class="row">	
							<div ng-if="adjust.Reason=='Purchase'" class="form-group col-md-3">
								<label  class="control-label">Qty. Purchased:</label>
								<br />
								<div class="input-group">
									<input type="number" value="" ng-model="adjust.qtyAdjusting" class='form-control'>
								</div>
							</div>
							<div ng-if="adjust.Reason=='Inventory Adjustment'" class="form-group col-md-3">
								<label class="control-label">+ or -</label><br />
								<select class='form-control' ng-model='adjust.AddorSubtract' ng-options="adjust.value as adjust.label for adjust in AddorSubtract">
								</select>	                
							</div>
							
							<div ng-if="adjust.Reason=='Inventory Adjustment' && adjust.AddorSubtract=='Subtract'"  class="form-group col-md-4">
								<label class="control-label">Amount to Subtract:</label>
								<br />
								<div class="input-group">
									<input type="number" value="" ng-model="adjust.qtyAdjusting" class='form-control'>
								</div>
							</div>
							<div ng-if="adjust.Reason=='Inventory Adjustment' && adjust.AddorSubtract=='Add'"  class="form-group col-md-4">
								<label class="control-label">Amount to Add:</label>
								<br />
								<div class="input-group">
									<input type="number" value="" ng-model="adjust.qtyAdjusting" class='form-control'>
								</div>
							</div>
							
							<div class="form-group col-md-4">
								<label ng-if="adjust.Reason=='Purchase'" class="control-label">Unit Cost:</label><br />
								<div    ng-if="adjust.Reason=='Purchase'" class="input-group">
									<span  class="input-group-btn">
										<button type="button" class="btn btn-primary">$</button>
									</span>
									<input type="number" ng-model="adjust.unitCost" class='form-control' >
								</div>
							</div>
						</div>
						<div class="row">	
							<div class="form-group col-md-3">
								<label ng-if="adjust.Reason=='Purchase'" class="control-label">Invoice #:</label><br />
								<div    ng-if="adjust.Reason=='Purchase'" class="input-group">
									<input type="text" value="" ng-model="adjust.invoice_number" class='form-control'>
								</div>
							</div>
							<div class="form-group col-md-4">
								<label ng-if="adjust.Reason=='Purchase'" class="control-label">Invoice Date:</label><br />
								<div    ng-if="adjust.Reason=='Purchase'" class="input-group">
									<span class="input-group-btn">
										<button type="button" class="btn btn-primary" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
									</span>
									<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="adjust.invoice_date" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="false" close-text="Close" />
            					</div>
        					</div>
	                    </div> <!-- END ROW -->
	                    <div class="row">
							<div class="form-group col-md-7">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="adjust.notes" value="" class='form-control' rows='2'></textarea>
							</div>
	                    </div>
	                    <br />
	                    
	                    <div class="row">
							<div class="form-group col-md-12">
								<label class="control-label" style="font-weight: bold;">Attach Document/Image:</label><br /><br />
								<div class="form-group col-md-12">
									<input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />
                    			</div>
                			</div>
            			</div> <!-- END ROW -->

                
					 	<div class="modal-footer">
<button ng-if="adjust.qtyAdjusting && adjust.Reason=='Inventory Adjustment'" type="button" class="btn btn-primary" ng-click="inventoryAdjustment()" ng-disabled="!frmadjustQuantity.$valid"><i class="fa fa-arrow-right"></i>Submit</button>

<button ng-if="adjust.invoice_date && adjust.Reason=='Purchase'" type="button" class="btn btn-primary" ng-click="newPurchase()" ng-disabled="!frmadjustQuantity.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                    	</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END INCREASE/DECREASER QUANTITY MODAL WINDOW -->  			
</div>
        
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Material Name</th>
					<th style="text-align:center;">Last Entry</th>
					<th style="text-align:center;">Units</th>
					<th style="text-align:center;">Current Qty.</th>
					<th style="text-align:center;">Qty. Used</th>
					<!--<th style="text-align:center;">Adjusted Qty.</th>-->
					<th style="text-align:center;">Unit Cost</th>
					<th style="text-align:center;">Total Cost</th>
					<th style="text-align:center;">Attachment</th>
					<th style="text-align:center;">Options</th>

				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='material in materialsList'>
					<td style="text-align:center;" data-title="Material Name"><a href="<?=$rootScope['RootUrl']?>/materials/view/{{material.id}}">{{material.name}}</a></td>
					<td style="text-align:center;" data-title="Date Created">{{material.time_stamp}}</td>
					
					<td style="text-align:center;" data-title="Units">{{material.units}}</td>
					<td style="text-align:center;" data-title="Current Qty">{{material.current_quantity}}</td><!--{{material.current_qty}}-->
					<td style="text-align:center;" data-title="Qty. Used">
						<input style="text-align:center;" type="number" ng-model="material.qtyUsed" class='form-control' >
					</td>
					<!--<td style="text-align:center;" data-title="Adjusted Qt. ($)">{{material.id}}</td>	-->
					<td style="text-align:center;" data-title="Unit Cost ($)">{{material.current_price | currency:"$"}}</td>	
					<td style="text-align:center;" data-title="Total Cost ($)">{{material.total_cost | currency:"$"}}</td>	<!--{material.current_price*material.current_qty | currency:"$"}}-->
					
					
					<td data-title="Attachment	">
						<div ng-repeat='file in fileList' ng-if="file.material_id==material.id"> 
							<span ng-hide="file.filepath.indexOf('.pdf')!=-1">
								<a href="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-lightbox="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-title="{{materialName}}">
									{{file.filepath}}
								</a>
							</span>
							<span ng-show="file.filepath.indexOf('.pdf')>=0">
								<a href="javascript:void(0);" ng-click="OpenWindow(file.filepath)">
									{{file.filepath}}
								</a>
							</span>
								<!--&nbsp;&nbsp;Uploaded at {{file.date_uploaded}}-->
							<br />
						</div>
					</td>
					
                    <td data-title="Options">
                        <button type="button" class="btn btn-primary btn-xs" ng-click="saveQtyUsed(material.id, material.qtyUsed);"><i></i>Save</button>
						<a class="glyphicon glyphicon-plus"  style="cursor: pointer;" title="Add quantity" ng-click="showAdjustMaterialModal(material.id, material.current_qty);"></a> 
						<a class="glyphicon glyphicon-edit"   style="cursor: pointer;" title="Edit material" ng-click="editMaterialModal(material.id);"></a>
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete material" ng-click="deleteMaterial(material.id);"></a>                             						    
<!--<a ng-if="ticket.type == 'Solids'" class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" href="deleteTicket(ticket.id, ticket.type);"></a>  -->

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


<script src="<?=$rootScope["RootUrl"]?>/includes/app/materials.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>