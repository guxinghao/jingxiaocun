<?php

/** 
 * This is the model class for table "frm_bill_log". 
 * 
 * The followings are the available columns in table 'frm_bill_log': 
 * @property integer $id
 * @property integer $form_id
 * @property string $form_sn
 * @property integer $title_id
 * @property integer $dict_bank_id
 * @property integer $company_id
 * @property integer $bank_id
 * @property string $fee
 * @property string $account_type
 * @property string $bill_type
 * @property string $pay_type
 * @property integer $account_by
 * @property string $comment
 * @property integer $created_at
 * @property integer $reach_at
 */ 
class FrmBillLogData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'frm_bill_log'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('form_id, title_id, company_id, bank_id', 'required'),
            array('form_id, title_id, dict_bank_id, company_id, bank_id, account_by, created_at, reach_at', 'numerical', 'integerOnly'=>true),
            array('form_sn, account_type, bill_type, pay_type', 'length', 'max'=>45),
            array('fee', 'length', 'max'=>11),
            array('comment', 'length', 'max'=>255),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, form_id, form_sn, title_id, dict_bank_id, company_id, bank_id, fee, account_type, bill_type, pay_type, account_by, comment, created_at, reach_at', 'safe', 'on'=>'search'),
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
            'form_id' => 'Form',
            'form_sn' => 'Form Sn',
            'title_id' => 'Title',
            'dict_bank_id' => 'Dict Bank',
            'company_id' => 'Company',
            'bank_id' => 'Bank',
            'fee' => 'Fee',
            'account_type' => 'Account Type',
            'bill_type' => 'Bill Type',
            'pay_type' => 'Pay Type',
            'account_by' => 'Account By',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'reach_at' => 'Reach At',
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
        $criteria->compare('form_id',$this->form_id);
        $criteria->compare('form_sn',$this->form_sn,true);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('dict_bank_id',$this->dict_bank_id);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('bank_id',$this->bank_id);
        $criteria->compare('fee',$this->fee,true);
        $criteria->compare('account_type',$this->account_type,true);
        $criteria->compare('bill_type',$this->bill_type,true);
        $criteria->compare('pay_type',$this->pay_type,true);
        $criteria->compare('account_by',$this->account_by);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('created_at',$this->created_at);
        $criteria->compare('reach_at', $this->reach_at);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return FrmBillLog the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 