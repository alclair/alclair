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
       
              /* $stmt = pdo_query( $pdo,
					   "SELECT DISTINCT * FROM reviewers
                       where (lastname::text ilike :search)
                       ORDER BY firstname",
						$params
					 );	*/
				$stmt = pdo_query( $pdo,
					   "SELECT *,  COALESCE(firstname, ' ') || ' ' || COALESCE(lastname, ' ') AS whole_name FROM reviewers
                       where (firstname::text ilike :search)
                       ORDER BY firstname",
						$params
					 );	

					 
					 
        
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["firstname"]=$row["firstname"];
            $data["lastname"]=$row["lastname"];
            $data["whole_name"]=$row["whole_name"];
            $response["data"][]=$data;
        }        
    }
	else
    {        
        $stmt = pdo_query( $pdo,
					   'SELECT * FROM reviewers
                        WHERE id=:id',
						array(":id"=>$_REQUEST["id"])
					 );	
        $row=pdo_fetch_array($stmt);
        $response["data"]["id"]=$row["id"];
        $response["data"]["name"]=$row["firstname"];
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