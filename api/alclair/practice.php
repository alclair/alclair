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

//$fc="1".":".$_FILES['documentfile']['name'];

try
{
	
	$des = "##adsf3#@4  adsf#@";
	$res = preg_replace("/[^a-zA-Z]/", "", $des);

	// Strip HTML Tags
$clear = strip_tags($des);
// Clean up things like &amp;
$clear = html_entity_decode($clear);
// Strip out any url-encoded stuff
$clear = urldecode($clear);
// Replace non-AlNum characters with space
$clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
// Replace Multiple spaces with single space
$clear = preg_replace('/ +/', ' ', $clear);
// Trim the string of leading/trailing space
$clear = trim($clear);
	
	echo "DES IS " . $clear;
	echo "<br/> and " . $res;
	

	// LOOK INSIDE BATCHES FOR PAID ORDERS WITH IMPRESSIONS
	//SELECT * FROM batches AS t1 
	//LEFT JOIN batch_item_log AS t2 ON t1.id = t2.batch_id
	//WHERE t1.received_date IS NULL AND t1.active = TRUE AND t2.active = TRUE AND t2.paid = TRUE

$response["code"] = "success";
$response["testing1"] = $inc;

echo json_encode($response);
}
catch(PDOException $ex)
{
	//$response["code"] = "error 2";
	$response["message"] = $ex->getMessage();
	//echo json_encode($response);
}

?>