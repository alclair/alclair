<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Orders">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
	            
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Repairs </a> (Total: {{TotalRecords}}) <span style="color:red"> (# with Fit Issues: {{OrdersWithFit}})</span></b>&nbsp;&nbsp;
                
            </div>
            <div class="form-group col-sm-3">
	            
	            <b ng-click="sound_modal()" style="font-size:20px;cursor: pointer"> </a> Function Issues: {{TotalSound}}  </b>&nbsp;&nbsp;
				<!--
				<input type="text" id="barcode_orders" ng-model="qrcode.barcode" placeholder="Barcode"  class="form-control" autofocus="autofocus">
				-->
			</div>
			<div class="col-md-2">
				<b ng-click="fit_modal()" style="font-size:20px;cursor: pointer"> </a> Fit Issues: {{TotalFit}}</b>&nbsp;&nbsp;
			</div>
            <div class="col-md-3">
	            <b ng-click="design_modal()" style="font-size:20px;cursor: pointer"> </a> Aesthetics Issues: {{TotalDesign}}</b>&nbsp;&nbsp; <!-- Design -->
                <!--
                <input type="checkbox" ng-model="rush_or_not" ng-true-value="1" ng-false-value="0" style="margin-top:16px"> &nbsp; <b style="font-size:14px;color:gray">RUSH ORDERS ONLY</b><br />
                -->
            </div>
            
            <!--<div class="col-md-2" style="margin-top:-10px;padding-bottom:10px;text-align:left;	" >
                <a type="button" class="btn btn-danger" href="<?=$rootScope['RootUrl']?>/alclair/qc_form">
					<span class="glyphicon glyphicon-pencil"></span> &nbsp;&nbsp;QC FORM
				</a>
            </div>-->
            <!--
            <div class="col-md-6" style="text-align:right;">
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
            -->
        </div>
        
		<!--<div class="row alert" style='background-color:#ddd;'>-->
            
            
		<div class="row alert" style='background-color:#ddd;'>
			
            <div class="form-group col-sm-1">                  
                <!--<select class='form-control' ng-model='build_type_id' ng-options="buildType.id as buildType.type for buildType in buildTypeList">
					<option value="">Select a build type</option>
				</select>-->
            </div>
            <div class="col-sm-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchStartDate" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>				
			</div>
			<div class="form-group col-sm-1">
                <select class='form-control' ng-model='month_range' ng-options="month.value as month.label for month in MONTH_RANGE">
                </select>
            </div>	
            <div class="form-group col-sm-1">       
				<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="reload_page()">
					<i class="fa fa-refresh"></i> &nbsp; RELOAD
				</button>
				<!--<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Monitor</option>
				</select>-->
            </div>
             <div class="form-group col-sm-7">       
				 	<b style="font-size:30px;cursor: pointer"> </a> # of Orders: {{TotalRecords2}} -> {{percentFitIssues}}% have fit issues!</b>&nbsp;&nbsp;
            </div>
			<!--
			<div class="form-group col-sm-2">
                <select class='form-control' ng-model='order_status_id' ng-options="orderStatus.order_in_manufacturing as orderStatus.status_of_order for orderStatus in orderStatusTableList">
	                <option value="">-- All States --</option>
                </select>
            </div>	
            -->

			<!--
			<div class="col-sm-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchEndDate" is-open="openedEnd" datepicker-options="dateOptions"  ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openEnd($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
			</div>
			-->
			<!--
            <div class="col-sm-2">
				<div class="input-group">
					<input type="text"  ng-model="SearchText" placeholder="Name or order ID"  uib-typeahead="customer as customer.designed_for for customer in customers| filter:{designed_for:$viewValue}| limitTo:8"  typeahead-on-select="SearchText=$item.designed_for" typeahead-editable="true" class="form-control" >		
					<a class="form-control-feedback form-control-clear" ng-click="ClearSearch()" style="pointer-events: auto; text-decoration: none;cursor: pointer;margin-top:10px;"></a>
					<span class="input-group-btn">
						<button class="btn btn-primary js-new pull-right" ng-click="Search();">
							<span class="glyphicon glyphicon-search"></span> 
						</button>
					</span>
				</div>
            </div>
			-->
		</div>
        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;"><span ng-click="test_this('Designed For')" style="cursor: pointer"> Designed For </span></th>
					<th style="text-align:center;"><span ng-click="test_this('RMA #')" style="cursor: pointer"> RMA # </span></th>
					<th style="text-align:center;"><span ng-click="test_this('Model')" style="cursor: pointer"> Model </span></th>
					
					<th style="text-align:center;"><span ng-click="test_this('# of RMAs')" style="cursor: pointer"> # of RMAs </span></th>
					<th style="text-align:center;"><span ng-click="test_this('Impression Color')" style="cursor: pointer"> Impression Color </span></th>
					<th style="text-align:center;"><span ng-click="test_this('Impressions Detailed')" style="cursor: pointer"> Impressions Detailed </span></th>
					<th style="text-align:center;"><span ng-click="test_this('Manufactured Date')" style="cursor: pointer"> Manufactured Date </span></th>
					
					<th style="text-align:center;"><span ng-click="test_this('Repair Received')" style="cursor: pointer"> Repair Received </span></th>
					<th style="text-align:center;"><span ng-click="test_this('Repair Shipped')" style="cursor: pointer"> Repair Shipped </span></th>
					
					
					
					<th style="text-align:center; width: 5%"> Function</th> <!-- Sound -->
					<th style="text-align:center; width: 5%"> Fit </th>
					<th style="text-align:center; width: 5%"> Aesthetics</th> <!-- Design -->
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id_of_order}}">{{order.customer_name}}</a></td>
					<td style="text-align:center;" data-title="RMA #"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_repair_form/{{order.id_of_repair}}">{{order.rma_number}}</a></td>
					
					<td  style="text-align:center;" data-title="Model">{{order.model_name}}</td>					
					
					<td  style="text-align:center;" data-title="# of RMAs">{{order.num_of_repairs_from_order_id}}</td>
					<td  style="text-align:center;" data-title="Impression Color">{{order.impression_color}}</td>
					<td  style="text-align:center;" data-title="Impressions Detailed">{{order.order_detailed}}</td>
					<td  style="text-align:center;" data-title="Done Date">{{order.date_done}}</td>					
					
					<td  style="text-align:center;" data-title="Repair Received">{{order.rma_received}}</td>
					<td  style="text-align:center;" data-title="Repair Shipped">{{order.rma_shipped}}</td>
					
					
					<td  class="bg-danger" ng-if="order.sound!='X'" style="text-align:center;" data-title="Sound">{{order.sound}}</td>
					<td  class="bg-success" ng-if="order.sound=='X'" style="text-align:center;" data-title="Sound">{{order.sound}}</td>
					
					<td   class="bg-danger" ng-if="order.fit!='X'" style="text-align:center;" data-title="Fit">{{order.fit}}</td>
					<td  class="bg-success" ng-if="order.fit=='X'" style="text-align:center;" data-title="Fit">{{order.fit}}</td>
				
					<td   class="bg-danger" ng-if="order.design!='X'" style="text-align:center;" data-title="Design">{{order.design}}</td> 
					<td  class="bg-success" ng-if="order.design=='X'" style="text-align:center;" data-title="Design">{{order.design}}</td> 
					
					<!--					
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    
		                    &nbsp;&nbsp;<button ng-disabled="order.highrise != 1" type="button" class="btn btn-primary btn-xs" ng-click="PDF(order.id);">Traveler</button>					
						<?php if($_SESSION["UserName"] == 'Scott' || $_SESSION["UserName"] == 'admin') { ?>
							&nbsp;&nbsp;<button ng-disabled="order.status_of_order == 'Done'" type="button" class="btn btn-primary btn-xs" ng-click="LoadSelectDateModal(order.id);">DONE</button>		
						<?php } ?>

							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Traveler" href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}"></a>		
	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteForm(order.id);"></a>
						</div>

                    </td>
                    -->
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
	
