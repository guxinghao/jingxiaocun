<?php

/**
 * This is the model class for table "dict_bank_info".
 *
 * The followings are the available columns in table 'dict_bank_info':
 * @property integer $id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $last_update_at
 * @property integer $last_update_by
 * @property integer $dict_title_id
 * @property string $bank_name
 * @property string $dict_name
 * @property string $bank_number
 * @property string $code
 * @property string $money
 * @property string $initial_money
 * @property integer $bank_level
 * @property integer $priority
 * @property string $number
 * @property integer $voucher_type
 */
class DictBankInfoData extends CActiveRecord
{
	public $dict_title_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dict_bank_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at, created_by, last_update_at, last_update_by, dict_title_id, bank_level, priority, voucher_type', 'numerical', 'integerOnly'=>true),
			array('bank_name, dict_name, bank_number, code, number', 'length', 'max'=>45),
			array('money, initial_money', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, created_at, created_by, last_update_at, last_update_by, dict_title_id, bank_name, dict_name, bank_number, code, money, initial_money, bank_level, priority, number, voucher_type', 'safe', 'on'=>'search'),
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
				'dictTitle' => array(self::BELONGS_TO, 'DictTitle', 'dict_title_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'last_update_at' => 'Last Update At',
			'last_update_by' => 'Last Update By',
			'dict_title_id' => 'Dict Title',
			'bank_name' => 'Bank Name',
			'dict_name' => 'Dict Name',
			'bank_number' => 'Bank Number',
			'code' => 'Code',
			'money' => 'Money',
			'initial_money' => 'Initial Money',
			'bank_level' => 'Bank Level',
			'priority' => 'Priority',
			'number' => 'Number',
			'voucher_type' => 'Voucher Type',
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
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('last_update_at',$this->last_update_at);
		$criteria->compare('last_update_by',$this->last_update_by);
		$criteria->compare('dict_title_id',$this->dict_title_id);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('dict_name',$this->dict_name,true);
		$criteria->compare('bank_number',$this->bank_number,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('initial_money',$this->initial_money,true);
		$criteria->compare('bank_level',$this->bank_level);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('voucher_type',$this->voucher_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictBankInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
