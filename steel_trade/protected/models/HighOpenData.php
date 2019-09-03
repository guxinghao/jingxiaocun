<?php

/** 
 * This is the model class for table "high_open". 
 * 
 * The followings are the available columns in table 'high_open': 
 * @property integer $id
 * @property integer $sales_id
 * @property integer $sales_detail_id
 * @property string $price
 * @property string $real_fee
 * @property string $fee
 * @property integer $is_pay
 * @property string $discount
 * @property integer $title_id
 * @property integer $target_id
 * @property integer $is_selected
 */ 
class HighOpenData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'high_open'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('sales_id, sales_detail_id, price, real_fee, fee, title_id, target_id', 'required'),
            array('sales_id, sales_detail_id, is_pay, title_id, target_id, is_selected', 'numerical', 'integerOnly'=>true),
            array('price, real_fee, fee, discount', 'length', 'max'=>11),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, sales_id, sales_detail_id, price, real_fee, fee, is_pay, discount, title_id, target_id, is_selected', 'safe', 'on'=>'search'), 
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
            'sales_id' => 'Sales',
            'sales_detail_id' => 'Sales Detail',
            'price' => 'Price',
            'real_fee' => 'Real Fee',
            'fee' => 'Fee',
            'is_pay' => 'Is Pay',
            'discount' => 'Discount',
            'title_id' => 'Title',
            'target_id' => 'Target',
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
        $criteria->compare('sales_id',$this->sales_id);
        $criteria->compare('sales_detail_id',$this->sales_detail_id);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('real_fee',$this->real_fee,true);
        $criteria->compare('fee',$this->fee,true);
        $criteria->compare('is_pay',$this->is_pay);
        $criteria->compare('discount',$this->discount,true);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('target_id',$this->target_id);
        $criteria->compare('is_selected',$this->is_selected);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return HighOpen the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 