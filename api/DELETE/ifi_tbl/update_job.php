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
		$query = "UPDATE reviewers_jobs SET title=:title, company=:company, sector_id=:sector_id
							WHERE id=:id"; 
		$params=array(
						":title"=>$_REQUEST["title"], 
						":company"=>$_REQUEST["company"], 
						":sector_id"=>$_REQUEST["sector_id"], 
						":id"=>$_REQUEST["id"]);
						
		$response['test'] = $_REQUEST["category_id"];
		$response['test1'] = $_REQUEST["id"];
		
		pdo_query($pdo,$query,$params);
		$response['code']='success';
		$response["message"] =" Update successful!";
		echo json_encode($response);
	//}
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>