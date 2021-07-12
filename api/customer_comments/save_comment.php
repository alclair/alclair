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

try
{     
    $conditionSql = "";
    $conditionSql_printed = "";
    $pagingSql = "";
    $params = array();
    $comment = $_POST["comment"];
    $after_comment = $_POST["after_comment"];
    
    $ID_is = $_REQUEST["id"];
    
    $query = "UPDATE date = now(), customer_comments SET comment = :comment, after_comment = :after_comment WHERE id = $ID_is";
    $stmt = pdo_query( $pdo, $query, array(":comment"=>$comment, ":after_comment"=>$after_comment) );
    $row = pdo_fetch_array( $stmt );
	
	$response["data"] = $row;
    $response["test"] = $ID_is;    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>