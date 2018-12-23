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

$response2 = array();
$response2["message"] = "";

try
{

	$ticket = array();
	$params = array();

	$ticket['barrels'] = $_POST['barrels'];
	$ticket['fluidtypeid'] = $_POST['fluid_type_id'];
	$ticket['tank_from_id'] = $_POST['tank_from_id'];
	$ticket['tank_to_id'] = $_POST['tank_to_id'];
	$ticket['notes'] = $_POST['notes'];
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	// FIND INFORMATION FOR THE FROM TANK
	// UPDATE TANK_TRACKER
   	$params[":tank_from_id"] = $ticket["tank_from_id"];   	
   	$params[":fluidtypeid"] = $ticket["fluidtypeid"];
    $get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_from_id AND fluid_id = :fluidtypeid', $params);
    $tank_info_from = pdo_fetch_array($get_tank_info);
    
    $new_fluid_amount_from = $tank_info_from["fluid_amount"] - $ticket["barrels"];
	
	$query =  "UPDATE tank_tracker SET fluid_amount = :new_fluid_amount_from WHERE id = :id";
	$params=array(
		":new_fluid_amount_from"=>$new_fluid_amount_from,
		":id"=>$tank_info_from["id"]);
	$stmt = pdo_query( $pdo, $query, $params );
	$tank_info = pdo_fetch_array($stmt);

	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	// FIND INFORMATION FOR THE TO TANK
	// UPDATE TANK_TRACKER
	$params2[":tank_to_id"] = $ticket["tank_to_id"];
	$params2[":fluidtypeid"] = $ticket["fluidtypeid"];
	$get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_to_id AND fluid_id = :fluidtypeid', $params2);
    $tank_info_to = pdo_fetch_array($get_tank_info);
    // IF TANK DOES NOT CONTAIN THE FLUID
    $response["from"] = $ticket["tank_to_id"];
    $response["from2"] = $tank_info_to;
    
    if( empty($tank_info_to) )     
    {
	    // THE NEXT TWO LINES ARE FOR THE TABLE "tank_movement_tracker"
	    $tank_info_to["fluid_amount"] = 0;
	    $new_fluid_amount_to = $ticket["barrels"];
	    // ADD ROW FOR TANK -> FLUID -> AMOUNT
	    $query = "INSERT INTO tank_tracker (
	    					tank_id, 
	    					fluid_id, 
	    					fluid_amount)
	    				VALUES(
	    					:tank_to_id,
	    					:fluid_type_id,
	    					:fluid_amount)";
	    
	    $params2=array(
			":tank_to_id"=>$ticket["tank_to_id"],
			":fluid_type_id"=>$ticket["fluidtypeid"],
			":fluid_amount"=>$ticket["barrels"]);
					
		$stmt = pdo_query($pdo,$query,$params2);
        $result = pdo_fetch_array($stmt);

		$tank_to_num_barrels = 0;
	    $response["message2"] = "is empty";
	    $response['code'] = 'success';
    }
    else // FLUID EXISTS INSIDE THE TANK
    {
	    // ADD FLUID TO THE FLUID ALREADY IN THE TANK
	    $new_fluid_amount_to = $tank_info_to["fluid_amount"] + $ticket["barrels"];
	    $query = "UPDATE tank_tracker SET fluid_amount = :new_fluid_amount WHERE id = :id";
	     $response["from2"] = $tank_info_to["id"];
	    $params2=array(
		    ":new_fluid_amount"=>$new_fluid_amount_to,
		    ":id"=>$tank_info_to["id"]);
		    
		 $stmt = pdo_query( $pdo, $query, $params2);
		 $result = pdo_fetch_array($stmt);

		$tank_to_num_barrels = $tank_info["fluid_amount"];
	    $response["message2"] = "is not empty";
	    $response["message3"] = $result;
	    
	    $response['code'] = 'success';
    }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////    
	
    ///////////////////////////////////////////////////  SIMILAR FOR ALL TANK MOVEMENTS ///////////////////////////////////////////////////   
	// SEE WHAT FLUIDS ARE IN TANK
	// NEED TO KNOW THE AMOUNT OF FLUID THAT EXISTS
   	//$params[":tank_from_id"] = $ticket["tank_id"];
   	//$params[":fluid_type_id"] = $ticket["fluidtypeid"];
    //$get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_movement_tracker WHERE tank_from_id = :tank_from_id AND fluid_type_id = :fluid_type_id', $params);
    //$tank_info = pdo_fetch_array($get_tank_info);

	 // SUBTRACT FLUID fROM THE FLUID ALREADY IN THE TANK
	 $new_fluid_amount = $tank_info["fluid_amount"] - $ticket["barrelsdelivered"];
	 $query = "INSERT INTO tank_movement_tracker (
	 						movement_type, 
	 						ticket_number,
	 						tank_from_id, 
	 						tank_to_id,
	 						fluid_type_id, 
	 						starting_barrels_from, 
	 						ending_barrels_from, 
	 						starting_barrels_to, 
	 						ending_barrels_to, 
	 						date, 
	 						created_by_id, 
	 						barrels_delivered,
	 						notes)
	    				VALUES(
	    					:movement_type, 
	    					:ticket_number, 
	    					:tank_from_id, 
	    					:tank_to_id,
	    					:fluid_type_id, 
	    					:starting_barrels_from, 
	    					:ending_barrels_from, 
	    					:starting_barrels_to, 
	    					:ending_barrels_to, 	
	    					now(), 
	    					:created_by_id, 
	    					:barrels_delivered.
	    					:notes)";
	    
	$params4=array(
		":movement_type"=>"Transfer",
		":ticket_number"=>"N/A",
		":tank_from_id"=>$ticket["tank_from_id"],
		":tank_to_id"=>$ticket["tank_to_id"],
		":fluid_type_id"=>$ticket["fluidtypeid"],
		":starting_barrels_from"=>$tank_info_from["fluid_amount"],
		":ending_barrels_from"=>$new_fluid_amount_from,
		":starting_barrels_to"=>$tank_info_to["fluid_amount"],
		":ending_barrels_to"=>$new_fluid_amount_to,
		":created_by_id"=>$_SESSION['UserId'],
		":barrels_delivered"=>$ticket["barrels"],
		":notes"=>$ticket["notes"]
		);
		    
	$stmt = pdo_query( $pdo, $query, $params4 );
	$result2 = pdo_fetch_array($stmt);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     
     $response['data'] = $result;
    echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>