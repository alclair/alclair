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
						   'select id,name from ticket_tracker_operator where id=:id',
							array(":id"=>$_REQUEST["id"])
						);	
		$result = pdo_fetch_array($stmt);
		$response['code'] = 'success';
		$response['data'] = $result;
        echo json_encode($response);        
	}
    else if($_REQUEST["all"]==1)
    {
        //$cache_path=$rootScope["RootPath"]."/data/cache/operator_list.txt";
        if(file_exists($cache_path))
        {
            $result=file_get_contents($cache_path);
            $response['code'] = 'success';
			$response['data'] = json_decode($result);						
        }
        else
        {
            $stmt = pdo_query( $pdo,
                                   'select * from ticket_tracker_operator order by name',
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
            $conditionSql = " and name ilike :SearchText";
            $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        }

		$pagingSql='';
		if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
		{
			$start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
		   
			$pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
		}
		 //Get Total Records
		$query = "select count(id) from ticket_tracker_operator where 1 = 1 $conditionSql";
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
						   "select id, name,parentid from ticket_tracker_operator where 1 = 1 $conditionSql order by name ".$pagingSql,
							$params
						);	
        $result=array();
		while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["name"]=$row["name"];
            if(!empty($row["parentid"]))
            {
                $query2="select name from ticket_tracker_operator where id=:id";
                $stmt2=pdo_query($pdo,$query2,array(":id"=>$row["parentid"]));
                $row2=pdo_fetch_array($stmt2);
                $data["parent_name"]=$row2["name"];
            }
            $query2="select count(*) from ticket_tracker_well where current_operator_id=:id";
            $stmt2=pdo_query($pdo,$query2,array(":id"=>$row["id"]));
            $row2=pdo_fetch_array($stmt2);
            $data["number_wells"]=$row2[0];
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