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
	$repair_form['id'] = $_POST['id'];
	
	$repair_form['repaired_shell_right'] = $_POST['repaired_shell_right'];
	$repair_form['new_shells_right'] = $_POST['new_shells_right'];
	$repair_form['repaired_faceplate_right'] = $_POST['repaired_faceplate_right'];
	$repair_form['new_faceplate_right'] = $_POST['new_faceplate_right'];

	$repair_form['replaced_faceplate_right'] = $_POST['replaced_faceplate_right'];
	$repair_form['replaced_tubes_right'] = $_POST['replaced_tubes_right'];
	$repair_form['replaced_mic_right'] = $_POST['replaced_mic_right'];
	$repair_form['moved_mic_right'] = $_POST['moved_mic_right'];

	$repair_form['repaired_jacks_right'] = $_POST['repaired_jacks_right'];
	$repair_form['cleaned_right'] = $_POST['cleaned_right'];
	$repair_form['touched_up_wires_chip_right'] = $_POST['touched_up_wires_chip_right'];
	$repair_form['replaced_chip_right'] = $_POST['replaced_chip_right'];

	$repair_form['replaced_driver_right'] = $_POST['replaced_driver_right'];
	$repair_form['adjusted_fit_right'] = $_POST['adjusted_fit_right'];
	
	$repair_form['repaired_shell_left'] = $_POST['repaired_shell_left'];
	$repair_form['new_shells_left'] = $_POST['new_shells_left'];
	$repair_form['repaired_faceplate_left'] = $_POST['repaired_faceplate_left'];
	$repair_form['new_faceplate_left'] = $_POST['new_faceplate_left'];

	$repair_form['replaced_faceplate_left'] = $_POST['replaced_faceplate_left'];
	$repair_form['replaced_tubes_left'] = $_POST['replaced_tubes_left'];
	$repair_form['replaced_mic_left'] = $_POST['replaced_mic_left'];
	$repair_form['moved_mic_left'] = $_POST['moved_mic_left'];

	$repair_form['repaired_jacks_left'] = $_POST['repaired_jacks_left'];
	$repair_form['cleaned_left'] = $_POST['cleaned_left'];
	$repair_form['touched_up_wires_chip_left'] = $_POST['touched_up_wires_chip_left'];
	$repair_form['replaced_chip_left'] = $_POST['replaced_chip_left'];

	$repair_form['replaced_driver_left'] = $_POST['replaced_driver_left'];
	$repair_form['adjusted_fit_left'] = $_POST['adjusted_fit_left'];
		
	$repair_form['notes'] = $_POST['notes'];
	$repair_form['rma_performed_date'] = date("m-d-Y");

	$stmt = pdo_query( $pdo, 
					   'UPDATE repair_form_active_hp SET 
					   
repaired_shell_right = :repaired_shell_right,
new_shells_right	= :new_shells_right,
repaired_faceplate_right = :repaired_faceplate_right,
new_faceplate_right = :new_faceplate_right,
replaced_faceplate_right = :replaced_faceplate_right,
replaced_tubes_right = :replaced_tubes_right,
replaced_mic_right = :replaced_mic_right,
moved_mic_right = :moved_mic_right,
repaired_jacks_right = :repaired_jacks_right,
cleaned_right = :cleaned_right,
touched_up_wires_chip_right = :touched_up_wires_chip_right,
replaced_chip_right	 = :replaced_chip_right,
replaced_driver_right = :replaced_driver_right,
adjusted_fit_right = :adjusted_fit_right,
repaired_shell_left = :repaired_shell_left,
new_shells_left	 = :new_shells_left,
repaired_faceplate_left = :repaired_faceplate_left,
new_faceplate_left = :new_faceplate_left,
replaced_faceplate_left = :replaced_faceplate_left,
replaced_tubes_left = :replaced_tubes_left,
replaced_mic_left	 = :replaced_mic_left,
moved_mic_left = :moved_mic_left,
repaired_jacks_left = :repaired_jacks_left,
cleaned_left = :cleaned_left,
touched_up_wires_chip_left = :touched_up_wires_chip_left,
replaced_chip_left = :replaced_chip_left,
replaced_driver_left = :replaced_driver_left,
adjusted_fit_left = :adjusted_fit_left,
notes = :notes
					   
WHERE id = :id', array("id"=>$repair_form["id"], 
					   
"repaired_shell_right"=>$repair_form['repaired_shell_right'],
"new_shells_right"=>$repair_form['new_shells_right'],
"repaired_faceplate_right"=>$repair_form['repaired_faceplate_right'],
"new_faceplate_right"=>$repair_form['new_faceplate_right'],
"replaced_faceplate_right"=>$repair_form['replaced_faceplate_right'],
"replaced_tubes_right"=>$repair_form['replaced_tubes_right'],
"replaced_mic_right"=>$repair_form['replaced_mic_right'],
"moved_mic_right"=>$repair_form['moved_mic_right'],
"repaired_jacks_right"=>$repair_form['repaired_jacks_right'],
"cleaned_right"=>$repair_form['cleaned_right'],
"touched_up_wires_chip_right"=>$repair_form['touched_up_wires_chip_right'],
"replaced_chip_right"=>$repair_form['replaced_chip_right'],
"replaced_driver_right"=>$repair_form['replaced_driver_right'],
"adjusted_fit_right"=>$repair_form['adjusted_fit_right'],
"repaired_shell_left"=>$repair_form['repaired_shell_left'],
"new_shells_left"=>$repair_form['new_shells_left'],
"repaired_faceplate_left"=>$repair_form['repaired_faceplate_left'],
"new_faceplate_left"=>$repair_form['new_faceplate_left'],
"replaced_faceplate_left"=>$repair_form['replaced_faceplate_left'],
"replaced_tubes_left"=>$repair_form['replaced_tubes_left'],
"replaced_mic_left"=>$repair_form['replaced_mic_left'],
"moved_mic_left"=>$repair_form['moved_mic_left'],
"repaired_jacks_left"=>$repair_form['repaired_jacks_left'],
"cleaned_left"=>$repair_form['cleaned_left'],
"touched_up_wires_chip_left"=>$repair_form['touched_up_wires_chip_left'],
"replaced_chip_left"=>$repair_form['replaced_chip_left'],
"replaced_driver_left"=>$repair_form['replaced_driver_left'],
"adjusted_fit_left"=>$repair_form['adjusted_fit_left'],
"notes"=>$repair_form['notes']));

	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response['code'] = 'NOPE';
		echo json_encode($response);
		exit;
	}
	
	$response['code'] = 'success';
	$response['data'] = $rowcount;
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>