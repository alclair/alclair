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
    $pagingSql = "";
    $orderBySqlDirection = "DESC";
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();
    
    

    if( !empty($_REQUEST['id']) )
    {
		$conditionSql .= " AND id = :id";
		if($_REQUEST["id"][0] == 'R') {
			$params[":id"] = substr($_REQUEST['id'], 1, strlen($_REQUEST['id']) ); // REMOVES THE "R" FROM THE ID WHEN A REPAIR
		} else {
			$params[":id"] = $_REQUEST['id'];
		}
    }
    
if($_REQUEST["id"][0] == 'R') {
	$repair_id = substr($_REQUEST['id'], 1, strlen($_REQUEST['id']) );
	// FETCH THE ID OF THE QC FORM WHICH EXISTS IN SOME IMPORT ORDERS
    // IT WAS A COLUMN ADDED LATER IN DEVELOPMENT
    $stmt = pdo_query($pdo, "SELECT * FROM repair_form WHERE id = :id", array(":id"=>$repair_id));
    $result2 = pdo_fetch_all($stmt);
        
	if($result2[0]["id_of_qc_form"] === null || $result2[0]["id_of_qc_form"] < 1) { // SOME LINES FROM import_orders WON'T HAVE AN id_of_qc_form BECAUSE THE ORIGINAL PROGRAMMING DIDN'T INCLUDE THAT COLUMN
		$stmt = pdo_query($pdo, "SELECT * FROM qc_form WHERE customer_name = :customer_name AND order_id = :order_id", array(":customer_name"=>$_REQUEST["customer_name"], ":order_id"=>$result2["rma_number"]));
		$result3 = pdo_fetch_all($stmt);

		if(count($result3) > 1) {
			// CAN'T UPDATE THE QC FORM BECAUSE MORE THAN 1 QC FORM WITH THAT ORDER #
			$response['code'] = "error";
			$response["message"] = "The link for the QC Form does not work for this repair.";
			echo json_encode($response);
			exit;
		} 
		else {
			$result = pdo_fetch_all( $stmt );
			$response['ID_is'] = $result[0]["id"];
			$response['code'] = 'success';
		}
	} else {
		//$stmt = pdo_query($pdo, "SELECT * FROM qc_form WHERE id = :id_of_qc_form", array(":id_of_qc_form"=>$_REQUEST["id_of_qc_form"]));
		//$result = pdo_fetch_all( $stmt );
		$response['ID_is'] = $result2[0]["id_of_qc_form"];
		$response['code'] = 'success';
	}

	
	
} else {
    // FETCH THE ID OF THE QC FORM WHICH EXISTS IN SOME IMPORT ORDERS
    // IT WAS A COLUMN ADDED LATER IN DEVELOPMENT
    $stmt = pdo_query($pdo, "SELECT * FROM import_orders WHERE id = :id", array(":id"=>$_REQUEST["id"]));
    $result2 = pdo_fetch_all($stmt);
    
        
	if($result2[0]["id_of_qc_form"] === null || $result2[0]["id_of_qc_form"] < 1) { // SOME LINES FROM import_orders WON'T HAVE AN id_of_qc_form BECAUSE THE ORIGINAL PROGRAMMING DIDN'T INCLUDE THAT COLUMN
		$stmt = pdo_query($pdo, "SELECT * FROM qc_form WHERE customer_name = :customer_name AND order_id = :order_id", array(":customer_name"=>$_REQUEST["customer_name"], ":order_id"=>$result2["order_id"]));
		$result3 = pdo_fetch_all($stmt);

		if(count($result3) > 1) {
			// CAN'T UPDATE THE QC FORM BECAUSE MORE THAN 1 QC FORM WITH THAT ORDER #
			$response['code'] = "error";
			$response["message"] = "The link for the QC Form does not work for this order.";
			echo json_encode($response);
			exit;
		} 
		else {
			$result = pdo_fetch_all( $stmt );
			$response['ID_is'] = $result[0]["id"];
			$response['code'] = 'success';
		}
	} else {
		//$stmt = pdo_query($pdo, "SELECT * FROM qc_form WHERE id = :id_of_qc_form", array(":id_of_qc_form"=>$_REQUEST["id_of_qc_form"]));
		//$result = pdo_fetch_all( $stmt );
		$response['ID_is'] = $result2[0]["id_of_qc_form"];
		$response['code'] = 'success';
	}
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