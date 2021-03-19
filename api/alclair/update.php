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
		
	$response['code'] = 'before everything';
	
    $qc_form = array();
	$qc_form['id'] = $_POST['id'];
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
	$qc_form['shells_density'] = $_POST['shells_density'];
	
	$qc_form['faceplate_seams'] = $_POST['faceplate_seams'];
	$qc_form['faceplate_shine'] = $_POST['faceplate_shine'];
	$qc_form['faceplate_colors'] = $_POST['faceplate_colors'];
	$qc_form['faceplate_rounded'] = $_POST['faceplate_rounded'];
	$qc_form['faceplate_foggy'] = $_POST['faceplate_foggy'];
	$qc_form['faceplate_residue'] = $_POST['faceplate_residue'];
	
	$qc_form['jacks_location'] = $_POST['jacks_location'];
	$qc_form['jacks_debris'] = $_POST['jacks_debris'];
	$qc_form['jacks_cable'] = $_POST['jacks_cable'];
	
	$qc_form['ports_cleaned'] = $_POST['ports_cleaned'];
	$qc_form['ports_smooth'] = $_POST['ports_smooth'];
	$qc_form['ports_glued_correctly'] = $_POST['ports_glued_correctly'];
	$qc_form['ports_kinked_tube'] = $_POST['ports_kinked_tube'];
	$qc_form['ports_crushed_damper'] = $_POST['ports_crushed_damper'];
	
	$qc_form['sound_signature'] = $_POST['sound_signature'];
	$qc_form['sound_balanced'] = $_POST['sound_balanced'];
	$qc_form['sound_correct_model'] = $_POST['sound_correct_model'];
	
	$qc_form['artwork_none'] = $_POST['artwork_none'];
	$qc_form['artwork_required'] = $_POST['artwork_required'];
	$qc_form['artwork_added'] = $_POST['artwork_added'];
	$qc_form['artwork_placement'] = $_POST['artwork_placement'];
	$qc_form['artwork_hq'] = $_POST['artwork_hq'];
	
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
	//if($qc_form['artwork_none']  != 1 &&  $qc_form['artwork_required'] != 1)
	if($qc_form['artwork_none']  != 1 &&  $qc_form['artwork_added'] != 1)
	{
		$response['message'] = 'Please select whether there will be artwork on the earphone.';
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
					 
	$qc_form['shipping_cable'] = $_POST['shipping_cable'];
	$qc_form['shipping_tools'] = $_POST['shipping_tools'];
	$qc_form['shipping_card'] = $_POST['shipping_card'];
	$qc_form['shipping_case'] = $_POST['shipping_case'];
	$qc_form['shipping_additional'] = $_POST['shipping_additional'];		
	
	if($qc_form['artwork_none'] == 1) {
		$qc_form['artwork_added'] = 0;
		$qc_form['artwork_placement'] = 0;
		$qc_form['artwork_hq'] = 0;
	}
	
if($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1	 && $qc_form['shells_density'] == 1 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['faceplate_foggy'] == 1 && $qc_form['faceplate_residue'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && $qc_form['ports_glued_correctly'] == 1 && $qc_form['ports_kinked_tube'] == 1 && $qc_form['ports_crushed_damper'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && $qc_form['sound_correct_model'] == 1 &&
	$qc_form['artwork_added'] == 1 && $qc_form['artwork_placement'] == 1 && $qc_form['artwork_hq'] == 1) {
		$qc_form['pass_or_fail'] = 'READY TO SHIP'; 
} elseif($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1 && $qc_form['shells_density'] == 1 &&
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['faceplate_foggy'] == 1 && $qc_form['faceplate_residue'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && $qc_form['ports_glued_correctly'] == 1 && $qc_form['ports_kinked_tube'] == 1 && $qc_form['ports_crushed_damper'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && $qc_form['sound_correct_model'] == 1 &&
	$qc_form['artwork_none'] == 1) {
		$qc_form['pass_or_fail'] = 'READY TO SHIP';
} elseif ($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1 && $qc_form['shells_density'] == 1 &&
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['faceplate_foggy'] == 1 && $qc_form['faceplate_residue'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && $qc_form['ports_glued_correctly'] == 1 && $qc_form['ports_kinked_tube'] == 1 && $qc_form['ports_crushed_damper'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && $qc_form['sound_correct_model'] == 1 &&
	$qc_form['artwork_required'] == 1 && $qc_form['artwork_added'] == 0 && $qc_form['artwork_placement'] == 0 && $qc_form['artwork_hq'] == 0) {
		$qc_form['pass_or_fail'] = 'WAITING FOR ARTWORK';		
} elseif ($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1 && $qc_form['shells_density'] == 1 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['faceplate_foggy'] == 1 && $qc_form['faceplate_residue'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && $qc_form['ports_glued_correctly'] == 1 && $qc_form['ports_kinked_tube'] == 1 && $qc_form['ports_crushed_damper'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && $qc_form['sound_correct_model'] == 1 &&
	$qc_form['artwork_added'] == 1 && ($qc_form['artwork_placement'] == 0 || $qc_form['artwork_hq'] == 0)) {
		$qc_form['pass_or_fail'] = 'FAIL';
} else {
		$qc_form['pass_or_fail'] = 'FAIL';
} 


	if( strcmp($qc_form['pass_or_fail'],  'FAIL') ) {
		$qc_form['initial_pass_or_fail'] = 'PASS';
	} else {
		$qc_form['initial_pass_or_fail'] = 'FAIL';
	}
	
	//if($_SESSION['IsAdmin'] == 0) {
	$stmt = pdo_query($pdo, "SELECT * FROM qc_form WHERE id = :id", array(":id"=>$_REQUEST["id"]));
	$get_stmt = pdo_fetch_all( $stmt );

	//if($get_stmt[0]["pass_or_fail"] == 'IMPORTED') {
	$now = date("Y-m-d H:i:s");
	if ( !strcmp( $get_stmt[0]["pass_or_fail"], 'IMPORTED' ) ) {
		
		$stmt = pdo_query( $pdo, 
					  'UPDATE qc_form SET customer_name = :customer_name, order_id = :order_id, monitor_id = :monitor_id, build_type_id = :build_type_id,
                       shells_defects = :shells_defects, shells_colors = :shells_colors, shells_faced_down = :shells_faced_down, shells_label = :shells_label, shells_edges = :shells_edges, shells_shine = :shells_shine, shells_canal = :shells_canal, shells_density = :shells_density,
                       faceplate_seams = :faceplate_seams, faceplate_shine = :faceplate_shine, faceplate_colors = :faceplate_colors, faceplate_rounded = :faceplate_rounded,
                       faceplate_foggy = :faceplate_foggy, faceplate_residue = :faceplate_residue,
                       jacks_location = :jacks_location, jacks_debris = :jacks_debris, jacks_cable = :jacks_cable, 
                       ports_cleaned = :ports_cleaned, ports_smooth = :ports_smooth, ports_glued_correctly = :ports_glued_correctly, ports_kinked_tube = :ports_kinked_tube, ports_crushed_damper = :ports_crushed_damper,
                       sound_signature = :sound_signature, sound_balanced = :sound_balanced, sound_correct_model = :sound_correct_model,
                       artwork_placement = :artwork_placement, artwork_hq = :artwork_hq, artwork_none = :artwork_none, artwork_required = :artwork_required, artwork_added = :artwork_added,
                       notes = :notes,
                       shipping_cable = :shipping_cable, shipping_tools = :shipping_tools, shipping_card = :shipping_card, shipping_case = :shipping_case, shipping_additional = :shipping_additional,
                       pass_or_fail = :pass_or_fail, qc_date = now(), initial_pass_or_fail = :initial_pass_or_fail
                       WHERE id = :id',
					   array("id"=>$qc_form["id"], "customer_name"=>$qc_form['customer_name'], "order_id"=>$qc_form['order_id'], "monitor_id"=>$qc_form['monitor_id'], "build_type_id"=>$qc_form['build_type_id'], 
					   "shells_defects"=>$qc_form['shells_defects'], "shells_colors"=>$qc_form['shells_colors'], "shells_faced_down"=>$qc_form['shells_faced_down'], "shells_label"=>$qc_form['shells_label'], "shells_edges"=>$qc_form['shells_edges'], "shells_shine"=>$qc_form['shells_shine'], "shells_canal"=>$qc_form['shells_canal'], "shells_density"=>$qc_form['shells_density'], 
					   "faceplate_seams"=>$qc_form['faceplate_seams'], "faceplate_shine"=>$qc_form['faceplate_shine'], "faceplate_colors"=>$qc_form['faceplate_colors'], "faceplate_rounded"=>$qc_form['faceplate_rounded'], "faceplate_foggy"=>$qc_form['faceplate_foggy'], "faceplate_residue"=>$qc_form['faceplate_residue'], 
					   "jacks_location"=>$qc_form['jacks_location'], "jacks_debris"=>$qc_form['jacks_debris'], "jacks_cable"=>$qc_form['jacks_cable'],
					   "ports_cleaned"=>$qc_form['ports_cleaned'], "ports_smooth"=>$qc_form['ports_smooth'], 
					   "ports_glued_correctly"=>$qc_form['ports_glued_correctly'], "ports_kinked_tube"=>$qc_form['ports_kinked_tube'], "ports_crushed_damper"=>$qc_form['ports_crushed_damper'],
					   "sound_signature"=>$qc_form['sound_signature'], "sound_balanced"=>$qc_form['sound_balanced'], "sound_correct_model"=>$qc_form['sound_correct_model'],
					   "artwork_placement"=>$qc_form['artwork_placement'], "artwork_hq"=>$qc_form['artwork_hq'], "artwork_none"=>$qc_form['artwork_none'], "artwork_required"=>$qc_form['artwork_required'], "artwork_added"=>$qc_form['artwork_added'], 
					   "notes"=>$qc_form['notes'],
					   "shipping_cable"=>$qc_form['shipping_cable'], "shipping_tools"=>$qc_form['shipping_tools'], "shipping_card"=>$qc_form['shipping_card'], 						"shipping_case"=>$qc_form['shipping_case'], "shipping_additional"=>$qc_form['shipping_additional'],
					   "pass_or_fail"=>$qc_form['pass_or_fail'], "initial_pass_or_fail"=>$qc_form['initial_pass_or_fail']));
					 $response['message'] = '1st part';
					 $response['message'] =  $qc_form['artwork_none'];
	} else {
		
		$stmt = pdo_query( $pdo, 
					   'UPDATE qc_form SET customer_name = :customer_name, order_id = :order_id, monitor_id = :monitor_id, build_type_id = :build_type_id,
                       shells_defects = :shells_defects, shells_colors = :shells_colors, shells_faced_down = :shells_faced_down, shells_label = :shells_label, shells_edges = :shells_edges, shells_shine = :shells_shine, shells_canal = :shells_canal, shells_density = :shells_density,
                       faceplate_seams = :faceplate_seams, faceplate_shine = :faceplate_shine, faceplate_colors = :faceplate_colors, faceplate_rounded = :faceplate_rounded,
                       faceplate_foggy = :faceplate_foggy, faceplate_residue = :faceplate_residue,
                       jacks_location = :jacks_location, jacks_debris = :jacks_debris, jacks_cable = :jacks_cable, 
                       ports_cleaned = :ports_cleaned, ports_smooth = :ports_smooth, ports_glued_correctly = :ports_glued_correctly, ports_kinked_tube = :ports_kinked_tube, ports_crushed_damper = :ports_crushed_damper,
                       sound_signature = :sound_signature, sound_balanced = :sound_balanced, sound_correct_model = :sound_correct_model,
                       artwork_placement = :artwork_placement, artwork_hq = :artwork_hq, artwork_none = :artwork_none, artwork_required = :artwork_required, artwork_added = :artwork_added,
                       notes = :notes,
                       shipping_cable = :shipping_cable, shipping_tools = :shipping_tools, shipping_card = :shipping_card, shipping_case = :shipping_case, shipping_additional = :shipping_additional,
                       pass_or_fail = :pass_or_fail, initial_pass_or_fail = :initial_pass_or_fail
                       WHERE id = :id',
					   array("id"=>$qc_form["id"], "customer_name"=>$qc_form['customer_name'], "order_id"=>$qc_form['order_id'], "monitor_id"=>$qc_form['monitor_id'], 						"build_type_id"=>$qc_form['build_type_id'], 
					   "shells_defects"=>$qc_form['shells_defects'], "shells_colors"=>$qc_form['shells_colors'], "shells_faced_down"=>$qc_form['shells_faced_down'], 						"shells_label"=>$qc_form['shells_label'], "shells_edges"=>$qc_form['shells_edges'], "shells_shine"=>$qc_form['shells_shine'], "shells_canal"=>$qc_form['shells_canal'], "shells_density"=>$qc_form['shells_density'], 
					   "faceplate_seams"=>$qc_form['faceplate_seams'], "faceplate_shine"=>$qc_form['faceplate_shine'], "faceplate_colors"=>$qc_form['faceplate_colors'],  						"faceplate_rounded"=>$qc_form['faceplate_rounded'],  "faceplate_foggy"=>$qc_form['faceplate_foggy'], "faceplate_residue"=>$qc_form['faceplate_residue'], 
					   "jacks_location"=>$qc_form['jacks_location'], "jacks_debris"=>$qc_form['jacks_debris'], "jacks_cable"=>$qc_form['jacks_cable'],
					   "ports_cleaned"=>$qc_form['ports_cleaned'], "ports_smooth"=>$qc_form['ports_smooth'], "ports_glued_correctly"=>$qc_form['ports_glued_correctly'], "ports_kinked_tube"=>$qc_form['ports_kinked_tube'], "ports_crushed_damper"=>$qc_form['ports_crushed_damper'],
					   "sound_signature"=>$qc_form['sound_signature'], "sound_balanced"=>$qc_form['sound_balanced'], "sound_correct_model"=>$qc_form['sound_correct_model'],
					   "artwork_placement"=>$qc_form['artwork_placement'],	"artwork_hq"=>$qc_form['artwork_hq'], "artwork_none"=>$qc_form['artwork_none'], 						"artwork_required"=>$qc_form['artwork_required'], "artwork_added"=>$qc_form['artwork_added'], 
					   "notes"=>$qc_form['notes'],
					   "shipping_cable"=>$qc_form['shipping_cable'], "shipping_tools"=>$qc_form['shipping_tools'], "shipping_card"=>$qc_form['shipping_card'], 						"shipping_case"=>$qc_form['shipping_case'], "shipping_additional"=>$qc_form['shipping_additional'],
					   "pass_or_fail"=>$qc_form['pass_or_fail'], "initial_pass_or_fail"=>$qc_form['initial_pass_or_fail']));
					 $response['message'] = '2nd part';
	}
	
	/*
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response['code'] = 'NOPE';
		echo json_encode($response);
		exit;
	}*/
	
	$response["show_modal_window"] = $qc_form['pass_or_fail'];
	
	$response['code'] = 'success';
	$response['data'] = $rowcount;
	$response['testing1'] = $qc_form['ports_cleaned'] ;
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>