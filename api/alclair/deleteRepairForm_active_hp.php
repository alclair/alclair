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
						"UPDATE qc_form SET active = FALSE WHERE id_of_repair = :id", array( ":id" => (int)$_REQUEST['id']) );

	
    $stmt = pdo_query( $pdo,
                       "UPDATE repair_form_active_hp SET active = FALSE WHERE id = :id and entered_by = :entered_by_id", array( ":id" => (int)$_REQUEST['id'], ":entered_by_id"=>$_SESSION["UserId"])   
                     );	
} else {
		$stmt = pdo_query( $pdo, 
						"UPDATE qc_form SET active = FALSE WHERE id_of_repair = :id", array( ":id" => (int)$_REQUEST['id']) );

	    $stmt = pdo_query( $pdo,
                       "UPDATE repair_form_active_hp SET active = FALSE WHERE id =:id", array( ":id" => (int)$_REQUEST['id'])  
                     );
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