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
	
    //Get Total Records
    	
	$params[":session_userid"]=$_SESSION['UserId'] ;
    //Get One Page Records
    if( isset($_REQUEST['id']) )
    {        
        $query = "SELECT *, to_char(time_stamp,'MM/dd/yyyy') as time_stamp, to_char(invoice_date, 'MM/dd/yyyy') as invoice_date
        				  FROM materials_tracker
                          WHERE id = :id";
    }
    else
    {
        $query = "SELECT t1.*, to_char(t1.time_stamp,'MM/dd/yyyy') as time_stamp
                          FROM materials_tracker as t1
						  WHERE active = TRUE $conditionSql $orderBySql $pagingSql";
    }    
    
    $params[":id"] = $_REQUEST['id'];
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
	
	$params[":invoice_id"] = $_REQUEST['id'];
	$query = "SELECT t1.*, to_char(t1.date_uploaded,'MM/dd/yyyy') as date_uploaded, uploaded_by_id, invoice_id
                       FROM materials_invoice_indexupload AS t1
                       WHERE invoice_id = :invoice_id"; // WHERE THE MATERIAL IS ACTIVE
	$stmt = pdo_query( $pdo, $query, $params); 
    $result2 = pdo_fetch_all( $stmt );

                       
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['data2'] = $result2;
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>