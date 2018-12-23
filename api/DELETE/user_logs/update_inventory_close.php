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
    $query = "update inventory set
              drip_oil = :drip_oil, 
              grease = :grease,
              cleaning_supplies  = :cleaning_supplies, 
              rags = :rags, 
              filter_socks = :filter_socks, 
              water  = :water,
              absorbent_pads = :absorbent_pads, 
              leaks_yn  = :leaks_yn, 
              pump_conditions_yn = :pump_conditions_yn, 
              meters_yn = :meters_yn,
              fire_extinguishers_yn = :fire_extinguishers_yn,
              eye_wash_station_yn = :eye_wash_station_yn,
			  signs_still_up_yn = :signs_still_up_yn,
              spills_yn = :spills_yn,
              notes = :notes,
              date_closed = now(),
              active = 'FALSE',
              created_by_id = :created_by_id WHERE id = :id";
			  //field_name = :field_name, qq = :quarter, file_number = :file_number, api_number = :api_number where created_by_id = :created_by_id and id = :id";
    $params=array(
                    ":drip_oil" => empty($_POST["drip_oil"]) ? null : $_POST["drip_oil"],
                    ":grease" => empty($_POST["grease"]) ? null : $_POST["grease"],
                    ":cleaning_supplies" => empty($_POST["cleaning_supplies"]) ? null : $_POST["cleaning_supplies"],
                    ":rags" => empty($_POST["rags"]) ? null : $_POST["rags"],
                    ":filter_socks" => empty($_POST["filter_socks"]) ? null : $_POST["filter_socks"],
                    ":water" => empty($_POST["water"]) ? null : $_POST["water"],
                    ":absorbent_pads" => empty($_POST["absorbent_pads"]) ? null : $_POST["absorbent_pads"], 
                    ":leaks_yn" => empty($_POST["leaks_yn"]) ? null : $_POST["leaks_yn"],
                    ":pump_conditions_yn" => empty($_POST["pump_conditions_yn"]) ? null : $_POST["pump_conditions_yn"],
                    ":meters_yn" => empty($_POST["meters_yn"]) ? null : $_POST["meters_yn"], 
                    ":fire_extinguishers_yn" => empty($_POST["fire_extinguishers_yn"]) ? null : $_POST["fire_extinguishers_yn"],
                    ":eye_wash_station_yn" => empty($_POST["eye_wash_station_yn"]) ? null : $_POST["eye_wash_station_yn"],
                    ":signs_still_up_yn" => empty($_POST["signs_still_up_yn"]) ? null : $_POST["signs_still_up_yn"],
                    ":spills_yn" => empty($_POST["spills_yn"]) ? null : $_POST["spills_yn"],
                    ":notes" => empty($_POST["notes"]) ? null : $_POST["notes"],
                    ":created_by_id" => $_SESSION["UserId"],
                    ":id" => $_POST["id"]
                 );

	$stmt=pdo_query($pdo,$query,$params
                    //,1
                    );	

	$response['code']='success';
    $response["message"] = "Update success";	
	                    
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>