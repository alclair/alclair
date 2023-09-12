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

	$qc_form = array();
	$qc_form['id'] = $_POST['id'];
	$qc_form['customer_name'] = $_POST['customer_name'];
	$qc_form['order_id'] = $_POST['order_id'];
	$qc_form['monitor_id'] = $_POST['monitor_id'];
	$qc_form['build_type_id'] = $_POST['build_type_id'];
	
	$qc_form['shells_hp_material'] = $_POST['shells_hp_material'];
	$qc_form['shells_defects'] = $_POST['shells_defects'];
	$qc_form['shells_colors'] = $_POST['shells_colors'];
	$qc_form['shells_matched_height'] = $_POST['shells_matched_height'];
	$qc_form['shells_canal_length'] = $_POST['shells_canal_length'];
	$qc_form['shells_helix_trimmed'] = $_POST['shells_helix_trimmed'];
	$qc_form['shells_label'] = $_POST['shells_label'];
	$qc_form['shells_edges'] = $_POST['shells_edges'];
	$qc_form['shells_high_shine'] = $_POST['shells_high_shine'];
	
	$qc_form['faceplate_colors'] = $_POST['faceplate_colors'];
	$qc_form['faceplate_buffing_material'] = $_POST['faceplate_buffing_material'];
	$qc_form['faceplate_seam'] = $_POST['faceplate_seam'];
	$qc_form['faceplate_orientation'] = $_POST['faceplate_orientation'];
	$qc_form['faceplate_lanyard_loop'] = $_POST['faceplate_lanyard_loop'];
	$qc_form['faceplate_knob_buttons'] = $_POST['faceplate_knob_buttons'];
	
	$qc_form['battery_door_closes'] = $_POST['battery_door_closes'];
	$qc_form['battery_door_correct'] = $_POST['battery_door_correct'];
	$qc_form['battery_door_opens_forward'] = $_POST['battery_door_opens_forward'];
	
	$qc_form['ports_cleaned'] = $_POST['ports_cleaned'];
	$qc_form['ports_mic_flush'] = $_POST['ports_mic_flush'];
	$qc_form['ports_glued_correctly'] = $_POST['ports_glued_correctly'];
	
	$qc_form['sound_chip_programmed'] = $_POST['sound_chip_programmed'];
	$qc_form['sound_battery_signal'] = $_POST['sound_battery_signal'];
	$qc_form['sound_programs'] = $_POST['sound_programs'];
	$qc_form['sound_volume_control'] = $_POST['sound_volume_control'];
	$qc_form['sound_mic_signal'] = $_POST['sound_mic_signal'];
	$qc_form['sound_balanced_volume'] = $_POST['sound_balanced_volume'];
	
	$qc_form['notes'] = $_POST['notes'];
	
	$qc_form['shipping_cable'] = $_POST['shipping_cable'];
	$qc_form['shipping_tools'] = $_POST['shipping_tools'];
	$qc_form['shipping_card'] = $_POST['shipping_card'];
	$qc_form['shipping_case'] = $_POST['shipping_case'];
	$qc_form['shipping_additional'] = $_POST['shipping_additional'];
	
	if(empty($qc_form['customer_name']) == true)
	{
		$response['message'] = 'Please enter a customer name.';
		echo json_encode($response);
		exit;
	}
	if(empty($qc_form['order_id']) == true)
	{
		$response['message'] = 'Please enter the order id #.';
		echo json_encode($response);
		exit;
	}
	if($qc_form['monitor_id']  < 1)
	{
		$response['message'] = 'Please select a monitor.';
		echo json_encode($response);
		exit;
	}
	if($qc_form['build_type_id']  < 1)
	{
		$response['message'] = 'Please select a build type.';
		echo json_encode($response);
		exit;
	}
		
		if( 
$qc_form['shells_hp_material'] & $qc_form['shells_defects'] & $qc_form['shells_colors'] & $qc_form['shells_matched_height']  & 	$qc_form['shells_canal_length'] & $qc_form['shells_helix_trimmed']  & $qc_form['shells_label'] & $qc_form['shells_edges']  & $qc_form['shells_high_shine'] &	
$qc_form['faceplate_colors'] & $qc_form['faceplate_buffing_material'] &	$qc_form['faceplate_seam'] &	$qc_form['faceplate_orientation'] & 	$qc_form['faceplate_lanyard_loop'] & 	$qc_form['faceplate_knob_buttons'] &		
$qc_form['battery_door_closes'] &	$qc_form['battery_door_correct'] & 	$qc_form['battery_door_opens_forward'] &	
$qc_form['ports_cleaned'] &	$qc_form['ports_mic_flush'] &	$qc_form['ports_glued_correctly'] &	
$qc_form['sound_chip_programmed'] & 	$qc_form['sound_battery_signal'] & $qc_form['sound_programs'] &	$qc_form['sound_volume_control'] &	$qc_form['sound_mic_signal'] &	$qc_form['sound_balanced_volume'] &&
	 
$qc_form['shipping_cable'] == 1 && $qc_form['shipping_tools'] == 1 && $qc_form['shipping_card'] == 1 && $qc_form['shipping_case'] == 1 && $qc_form['shipping_additional'] == 1) 
	{
		$qc_form['pass_or_fail'] = 'PASS'; 
	} 
	else 
	{
		$qc_form['pass_or_fail'] = "PRODUCT CAN'T SHIP";
	}
	
	if($qc_form['pass_or_fail'] == "PRODUCT CAN'T SHIP") {
		$response['message'] = "Something is incomplete";
		echo json_encode($response);
		exit;
	}
	
