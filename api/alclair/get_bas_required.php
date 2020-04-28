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
    $conditionSql2 = "";
    $conditionSql_printed = "";
    $pagingSql = "";
    $orderBySqlDirection = "ASC";
    $orderBySql = " ORDER BY order_id $orderBySqlDirection";
    $params = array();
    $params2 = array();
    
    $result_for_view_file = [];
    $result_for_view_file2 = [];
    
    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }
   
    if ($_REQUEST['RUSH_OR_NOT'] == 1) {
			$conditionSql .=" AND (t1.rush_process = 'Yes')";
			$conditionSql .= " AND (t1.order_status_id != 12)";
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    if ($_REQUEST['REMOVE_HEARING_PROTECTION'] == 1) {
			//$conditionSql .=" AND (t1.hearing_protection != TRUE)";
			$conditionSql .=" AND ( (t1.hearing_protection != TRUE AND t1.model IS NOT NULL) OR (t1.hearing_protection != TRUE AND t1.model IS NULL) )";
			$conditionSql .= " AND (t1.order_status_id != 12)";
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    if ($_REQUEST['monitor_id'] > 0 ) {
		$stmt = pdo_query( $pdo, "SELECT name FROM monitors WHERE id = :monitor_id", array(":monitor_id"=>$_REQUEST['monitor_id'] ));
		$name_of_monitor = pdo_fetch_array( $stmt );

		$conditionSql .= " AND t1.model = :name_of_monitor";
		$params[":name_of_monitor"] = $name_of_monitor["name"];
		
		//$response["test"] = $name_of_monitor["name"];
		//echo json_encode($response);
		//exit;
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    
	    
	    $today=getdate(date("U"));
		$date_4_sql = $today['m'] . "/". $today['day'] . "/" . $today['year'];
		$today_4_sql = date("m/d/Y");
		
		
		
		$tomorrow_4_sql = date('m/d/Y', strtotime('+1 days'));
		$nextWeek_4_sql = date('m/d/Y', strtotime('+7 days'));
		$threeWeeks_4_sql = date('m/d/Y', strtotime('+21 days'));
		$sixWeeks_4_sql = date('m/d/Y', strtotime('+42 days'));
		
		$twoWeeks_4_sql = date('m/d/Y', strtotime('+14 days'));
		$fourWeeks_4_sql = date('m/d/Y', strtotime('+28 days'));
		$fiveWeeks_4_sql = date('m/d/Y', strtotime('+35 days'));

		//if(!empty($_REQUEST["StartDate"])) {
		
		
		//if( strlen($_REQUEST['TODAY_OR_NEXT_WEEK']) == 17) {   // , 'Today and Tomorrow') ) {
		if( $_REQUEST['TODAY_OR_NEXT_WEEK'] == '1') {   // MEANS TODAY
			//$conditionSql.=" and (t1.estimated_ship_date = :Date) AND t1.order_status_id != 12 ";
			// EVERYTHING BEFORE CASING - CASING IS STATUS 5
			$conditionSql.=" and (t1.estimated_ship_date = :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Date"]=  $today_4_sql;
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '0') { // MEANS PAST DUE
			$conditionSql.=" and (t1.estimated_ship_date < :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Date"]= $today_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '2') { // MEANS TOMORROW
			$conditionSql.=" and (t1.estimated_ship_date = :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Date"]= $tomorrow_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '3') { // MEANS NEZT 7 CALENDAR DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $nextWeek_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '4') { // MEANS NEXT 14 CALENDAR DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"]= $twoWeeks_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '5') { // MEANS NEXT 21 CALENDAR DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"]= $threeWeeks_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '6') { // MEANS NEXT 28 CALENDAR DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"]= $fourWeeks_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '7') { // MEANS NEXT 35 CALENDAR DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Today"] = $today_4_sql;
			$params[":Date"]= $fiveWeeks_4_sql;	
		}  else {  // NEXT 42 CALENDAR DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.order_status_id <= 5 AND t1.order_status_id >=1 OR t1.order_status_id = 15) ";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $sixWeeks_4_sql;	
		}
    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
     if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
    
    //Get Total Passed
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE $conditionSql AND t1.printed = TRUE";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );

    $response["test"] = $conditionSql;
    $response["test2"] = $_REQUEST['id'];

        $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date,IEMs.id AS monitor_id, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE 1=1 AND t1.active = TRUE $conditionSql"; // $orderBySql $pagingSql";
                  
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    $response["test"] = count($result);
		echo json_encode($response);
		exit;
    
    // CALCULATE NUMBER OF DAYS PAST DUE
    // DETERMINE NUMBER OF QC FORMS AND STATUS OF INITIAL PASS
    $workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
	$holidayDays = array('*-12-25', '*-01-01', '2013-12-23'); # variable and fixed holidays
	$count_dual = 0;
	$count_dualxb = 0;
	$count_reference = 0;
	$count_tour = 0;
	$count_rsm = 0;
	$count_cmvk = 0;
	$count_spire = 0;
	$count_revx = 0;
	$count_studio3 = 0;
	$count_studio4 = 0;
	$count_electro = 0;
	$count_versa = 0;
	
	$part_2389 = 0;
	$part_2015 = 0;
	$part_2091 = 0;
	$part_33A007 = 0;
	$part_3800 = 0;
	$part_2800 = 0;
	$part_E25 = 0;
	$part_35A007 = 0;
	$part_36A007 = 0;
	$part_17A003 = 0;
	$part_6500 = 0;
	$part_1723WT03_9 = 0;

	
	// COUNTING THE NUMBER OF EACH MONITOR THAT IS IN THE PIPELINE
    for ($i = 0; $i < $rows_in_result; $i++) {
	    $response["test"] = $result[$i]["model"];
	    
	    if(strcmp($result[$i]["model"], "Dual") == 0) {
		    $count_dual = $count_dual + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "Dual XB") == 0) {
		    $count_dualxb = $count_dualxb + 1;
	    }	  
	    elseif(strcmp($result[$i]["model"], "Reference") == 0) {
		    $count_reference = $count_reference + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "Tour") == 0) {
		    $count_tour = $count_tour + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "RSM") == 0) {
		    $count_rsm = $count_rsm + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "CMVK") == 0) {
		    $count_cmvk = $count_cmvk + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "Spire") == 0) {
		    $count_spire = $count_spire + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "Rev X") == 0) {
		    $count_revx = $count_revx + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "Studio3") == 0) {
		    $count_studio3 = $count_studio3 + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "Studio4") == 0) {
		    $count_studio4 = $count_studio4 + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "Electro") == 0) {
		    $count_electro = $count_electro + 1;
	    }	
	     elseif(strcmp($result[$i]["model"], "Versa") == 0) {
		    $count_versa = $count_versa + 1;
	    }	
	}
	$response["test"] = $count_reference;
	//echo json_encode($response);
	//exit;
	
