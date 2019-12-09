<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<!-- PAGE REFRESH EVERY 5 MINUTES -->
<meta http-equiv="refresh" content="600" >

<div ng-controller="Manufacturing_Screen_1">

        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
 <div class="row">
	 <div class="form-group col-lg-6" >
		 <!--<br/><br/><br/><br/>-->
		  <h1 style="font-weight:bold;font-size: 70px" align=center> TOTAL SHIPPED</h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;font-style: italic;margin-top:-40px" align=center>{{last_year}} <span style="margin-left:300px"> {{this_year}} </span></h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-40px" align=center><span style="color:red">{{Shipped_Last_Year}} </span> <span style="margin-left:300px;color:blue"> {{Shipped_This_Year}} </span></h1><br/>

		  <h1 style="font-weight:bold;font-size: 70px" align=center> {{this_year}}</h1><br/>
		<h1 style="font-weight:bold;font-size: 70px;font-style:italic;margin-top:-40px" align=center>{{last_month}}  <span style="margin-left:300px;"> {{this_month}} </h1><br/>
		<h1 style="font-weight:bold;font-size: 70px;margin-top:-40px;margin-left:230px" ><span style="color:green">{{Shipped_Last_Month}} </span> <span style="margin-left:340px;color:green"> {{Shipped_This_Month}} </span></h1><br/>

		 <!--
		 <h1 style="font-weight:bold;font-size: 70px" align=center> SHIPPED IN {{this_month}} {{last_year}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-30px;color:red" align=center> {{Shipped_Last_Year_This_Month}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px" align=center> SHIPPED IN {{this_year}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-30px;color:blue" align=center> {{Shipped_This_Year}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px" align=center> SHIPPED IN {{last_month}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-30px;color:blue" align=center> {{Shipped_Last_Month}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px" align=center> SHIPPED IN {{this_month}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-30px;color:blue" align=center> {{Shipped_This_Month}} </h1><br/>
		 -->
		 
		 <h1 style="font-weight:bold;font-size: 70px" align=center> TURN AROUND TIME</h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;font-style: italic;margin-top:-40px" align=center>ORDERS<span style="margin-left:300px"> REPAIRS</span></h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-40px">
			 <span style="color:purple;margin-left:180px">{{avg}} </span> 
			 <span style="margin-left:490px;color:purple"> {{avg_repairs}} </span>
		</h1><br/>
		 
		 <h1 style="font-weight:bold;font-size: 70px" align=center> NEW IEMS SHIPPED</h1><br/>
		  <h1 style="font-weight:bold;font-size: 70px;font-style: italic;margin-top:-40px" align=center>YESTERDAY<span style="margin-left:300px"> TODAY</span></h1><br/>
		  <h1 style="font-weight:bold;font-size: 70px;margin-top:-40px">	
			 <span style="color:orange;margin-left:180px">{{orders_shipped_yesterday}} </span> 
			 <span style="margin-left:500px;color:orange"> {{orders_shipped_today}} </span>
			</h1><br/>
		  		 
	 </div>
	<div class="form-group col-lg-6">
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;font-size: 44px">CUSTOMER</th>
					<th style="text-align:center;font-size: 44px">STATION</th>
					<!--<th style="text-align:center;font-size: 28px">TYPE</th>-->
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td ng-if="order.type==1" style="text-align:center;font-size: 44px" data-title="CUSTOMER"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.customer}}</a></td>
					<td  ng-if="order.type==1" style="text-align:center;font-size: 44px" data-title="STATION">{{order.status}}</td>

					<td ng-if="order.type==2" style="text-align:center;font-size: 44px" data-title="CUSTOMER"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_repair_form/{{order.id}}">{{order.customer}}</a></td>
					<td  ng-if="order.type==2" style="text-align:center;font-size: 44px;background-color:orange" data-title="STATION">{{order.status}}</td>
					<!--<td  style="text-align:center;font-size: 28px" data-title="STATION">{{order.type}}</td>-->
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