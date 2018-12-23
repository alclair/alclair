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
	$start_cart['notes'] = $_POST['notes'];
	
	if(empty($start_cart['barcode']) == true)
	{
		$response['message'] = 'Please enter a barcode.';
		echo json_encode($response);
		exit;
	}

$tester = $start_cart['barcode'];
$barcode_length = strlen($start_cart['barcode']);	
/*for ($x = 0; $x < $barcode_length; $x++) {	
	if ( !strcmp($tester[$x], '-') ) {
		$order_id_is = substr($tester, 0, $x-1);
		break;
	}
	if ($x == $barcode_length - 1) {
		$response['message'] = 'Did not work';
		echo json_encode($response);
		exit;
	}
} */

if($start_cart["barcode"][0] == 'R') {
	
	
$repair_id_is = substr($start_cart["barcode"], 1, $barcode_length);			

$response["testing"] = $repair_id_is;
	//echo json_encode($response);
	//exit;

$query = pdo_query($pdo, "SELECT * FROM repair_status_table WHERE status_of_repair = 'Casing'", null);
$result = pdo_fetch_array($query);
$status_id = $result["order_in_repair"];

// ORDER STATUS LOG
// IMPORT ORDERS			
$stmt = pdo_query( $pdo, "INSERT INTO repair_status_log (date, repair_form_id, repair_status_id, notes,  user_id) VALUES (now(), :repair_form_id, :status_id, :notes, :user_id) RETURNING id",
									array(':repair_form_id'=>$repair_id_is, ':status_id'=>$status_id, ':notes'=>$start_cart['notes'], ':user_id'=>$_SESSION['UserId']));					 					 
	 
$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 ) {
	$response['message'] = pdo_errors();
	$response["testing8"] = "8888888";
	echo json_encode($response);
	exit;
}

$stmt = pdo_query( $pdo, 'UPDATE repair_form SET repair_status_id = :repair_status_id WHERE id = :id',
								   array("id"=>$repair_id_is, "repair_status_id"=>$status_id));
								   
} else {
	
$order_id_is = $start_cart['barcode'];		
$query = pdo_query($pdo, "SELECT * FROM order_status_table WHERE status_of_order = 'Casing'", null);
$result = pdo_fetch_array($query);
$status_id = $result["order_in_manufacturing"];

//$response["test"] = "the status id is " . $status_id;
//echo json_encode($response);
//exit;
			
// ORDER STATUS LOG
// IMPORT ORDERS			
$stmt = pdo_query( $pdo, "INSERT INTO order_status_log (date, import_orders_id, order_status_id, notes,  user_id) VALUES (now(), :import_orders_id, :status_id, :notes, :user_id) RETURNING id", array(':import_orders_id'=>$order_id_is, ':status_id'=>$status_id, ':notes'=>$start_cart['notes'], ':user_id'=>$_SESSION['UserId']));					 					 
	 
$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 ) {
	$response['message'] = pdo_errors();
	$response["testing8"] = "8888888";
	echo json_encode($response);
	exit;
}

$stmt = pdo_query( $pdo, 'UPDATE import_orders SET order_status_id = :order_status_id WHERE id = :id',
								   array("id"=>$order_id_is, "order_status_id"=>$status_id));
								   
} // CLOSE ELSE STATEMENT

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