for ($i = 0; $i < $rows_in_result; $i++) {
   
   $stmt3 = pdo_query( $pdo, "SELECT * FROM parts_required AS t1 LEFT JOIN part_table AS t2 ON t1.part_id = t2.id
   WHERE monitor_id = :monitor_id", array(":monitor_id"=>$result[$i]["monitor_id"])); 
   $get_parts = pdo_fetch_all( $stmt3 );
   
   $response["test"] = $get_parts[0]["part_id"];
	 //echo json_encode($response);
	 //exit;
   
     for ($j = 0; $j < count($get_parts); $j++) {
	     if($get_parts[$j]["part_id"] == 1) {
		     $part_2389 = $get_parts[$j]["quantity"]  + $part_2389;
	     } elseif($get_parts[$j]["part_id"] == 2) {
		     $part_2015 = $get_parts[$j]["quantity"] + $part_2015;
	     } elseif($get_parts[$j]["part_id"] == 3) {
		     $part_2091 = $get_parts[$j]["quantity"] + $part_2091;
	     } elseif($get_parts[$j]["part_id"] == 4) {
		     $part_33A007 = $get_parts[$j]["quantity"] + $part_33A007;
	     } elseif($get_parts[$j]["part_id"] == 5) {
		     $part_3800 = $get_parts[$j]["quantity"] + $part_3800;
	     } elseif($get_parts[$j]["part_id"] == 6) {
		      $part_2800 = $get_parts[$j]["quantity"] + $part_2800;
	     } elseif($get_parts[$j]["part_id"] == 7) {
		     $part_E25 = $get_parts[$j]["quantity"] + $part_E25;
	     } elseif($get_parts[$j]["part_id"] == 8) {
		     $part_35A007 = $get_parts[$j]["quantity"] + $part_35A007;
	     } elseif($get_parts[$j]["part_id"] == 9) {
		     $part_36A007 = $get_parts[$j]["quantity"] + $part_36A007;
	     } elseif($get_parts[$j]["part_id"] == 10) {
		     $part_17A003 = $get_parts[$j]["quantity"] + $part_17A003;
	     } elseif($get_parts[$j]["part_id"] == 11) {
		     $part_6500 = $get_parts[$j]["quantity"] + $part_6500;
	     } elseif($get_parts[$j]["part_id"] == 12) {
		     $part_1723WT03_9 = $get_parts[$j]["quantity"] + $part_1723WT03_9;
	     } 
	 }
	 
	 $response["test"] = $part_2389;
	 //echo json_encode($response);
	 //exit;
   //$result_for_view_file[3]["monitor_count"] = $count_reference;
}

   
   
   
   // BUILDING THE FINAL TABLES TO SEND TO THE VIEW FILE
   // THIS IS ALL OF THE MONITOR NAMES
   	//$Monitors = array("Dual", "Dual XB", "Reference", "Tour", "RSM", "CMVK", "Spire", "Studio4", "Studio3", "Rev X");
	//$stmt2 = pdo_query( $pdo, "SELECT * from monitors WHERE id > 1 ORDER BY id", null); 
	$stmt2 = pdo_query( $pdo, "SELECT * from monitors WHERE id > 0 AND id < 13 ORDER BY id", null);  
    $get_result = pdo_fetch_all( $stmt2 );
    $Monitors = $get_result["name"];
    for ($i = 0; $i < count($get_result); $i++) {
		$result_for_view_file[$i]["monitors"] = $get_result[$i]["name"];
		
		if($i == 0) {
			$result_for_view_file[$i]["monitor_count"]  = $count_versa;
		} else if ( $i == 1) {
			$result_for_view_file[$i]["monitor_count"]  = $count_dual;
		} else if ( $i == 2) {
			$result_for_view_file[$i]["monitor_count"]  = $count_dualxb;
   		} elseif ( $i == 3) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_reference;
   		} elseif ( $i == 4) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_tour;
   		} elseif ( $i == 5) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_rsm;
   		} elseif ( $i == 6) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_cmvk;
   		} elseif ( $i == 7) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_spire;
   		} elseif ( $i == 8) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_studio4;
   		} elseif ( $i == 9) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_studio3;
   		} elseif ( $i == 10) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_revx;
   		} elseif ( $i == 11) {
   			$result_for_view_file[$i]["monitor_count"]  = $count_electro;
   		}	elseif ( $i == 12) {
	   		
   		}
	}

   // BUILDING THE FINAL TABLE TO SEND TO THE VIEW FILE
   // THIS IS ALL OF THE BALANCED ARMATURES NAMES
   $stmt4 = pdo_query( $pdo, "SELECT * from part_table ORDER BY id", null); 
   $get_part_table = pdo_fetch_all( $stmt4 );
   
   for ($i = 0; $i < count($get_part_table); $i++) {
   		$result_for_view_file2[$i]["part"] = $get_part_table[$i]["part"];
   		
	     if($get_part_table[$i]["id"] == 1) {
		     $result_for_view_file2[$i]["quantity"] = $part_2389;
	     } elseif($get_part_table[$i]["id"] == 2) {
		     $result_for_view_file2[$i]["quantity"] = $part_2015;
	     } elseif($get_part_table[$i]["id"] == 3) {
		     $result_for_view_file2[$i]["quantity"] = $part_2091;
	     } elseif($get_part_table[$i]["id"] == 4) {
		     $result_for_view_file2[$i]["quantity"] = $part_33A007;
	     } elseif($get_part_table[$i]["id"] == 5) {
		     $result_for_view_file2[$i]["quantity"] = $part_3800;
	     } elseif($get_part_table[$i]["id"] == 6) {
		      $result_for_view_file2[$i]["quantity"] = $part_2800;
	     } elseif($get_part_table[$i]["id"] == 7) {
		    $result_for_view_file2[$i]["quantity"] = $part_E25;
	     } elseif($get_part_table[$i]["id"] == 8) {
		     $result_for_view_file2[$i]["quantity"] = $part_35A007;
	     } elseif($get_part_table[$i]["id"] == 9) {
		     $result_for_view_file2[$i]["quantity"] = $part_36A007;
	     } elseif($get_part_table[$i]["id"] == 10) {
		     $result_for_view_file2[$i]["quantity"] = $part_17A003;
	     } elseif($get_part_table[$i]["id"] == 11) {
		     $result_for_view_file2[$i]["quantity"] = $part_6500;
	     } elseif($get_part_table[$i]["id"] == 12) {
		     $result_for_view_file2[$i]["quantity"] = $part_1723WT03_9;
	     } 
   }
   
    
    //$response['code'] = 'success';
    //$response["message"] = $query;
    $response['data'] = $result_for_view_file;
    $response['data2'] = $result_for_view_file2;
    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////// THIS PORTION IS TO DETERMINE THE COUNTS IN CASING AND BEFORE /////////////////////////////////////////////////
    $query_casing = "SELECT *, IEMs.id AS monitor_id  FROM import_orders 
    								LEFT JOIN monitors AS IEMs ON import_orders.model = IEMs.name
									WHERE order_status_id <= 5 AND import_orders.active = TRUE"; // $orderBySql $pagingSql";

	$stmt_casing = pdo_query( $pdo, $query_casing, null); 
    $result = pdo_fetch_all( $stmt_casing );
    $rows_in_result = pdo_rows_affected($stmt_casing);
        
    // CALCULATE NUMBER OF DAYS PAST DUE
    // DETERMINE NUMBER OF QC FORMS AND STATUS OF INITIAL PASS
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
	$count_versa_casing = 0;
	
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
	
	
	// COUNTING THE NUMBER OF EACH MONITOR THAT IS IN THE PIPELINE
    for ($i = 0; $i < $rows_in_result; $i++) {
	    if(strcmp($result[$i]["model"], 'Dual') == 0) {
		    $count_dual_casing = $count_dual_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'Dual XB') == 0) {
		    $count_dualxb_casing = $count_dualxb_casing + 1;
	    }	  
	    if(strcmp($result[$i]["model"], 'Reference') == 0) {
		    $count_reference_casing = $count_reference_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'Tour') == 0) {
		    $count_tour_casing = $count_tour_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'RSM') == 0) {
		    $count_rsm_casing = $count_rsm_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'CMVK') == 0) {
		    $count_cmvk_casing = $count_cmvk_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'Spire') == 0) {
		    $count_spire_casing = $count_spire_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'Rev X') == 0) {
		    $count_revx_casing = $count_revx_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'Studio3') == 0) {
		    $count_studio3_casing = $count_studio3_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'Studio4') == 0) {
		    $count_studio4_casing = $count_studio4_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'Electro') == 0) {
		    $count_electro_casing = $count_electro_casing + 1;
	    }	
	     if(strcmp($result[$i]["model"], 'Versa') == 0) {
		    $count_versa_casing = $count_versa_casing + 1;
	    }	
	}
	$response["test"] = $count_reference_casing;
	//echo json_encode($response);
	//exit;
	
