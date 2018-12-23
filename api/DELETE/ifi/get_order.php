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
    $orderBySqlDirection = "DESC";
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();
    
    
    if( !empty($_REQUEST['id']) )
    {        
        $stmt = pdo_query( $pdo,
                           'SELECT * FROM log_movement WHERE id = :id',
                            array(":id"=>$_REQUEST['id'])
                         );	
        $result = pdo_fetch_all($stmt);
    }
    else if(!empty($_REQUEST["SearchText"]))
    {
        $stmt = pdo_query( $pdo,
                           'select * from log_movement where (name ilike :SearchText ) order by id',
                            array(":SearchText"=>"%".$_REQUEST["SearchText"]."%")
                            //,1
                         );	
        $result = pdo_fetch_all($stmt);
    }
    else
    { 
	    $query = "SELECT t1.* FROM log_movement AS t1";

        $stmt = pdo_query( $pdo, $query, $params); 
        $result = pdo_fetch_all($stmt);
    }	
	
	$response['code']='success';
	$response['data'] = $result;
	$response['test'] = $_REQUEST['id'];
	
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