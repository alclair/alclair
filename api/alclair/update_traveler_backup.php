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
	
    $traveler = array();
	$traveler['id'] = $_POST['id'];
	
	$traveler['order_id'] = $_POST['order_id'];	
	$traveler['designed_for'] = $_POST['designed_for'];
	$traveler['email'] = $_POST['email'];
	$traveler['phone'] = $_POST['phone'];
	$traveler['band_church'] = $_POST['band_church'];
	$traveler['address'] = $_POST['address'];
	$traveler['notes'] = $_POST['notes'];
	
	$traveler['monitor_id'] = $_POST['monitor_id'];
	$traveler['other_is'] = $_POST['other_is'];
	
	$traveler['left_tip'] = $_POST['left_tip'];
	$traveler['right_tip'] = $_POST['right_tip'];
	$traveler['left_shell'] = $_POST['left_shell'];
	$traveler['right_shell'] = $_POST['right_shell'];
	$traveler['left_faceplate'] = $_POST['left_faceplate'];
	$traveler['right_faceplate'] = $_POST['right_faceplate'];
	
	$traveler['cable_color'] = $_POST['cable_color'];
	$traveler['customer_type'] = $_POST['customer_type'];
	$traveler['artwork'] = $_POST['artwork'];
	$traveler['left_alclair_logo'] = $_POST['left_alclair_logo'];
	$traveler['right_alclair_logo'] = $_POST['right_alclair_logo'];
	
	$traveler['impression_color_id'] = $_POST['impression_color_id'];
	$traveler['order_status_id'] = $_POST['order_status_id'];
	
	if(!$_POST['date']) {
		// DO NOTHING BECAUSE IT IS NULL
		// THIS SEEMS TO WORK THOUGH I DON'T KNOW HOW
		/// NOT PROVIDING A VALUE FOR DATA DOESN'T SEEM TO AFFECT THE SQL QUERY
	} else {
		$traveler['date'] = $_POST['date'];
	}
	if(!$_POST['received_date']) {
		// SAME AS ABOVE
	} else {
		$traveler['received_date'] = $_POST['received_date'];
	}
	if(!$_POST['estimated_ship_date']) {
		// SAME AS ABOVE
	} else {
		$traveler['estimated_ship_date'] = $_POST['estimated_ship_date'];
	}
	
	$traveler['additional_items'] = $_POST['additional_items'];
	$traveler['consult_highrise'] = $_POST['consult_highrise'];
	$traveler['international'] = $_POST['international'];
	$traveler['universals'] = $_POST['universals'];
	$traveler['bite_block_not_used'] = $_POST['bite_block_not_used'];
	
	$traveler['hearing_protection'] = $_POST['hearing_protection'];
	$traveler['hearing_protection_color'] = $_POST['hearing_protection_color'];
	$traveler['musicians_plugs'] = $_POST['musicians_plugs'];
	$traveler['musicians_plugs_9db'] = $_POST['musicians_plugs_9db'];
	$traveler['musicians_plugs_15db'] = $_POST['musicians_plugs_15db'];
	$traveler['musicians_plugs_25db'] = $_POST['musicians_plugs_25db'];
	$traveler['pickup'] = $_POST['pickup'];
	
	$traveler['nashville_order'] = $_POST['nashville_order'];
	$traveler['fit_adjustment'] = $_POST['fit_adjustment'];
	
	$rush_process = $_POST['rush_process'];
	if($rush_process) {
		$traveler['rush_process'] = 'Yes';
	} else {
		$traveler['rush_process'] = '';
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
////////////////////////      ADDING CHANGE LOG INFORMATION HERE       //////////////////////////////////////////////////////////////////////////////////////////////////////////
	$stmt = pdo_query( $pdo, "SELECT * FROM import_orders WHERE id = :id", array(":id"=>$_REQUEST['id']));
	$updated = pdo_fetch_all( $stmt );
	$response['test'] = "New is " . $updated[0]['left_tip'] . " and old is " . $traveler['left_tip'];
	//echo json_encode($response);
	//exit;

	if(strcmp($updated[0]['left_tip'], $traveler['left_tip']) ) {
		$new_entry = $traveler['left_tip'];
		$old_entry = $updated[0]['left_tip'];
		if(strlen($traveler['left_tip']) < 2) { $new_entry = 'blank'; }
		if(strlen($updated[0]['left_tip']) < 2) { $old_entry = 'blank'; }
		$change = 'Left tip color changed from  "'. $old_entry . '" to "' . $new_entry . '"'; 
		$stmt = pdo_query( $pdo,  'INSERT INTO traveler_change_log  (user_id, import_orders_id, date, change)
																VALUES (:user_id, :import_orders_id, now(), :change) RETURNING id', 
																array(":user_id"=>$_SESSION['UserId'], ":import_orders_id"=>$traveler["id"], ":change"=>$change));
	}
	if(strcmp($updated[0]['right_tip'], $traveler['right_tip']) ) {
		$new_entry = $traveler['right_tip'];
		$old_entry = $updated[0]['right_tip'];
		if(strlen($traveler['right_tip']) < 2) { $new_entry = 'blank'; }
		if(strlen($updated[0]['right_tip']) < 2) { $old_entry = 'blank'; }
		$change = 'Right tip color changed from  "'. $old_entry . '" to "' . $new_entry . '"';
		$stmt = pdo_query( $pdo,  'INSERT INTO traveler_change_log  (user_id, import_orders_id, date, change)
																VALUES (:user_id, :import_orders_id, now(), :change) RETURNING id', 
																array(":user_id"=>$_SESSION['UserId'], ":import_orders_id"=>$traveler["id"], ":change"=>$change));
	}
	if(strcmp($updated[0]['left_shell'], $traveler['left_shell']) ) {
		$new_entry = $traveler['left_shell'];
		$old_entry = $updated[0]['left_shell'];
		if(strlen($traveler['left_shell']) < 2) { $new_entry = 'blank'; }
		if(strlen($updated[0]['left_shell']) < 2) { $old_entry = 'blank'; }
		$change = 'Left shell color changed from  "'. $old_entry . '" to "' . $new_entry . '"';
		$stmt = pdo_query( $pdo,  'INSERT INTO traveler_change_log  (user_id, import_orders_id, date, change)
																VALUES (:user_id, :import_orders_id, now(), :change) RETURNING id', 
																array(":user_id"=>$_SESSION['UserId'], ":import_orders_id"=>$traveler["id"], ":change"=>$change));
	}
	if(strcmp($updated[0]['right_shell'], $traveler['right_shell']) ) {
		$new_entry = $traveler['right_shell'];
		$old_entry = $updated[0]['right_shell'];
		if(strlen($traveler['right_shell']) < 2) { $new_entry = 'blank'; }
		if(strlen($updated[0]['right_shell']) < 2) { $old_entry = 'blank'; }
		$change = 'Right shell color changed from  "'. $old_entry . '" to "' . $new_entry . '"';
		$stmt = pdo_query( $pdo,  'INSERT INTO traveler_change_log  (user_id, import_orders_id, date, change)
																VALUES (:user_id, :import_orders_id, now(), :change) RETURNING id', 
																array(":user_id"=>$_SESSION['UserId'], ":import_orders_id"=>$traveler["id"], ":change"=>$change));
	}
	if(strcmp($updated[0]['left_alclair_logo'], $traveler['left_alclair_logo']) ) {
		$new_entry = $traveler['left_alclair_logo'];
		$old_entry = $updated[0]['left_alclair_logo'];
		if(strlen($traveler['left_alclair_logo']) < 2) { $new_entry = 'blank'; }
		if(strlen($updated[0]['left_alclair_logo']) < 2) { $old_entry = 'blank'; }
		$change = 'Left Alclair logo changed from  "'. $old_entry . '" to "' . $new_entry . '"';
		$stmt = pdo_query( $pdo,  'INSERT INTO traveler_change_log  (user_id, import_orders_id, date, change)
																VALUES (:user_id, :import_orders_id, now(), :change) RETURNING id', 
																array(":user_id"=>$_SESSION['UserId'], ":import_orders_id"=>$traveler["id"], ":change"=>$change));
	}
	if(strcmp($updated[0]['right_alclair_logo'], $traveler['right_alclair_logo']) ) {
		$new_entry = $traveler['right_alclair_logo'];
		$old_entry = $updated[0]['right_alclair_logo'];
		if(strlen($traveler['right_alclair_logo']) < 2) { $new_entry = 'blank'; }
		if(strlen($updated[0]['right_alclair_logo']) < 2) { $old_entry = 'blank'; }
		$change = 'Right Alclair logo changed from  "'. $old_entry . '" to "' . $new_entry . '"';
		$stmt = pdo_query( $pdo,  'INSERT INTO traveler_change_log  (user_id, import_orders_id, date, change)
																VALUES (:user_id, :import_orders_id, now(), :change) RETURNING id', 
																array(":user_id"=>$_SESSION['UserId'], ":import_orders_id"=>$traveler["id"], ":change"=>$change));
	}
	if(strcmp($updated[0]['left_faceplate'], $traveler['left_faceplate']) ) {
		$new_entry = $traveler['left_faceplate'];
		$old_entry = $updated[0]['left_faceplate'];
		if(strlen($traveler['left_faceplate']) < 2) { $new_entry = 'blank'; }
		if(strlen($updated[0]['left_faceplate']) < 2) { $old_entry = 'blank'; }
		$change = 'Left faceplate color changed from  "'. $old_entry . '" to "' . $new_entry . '"';
		$stmt = pdo_query( $pdo,  'INSERT INTO traveler_change_log  (user_id, import_orders_id, date, change)
																VALUES (:user_id, :import_orders_id, now(), :change) RETURNING id', 
																array(":user_id"=>$_SESSION['UserId'], ":import_orders_id"=>$traveler["id"], ":change"=>$change));
	}
	if(strcmp($updated[0]['right_faceplate'], $traveler['right_faceplate']) ) {
		$new_entry = $traveler['right_faceplate'];
		$old_entry = $updated[0]['right_faceplate'];
		if(strlen($traveler['right_faceplate']) < 2) { $new_entry = 'blank'; }
		if(strlen($updated[0]['right_faceplate']) < 2) { $old_entry = 'blank'; }
		$change = 'Right faceplate color changed from  "'. $old_entry . '" to "' . $new_entry . '"';
		$stmt = pdo_query( $pdo,  'INSERT INTO traveler_change_log  (user_id, import_orders_id, date, change)
																VALUES (:user_id, :import_orders_id, now(), :change) RETURNING id', 
																array(":user_id"=>$_SESSION['UserId'], ":import_orders_id"=>$traveler["id"], ":change"=>$change));
	}
////////////////////////      ENDING CHANGE LOG INFORMATION HERE       //////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	      
	$stmt = pdo_query( $pdo, "SELECT * FROM order_status_log WHERE import_orders_id = :id ORDER BY id DESC LIMIT 1", array(":id"=>$_REQUEST['id']));
	$is_order_status_same = pdo_fetch_all( $stmt );
	if($traveler['order_status_id'] != $is_order_status_same[0]["order_status_id"]) { // ADD TO THE LOG
		$stmt = pdo_query( $pdo, "INSERT INTO order_status_log (date, import_orders_id, order_status_id, notes,  user_id) 
				VALUES (now(), :import_orders_id, :order_status_id, :notes, :user_id) RETURNING id",
									array(':import_orders_id'=>$_REQUEST['id'], ':order_status_id'=>$traveler['order_status_id'], ':notes'=>"From the Edit Traveler Page", ':user_id'=>$_SESSION['UserId']));			
	}
	
	$response["testing"] = $is_order_status_same[0]["order_status_id"];
	$response["testing2"] = $traveler['order_status_id'];
	 $params = array();
	 $params[":monitor_id"] = $_REQUEST['monitor_id'];
	$query = "SELECT * FROM monitors WHERE id = :monitor_id";
	$stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );

