<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
//$out=print_r($_REQUEST,true);
//file_put_contents("log.txt",$out);
//return;
try
{
    $barrel_rate=$_REQUEST["barrel_rate"];
    $disposal_well_id=$_REQUEST["disposal_well_id"];
    $water_type_id=$_REQUEST["water_type_id"];
    $billto_option=$_REQUEST["billto_option"];
	$use_default=$_REQUEST["use_default"];
	$wells=$_REQUEST["well_ids"];
	
	if($use_default==1)
	{
		$barrel_rate=GetDefaultRate($disposal_well_id,$water_type_id);
	}
	for($i=0;$i<count($wells);$i++)
	{
		$source_well_id=$wells[$i];
		$query="select * from ticket_tracker_well where id=:well_id";
		$stmt=pdo_query($pdo,$query,array(":well_id"=>$source_well_id));
		$row=pdo_fetch_array($stmt);
		$billto_operator_id=$row["current_operator_id"];
		if($billto_option=="operator")
		{
			 $query2="select * from ticket_tracker_rate where source_well_id=:source_well_id and disposal_well_id=:disposal_well_id and billto_operator_id=:operator_id and water_type_id=:water_type_id";
			 $params2=array("source_well_id"=>$source_well_id,":disposal_well_id"=>$disposal_well_id,":operator_id"=>$billto_operator_id,":water_type_id"=>$water_type_id);
			
			 $stmt2=pdo_query($pdo,$query2,$params2);
			 $row2=pdo_fetch_array($stmt2);
			 if(!empty($row2[0]))
			 {
				 $query2="update ticket_tracker_rate set barrel_rate=:barrel_rate,use_default=:use_default where id=:id";
				  pdo_query($pdo,$query2,array(":barrel_rate"=>$barrel_rate,":id"=>$row2[0]),":use_default"=>$use_default);
			 }
			 else
			 {
				 $query2 = "insert into ticket_tracker_rate  (source_well_id, disposal_well_id, billto_option, billto_operator_id,
		water_type_id, barrel_rate,use_default) values (:source_well_id, :disposal_well_id, :billto_option, :billto_operator_id, :water_type_id, :barrel_rate,:use_default)";
				 $params2=array(":billto_option"=>$billto_option,
					 ":billto_operator_id"=>$billto_operator_id,                     
					 ":water_type_id"=>$water_type_id,
					 ":barrel_rate"=>$barrel_rate,
					 ":disposal_well_id"=>$disposal_well_id,
					 ":use_default"=>$use_default,
					":source_well_id"=>$source_well_id);
				 
				 pdo_query($pdo,$query2,$params2);
			 }
		}
		else if($billto_option=="trucking company")
		{			
			$trucking_companys=$_REQUEST["trucking_company_ids"];
			
			for($n=0;$n<count($trucking_companys);$n++)
			{
				$trucking_company_id=$trucking_companys[$n];
				$query2="select * from ticket_tracker_rate where source_well_id=:source_well_id and disposal_well_id=:disposal_well_id and trucking_company_id=:trucking_company_id and water_type_id=:water_type_id";
				$params2=array("source_well_id"=>$source_well_id,":disposal_well_id"=>$disposal_well_id,":trucking_company_id"=>$trucking_company_id,":water_type_id"=>$water_type_id);
				
				$stmt2=pdo_query($pdo,$query2,$params2);
				$row2=pdo_fetch_array($stmt2);
				if(!empty($row2[0]))
				{
					$query2="update ticket_tracker_rate set barrel_rate=:barrel_rate where id=:id";
					pdo_query($pdo,$query2,array(":barrel_rate"=>$barrel_rate,":id"=>$row2[0]));
				}       
				else
				{
					$query2 = "insert into ticket_tracker_rate  (source_well_id, disposal_well_id, billto_option, trucking_company_id,
		water_type_id, barrel_rate,use_default) values (:source_well_id, :disposal_well_id, :billto_option, :trucking_company_id, :water_type_id, :barrel_rate,:use_default)";
					$params2=array(":billto_option"=>$billto_option,
						":trucking_company_id"=>$trucking_company_id,                     
						":water_type_id"=>$water_type_id,
						":barrel_rate"=>$barrel_rate,
						":disposal_well_id"=>$disposal_well_id,":use_default"=>$use_default,
					   ":source_well_id"=>$source_well_id);
					
					pdo_query($pdo,$query2,$params2);
				}
			}
	   }       
	}
	

	$response['code']='success';
	$response["message"] =" Update success!";
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>