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
    $pagingSql = "";
    $orderBySqlDirection = "desc";
    $orderBySql = " order by id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql = " and material_id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

	$query = "SELECT name FROM materials WHERE id = :id";
	$stmt = pdo_query( $pdo, $query, $params );
	$row = pdo_fetch_array( $stmt );
	
	$response['name']= $row['name'];
	$response['testing2']= 'STUFF';
	
    //Get Total Records
    $query = "SELECT count(t1.id) FROM materials_tracker as t1 
		    		  WHERE material_id = :id";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];

	
	$params[":session_userid"]=$_SESSION['UserId'] ;
    //Get One Page Records
    if( isset($_REQUEST['id']) )
    {        
        $query = "SELECT t1.*, to_char(t1.time_stamp,'MM/dd/yyyy') as time_stamp, to_char(t1.invoice_date, 'MM/dd/yyyy') as invoice_date, t2.first_name as first_name, t2.last_name as last_name
				  		  FROM materials_tracker as t1
				  		  LEFT JOIN auth_user as t2 ON t2.id = t1.entered_by
                          WHERE t1.material_id = :id
                          ORDER BY t1.time_stamp ASC";
    }
    else
    {
        $query = "SELECT t1.*, to_char(t1.time_stamp,'MM/dd/yyyy') as time_stamp
                          FROM materials_tracker as t1
						  WHERE active = TRUE $conditionSql $orderBySql $pagingSql";
    }    
    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
	
	$query = "SELECT id, filepath, to_char(date_uploaded,'MM/dd/yyyy') as date_uploaded, uploaded_by_id, invoice_id
                       FROM materials_invoice_indexupload ";
	$stmt = pdo_query( $pdo, $query, null); 
    $result2 = pdo_fetch_all( $stmt );

                       
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['data2'] = $result2;
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>