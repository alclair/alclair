<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = array();

try
{	
	
	$start_cart = array();
	$start_cart['barcode'] = $_REQUEST['barcode'];
	//$start_cart['notes'] = $_POST['notes'];

$tester = $start_cart['barcode'];
$barcode_length = strlen($start_cart['barcode']);	
/*for ($x = 0; $x < $barcode_length; $x++) {	
	if ( !strcmp($tester[$x], '-') ) {
		$order_id_is = substr($tester, 0, $x-1);
		break;
	}
} */
$order_id_is = $start_cart['barcode'];
	
	$params = array();
    if(!empty($order_id_is)) {  
	    
	    $params[":order_id_is"] = $order_id_is;
        $stmt = pdo_query( $pdo, "SELECT DISTINCT * FROM import_orders WHERE id = :order_id_is", $params);	
        $row = pdo_fetch_all( $stmt );
        $response['data'] = $row;
        $response['test'] = $params[":order_id_is"];
    }
	else
    {        
		//$response['message'] = 'IN HERE';
		//echo json_encode($response);
		//exit;
    }
	$response['code']='success';	
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