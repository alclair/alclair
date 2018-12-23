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
    $year = $_REQUEST['year'];
    $month = $_REQUEST['month'];
    $disposalwell = intval($_REQUEST['disposalwell']);
    $condition = $disposalwell == 0 ? "":"and t1.disposal_well_id = $disposalwell ";    

    //Get tickets by Year and Month
    $query = "select to_char(t1.date_delivered,'dd') as created, twater.type as water_type,
              t1.disposal_well_id, t1.water_type_id, t1.source_well_id,t1.barrel_rate,
              t1.trucking_company_id,
              twell.current_operator_id as operator_id, 
              t1.barrels_delivered 
              from ticket_tracker_ticket as t1
              left join ticket_tracker_watertype as twater on t1.water_type_id = twater.id 
              left join ticket_tracker_well as twell on t1.source_well_id = twell.id              
              where to_char(date_delivered,'yyyy') = '$year' and to_char(date_delivered,'MM')='$month'  $condition              
              order by created asc, water_type asc"; 

    $stmt = pdo_query( $pdo, $query, null ); 
    $result = pdo_fetch_all( $stmt );


    //Get all of "Rate"
    $rates = pdo_fetch_all( pdo_query($pdo, "select * from ticket_tracker_rate", null) );


    //Get all of "Default Rate"
    $defaultrates = pdo_fetch_all( pdo_query($pdo, "select * from ticket_tracker_defaultrate", null) ); 


    //Calculate ticket's cost
    $ticket_index = 0;
    foreach($result as $ticket)
    {
        $ticket_rate = "";
		if(!empty($ticket["barrel_rate"]))
		{
			$ticket_rate=$ticket["barrel_rate"];
		}
		else
		{
			foreach($rates as $rate)
			{
				if($rate["disposal_well_id"] == $ticket["disposal_well_id"] 
					&& $rate["water_type_id"] == $ticket["water_type_id"] 
					&& $rate["source_well_id"] == $ticket["source_well_id"])
				{
					if($rate["billto_option"] == "trucking company" && $rate["trucking_company_id"] == $ticket["trucking_company_id"])
					{
						if( empty($rate["use_default"]) == false && $rate["use_default"] == 1 )
						{
							break;
						}
						else
						{
							$ticket_rate = $rate["barrel_rate"];
						}
					}
					else if( $rate["billto_option"] == "operator" && $rate["billto_operator_id"] == $ticket["operator_id"] )
					{
						if( empty($rate["use_default"]) == false && $rate["use_default"] == 1 )
						{
							break;
						}
						else
						{
							$ticket_rate = $rate["barrel_rate"];
						}
					}
				}
			}

			if(empty($ticket_rate) == true)
			{
				foreach($defaultrates as $defaultrate)
				{
					if($defaultrate["disposal_well_id"] == $ticket["disposal_well_id"] 
						&& $defaultrate["water_type_id"] == $ticket["water_type_id"])
					{
						$ticket_rate = $defaultrate["barrel_rate"];
					}
				}
			}

			if(empty($ticket_rate) == true)
				$ticket_rate = 0;
		}
        $result[$ticket_index]["barrel_rate"] = $ticket_rate;
        $ticket_index++;
    }

    //Format tickets
    $result_format = array();
    $last_created = "";
    $last_water_type = "";
    foreach($result as $ticket)
    {
        if($ticket["created"] == $last_created && $ticket["water_type"] == $last_water_type)
        {
            $result_format[count($result_format) - 1]["total_barrels_delivered"] += $ticket["barrels_delivered"] * floatval($ticket["barrel_rate"]);
        }
        else
        {
            $record = array("created" => $ticket["created"],
                            "water_type" => $ticket["water_type"],
                            "total_barrels_delivered" => $ticket["barrels_delivered"] * floatval($ticket["barrel_rate"]));
            array_push($result_format, $record);

            $last_created = $ticket["created"];
            $last_water_type = $ticket["water_type"];
        }
    }


    //echo json_encode($result_format);
    //exit;



    $response['code'] = 'success';
    $response['data'] = $result_format;
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>