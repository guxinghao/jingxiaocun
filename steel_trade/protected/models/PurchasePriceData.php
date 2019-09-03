<?php

/**
 * This is the model class for table "purchase_price".
 *
 * The followings are the available columns in table 'purchase_price':
 * @property integer $id
 * @property string $brand_std
 * @property string $product_std
 * @property string $texture_std
 * @property string $rank_range
 * @property integer $length
 * @property string $price
 * @property string $price_date
 */
class PurchasePriceData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_price';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('length', 'numerical', 'integerOnly'=>true),
			array('brand_std, product_std, texture_std, rank_range, price_date', 'length', 'max'=>45),
			array('price', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, brand_std, product_std, texture_std, rank_range, length, price, price_date', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'brand_std' => 'Brand Std',
			'product_std' => 'Product Std',
			'texture_std' => 'Texture Std',
			'rank_range' => 'Rank Range',
			'length' => 'Length',
			'price' => 'Price',
			'price_date' => 'Price Date',
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
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('rank_range',$this->rank_range,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('price_date',$this->price_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchasePrice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
