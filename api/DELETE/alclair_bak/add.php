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
	$qc_form['customer_name'] = $_POST['customer_name'];
	$qc_form['order_id'] = $_POST['order_id'];
	$qc_form['monitor_id'] = $_POST['monitor_id'];
	$qc_form['build_type_id'] = $_POST['build_type_id'];
	
	$qc_form['shells_defects'] = $_POST['shells_defects'];
	$qc_form['shells_colors'] = $_POST['shells_colors'];
	$qc_form['shells_faced_down'] = $_POST['shells_faced_down'];
	$qc_form['shells_label'] = $_POST['shells_label'];
	$qc_form['shells_edges'] = $_POST['shells_edges'];
	$qc_form['shells_shine'] = $_POST['shells_shine'];
	$qc_form['shells_canal'] = $_POST['shells_canal'];
	
	$qc_form['faceplate_seams'] = $_POST['faceplate_seams'];
	$qc_form['faceplate_shine'] = $_POST['faceplate_shine'];
	$qc_form['faceplate_colors'] = $_POST['faceplate_colors'];
	$qc_form['faceplate_rounded'] = $_POST['faceplate_rounded'];
	
	$qc_form['jacks_location'] = $_POST['jacks_location'];
	$qc_form['jacks_debris'] = $_POST['jacks_debris'];
	$qc_form['jacks_cable'] = $_POST['jacks_cable'];
	
	$qc_form['ports_cleaned'] = $_POST['ports_cleaned'];
	$qc_form['ports_smooth'] = $_POST['ports_smooth'];
	
	$qc_form['sound_signature'] = $_POST['sound_signature'];
	$qc_form['sound_balanced'] = $_POST['sound_balanced'];
	
	$qc_form['artwork_added'] = $_POST['artwork_added'];
	$qc_form['artwork_placement'] = $_POST['artwork_placement'];
	$qc_form['artwork_hq'] = $_POST['artwork_hq'];
	$qc_form['artwork_none'] = $_POST['artwork_none'];
	$qc_form['artwork_required'] = $_POST['artwork_required'];
	
	$qc_form['notes'] = $_POST['notes'];
	
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
	if($qc_form['artwork_none']  != 1 &&  $qc_form['artwork_required'] != 1)
	{
		$response['message'] = 'Please select whether there will be artwork.';
		echo json_encode($response);
		exit;
	}
	if($qc_form['artwork_none']  == 1 &&  $qc_form['artwork_required'] == 1)
	{
		$response['message'] = 'Both checkboxes under artwork cannot be checked.';
		echo json_encode($response);
		exit;
	}
	if($qc_form['artwork_placement']  == 1 &&  $qc_form['artwork_hq'] == 1 && $qc_form['artwork_added'] == 0 )
	{
		$response['message'] = 'Should "ARTWORK ADDED" be checked?';
		echo json_encode($response);
		exit;
	}
	
	if($qc_form['artwork_none'] == 1) {
		$qc_form['artwork_added'] = 0;
		$qc_form['artwork_placement'] = 0;
		$qc_form['artwork_hq'] = 0;
	}
	
	if($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1	 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && 
	$qc_form['artwork_added'] == 1 && $qc_form['artwork_placement'] == 1 && $qc_form['artwork_hq'] == 1) {
		$qc_form['pass_or_fail'] = 'READY TO SHIP'; 
	} else if($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1	 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && 
	$qc_form['artwork_none'] == 1) {
		$qc_form['pass_or_fail'] = 'READY TO SHIP';
	} else if ($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1	 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && 
	$qc_form['artwork_required'] == 1 && $qc_form['artwork_added'] == 0 && $qc_form['artwork_placement'] == 0 && $qc_form['artwork_hq'] == 0) {
		$qc_form['pass_or_fail'] = 'WAITING FOR ARTWORK';		
	} else if ($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1	 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && 
	$qc_form['artwork_added'] == 1 && ($qc_form['artwork_placement'] == 0 || $qc_form['artwork_hq'] == 0)) {
		$qc_form['pass_or_fail'] = 'FAIL';
	}
	else {
		$qc_form['pass_or_fail'] = 'FAIL';
	}
	
	if($qc_form != 'FAIL') {
		$qc_form['initial_pass_or_fail'] = 'PASS';
	} else {
		$qc_form['initial_pass_or_fail'] = 'FAIL';
	}
		
		
$stmt = pdo_query( $pdo, 
					   "INSERT INTO qc_form (
customer_name, order_id, monitor_id, build_type_id, shells_defects, shells_colors, shells_faced_down, shells_label, shells_edges, shells_shine, shells_canal, faceplate_seams, faceplate_shine, faceplate_colors, faceplate_rounded, jacks_location, jacks_debris, jacks_cable, ports_cleaned, ports_smooth, sound_signature, sound_balanced, 
artwork_placement, 
artwork_hq,
 artwork_none,
  artwork_required, artwork_added, 
  notes, qc_pass_by, qc_date, active, pass_or_fail, initial_pass_or_fail)
VALUES (
:customer_name, :order_id, :monitor_id, :build_type_id, :shells_defects, :shells_colors, :shells_faced_down, :shells_label, :shells_edges, :shells_shine, :shells_canal,
:faceplate_seams, :faceplate_shine, :faceplate_colors, :faceplate_rounded, :jacks_location, :jacks_debris, :jacks_cable, :ports_cleaned, :ports_smooth, :sound_signature, :sound_balanced, 
:artwork_placement, 
:artwork_hq, 
:artwork_none, 
:artwork_required, 
:artwork_added,
 :notes, :qc_pass_by, now(), :active, :pass_or_fail, :initial_pass_or_fail) RETURNING id",
array(':customer_name'=>$qc_form['customer_name'], ':order_id'=>$qc_form['order_id'],':monitor_id'=>$qc_form['monitor_id'], ':build_type_id'=>$qc_form['build_type_id'], ':shells_defects'=>$qc_form['shells_defects'], ':shells_colors'=>$qc_form['shells_colors'], ':shells_faced_down'=>$qc_form['shells_faced_down'], ':shells_label'=>$qc_form['shells_label'], ':shells_edges'=>$qc_form['shells_edges'], ':shells_shine'=>$qc_form['shells_shine'], ':shells_canal'=>$qc_form['shells_canal'], ':faceplate_seams'=>$qc_form['faceplate_seams'], ':faceplate_shine'=>$qc_form['faceplate_shine'], ':faceplate_colors'=>$qc_form['faceplate_colors'], ':faceplate_rounded'=>$qc_form['faceplate_rounded'], ':jacks_location'=>$qc_form['jacks_location'], ':jacks_debris'=>$qc_form['jacks_debris'], ':jacks_cable'=>$qc_form['jacks_cable'], ':ports_cleaned'=>$qc_form['ports_cleaned'], ':ports_smooth'=>$qc_form['ports_smooth'], ':sound_signature'=>$qc_form['sound_signature'], ':sound_balanced' =>$qc_form['sound_balanced'], 
':artwork_placement'=>$qc_form['artwork_placement'], 
':artwork_hq'=>$qc_form['artwork_hq'], 
':artwork_none'=>$qc_form['artwork_none'], 
':artwork_required'=>$qc_form['artwork_required'], 
':artwork_added'=>$qc_form['artwork_added'], 
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