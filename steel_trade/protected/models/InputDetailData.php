<?php

/**
 * This is the model class for table "input_detail".
 *
 * The followings are the available columns in table 'input_detail':
 * @property integer $id
 * @property integer $input_id
 * @property integer $input_amount
 * @property string $input_weight
 * @property integer $purchase_detail_id
 * @property string $cost_price
 * @property integer $product_id
 * @property integer $brand_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property string $card_id
 * @property integer $push_detail_id
 * @property integer $remain_amount
 * @property string $from
 *
 * The followings are the available model relations:
 * @property FrmInput $input
 */
class InputDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'input_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('input_id, purchase_detail_id, product_id, brand_id, texture_id, rank_id', 'required'),
			array('input_id, input_amount, purchase_detail_id, product_id, brand_id, texture_id, rank_id, length, push_detail_id, remain_amount', 'numerical', 'integerOnly'=>true),
			array('input_weight', 'length', 'max'=>15),
			array('cost_price', 'length', 'max'=>11),
			array('card_id, from', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, input_id, input_amount, input_weight, purchase_detail_id, cost_price, product_id, brand_id, texture_id, rank_id, length, card_id, push_detail_id, remain_amount, from', 'safe', 'on'=>'search'),
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
			'input' => array(self::BELONGS_TO, 'FrmInput', 'input_id'),
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
			'push_detail_id' => 'Push Detail',
			'remain_amount' => 'Remain Amount',
			'from' => 'From',
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
		$criteria->compare('push_detail_id',$this->push_detail_id);
		$criteria->compare('remain_amount',$this->remain_amount);
		$criteria->compare('from',$this->from,true);

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