$params = array();	
$params[":customer_name"]	 = $qc_form["customer_name"];
$params[":order_id"]	 = $qc_form["order_id"];
$params[":build_type_id"]	 = $qc_form["build_type_id"];
$params[":monitor_id"]	 = $qc_form["monitor_id"];

$query = "SELECT * FROM qc_form_active_hp WHERE customer_name = :customer_name AND order_id = :order_id AND build_type_id = :build_type_id AND monitor_id = :monitor_id"	;
$stmt = pdo_query( $pdo, $query, $params); 
$result = pdo_fetch_all( $stmt );
$num_rows = count($result);
$response['num_rows'] = $num_rows;



if($num_rows > 0 ) { // UPDATE THE ROW BECAUSE ALRADY EXISTS
	$stmt = pdo_query( $pdo, 
					   'UPDATE qc_form_active_hp SET customer_name = :customer_name, order_id = :order_id, monitor_id = :monitor_id, build_type_id = :build_type_id,
                       
	shells_hp_material = :shells_hp_material,
    shells_defects = :shells_defects,
    shells_colors = :shells_colors,
    shells_matched_height = :shells_matched_height,
    shells_canal_length = :shells_canal_length,
    shells_helix_trimmed = :shells_helix_trimmed,
    shells_label = :shells_label,
	shells_edges = :shells_edges,
	shells_high_shine = :shells_high_shine,

    faceplate_colors = :faceplate_colors,
    faceplate_buffing_material = :faceplate_buffing_material,
    faceplate_seam = :faceplate_seam,
	faceplate_orientation = :faceplate_orientation,
	faceplate_lanyard_loop = :faceplate_lanyard_loop,
	faceplate_knob_buttons = :faceplate_knob_buttons,

    battery_door_closes = :battery_door_closes,
    battery_door_correct = :battery_door_correct,
    battery_door_opens_forward = :battery_door_opens_forward,

    ports_cleaned = :ports_cleaned,
    ports_mic_flush = :ports_mic_flush,
    ports_glued_correctly = :ports_glued_correctly,

    sound_chip_programmed = :sound_chip_programmed,
    sound_battery_signal = :sound_battery_signal,
    sound_programs = :sound_programs,
    sound_volume_control = :sound_volume_control,
    sound_mic_signal = :sound_mic_signal,
    sound_balanced_volume = :sound_balanced_volume,
                       
                       notes = :notes,
                       shipping_cable = :shipping_cable, shipping_tools = :shipping_tools, shipping_card = :shipping_card, shipping_case = :shipping_case, shipping_additional = :shipping_additional,
                       shipping_by = :shipping_by, shipping_date = now(),
                       pass_or_fail = :pass_or_fail
                       WHERE id = :id',
					   array("id"=>$qc_form["id"], "customer_name"=>$qc_form['customer_name'], "order_id"=>$qc_form['order_id'], "monitor_id"=>$qc_form['monitor_id'], 						"build_type_id"=>$qc_form['build_type_id'], 
					   
    ':shells_hp_material'=>$qc_form['shells_hp_material'],
    ':shells_defects'=>$qc_form['shells_defects'],
    ':shells_colors'=>$qc_form['shells_colors'],
    ':shells_matched_height'=>$qc_form['shells_matched_height'],
    ':shells_canal_length'=>$qc_form['shells_canal_length'],
    ':shells_helix_trimmed'=>$qc_form['shells_helix_trimmed'],
    ':shells_label'=>$qc_form['shells_label'],
	':shells_edges'=>$qc_form['shells_edges'],
	':shells_high_shine'=>$qc_form['shells_high_shine'],

    ':faceplate_colors'=>$qc_form['faceplate_colors'],
    ':faceplate_buffing_material'=>$qc_form['faceplate_buffing_material'],
    ':faceplate_seam'=>$qc_form['faceplate_seam'],
	':faceplate_orientation'=>$qc_form['faceplate_orientation'],
	':faceplate_lanyard_loop'=>$qc_form['faceplate_lanyard_loop'],
	':faceplate_knob_buttons'=>$qc_form['faceplate_knob_buttons'],

    ':battery_door_closes'=>$qc_form['battery_door_closes'],
    ':battery_door_correct'=>$qc_form['battery_door_correct'],
    ':battery_door_opens_forward'=>$qc_form['battery_door_opens_forward'],

    ':ports_cleaned'=>$qc_form['ports_cleaned'],
    ':ports_mic_flush'=>$qc_form['ports_mic_flush'],
    ':ports_glued_correctly'=>$qc_form['ports_glued_correctly'],

    ':sound_chip_programmed'=>$qc_form['sound_chip_programmed'],
    ':sound_battery_signal'=>$qc_form['sound_battery_signal'],
    ':sound_programs'=>$qc_form['sound_programs'],
    ':sound_volume_control'=>$qc_form['sound_volume_control'],
    ':sound_mic_signal'=>$qc_form['sound_mic_signal'],
    ':sound_balanced_volume'=>$qc_form['sound_balanced_volume'],
					   
					   "notes"=>$qc_form['notes'],
					   "shipping_cable"=>$qc_form['shipping_cable'], "shipping_tools"=>$qc_form['shipping_tools'], "shipping_card"=>$qc_form['shipping_card'], 						"shipping_case"=>$qc_form['shipping_case'], "shipping_additional"=>$qc_form['shipping_additional'], "shipping_by"=>$_SESSION['UserId'],
					   "pass_or_fail"=>$qc_form['pass_or_fail'])
                       	  //,1
					 );
		
	/*$response['message'] = "Number of rows > 0";
	echo json_encode($response);
	exit;*/
} // CLOSE IF STATEMENT

