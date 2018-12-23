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
    
	    if(!empty($_REQUEST["ticket_number"]))
    {
        $conditionSql .= " and (ticket_number=:ticket_number)";
        $testing = $_REQUEST["ticket_number"];
        $params[":ticket_number"]= $testing["name"];
        //$params[":ticket_number"]= $_REQUEST["ticket_number"];
    }
  
    $query = "SELECT ticket_number as ticket_num from ticket_tracker_ticket WHERE 1=1 $conditionSql";
    // testing

    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
	
    $response['code'] = 'success';
    $response["message"] = $query;
    //$response['data'] = $result;
   
    $response["ticket_exists"] = $result[0]["ticket_num"];
    //$response["test"] = $_POST["ticket_number"];
    //$ticket["ticket_number"] = $_REQUEST["ticket_number"];
    $response["post"] = $_POST["ticket_number"];
    $response["request"] = $_REQUEST["ticket_number"];
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>