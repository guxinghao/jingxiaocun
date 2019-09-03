<?php

/**
 * This is the biz model class for table "profit_change".
 *
 */
class ProfitChange extends ProfitChangeData
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
			'type' => 'Type',
			'common_id' => 'Common',
			'created_at' => 'Created At',
			'run_time' => 'Run Time',
			'disposed' => 'Disposed',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('common_id',$this->common_id);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('run_time',$this->run_time);
		$criteria->compare('disposed',$this->disposed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfitChange the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	//创建
	public static function createNew($type,$common_id,$run_time)
	{
		$model=new ProfitChange();
		$model->type=$type;
		$model->common_id=$common_id;
		$model->run_time=$run_time;
		$model->created_at=time();
		$model->disposed=0;
		$model->insert();
	}

	//更新
	public static function updateData($id)
	{
		$model=ProfitChange::model()->findByPk($id);
		if($model)
		{
			$model->disposed=1;
			$model->update();
		}
	}

}
