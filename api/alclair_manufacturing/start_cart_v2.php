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

	$start_cart = array();
	$start_cart['barcode'] = $_POST['barcode'];
	$start_cart['notes'] = $_POST['notes'];
	
	if(empty($start_cart['barcode']) == true)
	{
		$response['message'] = 'Please enter a barcode.';
		echo json_encode($response);
		exit;
	}

$tester = $start_cart['barcode'];
$barcode_length = strlen($start_cart['barcode']);	
/*for ($x = 0; $x < $barcode_length; $x++) {	
	if ( !strcmp($tester[$x], '-') ) {
		$order_id_is = substr($tester, 0, $x-1);
		break;
	}
	if ($x == $barcode_length - 1) {
		$response['message'] = 'Did not work';
		echo json_encode($response);
		exit;
	}
} */

//if($start_cart["barcode"][0] == 'R') {	
if($start_cart["barcode"][0] == 'R' || $start_cart["barcode"][0] == '9') {
	//if($start_cart["barcode"][0] == 'R') {
		//$repair_id_is = substr($start_cart["barcode"], 1, $barcode_length);	
	
		if($start_cart["barcode"][0] == 'R') {	
			$repair_id_is = substr($start_cart["barcode"], 1, $barcode_length);			
		} else {
			$repair_id_is = intval($start_cart["barcode"]) - 80000;
			$repair_id_is = strval($repair_id_is);
		}	

$response["testing"] = $repair_id_is;
	//echo json_encode($response);
	//exit;

$query = pdo_query($pdo, "SELECT * FROM repair_status_table WHERE status_of_repair = 'Start Cart'", null);
$result = pdo_fetch_array($query);
$status_id = $result["order_in_repair"];

// ORDER STATUS LOG
// IMPORT ORDERS			
$stmt = pdo_query( $pdo, "INSERT INTO repair_status_log (date, repair_form_id, repair_status_id, notes,  user_id) VALUES (now(), :repair_form_id, :status_id, :notes, :user_id) RETURNING id",
									array(':repair_form_id'=>$repair_id_is, ':status_id'=>$status_id, ':notes'=>$start_cart['notes'], ':user_id'=>$_SESSION['UserId']));					 					 
	 
$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 ) {
	$response['message'] = pdo_errors();
	$response["testing8"] = "8888888";
	echo json_encode($response);
	exit;
}

$stmt = pdo_query( $pdo, 'UPDATE repair_form SET repair_status_id = :repair_status_id WHERE id = :id',
								   array("id"=>$repair_id_is, "repair_status_id"=>$status_id));
								   
} else {
	
$order_id_is = $start_cart['barcode'];
			
$query = pdo_query($pdo, "SELECT * FROM order_status_table WHERE status_of_order = 'Start Cart'", null);
$result = pdo_fetch_array($query);
$status_id = $result["order_in_manufacturing"];

//$response["test"] = "the status id is " . $status_id;
//echo json_encode($response);
//exit;
			
// ORDER STATUS LOG
// IMPORT ORDERS			
$stmt = pdo_query( $pdo, "INSERT INTO order_status_log (date, import_orders_id, order_status_id, notes,  user_id) VALUES (now(), :import_orders_id, :status_id, :notes, :user_id) RETURNING id",
									array(':import_orders_id'=>$order_id_is, ':status_id'=>$status_id, ':notes'=>$start_cart['notes'], ':user_id'=>$_SESSION['UserId']));					 					 
	 
$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 ) {
	$response['message'] = pdo_errors();
	$response["testing8"] = "8888888";
	echo json_encode($response);
	exit;
}

