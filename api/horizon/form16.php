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
		
		
//$month = $_REQUEST['Month'];
//$year = $_REQUEST['Year'];

$target_dir = "../../data/";
$target_file = $target_dir . basename($_FILES["documentfile"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check file size
if ($_FILES["docuementfile"]["size"] > 500000) {
    $response["message"] = "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
	$response['test'] = "upload ok equals 0";
    //$response['testing5'] = "Sorry, your file was not uploaded.";
	echo json_encode($response);
	exit;
// if everything is ok, try to upload file
} else {
	$response['test'] = "IN ELSE";
    if (move_uploaded_file($_FILES["documentfile"]["tmp_name"], $target_file)) {
        //$response['message'] = "The file ". basename( $_FILES["documentfile"]["name"]). " has been uploaded.";
    } else {
        $response['message'] = "Sorry, there was an error uploading your file.";
        echo json_encode($response);
		exit;
    }
}

//$File = '../../data/' + $target_file;
$File = $target_file;
$inc = 0;

// PUT ALL OF THE DATA INTO $arrResult
$arrResult  = array();
$handle     = fopen($File, "r");

if(empty($handle) === false) {
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $arrResult[$inc] = $data;
        $inc = $inc + 1;
    }
    fclose($handle);
}

//$response["code"] = "success";
//echo json_encode($response);
//exit;

if (count($arrResult[0]) != 50) {
	//$response['error_message'] = "Something is wrong with the file.  Please contact Tyler";
	//echo json_encode($response);
	//exit;
}

/*
<Facility2>
	<FacID>12345</FacID> 
	<FacReportPeriod>8/2007</FacReportPeriod> 
	<FacType>well</FacType> 
	<FacCompID>ABC Oil Co.</FacCompID> 
	<FacName>Smith 35-42</FacName> 
	<Location2>
		<LocQtrQtr>NWSE</LocQtrQtr> 
		<LocSection>35</LocSection> 
		<LocTownship>164</LocTownship> 
		<LocRange>78</LocRange> 
		<LocCounty>BOTTINEAU</LocCounty>
	</Location2> 
	<Production2>
		<ProdType>Water</ProdType> 
		<ProdUnits>bbls</ProdUnits>
		<ProdQuantity>7500</ProdQuantity> 
		<ProdSource>P</ProdSource>
	</Production2> 
</Facility2>
*/

$order  = array();
$file ='';
$print2screen = array();
//for ($i=1; $i < count($arrResult); $i++) {
$month = $_REQUEST['Month'];
$year = $_REQUEST['Year'];
$injection_quantity = $_REQUEST["injection_quantity"];
$begin_reading = $_REQUEST["begin_reading"];
$end_reading = $_REQUEST["end_reading"];
$injection_pressure = $_REQUEST["injection_pressure"];

$response['testing0'] = $arrResult[4][0];
$response['testing1'] = $arrResult[4][1];
$response['testing2'] = $arrResult[4][2];
$response['testing3'] = $arrResult[4][3];
$response['testing4'] = $arrResult[4][4];
$response['testing5'] = $arrResult[4][5];


for ($i=1; $i < count($arrResult); $i++) {
	if(!strcmp($arrResult[$i][0], "Grand Total")) {
		break;
	}
	
	//$Facility2_open = "<Facility2>";
	$number = $i+1;
	//$Facility2_open = "<Facility" . $number . ">";
	$Facility2_open = "<Facility" . "2" . ">";
	$FacID = "<FacID>" . $arrResult[$i][2] . "</FacID>";  // Well file number
	$FacReportPeriod = "<FacReportPeriod>" . $month ."/". $year . "</FacReportPeriod>";
	if( !strcmp($arrResult[$i][4], "P") ) {
		$FacType = "<FacType>pit</FacType>";
	} else {
		$FacType = "<FacType>well</FacType>";
	}	
	//$FacType = "<FacType>well</FacType>";
	
	$FacCompID = "<FacCompID>" . $arrResult[$i][0] . "</FacCompID>"; // Well company name
	$FacName = "<FacName>" . $arrResult[$i][1] . "</FacName>";
	//$Facility2_close = "</Facility2>";
	//$Facility2_close = "</Facility" . $number . ">";
	$Facility2_close = "</Facility" . "2" . ">";
	$Facility2 = $Facility2_open . $FacID . $FacReportPeriod . $FacType . $FacCompID . $FacName;
	
	$qtrqtr = substr($arrResult[$i][3], 0, 5);
	$location = $arrResult[$i][3];
	
	if(strlen($location) < 5) {
		$section = "N/A";
		$township = "N/A";
		$range = "N/A";
	} else {
		for($k=0; $k < strlen($location); $k++) {
			if( !strcmp($location[$k], "-") ) {
				$section = substr($location, $k-2, 2);
				$township = substr($location, $k+1, 3);
				$range = substr($location, $k+5, 2);
				break;
			}
		}	
	}
	
	$county = "N/A";
	$quantity = $arrResult[$i][5];

	$Location2_open = "<Location2>";
	$LocQtrQtr = "<LocQtrQtr>" . $qtrqtr . " </LocQtrQtr>"; 
	$LocSection = "<LocSection>" . $section . "</LocSection>";
	$LocTownship = "<LocTownship>" . $township . "</LocTownship>";
	$LocRange = "<LocRange>" . $range . "</LocRange>";
	$LocCountry = "<LocCounty>" . $county . "</LocCounty>";
	$Location2_close = "</Location2>";
	$Location2 = $Location2_open . $LocQtrQtr . $LocSection . $LocTownship . $LocRange . $LocCountry . $Location2_close;
	
	$Production2_open = "<Production2>";
	$ProdType = "<ProdType>Water</ProdType>";
	$ProdUnits = "<ProdUnits>bbls</ProdUnits>";
	$ProdQuantity = "<ProdQuantity>" . $quantity . "</ProdQuantity>";
	
	if( !strcmp($arrResult[$i][6], "T") ) {
		$ProdSource = "<ProdSource>T</ProdSource>";
	} else {
		$ProdSource = "<ProdSource>P</ProdSource>";
	}	
	//$ProdSource = "<ProdSource>P</ProdSource>";
	$Production2_close = "</Production2>";

	$Production2 = $Production2_open . $ProdType . $ProdUnits . $ProdQuantity . $ProdSource . $Production2_close;
	
	$print2screen[$i-1]["screen"] = $Facility2 . $Location2 . $Production2 . $Facility2_close ;
	//$order = $arrResult[$i];
	//$print2screen = 'PRINT ME NOW PLEASE!!!!';
}  // END FOR LOOP

$print2screen[$i]["screen"] = "</Facility> </Company></eReport>";
$response["code"] = "success";
$response["print2screen"] = $print2screen;
$response["facilty2_open"] = $Facility2_open;
$response["facilty2_close"] = $Facility2_close;

$response["TotalRows"] = $i - 1;
//$response["testing1"] = $inc;
$response["header"] = '<?xml version="1.0" encoding="utf-8" standalone="no"?>
<eReport xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="https://www.dmr.nd.gov/oilgas/xml/eReport36.xsd https://www.dmr.nd.gov/oilgas/xml/eReport36.xsd" xmlns="https://www.dmr.nd.gov/oilgas/xml/eReport36.xsd">';

$response["begin"] = 
"<Company>
<CompID>4256</CompID>
<CompName>HORIZON-OLSON, LLC</CompName> 
<Contact>
			<ContactFirstName>Stephanie</ContactFirstName> 
			<ContactLastName>Avery</ContactLastName> 
			<ContactTitle>Disposal Supervisor</ContactTitle> 
			<ContactStreetName>1625 BIOSCIENCE DR</ContactStreetName> 
			<ContactCity>WORTHINGTON</ContactCity> 
			<ContactState>MN</ContactState> 
			<ContactZip>56187</ContactZip> 
			<ContactCountry>USA</ContactCountry> 
			<ContactPhone>5137060902</ContactPhone>
</Contact> 
<Facility>
			<FacID>W0391S0865D</FacID> 
			<FacReportPeriod>" . $month ."/". $year . "</FacReportPeriod> 
			<FacAmend>0</FacAmend> 
			<FacType>well</FacType> 
			<FacFormation>DAKOTA</FacFormation> 
			<FacName>KILLDEER WEST SWD #1</FacName> 
			<FacGroup>SWD</FacGroup> 
			<FacComment></FacComment>
			<Location> 
				<LocQtrQtr>NWNW</LocQtrQtr> 
				<LocSection>29</LocSection> 
				<LocTownship>145</LocTownship> 
				<LocRange>96</LocRange> 
				<LocCounty>DUNN</LocCounty>
			</Location> 
			<Injection>
				<InjFluid>Water</InjFluid>
				<InjQuantity>" . $injection_quantity . "</InjQuantity> 
				<InjUnit>Bbls</InjUnit> 
				<InjPress>
					<InjPressAvg>" . $injection_pressure . "</InjPressAvg> 
				</InjPress>
			</Injection> 
			<Meters>
				<TypeReading>Begin</TypeReading>
				<Reading>" . $begin_reading . "</Reading> 
			</Meters>
			<Meters>
				<TypeReading>End</TypeReading>
				<Reading>" . $end_reading . "</Reading> 
			</Meters>";

$response["end"] = 
"</Facility> 
	</Company>
</eReport>";												

echo json_encode($response);
}
catch(PDOException $ex)
{
	//$response["code"] = "error 2";
	$response["message"] = $ex->getMessage();
	//echo json_encode($response);
}

?>