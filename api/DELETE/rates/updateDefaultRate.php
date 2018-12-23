<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";

try
{	
    $billto_operator_id=null;
	$trucking_company_id=null;
	$barrel_rate=$_REQUEST["barrel_rate"];
	$disposal_well_id=$_REQUEST["disposal_well_id"];
	$water_type_id=$_REQUEST["water_type_id"];    
	$query="select * from ticket_tracker_defaultrate where disposal_well_id=:disposal_well_id and water_type_id=:water_type_id";
	$params=array(":disposal_well_id"=>$_REQUEST["disposal_well_id"],
		":water_type_id"=>$water_type_id);
		
	$stmt=pdo_query($pdo,$query,$params);
	$row=pdo_fetch_array($stmt);
	if(empty($row[0]))
	{
		$query="insert into ticket_tracker_defaultrate (disposal_well_id, water_type_id, barrel_rate) values (:disposal_well_id, :water_type_id, :barrel_rate)";
		$params=array(":disposal_well_id"=>$disposal_well_id,
		":water_type_id"=>$water_type_id, ":barrel_rate"=>$barrel_rate);			
	}
	else
	{
		$query="update ticket_tracker_defaultrate set barrel_rate=:barrel_rate where id=:id";
		$params=array(":barrel_rate"=>$barrel_rate,":id"=>$row[0]);			
	}
	pdo_query($pdo,$query,$params);	

	$query="update ticket_tracker_rate set barrel_rate=:barrel_rate where disposal_well_id=:disposal_well_id and water_type_id=:water_type_id and use_default=1";
	$params=array(":barrel_rate"=>$barrel_rate,":disposal_well_id"=>$disposal_well_id,
		":water_type_id"=>$water_type_id);
	pdo_query($pdo,$query,$params);				
	
	$response['code']='success';
	$response["message"] =" Update success!";
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>