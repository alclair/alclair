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
	$ticket['notes'] = $_POST['notes'];
    $ticket['ticket_number'] = $_POST['ticket_number'];
	$ticket['disposal_well_id'] = $_POST['disposal_well_id'];
	$ticket['barrels_delivered'] = $_POST['barrels_delivered'];
	$ticket['water_type_id'] = $_POST['water_type_id'];
	$ticket['date_delivered'] = $_POST['date_delivered'];
	$ticket['delivery_method'] = $_POST['delivery_method'];
	$ticket['water_source_type'] = $_POST['water_source_type'];
	$ticket['source_well_id'] = $_POST['source_well_id'];
	$ticket['trucking_company_id'] = $_POST['trucking_company_id'];
	$ticket['barrel_rate'] = $_POST['barrel_rate'];	
	$ticket['trucking_company'] = $_POST['trucking_company'];			 
					 
	if($_SESSION['IsAdmin'] == 0) {
	$stmt = pdo_query( $pdo, 
					   'update ticket_tracker_water set notes = :notes, ticket_number = :ticket_number,
                       disposal_well_id = :disposal_well_id, barrels_delivered = :barrels_delivered,
                       water_type_id = :water_type_id, date_delivered = :date_delivered,
                       delivery_method = :delivery_method, 
                       source_well_id = :source_well_id,
                       barrel_rate = :barrel_rate, trucking_company = :trucking_company
                       where Id = :Id and created_by_id = :created_by_id',
					   array("Id"=>$ticket["Id"],"notes"=>$ticket["notes"],"ticket_number"=>$ticket["ticket_number"],
                       "disposal_well_id"=>$ticket["disposal_well_id"],"barrels_delivered"=>$ticket["barrels_delivered"],
                       "water_type_id"=>$ticket["water_type_id"],"date_delivered"=>$ticket["date_delivered"],
                       "delivery_method"=>$ticket["delivery_method"],
                       "source_well_id"=>$ticket["source_well_id"],
                       "barrel_rate"=>$ticket["barrel_rate"], "trucking_company"=>$ticket["trucking_company"], ":created_by_id"=>$_SESSION['UserId'])
						//,1
					 );
					 } else {
		$stmt = pdo_query( $pdo, 
					   'update ticket_tracker_water set notes = :notes, ticket_number = :ticket_number,
                       disposal_well_id = :disposal_well_id, barrels_delivered = :barrels_delivered,
                       water_type_id = :water_type_id, date_delivered = :date_delivered,
                       delivery_method = :delivery_method, 
                       source_well_id = :source_well_id, 
                       barrel_rate = :barrel_rate, trucking_company = :trucking_company
                       where Id = :Id',
					   array("Id"=>$ticket["Id"],"notes"=>$ticket["notes"],"ticket_number"=>$ticket["ticket_number"],
                       "disposal_well_id"=>$ticket["disposal_well_id"],"barrels_delivered"=>$ticket["barrels_delivered"],
                       "water_type_id"=>$ticket["water_type_id"],"date_delivered"=>$ticket["date_delivered"],
                       "delivery_method"=>$ticket["delivery_method"],
                       "source_well_id"=>$ticket["source_well_id"],
                       "barrel_rate"=>$ticket["barrel_rate"], "trucking_company"=>$ticket["trucking_company"], ":created_by_id"=>$_SESSION['UserId'])
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