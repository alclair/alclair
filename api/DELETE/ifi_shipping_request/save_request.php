<?php
include_once "../../config.inc.php";
//$dir = str_replace('/api/ifi_shipping_request', '', $dir);
//nclude_once $dir."/config.inc.php";
//include_once "../../config.inc.php";
include_once "../../includes/phpmailer/class.phpmailer.php";
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
	$export = array();
	$export["reviewer_id"] = $_REQUEST["reviewer_id"];
	$export["title_id"] = $_POST["title_id"];
	$export["firstname"] = $_POST["firstname"];
	$export["lastname"] = $_POST["lastname"];
	$export["name"] = $_POST["name"];
	$export["address_1"] = $_POST["address_1"];
	$export["address_2"] = $_POST["address_2"];
	$export["address_3"] = $_POST["address_3"];
	$export["address_4"] = $_POST["address_4"];
	$export["city"] = $_POST["city"];
	$export["state"] = $_POST["state"];
	$export["zip"] = $_POST["zip"];
	$export["country_id"] = $_POST["country_id"];
	$export["reason_id"] = $_POST["reason_id"];
	$export["unit_type_id"] = $_POST["unit_type_id"];
	$export["notes"] = $_POST["notes"];
	 
//////////////////////////////////////////////////////////////////////              EXPORT          //////////////////////////////////////////////////////////////////////////

/*	$stmt = pdo_query( $pdo, "INSERT INTO shipping_requests (title_id, firstname, lastname, address_1, address_2, address_3, address_4, city, state, zip, country_id, reason, notes, active, requested, approved, shipped, reviewer_received, received_back, date) VALUES (:title_id, :firstname, :lastname, :address_1, :address_2, :address_3, :address_4, :city, :state, :zip, :country_id, :reason, :notes, :active, :requested, :approved, :shipped, :reviewer_received, :received_back, now()) RETURNING id", array("title_id"=>$export["title_id"], "firstname"=>$export["firstname"], ":lastname"=>$export["lastname"], ":address_1"=>$export["address_1"], ":address_2"=>$export["address_2"], ":address_3"=>$export["address_3"], ":address_4"=>$export["address_4"], ":city"=>$export["city"], ":state"=>$export["state"], ":zip"=>$export["zip"], ":country_id"=>$export["country_id"], ":reason"=>$export["reason"], ":notes"=>$export["notes"], ":active"=>TRUE,
			":requested"=>TRUE,
			":approved"=>FALSE,
			":shipped"=>FALSE,
			":reviewer_received"=>FALSE,
			":received_back"=>FALSE));	*/		

     	$stmt = pdo_query( $pdo, "INSERT INTO shipping_requests (reviewer_id, title_id, firstname, lastname, address_1, address_2, address_3, address_4, city, state, zip, country_id, notes, active, requested, approved, shipped, reviewer_received, received_back, date, request_made_by, reason_id, unit_type_id) VALUES (:reviewer_id, :title_id, :firstname, :lastname, :address_1, :address_2, :address_3, :address_4, :city, :state, :zip, :country_id, :notes, :active, :requested, :approved, :shipped, :reviewer_received, :received_back, now(), :request_made_by) RETURNING id", array(":reviewer_id"=>$export["reviewer_id"], ":title_id"=>$export["title_id"], ":firstname"=>$export["firstname"], ":lastname"=>$export["lastname"], ":address_1"=>$export["address_1"], ":address_2"=>$export["address_2"], ":address_3"=>$export["address_3"], ":address_4"=>$export["address_4"], ":city"=>$export["city"], ":state"=>$export["state"], ":zip"=>$export["zip"], ":country_id"=>$export["country_id"], ":reason"=>$export["reason"], ":notes"=>$export["notes"], ":active"=>TRUE,
			":requested"=>TRUE,
			":approved"=>'NEEDS_APPROVAL',
			":shipped"=>0,
			":reviewer_received"=>0,
			":received_back"=>0,
			":request_made_by"=>$_SESSION['UserId'],
			":reason_id"=>$_SESSION['UserId'],
			":unit_type_id"=>$_SESSION['UserId']));	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$result2 = pdo_fetch_all($stmt);
$log_id = $result2[0]["id"];   
$response["log_id"] = $log_id;

$stmt = pdo_query( $pdo, "SELECT * FROM auth_user WHERE id = :UserId", array(":UserId"=>$_SESSION["UserId"]));
$result10 = pdo_fetch_all($stmt);
$user_is = $result10[0]["username"];


	$mail3= new PHPMailer();
    $mail3->IsSendmail(); // telling the class to use IsSendmail
	$mail3->Host       = "localhost"; // SMTP server
	$mail3->SMTPAuth   = false;                  // disable SMTP authentication  
    $mail3->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
    $mail3->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
    
	//$arr=TokenizeString($emails);
		
	//for($i=0;$i<count($arr);$i++)
	//{
		$mail3->AddAddress($_SESSION["Email"]);
		$mail3->AddAddress("tylerf@ifi-audio.com");
	//}
	//print_r($arr);
	//return;
	$mail3->Subject    = "New Shipping Request";
	$body3="<p>There has been a new shipping request.  It was made by <b>" . $user_is . "</b>.</p>" . 
	"<p>Click the link to be taken to the shipping request.  https://dev.swdmanager.com/ifi/open_review/" . $log_id . "</p>" . 
	"<p><a href='https://dev.swdmanager.com/ifi/open_review/" . $log_id . "'>Take Me to the Form</a>" . "</p>";

	$mail3->MsgHTML($body3);
	//$mail3->AddAttachment($file_lng, "ZzZzZ-".date("m-d-Y").".xlsx");
	//$mail3->AddAttachment($file_lng, "Step4.xlsx");
	//$mail3->AddAttachment($file_lng, "Alclaur Audio QC Stat Tracking Report-".date("m-d-Y").".xlsx");
	//$mail->AddAttachment($file_disposal, "DailyDisposal.xlsx");

	if(!$mail3->Send()) 
	{
		$error="Error: iFi Sending Shipping Request";
		file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
	} 

	$response['code']='success';
	$response['data'] = $result;
	echo json_encode($response);
	//$response['test'] = $export["new"];
	
	//var_export($result);
	
	//echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>