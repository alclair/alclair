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
$response['test'] = 'FC is ' . $fc . ' and files are ' . $FILES;
$response['test'] = 'Files are ' . print_r($_FILES);

try
{	
    $time = time();	
	$tmp=explode(".",$_FILES['documentfile']['name']);
	$ext=$tmp[count($tmp)-1];
	$file_name=$time . "-" .  $tmp[0] . "." . $ext;
	$just_filename = $time . "-" .  $tmp[0];
	//$file_name=$tmp[0].".".$ext;
	
    $path = "../../trd_data/".$file_name;
   
    file_put_contents ("../../trd_data/log.txt","\r\n".$path,FILE_APPEND);
   
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
 
	    //compressUploadedImage($path, $maxSize, $maxWidth);
	  
	     // ADDED THIS CODE FOR BOH'S CORRECTION PAGE ON AUGUST 12TH //
	     // HAVE TO CHECK IF THE UPLAODED FILE IS A PDF
	     // IF IT IS A PDF THEN CONVERT IT TO A JPG USING IMAGE MAGIC
	     // IF IT IS NOT A PDF THEN DO NOT CHANGE IT
	     // THE COPY OF THE UPLOADED FILE IS STORED IN DATA 2 AS WELL AS DATA
	     // DATA 2 IS USED FOR THE CORRECTION PAGE
		if( !strcmp(substr($file_name, -3) , "pdf")) {
	    		//$file_name2 = substr_replace($file_name, "jpg",-3);
			$stmt = pdo_query($pdo, "INSERT INTO trd_pdf ( filename, date_uploaded, uploaded_by_id, active) VALUES (:filename, now(), ".$_SESSION["UserId"].", TRUE) RETURNING id", array( ":filename"=>$file_name));
			$result = pdo_fetch_all( $stmt );
			$pdf_id = $result[0]["id"];
 			$rowcount = pdo_rows_affected( $stmt );
			
			$im = new Imagick();
			$im->readImage('/var/www/html/otisdev/trd_data/'.$file_name);//."[0]");
			$num_pages = $im->getNumberImages();

			for ($i = 0; $i < $num_pages; $i++) {
				//$im->readImage('/var/www/html/otisdev/trd_data/'.$file_name);//."[0]");
				//$file_name2 = substr_replace($file_name . '-' . $i, "jpg",-3);
				$file_name2 =$just_filename . '-' . $i . ".jpg";
				$im->readImage('/var/www/html/otisdev/trd_data/'.$file_name.'['.$i.']');//."[0]");
				$im->setImageFormat('jpeg'); 
				$im->writeImage('/var/www/html/otisdev/trd_data2/' . $file_name2);
				$im->clear(); 
				$im->destroy();
				
				$stmt = pdo_query($pdo, "INSERT INTO trd_jpg ( filename, date_uploaded, uploaded_by_id, active, trd_pdf_id) VALUES (:filename, now(), " . $_SESSION["UserId"] . " , TRUE, :trd_pdf_id)", array( ":filename"=>$file_name2, ":trd_pdf_id"=>$pdf_id));
				//$rowcount = pdo_rows_affected( $stmt );
			}
			/*
			if($i == 2) {
					$response['test'] = 'I is  ' . $i . ' and num is ' . $im->getNumberImages();	
					echo json_encode($response);
					exit;
				}
				*/
			//$im->setResolution(300,300);
			//$im->readimage('document.pdf[0]'); 
			
			//$im->readImage('/var/www/html/otisdev/trd_data/' . 'Axis Energy 0526.pdf');
			
			//$im->writeImage('thumb.jpg'); 
			
			//echo json_encode($response);
			//echo $path2;
			//exit;   
		} else {
			$path2 = "../../trd_data2/".$file_name;
			copy('/var/www/html/otisdev/trd_data/'.$file_name, '/var/www/html/otisdev/trd_data2/'.$file_name);
			compressUploadedImage($path2, $maxSize, $maxWidth);
			//$file_name2 = $file_name;
			//$path2 = "../../data2/".$file_name2;
		}
		
        //Move upload file from tmp to folder 'data' successful, insert a record to table
        
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