<?php

/**
 * This is the model class for table "sales_return_detail".
 *
 * The followings are the available columns in table 'sales_return_detail':
 * @property integer $id
 * @property string $card_no
 * @property integer $sale_detail_id
 * @property integer $return_amount
 * @property string $return_weight
 * @property string $return_price
 * @property integer $sales_return_id
 * @property integer $product_id
 * @property integer $brand_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property integer $input_amount
 * @property string $input_weight
 * @property integer $fix_amount
 * @property string $fix_weight
 * @property string $fix_price
 *
 * The followings are the available model relations:
 * @property FrmSalesReturn $salesReturn
 */
class SalesReturnDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sales_return_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sales_return_id, product_id, brand_id, texture_id, rank_id', 'required'),
			array('sale_detail_id, return_amount, sales_return_id, product_id, brand_id, texture_id, rank_id, length, input_amount, fix_amount', 'numerical', 'integerOnly'=>true),
			array('card_no', 'length', 'max'=>45),
			array('return_weight, input_weight, fix_weight, fix_price', 'length', 'max'=>15),
			array('return_price', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, card_no, sale_detail_id, return_amount, return_weight, return_price, sales_return_id, product_id, brand_id, texture_id, rank_id, length, input_amount, input_weight, fix_amount, fix_weight, fix_price', 'safe', 'on'=>'search'),
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
			'salesReturn' => array(self::BELONGS_TO, 'FrmSalesReturn', 'sales_return_id'),
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
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'fix_amount' => 'Fix Amount',
			'fix_weight' => 'Fix Weight',
			'fix_price' => 'Fix Price',
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
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('fix_amount',$this->fix_amount);
		$criteria->compare('fix_weight',$this->fix_weight,true);
		$criteria->compare('fix_price',$this->fix_price,true);

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
