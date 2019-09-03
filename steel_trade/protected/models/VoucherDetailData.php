<?php

/**
 * This is the model class for table "voucher_detail".
 *
 * The followings are the available columns in table 'voucher_detail':
 * @property integer $id
 * @property integer $voucher_id
 * @property string $comment
 * @property string $account_code
 * @property string $account_name
 * @property string $debit
 * @property string $credit
 * @property string $amount
 * @property string $unit
 * @property integer $company_id
 */
class VoucherDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'voucher_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('voucher_id, company_id', 'numerical', 'integerOnly'=>true),
			array('comment, account_code, account_name', 'length', 'max'=>255),
			array('debit, credit', 'length', 'max'=>13),
			array('amount', 'length', 'max'=>15),
			array('unit', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, voucher_id, comment, account_code, account_name, debit, credit, amount, unit, company_id', 'safe', 'on'=>'search'),
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
			'voucher_id' => 'Voucher',
			'comment' => 'Comment',
			'account_code' => 'Account Code',
			'account_name' => 'Account Name',
			'debit' => 'Debit',
			'credit' => 'Credit',
			'amount' => 'Amount',
			'unit' => 'Unit',
			'company_id' => 'Company',
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
		$criteria->compare('voucher_id',$this->voucher_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('account_code',$this->account_code,true);
		$criteria->compare('account_name',$this->account_name,true);
		$criteria->compare('debit',$this->debit,true);
		$criteria->compare('credit',$this->credit,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('company_id',$this->company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VoucherDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
