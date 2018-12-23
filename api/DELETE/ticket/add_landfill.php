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

	$ticket = array();
	$ticket['test_report_number'] = $_POST['ticket_number'];
	$ticket['test_date'] = $_POST['date_delivered'];
	$ticket['radium_226'] = $_POST['radium_226'];
	$ticket['radium_228'] = $_POST['radium_228'];
	$ticket['trucking_company_id'] = $_POST['trucking_company_id'];
	$ticket['landfill_disposal_site'] = $_POST['landfill_disposal_site'];
	$ticket['tank_id'] = $_POST['tank_id'];
	$ticket['fluid_type_id'] = $_POST['fluid_type_id'];
	$ticket['barrels_delivered'] = $_POST['barrels_delivered'];
	$ticket['landfill_ticket_number'] = $_POST['landfill_ticket_number'];		
	$ticket['tare_weight'] = $_POST['tare_weight'];
	$ticket['loaded_weight'] = $_POST['loaded_weight'];
	$ticket['bill_lading_number'] = $_POST['bill_lading_number'];
	$ticket['ship_date'] = $_POST['ship_date'];
	
	$ticket['typeid'] = 1;
	$ticket['notes'] = $_POST['notes'];
	
	$ticket['landfill_disposal_site_id'] = $_POST['landfill_disposal_site'];
	$_id = $ticket['landfill_disposal_site_id'];
	//echo $ticket['landfill_disposal_site_id'];
	
    $query_fees =   "SELECT  t2.name as landfill_site, t2.freight_fee as freight_fee, t2.tipping_fee as tipping_fee
                				FROM landfill_disposal_sites as t2
								WHERE  t2.id = $_id";  
	
  	$params_fees = null;
    $stmt_fees = pdo_query( $pdo, $query_fees, $params_fees); 
    $result_fees = pdo_fetch_all( $stmt_fees ); 

	$ticket['tons'] = ($ticket['loaded_weight'] - $ticket['tare_weight']) / 2000;
	$ticket['fees'] = ($result_fees[0]['freight_fee'] + $result_fees[0]['tipping_fee']);
	$ticket['total_dollars'] = $ticket['fees'] * $ticket['tons'];
	
	if(empty($ticket['test_report_number']) == true)
	{
		$response['message'] = 'Please input a test report #.';
		echo json_encode($response);
		exit;
	}
	if(empty($ticket['radium_226']) == true)
	{
		$response['message'] = 'Please input the radium 226 value.';
		echo json_encode($response);
		exit;
	}
	if(empty($ticket['radium_228']) == true)
	{
		$response['message'] = 'Please input the radium 228 value.';
		echo json_encode($response);
		exit;
	}

		$stmt = pdo_query( $pdo, 
					   "INSERT INTO ticket_tracker_landfill
					   (ticket_number, 
					   date_delivered, 
					   radium_226, 
					   radium_228,
					   bill_lading_number,
					   trucking_company_id, 
					   landfill_disposal_site, 
					   tank_id, 
					   fluid_type_id, 
					   barrels_delivered, 
					   landfill_ticket_number, 
					   tare_weight, 
					   loaded_weight,  
					   notes, 
					   date_created, 
					   created_by_id, 
					   tons, 
					   total_dollars, 
					   ship_date,
					   type_id)
					   VALUES
					   (:test_report_number,
					   :test_date,
					   :radium_226, 
					   :radium_228, 
					   :bill_lading_number,
					   :trucking_company_id, 
					   :landfill_disposal_site,
					   :tank_id, 
					   :fluid_type_id, 
					   :barrels_delivered, 
					   :landfill_ticket_number, 
					   :tare_weight, 
					   :loaded_weight, 
					   :notes, 
					   now(),
					   :created_by_id,
					   :tons,
					   :total_dollars, 
					   :ship_date,
					   :typeid) RETURNING id",
					   array(':test_report_number'=>$ticket['test_report_number'], ':test_date'=>$ticket["test_date"], ':radium_226'=>$ticket['radium_226'], ':radium_228'=>$ticket['radium_228'],
					   ':bill_lading_number'=>$ticket['bill_lading_number'], ':trucking_company_id'=>$ticket['trucking_company_id'],  ':landfill_disposal_site'=>$ticket['landfill_disposal_site'], 						':tank_id'=>$ticket['tank_id'], ':fluid_type_id'=>$ticket['fluid_type_id'], ':barrels_delivered'=>$ticket['barrels_delivered'], 						':landfill_ticket_number'=>$ticket['landfill_ticket_number'], ':tare_weight'=>$ticket['tare_weight'],':loaded_weight'=>$ticket['loaded_weight'], ':notes'=>$ticket['notes'], 						":created_by_id"=>$_SESSION['UserId'], ":tons"=>$ticket['tons'], ":total_dollars"=>$ticket['total_dollars'], ":ship_date"=>$ticket['ship_date'], ":typeid"=>$ticket['typeid'])
						//,1
					 );					 					 

	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}

    $result = pdo_fetch_array($stmt);
	$response['code'] = 'success';
	$response['data'] = $result; 

