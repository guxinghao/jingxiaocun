<?php

/** 
 * This is the model class for table "frm_form_bill". 
 * 
 * The followings are the available columns in table 'frm_form_bill': 
 * @property integer $id
 * @property string $form_type
 * @property string $bill_type
 * @property integer $is_yidan
 * @property string $pay_type
 * @property integer $company_id
 * @property integer $title_id
 * @property string $fee
 * @property string $weight
 * @property integer $form_id
 * @property integer $pledge_company_id
 * @property integer $account_by
 * @property integer $account_at
 * @property integer $dict_bank_info_id
 * @property integer $bank_info_id
 * @property integer $pledge_bank_info_id
 * @property integer $reach_at
 * @property integer $rebate_form_id
 * @property string $purpose
 * 
 * The followings are the available model relations: 
 * @property DictCompany $company
 * @property DictTitle $title
 */ 
class FrmFormBillData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'frm_form_bill'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('is_yidan, company_id, title_id, form_id, pledge_company_id, account_by, account_at, dict_bank_info_id, bank_info_id, pledge_bank_info_id, reach_at, rebate_form_id', 'numerical', 'integerOnly'=>true),
            array('form_type, bill_type, pay_type, purpose', 'length', 'max'=>45),
            array('fee', 'length', 'max'=>11),
            array('weight', 'length', 'max'=>15),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, form_type, bill_type, is_yidan, pay_type, company_id, title_id, fee, weight, form_id, pledge_company_id, account_by, account_at, dict_bank_info_id, bank_info_id, pledge_bank_info_id, reach_at, rebate_form_id, purpose', 'safe', 'on'=>'search'),
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
            'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
            'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'form_type' => 'Form Type',
            'bill_type' => 'Bill Type',
            'is_yidan' => 'Is Yidan',
            'pay_type' => 'Pay Type',
            'company_id' => 'Company',
            'title_id' => 'Title',
            'fee' => 'Fee',
            'weight' => 'Weight',
            'form_id' => 'Form',
            'pledge_company_id' => 'Pledge Company',
            'account_by' => 'Account By',
            'account_at' => 'Account At',
            'dict_bank_info_id' => 'Dict Bank Info',
            'bank_info_id' => 'Bank Info',
            'pledge_bank_info_id' => 'Pledge Bank Info',
            'reach_at' => 'Reach At',
            'rebate_form_id' => 'Rebate Form',
            'purpose' => 'Purpose',
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
        $criteria->compare('form_type',$this->form_type,true);
        $criteria->compare('bill_type',$this->bill_type,true);
        $criteria->compare('is_yidan',$this->is_yidan);
        $criteria->compare('pay_type',$this->pay_type,true);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('fee',$this->fee,true);
        $criteria->compare('weight',$this->weight,true);
        $criteria->compare('form_id',$this->form_id);
        $criteria->compare('pledge_company_id',$this->pledge_company_id);
        $criteria->compare('account_by',$this->account_by);
        $criteria->compare('account_at',$this->account_at);
        $criteria->compare('dict_bank_info_id',$this->dict_bank_info_id);
        $criteria->compare('bank_info_id',$this->bank_info_id);
        $criteria->compare('pledge_bank_info_id',$this->pledge_bank_info_id);
        $criteria->compare('reach_at',$this->reach_at);
        $criteria->compare('rebate_form_id',$this->rebate_form_id);
        $criteria->compare('purpose',$this->purpose,true);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return FrmFormBill the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 