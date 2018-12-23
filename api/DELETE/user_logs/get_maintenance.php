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
        $conditionSql_id = " and id = :id";
        $params_id[":id"] = $_REQUEST['id'];
    }
    
    $conditionSql .= " and (created_by_id=:created_by_id)";    
	$params[":created_by_id"]=$_SESSION["UserId"];
	//$params[":created_by_id"]=2;
    $query = "SELECT id as i_d FROM maintenance WHERE active = 'TRUE' $conditionSql ";
    $stmt = pdo_query( $pdo, $query, $params);
    $does_log_exist = pdo_fetch_array( $stmt );
    
    if( isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) )
    { 
	    $query = "SELECT t1.*
                  FROM maintenance as t1 
                  WHERE id = :id";	   
        $stmt = pdo_query( $pdo, $query, $params_id); 
		$result = pdo_fetch_all( $stmt );
		$response["testing"]=$_REQUEST['id'];

	}
    //  IF THERE IS AN ACTIVE MAINTENANCE LOG OPEN
    else if (!empty($does_log_exist))
    {
		$query = "SELECT t1.*, to_char(t1.date_created,'MM/dd/yyyy') as date_created
                  FROM maintenance as t1 
                  WHERE id = $does_log_exist[0]";	   
        $stmt = pdo_query( $pdo, $query, null); 
		$result = pdo_fetch_all( $stmt );
	    
	    $response["testing"] = $does_log_exist[0];//$_SESSION["UserId"];
    }
    else   //  IF THERE IS  NOT AN ACTIVE MAINTENANCE LOG OPEN
    {
	    	$stmt = pdo_query( $pdo, 
	    				"INSERT INTO maintenance
	    				(created_by_id, date_created, active, type_id )
	    				VALUES
	    				(:created_by_id, now(), :active, :type_id ) RETURNING id",
						array(':created_by_id'=>$_SESSION['UserId'], ':active'=>'TRUE', ':type_id'=>1) 	
	    										);	    										
	    										
		$result = pdo_fetch_array($stmt);
		
		// GRABBING THE NEW ROW FROM THE TABLE TO DISPLAY
		$conditionSql = "";
		$conditionSql .= " and (created_by_id=:created_by_id)";    
		$params[":created_by_id"]=$_SESSION["UserId"];
		//$params[":created_by_id"]=2;
		$query = "SELECT * FROM maintenance WHERE active = 'TRUE' $conditionSql ";
		$stmt = pdo_query( $pdo, $query, $params);
		$result = pdo_fetch_all( $stmt );
		
		$response["testing"] = "was empty, check";

    }
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>