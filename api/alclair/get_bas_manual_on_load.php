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
    $params = array();
    
    $Earphones_list = [];

    $response['testing'] = $testing;
    $response['TotalRows'] = $rows_first_sql;

	$count_dual_casing = 0;
	$count_dualxb_casing = 0;
	$count_reference_casing = 0;
	$count_tour_casing = 0;
	$count_rsm_casing = 0;
	$count_cmvk_casing = 0;
	$count_spire_casing = 0;
	$count_revx_casing = 0;
	$count_studio3_casing = 0;
	$count_studio4_casing = 0;
	$count_electro_casing = 0;
	$count_esm_casing = 0;
	$count_versa_casing = 0;
	$count_st3_casing = 0;
	$count_dkm_casing = 0;
	$count_icon_casing = 0;
	

	$stmt = pdo_query( $pdo, "SELECT  models.model, COALESCE(COUNT(t1.model), 0) AS model_count
													FROM (VALUES 
												        ('Versa'), ('Dual XB'), ('ST3'), ('Tour'), ('RSM'), ('CMVK'),
												        ('Spire'), ('Rev X'), ('Studio4'), ('Electro'), ('ESM'), 
												        ('DKM'), ('ICON')) AS models(model)
												LEFT JOIN 
												    import_orders AS t1 ON models.model = t1.model
												    AND t1.active = TRUE  
												    AND (t1.order_status_id <= 5 OR t1.order_status_id IN (15, 16, 17))
												LEFT JOIN 
												    monitors AS IEMs ON t1.model = IEMs.name
												LEFT JOIN 
												    order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
												GROUP BY 
												    models.model
												ORDER BY 
												    models.model", null); 
	
    $Monitors = pdo_fetch_all( $stmt );
    $monitor_array = ["Versa", "Dual XB", "ST3", "Tour", "RSM", "CMVK", "Spire", "Studio4", "Rev X", "Electro", "ESM", "DKM", "ICON"];
  
    for ($i = 0; $i < count($monitor_array); $i++) {
	    $Earphones_list[$i]["monitors"] = $monitor_array[$i];
	    $Earphones_list[$i]["casing_count"] = 0;
	}
    
    for ($i = 0; $i < count($Monitors); $i++) {
		$key = array_search($Monitors[$i]["model"], $monitor_array);	    
		$Earphones_list[$key]["casing_count"] = $Monitors[$i]["model_count"];
		if(!strcmp($Monitors[$i]["model"], "Tour")) {
			$response["test"] = "I is " . $i . " and Monitor is " . $Monitors[$i]["model"] . " and count is " . $Monitors[$i]["model_count"];
			//echo json_encode($response);
			//exit;
		}
	}
	$response["test"] = "Working is " . count($Monitors);
    //echo json_encode($response);
	//exit;
   
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $Earphones_list;

    
	$response["testing"] = count($store_dampers);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    

//////        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>