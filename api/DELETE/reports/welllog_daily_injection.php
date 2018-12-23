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
    
    $condition = "";
    if($disposalwell != 0)
    {
        $condition = "and t1.disposal_well_id = $disposalwell ";
    }


    $query =   "select to_char(date_created,'dd') as created,
                sum(injection_rate) as total_injection_rate,sum(injection_pressure) as total_injection_pressure
                from well_logs_dailywelllog
                where to_char(date_created,'yyyy') = '$year' and to_char(date_created,'MM')='$month' $condition
                group by created
                order by created asc"; 

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