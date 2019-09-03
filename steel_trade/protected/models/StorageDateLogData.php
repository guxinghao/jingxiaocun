<?php

/**
 * This is the model class for table "storage_date_log".
 *
 * The followings are the available columns in table 'storage_date_log':
 * @property integer $id
 * @property integer $warehouse_id
 * @property integer $product_id
 * @property integer $amount
 * @property string $weight
 * @property integer $total_output_amount
 * @property string $total_output_weight
 * @property integer $total_input_amount
 * @property string $total_input_weight
 * @property integer $total_pypk_amount
 * @property string $total_pypk_weight
 * @property integer $total_transfer_amount
 * @property string $total_transfer_weight
 * @property integer $is_yidan
 * @property string $type
 * @property string $date
 */
class StorageDateLogData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storage_date_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('warehouse_id, product_id, amount, weight, total_output_amount, total_output_weight, total_input_amount, total_input_weight, total_pypk_amount, total_pypk_weight, total_transfer_amount, total_transfer_weight, date', 'required'),
			array('warehouse_id, product_id, amount, total_output_amount, total_input_amount, total_pypk_amount, total_transfer_amount, is_yidan', 'numerical', 'integerOnly'=>true),
			array('weight, total_output_weight, total_input_weight, total_pypk_weight, total_transfer_weight', 'length', 'max'=>15),
			array('type', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, warehouse_id, product_id, amount, weight, total_output_amount, total_output_weight, total_input_amount, total_input_weight, total_pypk_amount, total_pypk_weight, total_transfer_amount, total_transfer_weight, is_yidan, type, date', 'safe', 'on'=>'search'),
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
			'warehouse_id' => 'Warehouse',
			'product_id' => 'Product',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'total_output_amount' => 'Total Output Amount',
			'total_output_weight' => 'Total Output Weight',
			'total_input_amount' => 'Total Input Amount',
			'total_input_weight' => 'Total Input Weight',
			'total_pypk_amount' => 'Total Pypk Amount',
			'total_pypk_weight' => 'Total Pypk Weight',
			'total_transfer_amount' => 'Total Transfer Amount',
			'total_transfer_weight' => 'Total Transfer Weight',
			'is_yidan' => 'Is Yidan',
			'type' => 'Type',
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
		$criteria->compare('total_output_amount',$this->total_output_amount);
		$criteria->compare('total_output_weight',$this->total_output_weight,true);
		$criteria->compare('total_input_amount',$this->total_input_amount);
		$criteria->compare('total_input_weight',$this->total_input_weight,true);
		$criteria->compare('total_pypk_amount',$this->total_pypk_amount);
		$criteria->compare('total_pypk_weight',$this->total_pypk_weight,true);
		$criteria->compare('total_transfer_amount',$this->total_transfer_amount);
		$criteria->compare('total_transfer_weight',$this->total_transfer_weight,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('type',$this->type,true);
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
