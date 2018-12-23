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
    if( !empty($_REQUEST['id']) )
    {        
        $stmt = pdo_query( $pdo,
                           'SELECT t1.*,  twell.file_number, twell.current_well_name as source_well_name, toperator.name as operator_name
                           FROM rigs AS t1 
                           LEFT JOIN ticket_tracker_well AS twell ON t1.source_well_id = twell.id
						   LEFT JOIN ticket_tracker_operator AS toperator ON twell.current_operator_id=toperator.id
						   WHERE t1.id = :id',
                            array(":id"=>$_REQUEST['id'])
                         );	
        $result = pdo_fetch_array($stmt);
        $result["name"]=$result["file_number"]."//".$result["source_well_name"]."//".$result["operator_name"];
    }
    else if(!empty($_REQUEST["SearchText"]))
    {
        $stmt = pdo_query( $pdo,
                           'SELECT t1.*, twell.file_number, twell.current_well_name as source_well_name, toperator.name as operator_name
						   FROM rigs AS t1
                           LEFT JOIN ticket_tracker_well AS twell ON t1.source_well_id = twell.id
						   LEFT JOIN ticket_tracker_operator AS toperator ON twell.current_operator_id=toperator.id
                           WHERE (name ilike :SearchText ) ORDER BY name',
                            array(":SearchText"=>"%".$_REQUEST["SearchText"]."%")
                            //,1
                         );	
        $result = pdo_fetch_all($stmt);
    }
    else
    {
        $stmt = pdo_query( $pdo,
                           'SELECT t1.*, twell.file_number, twell.current_well_name as source_well_name, toperator.name as operator_name
                           FROM rigs AS t1 
                           LEFT JOIN ticket_tracker_well AS twell ON t1.source_well_id = twell.id
						   LEFT JOIN ticket_tracker_operator AS toperator ON twell.current_operator_id = toperator.id',
                            null
                         );	
        $result = pdo_fetch_all($stmt);
    }	

	$response['code']='success';
	$response['data'] = $result;
	
	//var_export($result);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>