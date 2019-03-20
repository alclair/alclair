<?php
// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL & ~E_NOTICE);
if($_SERVER['SERVER_NAME']=="otis.alclr.co")
{
	include_once "config.otis.inc.php";

} 
elseif($_SERVER['SERVER_NAME']=="otisdev.alclr.co")
{
	include_once "config.otisdev.inc.php";

}
else
{
	if (php_sapi_name() == "cli") { //Are we running via command line?
		$workingDirectory = realpath(__DIR__); //script directory
		$customerFolder = substr($workingDirectory, strrpos($workingDirectory, "/") + 1); //get customer folder
		
		if ($customerFolder == 'swd') { //BOH?
			include_once($workingDirectory."/config.boh.inc.php"); 
		} else {
			include_once($workingDirectory."/config.$customerFolder.inc.php"); //include customer's config file
			//echo "Folder is " . $customerFolder;
		}
	} else {
		
		$domainParts = explode('.', $_SERVER['SERVER_NAME']); //split domain name
		$configFile = 'config.'.$domainParts[0].'.inc.php'; //include subdomain in config file name
		//$configFile = 'config.'. 'otis' .'.inc.php'; //include subdomain in config file name
		//echo "Domain parts are " . $domainParts; 
		if ($domainParts[0] == 'boh') {
			$configPath = '/home/ajswanso/public_html/otis/';
			$configPath = '/var/www/html/otis/';
		} else {
			//$configPath = '/home/ajswanso/public_html/otis/' . $domainParts[0] . '/';
			$configPath = '/var/www/html/otis' . $domainParts[0] . '/';
			$configPath = '/var/www/html/otis/';
			$configPath = '/var/www/html/' . $domainParts[0] . '/';
			//$configPath = '/home/ajswanso/public_html/' . $domainParts[0] . '/';

			//$configPath = '/home/ajswanso/public_html/otis/alclair/';
		}
		//echo "<br/> Config Path is " . $configPath;
		//echo "<br/> Config File is " . $configFile;
		//If the config file exists, include it.  Include the dev config file if the config file does not exist.
		if (file_exists($configPath . $configFile)) {
			//echo "<br/> Path is" . $configPath . $configFile;
			include_once($configPath . $configFile);
		} else {
			include_once('config.otisdev.inc.php');
		}
	}
}
?>