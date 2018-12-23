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
	$ticket['tank_to_id'] = $_POST['tank_to_id2'];
	$ticket['AddorSubtract'] = $_POST['AddorSubtract'];
	$ticket['notes'] = $_POST['notes'];
	
	$response['a']=$_POST['barrels'];
	$response['b']=$_POST['fluid_type_id'];
	$response['c']=$_POST['tank_to_id2'];
	$response['d']=$_POST['AddorSubtract'];
	// SEE WHAT FLUIDS ARE IN TANK
	// IF FLUID EXISTS, INCREASE THE AMOUNT
	// IF FLUID DOES NOT EXIST, ADD THE FLUID INTO THE TANK
   	$params[":tank_to_id"] = $ticket["tank_to_id"];   	
   	$params[":fluidtypeid"] = $ticket["fluidtypeid"];

	$get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_to_id AND fluid_id = :fluidtypeid', $params);
    $tank_info = pdo_fetch_array($get_tank_info);
    // IF TANK DOES NOT CONTAIN THE FLUID
    $response["message2"]=empty($tank_info);
    
    if( empty($tank_info) )     
    {
	    // ADD ROW FOR TANK -> FLUID -> AMOUNT
	    $query = "INSERT INTO tank_tracker (tank_id,  fluid_id, fluid_amount)
	    				VALUES(:tank_to_id, :fluid_type_id, :fluid_amount)";
	    
	    $params2=array(
			":tank_to_id"=>$ticket["tank_to_id"],
			":fluid_type_id"=>$ticket["fluidtypeid"],
			":fluid_amount"=>$ticket["barrels"]);
					
		$stmt = pdo_query($pdo,$query,$params2);
        $result = pdo_fetch_array($stmt);

	    //$response["message2"] = "is empty";
	    $tank_info["fluid_amount"] = 0;
	    $new_fluid_amount = $ticket["barrels"];
	    $response['code'] = 'success';
    }
    else // FLUID EXISTS INSIDE THE TANK
    {
	    // ADD FLUID TO THE FLUID ALREADY IN THE TANK
	    if($ticket['AddorSubtract'] == 'Add')
		    $new_fluid_amount = $tank_info["fluid_amount"] + $ticket["barrels"];
		 else
		 	$new_fluid_amount = $tank_info["fluid_amount"] - $ticket["barrels"];
		 	
	    $query = "UPDATE tank_tracker SET fluid_amount = :new_fluid_amount2 WHERE id = :id";
	    
	    $params2=array(
		    ":new_fluid_amount2"=>$new_fluid_amount,
		    ":id"=>$tank_info["id"]);
		    
		 pdo_query( $pdo, $query, $params2);
		 $result = pdo_fetch_array($stmt);

	    $response["message2"] = "is not empty";
	    $response["message3"] = $result;
	    
	    $response['code'] = 'success';
    }
    
    ///////////////////////////////////////////////////  SIMILAR FOR ALL TANK MOVEMENTS ///////////////////////////////////////////////////   
	// SEE WHAT FLUIDS ARE IN TANK
	// NEED TO KNOW THE AMOUNT OF FLUID THAT EXISTS
   	//$params[":tank_from_id"] = $ticket["tank_id"];
   	//$params[":fluid_type_id"] = $ticket["fluidtypeid"];
    //$get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_from_id AND fluid_id = :fluid_type_id', $params);
    //$tank_info = pdo_fetch_array($get_tank_info);

	 $query = "INSERT INTO tank_movement_tracker (
	 					movement_type, 
	 					ticket_number,
	 					tank_to_id, 
	 					fluid_type_id, 
	 					starting_barrels_to, 
	 					ending_barrels_to, 
	 					date, 
	 					created_by_id, 
	 					barrels_delivered,
	 					notes)
	    				VALUES(:movement_type, :ticket_number, :tank_to_id, :fluid_type_id, :starting_barrels_to, :ending_barrels_to, now(), :created_by_id, :barrels_delivered, :notes)";
	    
	$params=array(
		":movement_type"=>"Adjustment",
		":ticket_number"=>"N/A",
		":tank_to_id"=>$ticket["tank_to_id"],
		":fluid_type_id"=>$ticket["fluidtypeid"],
		":starting_barrels_to"=>$tank_info["fluid_amount"],
		":ending_barrels_to"=>$new_fluid_amount,
		":created_by_id"=>$_SESSION['UserId'],
		":barrels_delivered"=>$ticket["barrels"],
		":notes"=>$ticket["notes"]
		);
		    
	$stmt = pdo_query( $pdo, $query, $params );
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