for ($i = 0; $i < $rows_in_result; $i++) {
   
   $stmt3 = pdo_query( $pdo, "SELECT * FROM parts_required AS t1 LEFT JOIN part_table AS t2 ON t1.part_id = t2.id
   WHERE monitor_id = :monitor_id", array(":monitor_id"=>$result[$i]["monitor_id"])); 
   $get_parts = pdo_fetch_all( $stmt3 );
   
   //$response["test"] = "ROWS IS " . $rows_in_result;
   //echo json_encode($response);
   //exit;
   
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
	     } 
	 }
}
   // BUILDING THE FINAL TABLES TO SEND TO THE VIEW FILE
   // THIS IS ALL OF THE MONITOR NAMES
   	//$Monitors = array("Dual", "Dual XB", "Reference", "Tour", "RSM", "CMVK", "Spire", "Studio4", "Studio3", "Rev X", "Electro");
	//$stmt2 = pdo_query( $pdo, "SELECT * from monitors WHERE id > 1 ORDER BY id", null); 
	$stmt2 = pdo_query( $pdo, "SELECT * from monitors WHERE id > 0 ORDER BY id", null); 
    $get_result = pdo_fetch_all( $stmt2 );
    $Monitors = $get_result["name"];
    for ($i = 0; $i < count($get_result); $i++) {
		$result_for_view_file[$i]["monitors"] = $get_result[$i]["name"];
		
		if($i == 0) {
			$result_for_view_file[$i]["casing_count"]  = $count_versa_casing;
		} else if ( $i == 1) {
			$result_for_view_file[$i]["casing_count"]  = $count_dual_casing;
		} else if ( $i == 2) {
			$result_for_view_file[$i]["casing_count"]  = $count_dualxb_casing;
   		} elseif ( $i == 3) {
   			$result_for_view_file[$i]["casing_count"]  = $count_reference_casing;
   		} elseif ( $i == 4) {
   			$result_for_view_file[$i]["casing_count"]  = $count_tour_casing;
   		} elseif ( $i == 5) {
   			$result_for_view_file[$i]["casing_count"]  = $count_rsm_casing;
   		} elseif ( $i == 6) {
   			$result_for_view_file[$i]["casing_count"]  = $count_cmvk_casing;
   		} elseif ( $i == 7) {
   			$result_for_view_file[$i]["casing_count"]  = $count_spire_casing;
   		} elseif ( $i == 8) {
   			$result_for_view_file[$i]["casing_count"]  = $count_studio4_casing;
   		} elseif ( $i == 9) {
   			$result_for_view_file[$i]["casing_count"]  = $count_studio3_casing;
   		} elseif ( $i == 10) {
   			$result_for_view_file[$i]["casing_count"]  = $count_revx_casing;
   		} elseif ( $i == 11) {
   			$result_for_view_file[$i]["casing_count"]  = $count_electro_casing;
   		}	elseif ( $i == 12) {
	   		
   		}
	}
	
   // BUILDING THE FINAL TABLE TO SEND TO THE VIEW FILE
   // THIS IS ALL OF THE BALANCED ARMATURES NAMES
   $stmt4 = pdo_query( $pdo, "SELECT * from part_table ORDER BY id", null); 
   $get_part_table = pdo_fetch_all( $stmt4 );
   
   for ($i = 0; $i < count($get_part_table); $i++) {
   		//$result_for_view_file2[$i]["part"] = $get_part_table[$i]["part"];
   			$response["test"] = "RIGHT HERE";
		    //echo json_encode($response);
			//exit;
	     if($get_part_table[$i]["id"] == 1) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_2389_casing;
		     //$response["test"] = "THE NUMBER IS " . $result_for_view_file2[$i]["casing_quantity"];
		     //echo json_encode($response);
			 //exit;
	     } elseif($get_part_table[$i]["id"] == 2) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_2015_casing;
	     } elseif($get_part_table[$i]["id"] == 3) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_2091_casing;
	     } elseif($get_part_table[$i]["id"] == 4) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_33A007_casing;
	     } elseif($get_part_table[$i]["id"] == 5) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_3800_casing;
	     } elseif($get_part_table[$i]["id"] == 6) {
		      $result_for_view_file2[$i]["casing_quantity"] = $part_2800_casing;
	     } elseif($get_part_table[$i]["id"] == 7) {
		    $result_for_view_file2[$i]["casing_quantity"] = $part_E25_casing;
	     } elseif($get_part_table[$i]["id"] == 8) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_35A007_casing;
	     } elseif($get_part_table[$i]["id"] == 9) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_36A007_casing;
	     } elseif($get_part_table[$i]["id"] == 10) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_17A003_casing;
	     } elseif($get_part_table[$i]["id"] == 11) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_6500_casing;
	     } elseif($get_part_table[$i]["id"] == 12) {
		     $result_for_view_file2[$i]["casing_quantity"] = $part_1723WT03_9_casing;
	     } 
   }
   
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result_for_view_file;
    $response['data2'] = $result_for_view_file2;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>