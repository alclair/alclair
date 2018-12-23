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
	$import = array();
	$import["new"] = $_POST["new"];
	
	
	$if_statement_string = "";
	$practice_sn = array("456789", "678921");
	$num_sn = count($practice_sn);
	
	$stmt = pdo_query( $pdo, 'SELECT * FROM products',null);	
    $result = pdo_fetch_all($stmt);
    $rowcount = pdo_rows_affected( $stmt );
     
    $prefix_length=array(); 
    $count = 0;
    for ($i = 0; $i < $num_sn; $i++) {
    		for ($x = 0; $x < $rowcount; $x++) {
    			$prefix_length[$x]=strlen($result[$x]['sn_prefix']);  // OBTAINS THE LENGTH OF EVERY PRODUCT'S PREFIX TO USE FOR IF STATEMENT

			//if (substr($practice_sn, 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) {
			if (substr($practice_sn[$i], 0, $prefix_length[$x]) == $result[$x]['sn_prefix']) {
				//$test = $result[$x]['name'];
				$test2 = substr($practice_sn[$i]);
				$test[$count] = $result[$x]['name'];
				$count++;
			}
		} 
	}     
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		$response["testing8"] = "8888888";
		$response["test"] = "test test";
		echo json_encode($response);
		exit;
	} else {
		//echo $rowcount;
		for ($x = 0; $x < $rowcount; $x++) {
			echo $prefix_length[$x] . "\n";
		}
		echo $count;
		for ($x = 0; $x < count($test); $x++) {
			echo $test[$x];
		}
	}

	
	$response['code']='success';
	$response['data'] = $result;
	$response['test'] = $import["new"];
	
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