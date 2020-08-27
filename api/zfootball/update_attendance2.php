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
    $absent = $_REQUEST["ABSENT"];
    $personnel_id = $_REQUEST["PERSONNEL_ID"];
    
     //GET THE TEAM
    /*
    $query = "SELECT * FROM zteams WHERE id = $team_name";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TEAM_NAME'] = $row["team_name"];
	*/

    // GETT COUNT OF PLAYERS ON ROSTER
    $query = "SELECT t1.* FROM zattendance_log AS t1
    					LEFT JOIN zpersonnel AS t2 ON t1.personnel_id = t2.id
    					LEFT JOIN zteams AS t3 ON t2.team_id = t3.id
    					WHERE t1.date = :date AND t2.active = TRUE AND t3.id = :team_id AND t2.id = :personnel_id";

	$params[":date"] = $_REQUEST['DATE']; 
	$params[":team_id"] = $_REQUEST['TEAM_ID']; 
	$params[":personnel_id"] = $personnel_id; 
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    $response["test"] = "Rows in result " . $rows_in_result;
	//echo json_encode($response);
	//exit;
    
    if($rows_in_result > 0) {
		if($absent == 1) {
			$present = "NO";
		} else {
			$present = "YES";
		}
		$log_id = $_REQUEST["LOG_ID"];
		
		$response["test"] = "Present is " .$present . " Log ID is " . $log_id;
		//echo json_encode($response);
		//exit;

		$stmt = pdo_query( $pdo, "UPDATE zattendance_log SET present=:present WHERE id=:id", array(":present"=>$present, ":id"=>$log_id));
		$result = pdo_fetch_all( $stmt );
    } else {
		if($absent == 1) {
			$present = "NO";
		} else {
			$present = "YES";
		}
		
		$response["test"] = "Personnel ID is  " .$personnel_id . " Date is " . $_REQUEST['DATE'];
		//echo json_encode($response);
		//exit;
		$stmt = pdo_query( $pdo,  'INSERT INTO zattendance_log  (personnel_id, date, present) VALUES (:personnel_id, :date, :present) RETURNING id', 
																array(":personnel_id"=>$personnel_id, ":date"=>$_REQUEST['DATE'], ":present"=>$present));
    }   
  
    $response['code'] = 'success';
    $response["message"] = "Attendance Saved!";
    //$response['data'] = $result;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>