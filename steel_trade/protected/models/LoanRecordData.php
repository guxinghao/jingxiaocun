<?php

/** 
 * This is the model class for table "loan_record". 
 * 
 * The followings are the available columns in table 'loan_record': 
 * @property integer $id
 * @property integer $short_loan_id
 * @property string $account_direction
 * @property integer $dict_bank_id
 * @property integer $bank_id
 * @property integer $created_at
 * @property integer $created_by
 * @property string $amount_type
 * @property string $amount
 * @property integer $has_Ious
 * @property string $comment
 * @property integer $reach_at
 */ 
class LoanRecordData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'loan_record'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
//             array('short_loan_id, account_direction, created_at, created_by, amount_type', 'required'),
//             array('short_loan_id, dict_bank_id, bank_id, created_at, created_by, has_Ious, reach_at', 'numerical', 'integerOnly'=>true),
//             array('account_direction, amount_type', 'length', 'max'=>45),
//             array('amount', 'length', 'max'=>11),
//             array('comment', 'length', 'max'=>255),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
         //   array('id, short_loan_id, account_direction, dict_bank_id, bank_id, created_at, created_by, amount_type, amount, has_Ious, comment, reach_at', 'safe', 'on'=>'search'),
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
            'reach_at' => 'Reach At'
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
        $criteria->compare('reach_at', $this->reach_at);

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
} 
