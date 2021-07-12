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
    
    $ID_is = $_REQUEST["id"];
    
    if($ID_is) {
	    $query = "SELECT * FROM customer_comments WHERE id = $ID_is";
		$stmt = pdo_query( $pdo, $query, null );
		$row = pdo_fetch_array( $stmt );
	
		$response["data"] = $row;
	} else {
		//$query = "SELECT *, to_char(date, 'MM/dd/yyyy') AS date FROM customer_comments WHERE comment IS NOT NULL AND comment != ''";
		$query = "SELECT *, to_char(date, 'MM/dd/yyyy') AS date FROM customer_comments ORDER BY date IS NULL, id";
		$stmt = pdo_query( $pdo, $query, $params );
		$row = pdo_fetch_all( $stmt );
		$count = pdo_rows_affected($stmt);
		$response["data"] = $row;
		$response["count"] = $count;
	}
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>