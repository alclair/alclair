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
	$holiday = array();
	
	$response["test"] = $_POST["holiday"];
	$response['code']='success';
	//echo json_encode($response);
	//exit;

	$holiday["holiday"] = $_POST["holiday"];
	$holiday["holiday_date"] = $_POST["holiday_date"];
	
	//$response["test1"] = $_REQUEST["serial_numbers"];
		
//////////////////////////////////////////////////////////////////////              EXPORT          //////////////////////////////////////////////////////////////////////////
//for ($i = 0; $i < $num_of_sn; $i++) {
	
	//// INSERT INTO ORDER TRACKING THE SERIAL NUMBER AND LOG ID
	$stmt = pdo_query( $pdo, "INSERT INTO holiday_log (holiday, date, active) VALUES (:holiday, :holiday_date, :active) RETURNING id",
			array(':holiday'=>$holiday["holiday"], ':holiday_date'=>$holiday["holiday_date"], ':active'=>TRUE));			
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
