<?php

/**
 * This is the biz model class for table "dict_record_type".
 *
 */
class DictRecordType extends DictRecordTypeData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'parent_id' => 'Parent',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('parent_id',$this->parent_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictRecordType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getTypeList($type, $parent_id = "") 
	{
		$return = array();
		if ($parent_id !== "") $dr_types = DictRecordType::model()->findAllByAttributes(array('parent_id' => $parent_id,"parent_value"=>0));
		else $dr_types = DictRecordType::model()->findAll("parent_value=0");
		
		switch ($type) 
		{
			case 'array':
				foreach ($dr_types as $dr_type) 
				{
					$return[$dr_type->id] = $dr_type->name;
				}
				break;
			case 'json':
				foreach ($dr_types as $dr_type) 
				{
					$temp = array();
					$temp['id'] = "$dr_type->id";
					$temp['bs'] = "$dr_type->name";
					$temp['name'] = "$dr_type->name";
					array_push($return, $temp);
				}
				$return = json_encode($return);
				break;
			default: 
				break;
		}
		return $return;
	}
	
	//获取费用报支2级选项
	public static function getSecTypeList($id)
	{
		$return = array();
		$first = DictRecordType::model()->findByPk($id);
		if($first){
			$dr_types = DictRecordType::model()->findAll("parent_value=".$first->value);
			if($dr_types){
				foreach ($dr_types as $dr_type)
				{
					$return[$dr_type->id] = $dr_type->name;
				}
			}
		}
		return $return;
	}
}
