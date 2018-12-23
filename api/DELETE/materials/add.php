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

	$material = array();
	$material['name'] = $_POST['name'];
	$material['startingQty'] = $_POST['startingQty'];
	$material['startingPrice'] = $_POST['startingPrice'];
	$material['units'] = $_POST['units'];

	
	/*if(empty($ticket['ticketnumber']) == true)
	{
		$response['message'] = 'Please input ticket number.';
		echo json_encode($response);
		exit;
	}*/
	
$stmt = pdo_query($pdo, "INSERT INTO materials (name, units, time_stamp, created_by_id, active) 
												VALUES (:name, :units, now(), :created_by_id, :active) RETURNING id",
												array(
													':name'=>$material['name'], 
													':units'=>$material['units'], 
													':created_by_id'=>$_SESSION['UserId'],
													':active'=>1)
									);			 					 
					 
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}
	

    $result = pdo_fetch_array($stmt);
	$response['code'] = 'success';
	$response['data'] = $result; 
	echo json_encode($response);
	
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>