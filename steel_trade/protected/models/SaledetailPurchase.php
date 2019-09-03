<?php

/**
 * This is the biz model class for table "saledetail_purchase".
 *
 */
class SaledetailPurchase extends SaledetailPurchaseData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'saledetailCont'=>array(self::BELONGS_TO,'SalesDetail','sales_detail_id'),
				'purdetailCont'=>array(self::BELONGS_TO,'PurchaseDetail','purchase_detail_id'),
				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sales_detail_id' => 'Sales Detail',
			'purchase_id' => 'Purchase',
			'amount' => 'Amount',
			'weight' => 'Weight',
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
		$criteria->compare('sales_detail_id',$this->sales_detail_id);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SaledetailPurchase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
