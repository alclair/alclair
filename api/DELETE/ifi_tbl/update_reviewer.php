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
		$query = "UPDATE reviewers SET title_id=:title_id, firstname=:firstname, lastname=:lastname, 
							tag_id=:tag_id, iclub_id=:iclub_id, status_id=:status_id, 
							address_1=:address_1, address_2=:address_2, address_3=:address_3, address_4=:address_4,
							city=:city, state=:state, zip=:zip, country_id=:country_id, notes=:notes,
							ra_sent=:ra_sent, ra_signed=:ra_signed, nda_sent=:nda_sent, nda_signed=:nda_signed
							WHERE id=:id"; 
		$params=array(
						":title_id"=>$_REQUEST["title_id"], 
						":firstname"=>$_REQUEST["firstname"], 
						":lastname"=>$_REQUEST["lastname"], 
						":tag_id"=>$_REQUEST["tag_id"], 
						":iclub_id"=>$_REQUEST["iclub_id"], 
						":status_id"=>$_REQUEST["status_id"], 
						":address_1"=>$_REQUEST["address_1"],
						":address_2"=>$_REQUEST["address_2"],
						":address_3"=>$_REQUEST["address_3"],
						":address_4"=>$_REQUEST["address_4"], 
						":city"=>$_REQUEST["city"], 
						":state"=>$_REQUEST["state"],
						":zip"=>$_REQUEST["zip"],
						":country_id"=>$_REQUEST["country_id"],
						":notes"=>$_REQUEST["notes"],
						":ra_sent"=>$_REQUEST["ra_sent"],
						":ra_signed"=>$_REQUEST["ra_signed"],
						":nda_sent"=>$_REQUEST["nda_sent"],
						":nda_signed"=>$_REQUEST["nda_signed"],
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