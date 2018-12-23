<?php
include_once "../../config.inc.php";

try
{
	$query = "select * from settings";
	$stmt = pdo_query( $pdo, $query, null );
	$row = pdo_fetch_array($stmt);
	$response = array();
	$response["code"] = "success";
	$response["message"] = "";
	$response['site_name'] = null;
    $response["minimum_barrel_warning"] = "";
    $response["maximum_barrel_warning"] = "";
	$response["image_with_ticket"] = "";
	$response["allow_duplcate_tickets"] = "";
	
	if(!empty($row[0]))
	{
		$response["id"] = $row["id"];
		$response["site_name"] = $row["site_name"];
        $response["minimum_barrel_warning"] = $row["minimum_barrel_warning"];
        $response["maximum_barrel_warning"] = $row["maximum_barrel_warning"];
        $response["image_with_ticket"] = $row["image_with_ticket"];
        $response["allow_duplicate_tickets"] = $row["allow_duplicate_tickets"];
	}
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>