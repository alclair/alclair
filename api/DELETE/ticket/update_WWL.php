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
    //$_POST=json_decode('{"id":20,"ticket_number":"123456","barrels_delivered":0,"date_delivered":"2015-10-30","date_created":"10/30/2015","delivery_method":"0","water_source_type":"0","notes":"18.27","created_by_id":1,"disposal_well_id":0,"source_well_id":0,"trucking_company_id":"36","water_type_id":0,"water_type_name":null,"disposal_well_name":null,"company_name":null,"Id":20}',true);
	$ticket = array();
	$ticket['Id'] = $_POST['Id'];
	$ticket['ticketnumber'] = $_POST['ticket_number'];
	$ticket['datedelivered'] = $_POST['date_delivered'];
	$ticket['source_well_id'] = $_POST['source_well_id'];
	$ticket['producername'] = $_POST['producer_name'];
	$ticket['trucking_company_id'] = $_POST['trucking_company_id'];
	$ticket['barrelsdelivered'] = $_POST['barrels_delivered'];
	$ticket['fluidtypeid'] = $_POST['fluid_type_id'];
	$ticket['companymanname'] = $_POST['company_man_name'];
	$ticket['companymannumber'] = $_POST['company_man_number'];
	$ticket['drivername'] = $_POST['driver_name'];
	$ticket['percentsolid'] = $_POST['percent_solid'];
	$ticket['percenth2o'] = $_POST['percent_h2o'];
	$ticket['percentinterphase'] = $_POST['percent_interphase'];
	$ticket['percentoil'] = $_POST['percent_oil'];
	$ticket['afepo'] = $_POST['afepo'];
	$ticket['rig'] = $_POST['rig'];
	$ticket['microrens'] = $_POST['microrens'];

	$ticket['notes'] = $_POST['notes'];
	$ticket['washoutValue'] = $_POST['washoutValue'];
	$ticket['washoutbarrels'] = $_POST['washout_barrels'];
	$ticket['barrelrate'] = $_POST['barrel_rate'];
	$ticket['trucktype'] = $_POST['truck_type'];
	$ticket['h2s_exists'] = $_POST['h2s_exists'];
	
	if(empty($ticket['fluidtypeid']) == true)
	{
		$response['message'] = 'Please pick a Fluid Type.';
		echo json_encode($response);
		exit;
	}
	
	if($ticket['percentsolid'] + $ticket['percenth2o'] + $ticket['percentinterphase'] + $ticket['percentoil']  != 100)
	{
		$response['message'] = 'Percentages do not equal 100.';
		echo json_encode($response);
		exit;
	}
	
	if($ticket['microrens'] > 0 ) {
		$ticket['tenorm_picocuries'] = ($ticket['microrens'] * 0.599); 
		}
	else { 
		$ticket['tenorm_picocuries'] = $_POST['tenorm_picocuries']; 
		}
		
	if($ticket['tenorm_picocuries'] >= 5) {
			$ticket['picocuriesValue'] = "true"; 
			}
	else {
			$ticket['picocuriesValue'] = "false"; 
			}
		
	$stmt = pdo_query( $pdo, 
					   'update ticket_tracker_ticket set 
					   notes = :notes,
					   ticket_number = :ticket_number,
                       barrels_delivered = :barrels_delivered, 
                       date_delivered = :date_delivered,
                       local = :local, 
                       producer_name = :producer_name, 
                       trucking_company_id = :trucking_company_id, 
                       fluid_type_id = :fluid_type_id, 
                       company_man_name= :company_man_name, 
                       company_man_number= :company_man_number, 
                       driver_name= :driver_name, 
                       afepo= :afepo, 
                       rig= :rig,
                       percent_solid= :percent_solid,
                       percent_h2o= :percent_h2o,
                       percent_interphase= :percent_interphase,
                       percent_oil= :percent_oil,
                       washout = :washoutValue,
                       washout_barrels= :washout_barrels,
                       picocuries= :picocuries,
                       source_well_id = :source_well_id,
                       microrens = :microrens,
                       barrel_rate = :barrel_rate,
                       tenorm_picocuries = :tenorm_picocuries,
                       truck_type = :truck_type,
                       h2s_exists = :h2s_exists
                       where Id = :Id and created_by_id = :created_by_id',
					   array(
					   ":notes"=>$ticket["notes"],
					   ":ticket_number"=>$ticket["ticketnumber"],
                       ":barrels_delivered"=>$ticket["barrelsdelivered"],
                       ":date_delivered"=>$ticket["datedelivered"],
                       ":local"=>$ticket["local"],
                       ":producer_name"=>$ticket["producername"], 
                       ":trucking_company_id"=>$ticket["trucking_company_id"],
                       ":fluid_type_id"=>$ticket["fluidtypeid"],
                       ":company_man_name"=>$ticket["companymanname"], 
                       ":company_man_number"=>$ticket["companymannumber"],
                       ":driver_name"=>$ticket["drivername"],
                       ":afepo"=>$ticket["afepo"],
                       ":rig"=>$ticket["rig"], 
                       ":percent_solid"=>$ticket["percentsolid"], 
                       ":percent_h2o"=>$ticket["percenth2o"],
                       ":percent_interphase"=>$ticket["percentinterphase"],
                       ":percent_oil"=>$ticket["percentoil"],
                       ":washoutValue" =>$ticket['washoutValue'],
                       ":washout_barrels"=>$ticket["washoutbarrels"],
                       "source_well_id"=>$ticket["source_well_id"],
                       ":microrens"=>$ticket["microrens"],
                       ":picocuries"=>$ticket["picocuriesValue"],
                       ":barrel_rate"=>$ticket["barrelrate"],
                       ":tenorm_picocuries"=>$ticket['tenorm_picocuries'],
                       ":truck_type"=>$ticket['trucktype'],
					   ":h2s_exists"=>$ticket['h2s_exists'],
                       ":Id"=>$ticket["Id"],
                       ":created_by_id"=>$_SESSION['UserId'])
						//,1
					 );
					 				 
	$rowcount = pdo_rows_affected( $stmt );
	if( $rowcount == 0 )
	{
		$response['message'] = pdo_errors();
		echo json_encode($response);
		exit;
	}
	
	$response['code'] = 'success';
	$response["message"] = $stmt;
	$response['data'] = $rowcount;
	echo json_encode($response);
	
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>