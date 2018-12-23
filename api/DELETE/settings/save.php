<?php
include_once "../../config.inc.php";

try
{	
	$response = array();
	$response["code"] = "success";
	$response["message"] = "";
	$query = "select * from settings";
	$stmt = pdo_query( $pdo, $query, null );
	$row = pdo_fetch_array($stmt);
	if(empty($row[0]))
	{
		$query = "insert into settings (site_name,minimum_barrel_warning,maximum_barrel_warning, image_with_ticket, allow_duplicate_tickets) values (:site_name,:minimum_barrel_warning,:maximum_barrel_warning, :image_with_ticket, :allow_duplicate_tickets)";
		$params = array(
                ":site_name" => $_REQUEST["site_name"],
                "minimum_barrel_warning" => $_REQUEST["minimum_barrel_warning"],
                "maximum_barrel_warning" => $_REQUEST["maximum_barrel_warning"],
                "image_with_ticket" => $_REQUEST["image_with_ticket"],
                "allow_duplicate_tickets" =>$_REQUEST["allow_duplicate_tickets"],
            );
		pdo_query($pdo,$query,$params);
	}
	else
	{
		$query = "update settings set site_name = :site_name,minimum_barrel_warning = :minimum_barrel_warning,maximum_barrel_warning = :maximum_barrel_warning, image_with_ticket = :image_with_ticket, allow_duplicate_tickets = :allow_duplicate_tickets  where id = 1";
        $params = array(
            ":site_name" => $_REQUEST["site_name"],
            "minimum_barrel_warning" => $_REQUEST["minimum_barrel_warning"],
            "maximum_barrel_warning" => $_REQUEST["maximum_barrel_warning"],
            "image_with_ticket" => $_REQUEST["image_with_ticket"],
            "allow_duplicate_tickets" =>$_REQUEST["allow_duplicate_tickets"],
        );
		pdo_query($pdo,$query,$params);
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