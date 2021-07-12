<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/swdapp_manufacturing_screen.css">
<!--<script src="<?=$rootScope["RootUrl"]?>/js/d3.min.js"></script>
<script src="<?=$rootScope["RootUrl"]?>/js/d3.tip.v0.6.3.js"></script>-->
<!--<script src="<?=$rootScope["RootUrl"]?>/js/manufacturing_screen_alclair.js"></script>-->

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.9.2/d3.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.9.2/d3.min.js"></script>-->
<script src="https://d3js.org/d3.v4.min.js"></script>

<!-- PAGE REFRESH EVERY 5 MINUTES -->
<meta http-equiv="refresh" content="600" >

<div ng-controller="Manufacturing_Screen_2">

        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>  

<!--
 <div class="row">
	 <div class="form-group col-lg-6" >

		 <h1 style="font-weight:bold;font-size: 70px" align=center> TURN AROUND TIME FOR ORDERS </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-30px;color:blue" align=center> {{avg}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px" align=center> TURN AROUND TIME FOR REPAIRS </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-30px;color:green" align=center> {{avg_repairs}} </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px" align=center> NEW IEMS SHIPPED YESTERDAY </h1><br/>
		 <h1 style="font-weight:bold;font-size: 70px;margin-top:-30px;color:green" align=center> {{orders_shipped_yesterday}} </h1><br/>

	 </div>
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
 </div>
 -->
<!--
  <div class="row">
   <div style="width: 95%; margin-left: auto; margin-right: auto;">        
        <div style="text-align: center;font-size: 44px">

             <div style="text-align: center;font-size: 44px"> </div>
            <br />
            <div class="col-md-12">
                <span ng-repeat="label5 in labelRange5" >
                    <span style="display: inline-block; width: 40px; height: 40px; background: {{label5.color}}; "></span><span style="font-size:44px">{{label5.text}}
                    </span>
                </span>
            </div>
            <br />
        </div>
        <div id="impressions_received_date"></div>
    </div>
  </div>
  <div class="row">
  		<div style="width: 95%; margin-left: auto; margin-right: auto;">        
		</div>
	</div>
	-->
	<!--
	<script src="<?=$rootScope["RootUrl"]?>/js/GroupedBarChart.js"></script>
	<script>
		var groupedBarData = [
			{category: 'sam', val1: 10, val2: 13, val3: 5},
			{category: 'jenna', val1: 20, val2: 11, val3: 15},
			{category: 'peter', val1: 11, val2: 13, val3: 9},
		];
		var groupedBarChart = d3.select("#grouped-bar-chart")
			.append("svg")
			.chart("GroupedBarChart")
			.width(200)
			.height(150);
		groupedBarChart.draw(groupedBarData);
	</script>
	-->
	<head>
        <title>Learn D3.js</title>
    </head>
    <body>
        <div style="text-align:center;">
	        <h1 style="color:rgba(47, 18, 232, 0.5);font-size:80px; font-weight: bold; padding-bottom:40px">SHIPPED BY MONTH</h1>    
	        <svg class="bar-chart" ></svg>
        </div>    
     </body>

    <?php
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