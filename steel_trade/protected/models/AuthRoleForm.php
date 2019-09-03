<?php
class AuthRoleForm extends CFormModel {
	public $name;
	public $description;
	public $priority;
	public function rules()
	{
		return array(
		// username and password are required
		array('name,description,priority', 'required'),
		);
	}
}