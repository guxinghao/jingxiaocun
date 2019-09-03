<?php

/** 
 * This is the biz model class for table "bill_other_detail". 
 * 
 */ 
class BillOtherDetail extends BillOtherDetailData
{ 
     public $total_price;
    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array(
        	'recordType1' => array(self::BELONGS_TO, 'DictRecordType', 'type_1'),
        	'recordType2' => array(self::BELONGS_TO, 'DictRecordType', 'type_2'),
        	'frmBill'=>array(self::BELONGS_TO,'FrmBillOther','bill_other_id'),
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'bill_other_id' => 'Bill Other',
            'type' => 'Type',
            'fee' => 'Fee',
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
        $criteria->compare('bill_other_id',$this->bill_other_id);
        $criteria->compare('type',$this->type);
        $criteria->compare('fee',$this->fee,true);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
        /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return BillOtherDetail the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 

} 