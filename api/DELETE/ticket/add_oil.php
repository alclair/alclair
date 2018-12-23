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
	$ticket['ticketnumber'] = $_POST['ticket_number'];
	$ticket['datedelivered'] = $_POST['date_delivered'];
	$ticket['barrelsdelivered'] = $_POST['barrels_delivered'];
	$ticket['top_temperature'] = $_POST['top_temperature'];
	$ticket['bottom_temperature'] = $_POST['bottom_temperature'];	
	$ticket['observed_temperature'] = $_POST['observed_temperature'];	
	$ticket['bsw'] = $_POST['bsw'];
	$ticket['gravity'] = $_POST['gravity'];
	$ticket['oilprice'] = $_POST['oil_price'];
	$ticket['typeid'] = 2;
	$ticket['notes'] = $_POST['notes'];

	$ticket['top_ft'] = $_POST['top_ft'];	
	$ticket['top_in'] = $_POST['top_in'];
	$ticket['top_decimal'] = $_POST['top_decimal'];
	$ticket['bottom_ft'] = $_POST['bottom_ft'];	
	$ticket['bottom_in'] = $_POST['bottom_in'];
	$ticket['bottom_decimal'] = $_POST['bottom_decimal'];
	
	$ticket['tank_id'] = $_POST['tank_id'];
	$ticket['deduct'] = $_POST['deduct'];
	
	$ticket['fluidtypeid'] = $_POST['fluid_type_id'];

	$ticket['total_dollars'] = $ticket['barrelsdelivered'] * ($ticket['oilprice'] - $ticket['deduct']);
	
	if(empty($ticket['ticketnumber']) == true)
	{
		$response['message'] = 'Please input ticket number.';
		echo json_encode($response);
		exit;
	}
	
		$stmt = pdo_query( $pdo, 
					   "INSERT INTO ticket_tracker_oil
					   (ticket_number,date_delivered,barrels_delivered,top_temperature,bottom_temperature, observed_temperature, bsw,gravity,oil_price, notes,date_created,created_by_id,total_dollars, type_id, top_ft, top_in, top_decimal, bottom_ft, bottom_in, bottom_decimal, tank_id, deduct, fluid_type_id)
					   VALUES
					   (:ticketnumber,:datedelivered,:barrelsdelivered,:top_temperature,:bottom_temperature, :observed_temperature,:bsw,:gravity,:oilprice,:notes,now(),:created_by_id,:total_dollars, :typeid, :top_ft, :top_in, :top_decimal, :bottom_ft, :bottom_in, :bottom_decimal, :tank_id, :deduct, :fluidtypeid) RETURNING id",
					   array(':ticketnumber'=>$ticket['ticketnumber'],':datedelivered'=>$ticket["datedelivered"],':barrelsdelivered'=>$ticket['barrelsdelivered'],':top_temperature'=>	$ticket['top_temperature'],':bottom_temperature'=>$ticket['bottom_temperature'],':observed_temperature'=>$ticket['observed_temperature'],':bsw'=>$ticket['bsw'],':gravity'=>$ticket['gravity'],':oilprice'=>$ticket['oilprice'],':notes'=>$ticket['notes'],":created_by_id"=>$_SESSION['UserId'],":total_dollars"=>$ticket['total_dollars'], ":typeid"=>$ticket['typeid'], ':top_ft'=>	$ticket['top_ft'], ':top_in'=>	$ticket['top_in'], ':top_decimal'=>	$ticket['top_decimal'], ':bottom_ft'=>	$ticket['bottom_ft'], ':bottom_in'=>	$ticket['bottom_in'], ':bottom_decimal'=>	$ticket['bottom_decimal'], ':tank_id'=>	$ticket['tank_id'],':deduct'=>	$ticket['deduct'],':fluidtypeid'=>$ticket['fluidtypeid'])		
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
   	$params[":fluid_type_id"] = $ticket["fluidtypeid"];
    $get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_from_id AND fluid_id = :fluid_type_id', $params);
    $tank_info = pdo_fetch_array($get_tank_info);
        
   	 // IF TANK DOES NOT CONTAIN THE FLUID
    // ADD FLUID TO THE FLUID ALREADY IN THE TANK
	$new_fluid_amount = $tank_info["fluid_amount"] - $ticket["barrelsdelivered"];
	$query = "UPDATE tank_tracker SET fluid_amount = :new_fluid_amount WHERE id = :id";
	$params=array(
		":new_fluid_amount"=>$new_fluid_amount,
		":id"=>$tank_info["id"]);
	 $stmt = pdo_query( $pdo, $query, $params );
	 $result2 = pdo_fetch_array($stmt);
 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////  SIMILAR FOR ALL TANK MOVEMENTS ///////////////////////////////////////////////////   
	// SEE WHAT FLUIDS ARE IN TANK
	// NEED TO KNOW THE AMOUNT OF FLUID THAT EXISTS
   	//$params[":tank_from_id"] = $ticket["tank_id"];
   	//$params[":fluid_type_id"] = $ticket["fluidtypeid"];
    ///$get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_from_id AND fluid_id = :fluid_type_id', $params);
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
		":movement_type"=>"Outgoing Oil",
		"ticket_number"=>$ticket['ticketnumber'],
		":tank_from_id"=>$ticket["tank_id"],
		":fluid_type_id"=>$ticket["fluidtypeid"],
		":starting_barrels_from"=>$tank_info["fluid_amount"],
		":ending_barrels_from"=>$new_fluid_amount,
		":created_by_id"=>$_SESSION['UserId'],
		":barrels_delivered"=>$ticket["barrelsdelivered"]
		);
		    
	$stmt = pdo_query( $pdo, $query, $params );
	$result2 = pdo_fetch_array($stmt);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	echo json_encode($response);

}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>