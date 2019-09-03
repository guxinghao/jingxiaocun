<?php

/**
 * This is the biz model class for table "warehouse_output_detail".
 *
 */
class WarehouseOutputDetail extends WarehouseOutputDetailData
{
	public $product;
	public $rank;
	public $texture;
	public $brand;
	
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
			'warehouse_output_detailcol' => 'Warehouse Output Detailcol',
			'warehouse_output_id' => 'Warehouse Output',
			'card_id' => 'Card',
			'product' => 'Product',
			'texture' => 'Texture',
			'rand' => 'Rand',
			'brand' => 'Brand',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'real_weight' => 'Real Weight',
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
		$criteria->compare('warehouse_output_detailcol',$this->warehouse_output_detailcol,true);
		$criteria->compare('warehouse_output_id',$this->warehouse_output_id);
		$criteria->compare('card_id',$this->card_id,true);
		$criteria->compare('product',$this->product);
		$criteria->compare('texture',$this->texture);
		$criteria->compare('rand',$this->rand);
		$criteria->compare('brand',$this->brand);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('real_weight',$this->real_weight,true);
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
