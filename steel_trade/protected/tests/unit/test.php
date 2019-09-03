<?php
class Test extends CDbTestCase{
	public $fixtures = array(
		'user'=>'User',															
	);
	
	public function testShow()
	{
		$this->assertEquals("1","1");
		
		$user = $this->user;
		

	}
}