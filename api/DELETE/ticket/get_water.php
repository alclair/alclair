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
        $conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
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
    $query = "select count(t1.id) from ticket_tracker_water as t1
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
        $query = "SELECT t1.*, to_char(t1.date_delivered,'MM/dd/yyyy') as date_delivered, t1.barrel_rate, 
                  to_char(t1.date_created,'MM/dd/yyyy') as date_created,  twell.file_number as source_well_file_number, twell.current_well_name as source_well_name, toperator.name as source_well_operator_name,toperator.id as operator_id, t2.type as water_type_name, t3.common_name as disposal_well_name, t4.name as company_name, t5.name as tank_name, t6.type as fluid_type
                  FROM ticket_tracker_water as t1
                  left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                  left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                  left join ticket_tracker_watertype as t2 on t1.water_type_id = t2.id
                  left join ticket_tracker_disposalwell as t3 on t1.disposal_well_id = t3.id
                  left join ticket_tracker_truckingcompany as t4 on t1.trucking_company_id = t4.id 
                  LEFT JOIN tanks as t5 ON t1.tank_id = t5.id
                  LEFT JOIN ticket_tracker_fluidtype as t6 ON t1.fluid_type_id = t6.id
                  where t1.id = :id";
    }
    else
    {
        $query = "SELECT t1.id, t1.ticket_number, to_char(t1.date_delivered,'MM/dd/yyyy') as date_delivered, t1.barrels_delivered, twatertype.type as water_type_name, t1.barrel_rate,
        twell.file_number as source_well_file_number, twell.current_well_name as source_well_name, toperator.name as source_well_operator_name, t3.common_name as disposal_well_name, toperator.id as operator_id, t5.name as tank_name, t6.type as fluid_type
                  FROM ticket_tracker_water as t1
				  left join ticket_tracker_disposalwell as t3 on t1.disposal_well_id = t3.id
                  left join ticket_tracker_well as twell on t1.source_well_id =twell.id
                  left join ticket_tracker_operator toperator on twell.current_operator_id=toperator.id
                  left join ticket_tracker_watertype as twatertype on t1.water_type_id = twatertype.id
                  LEFT JOIN tanks as t5 ON t1.tank_id = t5.id
                  LEFT JOIN ticket_tracker_fluidtype as t6 ON t1.fluid_type_id = t6.id
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