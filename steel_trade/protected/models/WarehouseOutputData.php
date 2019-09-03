<?php

/**
 * This is the model class for table "warehouse_output".
 *
 * The followings are the available columns in table 'warehouse_output':
 * @property integer $id
 * @property integer $frm_send_id
 * @property string $output_no
 * @property string $car_no
 * @property integer $title_id
 * @property string $output_type
 * @property integer $customer_id
 * @property string $customer_name
 * @property string $remark
 * @property integer $created_at
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property WarehouseOutputDetail[] $warehouseOutputDetails
 */
class WarehouseOutputData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'warehouse_output';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('output_no', 'required'),
			array('frm_send_id, title_id, customer_id, created_at, status', 'numerical', 'integerOnly'=>true),
			array('output_no, car_no, output_type, customer_name, remark', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, frm_send_id, output_no, car_no, title_id, output_type, customer_id, customer_name, remark, created_at, status', 'safe', 'on'=>'search'),
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
			'warehouseOutputDetails' => array(self::HAS_MANY, 'WarehouseOutputDetail', 'warehouse_output_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'frm_send_id' => 'Frm Send',
			'output_no' => 'Output No',
			'car_no' => 'Car No',
			'title_id' => 'Title',
			'output_type' => 'Output Type',
			'customer_id' => 'Customer',
			'customer_name' => 'Customer Name',
			'remark' => 'Remark',
			'created_at' => 'Created At',
			'status' => 'Status',
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
		$criteria->compare('frm_send_id',$this->frm_send_id);
		$criteria->compare('output_no',$this->output_no,true);
		$criteria->compare('car_no',$this->car_no,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('output_type',$this->output_type,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WarehouseOutput the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
