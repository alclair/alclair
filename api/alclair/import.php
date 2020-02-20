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

//$fc="1".":".$_FILES['documentfile']['name'];

try
{
	
	
$target_dir = "../../data/";
$target_file = $target_dir . basename($_FILES["documentfile"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check file size
if ($_FILES["docuementfile"]["size"] > 500000) {
    $response["message"] = "Sorry, your file is too large.";
    $uploadOk = 0;
}
	// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    //$response['testing5'] = "Sorry, your file was not uploaded.";
	echo json_encode($response);
	exit;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["documentfile"]["tmp_name"], $target_file)) {
        //$response['message'] = "The file ". basename( $_FILES["documentfile"]["name"]). " has been uploaded.";
    } else {
        $response['message'] = "Sorry, there was an error uploading your file.";
        echo json_encode($response);
		exit;
    }
}

//$File = '../../data/' + $target_file;
$File = $target_file;
$inc = 0;

// PUT ALL OF THE DATA INTO $arrResult
$arrResult  = array();
$handle     = fopen($File, "r");
if(empty($handle) === false) {
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $arrResult[$inc] = $data;
        $inc = $inc + 1;
    }
    fclose($handle);
}
$response['testing3'] = $arrResult[3][1];
$response['testing1'] = count($arrResult[0]);
$response['testing4'] = $inc - 1; // NUMBER OF ROWS
//echo json_encode($response);
//exit;

if (count($arrResult[0]) != 50) {
	$response['error_message'] = "Something is wrong with the file.  Please contact Tyler";
	echo json_encode($response);
	exit;
}


