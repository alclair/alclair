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
	$ticket['deliverymethod'] = $_POST['delivery_method'];
	$ticket['watersourcetype'] = $_POST['water_source_type'];
	$ticket['sourcewell'] = $_POST['source_well_id'];
	$ticket['truckingcompany'] = $_POST['trucking_company_id'];
	$ticket['notes'] = $_POST['notes'];
	
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
	$barrel_rate=GetBarrelRate($ticket['disposalwell'],$ticket['watertype'],$ticket['sourcewell'],$operator_id,$ticket['truckingcompany']);
	if(empty($barrel_rate))
	{
		$barrel_rate=GetDefaultRate($ticket['disposalwell'],$ticket['watertype']);				 
	}
	$stmt = pdo_query( $pdo, 
					   "insert into ticket_tracker_water
					   (ticket_number,disposal_well_id,barrels_delivered,water_type_id,date_delivered,delivery_method,water_source_type,source_well_id,trucking_company_id,notes,date_created,created_by_id,barrel_rate)
					   values
					   (:ticketnumber,:disposalwell,:barrelsdelivered,:watertype,:datedelivered,:deliverymethod,:watersourcetype,:sourcewell,:truckingcompany,:notes,now(),:created_by_id,:barrel_rate) RETURNING id",
					   array(':ticketnumber'=>$ticket['ticketnumber'],':disposalwell'=>$ticket['disposalwell'],':barrelsdelivered'=>$ticket['barrelsdelivered'],':watertype'=>$ticket['watertype'],
                       ":datedelivered"=>$ticket["datedelivered"],
					   ':deliverymethod'=>$ticket['deliverymethod'],':watersourcetype'=>$ticket['watersourcetype'],':sourcewell'=>$ticket['sourcewell'],':truckingcompany'=>$ticket['truckingcompany'],':notes'=>$ticket['notes'],
					   ":created_by_id"=>$_SESSION['UserId'],":barrel_rate"=>$barrel_rate)
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
	echo json_encode($response);
	
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>