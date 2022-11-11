<!DOCTYPE html>
<html lang="en"  ng-app="swdApp">
  <head> 
  	<!--
  	<link rel="shortcut icon" href="<?=$rootScope["RootUrl"]?>/includes/OTISicon24X242.png" />
	<link rel="icon" type="image/png" href="<?=$rootScope["RootUrl"]?>/includes/OTISicon24X242.png" />
	<link rel="icon" href="../../favicon2.ico">
	->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <meta http-equiv='cache-control' content='no-cache'>
	<meta http-equiv='expires' content='0'>
	<meta http-equiv='pragma' content='no-cache'>
    
    <title><?=$rootScope["site_name"]?></title>

    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/bootstrap.js"></script>
	<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/bootstrap.min.css" media="screen,print">
	<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/bootstrap.css" media="screen,print">
    <link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/bootstrap/cosmo.min.css" media="screen,print">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?=$rootScope["m_Theme"]?>/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/jquery-ui/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/font-awesome/css/font-awesome.min.css" media="screen,print">
    <link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/toastr.min.css" media="screen,print">
    <!--<link href="$rootScope["RootUrl"]/css/matrixplus.css" rel="stylesheet">-->
    <link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/angular-animate.css" media="screen,print">
    <link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/js/jquery-ui/jquery-ui.css" media="screen,print">
	<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/site.css" media="screen,print">
    <link href="<?=$rootScope["RootUrl"]?>/css/lightbox.css" rel="stylesheet" />
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/moment.js"></script>
    
    <!--<script src="<?=$rootScope["RootUrl"]?>/js/angular.1.4.7.js"></script>-->
    <script src="<?=$rootScope["RootUrl"]?>/js/angular-1.5.0/angular.js"></script>
    
    <script src="<?=$rootScope["RootUrl"]?>/js/ui-bootstrap-tpls-0.14.3.js"></script>
    <script src="<?=$rootScope["RootUrl"]?>/js/lightbox-2.6.js"></script>
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/angular-file-upload-shim.js"></script>
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/angular-file-upload.js"></script>
    
    <!--<script src="<?=$rootScope["RootUrl"]?>/js/angular-animate.1.4.7.js"></script>
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/angular-sanitize.1.4.7.js"></script>
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/angular-cookies.1.4.7.js"></script>-->
    <script src="<?=$rootScope["RootUrl"]?>/js/angular-1.5.0/angular-animate.js"></script>
    <script src="<?=$rootScope["RootUrl"]?>/js/angular-1.5.0/angular-sanitize.js"></script>
    <script src="<?=$rootScope["RootUrl"]?>/js/angular-1.5.0/angular-cookies.js"></script>
    
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/toastr.min.js"></script>
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/ng-pattern-restrict.min.js"></script>

    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/blockui.js"></script>
    <script type="text/javascript" src="<?=$rootScope["RootUrl"]?>/js/jquery-inputmask.js"></script>
    <script src="<?=$rootScope["RootUrl"]?>/includes/app/app.js"></script>
	<script src="<?=$rootScope["RootUrl"]?>/includes/app/HorMenuCtrl.js"></script>
    <script src="<?=$rootScope["RootUrl"]?>/includes/app/service.js"></script>
    
    <!--TYLER CODE - NEXT FOUR LINES ARE FOR ANGULAR MOMENT PICKER-->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.js"></script>
	<script src="//cdn.rawgit.com/indrimuska/angular-moment-picker/master/dist/angular-moment-picker.min.js"></script>
	<link href="//cdn.rawgit.com/indrimuska/angular-moment-picker/master/dist/angular-moment-picker.min.css" rel="stylesheet">
	
	<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/pluscharts-master/src/css/pluscharts.css">
	<!--<link rel="stylesheet" href="path-to/src/css/pluscharts.css">-->
	<script src="https://d3js.org/d3.v5.min.js"></script>
	<script src="<?=$rootScope["RootUrl"]?>/pluscharts-master/dist/pluscharts.js"></script>
	<!--<script src="path-to/dist/pluscharts.js"></script>-->


	<script type="text/javascript">
        window.cfg = {};
        window.cfg.rootUrl = "<?=$rootScope["RootUrl"]?>";
        window.cfg.apiUrl = "<?=$rootScope['APIUrl']?>";
        window.cfg.Controller = "<?=$rootScope['Controller']?>";
        window.cfg.Action = "<?=$rootScope['Action']?>";
        window.cfg.Id = "<?=$rootScope['Id']?>";
        window.cfg.Id = parseInt(window.cfg.Id);
        window.cfg.PageSize = "<?=$rootScope['PageSize']?>";
        window.cfg.PageSize = parseInt(window.cfg.PageSize);
		<?php
		$FirstOfYear="01/01/".date("Y");
		$CurrentMonthFirstDate=date("m")."/01/".date("Y");
		$CurrentDay=date("m")."/".date("d")."/".date("Y");
		
		$CurrentDay_minus30 = date("m/d/Y", strtotime("-1 months"));
		$CurrentDay_football = date("m/d/Y", strtotime("-6 hours"));
		$CurrentDay_plus_2weeks = date("m/d/Y", strtotime("+2 weeks"));
		$CurrentDay_plus_3weeks = date("m/d/Y", strtotime("+3 weeks"));
		$date=date_create($CurrentMonthFirstDate);
		date_add($date,date_interval_create_from_date_string("1 month"));
		date_add($date,date_interval_create_from_date_string("-1 day"));
		$CurrentMonthLastDate=date_format($date,"m/d/Y");
		$OctoberOne="10/1/2019";
		$TwoMonthsPrior = date("m/1/Y", strtotime("-1 months"));
		?>
		window.cfg.FirstOfYear="<?=$FirstOfYear?>";
		window.cfg.CurrentMonthFirstDate="<?=$CurrentMonthFirstDate?>";
		window.cfg.CurrentMonthLastDate="<?=$CurrentMonthLastDate?>";
		window.cfg.CurrentDay="<?=$CurrentDay?>";
		window.cfg.CurrentDay_football="<?=$CurrentDay_football?>";
		window.cfg.CurrentDay_plus_2weeks="<?=$CurrentDay_plus_2weeks?>";
		window.cfg.CurrentDay_plus_3weeks="<?=$CurrentDay_plus_3weeks?>";
		window.cfg.OctoberOne="<?=$OctoberOne?>";
		window.cfg.TwoMonthsPrior="<?=$TwoMonthsPrior?>";
		window.cfg.CurrentDay_minus30="<?=$CurrentDay_minus30?>";

	</script>
  </head>

  <body>

    <div style="width:100%;margin:0;">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"><	/span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=$rootScope["RootUrl"]?>">
				<?=$rootScope["site_name"]?>
			</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse" ng-controller="HorMenuCtrl">
            <ul class="nav navbar-nav">
				<?php if(!empty($_SESSION["UserId"])) { ?>
				
				<?php if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair"  ) { ?>	
				<?php
						if ( $_SESSION["UserName"] != 'Football' ) {
					?>
					<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/dashboard" class="nav-link "> Dashboard</a></li>
					
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">TAT <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/turn_around_time" class="nav-link  ">CIEMs </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/turn_around_time_repairs" class="nav-link  ">Repairs </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/turn_around_time_hearing_protection" class="nav-link  ">Hearing Protection </a></li>
						</ul>
					</li>
					
					
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Quality Control <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/qc_form" class="nav-link  ">QC Form </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/qc_list" class="nav-link  ">QC List</a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/qc_form_active_hp" class="nav-link  ">QC Form (Active HP) </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/qc_list_active_hp" class="nav-link  ">QC List (Active HP)</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Repairs <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/repair_form" class="nav-link  ">Repair Form </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/repair_list" class="nav-link  ">Repair List</a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/repair_pipeline_v2" class="nav-link "> Pipeline (2 Weeks)</a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/repairs_done_by_date" class="nav-link "> Repairs Done By Date</a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/admin/repairs_manufacturing" class="nav-link "> Repairs From Manufacturing </a></li>
						</ul>
					</li>
					<?php } ?> 
				<?php } ?> 
				
				<?php if ( $rootScope["SWDCustomer"] != "lng" && $rootScope["SWDCustomer"] != "trd" && $rootScope["SWDCustomer"] != "wwl" && $rootScope["SWDCustomer"] != "alclair" && $rootScope["SWDCustomer"] != "ifi"  ) { ?>	
					<li class="active"><a href="<?=$rootScope["RootUrl"]?>">Dashboard </a></li>
				<?php } ?> 
				

				<?php
					if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi") {
				?>
	             	<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reviewers <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/add_reviewer" class="nav-link  ">Add Reviewer </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/view_reviewers" class="nav-link  ">Reviewers </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/reviews_list" class="nav-link  ">Reviews</a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/shipping_request" class="nav-link  ">Shipping Request Form</a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/review_process" class="nav-link  ">Review Process </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/import_excel" class="nav-link  ">Import </a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Inventory <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<!--<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/inventory" class="nav-link  ">Inventory </a></li>-->
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/import" class="nav-link  ">Receiving US </a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi/export" class="nav-link  ">Shipping US</a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi_uk/receiving_uk" class="nav-link  ">Receiving UK</a></li>
							<li class=" "><a href="<?=$rootScope['RootUrl']?>/ifi_uk/shipping_uk" class="nav-link  ">Shipping UK</a></li>
						</ul>
					</li>
                <?php } ?> 

								
                <?php
					if ( $rootScope["SWDCustomer"] == "dev" ||  $rootScope["SWDCustomer"] == "alclair" ) {
						$test = "10111";
						$db_server_alclair="localhost";
						//$db_server_alclair="54.173.238.250";
						if ( $rootScope["SWDCustomer"] == "dev" ) {
							$db_database_alclair="ajswanso_alclair";
						} else {
							$db_database_alclair="ajswanso_alclair";
						}
						$db_user_alclair="postgres";
						$db_password_alclair="Gorilla1";
						
						$pdo_alclair=new PDO("pgsql:host=$db_server_alclair;dbname=$db_database_alclair;user=$db_user_alclair;password=$db_password_alclair");

						/*$query_alclair="SELECT 
					  				count(*) FILTER (WHERE order_status_id = 1)  AS start_cart,
					  				count(*) FILTER (WHERE order_status_id = 2)  AS impression_detailing,
					  				count(*) FILTER (WHERE order_status_id = 3)  AS shell_pouring,
					  				count(*) FILTER (WHERE order_status_id = 4)  AS shell_detailing,
					  				count(*) FILTER (WHERE order_status_id = 5)  AS casing,
					  				count(*) FILTER (WHERE order_status_id = 6)  AS finishing,
					  				count(*) FILTER (WHERE order_status_id = 7)  AS quality_control,
					  				count(*) FILTER (WHERE order_status_id = 8)  AS electronics_qc,
					  				count(*) FILTER (WHERE order_status_id = 9)  AS artwork,
					  				count(*) FILTER (WHERE order_status_id = 10)  AS ready_to_ship,
					  				count(*) FILTER (WHERE order_status_id = 11)  AS group_order_holding,
					  				count(*) FILTER (WHERE order_status_id = 12)  AS done,
					  				count(*) FILTER (WHERE order_status_id = 99)  AS order_received
					  				FROM import_orders WHERE active = TRUE";*/
					  			
					  	$query_alclair="SELECT 
					  				count(CASE WHEN order_status_id = 1 THEN 1 END) as start_cart,
					  				count(CASE WHEN order_status_id = 2 THEN 1 END) as impression_detailing,
					  				count(CASE WHEN order_status_id = 3 THEN 1 END) as shell_pouring,
					  				count(CASE WHEN order_status_id = 4 THEN 1 END) as shell_detailing,
					  				count(CASE WHEN order_status_id = 5 THEN 1 END) as casing,
					  				count(CASE WHEN order_status_id = 6 THEN 1 END) as finishing,
					  				count(CASE WHEN order_status_id = 7 THEN 1 END) as quality_control,
					  				count(CASE WHEN order_status_id = 8 THEN 1 END) as electronics_qc,
					  				count(CASE WHEN order_status_id = 9 THEN 1 END) as artwork,
					  				count(CASE WHEN order_status_id = 10 THEN 1 END) as ready_to_ship,
					  				count(CASE WHEN order_status_id = 11 THEN 1 END) as group_order_holding,
					  				count(CASE WHEN order_status_id = 12 THEN 1 END) as done,
					  				count(CASE WHEN order_status_id = 13 THEN 1 END) as holding,
					  				count(CASE WHEN order_status_id = 14 THEN 1 END) as holding_for_payment,
					  				count(CASE WHEN order_status_id = 15 THEN 1 END) as digital_impression_detailing,
					  				count(CASE WHEN order_status_id = 16 THEN 1 END) as pre_group_order_holding,
					  				count(CASE WHEN order_status_id = 17 THEN 1 END) as driver_purgatory,
					  				count(CASE WHEN order_status_id = 18 THEN 1 END) as hearing_protection,
					  				count(CASE WHEN order_status_id = 19 THEN 1 END) as exp_assembly,
					  				count(CASE WHEN order_status_id = 99 THEN 1 END) as order_received
					  				FROM import_orders WHERE active = TRUE";
					  	//$query_alclair = "SELECT username AS order_received FROM auth_user WHERE id=1";	
					  	//$query_alclair="SELECT order_status_id FROM import_orders WHERE id = 50";
					  	$stmt_alclair = pdo_query($pdo_alclair, $query_alclair, null);
					  	$row_alclair = pdo_fetch_array($stmt_alclair);
					  	
					  	/*$query_alclair_repair = "SELECT 
					  				count(*) FILTER (WHERE repair_status_id = 1)  AS clark_cart,
					  				count(*) FILTER (WHERE repair_status_id = 2)  AS cathy_cart,
					  				count(*) FILTER (WHERE repair_status_id = 3)  AS impression_detailing,
					  				count(*) FILTER (WHERE repair_status_id = 4)  AS shell_pouring,
					  				count(*) FILTER (WHERE repair_status_id = 5)  AS shell_detailing,
					  				count(*) FILTER (WHERE repair_status_id = 6)  AS casing,
					  				count(*) FILTER (WHERE repair_status_id = 7)  AS finishing,
					  				count(*) FILTER (WHERE repair_status_id = 8)  AS quality_control,
					  				count(*) FILTER (WHERE repair_status_id = 9)  AS electronics_qc,
					  				count(*) FILTER (WHERE repair_status_id = 10)  AS artwork,
					  				count(*) FILTER (WHERE repair_status_id = 11)  AS ready_to_ship,
					  				count(*) FILTER (WHERE repair_status_id = 12)  AS pickup,
					  				count(*) FILTER (WHERE repair_status_id = 13)  AS group_order_holding	,
					  				count(*) FILTER (WHERE repair_status_id = 14)  AS done,
					  				count(*) FILTER (WHERE repair_status_id = 15)  AS repair_on_hold,
					  				count(*) FILTER (WHERE repair_status_id = 99)  AS repair_received
					  				FROM repair_form WHERE active = TRUE";	*/
					  	$query_alclair_repair = "SELECT 
					  				count(CASE WHEN repair_status_id = 1 THEN 1 END) as repair_cart,
					  				count(CASE WHEN repair_status_id = 2 THEN 1 END) as diagnosing,					  				
					  				count(CASE WHEN repair_status_id = 3 THEN 1 END) as repair_reshell,
					  				count(CASE WHEN repair_status_id = 4 THEN 1 END) as shell_pouring,
					  				count(CASE WHEN repair_status_id = 5 THEN 1 END) as shell_detailing,
					  				count(CASE WHEN repair_status_id = 6 THEN 1 END) as casing,
					  				count(CASE WHEN repair_status_id = 7 THEN 1 END) as finishing,
					  				count(CASE WHEN repair_status_id = 8 THEN 1 END) as quality_control,
					  				count(CASE WHEN repair_status_id = 9 THEN 1 END) as electronics_qc,
					  				count(CASE WHEN repair_status_id = 10 THEN 1 END) as artwork,
					  				count(CASE WHEN repair_status_id = 11 THEN 1 END) as ready_to_ship,
					  				count(CASE WHEN repair_status_id = 12 THEN 1 END) as active_repair,
					  				count(CASE WHEN repair_status_id = 13 THEN 1 END) as group_order_holding,
					  				count(CASE WHEN repair_status_id = 14 THEN 1 END) as done,
					  				count(CASE WHEN repair_status_id = 15 THEN 1 END) as holding,
					  				count(CASE WHEN repair_status_id = 16 THEN 1 END) as holding_for_payment,
					  				count(CASE WHEN repair_status_id = 17 THEN 1 END) as digital_impression_detailing,
					  				count(CASE WHEN repair_status_id = 18 THEN 1 END) as pre_group_order_holding,					  				
					  				count(CASE WHEN repair_status_id = 99 THEN 1 END) as repair_received
					  				FROM repair_form WHERE active = TRUE";
					  				
					  	$stmt_alclair_repair = pdo_query($pdo_alclair, $query_alclair_repair, null);
						$row_alclair_repair = pdo_fetch_array($stmt_alclair_repair);
					  	
					  	
					  	//$row_alclair=pdo_fetch_array($stmt_alclair_repair);
					  	//$row_alclair_repair = pdo_fetch_array($stmt_alclair_repair);
					  	
					  	$test = 100;
					  	
				?>	
				 <?php
						if ( $_SESSION["UserName"] != 'Football' ) {
					?>
					<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Orders <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/orders" class="nav-link "> Orders List</a></li>
								<!--<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/pipeline" class="nav-link "> Pipeline</a></li>-->
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/pipeline_v2" class="nav-link "> Pipeline (2 Weeks)</a></li>
								<!--<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/bas_required" class="nav-link "> BAs Required</a></li> -->
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/bas_required_new" class="nav-link "> BAs Required (New!)</a></li>
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/import" class="nav-link "> Import</a></li>
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/orders_done_by_date" class="nav-link "> Orders Done By Date</a></li>
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair/open_orders" class="nav-link "> Open Orders</a></li>
							</ul>
						</li>
						<?php } ?>
											<!--
						Repair States
Repairs on Hold
Clark Cart
Cathy Cart
Impression Detailing
Shell Pouring
Shell Detailing
Casing 
Finishing 
QC
Electronics QC
Artwork
 Ready to Ship
Pickup
- Group Order Hold
- Done
-->
						<?php
							if ( $_SESSION["UserName"] != 'Football' ) {
						?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manufacturing <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/order_received" class="nav-link "> 
									<span style="margin-right:41px; font-weight:bold;">Order Received </span>
									<span style="color:#228B22;font-weight:bold;">(<?php echo $row_alclair["order_received"] ?>)</span></a></li>
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/repair_received" class="nav-link "> 	  
									<span style="margin-right:69px;font-weight:bold;">Repair Received </span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["repair_received"] ?>)</span></a></li>
									  
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/start_cart" class="nav-link "> 
									<span style="margin-right:74px;font-weight:bold;">Start Cart</span>
									<span style="color:#228B22;font-weight:bold;">(<?php echo $row_alclair["start_cart"] ?>)</span></a></li> 
																
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/diagnosing" class="nav-link "> 
									<span style="margin-right:108px;font-weight:bold;">Diagnosing</span>
									<span style="color:#EE7600;font-weight:bold;"> (<? echo $row_alclair_repair["diagnosing"] ?>)</span></a></li> 

								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/repair_cart" class="nav-link "> 
									<span style="margin-right:108px;font-weight:bold;">Repair Cart</span>
									<span style="color:#EE7600;font-weight:bold;"> (<? echo $row_alclair_repair["repair_cart"] ?>)</span></a></li> 
								
								<!--<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/cathy_cart" class="nav-link "> 
									<span style="margin-right:105px;font-weight:bold;">Cathy Cart</span>
									<span style="color:#EE7600;font-weight:bold;"> (<? echo $row_alclair_repair["cathy_cart"] ?>)</span></a></li> -->
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/repair_reshell" class="nav-link "> 
									<span style="margin-right:90px;font-weight:bold;">Repair Reshell</span> 
									<!--<span style="margin-right:5px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["impression_detailing"] ?>)</span>  -->
									<span style="color:#EE7600; font-weight:bold;">(<?php echo $row_alclair_repair["repair_reshell"] ?>)</span></a></li>

								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/active_repair" class="nav-link "> 
									<span style="margin-right:97px;font-weight:bold;">Active Repair</span> 
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["active_repair"] ?>)</span></a></li> 
									
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/digital_impression_detailing" class="nav-link "> 
									<span style="margin-right:40px;font-weight:bold;">Digital Detailing </span> 
									<span style="margin-right:10px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["digital_impression_detailing"] ?>)</span>  
									<span style="color:#EE7600; font-weight:bold;">(<?php echo $row_alclair_repair["digital_impression_detailing"] ?>)</span></a></li>	
									
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/exp_assembly" class="nav-link "> 
									<span style="margin-right:50px;font-weight:bold;">Exp Assembly</span>
									<span style="color:#228B22;font-weight:bold;">(<?php echo $row_alclair["exp_assembly"] ?>)</span></a></li> 

								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/shell_pouring" class="nav-link "> 
									<span style="margin-right:53px;font-weight:bold;">Shell Pouring  </span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["shell_pouring"] ?>) </span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["shell_pouring"] ?>)</span></a></li>
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/shell_detailing" class="nav-link "> 
									<span style="margin-right:46px;font-weight:bold;"> Shell Detailing </span> 
									<span style="margin-right:5px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["shell_detailing"] ?>)</span>
									 <span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["shell_detailing"] ?>)</span></a></li>
									 
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/driver_purgatory" class="nav-link "> 
									<span style="margin-right:28px;font-weight:bold;"> Driver Purgatory </span> 
									<span style="color:#228B22;font-weight:bold;">(<?php echo $row_alclair["driver_purgatory"] ?>)</span></a></li> 
									 								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/casing" class="nav-link "> 
									<span style="margin-right:97px;font-weight:bold;">Casing </span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;"> (<?php echo $row_alclair["casing"] ?>)</span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["casing"] ?>)</span></a></li>
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/finishing" class="nav-link "> 
									<span style="margin-right:81px;font-weight:bold;">Finishing </span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["finishing"] ?>) </span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["finishing"] ?>)</span></a></li>
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/artwork" class="nav-link "> 
									<span style="margin-right:86px;font-weight:bold;">Artwork</span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;"> (<?php echo $row_alclair["artwork"] ?>)</span>
									<span style="color:#EE7600;font-weight:bold;"> (<?php echo $row_alclair_repair["artwork"] ?>)</span></a></li>
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/quality_control" class="nav-link "> 
									<span style="margin-right:39px;font-weight:bold;">Quality Control </span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["quality_control"] ?>) </span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["quality_control"] ?>)</span></a></li>
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/electronics_qc" class="nav-link "> 
									<span style="margin-right:45px;font-weight:bold;">Electronics QC</span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;"> (<?php echo $row_alclair["electronics_qc"] ?>)</span>
									<span style="color:#EE7600;font-weight:bold;"> (<?php echo $row_alclair_repair["electronics_qc"] ?>)</span></a></li>
																
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/ready_to_ship" class="nav-link "> 
									<span style="margin-right:50px;font-weight:bold;">Ready to Ship </span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["ready_to_ship"] ?>) </span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["ready_to_ship"] ?>)</span></a></li>

								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/hearing_protection" class="nav-link "> 
									<span style="margin-right:20px;font-weight:bold;"> Hearing Protection </span> 
									<span style="color:#228B22;font-weight:bold;">(<?php echo $row_alclair["hearing_protection"] ?>)</span></a></li> 
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/pre_group_order_holding" class="nav-link "> 
									<span style="margin-right:12px;font-weight:bold;">Group Holding (Pre-) </span>
									<span style="margin-right:9px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["pre_group_order_holding"] ?>) </span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["pre_group_order_holding"] ?>)</span></a></li>
									
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/group_order_holding" class="nav-link "> 
									<span style="margin-right:5px;font-weight:bold;">Group Holding (Post-)</span>
									<span style="margin-right:6px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["group_order_holding"] ?>) </span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["group_order_holding"] ?>)</span></a></li>
									
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/holding" class="nav-link "> 
									<span style="margin-right:90px;font-weight:bold;">Holding</span>
									<span style="margin-right:12px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["holding"] ?>) </span> 
									 <span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["holding"] ?>)</span></a></li> 
									 
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/holding_for_payment" class="nav-link "> 
									<span style="margin-right:4px;font-weight:bold;">Holding For Payment </span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["holding_for_payment"] ?>) </span>
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["holding_for_payment"] ?>)</span></a></li>
								
								<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_manufacturing/done" class="nav-link ">
									<span style="margin-right:107px;font-weight:bold;"> Done </span>
									<span style="margin-right:5px; color:#228B22;font-weight:bold;">(<?php echo $row_alclair["done"] ?>) 
									<span style="color:#EE7600;font-weight:bold;">(<?php echo $row_alclair_repair["done"] ?>)</span></a></li>
							</ul>
						</li>
						<?php } ?>
				<?php } ?> 
				<?php
				if ( $_SESSION["UserName"] != 'Football' ) {
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Batches <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/alclair_batch/batch_list" class="nav-link "> Batch List</a></li>
					</ul>
				</li>
				<?php } ?> 
				<?php
				if ( $_SESSION["UserName"] == 'admin' || $rootScope["SWDCustomer"] == "dev" ) {
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">TRD <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/trd/tickets" class="nav-link "> Tickets</a></li>
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/trd/customers" class="nav-link "> Customers</a></li>
					</ul>
				</li>
				<?php
				if ( $_SESSION["UserName"] == 'admin'  || $rootScope["SWDCustomer"] == "dev" &&  $_SESSION["UserName"] != 'Football') {
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">SOS <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/sos/sos" class="nav-link "> SOS</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">AC <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/activecampaign/test" class="nav-link "> ActiveCampaign</a></li>
					</ul>
				</li>
				<?php } ?> 
				<?php } ?> 
				<?php
				if ( $_SESSION["UserName"] == 'admin'  ||  $_SESSION["UserName"] == 'Football' ) {
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Football <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/zfootball/roster" class="nav-link "> Roster</a></li>
					</ul>
				</li>
				<?php } ?> 


				<!-- Adding a tab named "Monitor"-->
				<!-- Customers that pay for monitoring will have this tab added-->
				<?php
				if ( $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "dev" ) {
				?>
	             	<li class=" "><a href="<?=$rootScope['RootUrl']?>/monitor/index" class="nav-link "> Monitoring</a></li>
                <?php } ?> 
	            
			<?php if ( $rootScope["SWDCustomer"] == "lng"  ) { ?>	
				<li class=" "><a href="<?=$rootScope['RootUrl']?>/plots/index" class="nav-link ">Plots</a></li>
				<?php if ( $_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) { ?>		
					<li class=" "><a href="<?=$rootScope['RootUrl']?>/monitor/index" class="nav-link "> Monitoring</a></li>
				<?php } ?> 		
			<?php } ?> 
				
