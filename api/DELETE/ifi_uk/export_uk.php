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
	$export["company"] = $_POST["company"];
	$export["name"] = $_POST["name"];
	$export["address_1"] = $_POST["address_1"];
	$export["address_2"] = $_POST["address_2"];
	$export["city"] = $_POST["city"];
	$export["state"] = $_POST["state"];
	$export["zip_code"] = $_POST["zip_code"];
	$export["country"] = $_POST["country"];
	$export["carrier_id"] = $_POST["carrier_id"];
	$export["tracking"] = $_POST["tracking"];
	$export["notes"] = $_POST["notes"];
	$export["discount"] = $_POST["discount"];
	$export["sn"] = $_POST["serial_numbers"];
	
	//$log_id = $_POST["log_id"];
	$log_id = $_REQUEST["log_id"];
	
	//$response["test1"]=$log_id;
	//$response["test2"]=$log_id2;
	//echo json_encode($response);
	//exit;
	
	//$response["test1"] = count($export["serial_numbers"]);
	//$response["test1"] = $_REQUEST["serial_numbers"];
	$serial_numbers = explode("\n",$export["sn"]);
	$num_of_sn = count($serial_numbers);
	$response["test1"] = $export["sn"];
	$response["test1"]=$log_id;
	//$response["test2"] = $serial_numbers[0];
	//count($export["serial_numbers"]);
	//echo json_encode($response);
	//exit;
	
	// THIS CODE EXITS IF A SERIAL NUMBER DOES NOT EXIST IN INVENTORY
	for ($i = 0; $i < $num_of_sn; $i++) {
		$stmt = pdo_query( $pdo, 'SELECT * FROM serial_numbers_uk WHERE serial_number = :serial_number', array(":serial_number"=>$serial_numbers[$i]));	
		$result = pdo_fetch_all($stmt);
		$rowcount = pdo_rows_affected( $stmt );
		
		if ($rowcount == 0) {
			$response['message'] = 'SN ' .  $serial_numbers[$i] .  ' is not in inventory.';
			$response["test3"] = $rowcount;
			echo json_encode($response);
			exit;
		}
     }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
//////////////////////////////////////////////////////////////////////              EXPORT          //////////////////////////////////////////////////////////////////////////
for ($i = 0; $i < $num_of_sn; $i++) {
	//// UPDATE SERIAL NUMBER WITH NEW STATUS OF SHIPPED
	$query = "UPDATE serial_numbers_uk SET status = 'SHIPPED' WHERE serial_number = :serial_number";
	$params=array(":serial_number"=>$serial_numbers[$i]);
	pdo_query($pdo,$query,$params);
	
	//// GET THE NEXT LOG ID NUMBER
	
	//// INSERT INTO ORDER TRACKING THE SERIAL NUMBER AND LOG ID
	$stmt = pdo_query( $pdo, "INSERT INTO order_tracking_uk (serial_number, date, status, discount, notes, log_id) VALUES (:serial_number, now(), :status, :discount, :notes, :log_id) RETURNING id",
			array(':serial_number'=>$serial_numbers[$i], ':status'=>"SHIPPED", ':discount' =>$export['discount'], ':notes'=>"Customer", ":log_id"=>$log_id));			
}     
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//$response["test"] = $new[1];
	$response["test2"] = $count;


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