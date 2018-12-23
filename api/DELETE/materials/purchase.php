<?php
include_once "../../config.inc.php";
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

$purchase= array();
$purchase['invoice_date'] = $_POST['invoice_date'];
$purchase['invoice_date'] = $_REQUEST['invoice_date'];

try
{
	
	$query = "SELECT * FROM materials_tracker WHERE active = TRUE AND material_id = :material_id";
	$params[":material_id"] = $_POST['id'];
	$stmt = pdo_query($pdo, $query, $params);
	$rowcount = pdo_rows_affected( $stmt );
	if($rowcount < 1) {
		$stmt = pdo_query($pdo, "UPDATE materials SET current_qty = :ending_qty, current_price = :current_price WHERE id = :id", array(':ending_qty'=>$ending_qty, ':current_price'=>$_POST['qtyAdjusting'], ':id'=>$_POST['id']));
	}

	$ending_qty = $_POST['startingQty'] + $_POST["qtyAdjusting"];
	
	$stmt = pdo_query($pdo, "INSERT INTO materials_tracker (material_id, added, subtracted, ending_qty, reason, unit_cost, invoice_number, invoice_date, notes, entered_by, time_stamp, active, what_is_left)
												VALUES (:material_id, :added, :subtracted, :ending_qty, :reason, :unit_cost, :invoice_number, :invoice_date, :notes, :entered_by, now(), :active, :what_is_left ) RETURNING id",
												array(
													':material_id'=>$_POST['id'],
													':added'=>$_POST['qtyAdjusting'],
													':subtracted'=>NULL,
													':ending_qty'=>$ending_qty, 
													':reason'=>'Purchase', 
													':unit_cost'=>$_POST['unitCost'], 
													':invoice_number'=>$_POST['invoice_number'], 
													':invoice_date'=>$purchase['invoice_date'],
													':notes'=>$_POST['notes'], 
													':entered_by'=>$_SESSION['UserId'],
													':active'=>TRUE,
													':what_is_left'=>$_POST['qtyAdjusting']
												)
											);
    $result = pdo_fetch_array($stmt);
	$response['testing3']=$_POST['invoice_date'];
		
	$response['code'] = 'success';
	$response['data'] = $result; 
								
						 
	$stmt = pdo_query($pdo, "UPDATE materials SET current_qty = :ending_qty WHERE id = :id", array(':ending_qty'=>$ending_qty, ':id'=>$_POST['id']));

	echo json_encode($response);		
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>