<?php
include_once "../../config.inc.php";
//include_once "../../config.dev.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
include_once "../../includes/phpmailer/class.phpmailer.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;	
 
//require_once 'vendor/autoload.php';
//require __DIR__ . '/vendor/autoload.php';
require '/var/www/html/otisdev/vendor/autoload.php';
require '/var/www/html/otis/vendor/autoload.php';
//require $rootScope["RootPath"]."vendor/autoload.php";

try 
{
   
	//$url=$rootScope["RootUrl"]."/api/WooCommerce/excel_woocommerce.php";
	$url=$rootScope["RootUrl"]."/api/WooCommerce/01_get_woocommerce_orders.php";
	//echo $rootScope["RootUrl"]."/api/WooCommerce/excel_woocommerce.php";
			
	$json=file_get_contents($url);
	$list=json_decode($json,true);
	var_dump($list);
	//$file_lng="/var/www/html/otisdev/data/export/woocommerce/".$list["data"];
	$file_lng=$rootScope["RootPath"]."data/export/woocommerce/".$list["data"];
	//echo "File is " . "/var/www/html/otisdev/data/export/woocommerce/$filename";
    
//for($i = 0; $i < count($order); $i++) {
			if(file_exists($file_lng)) {
				$mail3= new PHPMailer();
				$mail3->IsSendmail(); // telling the class to use IsSendmail
				$mail3->Host       = "localhost"; // SMTP server
				$mail3->SMTPAuth   = false;                  // disable SMTP authentication  
				//$mail3->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
				$mail3->SetFrom("tyler@alclair.com", "Import Time!");
				//$mail3->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
				$mail3->AddReplyTo("tyler@alclair.com", "The Admin");
   
				$mail3->AddAddress("tyler@alclair.com");
				$mail3->AddAddress("scott@alclair.com");

				$mail3->Subject    = "Orders Imported";
				$body3="<p>Here are the orders that were imported today from yesterday.</p>";
				$mail3->MsgHTML($body3);

				$mail3->AddAttachment($file_lng, "Import File - ".date("m-d-Y").".xlsx");
				//$mail3->AddAttachment($json, "Testing-".date("m-d-Y").".xlsx");

				//echo json_encode($response);

				if(!$mail3->Send()) {
					$error="Error: Alclair  Excel document";
					file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
				} 
			} // CLOSE IF STATEMENT  -> if(file_exists($file_lng)&&!empty($emails))
			
//}
    
	//$arr = get_object_vars($result[25]); //28
	//echo $order[25]["billing_name"];
	//echo '<pre><code>' . print_r($arr, true) . '</code><pre>';
	//echo $arr;
    //echo '<p>Last Name is' . $arr[billing]->last_name;

} catch (HttpClientException $e) {
    echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
    echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
    echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
}
?>
?>