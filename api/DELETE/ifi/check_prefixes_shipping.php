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
	$import["new"] = $_POST["serial_numbers"];
	if (count($import["new"]) > 0 && strlen($import["new"]) >=1 )
		$new = explode("\n",$import["new"]);
	
	$import["import_type"] = $_POST["import_type"];
	$import["original_order_number"] = $_POST["original_order_number"];
	$import["received_from"] = $_POST["received_from"];
	
	//if (count($import["new"]) > 0 && strlen($import["new"]) >=1 )
	//	$new = explode("\n",$import["new"]);
	
	$stmt = pdo_query( $pdo, 'SELECT * FROM products',null);	
    $result = pdo_fetch_all($stmt);
    $row_count = pdo_rows_affected( $stmt );
     
    $num_new = count($new); 
   // $response["make_a_beep"] = count($new);
    //echo json_encode($response);
	//exit;
    
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