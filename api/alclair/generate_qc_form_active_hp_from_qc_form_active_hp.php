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

$stmt = pdo_query( $pdo, "SELECT * FROM qc_form_active_hp WHERE id = :id", array(':id'=>$_REQUEST["id"]));		
$qc_form_1 = pdo_fetch_all( $stmt );											   	 

$response["test"] = "The ID is " . $_REQUEST["id"] . " and the name is " . $qc_form_1[0]["customer_name"];

// BUILD TYPE ID
// IF 1 MEANS NEW ORDER THEN BUILD TYPE ID IS 3 "NEW ORDER - ORIGINALLY FAILED QC"
// IF 2 MEANS REPAIR THEN BUILD TYPE ID IS 4 "REPAIR - ORIGINALLY FAILED QC"
// CAN ONLY BE 1 OR 2
if($qc_form_1[0]["build_type_id"] == 1) {
	$build_type_id = 3;
} else {
	$build_type_id = 4;	
}
	
$stmt = pdo_query( $pdo, "INSERT INTO qc_form_active_hp (customer_name, order_id, monitor_id, build_type_id, notes, active, qc_date, pass_or_fail, id_of_order, id_of_repair)
											   	 VALUES (:customer_name, :order_id, :monitor_id, :build_type_id, :notes, :active, now(), :pass_or_fail, :id_of_order, :id_of_repair) RETURNING id",
											   	 array(':customer_name'=>$qc_form_1[0]['customer_name'], ':order_id'=>$qc_form_1[0]['order_id'],':monitor_id'=>$qc_form_1[0]['monitor_id'], ':build_type_id'=>$build_type_id, ':notes'=>$qc_form_1[0]['notes'], ":active"=>TRUE, ":pass_or_fail"=>"IMPORTED", 
											   	 ":id_of_order"=>$qc_form_1[0]["id_of_order"],
											   	 ":id_of_repair"=>$qc_form_1[0]["id_of_repair"]));				

$qc_form_2 = pdo_fetch_all( $stmt );

//$response["test"] = $qc_form_2[0]["id"]; 
//echo json_encode($response);
//exit;

	/*$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}*/
    
    //$response["id_to_print"]=$result[0]["id"];
	$response['code'] = 'success';
	$response['data'] = $qc_form_2; 
	$response['the_ID'] = $qc_form_2[0]["id"]; 
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>