$stmt = pdo_query( $pdo, 'UPDATE import_orders SET order_status_id = :order_status_id WHERE id = :id',
								   array("id"=>$order_id_is, "order_status_id"=>$status_id));

} // CLOSE ELSE STATEMENT

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////// CALC ESTIMATED SHIP DATE CODE  ///////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////      HOLIDAYS       //////////////////////////////////////////////////
    // GET THE HOLIDAYS
	$query = "SELECT *, to_char(date, 'MM/dd/yyyy') as holiday_date FROM holiday_log WHERE active = TRUE ORDER BY date ASC";
    //$params2[":repair_form_id"] = $_REQUEST['id'];
    $stmt = pdo_query( $pdo, $query, null); 
	$holidays = pdo_fetch_all( $stmt );  
	$rows_in_result = pdo_rows_affected($stmt);
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////     GET THE DAILY BUILD RATE INFORMATION     /////////////////////////////////////////////////////
	$query2 = "SELECT * FROM daily_build_rate ";
    $stmt2 = pdo_query( $pdo, $query2, null); 
	$daily_build_rate= pdo_fetch_all( $stmt2 );  

	$daily_rate = $daily_build_rate[0]["daily_rate"];
	$fudge = $daily_build_rate[0]["fudge"];
	$shop_days = $daily_build_rate[0]["shop_days"];	 
	
	//$daily_rate = 5;
	//$fudge = 1;
	//$shop_days = 7;
	$daily_rate = 	$daily_rate - $fudge;
	////////////////////////////////////////////////////////////////////////////////////////////////   
    
 ///////////////////////////////////////////////////////  FUNCTIONS START    /////////////////////////////////////////////////		 
	function test_function($pdo) {#030000
		$query = "SELECT *, to_char(date, 'MM/dd/yyyy') as holiday_date FROM holiday_log WHERE active = TRUE ORDER BY date ASC";
		$stmt = pdo_query( $pdo, $query, null); 
		$holidays = pdo_fetch_all( $stmt );  
		return $holidays;
	}
	
	function calc_estimate_ship_date($array, $date, $holidays, $shop_days, $pdo) {	
		if($array["use_for_estimated_ship_date"] == NULL) {
			$ship_day = new DateTime(); // TODAY'S DATE
			$ship_day->modify('+15 day'); // NEEDS TO START WITH TOMORROW
			$imp_date = $date->format('Y-m-d');
		} else {
			$today = new DateTime(); // TODAY'S DATE
			$today->modify('+1 day'); // NEEDS TO START WITH TOMORROW
			if($today->format('m/d/Y') > $date->format('m/d/Y') ) {
				$nextDay = clone $today;
				$finalDay = clone $today;
			} else {
				$nextDay = clone $date;
				$finalDay = clone $date;
			}
			
			$weekend = array('Sun', 'Sat');
			//$nextDay = clone $date;
			//$finalDay = clone $date;
			$work_days = 0;
			$days_to_final_date = 0;
			for ($i = 0; $i < count($holidays); $i++) {
				$store_holidays[$i] = $holidays[$i]["holiday_date"];	
			}
			while ($work_days < $shop_days)
			{
	   	 		$nextDay->modify('+1 day'); // Add 1 day
	   	 		if($nextDay->format('D'))
		   	 	if (in_array($nextDay->format('D'), $weekend) || in_array($nextDay->format('m/d/Y'), $store_holidays)) {
		   	 	//if (in_array($nextDay->format('D'), $weekend) || in_array($nextDay->format('m-d'), $holidays)) {
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
		}
			$query = "UPDATE import_orders SET fake_imp_date = :imp_date, estimated_ship_date = :estimated_ship_date WHERE id = :id";
			//$query = "UPDATE import_orders SET estimated_ship_date=:estimated_ship_date WHERE id = :id";
			$stmt = pdo_query( $pdo, $query, array(":imp_date"=>$imp_date, "estimated_ship_date"=>$ship_day, ":id"=>$array["id"])); 	
			//$stmt = pdo_query( $pdo, $query, array("estimated_ship_date"=>$ship_day, ":id"=>$array["id"])); 	
			return $array["id"];
		
		//return $finalDay;
	}
 ///////////////////////////////////////////////////////  FUNCTIONS END    /////////////////////////////////////////////////
	
	////////////////////////////////////   GET ORDERS IN START CART    ////////////////////////////////////////////////////////////   
	$weekend = array('Sun', 'Sat');
	//TF WAS HERE ON FEBRUARY 11, 2021
	//$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NOT NULL ORDER BY fake_imp_date DESC LIMIT :daily_rate";
	$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NOT NULL AND use_for_estimated_ship_date != FALSE ORDER BY fake_imp_date DESC LIMIT :daily_rate";
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
		//TF WAS HERE ON FEBRUARY 11, 2021
		//$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NULL ORDER BY received_date ASC";
		$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NULL AND use_for_estimated_ship_date != FALSE ORDER BY received_date ASC";
		$stmt3 = pdo_query( $pdo, $query3, null); 
		$populate_new= pdo_fetch_all( $stmt3 ); 
		$count = pdo_rows_affected($stmt3);
		$date = new DateTime(); // TODAY'S DATE
		$date->modify('+1 day'); // NEEDS TO START WITH TOMORROW
		//while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
		while (in_array($date->format('D'), $weekend) || in_array($date->format('m/d/Y'), $store_holidays)) {
			$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
		}
		
		for ($i = 0; $i < $count; $i++) {
			if($num > $daily_rate) {
				$date->modify('+1 day'); 
				//while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
				while (in_array($date->format('D'), $weekend) || in_array($date->format('m/d/Y'), $store_holidays)) {
					$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
				}
				$num = 1;
			}

			$response["test"] = calc_estimate_ship_date($populate_new[$i], $date, $holidays, $shop_days, $pdo);
			$num++;
		}
	} else{ // START HAS NO NULL ORDERS IN START CART - THIS CODE HAS RUN BEFORE
		$query4 = "SELECT * FROM import_orders WHERE active = TRUE AND id = :id";
		$stmt4 = pdo_query( $pdo, $query4, array("id"=>$traveler["id"])); 
		$order= pdo_fetch_all( $stmt4 ); 

		if($count == $daily_rate && ($find_last_fake_imp_date[0]["fake_imp_date"] == $find_last_fake_imp_date[$daily_rate-1]["fake_imp_date"]) ) {
			$date = new DateTime($find_last_fake_imp_date[0]["fake_imp_date"]); 
			$date->modify('+1 day');
			//while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
			while (in_array($date->format('D'), $weekend) || in_array($date->format('m/d/Y'), $store_holidays)) {
				$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
			}
		} else {
			$date = new DateTime($find_last_fake_imp_date[0]["fake_imp_date"]); 
		}
		$response["test"] = $order[0]["id"];
		//$response["test"] = "fasdfasdfadsf";
		//echo json_encode($response);
		//exit;
		$response["test"] = calc_estimate_ship_date($order[0], $date, $holidays, $shop_days, $pdo);
	}
/////////////////////////////////////////////////////////////////////// END CALC ESTIMATED SHIP DATE ///////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



$rowcount = pdo_rows_affected( $stmt );
if( $rowcount == 0 )
{
	$response['message'] = pdo_errors();
	$response['code'] = 'NOPE';
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