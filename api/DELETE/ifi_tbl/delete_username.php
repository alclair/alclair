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
        $response['message'] = 'Please QC form.';
        echo json_encode($response);
        exit;
    }

if($_SESSION['IsAdmin'] == 0) {
    $stmt = pdo_query( $pdo,
                       "UPDATE reviewers_usernames SET active = FALSE WHERE id = :id", array( ":id" => (int)$_REQUEST['id'])   
                     );	
} else {
	    $stmt = pdo_query( $pdo,
                       "UPDATE reviewers_usernames SET active = FALSE WHERE id =:id", array( ":id" => (int)$_REQUEST['id'])  
                     );
}								

	$result = pdo_fetch_all( $stmt );
    $rowcount = pdo_rows_affected( $stmt );
    if( $rowcount == 0 )
    {
        $response['code'] = 'error';
        $response['message'] = pdo_errors();
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