<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="displaySound" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmDisplaySound">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Function Faults</h4> <!-- Sound -->
                    </div>
                    <div class="modal-body" style="margin-left:80px">
	                    <b style="font-size:20px"> </a> No Signal: <span style = "color:red"> {{one}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Cutting In & Out: <span style = "color:red"> {{two}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Unbalanced: <span style = "color:red"> {{three}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Distortion/Rattling/Farting: <span style = "color:red"> {{four}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> No Highs: <span style = "color:red"> {{five}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> No Lows/Mids: <span style = "color:red"> {{six}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Other: <span style = "color:red"> {{seven}} </span> </b>&nbsp;&nbsp;<br/>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->    
	
	<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="displayFit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmDisplayFit">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Fit Faults</h4>
                    </div>
                    <div class="modal-body" style="margin-left:80px">
	                    <b style="font-size:20px"> </a> Canal Too Tight: <span style = "color:red"> {{one}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Canal Too Loose: <span style = "color:red"> {{two}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Canal Too Long: <span style = "color:red"> {{three}}  </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Canals Too Short: <span style = "color:red"> {{four}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Helix pain: <span style = "color:red"> {{five}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Shells Too Tall: <span style = "color:red"> {{six}}   </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Wrong Impressions: <span style = "color:red"> {{seven}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Canal tip pain: <span style = "color:red"> {{eight}}  </span></b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Reshell W/ New Impression(s): <span style = "color:red"> {{fifteen}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Shells Too Short: <span style = "color:red"> {{sixteen}}   </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Monitor Does Not Fit: <span style = "color:red"> {{seventeen}}   </span> </b>&nbsp;&nbsp;<br/>
	                    
	                    <!-- <b style="font-size:20px"> </a> Shells Too Tall: <span style = "color:red"> {{sixteen}}   </span> </b>&nbsp;&nbsp;<br/>-->
	                     <!--
	                    <b style="font-size:20px"> </a> No seal on left: <span style = "color:red"> {{nine}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> No seal on right: <span style = "color:red"> {{ten}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> No seal on both: <span style = "color:red"> {{eleven}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Re-shell left: <span style = "color:red"> {{twelve}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Re-shell right: <span style = "color:red"> {{thirteen}}  </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Re-shell both: <span style = "color:red"> {{fourteen}} </span> </b>&nbsp;&nbsp;<br/>
	                    -->
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->    
	
	<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="displayDesign" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmDisplayDesign">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Design Faults</h4>
                    </div>
                    <div class="modal-body" style="margin-left:80px">
	                    <b style="font-size:20px"> </a> Incorrect Shells(s) Color: <span style = "color:red"> {{one}} </span>  </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Incorrect Faceplate(s) Color: <span style = "color:red"> {{two}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Incorrect Tip(s) Color: <span style = "color:red"> {{three}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Incorrect Artwork: <span style = "color:red"> {{four}} </span>  </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Incorrect Name Label: <span style = "color:red"> {{five}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Faceplate(s) Popped Off: <span style = "color:red"> {{six}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Jack(s) Crooked/Mismatched: <span style = "color:red"> {{seven}} </span> </b>&nbsp;&nbsp;<br/>
	                    
	                    <b style="font-size:20px"> </a> Jack(s) Plugged: <span style = "color:red"> {{eight}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Visual Blemish: <span style = "color:red"> {{nine}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Unmatched Shell Height: <span style = "color:red"> {{ten}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Unmatched Canal Length: <span style = "color:red"> {{eleven}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Cracked/Broken Shell & Faceplate: <span style = "color:red"> {{twelve}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Jack(s) Pushed In: <span style = "color:red"> {{thirteen}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Jack(s) Loose: <span style = "color:red"> {{fourteen}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Driver Detached from Tube: <span style = "color:red"> {{fifteen}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Wire Disconnected/Broken: <span style = "color:red"> {{sixteen}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Floating/Rattling Components: <span style = "color:red"> {{seventeen}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Shell Too Thin: <span style = "color:red"> {{eighteen}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> New Design: <span style = "color:red"> {{nineteen}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Ownership Transfer: <span style = "color:red"> {{twenty}} </span> </b>&nbsp;&nbsp;<br/>
	                    <b style="font-size:20px"> </a> Crushed or Punctured Damper(s): <span style = "color:red"> {{twentyone}} </span> </b>&nbsp;&nbsp;<br/>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->     
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_DBR.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>