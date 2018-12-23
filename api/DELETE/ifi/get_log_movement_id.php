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
	$log = array();
	$log['company'] = $_POST['company'];
	$log['name'] = $_POST['name'];
	$log['address_1'] = $_POST['address_1'];
	$log['address_2'] = $_POST['address_2'];
	$log['city'] = $_POST['city'];
	$log['state'] = $_POST['state'];
	$log['zip_code'] = $_POST['zip_code'];
	$log['country'] = $_POST['country'];
	$log['notes'] = $_POST['notes'];
	$log['carrier_id'] = $_POST['carrier_id'];
	$log['tracking'] = $_POST['tracking'];
	$log['title'] = $_POST['new_ship_to'];
	$log['warehouse_id'] = $_POST['warehouse_id'];
	$log['ordertype_id'] = $_POST['ordertype_id'];
	$log['po_number'] = $_POST['po_number'];
	$log['shipping_cost'] = $_POST['shipping_cost'];
	

$stmt = pdo_query( $pdo, 
					   "INSERT INTO log_movement (movement, movement_type, company, name, address_1, address_2, city, state, zip_code, country, date, notes, carrier_id, warehouse_id, ordertype_id, po_number, shipping_cost, tracking, title)
					     VALUES (:movement, :movement_type, :company, :name, :address_1, :address_2, :city, :state, :zip_code, :country, now(), :notes, :carrier_id, :warehouse_id, :ordertype_id, :po_number, :shipping_cost, :tracking, :title) RETURNING id",
						 array(':movement'=>"EXPORT", ':movement_type'=>"Ship", ':company'=>$log['company'], ':name'=>$log['name'], ':address_1'=>$log['address_1'], ':address_2'=>$log['address_2'], ':city'=>$log['city'], ':state'=>$log['state'], ':zip_code'=>$log['zip_code'], ':country'=>$log['country'], ':notes'=>$log['notes'], ':carrier_id'=>$log['carrier_id'], ':warehouse_id'=>$log['warehouse_id'], ':ordertype_id'=>$log['ordertype_id'], ':po_number'=>$log['po_number'], ':shipping_cost'=>$log['shipping_cost'], ':tracking'=>$log['tracking'], ':title'=>$log['title'])
				);		
	$result2 = pdo_fetch_all($stmt);
    $log_id = $result2[0]["id"];   
    $response["log_id"] = $log_id;
					     					 
	//$rowcount = pdo_rows_affected( $stmt );
	/*if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response["test"] = "test test";
		echo json_encode($response);
		exit;
	}*/
	
    $result = pdo_fetch_array($stmt);
	$response['code'] = 'success';
	$response['data'] = $result; 
	$response["test"] = "8888888";
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

