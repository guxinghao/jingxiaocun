<?php

/**
 * This is the biz model class for table "input_detail".
 *
 */
class InputDetail extends InputDetailData
{
	public $warehouse_id;

	public $total_amount;
	public $total_weight;
	public $total_money;
	public $total_num;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'input' => array(self::BELONGS_TO, 'FrmInput', 'input_id'),
			'purchaseDetail' => array(self::BELONGS_TO, 'PurchaseDetail', 'purchase_detail_id'),
			'storage'=>array(self::HAS_ONE,'Storage','input_detail_id','condition'=>'storage.is_deleted=0 and card_status!="deleted"'),
			'pushdetail'=>array(self::BELONGS_TO,'PushedStorageDetail','push_detail_id'),
			'returnDetail'=>array(self::BELONGS_TO,'SalesReturnDetail','purchase_detail_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'input_id' => 'Input',
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'purchase_detail_id' => 'Purchase Detail',
			'cost_price' => 'Cost Price',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'card_id' => 'Card',
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
		$criteria->compare('input_id',$this->input_id);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('purchase_detail_id',$this->purchase_detail_id);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('card_id',$this->card_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InputDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
