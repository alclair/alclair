<?php
include_once "../../config.inc.php";

try
{
	$query="select * from settings";
	$stmt=pdo_query($pdo,$query,null);
	$row=pdo_fetch_array($stmt);
	$response = array();
	$response["code"] = "success";
	$response["message"] = "";
	$response['daily_emails'] = null;
	$response["weekly_emails"]=null;
	$response["monthly_emails"]=null;
	$response["daily_disposal_total"]=null;
	
	if(!empty($row[0]))
	{
		$response["id"]=$row["id"];
		$response["daily_emails"]=$row["daily_emails"];
		$response["weekly_emails"]=$row["weekly_emails"];
		$response["monthly_emails"]=$row["monthly_emails"];
		$response["daily_disposal_total"]=$row["daily_disposal_total"];
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