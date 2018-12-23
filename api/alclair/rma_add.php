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

	$repair_form = array();
	$repair_form['customer_name'] = $_POST['customer_name'];
	$repair_form['email'] = $_POST['email'];
	$repair_form['phone'] = $_POST['phone'];
	$repair_form['address'] = $_POST['address'];
	$repair_form['monitor_id'] = $_POST['monitor_id'];
	$repair_form['diagnosis'] = $_POST['diagnosis'];
	$repair_form['quotation'] = $_POST['quotation'];
	
	$repair_form['artwork_white'] = $_POST['artwork_white'];
	$repair_form['artwork_black'] = $_POST['artwork_black'];
	$repair_form['artwork_logo'] = $_POST['artwork_logo'];
	$repair_form['artwork_icon'] = $_POST['artwork_icon'];
	$repair_form['artwork_stamp'] = $_POST['artwork_stamp'];
	$repair_form['artwork_script'] = $_POST['artwork_script'];
	$repair_form['artwork_custom'] = $_POST['artwork_custom'];
	
	$repair_form['left_alclair_logo'] = $_POST['left_alclair_logo'];
	$repair_form['right_alclair_logo'] = $_POST['right_alclair_logo'];
	
	$repair_form['shell_left_color'] = $_POST['shell_left_color'];
	$repair_form['shell_right_color'] = $_POST['shell_right_color'];
	$repair_form['shell_left_face'] = $_POST['shell_left_face'];
	$repair_form['shell_right_face'] = $_POST['shell_right_face'];
	$repair_form['shell_left_tip'] = $_POST['shell_left_tip'];
	$repair_form['shell_right_tip'] = $_POST['shell_right_tip'];
	
	$repair_form['customer_contacted'] = $_POST['customer_contacted'];
	$repair_form['warranty_repair'] = $_POST['warranty_repair'];
	$repair_form['customer_billed'] = $_POST['customer_billed'];
	$repair_form['consulted'] = $_POST['consulted'];
	$repair_form['name_contacted'] = $_POST['name_contacted'];
	$repair_form['personal_item'] = $_POST['personal_item'];
	
	$repair_form['received_date'] = $_POST['received_date'];
	$repair_form['estimated_ship_date'] = $_POST['estimated_ship_date'];
	$repair_form['rma_number'] = $_POST['rma_number'];
	$repair_form['active'] = $_POST['active'];
	
	if(empty($repair_form['customer_name']))
	{
		$response['message'] = 'Please enter a customer name.';
		echo json_encode($response);
		exit;
	}
	/*if(empty($repair_form['phone']))
	{
		$response['message'] = 'Please enter a phone number.';
		echo json_encode($response);
		exit;
	}
	if(empty($repair_form['email']))
	{
		$response['message'] = 'Please enter an email address.';
		echo json_encode($response);
		exit;
	}*/
	if($repair_form['monitor_id']  < 1)
	{
		$response['message'] = 'Please select a monitor.';
		echo json_encode($response);
		exit;
	}

$repair_form['build_type_id'] = 2;
	
