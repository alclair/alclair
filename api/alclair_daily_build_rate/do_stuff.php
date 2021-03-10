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
	
    $conditionSql = "";
    $pagingSql = "";
    $orderBySqlDirection = "DESC";
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND id = :id";
        $params[":id"] = $_REQUEST['id'];
    }
    
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
	
	$daily_rate = 5;
	$fudge = 1;
	$shop_days = 7;
	$daily_rate = 	$daily_rate - $fudge;
	////////////////////////////////////////////////////////////////////////////////////////////////   
    
 ///////////////////////////////////////////////////////  FUNCTIONS START    /////////////////////////////////////////////////		 
	function test_function($pdo) {#030000
		$query = "SELECT *, to_char(date, 'MM/dd/yyyy') as holiday_date FROM holiday_log WHERE active = TRUE ORDER BY date ASC";
		$stmt = pdo_query( $pdo, $query, null); 
		$holidays = pdo_fetch_all( $stmt );  
		return $holidays;
		//return $array["designed_for"];
	}
	
	//$holidays = array('03-15', '03-14');
	//$holidays = array('03-15');	
	//$date = new DateTime('2019-03-05');
	function calc_estimate_ship_date($array, $date, $holidays, $shop_days, $pdo) {	
		$weekend = array('Sun', 'Sat');
		$nextDay = clone $date;
		$finalDay = clone $date;
		$work_days = 0;
		$days_to_final_date = 0;
		for ($i = 0; $i < count($holidays); $i++) {
			$store_holidays[$i] = $holidays[$i]["holiday_date"];	
		}
		while ($work_days < $shop_days)
		{
   	 		$nextDay->modify('+1 day'); // Add 1 day
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
		
		$query = "UPDATE import_orders SET fake_imp_date = :imp_date, estimated_ship_date = :estimated_ship_date WHERE id = :id";
		//$query = "UPDATE import_orders SET estimated_ship_date=:estimated_ship_date WHERE id = :id";
		$stmt = pdo_query( $pdo, $query, array(":imp_date"=>$imp_date, "estimated_ship_date"=>$ship_day, ":id"=>$array["id"])); 	
		//$stmt = pdo_query( $pdo, $query, array("estimated_ship_date"=>$ship_day, ":id"=>$array["id"])); 	
		return $array["id"];
		//return $finalDay;
	}
 ///////////////////////////////////////////////////////  FUNCTIONS END    /////////////////////////////////////////////////
	
	////////////////////////////////////   GET ORDERS IN START CART    ////////////////////////////////////////////////////////////   
	//TF WAS HERE ON FEBRUARY 11, 2021
	//$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NOT NULL ORDER BY fake_imp_date DESC LIMIT :daily_rate";
	$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NOT NULL AND use_for_estimated_ship_date != FALSEORDER BY fake_imp_date DESC LIMIT :daily_rate";
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
		
		for ($i = 0; $i < $count; $i++) {
			if($num > $daily_rate) {
				$date->modify('+1 day'); 
				$num = 1;
			}
			$response["test"] = calc_estimate_ship_date($populate_new[$i], $date, $holidays, $shop_days, $pdo);
			$num++;
			//$response["test"] = "Array is " . test_function($pdo);
			//echo json_encode($response);
			//exit;
		}
	} else{ // START HAS NO NULL ORDERS IN START CART - THIS CODE HAS RUN BEFORE
		if($count == $daily_rate) {
			$date = new DateTime($find_last_fake_imp_date[0]["fake_imp_date"]); 
			$date->modify('+1 day');
		} else {
			$date = new DateTime($find_last_fake_imp_date[0]["fake_imp_date"]); 
		}
		$response["test"] = calc_estimate_ship_date($populate_new[$i], $date, $holidays, $shop_days, $pdo);
	}
	
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['data_holidays'] = $result2;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>