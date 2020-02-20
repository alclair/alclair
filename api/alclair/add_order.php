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
			
	$traveler = array();
	$traveler['order_id'] = $_POST['order_id'];
	$traveler['designed_for'] = $_POST['designed_for'];
	//$traveler['email'] = $_POST['email'];
	$traveler['phone'] = $_POST['phone'];	
	$traveler['band_church'] = $_POST['band_church'];
	$traveler['address'] = $_POST['address'];
	$traveler['notes'] = $_POST['notes'];

	$traveler['left_tip'] = $_POST['left_tip'];
	$traveler['right_tip'] = $_POST['right_tip'];	
	$traveler['left_shell'] = $_POST['left_shell'];
	$traveler['right_shell'] = $_POST['right_shell'];
	$traveler['left_faceplate'] = $_POST['left_faceplate'];
	$traveler['right_faceplate'] = $_POST['right_faceplate'];
	
	$traveler['model'] = $_POST['model'];
	$traveler['cable_color'] = $_POST['cable_color'];
	$traveler['customer_type'] = $_POST['customer_type'];
	$traveler['artwork'] = $_POST['artwork'];
	$traveler['left_alclair_logo'] = $_POST['left_alclair_logo'];
	$traveler['right_alclair_logo'] = $_POST['right_alclair_logo'];
	
	$traveler['additional_items'] = $_POST['additional_items'];
	$traveler['consult_highrise'] = $_POST['consult_highrise'];
	$traveler['international'] = $_POST['international'];
	$traveler['universals'] = $_POST['universals'];
	
	$traveler['hearing_protection'] = $_POST['hearing_protection'];
	$traveler['hearing_protection_color'] = $_POST['hearing_protection_color'];
	$traveler['musicians_plugs'] = $_POST['musicians_plugs'];
	$traveler['musicians_plugs_9db'] = $_POST['musicians_plugs_9db'];
	$traveler['musicians_plugs_15db'] = $_POST['musicians_plugs_15db'];
	$traveler['musicians_plugs_25db'] = $_POST['musicians_plugs_25db'];
	$traveler['pickup'] = $_POST['pickup'];
	
	$traveler['nashville_order'] = $_POST['nashville_order'];
	
	$traveler['date'] = $_POST['date'];
	$traveler['estimated_ship_date'] = $_POST['estimated_ship_date'];
	$traveler['received_date'] = $_POST['received_date'];
	
	$traveler['impression_color_id'] = $_POST['impression_color_id'];
	
	if ($_POST['rush_process'] == 1) {
		$traveler['rush_process'] = 'Yes';
	} 
	
	
	if(empty($traveler['order_id']) == true)
	{
		$response['message'] = 'Please enter a order ID.';
		echo json_encode($response);
		exit;
	}
	if(empty($traveler['designed_for']) == true)
	{
		$response['message'] = 'Please enter who the earphone is designed for.';
		echo json_encode($response);
		exit;
	}
	if($traveler['customer_type'] == 0)
	{
		$response['message'] = 'Please enter a customer type.';
		echo json_encode($response);
		exit;
	}
	/*if($traveler['model']  == NULL)
	{
		$response['message'] = 'Please select a monitor.';
		echo json_encode($response);
		exit;
	}*/
	/*if(strlen($traveler['cable_color'])  < 3)
	{
		$response['message'] = 'Please select a cable preference.';
		echo json_encode($response);
		exit;
	}*/
	
		$date_today = date("Y-m-d H:i:s");
			