<?php if ( $rootScope["SWDCustomer"] != "lng" && $rootScope["SWDCustomer"] != "alclair" && $rootScope["SWDCustomer"] != "ifi") { ?>
				<!--TICKETS tab starts HERE -->
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Incoming Tickets <span class="caret"></span></a>
				<!--<?php
				if ( $rootScope["SWDCustomer"] == "trd") {
				?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Incoming Tickets <span class="caret"></span></a>
				<?php } else { ?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tickets <span class="caret"></span></a>
				<?php } ?>-->
					<ul class="dropdown-menu">
					<li class=" "><a href="<?=$rootScope['RootUrl']?>/ticket/index" class="nav-link  ">All Tickets </a></li>
	<?php if ( $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test") { ?>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">View Ticket By Site <span class="caret"></span></a>
						<ul style="list-style:none;">
                                        <li ng-repeat='site in siteList'>
                                            <a href="<?=$rootScope['RootUrl']?>/ticket/index/?disposal_well_id={{site.id}}" class="nav-link ">{{site.common_name}}</a>
                                        </li>
                        </ul>
					</li>
	<?php } ?>
					<li class=" ">
						<a href="<?=$rootScope['RootUrl']?>/ticket/add" class="nav-link  ">Add New Ticket </a>
					</li>

					<li class=" ">
						<a href="<?=$rootScope['RootUrl']?>/ticket/exportpdf" class="nav-link  ">Export PDF </a>
					</li>
					
	<?php if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "flatland" ) { ?>
							<li class=" ">
								<a href="<?=$rootScope['RootUrl']?>/ticket/reconreport" class="nav-link  ">Reconciliation Report </a>
							</li>
    <?php } ?> 
					</ul>
				</li> 
				<!-- TICKETS tab ends HERE-->
				
				<!-- USER LOGS -->
	<?php if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "henryhill") { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User Logs <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/user_logs/maintenance" class="nav-link  ">Maintenance </a></li>
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/user_logs/inventory" class="nav-link  ">Inventory</a></li>
		<?php if($_SESSION["IsAdmin"]==1) { ?>
                        		<li class=" "><a href="<?=$rootScope['RootUrl']?>/user_logs/index" class="nav-link  ">View All Logs</a></li>    
	    <?php } ?>
					</ul>
				</li>
    <?php } ?> <!-- USER LOGS ENDS HERE -->

