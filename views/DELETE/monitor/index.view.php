<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>
	
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<?php
	if ( $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "dev" ) {
?>
        
   <?php 
$db_server_springbrook="127.0.0.1";
$db_database_springbrook="monitor";
$db_user_springbrook="tyler";
$db_password_springbrook="Gorilla1";
$pdo_springbrook=new PDO("pgsql:host=$db_server_springbrook;dbname=$db_database_springbrook;user=$db_user_springbrook;password=$db_password_springbrook");
$query_springbrook="select * from springbrook order by id desc limit 1";
$stmt_springbrook=pdo_query($pdo_springbrook,$query_springbrook,null);
$row_springbrook=pdo_fetch_array($stmt_springbrook);
?>       
	
<div class="row voffset5">
 	<div class="container">
		<table style="width: auto;" align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">North Oil Tank PV</th>
					<th class="text-center">North Oil Tank TV</th>
					<th class="text-center">North Oil Tank SV</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="North Oil Tank PV"><?echo $row_springbrook["notpv"]?> Ft.</td>
					<td class="text-center" data-title="North Oil Tank TV"><?echo $row_springbrook["nottv"]?> Ft.</td>
					<td class="text-center" data-title="North Oil Tank SV"><?echo $row_springbrook["notsv"]?> Ft.</td>
			</tbody>
		</table>
	</div>
</div>
<div class="row voffset5">
 	<div class="container">
		<table  style="width: auto;" align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">South Oil Tank PV</th>
					<th class="text-center">South Oil Tank TV</th>
					<th class="text-center">South Oil Tank SV</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="South Oil Tank PV"><?echo $row_springbrook["sotpv"]?> Ft.</td>
					<td class="text-center" data-title="South Oil Tank TV"><?echo $row_springbrook["sottv"]?> Ft.</td>
					<td class="text-center" data-title="South Oil Tank SV"><?echo $row_springbrook["sotsv"]?> Ft.</td>
			</tbody>
		</table>
	</div>
</div>

<div class="row voffset5">
	<div class="col-xs-6">
		<div class="table-responsive">

		<table style="width: auto;" align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Irgens 2_4 Totalizer</th>
					<th class="text-center">Irgens 3_4 Totalizer</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Irgens 2_4 Totalizer"><?echo $row_springbrook["irgens24totalizer"]?> BBLs</td>
					<td class="text-center" data-title="Irgens 3_4 Totalizer"><?echo $row_springbrook["irgens34totalizer"]?> BBLs</td>
			</tbody>
		</table>
		</div>
	</div>

	 	<div class="col-xs-6">
		 	<div class="table-responsive">
			
		 		<table  style="width: auto;" align="center" class = "table-condensed">
			<thead>
				<tr>
					<th class="text-center">Irgens 2_4 Flowrate</th>
					<th class="text-center">Irgens 3_4 Flowrate</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Irgens 2_4 Flowrate"</td><?echo $row_springbrook["irgens24flowrate"]?>
					<td class="text-center" data-title="Irgens 2_4 Flowrate"</td><?echo $row_springbrook["irgens34flowrate"]?>
			</tbody>
				</table>
				</div>
		</div>
</div>


<div class="row voffset5">
	<div class="col-xs-5">
		<div class="table-responsive">

		<table style="width: auto;" align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Whiting Totalizer</th>
					<th class="text-center">Whiting Flowrate</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Whiting Totalizer"><?echo $row_springbrook["totalizer"]?> BBLs</td>
					<td class="text-center" data-title="Whiting Flowrate"</td><?echo $row_springbrook["flowrate"]?>
			</tbody>
		</table>
		</div>
	</div>

	 	<div class="col-xs-2">
		 	<div class="table-responsive">
			
		 		<table  style="width: auto;" align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Time Recorded</th>
				</tr>
			</thead>	
			<tbody>
				<?php
						if (date('I', time()))
						{
							$TIME = date("m/d/Y H:i:s",strtotime($row_springbrook["time_stamp"]) - 5 * 3600);	?>
							<td class="text-center" data-title="Time Recorded"</td><? echo $TIME;		
						}
						else
						{
							$TIME = date("m/d/Y H:i:s",strtotime($row_springbrook["time_stamp"]) - 6 * 3600);	?>
							<td class="text-center" data-title="Time Recorded"</td><? echo $TIME;
						}
				?>
					<!--<td class="text-center" data-title="Time Recorded"</td><?echo $row_springbrook["time_stamp"]?>-->
			</tbody>
		</table>
		</div>
		</div>

	 	<div class="col-xs-5">
		 	<div class="table-responsive">
			
		 		<table  style="width: auto;" align="center" class = "table-condensed">
			<thead>
				<tr>
					<th class="text-center">Whiting Temperature</th>
					<th class="text-center">Whiting Pressure</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Whiting Temperature"><?echo $row_springbrook["temperature"]?> &degF</td>
					<td class="text-center" data-title="Whiting Pressure"><?echo $row_springbrook["pressure"]?> psi
			</tbody>
		</table>
		</div>
</div>
</div>

<?php
	} elseif( $rootScope["SWDCustomer"] == "flatland") {   
?>
   <?php 
$db_server_lester="127.0.0.1";
$db_database_lester="monitor";
$db_user_lester="tyler";
$db_password_lester="Gorilla1";
$pdo_lester=new PDO("pgsql:host=$db_server_lester;dbname=$db_database_lester;user=$db_user_lester;password=$db_password_lester");
$query_lester="select * from lester order by id desc limit 1";
$stmt_lester=pdo_query($pdo_lester,$query_lester,null);
$row_lester=pdo_fetch_array($stmt_lester);
?>    

<div class="container">
	<h1 class="events text-center">Tank Battery</h1>
		<!--<h2>Tank Battery</h2>-->
		<table style="width:auto;"  align="center" class="table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Unload Tank #1</th>
					<th class="text-center">Unload Tank #1 (Water)</th>
					<th class="text-center">Unload Tank #2</th>
					<th class="text-center">Unload Tank #2 (Water)</th>
				</tr>
			</thead>	
			<tbody>
				<!--<tr ng-repeat='ticket in ticketList'>-->
					<td class="text-center" data-title="Unload Tank #1" ><?echo $row_lester["unload_tank_1"]?> Ft.</td>					
					<td class="text-center" data-title="Unload Tank #1 (Water)"><?echo $row_lester["unload_tank_1_water"]?> Ft.</td>
					<td class="text-center" data-title="Unload Tank #2"><?echo $row_lester["unload_tank_2"]?> Ft.</td>
					<td class="text-center" data-title="Unload Tank #2 (Water)"><?echo $row_lester["unload_tank_2_water"]?> Ft.</td>
				<!--	<td  class="text-center" data-title="Ticket Number"><a href="<?=$rootScope['RootUrl']?>/ticket/view/{{ticket.id}}"><? echo $row["ticket_number"] ?> </a></td>-->
                    </td>
			</tbody>
		</table>
</div>
<div class="row voffset5">
 	<div class="container">
		<table style="width: auto;" align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Transfer Pump A Run</th>
					<th class="text-center">Transfer Pump B Run</th>
				</tr>
			</thead>	
			<tbody>
<?php
				if ($row_lester["transfer_pump_a"] = 0 ) {
?>
					<td class="text-center" data-title="Transfer Pump A Run" >OFF</td>
<?php	} else { ?>
					<td class="text-center" data-title="Transfer Pump A Run">ON</td>
<?php
						}?>
<?php
				if ($row_lester["transfer_pump_b"] = 0 ) {
?>
					<td class="text-center" data-title="Transfer Pump B Run" >OFF</td>
<?php	} else { ?>
					<td class="text-center" data-title="Transfer Pump B Run">ON</td>
<?php
						}?>
					<!--<td class="text-center" data-title="Transfer Pump A Run"</td><?echo $row_lester["transfer_pump_a"]?>
					<td class="text-center" data-title="Transfer Pump B Run"</td><?echo $row_lester["transfer_pump_b"]?>-->
			</tbody>
		</table>
	</div>
</div>
<div class="row voffset5">
 	<div class="container">
		<table  style="width: auto;" align="center"  class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Gunbarrel 1</th>
					<th class="text-center">Gunbarrel 1 (Water)</th>
					<th class="text-center">Gunbarrel 2</th>
					<th class="text-center">Gunbarrel 2 (Water)</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Gunbarrel 1"><?echo $row_lester["gun_barrel_1"]?> Ft.</td>
					<td class="text-center" data-title="Gunbarrel 1 (Water)"><?echo $row_lester["gun_barrel_1_water"]?> Ft.</td>
					<td class="text-center" data-title="Gunbarrel 2"><?echo $row_lester["gun_barrel_2"]?> Ft.</td>
					<td class="text-center" data-title="Gunbarrel 2 (Water)"><?echo $row_lester["gun_barrel_2_water"]?> Ft.</td>
			</tbody>
		</table>
	</div>
</div>
<div class="row voffset5">
 	<div class="col-xs-5">
		<div class="table-responsive">
			<table style="width: auto;" align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Injection Tank</th>
					<th class="text-center">Injection Tank (Water)</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Injection Tank"><?echo $row_lester["injection_tank"]?> Ft.</td>
					<td class="text-center" data-title="Injection Tank (Water)"><?echo $row_lester["injection_tank_water"]?> Ft.</td>
			</tbody>
			</table>
		</div>
	</div>
<div class="col-xs-2">
		 	<div class="table-responsive">
			
		 		<table  style="width: auto;"  align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Time Recorded</th>
				</tr>
			</thead>	
			<tbody>
				<?php
						if (date('I', time()))
						{
							$TIME = date("m/d/Y H:i:s",strtotime($row_lester["time_stamp"]) - 5 * 3600);	?>
							<td class="text-center" data-title="Time Recorded"</td><? echo $TIME;		
						}
						else
						{
							$TIME = date("m/d/Y H:i:s",strtotime($row_lester["time_stamp"]) - 6 * 3600);	?>
							<td class="text-center" data-title="Time Recorded"</td><? echo $TIME;
						}
				?>
					<!--<td class="text-center" data-title="Time Recorded"</td><?echo $row_lester["time_stamp"]?>-->
			</tbody>
		</table>
		</div>
		</div>

	 <div class="col-xs-5">
		 <div class="table-responsive">
		 	<table  style="width: auto;"  align="center"  class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Oil Tank #1</th>
					<th class="text-center">Oil Tank #2</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Oil Tank #1"><?echo $row_lester["oil_tank_1"]?> Ft.</td>
					<td class="text-center" data-title="Oil Tank #2"><?echo $row_lester["oil_tank_2"]?> Ft.</td>
			</tbody>
			</table>
		</div>
	</div>
</div>

<div class="row voffset5">
<h1 class="events text-center">Injection Pumps</h1>
</div>
<div class="row voffset5">
	<div class="col-xs-6">
		<div class="table-responsive">

		<table style="width: auto;"  align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Injection Tank</th>
					<th class="text-center">Injection Filter Pod</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Injection Tank"</td><?echo $row_lester["injection_tank"]?> Ft.</td>
					<td class="text-center" data-title="Injection Filter Pod"</td><?echo $row_lester["injection_filter_pod"]?>
			</tbody>
		</table>
		</div>
	</div>

	 	<div class="col-xs-6">
		 	<div class="table-responsive">
			
		 		<table  style="width: auto;" align="center"  class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Charge/Boost Pump A</th>
					<th class="text-center">Charge/Boost Pump B</th>
				</tr>
			</thead>	
			<tbody>
<?php
				if ($row_lester["charge_pump_a"] > 1 ) {
?>
					<td class="text-center" data-title="Charge/Boost Pump A">ON</td>
<?php	} else { ?>
					<td class="text-center" data-title="Charge/Boost Pump A">OFF</td>
<?php
						}?>
<?php
				if ($row_lester["charge_pump_b"] > 1 ) {
?>
					<td class="text-center" data-title="Charge/Boost Pump B">ON</td>
<?php	} else { ?>
					<td class="text-center" data-title="Charge/Boost Pump B">OFF</td>
<?php
						}?>
					<!--<td class="text-center" data-title="Charge/Boost Pump A"</td><?echo $row_lester["charge_pump_a"]?>
					<td class="text-center" data-title="Charge/Boost Pump B"</td><?echo $row_lester["charge_pump_b"]?>-->
			</tbody>
		</table>
		</div>
</div>
	
</div>
<div class="row voffset5">
 	<div class="container">
		<table  style="width: auto;" align="center"  class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Injection Pump Motor Status A</th>
					<th class="text-center">Injection Pump Warmup Status A</th>
					<th class="text-center">Injection Pump Motor Status B</th>
					<th class="text-center">Injection Pump Warmup Status B</th>
				</tr>
			</thead>	
			<tbody>
				
<?php
				if ($row_lester["injection_motor_pump_status_a"] >= 2 && $row_lester["injection_motor_pump_status_a"] < 5  ) {
?>
					<td class="text-center" data-title="Injection Pump Motor Status A">ON</td>
<?php	} else { ?>
					<td class="text-center" data-title="Injection Pump Motor Status A">OFF</td>
<?php
						}?>
					<!--<td class="text-center" data-title="Injection Pump Motor Status A"</td><?echo $row_lester["injection_motor_pump_status_a"]?>-->

<?php
					if ($row_lester["injection_motor_warmup_status_a"] >= 1 ){
?>	
					<td class="text-center" data-title="Injection Pump Warmup Status A">Warming Up</td>
<?php	} else { ?>
					<td class="text-center" data-title="Injection Pump Warmup Status A">Not Warming Up</td>
<?php
						}?>					
					
<?php
				if ($row_lester["injection_motor_pump_status_b"] >= 2 && $row_lester["injection_motor_pump_status_b"] < 5  ) {
?>
					<td class="text-center" data-title="Injection Pump Motor Status B">ON</td>
<?php	} else { ?>
					<td class="text-center" data-title="Injection Pump Motor Status B">OFF</td>
<?php
						}?>

<?php
					if ($row_lester["injection_motor_warmup_status_b"] >= 1 ){
?>	
					<td class="text-center" data-title="Injection Pump Warmup Status B">Warming Up</td>
<?php	} else { ?>
					<td class="text-center" data-title="Injection Pump Warmup Status B">Not Warming Up</td>
<?php
						}?>	
						
			</tbody>
		</table>
	</div>
</div>


<div class="row voffset5">
 	<div class="container">
		<table  style="width: auto;"  align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Injection Pump SPSI A</th>
					<th class="text-center">Injection Pump SPSI B</th>
					<th class="text-center">Injection Pump DPSI A</th>
					<th class="text-center">Injection Pump DPSI B</th>
				</tr>
			</thead>	
			<tbody>
					<td class="text-center" data-title="Injection Pump SPSI A"><?echo $row_lester["injection_pump_spsi_a"]?> psi</td>
					<td class="text-center" data-title="Injection Pump SPSI B"><?echo $row_lester["injection_pump_spsi_b"]?> psi</td>
					<td class="text-center" data-title="Injection Pump DPSI A"><?echo $row_lester["injection_pump_dpsi_a"]?> psi</td>
					<td class="text-center" data-title="Injection Pump DPSI B"><?echo $row_lester["injection_pump_dpsi_b"]?> psi</td>
			</tbody>
		</table>
	</div>
</div>
<br>

<?php
	} elseif( $rootScope["SWDCustomer"] == "lng") {   
?>
 <?php 
$db_server_queens="127.0.0.1";
$db_database_queens="lng";
$db_user_queens="tyler";
$db_password_queens="Gorilla1";
$pdo_queens=new PDO("pgsql:host=$db_server_queens;dbname=$db_database_queens;user=$db_user_queens;password=$db_password_queens");
$query = "SELECT t1.*, t2.customer_id as customer_id, t2.name, t2.id as ID, t2.queens_id as queens_id, t2.customer_id as queens_customer_id FROM auth_user as t1 LEFT JOIN queens as t2 on t1.customer_id = t2.customer_id WHERE t1.id = {$_SESSION["UserId"]}";  //{$_SESSION["UserId"]}
	
$stmt = pdo_query($pdo_queens,$query,null);
$result1 = pdo_fetch_all($stmt);
$query_queens="select * from queens ORDER BY queens_id";
$stmt_queens=pdo_query($pdo_queens,$query_queens,null);
$row_queens=pdo_fetch_all($stmt_queens);
//echo json_encode($row_queens);
?> 

 <?php 
$db_server_inox_q1="127.0.0.1";
$db_database_inox_q1="lng";
$db_user_inox_q1="tyler";
$db_password_inox_q1="Gorilla1";
$pdo_inox_q1=new PDO("pgsql:host=$db_server_inox_q1;dbname=$db_database_inox_q1;user=$db_user_inox_q1;password=$db_password_inox_q1");
$query_inox_q1="select * from inox_q1 order by id desc limit 1";
$stmt_inox_q1=pdo_query($pdo_inox_q1,$query_inox_q1,null);
$row_inox_q1=pdo_fetch_array($stmt_inox_q1);
$query_inox_q1="SELECT * FROM inox_lng_calc WHERE level_h2o = ROUND({$row_inox_q1["tank_level"]}*2,0)/2";
$stmt_inox_q1=pdo_query($pdo_inox_q1,$query_inox_q1,null);
$inox_q1_tank_level_LNGg=pdo_fetch_all($stmt_inox_q1);
$query_inox_q1="select * from  inox_q1 order by id desc limit 2";
$stmt_inox_q1=pdo_query($pdo_inox_q1,$query_inox_q1,null);
$totalizer_values_q1=pdo_fetch_all($stmt_inox_q1);
$totalizer_q1 = ($totalizer_values_q1[0]["totalizer"] -  $totalizer_values_q1[1]["totalizer"])*1024/82644; 

$query_inox_q1="SELECT t.*, to_char(t.q1_timestamp, 'MM/dd/yyyy') as q1_timestamp, t1.first_name as q1_first_name, t1.last_name as q1_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q1_user_id
WHERE t.id = 1";
$stmt_inox_q1=pdo_query($pdo_inox_q1,$query_inox_q1,null);
$outage_q1=pdo_fetch_array($stmt_inox_q1);
?> 
<br><br>

<br>
	<div class="container">	
		
		<div class="col-xs-12">
		 	<div class="table-responsive">
			
		 		<table  style="width: auto;"  align="center" class = "table-condensed">		
			<thead>
				<tr>
					<th class="text-center">Time Recorded</th>
				</tr>
			</thead>	
			<tbody>
				<?php
						if (date('I', time()))
						{
							$TIME = date("m/d/Y H:i:s",strtotime($row_inox_q1["time_stamp"]) - 5 * 3600);	?>
							<td class="text-center" data-title="Time Stamp"</td><? echo $TIME;		
						}
						else
						{
							$TIME = date("m/d/Y H:i:s",strtotime($row_inox_q1["time_stamp"]) - 6 * 3600);	?>
							<td class="text-center" data-title="Time Stamp"</td><? echo $TIME;
						}
				?>
					<!--<td class="text-center" data-title="Time Recorded"</td><?echo $row_lester["time_stamp"]?>-->
			</tbody>
		</table>
			</div>
		</div>
	</div>
</div>

<div ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 1 ||  $_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[0]["name"]?></h1> <!-- INOX Q4 - Patterson 286-->
	
			<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q1==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q1_is_up"  ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q1==1" class="btn btn-tyler_2 center-block" ng-model="vm.q1_is_up" ng-click="clickQ1(0, 1)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q1==0" class="btn btn-tyler_2 center-block" ng-model="vm.q1_is_up" ng-click="clickQ1(1, 1)"  ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q1==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q1_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.q1==1">
		<h3 class="events text-center"> <?php echo $outage_q1["q1_first_name"] . " " .  $outage_q1["q1_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q1["q1_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q1["q1_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_q1["line_pressure"] == -1 && $row_inox_q1["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>
		
	<?php	} else { ?>
	<div ng-if="vm.q1==0" class="container">
	<div class="row">
			<div class="col-sm-8">
				<h2 style="display:inline; "><b>Tank Level</b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($inox_q1_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q1["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q1["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q1["flow_rate"] *60?>    scfh </b>    </h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q1) ?>    LNGg </b>    </h2> <br />
				<br />
			</div>
<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_inox_q1["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q1["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q1["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q1["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_inox_q1["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q1["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q1["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q1["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
						
					<?php if ($row_inox_q1["line_pressure"] > 120) { // High-High Setpoint is set at 120-->?>
						<h4 style="display:inline"><b>Tank Pressure HH Alarm</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} elseif ($row_inox_q1["line_pressure"] > 100) { ?>
						<h4 style="display:inline"><b>Tank Pressure H Alarm</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Tank Pressure Alarm </b> </h4> <h4 style="display:inline;color: green"> <b> OFF</b> </h4> <br />	
					<?php }?>
					
					<?php if ($row_inox_q1["heater_deviation_1"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 1</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 1</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q1["heater_deviation_2"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 2</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 2</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q1["heater_deviation_3"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 3</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 3</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q1["heater_deviation_4"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 4</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 4</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>						
						
					<?php if ($row_inox_q1["alert_indicator"] > 0 ) { ?>
						<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b> NONE</b> </h4> <br />	
					<?php }?>
					<?php
						if ($row_inox_q1["alarm_indicator_shutdown"] > 0 ) {
					?>
						<h4 style="display:inline"><b>Alarm indicator </b> </h4> <h4 style="display:inline;color: red"> <b> SHUTDOWN</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Alarm indicator </b> </h4> <h4 style="display:inline;color: green"> <b> NONE</b> </h4> <br />	
					<?php }?>

				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 1)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

<?php 
$db_server_chart_q2="127.0.0.1";
$db_database_chart_q2="lng";
$db_user_chart_q2="tyler";
$db_password_chart_q2="Gorilla1";
$pdo_chart_q2=new PDO("pgsql:host=$db_server_chart_q2;dbname=$db_database_chart_q2;user=$db_user_chart_q2;password=$db_password_chart_q2");
$query_chart_q2="select * from  chart_q2 order by id desc limit 1";
$stmt_chart_q2=pdo_query($pdo_chart_q2,$query_chart_q2,null);
$row_chart_q2=pdo_fetch_array($stmt_chart_q2);
$query_chart_q2="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_q2["tank_level"]}";
$stmt_chart_q2=pdo_query($pdo_chart_q2,$query_chart_q2,null);
$chart_q2_tank_level_LNGg=pdo_fetch_all($stmt_chart_q2);
$query_chart_q2="select * from  chart_q2 order by id desc limit 2";
$stmt_chart_q2=pdo_query($pdo_chart_q2,$query_chart_q2,null);
$totalizer_values_chart_q2=pdo_fetch_all($stmt_chart_q2);
$totalizer_q2 = ($totalizer_values_chart_q2[0]["totalizer"] -  $totalizer_values_chart_q2[1]["totalizer"])*1024/82644; 

$query_chart_q2="SELECT t.*, to_char(t.q2_timestamp, 'MM/dd/yyyy') as q2_timestamp, t1.first_name as q2_first_name, t1.last_name as q2_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q2_user_id
WHERE t.id = 1";
$stmt_chart_q2=pdo_query($pdo_chart_q2,$query_chart_q2,null);
$outage_q2=pdo_fetch_array($stmt_chart_q2);
?> 

<br><br>
<div ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 2 ||  $_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
	?>
	<h1 class="events text-center"><?echo $row_queens[1]["name"]?> </h1> <!--Chart Q2 - Nabors X-27-->
		<div class="text-center">
			<label>Planned outage?: &nbsp</label><br />
			<div float="right" class="btn-group" data-toggle="buttons">												
				<label ng-if="vm.q2==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q2_is_up" ng-value="1">
					<input type="radio" value="Yes">Yes</label>
				<label ng-if="vm.q2==1" class="btn btn-tyler_2 center-block" ng-model="vm.q2_is_up" ng-click="clickQ2(0, 2)" ng-value="0">
					<input type="radio" value="No">No</label>
				<label ng-if="vm.q2==0" class="btn btn-tyler_2 center-block" ng-model="vm.q2_is_up" ng-click="clickQ2(1, 2)" ng-value="1">
					<input type="radio" value="Yes">Yes</label>
				<label ng-if="vm.q2==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q2_is_up" ng-value="0">
					<input type="radio" value="No">No</label>-
			</div>
		</div>
	
</br>

	<div ng-if="vm.q2==1">
		<h3 class="events text-center"> <?php echo $outage_q2["q2_first_name"] . " " .  $outage_q2["q2_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q2["q2_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q2["q2_notes"]?></b> </h3> 
	</div>

	<?php if ($row_chart_q2["line_pressure"] == -1 && $row_chart_q2["tank_level"]  == -1  ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>
		
	<?php	} else { ?>
		<div ng-if="vm.q2==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level</b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_q2_tank_level_LNGg[0]["volume_gal"])?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q2["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q2["tank_pressure"] ?> PSI </b></h2> <br />
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q2["flow_rate"] ?>    scfh </b>    </h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q2) ?>    LNGg </b>    </h2> <br />
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q2["temperature"] ?>&#8451;/<?php echo $row_chart_q2["temperature"]*1.8+32 ?> &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_q2["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_q2["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-16px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_q2["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q2["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->
			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q2["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q2["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_q2["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_q2["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 2)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_inox_q3="127.0.0.1";
$db_database_inox_q3="lng";
$db_user_inox_q3="tyler";
$db_password_inox_q3="Gorilla1";
$pdo_inox_q3=new PDO("pgsql:host=$db_server_inox_q3;dbname=$db_database_inox_q3;user=$db_user_inox_q3;password=$db_password_inox_q3");
$query_inox_q3="select * from inox_q3 order by id desc limit 1";
$stmt_inox_q3=pdo_query($pdo_inox_q3,$query_inox_q3,null);
$row_inox_q3=pdo_fetch_array($stmt_inox_q3);
$query_inox_q3="SELECT * FROM inox_lng_calc WHERE level_h2o = ROUND({$row_inox_q3["tank_level"]}*2,0)/2";
$stmt_inox_q3=pdo_query($pdo_inox_q3,$query_inox_q3,null);
$inox_q3_tank_level_LNGg=pdo_fetch_all($stmt_inox_q3);
$query_inox_q3="select * from  inox_q3 order by id desc limit 2";
$stmt_inox_q3=pdo_query($pdo_inox_q3,$query_inox_q3,null);
$totalizer_values_q3=pdo_fetch_all($stmt_inox_q3);
$totalizer_q3 = ($totalizer_values_q3[0]["totalizer"] -  $totalizer_values_q3[1]["totalizer"])*1024/82644; 

$query_inox_q3="SELECT t.*, to_char(t.q3_timestamp, 'MM/dd/yyyy') as q3_timestamp, t1.first_name as q3_first_name, t1.last_name as q3_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q3_user_id
WHERE t.id = 1";
$stmt_inox_q3=pdo_query($pdo_inox_q3,$query_inox_q3,null);
$outage_q3=pdo_fetch_array($stmt_inox_q3);
?> 
<br><br>
<div ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 3 ||  $_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[2]["name"]?></h1> <!-- INOX Q4 - Patterson 286-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q3==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q3_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q3==1" class="btn btn-tyler_2 center-block" ng-model="vm.q3_is_up" ng-click="clickQ3(0, 3)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q3==0" class="btn btn-tyler_2 center-block" ng-model="vm.q3_is_up" ng-click="clickQ3(1, 3)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q3==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q3_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.q3==1">
		<h3 class="events text-center"> <?php echo $outage_q3["q3_first_name"] . " " .  $outage_q3["q3_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q3["q3_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q3["q3_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_q3["line_pressure"] == -1 && $row_inox_q3["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

	<?php	} else { ?>
	<div ng-if="vm.q3==0" class="container">
	<div class="row">
			<div class="col-sm-8">
				<h2 style="display:inline; "><b>Tank Level</b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($inox_q3_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q3["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q3["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q3["flow_rate"] *60?>    scfh </b>    </h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q3) ?>    LNGg </b>    </h2> <br />
				<br />
			</div>
<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_inox_q3["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q3["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q3["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q3["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_inox_q3["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q3["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q3["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q3["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
						
					<?php if ($row_inox_q3["line_pressure"] > 120) { // High-High Setpoint is set at 120-->?>
						<h4 style="display:inline"><b>Tank Pressure HH Alarm</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} elseif ($row_inox_q3["line_pressure"] > 100) { ?>
						<h4 style="display:inline"><b>Tank Pressure H Alarm</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Tank Pressure Alarm </b> </h4> <h4 style="display:inline;color: green"> <b> OFF</b> </h4> <br />	
					<?php }?>
					
					<?php if ($row_inox_q3["heater_deviation_1"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 1</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 1</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q3["heater_deviation_2"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 2</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 2</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q3["heater_deviation_3"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 3</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 3</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q3["heater_deviation_4"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 4</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 4</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>						
						
					<?php if ($row_inox_q3["alert_indicator"] > 0 ) { ?>
						<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b> NONE</b> </h4> <br />	
					<?php }?>
					<?php
						if ($row_inox_q3["alarm_indicator_shutdown"] > 0 ) {
					?>
						<h4 style="display:inline"><b>Alarm indicator </b> </h4> <h4 style="display:inline;color: red"> <b> SHUTDOWN</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Alarm indicator </b> </h4> <h4 style="display:inline;color: green"> <b> NONE</b> </h4> <br />	
					<?php }?>

				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 3)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>			

 <?php 
$db_server_inox_q4="127.0.0.1";
$db_database_inox_q4="lng";
$db_user_inox_q4="tyler";
$db_password_inox_q4="Gorilla1";
$pdo_inox_q4=new PDO("pgsql:host=$db_server_inox_q4;dbname=$db_database_inox_q4;user=$db_user_inox_q4;password=$db_password_inox_q4");
$query_inox_q4="select * from inox_q4 order by id desc limit 1";
$stmt_inox_q4=pdo_query($pdo_inox_q4,$query_inox_q4,null);
$row_inox_q4=pdo_fetch_array($stmt_inox_q4);
$query_inox_q4="SELECT * FROM inox_lng_calc WHERE level_h2o = ROUND({$row_inox_q4["tank_level"]}*2,0)/2";
$stmt_inox_q4=pdo_query($pdo_inox_q4,$query_inox_q4,null);
$inox_q4_tank_level_LNGg=pdo_fetch_all($stmt_inox_q4);
$query_inox_q4="select * from  inox_q4 order by id desc limit 2";
$stmt_inox_q4=pdo_query($pdo_inox_q4,$query_inox_q4,null);
$totalizer_values_q4=pdo_fetch_all($stmt_inox_q4);
$totalizer_q4 = ($totalizer_values_q4[0]["totalizer"] -  $totalizer_values_q4[1]["totalizer"])*1024/82644; 

$query_inox_q4="SELECT t.*, to_char(t.q4_timestamp, 'MM/dd/yyyy') as q4_timestamp, t1.first_name as q4_first_name, t1.last_name as q4_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q4_user_id
WHERE t.id = 1";
$stmt_inox_q4=pdo_query($pdo_inox_q4,$query_inox_q4,null);
$outage_q4=pdo_fetch_array($stmt_inox_q4);
?> 

<br><br>
<div ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 4 ||  $_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[3]["name"]?></h1> <!-- INOX Q4 - Patterson 286-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q4==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q4_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q4==1" class="btn btn-tyler_2 center-block" ng-model="vm.q4_is_up" ng-click="clickQ4(0, 4)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q4==0" class="btn btn-tyler_2 center-block" ng-model="vm.q4_is_up" ng-click="clickQ4(1, 4)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q4==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q4_is_up" ng-value="0">
						<input type="radio" value="No">No</label>
				</div>
			</div>
</br>

	<div ng-if="vm.q4==1">
		<h3 class="events text-center"> <?php echo $outage_q4["q4_first_name"] . " " .  $outage_q4["q4_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q4["q4_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q4["q4_notes"]?></b> </h3> 
	</div>
	
	<?php if ($row_inox_q4["line_pressure"] == -1 && $row_inox_q4["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>	

	<?php	} else { ?>
	<div ng-if="vm.q4==0" class="container">
	<div class="row">
			<div class="col-sm-8">
				<h2 style="display:inline; "><b>Tank Level</b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($inox_q4_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q4["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q4["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q4["flow_rate"] *60?>    scfh </b>    </h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q4) ?>    LNGg </b>    </h2> <br />				
				<br />
			</div>
<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&n3-bsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_inox_q4["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q4["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q4["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q4["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_inox_q4["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q4["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q4["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q4["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
						
					<?php if ($row_inox_q4["line_pressure"] > 120) { // High-High Setpoint is set at 120-->?>
						<h4 style="display:inline"><b>Tank Pressure HH Alarm</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} elseif ($row_inox_q4["line_pressure"] > 100) { ?>
						<h4 style="display:inline"><b>Tank Pressure H Alarm</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Tank Pressure Alarm </b> </h4> <h4 style="display:inline;color: green"> <b> OFF</b> </h4> <br />	
					<?php }?>
					
					<?php if ($row_inox_q4["heater_deviation_1"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 1</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 1</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q4["heater_deviation_2"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 2</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 2</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q4["heater_deviation_3"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 3</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 3</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q4["heater_deviation_4"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 4</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 4</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>						
						
					<?php if ($row_inox_q4["alert_indicator"] > 0 ) { ?>
						<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b> NONE</b> </h4> <br />	
					<?php }?>
					<?php
						if ($row_inox_q4["alarm_indicator_shutdown"] > 0 ) {
					?>
						<h4 style="display:inline"><b>Alarm indicator </b> </h4> <h4 style="display:inline;color: red"> <b> SHUTDOWN</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Alarm indicator </b> </h4> <h4 style="display:inline;color: green"> <b> NONE</b> </h4> <br />	
					<?php }?>

				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q4" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 4)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_chart_q5="127.0.0.1";
$db_database_chart_q5="lng";
$db_user_chart_q5="tyler";
$db_password_chart_q5="Gorilla1";
$pdo_chart_q5=new PDO("pgsql:host=$db_server_chart_q5;dbname=$db_database_chart_q5;user=$db_user_chart_q5;password=$db_password_chart_q5");
$query_chart_q5="select * from  chart_q5 order by id desc limit 1";
$stmt_chart_q5=pdo_query($pdo_chart_q5,$query_chart_q5,null);
$row_chart_q5=pdo_fetch_array($stmt_chart_q5);
$query_chart_q5="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_q5["tank_level"]}";
$stmt_chart_q5=pdo_query($pdo_chart_q5,$query_chart_q5,null);
$chart_q5_tank_level_LNGg=pdo_fetch_all($stmt_chart_q5);
$query_chart_q5="select * from  chart_q5 order by id desc limit 2";
$stmt_chart_q5=pdo_query($pdo_chart_q5,$query_chart_q5,null);
$totalizer_values_chart_q5=pdo_fetch_all($stmt_chart_q5);
$totalizer_q5 = ($totalizer_values_chart_q5[0]["totalizer"] -  $totalizer_values_chart_q5[1]["totalizer"])*1024/82644; 
//echo $row_chart_q5["tank_level"];
//print_r($chart_q5_tank_level_LNGg[0]["volume_gal"]);
//print_r($tank_level_lngg["volume_gal"]);
//echo '<pre>'; print_r($tank_level_lngg); echo '</pre>';

$query_chart_q5="SELECT t.*, to_char(t.q5_timestamp, 'MM/dd/yyyy') as q5_timestamp, t1.first_name as q5_first_name, t1.last_name as q5_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q5_user_id
WHERE t.id = 1";
$stmt_chart_q5=pdo_query($pdo_chart_q5,$query_chart_q5,null);
$outage_q5=pdo_fetch_array($stmt_chart_q5);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 5 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[4]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q5==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q5_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q5==1" class="btn btn-tyler_2 center-block" ng-model="vm.q5_is_up" ng-click="clickQ5(0, 5)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q5==0" class="btn btn-tyler_2 center-block" ng-model="vm.q5_is_up" ng-click="clickQ5(1, 5)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q5==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q5_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.q5==1">
		<h3 class="events text-center"> <?php echo $outage_q5["q5_first_name"] . " " .  $outage_q5["q5_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q5["q5_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q5["q5_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_q5["line_pressure"] == -1 && $row_inox_q5["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.q5==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_q5_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q5["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q5["tank_pressure"] ?> PSI </b></h2> <br />
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_q5["flow_rate"]) ?>    scfh </b></h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q5) ?>    LNGg </b></h2> <br />
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q5["temperature"] ?>&#8451;/<?php echo $row_chart_q5["temperature"]*1.8+32 ?>&#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_q5["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_q5["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_q5["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:12px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_q5["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q5["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q5["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q5["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_q5["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_q5["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 5)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_inox_q6="127.0.0.1";
$db_database_inox_q6="lng";
$db_user_inox_q6="tyler";
$db_password_inox_q6="Gorilla1";
$pdo_inox_q6=new PDO("pgsql:host=$db_server_inox_q6;dbname=$db_database_inox_q6;user=$db_user_inox_q6;password=$db_password_inox_q6");
$query_inox_q6="select * from inox_q6 order by id desc limit 1";
$stmt_inox_q6=pdo_query($pdo_inox_q6,$query_inox_q6,null);
$row_inox_q6=pdo_fetch_array($stmt_inox_q6);
$query_inox_q6="SELECT * FROM inox_lng_calc WHERE level_h2o = ROUND({$row_inox_q6["tank_level"]}*2,0)/2";
$stmt_inox_q6=pdo_query($pdo_inox_q6,$query_inox_q6,null);
$inox_q6_tank_level_LNGg=pdo_fetch_all($stmt_inox_q6);
$query_inox_q6="select * from  inox_q6 order by id desc limit 2";
$stmt_inox_q6=pdo_query($pdo_inox_q6,$query_inox_q6,null);
$totalizer_values_q6=pdo_fetch_all($stmt_inox_q6);
$totalizer_q6 = ($totalizer_values_q6[0]["totalizer"] -  $totalizer_values_q6[1]["totalizer"])*1024/82644; 

$query_inox_q6="SELECT t.*, to_char(t.q6_timestamp, 'MM/dd/yyyy') as q6_timestamp, t1.first_name as q6_first_name, t1.last_name as q6_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q6_user_id
WHERE t.id = 1";
$stmt_inox_q6=pdo_query($pdo_inox_q6,$query_inox_q6,null);
$outage_q6=pdo_fetch_array($stmt_inox_q6);
?> 
<br><br>
<div ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 6 ||  $_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[5]["name"]?></h1> <!-- INOX Q4 - Patterson 286-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q6==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q6_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q6==1" class="btn btn-tyler_2 center-block" ng-model="vm.q6_is_up" ng-click="clickQ6(0, 6)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q6==0" class="btn btn-tyler_2 center-block" ng-model="vm.q6_is_up" ng-click="clickQ6(1, 6)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q6==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q6_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.q6==1">
		<h3 class="events text-center"> <?php echo $outage_q6["q6_first_name"] . " " .  $outage_q6["q6_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q6["q6_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q6["q6_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_q6["line_pressure"] == -1 && $row_inox_q6["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

	<?php	} else { ?>
	<div ng-if="vm.q6==0" class="container">
	<div class="row">
			<div class="col-sm-8">
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($inox_q6_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q6["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q6["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_inox_q6["flow_rate"] *60?>    scfh </b>    </h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q6) ?>    LNGg </b>    </h2> <br />
				<br />
			</div>
<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_inox_q6["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q6["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q6["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q6["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_inox_q6["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q6["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q6["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_inox_q6["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
						
					<?php if ($row_inox_q6["line_pressure"] > 120) { // High-High Setpoint is set at 120-->?>
						<h4 style="display:inline"><b>Tank Pressure HH Alarm</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} elseif ($row_inox_q6["line_pressure"] > 100) { ?>
						<h4 style="display:inline"><b>Tank Pressure H Alarm</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Tank Pressure Alarm </b> </h4> <h4 style="display:inline;color: green"> <b> OFF</b> </h4> <br />	
					<?php }?>
					
					<?php if ($row_inox_q6["heater_deviation_1"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 1</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 1</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q6["heater_deviation_2"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 2</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 2</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q6["heater_deviation_3"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 3</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 3</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>
					<?php if ($row_inox_q6["heater_deviation_4"] > 0 ) { ?>
						<h4 style="display:inline"><b>Heat Dev 4</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Heat Dev 4</b> </h4> <h4 style="display:inline;color: GREEN"> <b> OFF</b> </h4> <br />	
					<?php }?>						
						
					<?php if ($row_inox_q6["alert_indicator"] > 0 ) { ?>
						<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b> ON</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b> NONE</b> </h4> <br />	
					<?php }?>
					<?php
						if ($row_inox_q6["alarm_indicator_shutdown"] > 0 ) {
					?>
						<h4 style="display:inline"><b>Alarm indicator </b> </h4> <h4 style="display:inline;color: red"> <b> SHUTDOWN</b> </h4> <br />	
					<?php	} else { ?>
						<h4 style="display:inline"><b>Alarm indicator </b> </h4> <h4 style="display:inline;color: green"> <b> NONE</b> </h4> <br />	
					<?php }?>

				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q6" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 6)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_chart_q7="127.0.0.1";
$db_database_chart_q7="lng";
$db_user_chart_q7="tyler";
$db_password_chart_q7="Gorilla1";
$pdo_chart_q7=new PDO("pgsql:host=$db_server_chart_q7;dbname=$db_database_chart_q7;user=$db_user_chart_q7;password=$db_password_chart_q7");
$query_chart_q7="select * from  chart_q7 order by id desc limit 1";
$stmt_chart_q7=pdo_query($pdo_chart_q7,$query_chart_q7,null);
$row_chart_q7=pdo_fetch_array($stmt_chart_q7);
$query_chart_q7="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_q7["tank_level"]}";
$stmt_chart_q7=pdo_query($pdo_chart_q5,$query_chart_q7,null);
$chart_q7_tank_level_LNGg=pdo_fetch_all($stmt_chart_q7);
$query_chart_q7="select * from  chart_q7 order by id desc limit 2";
$stmt_chart_q7=pdo_query($pdo_chart_q7,$query_chart_q7,null);
$totalizer_values_chart_q7=pdo_fetch_all($stmt_chart_q7);
$totalizer_q7 = ($totalizer_values_chart_q7[0]["totalizer"] -  $totalizer_values_chart_q7[1]["totalizer"])*1024/82644; 
//echo $row_chart_q5["tank_level"];
//print_r($chart_q5_tank_level_LNGg[0]["volume_gal"]);
//print_r($tank_level_lngg["volume_gal"]);
//echo '<pre>'; print_r($tank_level_lngg); echo '</pre>';
$query_chart_q7="SELECT t.*, to_char(t.q7_timestamp, 'MM/dd/yyyy') as q7_timestamp, t1.first_name as q7_first_name, t1.last_name as q7_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q7_user_id
WHERE t.id = 1";
$stmt_chart_q7=pdo_query($pdo_chart_q7,$query_chart_q7,null);
$outage_q7=pdo_fetch_array($stmt_chart_q7);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 7 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[6]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q7==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q7_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q7==1" class="btn btn-tyler_2 center-block" ng-model="vm.q7_is_up" ng-click="clickQ7(0, 7)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q7==0" class="btn btn-tyler_2 center-block" ng-model="vm.q7_is_up" ng-click="clickQ7(1, 7)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q7==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q7_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.q7==1">
		<h3 class="events text-center"> <?php echo $outage_q7["q7_first_name"] . " " .  $outage_q7["q7_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q7["q7_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q7["q7_notes"]?></b> </h3> 
	</div>
	
	<?php if ($row_inox_q7["line_pressure"] == -1 && $row_inox_q7["tank_level"]  == -1  ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.q7==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_q7_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q7["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q7["tank_pressure"] ?> PSI </b></h2> <br />
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_q7["flow_rate"]) ?>    scfh </b></h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q7) ?>    LNGg </b>    </h2> <br />				
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q7["temperature"] ?>&#8451;/<?php echo $row_chart_q7["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_q7["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_q7["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_q7["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_q7["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q7["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q7["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q7["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_q7["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_q7["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q7" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 7)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_chart_q8="127.0.0.1";
$db_database_chart_q8="lng";
$db_user_chart_q8="tyler";
$db_password_chart_q8="Gorilla1";
$pdo_chart_q8=new PDO("pgsql:host=$db_server_chart_q8;dbname=$db_database_chart_q8;user=$db_user_chart_q8;password=$db_password_chart_q8");
$query_chart_q8="select * from  chart_q8 order by id desc limit 1";
$stmt_chart_q8=pdo_query($pdo_chart_q8,$query_chart_q8,null);
$row_chart_q8=pdo_fetch_array($stmt_chart_q8);
$query_chart_q8="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_q8["tank_level"]}";
$stmt_chart_q8=pdo_query($pdo_chart_q8,$query_chart_q8,null);
$chart_q8_tank_level_LNGg=pdo_fetch_all($stmt_chart_q8);
$query_chart_q8="select * from  chart_q8 order by id desc limit 2";
$stmt_chart_q8=pdo_query($pdo_chart_q8,$query_chart_q8,null);
$totalizer_values_chart_q8=pdo_fetch_all($stmt_chart_q8);
$totalizer_q8 = ($totalizer_values_chart_q8[0]["totalizer"] -  $totalizer_values_chart_q8[1]["totalizer"])*1024/82644; 

$query_chart_q8="SELECT t.*, to_char(t.q8_timestamp, 'MM/dd/yyyy') as q8_timestamp, t1.first_name as q8_first_name, t1.last_name as q8_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q8_user_id
WHERE t.id = 1";
$stmt_chart_q8=pdo_query($pdo_chart_q8,$query_chart_q8,null);
$outage_q8=pdo_fetch_array($stmt_chart_q8);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 8 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[7]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q8==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q8_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q8==1" class="btn btn-tyler_2 center-block" ng-model="vm.q8_is_up" ng-click="clickQ8(0, 8)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q8==0" class="btn btn-tyler_2 center-block" ng-model="vm.q8_is_up" ng-click="clickQ8(1, 8)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q8==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q8_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.q8==1">
		<h3 class="events text-center"> <?php echo $outage_q8["q8_first_name"] . " " .  $outage_q8["q8_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q8["q8_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q8["q8_notes"]?>.</b> </h3> 
	</div>

	<?php if ($row_inox_q8["line_pressure"] == -1 && $row_inox_q8["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.q8==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_q8_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q8["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q8["tank_pressure"] ?> PSI </b></h2> <br />
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_q8["flow_rate"]) ?>    scfh </b></h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q8) ?>    LNGg </b>    </h2> <br />
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q8["temperature"] ?>&#8451;/<?php echo $row_chart_q8["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_q8["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_q8["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_q8["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_q8["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q8["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q8["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q8["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_q8["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_q8["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q8" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 8)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_chart_q9="127.0.0.1";
$db_database_chart_q9="lng";
$db_user_chart_q9="tyler";
$db_password_chart_q9="Gorilla1";
$pdo_chart_q9=new PDO("pgsql:host=$db_server_chart_q9;dbname=$db_database_chart_q9;user=$db_user_chart_q9;password=$db_password_chart_q9");
$query_chart_q9="select * from  chart_q9 order by id desc limit 1";
$stmt_chart_q9=pdo_query($pdo_chart_q9,$query_chart_q9,null);
$row_chart_q9=pdo_fetch_array($stmt_chart_q9);
$query_chart_q9="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_q9["tank_level"]}";
$stmt_chart_q9=pdo_query($pdo_chart_q9,$query_chart_q9,null);
$chart_q9_tank_level_LNGg=pdo_fetch_all($stmt_chart_q9);
$query_chart_q9="select * from  chart_q9 order by id desc limit 2";
$stmt_chart_q9=pdo_query($pdo_chart_q9,$query_chart_q9,null);
$totalizer_values_chart_q9=pdo_fetch_all($stmt_chart_q9);
$totalizer_q9 = ($totalizer_values_chart_q9[0]["totalizer"] -  $totalizer_values_chart_q9[1]["totalizer"])*1024/82644; 

$query_chart_q9="SELECT t.*, to_char(t.q9_timestamp, 'MM/dd/yyyy') as q9_timestamp, t1.first_name as q9_first_name, t1.last_name as q9_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q9_user_id
WHERE t.id = 1";
$stmt_chart_q9=pdo_query($pdo_chart_q9,$query_chart_q9,null);
$outage_q9=pdo_fetch_array($stmt_chart_q9);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 9 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[8]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q9==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q9_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q9==1" class="btn btn-tyler_2 center-block" ng-model="vm.q9_is_up" ng-click="clickQ9(0, 9)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q9==0" class="btn btn-tyler_2 center-block" ng-model="vm.q9_is_up" ng-click="clickQ9(1, 9)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q9==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q9_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.q9==1">
		<h3 class="events text-center"> <?php echo $outage_q9["q9_first_name"] . " " .  $outage_q9["q9_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q9["q9_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q9["q9_notes"]?>.</b> </h3> 
	</div>

	<?php if ($row_inox_q9["line_pressure"] == -1 && $row_inox_q9["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.q9==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_q9_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q9["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q9["tank_pressure"] ?> PSI </b></h2> <br />
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_q9["flow_rate"]) ?>    scfh </b></h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q9) ?>    LNGg </b>    </h2> <br />
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q9["temperature"] ?>&#8451;/<?php echo $row_chart_q9["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_q9["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_q9["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_q9["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_q9["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q9["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q9["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q9["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_q9["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_q9["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q9" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 9)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_chart_q10="127.0.0.1";
$db_database_chart_q10="lng";
$db_user_chart_q10="tyler";
$db_password_chart_q10="Gorilla1";
$pdo_chart_q10=new PDO("pgsql:host=$db_server_chart_q10;dbname=$db_database_chart_q10;user=$db_user_chart_q10;password=$db_password_chart_q10");
$query_chart_q10="select * from  chart_q10 order by id desc limit 1";
$stmt_chart_q10=pdo_query($pdo_chart_q10,$query_chart_q10,null);
$row_chart_q10=pdo_fetch_array($stmt_chart_q10);
$query_chart_q10="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_q10["tank_level"]}";
$stmt_chart_q10=pdo_query($pdo_chart_q10,$query_chart_q10,null);
$chart_q10_tank_level_LNGg=pdo_fetch_all($stmt_chart_q10);
$query_chart_q10="select * from  chart_q10 order by id desc limit 2";
$stmt_chart_q10=pdo_query($pdo_chart_q10,$query_chart_q10,null);
$totalizer_values_chart_q10=pdo_fetch_all($stmt_chart_q10);
$totalizer_q10 = ($totalizer_values_chart_q10[0]["totalizer"] -  $totalizer_values_chart_q10[1]["totalizer"])*1024/82644; 

$query_chart_q10="SELECT t.*, to_char(t.q10_timestamp, 'MM/dd/yyyy') as q10_timestamp, t1.first_name as q10_first_name, t1.last_name as q10_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.q10_user_id
WHERE t.id = 1";
$stmt_chart_q10=pdo_query($pdo_chart_q10,$query_chart_q10,null);
$outage_q10=pdo_fetch_array($stmt_chart_q10);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 10 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[9]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.q10==1" class="btn btn-tyler_2 center-block active" ng-model="vm.q10_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q10==1" class="btn btn-tyler_2 center-block" ng-model="vm.q10_is_up" ng-click="clickQ10(0, 10)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.q10==0" class="btn btn-tyler_2 center-block" ng-model="vm.q10_is_up" ng-click="clickQ10(1, 10)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.q10==0" class="btn btn-tyler_2 center-block active" ng-model="vm.q10_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.q10==1">
		<h3 class="events text-center"> <?php echo $outage_q10["q10_first_name"] . " " .  $outage_q10["q10_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_q10["q10_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_q10["q10_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_q10["line_pressure"] == -1 && $row_inox_q10["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.q10==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_q10_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q10["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q10["tank_pressure"] ?> PSI </b></h2> <br />
				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_q10["flow_rate"]) ?>    scfh </b></h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_q10) ?>    LNGg </b>    </h2> <br />
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_q10["temperature"] ?>&#8451;/<?php echo $row_chart_q10["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_q10["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_q10["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_q10["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_q10["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_q10["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q10["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_q10["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_q10["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_q10["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_Q10" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 10)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_chart_pq31="127.0.0.1";
$db_database_chart_pq31="lng";
$db_user_chart_pq31="tyler";
$db_password_chart_pq31="Gorilla1";
$pdo_chart_pq31=new PDO("pgsql:host=$db_server_chart_pq31;dbname=$db_database_chart_pq31;user=$db_user_chart_pq31;password=$db_password_chart_pq31");
$query_chart_pq31="select * from  chart_pq31 order by id desc limit 1";
$stmt_chart_pq31=pdo_query($pdo_chart_pq31,$query_chart_pq31,null);
$row_chart_pq31=pdo_fetch_array($stmt_chart_pq31);
$query_chart_pq31="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_pq31["tank_level"]}";
$stmt_chart_pq31=pdo_query($pdo_chart_pq31,$query_chart_pq31,null);
$chart_pq31_tank_level_LNGg=pdo_fetch_all($stmt_chart_pq31);
$query_chart_pq31="select * from  chart_pq31 order by id desc limit 2";
$stmt_chart_pq31=pdo_query($pdo_chart_pq31,$query_chart_pq31,null);
$totalizer_values_chart_pq31=pdo_fetch_all($stmt_chart_pq31);
$totalizer_pq31 = ($totalizer_values_chart_pq31[0]["totalizer"] -  $totalizer_values_chart_pq31[1]["totalizer"])*1024/82644; 

$query_chart_pq31="SELECT t.*, to_char(t.pq31_timestamp, 'MM/dd/yyyy') as pq31_timestamp, t1.first_name as pq31_first_name, t1.last_name as pq31_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.pq31_user_id
WHERE t.id = 1";
$stmt_chart_pq31=pdo_query($pdo_chart_pq31,$query_chart_pq31,null);
$outage_pq31=pdo_fetch_array($stmt_chart_pq31);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 16 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[16]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.pq31==1" class="btn btn-tyler_2 center-block active" ng-model="vm.pq31_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq31==1" class="btn btn-tyler_2 center-block" ng-model="vm.pq31_is_up" ng-click="clickPQ31(0, 17)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.pq31==0" class="btn btn-tyler_2 center-block" ng-model="vm.pq31_is_up" ng-click="clickPQ31(1, 17)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq31==0" class="btn btn-tyler_2 center-block active" ng-model="vm.pq31_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.pq31==1">
		<h3 class="events text-center"> <?php echo $outage_pq31["pq31_first_name"] . " " .  $outage_pq31["pq31_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_pq31["pq31_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_pq31["pq31_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_pq31["line_pressure"] == -1 && $row_inox_pq31["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.pq31==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_pq31_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq31["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq31["tank_pressure"] ?> PSI </b></h2> <br />
<!--				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_pq31["flow_rate"]) ?>    scfh </b></h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_pq31) ?>    LNGg </b>    </h2> <br />-->
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq31["temperature"] ?>&#8451;/<?php echo $row_chart_pq31["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_pq31["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_pq31["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_pq31["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_pq31["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq31["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq31["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq31["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_pq31["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_pq31["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_PQ31" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 17)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

 <?php 
$db_server_chart_pq33="127.0.0.1";
$db_database_chart_pq33="lng";
$db_user_chart_pq33="tyler";
$db_password_chart_pq33="Gorilla1";
$pdo_chart_pq33=new PDO("pgsql:host=$db_server_chart_pq33;dbname=$db_database_chart_pq33;user=$db_user_chart_pq33;password=$db_password_chart_pq33");
$query_chart_pq33="select * from  chart_pq33 order by id desc limit 1";
$stmt_chart_pq33=pdo_query($pdo_chart_pq33,$query_chart_pq33,null);
$row_chart_pq33=pdo_fetch_array($stmt_chart_pq33);
$query_chart_pq33="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_pq33["tank_level"]}";
$stmt_chart_pq33=pdo_query($pdo_chart_pq33,$query_chart_pq33,null);
$chart_pq33_tank_level_LNGg=pdo_fetch_all($stmt_chart_pq33);
$query_chart_pq33="select * from  chart_pq33 order by id desc limit 2";
$stmt_chart_pq33=pdo_query($pdo_chart_pq33,$query_chart_pq33,null);
$totalizer_values_chart_pq33=pdo_fetch_all($stmt_chart_pq33);
$totalizer_pq33 = ($totalizer_values_chart_pq33[0]["totalizer"] -  $totalizer_values_chart_pq33[1]["totalizer"])*1024/82644; 

$query_chart_pq33="SELECT t.*, to_char(t.pq33_timestamp, 'MM/dd/yyyy') as pq33_timestamp, t1.first_name as pq33_first_name, t1.last_name as pq33_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.pq33_user_id
WHERE t.id = 1";
$stmt_chart_pq33=pdo_query($pdo_chart_pq33,$query_chart_pq33,null);
$outage_pq33=pdo_fetch_array($stmt_chart_pq33);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 18 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[17]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.pq33==1" class="btn btn-tyler_2 center-block active" ng-model="vm.pq33_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq33==1" class="btn btn-tyler_2 center-block" ng-model="vm.pq33_is_up" ng-click="clickPQ33(0, 18)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.pq33==0" class="btn btn-tyler_2 center-block" ng-model="vm.pq33_is_up" ng-click="clickPQ33(1, 18)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq33==0" class="btn btn-tyler_2 center-block active" ng-model="vm.pq33_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.pq33==1">
		<h3 class="events text-center"> <?php echo $outage_pq33["pq33_first_name"] . " " .  $outage_pq33["pq33_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_pq33["pq33_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_pq33["pq33_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_pq33["line_pressure"] == -1 && $row_inox_pq33["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.pq33==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_pq33_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq33["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq33["tank_pressure"] ?> PSI </b></h2> <br />
<!--				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_pq33["flow_rate"]) ?>    scfh </b></h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_pq33) ?>    LNGg </b>    </h2> <br />-->
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq33["temperature"] ?>&#8451;/<?php echo $row_chart_pq33["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_pq33["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_pq33["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_pq33["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_pq33["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq33["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq33["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq33["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_pq33["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_pq33["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_PQ33" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 18)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>

<?php 
$db_server_chart_pq34="127.0.0.1";
$db_database_chart_pq34="lng";
$db_user_chart_pq34="tyler";
$db_password_chart_pq34="Gorilla1";
$pdo_chart_pq34=new PDO("pgsql:host=$db_server_chart_pq34;dbname=$db_database_chart_pq34;user=$db_user_chart_pq34;password=$db_password_chart_pq34");
$query_chart_pq34="select * from  chart_pq34 order by id desc limit 1";
$stmt_chart_pq34=pdo_query($pdo_chart_pq34,$query_chart_pq34,null);
$row_chart_pq34=pdo_fetch_array($stmt_chart_pq34);
$query_chart_pq34="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_pq34["tank_level"]}";
$stmt_chart_pq34=pdo_query($pdo_chart_pq34,$query_chart_pq34,null);
$chart_pq34_tank_level_LNGg=pdo_fetch_all($stmt_chart_pq34);
//$query_chart_pq34="select * from  chart_pq34 order by id desc limit 2";
//$stmt_chart_pq34=pdo_query($pdo_chart_pq34,$query_chart_pq34,null);
//$totalizer_values_chart_pq34=pdo_fetch_all($stmt_chart_pq34);
//$totalizer_pq34 = ($totalizer_values_chart_pq34[0]["totalizer"] -  $totalizer_values_chart_pq34[1]["totalizer"])*1024/82644; 

$query_chart_pq34="SELECT t.*, to_char(t.pq34_timestamp, 'MM/dd/yyyy') as pq34_timestamp, t1.first_name as pq34_first_name, t1.last_name as pq34_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.pq34_user_id
WHERE t.id = 1";
$stmt_chart_pq34=pdo_query($pdo_chart_pq34,$query_chart_pq34,null);
$outage_pq34=pdo_fetch_array($stmt_chart_pq34);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 13 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[12]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.pq34==1" class="btn btn-tyler_2 center-block active" ng-model="vm.pq34_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq34==1" class="btn btn-tyler_2 center-block" ng-model="vm.pq34_is_up" ng-click="clickPQ34(0, 13)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.pq34==0" class="btn btn-tyler_2 center-block" ng-model="vm.pq34_is_up" ng-click="clickPQ34(1, 13)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq34==0" class="btn btn-tyler_2 center-block active" ng-model="vm.pq34_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.pq34==1">
		<h3 class="events text-center"> <?php echo $outage_pq34["pq34_first_name"] . " " .  $outage_pq34["pq34_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_pq34["pq34_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_pq34["pq34_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_pq34["line_pressure"] == -1 && $row_inox_pq34["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.pq34==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_pq34_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq34["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq34["tank_pressure"] ?> PSI </b></h2> <br />
				<!--<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_pq34["flow_rate"]) ?>    scfh </b></h2> <br />-->
				<!--<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_pq34) ?>    LNGg </b>    </h2> <br />-->
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq34["temperature"] ?>&#8451;/<?php echo $row_chart_pq34["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_pq34["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_pq34["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_pq34["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_pq34["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq34["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq34["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq34["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_pq34["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_pq34["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_PQ34" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 13)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>




 <?php 
$db_server_chart_pq39="127.0.0.1";
$db_database_chart_pq39="lng";
$db_user_chart_pq39="tyler";
$db_password_chart_pq39="Gorilla1";
$pdo_chart_pq39=new PDO("pgsql:host=$db_server_chart_pq39;dbname=$db_database_chart_pq39;user=$db_user_chart_pq39;password=$db_password_chart_pq39");
$query_chart_pq39="select * from  chart_pq39 order by id desc limit 1";
$stmt_chart_pq39=pdo_query($pdo_chart_pq39,$query_chart_pq39,null);
$row_chart_pq39=pdo_fetch_array($stmt_chart_pq39);
$query_chart_pq39="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_pq39["tank_level"]}";
$stmt_chart_pq39=pdo_query($pdo_chart_pq39,$query_chart_pq39,null);
$chart_pq39_tank_level_LNGg=pdo_fetch_all($stmt_chart_pq39);
$query_chart_pq39="select * from  chart_pq39 order by id desc limit 2";
$stmt_chart_pq39=pdo_query($pdo_chart_pq39,$query_chart_pq39,null);
$totalizer_values_chart_pq39=pdo_fetch_all($stmt_chart_pq39);
$totalizer_pq39 = ($totalizer_values_chart_pq39[0]["totalizer"] -  $totalizer_values_chart_pq39[1]["totalizer"])*1024/82644; 

$query_chart_pq39="SELECT t.*, to_char(t.pq39_timestamp, 'MM/dd/yyyy') as pq39_timestamp, t1.first_name as pq39_first_name, t1.last_name as pq39_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.pq39_user_id
WHERE t.id = 1";
$stmt_chart_pq39=pdo_query($pdo_chart_pq39,$query_chart_pq39,null);
$outage_pq39=pdo_fetch_array($stmt_chart_pq39);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 15 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[14]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.pq39==1" class="btn btn-tyler_2 center-block active" ng-model="vm.pq39_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq39==1" class="btn btn-tyler_2 center-block" ng-model="vm.pq39_is_up" ng-click="clickPQ39(0, 15)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.pq39==0" class="btn btn-tyler_2 center-block" ng-model="vm.pq39_is_up" ng-click="clickPQ39(1, 15)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq39==0" class="btn btn-tyler_2 center-block active" ng-model="vm.pq39_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.pq39==1">
		<h3 class="events text-center"> <?php echo $outage_pq39["pq39_first_name"] . " " .  $outage_pq39["pq39_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_pq39["pq39_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_pq39["pq39_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_pq39["line_pressure"] == -1 && $row_inox_pq39["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.pq39==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_pq39_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq39["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq39["tank_pressure"] ?> PSI </b></h2> <br />
<!--				<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_pq39["flow_rate"]) ?>    scfh </b></h2> <br />
				<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_pq39) ?>    LNGg </b>    </h2> <br />-->
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq39["temperature"] ?>&#8451;/<?php echo $row_chart_pq39["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_pq39["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_pq39["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_pq39["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_pq39["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq39["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq39["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq39["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_pq39["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_pq39["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_PQ39" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 15)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>


<?php 
$db_server_chart_pq66="127.0.0.1";
$db_database_chart_pq66="lng";
$db_user_chart_pq66="tyler";
$db_password_chart_pq66="Gorilla1";
$pdo_chart_pq66=new PDO("pgsql:host=$db_server_chart_pq66;dbname=$db_database_chart_pq66;user=$db_user_chart_pq66;password=$db_password_chart_pq66");
$query_chart_pq66="select * from  chart_pq66 order by id desc limit 1";
$stmt_chart_pq66=pdo_query($pdo_chart_pq66,$query_chart_pq66,null);
$row_chart_pq66=pdo_fetch_array($stmt_chart_pq66);
$query_chart_pq66="SELECT * FROM chart_lng_calc WHERE level_h2o = {$row_chart_pq66["tank_level"]}";
$stmt_chart_pq66=pdo_query($pdo_chart_pq66,$query_chart_pq66,null);
$chart_pq66_tank_level_LNGg=pdo_fetch_all($stmt_chart_pq66);
//$query_chart_pq66="select * from  chart_pq66 order by id desc limit 2";
//$stmt_chart_pq66=pdo_query($pdo_chart_pq66,$query_chart_pq66,null);
//$totalizer_values_chart_pq66=pdo_fetch_all($stmt_chart_pq66);
//$totalizer_pq66 = ($totalizer_values_chart_pq66[0]["totalizer"] -  $totalizer_values_chart_pq66[1]["totalizer"])*1024/82644; 

$query_chart_pq66="SELECT t.*, to_char(t.pq66_timestamp, 'MM/dd/yyyy') as pq66_timestamp, t1.first_name as pq66_first_name, t1.last_name as pq66_last_name  FROM queens_status as t
LEFT JOIN auth_user as t1 ON t1.id = t.pq66_user_id
WHERE t.id = 1";
$stmt_chart_pq66=pdo_query($pdo_chart_pq66,$query_chart_pq66,null);
$outage_pq66=pdo_fetch_array($stmt_chart_pq66);
?> 

<br><br>
<div  ng-controller="monitorCtrl" class="container">
	<?php for($i=0; $i<count($result1); $i++) { 
		if($result1[$i]["queens_id"] == 16 ||  $_SESSION["IsAdmin"] == 1  || $_SESSION["IsManager"] == 1) {
	?>
	<h1  class="events text-center"> <?echo $row_queens[15]["name"]?> </h1> <!--Chart Q5 - H & P 640-->
	
	<div class="text-center">
				<label>Planned outage?: &nbsp</label><br />
				<div float="right" class="btn-group" data-toggle="buttons">												
					<label ng-if="vm.pq66==1" class="btn btn-tyler_2 center-block active" ng-model="vm.pq66_is_up" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq66==1" class="btn btn-tyler_2 center-block" ng-model="vm.pq66_is_up" ng-click="clickPQ66(0, 16)" ng-value="0">
						<input type="radio" value="No">No</label>
					<label ng-if="vm.pq66==0" class="btn btn-tyler_2 center-block" ng-model="vm.pq66_is_up" ng-click="clickPQ66(1, 16)" ng-value="1">
						<input type="radio" value="Yes">Yes</label>
					<label ng-if="vm.pq66==0" class="btn btn-tyler_2 center-block active" ng-model="vm.pq66_is_up" ng-value="0">
						<input type="radio" value="No">No</label>-
				</div>
			</div>
</br>

	<div ng-if="vm.pq66==1">
		<h3 class="events text-center"> <?php echo $outage_pq66["pq66_first_name"] . " " .  $outage_pq66["pq66_last_name"] ?> pressed the planned outage button on   <?php  echo $outage_pq66["pq66_timestamp"] ?>. </h3>
		<h3 class="events text-center"> Notes about the outage are in bold <b><?php  echo $outage_pq66["pq66_notes"]?></b> </h3> 
	</div>

	<?php if ($row_inox_pq66["line_pressure"] == -1 && $row_inox_pq66["tank_level"]  == -1 ) { ?>
		<h2 class="events text-center">There are no communications with this queen</h2>

		<?php	} else { ?>
		<div ng-if="vm.pq66==0" class="container">
		<div class="row">
			<div class="col-sm-8">
				<h2> </h2>
				<h2 style="display:inline; "><b>Tank Level </b> </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($chart_pq66_tank_level_LNGg[0]["volume_gal"]) ?> LNGg </b> </h2> <br />
				<h2 style="display:inline;"><b>Line Pressure  </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq66["line_pressure"] ?> PSI </b> </h2> <br /> 
				<h2 style="display:inline;"><b>Tank Pressure </b>  </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq66["tank_pressure"] ?> PSI </b></h2> <br />
				<!--<h2 style="display:inline;"><b>Flow Rate </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($row_chart_pq66["flow_rate"]) ?>    scfh </b></h2> <br />-->
				<!--<h2 style="display:inline;"><b>Totalizer </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo number_format($totalizer_pq66) ?>    LNGg </b>    </h2> <br />-->
				<h2 style="display:inline;"><b>Line Temperature </b>   </h2> <h2 style="display:inline;color: red"> <b><?php echo $row_chart_pq66["temperature"] ?>&#8451;/<?php echo $row_chart_pq66["temperature"]*1.8+32 ?>  &#8457; </b>    </h2> <br />
				<br />
			</div>
			<div class="col-sm-4">
					<h2 style="display:inline; "><b>HH&nbsp;&nbsp; H&nbsp;&nbsp;&nbsp; L &nbsp;&nbsp; LL</b> </h2> </h2> <br />
					<h4 style="margin-left: 200px;margin-top:-2px">Tank Level</h4>
					<!--&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; -->
					<!-- TANK LEVEL ALARMS -->
					<?php if($_SESSION["IsAdmin"] == 1 || $_SESSION["IsManager"] == 1) {
						if ($row_chart_pq66["tank_level_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["tank_level_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["tank_level_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:36px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["tank_level_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:36px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:36px;"></div>
					<?php } ?>	<br />
					
					<!-- LINE PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-20px">Line Pressure</h4>
					<?php
						if ($row_chart_pq66["line_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["line_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["line_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:67px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["line_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:67px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:67px;"></div>
					<?php } ?>	<br />
	
					<!-- TANK PRESSURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:-18px">Tank Pressure</h4>
					<?php
						if ($row_chart_pq66["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["tank_pressure_h_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["tank_pressure_l_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:100px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	
					
					<!-- LINE TEMPERATURE ALARMS -->	
					<h4 style="margin-left: 200px;margin-top:14px">Line Temperature</h4>
					<!--<?php
						if ($row_chart_q2["tank_pressure_hh_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:28px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:28px; top:100px;"></div>
					<?php } ?>-->
					<?php
						if ($row_chart_pq66["temperature"]*1.8+32  > 40 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:78px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:78px; top:133px;"></div>
					<?php } ?>
					<?php
						if ($row_chart_pq66["temperature"]*1.8+32 < 20 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:125px; top:133px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:125px; top:133px;"></div>
					<?php } ?>
					<!--<?php
						if ($row_chart_q2["tank_pressure_ll_alarm"] > 0 ) { ?>
							<div class="circle" style="background-color:#ff0000; left:174px; top:100px;"></div>
					<?php	} else { ?>
							<div class="circle" style="background-color:#00b300; left:174px; top:100px;"></div>
					<?php } ?>	-->

			
					<h4 style="display:inline"><b>Dev Alarm Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq66["deviation_alarm_level"]?></b> </h4> <br />
					<h4 style="display:inline"><b>Dev Alert Level</b> </h4> <h4 style="display:inline;color: red"> <b><?echo $row_chart_pq66["deviation_alert_level"]?></b> </h4> <br />
					<?php
						if ($row_chart_pq66["alert_indicator"] > 0 ) { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: red"> <b>ON</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alert Indicator</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>
						
					<?php
						if ($row_chart_pq66["alarm_indicator_shutdown"] > 0 ) { ?>	
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: red"> <b>SHUTDOWN</b> </h4> <br />
					<?php	} else { ?>
							<h4 style="display:inline"><b>Alarm Shutdown</b> </h4> <h4 style="display:inline;color: green"> <b>NONE</b> </h4> <br />
					<?php } ?>				
				<br />
			</div>
	</div>
</div>
			
	<?php } ?>	 <!-- END ELSE STATEMENT -->
	<?php break; } }?>	 <!-- BREAK OUT OF FOR LOOP & CLOSE IF STATEMENT  AND OTHER IF STATEMENT-->
<?php } ?>	<!-- CLOSE FOR LOOP -->

<div ng-controller="monitorCtrl" class="container">
	<div class="modal fade modal-wide" id="outageNotes_PQ66" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmOutageNotes">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Notes About the Outage </h4><br />
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-12">
								<label class="control-label">Notes:</label><br />
								<textarea type="text" name="notes" ng-model="vm.notes" value="" class='form-control' rows='2'   ></textarea>
		                    </div>
					 	</div> 
                     </div> 
					 <div class="modal-footer">
                        <button ng-if="vm.notes" type="button" class="btn btn-primary" ng-click="submitNotes(1, 16)" ng-disabled="!frmOutageNotes.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                   	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->
</div>

</div>


</div>


</div>	

 <?php
 	} 
 ?>	

<!--<script text="javascript">
window.cfg.disposal_well_id="<?=$rootScope["disposal_well_id"]?>";
</script>-->
    <!-- -->
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" || $rootScope["SWDCustomer"] == "lng" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
    <?php
	    } elseif( $rootScope["SWDCustomer"] == "trd" ) {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_TRD.js"></script>
	<?php
	    } elseif( $rootScope["SWDCustomer"] == "wwl" ) {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_WWL.js"></script>
	<?php
 	} else {
	 ?>
	 		<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
 	<?php
	 	}
 	?>	
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>