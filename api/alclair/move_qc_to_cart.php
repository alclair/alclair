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
	
	$stmt = pdo_query( $pdo,  'UPDATE qc_form SET notes = :notes WHERE id = :id', array("id"=>$qc_id, ":notes"=>$notes));
	$stmt = pdo_query( $pdo,  'UPDATE import_orders SET order_status_id = :order_status_id WHERE id = :id', array(":id"=>$id_of_order, ":order_status_id"=>$order_status_id));
	
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