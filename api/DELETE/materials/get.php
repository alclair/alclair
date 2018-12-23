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
    $orderBySqlDirection = "desc";
    $orderBySql = " order by id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql = " and material_id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

    /*if(!empty($_REQUEST["SearchText"]))
    {
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
        $conditionSql .= " and (t1.name ilike :SearchText)";
        $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        //$params[":FileNumber"]=$_REQUEST["SearchText"];
    }

	if(!empty($_REQUEST["StartDate"]))
	{
		$conditionSql.=" and (t1.date_delivered>=:StartDate)";
		$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	if(!empty($_REQUEST["EndDate"]))
	{
		$conditionSql.=" and (t1.date_delivered<=:EndDate)";
		$params[":EndDate"]=$_REQUEST["EndDate"];
	}

    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }*/

    //Get Total Records
    $query = "SELECT count(t1.id) from materials as t1 
		    		  WHERE active = 1 $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];

   /* if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }*/
	
	$params[":session_userid"]=$_SESSION['UserId'] ;
    //Get One Page Records
    if( isset($_REQUEST['id']) )
    {        
        $query = "SELECT t1.*, to_char(t1.time_stamp,'MM/dd/yyyy') as time_stamp
				  		  FROM materials as t1
                          WHERE t1.id = :id AND active = 1";
    }
    else
    {
        $query = "SELECT t1.*, to_char(t1.time_stamp,'MM/dd/yyyy') as time_stamp
                          FROM materials as t1
						  WHERE active = 1 $conditionSql $orderBySql $pagingSql";
    }    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    $query = "SELECT id, material_id, what_is_left, unit_cost, (what_is_left*unit_cost) as multiplication FROM materials_tracker WHERE active = TRUE AND reason = 'Purchase'";
	$stmt = pdo_query( $pdo, $query, null); 
	$multiplication = pdo_fetch_all( $stmt );
    $rows_in_multiplication = pdo_rows_affected($stmt);
    
    $ind = 0;
    for ($x = 0; $x < $rows_in_result; $x++) {
	    $sum_total_cost = 0;
	    $sum_current_quantity = 0;
	    for ($y = 0; $y < $rows_in_multiplication; $y++) {
		    if($multiplication[$y]['material_id'] == $result[$x]['id']) {
			    $sum_total_cost = $multiplication[$y]['multiplication'] + $sum_total_cost;
				$sum_current_quantity = $multiplication[$y]['what_is_left'] + $sum_current_quantity;	    
		    }
		 }
		$result[$ind]['total_cost'] = $sum_total_cost;
		$result[$ind]['current_quantity'] = $sum_current_quantity;
		$ind = $ind + 1;
	}
    
    $query = "SELECT t1.*, to_char(t1.date_uploaded,'MM/dd/yyyy') as date_uploaded, uploaded_by_id, material_id
                       FROM materials_indexupload AS t1
                       LEFT JOIN materials AS t2 ON t1.material_id = t2.id
                       WHERE t2.active=1 $conditionSql"; // WHERE THE MATERIAL IS ACTIVE
	$stmt = pdo_query( $pdo, $query, $params); 
    $result2 = pdo_fetch_all( $stmt );
	
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