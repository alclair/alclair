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
						   'SELECT t1.id, t1.customer, t1.operator_id, t4.account_code, t4.id as account_code_id, t2.name as operator, t3.type as product_type, t3.id as product_type_id from customer_operator_account as t1 
						   LEFT JOIN ticket_tracker_operator as t2 on t1.operator_id = t2.id 
						   LEFT JOIN ticket_tracker_watertype as t3 on t1.product_type_id = t3.id
						   LEFT JOIN rate_sheet as t4 on t1.account_code_id = t4.id
						   where t1.id=:id',
							array(":id"=>$_REQUEST["id"])
						);	
		$result = pdo_fetch_array($stmt);
		$response['code'] = 'success';
		$response['data'] = $result;
        echo json_encode($response);        
	}
    else if($_REQUEST["all"]==1)
    {
        $cache_path=$rootScope["RootPath"]."/data/cache/accounting_list.txt";
        if(file_exists($cache_path))
        {
            $result=file_get_contents($cache_path);
            $response['code'] = 'success';
			$response['data'] = json_decode($result);						
        }
        else
        {
            $stmt = pdo_query( $pdo,
                                   't1.id, t1.customer, t1.operator_id, t2.name as operator, t3.name as product_type, t1.account_code as account_code from customer_operator_account as t1 
								   LEFT JOIN ticket_tracker_operator as t2 on t1.operator_id = t2.id
								   LEFT JOIN ticket_tracker_watertype as t3 on t1.product_type_id = t3.id
								   order by customer',
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
				if(strpos(strtolower($response["data"][$i]->name),strtolower($_REQUEST["SearchText"]))!==false)
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
            $conditionSql = " and customer ilike :SearchText";
            $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        }

		$pagingSql='';
		if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
		{
			$start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
		   
			$pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
		}
		 //Get Total Records
		$query = "select count(id) from customer_operator_account where 1 = 1 $conditionSql";
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
						   "select t1.id, t1.customer, t1.operator_id, t4.account_code, t4.id as account_code_id, t2.name as operator, t3.type as product_type from customer_operator_account as t1 
						   LEFT JOIN ticket_tracker_operator as t2 on t1.operator_id = t2.id 
						   LEFT JOIN ticket_tracker_watertype as t3 on t1.product_type_id = t3.id
						   LEFT JOIN rate_sheet as t4 on t1.account_code_id = t4.id
						   where 1 = 1 $conditionSql order by customer ".$pagingSql,
							$params
						);	
        $result=array();
		while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["customer"]=$row["customer"];
            $data["operator"]=$row["operator"];
            $data["operator_id"]=$row["operator_id"];
			$data["account_code"]=$row["account_code"];
            $data["account_code_id"]=$row["account_code_id"];
            $data["product_type"]=$row["product_type"];
            $data["product_type_id"]=$row["product_type_id"];
            //if(!empty($row["account_code"]))
            //{
            //    $query2="select customer from customer_operator_account where id=:id";
            //    $stmt2=pdo_query($pdo,$query2,array(":id"=>$row["id"]));
            //    $row2=pdo_fetch_array($stmt2);
            //    $data["customer"]=$row2["customer"];
            //}
            //$query2="select count(*) from customer_operator_account where id=:id";
            //$stmt2=pdo_query($pdo,$query2,array(":id"=>$row["id"]));
            //$row2=pdo_fetch_array($stmt2);
            //$data["number_wells"]=$row2[0];
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