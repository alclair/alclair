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
    $BAs_list = [];
    
    if( !empty($_REQUEST['id']) )
    {
        //$conditionSql .= " AND t1.id = :id";
        //$params[":id"] = $_REQUEST['id'];
    }
   
    if ($_REQUEST['RUSH_OR_NOT'] == 1) {
			//$conditionSql .=" AND (t1.rush_process = 'Yes')";
			//$conditionSql .= " AND (t1.order_status_id != 12)";
    }
    if ($_REQUEST['REMOVE_HEARING_PROTECTION'] == 1) {
			//$conditionSql .=" AND (t1.hearing_protection != TRUE AND (t1.product IS NOT NULL AND t1.model IS NOT NULL)";
			$conditionSql .= " AND (t1.product IS NOT NULL AND t1.model IS NOT NULL)";
			//$conditionSql .=" AND ( (t1.hearing_protection != TRUE AND t1.model IS NOT NULL) OR (t1.hearing_protection != TRUE AND t1.model IS NULL) )";
			//$conditionSql .= " AND (t1.order_status_id != 12)";
			
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    
    $StartDate = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00'));
    $EndDate = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
     // GETS ALL ORDERS BETWEEN START CART AND CASING
	
	$query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date, to_char(t1.fake_imp_date,'yyyy/MM/dd') as fake_imp_date,IEMs.id AS monitor_id, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE t1.active = TRUE AND (t1.estimated_ship_date >= :StartDate AND t1.estimated_ship_date <= :EndDate) AND (t1.order_status_id <= 5 OR t1.order_status_id = 16)  AND (t1.product IS NOT NULL AND t1.model IS NOT NULL) ORDER BY t1.estimated_ship_date ASC";
          
    $response["test"] = "The start date is " . $StartDate . " and end date is " . $EndDate;
	//echo json_encode($response);
	//exit;      
              
    $stmt = pdo_query( $pdo, $query, array(":StartDate"=>$StartDate, ":EndDate"=>$EndDate)); 
    $first_sql = pdo_fetch_all( $stmt );
    $rows_first_sql = pdo_rows_affected($stmt);
   
    $todays_date = new DateTime();
	$todays_date->modify('+1 day'); // TOMORROW'S DATE
	$tomorrow = $todays_date;
	$tomorrow = $tomorrow->format('Y-m-d');
	$first_fake_imp_date = $tomorrow;
	//$first_fake_imp_date = $first_sql[0]["estimated_ship_date"];
	//$today_4_sql = date("m/d/Y");	
	
   // 01/04/2020 CHANGED FROM 'm/d/Y' to 'Y/m/d' SO THE LESS THAN EQUAL EQUATION ON 85 WOULD WORK
   $tomorrow_4_sql = date('Y/m/d', strtotime($first_fake_imp_date. '+1 days'));
   $threeDays_4_sql = date('Y/m/d', strtotime($first_fake_imp_date. '+3 days'));
   $nextWeek_4_sql = date('Y/m/d', strtotime($first_fake_imp_date. '+7 days'));
   $twoWeeks_4_sql = date('Y/m/d', strtotime($first_fake_imp_date. '+14 days'));
   $threeWeeks_4_sql =date('Y/m/d', strtotime($first_fake_imp_date. '+21 days'));
   $fourWeeks_4_sql = date('Y/m/d', strtotime($first_fake_imp_date. '+28 days'));
   $fiveWeeks_4_sql = date('Y/m/d', strtotime($first_fake_imp_date. '+35 days'));
   $sixWeeks_4_sql = date('Y/m/d', strtotime($first_fake_imp_date. '+42 days'));
   
	if( $_REQUEST['TODAY_OR_NEXT_WEEK'] == '1') {   // MEANS TODAY
		$date_to_use = $first_fake_imp_date;
	} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '0') { // MEANS PAST DUE
		$date_to_use = $first_fake_imp_date;
	} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '2') { // MEANS TOMORROW
		$date_to_use = $tomorrow_4_sql;
	} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '3') { // MEANS NEXT 7 CALENDAR DAYS
		$date_to_use = $nextWeek_4_sql;
	} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '4') { // MEANS NEXT 14 CALENDAR DAYS
		$date_to_use = $twoWeeks_4_sql;
	} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '5') { // MEANS NEXT 21 CALENDAR DAYS
		$date_to_use = $threeWeeks_4_sql;
	} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '6') { // MEANS NEXT 28 CALENDAR DAYS
		$date_to_use = $fourWeeks_4_sql;
	} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '7') { // MEANS NEXT 35 CALENDAR DAYS
		$date_to_use = $fiveWeeks_4_sql;
	} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '8') { // MEANS NEXT 3 CALENDAR DAYS
		$date_to_use = $threeDays_4_sql;
	}  else {  // NEXT 42 CALENDAR DAYS
		$date_to_use = $sixWeeks_4_sql;
	}
		
	$store_order = array();
	$testing = '';
	/*
	for ($i = 0; $i < $rows_first_sql; $i++) {
		//if($first_sql[$i]["estimated_ship_date"] <= $date_to_use ) {
		if($first_sql[$i]["estimated_ship_date"] >= $StartDate && $first_sql[$i]["estimated_ship_date"] <= $EndDate ) {
			$store_order[$i] = $first_sql[$i];
			$testing .= " " . $first_sql[$i]["order_id"];
		} else {
			//break;
		}
	}
	*/
	$store_order = $first_sql;
	
	$response["test"] = "First is " . $first_sql[0]["estimated_ship_date"] . " and Second is " . $StartDate;
	//echo json_encode($response);
	//exit;
	
	 //Get Total Records
    $response['TotalRecords'] = count($store_order);
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
	
	// COUNTING THE NUMBER OF EACH MONITOR THAT IS IN THE PIPELINE
    for ($i = 0; $i < count($store_order); $i++) {
	    //$response["test"] = $store_order[$i]["model"];
	    
	    if(strcmp($store_order[$i]["model"], "Dual") == 0) {
		    $count_dual_casing = $count_dual_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "Dual XB") == 0) {
		    $count_dualxb_casing = $count_dualxb_casing + 1;
	    } elseif(strcmp($store_order[$i]["model"], "Reference") == 0) {
		    $count_reference_casing = $count_reference_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "Tour") == 0) {
		    $count_tour_casing = $count_tour_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "RSM") == 0) {
		    $count_rsm_casing = $count_rsm_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "CMVK") == 0) {
		    $count_cmvk_casing = $count_cmvk_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "Spire") == 0) {
		    $count_spire_casing = $count_spire_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "Rev X") == 0) {
		    $count_revx_casing = $count_revx_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "Studio3") == 0) {
		    $count_studio3_casing = $count_studio3_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "Studio4") == 0) {
		    $count_studio4_casing = $count_studio4_casing + 1;
	    }	elseif(strcmp($store_order[$i]["model"], "Electro") == 0) {
		    $count_electro_casing = $count_electro_casing + 1;
		}	elseif(strcmp($store_order[$i]["model"], "ESM") == 0) {
			$count_esm_casing = $count_esm_casing + 1;    
	    }	elseif(strcmp($store_order[$i]["model"], "Versa") == 0) {
		    $count_versa_casing = $count_versa_casing + 1;
	    }	
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// THIS PORTION IS TO DETERMINE THE COUNTS IN CASING AND BEFORE /////////////////////////////////////////////////
    // CALCULATE NUMBER OF DAYS PAST DUE
    // DETERMINE NUMBER OF QC FORMS AND STATUS OF INITIAL PASS	
	$part_2389_casing = 0;
	$part_2015_casing = 0;
	$part_2091_casing = 0;
	$part_33A007_casing = 0;
	$part_3800_casing= 0;
	$part_2800_casing = 0;
	$part_E25_casing = 0;
	$part_35A007_casing = 0;
	$part_36A007_casing = 0;
	$part_17A003_casing = 0;
	$part_6500_casing = 0;
	$part_1723WT03_9_casing = 0;
	$part_3800_filtered_casing= 0;
	       
