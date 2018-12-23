<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";

try
{	
   /* if( check_accesstoken() == false )
    {
        $response['code'] = 'error';
        $response['message'] = 'Please specify access token';
        echo json_encode($response);
        exit;
    }
*/
	$pagingSql='';
    if( $_REQUEST["id"] !='' && $_REQUEST["name"] !='')
    {
	    $parentid=null;
        if(!empty($_REQUEST["parentid"]))
        {
            $parentid=$_REQUEST["parentid"];
        }
		$query = "Update ticket_tracker_operator  set name=:name,parentid=:parentid where id=:id";
		$params=array(":name"=>$_REQUEST["name"],":parentid"=>$parentid,
		   ":id"=>$_REQUEST["id"]);
		
		pdo_query($pdo,$query,$params);
		$response['code']='success';
		$response["message"] =" Update success!";
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