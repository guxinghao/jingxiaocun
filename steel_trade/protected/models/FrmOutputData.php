<?php

/**
 * This is the model class for table "frm_output".
 *
 * The followings are the available columns in table 'frm_output':
 * @property integer $id
 * @property integer $output_amount
 * @property string $output_weight
 * @property string $output_type
 * @property integer $frm_sales_id
 * @property string $from
 * @property integer $push_id
 * @property integer $input_status
 * @property integer $is_return
 * @property integer $output_at
 * @property integer $output_by
 *
 * The followings are the available model relations:
 * @property OutputDetail[] $outputDetails
 */
class FrmOutputData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_output';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('output_amount, frm_sales_id, push_id, input_status, is_return, output_at, output_by', 'numerical', 'integerOnly'=>true),
			array('output_weight', 'length', 'max'=>15),
			array('output_type, from', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, output_amount, output_weight, output_type, frm_sales_id, from, push_id, input_status, is_return, output_at, output_by', 'safe', 'on'=>'search'),
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
			'outputDetails' => array(self::HAS_MANY, 'OutputDetail', 'frm_output_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'output_type' => 'Output Type',
			'frm_sales_id' => 'Frm Sales',
			'from' => 'From',
			'push_id' => 'Push',
			'input_status' => 'Input Status',
			'is_return' => 'Is Return',
			'output_at' => 'Output At',
			'output_by' => 'Output By',
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
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight,true);
		$criteria->compare('output_type',$this->output_type,true);
		$criteria->compare('frm_sales_id',$this->frm_sales_id);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('push_id',$this->push_id);
		$criteria->compare('input_status',$this->input_status);
		$criteria->compare('is_return',$this->is_return);
		$criteria->compare('output_at',$this->output_at);
		$criteria->compare('output_by',$this->output_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmOutput the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
