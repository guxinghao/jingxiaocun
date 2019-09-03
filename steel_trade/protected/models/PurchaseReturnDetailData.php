<?php

/**
 * This is the model class for table "purchase_return_detail".
 *
 * The followings are the available columns in table 'purchase_return_detail':
 * @property integer $id
 * @property integer $card_no
 * @property integer $return_amount
 * @property string $return_weight
 * @property string $return_price
 * @property integer $purchase_return_id
 * @property integer $product_id
 * @property integer $brand_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property integer $send_amount
 * @property string $send_weight
 * @property integer $output_amount
 * @property string $output_weight
 *
 * The followings are the available model relations:
 * @property FrmPurchaseReturn $purchaseReturn
 */
class PurchaseReturnDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_return_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('card_no, return_amount, return_weight, return_price, purchase_return_id, product_id, brand_id, texture_id, rank_id', 'required'),
			array('card_no, return_amount, purchase_return_id, product_id, brand_id, texture_id, rank_id, length, send_amount, output_amount', 'numerical', 'integerOnly'=>true),
			array('return_weight, send_weight, output_weight', 'length', 'max'=>15),
			array('return_price', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, card_no, return_amount, return_weight, return_price, purchase_return_id, product_id, brand_id, texture_id, rank_id, length, send_amount, send_weight, output_amount, output_weight', 'safe', 'on'=>'search'),
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
			'purchaseReturn' => array(self::BELONGS_TO, 'FrmPurchaseReturn', 'purchase_return_id'),
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
			'send_amount' => 'Send Amount',
			'send_weight' => 'Send Weight',
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
		$criteria->compare('card_no',$this->card_no);
		$criteria->compare('return_amount',$this->return_amount);
		$criteria->compare('return_weight',$this->return_weight,true);
		$criteria->compare('return_price',$this->return_price,true);
		$criteria->compare('purchase_return_id',$this->purchase_return_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('send_amount',$this->send_amount);
		$criteria->compare('send_weight',$this->send_weight,true);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight,true);

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
