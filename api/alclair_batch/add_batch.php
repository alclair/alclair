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
	$batch = array();

	$batch["batch_type_id"] = $_POST["batch_type_id"];
	$batch["batch_status_id"] = $_POST["batch_status_id"];		
	
	$batch["batch_name"] = $_POST["batch_name"];	
	$batch["batch_notes"] = $_POST["batch_notes"];		
	
	//if(!empty($_REQUEST["ShippedDate"])) {
	if($batch["batch_status_id"] == 2) { // MEANS NOT SHIPPED/MAILED
		$batch["shipped_date"] = $_REQUEST["ShippedDate"];
		$batch["shipped_by"] = $_SESSION["UserId"];
		//$params[":ShippedDate"]=$_REQUEST["ShippedDate"];
	} else {
		$batch["shipped_date"] = NULL;
		$batch["shipped_by"] = NULL;
	}
	
	$response["test"] = "Type ID " . $batch["batch_type_id"] . " Status ID " . $batch["batch_status_id"] . "Name " . $batch["batch_name"] . " Notes " . $batch["batch_notes"] . " Date and ID " . $batch["shipped_date"] . $batch["shipped_by"];
	$response["test"] = $batch["shipped_date"];
	//echo json_encode($response);
	//exit;
		
	//$response["test1"] = count($batch["serial_numbers"]);
	//$response["test1"] = $_REQUEST["serial_numbers"];
		
//////////////////////////////////////////////////////////////////////              BATCH          //////////////////////////////////////////////////////////////////////////
//for ($i = 0; $i < $num_of_sn; $i++) {
	
	//// INSERT INTO ORDER TRACKING THE SERIAL NUMBER AND LOG ID					
	$stmt = pdo_query( $pdo, "INSERT INTO batches (created_by_id, created_date, batch_type_id, batch_status_id, shipped_by, shipped_date, batch_name, batch_notes, active, archive) VALUES (:created_by_id, now(), :batch_type_id, :batch_status_id, :shipped_by, :shipped_date, :batch_name, :batch_notes, :active, :archive) RETURNING id",
			array("created_by_id"=>$_SESSION["UserId"], ":batch_type_id"=>$batch["batch_type_id"], ":batch_status_id"=>$batch["batch_status_id"], ":shipped_by"=>$batch["shipped_by"], ":shipped_date"=>$batch["shipped_date"], ":batch_name"=>$batch["batch_name"], ":batch_notes"=>$batch["batch_notes"], ":active"=>TRUE, ":archive"=>FALSE));					
//}     
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//$response["test"] = $new[1];
	//$response["test2"] = $count;

	$result = pdo_fetch_array($stmt);
	$response["code"]="success";
	$response["data"] = $result;
	echo json_encode($response);
	//$response["test"] = $batch["new"];
	
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