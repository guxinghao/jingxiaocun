<?php

/**
 * This is the model class for table "storage_turnover_view".
 *
 * The followings are the available columns in table 'storage_turnover_view':
 * @property integer $detail_id
 * @property string $type
 * @property integer $warehouse_id
 * @property integer $title_id
 * @property integer $company_id
 * @property string $card_no
 * @property integer $brand_id
 * @property integer $product_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property integer $amount
 * @property string $weight
 * @property integer $created_at
 * @property string $brand_name
 * @property string $product_name
 * @property string $texture_name
 * @property string $rank_name
 * @property string $warehouse_name
 * @property string $title_name
 * @property string $company_name
 */
class StorageTurnoverViewData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storage_turnover_view';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('detail_id, warehouse_id, title_id, company_id, brand_id, product_id, texture_id, rank_id, length, amount, created_at', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>3),
			array('card_no, brand_name, product_name, texture_name, rank_name, warehouse_name, title_name, company_name', 'length', 'max'=>45),
			array('weight', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('detail_id, type, warehouse_id, title_id, company_id, card_no, brand_id, product_id, texture_id, rank_id, length, amount, weight, created_at, brand_name, product_name, texture_name, rank_name, warehouse_name, title_name, company_name', 'safe', 'on'=>'search'),
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
			'detail_id' => 'Detail',
			'type' => 'Type',
			'warehouse_id' => 'Warehouse',
			'title_id' => 'Title',
			'company_id' => 'Company',
			'card_no' => 'Card No',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'created_at' => 'Created At',
			'brand_name' => 'Brand Name',
			'product_name' => 'Product Name',
			'texture_name' => 'Texture Name',
			'rank_name' => 'Rank Name',
			'warehouse_name' => 'Warehouse Name',
			'title_name' => 'Title Name',
			'company_name' => 'Company Name',
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

		$criteria->compare('detail_id',$this->detail_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('texture_name',$this->texture_name,true);
		$criteria->compare('rank_name',$this->rank_name,true);
		$criteria->compare('warehouse_name',$this->warehouse_name,true);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('company_name',$this->company_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StorageTurnoverView the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
