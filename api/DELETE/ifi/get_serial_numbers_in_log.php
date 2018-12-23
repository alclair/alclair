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
    $orderBySql = " ORDER BY t1.date $orderBySqlDirection";
    $params = array();
    
    if( !empty($_REQUEST['id']) )
    {
        $conditionSql = " AND log_id = :log_id";
        $params[":log_id"] = $_REQUEST['id'];        
    }
    //Get Total Records
    $query = "SELECT count(t1.id) 
    				  FROM order_tracking AS t1
    				  WHERE 1=1 $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
    if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
	
	$query = "SELECT t1.*, t2.product_name as name, t2.status, t3.name AS product_name, t4.name AS category_name
					  FROM order_tracking AS t1
					  LEFT JOIN serial_numbers AS t2 ON t1.serial_number = t2.serial_number
					  LEFT JOIN products AS t3 ON t2.product_id = t3.id
					  LEFT JOIN product_categories AS t4 ON t3.category_id = t4.id
					  WHERE 1=1 $conditionSql";
	$stmt = pdo_query($pdo, $query, $params);
	$result = pdo_fetch_all( $stmt );
    
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