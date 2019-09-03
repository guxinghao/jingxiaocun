<?php

/** 
 * This is the model class for table "short_loan". 
 * 
 * The followings are the available columns in table 'short_loan': 
 * @property integer $id
 * @property integer $title_id
 * @property integer $company_id
 * @property string $lending_direction
 * @property string $amount
 * @property string $interest_rate
 * @property string $accounted_principal
 * @property string $accounted_interest
 * @property string $out_account_principal
 * @property string $out_account_interest
 * @property string $balance
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $has_Ious
 * @property integer $performance_status
 * @property integer $account_at
 * @property integer $account_by
 */ 
class ShortLoanData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'short_loan'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('id, lending_direction, start_time, end_time', 'required'),
            array('id, title_id, company_id, start_time, end_time, has_Ious, performance_status, account_at, account_by', 'numerical', 'integerOnly'=>true),
            array('lending_direction', 'length', 'max'=>45),
            array('amount, accounted_principal, accounted_interest, out_account_principal, out_account_interest, balance', 'length', 'max'=>11),
            array('interest_rate', 'length', 'max'=>7),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, title_id, company_id, lending_direction, amount, interest_rate, accounted_principal, accounted_interest, out_account_principal, out_account_interest, balance, start_time, end_time, has_Ious, performance_status, account_at, account_by', 'safe', 'on'=>'search'),
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
            'title_id' => 'Title',
            'company_id' => 'Company',
            'lending_direction' => 'Lending Direction',
            'amount' => 'Amount',
            'interest_rate' => 'Interest Rate',
            'accounted_principal' => 'Accounted Principal',
            'accounted_interest' => 'Accounted Interest',
            'out_account_principal' => 'Out Account Principal',
            'out_account_interest' => 'Out Account Interest',
            'balance' => 'Balance',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'has_Ious' => 'Has Ious',
            'performance_status' => 'Performance Status',
            'account_at' => 'Account At',
            'account_by' => 'Account By',
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
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('lending_direction',$this->lending_direction,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('interest_rate',$this->interest_rate,true);
        $criteria->compare('accounted_principal',$this->accounted_principal,true);
        $criteria->compare('accounted_interest',$this->accounted_interest,true);
        $criteria->compare('out_account_principal',$this->out_account_principal,true);
        $criteria->compare('out_account_interest',$this->out_account_interest,true);
        $criteria->compare('balance',$this->balance,true);
        $criteria->compare('start_time',$this->start_time);
        $criteria->compare('end_time',$this->end_time);
        $criteria->compare('has_Ious',$this->has_Ious);
        $criteria->compare('performance_status',$this->performance_status);
        $criteria->compare('account_at',$this->account_at);
        $criteria->compare('account_by',$this->account_by);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return ShortLoan the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 