<?php

/**
 * This is the biz model class for table "sale_subsidy".
 *
 */
class SaleSubsidy extends SaleSubsidyData
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
			'grade' => 'Grade',
			'sale_weight' => 'Sale Weight',
			'per_money' => 'Per Money',
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

		$criteria->compare('grade',$this->grade,true);
		$criteria->compare('sale_weight',$this->sale_weight,true);
		$criteria->compare('per_money',$this->per_money);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SaleSubsidy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/*
	* 获取销售提成
	*/
	public static function getSubsity($grade='')
	{
		$return='';
		if($grade)
		{
			$result=SaleSubsidy::model()->findByPk($grade);
			if($result)
			{
				$return= $result->per_money;
			}
		}
		return $return;

	}

}
