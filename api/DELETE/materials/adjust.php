<?php
include_once "../../config.inc.php";
$response['testing3']=$_POST['qtyAdjusting'];
if(empty($_SESSION["UserId"]))
{
	$response['code'] = 'not good';
	echo json_encode($response);
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{

	$material = array();
	$material['name'] = $_POST['name'];
	$material['startingQty'] = $_POST['startingQty'];
	$material['startingPrice'] = $_POST['startingPrice'];
	$material['units'] = $_POST['units'];
	$material['qtyAdjusting'] = $_POST["qtyAdjusting"];

	 $ending_qty = $_POST['startingQty'] + $_POST["qtyAdjusting"];
	
if($_POST['AddorSubtract']=='Add' ) {
	 $ending_qty = $_POST['startingQty'] + $_POST["qtyAdjusting"];
	 
	 $stmt = pdo_query($pdo, "INSERT INTO materials_tracker (material_id, added, subtracted, ending_qty, reason, unit_cost, invoice_number, invoice_date,  notes, entered_by, time_stamp)
												VALUES (:material_id, :added, :subtracted, :ending_qty, :reason, :unit_cost, :invoice_number, :invoice_date, :notes, :entered_by, now())",
												array(
													':material_id'=>$_POST['id'],
													':added'=>$_POST['qtyAdjusting'],
													':subtracted'=>NULL,
													':ending_qty'=>$ending_qty, 
													':reason'=>'Inventory Adjustment', 
													':unit_cost'=>NULL, 
													':invoice_number'=>NULL, 
													':invoice_date'=>NULL, 
													':notes'=>$_POST['notes'], 
													':entered_by'=>$_SESSION['UserId'], 
												)
										);
										
		
} elseif($_POST['AddorSubtract']=='Subtract' ) {
	 $ending_qty = $_POST['startingQty'] - $_POST["qtyAdjusting"];
 
	 $stmt = pdo_query($pdo, "INSERT INTO materials_tracker (material_id, added, subtracted, ending_qty, reason, unit_cost, invoice_number, invoice_date,  notes, entered_by, time_stamp, active, what_is_left)
												VALUES (:material_id, :added, :subtracted, :ending_qty, :reason, :unit_cost, :invoice_number, :invoice_date, :notes, :entered_by, now(), :active, :what_is_left)",
												array(
													':material_id'=>$_POST['id'],
													':added'=>NULL,
													':subtracted'=>$_POST['qtyAdjusting'],
													':ending_qty'=>$ending_qty, 
													':reason'=>'Inventory Adjustment', 
													':unit_cost'=>NULL, 
													':invoice_number'=>NULL, 
													':invoice_date'=>NULL, 
													':notes'=>$_POST['notes'], 
													':entered_by'=>$_SESSION['UserId'], 
													':active'=>NULL,
													':what_is_left'=>NULL
												)
										);
}

	$query =  "SELECT count(*) AS number_of_rows FROM materials_tracker WHERE reason = 'Purchase' AND material_id = :id AND active = TRUE";
	$stmt = pdo_query($pdo, $query, array(':id'=>$material["id"]) );
	$result3 = pdo_fetch_array( $stmt );  // GET NUMBER OF ROWS - THAT IS IT

	$query =  "SELECT * FROM materials_tracker WHERE reason = 'Purchase' AND material_id = :id AND active = TRUE ORDER BY id";
	$stmt = pdo_query($pdo, $query, array(':id'=>$_POST["id"]) );
	$result2 = pdo_fetch_all( $stmt ); 
	$count = 0;

$remainder = $material["qtyAdjusting"];
if($_POST['AddorSubtract']=='Add' ) {
	$remainder = $result2[0]['what_is_left'] + $remainder;
	$params[':remainder'] = $remainder;
	$params[':result2_id'] = $result2[0]['id'];
	$query = "UPDATE materials_tracker SET what_is_left = :remainder WHERE id = :result2_id";
	$stmt = pdo_query($pdo, $query, $params);		
	
	$params[':price'] = $result2[0]['unit_cost'];
	$params[':result2_id'] = $result2[0]['material_id'];
	$query = "UPDATE materials SET current_qty = :remainder, current_price = :price WHERE id = :result2_id";
	$stmt = pdo_query($pdo, $query, $params);		
	
} elseif($_POST['AddorSubtract']=='Subtract' ) {
	for ($x = 0; $x <= count($result2); $x++) {
		if(abs($remainder) >= $result2[$x]['what_is_left']) {
			$remainder = $result2[$x]['what_is_left'] - abs($remainder);
			$params[':result2_id'] = $result2[$x]['id'];
			$query = "UPDATE materials_tracker SET active = FALSE, what_is_left = 0 WHERE id = :result2_id";
			$stmt = pdo_query($pdo, $query, $params);		
			
			$params[':price'] = $result2[$x]['unit_cost'];
			$params[':remainder'] = abs($remainder);
			$query = "UPDATE materials SET current_qty = :remainder, current_price = :price WHERE id = :result2_id";
			$stmt = pdo_query($pdo, $query, $params);	
		}
		else {
			//if($remainder < 0)
			//	$remainder = $result2[$x]['what_is_left'] + $remainder; // ADD BECAUSE REMAINDER BECOMES NEGATIVE BECAUSE OF IF STATEMENT
			//else
				$remainder = $result2[$x]['what_is_left'] - abs($remainder); 
			$params[':result2_id'] = $result2[$x]['id'];
			$params[':remainder'] = abs($remainder);
			$query = "UPDATE materials_tracker SET what_is_left = :remainder WHERE id = :result2_id";
			$stmt = pdo_query($pdo, $query, $params);		
			
			$params[':price'] = $result2[$x]['unit_cost'];
			$params[':result2_id'] = $result2[$x]['material_id'];
			$query = "UPDATE materials SET current_qty = :remainder, current_price = :price WHERE id = :result2_id";
			$stmt = pdo_query($pdo, $query, $params);		
			break;
		}
	} 
}


	$result = pdo_fetch_array($stmt);
	$response['code'] = 'success';
	$response['data'] = $result; 
								
					 
$stmt = pdo_query($pdo, "UPDATE materials SET current_qty = :ending_qty WHERE id = :id", array(':ending_qty'=>$ending_qty, ':id'=>$_POST['id']));

echo json_encode($response);		
	/*if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}*/
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>