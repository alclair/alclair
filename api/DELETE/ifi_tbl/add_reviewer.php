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
	$reviewer = array();
	$reviewer['title_id'] = $_POST['title_id'];
	$reviewer['firstname'] = $_POST['firstname'];
	$reviewer['lastname'] = $_POST['lastname'];
	$reviewer['tag_id'] = $_POST['tag_id'];
	$reviewer['iclub_id'] = $_POST['iclub_id'];
	$reviewer['status_id'] = $_POST['status_id'];
	
	$reviewer['address_1'] = $_POST['address_1'];
	$reviewer['address_2'] = $_POST['address_2'];
	$reviewer['address_3'] = $_POST['address_3'];
	$reviewer['address_4'] = $_POST['address_4'];
	
	$reviewer['city'] = $_POST['city'];
	$reviewer['state'] = $_POST['state'];
	$reviewer['zip'] = $_POST['zip'];
	$reviewer['country_id'] = $_POST['country_id'];	
	$reviewer['notes'] = $_POST['notes'];	
	
	$reviewer['ra_sent'] = $_POST['ra_sent'];
	$reviewer['ra_signed'] = $_POST['ra_signed'];
	$reviewer['nda_sent'] = $_POST['nda_sent'];
	$reviewer['nda_signed'] = $_POST['nda_signed'];
		 					 
$stmt = pdo_query( $pdo, 
					   "INSERT INTO reviewers (title_id, firstname, lastname, tag_id, iclub_id, status_id, address_1, address_2, address_3, address_4, city, state, zip, country_id, date, notes, ra_sent, ra_signed, nda_sent, nda_signed, active)
					     VALUES (:title_id, :firstname, :lastname, :tag_id, :iclub_id, :status_id, :address_1, :address_2, :address_3, :address_4, :city, :state, :zip, :country_id, now(), :notes, :ra_sent, :ra_signed, :nda_sent, :nda_signed, :active) RETURNING id",
						 array(':title_id'=>$reviewer['title_id'], ':firstname'=>$reviewer['firstname'], ':lastname'=>$reviewer['lastname'], 
						 ':tag_id'=>$reviewer['tag_id'], ':iclub_id'=>$reviewer['iclub_id'], ':status_id'=>$reviewer['status_id'],
						 ':address_1'=>$reviewer['address_1'], ':address_2'=>$reviewer['address_2'], ':address_3'=>$reviewer['address_3'], ':address_4'=>$reviewer['address_4'], 
						 ':city'=>$reviewer['city'],  ':state'=>$reviewer['state'], ':zip'=>$reviewer['zip'],  ':country_id'=>$reviewer['country_id'], ':notes'=>$reviewer['notes'],
						 ':ra_sent'=>$reviewer['ra_sent'], ':ra_signed'=>$reviewer['ra_signed'], ':nda_sent'=>$reviewer['nda_sent'], ':nda_signed'=>$reviewer['nda_signed'], ':active'=>$reviewer['active'])
				);		
    				 
	$result2 = pdo_fetch_all($stmt);
    $reviewers_id = $result2[0]["id"];   
    $response["reviewers_id"] = $reviewers_id;		
    		
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