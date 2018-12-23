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
	if($_REQUEST["id"] !='')
	{
		 //Get One Page Records
		$stmt = pdo_query( $pdo,
						   'SELECT t1.*, t2.customer_name FROM queens as t1
						    LEFT JOIN customer as t2 on t1.customer_id = t2.id
						    WHERE queens_id=:id',
							array(":id"=>$_REQUEST["id"])
						);	
		$result = pdo_fetch_array($stmt);
		$response['code'] = 'success';
		$response['data'] = $result;
		$response['test2'] = "FIRST";
        echo json_encode($response);        
	}
	else if ($_REQUEST["find_customer_id"] == 1){
		$stmt = pdo_query( $pdo, "SELECT * FROM auth_user
    													WHERE id = {$_SESSION["UserId"]}",null);	

		$result = pdo_fetch_array($stmt);
		$response['code'] = 'success';
		$response['data'] = $result;
		$response['customer_id'] = $result[0]["customer_id"];
		$response['test2'] = "first else if";
		echo json_encode($response); 

	}
	else if ($_REQUEST["customer_id"] > 0) {
		$stmt = pdo_query( $pdo, "SELECT * FROM queens
    													WHERE customer_id = {$_REQUEST["customer_id"]}
    													ORDER BY queens_id",null);	
    													
        $result = pdo_fetch_all($stmt);
		$response['code'] = 'success';
		$response['data'] = $result;
		
		$response['test'] = $result["name"];
		$response['test2'] = "ELSE IF";
		echo json_encode($response); 

	}
	else
	{
    	$stmt = pdo_query( $pdo, 'SELECT t1.*, t2.customer_name FROM queens as t1
    													LEFT JOIN customer as t2 on t1.customer_id = t2.id
    													ORDER BY queens_id',null);	
		$result = pdo_fetch_all($stmt);
		$response['code'] = 'success';
		$response['data'] = $result;
        
        $query = "SELECT count(id) FROM queens WHERE 1 = 1";
		$stmt = pdo_query( $pdo, $query, null );
		$row = pdo_fetch_array( $stmt );
		$response['TotalRecords'] = $row[0];
		$response['test2'] = "LAST";
		$response['test3'] = $_SESSION["customer_id"];		
		echo json_encode($response); 
	}
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>