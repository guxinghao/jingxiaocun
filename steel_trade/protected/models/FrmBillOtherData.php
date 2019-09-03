<?php

/** 
 * This is the model class for table "frm_bill_other". 
 * 
 * The followings are the available columns in table 'frm_bill_other': 
 * @property integer $id
 * @property integer $title_id
 * @property integer $dict_bank_id
 * @property integer $company_id
 * @property integer $bank_id
 * @property integer $team_id
 * @property string $bill_type
 * @property string $amount
 * @property integer $status
 * @property string $comment
 * @property integer $account_by
 * @property integer $account_at
 * @property integer $reach_at
 * 
 * The followings are the available model relations: 
 * @property BillOtherDetail[] $billOtherDetails
 * @property DictCompany $company
 * @property DictTitle $title
 * @property Team $team
 */ 
class FrmBillOtherData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'frm_bill_other'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('title_id, dict_bank_id, company_id, bank_id, team_id, status, account_by, account_at, reach_at', 'numerical', 'integerOnly'=>true),
            array('bill_type', 'length', 'max'=>45),
            array('amount', 'length', 'max'=>11),
            array('comment', 'length', 'max'=>255),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, title_id, dict_bank_id, company_id, bank_id, team_id, bill_type, amount, status, comment, account_by, account_at, reach_at', 'safe', 'on'=>'search'),
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
            'billOtherDetails' => array(self::HAS_MANY, 'BillOtherDetail', 'bill_other_id'),
            'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
            'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
            'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
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
            'dict_bank_id' => 'Dict Bank',
            'company_id' => 'Company',
            'bank_id' => 'Bank',
            'team_id' => 'Team',
            'bill_type' => 'Bill Type',
            'amount' => 'Amount',
            'status' => 'Status',
            'comment' => 'Comment',
            'account_by' => 'Account By',
            'account_at' => 'Account At',
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
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('dict_bank_id',$this->dict_bank_id);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('bank_id',$this->bank_id);
        $criteria->compare('team_id',$this->team_id);
        $criteria->compare('bill_type',$this->bill_type,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('account_by',$this->account_by);
        $criteria->compare('account_at',$this->account_at);
        $criteria->compare('reach_at', $this->reach_at);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return FrmBillOther the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 