<?php

/**
 * This is the model class for table "frm_send".
 *
 * The followings are the available columns in table 'frm_send':
 * @property integer $id
 * @property string $auth_type
 * @property string $auth_text
 * @property string $auth_code
 * @property integer $is_complete
 * @property integer $output_amount
 * @property string $output_weight
 * @property integer $amount
 * @property string $weight
 * @property integer $frm_sales_id
 * @property string $status
 * @property integer $start_time
 * @property integer $end_time
 * @property string $is_return
 *
 * The followings are the available model relations:
 * @property FrmSendDetail[] $frmSendDetails
 */
class FrmSendData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_send';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('frm_sales_id', 'required'),
			array('is_complete, output_amount, amount, frm_sales_id, start_time, end_time', 'numerical', 'integerOnly'=>true),
			array('auth_type, auth_code, status, is_return', 'length', 'max'=>45),
			array('auth_text', 'length', 'max'=>500),
			array('output_weight, weight', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, auth_type, auth_text, auth_code, is_complete, output_amount, output_weight, amount, weight, frm_sales_id, status, start_time, end_time, is_return', 'safe', 'on'=>'search'),
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
			'frmSendDetails' => array(self::HAS_MANY, 'FrmSendDetail', 'frm_send_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'auth_type' => 'Auth Type',
			'auth_text' => 'Auth Text',
			'auth_code' => 'Auth Code',
			'is_complete' => 'Is Complete',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'frm_sales_id' => 'Frm Sales',
			'status' => 'Status',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'is_return' => 'Is Return',
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
		$criteria->compare('auth_type',$this->auth_type,true);
		$criteria->compare('auth_text',$this->auth_text,true);
		$criteria->compare('auth_code',$this->auth_code,true);
		$criteria->compare('is_complete',$this->is_complete);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('frm_sales_id',$this->frm_sales_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('start_time',$this->start_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('is_return',$this->is_return,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
