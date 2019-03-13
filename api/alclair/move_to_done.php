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

	$start_cart = array();
	$start_cart['barcode'] = $_POST['barcode'];
	if($_SESSION["UserName"] == 'Scott') {
		$start_cart['notes'] = "Moved to done by Scott through the Orders page. ";
	} elseif($_SESSION["UserName"] == 'Amanda') {
		$start_cart['notes'] = "Moved to done by Amanda through the Orders page. ";
	} else {
		$start_cart['notes'] = "Moved to done by Tyler through the Orders page. ";
	}
	$order_id_is = $_REQUEST["ID"];
	$status_id =12;
	

//$response["test1"] = "the order id is " . $_REQUEST["ID"];
//echo json_encode($response);
//exit;
			
// ORDER STATUS LOG
// IMPORT ORDERS			
$stmt = pdo_query( $pdo, "INSERT INTO order_status_log (date, import_orders_id, order_status_id, notes,  user_id) VALUES (:date, :import_orders_id, :status_id, :notes, :user_id) RETURNING id",
									array(':date'=>$_REQUEST['DoneDate'], ':import_orders_id'=>$order_id_is, ':status_id'=>$status_id, ':notes'=>$start_cart['notes'], ':user_id'=>$_SESSION['UserId']));					 					 
	 
$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 ) {
	$response['message'] = pdo_errors();
	$response["testing8"] = "8888888";
	echo json_encode($response);
	exit;
}

$stmt = pdo_query( $pdo, 'UPDATE import_orders SET order_status_id = :order_status_id WHERE id = :id',
								   array("id"=>$order_id_is, "order_status_id"=>$status_id));


$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 )
{
	$response['message'] = pdo_errors();
	$response['code'] = 'NOPE';
	echo json_encode($response);
	exit;
}
	
$result = pdo_fetch_array($stmt);
$response['code'] = 'success';
$response['data'] = $result; 
$response["testing8"] = "8888888";
echo json_encode($response);	
	
}
catch(PDOException $ex)
{
	$response["testing8"] = "8888888";
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>