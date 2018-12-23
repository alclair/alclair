<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = array();

try
{	
    if(empty($_REQUEST["id"]))
    {
        $params=array(":search"=>$_REQUEST["q"]."%");
        if(!empty($_REQUEST["county_name"]))
        {
            $county_sql=" and county=:county";
            $params[":county"]=$_REQUEST["county_name"];
        }
        if(!empty($_REQUEST["field_name"]))
        {
            $field_sql=" and field_name=:field_name";
            $params[":field_name"]=$_REQUEST["field_name"];
        }
        if(!empty($_REQUEST["operator_id"]))
        {
            if(strpos($_REQUEST["operator_id"],"|")!==false)
            {
                $tmp=explode("|",$_REQUEST["operator_id"]);
                $operator_sql="";
                for($i=0;$i<count($tmp);$i++)
                {
                    if(!empty($tmp[$i]))
                    {
                        if($operator_sql!="") $operator_sql.=" or ";
                        $operator_sql.=" current_operator_id={$tmp[$i]}";
                    }
                }
                if($operator_sql!="")
                {
                    $operator_sql=" and ($operator_sql)";
                }
            }
            else
            {
                $operator_sql=" and current_operator_id=:operator_id";
                $params[":operator_id"]=$_REQUEST["operator_id"];
            }
            
        }
        if(!empty($_REQUEST["township"]))
        {
            $township_sql=" and township=:township";
            $params[":township"]=$_REQUEST["township"];
        }
        if(!empty($_REQUEST["range"]))
        {
            $range_sql=" and rng=:range";
            $params[":range"]=$_REQUEST["range"];
        }
        if(!empty($_REQUEST["section"]))
        {
            $section_sql=" and section=:section";
            $params[":section"]=$_REQUEST["section"];
        }
        $stmt = pdo_query( $pdo,
					   "select distinct wells_list.id, wells_list.file_number, wells_list.api_number, wells_list.current_well_name,ticket_tracker_operator.name as operator_name
                       from wells_list 
                       left join ticket_tracker_operator on wells_list.current_operator_id=ticket_tracker_operator.id
                       where (wells_list.file_number::text ilike :search or wells_list.current_well_name ilike :search or ticket_tracker_operator.name ilike :search) $field_sql $county_sql $operator_sql $township_sql $section_sql $range_sql
                       order by wells_list.file_number,wells_list.current_well_name",
						$params
					 );	
        $response["test1"] = "Before While";
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["name"]=$row["file_number"]."//".$row["current_well_name"]."//".$row["operator_name"];
            $data["source_well_file_number"]=$row["file_number"];
            $data["source_well_operator_name"]=$row["operator_name"];
            $data["source_well_name"]=$row["current_well_name"];
            $response["data"][]=$data;
            
            $response["test2"] = "In While";
        }        
    }
	else
    {        
        $stmt = pdo_query( $pdo,
					   'select wells_list.id, wells_list.file_number, wells_list.api_number, wells_list.current_well_name,ticket_tracker_operator.name as operator_name
                       from wells_list 
                       left join ticket_tracker_operator on wells_list.current_operator_id=ticket_tracker_operator.id
                       where wells_list.id=:id',
						array(":id"=>$_REQUEST["id"])
					 );	
        $row=pdo_fetch_array($stmt);
        $response["data"]["id"]=$row["id"];
        $response["data"]["name"]=$row["file_number"]."//".$row["current_well_name"]."//".$row["operator_name"];
        
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["name"]=$row["file_number"]."//".$row["current_well_name"]."//".$row["operator_name"];
            $data["source_well_file_number"]=$row["file_number"];
            $data["source_well_operator_name"]=$row["operator_name"];
            $data["source_well_name"]=$row["current_well_name"];
            $response["data"][]=$data;
        }        
        
    }
	$response['code']='success';	
	//var_export($result);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>