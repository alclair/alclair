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
	$ticket['ticketnumber'] = $_POST['ticket_number'];
	$ticket['datedelivered'] = $_POST['date_delivered'];
	//$ticket['ticketlocal'] = $_POST['local'];
	$ticket['sourcewell'] = $_POST['source_well_id'];
	$ticket['ticketproducer'] = $_POST['producer_name'];
	$ticket['truckingcompany'] = $_POST['trucking_company_id'];
	$ticket['barrelsdelivered'] = $_POST['barrels_delivered'];
	$ticket['fluidtypeid'] = $_POST['fluid_type_id'];
	$ticket['companymanname'] = $_POST['company_man_name'];
	$ticket['companymannumber'] = $_POST['company_man_number'];
	$ticket['drivername'] = $_POST['driver_name_id'];
	$ticket['percentsolid'] = $_POST['percent_solid'];
	$ticket['percenth2o'] = $_POST['percent_h2o'];
	$ticket['percentinterphase'] = $_POST['percent_interphase'];
	$ticket['percentoil'] = $_POST['percent_oil'];
	$ticket['afepo'] = $_POST['afepo'];
	$ticket['rig'] = $_POST['rig'];
	$ticket['microrens'] = $_POST['microrens'];

	$ticket['notes'] = $_POST['notes'];
	$ticket['washoutValue'] = $_POST['washoutValue'];
	$ticket['washoutbarrels'] = $_POST['washout_barrels'];
	$ticket['barrelrate'] = $_POST['barrel_rate'];
	$ticket['trucktype'] = $_POST['truck_type'];
	$ticket['h2s_exists'] = $_POST['h2s_exists'];
	
	$ticket['tank_id'] = $_POST['tank_id'];
	
	if(empty($ticket['ticketnumber']) == true)
	{
		$response['message'] = 'Please input ticket number.';
		echo json_encode($response);
		exit;
	}
		if(empty($ticket['fluidtypeid']) == true)
	{
		$response['message'] = 'Please pick a Fluid Type.';
		echo json_encode($response);
		exit;
	}
		if(empty($ticket['tank_id']) == true)
	{
		$response['message'] = 'Please pick a destination tank.';
		echo json_encode($response);
		exit;
	}
	if($ticket['percentsolid'] + $ticket['percenth2o'] + $ticket['percentinterphase'] + $ticket['percentoil']  != 100)
	{
		$response['message'] = 'Percentages do not equal 100.';
		echo json_encode($response);
		exit;
	}
	
	if($ticket['microrens'] > 0 ) {
		$ticket['tenorm_picocuries'] = ($ticket['microrens'] * 0.599); 
		}
	else { 
		$ticket['tenorm_picocuries'] = $_POST['tenorm_picocuries']; 
		}
		
	if($ticket['tenorm_picocuries'] >= 5) {
			$ticket['picocuriesValue'] = "true"; 
			}
	else {
			$ticket['picocuriesValue'] = "false"; 
			}
	
