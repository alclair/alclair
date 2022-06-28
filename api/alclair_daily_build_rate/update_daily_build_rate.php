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
    $daily = array();
	$daily['daily_rate'] = $_POST['daily_rate'];
	$daily['fudge'] = $_POST['fudge'];
	$daily['shop_days'] = $_POST['shop_days'];
	$daily['tat_repairs'] = $_POST['tat_repairs'];
	$daily['tat_orders'] = $_POST['tat_orders'];
		
	$stmt = pdo_query( $pdo, 'UPDATE daily_build_rate SET daily_rate = :daily_rate, fudge = :fudge, shop_days = :shop_days, tat_repairs = :tat_repairs, tat_orders = :tat_orders', array(":daily_rate"=>$daily["daily_rate"], ":fudge"=>$daily["fudge"], ":shop_days"=>$daily["shop_days"], ":tat_repairs"=>$daily["tat_repairs"], ":tat_orders"=>$daily["tat_orders"]));
	
	/*
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response['code'] = 'NOPE';
		echo json_encode($response);
		exit;
	}*/
	
	$response['code'] = 'success';
	$response['data'] = $rowcount;
	$response['testing1'] = $qc_form['ports_cleaned'] ;
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>