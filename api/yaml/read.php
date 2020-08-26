<?php
include_once "../../config.inc.php";

include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require '/var/www/html/otisdev/vendor/autoload.php';

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
$target_dir = "../../data/yaml/";

//$THE_FILES = ($_REQUEST["THE_FILES"]);
//$THE_FILES = explode(',', $_REQUEST["THE_FILES"]);

//$bbb = array_keys($_FILES["documentfile"]);
//$bbb = $_FILES['documentfile']['name'];

$customer_id = $_POST["customer_id"];
$date = $_POST["date"];
$author = $_POST["author"];
$body = $_POST["body"];
$no_record = '';


if($_REQUEST["loop_number"] == 1) {
	$response['test'] = $body . " and right here";
	echo json_encode($response);
	exit;
}

$target_file = $target_dir . basename($_FILES["documentfile"]["name"]);
$response["filename"] = $_FILES["documentfile"]["name"];
//$target_file = $target_dir . basename($THE_FILES[0]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

$index = 0;
$customer_index = 0;

//for ($s=0; $s < count($_FILES); $s++) {
	// Check file size
	if ($_FILES["docuementfile"]["size"] > 500000) {
	    $response["message"] = "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$response['test'] = "upload ok equals 0";
	} else {
		$response['test'] = "IN ELSE";
	    if (move_uploaded_file($_FILES["documentfile"]["tmp_name"], $target_file)) {
		//if (move_uploaded_file($THE_FILES[0], $target_file)) {
	        $response['message'] = "The file ". basename( $_FILES["documentfile"]["name"]). " has been uploaded.";
	        $response['test'] = "The file ". basename( $_FILES["documentfile"]["name"]). " has been uploaded.";
	        //echo json_encode($response);
			//exit;
	    } else {
	        $response['message'] = "Sorry, there was an error uploading your file.";
	        $response['test'] = "FAILED";
	        echo json_encode($response);
			exit;
	    }
	}
	
	$File = $target_file;
	$parsed2 = yaml_parse_file($File);
	$response['test'] =count($parsed2);
	$response['test'] =array_keys($parsed2) . " and here";

		
	if(count($parsed2) > 1) {
		//$response["things"] = "Has records - " . $_FILES["documentfile"]["name"];
	} else {
		//$response["things"] = "Does not have records - " . $_FILES["documentfile"]["name"];	
		$no_record = $no_record . "," .   $_FILES["documentfile"]["name"];	
	}
	
	for ($i=0; $i < count($parsed2); $i++) {
		if($i == 0 ) {
			$customer_id_store[$customer_index] = $parsed2[$i]["ID"];
			$customer_index = $customer_index + 1;
			
		//} elseif($i == 1) { // COMPANY INFO & DO NOT NEED
		
		//} elseif($i == 2) { // CONTACT INFO & DO NOT NEED
			/*
			$hold = $parsed2[2]["Contact"];
			for ($k=0; $k < count($parsed2[2]["Contact"]); $k++) {
				if(!strcmp($hold[$k][0], "Phone_numbers")) {
					$hold1 = $hold[$k][1];
				} elseif(!strcmp($hold[$k][0], "Email_addresses")) {
					$hold1 = $hold[$k][1];
				}
			}
			*/
		} elseif($i >= 1) {
			//$customer_id[$index] = $customer_id[$customer_index-1];	
			
			$string = json_encode($parsed2[$i]);
						
			if(stristr($string, "Note ") || stristr($string, "Email ") ) {  
				
				$ind = strpos($string, "Note");
				$ind2 = strpos($string, "Email");
				if(stristr(substr($string, 0, 24), "Note")) {  // NOTE IS NOT PART OF THIS ITERATION THROUGH THE FOR LOOP
					$customer_id = $customer_id . "," .  $customer_id_store[$customer_index-1];	
					
					$colon_pos = strpos($string,":");
					$note_id = "Note" . substr($string, 6, $colon_pos-7);

					$response["test"] = "Test is " . $note_id . " and note 2 is " . $note_id2;
						//echo json_encode($response);
						//exit;
					
					//$note_id = "Note " . substr($string, $ind+5, 9);
					$hold = $parsed2[$i][$note_id];
					
					$string_hold = json_encode($hold[3]["Body"]);
					$string_hold = str_replace(",", " ", $string_hold); // REMOVING COMMAS FROM THE BODY
					$string_hold = str_replace("|-", "", $string_hold); // REMOVING |- FROM THE BODY
					$body = $body . "," .  $string_hold;
					//$body = $body . "," .   json_encode($hold[3]["Body"]);
					if($i == 5) {
						$response["test"] = "TESTHERE " . $i . " IS  " .  json_encode($hold[3]["Body"]);//$parsed2[$i][$note_id];
						//echo json_encode($response);
						//exit;
					}
				} elseif(stristr(substr($string, 0, 24), "Email")) {				
					$customer_id = $customer_id . "," .  $customer_id_store[$customer_index-1];	
				
					$colon_pos = strpos($string,":");
					$note_id = "Email" . substr($string, 7, $colon_pos-8);
					$response["test"] = "Test is " . $note_id;
						//echo json_encode($response);
						//exit;
					//$note_id = "Email " . substr($string, $ind2+6, 9);
					$hold = $parsed2[$i][$note_id];
					
					if($i == 5) {
						$response["test"] = "TEST " . $i . " IS  " .  json_encode($hold[3]["Body"]);//$parsed2[$i][$note_id];
						//echo json_encode($response);
						//exit;
					}
					
					$string_hold = json_encode($hold[4]["Body"]);
					if(strlen($string_hold) > 500) {
						$string_hold = "Truncated due to size.";
						$body = $body . "," .  $string_hold;
					} else {
						$string_hold = str_replace(",", " ", $string_hold); // REMOVING COMMAS FROM THE BODY
						$string_hold = str_replace("|-", "", $string_hold); // REMOVING |- FROM THE BODY
						$body = $body . "," .  $string_hold;
						//$body = $body . "," .   json_encode($hold[4]["Body"]);	
					}
	
				} else {
					$response["test"] = "IN ELSE and I is " . $i;
					//echo json_encode($response);
					//exit;
				}
	
						
				
				$written = json_encode($hold[1]["Written"]);  // THIS IS THE DATE
				$written = substr($written, 1, strlen($written)-2);
				//$date[$index]= date('m/d/Y H:m', strtotime($written));
				$date = $date. "," .  date('m/d/Y H:i', strtotime($written));
				//$date = $date. "," .  $written;
				
				$about = json_encode($hold[2]["About"]);
				//$body[$index] = json_encode($hold[3]["Body"]);
				
				
				$employee = json_encode($hold[0]["Author"]);
				if( stristr($employee, "Amanda") ) {
					$this_author = "amanda@alclair.com";
				} elseif( stristr($employee, "Amy") ) {
					$this_author = "amy@alclair.com";
				} elseif( stristr($employee, "Marc") ) {
					$this_author = "marc@alclair.com";
				} elseif( stristr($employee, "Andy") ) {
					$this_author = "andy@alclair.com";
				} elseif( stristr($employee, "Scott") ) {
					$this_author = "scott@alclair.com";
				} elseif( stristr($employee, "Jonny") ) {
					$this_author = "jonny@alclair.com";
				} elseif( stristr($employee, "Jeremy") ) {
					$this_author = "jeremy.lee@alclair.com";
				} elseif( stristr($employee, "Stephen") ) {
					$this_author = "stephen@alclair.com";
				} else {
					//$author[$index] = "sales@alclair.com";
					$this_author = "sales@alclair.com";
				}
				$author = $author . "," .   $this_author;
				$index = $index + 1;		
			} // CLOSE IF STATEMENT TO SEE IF NOTE OR EMAIL NEEDS TO BE FOUND
		}	// CLOSE IF STATEMENT
	}  // END FOR LOOP FOR PARSING THROUGH EACH FILE