$stmt = pdo_query( $pdo, 
					   'UPDATE import_orders SET designed_for=:designed_for, email=:email, phone=:phone, band_church=:band_church, address=:address, notes=:notes, model=:model, left_tip=:left_tip, right_tip=:right_tip, left_shell=:left_shell, right_shell=:right_shell, left_faceplate=:left_faceplate, right_faceplate=:right_faceplate, cable_color=:cable_color, artwork=:artwork, left_alclair_logo=:left_alclair_logo, right_alclair_logo=:right_alclair_logo, additional_items=:additional_items, consult_highrise=:consult_highrise,  international=:international, universals=:universals, bite_block_not_used=:bite_block_not_used, hearing_protection=:hearing_protection, musicians_plugs=:musicians_plugs, musicians_plugs_9db=:musicians_plugs_9db, musicians_plugs_15db=:musicians_plugs_15db, musicians_plugs_25db=:musicians_plugs_25db, pickup=:pickup, rush_process=:rush_process, date=:date, received_date=:received_date, impression_color_id=:impression_color_id, order_status_id=:order_status_id, estimated_ship_date=:estimated_ship_date, hearing_protection_color=:hearing_protection_color, nashville_order=:nashville_order, fit_adjustment=:fit_adjustment, customer_type=:customer_type
                       WHERE id = :id',
					   array("designed_for"=>$traveler["designed_for"], "email"=>$traveler["email"], "phone"=>$traveler["phone"], "band_church"=>$traveler["band_church"], "address"=>$traveler["address"], "notes"=>$traveler["notes"], "model"=>$result[0]["name"], "left_tip"=>$traveler["left_tip"], "right_tip"=>$traveler["right_tip"], "left_shell"=>$traveler["left_shell"], "right_shell"=>$traveler["right_shell"], "left_faceplate"=>$traveler["left_faceplate"], "right_faceplate"=>$traveler["right_faceplate"], "cable_color"=>$traveler["cable_color"], "artwork"=>$traveler["artwork"], "left_alclair_logo"=>$traveler["left_alclair_logo"], "right_alclair_logo"=>$traveler["right_alclair_logo"], "additional_items"=>$traveler["additional_items"], "consult_highrise"=>$traveler["consult_highrise"], "international"=>$traveler["international"], "universals"=>$traveler["universals"], "bite_block_not_used"=>$traveler["bite_block_not_used"], "hearing_protection"=>$traveler["hearing_protection"], "musicians_plugs"=>$traveler["musicians_plugs"], "musicians_plugs_9db"=>$traveler["musicians_plugs_9db"], "musicians_plugs_15db"=>$traveler["musicians_plugs_15db"], "musicians_plugs_25db"=>$traveler["musicians_plugs_25db"], "pickup"=>$traveler["pickup"], "rush_process"=>$traveler["rush_process"], "date"=>$traveler["date"], "received_date"=>$traveler["received_date"], "impression_color_id"=>$traveler["impression_color_id"], "order_status_id"=>$traveler["order_status_id"], "id"=>$traveler["id"], "estimated_ship_date"=>$traveler["estimated_ship_date"], "hearing_protection_color"=>$traveler["hearing_protection_color"], "nashville_order"=>$traveler["nashville_order"], "fit_adjustment"=>$traveler["fit_adjustment"], "customer_type"=>$traveler["customer_type"])
                       	  //,1
					 );
	
	$query = pdo_query($pdo, "SELECT * FROM import_orders WHERE id = :id", array(":id"=>$traveler["id"]));
	$result2 = pdo_fetch_all( $stmt );
	
	if($result2[0]["id_of_qc_form"] === null) { // SOME LINES FROM import_orders WON'T HAVE AN id_of_qc_form BECAUSE THE ORIGINAL PROGRAMMING DIDN'T INCLUDE THAT COLUMN
		$stmt = pdo_query($pdo, "SELECT * FROM qc_form WHERE order_id = :order_id", array(":order_id"=>$traveler['order_id']));
		$result3 = pdo_fetch_all($stmt);
		if(count($result3) > 1) {
			// CAN'T UPDATE THE QC FORM BECAUSE MORE THAN 1 QC FORM WITH THAT ORDER # EXISTS
			$response["code"] = "success";
			$response["message"] = "Updated order but not QC Form.";
			echo json_encode($response);
			exit;
		} else { // UPDATE NAME ON THE QC FORM
			$stmt = pdo_query($pdo, "UPDATE qc_form SET customer_name = :designed_for WHERE order_id = :order_id", array(":designed_for"=>$traveler["designed_for"], ":order_id"=>$traveler["order_id"]));
		}
			$response["message"] = "Updated order and QC Form.";
	} else {
		$stmt = pdo_query($pdo, "UPDATE qc_form SET customer_name = :designed_for WHERE id = :id", array(":designed_for"=>$traveler["designed_for"], ":id"=>$result2[0]["id_of_qc_form"]));
		$response["message"] = "Updated order and QC Form.";
	}
	
	$response['code'] = 'success';
	$response['data'] = $rowcount;
	//$response['testing1'] = $traveler['ports_cleaned'] ;
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>