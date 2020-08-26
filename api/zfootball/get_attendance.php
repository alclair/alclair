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
    $conditionSql = "";
    $conditionSql_printed = "";
    $pagingSql = "";
    $orderBySqlDirection = "ASC";
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();
    
    $team_name = $_REQUEST["TEAM_ID"];
    
     //GET THE TEAM
    $query = "SELECT * FROM zteams WHERE id = 2";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TEAM_NAME'] = $row["team_name"];

    //Get Total Passed
    $query = "SELECT count(t1.id) FROM zattendance_log AS t1
    					LEFT JOIN zpersonnel AS t2 ON t1.personnel_id = t2.id
    					LEFT JOIN zteams AS t3 ON t2.team_id = t3.id
    					WHERE t1.date = :date AND t2.active = TRUE AND t3.id = :team_id";

	$params[":date"] = $_REQUEST['DATE']; 
	$params[":team_id"] = $_REQUEST['TEAM_ID']; 
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];

	$query = "SELECT t1.*, t2.id AS person_id, t2.first_name, t2.last_name, t3.* FROM zattendance_log AS t1
    					LEFT JOIN zpersonnel AS t2 ON t1.personnel_id = t2.id
    					LEFT JOIN zteams AS t3 ON t2.team_id = t3.id
    					WHERE t1.date = '08/26/2020' AND t2.active = TRUE AND t3.id = 2";
    					//WHERE t1.date = :date AND t2.active = TRUE AND t3.id = :team_id";
       
    $stmt = pdo_query( $pdo, $query, null); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);

    $response['code'] = 'success';
    $response["message"] = $query;
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