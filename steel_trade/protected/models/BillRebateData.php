<?php

/** 
 * This is the model class for table "bill_rebate". 
 * 
 * The followings are the available columns in table 'bill_rebate': 
 * @property integer $id
 * @property string $type
 * @property integer $warehouse_id
 * @property integer $company_id
 * @property integer $title_id
 * @property string $fee
 * @property integer $start_time
 * @property integer $end_time
 */ 
class BillRebateData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'bill_rebate'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('company_id, title_id, fee, start_time, end_time', 'required'),
            array('warehouse_id, company_id, title_id, start_time, end_time', 'numerical', 'integerOnly'=>true),
            array('type', 'length', 'max'=>45),
            array('fee', 'length', 'max'=>11),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, type, warehouse_id, company_id, title_id, fee, start_time, end_time', 'safe', 'on'=>'search'), 
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
            'type' => 'Type',
            'warehouse_id' => 'Warehouse',
            'company_id' => 'Company',
            'title_id' => 'Title',
            'fee' => 'Fee',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
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
        $criteria->compare('type',$this->type,true);
        $criteria->compare('warehouse_id',$this->warehouse_id);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('fee',$this->fee,true);
        $criteria->compare('start_time',$this->start_time);
        $criteria->compare('end_time',$this->end_time);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return BillRebate the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 