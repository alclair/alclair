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
	$theID =$_REQUEST["id"];
   /* if( check_accesstoken() == false )
    {
        $response['code'] = 'error';
        $response['message'] = 'Please specify access token';
        echo json_encode($response);
        exit;
    }
*/
	$pagingSql='';
    //if( $_REQUEST["id"] !='' && $_REQUEST["name"] !='')
    //{
	//    $parentid=null;
    //    if(!empty($_REQUEST["parentid"]))
    //    {
    //        $parentid=$_REQUEST["parentid"];
    //    }
		$query = "UPDATE holiday_log SET holiday=:holiday, date=:holiday_date
							WHERE id=:id"; 
		$params=array(
						":holiday"=>$_REQUEST["holiday"], 
						":holiday_date"=>$_REQUEST["holiday_date"], 
						":id"=>$_REQUEST["id"]);
						
		$response['test1'] = $_REQUEST["id"];
		
		pdo_query($pdo,$query,$params);
		$response['code']='success';
		$response["message"] =" Update successful!";
		
		
		$response["test"] = $theID;
		echo json_encode($response);
		exit;
	//}
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>