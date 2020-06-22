<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="Manufacturing_Screen_For_Marc">
<!--<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>-->
<!--<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/swdapp_manufacturing_screen.css">-->
<script src="https://d3js.org/d3.v4.min.js"></script>
<div class="row">
	 <div class="form-group col-lg-6" >
		 <!--<br/><br/><br/><br/>-->
		 <h1 style="font-weight:bold;font-size: 40px" align=center> TOTAL SHIPPED</h1><br/>
		 <h1 style="font-weight:bold;font-size: 40px;font-style: italic;margin-top:-40px" align=center>{{last_year}} <span style="margin-left:300px"> {{this_year}} </span></h1><br/>
		 <h1 style="font-weight:bold;font-size: 40px;margin-top:-40px" align=center><span style="color:red">{{Shipped_Last_Year}} </span> <span style="margin-left:300px;color:blue"> {{Shipped_This_Year}} </span></h1><br/>
		 
		 <h1 style="font-weight:bold;font-size: 40px" align=center> {{this_year}}</h1><br/>
		 <h1 style="font-weight:bold;font-size: 40px;font-style: italic;margin-top:-40px" align=center>{{last_month}} 
			 <span style="margin-left:300px"> {{this_month}} </span></h1><br/>
		 <h1 style="font-weight:bold;font-size: 40px;margin-top:-40px;" align=center>
			 <span style="color:green">{{Shipped_Last_Month}} </span> 
			 <span style="margin-left:330px;color:green"> {{Shipped_This_Month}} </span></h1><br/>

		 <h1 style="font-weight:bold;font-size: 40px" align=center> TURN AROUND TIME</h1><br/>
		 <h1 style="font-weight:bold;font-size: 40px;font-style: italic;margin-top:-40px" align=center>ORDERS<span style="margin-left:300px"> REPAIRS</span></h1><br/>
		 <h1 style="font-weight:bold;font-size: 40px;margin-top:-40px">
			 <span style="color:purple;margin-left:300px">{{avg}} </span> 
			 <span style="margin-left:400px;color:purple"> {{avg_repairs}} </span></h1><br/>
		 
		 <h1 style="font-weight:bold;font-size: 40px" align=center> NEW IEMS SHIPPED YESTERDAY </h1><br/>
		 <h1 style="font-weight:bold;font-size: 40px;margin-top:-30px;color:orange" align=center> {{orders_shipped_yesterday}} </h1><br/>
		 
		 <h1 style="font-weight:bold;font-size: 40px" align=center> NEW IEMS SHIPPED TODAY </h1><br/>
		 <h1 style="font-weight:bold;font-size: 40px;margin-top:-30px;color:orange" align=center> {{orders_shipped_today}} </h1><br/>
		 
	 </div>
 </div>

  <div class="row" >
	  <body>
	 	<div style="text-align: center;margin" >
		 	<span style="font-size:40px; text-align: center" > Choose plot: </span> 
	 		<select style="width: 200px; height: 60px;font-size:40px" ng-model='the_plot' ng-options="marc.value as marc.label for marc in marc_plot" ng-blur="reloadPage()"></select>
		</div>
	  </body>	
  </div>
  <br/>
  <div class="row" >
		<body>
	 	<div style="text-align: center;margin" ng-show="the_plot == 'day'" >
		 	<span style="font-size:40px; text-align: center" > Month: </span> 
		 	<select style="width: 120px; height: 60px;font-size:40px" ng-model="month_month" ng-options="month.value as month.label for month in monthRange" 
			 	ng-blur="RefreshPage()">
			 </select>
	 	</div>
        
        <div style="text-align:center;" ng-if="the_plot == 'day'">
	        <h1 style="color:rgba(47, 18, 232, 0.5);font-size:80px; font-weight: bold; padding-bottom:40px">{{THE_MONTH}} SHIPPED BY DAY</h1>    

        </div>  
        
        <div style="text-align:center;" ng-if="the_plot == 'month'">
	        <h1 style="color:rgba(47, 18, 232, 0.5);font-size:80px; font-weight: bold; padding-bottom:40px">SHIPPED BY MONTH</h1>    
	        
        </div>  
        <div style="text-align:center;" >
        <svg class="bar-chart" ></svg>
        >/div>
     </body>
  </div>
  <!--
  <div class="row" ng-show="plot_number > 6">
	<body>
		<div style="text-align:center;">
	        <h1 style="color:rgba(47, 18, 232, 0.5);font-size:80px; font-weight: bold; padding-bottom:40px">SHIPPED BY MONTH</h1>    
	        <svg class="bar-chart3" ></svg>
        </div>  
	</body>
</div>
-->
<script src="<?=$rootScope["RootUrl"]?>/includes/app/MarcPlot2.js"></script>
</div>

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>