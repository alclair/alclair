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
    $conditionSql = "";
    $params = array();
    
	    if(!empty($_REQUEST["ID_num"])) {
        	$conditionSql .= " and (id=:id)";
			$testing = $_REQUEST["ID_num"];
			$params[":id"] = $_REQUEST["ID_num"];
			//$params[":ticket_number"]= $_REQUEST["ticket_number"];
    	}
  
    $query = "SELECT * from ticket_tracker_well WHERE 1=1 $conditionSql";
    // testing

    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
	
    $response['code'] = 'success';
    $response["message"] = $query;
    //$response['data'] = $result;
   
    $response["well_exists"] = $result[0]["id"];
    //$response["test"] = $_POST["ticket_number"];
    //$ticket["ticket_number"] = $_REQUEST["ticket_number"];
    $response["post"] = $_POST["source_well_name.id"];
    $response["request"] = $_REQUEST["source_well_name.id"];
	$response["testing2"] = $_REQUEST["ID_num"];
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>