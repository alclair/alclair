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
   
	$record = array(); 
    $record['Id'] = $_POST['Id'];
    while(list($key,$value)=each($_POST))
	{
		$value=trim($value);
		$_POST[$key]=$value;
		if(empty($value)&&$key!="notes")
		{
			$_POST[$key]=0;
		}
	}
	$record['disposal_well_id'] = $_POST['disposal_well_id'];
	$record['level_skim_tank_1_ft'] = $_POST['level_skim_tank_1_ft'];
	$record['level_skim_tank_2_ft'] = $_POST['level_skim_tank_2_ft'];
	$record['level_oil_tank_1_ft'] = $_POST['level_oil_tank_1_ft'];
	$record['level_oil_tank_2_ft'] = $_POST['level_oil_tank_2_ft'];
	
	$record['level_gun_ft'] = $_POST['level_gun_ft'];
	$record['flowmeter_barrels'] = $_POST['flowmeter_barrels'];
	$record['injection_rate'] = $_POST['injection_rate'];
	$record['injection_pressure'] = $_POST['injection_pressure'];
	$record['oil_sold_barrels'] = $_POST['oil_sold_barrels'];
	$record['notes'] = $_POST['notes'];	
    $record['date_logged'] = $_POST['date_logged']; 
    $record['pipeline1_starting_total'] = $_POST['pipeline1_starting_total'];
	$record['pipeline1_ending_total'] = $_POST['pipeline1_ending_total'];
	$record['pipeline2_starting_total'] = $_POST['pipeline2_starting_total'];
	$record['pipeline2_ending_total'] = $_POST['pipeline2_ending_total'];
					 
	$stmt = pdo_query( $pdo, 
					   "update well_logs_dailywelllog set 
                       disposal_well_id = :disposal_well_id, 
                       level_skim_tank_1_ft = :level_skim_tank_1_ft, level_skim_tank_2_ft = :level_skim_tank_2_ft,
                       level_oil_tank_1_ft = :level_oil_tank_1_ft, level_oil_tank_2_ft = :level_oil_tank_2_ft, 
                       level_gun_ft = :level_gun_ft, flowmeter_barrels = :flowmeter_barrels,
                       injection_rate = :injection_rate, injection_pressure = :injection_pressure, oil_sold_barrels = :oil_sold_barrels,
                       notes = :notes, date_logged = :date_logged,
					   pipeline1_starting_total=:pipeline1_starting_total,
					   pipeline1_ending_total=:pipeline1_ending_total,
					   pipeline2_starting_total=:pipeline2_starting_total,
					   pipeline2_ending_total=:pipeline2_ending_total
                       where Id = :Id",
					   array("Id"=>$record["Id"],
                       ':disposal_well_id'=>$record['disposal_well_id'],
                       ':level_skim_tank_1_ft'=>$record['level_skim_tank_1_ft'],':level_skim_tank_2_ft'=>$record['level_skim_tank_2_ft'],
					   ':level_oil_tank_1_ft'=>$record['level_oil_tank_1_ft'],':level_oil_tank_2_ft'=>$record['level_oil_tank_2_ft'],
					   ':level_gun_ft'=>$record['level_gun_ft'],':flowmeter_barrels'=>$record['flowmeter_barrels'],
                       ':injection_rate'=>$record['injection_rate'],':injection_pressure'=>$record['injection_pressure'],':oil_sold_barrels'=>$record['oil_sold_barrels'],
                       ':notes'=>$record['notes'],':date_logged'=>$record['date_logged'],
					   ":pipeline1_starting_total"=>$record["pipeline1_starting_total"],
					   ":pipeline1_ending_total"=>$record["pipeline1_ending_total"],
					   ":pipeline2_starting_total"=>$record["pipeline2_starting_total"],
					   ":pipeline2_ending_total"=>$record["pipeline2_ending_total"]
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