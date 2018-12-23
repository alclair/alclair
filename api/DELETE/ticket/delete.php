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
        $response['message'] = 'Please specify id';
        echo json_encode($response);
        exit;
    }

if($_SESSION['IsAdmin'] == 0) {
    $stmt = pdo_query( $pdo,
                       "delete from ticket_tracker_ticket where id = :id and created_by_id = :created_by_id", 
                        array( ":id" => (int)$_REQUEST['id'],":created_by_id"=>$_SESSION["UserId"] )                         
                     );	
                     									} else {
	    $stmt = pdo_query( $pdo,
                       "delete from ticket_tracker_ticket where id = :id", 
                        array( ":id" => (int)$_REQUEST['id'],":created_by_id"=>$_SESSION["UserId"] )                         
                     );	
                     												}								

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