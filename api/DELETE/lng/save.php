<?php
include_once "../../config.inc.php";

try
{	
	$response = array();
	$response["code"] = "success";
	$response["message"] = "";
	$query = "select * from queens_status";
	$stmt = pdo_query( $pdo, $query, null );
	$row = pdo_fetch_array($stmt);
	
	if(empty($row[0])) // THE CODE WITHIN THIS IF STATEMENT SHOULD NEVER BE USED.  IT WILL ERROR.  IF THE TABLE IN THE DATABASE IS PROPERLY 
									  // SETUP PRIOR TO INCLUDING A NEW QUEEN THEN OPERATION WILL BE FINE
	{
		$query = "INSERT INTO queens_status (q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, q13, q18) values (:q1, :q2, :q3, :q4, :q5, :q6, :q7, :q8, :q9, :q10, :q33, :q34, :q66)";
		$params = array(
                ":q1" => $_REQUEST["value1"],
				":q2" => $_REQUEST["value2"],
				":q3" => $_REQUEST["value3"],
				":q4" => $_REQUEST["value4"],
				":q5" => $_REQUEST["value5"],
				":q6" => $_REQUEST["value6"],
				":q7" => $_REQUEST["value7"],
				":q8" => $_REQUEST["value8"],
				":q9" => $_REQUEST["value9"],
				":q10" => $_REQUEST["value10"],
				":q13" => $_REQUEST["value13"],
				":q18" => $_REQUEST["value18"],
            );
		pdo_query($pdo,$query,$params);
	}
	else	if($_REQUEST["QueenIs"] == 1) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q1 = :q1, q1_user_id = :q1_user_id, q1_notes = :q1_notes, q1_timestamp = now() WHERE id = 1";
			$params = array(":q1" => $_REQUEST["Outage"], ":q1_user_id" => $_SESSION["UserId"], ":q1_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q1 = :q1 WHERE id = 1";
			$params = array(":q1" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 2) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q2 = :q2, q2_user_id = :q2_user_id, q2_notes = :q2_notes, q2_timestamp = now() WHERE id = 1";
			$params = array(":q2" => $_REQUEST["Outage"], ":q2_user_id" => $_SESSION["UserId"], ":q2_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q2 = :q2 WHERE id = 1";
			$params = array(":q2" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 3) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q3 = :q3, q3_user_id = :q3_user_id, q3_notes = :q3_notes, q3_timestamp = now() WHERE id = 1";
			$params = array(":q3" => $_REQUEST["Outage"], ":q3_user_id" => $_SESSION["UserId"], ":q3_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q3 = :q3 WHERE id = 1";
			$params = array(":q3" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 4) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q4 = :q4, q4_user_id = :q4_user_id, q4_notes = :q4_notes, q4_timestamp = now() WHERE id = 1";
			$params = array(":q4" => $_REQUEST["Outage"], ":q4_user_id" => $_SESSION["UserId"], ":q4_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q4 = :q4 WHERE id = 1";
			$params = array(":q4" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 5) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q5 = :q5, q5_user_id = :q5_user_id, q5_notes = :q5_notes, q5_timestamp = now() WHERE id = 1";
			$params = array(":q5" => $_REQUEST["Outage"], ":q5_user_id" => $_SESSION["UserId"], ":q5_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q5 = :q5 WHERE id = 1";
			$params = array(":q5" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 6) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q6 = :q6, q6_user_id = :q6_user_id, q6_notes = :q6_notes, q6_timestamp = now() WHERE id = 1";
			$params = array(":q6" => $_REQUEST["Outage"], ":q6_user_id" => $_SESSION["UserId"], ":q6_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q6 = :q6 WHERE id = 1";
			$params = array(":q6" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 7) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q7 = :q7, q7_user_id = :q7_user_id, q7_notes = :q7_notes, q7_timestamp = now() WHERE id = 1";
			$params = array(":q7" => $_REQUEST["Outage"], ":q7_user_id" => $_SESSION["UserId"], ":q7_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q7 = :q7 WHERE id = 1";
			$params = array(":q7" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 8) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q8 = :q8, q8_user_id = :q8_user_id, q8_notes = :q8_notes, q8_timestamp = now() WHERE id = 1";
			$params = array(":q8" => $_REQUEST["Outage"], ":q8_user_id" => $_SESSION["UserId"], ":q8_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q8 = :q8 WHERE id = 1";
			$params = array(":q8" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 9) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q9 = :q9, q9_user_id = :q9_user_id, q9_notes = :q9_notes, q9_timestamp = now() WHERE id = 1";
			$params = array(":q9" => $_REQUEST["Outage"], ":q9_user_id" => $_SESSION["UserId"], ":q9_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q9 = :q9 WHERE id = 1";
			$params = array(":q9" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 10) { // INOX Q1 
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET q10 = :q10, q10_user_id = :q10_user_id, q10_notes = :q10_notes, q10_timestamp = now() WHERE id = 1";
			$params = array(":q10" => $_REQUEST["Outage"], ":q10_user_id" => $_SESSION["UserId"], ":q10_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET q10 = :q10 WHERE id = 1";
			$params = array(":q10" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 17) { //  PQ3-4CHART  
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET pq31 = :pq31, pq31_user_id = :pq31_user_id, pq31_notes = :pq31_notes, pq34_timestamp = now() WHERE id = 1";
			$params = array(":pq31" => $_REQUEST["Outage"], ":pq31_user_id" => $_SESSION["UserId"], ":pq31_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET pq31 = :pq31 WHERE id = 1";
			$params = array(":pq31" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 18) { //  PQ3-4CHART  
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET pq33 = :pq33, pq33_user_id = :pq33_user_id, pq33_notes = :pq33_notes, pq34_timestamp = now() WHERE id = 1";
			$params = array(":pq33" => $_REQUEST["Outage"], ":pq33_user_id" => $_SESSION["UserId"], ":pq33_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET pq33 = :pq33 WHERE id = 1";
			$params = array(":pq33" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 13) { //  PQ3-4CHART  
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET pq34 = :pq34, pq34_user_id = :pq34_user_id, pq34_notes = :pq34_notes, pq34_timestamp = now() WHERE id = 1";
			$params = array(":pq34" => $_REQUEST["Outage"], ":pq34_user_id" => $_SESSION["UserId"], ":pq34_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET pq34 = :pq34 WHERE id = 1";
			$params = array(":pq34" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 15) { //  PQ3-4CHART  
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET pq39 = :pq39, pq39_user_id = :pq39_user_id, pq39_notes = :pq39_notes, pq34_timestamp = now() WHERE id = 1";
			$params = array(":pq39" => $_REQUEST["Outage"], ":pq39_user_id" => $_SESSION["UserId"], ":pq39_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET pq39 = :pq39 WHERE id = 1";
			$params = array(":pq39" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 16) { // CHART PQ6-6
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET pq66 = :pq66, pq66_user_id = :pq66_user_id, pq66_notes = :pq66_notes, pq66_timestamp = now() WHERE id = 1";
			$params = array(":pq66" => $_REQUEST["Outage"], ":pq66_user_id" => $_SESSION["UserId"], ":pq66_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET pq66 = :pq66 WHERE id = 1";
			$params = array(":pq66" => $_REQUEST["Outage"]);
		}
	}
	else	if($_REQUEST["QueenIs"] == 18) { // CHART PQ3-3
		if($_REQUEST["Outage"] == 1) {// THERE IS AN OUTAGE
			$query = "UPDATE queens_status SET pq33 = :pq33, pq33_user_id = :pq33_user_id, pq33_notes = :pq33_notes, pq33_timestamp = now() WHERE id = 1";
			$params = array(":pq33" => $_REQUEST["Outage"], ":pq33_user_id" => $_SESSION["UserId"], ":pq33_notes"=>$_REQUEST["notes"]);
		}
		else {
			$query = "UPDATE queens_status SET pq33 = :pq33 WHERE id = 1";
			$params = array(":pq33" => $_REQUEST["Outage"]);
		}
	}
	else {
		$query = "broken";
	}
	/*{
		$query = "UPDATE queens_status SET 
							q1 = :q1, 
							q2 = :q2, 
							q3 = :q3, 
							q4 = :q4, 
							q5 = :q5, 
							q6 = :q6, 
							q7 = :q7, 
							q8 = :q8, 
							q9 = :q9, 
							q10 = :q10, 
							q1_user_id = :q1_user_id, 
							q1_notes = :q1_notes,
							q1_timestamp = :q1_timestamp,
							q2_user_id = :q2_user_id, 
							q2_notes = :q2_notes,
							q2_timestamp = :q2_timestamp 
							WHERE id = 1";
        $params = array(
			":q1" => $_REQUEST["value1"],
			":q2" => $_REQUEST["value2"],
			":q3" => $_REQUEST["value3"],
			":q4" => $_REQUEST["value4"],
			":q5" => $_REQUEST["value5"],
			":q6" => $_REQUEST["value6"],
			":q7" => $_REQUEST["value7"],
			":q8" => $_REQUEST["value8"],
			":q9" => $_REQUEST["value9"],
			":q10" => $_REQUEST["value10"],
			":q1_user_id" => $_REQUEST["q1_user_id"],
			":q1_notes" => $_REQUEST["q1_notes"],
			":q1_timestamp" => $_REQUEST["q1_timestamp"],
			":q2_user_id" => $_REQUEST["q2_user_id"],
			":q1_notes" => $_REQUEST["q1_notes"],
			":q1_timestamp" => $_REQUEST["q1_timestamp"],
        );
		pdo_query($pdo,$query,$params);
	}*/
	
	pdo_query($pdo,$query,$params);
	$response["testing"] = $query;
	//$response["testing2"] = $_REQUEST["Outage"];
	//$response["testing3"] = $_REQUEST["QueenIs"];
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["testing"] = "No good";
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>