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
              SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(DISTINCT(t1.id)) ) AS num_in_day, t2.type
              FROM repair_form AS t1
			  LEFT JOIN status_type_repairs_with_fit_issues AS t2 ON 2 = t2.id
              LEFT JOIN rma_faults_log AS t3 ON t1.id = t3.id_of_rma
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month'  AND t3.classification = 'Fit'
              GROUP BY the_day, type
              ORDER BY the_day ASC";              
             


    $stmt = pdo_query( $pdo, $query, $params ); 
	$num_in_day = pdo_fetch_all( $stmt );

	$num_repairs = 0;
	$num_fit_issues = 0; 
	for($j=0; $j<count($num_in_day); $j++) {    
		if(stristr($num_in_day[$j]["type"], "# of Repairs Received") ) {
			$num_repairs = $num_repairs + $num_in_day[$j]["num_in_day"];		
		} elseif(stristr($num_in_day[$j]["type"], "# of Fit Issues") ) {
			$num_fit_issues = $num_fit_issues + $num_in_day[$j]["num_in_day"];	
		}
	}


    $response["num_repairs"] = $num_repairs;
	$response["num_fit_issues"] = $num_fit_issues;
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