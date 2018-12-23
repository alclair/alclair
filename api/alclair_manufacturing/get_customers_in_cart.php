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
    if( !empty($_REQUEST['id']) )
    {        
        $stmt = pdo_query( $pdo,
                           'select * from import_orders where id = :id',
                            array(":id"=>$_REQUEST['id'])
                         );	
        $result = pdo_fetch_array($stmt);
    }
    else
    {
        $stmt = pdo_query( $pdo,
                           "select t1.*, to_char(t1.date, 'MM/dd/yyyy') as date_to_post, to_char(t1.estimated_ship_date, 'MM/dd/yyyy') as estimated_ship_date_to_post, to_char(t1.received_date, 'MM/dd/yyyy') as received_date_to_post, IEMs.id as monitor_id, t2.status_of_order
                            FROM import_orders AS t1
                            LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
                            LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                            WHERE t1.order_status_id = :order_status_id AND t1.active = TRUE ORDER BY id",
                            array(":order_status_id"=>$_REQUEST["ORDER_STATUS_ID"])
                         );	
        $result = pdo_fetch_all($stmt);
        
         $stmt2 = pdo_query( $pdo,
                           "SELECT t1.*, to_char(t1.date_entered, 'MM/dd/yyyy') AS date_entered, IEMs.name as monitor_name, t2.status_of_repair FROM repair_form AS t1
                           LEFT JOIN monitors AS IEMS ON t1.monitor_id = IEMs.id
                           LEFT JOIN repair_status_table AS t2 ON t1.repair_status_id = t2.order_in_repair
                           WHERE t1.repair_status_id = :repair_status_id AND t1.active = TRUE ORDER BY id", 
                           array(":repair_status_id"=>$_REQUEST["REPAIR_STATUS_ID"])
                           );
                           	
        $result2 = pdo_fetch_all($stmt2);

    }	
	
	$response['code']='success';
	$response['data'] = $result;
	$response['data2'] = $result2;
	
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