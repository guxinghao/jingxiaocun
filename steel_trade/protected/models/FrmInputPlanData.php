<?php

/**
 * This is the model class for table "frm_input_plan".
 *
 * The followings are the available columns in table 'frm_input_plan':
 * @property integer $id
 * @property string $input_type
 * @property integer $purchase_id
 * @property integer $input_date
 * @property integer $warehouse_id
 * @property integer $input_company
 * @property integer $input_status
 * @property integer $owner_company
 * @property string $ship_no
 * @property string $form_sn
 * @property integer $input_time
 */
class FrmInputPlanData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_input_plan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('purchase_id, input_date, warehouse_id, input_company, input_status, owner_company, input_time', 'numerical', 'integerOnly'=>true),
			array('input_type, form_sn', 'length', 'max'=>45),
			array('ship_no', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, input_type, purchase_id, input_date, warehouse_id, input_company, input_status, owner_company, ship_no, form_sn, input_time', 'safe', 'on'=>'search'),
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
			'input_type' => 'Input Type',
			'purchase_id' => 'Purchase',
			'input_date' => 'Input Date',
			'warehouse_id' => 'Warehouse',
			'input_company' => 'Input Company',
			'input_status' => 'Input Status',
			'owner_company' => 'Owner Company',
			'ship_no' => 'Ship No',
			'form_sn' => 'Form Sn',
			'input_time' => 'Input Time',
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
		$criteria->compare('input_type',$this->input_type,true);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('input_date',$this->input_date);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('input_company',$this->input_company);
		$criteria->compare('input_status',$this->input_status);
		$criteria->compare('owner_company',$this->owner_company);
		$criteria->compare('ship_no',$this->ship_no,true);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('input_time',$this->input_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmInputPlan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