$stmt = pdo_query( $pdo, 
					   "INSERT INTO repair_form (
customer_name, email, phone, monitor_id, address, diagnosis, quotation, artwork_white, artwork_black, artwork_logo, artwork_icon, artwork_stamp, artwork_script, artwork_custom, shell_left_color, shell_right_color, shell_left_face, shell_right_face, shell_left_tip, shell_right_tip, left_alclair_logo, right_alclair_logo, customer_contacted, warranty_repair, customer_billed, consulted, name_contacted, personal_item, received_date, estimated_ship_date, date_entered, rma_number,  entered_by, active, repair_status_id)
VALUES (
:customer_name, :email, :phone, :monitor_id, :address, :diagnosis, :quotation, :artwork_white, :artwork_black, :artwork_logo, :artwork_icon, :artwork_stamp, :artwork_script, :artwork_custom, :shell_left_color, :shell_right_color, :shell_left_face, :shell_right_face, :shell_left_tip, :shell_right_tip, :left_alclair_logo, :right_alclair_logo, :customer_contacted, :warranty_repair, :customer_billed, :consulted, :name_contacted, :personal_item, :received_date, :estimated_ship_date, now(), :rma_number,  :entered_by, :active, :repair_status_id) RETURNING id",
array(':customer_name'=>$repair_form['customer_name'], ':email'=>$repair_form['email'],':phone'=>$repair_form['phone'], ':address'=>$repair_form['address'], ':monitor_id'=>$repair_form['monitor_id'], ':diagnosis'=>$repair_form['diagnosis'], ':quotation'=>$repair_form['quotation'], ':artwork_white'=>$repair_form['artwork_white'], ':artwork_black'=>$repair_form['artwork_black'], ':artwork_logo'=>$repair_form['artwork_logo'], ':artwork_icon'=>$repair_form['artwork_icon'], ':artwork_stamp'=>$repair_form['artwork_stamp'], ':artwork_script'=>$repair_form['artwork_script'], ':artwork_custom'=>$repair_form['artwork_custom'], ':shell_left_color'=>$repair_form['shell_left_color'], ':shell_right_color'=>$repair_form['shell_right_color'], ':shell_left_face'=>$repair_form['shell_left_face'], ':shell_right_face'=>$repair_form['shell_right_face'], ':shell_left_tip'=>$repair_form['shell_left_tip'], ':shell_right_tip'=>$repair_form['shell_right_tip'], ':left_alclair_logo'=>$repair_form['left_alclair_logo'], ':right_alclair_logo'=>$repair_form['right_alclair_logo'],
 ':customer_contacted' =>$repair_form['customer_contacted'],  ':warranty_repair' =>$repair_form['warranty_repair'], ':customer_billed'=>$repair_form['customer_billed'], ':consulted'=>$repair_form['consulted'], ':name_contacted'=>$repair_form['name_contacted'], ':personal_item'=>$repair_form['personal_item'], ':received_date'=>$repair_form['received_date'], ':estimated_ship_date'=>$repair_form['estimated_ship_date'], ':rma_number'=>$repair_form['rma_number'], ":entered_by"=>$_SESSION['UserId'], ":active"=>TRUE, ':repair_status_id'=>99)
);					 		

$id_after_add_repair = pdo_fetch_all( $stmt );
$id_of_repair = $id_after_add_repair[0]["id"];

$response["id_of_repair"] = $id_of_repair;

//$result = pdo_fetch_array($stmt);
	$response["test"] = $id_after_add_repair[0]["id"];
	//echo json_encode($response);
	//exit;

$response['test'] = $id_of_repair; 
//echo json_encode($response);
//	exit;

$stmt = pdo_query( $pdo, "INSERT INTO qc_form (customer_name, order_id, monitor_id, build_type_id, notes, active, qc_date, pass_or_fail, id_of_repair)
											   	 VALUES (:customer_name, :order_id, :monitor_id, :build_type_id, :notes, :active, now(), :pass_or_fail, :id_of_repair) RETURNING id",
											   	 array(':customer_name'=>$repair_form['customer_name'], ':order_id'=>$repair_form['rma_number'],':monitor_id'=>$repair_form['monitor_id'], ':build_type_id'=>$repair_form['build_type_id'], ':notes'=>$repair_form['diagnosis'], ":active"=>TRUE, ":pass_or_fail"=>"IMPORTED", ":id_of_repair"=>$id_of_repair));				

$id_of_qc_form = pdo_fetch_all( $stmt );

$stmt = pdo_query( $pdo, "UPDATE repair_form SET id_of_qc_form = :id_of_qc_form WHERE id = :id_of_repair RETURNING id", array(":id_of_qc_form"=>$id_of_qc_form[0]["id"], ":id_of_repair"=>$id_of_repair));
	 
	/*$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}*/
    
    //$response["id_to_print"]=$result[0]["id"];
	$response['code'] = 'success';
	$response['data'] = $id_after_add_repair; 
	$response['the_ID'] = $id_after_add_repair[0]["id"]; 
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>