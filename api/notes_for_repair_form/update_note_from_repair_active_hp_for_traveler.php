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
    $orderBySqlDirection = "ASC";
    $orderBySql = " ORDER BY order_id $orderBySqlDirection";
    $params = array();
    
    $notes = $_POST['notes'];
    
    $ID_is = $_REQUEST["id_to_edit"];
    
    $response["test"] = "The ID is " . $ID_is . " and notes are " . $notes;
	//echo json_encode($response);
	//exit;
    
    $query = "UPDATE repair_status_log_active_hp SET notes = :notes WHERE id = $ID_is";
    $stmt = pdo_query( $pdo, $query, array(":notes"=>$notes) );
    //$row = pdo_fetch_array( $stmt );
	
	$response["data"] = $row;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>