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


                           'SELECT DISTINCT ON (t1.current_operator_id) t1.id as id, t1.current_operator_id as current_operator_id, t2.name as name FROM ticket_tracker_well as t1
LEFT JOIN ticket_tracker_operator as t2 on t1.current_operator_id = t2.id 
where (name ilike :SearchText ) order by name',
                            array(":SearchText"=>"%".$_REQUEST["SearchText"]."%")
                            //,1
                         );	
        $result = pdo_fetch_all($stmt);
    }
    else
    {
        $stmt = pdo_query( $pdo,
                           'SELECT DISTINCT ON (t1.current_operator_id) t1.id as id, t1.current_operator_id as current_operator_id, t2.name as name FROM ticket_tracker_well as t1
LEFT JOIN ticket_tracker_operator as t2 on t1.current_operator_id = t2.id',
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