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
	$export["title"] = $_POST["title"];
	$export["company"] = $_POST["company"];
	$export["sector_id"] = $_POST["sector_id"];
	
	//$reviewers_id = $_POST["reviewers_id"];
	$reviewers_id = $_REQUEST["reviewers_id"];
	
	//$response["test1"] = count($export["serial_numbers"]);
	//$response["test1"] = $_REQUEST["serial_numbers"];
		
//////////////////////////////////////////////////////////////////////              EXPORT          //////////////////////////////////////////////////////////////////////////
//for ($i = 0; $i < $num_of_sn; $i++) {
	
	//// INSERT INTO ORDER TRACKING THE SERIAL NUMBER AND LOG ID
	$stmt = pdo_query( $pdo, "INSERT INTO reviewers_jobs (reviewer_id, title, company, active, sector_id) VALUES (:reviewer_id, :title, :company, :active, :sector_id) RETURNING id",
			array(':reviewer_id'=>$reviewers_id, ':title'=>$export["title"], ':company' =>$export['company'], ':active'=>TRUE, ":sector_id"=>$export["sector_id"]));			
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