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
    
    $condition = "";
    if($IEM != 0)
    {
        $condition = "and t1.monitor_id = $IEM ";
    }
      
   	$query = "SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(to_char(received_date, 'dd') ) ) AS num_days
              FROM repair_form
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
              GROUP BY the_day
              ORDER BY the_day ASC";
   $stmt = pdo_query( $pdo, $query, $params ); 
   $num_of_impressions_in_day = pdo_fetch_all( $stmt );

$query = "SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(to_char(received_date, 'dd') ) ) AS num_in_day, t2.type
              FROM repair_form
              LEFT JOIN status_type_repairs AS t2 ON 1 = t2.id
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
              GROUP BY the_day, type
      UNION ALL
              SELECT to_char(date, 'dd') AS the_day, ( SELECT COUNT(to_char(date, 'dd') ) ) AS num_in_day, t2.type
              FROM repair_status_log
              LEFT JOIN status_type_repairs AS t2 ON 2 = t2.id
              WHERE to_char(date,'yyyy') = '$year' AND to_char(date,'MM') = '$month'  AND repair_status_id = 14
              GROUP BY the_day, type
              ORDER BY the_day ASC";

    $stmt = pdo_query( $pdo, $query, $params ); 
	$num_in_day = pdo_fetch_all( $stmt );
	
	$num_repairs = 0;
	$num_shipped = 0; 
	for($j=0; $j<count($num_in_day); $j++) {    
		if(stristr($num_in_day[$j]["type"], "# of Repairs Received") ) {
			$num_repairs = $num_repairs + $num_in_day[$j]["num_in_day"];		
		} elseif(stristr($num_in_day[$j]["type"], "# Shipped") ) {
			$num_shipped = $num_shipped + $num_in_day[$j]["num_in_day"];	
		}
	}


    $result = array();
    
    
// ADDED THIS CODE BECAUSE COULD NOT FIGURE OUT HOW TO ACCOUNT FOR DAYS THAT RETURNED ZERO
// THIS CODE POPULATES A TABLE THAT GETS USED FOR DATA STUDIO

/*
    $count_for_table = array();
	if(
		strcmp(date("m"), '01') || strcmp(date("m"), '03') || trcmp(date("m"), '05') || 
		strcmp(date("m"), '07') || strcmp(date("m"), '08') || strcmp(date("m"), '10') ||strcmp(date("m"), '12')
	) {
		$days_in_month = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];	
	} elseif (strcmp(date("m"), '02')) {
		$days_in_month = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28'];	
	} else {
		$days_in_month = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30'];	
	}
	$for_loop = array_search(date("d"), $days_in_month);
	
	for($j=1; $j=$for_loop+1; $j++) {   
		$query = "SELECT count(t1.received_date) AS received_today
							FROM repair_form t1
							WHERE to_char(t1.received_date,'yyyy') = to_char(date_trunc('year', CURRENT_DATE), 'YYYY')  
							AND to_char(t1.received_date,'MM') = to_char(date_trunc('month', CURRENT_DATE), 'MM') 
							AND to_char(t1.received_date,'dd') = '01'
							GROUP BY t1.received_date";
		$stmt = pdo_query( $pdo, $query, $params );
		$count = pdo_fetch_all( $stmt );
		
		if(!pdo_fetch_all( $stmt )) {
			$count_for_table[$j-1] = $count[0]["received_today"];
		} else {
			$count_for_table[$j-1] = 0;
		}
	}
	*/
	$response["test"] = $count_for_table;
	echo json_encode($response);
    exit;
	
	for($j=0; $j<count($num_of_impressions_in_day); $j++) {   

	}
	$response["test1"] = $num_of_impresions_in_day;
	
	$response["num_repairs"] = $num_repairs;
	$response["num_shipped"] = $num_shipped;;
    $response['code'] = 'success';
    $response['data'] = $result;
    $response['data'] = $num_in_day;
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>