// REQUIRED 2 INCREMENTS BUT OF THE SAME ARRAY
$inc2 = $inc;
// DETERMINE NUMBER OF EARPHONES PER ORDER
for ($x=1; $x < $inc; $x++) {  // 	START ROW 2 TO IGNORE HEADER
	$num_of_earphones_in_order = 0;  // START NUMBER OF EARPHONES PER ORDER AT ZERO
	for ($y=1; $y < $inc2; $y++) {	// 	START ROW 2 TO IGNORE HEADER
		if($arrResult[$x][1] == $arrResult[$y][1]) {  // SEEEING IF THE ORDER NUMBER EXISTS
			$num_of_earphones_in_order = $num_of_earphones_in_order + 1;  // EACH ORDER NUMBER WILL EXIST AT LEAST ONCE
		}
		if($y == $inc2-1) {  // AT THE END OF THE ARRAY SET THE 28TH COLUMN TO THE NUMBER OF EARPHONES PER ORDER
			// COMMENTED OUT LINE BELOW BECAUSE IMPORT FORM CHANGED
			// HAD TO CHANGE 28 TO 50
			//$arrResult[$x][28] = $num_of_earphones_in_order; 
			$arrResult[$x][50] = $num_of_earphones_in_order;
		}
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////// CALC ESTIMATED SHIP DATE CODE  ////////////////////////////////////////////////////////////////

									//////////////////////////////////////////////////////      HOLIDAYS       //////////////////////////////////////////////////
    // GET THE HOLIDAYS
	$query = "SELECT *, to_char(date, 'MM/dd/yyyy') as holiday_date FROM holiday_log WHERE active = TRUE ORDER BY date ASC";
    //$params2[":repair_form_id"] = $_REQUEST['id'];
    $stmt = pdo_query( $pdo, $query, null); 
	$holidays = pdo_fetch_all( $stmt );  
	$rows_in_result = pdo_rows_affected($stmt);
													////////////////////////////////////////////////////////////////////////////////////////////////////////
					//////////////////////////////////////     GET THE DAILY BUILD RATE INFORMATION     ///////////////////////////////////////////////
	$query2 = "SELECT * FROM daily_build_rate ";
    $stmt2 = pdo_query( $pdo, $query2, null); 
	$daily_build_rate= pdo_fetch_all( $stmt2 );  

	$daily_rate = $daily_build_rate[0]["daily_rate"];
	$fudge = $daily_build_rate[0]["fudge"];
	$shop_days = $daily_build_rate[0]["shop_days"];	 
	
	//$daily_rate = 5;
	//$fudge = 1;
	//$shop_days = 7;
	$daily_rate = $daily_rate - $fudge;
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
    
									///////////////////////////////////////////////////////  FUNCTIONS START    /////////////////////////////////////////////////		 
	function calc_estimate_ship_date($array, $date, $holidays, $shop_days, $pdo) {	
		$weekend = array('Sun', 'Sat');
		$nextDay = clone $date;
		$finalDay = clone $date;
		$work_days = 0;
		$days_to_final_date = 0;
		while ($work_days < $shop_days)
		{
   	 		$nextDay->modify('+1 day'); // Add 1 day
   	 		if($nextDay->format('D'))
   	 		if (in_array($nextDay->format('D'), $weekend) || in_array($nextDay->format('m-d'), $holidays)) {
	   	 		$response["test"] = "HERE"; 
	   	 		$days_to_final_date++;
	   	 	} else {		   	 
		   		$days_to_final_date++;
		   		$work_days++;
	   	 	}
		}
		$finalDay->modify('+' . $days_to_final_date .  ' day');
		$ship_day = $finalDay->format('Y-m-d');
		$imp_date = $date->format('Y-m-d');
		
		// IN THIS FILE WE HAVE TO MANUALLY SET THE ORDER STATUS ID EQUAL TO 1 WHICH IS THE START CART
		$query = "UPDATE import_orders SET fake_imp_date = :imp_date, estimated_ship_date = :estimated_ship_date, order_status_id = 1 WHERE id = :id";
		//$query = "UPDATE import_orders SET estimated_ship_date=:estimated_ship_date WHERE id = :id";
		$stmt = pdo_query( $pdo, $query, array(":imp_date"=>$imp_date, "estimated_ship_date"=>$ship_day, ":id"=>$array["id"])); 	
		//$stmt = pdo_query( $pdo, $query, array("estimated_ship_date"=>$ship_day, ":id"=>$array["id"])); 	
		return $array["id"];
		//return $finalDay;
	}
						///////////////////////////////////////////////////////  FUNCTIONS END    /////////////////////////////////////////////////

// DATE -> 0 
// ORDER ID -> 1
// PRODUCT -> 2
// QUANTITY -> 3
// MODEL -> 4
// ARTWORK-> 5
// COLOR-> 6
// RUSH PROCESS -> 7
// LEFT SHELL -> 8
// RIGHT SHELL -> 9
// LEFT FACEPLATE -> 10
// RIGHT FACEPLATE -> 11
// CABLE COLOR -> 12
// CLEAR CANAL -> 13
// LEFT ALCLAIR LOGO -> 14
// RIGHT ALCLAIR LOGO -> 15
// LEFT CUSTOM ART -> 16
// RIGHT CUSTOM ART -> 17
// LINK TO DESIGN IMAGE -> 18
// OPEN ORDER IN DESIGNER -> 19
// DESIGNED FOR -> 20
// MY IMPRESSIONS -> 21

// BILLING NAME -> 22  NEW -> 44
// SHIPPING NAME -> 23  NEW -> 45
// PRICE -> 24  NEW -> 46
// COUPON ->25   NEW -> 47
// DISCOUNT -> 26  NEW -> 48
// TOTAL -> 27  NEW -> 49
// ENTERED BY = INTEGER
// ACTIVE = TRUE
// ORDER STATUS ID = 99
// NUM EARPHONES PER ORDER -> 28  NEW -> 50

// PELICAN CASE NAME -> 29

$order  = array();

for ($i=1; $i < $inc; $i++) {
	$order = $arrResult[$i];
	
	if(!strcmp($order[2], "ELECTRO 6 DRIVER ELECTROSTATIC HYBRID") ) {
		$order[4] = "Electro";
		//$response["test1"] = "Model is  " . $order[2] . " and I is " . $i;
		//$response["test2"] = "PELICAN CASE NAME IS " . $order[29];
 	} 
 	
 	if(strcmp($order[13], "Yes")) {
		$left_tip = null;
		$right_tip = null;
	} else {
		$left_tip = "Clear";
		$right_tip = "Clear";
	}
 	
 	// IF STRING LENGTH IS GREATER THAN 1 AND DOES NOT EQUAL BLANK SPACES
    if(strlen($order[29]) > 1 && (strcmp($order[29], "  ") || strcmp($order[29], "   ") || strcmp($order[29], "    ") || strcmp($order[29], "     ") )  ) {
	    $notes = 'Pelican case name "' . $order[29] . '"';	   
    } else {
	    $notes = "";
    }
 
$stmt = pdo_query( $pdo, 
					   "INSERT INTO import_orders (
date, order_id, product, quantity, model, artwork, color, rush_process, left_shell, right_shell, left_faceplate, right_faceplate, cable_color, clear_canal, left_alclair_logo, right_alclair_logo, left_custom_art, right_custom_art, link_to_design_image, open_order_in_designer, designed_for, my_impressions, billing_name, shipping_name, price, coupon, discount, total, entered_by, active, order_status_id, num_earphones_per_order, left_tip, right_tip, pelican_case_name, notes, nashville_order, customer_type)
VALUES (
:date, :order_id, :product, :quantity, :model, :artwork, :color, :rush_process, :left_shell, :right_shell, :left_faceplate, :right_faceplate, :cable_color, :clear_canal, :left_alclair_logo, :right_alclair_logo, :left_custom_art, :right_custom_art, :link_to_design_image, :open_order_in_designer, :designed_for, :my_impressions, :billing_name, :shipping_name, :price, :coupon, :discount, :total, :entered_by, :active, :order_status_id, :num_earphones_per_order, :left_tip, :right_tip, :pelican_case_name, :notes, :nashville_order, :customer_type) RETURNING id",
array(':date'=>$order[0], ':order_id'=>$order[1],':product'=>$order[2], ':quantity'=>$order[3], ':model'=>$order[4], ':artwork'=>$order[5], ':color'=>$order[6], ':rush_process'=>$order[7], ':left_shell'=>$order[8], ':right_shell'=>$order[9], ':left_faceplate'=>$order[10], ':right_faceplate'=>$order[11], ':cable_color'=>$order[12], ':clear_canal'=>$order[13], ':left_alclair_logo'=>$order[14], ':right_alclair_logo'=>$order[15], ':left_custom_art'=>$order[16], ':right_custom_art'=>$order[17], ':link_to_design_image'=>$order[18], ':open_order_in_designer'=>$order[19], 
':designed_for' =>$order[20], 
':my_impressions'=>$order[21], 
':billing_name'=>$order[44], //=>$order[22], 
':shipping_name'=>$order[45], //=>$order[23], 
':price'=>$order[46], //$order[24], 
':coupon'=>$order[47], //=>$order[25], 
':discount'=>$order[48], //=>$order[26], 
':total'=>$order[49], //$order[27],
':entered_by'=>$_SESSION['UserId'],
':active'=>TRUE,
':order_status_id'=>99, 
':num_earphones_per_order'=>$order[50],
':left_tip'=>$left_tip,
':right_tip'=>$right_tip,
':pelican_case_name'=>$order[29],
':notes'=>$notes,
':nashville_order'=>$order[$k]['nashville_order'],
':customer_type'=>'Customer') //=>$order[28])
);

	$id_after_import = pdo_fetch_all( $stmt );
	$id_of_order = $id_after_import[0]["id"];

	$stmt = pdo_query($pdo, "SELECT * FROM monitors WHERE name = :monitor_name", array('monitor_name'=>$order[4]));
	$result = pdo_fetch_all( $stmt );

	$qc_form = array();
	$qc_form['customer_name'] = $order[20];  // DESIGNED FOR
	$qc_form['order_id'] = $order[1];  // ORDER ID
	$qc_form['monitor_id'] = $result[0]["id"];
	$qc_form['build_type_id'] = 1; // New Build
					
	$qc_form['notes'] = "Entry from import " . $i;
	$qc_form['notes'] = "";

	$stmt = pdo_query( $pdo, 
					   "INSERT INTO qc_form (customer_name, order_id, monitor_id, build_type_id, notes, active, qc_date, pass_or_fail, id_of_order)
					   	 VALUES (:customer_name, :order_id, :monitor_id, :build_type_id, :notes, :active, now(), :pass_or_fail, :id_of_order) RETURNING id",
array(':customer_name'=>$qc_form['customer_name'], ':order_id'=>$qc_form['order_id'],':monitor_id'=>$qc_form['monitor_id'], ':build_type_id'=>$qc_form['build_type_id'], ':notes'=>$qc_form['notes'], ":active"=>TRUE, ":pass_or_fail"=>"IMPORTED", ":id_of_order"=>$id_of_order)
);		

	$id_of_qc_form = pdo_fetch_all( $stmt );

	$stmt = pdo_query( $pdo, "UPDATE import_orders SET id_of_qc_form = :id_of_qc_form WHERE id = :id_of_order", array(":id_of_qc_form"=>$id_of_qc_form[0]["id"], ":id_of_order"=>$id_of_order));

	// LOOK INSIDE BATCHES FOR PAID ORDERS WITH IMPRESSIONS
	// GREATER THAN BATCH TYPE ID 1 IS ALL BATCHES THAT ARE NOT FROM TRADE SHOWS
	$query_batches = "SELECT * FROM batches AS t1 
					  LEFT JOIN batch_item_log AS t2 ON t1.id = t2.batch_id
					  WHERE t1.received_date IS NULL AND t1.batch_type_id > 1 AND t1.active = TRUE AND t2.active = TRUE AND t2.paid = TRUE";
	$stmt_batches = pdo_query( $pdo, $query_batches, null);
	$result_batches = pdo_fetch_all( $stmt_batches );
	$row_count_batches = pdo_rows_affected( $stmt_batches );
	
for ($j=0; $j < $row_count_batches; $j++) {
		//$batch_order_num = preg_replace("/[^a-zA-Z]/", "", $result_batches[$j]["order_number"]);
		//$woo_order_num = preg_replace("/[^a-zA-Z]/", "", $order[1]);
		$batch_order_num = preg_replace("/[^0-9]/", "",  $result_batches[$j]["order_number"] );
		$woo_order_num = preg_replace("/[^0-9]/", "",  $order[1] );
				
		//if(!strcmp($order[1], $clear) {
		// MOVE TO START CART	
	if(!strcmp($woo_order_num, $batch_order_num)) {	

						//////////////////////////////////////////////   GET ORDERS IN START CART    ////////////////////////////////////////////////////////////   
	$weekend = array('Sun', 'Sat');
	
	$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NOT NULL ORDER BY fake_imp_date DESC LIMIT :daily_rate";
	$stmt3 = pdo_query( $pdo, $query3, array(":daily_rate"=>$daily_rate)); 
	$find_last_fake_imp_date= pdo_fetch_all( $stmt3 ); 
	$count = pdo_rows_affected($stmt3);
		
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// IF ABOVE QUERY RETURNS ZERO MEANS EVERY ORDER IN START CART IS NULL - THE CODE HAS BEEN NEVER RUN BEFORE   
	// IF THIS ALGORITHM HAS NEVER BEEN RUN BEFORE - THE SYSTEM AUTO-POPULATES ALL OF THE ORDERS IN THE START CART	 
	// START WITH THE IF STATEMENT
	// PULLS ALL OF START CART WHICH IS NULL FOR FAKE IMPRESSION DATE
	if($count == 0) {
		$num = 1;
		$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NULL ORDER BY received_date ASC";
		$stmt3 = pdo_query( $pdo, $query3, null); 
		$populate_new= pdo_fetch_all( $stmt3 ); 
		$count = pdo_rows_affected($stmt3);
		$date = new DateTime(); // TODAY'S DATE
		$date->modify('+1 day'); // NEEDS TO START WITH TOMORROW
		while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
			$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
		}
		
		for ($i = 0; $i < $count; $i++) {
			if($num > $daily_rate) {
				$date->modify('+1 day'); 
				while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
					$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
				}
				$num = 1;
			}

			$response["test"] = calc_estimate_ship_date($populate_new[$i], $date, $holidays, $shop_days, $pdo);
			$num++;
		}
	} else{ // START HAS NO NULL ORDERS IN START CART - THIS CODE HAS RUN BEFORE
		$query4 = "SELECT * FROM import_orders WHERE active = TRUE AND id = :id";
		$stmt4 = pdo_query( $pdo, $query4, array("id"=>$id_of_order)); 
		$order_batch= pdo_fetch_all( $stmt4 ); 
		
		if($count == $daily_rate && ($find_last_fake_imp_date[0]["fake_imp_date"] == $find_last_fake_imp_date[$daily_rate-1]["fake_imp_date"]) ) {
			$date = new DateTime($find_last_fake_imp_date[0]["fake_imp_date"]); 
			$date->modify('+1 day');
			while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
				$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
			}
		} else {
			$date = new DateTime($find_last_fake_imp_date[0]["fake_imp_date"]); 
		}
		$response["test"] = $order[0]["id"];
		//$response["test"] = "fasdfasdfadsf";
		//echo json_encode($response);
		//exit;
		$response["test"] = calc_estimate_ship_date($order_batch[0], $date, $holidays, $shop_days, $pdo);
	}
/////////////////////////////////////////////////////// END CALC ESTIMATED SHIP DATE ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	} // CLOSE THE IF STATEMENT THAT RUNS THE START CART CODE
	
} // CLOSE FOR LOOP THAT CYCLES THROUGH BATCHES


}  // END FOR LOOP
$response["code"] = "success";
$response["testing1"] = $inc;

echo json_encode($response);
}
catch(PDOException $ex)
{
	//$response["code"] = "error 2";
	$response["message"] = $ex->getMessage();
	//echo json_encode($response);
}

?>