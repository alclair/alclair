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
	$repair_form['rep_fit_issue'] = $_POST['rep_fit_issue'];
	
	$repair_form['received_date'] = $_POST['received_date'];
	$repair_form['estimated_ship_date'] = $_POST['estimated_ship_date'];
	
	// IF STATEMENT ADDED 07/29/2019 TO SET THE ESTIMATED SHIP DATE TO NULL WHEN
	// USER WANTS TO REMOVE THE DATE FRO THE REPAIR FORM
	// THIS IS NEEDED BECAUSE ON OCCASION A REPAIR WILL REQUIRE IMPRESSIONS
	// THAT TAKE A LONG TIME TO COME IN AND THE ESTIMATED SHIP DATE IS UNKNOWN
	if($repair_form['estimated_ship_date'] == '') {
		$repair_form['estimated_ship_date'] = null;
	}
	$repair_form['rma_number'] = $_POST['rma_number'];
	
	$repair_form['repair_status_id'] = $_POST['repair_status_id'];
	
	$stmt = pdo_query( $pdo, "SELECT * FROM repair_status_log WHERE repair_form_id = :id ORDER BY id DESC LIMIT 1", array(":id"=>$_REQUEST['id']));
	$is_order_status_same = pdo_fetch_all( $stmt );
	if($repair_form['repair_status_id'] != $is_order_status_same[0]["repair_status_id"]) { // ADD TO THE LOG
		$stmt = pdo_query( $pdo, "INSERT INTO repair_status_log (date, repair_form_id, repair_status_id, notes,  user_id) 
				VALUES (now(), :repair_form_id, :repair_status_id, :notes, :user_id) RETURNING id",
									array(':repair_form_id'=>$_REQUEST['id'], ':repair_status_id'=>$repair_form['repair_status_id'], ':notes'=>"From the Edit RMA Page", ':user_id'=>$_SESSION['UserId']));			
	}

	$stmt = pdo_query( $pdo, 
					   'UPDATE repair_form SET customer_name = :customer_name, email = :email, phone = :phone, address = :address, monitor_id = :monitor_id, diagnosis = :diagnosis, quotation = :quotation, artwork_white = :artwork_white, artwork_black = :artwork_black, artwork_logo = :artwork_logo, artwork_icon = :artwork_icon, artwork_stamp = :artwork_stamp, artwork_script = :artwork_script, artwork_custom = :artwork_custom, shell_left_color = :shell_left_color, shell_right_color = :shell_right_color, shell_left_face = :shell_left_face, shell_right_face = :shell_right_face, shell_left_tip = :shell_left_tip, shell_right_tip = :shell_right_tip, left_alclair_logo = :left_alclair_logo, right_alclair_logo = :right_alclair_logo, customer_contacted = :customer_contacted, warranty_repair = :warranty_repair, customer_billed = :customer_billed, consulted = :consulted, name_contacted = :name_contacted, personal_item = :personal_item, rep_fit_issue = :rep_fit_issue, received_date = :received_date, estimated_ship_date = :estimated_ship_date, rma_number = :rma_number, repair_status_id = :repair_status_id
                       WHERE id = :id',
					   array("id"=>$repair_form["id"], "customer_name"=>$repair_form['customer_name'], "email"=>$repair_form['email'], "phone"=>$repair_form['phone'], "address"=>$repair_form['address'], "monitor_id"=>$repair_form['monitor_id'], "diagnosis"=>$repair_form['diagnosis'], "quotation"=>$repair_form['quotation'], "artwork_white"=>$repair_form['artwork_white'], "artwork_black"=>$repair_form['artwork_black'],  "artwork_logo"=>$repair_form['artwork_logo'], "artwork_icon"=>$repair_form['artwork_icon'], "artwork_stamp"=>$repair_form['artwork_stamp'], "artwork_script"=>$repair_form['artwork_script'], "artwork_custom"=>$repair_form['artwork_custom'], "shell_left_color"=>$repair_form['shell_left_color'], "shell_right_color"=>$repair_form['shell_right_color'], "shell_left_face"=>$repair_form['shell_left_face'], "shell_right_face"=>$repair_form['shell_right_face'], "shell_left_tip"=>$repair_form['shell_left_tip'], "shell_right_tip"=>$repair_form['shell_right_tip'], "left_alclair_logo"=>$repair_form['left_alclair_logo'], "right_alclair_logo"=>$repair_form['right_alclair_logo'],
"customer_contacted"=>$repair_form['customer_contacted'], "warranty_repair"=>$repair_form['warranty_repair'], "customer_billed"=>$repair_form['customer_billed'], "consulted"=>$repair_form['consulted'], "name_contacted"=>$repair_form['name_contacted'], "personal_item"=>$repair_form['personal_item'], "rep_fit_issue"=>$repair_form['rep_fit_issue'], "received_date"=>$repair_form['received_date'], "estimated_ship_date"=>$repair_form["estimated_ship_date"], "rma_number"=>$repair_form['rma_number'], "repair_status_id"=>$repair_form["repair_status_id"]));
			 
	$query = pdo_query($pdo, "SELECT * FROM repair_form WHERE id = :id", array(":id"=>$repair_form["id"]));
	$result2 = pdo_fetch_all( $stmt );
	
	if($result2[0]["id_of_qc_form"] === null) { // SOME LINES FROM import_orders WON'T HAVE AN id_of_qc_form BECAUSE THE ORIGINAL PROGRAMMING DIDN'T INCLUDE THAT COLUMN
		$stmt = pdo_query($pdo, "SELECT * FROM qc_form WHERE order_id = :order_id AND customer_name = :customer_name", array(":order_id"=>$repair_form['rma_number'], ":customer_name"=>$repair_form['customer_name']));
		$result3 = pdo_fetch_all($stmt);
		if(count($result3) > 1) {
			// CAN'T UPDATE THE QC FORM BECAUSE MORE THAN 1 QC FORM WITH THAT ORDER # EXISTS
			$response["code"] = "success";
			$response["message"] = "Updated RMA but not QC Form.";
			echo json_encode($response);
			exit;
		} else { // UPDATE NAME ON THE QC FORM
			$stmt = pdo_query($pdo, "UPDATE qc_form SET customer_name = :customer_name WHERE order_id = :order_id", array(":customer_name"=>$repair_form["customer_name"], ":order_id"=>$repair_form['rma_number']));
		}
			$response["message"] = "Updated RMA and QC Form.";
	} else {
		$stmt = pdo_query($pdo, "UPDATE qc_form SET customer_name = :customer_name WHERE id = :id", array(":customer_name"=>$repair_form["customer_name"], ":id"=>$result2[0]["id_of_qc_form"]));
		$response["message"] = "Updated RMA and QC Form.";
	}
	
	

	$rowcount = pdo_rows_affected( $stmt );
	
	$response["test"] = "111111";
	//echo json_encode($response);
	//exit;
	
	
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