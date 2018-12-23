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
    $pagingSql = "";
    $orderBySqlDirection = "desc";
    $orderBySql = " order by id $orderBySqlDirection";
    $params = array();

    //Get Total Records
    $query = "SELECT * FROM tank_status";
    $stmt = pdo_query( $pdo, $query, $params );
    $result2 = pdo_fetch_array( $stmt );
    //$response['TotalRecords'] = $row[0];
    
/*    $query2 = "SELECT t3.name as tank_name, t2.type as fluid_type, t1.fluid_amount as fluid_amount, t2.priority as priority FROM tank_tracker as t1
						LEFT JOIN ticket_tracker_fluidtype as t2 on t1.fluid_id=t2.id
						LEFT JOIN tanks as t3 on t1.tank_id=t3.id
						WHERE tank_id = 1";*/
						
	$query2 = "SELECT t1.id, t1.name, t2.tank_id, t2.fluid_id, t2.fluid_amount, t3.type, t3.priority FROM tanks as t1
LEFT JOIN tank_tracker as t2 on t1.id=t2.tank_id
LEFT JOIN ticket_tracker_fluidtype as t3 on t2.fluid_id=t3.id
WHERE 1=1 ORDER BY t1.id, t3.priority DESC";
    $stmt2 = pdo_query( $pdo, $query2, $params );
    $result = pdo_fetch_all( $stmt2 );
    //$response['TotalRecords'] = $row[0];
    $response['test'] = $result[1]["fluid_type"];
    $response['test2'] = count($result);
    $response['num_rows'] = count($result);
    	
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