<?php

/**
 * This is the biz model class for table "sales_return_detail".
 *
 */
class SalesReturnDetail extends SalesReturnDetailData
{
	

	public $total_amount;
	public $total_weight;
	public $total_money;
	public $total_num;

	public $re_money,$re_weight;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'salesReturn' => array(self::BELONGS_TO, 'FrmSalesReturn', 'sales_return_id'),
			"turnover"=>array(self::HAS_ONE,"Turnover","form_detail_id"),
			'inputDetails'=>array(self::HAS_MANY,'InputDetail','purchase_detail_id','condition'=>'inputDetails.from="return"'),
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
			'sale_detail_id' => 'Sale Detail',
			'return_amount' => 'Return Amount',
			'return_weight' => 'Return Weight',
			'return_price' => 'Return Price',
			'sales_return_id' => 'Sales Return',
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
		$criteria->compare('sale_detail_id',$this->sale_detail_id);
		$criteria->compare('return_amount',$this->return_amount);
		$criteria->compare('return_weight',$this->return_weight,true);
		$criteria->compare('return_price',$this->return_price,true);
		$criteria->compare('sales_return_id',$this->sales_return_id);
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
	 * @return SalesReturnDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
