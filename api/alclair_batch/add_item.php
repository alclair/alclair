<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response["data"] = null;


try
{	   
	$item = array();

	$item["batch_id"] = $_REQUEST["batch_id"];
	$item["designed_for"] = $_POST["designed_for"];
	//$item["ordered_by"] = $_POST["ordered_by"];		
	$item["impression_date"] = $_REQUEST["ImpressionDate"];
	$item["order_number"] = $_POST["order_number"];
	$item["same_name"] = $_POST["same_name"];
	$item["paid"] = $_POST["paid"];
	$item["customer_status"] = $_POST["customer_status"];
	$item["next_step_id"] = $_POST["next_step_id"];
	$item["notes"] = $_POST["notes"];		
	
	//if(!empty($_REQUEST["ShippedDate"])) {
	if($item["batch_status_id"] == 2) { // MEANS NOT SHIPPED/MAILED
		$item["shipped_date"] = $_REQUEST["ImpressionDate"];
		$item["shipped_by"] = $_SESSION["UserId"];
		//$params[":ShippedDate"]=$_REQUEST["ShippedDate"];
	} else {
		$item["shipped_date"] = NULL;
		$item["shipped_by"] = NULL;
	}
	
	$response["test"] = "Type ID " . $item["batch_id"] . " DESIGNED  " . $item["designed_for"];
	//$response["test"] = $item["shipped_date"];
	//echo json_encode($response);
	//exit;
		
//////////////////////////////////////////////////////////////////////              BATCH          //////////////////////////////////////////////////////////////////////////
//for ($i = 0; $i < $num_of_sn; $i++) {
	
	//// INSERT INTO ORDER TRACKING THE SERIAL NUMBER AND LOG ID					
/*	$stmt = pdo_query( $pdo, 
"INSERT INTO batch_item_log (batch_id, designed_for, ordered_by, impression_date, order_number, paid, same_name, customer_status, next_step_id, notes, active, added_date, entered_by)
 VALUES (:batch_id, :designed_for, :ordered_by, :impression_date, :order_number, :paid, :same_name, :customer_status, :next_step_id, :notes, :active, now(), :entered_by) RETURNING id",
			array(":batch_id"=>$item["batch_id"], ":designed_for"=>$item["designed_for"], ":ordered_by"=>$item["ordered_by"], 
					":impression_date"=>$item["impression_date"], ":order_number"=>$item["order_number"], ":paid"=>$item["paid"], ":same_name"=>$item["same_name"], ":customer_status"=>$item["customer_status"], ":next_step_id"=>$item["next_step_id"], ":notes"=>$item["notes"], ":active"=>TRUE, ":entered_by"=>$_SESSION["UserId"]));		
*/					
					
$stmt = pdo_query( $pdo, 
"INSERT INTO batch_item_log (batch_id, designed_for, impression_date, order_number, paid, customer_status, next_step_id, notes, active, added_date, entered_by)
 VALUES (:batch_id, :designed_for, :impression_date, :order_number, :paid, :customer_status, :next_step_id, :notes, :active, now(), :entered_by) RETURNING id",
			array(":batch_id"=>$item["batch_id"], ":designed_for"=>$item["designed_for"], 
					":impression_date"=>$item["impression_date"], ":order_number"=>$item["order_number"], ":paid"=>$item["paid"], ":customer_status"=>$item["customer_status"], ":next_step_id"=>$item["next_step_id"], ":notes"=>$item["notes"], ":active"=>TRUE, ":entered_by"=>$_SESSION["UserId"]));					
//}     
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//$response["test"] = $new[1];
	//$response["test2"] = $count;

	$result = pdo_fetch_array($stmt);
	$response["code"]="success";
	$response["data"] = $result;
	echo json_encode($response);
	//$response["test"] = $item["new"];
	
	//var_batch($result);
	
	//echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>