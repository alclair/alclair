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




//$response['testing1'] = ($_FILES["documentfile"]["name"]);
//$response['testing2'] = basename($_FILES["documentfile"]["name"]);
//$response['testing3'] = ($_FILES["documentfile"]["name"]);
//$response['testing4'] = $fileType;
//$response['testing5'] = $target_dir;


/*
// Check if file already exists
if (file_exists($target_file)) {
    $response["message"] = "Sorry, file already exists.";
    $uploadOk = 0;
    $response["testing1"] = $target_file;
}
*/

// Check file size
if ($_FILES["docuementfile"]["size"] > 500000) {
    $response["message"] = "Sorry, your file is too large.";
    $uploadOk = 0;
}
	// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    //$response['testing5'] = "Sorry, your file was not uploaded.";
	echo json_encode($response);
	exit;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["documentfile"]["tmp_name"], $target_file)) {
        $response['message'] = "The file ". basename( $_FILES["documentfile"]["name"]). " has been uploaded.";
    } else {
        $response['message'] = "Sorry, there was an error uploading your file.";
        echo json_encode($response);
		exit;
    }
}

////////////////////////////////////////////////////////$row = 1;
/*
if (($handle = fopen("../../data/US Inventory Table 10_12_2017.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        //echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            $respons['testing3'] =  $data[$c] . "<br />\n";
        }
    }
    fclose($handle);
}
*/
//$File = '../../data/' + $target_file;
$File = $target_file;
$inc = 0;

// PUT ALL OF THE DATA INTO $arrResult
$arrResult  = array();
$handle     = fopen($File, "r");
if(empty($handle) === false) {
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $arrResult[$inc] = $data;
        $inc = $inc + 1;
    }
    fclose($handle);
}
$response['testing3'] = $arrResult[2][2];
$response['testing1'] = $arrResult[0];
$response['testing2'] = $arrResult[1][12];
$response['testing4'] = $inc;

echo json_encode($response);
exit;
//print_r($arrResult);
//file_put_contents ("../../data/log.txt","\r\n".$path,FILE_APPEND); 

$order  = array();
for ($i=1; $i < $inc; $i++) {
	$order = $arrResult[$i];
	
	// IS NAME SAME AS PRVIOUS NAME - COMPARE LAST NAME THEN FIRST NAME
	// IF NAME IS DIFFERENT MOVE ON - IF NAME IS SAME THEN POPULATE ONLY  GET PRODUCT DATA
	// Reviews Table
	title_id - COLUMN 1 / firstname-  COLUMN 2 / lastname - COLUMN 3	/ tag_id - COLUMN 5 / iclub_id - COLUMN 7 / status_id - COLUMN 9
	address_1 - COLUMN 26 / address_2 - COLUMN 27 / address_3 - COLUMN 28 / address_4 - COLUMN 29 / citty - COLUMN 30 / state - COLUMN 31 / zip - COLUMN 32
	// FIND COUNTRY ID USING SQL
	region_id - COLUMN 36 / 
	
	$stmt = pdo_query( $pdo, 
					   "INSERT INTO import_orders (
date, order_id, product, quantity, model, artwork, color, rush_process, left_shell, right_shell, left_faceplate, right_faceplate, cable_color, clear_canal, left_alclair_logo, right_alclair_logo, left_custom_art, right_custom_art, link_to_design_image, open_order_in_designer, designed_for, my_impressions, billing_name, shipping_name, price, coupon, discount, total, entered_by, active, order_status_id, num_earphones_per_order)
VALUES (
:date, :order_id, :product, :quantity, :model, :artwork, :color, :rush_process, :left_shell, :right_shell, :left_faceplate, :right_faceplate, :cable_color, :clear_canal, :left_alclair_logo, :right_alclair_logo, :left_custom_art, :right_custom_art, :link_to_design_image, :open_order_in_designer, :designed_for, :my_impressions, :billing_name, :shipping_name, :price, :coupon, :discount, :total, :entered_by, :active, :order_status_id, :num_earphones_per_order) RETURNING id",
array(':date'=>$order[0], ':order_id'=>$order[1],':product'=>$order[2], ':quantity'=>$order[3], ':model'=>$order[4], ':artwork'=>$order[5], ':color'=>$order[6], ':rush_process'=>$order[7], ':left_shell'=>$order[8], ':right_shell'=>$order[9], ':left_faceplate'=>$order[10], ':right_faceplate'=>$order[11], ':cable_color'=>$order[12], ':clear_canal'=>$order[13], ':left_alclair_logo'=>$order[14], ':right_alclair_logo'=>$order[15], ':left_custom_art'=>$order[16], ':right_custom_art'=>$order[17], ':link_to_design_image'=>$order[18], ':open_order_in_designer'=>$order[19], 
':designed_for' =>$order[20], 
':my_impressions'=>$order[21], 
':billing_name'=>$order[22], 
':shipping_name'=>$order[23], 
':price'=>$order[24], 
':coupon'=>$order[25], 
':discount'=>$order[26], 
':total'=>$order[27],
':entered_by'=>$_SESSION['UserId'],
':active'=>TRUE,
':order_status_id'=>99, 
':num_earphones_per_order'=>$order[28])
);   

$id_after_import = pdo_fetch_all( $stmt );
$id_of_order = $id_after_import[0]["id"];

	$stmt = pdo_query($pdo, "SELECT * FROM monitors WHERE name = :monitor_name", array('monitor_name'=>$order[4]));
	$result = pdo_fetch_all( $stmt );

	$qc_form = array();
	$qc_form['customer_name'] = $order[20];
	$qc_form['order_id'] = $order[1];
	$qc_form['monitor_id'] = $result[0]["id"];
	$qc_form['build_type_id'] = 1; // New Build
			
	$qc_form['notes'] = "Entry from import " . $i;
	$qc_form['notes'] = "";

$stmt = pdo_query( $pdo, 
					   "INSERT INTO qc_form (customer_name, order_id, monitor_id, build_type_id, notes, active, qc_date, pass_or_fail, id_of_order)
					   	 VALUES (:customer_name, :order_id, :monitor_id, :build_type_id, :notes, :active, now(), :pass_or_fail, :id_of_order) RETURNING id",
array(':customer_name'=>$qc_form['customer_name'], ':order_id'=>$qc_form['order_id'],':monitor_id'=>$qc_form['monitor_id'], ':build_type_id'=>$qc_form['build_type_id'], ':notes'=>$qc_form['notes'], ":active"=>TRUE, ":pass_or_fail"=>"IMPORTED", ":id_of_order"=>$id_of_order)
);		

$id_of_qc_form = pdo_fetch_all( $stmt );

$stmt = pdo_query( $pdo, "UPDATE import_orders SET id_of_qc_form = :id_of_qc_form WHERE id = :id_of_order", array(":id_of_qc_form"=>$id_of_qc_form[0]["id"], ":id_of_order"=>$id_of_order));

}  // END FOR LOOP
$response["code"] = "success";
$response["testing1"] = $inc;

echo json_encode($response);
}
catch(PDOException $ex)
{
	//$response["code"] = "error 2";
	$response["message"] = $ex->getMessage();
	//echo json_encode($response);
}

?>