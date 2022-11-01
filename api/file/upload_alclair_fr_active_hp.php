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
	$query="SELECT customer_name FROM qc_form_ative_hp WHERE id=:id";
	$stmt=pdo_query($pdo,$query,array(":id"=>$_REQUEST["id"]));
	$row=pdo_fetch_array($stmt);
	$tmp=explode(".",$_FILES['documentfile']['name']);
	$ext=$tmp[count($tmp)-1];
	$file_name=$row["customer_name"]."_".$time.".".$ext;
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
                            "INSERT INTO qc_form_active_hp_indexupload
                            ( filepath, date_uploaded,  uploaded_by_id, qc_form_id)
                            VALUES
                            ( :filepath, now(), ".$_SESSION["UserId"].", :qc_form_id);", 
                            array( ":filepath" => $file_name, ":qc_form_id" => $_REQUEST['id'] )
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
                                    "qc_form_id" => $_REQUEST['id'],
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