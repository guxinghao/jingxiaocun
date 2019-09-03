<?php

/**
 * This is the biz model class for table "frm_send_detail".
 *
 */
class FrmSendDetail extends FrmSendDetailData
{
	public $one_weight;
	public $product;
	public $rank;
	public $texture;
	public $brand;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'frmSend'=>array(self::BELONGS_TO,'FrmSend','frm_send_id'),
			'salesDetail'=>array(self::BELONGS_TO,'SalesDetail','sales_detail_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'frm_send_id' => 'Frm Send',
			'sales_detail_id' => 'Sales Detail',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
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
		$criteria->compare('frm_send_id',$this->frm_send_id);
		$criteria->compare('sales_detail_id',$this->sales_detail_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSendDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * 根据id获取单条数据
	 */
	public static function getOne($id)
	{
		$model=FrmSendDetail::model()->findByPk($id);
		if($model)
		{
			return $model;
		}
		return false;
	}
	
}
