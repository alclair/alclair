<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<!-- PAGE REFRESH EVERY 5 MINUTES -->
<!--<meta http-equiv="refresh" content="600" >-->

<div ng-controller="Manufacturing_Screen_1">

        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
 <div class="row">
	 <div class="form-group col-lg-12" >
		 <!--
		 <h1 style="font-weight:bold;font-size: 70px" align=center> NEW IEMS SHIPPED</h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;font-style: italic;margin-top:-40px" align=center>YESTERDAY<span style="margin-left:300px"> TODAY</span></h1><br/>
		  <h1 style="font-weight:bold;font-size: 70px;margin-top:-40px">	
		 <span style="color:orange;margin-left:580px">{{orders_shipped_yesterday}} </span> 
			 <span style="margin-left:500px;color:orange">&nbsp;&nbsp; {{orders_shipped_today}} </span>
			</h1><br/>
		-->
		 <h1 style="font-weight:bold;font-size: 70px" align=center> SHIPPED TODAY BY PRODUCT LINE </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;font-style: italic;margin-top:-40px" align=center>
			 AUDIO
			 <span style="margin-left:100px">  HP </span>
			 <span style="margin-left:150px">  OUTDOOR </span>
			 <span style="margin-left:200px">  IFB/SEC </span>
			 <span style="margin-left:250px">  MOTO </span>
		 </h1>
		 <br/>
		 
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-40px">	
			 <span style="color:orange;margin-left:175px">
			 	 {{orders_shipped_today}}
			 </span> 
			 <span style="color:green;margin-left:205px">
			 	{{hp_shipped_today}}
			 </span> 
				<span style="margin-left:310px;color:red">
				{{outdoor_shipped_today}}
			</span>
			<span style="color:blue;margin-left:445px">
			 	{{ifb_shipped_today}}
			 </span> 
			<span style="margin-left:435px;color:black">
				{{moto_shipped_today}}
			</span>
		</h1><br/>	
		
		<h1 style="font-weight:bold;font-size: 70px" align=center> TOTAL SHIPPED</h1>
		 <br/>
<!--		<h1 style="font-weight:bold;font-size: 70px" align=center> {{this_year}}</h1><br/> -->
		
		<!-- <h1 style="font-weight:bold;font-size: 70px;font-style:italic;margin-top:-40px" align=center>{{last_month}}  
			<span style="margin-left:260px;"> 			{{this_month}} 
		</h1><br/>
		-->
		<h1 style="font-weight:bold;font-size: 70px;font-style:italic;margin-top:-40px" align=center>TODAY
			<span style="margin-left:260px;"> 			{{this_month}} 
			<span style="margin-left:260px;"> 			{{this_year}} 
		</h1><br/>
				 		
		<h1 style="font-weight:bold;font-size: 70px;margin-top:-40px;margin-left:460px" >
			<span style="color:green"> {{all_shipped_today}}  </span> 
			<span style="margin-left:500px;color:green"> {{Shipped_This_Month}} </span>
			<span style="margin-left:360px;color:blue"> {{Shipped_This_Year}}  </span>
		</h1><br/>
		<br/>
		
			
		 <h1 style="font-weight:bold;font-size: 70px" align=center> TURN AROUND TIME</h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;font-style: italic;margin-top:-40px" align=center>ORDERS<span style="margin-left:300px"> REPAIRS</span></h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-40px">
			 <span style="color:purple;margin-left:680px">{{avg}} </span> 
			 <span style="margin-left:490px;color:purple"> {{avg_repairs}} </span>
		</h1><br/>
	
		

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
		 
				 
		 
		  		 
	 </div>
	 <!--
	<div class="form-group col-lg-6">
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;font-size: 44px">CUSTOMER</th>
					<th style="text-align:center;font-size: 44px">STATION</th>

				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td ng-if="order.type==1" style="text-align:center;font-size: 44px" data-title="CUSTOMER"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.customer}}</a></td>
					<td  ng-if="order.type==1" style="text-align:center;font-size: 44px" data-title="STATION">{{order.status}}</td>

					<td ng-if="order.type==2" style="text-align:center;font-size: 44px" data-title="CUSTOMER"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_repair_form/{{order.id}}">{{order.customer}}</a></td>
					<td  ng-if="order.type==2" style="text-align:center;font-size: 44px;background-color:orange" data-title="STATION">{{order.status}}</td>

				</tr>
			</tbody>
		</table>
	</div>
	-->
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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ManufacturingScreenCtrl.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>