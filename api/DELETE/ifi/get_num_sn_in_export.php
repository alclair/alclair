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
				//$response["test3"] = $rowcount;
				echo json_encode($response);
				exit;
				*/
	$import = array();
	$export["sn"] = $_POST["serial_numbers"];
	
	if (count($export["sn"]) > 0 && strlen($export["sns"]) >=1 )
		$SNs = explode("\n",$export["sn"]);
		
	$stmt = pdo_query( $pdo, 'SELECT * FROM products',null);	
    $result = pdo_fetch_all($stmt);
    $rowcount = pdo_rows_affected( $stmt );
     
    $num_SNs = count($SNs);  
    
    $total_sns = $num_SNs;
    
    $response["total_sns"] = $total_sns;
        $response["test"] = $_POST["sealed"];
    $response["test2"] = strlen($import["new"]);
 // THESE NEXT FOUR SECTIONS DETERMINES IF THE SERIAL NUMBER SHOULD EXIST BY DETERMINING IF ITS PREFIX EXISTS
 // SEE ELSE STATEMENTS BELOW FOR THAT CHECK
 // IF THE PREFIX DOES EXIST THE PERTINENT VALUES ARE STORED (SN, PRODUCT NAME, PRODUCT ID & CATEGORY OF PRODUCT
 // THERE ARE FOUR SECTIONS (SEALED, AMAZON, DEMO & FAULTY)    
//////////////////////////////////////////////////////////////////////               SEALED           //////////////////////////////////////////////////////////////////////////
/*$count_new = 0;
for ($i = 0; $i < $num_new; $i++) {
    	for ($x = 0; $x < $rowcount; $x++) {  // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			if (substr($new[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) { // Compares the imported SN with the prefix for products
				$SN_new[$count_new] = $new[$i];  // IF prefix is a match then SAVE SN
				$NAME_new[$count_new] = $result[$x]['name'];  // SAVE the name of the product
				$ID_new[$count_new] = $result[$x]['id'];  // SAVE the ID of the product
				$CATEGORY_new[$count_new] = $result[$x]['category_id'];  // SAVE catgegory ID of the product
				$count_new++;
				break;
			} else if($x == $rowcount-1) {
				$response['message'] = 'SN ' .  $new[$i] .  ' in NEW does not contain a prefix.';
				//$response["test3"] = $rowcount;
				echo json_encode($response);
				exit;
			}
	} 
} 
//////////////////////////////////////////////////////////////////////                AMAZON           ////////////////////////////////////////////////////////////////////////
$count_amazon = 0;
for ($i = 0; $i < $num_amazon; $i++) { // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
    	for ($x = 0; $x < $rowcount; $x++) { // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			if (substr($amazon[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) { // Compares the imported SN with the prefix for products
				$SN_amazon[$count_amazon] = $amazon[$i];  // IF prefix is a match then SAVE SN
				$NAME_amazon[$count_amazon] = $result[$x]['name'];  // SAVE name of the product
				$ID_amazon[$count_amazon] = $result[$x]['id'];  // SAVE name of the product
				$CATEGORY_amazon[$count_amazon] = $result[$x]['category_id'];  // SAVE catgegory ID of the product
				$count_amazon++;
				break;
			} else if($x == $rowcount-1) {
				$response['message'] = 'SN ' .  $amazon[$i] .  ' in AMAZON does not contain a prefix.';
				//$response["test3"] = $rowcount;
				echo json_encode($response);
				exit;
			}
	} 
}     
 //////////////////////////////////////////////////////////////////////               DEMO           ///////////////////////////////////////////////////////////////////////////////
$count_demo = 0;
for ($i = 0; $i < $num_demo; $i++) { // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
    	for ($x = 0; $x < $rowcount; $x++) {  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			if (substr($demo[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) { // Compares the imported SN with the prefix for products
				$SN_demo[$count_demo] = $demo[$i];  // IF prefix is a match then SAVE SN
				$NAME_demo[$count_demo] = $result[$x]['name'];  // SAVE name of the product
				$ID_demo[$count_demo] = $result[$x]['id'];  // SAVE name of the product
				$CATEGORY_demo[$count_demo] = $result[$x]['category_id'];  // SAVE catgegory ID of the product
				$count_demo++;
				break;
			} else if($x == $rowcount-1) {
				$response['message'] = 'SN ' .  $demo[$i] .  ' in DEMO does not contain a prefix.';
				//$response["test3"] = $rowcount;
				echo json_encode($response);
				exit;
			}
	} 
}     
//////////////////////////////////////////////////////////////////////               FAULTY           ///////////////////////////////////////////////////////////////////////////////
$count_faulty = 0;
for ($i = 0; $i < $num_faulty; $i++) {  // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
    	for ($x = 0; $x < $rowcount; $x++) {  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT
			if (substr($faulty[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) { // Compares the imported SN with the prefix for products
				$SN_faulty[$count_faulty] = $faulty[$i];  // IF prefix is a match then SAVE SN
				$NAME_faulty[$count_faulty] = $result[$x]['name'];  // SAVE name of the product
				$ID_faulty[$count_faulty] = $result[$x]['id'];  // SAVE name of the product
				$CATEGORY_faulty[$count_faulty] = $result[$x]['category_id'];  // SAVE catgegory ID of the product
				$count_faulty++;
				break;
			} else if($x == $rowcount-1) {
				$response['message'] = 'SN ' .  $faulty[$i] .  ' in FAULTY does not contain a prefix.';
				//$response["test3"] = $rowcount;
				echo json_encode($response);
				exit;
			}
	} 
}     
 */

	//$response["test"] = $new[1];
	//$response["test2"] = $count;


	$response['code']='success';
	//$response['data'] = $result;
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