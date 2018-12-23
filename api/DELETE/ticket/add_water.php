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
	$ticket['disposalwell'] = $_POST['disposal_well_id'];
	$ticket['barrelsdelivered'] = $_POST['barrels_delivered'];
	$ticket['watertype'] = $_POST['water_type_id'];
	$ticket['datedelivered'] = $_POST['date_delivered'];
	//$ticket['deliverymethod'] = $_POST['delivery_method'];
	//$ticket['watersourcetype'] = $_POST['water_source_type'];
	$ticket['sourcewell'] = $_POST['source_well_id'];
	$ticket['truckingcompanyid'] = $_POST['trucking_company_id'];
	$ticket['typeid'] = 3;
	$ticket['notes'] = $_POST['notes'];
	$ticket['barrelrate'] = $_POST['barrel_rate'];
	//$ticket['truckingcompany'] = $_POST['trucking_company'];
	
	$ticket['tank_id'] = $_POST['tank_id'];
	$ticket['fluidtypeid'] = $_POST['fluid_type_id'];
	
	if(empty($ticket['ticketnumber']) == true)
	{
		$response['message'] = 'Please input ticket number.';
		echo json_encode($response);
		exit;
	}
	$query="select current_operator_id from ticket_tracker_well where id=:id";
	$stmt=pdo_query($pdo,$query,array(":id"=>$ticket['sourcewell']));
	$row=pdo_fetch_array($stmt);
	$operator_id=$row[0];
	//$barrel_rate=GetBarrelRate($ticket['disposalwell'],$ticket['watertype'],$ticket['sourcewell'],$operator_id,$ticket['truckingcompany']);
	if(empty($barrel_rate))
	{
		$barrel_rate=GetDefaultRate($ticket['disposalwell'],$ticket['watertype']);				 
	}
	$stmt = pdo_query( $pdo, 
					   "INSERT INTO ticket_tracker_water 
					   (ticket_number, 
					   disposal_well_id, 
					   barrels_delivered, 
					   water_type_id, 
					   date_delivered, 


					   source_well_id, 
					   trucking_company_id, 
					   notes, 
					   date_created, 
					   created_by_id, 
					   barrel_rate, 

					   type_id, 
					   tank_id, 
					   fluid_type_id)
					   VALUES
					   (:ticketnumber, 
					   :disposalwell, 
					   :barrelsdelivered, 
					   :watertype, 
					   :datedelivered, 


					   :sourcewell, 
					   :truckingcompanyid, 
					   :notes, 
					   now(), 
					   :created_by_id, 
					   :barrelrate, 

					   :typeid, 
					   :tank_id, 
					   :fluid_type_id) 
					   RETURNING id",
					   array(':ticketnumber'=>$ticket['ticketnumber'], 
					   ':disposalwell'=>$ticket['disposalwell'], 
					   ':barrelsdelivered'=>$ticket['barrelsdelivered'], 
					   ':watertype'=>$ticket['watertype'], 
					   ":datedelivered"=>$ticket["datedelivered"], 


					   ':sourcewell'=>$ticket['sourcewell'], 
					   ':truckingcompanyid'=>$ticket['truckingcompanyid'], 
					   ':notes'=>$ticket['notes'],  
					   ':typeid'=>$ticket['typeid'], 
					   ":created_by_id"=>$_SESSION['UserId'], 
					   ":barrelrate"=>$ticket['barrelrate'], 

					   ":typeid"=>$ticket['typeid'], 
					   ":tank_id"=>$ticket['tank_id'], 
					   ":fluid_type_id"=>$ticket['fluidtypeid'])
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
    //$get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_from_id AND fluid_id = :fluid_type_id', $params);
    //$tank_info = pdo_fetch_array($get_tank_info);

	 // SUBTRACT FLUID fROM THE FLUID ALREADY IN THE TANK
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
		":movement_type"=>"Outgoing Water",
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