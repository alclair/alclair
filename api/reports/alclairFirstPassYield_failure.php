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
    $params = null;
    $year = $_REQUEST['year'];
    $month = $_REQUEST['month'];
    $IEM = intval($_REQUEST['IEM']);
    
    /*
	if(!empty($_REQUEST["month"]))
	{
		if (date('I', time())) {	 $month = date("m/d/Y H:i:s",strtotime($month. '00:00:00') + 5 * 3600); }
		else { $month = date("m/d/Y H:i:s",strtotime($month . '00:00:00')+ 6 * 3600); }
	}
	
	if(!empty($_REQUEST["month"]))
	{
		if (date('I', time())) { $month = date("m/d/Y H:i:s",strtotime($month . '23:59:59') + 5 * 3600); }
		else { $month = date("m/d/Y H:i:s",strtotime($month . '23:59:59') + 6 * 3600); }
	}*/

    $condition = "";
    if($IEM != 0)
    {
        $condition = "and t1.monitor_id = $IEM ";
    }
/*
    $query = "SELECT to_char(t1.qc_date,'dd') AS created, pass_or_fail, ( SELECT COUNT(pass_or_fail) ) AS num_status
              FROM qc_form as t1
              WHERE to_char(qc_date,'yyyy') = '$year' AND to_char(qc_date,'MM') = '$month' $condition AND build_type_id = 1 AND pass_or_fail = 'FAIL'
              GROUP BY created, pass_or_fail
              ORDER BY created ASC"; 
     
     $stmt = pdo_query( $pdo, $query, $params ); 
	 $result = pdo_fetch_all( $stmt );
   */           
    $query = "SELECT t1.id as id, to_char(t1.qc_date,'dd') AS created, pass_or_fail
              FROM qc_form as t1
              WHERE to_char(qc_date,'yyyy') = '$year' AND to_char(qc_date,'MM') = '$month' $condition AND build_type_id = 1 AND pass_or_fail = 'FAIL'
              GROUP BY created, pass_or_fail, id
              ORDER BY created ASC";
    $stmt = pdo_query( $pdo, $query, $params ); 
    $result2 = pdo_fetch_all( $stmt );
    
    $query = "SELECT to_char(t1.qc_date,'dd') AS created, ( SELECT COUNT(to_char(t1.qc_date, 'dd') ) ) AS num_status
              FROM qc_form as t1
              WHERE to_char(qc_date,'yyyy') = '$year' AND to_char(qc_date,'MM') = '$month' $condition AND build_type_id = 1 AND pass_or_fail = 'FAIL'
              GROUP BY created
              ORDER BY created ASC";
	$stmt = pdo_query( $pdo, $query, $params ); 
    $num_of_fails_in_day = pdo_fetch_all( $stmt );
    
    $step = 0;
    $count_sound = 0;
    $count_shells = 0;
    $count_faceplate = 0;
    $count_jacks = 0;
    $count_ports = 0;
    $count_artwork = 0;
    $result = array();
	for($j=0; $j<count($num_of_fails_in_day); $j++) {   
		$count_sound = 0;
		$count_shells = 0;
		$count_faceplate = 0;
		$count_jacks = 0;
		$count_ports = 0;
		$count_artwork = 0;
	    for($i=0; $i<count($result2); $i++) {
		    	if($num_of_fails_in_day[$j]["created"] == $result2[$i]["created"]) {
				$query = "SELECT id, sound_signature, sound_balanced, to_char(qc_date,'dd') AS created FROM qc_form WHERE id = :id";
				$stmt = pdo_query( $pdo, $query, array(":id"=>$result2[$i]["id"])); 
				$result_sound = pdo_fetch_array( $stmt );
				if(!$result_sound["sound_signature"]  || !$result_sound["sound_balanced"] ) {
					$count_sound = $count_sound + 1;
				}
				
				$query = "SELECT id, shells_defects, shells_colors, shells_faced_down, shells_label, shells_edges, shells_shine, shells_canal, to_char(qc_date,'dd') AS created FROM qc_form WHERE id = :id";
				$stmt = pdo_query( $pdo, $query, array(":id"=>$result2[$i]["id"])); 
				$result_shells = pdo_fetch_array( $stmt );
				if(!$result_shells["shells_defects"]  || !$result_shells["shells_colors"] || !$result_shells["shells_faced_down"] || !$result_shells["shells_label"] || !$result_shells["shells_edges"] || !$result_shells["shells_shine"] || !$result_shells["shells_canal"]) {
					$count_shells = $count_shells + 1;
				}
				
				$query = "SELECT id, faceplate_seams, faceplate_shine, faceplate_colors, faceplate_rounded, to_char(qc_date,'dd') AS created FROM qc_form WHERE id = :id";
				$stmt = pdo_query( $pdo, $query, array(":id"=>$result2[$i]["id"])); 
				$result_faceplate = pdo_fetch_array( $stmt );
				if(!$result_faceplate["faceplate_seams"]  || !$result_faceplate["faceplate_shine"] || !$result_faceplate["faceplate_colors"] || !													$result_faceplate["faceplate_rounded"]) {
					$count_faceplate = $count_faceplate + 1;
				}
			
				$query = "SELECT id, jacks_location, jacks_debris, jacks_cable, to_char(qc_date,'dd') AS created FROM qc_form WHERE id = :id";
				$stmt = pdo_query( $pdo, $query, array(":id"=>$result2[$i]["id"])); 
				$result_jacks = pdo_fetch_array( $stmt );
				if(!$result_jacks["jacks_location"]  || !$result_jacks["jacks_debris"] || !$result_jacks["jacks_cable"]) {
					$count_jacks = $count_jacks + 1;
				}
				
				$query = "SELECT id, ports_cleaned, ports_smooth, to_char(qc_date,'dd') AS created FROM qc_form WHERE id = :id";
				$stmt = pdo_query( $pdo, $query, array(":id"=>$result2[$i]["id"])); 
				$result_ports = pdo_fetch_array( $stmt );
				if(!$result_ports["ports_cleaned"]  || !$result_ports["ports_smooth"]) {
					$count_ports = $count_ports + 1;
				}
				
				$query = "SELECT *, to_char(qc_date,'dd') AS created FROM qc_form WHERE id = :id";
				$stmt = pdo_query( $pdo, $query, array(":id"=>$result2[$i]["id"])); 
				$result_artwork = pdo_fetch_array( $stmt );
				if($result_artwork["sound_signature"] && $result_artwork["sound_balanced"] && 
						$result_artwork["shells_defects"] && $result_artwork["shells_colors"]&& $result_artwork["shells_faced_down"] && 														$result_artwork["shells_label"]&& $result_artwork["shells_edges"] && $result_artwork["shells_shine"] && $result_artwork["shells_canal"] && 
						$result_artwork["faceplate_seams"] && $result_artwork["faceplate_shine"] && $result_artwork["faceplate_colors"] && 												$result_artwork["faceplate_rounded"] &&
						$result_artwork["jacks_location"] && $result_artwork["jacks_debris"] && $result_artwork["jacks_cable"] &&
						$result_artwork["ports_cleaned"] && $result_artwork["ports_smooth"]) {
					if(!$result_artwork["artwork_placement"]  || !$result_artwork["artwork_hq"]) {
						$count_artwork = $count_artwork + 1;
					}
				}
			}
			
			if($i == count($result2)-1) {
				$result[$step]["0"] = $result_sound["created"];
				$result[$step]["created"] = $result_sound["created"];
				$result[$step]["pass_or_fail"] = 'Sound';
				$result[$step]["num_status"] = $count_sound; 
				$step = $step + 1;
				
				$result[$step]["0"] = $result_sound["created"];
				$result[$step]["created"] = $result_sound["created"];
				$result[$step]["pass_or_fail"] = 'Shells';
				$result[$step]["num_status"] = $count_shells; 
				$step = $step + 1;
				
				$result[$step]["0"] = $result_sound["created"];
				$result[$step]["created"] = $result_sound["created"];
				$result[$step]["pass_or_fail"] = 'Faceplate';
				$result[$step]["num_status"] = $count_faceplate; 
				$step = $step + 1;
				
				$result[$step]["0"] = $result_sound["created"];
				$result[$step]["created"] = $result_sound["created"];
				$result[$step]["pass_or_fail"] = 'Jacks';
				$result[$step]["num_status"] = $count_jacks; 
				$step = $step + 1;
				
				$result[$step]["0"] = $result_sound["created"];
				$result[$step]["created"] = $result_sound["created"];
				$result[$step]["pass_or_fail"] = 'Ports';
				$result[$step]["num_status"] = $count_ports; 
				$step = $step + 1;
				
				$result[$step]["0"] = $result_sound["created"];
				$result[$step]["created"] = $result_sound["created"];
				$result[$step]["pass_or_fail"] = 'Artwork';
				$result[$step]["num_status"] = $count_artwork; 
				$step = $step + 1;

			}
				/*	
			
			
		
		
		+ 1;
			}	
			
		$query = "SELECT *, to_char(qc_date,'dd') AS created FROM qc_form WHERE id = :id";
			$stmt = pdo_query( $pdo, $query, array(":id"=>$result2[$i]["id"])); 
			$result_artwork = pdo_fetch_array( $stmt );
			if($result_artwork["sound_signature"] && $result_artwork["sound_balanced"] && 
			$result_artwork["shells_defects"] && $result_artwork["shells_colors"]&& $result_artwork["shells_faced_down"] && 															$result_artwork["shells_label"]&& $result_artwork["shells_edges"] && $result_artwork["shells_shine"] && 
			$result_artwork["faceplate_seams"] && $result_artwork["faceplate_shine"] && $result_artwork["faceplate_colors"] && 													$result_artwork["faceplate_rounded"] &&
			$result_artwork["jacks_location"] && $result_artwork["jacks_debris"] && $result_artwork["jacks_cable"] &&
			$result_artwork["ports_cleaned"] && $result_artwork["ports_smooth"]) {
					if(!$result_artwork["artwork_placement"]  || !$result_artwork["artwork_hq"]) {
						$count_artwork = $count_artwork + 1;
						$result[$step]["0"] = $result_artwork["created"];
						$result[$step]["created"] = $result_artwork["created"];
						$result[$step]["pass_or_fail"] = 'Artwork';
						$step = $step + 1;
					}
			}	*/
		}
	}
	$response["test1"] = $count_faceplate;
	$response["test2"] = $count_sound;
	$response["test3"] = $count_shells;
	$response["test4"] = $count_jacks;
	$response["test5"] = $count_ports;
	$response["test6"] = $count_artwork;
	
    $response['code'] = 'success';
    $response['data'] = $result;
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>