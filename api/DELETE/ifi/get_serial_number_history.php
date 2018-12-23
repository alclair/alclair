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
    $orderBySql = " ORDER BY t1.date $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['serial_number']) )
    {
        $conditionSql .= " AND t1.serial_number = :serial_number";
        $params[":serial_number"] = $_REQUEST['serial_number'];
    }

    
    if(!empty($_REQUEST["SearchText"]))
    {
	    if(is_numeric($_REQUEST["SearchText"])) {
		    $conditionSql .= " AND (t1.serial_number = :SearchOrderID)";
		    $params[":SearchOrderID"] = $_REQUEST["SearchText"];
	    }
	    else {
		    $conditionSql .= " AND (t1.serial_number ilike :SearchText)";
			$params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
		}
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
        /*$conditionSql .= " AND (t1.customer_name ilike :SearchText OR t1.order_id = :SearchOrderID)";
        $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        $params[":SearchOrderID"] = $_REQUEST["SearchText"];
        $params[":Order_ID"]=$_REQUEST["SearchText"];
        $response['testing']="%".$_REQUEST["SearchText"]."%";*/
    }
	if(!empty($_REQUEST["StartDate"])) {
		$conditionSql.=" and (t1.date>=:StartDate)";
		$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	if(!empty($_REQUEST["EndDate"])) {
		$conditionSql.=" and (t1.date<=:EndDate)";
		$params[":EndDate"]=date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
	}

    //Get Total Records
    $query = "SELECT count(t1.id) FROM order_tracking AS t1
    					WHERE 1=1 $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
    //Get Total Passed
    $query = "SELECT count(t1.id) FROM serial_numbers AS t1
    					WHERE 1=1 $conditionSql";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['Printed'] = $row[0];

    if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
	
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    $response["test"] = $conditionSql;
    $response["test2"] = $_REQUEST['id'];

        $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date
                  FROM order_tracking AS t1
                  WHERE 1=1 $conditionSql $orderBySql $pagingSql";
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    /*for ($i = 0; $i < $rows_in_result; $i++) {
    	if (date('I', time()))
		{
			$result[$i]["date"] = date("m/d/Y",strtotime($result[$i]["qc_date"]) - 5 * 3600);
		}
		else
		{
			$result[$i]["date"] = date("m/d/Y",strtotime($result[$i]["qc_date"]) - 6 * 3600);
		}
	}*/
    
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