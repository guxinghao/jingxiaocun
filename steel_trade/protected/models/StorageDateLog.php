<?php

/**
 * This is the biz model class for table "storage_date_log".
 *
 */
class StorageDateLog extends StorageDateLogData
{
	public $start;
	public $input;
	public $output;
	public $end;
	public $pypk;
	public $transfer;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'warehouse'=>array(self::BELONGS_TO,'Warehouse','warehouse_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'warehouse_id' => 'Warehouse',
			'product_id' => 'Product',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'total_output_weight' => 'Total Output Weight',
			'total_output_amount' => 'Total Output Amount',
			'total_input_amount' => 'Total Input Amount',
			'total_input_weight' => 'Total Input Weight',
			'date' => 'Date',
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
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('total_output_weight',$this->total_output_weight,true);
		$criteria->compare('total_output_amount',$this->total_output_amount);
		$criteria->compare('total_input_amount',$this->total_input_amount);
		$criteria->compare('total_input_weight',$this->total_input_weight,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StorageDateLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
