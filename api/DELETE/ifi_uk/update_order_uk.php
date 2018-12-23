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
	$theID =$_REQUEST["id"];
   /* if( check_accesstoken() == false )
    {
        $response['code'] = 'error';
        $response['message'] = 'Please specify access token';
        echo json_encode($response);
        exit;
    }
*/
	$pagingSql='';
    //if( $_REQUEST["id"] !='' && $_REQUEST["name"] !='')
    //{
	//    $parentid=null;
    //    if(!empty($_REQUEST["parentid"]))
    //    {
    //        $parentid=$_REQUEST["parentid"];
    //    }
    /*$stmt = pdo_query( $pdo, "UPDATE log_movement SET company=:company, name=:name, address_1=:address_1, address_2=:address_2, city=:city, state=:state, zip_code=:zip_code, notes=:notes, carrier_id=:carrier_id,	tracking=:tracking,	country=:country, title=:title, warehouse_id=:warehouse_id, ordertype_id=:ordertype_id, po_number=:po_number, shipping_cost=:shipping_cost WHERE id=:id", array(
						":company"=>$_REQUEST["company"], 
						":name"=>$_REQUEST["name"], 
						":address_1"=>$_REQUEST["address_1"], 
						":address_2"=>$_REQUEST["address_1"], 
						":city"=>$_REQUEST["city"], 
						":state"=>$_REQUEST["state"],
						":zip_code"=>$_REQUEST["zip_code"],
						":country"=>$_REQUEST["country"],
						":notes"=>$_REQUEST["notes"],
						":carrier_id"=>$_REQUEST["carrier_id"],
						":tracking"=>$_REQUEST["tracking"],
						":title"=>$_REQUEST["title"],
						":warehouse_id"=>$_REQUEST["warehouse_id"],
						":ordertype_id"=>$_REQUEST["ordertype_id"],
						":po_number"=>$_REQUEST["po_number"],
						":shipping_cost"=>$_REQUEST["shipping_cost"],
						":id"=>$_REQUEST["id"]));*/

$stmt = pdo_query( $pdo, "UPDATE log_movement_uk SET company=:company, name=:name, address_1=:address_1, address_2=:address_2, city=:city, state=:state, zip_code=:zip_code, country=:country, notes=:notes, carrier_id=:carrier_id, tracking=:tracking, title=:title, warehouse_id=:warehouse_id, ordertype_id=:ordertype_id, po_number=:po_number, shipping_cost=:shipping_cost WHERE id=:id", 
							array(
										":company"=>$_REQUEST["company"], 
										":name"=>$_REQUEST["name"], 
										":address_1"=>$_REQUEST["address_1"], 
										":address_2"=>$_REQUEST["address_2"], 
										":city"=>$_REQUEST["city"], 
										":state"=>$_REQUEST["state"],
										":zip_code"=>$_REQUEST["zip_code"],
										":country"=>$_REQUEST["country"],
										":notes"=>$_REQUEST["notes"],
										":carrier_id"=>$_REQUEST["carrier_id"],
										":tracking"=>$_REQUEST["tracking"],
										":title"=>$_REQUEST["title"],
										":warehouse_id"=>$_REQUEST["warehouse_id"],
										":ordertype_id"=>$_REQUEST["ordertype_id"],
										":po_number"=>$_REQUEST["po_number"],
										":shipping_cost"=>$_REQUEST["shipping_cost"],
										":id"=>$_REQUEST["id"]
									)
								);
						
			/*			
		$query = "UPDATE log_movement SET company=:company, name=:name, address_1=:address_1, address_2=:address_2, city=:city, state=:state, zip_code=:zip_code, notes=:notes, carrier_id=:carrier_id,	tracking=:tracking,	country=:country, title=:title, warehouse_id=:warehouse_id, ordertype_id=:ordertype_id, po_number=:po_number, shipping_cost=:shipping_cost WHERE id=:id"; 
		$params=array(
						":company"=>$_REQUEST["company"], 
						":name"=>$_REQUEST["name"], 
						":address_1"=>$_REQUEST["address_1"], 
						":address_2"=>$_REQUEST["address_1"], 
						":city"=>$_REQUEST["city"], 
						":state"=>$_REQUEST["state"],
						":zip_code"=>$_REQUEST["zip_code"],
						":country"=>$_REQUEST["country"],
						":notes"=>$_REQUEST["notes"],
						":carrier_id"=>$_REQUEST["carrier_id"],
						":tracking"=>$_REQUEST["tracking"],
						":title"=>$_REQUEST["title"],
						":warehouse_id"=>$_REQUEST["warehouse_id"],
						":ordertype_id"=>$_REQUEST["ordertype_id"],
						":po_number"=>$_REQUEST["po_number"],
						":shipping_cost"=>$_REQUEST["shipping_cost"],
						":id"=>$_REQUEST["id"]);
						*/
						
	$rowcount = pdo_rows_affected( $stmt );
	/*if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response['code']='Fail';
		$response["message"] =" Update Not";
		$response['test'] = $rowcount;
		$response['test1'] = $_REQUEST["id"];
		echo json_encode($response);
		exit;
	} else {*/
		$response['code']='success';
		$response["message"] =" Update successful!";
		$response['test'] = $_REQUEST["warehouse_id"];
		$response['test1'] = $_REQUEST["id"];
		echo json_encode($response);
	//}
			//}
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>