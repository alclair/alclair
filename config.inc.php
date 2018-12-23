<?php
if($_SERVER['SERVER_NAME']=="otis.alclr.co")
{
	include_once "config.otis.inc.php";

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
		}
	} else {
		
		$domainParts = explode('.', $_SERVER['SERVER_NAME']); //split domain name
		$configFile = 'config.'.$domainParts[0].'.inc.php'; //include subdomain in config file name
		
		if ($domainParts[0] == 'boh') {
			$configPath = '/home/ajswanso/public_html/otis/';
		} else {
			$configPath = '/home/ajswanso/public_html/otis/' . $domainParts[0] . '/';
		//	$configPath = '/home/ajswanso/public_html/' . $domainParts[0] . '/';

			//$configPath = '/home/ajswanso/public_html/otis/alclair/';
		}
		echo "<br/> Server name is " . $configPath;
		//If the config file exists, include it.  Include the dev config file if the config file does not exist.
		if (file_exists($configPath . $configFile)) {
			echo "<br/> Path is" . $configPath . $configFile;
			include_once($configPath . $configFile);
		} else {
			include_once('config.dev.inc.php');
		}
	}
}
?>