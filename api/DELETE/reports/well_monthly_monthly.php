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
    $disposalwell = intval($_REQUEST['disposalwell']);

    $condition = "";
    if($disposalwell != 0)
    {
        $condition = "and t1.disposal_well_id = $disposalwell ";
    }

    $query = "select to_char(t1.date_delivered,'dd') as created,t3.type as water_type,  sum(t1.barrels_delivered) as total_barrels_delivered
              from ticket_tracker_ticket as t1
              left join ticket_tracker_watertype as t3 on t1.water_type_id = t3.id
              where to_char(date_delivered,'yyyy') = '$year' and to_char(date_delivered,'MM')='$month'  $condition
              group by created ,water_type
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