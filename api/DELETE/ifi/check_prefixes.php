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
	/*
	$response['message'] = "the count is " . $num_new . " open " . $num_open_box . " demo " . $num_demo . " prc " . $num_prc;
				//$response["test3"] = $row_count;
				echo json_encode($response);
				exit;
				*/
	$import = array();
	$import["new"] = $_POST["sealed"];
	$import["amazon"] = $_POST["amazon"];
	$import["demo"] = $_POST["demo"];
	$import["faulty"] = $_POST["faulty"];
	
	$import["import_type"] = $_POST["import_type"];
	$import["original_order_number"] = $_POST["original_order_number"];
	$import["received_from"] = $_POST["received_from"];
	
	if (count($import["new"]) > 0 && strlen($import["new"]) >=1 )
		$new = explode("\n",$import["new"]);
	
	if (count($import["amazon"]) > 0 && strlen($import["amazon"]) >=1 ) 
		$amazon = explode("\n",$import["amazon"]);
	
	if (count($import["demo"]) > 0 && strlen($import["demo"]) >=1 ) 			
		$demo = explode("\n",$import["demo"]);
	
	if (count($import["faulty"]) > 0 && strlen($import["faulty"]) >=1 ) 
		$faulty = explode("\n",$import["faulty"]);
	
	$stmt = pdo_query( $pdo, 'SELECT * FROM products',null);	
    $result = pdo_fetch_all($stmt);
    $row_count = pdo_rows_affected( $stmt );
     
    $num_new = count($new); 
    $num_amazon = count($amazon); 
    $num_demo = count($demo); 
    $num_faulty = count($faulty); 
    
    
 // THESE NEXT FOUR SECTIONS DETERMINES IF THE SERIAL NUMBER SHOULD EXIST BY DETERMINING IF ITS PREFIX EXISTS
 // SEE ELSE STATEMENTS BELOW FOR THAT CHECK
 // IF THE PREFIX DOES EXIST THE PERTINENT VALUES ARE STORED (SN, PRODUCT NAME, PRODUCT ID & CATEGORY OF PRODUCT
 // THERE ARE FOUR SECTIONS (SEALED, AMAZON, DEMO & FAULTY)    
//////////////////////////////////////////////////////////////////////               SEALED           //////////////////////////////////////////////////////////////////////////
$count_new = 0;
for ($i = 0; $i < $num_new; $i++) {
	// LOOK FOR THE SERIAL NUMBER TO SEE IF IT ALREADY EXISTS IN THAT WAREHOUSE
	$stmt2 = pdo_query( $pdo, 'SELECT * FROM serial_numbers WHERE serial_number = :serial_number', array(":serial_number"=>$new[$i]));	
    $does_SN_exist = pdo_fetch_all($stmt2);
    if(count($does_SN_exist) > 0 && $does_SN_exist[0]["status"] == 'SEALED') { // IF THE COUNT IS GREATER THAN ZERO THE SN EXISTS IN THE WAREHOUSE SO EXIT AND MESSAGE
	    $response['message'] = 'SN ' .  $new[$i] .  ' in NEW exists in that warehouse.';
		echo json_encode($response);
		exit;
    }
    	for ($x = 0; $x < $row_count; $x++) {  // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			if (substr($new[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) { // Compares the imported SN with the prefix for products
				$SN_new[$count_new] = $new[$i];  // IF prefix is a match then SAVE SN
				$NAME_new[$count_new] = $result[$x]['name'];  // SAVE the name of the product
				$ID_new[$count_new] = $result[$x]['id'];  // SAVE the ID of the product
				$CATEGORY_new[$count_new] = $result[$x]['category_id'];  // SAVE catgegory ID of the product
				$count_new++;
				break;
			} else if($x == $row_count-1) {
				$response['message'] = 'SN ' .  $new[$i] .  ' in NEW does not contain a prefix.';
				$response["make_a_beep"] = "Yes";
				//$response["test3"] = $row_count;
				echo json_encode($response);
				exit;
			}
	} 
} 
//////////////////////////////////////////////////////////////////////                AMAZON           ////////////////////////////////////////////////////////////////////////
$count_amazon = 0;
for ($i = 0; $i < $num_amazon; $i++) { // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
	// LOOK FOR THE SERIAL NUMBER TO SEE IF IT ALREADY EXISTS IN THAT WAREHOUSE
	$stmt2 = pdo_query( $pdo, 'SELECT * FROM serial_numbers WHERE serial_number = :serial_number', array(":serial_number"=>$amazon[$i]));	
    $does_SN_exist = pdo_fetch_all($stmt2);
    if(count($does_SN_exist) > 0 && $does_SN_exist[0]["status"] == 'AMAZON') { // IF THE COUNT IS GREATER THAN ZERO THE SN EXISTS IN THE WAREHOUSE SO EXIT AND MESSAGE
	    $response['message'] = 'SN ' .  $amazon[$i] .  ' in AMAZON exists in that warehouse.';
		echo json_encode($response);
		exit;
    }
    	for ($x = 0; $x < $row_count; $x++) { // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			if (substr($amazon[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) { // Compares the imported SN with the prefix for products
				$SN_amazon[$count_amazon] = $amazon[$i];  // IF prefix is a match then SAVE SN
				$NAME_amazon[$count_amazon] = $result[$x]['name'];  // SAVE name of the product
				$ID_amazon[$count_amazon] = $result[$x]['id'];  // SAVE name of the product
				$CATEGORY_amazon[$count_amazon] = $result[$x]['category_id'];  // SAVE catgegory ID of the product
				$count_amazon++;
				break;
			} else if($x == $row_count-1) {
				$response['message'] = 'SN ' .  $amazon[$i] .  ' in AMAZON does not contain a prefix.';
				$response["make_a_beep"] = "Yes";
				//$response["test3"] = $row_count;
				echo json_encode($response);
				exit;
			}
	} 
}     
 //////////////////////////////////////////////////////////////////////               DEMO           ///////////////////////////////////////////////////////////////////////////////
