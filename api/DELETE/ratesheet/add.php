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
    {
        $parentid=null;
        if(!empty($_REQUEST["parentid"]))
        {
            $parentid=$_REQUEST["parentid"];
        }
		$query = "INSERT into rate_sheet(product_type_id, delivery_method, water_source_type, account_code, trucking_company_id, disposal_well_id, price, created_by_id) 																								   					  VALUES(:product_type_id, :delivery_method, :water_source_type, :account_code, :trucking_company_id, :disposal_well_id, :price, :created_by_id)";
		$params=array(
		":product_type_id"=>$_REQUEST["product_type_id"],
		":delivery_method"=>$_REQUEST["delivery_method"],
		":water_source_type"=>$_REQUEST["water_source_type"],
		":account_code"=>$_REQUEST["account_code"],
		":trucking_company_id"=>$_REQUEST["trucking_company_id"],
		":disposal_well_id"=>$_REQUEST["disposal_well_id"],
		":price"=>$_REQUEST["price"],
		":created_by_id"=>$_SESSION['UserId']);

		
		$stmt = pdo_query($pdo,$query,$params);
        $result = pdo_fetch_array($stmt);

		$response['code']='success';
		$response["message"] =" Add success!";
        $response['data'] = $result;
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