///////////////////////////////////////////////////  SAME FOR ALL OUTGOING TICKETS  ///////////////////////////////////////////////////   
	// SEE WHAT FLUIDS ARE IN TANK
	// FLUID WILL EXIST THUS DECREASE  THE AMOUNT
   	$params[":tank_from_id"] = $ticket["tank_id"];
   	$params[":fluid_type_id"] = $ticket["fluid_type_id"];
    $get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_from_id AND fluid_id = :fluid_type_id', $params);
    $tank_info = pdo_fetch_array($get_tank_info);
        
   	 // IF TANK DOES NOT CONTAIN THE FLUID
    // ADD FLUID TO THE FLUID ALREADY IN THE TANK
	$new_fluid_amount = $tank_info["fluid_amount"] - $ticket["barrels_delivered"];
	$query = "UPDATE tank_tracker SET fluid_amount = :new_fluid_amount WHERE id = :id";
	$params=array(
		":new_fluid_amount"=>$new_fluid_amount,
		":id"=>$tank_info["id"]);
	 $stmt = pdo_query( $pdo, $query, $params );
	 $result2 = pdo_fetch_array($stmt);
	 $response["from"]="woohoo";
	 $response["from2"]=$tank_info;	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////  SIMILAR FOR ALL TANK MOVEMENTS ///////////////////////////////////////////////////   
	// SEE WHAT FLUIDS ARE IN TANK
	// NEED TO KNOW THE AMOUNT OF FLUID THAT EXISTS
   	//$params[":tank_from_id"] = $ticket["tank_id"];
   	//$params[":fluid_type_id"] = $ticket["fluidtypeid"];
    //$get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_movement_tracker WHERE tank_id = :tank_from_id AND fluid_id = :fluid_type_id', $params);
    //$tank_info = pdo_fetch_array($get_tank_info);

	 // SUBTRACT FLUID fROM THE FLUID ALREADY IN THE TANK
	 //$new_fluid_amount = $tank_info["fluid_amount"] - $ticket["barrelsdelivered"];
	 $query = "INSERT INTO tank_movement_tracker (
	 					movement_type, 
	 					ticket_number,
	 					tank_from_id, 
	 					fluid_type_id, 
	 					starting_barrels_from, 
	 					ending_barrels_from, 
	 					date, 
	 					created_by_id, 
	 					barrels_delivered)
	    				VALUES(:movement_type, :ticket_number, :tank_from_id, :fluid_type_id, :starting_barrels_from, :ending_barrels_from, now(), :created_by_id, :barrels_delivered)";
	    
	$params=array(
		":movement_type"=>"Outgoing Landfill",
		"ticket_number"=>$ticket['test_report_number'],
		":tank_from_id"=>$ticket["tank_id"],
		":fluid_type_id"=>$ticket["fluid_type_id"],
		":starting_barrels_from"=>$tank_info["fluid_amount"],
		":ending_barrels_from"=>$new_fluid_amount,
		":created_by_id"=>$_SESSION['UserId'],
		":barrels_delivered"=>$ticket["barrelsdelivered"]
		);
		    
	$stmt = pdo_query( $pdo, $query, $params );
	$result2 = pdo_fetch_array($stmt);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$response["message2"] = "is not empty";
	$response["message3"] = $result2;

	echo json_encode($response);
	
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>