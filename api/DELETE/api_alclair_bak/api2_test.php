<?php
//include_once "../../config-default.php";
include_once("../../lib/Highrise_API_library/HighriseAPI.class.php");


// THE LINK TO THE GITHUB REPOSITOR
https://github.com/AppSaloon/Highrise-PHP-Api

# put in your information and then rename this file to config.php
#
# your account name is the first part of the url that you use to access highrise.
# For example, if you use "http://example.highrisehq.com" to access highrise, then
# "example" will be your $hr_account.
#
$hr_account = 'matrics';
#
# log into highrise, go to settings->my info->API token.  That long funky string 
#  is your apikey
#
$hr_apikey  = '55e162ff41565e6d97257cfce5421b7c';
?>


<?php

//if (count($argv) != 3)
	//die("Usage: php users.test.php [account-name] [access-token]\n");
$highrise = new HighriseAPI();
$highrise->debug = false;
//$highrise->setAccount($argv[1]);
$highrise->setAccount($hr_account);
//$highrise->setToken($argv[2]);
$highrise->setToken($hr_apikey);
$people = $highrise->findPeopleBySearchTerm("Tony");
foreach($people as $p)
	print $p->getFirstName() .  " " . $p->getLastName() . "<br>";
	
$person = new HighrisePerson($highrise);
$person->setFirstName("Dude");
$person->setLastName("Time");
$person->addEmailAddress("johndoe@gmail.com");

$address = new HighriseAddress();
$address->setStreet("165 Test St.");
$address->setCity("Glasgow");
$address->setCountry("Scotland");
$address->setZip("GL1");
$person->addAddress($address);

$person->save();
	
//$users = $highrise->findAllUsers();
//	print_r($users);

		//$new_person = $highrise->HighrisePerson();
		//$new_person->debug = false;
		//$new_person->setAccount($hr_account);
		//$new_person->setToken($hr_apikey);
		
		/*$new_person->setFirstName("Fake");
		$new_person->setLastName("Person");
		$new_person->addEmailAddress("FakePerson@gmail.com");
		$new_person->save();*/
		
		/*$this->person = new HighrisePerson($this->highrise);
		$this->person->setFirstName("Dude");
		$this->person->setLastName("Dudette");
		$this->person->addEmailAddress("gF5fK5hU@Nc23jvHP.com");
		$this->person->save();*/
?>