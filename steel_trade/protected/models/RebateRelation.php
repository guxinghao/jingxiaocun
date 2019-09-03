<?php

/**
 * This is the biz model class for table "rebate_relation".
 *
 */
class RebateRelation extends RebateRelationData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'rebate' => array(self::BELONGS_TO, 'FrmRebate', 'rebate_id'),
				'sales_form' => array(self::BELONGS_TO, 'CommonForms', 'sales_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'rebate_id' => 'Rebate',
			'sales_id' => 'Sales',
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
		$criteria->compare('rebate_id',$this->rebate_id);
		$criteria->compare('sales_id',$this->sales_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RebateRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
