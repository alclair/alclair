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
	$repair_form['repaired_shell'] = $_POST['repaired_shell'];
	$repair_form['repaired_faceplate'] = $_POST['repaired_faceplate'];
	$repair_form['repaired_jacks'] = $_POST['repaired_jacks'];
	$repair_form['replaced_drivers'] = $_POST['replaced_drivers'];
	
	$repair_form['new_shells'] = $_POST['new_shells'];
	$repair_form['replaced_tubes'] = $_POST['replaced_tubes'];
	$repair_form['cleaned'] = $_POST['cleaned'];
	$repair_form['adjusted_fit'] = $_POST['adjusted_fit'];
		
	$repair_form['notes'] = $_POST['notes'];
	$repair_form['rma_performed_date'] = date("m-d-Y");

	$stmt = pdo_query( $pdo, 
					   'UPDATE repair_form SET repaired_shell = :repaired_shell, repaired_faceplate = :repaired_faceplate, repaired_jacks = :repaired_jacks, replaced_drivers = :replaced_drivers, new_shells = :new_shells, replaced_tubes = :replaced_tubes, cleaned = :cleaned, adjusted_fit = :adjusted_fit, notes = :notes, rma_performed_date = :rma_performed_date, 
					   
					   repaired_shell_left = :repaired_shell_left, repaired_faceplate_left = :repaired_faceplate_left, repaired_jacks_left = :repaired_jacks_left, replaced_drivers_left = :replaced_drivers_left, new_shells_left = :new_shells_left, replaced_tubes_left = :replaced_tubes_left, cleaned_left = :cleaned_left, adjusted_fit_left = :adjusted_fit_left 
                       WHERE id = :id',
					   array("id"=>$repair_form["id"], "repaired_shell"=>$repair_form['repaired_shell'], "repaired_faceplate"=>$repair_form['repaired_faceplate'], "repaired_jacks"=>$repair_form['repaired_jacks'], "replaced_drivers"=>$repair_form['replaced_drivers'], "new_shells"=>$repair_form['new_shells'], "replaced_tubes"=>$repair_form['replaced_tubes'], "cleaned"=>$repair_form['cleaned'], "adjusted_fit"=>$repair_form['adjusted_fit'], "notes"=>$repair_form['notes'], "rma_performed_date"=>$repair_form["rma_performed_date"],
					   
					   "repaired_shell_left"=>$repair_form['repaired_shell_left'], "repaired_faceplate_left"=>$repair_form['repaired_faceplate_left'], "repaired_jacks_left"=>$repair_form['repaired_jacks_left'], "replaced_drivers_left"=>$repair_form['replaced_drivers_left'], "new_shells_left"=>$repair_form['new_shells_left'], "replaced_tubes_left"=>$repair_form['replaced_tubes_left'], "cleaned_left"=>$repair_form['cleaned_left'], "adjusted_fit_left"=>$repair_form['adjusted_fit_left'])
						//,1
					 );

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