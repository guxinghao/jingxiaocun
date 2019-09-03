<?php

/**
 * This is the biz model class for table "voucher_detail".
 *
 */
class VoucherDetail extends VoucherDetailData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'voucher' => array(self::BELONGS_TO,'Voucher','voucher_id'),
			'company'=>array(self::BELONGS_TO,'DictCompany','company_id'),//客户
			'baseform'=>array(self::BELONGS_TO,'CommonForms','common_id'),
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
