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
    $conditionSql_printed = "";
    $pagingSql = "";
    $params = array();
    
    $params = array(":batch_notes" => $_REQUEST["batch_notes"], ":batch_id"=>$_REQUEST["batch_id"]);
	$query = "UPDATE batches SET batch_notes = :batch_notes WHERE id = :batch_id";
    $stmt = pdo_query( $pdo, $query, $params); 
    
	$response["test"] = "Specific notes are " . $_POST["specific_notes"] . " & and SN ID is " . $_REQUEST["serial_numbers_id"];
	//echo json_encode($response);
	//exit;
    
    $response['code'] = 'success';
    $response["message"] = $query;
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
