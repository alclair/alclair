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
	$ticket['id'] = $_POST['id'];
	$ticket['name'] = $_POST['name'];
    $ticket['units'] = $_POST['units'];
						 
	if($_SESSION['IsAdmin'] == 0) {
	$stmt = pdo_query( $pdo, 
					   'UPDATE materials SET name = :name, units = :units
                        WHERE id = :id and created_by_id = :created_by_id',
					   array("id"=>$ticket["id"],"name"=>$ticket["name"],"units"=>$ticket["units"], ":created_by_id"=>$_SESSION['UserId']));
					 } else {
		$stmt = pdo_query( $pdo, 
					   'UPDATE materials SET name = :name, units = :units
                       where id = :id',
					   array("id"=>$ticket["id"],"name"=>$ticket["name"],"units"=>$ticket["units"], ":created_by_id"=>$_SESSION['UserId']));
                       }
                       
	$rowcount = pdo_rows_affected( $stmt );
	$response['testing'] = $rowcount;
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