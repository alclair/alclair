<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	   
    //$_REQUEST=json_decode("{\"name\":\"test 101\"}",true);
    //print_r($_REQUEST);
    $query="insert into ticket_tracker_well (current_operator_id,current_well_name,township,rng,section,county,field_name, date_created,qq,file_number,api_number,created_by_id)
    values (:current_operator_id,:current_well_name,:township,:range,:section,:county,:field_name, now(),:qq,:file_number,:api_number,:created_by_id);";
    $params=array(":current_operator_id"=>empty($_REQUEST["operator_id"])?null:$_REQUEST["operator_id"],
        ":current_well_name"=>$_REQUEST["name"],
        ":township"=>empty($_REQUEST["township"])?null:$_REQUEST["township"],
        ":range"=>empty($_REQUEST["range"])?null:$_REQUEST["range"],
        ":section"=>empty($_REQUEST["section"])?null:$_REQUEST["section"],
        ":county"=>empty($_REQUEST["county_name"])?null:$_REQUEST["county_name"],
        ":field_name"=>empty($_REQUEST["field_name"])?null:$_REQUEST["field_name"], 
        ":qq"=>empty($_REQUEST["quarter"])?null:$_REQUEST["quarter"], 
        ":file_number"=>empty($_REQUEST["file_number"])?null:$_REQUEST["file_number"], 
        ":api_number"=>empty($_REQUEST["api_number"])?null:$_REQUEST["api_number"], 
        ":created_by_id"=>$_SESSION["UserId"]);
	$stmt=pdo_query($pdo,$query,$params);
			
	$query="select max(id) from ticket_tracker_well";
    $stmt=pdo_query($pdo,$query,null);
    $row=pdo_fetch_array($stmt);
	$id=$row[0];
    $stmt = pdo_query( $pdo,
					   'select ticket_tracker_well.id, ticket_tracker_well.file_number,ticket_tracker_well.current_well_name,ticket_tracker_operator.name as operator_name, ticket_tracker_well.api_number, ticket_tracker_well.file_number, ticket_tracker_well.qq
                       from ticket_tracker_well 
                       left join ticket_tracker_operator on ticket_tracker_well.current_operator_id=ticket_tracker_operator.id
                       where ticket_tracker_well.id=:id',
						array(":id"=>$id)
					 );	
    $row=pdo_fetch_array($stmt);
    $response["data"]["id"]=$row["id"];
    $response["data"]["name"]=$row["file_number"]."//".$row["current_well_name"]."//".$row["operator_name"];
	$response['code']='success';
    $response["message"] = "Add success";

	/*
	$id=$row[0];
    $stmt = pdo_query( $pdo,
					   'select ticket_tracker_well.id, ticket_tracker_well.file_number,ticket_tracker_well.current_well_name,ticket_tracker_operator.name as operator_name
                       from ticket_tracker_well 
                       left join ticket_tracker_operator on ticket_tracker_well.current_operator_id=ticket_tracker_operator.id
                       where ticket_tracker_well.id=:id',
						array(":id"=>$id)
					 );	
    $row=pdo_fetch_array($stmt);
    */
    //$response["data"]["id"]=$row["id"];
    //$response["data"]["name"]=$row["file_number"]."//".$row["current_well_name"]."//".$row["operator_name"];
	//$response['code']='success';
    //$response["message"] = "Add success";

	
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