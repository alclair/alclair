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
    $orderBySql = " ORDER BY t1.received_date $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

   if($_REQUEST['MONTH_RANGE'] != '0' && empty($_REQUEST['id'])) {
		
    }
	
	$start_date = new DateTime($_REQUEST["StartDate"]);
	$days_back = clone $start_date;
	$days_back->modify('-' . $_REQUEST['MONTH_RANGE'] . ' day');
	
	$start_date = $start_date->format('Y-m-d');
	$days_back = $days_back->format('Y-m-d');
	$conditionSql .= " AND (t1.received_date < :start_date AND t3.date > :days_back)";
	$params[":start_date"] = $start_date;
	$params[":days_back"] = $days_back;
	
	$response['test'] = "Start is " . $start_date . " and back is " . $days_back;
	
	$conditionSql_2 = '';
	$params_2 = array();
	$conditionSql_2 .= " AND (t1.date < :start_date AND t1.date > :days_back)";
	$params_2[":start_date"] = $start_date;
	$params_2[":days_back"] = $days_back;
	//echo json_encode($response);
	//exit;
    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords2'] = $row[0];
    
     if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
    
     //Get One Page Records
     
     $query_2 = "SELECT * FROM order_status_log AS t1
     						LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
     						WHERE t1.order_status_id = 12 AND t2.active = TRUE  $conditionSql_2";
	 $stmt_2 = pdo_query( $pdo, $query_2, $params_2); 
    $result_2 = pdo_fetch_all( $stmt_2 );
    $response['TotalRecords2'] = count($result_2);
    
        $query = "SELECT t1.id AS id_of_repair, t1.customer_name, t1.rma_number, to_char(t1.received_date, 'MM/dd/yyyy') AS rma_received, t2.designed_for, t2.id AS id_of_order, t3.order_status_id, to_char(t3.date, 'MM/dd/yyyy') AS date_done FROM repair_form AS t1 
							LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
							LEFT JOIN order_status_log AS t3 ON t2.id = t3.import_orders_id
							WHERE t1.import_orders_id IS NOT NULL AND t3.order_status_id = 12 $conditionSql $orderBySql $pagingSql";
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    $response['TotalRecords'] = $rows_in_result;
    
    $final_result = $result;
    
    $sound = 0;
	$fit = 0;
	$design = 0;
   for ($i = 0; $i < $rows_in_result; $i++) {
		$query2 = "SELECT * FROM rma_faults_log WHERE id_of_rma = :id_of_rma";
		$stmt2 = pdo_query( $pdo, $query2, array(":id_of_rma"=>$result[$i]['id_of_repair']));
		$faults = pdo_fetch_all( $stmt2 );
		$rows = pdo_rows_affected($stmt2);
	
		for ($j = 0; $j < $rows; $j++) {
				//$response['test'] = "J is " . $j . " and fault is " . $faults[$j]['classification'] ;
				//echo json_encode($response);
				//exit;
			if(!strcmp($faults[$j]['classification'], 'Sound') ) {
				$response['test'] = "J is " . $j . " and fault is " . $faults[$j]['classification'] ;
				$sound++;
				//echo json_encode($response);
				//exit;
				$final_result[$i]['sound']= 'X';
			} else if(!strcmp($faults[$j]['classification'] , 'Fit') ) {
				$response['test'] = "J is " . $j . " and fault is " . $faults[$j]['classification'] ;
				$fit++;
				//echo json_encode($response);
				//exit;
				$final_result[$i]['fit'] = 'X';
			} else if(!strcmp($faults[$j]['classification'], 'Design') ) {
				$design++;
				$final_result[$i]['design'] = 'X';
			}
		}
	}
	/*
	SELECT t1.id, t1.customer_name, to_char(t1.received_date, 'MM/dd/yyyy') AS rma_received, t2.designed_for, t3.order_status_id, 
to_char(t3.date, 'MM/dd/yyyy') AS date_done, t4.classification

FROM repair_form AS t1 
LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
LEFT JOIN order_status_log AS t3 ON t2.id = t3.import_orders_id
LEFT JOIN rma_faults_log AS t4 ON t1.id = t4.id_of_rma
WHERE t1.import_orders_id IS NOT NULL AND t3.order_status_id = 12  AND (t1.received_date < '02/01/2019' AND t3.date > '01/01/2019')  ORDER BY t1.received_date ASC  limit 100 offset 0
*/
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $final_result;
    //$response['test'] = $rows_in_result; 
    //$response['test'] = $query; 
    $response['data2'] = $result2;
    $response["TotalSound"] = $sound;
    $response["TotalFit"] = $fit;
    $response["TotalDesign"] = $design;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>