else { // NO ROW FOR THE EARPHONE SO MUST INSERT ROW INSTEAD OF UPDATE
	
$stmt = pdo_query( $pdo, 
					   "INSERT INTO qc_form_active_hp (
customer_name, order_id, monitor_id, build_type_id, 

shells_defects, shells_colors, shells_faced_down, shells_label, shells_edges, shells_shine, shells_canal, shells_density, faceplate_seams, faceplate_shine, faceplate_colors, faceplate_rounded, faceplate_foggy, faceplate_residue, jacks_location, jacks_debris, jacks_cable, ports_cleaned, ports_smooth, ports_glued_correctly, ports_kinked_tube, ports_crushed_damper, sound_signature, sound_balanced, sound_correct_model, 

	shells_hp_material,
    shells_defects,
    shells_colors,
    shells_matched_height,
    shells_canal_length,
    shells_helix_trimmed,
    shells_label,
	shells_edges,
	shells_high_shine,

    faceplate_colors,
    faceplate_buffing_material,
    faceplate_seam,
	faceplate_orientation,
	faceplate_lanyard_loop,
	faceplate_knob_buttons,

    battery_door_closes,
    battery_door_correct,
    battery_door_opens_forward,

    ports_cleaned,
    ports_mic_flush,
    ports_glued_correctly,

    sound_chip_programmed,
    sound_battery_signal,
    sound_programs,
    sound_volume_control,
    sound_mic_signal,
    sound_balanced_volume,
    
notes, shipping_cable, shipping_tools, shipping_card, shipping_case, shipping_additional, shipping_by, shipping_date, pass_or_fail)
VALUES (
:customer_name, :order_id, :monitor_id, :build_type_id, 

	:shells_hp_material,
    :shells_defects,
    :shells_colors,
    :shells_matched_height,
    :shells_canal_length,
    :shells_helix_trimmed,
    :shells_label,
	:shells_edges,
	:shells_high_shine,

    :faceplate_colors,
    :faceplate_buffing_material,
    :faceplate_seam,
	:faceplate_orientation,
	:faceplate_lanyard_loop,
	:faceplate_knob_buttons,

    :battery_door_closes,
    :battery_door_correct,
    :battery_door_opens_forward,

    :ports_cleaned,
    :ports_mic_flush,
    :ports_glued_correctly,

    :sound_chip_programmed,
    :sound_battery_signal,
    :sound_programs,
    :sound_volume_control,
    :sound_mic_signal,
    :sound_balanced_volume,

:notes, :shipping_cable, :shipping_tools, :shipping_card, :shipping_case, :shipping_additional, :shipping_by, now(), :pass_or_fail) RETURNING id",

array(':customer_name'=>$qc_form['customer_name'], ':order_id'=>$qc_form['order_id'],':monitor_id'=>$qc_form['monitor_id'], ':build_type_id'=>$qc_form['build_type_id'], 

':shells_hp_material'=>$qc_form['shells_hp_material'],
    ':shells_defects'=>$qc_form['shells_defects'],
    ':shells_colors'=>$qc_form['shells_colors'],
    ':shells_matched_height'=>$qc_form['shells_matched_height'],
    ':shells_canal_length'=>$qc_form['shells_canal_length'],
    ':shells_helix_trimmed'=>$qc_form['shells_helix_trimmed'],
    ':shells_label'=>$qc_form['shells_label'],
	':shells_edges'=>$qc_form['shells_edges'],
	':shells_high_shine'=>$qc_form['shells_high_shine'],

    ':faceplate_colors'=>$qc_form['faceplate_colors'],
    ':faceplate_buffing_material'=>$qc_form['faceplate_buffing_material'],
    ':faceplate_seam'=>$qc_form['faceplate_seam'],
	':faceplate_orientation'=>$qc_form['faceplate_orientation'],
	':faceplate_lanyard_loop'=>$qc_form['faceplate_lanyard_loop'],
	':faceplate_knob_buttons'=>$qc_form['faceplate_knob_buttons'],

    ':battery_door_closes'=>$qc_form['battery_door_closes'],
    ':battery_door_correct'=>$qc_form['battery_door_correct'],
    ':battery_door_opens_forward'=>$qc_form['battery_door_opens_forward'],

    ':ports_cleaned'=>$qc_form['ports_cleaned'],
    ':ports_mic_flush'=>$qc_form['ports_mic_flush'],
    ':ports_glued_correctly'=>$qc_form['ports_glued_correctly'],

    ':sound_chip_programmed'=>$qc_form['sound_chip_programmed'],
    ':sound_battery_signal'=>$qc_form['sound_battery_signal'],
    ':sound_programs'=>$qc_form['sound_programs'],
    ':sound_volume_control'=>$qc_form['sound_volume_control'],
    ':sound_mic_signal'=>$qc_form['sound_mic_signal'],
    ':sound_balanced_volume'=>$qc_form['sound_balanced_volume'],

':notes'=>$qc_form['notes'], ":shipping_cable"=>$qc_form['shipping_cable'], ":shipping_tools"=>$qc_form['shipping_tools'], ":shipping_card"=>$qc_form['shipping_card'], ":shipping_case"=>$qc_form['shipping_case'], ":shipping_additional"=>$qc_form['shipping_additional'], ":shipping_by"=>$_SESSION['UserId'], ":pass_or_fail"=>$qc_form['pass_or_fail'])
);			

	/*$response['message'] = "Not greater than 0";
	echo json_encode($response);
	exit;*/
}		 					 

