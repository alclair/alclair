<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    //return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{
		
	$contact = array();
	
	$contact['customer'] = $_POST['customer'];
	$contact['contact_name'] = $_POST['contact_name'];

	$contact['address'] = $_POST['address'];
	$contact['email'] = $_POST['email'];
	$contact['phone'] = $_POST['phone'];
	$contact['notes'] = $_POST['notes'];
	
	if($_REQUEST["new_contact"] == 1) { // NEW CONTACT
		
		$stmt = pdo_query( $pdo, "INSERT INTO trd_customer (active) VALUES (TRUE) RETURNING id", null);	
		$result = pdo_fetch_all($stmt);
		$customer_id = $result[0]["id"];	 
						
	// IF 1  -  DO NOT ADD A NEW CONTACT - ADD A NEW ADDRESS LINE - ADD A NEW LINK BETWEEN CONTACT AND ADDRESS LINE
	}  else { // NOT A NEW CONTACT OR NEW ADDRESS
		$customer_id = $_REQUEST["customer_id"];
	}
	
	$response["test"] = "ID is " . $customer_id . " and name is " . $contact["customer"] . " contact is" . $contact["contact_name"];
	//echo json_encode($response);
	//exit;


	$stmt = pdo_query( $pdo, "UPDATE trd_customer SET customer=:customer, contact_name=:contact_name, address=:address, email=:email, phone=:phone, notes=:notes WHERE id=:id", 
	array(":customer"=>$contact["customer"], ":contact_name"=>$contact["contact_name"], ":address"=>$contact["address"], ":email"=>$contact["email"], ":phone"=>$contact["phone"], ":notes"=>$contact["notes"], ":id"=>$customer_id));	

    $result = pdo_fetch_array($stmt);
	$response['code'] = 'success';
	$response['data'] = $result; 
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
