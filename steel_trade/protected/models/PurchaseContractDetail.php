<?php

/**
 * This is the biz model class for table "purchase_contract_detail".
 *
 */
class PurchaseContractDetail extends PurchaseContractDetailData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'purchaseContract' => array(self::BELONGS_TO, 'FrmPurchaseContract', 'purchase_contract_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'price' => 'Price',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'purchased_amount' => 'Purchased Amount',
			'purchased_weight' => 'Purchased Weight',
			'purchase_contract_id' => 'Purchase Contract',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
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
		$criteria->compare('price',$this->price,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('purchased_amount',$this->purchased_amount);
		$criteria->compare('purchased_weight',$this->purchased_weight,true);
		$criteria->compare('purchase_contract_id',$this->purchase_contract_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchaseContractDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 获取单条数据，根据id
	 */
	public static function getOne($id)
	{
		$model=PurchaseContractDetail::model()->findByPk($id);
		if($model)
		{
			return $model;
		}
		return false;
	}
	

}
