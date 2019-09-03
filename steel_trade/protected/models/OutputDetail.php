<?php

/**
 * This is the biz model class for table "output_detail".
 *
 */
class OutputDetail extends OutputDetailData
{
	public $product;
	public $texture;
	public $rank;
	public $brand;
	public $one_weight;	
	public $surplus;
	//库存汇总用
	public $warehouse_id;
	public $weight_sum;
	public $amount_sum;
	public $total_amount;
	public $total_weight;
	public $total_price;
	public $total_num;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'frmOutput' => array(self::BELONGS_TO, 'FrmOutput', 'frm_output_id'),
			'salesDetails' => array(self::BELONGS_TO, 'SalesDetail', 'sales_detail_id'),
			'returnDetails' => array(self::BELONGS_TO, 'PurchaseReturnDetail', 'sales_detail_id'),
			'storage' => array(self::BELONGS_TO, 'Storage', 'storage_id'),
			'warehouseOutputDetail' => array(self::BELONGS_TO, 'WarehouseOutputDetail', 'warehouse_output_detail_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'frm_output_id' => 'Frm Output',
			'sales_detail_id' => 'Sales Detail',
			'storage_id' => 'Storage',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'warehouse_output_detail_id' => 'Warehouse Output Detail',
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
		$criteria->compare('frm_output_id',$this->frm_output_id);
		$criteria->compare('sales_detail_id',$this->sales_detail_id);
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('warehouse_output_detail_id',$this->warehouse_output_detail_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OutputDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
