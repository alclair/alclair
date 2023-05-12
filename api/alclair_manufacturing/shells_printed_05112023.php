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
    if( isset($_REQUEST['id']) == false )
    {
        $response['code'] = 'error';
        $response['message'] = 'Please batch.';
        echo json_encode($response);
        exit;
    }
    if (!strcmp($_REQUEST['printed'], "No")) {
	    $response['test'] = "Test is Not Printed";
	    $printed = "TRUE";
    } else {
	    $response['test'] = "Test is Printed";
	    $printed = "FALSE";
    }
    //echo json_encode($response);
    //exit;

if($_SESSION['IsAdmin'] == 0) {
    $stmt = pdo_query( $pdo,
                       "UPDATE import_orders SET shells_printed = :printed WHERE id = :id", array( ":id" => (int)$_REQUEST['id'], ":printed"=>$printed));	
} else {
	    $stmt = pdo_query( $pdo,
                       "UPDATE import_orders SET shells_printed = :printed WHERE id = :id", array( ":id" => (int)$_REQUEST['id'], ":printed"=>$printed));
}								

	$result = pdo_fetch_all( $stmt );
    $rowcount = pdo_rows_affected( $stmt );
    if( $rowcount == 0 )
    {
        $response['code'] = 'error';
        $response['message'] = pdo_errors();
        $response['message'] = "asdfasdfasdfasd";
        echo json_encode($response);
        exit;
    }
	
	$response["test"] = $rowcount;
	$response["test2"] = $test2;
    $response['code'] = 'success';
    echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>