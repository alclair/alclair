<?php
include_once "../../config.inc.php";
if(empty($_SESSION["IsAdmin"]))
{
    return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{
	if( !empty($_REQUEST['customer_id']) ) {
		$stmt = pdo_query( $pdo, "SELECT * FROM trd_customer WHERE id = :id", array(":id"=>$_REQUEST['customer_id']));	
		$result = pdo_fetch_array($stmt);
		$response['code']='success';
	    $response['data'] = $result;
	    $response["test"] = "TESTING " . $result["customer"] . " and customer id is " . $_REQUEST['customer_id'];
	} else if(!empty($_REQUEST["SearchText"])) {
        $stmt = pdo_query( $pdo, 'SELECT * FROM WHERE (customer ilike :SearchText) ORDER BY customer', array(":SearchText"=>"%".$_REQUEST["SearchText"]."%"));	
        $result = pdo_fetch_all($stmt);
        $response['code']='success';
        $response['data'] = $result;
        $response["test"] = "NOT WORKING";
    } else {
        $stmt = pdo_query( $pdo, 'SELECT * FROM trd_customer ORDER BY customer', null);	
        $result = pdo_fetch_all($stmt);
        $response["test"] = "TESTING 3";
        $response['code']='success';
        $response['data'] = $result;
    }
	
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
