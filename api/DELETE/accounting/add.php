<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";

try
{	
   /* if( check_accesstoken() == false )
    {
        $response['code'] = 'error';
        $response['message'] = 'Please specify access token';
        echo json_encode($response);
        exit;
    }
*/
	
   // if( $_REQUEST["name"] !='')
    
        $parentid=null;
       // if(!empty($_REQUEST["parentid"]))
       // {
       //     $parentid=$_REQUEST["parentid"];
       // }
       $conditionSql = $_REQUEST['account_code_id'];
        $query = "SELECT  product_type_id as product_id FROM rate_sheet WHERE id=$conditionSql";
        $stmt = pdo_query($pdo,$query,null);
        $product = pdo_fetch_array($stmt);
        $product2 = $product["product_id"];
        
       
		$query = "INSERT into customer_operator_account(operator_id, customer, product_type_id, account_code_id, created_by_id)																								  					  VALUES(:operator_id, :customer, :product_type_id, :account_code_id, :created_by_id)";
		
		$params=array(
		":operator_id"=>$_REQUEST["operator_id"],
		":customer"=>$_REQUEST["customer"],
		":account_code_id"=>$_REQUEST["account_code_id"],
		":product_type_id"=>$product2,
		":created_by_id"=>$_SESSION['UserId']);

		
		$stmt = pdo_query($pdo,$query,$params);
        $result = pdo_fetch_array($stmt);


		$response['code']='success';
		$response["message"] =" Add success!";
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