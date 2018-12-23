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
    $params = null;
    $year = $_REQUEST['year'];
    $month = $_REQUEST['month'];
    $IEM = intval($_REQUEST['IEM']);
    
    /*
	if(!empty($_REQUEST["month"]))
	{
		if (date('I', time())) {	 $month = date("m/d/Y H:i:s",strtotime($month. '00:00:00') + 5 * 3600); }
		else { $month = date("m/d/Y H:i:s",strtotime($month . '00:00:00')+ 6 * 3600); }
	}
	
	if(!empty($_REQUEST["month"]))
	{
		if (date('I', time())) { $month = date("m/d/Y H:i:s",strtotime($month . '23:59:59') + 5 * 3600); }
		else { $month = date("m/d/Y H:i:s",strtotime($month . '23:59:59') + 6 * 3600); }
	}*/

    $condition = "";
    if($IEM != 0)
    {
        $condition = "and t1.monitor_id = $IEM ";
    }

    $query = "SELECT to_char(t1.qc_date,'dd') AS created, pass_or_fail, ( SELECT COUNT(pass_or_fail) ) AS num_status
              FROM qc_form as t1
              WHERE to_char(qc_date,'yyyy') = '$year' AND to_char(qc_date,'MM') = '$month' $condition AND build_type_id = 1 AND pass_or_fail != 'PASS'
              GROUP BY created, pass_or_fail
              ORDER BY created ASC"; 
              

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

    $response['code'] = 'success';
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