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
	
    if( $_REQUEST["name"] !='')
    {
        $parentid=null;
        if(!empty($_REQUEST["parentid"]))
        {
            $parentid=$_REQUEST["parentid"];
        }
		$query = "Insert into ticket_tracker_operator(name,parentid) values(:name,:parentid)   RETURNING id";
		$params=array(":name"=>$_REQUEST["name"],":parentid"=>$parentid);
		
		$stmt = pdo_query($pdo,$query,$params);
        $result = pdo_fetch_array($stmt);

		$response['code']='success';
		$response["message"] =" Add success!";
        $response['data'] = $result;
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