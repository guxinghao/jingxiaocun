<?php
class DataTableWdiget extends CWidget {
	public $id="datatable1";
	public $tableHeader=array();
	public $tableData = array();
	public $totalData = array();
	public $hide = 0;
	function init() {
		
	}
	
	function run()
	{
		$this->render('_data_table',array('data'=>$this));
	}
}