//} // END FOR LOOP FOR EACH FILE
	$response["customer_id"] = $customer_id;
	$response["date"] = $date;
	$response["author"] = $author;
	$response["body"] = $body;
	$response["no_record"] = $no_record;
	//$response['test'] = "HERE3";
	echo json_encode($response);
	//exit;



/*
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
// Set workbook properties
$spreadsheet->getProperties()->setCreator('Tyler Folsom')
    ->setLastModifiedBy('Tyler Folsom')
    ->setTitle('YAML')
    ->setSubject('PhpSpreadsheet')
    ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
    ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
    ->setCategory('YAML IMPORT');
    
// Set worksheet title
$spreadsheet->getActiveSheet()->setTitle('YAML Test 1');

//Set active sheet index to the first sheet, and add some data
$spreadsheet->setActiveSheetIndex(0)
        ->setCellValue("A1", "User ID") 
		->setCellValue("B1", "Date 1") 
		->setCellValue("C1",  "Author") 
		->setCellValue("D1", "Note Content");

$row = 2;
for($p = 0; $p < $index; $p++) {
	$spreadsheet->setActiveSheetIndex(0)
       ->setCellValue("A".$row, $customer_id[$p]) 
	   ->setCellValue("B".$row, $date[$p]) 
	   ->setCellValue("C".$row,  $author[$p]) 
	   ->setCellValue("D".$row, $body[$p]);
	$row++;
}

$filename = "YAMLtest2.xlsx";
$writer_dev = IOFactory::createWriter($spreadsheet, 'Xlsx');
//$writer_dev->save("/var/www/html/otisdev/data/export/woocommerce/$filename");
$writer_dev->save($rootScope["RootPath"]."/data/yaml/$filename");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/


//echo json_encode($response);
}
catch(PDOException $ex)
{
	//$response["code"] = "error 2";
	$response["message"] = $ex->getMessage();
	//echo json_encode($response);
}

?>