<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	
    $welllog = array();
	while(list($key,$value)=each($_POST))
	{
		$value=trim($value);
		$_POST[$key]=$value;
		if(empty($value)&&$key!="notes")
		{
			$_POST[$key]=0;
		}
	}
	$welllog['date_logged'] = $_POST['date_logged'];
	
	$welllog['injection_rate'] = $_POST['injection_rate'];
	$welllog['injection_pressure'] = $_POST['injection_pressure'];
	$welllog['oil_sold_barrels'] = $_POST['oil_sold_barrels'];
	$welllog['notes'] = $_POST['notes'];	
	$welllog['disposal_well_id'] = $_POST['disposal_well_id'];
	$welllog['level_skim_tank_1_ft'] = $_POST['level_skim_tank_1_ft'];
	$welllog['level_skim_tank_2_ft'] = $_POST['level_skim_tank_2_ft'];
	$welllog['level_oil_tank_1_ft'] = $_POST['level_oil_tank_1_ft'];
	$welllog['level_oil_tank_2_ft'] = $_POST['level_oil_tank_2_ft'];
	$welllog['pipeline1_starting_total'] = $_POST['pipeline1_starting_total'];
	$welllog['pipeline1_ending_total'] = $_POST['pipeline1_ending_total'];
	$welllog['pipeline2_starting_total'] = $_POST['pipeline2_starting_total'];
	$welllog['pipeline2_ending_total'] = $_POST['pipeline2_ending_total'];
	$welllog['level_gun_ft'] = $_POST['level_gun_ft'];
	$welllog['flowmeter_barrels'] = $_POST['flowmeter_barrels'];  
    
					 
	$stmt = pdo_query( $pdo, 
					   "insert into well_logs_dailywelllog
					   (disposal_well_id,level_skim_tank_1_ft,level_skim_tank_2_ft,
					   level_oil_tank_1_ft,level_oil_tank_2_ft,
					    level_gun_ft,flowmeter_barrels,injection_rate,
						injection_pressure,oil_sold_barrels,notes,date_created,date_logged,created_by_id,pipeline1_starting_total,
						pipeline1_ending_total,pipeline2_starting_total,pipeline2_ending_total)
					   values
					   (:disposal_well_id,:level_skim_tank_1_ft,:level_skim_tank_2_ft,
					   :level_oil_tank_1_ft,:level_oil_tank_2_ft,
					   :level_gun_ft,:flowmeter_barrels,:injection_rate,
					   :injection_pressure,:oil_sold_barrels,:notes,now(),:date_logged,{$_SESSION["UserId"]},:pipeline1_starting_total,
						:pipeline1_ending_total,:pipeline2_starting_total,:pipeline2_ending_total)",
					   array(':disposal_well_id'=>$welllog['disposal_well_id'],':level_skim_tank_1_ft'=>$welllog['level_skim_tank_1_ft'],':level_skim_tank_2_ft'=>$welllog['level_skim_tank_2_ft'],
					   ':level_oil_tank_1_ft'=>$welllog['level_oil_tank_1_ft'],':level_oil_tank_2_ft'=>$welllog['level_oil_tank_2_ft'],
					   ':level_gun_ft'=>$welllog['level_gun_ft'],':flowmeter_barrels'=>$welllog['flowmeter_barrels'],':injection_rate'=>$welllog['injection_rate'],
					   ':injection_pressure'=>$welllog['injection_pressure'],':oil_sold_barrels'=>$welllog['oil_sold_barrels'],':notes'=>$welllog['notes'],':date_logged'=>$welllog['date_logged'],
					   ":pipeline1_starting_total"=>$welllog["pipeline1_starting_total"],
					   ":pipeline1_ending_total"=>$welllog["pipeline1_ending_total"],
					   ":pipeline2_starting_total"=>$welllog["pipeline2_starting_total"],
					   ":pipeline2_ending_total"=>$welllog["pipeline2_ending_total"]
                       )
						//,1
					 );					 					 
					 
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}
	
	$response['code'] = 'success';
	$response['data'] = $rowcount;
	echo json_encode($response);
	
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>