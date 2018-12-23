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
	$product = array();
	$product['category_id'] = $_POST['category_id'];
	$product['name'] = $_POST['name'];
	$product['sku'] = $_POST['sku'];
	$product['ean'] = $_POST['ean'];
	$product['sn_prefix'] = $_POST['sn_prefix'];
	$product['price'] = $_POST['price'];
		
$stmt = pdo_query( $pdo, 
					   "INSERT INTO products (category_id, name, sku, ean, sn_prefix, price, active)
					     VALUES (:category_id, :name, :sku, :ean, :sn_prefix, :price, :active) RETURNING id",
						 array(':category_id'=>$product['category_id'], ':name'=>$product['name'],':sku'=>$product['sku'],':ean'=>$product['ean'], ':sn_prefix'=>$product['sn_prefix'], ':price'=>$product['price'], 'active'=>TRUE)
				);					 					 
					 
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response["testing8"] = "8888888";
		$response["test"] = "test test";
		echo json_encode($response);
		exit;
	}
	
    $result = pdo_fetch_array($stmt);
	$response['code'] = 'success';
	$response['data'] = $result; 
	$response["testing8"] = "8888888";
	echo json_encode($response);
	
	
}
catch(PDOException $ex)
{
	$response["testing8"] = "8888888";
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>