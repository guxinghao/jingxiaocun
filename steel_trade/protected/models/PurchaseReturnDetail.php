<?php

/**
 * This is the biz model class for table "purchase_return_detail".
 *
 */
class PurchaseReturnDetail extends PurchaseReturnDetailData
{
	public $product;
	public $rank;
	public $texture;
	public $brand;
	public $surplus;
	public $one_weight;
	public $weight;
	public $total_amount;
	public $total_weight;
	public $total_money;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'purchaseReturn' => array(self::BELONGS_TO, 'FrmPurchaseReturn', 'purchase_return_id'),
			'storage'=>array(self::BELONGS_TO,'Storage','card_no'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'card_no' => 'Card No',
			'return_amount' => 'Return Amount',
			'return_weight' => 'Return Weight',
			'return_price' => 'Return Price',
			'purchase_return_id' => 'Purchase Return',
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
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('return_amount',$this->return_amount);
		$criteria->compare('return_weight',$this->return_weight,true);
		$criteria->compare('return_price',$this->return_price,true);
		$criteria->compare('purchase_return_id',$this->purchase_return_id);
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
	 * @return PurchaseReturnDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
