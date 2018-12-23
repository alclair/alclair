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
    $pagingSql = "";
    $orderBySqlDirection = "desc";
    $orderBySql = " order by id $orderBySqlDirection";
    $params = array();
    
	    if(!empty($_REQUEST["type"]))
    {
        $conditionSql .= " and (type=:type)";
        $testing = $_REQUEST["type"];
        $params[":type"]= $testing["name"];
        //$params[":type"]= $_REQUEST["type"];
    }
  
    $query = "SELECT type from ticket_tracker_watertype WHERE 1=1 $conditionSql";
    // testing

    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
	
    $response['code'] = 'success';
    $response["message"] = $query;
    //$response['data'] = $result;
   
    $response["product_type_exists"] = $result[0]["type"];
    //$response["test"] = $_POST["ticket_number"];
    //$ticket["ticket_number"] = $_REQUEST["ticket_number"];
    $response["post"] = $_POST["type"];
    $response["request"] = $_REQUEST["type"];
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>