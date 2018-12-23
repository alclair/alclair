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
	$import["new"] = $_POST["sealed"];
	$import["amazon"] = $_POST["amazon"];
	$import["demo"] = $_POST["demo"];
	$import["faulty"] = $_POST["faulty"];
	
	$import["category_id"] = $_POST["category_id"];
	$import["product_id"] = $_POST["product_id"];
	
	
	if (count($import["new"]) > 0)
	$new = explode("\n",$import["new"]);
	
	if (count($import["amazon"]) > 0) 
	$amazon = explode("\n",$import["amazon"]);
	
	if (count($import["demo"]) > 0) 			
	$demo = explode("\n",$import["demo"]);
	
	if (count($import["faulty"]) > 0) 
	$faulty = explode("\n",$import["faulty"]);
	
	$stmt = pdo_query( $pdo, 'SELECT * FROM products',null);	
    $result = pdo_fetch_all($stmt);
    $rowcount = pdo_rows_affected( $stmt );
     
    $num_new = count($new); 
    $num_amazon = count($amazon); 
    $num_demo = count($demo); 
    $num_faulty = count($faulty); 
    
    $response["test"] = $new[0];
 // THESE NEXT FOUR SECTIONS DETERMINES IF THE SERIAL NUMBER SHOULD EXIST BY DETERMINING IF ITS PREFIX EXISTS
 // SEE ELSE STATEMENTS BELOW FOR THAT CHECK
 // IF THE PREFIX DOES EXIST THE PERTINENT VALUES ARE STORED (SN, PRODUCT NAME, PRODUCT ID & CATEGORY OF PRODUCT
 // THERE ARE FOUR SECTIONS (SEALED, AMAZON, DEMO & FAULTY)    
