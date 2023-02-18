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
$response['move_on'] = "";


try
{

	$qc_form = array();
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
	
	if(empty($qc_form['customer_name']) == true)
	{
		$response['message'] = 'Please enter a customer name.';
		$response["move_on"] = 'Break';
		echo json_encode($response);
		exit;
	}
	if(empty($qc_form['order_id']) == true)
	{
		$response['message'] = 'Please enter the order id #.';
		//$response["Break"] = 'Break';
		echo json_encode($response);
		exit;
	}
	if($qc_form['monitor_id']  < 1)
	{
		$response['message'] = 'Please select a monitor.';
		$response["move_on"] = 'Break';
		echo json_encode($response);
		exit;
	}
	if($qc_form['build_type_id']  < 1)
	{
		$response['message'] = 'Please select a build type.';
		//$response["Break"] = 'Break';
		echo json_encode($response);
		exit;
	}
	
	if (
$qc_form['shells_hp_material'] & $qc_form['shells_defects'] & $qc_form['shells_colors'] & $qc_form['shells_matched_height']  & 	$qc_form['shells_canal_length'] &	$qc_form['shells_helix_trimmed']  & $qc_form['shells_label'] & $qc_form['shells_edges']  & $qc_form['shells_high_shine'] &	
$qc_form['faceplate_colors'] & $qc_form['faceplate_buffing_material'] &	$qc_form['faceplate_seam'] &	$qc_form['faceplate_orientation'] & 	$qc_form['faceplate_lanyard_loop'] & 	$qc_form['faceplate_knob_buttons'] &		
$qc_form['battery_door_closes'] &	$qc_form['battery_door_correct'] & 	$qc_form['battery_door_opens_forward'] &	
$qc_form['ports_cleaned'] &	$qc_form['ports_mic_flush'] &	$qc_form['ports_glued_correctly'] &	
$qc_form['sound_chip_programmed'] & 	$qc_form['sound_battery_signal'] & $qc_form['sound_programs'] &	$qc_form['sound_volume_control'] &	$qc_form['sound_mic_signal'] &	$qc_form['sound_balanced_volume'] ) 
	{ 
		$qc_form['pass_or_fail'] = 'READY TO SHIP'; 
	} 
	else 
	{ 
		$qc_form['pass_or_fail'] = 'FAIL';
	}
	
	if( strcmp($qc_form,  'FAIL') ) {
		$qc_form['initial_pass_or_fail'] = 'PASS';
	} else {
		$qc_form['initial_pass_or_fail'] = 'FAIL';
	}
		
		
$stmt = pdo_query( $pdo, 
					   "INSERT INTO qc_form_active_hp (
					   customer_name, order_id, monitor_id, build_type_id, 

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

  notes, qc_pass_by, qc_date, active, pass_or_fail, initial_pass_or_fail)
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

	:notes, :qc_pass_by, now(), :active, :pass_or_fail, :initial_pass_or_fail) RETURNING id",
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

':notes'=>$qc_form['notes'], ":qc_pass_by"=>$_SESSION['UserId'], ":active"=>TRUE, ":pass_or_fail"=>$qc_form['pass_or_fail'], ":initial_pass_or_fail"=>$qc_form['initial_pass_or_fail'])
);					 					 
					 
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response["testing8"] = "8888888";
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