$stmt = pdo_query( $pdo, 
					   "INSERT INTO import_orders (
order_id, designed_for, phone, band_church, address, notes, model, left_tip, right_tip, left_shell, right_shell, left_faceplate, right_faceplate, cable_color, artwork, left_alclair_logo, right_alclair_logo, additional_items, consult_highrise, international, universals, hearing_protection, musicians_plugs, musicians_plugs_9db, musicians_plugs_15db, musicians_plugs_25db, pickup, estimated_ship_date, received_date, date, entered_by,  rush_process, active, impression_color_id, order_status_id, link_to_design_image, hearing_protection_color, nashville_order, customer_type)
VALUES (
:order_id, :designed_for, :phone, :band_church, :address, :notes, :model, :left_tip, :right_tip, :left_shell, :right_shell, 
:left_faceplate, :right_faceplate, :cable_color, :artwork, :left_alclair_logo, :right_alclair_logo, :additional_items, :consult_highrise, :international, :universals, :hearing_protection, :musicians_plugs, 
:musicians_plugs_9db, :musicians_plugs_15db, :musicians_plugs_25db, :pickup, :estimated_ship_date, :received_date, :date, :entered_by,  :rush_process, :active, :impression_color_id, 99, ' ', :hearing_protection_color, :nashville_order, :customer_type) RETURNING id",
array(':order_id'=>$traveler['order_id'], ':designed_for'=>$traveler['designed_for'], ':phone'=>$traveler['phone'], ':band_church'=>$traveler['band_church'], ':address'=>$traveler['address'], ':notes'=>$traveler['notes'], ':model'=>$traveler['model'], ':left_tip'=>$traveler['left_tip'], ':right_tip'=>$traveler['right_tip'], ':left_shell'=>$traveler['left_shell'], ':right_shell'=>$traveler['right_shell'], ':left_faceplate'=>$traveler['left_faceplate'], ':right_faceplate'=>$traveler['right_faceplate'], ':cable_color'=>$traveler['cable_color'], ':artwork'=>$traveler['artwork'], ':left_alclair_logo'=>$traveler['left_alclair_logo'], ':right_alclair_logo'=>$traveler['right_alclair_logo'],  ':additional_items'=>$traveler['additional_items'], ':consult_highrise'=>$traveler['consult_highrise'], ':international'=>$traveler['international'], ':universals'=>$traveler['universals'], ':hearing_protection'=>$traveler['hearing_protection'], ':musicians_plugs'=>$traveler['musicians_plugs'], ':musicians_plugs_9db'=>$traveler['musicians_plugs_9db'], ':musicians_plugs_15db'=>$traveler['musicians_plugs_15db'], ':musicians_plugs_25db'=>$traveler['musicians_plugs_25db'], ':pickup'=>$traveler['pickup'],':estimated_ship_date'=>$traveler['estimated_ship_date'], ':received_date'=>$traveler['received_date'], ':date'=>$traveler["date"], ":entered_by"=>$_SESSION['UserId'], ':rush_process'=>$traveler['rush_process'],":active"=>TRUE, ':impression_color_id'=>$traveler['impression_color_id'], ':hearing_protection_color'=>$traveler['hearing_protection_color'], ':nashville_order'=>$traveler['nashville_order'], ':customer_type'=>$traveler['customer_type'])
);		

$id_of_order = pdo_fetch_all( $stmt );

$get_monitor_id = pdo_query( $pdo, "SELECT * FROM monitors WHERE name = :model_name", array(":model_name"=>$traveler['model']));
$monitor_id = pdo_fetch_all( $get_monitor_id );

$stmt = pdo_query( $pdo, 
					   "INSERT INTO qc_form (customer_name, order_id, monitor_id, build_type_id, notes, active, qc_date, pass_or_fail, id_of_order)
					   	 VALUES (:customer_name, :order_id, :monitor_id, :build_type_id, :notes, :active, now(), :pass_or_fail, :id_of_order) RETURNING id",
array(':customer_name'=>$traveler['designed_for'], ':order_id'=>$traveler['order_id'],':monitor_id'=>$monitor_id[0]["id"], ':build_type_id'=>1, ':notes'=>"", ":active"=>TRUE, ":pass_or_fail"=>"IMPORTED", ":id_of_order"=>$id_of_order[0]["id"]));		

$id_of_qc_form = pdo_fetch_all( $stmt );

$stmt = pdo_query( $pdo, "UPDATE import_orders SET id_of_qc_form = :id_of_qc_form WHERE id = :id_of_order", array(":id_of_qc_form"=>$id_of_qc_form[0]["id"], ":id_of_order"=>$id_of_order[0]["id"]));
		 		 
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response["testing8"] = "8888888";
		echo json_encode($response);
		exit;
	}

	
	$response["id_of_order"] = $id_of_order[0]["id"];
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