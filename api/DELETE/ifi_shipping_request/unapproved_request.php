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
	$export["unapprove_notes"] = $_REQUEST["unapprove_notes"];
	$export["request_made_by"] = $_REQUEST["request_made_by"];
	$export["id"] = $_REQUEST["id"];
	 
//////////////////////////////////////////////////////////////////////              EXPORT          //////////////////////////////////////////////////////////////////////////
		$stmt = pdo_query($pdo, "UPDATE shipping_requests SET unapprove_notes = :unapprove_notes, approved = 'UNAPPROVED' WHERE id = :id", array(":unapprove_notes"=>$export["unapprove_notes"], ":id"=>$export["id"]));

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$result2 = pdo_fetch_all($stmt);
$log_id = $result2[0]["id"];   
$response["log_id"] = $log_id;

$stmt = pdo_query( $pdo, "SELECT * FROM auth_user WHERE id = :request_made_by", array(":request_made_by"=>$export["request_made_by"]));
$result10 = pdo_fetch_all($stmt);
$user_is = $result10[0]["username"];
$email_is = $result10[0]["email"];

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
		$mail3->AddAddress($email_is);
	//}
	//print_r($arr);
	//return;
	$mail3->Subject    = "Shipping Request Unapproved";
	$body3="<p>Your shipping request has been unapproved.</p>" . 
	"<p>The shipping request was unapproved because <b>" . $export["unapprove_notes"] . "</b>.</p>" . 
	"<p><a href='https://dev.swdmanager.com/ifi/open_review/" . $export["id"] . "'>Take Me to the Shipping Request</a>" . "</p>";

	$mail3->MsgHTML($body3);
	//$mail3->AddAttachment($file_lng, "ZzZzZ-".date("m-d-Y").".xlsx");
	//$mail3->AddAttachment($file_lng, "Step4.xlsx");
	//$mail3->AddAttachment($file_lng, "Alclaur Audio QC Stat Tracking Report-".date("m-d-Y").".xlsx");
	//$mail->AddAttachment($file_disposal, "DailyDisposal.xlsx");

	if(!$mail3->Send()) 
	{
		$error="Error: iFi Unapproving Shipping Request";
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