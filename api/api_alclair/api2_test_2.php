<?php
class TestOfHighrisePeople extends UnitTestCase {
	public $account;
	public $token;
	private $highrise;
	private $person;
	function __construct() {
		global $hr_account, $hr_apikey;
		$this->account = $hr_account;
		$this->token   = $hr_apikey;
	}
	// setup highrise and a person before testing.
	function setup() {
		$this->highrise = new HighriseAPI;
		$this->highrise->setAccount($this->account);
		$this->highrise->setToken($this->token);
		$this->person = new HighrisePerson($this->highrise);
		$this->person->setFirstName("NotReal");
		$this->person->setLastName("Person");
		$this->person->addEmailAddress("gF5fK5hU@Nc23jvHP.com");
		$this->person->save();
	}
	// delete the person when complete.
	function tearDown() {
		//$this->person->delete();
	}
	function testFindTestUser() {
		$people = $this->highrise->findPeopleByEmail('gF5fK5hU@Nc23jvHP.com');
		$cnt = count($people);
		$this->assertTrue($cnt==1, "We found $cnt people and we should've only found one");
	}
	function testEmailAddresses() {
		$testaddr = "xxx1xxx@xxx1xxx.com";
		$this->person->addEmailAddress($testaddr);
		$this->person->save();
		$addresses = $this->person->getEmailAddresses();
		$emails = array();
		foreach ($addresses as $obj) {
			$emails[] = $obj->address;
		}
		$this->assertTrue(in_array($testaddr,$emails));
	}
	function testAddresses() {
		$this->person->addAddress("123 TEst Drive", "TestCity", "TE", "12345", "USA");
		$this->person->save();
		$addresses = $this->person->getAddresses();
		$this->assertEqual("123 TEst Drive, TestCity, TE, 12345, USA.",$addresses[0]->getFullAddress());
	}
	function testPhoneNumber() {
		$this->person->addPhoneNumber("(123) 123 - 1234");
		$this->person->save();
		$phones = $this->person->getPhoneNumbers();
		$this->assertEqual("(123) 123 - 1234",$phones[0]->getNumber());
	}
	function testWebAddress() {
		$this->person->addWebAddress("http://google.com");
		$this->person->save();
		$webaddrs = $this->person->getWebAddresses();
		$this->assertEqual("http://google.com",$webaddrs[0]->getUrl());
	}
	function testInstantMessenger() {
		$this->person->addInstantMessenger("AIM","MYFAKEHANDLE");
		$this->person->save();
		$ims = $this->person->getInstantMessengers();
		$this->assertEqual("AIM:MYFAKEHANDLE",(string)$ims[0]);
	}
	function testTwitter() {
		$this->person->addTwitterAccount("MYFAKETWITTERACCOUNT");
		$this->person->save();
		$twitter_accounts = $this->person->getTwitterAccounts();
		$this->assertEqual("MYFAKETWITTERACCOUNT",$twitter_accounts[0]->getUsername());
	}
	function testTags() {
		$this->person->addTag("XXXFAKETAGXXX");
		$this->person->save();
		$tags = array_keys($this->person->getTags());
		$this->assertTrue(in_array("XXXFAKETAGXXX",$tags));
	}
	function testCustomFields() {
		$this->person->addCustomfield("XXXFAKETAGXXX");
		$this->person->save();
		$tags = array_keys($this->person->getTags());
		$this->assertTrue(in_array("XXXFAKETAGXXX",$tags));
	}
}
?>
