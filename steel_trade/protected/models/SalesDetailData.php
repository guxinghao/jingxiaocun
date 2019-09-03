<?php

/**
 * This is the model class for table "sales_detail".
 *
 * The followings are the available columns in table 'sales_detail':
 * @property integer $id
 * @property string $price
 * @property string $bonus_price
 * @property integer $amount
 * @property string $weight
 * @property integer $send_amount
 * @property string $send_weight
 * @property integer $output_amount
 * @property string $output_weight
 * @property integer $warehouse_output_amount
 * @property string $warehouse_output_weight
 * @property integer $product_id
 * @property integer $brand_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property integer $frm_sales_id
 * @property string $card_id
 * @property integer $is_related
 * @property integer $purchased_amount
 * @property string $purchased_weight
 * @property integer $need_purchase_amount
 * @property integer $pre_amount
 * @property string $pre_weight
 *
 * The followings are the available model relations:
 * @property HighOpen[] $highOpens
 * @property SalesInvoiceDetail[] $salesInvoiceDetails
 */
class SalesDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sales_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('amount, send_amount, output_amount, warehouse_output_amount, product_id, brand_id, texture_id, rank_id, length, frm_sales_id, is_related, purchased_amount, need_purchase_amount, pre_amount', 'numerical', 'integerOnly'=>true),
			array('price, bonus_price', 'length', 'max'=>11),
			array('weight, send_weight, output_weight, warehouse_output_weight, purchased_weight, pre_weight', 'length', 'max'=>15),
			array('card_id', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, price, bonus_price, amount, weight, send_amount, send_weight, output_amount, output_weight, warehouse_output_amount, warehouse_output_weight, product_id, brand_id, texture_id, rank_id, length, frm_sales_id, card_id, is_related, purchased_amount, purchased_weight, need_purchase_amount, pre_amount, pre_weight', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'highOpens' => array(self::HAS_MANY, 'HighOpen', 'sales_detail_id'),
			'salesInvoiceDetails' => array(self::HAS_MANY, 'SalesInvoiceDetail', 'sales_detail_id'),
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
			'bonus_price' => 'Bonus Price',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'send_amount' => 'Send Amount',
			'send_weight' => 'Send Weight',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'warehouse_output_amount' => 'Warehouse Output Amount',
			'warehouse_output_weight' => 'Warehouse Output Weight',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'frm_sales_id' => 'Frm Sales',
			'card_id' => 'Card',
			'is_related' => 'Is Related',
			'purchased_amount' => 'Purchased Amount',
			'purchased_weight' => 'Purchased Weight',
			'need_purchase_amount' => 'Need Purchase Amount',
			'pre_amount' => 'Pre Amount',
			'pre_weight' => 'Pre Weight',
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
		$criteria->compare('bonus_price',$this->bonus_price,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('send_amount',$this->send_amount);
		$criteria->compare('send_weight',$this->send_weight,true);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight,true);
		$criteria->compare('warehouse_output_amount',$this->warehouse_output_amount);
		$criteria->compare('warehouse_output_weight',$this->warehouse_output_weight,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('frm_sales_id',$this->frm_sales_id);
		$criteria->compare('card_id',$this->card_id,true);
		$criteria->compare('is_related',$this->is_related);
		$criteria->compare('purchased_amount',$this->purchased_amount);
		$criteria->compare('purchased_weight',$this->purchased_weight,true);
		$criteria->compare('need_purchase_amount',$this->need_purchase_amount);
		$criteria->compare('pre_amount',$this->pre_amount);
		$criteria->compare('pre_weight',$this->pre_weight);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalesDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
