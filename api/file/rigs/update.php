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
    if( empty( $_REQUEST["id"] ) )
        return;


	$query = "UPDATE rigs SET 
                rig_name = :rig_name, company_man_name = :company_man_name, company_man_number = :company_man_number, source_well_id = :source_well_id WHERE id = :id";
	$params = array(
                    ":rig_name" => $_POST["rig_name"],
                    ":company_man_name" => $_POST["company_man_name"],
                    ":company_man_number" => $_POST["company_man_number"],
                    ":source_well_id" => $_POST["source_well_id"],		
		            ":id" => $_POST["id"] 
                    );
    pdo_query( $pdo, $query, $params );
		
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