<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<!-- PAGE REFRESH EVERY 5 MINUTES -->
<meta http-equiv="refresh" content="600" >

<div ng-controller="Manufacturing_Screen">

        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
 <div class="row">
	 <div class="form-group col-lg-6" >
		 <br/><br/><br/><br/>
		 <h1 style="font-weight:bold" align=center> SHIPPED IN {{last_year}} </h1><br/>
		 <h1 style="font-weight:bold;color:red" align=center> {{Shipped_Last_Year}} </h1><br/>
		 <h1 style="font-weight:bold" align=center> SHIPPED IN {{last_year}} IN {{this_month}} </h1><br/>
		 <h1 style="font-weight:bold;color:red" align=center> {{Shipped_Last_Year_This_Month}} </h1><br/>
		 <h1 style="font-weight:bold" align=center> SHIPPED IN {{this_year}} </h1><br/>
		 <h1 style="font-weight:bold;color:blue" align=center> {{Shipped_This_Year}} </h1><br/>
		 <h1 style="font-weight:bold" align=center> SHIPPED SO FAR IN {{this_month}} </h1><br/>
		 <h1 style="font-weight:bold;color:blue" align=center> {{Shipped_This_Month}} </h1><br/>
		 
	 </div>
	<div class="form-group col-lg-6">
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;font-size: 28px">CUSTOMER</th>
					<th style="text-align:center;font-size: 28px">STATION</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td style="text-align:center;font-size: 28px" data-title="CUSTOMER"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.designed_for}}</a></td>
					<td  style="text-align:center;font-size: 28px" data-title="STATION">{{order.status_of_order}}</td>
				</tr>
			</tbody>
		</table>
	</div>
 </div>
    <?php
	    // FORM FOR TRD COMPANY
	    } 
	?>

	</div>
	
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>