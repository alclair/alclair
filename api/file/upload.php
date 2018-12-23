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

$fc=$_REQUEST["id"].":".$_FILES['documentfile']['name'];

try
{
    $time = time();
	$query="select ticket_number from ticket_tracker_ticket where id=:id";
	$stmt=pdo_query($pdo,$query,array(":id"=>$_REQUEST["id"]));
	$row=pdo_fetch_array($stmt);
	$tmp=explode(".",$_FILES['documentfile']['name']);
	$ext=$tmp[count($tmp)-1];
	$file_name=$row["ticket_number"]."_".$time.".".$ext;
    $path = "../../data/".$file_name;
    file_put_contents ("../../data/log.txt","\r\n".$path,FILE_APPEND);
    
    if( !move_uploaded_file( $_FILES["documentfile"]["tmp_name"], $path ) )
    {
        $response['code'] = 'error';
        $response['message'] = 'failed_'.$_FILES["documentfile"]["error"].'_'.$_FILES["documentfile"]["tmp_name"];	
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
        
        $stmt = pdo_query(
                            $pdo,
                            "insert into ticket_tracker_indexupload
                            ( filepath, date_uploaded,  uploaded_by_id,
                            ticket_id)
                            values
                            ( :filepath, now(), ".$_SESSION["UserId"].",
                            :ticket_id);", 
                            array( ":filepath" => $file_name, ":ticket_id" => $_REQUEST['id'] )
                         );
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
        $response['data'] = array( 
                                    "file_id" => $result, 
                                    "ticket_id" => $_REQUEST['id'],
                                    "filepath" => $file_name,
                                 );
        file_put_contents ("../../data/log.txt",json_encode($response),FILE_APPEND);
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