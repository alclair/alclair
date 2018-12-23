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
    
    if( isset($_REQUEST['id']) )
    {
        $conditionSql = " and id = :id";
        $params[':id'] = $_REQUEST['id'];
    }

    if( isset($_REQUEST['ticket_id']) )
    {
        $conditionSql = " and ticket_id = :ticket_id";
        $params[':ticket_id'] = $_REQUEST['ticket_id'];
    }

    //add uploaded_by_id
    $stmt = pdo_query( $pdo,
					   "select id,filepath,to_char(date_uploaded,'MM/dd/yyyy') as date_uploaded,ticket_id
                       from ticket_tracker_indexupload where 1 = 1 $conditionSql", 
						$params
					 );	
	$result = pdo_fetch_all($stmt);
	
	$response['code']='success';
	$response['data'] = $result;
	
	//var_export($result);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>