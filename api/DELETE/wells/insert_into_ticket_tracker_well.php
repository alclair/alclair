<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	 
	
//$string = $_POST["well_info"];
$ids = $_REQUEST["ID_num"];
$response["ids"] = $_REQUEST["ID_num"];

$query = "INSERT INTO ticket_tracker_well (id, file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew, feet_ns, fewl, fnsl, active, date_created, is_from_ndic, validated, created_by_id)
SELECT id, file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew, feet_ns, fewl, fnsl, active, date_created, is_from_ndic, validated, created_by_id
FROM wells_list
WHERE id = ('$ids')";

$params_null = null;
$stmt = pdo_query($pdo,$query,$params_null);
$row = pdo_fetch_all($stmt);

}

catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>