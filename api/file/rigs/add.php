<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";

try
{	    
	$query = "Insert into rigs
                ( rig_name, company_man_name, company_man_number, source_well_id )
                values
                ( :rig_name, :company_man_name, :company_man_number, :source_well_id) RETURNING id"; 
	$params = array(
                    ":rig_name" => $_POST["rig_name"],	
                    ":company_man_name" => $_POST["company_man_name"],	
                    ":company_man_number" => $_POST["company_man_number"],	
                    ":source_well_id" => $_POST["source_well_id"],			
                    );
					    
    $stmt = pdo_query( $pdo, $query, $params );	
    $result = pdo_fetch_array($stmt);

	$response['code']='success';
	$response["message"] =" Add success!";
    $response['data'] = $result;
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>