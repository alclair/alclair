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
                           'select * from tanks where id = :id',
                            array(":id"=>$_REQUEST['id'])
                         );	
        $result = pdo_fetch_array($stmt);
    }
    else if(!empty($_REQUEST["SearchText"]))
    {
        $stmt = pdo_query( $pdo,
                           'select * from tanks where (name ilike :SearchText ) order by name',
                            array(":SearchText"=>"%".$_REQUEST["SearchText"]."%")
                            //,1
                         );	
        $result = pdo_fetch_all($stmt);
    }
    else
    {
        $stmt = pdo_query( $pdo,
                           'select * from tanks WHERE id != :id order by name',
                           array(":id"=>$_REQUEST["index"])
                         );	
                           // 'select * from tanks WHERE id != 1 order by name',
                           // null
                         //);	
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