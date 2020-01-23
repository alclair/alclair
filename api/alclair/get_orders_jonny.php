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
    $orderBySql = " ORDER BY order_id $orderBySqlDirection";
    $params = array();
    
    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }
    
    $response['the_user_is'] = $_SESSION['UserName'];

    if(!empty($_REQUEST["SearchText"]))
    {
	    if(is_numeric($_REQUEST["SearchText"])) {
		    $conditionSql .= " AND (t1.order_id = :SearchOrderID)";
		    $params[":SearchOrderID"] = $_REQUEST["SearchText"];
	    }
	    else {
		    //$conditionSql .= " AND (t1.designed_for ilike :SearchText)";
			//$params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
			
			$conditionSql .= " AND (t1.billing_name ilike :SearchText OR t1.designed_for ilike :SearchText OR t1.shipping_name ilike :SearchText)";
			$params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
		}
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
        /*$conditionSql .= " AND (t1.customer_name ilike :SearchText OR t1.order_id = :SearchOrderID)";
        $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        $params[":SearchOrderID"] = $_REQUEST["SearchText"];
        $params[":Order_ID"]=$_REQUEST["SearchText"];
        $response['testing']="%".$_REQUEST["SearchText"]."%";*/
    }
    else if ($_REQUEST['RUSH_OR_NOT'] == 1) {
			$conditionSql .=" AND (t1.rush_process = 'Yes')";
			$conditionSql .= " AND (t1.order_status_id != 12)";
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    else {
		if($_REQUEST['PRINTED_OR_NOT'] != '0' && empty($_REQUEST['id'])) {
		    if($_REQUEST['PRINTED_OR_NOT'] ==  'TRUE') {
			    $conditionSql .= " AND (t1.printed = TRUE)";    
			} else {
	        	$conditionSql .= " AND (t1.printed = FALSE OR t1.printed IS NULL)";
				//$params[":printed"] = $_REQUEST['PRINTED_OR_NOT'];
			}
    	}
		if($_REQUEST['ORDER_STATUS_ID'] >= 1) {
		    //$response['message'] = 'Please ' . $_REQUEST['ORDER_STATUS_ID'];
			//echo json_encode($response);
			//exit;	
			$conditionSql .= " AND (t1.order_status_id = :OrderStatusID)";
			$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    	}  else {
	    
	    		  if($_REQUEST["USE_IMPRESSION_DATE"] == 1) {
				  if(!empty($_REQUEST["StartDate"])) {
				  	$conditionSql.=" and (t1.received_date>=:StartDate)";
				  	$params[":StartDate"]=$_REQUEST["StartDate"];
					}
				if(!empty($_REQUEST["EndDate"])) {
					$conditionSql.=" and (t1.received_date<=:EndDate)";
					$params[":EndDate"]=date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
				}

    			} else {
				if(!empty($_REQUEST["StartDate"])) {
					$conditionSql.=" and (t1.date>=:StartDate)";
					$params[":StartDate"]=$_REQUEST["StartDate"];
				}
				if(!empty($_REQUEST["EndDate"])) {
					$conditionSql.=" and (t1.date<=:EndDate)";
					$params[":EndDate"]=date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
				}
			}
		}
		
	} // END ELSE STATEMENT
	/*if(!empty($_REQUEST["StartDate"]))
	{
		if (date('I', time()))
		{	
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00') + 5 * 3600);
		}
		else
		{
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')+ 6 * 3600);
		}
		$conditionSql.=" and (t1.date>=:StartDate)";
		$params[":StartDate"]=$TIME_START;
		//$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	
	if(!empty($_REQUEST["EndDate"]))
	{
		if (date('I', time()))
		{
			$TIME_END = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 5 * 3600);
		}
		else
		{
			$TIME_END = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 6 * 3600);
		}
		$conditionSql.=" and (t1.date<=:EndDate)";
		$params[":EndDate"]=$TIME_END;
		//$params[":EndDate"]=$_REQUEST["EndDate"];
	}*/
	
	
    /*if(!empty($_REQUEST["SearchDisposalWell"]))
    {
        $conditionSql .= " and (t1.disposal_well_id=:DisposalWellId)";
        
        $params[":DisposalWellId"]=$_REQUEST["SearchDisposalWell"];
    }*/
    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE AND nashville_order = TRUE $conditionSql";
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
    					WHERE 1=1 AND t1.active = TRUE $conditionSql AND t1.printed = TRUE AND t1.nashville_order = TRUE";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['Printed'] = $row[0];

   
	
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    $response["test"] = $conditionSql;
    $response["test2"] = $_REQUEST['id'];
    /*if( !empty($_REQUEST['orderID']) )
    {        
        $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date
                  FROM import_orders AS t1
                  WHERE 1=1 $conditionSql"; //t1.id = :id";
    }
    else
    {*/
        $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date,IEMs.id AS monitor_id, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE 1=1 AND t1.active = TRUE AND t1.nashville_order = TRUE $conditionSql $orderBySql $pagingSql";
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    $query2 = "SELECT *, to_char(date, 'MM/dd/yyyy HH24:MI') as date_moved, t2.status_of_order, t3.first_name, t3.last_name
    					FROM order_status_log 
    					LEFT JOIN order_status_table AS t2 ON order_status_log.order_status_id = t2.order_in_manufacturing
    					LEFT JOIN auth_user AS t3 ON order_status_log.user_id = t3.id
    					WHERE import_orders_id = :import_orders_id
    					ORDER BY date DESC";
    $params2[":import_orders_id"] = $_REQUEST['id'];
    $stmt2 = pdo_query( $pdo, $query2, $params2); 
	$result2 = pdo_fetch_all( $stmt2 );  
	$rows_in_result2 = pdo_rows_affected($stmt2);
   
    for ($i = 0; $i < $rows_in_result2; $i++) {
    	if (date('I', time()))
		{
			$result2[$i]["date_to_show"] = date("m/d/Y  h:i A",strtotime($result2[$i]["date_moved"]) );
			$result2[$i]["date_to_show_date"] = date("m/d/Y",strtotime($result2[$i]["date_moved"]) );
			//$result2[$i]["date_to_show_hours"] = date("h:i A",strtotime($result2[$i]["date_moved"]) );
			$result2[$i]["date_to_show_hours"] = date("h:i A",strtotime($result2[$i]["date_moved"]) - 6*3600 );
		}
		else
		{
			$result2[$i]["date_to_show"] = date("m/d/Y  h:i A",strtotime($result2[$i]["date_moved"]) );
			$result2[$i]["date_to_show_date"] = date("m/d/Y ",strtotime($result2[$i]["date_moved"]) );
			//$result2[$i]["date_to_show_hours"] = date("h:i A",strtotime($result2[$i]["date_moved"]) );		
			$result2[$i]["date_to_show_hours"] = date("h:i A",strtotime($result2[$i]["date_moved"]) - 5*3600 );

		}
	}
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['data2'] = $result2;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>