<?php
include_once "../../config.inc.php";

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

if( empty($_SESSION["UserId"]) || empty($_SESSION["IsAdmin"]) )
{
    return;
}

try
{	
    if( isset($_REQUEST['id']) == false )
    {
        $response['code'] = 'error';
        $response['message'] = 'Please specify id';
        echo json_encode($response);
        exit;
    }


    $stmt = pdo_query( $pdo,
                       "delete from ticket_tracker_rate where id = :id", 
                        array( 
                                ":id" => (int)$_REQUEST['id']
                             )                         
                     );	

    $rowcount = pdo_rows_affected( $stmt );
    if( $rowcount == 0 )
    {
        $response['code'] = 'error';
        $response['message'] = pdo_errors();
        echo json_encode($response);
        exit;
    }

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