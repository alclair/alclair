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

$fc=$_FILES['documentfile']['name'];
$FILES = $_REQUEST["files"];
$response["test"] = "Test is " . $fc . " and count is " . count($_REQUEST["files"]);

try
{
    $time = time();	
	$tmp=explode(".",$_FILES['documentfile']['name']);
	$ext=$tmp[count($tmp)-1];
	$file_name=$time.".".$ext;
    $path = "../../trd_data/".$file_name;
    file_put_contents ("../../trd_data/log.txt","\r\n".$path,FILE_APPEND);
    
    if( !move_uploaded_file( $_FILES["documentfile"]["tmp_name"], $path ) )
    {
        $response['code'] = 'error';
        $response['message'] = 'failed_'.$_FILES["documentfile"]["error"].'_'.$_FILES["documentfile"]["tmp_name"];	
        
        $response['test'] = "FAILED " . $_FILES["documentfile"]["tmp_name"];
        echo json_encode($response);
        exit;
    }
    else
    {   
	    //compress the uploaded image
	    $maxSize = 200; //200K
	    $maxWidth = 1080;
	    compressUploadedImage($path, $maxSize, $maxWidth);
        //Move upload file from tmp to folder 'data' successful, insert a record to table
                
        $response['test'] = "Filename is " . $file_name;
		//echo json_encode($response);
		//exit;
        //$stmt = pdo_query($pdo, "INSERT INTO trd_files ( filename, date_uploaded,  uploaded_by_id, active) VALUES ( :filename, now(), ".$_SESSION["UserId"].", :active);", 
                            //array( ":filename"=>$file_name, ":active"=>TRUE));
		$stmt = pdo_query($pdo, "INSERT INTO trd_files ( filename, date_uploaded, uploaded_by_id, active) VALUES (:filename, now(), ".$_SESSION["UserId"].", TRUE)", array( ":filename"=>$file_name));
       $rowcount = pdo_rows_affected( $stmt );
        
        
        if( $rowcount == 0 )
        {
            $response['code'] = 'error';
            $response['message'] = pdo_errors();
            echo json_encode($response);
            exit;
        }

        $result = pdo_fetch_array($stmt);
        $response['code'] = 'success';
        $response['data'] = array( "file_id" => $result,  "filepath" => $file_name,);
        file_put_contents ("../../trd_data/log.txt",json_encode($response),FILE_APPEND);
        echo json_encode($response);   
    }	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>