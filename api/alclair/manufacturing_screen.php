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
    //Get Total Records
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
     if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
    
    //Get Total Passed
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE AND t1.manufacturing_screen = TRUE";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['Orders'] = $row[0];

    $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  LEFT JOIN qc_form AS t3 ON t1.id = t3.id_of_order
                  WHERE 1=1 AND t1.active = TRUE AND t1.manufacturing_screen = TRUE AND t1.order_status_id <> 12";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    
    $current_year = date("Y");
    $current_month = date("m");
    $last_year = $current_year - 1;
    $january_current_year = '01/01/' . $current_year;
    $december_current_year = '12/31/' . $current_year;
    
	// SHIPPED LAST YEAR
	$january_last_year = '01/01/' . $last_year;
    $december_last_year = '12/31/' . $last_year;
    $query2 = "SELECT count(t1.id) FROM order_status_log AS t1
    					LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
						LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
						WHERE t1.order_status_id = 12 AND t2.active = TRUE AND t1.date > '$january_last_year' AND t1.date < '$december_last_year'";

    $stmt2 = pdo_query( $pdo, $query2, null); 
    $result2 = pdo_fetch_array( $stmt2 );
    $response['Shipped_Last_Year'] = $result2[0];
    
    // SHIPPED LAST YEAR THIS MONTH
    $beginning_of_month_last_year = $current_month . '/01/' . $last_year;
    $end_of_month_last_year = $current_month . '/31/' . $last_year;
    $query2 = "SELECT count(t1.id) FROM order_status_log AS t1
    					LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
						LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
						WHERE t1.order_status_id = 12 AND t2.active = TRUE AND to_char(t1.date, 'MM')  =  '$current_month' AND to_char(t1.date, 'YYYY') = '$last_year'";

    $stmt2 = pdo_query( $pdo, $query2, null); 
    $result2 = pdo_fetch_array( $stmt2 );
    $response['Shipped_Last_Year_This_Month'] = $result2[0];
    
    
    // SHIPPED THIS YEAR
	$january_current_year = '01/01/' . $current_year;
    $december_current_year = '12/31/' . $current_year;
    $query2 = "SELECT count(t1.id) FROM order_status_log AS t1
    					LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
						LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
						WHERE t1.order_status_id = 12 AND t2.active = TRUE AND t1.date > '$january_current_year' AND t1.date < '$december_current_year'";

    $stmt2 = pdo_query( $pdo, $query2, null); 
    $result2 = pdo_fetch_array( $stmt2 );
    $response['Shipped_This_Year'] = $result2[0];
	 
	 // SHIPPED THIS YEAR THIS MONTH
    $beginning_of_month_current_year = $current_month . '/01/' . $current_year;
    $end_of_month_current_year = $current_month . '/31/' . $current_year;
    $query2 = "SELECT count(t1.id) FROM order_status_log AS t1
    					LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
						LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
						WHERE t1.order_status_id = 12 AND t2.active = TRUE AND to_char(t1.date, 'MM') = '$current_month' AND to_char(t1.date, 'YYYY') =  '$current_year'";

    $stmt2 = pdo_query( $pdo, $query2, null); 
    $result2 = pdo_fetch_array( $stmt2 );
    $response['Shipped_This_Year_This_Month'] = $result2[0];


    $response["test"] = $current_year;
	$response["test"] = $response['Shipped_This_Year_This_Month'];
	
	$response["this_year"] = $current_year;
	$response["last_year"] = $last_year;
	$current_month_fullname = date("F");
	$response["this_month"] = $current_month_fullname;
    
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