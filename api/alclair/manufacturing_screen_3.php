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
    
    $query = "SELECT * FROM customer_comments WHERE comment IS NOT NULL AND comment != ''";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_all( $stmt );
    $count = pdo_rows_affected($stmt);
	
	$comment_to_grab = rand(0,$count-1);
	$response['comment'] = $row[$comment_to_grab]["comment"];
	$response['after_comment'] = $row[$comment_to_grab]["after_comment"];
	
	//$response["test"] = $row[0];
	//echo json_encode($response);
	//exit;
        
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>