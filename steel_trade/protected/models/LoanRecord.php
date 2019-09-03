<?php

/** 
 * This is the biz model class for table "loan_record". 
 * 
 */ 
class LoanRecord extends LoanRecordData
{ 
     public static $accountDirection = array('accounted' => "入账", 'out_account' => "出账"); //出入账方向
     public static $amountType = array('principal' => "本金", 'interest' => "利息"); //金额类型
     public static $hasIous = array('0' => "无借据", '1' => "有借据"); //是否有借据
     
    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array( 
        		'shortLoan' => array(self::BELONGS_TO, 'ShortLoan', 'short_loan_id'), //短期借贷
        		'dictBank' => array(self::BELONGS_TO, 'DictBankInfo', 'dict_bank_id'), //公司账户
        		'bank' => array(self::BELONGS_TO, 'BankInfo', 'bank_id'), //对方账户
        		'operator' => array(self::BELONGS_TO, 'User', 'created_by'), //操作员
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'short_loan_id' => 'Short Loan',
            'account_direction' => 'Account Direction',
            'dict_bank_id' => 'Dict Bank',
            'bank_id' => 'Bank',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'amount_type' => 'Amount Type',
            'amount' => 'Amount',
            'has_Ious' => 'Has Ious',
            'comment' => 'Comment',
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
        $criteria->compare('short_loan_id',$this->short_loan_id);
        $criteria->compare('account_direction',$this->account_direction,true);
        $criteria->compare('dict_bank_id',$this->dict_bank_id);
        $criteria->compare('bank_id',$this->bank_id);
        $criteria->compare('created_at',$this->created_at);
        $criteria->compare('created_by',$this->created_by);
        $criteria->compare('amount_type',$this->amount_type,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('has_Ious',$this->has_Ious);
        $criteria->compare('comment',$this->comment,true);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
        /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return LoanRecord the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
    
    public static function createRecord($data, $short_loan_id) 
    {
    	$model = new LoanRecord();
		$model->short_loan_id = intval($short_loan_id); //短期借贷id
    	$model->account_direction = $data->account_direction; //出/入账方向
    	$model->dict_bank_id = intval($data->dict_bank_id); //公司账户
    	$model->bank_id = intval($data->bank_id); //对方账户
    	$model->created_at = $data->created_at; //发生日期
    	$model->created_by = intval($data->created_by); //操作人
    	$model->amount_type = $data->amount_type; //金额类型
    	$model->amount = $data->amount; //金额
    	$model->has_Ious = intval($data->has_Ious); //是否有借据
    	$model->comment = $data->comment; //备注
        $model->reach_at = $data->reach_at; //到账日期

    	if (!$model->insert()) return false;
    	return $model;
    }
    
    public static function updateRecord($id, $data) 
    {
    	$model = LoanRecord::model()->findByPK($id);
//     	$model->short_loan_id = $short_loan_id; //短期借贷id
    	$model->account_direction = $data->account_direction; //出/入账方向
    	$model->dict_bank_id = $data->dict_bank_id; //公司账户
    	$model->bank_id = $data->bank_id; //对方账户
    	$model->created_at = $data->created_at; //发生日期
    	$model->created_by = $data->created_by; //操作人
    	$model->amount_type = $data->amount_type; //金额类型
    	$model->amount = $data->amount; //金额
    	$model->has_Ious = $data->has_Ious; //是否有借据
    	$model->comment = $data->comment; //备注
        $model->reach_at = $data->reach_at; //到账日期
    	if (!$model->update()) return false;
    	return $model;
    }

    /**
     * 账户出入账
     * @param  integer $id  出入账记录id
     * @param  string $type 操作类型 出入账|取消出入账 confirm|cancel
     * @return boolean      操作结果
     */
    public static function updateAccount($id, $type = 'confirm') 
    {
        $record = LoanRecord::model()->findByPK($id);
        $model = $record->shortLoan; //短期借贷
        $dict_bank = $record->dictBank; //公司账户
        $operation = "";

        if ($type == 'confirm') {
            switch ($record->account_direction) {
                case 'accounted': //入账
                    $account_type = 'in';
                    $operation = "入账";
                    $dict_bank->money += $record->amount;
                    if ($record->amount_type == 'principal') //本金
                    {
                        $model->accounted_principal += $record->amount;
                        $model->balance += $record->amount;
                    } 
                    elseif ($record->amount_type == 'interest') //利息
                    {
                        $model->accounted_interest += $record->amount;
                    }
                    break;
                case 'out_account': //出账
                    $account_type = "out";
                    $operation = "出账";
                    $dict_bank->money -= $record->amount;
                    if ($record->amount_type == 'principal') //本金
                    {
                        $model->out_account_principal += $record->amount;
                        $model->balance -= $record->amount;
                    }
                    elseif ($record->amount_type == 'interest') //利息
                    {
                        $model->out_account_interest += $record->amount;
                    }
                    break;
                default: 
                    return false;
                    break;
            }
            if (!$record->update() || !$model->update()) return false; 
            //入账日志
            $billLog = new FrmBillLog();
            $billLog->form_id = $model->baseform->id;
            $billLog->form_sn = $model->baseform->form_sn;
            $billLog->title_id = $model->title_id;
            $billLog->dict_bank_id = $record->dict_bank_id;
            $billLog->company_id = $model->company_id;
            $billLog->bank_id = $record->bank_id;
            $billLog->account_type = $account_type;
            $billLog->fee = $record->amount;
            $billLog->bill_type = 6;
            $billLog->account_by = $record->created_by;
            $billLog->created_at = $record->created_at;
            $billLog->reach_at = $record->reach_at;
            $billLog->insert();
        } else {
            switch ($record->account_direction) {
                case 'accounted': //取消入账
                    $operation = "取消入账";
                    $dict_bank->money -= $record->amount;
                    if ($record->amount_type == 'principal') //本金
                    {
                        $model->accounted_principal -= $record->amount;
                        $model->balance -= $record->amount;
                    } 
                    elseif ($record->amount_type == 'interest') //利息
                    {
                        $model->accounted_interest -= $record->amount;
                    }
                    break;
                case 'out_account': //取消出账
                    $operation = "取消出账";
                    $dict_bank->money += $record->amount;
                    if ($record->amount_type == 'principal') //本金
                    {
                        $model->out_account_principal -= $record->amount;
                        $model->balance += $record->amount;
                    }
                    elseif ($record->amount_type == 'interest') //利息
                    {
                        $model->out_account_interest -= $record->amount;
                    }
                    break;
                default: 
                    return false;
                    break;
            }
            if (!$record->update() || !$model->update()) return false; 
            //删除入账日志
            $billLog = FrmBillLog::model()->find('bill_type = 6 AND form_id = :form_id', array(':form_id' => $record->id));
            if ($billLog) $billLog->delete();
        }
        return $operation;
    }
} 
