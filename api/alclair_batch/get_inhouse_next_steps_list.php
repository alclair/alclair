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
    $response["test"] = $_REQUEST['category_in_inventory'];
    $response["test"] = $_REQUEST['index'];
    
    if($_REQUEST['customer_status'] ==  0)
    {
        //$conditionSql .= " AND (customer_status_id = :customer_status_id)";
        $conditionSql .= " AND (customer_status_id = 0 OR customer_status_id = 2)";
        $response["test"] = $_REQUEST['customer_status_id'];
        //echo json_encode($response);
        //exit;
        //$params[":customer_status_id"] = $_REQUEST['customer_status_id'];
    } else if ($_REQUEST['customer_status'] ==  1) {
	    //$conditionSql .= " AND (customer_status_id = :customer_status_id)";
	    $conditionSql .= " AND (customer_status_id = 1 OR customer_status_id = 2)";
	    $response["test"] = "ELSE STATEMENT";
        //echo json_encode($response);
        //exit;
        //$params[":customer_status_id"] = $_REQUEST['customer_status_id'];
    }
    
    $response["test"] = $conditionSql;
    //echo json_encode($response);
    //exit;
    
    if( !empty($_REQUEST['id']) )
    {        
        $stmt = pdo_query( $pdo,
                           'SELECT * FROM in_house_next_steps WHERE id = :id',
                            array(":id"=>$_REQUEST['id'])
                         );	
        $result = pdo_fetch_array($stmt);
    }
    else if(!empty($_REQUEST["SearchText"]))
    {
        $stmt = pdo_query( $pdo,
                           'select * from in_house_next_steps where (name ilike :SearchText ) order by id',
                            array(":SearchText"=>"%".$_REQUEST["SearchText"]."%")
                            //,1
                         );	
        $result = pdo_fetch_all($stmt);
    }
    else
    { 
	    
	    $query = "SELECT * FROM in_house_next_steps
	    				WHERE 1=1 $conditionSql ORDER BY id";

        $stmt = pdo_query( $pdo, $query, null); 
        //$stmt = pdo_query( $pdo, $query, $params); 
        $result = pdo_fetch_all($stmt);
        
        $response["test"] = $conditionSql;//$result[0]["customer_status_id"];
        //echo json_encode($response);
        //exit;
    }	
	
	
	$response['code']='success';
	
	$response['data'] = $result;
	$response["index"] = $_REQUEST['index'];
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