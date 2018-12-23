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

    $query =   "select to_char(date_created,'dd') as created,sum(oil_sold_barrels) as total_oil_sold, 
                sum(level_oil_tank_1_ft) as total_oil_tank1,sum(level_oil_tank_2_ft) as total_oil_tank2,sum(level_oil_tank_3_ft) as total_oil_tank3,
                sum(level_gun_ft) as total_gun,
                sum(level_skim_tank_1_ft) as total_skim_tank1,sum(level_skim_tank_2_ft) as total_skim_tank2
                from well_logs_dailywelllog
                where to_char(date_created,'yyyy') = '$year' and to_char(date_created,'MM')='$month'
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