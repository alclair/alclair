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

$material['qtyUsed'] = $_REQUEST['qtyUsed'];
$material['id'] = $_REQUEST['id'];

try
{     
    $conditionSql = "";
    $pagingSql = "";
    $orderBySqlDirection = "desc";
    $orderBySql = " order by id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql = " and id = :id";
        $params[":id"] = $_REQUEST['id'];
    }
	
	$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    if( isset($_REQUEST['id']) )
    {        
        $query_1 = "SELECT id, name, current_qty, current_price, to_char(time_stamp,'MM/dd/yyyy') as time_stamp
				  		  FROM materials
                          WHERE id = :id";
						  //ORDER BY time_stamp 
                          //DESC LIMIT 1";
                          $test4 = "in here";
		$query_2 = "SELECT * FROM materials_tracker WHERE material_id = :id";
    }

    $stmt_1 = pdo_query( $pdo, $query_1, $params); 
    $result = pdo_fetch_array( $stmt_1 );
    
    $stmt_2 = pdo_query( $pdo, $query_2, $params); 
    $result_2 = pdo_fetch_all( $stmt_2 );
    $response['result']=$result_2;
    
    $ending_qty = $result["current_qty"] - $material["qtyUsed"];
	if($ending_qty < 0)
	{
		$response['message'] = 'You do not have that much material to use.';
		echo json_encode($response);
		exit;
	}
    
    $stmt = pdo_query($pdo, "INSERT INTO materials_tracker (material_id, added, subtracted, ending_qty, reason, unit_cost, invoice_number, invoice_date, notes, active, entered_by, time_stamp) 
												VALUES (:material_id, :added, :subtracted, :ending_qty, :reason, :unit_cost, :invoice_number, :invoice_date, :notes, :active, :entered_by, now()) RETURNING id",
												array(
													':material_id'=>$_REQUEST['id'], 
													':added'=>null, 
													':subtracted'=>$material['qtyUsed'], 
													':ending_qty'=>$ending_qty, 
													':reason'=>"Quantity Used",
													':unit_cost'=>null,
													':invoice_number'=>null,
													':invoice_date'=>null,
													':notes'=>null,
													':active'=>TRUE,
													':entered_by'=>$_SESSION['UserId'])
									);	
    $result = pdo_fetch_all( $stmt ); 
    
	/*$stmt = pdo_query($pdo, "UPDATE materials SET current_qty = :ending_qty WHERE id = :id", 
													array(
														':ending_qty'=>$ending_qty,
														':id'=>$material["id"])
										);*/
										
	$query =  "SELECT count(*) AS number_of_rows FROM materials_tracker WHERE reason = 'Purchase' AND material_id = :id AND active = TRUE";
	$stmt = pdo_query($pdo, $query, array(':id'=>$material["id"]) );
	$result3 = pdo_fetch_array( $stmt );  // GET NUMBER OF ROWS - THAT IS IT

	$query =  "SELECT * FROM materials_tracker WHERE reason = 'Purchase' AND material_id = :id AND active = TRUE ORDER BY id";
	$stmt = pdo_query($pdo, $query, array(':id'=>$material["id"]) );
	$result2 = pdo_fetch_all( $stmt ); 
	$count = 0;
	$remainder = $material["qtyUsed"];
	for ($x = 0; $x <= $result3["number_of_rows"]; $x++) {
		if($remainder >= $result2[$x]['what_is_left'] ) {
			$remainder = $result2[$x]['what_is_left'] - abs($remainder);
			$params[':result2_id'] = $result2[$x]['id'];
			$query = "UPDATE materials_tracker SET active = FALSE, what_is_left = 0 WHERE id = :result2_id";
			$stmt = pdo_query($pdo, $query, $params);		
			
			$params[':price'] = $result2[$x]['unit_cost'];
			$params[':remainder'] = abs($remainder);
			$query = "UPDATE materials SET current_qty = :remainder, current_price = :price WHERE id = :result2_id";
			$stmt = pdo_query($pdo, $query, $params);	
			$response['testing3'] = $result2[$x]['what_is_left'];
			$response['testing4'] = $count;
			$count = $count+1;
		}
		else {
			//if($remainder < 0)
			//	$remainder = $result2[$x]['what_is_left'] + $remainder; // ADD BECAUSE REMAINDER BECOMES NEGATIVE BECAUSE OF IF STATEMENT
			//else
			$remainder = $result2[$x]['what_is_left'] - abs($remainder); 
			$params[':result2_id'] = $result2[$x]['id'];
			$params[':remainder'] = abs($remainder);
			$query = "UPDATE materials_tracker SET what_is_left = :remainder WHERE id = :result2_id";
			$stmt = pdo_query($pdo, $query, $params);		
			
			$params[':price'] = $result2[$x]['unit_cost'];
			$params[':result2_id'] = $result2[$x]['material_id'];
			$query = "UPDATE materials SET current_qty = :remainder, current_price = :price WHERE id = :result2_id";
			$stmt = pdo_query($pdo, $query, $params);		
			$response['testing'] = "HERE";
			$response['testing2'] = $result2[$x]['what_is_left'];
			$response['testing3'] = $remainder;

			break;
		}
	} 
		
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    //$response['testing'] = $result2;
    //$response['testing2'] = $result2[0]['what_is_left'];
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>