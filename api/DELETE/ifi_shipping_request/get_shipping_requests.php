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
        
        //$conditionSql .= " AND t5.username = :username";
        $params[":username"] = $_SESSION["UserName"];
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
	   		$conditionSql .= " AND (t1.approved = :approved)";
	   		$params[":approved"] = $_REQUEST["status"];
	   		
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
    $query = "SELECT count(t1.id) FROM shipping_requests AS t1
    					WHERE 1=1 $conditionSql AND active = TRUE";
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
	
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    //$response["test"] = $conditionSql;
    $response["test"] =  $params[":username"];

	 if( isset($_REQUEST['id']) ) {        
	     $query = "SELECT t1.*, t2.first_name, t2.last_name, t3.title, t5.ifi_tag, t6.iclub, t7.status, t8.reason, t9.unit_type
                  FROM shipping_requests AS t1
                  LEFT JOIN auth_user AS t2 ON t1.request_made_by = t2.id
                  LEFT JOIN tbl_title AS t3 ON t1.title_id = t3.id
                  LEFT JOIN reviewers AS t4 ON t1.reviewer_id = t4.id
                  LEFT JOIN tbl_ifi_tag AS t5 ON t4.tag_id = t5.id
                  LEFT JOIN tbl_iclub AS t6 ON t4.iclub_id = t6.id
                  LEFT JOIN tbl_status AS t7 ON t4.status_id = t7.id
                  LEFT JOIN tbl_reason AS t8 ON t1.reason_id = t8.id
                  LEFT JOIN tbl_unit_type AS t9 ON t1.unit_type_id = t9.id
                  WHERE t1.id = :id AND t1.active = TRUE";
			$stmt = pdo_query( $pdo, $query, $params); 
			$result = pdo_fetch_all( $stmt );
			$rows_in_result = pdo_rows_affected($stmt);
			
			$query = "SELECT t1.*, t2.*, t3.name AS category, t4.name AS product_name, t5.username, t6.new_or_demo
                  FROM shipping_requests AS t1
                  LEFT JOIN product_requested AS t2 ON t1.id = t2.request_id
                  LEFT JOIN product_categories AS t3 ON t2.category_id = t3.id
                  LEFT JOIN products AS t4 ON t2.product_id = t4.id
                  LEFT JOIN auth_user as t5 ON :username = t5.username
                  LEFT JOIN tbl_new_or_demo AS t6 ON t2.new_or_demo_id = t6.id
                  WHERE t1.id = :id AND t1.active = TRUE AND t4.active = TRUE ORDER BY category ASC";
			$stmt = pdo_query( $pdo, $query, $params); 
			$result_product = pdo_fetch_all( $stmt );
			$rows_in_result_product = pdo_rows_affected($stmt);
		
		$new_result_product = array();
		$ind = 0;

		for($x = 1; $x <= $rows_in_result_product; $x++) {
			if($result_product[$x]["category"] == $result_product[$x-1]["category"] && $result_product[$x]["product_name"] == $result_product[$x-1]["product_name"]) {
			} else {
				$new_result_product[$ind] = $result_product[$x-1];
				$ind = $ind + 1;
			}
		}
			
		$response['data_product'] = $new_result_product;
		//$response['data_product'] = $result_product;

				  
    	
     } else {   
            $query = "SELECT  t1.*, t2.first_name, t2.last_name, t3.title
                  FROM shipping_requests AS t1
                  LEFT JOIN auth_user AS t2 ON t1.request_made_by = t2.id
                  LEFT JOIN tbl_title AS t3 ON t1.title_id = t3.id
                  WHERE active=TRUE $conditionSql $orderBySql $pagingSql";
			$stmt = pdo_query( $pdo, $query, $params); 
			$result = pdo_fetch_all( $stmt );
			$rows_in_result = pdo_rows_affected($stmt);
    }
    for ($i = 0; $i < $rows_in_result; $i++) {
    	if (date('I', time()))
		{
			$result[$i]["date"] = date("m/d/Y",strtotime($result[$i]["date"]) - 5 * 3600);
		}
		else
		{
			$result[$i]["date"] = date("m/d/Y",strtotime($result[$i]["date"]) - 6 * 3600);
		}
	}
    
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