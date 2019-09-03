<?php

/**
 * This is the model class for table "dict_goods".
 *
 * The followings are the available columns in table 'dict_goods':
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property integer $length
 * @property integer $last_update
 * @property string $unit_weight
 * @property string $product_std
 * @property string $brand_std
 * @property string $texture_std
 * @property string $rank_std
 */
class DictGoodsData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dict_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('length, last_update', 'numerical', 'integerOnly'=>true),
			array('name, short_name', 'length', 'max'=>100),
			array('unit_weight', 'length', 'max'=>15),
			array('product_std, brand_std, texture_std, rank_std', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, short_name, length, last_update, unit_weight, product_std, brand_std, texture_std, rank_std', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'short_name' => 'Short Name',
			'length' => 'Length',
			'last_update' => 'Last Update',
			'unit_weight' => 'Unit Weight',
			'product_std' => 'Product Std',
			'brand_std' => 'Brand Std',
			'texture_std' => 'Texture Std',
			'rank_std' => 'Rank Std',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('unit_weight',$this->unit_weight,true);
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('rank_std',$this->rank_std,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
