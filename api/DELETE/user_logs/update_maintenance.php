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
    $query = "update maintenance set
              injection_pump_hertz = :injection_pump_hertz, 
              computer_psi = :computer_psi,
              unload_pump_oil_yn  = :unload_pump_oil_yn, 
              tank_battery_lines_yn = :tank_battery_lines_yn, 
              injector_plunger_temp_1 = :injector_plunger_temp_1, 
              injector_plunger_temp_2 = :injector_plunger_temp_2, 
              lubricator_working_yn  = :lubricator_working_yn,
              oil_in_triplex_yn = :oil_in_triplex_yn, 
              rock_oil_level  = :rock_oil_level, 
              transfer_pump_house = :transfer_pump_house, 
              leaks_plungers_valves_pipes_fittings_yn = :leaks_plungers_valves_pipes_fittings_yn,
              pump_house_filters_yn = :pump_house_filters_yn,
              well_head_tubing_pressure = :well_head_tubing_pressure,
              well_head_casing_pressure = :well_head_casing_pressure,
              drinking_water = :drinking_water,
              fire_ext_sump_flow_meter = :fire_ext_sump_flow_meter,
              created_by_id = :created_by_id WHERE id = :id";
			  //field_name = :field_name, qq = :quarter, file_number = :file_number, api_number = :api_number where created_by_id = :created_by_id and id = :id";
    $params=array(
                    ":injection_pump_hertz" => empty($_POST["injection_pump_hertz"]) ? null : $_POST["injection_pump_hertz"],
                    ":computer_psi" => empty($_POST["computer_psi"]) ? null : $_POST["computer_psi"],
                    ":unload_pump_oil_yn" => empty($_POST["unload_pump_oil_yn"]) ? null : $_POST["unload_pump_oil_yn"],
                    ":tank_battery_lines_yn" => empty($_POST["tank_battery_lines_yn"]) ? null : $_POST["tank_battery_lines_yn"],
                    ":injector_plunger_temp_1" => empty($_POST["injector_plunger_temp_1"]) ? null : $_POST["injector_plunger_temp_1"],
                    ":injector_plunger_temp_2" => empty($_POST["injector_plunger_temp_2"]) ? null : $_POST["injector_plunger_temp_2"],
                    ":lubricator_working_yn" => empty($_POST["lubricator_working_yn"]) ? null : $_POST["lubricator_working_yn"],
                    ":oil_in_triplex_yn" => empty($_POST["oil_in_triplex_yn"]) ? null : $_POST["oil_in_triplex_yn"], 
                    ":rock_oil_level" => empty($_POST["rock_oil_level"]) ? null : $_POST["rock_oil_level"],
                    ":transfer_pump_house" => empty($_POST["transfer_pump_house"]) ? null : $_POST["transfer_pump_house"],
                    ":leaks_plungers_valves_pipes_fittings_yn" => empty($_POST["leaks_plungers_valves_pipes_fittings_yn"]) ? null : $_POST["leaks_plungers_valves_pipes_fittings_yn"], 
                    ":pump_house_filters_yn" => empty($_POST["pump_house_filters_yn"]) ? null : $_POST["pump_house_filters_yn"],
                    ":well_head_tubing_pressure" => empty($_POST["well_head_tubing_pressure"]) ? null : $_POST["well_head_tubing_pressure"],
                    ":well_head_casing_pressure" => empty($_POST["well_head_casing_pressure"]) ? null : $_POST["well_head_casing_pressure"],
                    ":drinking_water" => empty($_POST["drinking_water"]) ? null : $_POST["drinking_water"],
                    ":fire_ext_sump_flow_meter" => empty($_POST["fire_ext_sump_flow_meter"]) ? null : $_POST["fire_ext_sump_flow_meter"],
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