//if($start_cart["barcode"][0] == 'R') {
if( $qc_form['build_type_id'] == 2 || $qc_form['build_type_id'] == 4) {
	////////////////////////////////////////////  MOVES THE REPAIR TO DONE ////////////////////////////////////////////
	$repair_id_is = substr($qc_form["order_id"], 1, strlen($qc_form["order_id"]) ); // REMOVES THE "S" FROM THE ID WHEN A REPAIR
	$query = pdo_query($pdo, "SELECT * FROM repair_status_table WHERE status_of_repair= 'Done'", null);
	$result = pdo_fetch_array($query);
	$status_id = $result["order_in_repair"];

	$query = pdo_query($pdo, "SELECT * FROM qc_form_active_hp WHERE id = :id", array(":id"=>$qc_form["id"]));
	$result = pdo_fetch_array($query);
	
	$response["test"] = "it is " . $result["id_of_repair"] . " and it is " . $result[0]["id_of_repair"];
	//echo json_encode($response);
	//exit;

	if($result["id_of_repair"] === null || $result["id_of_repair"] < 1) { // SOME LINES FROM qc_form WON'T HAVE AN id_of_order BECAUSE THE ORIGINAL PROGRAMMING 	DIDN'T INCLUDE THAT COLUMN
		$stmt = pdo_query($pdo, "SELECT * FROM repair_form_active_hp WHERE customer_name = :customer_name AND rma_number = :repair_id", 			array(":customer_name"=>$qc_form['customer_name'], ":repair_id"=>$repair_id_is));
		$result2 = pdo_fetch_all($stmt);
		
		$stuff = $result[0]["id_of_repair"];
		if(count($result2) > 1) {
			// CAN'T UPDATE THE QC FORM BECAUSE MORE THAN 1 QC FORM WITH THAT ORDER #
			$response['code'] = "success";
			$response["update"] = "Halt"; //
			$response["return_message"] = "Could not determine the correct Repair ID.  The repair's state was not updated.";
			echo json_encode($response);
			exit;
		} 
		else {
			//$result = pdo_fetch_all( $stmt );
			$response['ID_is'] = $result2[0]["id"];
			$response['code'] = 'success';
			$response["test"] =  $result2[0]["id"];
			//echo json_encode($response);
			//exit;
		}
	} else {
		$stuff = "second";
		$response['ID_is'] = $result["id_of_repair"];
		$response['code'] = 'success';
	}
	$response["test"] = "ID IS " . $response['ID_is'] . " and " . $status_id;
		//echo json_encode($response);
		//exit;
	
		$stmt = pdo_query( $pdo, "INSERT INTO repair_status_log_active_hp (date, repair_form_id, repair_status_id, notes,  user_id) VALUES (now(), :repair_form_id, :repair_status_id, :notes, :user_id) RETURNING id", array(':repair_form_id'=>$response['ID_is'], ':repair_status_id'=>$status_id, ':notes'=>"Moved to done by shipping", ':user_id'=>$_SESSION['UserId']));					 					 
	 
		$stmt = pdo_query( $pdo, 'UPDATE repair_form_active_hp SET repair_status_id = :repair_status_id WHERE id = :id', array("id"=>$response['ID_is'], "repair_status_id"=>$status_id));
	
	
} else {
////////////////////////////////////////////  MOVES THE EARPHONE TO DONE ////////////////////////////////////////////
$order_id_is = $qc_form["order_id"];		
$query = pdo_query($pdo, "SELECT * FROM order_status_table WHERE status_of_order = 'Done'", null);
$result = pdo_fetch_array($query);
$status_id = $result["order_in_manufacturing"];

$query = pdo_query($pdo, "SELECT * FROM qc_form_active_hp WHERE id = :id", array(":id"=>$qc_form["id"]));

$query = pdo_query($pdo, 
"SELECT t1.*, t2.email, t2.estimated_ship_date FROM qc_form_active_hp AS t1 LEFT JOIN import_orders AS t2 ON t1.id_of_order = t2.id WHERE t1.id = :id", 
array(":id"=>$qc_form["id"]));
$result = pdo_fetch_all($query);
$response['email'] = $result[0]['email'];
$response['estimated_ship_date'] = $result[0]['estimated_ship_date'];
//$response['code'] = "success";
//$response["test"] = "TEST ";
//echo json_encode($response);
//exit;

if($result[0]["id_of_order"] === null || $result[0]["id_of_order"] < 1) { // SOME LINES FROM qc_form WON'T HAVE AN id_of_order BECAUSE THE ORIGINAL PROGRAMMING DIDN'T INCLUDE THAT COLUMN
	$stmt = pdo_query($pdo, "SELECT * FROM import_orders WHERE designed_for = :customer_name AND order_id = :order_id AND active = TRUE", array(":customer_name"=>$qc_form['customer_name'], ":order_id"=>$order_id_is));
	$result2 = pdo_fetch_all($stmt);

		if(count($result2) > 1) {
			// CAN'T UPDATE THE QC FORM BECAUSE MORE THAN 1 QC FORM WITH THAT ORDER #
			$response['code'] = "success";
			$response["update"] = $result2;//count($result2);//"Could not determine the correct Order ID.  The order's manufacturing state was not updated.";
			echo json_encode($response);
			exit;
		} 
		else {
			//$result = pdo_fetch_all( $stmt );
			$response['ID_is'] = $result2[0]["id"];
			$response['code'] = 'success';
		}
} else {
		$response['ID_is'] = $result[0]["id_of_order"];
		$response['code'] = 'success';
}


	// ORDER STATUS LOG
	// IMPORT ORDERS			
	$stmt = pdo_query( $pdo, "INSERT INTO order_status_log (date, import_orders_id, order_status_id, notes,  user_id) VALUES (now(), :import_orders_id, :status_id, :notes, :user_id) RETURNING id", array(':import_orders_id'=>$response['ID_is'], ':status_id'=>$status_id, ':notes'=>"Moved to done by shipping", ':user_id'=>$_SESSION['UserId']));					 					 
	 
	/*$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 ) {
		$response['message'] = pdo_errors();
		$response["testing8"] = "8888888";
		echo json_encode($response);
		exit;
	}*/
		$stmt = pdo_query( $pdo, 'UPDATE import_orders SET order_status_id = :order_status_id WHERE id = :id', array("id"=>$response['ID_is'], "order_status_id"=>$status_id));
	 
	/*$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}*/
}	// CLOSE ELSE STATEMENT

    //$result = pdo_fetch_array($stmt);
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
