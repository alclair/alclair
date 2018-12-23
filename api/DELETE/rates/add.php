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
    
		$use_default=$_REQUEST["use_default"];
		if($use_default==1)
		{
			$barrel_rate=GetDefaultRate($disposal_well_id,$water_type_id);
		}
    if($_REQUEST["billto_option"]=="operator")
    {
        $billto_operator_id=$_REQUEST["billto_operator_id"];
    }
    if($_REQUEST["billto_option"]=="trucking company")
    {
        $trucking_company_id=$_REQUEST["trucking_company_id"];
    }
    $query = "insert into ticket_tracker_rate  (source_well_id, disposal_well_id, billto_option, billto_operator_id,
        trucking_company_id, water_type_id, barrel_rate) values (:source_well_id, :disposal_well_id, :billto_option, :billto_operator_id, :trucking_company_id, :water_type_id, :barrel_rate,:use_default)";
    $params=array(":billto_option"=>$_REQUEST["billto_option"],
        ":billto_operator_id"=>$billto_operator_id,
        ":trucking_company_id"=>$trucking_company_id,
        ":water_type_id"=>$water_type_id,
        ":barrel_rate"=>$barrel_rate,
        ":disposal_well_id"=>$disposal_well_id,
		":use_default"=>$use_default,
       ":source_well_id"=>$_REQUEST["source_well_id"]);
    
    pdo_query($pdo,$query,$params);
    $response['code']='success';
    $response["message"] =" insert success!";
    echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>