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

// SET $_POST TO A VARIABLE WHICH IS ALSO A STRING
$string = $_POST["to"];
// STORE THE IDs AS AN ARRAY OF INTEGERS
$integerIDs = array_map('intval', explode(',', $string));

$params_null = null;
// PUT THE ARRAY OF INTEGERS BACK INTO A STRING FOR THE QUERY
$ids = join("','",$integerIDs);  
// FIND ALL OF THE IDs THAT ALREADY EXIST IN THE  RIGHT TABLE
$query_common = "SELECT id FROM ticket_tracker_well WHERE ticket_tracker_well.id IN ('$ids')";
//$query_common = "SELECT id FROM well WHERE ticket_tracker_well.id IN ('$ids')";
$stmt_common = pdo_query($pdo, $query_common, $params_null );
$row_common = pdo_fetch_all($stmt_common);

$IDs_not_common = array();
$inc = 0;

if( empty($row_common) ) {
	$IDs_not_common = $integerIDs;
}
else {
	for($i=0; $i<sizeof($integerIDs); $i++) {
		for($j=0; $j<sizeof($row_common); $j++) {	
			if( $integerIDs[$i] == $row_common[$j]["id"] ) {
				$j = sizeof($row_common); 
			}
			elseif ($integerIDs[$i] != $row_common[$j]["id"] && $j == sizeof($row_common)-1) {
					$IDs_not_common[$inc] = $integerIDs[$i];
					$inc++; 
			}
		}
	}
}

// PUT THE ARRAY OF INTEGERS BACK INTO A STRING FOR THE QUERY
$ids = join("','",$IDs_not_common);   
// INSERT ALL OF THE IDs THAT DON'T ALREADY EXIST  IN THE RIGHT TABLE INTO THE TABLE FROM THE LEFT TABLE
/*$query = "INSERT INTO well (id, file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew, feet_ns, fewl, fnsl, active, date_created, is_from_ndic, validated, created_by_id)
SELECT id, file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew, feet_ns, fewl, fnsl, active, date_created, is_from_ndic, validated, created_by_id
FROM ticket_tracker_well
WHERE id IN ('$ids')";
*/
$query = "INSERT INTO ticket_tracker_well (id, file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew, feet_ns, fewl, fnsl, active, date_created, is_from_ndic, validated, created_by_id)
SELECT id, file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew, feet_ns, fewl, fnsl, active, date_created, is_from_ndic, validated, created_by_id
FROM wells_list
WHERE id IN ('$ids')";


$stmt = pdo_query($pdo,$query,$params_null);
$row = pdo_fetch_all($stmt);

// FIND ALL OF THE IDs NOW IN THE TABLE
$query = "SELECT id from ticket_tracker_well WHERE 1 = 1";
//$query = "SELECT id from well WHERE 1 = 1";
$stmt = pdo_query($pdo, $query, $params_null);
$IDs = pdo_fetch_all($stmt);

$IDS_to_delete = array();
$inc = 0;

for($i=0; $i<sizeof($IDs); $i++) {
	for($j=0; $j<sizeof($integerIDs); $j++) {	
		if( $IDs[$i]["id"] == $integerIDs[$j] ) {
			$j = sizeof($integerIDs); 
		}
		elseif ($IDs[$i]["id"] != $integerIDs[$j] && $j == sizeof($integerIDs)-1) {
				$IDS_to_delete[$inc] = $IDs[$i]["id"];
				$inc++; 
		}
	}
}
$ids = join("','",$IDS_to_delete);   
//WHERE id IN ('$ids')";
$query = "delete from ticket_tracker_well where id IN ('$ids')";
//$query = "delete from well where id IN ('$ids')";
$stmt = pdo_query($pdo, $query, $params_null);
$row2 = pdo_fetch_all($stmt);
//$stmt = pdo_query( $pdo,"delete from well where id IN ('$ids')", null);	

if(empty($row) == false || empty($row2) == false)
	{
		print_r("success");
	}
else {
		print_r("error");
	}
//print_r($integerIDs[2]);
//print_r($IDs[2]);
//print_r(empty($row));
//print_r($c);
/*
$query = "INSERT INTO well (id, file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew, feet_ns, fewl, fnsl, active, date_created, is_from_ndic, validated, created_by_id)
				  
				  VALUES (:id, :file_number, :api_number, :well_type, :current_operator_id, :current_well_name, :spud_date, :td, :county, :township, :rng, :section, :qq, :field_name, :latitude, :longitude, :well_status, :feet_ew, :feet_ns, :fewl, :fnsl, :active, :date_created, :is_from_ndic, :validated, :created_by_id)
				  WHERE NOT EXISTS (
				  SELECT id FROM well WHERE id = :id)";
				


$query = "INSERT INTO well (id, file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew, feet_ns, fewl, fnsl, active, date_created, is_from_ndic, validated, created_by_id)
				  SELECT 1, 'John'
				  WHERE NOT EXISTS (
				  SELECT id FROM well WHERE id = 1)";				
				
*/				
				
				
				  
}

catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>