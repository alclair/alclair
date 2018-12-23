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

	$query_fees =   "SELECT t2.name as landfill_site, t2.freight_fee as freight_fee, t2.tipping_fee as tipping_fee
                FROM landfill_disposal_sites as t2
                WHERE  t2.id = $_id";  
	 
	$params_fees = null;
    $stmt_fees = pdo_query( $pdo, $query_fees, $params_fees); 
    $result_fees = pdo_fetch_all( $stmt_fees );

	$ticket['tons'] = ($ticket['loaded_weightt'] - $ticket['tare_weight']) / 2000;
	$ticket['fees'] = ($result_fees[0]["freight_fee"] + $result_fees[0]["tipping_fee"]);
	$ticket['total_dollars'] = $ticket['fees'] * $ticket['tons'];//$ticket['fees'] * $ticket['tons'];
						 
	if($_SESSION['IsAdmin'] == 0) {
	$stmt = pdo_query( $pdo, 
					   'UPDATE ticket_tracker_landfill SET
					   ticket_number = :test_report_number, 
					   date_delivered = :test_date,
                       radium_226 = :radium_226, 
                       radium_228 = :radium_228,
                       bill_lading_number = :bill_lading_number,
                       ship_date = :ship_date,
                       trucking_company_id = :trucking_company_id, 
                       landfill_disposal_site = :landfill_disposal_site,
                       tank_id = :tank_id, 
                       fluid_type_id = :fluid_type_id,
                       barrels_delivered = :barrels_delivered,
                       landfill_ticket_number = :landfill_ticket_number,
                       tare_weight = :tare_weight,
                       loaded_weight = :loaded_weight,
                       notes = :notes,
                       tons = :tons,
                       total_dollars = :total_dollars
                       WHERE id = :Id and created_by_id = :created_by_id',                       
					   array(
					   ":test_report_number"=>$ticket["test_report_number"],
					   ":test_date"=>$ticket["test_date"],
					   ":radium_226"=>$ticket["radium_226"],
					   ":radium_228"=>$ticket["radium_228"],
					   ":bill_lading_number"=>$ticket["bill_lading_number"],
					   ":ship_date"=>$ticket["ship_date"],
					   ":trucking_company_id"=>$ticket["trucking_company_id"],
                       ":landfill_disposal_site"=>$ticket["landfill_disposal_site"],					   
					   ":tank_id"=>$ticket["tank_id"],
					   ":fluid_type_id"=>$ticket["fluid_type_id"],
					   ":barrels_delivered"=>$ticket["barrels_delivered"],
					   ":landfill_ticket_number"=>$ticket["landfill_ticket_number"],
                       ":tare_weight"=>$ticket["tare_weight"],
                       ":loaded_weight"=>$ticket["loaded_weight"],	
                       ":notes"=>$ticket["notes"],
                       ":tons"=>$ticket["tons"],
					   ":total_dollars"=>$ticket["total_dollars"],
                       ":id"=>$_POST["id"],
                       ":created_by_id"=>$_SESSION['UserId'])
						//,1
					 );
					 } else {
		$stmt = pdo_query( $pdo, 
					   'UPDATE ticket_tracker_landfill SET
					   ticket_number = :test_report_number, 
					   date_delivered = :test_date,
                       radium_226 = :radium_226, 
                       radium_228 = :radium_228,
                       bill_lading_number = :bill_lading_number,
                       ship_date = :ship_date,
                       trucking_company_id = :trucking_company_id, 
                       landfill_disposal_site = :landfill_disposal_site,
                       tank_id = :tank_id, 
                       fluid_type_id = :fluid_type_id,
                       barrels_delivered = :barrels_delivered,
                       landfill_ticket_number = :landfill_ticket_number,
                       tare_weight = :tare_weight,
                       loaded_weight = :loaded_weight,
                       notes = :notes,
                       tons = :tons,
                       total_dollars = :total_dollars
                       WHERE id = :id',
					   array(
					   ":test_report_number"=>$ticket["test_report_number"],
					   ":test_date"=>$ticket["test_date"],
					   ":radium_226"=>$ticket["radium_226"],
					   ":radium_228"=>$ticket["radium_228"],
					   ":bill_lading_number"=>$ticket["bill_lading_number"],
					   ":ship_date"=>$ticket["ship_date"],
					   ":trucking_company_id"=>$ticket["trucking_company_id"],
                       ":landfill_disposal_site"=>$ticket["landfill_disposal_site"],					   
					   ":tank_id"=>$ticket["tank_id"],
					   ":fluid_type_id"=>$ticket["fluid_type_id"],
					   ":barrels_delivered"=>$ticket["barrels_delivered"],
					   ":landfill_ticket_number"=>$ticket["landfill_ticket_number"],
                       ":tare_weight"=>$ticket["tare_weight"],
                       ":loaded_weight"=>$ticket["loaded_weight"],
                       ":notes"=>$ticket["notes"],
                       ":tons"=>$ticket["tons"],
					   ":total_dollars"=>$ticket["total_dollars"],
                       ":id"=>$_POST["id"])
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