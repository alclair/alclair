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
	$repair_id= $_REQUEST['repair_id'];
	//$start_cart['notes'] = $_POST['notes'];

$tester = $start_cart['barcode'];
$barcode_length = strlen($start_cart['barcode']);	
/*for ($x = 0; $x < $barcode_length; $x++) {	
	if ( !strcmp($tester[$x], '-') ) {
		$order_id_is = substr($tester, 0, $x-1);
		break;
	}
} */
$rma_id_is = $start_cart['barcode'];
	
	$params = array();
    if(!empty($rma_id_is)) {  
	    
	    $params[":rma_id_is"] = $rma_id_is;
        $stmt = pdo_query( $pdo, "SELECT DISTINCT * FROM repair_form WHERE id = :rma_id_is", $params);	
        $row = pdo_fetch_all( $stmt );
        $response['data'] = $row;
        $response['test'] = $params[":rma_id_is"];
        
        $stmt2 = pdo_query( $pdo,
		  		"SELECT *, to_char(date, 'MM/dd/yyyy HH24:MI:SS') as date_of_log FROM repair_status_log WHERE  repair_form_id = :repair_id ORDER BY date DESC",
		  		//"SELECT * FROM order_status_log WHERE  import_orders_id = :order_id ORDER BY date DESC LIMIT 1",
		  		array(":repair_id"=>$repair_id));	
		 $result2 = pdo_fetch_all($stmt2);
		 /*
		 $date11 = $result2[0]["date_of_log"];
		 for($j=0; $j<count($result2); $j++) {   
			 if($result2[$j]["repair_status_id"] == $result2[$j+1]["repair_status_id"]) {
				 $date11 = $result2[$j+1]["date_of_log"];
			 } else {
				 //break;
			 }
		}
		*/
		// COMMENTED OUT CODE ABOVE & ADDED CODE BELOW ON 02/12
		// THE CALCULATION WAS NOT WORKING CORRECTLY
		$date11 = $result2[0]["date_of_log"];
		 for($j=0; $j<count($result2); $j++) {   
			//if($result2[$j]["order_status_id"] == $result2[$j+1]["order_status_id"]) {
			if($result2[0]["repair_status_id"] == $result2[$j+1]["repair_status_id"]) {	 
				 //$date11 = $result2[$j+1]["date_of_log"];
				 $date11 = $result2[$j+1]["date_of_log"];
				 //break;
			 } else {
				 //break;
				 //$date11 = $result2[0]["date_of_log"];
			 }
		}



		$date1 = new DateTime($date11);
		$date2 = new DateTime('01/08/2019 12:11:11');
		$date_today = new DateTime('now');

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