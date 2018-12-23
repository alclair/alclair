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


    if(!empty($_REQUEST["SearchText"]))
    {
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
        $conditionSql .= " and (t2.first_name ilike :SearchText or t2.last_name ilike :SearchText)";
        $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        //$params[":FileNumber"]=$_REQUEST["SearchText"];
    }
	if(!empty($_REQUEST["StartDate"]))
	{
		$conditionSql.=" and (t1.date_created>=:StartDate)";
		$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	if(!empty($_REQUEST["EndDate"]))
	{
		$conditionSql.=" and (t1.date_created<=:EndDate)";
		$params[":EndDate"]=$_REQUEST["EndDate"];
	}
    
     if(!empty($_REQUEST["SearchUserLogTypes"]))
    {
        $conditionSql .= " and (t1.type_id=:UserLogTypes)";
        
        $params[":UserLogTypes"]=$_REQUEST["SearchUserLogTypes"];
    }

    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT
    				(COUNT(t1.id) FROM maintenance as t1 WHERE 1 = 1 $conditionSql)+
    				(COUNT(t1.id) FROM inventory as t1 WHERE 1 = 1 $conditionSql)
    				AS count";
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

    //Get One Page Records
           $query = "select t1.id, t1.created_by_id, to_char(t1.date_created,'MM/dd/yyyy') as date_created, to_char(t1.date_closed,'MM/dd/yyyy') as date_closed,
			t2.first_name, t2.last_name, 'Maintenance' as type
                  from maintenance as t1
				  left join auth_user as t2 on t1.created_by_id = t2.id
                  where 1 = 1 $conditionSql
                  UNION
                  select t1.id, t1.created_by_id, to_char(t1.date_created,'MM/dd/yyyy') as date_created, to_char(t1.date_closed,'MM/dd/yyyy') as date_closed,
			t2.first_name, t2.last_name, 'Inventory' as type
                  from inventory as t1
				  left join auth_user as t2 on t1.created_by_id = t2.id
                  where 1 = 1 $conditionSql
                  ORDER BY date_created asc";
                   //$conditionSql $orderBySql $pagingSql

    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
	
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