//////////////////////////////////////////////////////////////////////               SEALED           //////////////////////////////////////////////////////////////////////////
/*
$count_new = 0;
for ($i = 0; $i < $num_new; $i++) {
    	for ($x = 0; $x < $rowcount; $x++) {  // LOOPING THROUGH ALL OF THE PRODUCTS AND THEIR PREFIXES
			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // LOOPS THROUGH EVERY PRODUCT
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
    	for ($x = 0; $x < $rowcount; $x++) { // LOOPS THROUGH EVERY PRODUCT
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
////////////////////////////////////////////////////     FINDS THE ID FOR THE LOG MOVEMENT ENTRY       /////////////////////////////////////
// THIS IS PLACED HERE BECAUSE IN THE EVENT A SERIAL NUMBER SHOULD NOT EXIST DUE TO ITS PREFIX NOT EXISTING
// WE DO NOT WANT A NEW LINE INSERTED 	INTO THE LOG MOVEMENT TABLE
    $stmt = pdo_query( $pdo, 
					   "INSERT INTO log_movement (movement, movement_type, date)
					     VALUES (:movement, :movement_type, now()) RETURNING id",
						 array(':movement'=>"IMPORT", ':movement_type'=>"Import Page")
				);		
	$result2 = pdo_fetch_all($stmt);
    $log_id = $result2[0]["id"];   
 
//////////////////////////////////////////////////////////////////// 		SEALED 		////////////////////////////////////////////////////////////////////////////////////////
	for ($i = 0; $i < $num_new; $i++) {
		$stmt = pdo_query( $pdo, 'SELECT * FROM serial_numbers WHERE serial_number = :sn_new', array(":sn_new"=>$new[$i]) );	
		$result = pdo_fetch_all($stmt);
		if ( count($result) > 0) {
			if ($result[0]["status"] != 'SEALED') {
				$query = "UPDATE serial_numbers SET status = 'SEALED' WHERE serial_number = :serial_number"; 
				$params=array(":serial_number"=>$new[$i]);
				pdo_query($pdo,$query,$params);
			} else {
				break;
			}
		} else {
			$stmt = pdo_query( $pdo, "SELECT * FROM products WHERE id = :product_id", array(':product_id'=>$import["product_id"]));		
			$product_name = pdo_fetch_all($stmt);
			
			$stmt = pdo_query( $pdo, 
					   "INSERT INTO serial_numbers (serial_number, product_id, product_name, category_id, status, active)
					     VALUES (:serial_number, :product_id, :product_name,  :category_id, :status, :active) RETURNING id",
						 array(':serial_number'=>$new[$i], ':product_id'=>$import["product_id"], ':product_name'=>$product_name[0]["name"], ':category_id'=>$import["category_id"], ':status'=>"SEALED", ':active'=>TRUE)
				);		
		}
		$stmt = pdo_query( $pdo, 
			"INSERT INTO order_tracking (serial_number, date, status, notes, log_id)
			VALUES (:serial_number, now(), :status, :notes, :log_id) RETURNING id",
			array(':serial_number'=>$new[$i], ':status'=>"IMPORTED", ':notes'=>"Sealed", ":log_id"=>$log_id)
			);					 	
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////                AMAZON           ////////////////////////////////////////////////////////////////////////
	
	for ($i = 0; $i < $num_amazon; $i++) {
		$stmt = pdo_query( $pdo, 'SELECT * FROM serial_numbers WHERE serial_number = :sn_amazon', 	array(":sn_amazon"=>$amazon[$i]) );	
		$result = pdo_fetch_all($stmt);
		if ( count($result) > 0) {
			if ($result[0]["status"] != 'AMAZON') {
				$query = "UPDATE serial_numbers SET status = 'AMAZON' WHERE serial_number = :serial_number"; 
				$params=array(":serial_number"=>$amazon[$i]);
				pdo_query($pdo,$query,$params);
			} else {
				break;
			}
		} else {
			$stmt = pdo_query( $pdo, "SELECT * FROM products WHERE id = :product_id", array(':product_id'=>$import["product_id"]));		
			$product_name = pdo_fetch_all($stmt);
						
			$stmt = pdo_query( $pdo, 
					   "INSERT INTO serial_numbers (serial_number, product_id, product_name, category_id, status, active)
					     VALUES (:serial_number, :product_id, :product_name, :category_id, :status, :active) RETURNING id",
						 array(':serial_number'=>$amazon[$i], ':product_id'=>$import["product_id"], ':product_name'=>$product_name[0]["name"],  						':category_id'=>$import["category_id"], ':status'=>"AMAZON", ':active'=>TRUE)
				);					 	
		}
		$stmt = pdo_query( $pdo, 
			"INSERT INTO order_tracking (serial_number, date, status, notes, log_id)
			VALUES (:serial_number, now(), :status, :notes, :log_id) RETURNING id",
			array(':serial_number'=>$amazon[$i], ':status'=>"IMPORTED", ':notes'=>"AMAZON", ":log_id"=>$log_id)
			);				
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
//////////////////////////////////////////////////////////////////////               DEMO           ///////////////////////////////////////////////////////////////////////////////
	
	for ($i = 0; $i < $num_demo; $i++) {
		$stmt = pdo_query( $pdo, 'SELECT * FROM serial_numbers WHERE serial_number = :sn_demo', array(":sn_demo"=>$demo[$i]) );	
		$result = pdo_fetch_all($stmt);
		if ( count($result) > 0) {
			if ($result[0]["status"] != 'DEMO') {
				$query = "UPDATE serial_numbers SET status = 'DEMO' WHERE serial_number = :serial_number"; 
				$params=array(":serial_number"=>$demo[$i]);
				pdo_query($pdo,$query,$params);
			} else {
				break;
			}
		} else {
			$stmt = pdo_query( $pdo, "SELECT * FROM products WHERE id = :product_id", array(':product_id'=>$import["product_id"]));		
			$product_name = pdo_fetch_all($stmt);
			
			$stmt = pdo_query( $pdo, 
					   "INSERT INTO serial_numbers (serial_number, product_id, product_name, category_id, status, active)
					     VALUES (:serial_number, :product_id, :product_name, :category_id, :status, :active) RETURNING id",
						 array(':serial_number'=>$demo[$i], ':product_id'=>$import["product_id"], ':product_name'=>$product_name[0]["name"], ':category_id'=>$import["category_id"], ':status'=>"DEMO", ':active'=>TRUE)
				);					 	
		}
		$stmt = pdo_query( $pdo, 
			"INSERT INTO order_tracking (serial_number, date, status, notes, log_id)
			VALUES (:serial_number, now(), :status, :notes, :log_id) RETURNING id",
			array(':serial_number'=>$demo[$i], ':status'=>"IMPORTED", ':notes'=>"Demo", ":log_id"=>$log_id)
			);				
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////               FAULTY           ///////////////////////////////////////////////////////////////////////////////
	
	for ($i = 0; $i < $num_faulty; $i++) {
		$stmt = pdo_query( $pdo, 'SELECT * FROM serial_numbers WHERE serial_number = :sn_faulty', array(":sn_faulty"=>$faulty[$i]) );	
		$result = pdo_fetch_all($stmt);
		if ( count($result) > 0) {
			if ($result[0]["status"] != 'FAULTY') {
				$query = "UPDATE serial_numbers SET status = 'FAULTY' WHERE serial_number = :serial_number"; 
				$params=array(":serial_number"=>$faulty[$i]);
				pdo_query($pdo,$query,$params);
			} else {
				break;
			}
		} else {
			$stmt = pdo_query( $pdo, "SELECT * FROM products WHERE id = :product_id", array(':product_id'=>$import["product_id"]));		
			$product_name = pdo_fetch_all($stmt);
			
			$stmt = pdo_query( $pdo, 
					   "INSERT INTO serial_numbers (serial_number, product_id, product_name, category_id, status, active)
					     VALUES (:serial_number, :product_id, :product_name, :category_id, :status, :active) RETURNING id",
						 array(':serial_number'=>$faulty[$i], ':product_id'=>$import["product_id"], ':product_name'=>$product_name[0]["name"],  	':category_id'=>$import["category_id"], ':status'=>"FAULTY", ':active'=>TRUE)
				);					 	
		}
		$stmt = pdo_query( $pdo, 
			"INSERT INTO order_tracking (serial_number, date, status, notes, log_id)
			VALUES (:serial_number, now(), :status, :notes, :log_id) RETURNING id",
			array(':serial_number'=>$faulty[$i], ':status'=>"IMPORTED", ':notes'=>"FAULTY", ":log_id"=>$log_id)
			);				
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//$response["test2"] = $count;


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