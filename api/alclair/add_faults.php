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
	$export = array();

	$export["fault_classification"] = $_POST["classification"];
	
	if($_POST["classification"] == 'Fit') {
		$export["the_fault_id"] = $_POST["description_id"];	
	}
	if($_POST["classification"] == 'Sound') {
		$export["the_fault_id"] = $_POST["description_id"];	
	}
	if($_POST["classification"] == 'Design') {
		$export["the_fault_id"] = $_POST["description_id"];	
	}
	
	//$reviewers_id = $_POST["reviewers_id"];
	$id_of_rma = $_REQUEST["id_of_repair"];
	
	//$response["test1"] = count($export["serial_numbers"]);
	//$response["test1"] = $_REQUEST["serial_numbers"];
		
//////////////////////////////////////////////////////////////////////              EXPORT          //////////////////////////////////////////////////////////////////////////
//for ($i = 0; $i < $num_of_sn; $i++) {
	
	//// INSERT INTO ORDER TRACKING THE SERIAL NUMBER AND LOG ID
	$stmt = pdo_query( $pdo, "INSERT INTO rma_faults_log (id_of_rma, classification, description_id, date, active) VALUES (:id_of_rma, :classification, :description_id, now(), :active) RETURNING id",
			array(':id_of_rma'=>$id_of_rma, ':classification'=>$export["fault_classification"], ':description_id' =>$export['the_fault_id'], ':active'=>TRUE));			
//}     
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//$response["test"] = $new[1];
	//$response["test2"] = $count;


	$response['code']='success';
	$response['data'] = $result;
	echo json_encode($response);
	//$response['test'] = $export["new"];
	
	//var_export($result);
	
	//echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>