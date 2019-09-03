<?php

/**
 * This is the model class for table "warehouse_output_detail".
 *
 * The followings are the available columns in table 'warehouse_output_detail':
 * @property integer $id
 * @property integer $warehouse_output_id
 * @property string $card_no
 * @property integer $product_id
 * @property integer $texture_id
 * @property integer $brand_id
 * @property integer $rank_id
 * @property integer $length
 * @property integer $amount
 * @property string $weight
 * @property string $remark
 *
 * The followings are the available model relations:
 * @property WarehouseOutput $warehouseOutput
 */
class WarehouseOutputDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'warehouse_output_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('warehouse_output_id', 'required'),
			array('warehouse_output_id, product_id, texture_id, brand_id, rank_id, length, amount', 'numerical', 'integerOnly'=>true),
			array('card_no, remark', 'length', 'max'=>45),
			array('weight', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, warehouse_output_id, card_no, product_id, texture_id, brand_id, rank_id, length, amount, weight, remark', 'safe', 'on'=>'search'),
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
			'warehouseOutput' => array(self::BELONGS_TO, 'WarehouseOutput', 'warehouse_output_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'warehouse_output_id' => 'Warehouse Output',
			'card_no' => 'Card No',
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'brand_id' => 'Brand',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'remark' => 'Remark',
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
		$criteria->compare('warehouse_output_id',$this->warehouse_output_id);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WarehouseOutputDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
