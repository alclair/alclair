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
	$start_cart = array();
	
	if($_SESSION["UserName"] == 'Scott') {
		$start_cart['notes'] = "Moved to done by Scott through the Repairs page. ";
	} elseif($_SESSION["UserName"] == 'Amanda') {
		$start_cart['notes'] = "Moved to done by Amanda through the Repairs page. ";
	} elseif($_SESSION["UserName"] == 'Will') {
		$start_cart['notes'] = "Moved to done by Will through the Repairs page. ";
	} else {
		$start_cart['notes'] = "Moved to done by Tyler through the Repairs page. ";
	}
	$repair_id_is = $_REQUEST["ID"];
	$status_id =14;
	
// ORDER STATUS LOG
// IMPORT ORDERS			
$stmt = pdo_query( $pdo, "INSERT INTO repair_status_log_active_hp (date, repair_form_id, repair_status_id, notes,  user_id) VALUES (:date, :repair_form_id, :status_id, :notes, :user_id) RETURNING id",
									array(':date'=>$_REQUEST['DoneDate'], ':repair_form_id'=>$repair_id_is, ':status_id'=>$status_id, ':notes'=>$start_cart['notes'], ':user_id'=>$_SESSION['UserId']));					 					 
	 
$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 ) {
	$response['message'] = pdo_errors();
	$response["testing8"] = "8888888";
	echo json_encode($response);
	exit;
}

$stmt = pdo_query( $pdo, 'UPDATE repair_form_active_hp SET repair_status_id = :repair_status_id WHERE id = :id',
								   array("id"=>$repair_id_is, "repair_status_id"=>$status_id));
								   

$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 )
{
	$response['message'] = pdo_errors();
	$response['code'] = 'NOPE';
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