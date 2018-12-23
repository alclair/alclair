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
	if($_REQUEST["id"] !='')
	{
		 //Get One Page Records
		$stmt = pdo_query( $pdo,
						   'select ticket_tracker_rate.id, ticket_tracker_rate.billto_option, ticket_tracker_rate.barrel_rate,ticket_tracker_rate.disposal_well_id,
                           ticket_tracker_rate.billto_operator_id, ticket_tracker_rate.water_type_id,
                           ticket_tracker_well.file_number as source_well_filenumber, ticket_tracker_well.current_well_name as source_well_name,
                           ticket_tracker_operator.name as operator_name,ticket_tracker_rate.trucking_company_id,use_default
                           
                           from ticket_tracker_rate
                           join ticket_tracker_well on ticket_tracker_rate.source_well_id=ticket_tracker_well.id
                           join ticket_tracker_operator on ticket_tracker_well.current_operator_id=ticket_tracker_operator.id
                           
                           where ticket_tracker_rate.id=:id',
							array(":id"=>$_REQUEST["id"])
						);	
		$result = pdo_fetch_array($stmt,PDO::FETCH_ASSOC);
		$response['code'] = 'success';
		$response['data'] = $result;
	}
	else
	{
        $conditionSql = "";
        $params = null;
        if(!empty($_REQUEST["SearchText"]))
        {
            $conditionSql = " and (ticket_tracker_well.current_well_name ilike :SearchText or ticket_tracker_disposalwell.common_name ilike :SearchText or ticket_tracker_operator.name ilike :SearchText or ticket_tracker_well.file_number=:SearchTextInt)";
            
            $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";       
            $params[":SearchTextInt"]=intval($_REQUEST["SearchText"]);
        }

		$pagingSql='';
		if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
		{
			$start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
		   
			$pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
		}
		 //Get Total Records
		$query = "select count(ticket_tracker_rate.id) from ticket_tracker_rate 
        join ticket_tracker_well on ticket_tracker_rate.source_well_id=ticket_tracker_well.id
                           join ticket_tracker_operator on ticket_tracker_well.current_operator_id=ticket_tracker_operator.id
                           join ticket_tracker_disposalwell on ticket_tracker_rate.disposal_well_id=ticket_tracker_disposalwell.id
                           join ticket_tracker_watertype on ticket_tracker_rate.water_type_id=ticket_tracker_watertype.id 
                           where 1=1 $conditionSql";
		$stmt = pdo_query( $pdo, $query, $params);
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
		$stmt = pdo_query( $pdo,
						   "select ticket_tracker_rate.id, ticket_tracker_rate.billto_option, ticket_tracker_rate.barrel_rate,
                           (select name from ticket_tracker_operator where id=ticket_tracker_rate.billto_operator_id) as billto_operator_name,
                           (select name from ticket_tracker_truckingcompany where id=ticket_tracker_rate.trucking_company_id) as trucking_company_name,
                           ticket_tracker_well.file_number as source_well_filenumber, ticket_tracker_well.current_well_name as source_well_name,
                           ticket_tracker_operator.name as operator_name,
                           ticket_tracker_watertype.type as water_type_name, ticket_tracker_disposalwell.common_name as disposal_well_name,use_default
                           from ticket_tracker_rate
                           join ticket_tracker_well on ticket_tracker_rate.source_well_id=ticket_tracker_well.id
                           join ticket_tracker_operator on ticket_tracker_well.current_operator_id=ticket_tracker_operator.id
                           join ticket_tracker_disposalwell on ticket_tracker_rate.disposal_well_id=ticket_tracker_disposalwell.id
                           join ticket_tracker_watertype on ticket_tracker_rate.water_type_id=ticket_tracker_watertype.id
                           
                           where 1 = 1 $conditionSql order by id ".$pagingSql,
							$params
						);	
		$result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
		$response['code'] = 'success';
		$response['data'] = $result;
		//echo 'select * from ticket_tracker_operator '.$pagingSql;
		//var_export($response['data']);
	}
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>