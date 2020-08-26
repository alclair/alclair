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
$stuff = "";

try
{	   
    if( !empty($_REQUEST['id']) )
    {        
        $stmt = pdo_query( $pdo, "SELECT * FROM zteams WHERE id = :id AND active = TRUE", array(":id"=>$_REQUEST['id']));	
        $result = pdo_fetch_array($stmt);
        $stuff = "one";
    }
    else if(!empty($_REQUEST["SearchText"]))
    {
        $stmt = pdo_query( $pdo, "SELECT * FROM zteams WHERE (name ilike :SearchText ) AND active = TRUE ORDER BY id",  array(":SearchText"=>"%".$_REQUEST["SearchText"]."%"));	
        $result = pdo_fetch_all($stmt);
        $stuff = "two";
    }
    else
    {
        $stmt = pdo_query( $pdo, "SELECT * FROM zteams WHERE active = TRUE ORDER BY id", null);	
        $result = pdo_fetch_all($stmt);
        $rows = pdo_rows_affected($stmt);
        $stuff = "three";
    }	
	
	$response['code']='success';
	$response['data'] = $result;
	$response['test'] = $rows;
	$response['stuff'] = $stuff;
	
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