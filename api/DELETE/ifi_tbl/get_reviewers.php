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
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND id = :id";
        $params[":id"] = $_REQUEST['id'];
    }


	if($_REQUEST["product_id"] >= 1)
    {
		$conditionSql .= " AND (t1.product_id = :product_id)";
		$params[":product_id"] = $_REQUEST["product_id"];
	}
	if($_REQUEST["category_id"] >= 1)
    {
		$conditionSql .= " AND (t1.category_id = :category_id)";
		$params[":category_id"] = $_REQUEST["category_id"];
	}
	if( $_REQUEST["status"] != undefined)
    {
	    if ($_REQUEST["status"] != '0') {
	   		$conditionSql .= " AND (t1.status = :status)";
	   		$params[":status"] = $_REQUEST["status"];
	   		
	   		$response["test"] = $_REQUEST["status"];
	   		//echo json_encode($response);
	   		//exit;
		}
	}
    
    if(!empty($_REQUEST["SearchText"]))
    {
	    if(is_numeric($_REQUEST["SearchText"])) {
		    $conditionSql .= " AND (t1.firstname = :firstname)";
		    $params[":firstname"] = $_REQUEST["SearchText"];
	    }
	    else {
		    $conditionSql .= " AND (t1.firstname ilike :SearchText)";
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
    $query = "SELECT count(t1.id) FROM reviewers AS t1
    					WHERE 1=1 $conditionSql AND active = TRUE";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
    //Get Total Passed
    $query = "SELECT count(t1.id) FROM reviewers AS t1
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
	 if( isset($_REQUEST['id']) ) {        
	     $query = "SELECT t1.*, t2.title AS title, t3.ifi_tag AS tag, t4.iclub AS iClub, t5.status AS status, t6.country AS country
                  FROM reviewers AS t1
                  LEFT JOIN tbl_title AS t2 ON t1.title_id = t2.id
                  LEFT JOIN tbl_ifi_tag AS t3 ON t1.tag_id = t3.id
                  LEFT JOIN tbl_iclub AS t4 ON t1.iclub_id = t4.id
                  LEFT JOIN tbl_status AS t5 ON t1.status_id = t5.id
                  LEFT JOIN tbl_country AS t6 ON t1.country_id = t6.id

                  WHERE t1.id = :id";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    	
    	$query2 = "SELECT t1.*, t2.sector AS sector
                  FROM reviewers_jobs AS t1
                  LEFT JOIN tbl_sector AS t2 ON t1.sector_id = t2.id
                  WHERE t1.reviewer_id = :id AND active = true";
                  
		$stmt2 = pdo_query( $pdo, $query2, $params); 
		$result_jobs = pdo_fetch_all( $stmt2 );
		$response['data_jobs'] = $result_jobs;
		
		$query3 = "SELECT t1.*, t2.sector AS sector
                  FROM reviewers_usernames AS t1
                  LEFT JOIN tbl_sector AS t2 ON t1.sector_id = t2.id
                  WHERE t1.reviewer_id = :id AND active = true";
                  
		$stmt3 = pdo_query( $pdo, $query3, $params); 
		$result_usernames = pdo_fetch_all( $stmt3 );
		$response['data_usernames'] = $result_usernames;
		
		$query4 = "SELECT t1.*
                  FROM shipping_requests AS t1
                  WHERE t1.reviewer_id = 2 AND active = true";
                  
		$stmt4 = pdo_query( $pdo, $query4, $params); 
		$result_shipping_requests = pdo_fetch_all( $stmt4 );
		$response['data_shipping_requests'] = $result_shipping_requests;

		

     } else {   
            $query = "SELECT t1.*, t2.title AS title, t3.ifi_tag AS tag, t4.iclub AS iClub, t5.status AS status, t6.country AS country
                  FROM reviewers AS t1
                  LEFT JOIN tbl_title AS t2 ON t1.title_id = t2.id
                  LEFT JOIN tbl_ifi_tag AS t3 ON t1.tag_id = t3.id
                  LEFT JOIN tbl_iclub AS t4 ON t1.iclub_id = t4.id
                  LEFT JOIN tbl_status AS t5 ON t1.status_id = t5.id
                  LEFT JOIN tbl_country AS t6 ON t1.country_id = t6.id

                  WHERE active=TRUE $conditionSql $orderBySql $pagingSql";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    }
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
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>