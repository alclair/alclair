<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<br />
<div ng-controller="showMaterialTrackerCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <b style='font-size:20px;'>{{materialName}} (Total: {{TotalRecords}})</b>
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
    <br />

<!--INCREASE/DECREASEQUANTITY MODAL WINDOW -->
    <div class="modal fade modal-wide" id="editPurchase" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmEditPurchase">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit Purchase</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
							<div class="form-group col-md-4">
								<label  class="control-label">Reason:</label><br />
								<select class='form-control' ng-model='editPurchas.Reason' ng-disabled="true" ng-options="editPurchas.value as editPurchas.label for editPurchas in Reason"></select>
							</div>
	                    </div> <!-- END ROW -->
	                    
						<div class="row">	
							<div class="form-group col-md-3">
								<label  class="control-label">Qty. Purchased:</label>
								<br />
								<div class="input-group">
									<input type="text" value="" ng-model="editPurchase.added" class='form-control' >
								</div>
							</div>
														
							<div class="form-group col-md-4">
								<label  class="control-label">Unit Cost:</label><br />
								<div     class="input-group">
									<span  class="input-group-btn">
										<button type="button" class="btn btn-primary">$</button>
									</span>
									<input type="text" ng-model="editPurchase.unit_cost" class='form-control'>
								</div>
							</div>
						</div>
						<div class="row">	
							<div class="form-group col-md-3">
								<label  class="control-label">Invoice #:</label><br />
								<div     class="input-group">
									<input type="text" value="" ng-model="editPurchase.invoice_number" class='form-control'>
								</div>
							</div>
							<div class="form-group col-md-4">
								<label class="control-label">Invoice Date:</label><br />
								<div    class="input-group">
									<span class="input-group-btn">
										<button type="button" class="btn btn-primary" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
									</span>
									<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="editPurchase.invoice_date" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="false" close-text="Close" />
            					</div>
        					</div>
	                    </div> <!-- END ROW -->
	                    <div class="row">
							<div class="form-group col-md-7">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="editPurchase.notes" value="" class='form-control' rows='2'></textarea>
							</div>
	                    </div>
	                    <br />
	                    
	                    <div class="row">
							<div class="form-group col-md-12">
								<label ng-if="editPurchase.units" class="control-label" style="font-weight: bold;">Attach Document/Image:</label><br /><br />
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
					 		<button ng-if="editPurchase.invoice_date" type="button" class="btn btn-primary" ng-click="editPurchase2(editPurchase.id)" ng-disabled="!frmEditPurchase.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                    	</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END INCREASE/DECREASER QUANTITY MODAL WINDOW -->  	
		
	
    <!-- Main Container Starts -->

    	<table>		
			<thead>
				<tr>
					<th style="text-align:center;" >Reason</th>
					<th style="text-align:center;" >Entered By</th>
					<th style="text-align:center;" >Date</th>
					<th style="text-align:center;" >Adjusted Qty.</th>
					<th style="text-align:center;" >Ending Qty.</th>
					<th style="text-align:center;" >Invoice #</th>
					<th style="text-align:center;" >Invoice Date</th>
					<th style="text-align:center;" >Unit Price</th>
					<th style="text-align:center;" >Total Price</th>
					<th style="text-align:center;" >Notes</th>
					<th style="text-align:center;" >Attachment</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='material in materialsList'>
					<td style="text-align:center;" data-title="Reason">{{material.reason}} &nbsp;
						<a ng-if="material.reason=='Purchase'" class="glyphicon glyphicon-edit"   style="cursor: pointer;" title="Edit material" ng-click="editPurchaseModal(material.id);"></a>
					</td>
					<td style="text-align:center;" data-title="Entered By">{{material.first_name}} {{material.last_name}}</td>
					
					<td style="text-align:center;" data-title="Date">{{material.time_stamp}}</td>
					<td style="text-align:center;" ng-if="material.added" data-title="Adjusted Qty.">{{material.added}}</td>
					<td style="text-align:center;" ng-if="material.subtracted" data-title="Adjusted Qty.">-{{material.subtracted}}</td>
					<td style="text-align:center;" data-title="Ending Qty.">{{material.ending_qty}}</td>	
					<td style="text-align:center;" ng-if="material.invoice_number" data-title="Invoice #">{{material.invoice_number}}</td>	
					<td style="text-align:center;" ng-if="material.invoice_number==NULL" data-title="Invoice #">N/A</td>	
					<td style="text-align:center;" ng-if="material.invoice_date" data-title="Invoice Date">{{material.invoice_date}}</td>	
					<td style="text-align:center;" ng-if="material.invoice_date==NULL" data-title="Invoice Date">N/A</td>	
					<td style="text-align:center;" ng-if="material.unit_cost" data-title="Unit Price">{{material.unit_cost}}</td>	
					<td style="text-align:center;" ng-if="material.unit_cost" data-title="Total Price">{{material.invoice_number*material.added}}</td>	
					<td style="text-align:center;" ng-if="material.notes" data-title="Notes">{{material.notes}}</td>	
					<td style="text-align:center;" ng-if="material.notes==NULL || !material.notes" data-title="Notes">N/A</td>	

					<td data-title="Attachment	">
						<div ng-repeat='file in fileList' ng-if="file.invoice_id==material.id"> 
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
							<br />
						</div>
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


<script src="<?=$rootScope["RootUrl"]?>/includes/app/materials.js"></script>


<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>