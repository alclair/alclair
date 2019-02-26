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
    $orderBySqlDirection = "DESC";
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND id = :id";
        $params[":id"] = $_REQUEST['id'];
    }
        
	if(!empty($_REQUEST["StartDate"]))
	{
		if (date('I', time()))
		{	
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00') + 5 * 3600);
		}
		else
		{
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')+ 6 * 3600);
		}
		$conditionSql.=" and (t1.received_date>=:StartDate)";
		$params[":StartDate"]=$TIME_START;
		//$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	
        $query = "SELECT * FROM daily_build_rate";
		$stmt = pdo_query( $pdo, $query, null); 
		$result = pdo_fetch_all( $stmt );
		$response["test"] = "HERE";
		//echo json_encode($response);
		//exit;
		//$rows_in_result = pdo_rows_affected($stmt);

    
    /*for ($i = 0; $i < $rows_in_result; $i++) {
    	if (date('I', time()))
		{
			$result[$i]["received_date"] = date("m/d/Y",strtotime($result[$i]["received_date"]) - 5 * 3600);
			$result[$i]["date_entered"] = date("m/d/Y",strtotime($result[$i]["date_entered"]) - 5 * 3600);
			$result[$i]["estimated_ship_date"] = date("m/d/Y",strtotime($result[$i]["estimated_ship_date"]) - 5 * 3600);

		}
		else
		{
			$result[$i]["received_date"] = date("m/d/Y",strtotime($result[$i]["received_date"]) - 6 * 3600);
			$result[$i]["date_entered"] = date("m/d/Y",strtotime($result[$i]["date_entered"]) - 6 * 3600);
			$result[$i]["estimated_ship_date"] = date("m/d/Y",strtotime($result[$i]["estimated_ship_date"]) - 6 * 3600);
		}
	}*/

	 $query2 = "SELECT *, to_char(date, 'MM/dd/yyyy') as holiday_date FROM holiday_log  WHERE active = TRUE ORDER BY date ASC";
    //$params2[":repair_form_id"] = $_REQUEST['id'];
    $stmt2 = pdo_query( $pdo, $query2, null); 
	$result2 = pdo_fetch_all( $stmt2 );  
	
	//$today = now();
    //$tomorrow = $today->modify('+1 day'); 
    $todays_date = new DateTime(); // TODAY'S DATE
    
    $today = new DateTime();
    $todays_date = new DateTime();
	$todays_date->modify('+1 day'); // TOMORROW'S DATE
	$tomorrow = $todays_date;
	
	$today = $today->format('Y-m-d');
	$tomorrow = $tomorrow->format('Y-m-d');
	
	$query4 = "SELECT DISTINCT fake_imp_date FROM import_orders WHERE order_status_id = 1 and active = TRUE AND fake_imp_date >= :today ORDER BY fake_imp_date LIMIT 5";
    $stmt4 = pdo_query( $pdo, $query4, array(":today"=>$today)); 
	$result22 = pdo_fetch_all( $stmt4 );
	
	$index = $_REQUEST['day_to_view'];
	$fake_imp_date = $result22[$index]["fake_imp_date"];
	
	$query4 = "SELECT t1.*, to_char(t1.fake_imp_date, 'MM/dd/yyyy') as fake_imp_date, to_char(t1.estimated_ship_date, 'MM/dd/yyyy') as estimated_ship_date, IEMs.name AS monitor_name
    					FROM import_orders AS t1
    					LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
    					WHERE t1.active = TRUE AND t1.fake_imp_date = :today AND t1.fake_imp_date = :tomorrow
    					ORDER BY t1.fake_imp_date ASC";
	$query4 = "SELECT t1.*, to_char(t1.fake_imp_date, 'MM/dd/yyyy') as fake_imp_date, to_char(t1.estimated_ship_date, 'MM/dd/yyyy') as estimated_ship_date, IEMs.name AS monitor_name
    					FROM import_orders AS t1
    					LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
    					WHERE t1.active = TRUE AND t1.fake_imp_date = :fake_imp_date
    					ORDER BY t1.fake_imp_date ASC";
    //$params2[":repair_form_id"] = $_REQUEST['id'];
    //$stmt4 = pdo_query( $pdo, $query4, array(":today"=>$today, ":tomorrow"=>$tomorrow)); 
    $stmt4 = pdo_query( $pdo, $query4, array(":fake_imp_date"=>$fake_imp_date)); 
	$DailyList = pdo_fetch_all( $stmt4 );  
	
	$query5 = "SELECT *, to_char(estimated_ship_date, 'MM/dd/yyyy') AS estimated_ship_date2 FROM import_orders WHERE order_status_id = 1 AND active = TRUE
    					ORDER BY estimated_ship_date DESC";
	$stmt5 = pdo_query( $pdo, $query5, null); 
	$result5 = pdo_fetch_all( $stmt5 );  
	
	$current_ship_date = $result5[0]["estimated_ship_date2"];
	$response["test"] = $current_ship_date;
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['data_holidays'] = $result2;
    $response['DailyList'] = $DailyList;
	$response['current_ship_date'] = $current_ship_date;
            
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>