for ($i = 0; $i < count($store_order); $i++) {
   
   $stmt3 = pdo_query( $pdo, "SELECT * FROM parts_required AS t1 LEFT JOIN part_table AS t2 ON t1.part_id = t2.id
   WHERE monitor_id = :monitor_id", array(":monitor_id"=>$store_order[$i]["monitor_id"])); 
   $get_parts = pdo_fetch_all( $stmt3 );
   
     for ($j = 0; $j < count($get_parts); $j++) {
	     if($get_parts[$j]["part_id"] == 1) {
		     $part_2389_casing = $get_parts[$j]["quantity"]  + $part_2389_casing;
	     } elseif($get_parts[$j]["part_id"] == 2) {
		     $part_2015_casing = $get_parts[$j]["quantity"] + $part_2015_casing;
	     } elseif($get_parts[$j]["part_id"] == 3) {
		     $part_2091_casing = $get_parts[$j]["quantity"] + $part_2091_casing;
	     } elseif($get_parts[$j]["part_id"] == 4) {
		     $part_33A007_casing = $get_parts[$j]["quantity"] + $part_33A007_casing;
	     } elseif($get_parts[$j]["part_id"] == 5) {
		     $part_3800_casing = $get_parts[$j]["quantity"] + $part_3800_casing;
	     } elseif($get_parts[$j]["part_id"] == 6) {
		      $part_2800_casing = $get_parts[$j]["quantity"] + $part_2800_casing;
	     } elseif($get_parts[$j]["part_id"] == 7) {
		     $part_E25_casing = $get_parts[$j]["quantity"] + $part_E25_casing;
	     } elseif($get_parts[$j]["part_id"] == 8) {
		     $part_35A007_casing = $get_parts[$j]["quantity"] + $part_35A007_casing;
	     } elseif($get_parts[$j]["part_id"] == 9) {
		     $part_36A007_casing = $get_parts[$j]["quantity"] + $part_36A007_casing;
	     } elseif($get_parts[$j]["part_id"] == 10) {
		     $part_17A003_casing = $get_parts[$j]["quantity"] + $part_17A003_casing;
	     } elseif($get_parts[$j]["part_id"] == 11) {
		     $part_6500_casing = $get_parts[$j]["quantity"] + $part_6500_casing;
	     } elseif($get_parts[$j]["part_id"] == 12) {
		     $part_1723WT03_9_casing = $get_parts[$j]["quantity"] + $part_1723WT03_9_casing;
	     } elseif($get_parts[$j]["part_id"] == 13) {
		     $part_3800_filtered_casing = $get_parts[$j]["quantity"] + $part_3800_filtered_casing;
	     } 
	 }
}
   // BUILDING THE FINAL TABLES TO SEND TO THE VIEW FILE
   // THIS IS ALL OF THE MONITOR NAMES
   	//$Monitors = array("Dual", "Dual XB", "Reference", "Tour", "RSM", "CMVK", "Spire", "Studio4", "Studio3", "Rev X", "Electro");
	//$stmt2 = pdo_query( $pdo, "SELECT * from monitors WHERE id > 1 ORDER BY id", null); 
	$stmt2 = pdo_query( $pdo, "SELECT * from monitors WHERE id > 0 AND (id != 13 OR id != 14) ORDER BY id", null); 
    $get_result = pdo_fetch_all( $stmt2 );
    $Monitors = $get_result["name"];
    for ($i = 0; $i < count($get_result); $i++) {
		$Earphones_list[$i]["monitors"] = $get_result[$i]["name"];
		
		if($i == 0) {
			$Earphones_list[$i]["casing_count"]  = $count_versa_casing;
		} else if ( $i == 1) {
			$Earphones_list[$i]["casing_count"]  = $count_dual_casing;
		} else if ( $i == 2) {
			$Earphones_list[$i]["casing_count"]  = $count_dualxb_casing;
   		} elseif ( $i == 3) {
   			$Earphones_list[$i]["casing_count"]  = $count_reference_casing;
   		} elseif ( $i == 4) {
   			$Earphones_list[$i]["casing_count"]  = $count_tour_casing;
   		} elseif ( $i == 5) {
   			$Earphones_list[$i]["casing_count"]  = $count_rsm_casing;
   		} elseif ( $i == 6) {
   			$Earphones_list[$i]["casing_count"]  = $count_cmvk_casing;
   		} elseif ( $i == 7) {
   			$Earphones_list[$i]["casing_count"]  = $count_spire_casing;
   		} elseif ( $i == 8) {
   			$Earphones_list[$i]["casing_count"]  = $count_studio4_casing;
   		} elseif ( $i == 9) {
   			$Earphones_list[$i]["casing_count"]  = $count_studio3_casing;
   		} elseif ( $i == 10) {
   			$Earphones_list[$i]["casing_count"]  = $count_revx_casing;
   		} elseif ( $i == 11) {
   			$Earphones_list[$i]["casing_count"]  = $count_electro_casing;
   		}	elseif ( $i == 12) {
	   		$Earphones_list[$i]["casing_count"]  = $count_esm_casing;
   		}
	}
	
   // BUILDING THE FINAL TABLE TO SEND TO THE VIEW FILE
   // THIS IS ALL OF THE BALANCED ARMATURES NAMES
   $stmt4 = pdo_query( $pdo, "SELECT * from part_table ORDER BY id", null); 
   $get_part_table = pdo_fetch_all( $stmt4 );
   
   for ($i = 0; $i < count($get_part_table); $i++) {
	   	$BAs_list[$i]["part"] = $get_part_table[$i]["part"];
	     if($get_part_table[$i]["id"] == 1) {
		     $BAs_list[$i]["casing_quantity"] = $part_2389_casing;
	     } elseif($get_part_table[$i]["id"] == 2) {
		     $BAs_list[$i]["casing_quantity"] = $part_2015_casing;
	     } elseif($get_part_table[$i]["id"] == 3) {
		     $BAs_list[$i]["casing_quantity"] = $part_2091_casing;
	     } elseif($get_part_table[$i]["id"] == 4) {
		     $BAs_list[$i]["casing_quantity"] = $part_33A007_casing;
	     } elseif($get_part_table[$i]["id"] == 5) {
		     $BAs_list[$i]["casing_quantity"] = $part_3800_casing;
	     } elseif($get_part_table[$i]["id"] == 6) {
		      $BAs_list[$i]["casing_quantity"] = $part_2800_casing;
	     } elseif($get_part_table[$i]["id"] == 7) {
		    $BAs_list[$i]["casing_quantity"] = $part_E25_casing;
	     } elseif($get_part_table[$i]["id"] == 8) {
		     $BAs_list[$i]["casing_quantity"] = $part_35A007_casing;
	     } elseif($get_part_table[$i]["id"] == 9) {
		     $BAs_list[$i]["casing_quantity"] = $part_36A007_casing;
	     } elseif($get_part_table[$i]["id"] == 10) {
		     $BAs_list[$i]["casing_quantity"] = $part_17A003_casing;
	     } elseif($get_part_table[$i]["id"] == 11) {
		     $BAs_list[$i]["casing_quantity"] = $part_6500_casing;
	     } elseif($get_part_table[$i]["id"] == 12) {
		     $BAs_list[$i]["casing_quantity"] = $part_1723WT03_9_casing;
	     } elseif($get_part_table[$i]["id"] == 13) {
		     $BAs_list[$i]["casing_quantity"] = $part_3800_filtered_casing;
	     } 
   }
   
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $Earphones_list;
    $response['data2'] = $BAs_list;

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