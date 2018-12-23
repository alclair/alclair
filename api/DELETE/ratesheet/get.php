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
						   'SELECT t1.id, t1.product_type_id, t1.delivery_method, t1.water_source_type, t1.account_code, t1.trucking_company_id, t1.disposal_well_id, t1.price, t2.type as product_type, t3.name as trucking_company, t4.common_name as disposal_well from rate_sheet as t1
						   LEFT JOIN ticket_tracker_watertype as t2 on t1.product_type_id = t2.id
						   LEFT JOIN ticket_tracker_truckingcompany as t3 on t1.trucking_company_id = t3.id
						   LEFT JOIN ticket_tracker_disposalwell as t4 on t1.disposal_well_id = t4.id
						   WHERE t1.id=:id',
							array(":id"=>$_REQUEST["id"])
						);	
						
		$result = pdo_fetch_array($stmt);
		$response['code'] = 'success';
		$response['data'] = $result;
        echo json_encode($response);        
	}
    else if($_REQUEST["all"]==1)
    {
        $cache_path=$rootScope["RootPath"]."/data/cache/rate_sheet.txt";
        if(file_exists($cache_path))
        {
            $result=file_get_contents($cache_path);
            $response['code'] = 'success';
			$response['data'] = json_decode($result);						
        }
        else
        {
            $stmt = pdo_query( $pdo,
                                   'select  t1.id, t1.product_type_id, t1.product_type_id, t1.delivery_method, t1.water_source_type, t1.account_code, t1.trucking_company_id, t1.disposal_well_id, t1.price, t2.type, t3.name as trucking_company, t4.common_name as disposal_well from rate_sheet as t1 
                                   LEFT JOIN ticket_tracker_watertype as t2 on t1.product_type_id = t2.id
                                   LEFT JOIN ticket_tracker_truckingcompany as t3 on t1.trucking_company_id = t3.id
								   LEFT JOIN ticket_tracker_disposalwell as t4 on t1.disposal_well_id = t4.id
                                   order by t1.account_code',
                                    null
                                 );	
                                 
            $result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
         
            $cache_content=json_encode($result);
            file_put_contents($cache_path,$cache_content);
            $response['code'] = 'success';
            $response['data'] = $result;           
        }    
		if(!empty($_REQUEST["SearchText"]))
		{				
			$final_result=array();
			for($i=0;$i<count($response["data"]);$i++)
			{
				if(strpos(strtolower($response["data"][$i]->t2.type),strtolower($_REQUEST["SearchText"]))!==false)
				{
					$final_result[]=	$response["data"][$i];
				}
			}
			$response["data"]=$final_result;
		}
		echo json_encode($response);		
    }
	else
	{
        $conditionSql = "";
        $params = null;
        if(!empty($_REQUEST["SearchText"]))
        {
            $conditionSql = " and (t1.account_code ilike :SearchText or t2.type ilike :SearchText)";

            $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        }

		$pagingSql='';
		if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
		{
			$start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
		   
			$pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
		}
		 //Get Total Records
		$query = "select count(id) from rate_sheet where 1 = 1 $conditionSql";
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
						   "SELECT t1.id, t1.product_type_id, t1.delivery_method, t1.water_source_type, t1.account_code, t1.trucking_company_id, t1.disposal_well_id, t1.price, t2.type, t3.name as trucking_company, t4.common_name as disposal_well from rate_sheet as t1 
						   LEFT JOIN ticket_tracker_watertype as t2 on t1.product_type_id = t2.id
						   LEFT JOIN ticket_tracker_truckingcompany as t3 on t1.trucking_company_id = t3.id
						   LEFT JOIN ticket_tracker_disposalwell as t4 on t1.disposal_well_id = t4.id
						   where 1 = 1 $conditionSql order by t1.account_code".$pagingSql,
							$params
						);	
        $result=array();
		while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["product_type"]=$row["type"];
            $data["delivery_method"]=$row["delivery_method"];
            $data["water_source_type"]=$row["water_source_type"];
            $data["account_code"]=$row["account_code"];
            $data["trucking_company"]=$row["trucking_company"];
            $data["disposal_well"]=$row["disposal_well"];
            $data["price"]=$row["price"];

            $result[]=$data;
        }
		$response['code'] = 'success';
		$response['data'] = $result;
		//echo 'select * from ticket_tracker_operator '.$pagingSql;
		//var_export($response['data']);
        echo json_encode($response);
	}
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>