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
		
	$response['code'] = 'before everything';
	
    $qc_form = array();
    $qc_id = $_REQUEST['id'];
    $order_status_id = $_REQUEST['order_status_id'];
    $notes = $_REQUEST['notes'];
	
	
	//if($_SESSION['IsAdmin'] == 0) {
	$stmt = pdo_query($pdo, "SELECT * FROM qc_form WHERE id = :id", array(":id"=>$qc_id));
	$get_stmt = pdo_fetch_all( $stmt );
	$id_of_order = $get_stmt[0]["id_of_order"];
	$id_of_repair = $get_stmt[0]["id_of_repair"];
	
	$stmt = pdo_query( $pdo,  'UPDATE qc_form SET notes = :notes WHERE id = :id', array("id"=>$qc_id, ":notes"=>$notes));
	
	if($get_stmt[0]["id_of_order"] != NULL) {
		$stmt = pdo_query( $pdo,  'UPDATE import_orders SET order_status_id = :order_status_id WHERE id = :id', array(":id"=>$id_of_order, ":order_status_id"=>$order_status_id));
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		//$query = pdo_query($pdo, "SELECT * FROM order_status_table WHERE status_of_order = 'Digital Impression Detailing'", null);
		//$result = pdo_fetch_array($query);
		//$status_id = $result["order_in_manufacturing"];
			
		// ORDER STATUS LOG
		// IMPORT ORDERS			
		$stmt = pdo_query( $pdo, "INSERT INTO order_status_log (date, import_orders_id, order_status_id, notes,  user_id) VALUES (now(), :import_orders_id, :status_id, :notes, :user_id) RETURNING id", array(':import_orders_id'=>$id_of_order, ':status_id'=>$order_status_id, ':notes'=>$notes, ':user_id'=>$_SESSION['UserId']));					 					 
		$rowcount = pdo_rows_affected( $stmt );
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	} else {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		//$query = pdo_query($pdo, "SELECT * FROM repair_status_table WHERE status_of_repair = 'Quality Control'", null);
		//$result = pdo_fetch_array($query);
		//$status_id = $result["order_in_repair"];
		$stmt = pdo_query( $pdo,  'UPDATE repair_form SET repair_status_id = :repair_status_id WHERE id = :id', array(":id"=>$id_of_repair, ":repair_status_id"=>$order_status_id));
		// ORDER STATUS LOG
		// IMPORT ORDERS			
		$stmt = pdo_query( $pdo, "INSERT INTO repair_status_log (date, repair_form_id, repair_status_id, notes,  user_id) VALUES (now(), :repair_form_id, :status_id, :notes, :user_id) RETURNING id", array(':repair_form_id'=>$id_of_repair, ':status_id'=>$order_status_id, ':notes'=>$notes, ':user_id'=>$_SESSION['UserId']));					 					 
			 
		$rowcount = pdo_rows_affected( $stmt );
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
	$response['code'] = 'success';
	$response['data'] = $rowcount;
	$response['testing1'] = "Made it.";
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>