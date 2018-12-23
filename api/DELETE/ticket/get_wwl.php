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
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or t2.type ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
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
    if(!empty($_REQUEST["SearchDisposalWell"]))
    {
        $conditionSql .= " and (t1.disposal_well_id=:DisposalWellId)";
        
        $params[":DisposalWellId"]=$_REQUEST["SearchDisposalWell"];
    }
    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "select count(t1.id) from ticket_tracker_ticket as t1
    left join ticket_tracker_fluidtype as t2 on t2.id = t1.fluid_type_id
    left join ticket_tracker_well as twell on t1.source_well_id =twell.id
    where 1 = 1 $conditionSql";
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
        $query = "select t1.*, to_char(t1.date_delivered,'MM/dd/yyyy') as date_delivered, t1.barrel_rate, 
                  to_char(t1.date_created,'MM/dd/yyyy') as date_created, twell.file_number as source_well_file_number, twell.current_well_name as source_well_name, toperator.name as source_well_operator_name,toperator.id as operator_id, t2.type as fluid_type, t4.name as company_name
                  from ticket_tracker_ticket as t1 
                  left join ticket_tracker_fluidtype as t2 on t2.id = t1.fluid_type_id
                  left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                  left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                  left join ticket_tracker_truckingcompany as t4 on t1.trucking_company_id = t4.id 
                  where t1.id = :id";
    }
    else
    {
        $query = "select t1.*, to_char(t1.date_delivered,'MM/dd/yyyy') as date_delivered, t1.barrel_rate, 
                  to_char(t1.date_created,'MM/dd/yyyy') as date_created, twell.file_number as source_well_file_number,twell.current_well_name as source_well_name, toperator.name as source_well_operator_name, t2.type as fluid_type, t4.name as company_name
                  from ticket_tracker_ticket as t1 
				  left join ticket_tracker_fluidtype as t2 on t2.id = t1.fluid_type_id
				  left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                  left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                  left join ticket_tracker_truckingcompany as t4 on t1.trucking_company_id = t4.id 
                  where 1 = 1 $conditionSql $orderBySql $pagingSql";
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