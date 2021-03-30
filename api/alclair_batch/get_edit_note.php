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
    $orderBySql = " ORDER BY t2.movement_date_lm $orderBySqlDirection";
    $params = array();


	$query = "SELECT * FROM batches WHERE id = :batch_id";

    $stmt = pdo_query( $pdo, $query, array(":batch_id"=>$_REQUEST['batch_id'])); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    
    $response["test"] = $result[0]["batch_notes"];
	//echo json_encode($response);
	//exit;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>
