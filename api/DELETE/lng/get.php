<?php
include_once "../../config.inc.php";

try
{
	$query = "SELECT * FROM queens_status";
	$stmt = pdo_query( $pdo, $query, null );
	$row = pdo_fetch_array($stmt);
	$response = array();
	$response["code"] = "success";
	$response["message"] = "";
	$response['q1'] = "";
	$response['q2'] = "";
	$response['q3'] = "";
	$response['q4'] = "";
	$response['q5'] = "";
	$response['q6'] = "";
	$response['q7'] = "";
	$response['q8'] = "";
	$response['q9'] = "";
	$response['q10'] = "";
	$response['pq31'] = "";
	$response['pq33'] = ""; //$response['q18'] = "";
	$response['pq34'] = ""; // $response['q13'] = "";
	$response['pq37'] = "";
	$response['pq39'] = "";
	$response['pq66'] = "";
	
	if(!empty($row[0]))
	{
		$response["id"] = $row["id"];
		$response["q1"] = $row["q1"];
		$response["q2"] = $row["q2"];
		$response["q3"] = $row["q3"];
		$response["q4"] = $row["q4"];
		$response["q5"] = $row["q5"];
		$response["q6"] = $row["q6"];
		$response["q7"] = $row["q7"];
		$response["q8"] = $row["q8"];
		$response["q9"] = $row["q9"];
		$response["q10"] = $row["q10"];
		$response["pq31"] = $row["pq31"];
		$response["pq33"] = $row["pq33"];
		$response["pq34"] = $row["pq34"];
		$response["pq37"] = $row["pq37"];
		$response["pq39"] = $row["pq39"];
		$response["pq66"] = $row["pq66"];		
		
		$response["q1_user_id"] = $row["q1_user_id"];
		$response["q1_notes"] = $row["q1_notes"];
		$response["q1_timestamp"] = $row["q1_timestamp"];
		
		$response["q2_user_id"] = $row["q2_user_id"];
		$response["q2_notes"] = $row["q2_notes"];
		$response["q2_timestamp"] = $row["q2_timestamp"];
		
		$response["q3_user_id"] = $row["q3_user_id"];
		$response["q3_notes"] = $row["q3_notes"];
		$response["q3_timestamp"] = $row["q3_timestamp"];
		
		$response["q4_user_id"] = $row["q4_user_id"];
		$response["q4_notes"] = $row["q4_notes"];
		$response["q4_timestamp"] = $row["q4_timestamp"];
		
		$response["q5_user_id"] = $row["q5_user_id"];
		$response["q5_notes"] = $row["q5_notes"];
		$response["q5_timestamp"] = $row["q5_timestamp"];
		
		$response["q6_user_id"] = $row["q6_user_id"];
		$response["q6_notes"] = $row["q6_notes"];
		$response["q6_timestamp"] = $row["q6_timestamp"];
		
		$response["q7_user_id"] = $row["q7_user_id"];
		$response["q7_notes"] = $row["q7_notes"];
		$response["q7_timestamp"] = $row["q7_timestamp"];
		
		$response["q8_user_id"] = $row["q8_user_id"];
		$response["q8_notes"] = $row["q8_notes"];
		$response["q8_timestamp"] = $row["q8_timestamp"];
		
		$response["q9_user_id"] = $row["q9_user_id"];
		$response["q9_notes"] = $row["q9_notes"];
		$response["q9_timestamp"] = $row["q9_timestamp"];
		
		$response["q10_user_id"] = $row["q10_user_id"];
		$response["q10_notes"] = $row["q10_notes"];
		$response["q10_timestamp"] = $row["q10_timestamp"];
		
		$response["q31_user_id"] = $row["q31_user_id"];
		$response["q31_notes"] = $row["q31_notes"];
		$response["q31_timestamp"] = $row["q31_timestamp"];
		
		$response["q33_user_id"] = $row["q33_user_id"];
		$response["q33_notes"] = $row["q33_notes"];
		$response["q33_timestamp"] = $row["q33_timestamp"];

		$response["q34_user_id"] = $row["q34_user_id"];
		$response["q34_notes"] = $row["q34_notes"];
		$response["q34_timestamp"] = $row["q34_timestamp"];
		
		$response["q37_user_id"] = $row["q37_user_id"];
		$response["q37_notes"] = $row["q37_notes"];
		$response["q37_timestamp"] = $row["q37_timestamp"];
		
		$response["q39_user_id"] = $row["q39_user_id"];
		$response["q39_notes"] = $row["q39_notes"];
		$response["q39_timestamp"] = $row["q39_timestamp"];
		
		$response["q66_user_id"] = $row["q66_user_id"];
		$response["q66_notes"] = $row["q66_notes"];
		$response["q66_timestamp"] = $row["q66_timestamp"];
		
	}
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>