<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = array();

try
{	
	
	$start_cart = array();
	$start_cart['barcode'] = $_REQUEST['barcode'];
	$cart_id= $_REQUEST['cart'];
	$order_id= $_REQUEST['order_id'];
	//$start_cart['notes'] = $_POST['notes'];

$tester = $start_cart['barcode'];
$barcode_length = strlen($start_cart['barcode']);	
/*for ($x = 0; $x < $barcode_length; $x++) {	
	if ( !strcmp($tester[$x], '-') ) {
		$order_id_is = substr($tester, 0, $x-1);
		break;
	}
} */
$order_id_is = $start_cart['barcode'];
	
	$params = array();
    if(!empty($order_id_is)) {  
	    
	    $params[":order_id_is"] = $order_id_is;
        $stmt = pdo_query( $pdo, "SELECT DISTINCT * FROM import_orders WHERE id = :order_id_is", $params);	
        $row = pdo_fetch_all( $stmt );
        $response['data'] = $row;
        $response['test'] = $params[":order_id_is"];
        
        
        $stmt2 = pdo_query( $pdo,
		  		"SELECT *, to_char(date, 'MM/dd/yyyy HH24:MI:SS') as date_of_log FROM order_status_log WHERE  import_orders_id = :order_id ORDER BY date DESC",
		  		//"SELECT * FROM order_status_log WHERE  import_orders_id = :order_id ORDER BY date DESC LIMIT 1",
		  		array(":order_id"=>$order_id));	
		 $result2 = pdo_fetch_all($stmt2);
		 
		 $date11 = $result2[0]["date_of_log"];
		 for($j=0; $j<count($result2); $j++) {   
			 if(count($result2) == 1) { // IF ONLY ONE LOG ENTRY THEN USE ITS DATE FOR THE CALCULATION
				break;	 
			 }
			 if($j == 0 && $result2[0]["order_status_id"] != $result2[1]["order_status_id"]) { // THIS IS WHERE 0 DAYS IN CART HAPPENS
				 $date11 = new DateTime('now');
				 $date11 = date_format($date11, 'm/d/Y H:i:s');
				 $response["test2"] = "HERE 1 and " . $result2[0]["order_status_id"] . " AND " . $result2[1]["order_status_id"];
				 break;
			 }
			//if($result2[$j]["order_status_id"] == $result2[$j+1]["order_status_id"]) {
			elseif($result2[0]["order_status_id"] == $result2[$j+1]["order_status_id"]) {	 // THIS IS WHERE SYSTEM DETERMINES HOW MANY DAYS IN SAME CART
				 //$date11 = $result2[$j+1]["date_of_log"];
				 $date11 = $result2[$j+1]["date_of_log"];
				 $response["test2"] = "HERE 2";
				 //break;
			 } else {
				 //$date11 = $result2[$j+1]["date_of_log"];
				 $response["test2"] = "HERE 3";
				 break;
			 }
		}
		
		// ORIGINAL METHOD BEFORE IMPLEMENTING THE FOR LOOP A FEW LINES ABOVE
        /*$stmt2 = pdo_query( $pdo,
		  		"SELECT *, to_char(date, 'MM/dd/yyyy HH24:MI:SS') as date_of_log FROM order_status_log WHERE  import_orders_id = :order_id ORDER BY date DESC LIMIT 1",
		  		//"SELECT * FROM order_status_log WHERE  import_orders_id = :order_id ORDER BY date DESC LIMIT 1",
		  		array(":order_id"=>$order_id));	
        
        $result2 = pdo_fetch_all($stmt2);
        */
       //$date1 = new DateTime($result2[0]["date_of_log"]);
       $date1 = new DateTime($date11);
       //$date1 = new DateTime('05/10/2019 11:11:11');
		//$date2 = new DateTime('05/14/2019 11:11:11');
		$date2 = new DateTime('01/08/2019 12:11:11');
		$date_today = new DateTime('now');

		// The diff-methods returns a new DateInterval-object...
		//$diff = $date2->diff($date1);
		$diff = $date_today->diff($date1);

		// Call the format method on the DateInterval-object
		$diff->format('%a Day and %h hours');
		$hours = $diff->format('%h');
		$days = $diff->format('%a');
		
		$total_hours = $days * 24 + $hours;
		
		if($total_hours < 24) {
			$total_days = 0 . " days";
		} else {
			if($days ==1 ) {
				$total_days = $days . " day";
			} else {
				$total_days = $days . " days";	
			}
		}
		        
        $response["days"] = $result2[0]["date_of_log"];
        $response["test"] = $date11;
		 //$response["random"] = "CART IS " . $cart_id . " and order ID is " . $order_id . " hours " . $hours ;
		 //$response["random"] = $days . " days and " . $hours . " hours";
		 $response["days"] = $total_days;

    }
	else
    {        
		//$response['message'] = 'IN HERE';
		//echo json_encode($response);
		//exit;
    }
	$response['code']='success';	
	//var_export($result);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>