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
    $params = array();
    
    $Earphones_list = [];
    $BAs_list = [];
    
    if( !empty($_REQUEST['id']) )
    {
        //$conditionSql .= " AND t1.id = :id";
        //$params[":id"] = $_REQUEST['id'];
    }
   
    if ($_REQUEST['RUSH_OR_NOT'] == 1) {
			//$conditionSql .=" AND (t1.rush_process = 'Yes')";
			//$conditionSql .= " AND (t1.order_status_id != 12)";
    }
    if ($_REQUEST['REMOVE_HEARING_PROTECTION'] == 1) {
			//$conditionSql .=" AND (t1.hearing_protection != TRUE AND (t1.product IS NOT NULL AND t1.model IS NOT NULL)";
			$conditionSql .= " AND (t1.product IS NOT NULL AND t1.model IS NOT NULL)";
			//$conditionSql .=" AND ( (t1.hearing_protection != TRUE AND t1.model IS NOT NULL) OR (t1.hearing_protection != TRUE AND t1.model IS NULL) )";
			//$conditionSql .= " AND (t1.order_status_id != 12)";
			
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    
    $StartDate = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00'));
    $EndDate = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
     // GETS ALL ORDERS BETWEEN START CART AND CASING
    $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date, to_char(t1.fake_imp_date,'yyyy/MM/dd') as fake_imp_date,IEMs.id AS monitor_id, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE t1.active = TRUE AND (t1.order_status_id <= 5 OR t1.order_status_id = 16)  AND (t1.product IS NOT NULL AND t1.model IS NOT NULL) AND IEMs.name = :iem_name AND t1.estimated_ship_date >= :StartDate AND t1.estimated_ship_date <= :EndDate ORDER BY t1.estimated_ship_date ASC";
                
    $stmt = pdo_query( $pdo, $query, array(":iem_name"=>$_REQUEST["iem_name"], ":StartDate"=>$StartDate, ":EndDate"=>$EndDate)); 
    $first_sql = pdo_fetch_all( $stmt );
    $rows_first_sql = pdo_rows_affected($stmt);
   			
	$response["test"] = "First is " . $_REQUEST["iem_name"] .  " and Second is " . $StartDate . " and 3rd is " . $EndDate . "  and # of rows is " . $rows_first_sql;
	//echo json_encode($response);
	//exit;
	
	 //Get Total Records
    $response['TotalRecords'] = count($store_order);
    $response['testing'] = $testing;
    $response['TotalRecords'] = $rows_first_sql;

   
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $first_sql;//$Earphones_list;
    $response['data2'] = $BAs_list;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    

//////        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>