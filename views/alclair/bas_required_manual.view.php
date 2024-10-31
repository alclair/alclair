<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="BAs_Required_Manual">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">

            

	<div class="row alert" style='background-color:#ddd;'>
	<div class="row">
			<div class="form-group col-md-1">
		   		<label class="control-label"></label><br />
			</div>
			<div class="form-group col-md-1">
		   		<label class="control-label"></label><br />
			</div>
			<div class="form-group col-md-1">
		   		<label class="control-label"></label><br />
			</div>  
            <div class="form-group col-md-1">
		   		<label class="control-label">Versa:</label><br />
		   		<input type="text" ng-model="iem.versa"  class="form-control" style="text-align:center;"> 
			</div>  
			<div class="form-group col-md-1">
		   		<label class="control-label">Dual XB:</label><br />
		   		<input type="text" ng-model="iem.dual_xb" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">ST3:</label><br />
		   		<input type="text" ng-model="iem.st3"  class="form-control" style="text-align:center;"> 
			</div>  
			<div class="form-group col-md-1">
		   		<label class="control-label">Tour:</label><br />
		   		<input type="text" ng-model="iem.tour" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">RSM:</label><br />
		   		<input type="text" ng-model="iem.rsm" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">CMVK:</label><br />
		   		<input type="text" ng-model="iem.cmvk" class="form-control" style="text-align:center;"> 
			</div> 
			
	</div>
	<div class="row">	
			<div class="form-group col-md-1">
		   		<label class="control-label"></label><br />
			</div>
			<div class="form-group col-md-1">
		   		<label class="control-label"></label><br />
			</div>
			<div class="form-group col-md-1">
		   		<label class="control-label">Spire:</label><br />
		   		<input type="text" ng-model="iem.spire" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">Studio4:</label><br />
		   		<input type="text" ng-model="iem.studio4" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">RevX:</label><br />
		   		<input type="text" ng-model="iem.revx" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">Electro:</label><br />
		   		<input type="text" ng-model="iem.electro" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">ESM:</label><br />
		   		<input type="text" ng-model="iem.esm" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">DKM:</label><br />
		   		<input type="text" ng-model="iem.dkm" class="form-control" style="text-align:center;"> 
			</div> 
			<div class="form-group col-md-1">
		   		<label class="control-label">ICON:</label><br />
		   		<input type="text" ng-model="iem.icon" class="form-control" style="text-align:center;"> 
			</div>   
			<div class="col-md-3">
			<br />
				<button style="border-radius: 4px; border-color: blue; margin-top:10px; width: 140px" class="btn btn-warning" ng-click="LoadData($event)">RUN</button>
			</div>	          
        </div>        
	</div>
        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
				
		</br>
		
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Balanced Armature</th>
					<th style="text-align:center;">Casing & Before</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='the_part in PartsList'>
					<td  style="text-align:center;" data-title="Monitor">{{the_part.part}}</td>
					<td  style="text-align:center;" data-title="Casing">{{the_part.casing_quantity}}</td>
				</tr>
			</tbody>
		</table>
		
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Damper</th>
					<th style="text-align:center;">Casing & Before</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='the_damper in DampersList'>
					<td  style="text-align:center;" data-title="Damper">{{the_damper.damper}}</td>
					<td  style="text-align:center;" data-title="Casing">{{the_damper.casing_quantity}}</td>
				</tr>
			</tbody>
		</table>
    <?php
	    // FORM FOR TRD COMPANY
	    } 
	?>

<!--		 
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
-->

	</div>
	

    <!--Edit Popup Window End-->
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/BAs_Required_Manual.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>