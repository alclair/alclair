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
						   'select t1.id, t1.current_well_name as name, t1.township, t1.rng as range, t1.county as county_name, t1.field_name, 
                           t1.current_operator_id as operator_id, t1.section, t1.qq, t1.file_number, t1.api_number, t2.name as operator_name, t3.id as field_name_id, t4.id as 												   county_name_id
                           from ticket_tracker_well as t1
                           left join ticket_tracker_operator as t2 on t1.current_operator_id = t2.id
                           left join ticket_tracker_field as t3 on t1.field_name = t3.name
						   left join ticket_tracker_county as t4 on t1.county = t4.name
                           where t1.id = :id',
							array( ":id" => $_REQUEST["id"])
                            //,1
						);	
		$result = pdo_fetch_array($stmt);
		$response['code'] = 'success';
		$response['data'] = $result;
	}
	else
	{
        $conditionSql = "";
        $params = null;
        if(!empty($_REQUEST["SearchText"]))
        {
            $conditionSql = " and (t1.current_well_name ilike :SearchText or t1.county ilike :SearchText or t1.field_name ilike :SearchText )";
            $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        }

		$pagingSql='';
		if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
		{
			$start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
		   
			$pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
		}
		 //Get Total Records
		$query = "select count(id) from ticket_tracker_well ";
		$stmt = pdo_query( $pdo, $query, null );
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
						   "select t1.id, t1.current_well_name as name, t1.township, t1.rng as range, t1.county as county_name, t1.field_name as field_name, 
                           t1.current_operator_id as operator_id, t1.section, t1.qq as qq, t1.file_number, t1.api_number, t2.name as operator_name, t3.id as field_name_id, t4.id as 									   county_name_id
                           from ticket_tracker_well as t1
                           left join ticket_tracker_operator as t2 on t1.current_operator_id = t2.id
                           left join ticket_tracker_field as t3 on t1.field_name = t3.name
                           left join ticket_tracker_county as t4 on t1.county = t4.name
                           where 1 = 1 $conditionSql 
                           order by t1.current_well_name ".$pagingSql,
							$params
						);	
		$result = pdo_fetch_all($stmt);
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