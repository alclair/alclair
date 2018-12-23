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
	$ship_to = array();
	$ship_to['title'] = $_POST['new_ship_to'];
	$ship_to['company'] = $_POST['company'];
	$ship_to['name'] = $_POST['name'];
	$ship_to['address_1'] = $_POST['address_1'];
	$ship_to['address_2'] = $_POST['address_2'];
	$ship_to['city'] = $_POST['city'];
	$ship_to['state'] = $_POST['state'];
	$ship_to['zip_code'] = $_POST['zip_code'];
	$ship_to['country'] = $_POST['country'];
	$ship_to['notes'] = $_POST['notes'];
	$ship_to['serial_numbers'] = $_POST['serial_numbers'];
		
/*$stmt = pdo_query( $pdo, 
					   "INSERT INTO shipping_addresses (company, name, address_1, address_2, city, state, zip_code, country, date, notes, serial_numbers)
					     VALUES (:company, :name, :address_1, :address_2, :city, :state, :zip_code, :country, now(), :notes, :serial_numbers) RETURNING id",
						 array(':company'=>$ship_to['company'], ':name'=>$ship_to['name'],':address_1'=>$ship_to['address_1'], ':address_2'=>$ship_to['address_2'], ':city'=>$ship_to['city'],  ':state'=>$ship_to['state'], ':zip_code'=>$ship_to['zip_code'],  ':country'=>$ship_to['country'], ':notes'=>$ship_to['notes'], ':serial_numbers'=>$ship_to['serial_number'])
				);*/					 					 

$stmt = pdo_query( $pdo, 
					   "INSERT INTO shipping_addresses (company, name, address_1, address_2, city, state, zip_code, country, date, notes, title)
					     VALUES (:company, :name, :address_1, :address_2, :city, :state, :zip_code, :country, now(), :notes, :title) RETURNING id",
						 array(':company'=>$ship_to['company'], ':name'=>$ship_to['name'],':address_1'=>$ship_to['address_1'], ':address_2'=>$ship_to['address_2'], ':city'=>$ship_to['city'],  ':state'=>$ship_to['state'], ':zip_code'=>$ship_to['zip_code'],  ':country'=>$ship_to['country'], ':notes'=>$ship_to['notes'], ':title'=>$ship_to['title'])
				);		
    				 
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response["testing8"] = "8888888";
		$response["test"] = "test test";
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