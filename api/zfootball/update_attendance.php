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
    $personnel_list = $_REQUEST["PERSONNEL_LIST"];
    
     //GET THE TEAM
    $query = "SELECT * FROM zteams WHERE id = $team_name";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TEAM_NAME'] = $row["team_name"];

    // GETT COUNT OF PLAYERS ON ROSTER
    $query = "SELECT count(t1.id) FROM zattendance_log AS t1
    					LEFT JOIN zpersonnel AS t2 ON t1.personnel_id = t2.id
    					LEFT JOIN zteams AS t3 ON t2.team_id = t3.id
    					WHERE t1.date = :date AND t2.active = TRUE AND t3.id = :team_id";

	$params[":date"] = $_REQUEST['DATE']; 
	$params[":team_id"] = $_REQUEST['TEAM_ID']; 
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
    $response["test"] = "Count is " . count($personnel_list) . " Absent is " . $personnel_list[0]["absent"];
    echo json_encode($response);
    exit;
    
    if($row[0] > 0) {
	    for ($p = 0; $p < count($personnel_list); $p++) {
			if($personnel_list[p]["absent"] == 1) {
				$present = "NO";
			} else {
				$present = "YES";
			}
		}
		$log_id = $personnel_list[p]["id"]; // THE ID OF THE ATTENDANCE LOG
		$stmt = pdo_query( $pdo, "UPDATE zattendance_log SET present=:present WHERE id=:id", array(":present"=>$present, ":id"=>$log_id));
		$result = pdo_fetch_all( $stmt );
    } else {
		for ($p = 0; $p < count($personnel_list); $p++) {
			if($personnel_list[p]["absent"] == 1) {
				$present = "NO";
			} else {
				$present = "YES";
			}
		}
		$personnel_id = $personnel_list[p]["personnel_id"];
		$stmt = pdo_query( $pdo,  'INSERT INTO zattendance_log  (personnel_id, date, present) VALUES (:personnel_id, :date, :present) RETURNING id', 
																array(":personnel_id"=>$personnel_id, ":import_orders_id"=>$_REQUEST['DATE'], ":present"=>$present));
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