<?php } ?>  <!-- ENDING THE NOT EQUAL TO LNG IF STATEMENT ( 1 OF )-->
      

<?php if ( $rootScope["SWDCustomer"] != "lng" && $rootScope["SWDCustomer"] != "alclair" && $rootScope["SWDCustomer"] != "ifi" ) { ?>
   
				<!--OUTGOING TICKETS TAB STARTS HERE-->

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Outgoing Tickets <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class=" "><a href="<?=$rootScope['RootUrl']?>/ticket/index_outgoing" class="nav-link  ">All Tickets </a></li>
						<li>
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Add Ticket <span class="caret"></span></a>
							<ul style="list-style:none;">
								<?php if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "trd" ||  $rootScope["SWDCustomer"] == "wwl") { ?>
									<li>
                            			<a href="<?=$rootScope['RootUrl']?>/ticket/outgoinglandfill" class="nav-link  "> Landfill Disposal</a>
									</li>
									<li>
                            	 		<a href="<?=$rootScope['RootUrl']?>/ticket/outgoingwater" class="nav-link  "> Water Disposal</a>
								 	</li>
								 <?php } ?>
								 	<li>
                            	 		<a href="<?=$rootScope['RootUrl']?>/ticket/oilsale" class="nav-link  "> Oil Sale</a>
								 	</li>
                        	</ul>
						</li>
					</ul>
				</li>

	<!--OUTGOING TICKETS TAB ENDS HERE-->
