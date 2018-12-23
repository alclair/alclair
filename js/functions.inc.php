<?php
function run_scripts($pdo,$sql,$debug)
{
    $pdo->beginTransaction();
    $scripts=explode(";",$sql);
    for($i=0;$i<count($scripts);$i++)
    {
        $query=trim($scripts[$i]);
        if(!empty($query))
        {
            if($debug==1)
            {
                echo nl2br($query);
            }
            $stmt=$pdo->prepare($query);
            $stmt->execute();
        }       
    }    
    $pdo->commit();
    return $stmt;
}
function pdo_query($pdo,$query,$params,$debug=0)
{    
	$log=false;
	if(strpos($query,"applog")===false)
	{
		if(strpos(strtolower($query),"delete")!==false||
		strpos(strtolower($query),"insert")!==false||
		strpos(strtolower($query),"update")!==false)
		{
			$log=true;
		}
	}
    if($debug==1)
    {
        echo "<br><Br><BR><p>".nl2br($query)."</p>";
        print_r($params);
    }
    $stmt=$pdo->prepare($query);
    while(list($key,$value)=each($params))
    {      
        $stmt->bindValue($key,$value);
        if($debug==1||$log==true)
		{
			if(empty($value))
			{
                $value="null";
			}
			else
			{
				$value="'".str_replace("'","''",$value)."'";
			}
			$query=str_replace($key,$value,$query);            
		}
    }
    $stmt->execute();
	if($debug==1)
    {
		echo "<p>".$query."</p>";
	}
    if($log==true) //debug don't need to log
    {
        log_query ($query,$_REQUEST["REQUEST_URI"]);
    }
    return $stmt;
}

function pdo_rows_affected($stmt)
{
    return $stmt->rowCount();
}

function pdo_fetch_array($stmt)
{
    return $stmt->fetch();
}

function pdo_errors()
{
    global $pdo;
    print_r($pdo->errorInfo());
}

function pdo_commit($pdo)
{
    $pdo->commit();
}

function pdo_rollback($pdo)
{
    $pdo->rollback();
}

function pdo_begin_transaction($pdo)
{
    $pdo->beginTransaction();
}

function log_query($query,$page)
{
    global $pdo;
    
    $by=0;
    if(!empty($_SESSION['Username']))
    {
        $by=$_SESSION['Username'];
    }
	
	$log_query="insert into vbook_applog (Query,QueryDatetime,QueryBy,PageName) values(:Query,now(),'$by',:PageName)";
    
    $stmt=pdo_query($pdo,$log_query,array(":Query"=>$query,":PageName"=>$page));   
}
function array_copy($arr1,&$arr2)
{
	foreach($arr1 as $key=>$value)
	{
		$arr2[$key]=$value;
	}
}
function html_escape($str)
{
    return strip_tags($str);
}
function encrypt($text,$encrypt_key)
{
	if(empty($text)) return null;
    
    return @base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, $encrypt_key, $text, MCRYPT_MODE_CFB));
}
function decrypt($text,$encrypt_key)
{
	if(empty($text)) return null;
    
    return @mcrypt_decrypt(MCRYPT_BLOWFISH, $encrypt_key, base64_decode($text), MCRYPT_MODE_CFB);
}
function GetActionFile($controller=null,$action=null)
{	
	global $rootScope;
	if(!isset($controller)||empty($controller))
	{
		$controller=$rootScope["Controller"];		
	}	
	if(!isset($action)||empty($action))
	{
		$action=$rootScope["Action"];		
	}	
	$controller=strtolower($controller);	
	$action=strtolower($action);	
	$filePath= $rootScope["RootPath"]."/controllers/".$controller."/".$action.".action.php";
	if(!file_exists($filePath))
	{
		return $rootScope["RootPath"]."/controllers/home/error.action.php";;
	}
	return $rootScope["RootPath"]."/controllers/".$controller."/".$action.".action.php";
}
function GetViewFile($controller=null,$action=null)
{
	global $rootScope;
	
	if(!isset($controller)||empty($controller))
	{
		$controller=$rootScope["Controller"];		
	}	
	if(!isset($action)||empty($action))
	{
		$action=$rootScope["Action"];		
	}
	$controller=strtolower($controller);	
	$action=strtolower($action);	
	return $rootScope["RootPath"]."/views/".$controller."/".$action.".view.php";
}
function ParseDate($str)
{
	if(strtotime($str)!==false)
	{
		return date("Y-m-d",strtotime($str));
	}
	else
	{
		return null;
	}
}
?>