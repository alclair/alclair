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
        $conditionSql = " and id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

   if(!empty($_REQUEST["SearchText"]))
    {
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
        $conditionSql .= " and (t1.ticket_number ilike :SearchText)";
        $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        $params[":FileNumber"]=$_REQUEST["SearchText"];
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
	
    //if(!empty($_REQUEST["SearchDisposalWell"]))
    {
    //    $conditionSql .= " and (t1.disposal_well_id=:DisposalWellId)";
        
    //    $params[":DisposalWellId"]=$_REQUEST["SearchDisposalWell"];
    }
    
    if(!empty($_REQUEST["SearchOutgoingTicketTypes"]))
    {
        $conditionSql .= " and (t1.type_id=:OutgoingTicketTypes)";
        
        $params[":OutgoingTicketTypes"]=$_REQUEST["SearchOutgoingTicketTypes"];
    }
    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }
	
    //Get Total Records
    $query = "SELECT
					  (SELECT COUNT(*) FROM ticket_tracker_landfill as t1 WHERE 1=1 $conditionSql)+
					  (SELECT COUNT(*) FROM ticket_tracker_oil as t1 WHERE 1=1 $conditionSql)+
					  (SELECT COUNT(*) FROM ticket_tracker_water as t1 WHERE 1=1 $conditionSql)
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
    if( isset($_REQUEST['id']) )
    {        
        $query = "SELECT t1.ticket_number, t1.date_delivered FROM ticket_tracker_landfill as t1 WHERE t1.id = id
						  UNION
						  SELECT t1.ticket_number, t1.date_delivered FROM ticket_tracker_oil as t1 WHERE t1.id = id 
						  UNION
						  SELECT t1.ticket_number, t1.date_delivered FROM ticket_tracker_water as t1 WHERE t1.id = id ORDER BY date_delivered asc";
    }
    else
    {
        $query = "SELECT t1.ticket_number, to_char(t1.date_delivered, 'MM/dd/yyyy') as date_delivered, tons, null as barrels_delivered, total_dollars, id, 'Solids' as type FROM ticket_tracker_landfill as t1 WHERE id = id $conditionSql 
						  UNION
						  SELECT t1.ticket_number, to_char(t1.date_delivered, 'MM/dd/yyyy') as date_delivered, null as tons, barrels_delivered, total_dollars,  id, 'Oil' as type FROM ticket_tracker_oil as t1 WHERE id = id $conditionSql 
						  UNION
						  SELECT t1.ticket_number, to_char(t1.date_delivered, 'MM/dd/yyyy') as date_delivered, null as tons, barrels_delivered, null as total_dollars, id, 'Water' as type FROM ticket_tracker_water as t1 WHERE id = id $conditionSql ORDER BY date_delivered asc";
    }
    
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