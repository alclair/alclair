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
    //$_POST=json_decode('{"id":20,"ticket_number":"123456","barrels_delivered":0,"date_delivered":"2015-10-30","date_created":"10/30/2015","delivery_method":"0","water_source_type":"0","notes":"18.27","created_by_id":1,"disposal_well_id":0,"source_well_id":0,"trucking_company_id":"36","water_type_id":0,"water_type_name":null,"disposal_well_name":null,"company_name":null,"Id":20}',true);
    $ticket = array();
    $ticket['Id'] = $_POST['Id'];
	$ticket['ticket_number'] = $_POST['ticket_number'];
	$ticket['date_delivered'] = $_POST['date_delivered'];
	$ticket['barrels_delivered'] = $_POST['barrels_delivered'];
	$ticket['top_temperature'] = $_POST['top_temperature'];
	$ticket['bottom_temperature'] = $_POST['bottom_temperature'];
	$ticket['observed_temperature'] = $_POST['observed_temperature'];	
	$ticket['bsw'] = $_POST['bsw'];
	$ticket['gravity'] = $_POST['gravity'];
	$ticket['oil_price'] = $_POST['oil_price'];
	$ticket['notes'] = $_POST['notes'];

	$ticket['top_ft'] = $_POST['top_ft'];	
	$ticket['top_in'] = $_POST['top_in'];
	$ticket['top_decimal'] = $_POST['top_decimal'];
	$ticket['bottom_ft'] = $_POST['bottom_ft'];	
	$ticket['bottom_in'] = $_POST['bottom_in'];
	$ticket['bottom_decimal'] = $_POST['bottom_decimal'];
	
	$ticket['tank_id'] = $_POST['tank_id'];
	$ticket['fluid_type_id'] = $_POST['fluid_type_id'];
	
	//$ticket['tank_number'] = $_POST['tank_number'];
	$ticket['deduct'] = $_POST['deduct'];

	$ticket['total_dollars'] = $ticket['barrels_delivered'] * ($ticket['oil_price'] - $ticket['deduct']);
				
						 
	if($_SESSION['IsAdmin'] == 0) {
	$stmt = pdo_query( $pdo, 
					   'update ticket_tracker_oil set 
					   notes = :notes, 
					   ticket_number = :ticket_number,
                       barrels_delivered = :barrels_delivered, 
                       top_temperature = :top_temperature,
                       bottom_temperature = :bottom_temperature,
                       observed_temperature = :observed_temperature,
                       bsw = :bsw, 
                       gravity = :gravity,
                       oil_price = :oil_price, 
                       total_dollars = :total_dollars, 
                       date_delivered = :date_delivered,
                       top_ft = :top_ft,
                       top_in = :top_in,
                       top_decimal = :top_decimal,
                       bottom_ft = :bottom_ft,
                       bottom_in = :bottom_in,
                       bottom_decimal = :bottom_decimal,
                       tank_number = :tank_number,
                       deduct = :deduct,
                       tank_id = :tank_id,
                       fluid_type_id = :fluid_type_id
                       WHERE id = :Id and created_by_id = :created_by_id',
					   array(
					   "notes"=>$ticket["notes"],
					   "ticket_number"=>$ticket["ticket_number"],
                       "barrels_delivered"=>$ticket["barrels_delivered"],
                       "top_temperature"=>$ticket["top_temperature"],
                       "bottom_temperature"=>$ticket["bottom_temperature"],
                       "observed_temperature"=>$ticket["observed_temperature"],
                       "bsw"=>$ticket["bsw"],
                       "gravity"=>$ticket["gravity"],
                       "oil_price"=>$ticket["oil_price"],
                       "total_dollars"=>$ticket["total_dollars"],
                       "date_delivered"=>$ticket["date_delivered"],
                       "top_ft"=>$ticket["top_ft"],
                       "top_in"=>$ticket["top_in"],
					   "top_decimal"=>$ticket["top_decimal"],
					   "bottom_ft"=>$ticket["bottom_ft"],
                       "bottom_in"=>$ticket["bottom_in"],
					   "bottom_decimal"=>$ticket["bottom_decimal"],
					   "tank_number"=>$ticket["tank_number"],
					   "deduct"=>$ticket["deduct"],
                       "Id"=>$_POST["Id"],
                       "created_by_id"=>$_SESSION['UserId'])
						//,1
					 );
					 } else {
		$stmt = pdo_query( $pdo, 
					   'update ticket_tracker_oil set 
					   notes = :notes, 
					   ticket_number = :ticket_number,
                       barrels_delivered = :barrels_delivered, 
                       top_temperature = :top_temperature,
                       bottom_temperature = :bottom_temperature,
                       observed_temperature = :observed_temperature,
                       bsw = :bsw, 
                       gravity = :gravity,
                       oil_price = :oil_price, 
                       total_dollars = :total_dollars, 
                       date_delivered = :date_delivered,
                       top_ft = :top_ft,
                       top_in = :top_in,
                       top_decimal = :top_decimal,
                       bottom_ft = :bottom_ft,
                       bottom_in = :bottom_in,
                       bottom_decimal = :bottom_decimal,
                       tank_number = :tank_number,
                       deduct = :deduct
                       WHERE id = :Id',
					   array(
					   "notes"=>$ticket["notes"],
					   "ticket_number"=>$ticket["ticket_number"],
                       "barrels_delivered"=>$ticket["barrels_delivered"],
                       "top_temperature"=>$ticket["top_temperature"],
                       "bottom_temperature"=>$ticket["bottom_temperature"],
                       "observed_temperature"=>$ticket["observed_temperature"],
                       "bsw"=>$ticket["bsw"],
                       "gravity"=>$ticket["gravity"],
                       "oil_price"=>$ticket["oil_price"],
                       "total_dollars"=>$ticket["total_dollars"],
                       "date_delivered"=>$ticket["date_delivered"],
                       "top_ft"=>$ticket["top_ft"],
                       "top_in"=>$ticket["top_in"],
					   "top_decimal"=>$ticket["top_decimal"],
					   "bottom_ft"=>$ticket["bottom_ft"],
                       "bottom_in"=>$ticket["bottom_in"],
					   "bottom_decimal"=>$ticket["bottom_decimal"],
					   "tank_number"=>$ticket["tank_number"],
					   "deduct"=>$ticket["deduct"],
                       "Id"=>$_POST["Id"])
                       	  //,1
					 );
                       }
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}
	
	$response['code'] = 'success';
	$response['data'] = $rowcount;
	echo json_encode($response);
	
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>