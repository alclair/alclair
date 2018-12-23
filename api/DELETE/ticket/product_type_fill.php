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
    
	/*    if(!empty($_REQUEST["ticket_number"]))
    {
        $conditionSql .= " and (ticket_number=:ticket_number)";
        $testing = $_REQUEST["ticket_number"];
        $params[":ticket_number"]= $testing["name"];
        //$params[":ticket_number"]= $_REQUEST["ticket_number"];
    }
  */
  
  	$conditionSql = $_REQUEST['water_type_id'];
    $query = "SELECT * from rate_sheet WHERE product_type_id = $conditionSql";
    
    
    $stmt = pdo_query( $pdo, $query, null); 
    $result = pdo_fetch_all( $stmt );
	
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['test'] = $_REQUEST["water_type_id"];
    $response['water_source_type'] = $result[0]["water_source_type"];
    $response['delivery_method'] = $result[0]["delivery_method"];
    $response['trucking_company_id'] = $result[0]["trucking_company_id"];
    $response['disposal_well_id'] = $result[0]["disposal_well_id"];
    //$response['data'] = $result;
   
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>