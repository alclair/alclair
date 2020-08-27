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
    $query = "SELECT * FROM zteams WHERE id = $team_name";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TEAM_NAME'] = $row["team_name"];
    $response['DATE_DISPLAYED'] = $_REQUEST['DATE'];

    // CHECKING ATTENDANCE LOG TO SEE IF ATTENDANCE HAS ALREADY BEEN TAKEN FOR THIS DAY
    //  IF ATTENDANCE HAS BEEN TAKEN THEN THE NUMBER OF PLAYERS ON THE ROSTER IS DETERMINED HERE
    //  IF ATTENDANCE HAS NOT BEEN TAKEN THEN THE NUMBER OF PLAYERS ON THE ROSTER IS DETERMINED BELOW
    $query = "SELECT count(t1.id) FROM zattendance_log AS t1
    					LEFT JOIN zpersonnel AS t2 ON t1.personnel_id = t2.id
    					LEFT JOIN zteams AS t3 ON t2.team_id = t3.id
    					WHERE t1.date = :date AND t2.active = TRUE AND t3.id = :team_id";

	$params[":date"] = $_REQUEST['DATE']; 
	$params[":team_id"] = $_REQUEST['TEAM_ID']; 
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    $attendance_yet = "YES";
    
    if($row[0] < 1) {  // GRAB TOTAL RECORDS IF ATTENDANCE HAS NOT BEEN TAKEN FOR THAT DAY
	    $query = "SELECT * FROM zpersonnel WHERE active = TRUE AND team_id = :team_id ORDER BY last_name";
		$params[":team_id"] = $_REQUEST['TEAM_ID']; 
		$stmt = pdo_query( $pdo, $query, $params );
		$row2 = pdo_fetch_all( $stmt );
		$response['TotalRecords'] = count($row2);
		$attendance_yet = "NO";
    }
    
    if($row[0] > 0) {  // GRABS ROSTER ATTENDANCE BECAUSE IT HAS BEEN TAKEN ALREADY

		$query = "SELECT t1.id AS log_id, t1.present, t1.date, t2.id AS person_id, t2.first_name, t2.last_name, t3.* FROM zattendance_log AS t1
    							LEFT JOIN zpersonnel AS t2 ON t1.personnel_id = t2.id
								LEFT JOIN zteams AS t3 ON t2.team_id = t3.id
								WHERE t1.date = :date AND t2.active = TRUE AND t3.id = :team_id ORDER BY last_name";
    } else {  // GRABS ROSTER FOR ATTENDANCE TO BE TAKEN BECAUSE IT HAS NOT BEEN TAKEN ALREADY FOR THE DAY CHOSEN

	    $query = "SELECT t1.*, t1.id AS person_id FROM zpersonnel AS t1 LEFT JOIN zteams AS t2 ON t1.team_id = t2.id WHERE t1.active = TRUE AND t2.id = :team_id ORDER BY last_name";
    }   
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);

    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response["ATTENDANCE_YET"] = $attendance_yet;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>