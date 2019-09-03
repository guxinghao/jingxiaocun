<?php

/**
 * This is the biz model class for table "storage_change_log".
 *
 */
class StorageChangeLog extends StorageChangeLogData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'storage' => array(self::BELONGS_TO, 'Storage', 'storage_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'storage_id' => 'Storage',
			'change_type' => 'Change Type',
			'change_amount' => 'Change Amount',
			'change_weight' => 'Change Weight',
			'order_id' => 'Order',
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
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('change_type',$this->change_type,true);
		$criteria->compare('change_amount',$this->change_amount);
		$criteria->compare('change_weight',$this->change_weight,true);
		$criteria->compare('order_id',$this->order_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StorageChangeLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function createNew($data)
	{
		$model=new StorageChangeLog();
		foreach ($data as $k=>$v)
		{
			$model->$k=$v;
		}
		if($model->insert())return true;
	
		return false;
	}
}