<?php } ?>  <!-- ENDING THE NOT EQUAL TO LNG IF STATEMENT ( 1 OF )-->

<?php if ( $rootScope["SWDCustomer"] == "trd"  ) { ?> <!--MATERIALS TAB STARTS HERE-->
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Materials <span class="caret"></span></a>
		<ul class="dropdown-menu">
			<li class=" "><a href="<?=$rootScope['RootUrl']?>/materials/index" class="nav-link  ">Materials List </a></li>
			
		</ul>
	</li> 
	<!-- MATERIALS  TAB ENDS HERE-->
<?php } ?>  <!-- ENDING THE NOT EQUAL TO TRD IF STATEMENT ( 1 OF )-->


 <?php if ( $rootScope["SWDCustomer"] != "lng"  ) { ?>
				
	<?php if ( $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test") { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Well Logs <span class="caret"></span></a>
					<ul class="dropdown-menu">
					<li class=" "><a href="<?=$rootScope['RootUrl']?>/welllog/index" class="nav-link  ">All Well Logs </a></li>
					<li>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">View Log By Site</a>
						<ul style="list-style:none;">
                                        <li ng-repeat='site in siteList'>
                                            <a href="<?=$rootScope['RootUrl']?>/welllog/index/?disposal_well_id={{site.id}}" class="nav-link ">{{site.common_name}}</a>
                                        </li>
                        </ul>
					</li>
					<li class=" ">
						<a href="<?=$rootScope['RootUrl']?>/welllog/add" class="nav-link  ">Add New Log </a>
					</li>
					</ul>
				</li>
	<?php } ?>

<?php } ?>  <!-- ENDING THE NOT EQUAL TO LNG IF STATEMENT-->


