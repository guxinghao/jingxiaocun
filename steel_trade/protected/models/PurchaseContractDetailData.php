<?php

/**
 * This is the model class for table "purchase_contract_detail".
 *
 * The followings are the available columns in table 'purchase_contract_detail':
 * @property integer $id
 * @property string $price
 * @property integer $amount
 * @property string $weight
 * @property integer $purchased_amount
 * @property string $purchased_weight
 * @property integer $purchase_contract_id
 * @property integer $product_id
 * @property integer $brand_id
 * @property string $texture_id
 * @property string $rank_id
 * @property integer $length
 *
 * The followings are the available model relations:
 * @property FrmPurchaseContract $purchaseContract
 */
class PurchaseContractDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_contract_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('purchase_contract_id, product_id, brand_id, texture_id, rank_id', 'required'),
			array('amount, purchased_amount, purchase_contract_id, product_id, brand_id, length', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>11),
			array('weight, purchased_weight', 'length', 'max'=>15),
			array('texture_id, rank_id', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, price, amount, weight, purchased_amount, purchased_weight, purchase_contract_id, product_id, brand_id, texture_id, rank_id, length', 'safe', 'on'=>'search'),
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
		$criteria->compare('texture_id',$this->texture_id,true);
		$criteria->compare('rank_id',$this->rank_id,true);
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
}
