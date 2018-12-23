<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = array();

try
{	
    if(empty($_REQUEST["id"]))
    {
        $params=array(":search"=>$_REQUEST["q"]."%");
         $stmt = pdo_query( $pdo,
					   "SELECT DISTINCT reviewers.id, reviewers.firstname, reviewers.lastname, reviewers.title_id, 
					   reviewers.tag_id, reviewers.iclub_id, reviewers.status_id,
					   reviewers.address_1, reviewers.address_2, reviewers.address_3, reviewers.address_4, reviewers.city, reviewers.state, reviewers.zip, reviewers.country_id,
					   reviewers.ra_sent, reviewers.ra_signed, reviewers.nda_sent, reviewers.nda_signed, reviewers.notes,
					   tbl_title.title, tbl_ifi_tag.ifi_tag, tbl_iclub.iclub, tbl_status.status, tbl_country.country
					   
                       FROM reviewers
                       LEFT JOIN tbl_title ON reviewers.title_id=tbl_title.id
                       LEFT JOIN tbl_ifi_tag ON reviewers.tag_id=tbl_ifi_tag.id
                       LEFT JOIN tbl_iclub ON reviewers.iclub_id=tbl_iclub.id
                       LEFT JOIN tbl_status ON reviewers.status_id=tbl_status.id
                       LEFT JOIN tbl_country ON reviewers.country_id=tbl_country.id
                       WHERE (reviewers.firstname::text ilike :search OR reviewers.lastname ilike :search)
					   ORDER BY reviewers.lastname, reviewers.firstname",
						$params
					 );	
        
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
			$data["name"]=$row["title"]. " " . $row["firstname"]." ".$row["lastname"];
            //$data["name"]=$row["file_number"]."//".$row["current_well_name"]."//".$row["operator_name"];
            $response["data"][]=$data;
            
            $response["Q"] = $_REQUEST["q"];
             if(empty($_REQUEST["id"])) {
			 	$response["the_id"] = $_REQUEST["id"];
			 	$response["the_answer"] = "empty";
			}  else {
				$response["the_answer"] = "not empty";
			}
        }        
    }
	else
    {        
        $stmt = pdo_query( $pdo,
					   'SELECT DISTINCT reviewers.id, reviewers.firstname, reviewers.lastname, reviewers.title_id, 
					   reviewers.tag_id, reviewers.iclub_id, reviewers.status_id,
					   reviewers.address_1, reviewers.address_2, reviewers.address_3, reviewers.address_4, reviewers.city, reviewers.state, reviewers.zip, reviewers.country_id,
					   reviewers.ra_sent, reviewers.ra_signed, reviewers.nda_sent, reviewers.nda_signed, reviewers.notes,
					   tbl_title.title, tbl_ifi_tag.ifi_tag, tbl_iclub.iclub, tbl_status.status, tbl_country.country
					   
                       FROM reviewers
                       LEFT JOIN tbl_title ON reviewers.title_id=tbl_title.id
                       LEFT JOIN tbl_ifi_tag ON reviewers.tag_id=tbl_ifi_tag.id
                       LEFT JOIN tbl_iclub ON reviewers.iclub_id=tbl_iclub.id
                       LEFT JOIN tbl_status ON reviewers.status_id=tbl_status.id
                       LEFT JOIN tbl_country ON reviewers.country_id=tbl_country.id
                       WHERE reviewers.id = :id
					   ORDER BY reviewers.lastname, reviewers.firstname',
						array(":id"=>$_REQUEST["id"]));	
        $row=pdo_fetch_array($stmt);
        $response["data"]["id"]=$row["id"];
        $response["data"]["name"]=$row["title"]. " " . $row["firstname"]." ".$row["lastname"];
        $response["output"] = $row;
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["name"]=$row["title"]. " " . $row["firstname"]." ".$row["lastname"];
            //$data["source_well_file_number"]=$row["file_number"];
            //$data["source_well_operator_name"]=$row["operator_name"];
            //$data["source_well_name"]=$row["current_well_name"];
            $response["data"][]=$data;
            
        }        
        
    }
	$response['code']='success';	
	//var_export($result);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>