<?php
class AdminHeaderWidget extends CWidget {
	public $title;
	public $opButtons = array();
	function init() {
		
	}
	
	function run()
	{
		//因为button是右对齐，所以输出前，需要将数组倒序排列一次
		$this->opButtons = array_reverse($this->opButtons);
		$this->render('_admin_header',array('data'=>$this));
	}
}