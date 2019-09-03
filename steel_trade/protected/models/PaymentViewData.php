<?php

/**
 * This is the model class for table "payment_view".
 *
 * The followings are the available columns in table 'payment_view':
 * @property integer $common_id
 * @property integer $main_id
 * @property integer $amount
 * @property string $weight
 * @property string $fee
 * @property integer $is_yidan
 * @property string $form_type
 * @property string $form_sn
 * @property integer $created_at
 * @property integer $created_by
 * @property string $created_by_nickname
 * @property string $form_time
 * @property string $form_status
 * @property integer $owned_by
 * @property string $owned_by_nickname
 * @property string $customer_name
 * @property string $customer_short_name
 * @property string $title_name
 */
class PaymentViewData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payment_view';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('common_id, main_id, amount, is_yidan, created_at, created_by, owned_by', 'numerical', 'integerOnly'=>true),
			array('weight', 'length', 'max'=>15),
			array('fee', 'length', 'max'=>11),
			array('form_type, form_sn, created_by_nickname, owned_by_nickname, customer_short_name, title_name', 'length', 'max'=>45),
			array('form_status', 'length', 'max'=>20),
			array('customer_name', 'length', 'max'=>50),
			array('form_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('common_id, main_id, amount, weight, fee, is_yidan, form_type, form_sn, created_at, created_by, created_by_nickname, form_time, form_status, owned_by, owned_by_nickname, customer_name, customer_short_name, title_name', 'safe', 'on'=>'search'),
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
			'common_id' => 'Common',
			'main_id' => 'Main',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'fee' => 'Fee',
			'is_yidan' => 'Is Yidan',
			'form_type' => 'Form Type',
			'form_sn' => 'Form Sn',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'created_by_nickname' => 'Created By Nickname',
			'form_time' => 'Form Time',
			'form_status' => 'Form Status',
			'owned_by' => 'Owned By',
			'owned_by_nickname' => 'Owned By Nickname',
			'customer_name' => 'Customer Name',
			'customer_short_name' => 'Customer Short Name',
			'title_name' => 'Title Name',
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

		$criteria->compare('common_id',$this->common_id);
		$criteria->compare('main_id',$this->main_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('form_type',$this->form_type,true);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_by_nickname',$this->created_by_nickname,true);
		$criteria->compare('form_time',$this->form_time,true);
		$criteria->compare('form_status',$this->form_status,true);
		$criteria->compare('owned_by',$this->owned_by);
		$criteria->compare('owned_by_nickname',$this->owned_by_nickname,true);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_short_name',$this->customer_short_name,true);
		$criteria->compare('title_name',$this->title_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentView the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