/*
	$query="select current_operator_id from ticket_tracker_well where id=:id";
	$stmt=pdo_query($pdo,$query,array(":id"=>$ticket['sourcewell']));
	$row=pdo_fetch_array($stmt);
	$operator_id=$row[0];
	$barrel_rate=GetBarrelRate($ticket['disposalwell'],$ticket['watertype'],$ticket['sourcewell'],$operator_id,$ticket['truckingcompany']);
	if(empty($barrel_rate))
	{
		$barrel_rate=GetDefaultRate($ticket['disposalwell'],$ticket['watertype']);				 
	}
*/
	$stmt = pdo_query( $pdo, 
					   "insert into ticket_tracker_ticket_trd
					   (ticket_number, barrels_delivered, date_delivered, date_created, local, notes, created_by_id, producer_name, trucking_company_id, source_well_id, fluid_type_id, company_man_name, company_man_number, driver_name, afepo, rig, percent_solid, percent_h2o, percent_interphase, percent_oil, washout, washout_barrels, picocuries, microrens, barrel_rate, tenorm_picocuries, truck_type, h2s_exists, tank_id)
					   values
					   (:ticket_number, :barrels_delivered, :date_delivered, now(), :local, :notes, :created_by_id, :producer_name, :trucking_company_id, :sourcewell, :fluid_type_id, :company_man_name, :company_man_number, :driver_name, :afepo, :rig, :percent_solid, :percent_h2o, :percent_interphase, :percent_oil, :washoutValue, :washout_barrels, :picocuriesValue, :microrens, :barrel_rate, :tenorm_picocuries, :truck_type, :h2s_exists, :tank_id) RETURNING id",
					   array(':ticket_number'=>$ticket['ticketnumber'], ':barrels_delivered'=>$ticket['barrelsdelivered'], ':date_delivered'=>$ticket['datedelivered'], ':local'=>$ticket['ticketlocal'], ':notes'=>$ticket['notes'], ':created_by_id'=>$_SESSION['UserId'], ':producer_name'=>$ticket['ticketproducer'], ':trucking_company_id'=>$ticket['truckingcompany'], ':sourcewell'=>$ticket['sourcewell'], ':fluid_type_id'=>$ticket['fluidtypeid'], ':company_man_name'=>$ticket['companymanname'], ':company_man_number'=>$ticket['companymannumber'], ':driver_name'=>$ticket['drivername'], ':afepo'=>$ticket['afepo'], ':rig'=>$ticket['rig'], ':percent_solid'=>$ticket['percentsolid'], ':percent_h2o'=>$ticket['percenth2o'], ':percent_interphase'=>$ticket['percentinterphase'], ':percent_oil'=>$ticket['percentoil'], ':washoutValue'=>$ticket['washoutValue'],':washout_barrels'=>$ticket['washoutbarrels'], ':picocuriesValue'=>$ticket['picocuriesValue'], ':microrens'=>$ticket['microrens'], ':barrel_rate'=>$ticket['barrelrate'], ':tenorm_picocuries'=>$ticket['tenorm_picocuries'], ':truck_type'=>$ticket['trucktype'], ':h2s_exists'=>$ticket['h2s_exists'], ':tank_id'=>$ticket['tank_id'])
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
	//echo json_encode($response);
	
	// SEE WHAT FLUIDS ARE IN TANK
	// IF FLUID EXISTS, INCREASE THE AMOUNT
	// IF FLUID DOES NOT EXIST, ADD THE FLUID INTO THE TANK
   	$params[":tank_to_id"] = $ticket["tank_id"];
   	$params[":fluid_type_id"] = $ticket["fluidtypeid"];
    $get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_to_id AND fluid_id = :fluid_type_id', $params);
    $tank_info = pdo_fetch_array($get_tank_info);
        

    // IF TANK DOES NOT CONTAIN THE FLUID
    if( empty($tank_info) )     
    {
	    // ADD ROW FOR TANK -> FLUID -> AMOUNT
	    $query = "INSERT INTO tank_tracker (tank_id,  fluid_id, fluid_amount)
	    				VALUES(:tank_to_id, :fluid_type_id, :fluid_amount)";
	    
	    $params=array(
			":tank_to_id"=>$ticket["tank_id"],
			":fluid_type_id"=>$ticket["fluidtypeid"],
			":fluid_amount"=>$ticket["barrelsdelivered"]);
					
		$stmt = pdo_query($pdo,$query,$params);
        $result2 = pdo_fetch_array($stmt);

	    $response["message2"] = "is empty";
    }
    else // FLUID EXISTS INSIDE THE TANK
    {
	    // ADD FLUID TO THE FLUID ALREADY IN THE TANK
	    $new_fluid_amount = $tank_info["fluid_amount"] + $ticket["barrelsdelivered"];
	    $query = "UPDATE tank_tracker SET fluid_amount = :new_fluid_amount WHERE id = :id";
	    
	    $params=array(
		    ":new_fluid_amount"=>$new_fluid_amount,
		    ":id"=>$tank_info["id"]);
		    
		 $stmt = pdo_query( $pdo, $query, $params );
		 $result2 = pdo_fetch_array($stmt);

	    $response["message2"] = "is not empty";
	    $response["message3"] = $result;
    }
	
     $response['code2'] = 'success';
     
     ///////////////////////////////////////////////////  SIMILAR FOR ALL TANK MOVEMENTS ///////////////////////////////////////////////////   
	// SEE WHAT FLUIDS ARE IN TANK
	// NEED TO KNOW THE AMOUNT OF FLUID THAT EXISTS
   	$params[":tank_from_id"] = $ticket["tank_id"];
   	$params[":fluid_type_id"] = $ticket["fluidtypeid"];
    $get_tank_info = pdo_query($pdo, 'SELECT * FROM tank_tracker WHERE tank_id = :tank_from_id AND fluid_id = :fluid_type_id', $params);
    $tank_info = pdo_fetch_array($get_tank_info);

	 // SUBTRACT FLUID fROM THE FLUID ALREADY IN THE TANK
	 $new_fluid_amount = $tank_info["fluid_amount"] - $ticket["barrelsdelivered"];
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
		":movement_type"=>"Incoming",
		":ticket_number"=>$ticket['ticketnumber'],
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