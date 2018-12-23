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
    $orderBySql = " ORDER BY rma_number $orderBySqlDirection";
    $params = array();
    
    

	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    $response["test"] = $conditionSql;
    $response["test2"] = $_REQUEST['id'];
    $repair_id_is = $_REQUEST["ID"];
    $done_date = $_REQUEST['DoneDate'];

        $query = "SELECT t1.*, to_char(t1.date, 'MM/dd/yyyy    HH24:MI') as date_made_done  FROM repair_status_log AS t1
LEFT JOIN repair_form AS t2 ON t1.repair_form_id = t2.id 
WHERE t2.id = :repair_id_is AND t1.repair_status_id = 14
ORDER BY date_made_done ASC LIMIT 1";
//AND t1.date >= '08/01/2018' AND t1.date <= '08/28/2018'
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, array(":repair_id_is"=>$repair_id_is)); 
    $result = pdo_fetch_all( $stmt );
    
    $repair_status_log_id = $result[0]["id"];
    //$response["test1"] = "HERE";
    //echo json_encode($response);
    //exit;
    date_default_timezone_set('America/Chicago');
    $date = date('m/d/Y h:i:s A', time());
    $notes = $result[0]["notes"] . " // Done date changed on " . $date . " by " . $_SESSION['UserName'] . ".";
    
    $response["test1"] = "Repair status log ID is " . $repair_status_log_id . " and the notes are " . $notes . " and Done Date is " . $done_date;
    //echo json_encode($response);
    //exit;

    $query = pdo_query($pdo, "UPDATE repair_status_log SET date = :done_date, notes = :notes WHERE id = :id",  
    						array(":done_date"=>$done_date,  ":notes"=>$notes, ":id"=>$repair_status_log_id));
        
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['data2'] = $result2;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>