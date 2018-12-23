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
                $stmt = pdo_query( $pdo,
					   "select distinct ticket_tracker_ticket.id, ticket_number
                       from ticket_tracker_ticket",$params
					 );	
        
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["name"]=$row["ticket_number"];
            $response["data"][]=$data;
        }        
    }
	else
    {        
        $stmt = pdo_query( $pdo,
					   'select ticket_tracker_ticket.id, ticket_number
                       from ticket_tracker_ticket
                       where ticket_tracker_ticket.id=:id',
						array(":id"=>$_REQUEST["id"])
					 );	
        $row=pdo_fetch_array($stmt);
        $response["data"]["id"]=$row["id"];
        $response["data"]["name"]=$row["ticket_number"];
        
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