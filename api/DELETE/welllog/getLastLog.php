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
	if(!empty($_REQUEST["disposal_well_id"]))
	{
		$query="select * from well_logs_dailywelllog where disposal_well_id=:disposal_well_id order by date_logged desc,id desc limit 1";
		$stmt=pdo_query($pdo,$query,array(":disposal_well_id"=>$_REQUEST["disposal_well_id"]));
		$row=pdo_fetch_array($stmt);
		$response["pipeline1_starting_total"]=$row["pipeline1_ending_total"];
		$response["pipeline2_starting_total"]=$row["pipeline2_ending_total"];
	}
	$response['code'] = 'success';
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>