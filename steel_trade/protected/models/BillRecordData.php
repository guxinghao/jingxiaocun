<?php

/** 
 * This is the model class for table "bill_record". 
 * 
 * The followings are the available columns in table 'bill_record': 
 * @property integer $id
 * @property integer $company_id
 * @property integer $title_id
 * @property integer $frm_common_id
 * @property string $bill_type
 * @property string $price
 * @property string $weight
 * @property string $amount
 * @property integer $is_yidan
 * @property string $travel
 * @property string $discount
 * @property integer $is_selected
 */ 
class BillRecordData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'bill_record'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('company_id, title_id, frm_common_id, is_yidan, is_selected', 'numerical', 'integerOnly'=>true),
            array('bill_type, travel', 'length', 'max'=>45),
            array('price, amount, discount', 'length', 'max'=>11),
            array('weight', 'length', 'max'=>15),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, company_id, title_id, frm_common_id, bill_type, price, weight, amount, is_yidan, travel, discount, is_selected', 'safe', 'on'=>'search'), 
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
            'company_id' => 'Company',
            'title_id' => 'Title',
            'frm_common_id' => 'Frm Common',
            'bill_type' => 'Bill Type',
            'price' => 'Price',
            'weight' => 'Weight',
            'amount' => 'Amount',
            'is_yidan' => 'Is Yidan',
            'travel' => 'Travel',
            'discount' => 'Discount',
            'is_selected' => 'Is Selected',
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
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('frm_common_id',$this->frm_common_id);
        $criteria->compare('bill_type',$this->bill_type,true);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('weight',$this->weight,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('is_yidan',$this->is_yidan);
        $criteria->compare('travel',$this->travel,true);
        $criteria->compare('discount',$this->discount,true);
        $criteria->compare('is_selected',$this->is_selected);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return BillRecord the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 