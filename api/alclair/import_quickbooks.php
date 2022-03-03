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

//$fc="1".":".$_FILES['documentfile']['name'];

try
{
	
$target_dir = "../../data/";
$target_file = $target_dir . basename($_FILES["documentfile"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check file size
if ($_FILES["docuementfile"]["size"] > 500000) {
    $response["message"] = "Sorry, your file is too large.";
    $uploadOk = 0;
}
	// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    //$response['testing5'] = "Sorry, your file was not uploaded.";
    $response['code'] = "broken";
	echo json_encode($response);
	exit;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["documentfile"]["tmp_name"], $target_file)) {
        //$response['message'] = "The file ". basename( $_FILES["documentfile"]["name"]). " has been uploaded.";
    } else {
        $response['message'] = "Sorry, there was an error uploading your file.";
        $response['code'] = "broken here";
        echo json_encode($response);
		exit;
    }
}

// GET THE GL CODES
$query = "SELECT name FROM marketing_gl_codes ORDER BY id";
$stmt = pdo_query( $pdo, $query, null); 
$output = pdo_fetch_all( $stmt );  
$num_gl_codes = pdo_rows_affected($stmt);
$gl_codes = array();

for ($i=0; $i < $num_gl_codes; $i++) {
	$gl_codes[$i] = $output[$i]["name"];	
}

$File = $target_file;
$num_of_rows = 0;

// PUT ALL OF THE DATA INTO $arrResult
$arrResult  = array();
$handle     = fopen($File, "r");
if(empty($handle) === false) {
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $arrResult[$num_of_rows] = $data;
        $num_of_rows  = $num_of_rows + 1;
    }
    fclose($handle);
}



// EMPTY TABLES BEFORE IMPORTING
$query = pdo_query($pdo, "TRUNCATE TABLE marketing_expenses", null);		
$query = pdo_query($pdo, "TRUNCATE TABLE marketing_expenses_totals", null);		

$row = array();
$id_for_expense_line = 1;
$id_for_expense_total = 1;
for ($i=5; $i < $num_of_rows-3; $i++) {  // LOOP THROUGH ALL OF THE ROWS
	$row = $arrResult[$i];
	$is_gl_code = in_array($row[0], $gl_codes);
	
	if( $is_gl_code && !stristr($row[0], "Total")) {
		$id_in_gl_table = array_search($row[0], $gl_codes) + 1; // FINDS THE ID FROM THE GL CODE TABLE
		//continue; // STORE THE GL CODE ID THEN CONTINUE ON TO POPULATE THE marketing_expenses TABLE
	} elseif( stristr($row[0], "Total") ) {
		
		$type2 = gettype($row[8]);
		$dollar_val = ltrim($row[8], '$ ');
		$dollar_val = str_replace(",", "", $dollar_val);
		$response["code"] = "success";
		$response["testing1"] = "The type is " . strlen($dollar_val) . " and " . $dollar_val;
		//echo json_encode($response);
		//exit;
		
		$query = pdo_query( $pdo, 
			"INSERT INTO marketing_expenses_totals (id, gl_code_id, date, total)
			VALUES (:id, :gl_code_id, now(), :total) RETURNING id",
			array(':id'=>$id_for_expense_total, ':gl_code_id'=>$id_in_gl_table, ':total'=>$dollar_val)
		);
		$id_expense_total= pdo_fetch_all( $stmt );
		$id_in_expense_total = $id_expense_total[0]["id"];
		$id_for_expense_total++;
		//continue; // SKIP THIS ROW BECAUSE IT'S THE SUM OF THE LINES WITHIN THE GL CODE - IT DOES NOT NEED TO BE STORED
	} else {
		for ($j=1; $j < count($row); $j++) {
			if( strlen($row[$j]) == 0 ) {
				$row[$j] = NULL;	
			}
		}
		//if($i == 7) {
			$response["code"] = "success";
			//$date_ = str_replace("/", "-", $row[1]);
			$date1= strtotime($row[1]);
			$date2 = strtotime("+1 day", $date1);
			$date3 =  date("m/d/Y", $date2);
			$response["testing1"] = "Date is " . $date3 . " and row # is " . $i . " and final date is " . $date3;
			
			//echo json_encode($response);
			//exit;
		//}
		
		$query = pdo_query( $pdo, 
			"INSERT INTO marketing_expenses (id, gl_code_id, date, transaction_type, num, name, class, memo_description, split, amount)
			VALUES (:id, :gl_code_id, :date, :transaction_type, :num, :name, :class, :memo_description, :split, :amount) RETURNING id",
			array(':id'=>$id_for_expense_line, ':gl_code_id'=>$id_in_gl_table, ':date'=>$date3, ':transaction_type'=>$row[2], 
						':num'=>$row[3], ':name'=>$row[4], ':class'=>$row[5], ':memo_description'=>$row[6], ':split'=>$row[7], ':amount'=>$row[8])
		);
		$id_after_insert = pdo_fetch_all( $stmt );
		$id_in_table = $id_after_insert[0]["id"];
		$id_for_expense_line++;
	}
}  // END FOR LOOP
$response["code"] = "success";
$response["testing1"] = "The id ends up as " . $id_for_table;

echo json_encode($response);
}
catch(PDOException $ex)
{
	//$response["code"] = "error 2";
	$response["message"] = $ex->getMessage();
	//echo json_encode($response);
}

?>