<?php if ( $rootScope["SWDCustomer"] != "lng" && $rootScope["SWDCustomer"] != "alclair" && $rootScope["SWDCustomer"] != "ifi"  ) { ?> 	
				<?php if($_SESSION["IsAdmin"]==1) { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Exports <span class="caret"></span></a>
						<ul class="dropdown-menu"  style="width:280px;">
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/export/index/daily_logs" class="nav-link  "><i class="fa fa-file-excel-o"></i> Daily Logs</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/export/index/by_trucking_co" class="nav-link  "><i class="fa fa-file-excel-o"></i> By Trucking Co</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/export/index/by_oil_co" class="nav-link  "><i class="fa fa-file-excel-o"></i> By Oil Co</a>
                                </li>
                                <li>
                                <a href="<?=$rootScope['RootUrl']?>/export/index/tickets" class="nav-link  "><i class="fa fa-file-excel-o"></i> Incoming Tickets</a>
                                </li>
								<li>
                                    <a href="<?=$rootScope['RootUrl']?>/export/index/outgoing_tickets" class="nav-link  "><i class="fa fa-file-excel-o"></i> Outgoing Tickets</a>
                                </li>
								<li>
                                    <a href="<?=$rootScope['RootUrl']?>/export/index/well_logs" class="nav-link  "><i class="fa fa-file-excel-o"></i> Well Logs</a>
                                </li>
                                <!--<?php if ($rootScope["SWDCustomer"] == "trd") { ?>
                                	<a href="<?=$rootScope['RootUrl']?>/export/index/tickets" class="nav-link  "><i class="fa fa-file-excel-o"></i> Incoming Tickets</a>
                                <?php } else { ?>
                                    <a href="<?=$rootScope['RootUrl']?>/export/index/tickets" class="nav-link  "><i class="fa fa-file-excel-o"></i> Tickets</a>
                                <?php } ?>
                                </li>
                                <?php if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "trd") { ?>-->

                                <!--<?php } ?>-->

                            </ul>
					</li>
		<?php if ( $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test") { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>

                            <ul class="dropdown-menu"  style="width:380px;">
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/well_monthly_by_disposal" class="nav-link  "><i class="fa fa-file-image-o"></i> Well Monthly - By Disposal</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/well_monthly_by_water" class="nav-link  "><i class="fa fa-file-image-o"></i> Well Monthly - By Water Type</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/well_monthly_by_delivery_method" class="nav-link  "><i class="fa fa-file-image-o"></i> Well Monthly - By Delivery Method</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/well_monthly_pie" class="nav-link  "><i class="fa fa-file-image-o"></i> Well Monthly - Pie Chart</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/well_monthly_pie_avg" class="nav-link  "><i class="fa fa-file-image-o"></i> Well Monthly AVG- Pie Chart</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/welllog_daily_injection" class="nav-link  "><i class="fa fa-file-image-o"></i> Daily Injection</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/welllog_daily_oil_and_tank" class="nav-link  "><i class="fa fa-file-image-o"></i> Daily Oil & Tankage</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/daily_water_by_trucking_company" class="nav-link  "><i class="fa fa-file-image-o"></i> Daily Water Totals by Trucking Company</a>
                                </li>
                                <li>
                                    <a href="<?=$rootScope['RootUrl']?>/reports/index/daily_water_by_oil_company" class="nav-link  "><i class="fa fa-file-image-o"></i> Daily Water Totals by Oil Company</a>
                                </li>
                            </ul>
					</li>
		<?php } ?>
	<?php } ?>    
    <?php } ?>  <!-- ENDING THE NOT EQUAL TO LNG IF STATEMENT-->
	
	<?php if ( $rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl" ) { ?> 
    	<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Tanks <span class="caret"></span></a>
				<ul class="dropdown-menu" style="width:260px;">
					<!-- Manage Operators -->
                 	<li class=" ">
				 		<a href="<?=$rootScope['RootUrl']?>/tanks/index" class="nav-link  "><i class="fa fa-columns"></i> &nbsp Tank Status (web) </a>
				 	</li>
				 	<li class=" ">
				 		<a href="<?=$rootScope['RootUrl']?>/tanks/index_phone" class="nav-link  "><i class="fa fa-columns"></i> &nbsp Tank Status (phone) </a>
				 	</li>
				</ul>
		</lI>
	<?php } ?>       

	
				<?php if($_SESSION["IsAdmin"]==1 || ($_SESSION["IsManager"] == 1 && $rootScope["SWDCustomer"] == "lng")) { ?>
					<?php if ( $rootScope["SWDCustomer"] != "lng" && $rootScope["SWDCustomer"] != "alclair" && $rootScope["SWDCustomer"] != "ifi") { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
							<ul class="dropdown-menu" style="width:260px;">
								<!-- Manage Operators -->
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/operators" class="nav-link  "><i class="fa fa-phone"></i> &nbsp Producers </a>
                                </li>
                                <!-- Manage Source Wells -->
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/wells" class="nav-link  "><i class="fa fa-database"></i>  &nbsp Source Sites </a>
                                </li>
                                <!-- Manage Rates-->
								<li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/accounting" class="nav-link  "><i class="fa fa-users"></i>  &nbsp Customer Manager </a>
                                </li>
                                <!-- Manage Rate Sheets-->
								<li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/rate_sheet" class="nav-link  "><i class="fa fa-money"></i>  &nbsp Rate sheet </a>
                                </li>
                                <!-- Manage Trucking Company-->
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/truckingcompanies" class="nav-link  "><i class="fa fa-car"></i>  Trucking Companies </a>
                                </li>
							<?php if ( $rootScope["SWDCustomer"] == "trd" ||  $rootScope["SWDCustomer"] == "wwl"  ) { ?> 
								<!-- Manage Fluid Tyipes -->
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/fluidtypes" class="nav-link  "><i class="fa fa-tint"></i> &nbsp  Fluid Types </a>
                                </li>
							<?php } ?>
                                <!-- Manage Disposal Wells -->
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/disposalwells" class="nav-link  "><i class="fa fa-map-marker"></i> &nbsp  Disposal Wells </a>
                                </li>
								<!-- Manage Rates -->
                                <!--<li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/rates" class="nav-link  "><i class="fa fa-money"></i> Rates </a>
                                </li>-->
								<!-- Manage Users -->
								<li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/users" class="nav-link "><i class="fa fa-user"></i> &nbsp Users </a>
                                </li>
								<!-- Manage Cron Task  -->
								<li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/cron" class="nav-link  "><i class="fa fa-clock-o"></i> &nbsp Report Automator </a>
                                </li>
                                <!-- Site Settings -->
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/settings" class="nav-link  "><i class="fa fa-gear"></i> &nbsp Warnings/Alerts </a>
                                </li>
                                <?php if ( $rootScope["SWDCustomer"] == "trd" ||  $rootScope["SWDCustomer"] == "wwl"  ) { ?> 
									<li class=" ">
                                    	<a href="<?=$rootScope['RootUrl']?>/admin/rigs" class="nav-link  "><i class="fa fa-sitemap"></i> &nbsp  Rigs </a>
									</li>
								<?php } ?>
                            </ul>
					</lI>
					<?php } elseif($rootScope["SWDCustomer"] == "alclair") { ?>
					<?php
						if ( $_SESSION["UserName"] != 'Football' ) {
					?> 
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
							<ul class="dropdown-menu" style="width:260px;">
								<!-- Manage Users -->
								<li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/users" class="nav-link "><i class="fa fa-user"></i> &nbsp Users </a>
                                </li>
                          
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/daily_build_rate" class="nav-link "><i class="fa fa-calendar"></i> &nbsp Daily Build Rate </a>
                                </li>
                          
                           		<!--<li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/repairs_manufacturing" class="nav-link "><i class="fa fa-align-left"></i> &nbsp Repairs From Manufacturing </a>
                                </li>-->
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/manufacturing_screen_2" class="nav-link "><i class="fa fa-line-chart"></i> &nbsp Manufacturing Screen </a>
                                </li>
                                 <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/get_order_items" class="nav-link "><i class="fa fa-search"></i> &nbsp Search WooCommerce </a>
                                </li>
                                <?php if($_SESSION["UserName"] == 'Marc' || $_SESSION["UserName"] == 'Zeeshan' || $_SESSION["UserName"] == 'admin' ) { ?>
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/manufacturing_screen_for_phil" class="nav-link "><i class="fa fa-smile-o"></i> &nbsp Hey Phil & Z-Man! </a>
                                </li>
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/marc" class="nav-link "><i class="fa fa-smile-o"></i> &nbsp Marc! </a>
                                </li>
                                <?php } ?>
                                 
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/alclair/jonny_orders" class="nav-link "><i class="fa fa-coffee"></i> &nbsp Jonny's Shwag </a>
                                </li>
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/customer_comments" class="nav-link "><i class="fa fa-edit"></i> &nbsp Customer Comments </a>
                                </li>
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/glowforge" class="nav-link "><i class="fa fa-sun-o"></i> &nbsp Glowforge </a>
                                </li>

                                 	<?php if($_SESSION["UserName"] == 'Scott' || $_SESSION["UserName"] == 'admin' ) { ?>
								 		<li class=" ">
                                    		<a href="<?=$rootScope['RootUrl']?>/admin/reconcile" class="nav-link "><i class="fa fa-adjust"></i> &nbsp Reconciliation </a>
											</li>
										<li class=" ">
                                    		<a href="<?=$rootScope['RootUrl']?>/admin/expenses_import" class="nav-link "><i class="fa fa-smile-o"></i> &nbsp Sales/Marketing Expense Import </a>	
                                    	</li>
									<?php } ?>
                                <?php } ?>
                                
							</ul>
						</li> 
					<?php } elseif ( $rootScope["SWDCustomer"] == "ifi") { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
								<ul class="dropdown-menu" style="width:260px;">
									<li class=" ">
										<a href="<?=$rootScope['RootUrl']?>/admin/users" class="nav-link "><i class="fa fa-users"></i> &nbsp Users </a>
									</li>
									<li class=" ">
										<a href="<?=$rootScope['RootUrl']?>/admin/ifi_products" class="nav-link "><i class="fa fa-briefcase"></i> &nbsp Products </a>
									</li>
									<li class=" ">
										<a href="<?=$rootScope['RootUrl']?>/ifi/serial_numbers" class="nav-link "><i class="fa fa-list-ol"></i> &nbsp Serial Numbers US</a>
									</li>
									<li class=" ">
										<a href="<?=$rootScope['RootUrl']?>/ifi/log_history" class="nav-link "><i class="fa fa-history"></i> &nbsp Log History US</a>
									</li>
									<li class=" ">
										<a href="<?=$rootScope['RootUrl']?>/ifi_uk/serial_numbers_uk" class="nav-link "><i class="fa fa-list-ol"></i> &nbsp Serial Numbers UK</a>
									</li>
									<li class=" ">
										<a href="<?=$rootScope['RootUrl']?>/ifi_uk/log_history_uk" class="nav-link "><i class="fa fa-history"></i> &nbsp Log History UK</a>
									</li>
								</ul>
						</li>
					<?php } else { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
							<ul class="dropdown-menu" style="width:260px;">
								<!-- Manage Users -->
								<li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/users_lng" class="nav-link "><i class="fa fa-user"></i> &nbsp Users </a>
                                </li>
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/queens" class="nav-link  "><i class="fa fa-truck"></i> &nbsp Queens</a>
                                </li>
                                <li class=" ">
                                    <a href="<?=$rootScope['RootUrl']?>/admin/customers" class="nav-link  "><i class="fa fa-check-square"></i> &nbsp Customers</a>
                                </li>
							</ul>
						</li>
				<?php }	?>     
			<?php }	?>     
			</ul>
            <ul class="nav navbar-nav navbar-right">
              <!--<li>  COMMENTED OUT ON OCTOBER 1ST, 2016 -->
				<li class="dropdown dropdown-user dropdown-dark">
                        <?php if(empty($_SESSION["UserId"])) { ?>
                            	<a href='<?=$rootScope["RootUrl"]?>/account/login'>Login</a>
                            <?php } else { ?>
                            	<a href="<?=$rootScope['RootUrl']?>/account/index" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <!--<?=$_SESSION["UserName"]?>-->
                                <span class="username username-hide-mobile"><?=$_SESSION["UserName"]?></span><span class="caret"></span>
                            	</a>
								<ul class="dropdown-menu dropdown-menu-default">
                                	<li>
                                    	<a href="<?=$rootScope['RootUrl']?>/account/index">
                                        <i class="icon-user"></i>My Profile </a>
									</li>
									<li>
                                    	<a href="<?=$rootScope["RootUrl"]?>/account/logout">
                                        <i class="icon-key"></i>Log Out </a>
									</li>
                            	</ul>
                            <?php } ?>
						<?php } ?> <!--	END LINE 96    CHECKING FOR USERID -->
				</li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
	