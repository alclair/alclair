<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	   
    $query = "update ticket_tracker_well set
              current_operator_id = :current_operator_id, current_well_name = :current_well_name,
              township = :township, rng = :range, 
              section = :section, county = :county,
              field_name = :field_name, qq = :quarter, file_number = :file_number, api_number = :api_number where id = :id";
			  //field_name = :field_name, qq = :quarter, file_number = :file_number, api_number = :api_number where created_by_id = :created_by_id and id = :id";
    $params=array(
                    ":current_operator_id" => empty($_POST["operator_id"]) ? null : $_POST["operator_id"],
                    ":current_well_name" => $_POST["name"],
                    ":township" => empty($_POST["township"]) ? null : $_POST["township"],
                    ":range" => empty($_POST["range"]) ? null : $_POST["range"],
                    ":section" => empty($_POST["section"]) ? null : $_POST["section"],
                    ":county" => empty($_POST["county_name"]) ? null : $_POST["county_name"],
                    ":field_name" => empty($_POST["field_name"]) ? null : $_POST["field_name"], 
                    ":quarter" => empty($_POST["qq"]) ? null : $_POST["qq"],
                    ":file_number" => empty($_POST["file_number"]) ? null : $_POST["file_number"],
                    ":api_number" => empty($_POST["api_number"]) ? null : $_POST["api_number"], 
                    ":created_by_id" => $_SESSION["UserId"],
                    ":id" => $_POST["id"],
                 );

	$stmt=pdo_query($pdo,$query,$params
                    //,1
                    );	

	$response['code']='success';
    $response["message"] = "Update success";	
	
	
	
	$query2 = "update wells_list set
              current_operator_id = :current_operator_id, current_well_name = :current_well_name,
              township = :township, rng = :range, 
              section = :section, county = :county,
              field_name = :field_name, qq = :quarter, file_number = :file_number, api_number = :api_number where id = :id";
			  //field_name = :field_name, qq = :quarter, file_number = :file_number, api_number = :api_number where created_by_id = :created_by_id and id = :id";

	$stmt=pdo_query($pdo,$query2,$params
                    //,1
                    );	
                    
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>