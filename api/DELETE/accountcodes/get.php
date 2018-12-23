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
    if( !empty($_REQUEST['id']) )
    {        
        $stmt = pdo_query( $pdo,
                           'select * from ticket_tracker_well where id = :id',
                            array(":id"=>$_REQUEST['id'])
                         );	
        $result = pdo_fetch_array($stmt);
    }
    else if(!empty($_REQUEST["SearchText"]))
    {
        $stmt = pdo_query( $pdo,


                           'SELECT DISTINCT ON (t1.account_code) t1.id as id, t1.account_code as account_code FROM rate_sheet as t1
						   WHERE (account_code ilike :SearchText ) order by account_code',
                            array(":SearchText"=>"%".$_REQUEST["SearchText"]."%")
                            //,1
                         );	
        $result = pdo_fetch_all($stmt);
    }
    else
    {
        $stmt = pdo_query( $pdo,
                           'SELECT DISTINCT ON (t1.account_code) t1.id as id, t1.account_code as account_code  FROM rate_sheet as t1',
                            null
                         );	
        $result = pdo_fetch_all($stmt);
    }	
	
	$response['code']='success';
	$response['data'] = $result;
	
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