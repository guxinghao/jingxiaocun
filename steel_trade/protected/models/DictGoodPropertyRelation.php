<?php

/**
 * This is the biz model class for table "dict_good_property_relation".
 *
 */
class DictGoodPropertyRelation extends DictGoodPropertyRelationData
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
			'jxc_property_id' => 'Jxc Property',
			'api_property_id' => 'Api Property',
			'property_type' => 'Property Type',
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
		$criteria->compare('jxc_property_id',$this->jxc_property_id);
		$criteria->compare('api_property_id',$this->api_property_id);
		$criteria->compare('property_type',$this->property_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictGoodPropertyRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//根据进销存id获取接口中心id
	public static function getApiId($id)
	{
		$model = DictGoodPropertyRelation::model()->find('jxc_property_id='.$id);
		if($model){
			return $model->api_property_id;
		}else{
			return false;
		} 
	}
	
	//获取进销存id
	public static function getJxcId($id,$type)
	{
		$model=DictGoodPropertyRelation::model()->find('api_property_id="'.$id.'" and property_type="'.$type.'"');
		if($model){
			return $model->jxc_property_id;
		}else{
			return false;
		}
	}
}
