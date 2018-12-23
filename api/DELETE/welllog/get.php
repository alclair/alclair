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
    $orderBySql = " order by t1.date_logged $orderBySqlDirection"; 
    $params = null;

    if( isset($_REQUEST['id']) )
    {
        $conditionSql = " and id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

    if(!empty($_REQUEST["SearchText"]))
    {
		if(intval($_REQUEST["SearchText"])>0)
		{
			$conditionSql = " and t3.id=  :SearchText";
			$params[":SearchText"] = intval($_REQUEST["SearchText"]);
		}
		else
		{
			$conditionSql = " and (t3.common_name ilike :SearchText or notes ilike :SearchText)";
			$params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
		}	
		
    }

   
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
        
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "select count(id) from well_logs_dailywelllog where 1 = 1 $conditionSql"; 
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
    if( isset($_REQUEST['id']) )
    {        
        $query = "select t1.*, to_char(t1.date_logged,'MM/dd/yyyy') as date_logged, 
                  to_char(t1.date_created,'MM/dd/yyyy') as date_created,  
                  t3.common_name as disposal_well_name
                  from well_logs_dailywelllog as t1                   
                  left join ticket_tracker_disposalwell as t3 on t1.disposal_well_id = t3.id                  
                  where t1.id = :id";
    }
    else
    {
        $query = "select t1.*, to_char(t1.date_logged,'MM/dd/yyyy') as date_logged, t3.common_name as disposal_well_name   
                  from well_logs_dailywelllog as t1 
                  left join ticket_tracker_disposalwell as t3 on t1.disposal_well_id = t3.id  
                  where 1 = 1 $conditionSql $orderBySql $pagingSql"; 
    }    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );




    $response['code'] = 'success';
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