$count_demo = 0;
for ($i = 0; $i < $num_demo; $i++) { // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
	// LOOK FOR THE SERIAL NUMBER TO SEE IF IT ALREADY EXISTS IN THAT WAREHOUSE
	$stmt2 = pdo_query( $pdo, 'SELECT * FROM serial_numbers WHERE serial_number = :serial_number', array(":serial_number"=>$demo[$i]));	
    $does_SN_exist = pdo_fetch_all($stmt2);
    if(count($does_SN_exist) > 0 && $does_SN_exist[0]["status"] == 'DEMO') { // IF THE COUNT IS GREATER THAN ZERO THE SN EXISTS IN THE WAREHOUSE SO EXIT AND MESSAGE
	    $response['message'] = 'SN ' .  $demo[$i] .  ' in DEMO exists in that warehouse.';
		echo json_encode($response);
		exit;
    }
    	for ($x = 0; $x < $row_count; $x++) {  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			if (substr($demo[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) { // Compares the imported SN with the prefix for products
				$SN_demo[$count_demo] = $demo[$i];  // IF prefix is a match then SAVE SN
				$NAME_demo[$count_demo] = $result[$x]['name'];  // SAVE name of the product
				$ID_demo[$count_demo] = $result[$x]['id'];  // SAVE name of the product
				$CATEGORY_demo[$count_demo] = $result[$x]['category_id'];  // SAVE catgegory ID of the product
				$count_demo++;
				break;
			} else if($x == $row_count-1) {
				$response['message'] = 'SN ' .  $demo[$i] .  ' in DEMO does not contain a prefix.';
				$response["make_a_beep"] = "Yes";
				//$response["test3"] = $row_count;
				echo json_encode($response);
				exit;
			}
	} 
}     
//////////////////////////////////////////////////////////////////////               FAULTY           ///////////////////////////////////////////////////////////////////////////////
$count_faulty = 0;
for ($i = 0; $i < $num_faulty; $i++) {  // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
	// LOOK FOR THE SERIAL NUMBER TO SEE IF IT ALREADY EXISTS IN THAT WAREHOUSE
	$stmt2 = pdo_query( $pdo, 'SELECT * FROM serial_numbers WHERE serial_number = :serial_number', array(":serial_number"=>$faulty[$i]));	
    $does_SN_exist = pdo_fetch_all($stmt2);
    if(count($does_SN_exist) > 0 && $does_SN_exist[0]["status"] == 'FAULTY') { // IF THE COUNT IS GREATER THAN ZERO THE SN EXISTS IN THE WAREHOUSE SO EXIT AND MESSAGE
	    $response['message'] = 'SN ' .  $faulty[$i] .  ' in FAULTY exists in that warehouse.';
	    $response["make_a_beep"] = "Yes";
		echo json_encode($response);
		exit;
    }
    	for ($x = 0; $x < $row_count; $x++) {  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			if (substr($faulty[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) { // Compares the imported SN with the prefix for products
				$SN_faulty[$count_faulty] = $faulty[$i];  // IF prefix is a match then SAVE SN
				$NAME_faulty[$count_faulty] = $result[$x]['name'];  // SAVE name of the product
				$ID_faulty[$count_faulty] = $result[$x]['id'];  // SAVE name of the product
				$CATEGORY_faulty[$count_faulty] = $result[$x]['category_id'];  // SAVE catgegory ID of the product
				$count_faulty++;
				break;
			} else if($x == $row_count-1) {
				$response['message'] = 'SN ' .  $faulty[$i] .  ' in FAULTY does not contain a prefix.';
				//$response["test3"] = $row_counts;
				echo json_encode($response);
				exit;
			}
	} 
}     
 
	$response['code']='success';
	$response['data'] = $result;
	echo json_encode($response);
	//$response['test'] = $import["new"];
	
	//var_export($result);
	
	//echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>