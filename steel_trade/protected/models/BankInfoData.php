<?php

/**
 * This is the model class for table "bank_info".
 *
 * The followings are the available columns in table 'bank_info':
 * @property integer $id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $last_update_at
 * @property integer $last_update_by
 * @property string $bank_name
 * @property string $company_name
 * @property string $bank_number
 * @property string $code
 * @property string $money
 * @property integer $dict_company_id
 *
 * The followings are the available model relations:
 * @property DictCompany $dictCompany
 */
class BankInfoData extends CActiveRecord
{
	public $dict_company_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bank_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at, created_by, last_update_at, last_update_by, dict_company_id', 'numerical', 'integerOnly'=>true),
			array('bank_name, company_name, bank_number, code', 'length', 'max'=>45),
			array('money', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, created_at, created_by, last_update_at, last_update_by, bank_name, company_name, bank_number, code, money, dict_company_id', 'safe', 'on'=>'search'),
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
			'dictCompany' => array(self::BELONGS_TO, 'DictCompany', 'dict_company_id'),
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
			'bank_name' => 'Bank Name',
			'company_name' => 'Company Name',
			'bank_number' => 'Bank Number',
			'code' => 'Code',
			'money' => 'Money',
			'dict_company_id' => 'Dict Company',
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
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('bank_number',$this->bank_number,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('dict_company_id',$this->dict_company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BankInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
