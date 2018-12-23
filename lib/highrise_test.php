<?php
class TestOfHighrise extends UnitTestCase {
	public $account;
	public $token;
	private $highrise;
	function __construct() {
		global $hr_account, $hr_apikey;
		$this->account = $hr_account;
		$this->token   = $hr_apikey;
	}
	function setUp() {  // executes before each test.
		$this->highrise = new HighriseAPI;
		$this->highrise->setAccount($this->account);
		$this->highrise->setToken($this->token);
	}
	function tearDown() { //executes after each test.
	}
	function testHRConnect() {
		try {
			$ret = $this->highrise->findMe();
		} catch (Exception $e) {
			$this->assertFalse($e->getMessage());
		}
		$this->assertTrue(is_object($ret));
		
	}
}
?>