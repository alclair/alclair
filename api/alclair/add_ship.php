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
	if($qc_form['artwork_none']  != 1 &&  $qc_form['artwork_added'] != 1)
	{
		$response['message'] = 'Please select whether there will be artwork.';
		echo json_encode($response);
		exit;
	}
	if($qc_form['artwork_none']  == 1 &&  $qc_form['artwork_added'] == 1)
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
	
		if($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1 && $qc_form['shells_density'] == 1 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['faceplate_foggy'] == 1 && $qc_form['faceplate_residue'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && $qc_form['ports_glued_correctly'] == 1 && $qc_form['ports_kinked_tube'] == 1 && $qc_form['ports_crushed_damper'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && $qc_form['sound_correct_model'] == 1 &&
	$qc_form['artwork_added'] == 1 && $qc_form['artwork_placement'] == 1 && $qc_form['artwork_hq'] == 1 && 
	$qc_form['shipping_cable'] == 1 && $qc_form['shipping_tools'] == 1 && $qc_form['shipping_card'] == 1 && $qc_form['shipping_case'] == 1 && $qc_form['shipping_additional'] == 1) {
		$qc_form['pass_or_fail'] = 'PASS'; 
	} else if($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1 && $qc_form['shells_density'] == 1 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['faceplate_foggy'] == 1 && $qc_form['faceplate_residue'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && $qc_form['ports_glued_correctly'] == 1 && $qc_form['ports_kinked_tube'] == 1 && $qc_form['ports_crushed_damper'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && $qc_form['sound_correct_model'] == 1 &&
	$qc_form['artwork_none'] == 1 &&
	$qc_form['shipping_cable'] == 1 && $qc_form['shipping_tools'] == 1 && $qc_form['shipping_card'] == 1 && $qc_form['shipping_case'] == 1 && $qc_form['shipping_additional'] == 1) {
		$qc_form['pass_or_fail'] = 'PASS';
	} else if ($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1 && $qc_form['shells_density'] == 1 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['faceplate_foggy'] == 1 && $qc_form['faceplate_residue'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && $qc_form['ports_glued_correctly'] == 1 && $qc_form['ports_kinked_tube'] == 1 && $qc_form['ports_crushed_damper'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && $qc_form['sound_correct_model'] == 1 &&
	$qc_form['artwork_required'] == 1 && $qc_form['artwork_added'] == 0 && $qc_form['artwork_placement'] == 0 && $qc_form['artwork_hq'] == 0 && 
	$qc_form['shipping_cable'] == 1 && $qc_form['shipping_tools'] == 1 && $qc_form['shipping_card'] == 1 && $qc_form['shipping_case'] == 1 && $qc_form['shipping_additional'] == 1) {
		$qc_form['pass_or_fail'] = "PRODUCT CAN'T SHIP";		
	} else if ($qc_form['shells_defects'] == 1 && $qc_form['shells_colors'] == 1 && $qc_form['shells_faced_down'] == 1 && $qc_form['shells_label'] == 1 && $qc_form['shells_edges']  == 1 && $qc_form['shells_shine'] == 1	 && $qc_form['shells_canal'] == 1 && $qc_form['shells_density'] == 1 && 
	$qc_form['faceplate_seams'] == 1 && $qc_form['faceplate_shine'] == 1 && $qc_form['faceplate_colors'] == 1 && $qc_form['faceplate_rounded'] == 1 &&
	$qc_form['faceplate_foggy'] == 1 && $qc_form['faceplate_residue'] == 1 && 
	$qc_form['jacks_location'] == 1 && $qc_form['jacks_debris'] == 1 && $qc_form['jacks_cable'] == 1 && 
	$qc_form['ports_cleaned'] == 1 && $qc_form['ports_smooth'] == 1 && $qc_form['ports_glued_correctly'] == 1 && $qc_form['ports_kinked_tube'] == 1 && $qc_form['ports_crushed_damper'] == 1 && 
	$qc_form['sound_signature']  == 1 && $qc_form['sound_balanced'] == 1 && $qc_form['sound_correct_model'] == 1 &&
	$qc_form['artwork_added'] == 1 && ($qc_form['artwork_placement'] == 0 || $qc_form['artwork_hq'] == 0) &&
	$qc_form['shipping_cable'] == 1 && $qc_form['shipping_tools'] == 1 && $qc_form['shipping_card'] == 1 && $qc_form['shipping_case'] == 1 && $qc_form['shipping_additional'] == 1) {
		$qc_form['pass_or_fail'] = "PRODUCT CAN'T SHIP";
	}
	else {
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

$query = "SELECT * FROM qc_form WHERE customer_name = :customer_name AND order_id = :order_id AND build_type_id = :build_type_id AND monitor_id = :monitor_id"	;
$stmt = pdo_query( $pdo, $query, $params); 
$result = pdo_fetch_all( $stmt );
$num_rows = count($result);
$response['num_rows'] = $num_rows;



if($num_rows > 0 ) { // UPDATE THE ROW BECAUSE ALRADY EXISTS
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
                       shipping_by = :shipping_by, shipping_date = now(),
                       pass_or_fail = :pass_or_fail
                       WHERE id = :id',
					   array("id"=>$qc_form["id"], "customer_name"=>$qc_form['customer_name'], "order_id"=>$qc_form['order_id'], "monitor_id"=>$qc_form['monitor_id'], 						"build_type_id"=>$qc_form['build_type_id'], 
					   "shells_defects"=>$qc_form['shells_defects'], "shells_colors"=>$qc_form['shells_colors'], "shells_faced_down"=>$qc_form['shells_faced_down'], 						"shells_label"=>$qc_form['shells_label'], "shells_edges"=>$qc_form['shells_edges'], "shells_shine"=>$qc_form['shells_shine'], "shells_canal"=>$qc_form['shells_canal'], "shells_density"=>$qc_form['shells_density'],
					   "faceplate_seams"=>$qc_form['faceplate_seams'], "faceplate_shine"=>$qc_form['faceplate_shine'], "faceplate_colors"=>$qc_form['faceplate_colors'],  						"faceplate_rounded"=>$qc_form['faceplate_rounded'], "faceplate_foggy"=>$qc_form['faceplate_foggy'], "faceplate_residue"=>$qc_form['faceplate_residue'], 
					   "jacks_location"=>$qc_form['jacks_location'], "jacks_debris"=>$qc_form['jacks_debris'], "jacks_cable"=>$qc_form['jacks_cable'],
					   "ports_cleaned"=>$qc_form['ports_cleaned'], "ports_smooth"=>$qc_form['ports_smooth'], "ports_glued_correctly"=>$qc_form['ports_glued_correctly'], "ports_kinked_tube"=>$qc_form['ports_kinked_tube'], "ports_crushed_damper"=>$qc_form['ports_crushed_damper'],
					   "sound_signature"=>$qc_form['sound_signature'], "sound_balanced"=>$qc_form['sound_balanced'], "sound_correct_model"=>$qc_form['sound_correct_model'],
					   "artwork_placement"=>$qc_form['artwork_placement'],	"artwork_hq"=>$qc_form['artwork_hq'], "artwork_none"=>$qc_form['artwork_none'], 						"artwork_required"=>$qc_form['artwork_required'], "artwork_added"=>$qc_form['artwork_added'], 
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
					   "INSERT INTO qc_form (
customer_name, order_id, monitor_id, build_type_id, shells_defects, shells_colors, shells_faced_down, shells_label, shells_edges, shells_shine, shells_canal, shells_density, faceplate_seams, faceplate_shine, faceplate_colors, faceplate_rounded, faceplate_foggy, faceplate_residue, jacks_location, jacks_debris, jacks_cable, ports_cleaned, ports_smooth, ports_glued_correctly, ports_kinked_tube, ports_crushed_damper, sound_signature, sound_balanced, sound_correct_model, artwork_placement, artwork_hq, artwork_none, artwork_required, artwork_added, notes, shipping_cable, shipping_tools, shipping_card, shipping_case, shipping_additional, shipping_by, shipping_date, pass_or_fail)
VALUES (
:customer_name, :order_id, :monitor_id, :build_type_id, :shells_defects, :shells_colors, :shells_faced_down, :shells_label, :shells_edges, :shells_shine, :shells_canal, :shells_density, 
:faceplate_seams, :faceplate_shine, :faceplate_colors, :faceplate_rounded, :faceplate_foggy, :faceplate_residue, :jacks_location, :jacks_debris, :jacks_cable, :ports_cleaned, :ports_smooth, :ports_glued_correctly, :ports_kinked_tube, :ports_crushed_damper, :sound_signature, :sound_balanced, :sound_correct_model, :artwork_placement, :artwork_hq, :artwork_none, :artwork_required, :artwork_added, :notes, :shipping_cable, :shipping_tools, :shipping_card, :shipping_case, :shipping_additional, :shipping_by, now(), :pass_or_fail) RETURNING id",

array(':customer_name'=>$qc_form['customer_name'], ':order_id'=>$qc_form['order_id'],':monitor_id'=>$qc_form['monitor_id'], ':build_type_id'=>$qc_form['build_type_id'], ':shells_defects'=>$qc_form['shells_defects'], ':shells_colors'=>$qc_form['shells_colors'], ':shells_faced_down'=>$qc_form['shells_faced_down'], ':shells_label'=>$qc_form['shells_label'], ':shells_edges'=>$qc_form['shells_edges'], ':shells_shine'=>$qc_form['shells_shine'], ':shells_canal'=>$qc_form['shells_canal'], ':shells_density'=>$qc_form['shells_density'], ':faceplate_seams'=>$qc_form['faceplate_seams'], ':faceplate_shine'=>$qc_form['faceplate_shine'], ':faceplate_colors'=>$qc_form['faceplate_colors'], ':faceplate_rounded'=>$qc_form['faceplate_rounded'], ":faceplate_foggy"=>$qc_form['faceplate_foggy'], ":faceplate_residue"=>$qc_form['faceplate_residue'], 
':jacks_location'=>$qc_form['jacks_location'], ':jacks_debris'=>$qc_form['jacks_debris'], ':jacks_cable'=>$qc_form['jacks_cable'], ':ports_cleaned'=>$qc_form['ports_cleaned'], ':ports_smooth'=>$qc_form['ports_smooth'],  ":ports_glued_correctly"=>$qc_form['ports_glued_correctly'], ":ports_kinked_tube"=>$qc_form['ports_kinked_tube'], ":ports_crushed_damper"=>$qc_form['ports_crushed_damper'],
':sound_signature'=>$qc_form['sound_signature'], ':sound_balanced' =>$qc_form['sound_balanced'], ":sound_correct_model"=>$qc_form['sound_correct_model'], 
':artwork_placement'=>$qc_form['artwork_placement'], ':artwork_hq'=>$qc_form['artwork_hq'], ':artwork_none'=>$qc_form['artwork_none'], ':artwork_required'=>$qc_form['artwork_required'], ':artwork_added'=>$qc_form['artwork_added'], ':notes'=>$qc_form['notes'], ":shipping_cable"=>$qc_form['shipping_cable'], ":shipping_tools"=>$qc_form['shipping_tools'], ":shipping_card"=>$qc_form['shipping_card'], ":shipping_case"=>$qc_form['shipping_case'], ":shipping_additional"=>$qc_form['shipping_additional'], ":shipping_by"=>$_SESSION['UserId'], ":pass_or_fail"=>$qc_form['pass_or_fail'])
);			

	/*$response['message'] = "Not greater than 0";
	echo json_encode($response);
	exit;*/
}		 					 

//if($start_cart["barcode"][0] == 'R') {
if( $qc_form['build_type_id'] == 2 || $qc_form['build_type_id'] == 4) {
	////////////////////////////////////////////  MOVES THE REPAIR TO DONE ////////////////////////////////////////////
	$repair_id_is = substr($qc_form["order_id"], 1, strlen($qc_form["order_id"]) ); // REMOVES THE "R" FROM THE ID WHEN A REPAIR
	$query = pdo_query($pdo, "SELECT * FROM repair_status_table WHERE status_of_repair= 'Done'", null);
	$result = pdo_fetch_array($query);
	$status_id = $result["order_in_repair"];

	$query = pdo_query($pdo, "SELECT * FROM qc_form WHERE id = :id", array(":id"=>$qc_form["id"]));
	$result = pdo_fetch_array($query);
	
	$response["test"] = "it is " . $result["id_of_repair"] . " and it is " . $result[0]["id_of_repair"];
	//echo json_encode($response);
	//exit;

	if($result["id_of_repair"] === null || $result["id_of_repair"] < 1) { // SOME LINES FROM qc_form WON'T HAVE AN id_of_order BECAUSE THE ORIGINAL PROGRAMMING 	DIDN'T INCLUDE THAT COLUMN
		$stmt = pdo_query($pdo, "SELECT * FROM repair_form WHERE customer_name = :customer_name AND rma_number = :repair_id", 			array(":customer_name"=>$qc_form['customer_name'], ":repair_id"=>$repair_id_is));
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
	
		$stmt = pdo_query( $pdo, "INSERT INTO repair_status_log (date, repair_form_id, repair_status_id, notes,  user_id) VALUES (now(), :repair_form_id, :repair_status_id, :notes, :user_id) RETURNING id", array(':repair_form_id'=>$response['ID_is'], ':repair_status_id'=>$status_id, ':notes'=>"Moved to done by shipping", ':user_id'=>$_SESSION['UserId']));					 					 
	 
		$stmt = pdo_query( $pdo, 'UPDATE repair_form SET repair_status_id = :repair_status_id WHERE id = :id', array("id"=>$response['ID_is'], "repair_status_id"=>$status_id));
	
	
} else {
////////////////////////////////////////////  MOVES THE EARPHONE TO DONE ////////////////////////////////////////////
$order_id_is = $qc_form["order_id"];		
$query = pdo_query($pdo, "SELECT * FROM order_status_table WHERE status_of_order = 'Done'", null);
$result = pdo_fetch_array($query);
$status_id = $result["order_in_manufacturing"];

$query = pdo_query($pdo, "SELECT * FROM qc_form WHERE id = :id", array(":id"=>$qc_form["id"]));
$result = pdo_fetch_all($query);

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
