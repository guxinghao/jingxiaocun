<?php

/**
 * This is the model class for table "quoted_detail".
 *
 * The followings are the available columns in table 'quoted_detail':
 * @property integer $id
 * @property string $product_std
 * @property string $texture_std
 * @property string $brand_std
 * @property string $rank_std
 * @property integer $length
 * @property string $area
 * @property string $price
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $last_update
 * @property string $type
 * @property string $price_date
 * @property integer $prefecture
 * @property integer $last_update_by
 * @property integer $warehouse_id
 * @property integer $pushed
 */
class QuotedDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quoted_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_std, texture_std, rank_std, created_at, created_by, price_date', 'required'),
			array('length, created_at, created_by, last_update, prefecture, last_update_by, warehouse_id, pushed', 'numerical', 'integerOnly'=>true),
			array('product_std, texture_std, brand_std, rank_std, area, type', 'length', 'max'=>45),
			array('price', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_std, texture_std, brand_std, rank_std, length, area, price, created_at, created_by, last_update, type, price_date, prefecture, last_update_by, warehouse_id, pushed', 'safe', 'on'=>'search'),
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
			'product_std' => 'Product Std',
			'texture_std' => 'Texture Std',
			'brand_std' => 'Brand Std',
			'rank_std' => 'Rank Std',
			'length' => 'Length',
			'area' => 'Area',
			'price' => 'Price',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'last_update' => 'Last Update',
			'type' => 'Type',
			'price_date' => 'Price Date',
			'prefecture' => 'Prefecture',
			'last_update_by' => 'Last Update By',
			'warehouse_id' => 'Warehouse',
			'pushed' => 'Pushed',
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
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('rank_std',$this->rank_std,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('price_date',$this->price_date,true);
		$criteria->compare('prefecture',$this->prefecture);
		$criteria->compare('last_update_by',$this->last_update_by);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('pushed',$this->pushed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotedDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
