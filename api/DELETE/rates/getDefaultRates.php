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
	$stmt = pdo_query( $pdo,
						   'select ticket_tracker_defaultrate.id, ticket_tracker_defaultrate.barrel_rate,
						   ticket_tracker_defaultrate.disposal_well_id,
						   ticket_tracker_disposalwell.common_name as disposal_well_name, 
						   
                           ticket_tracker_defaultrate.water_type_id   ,                    
                           ticket_tracker_watertype.type as water_type_name
                           from ticket_tracker_defaultrate
                           join ticket_tracker_disposalwell on ticket_tracker_defaultrate.disposal_well_id=ticket_tracker_disposalwell.id
                           join ticket_tracker_watertype on ticket_tracker_defaultrate.water_type_id=ticket_tracker_watertype.id
                           
                           order by ticket_tracker_disposalwell.common_name',
							null
						);	
		$result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
		$response['code'] = 'success';
		$response['data'] = $result;
		//echo 'select * from ticket_tracker_operator '.$pagingSql;
		//var_export($response['data']);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>