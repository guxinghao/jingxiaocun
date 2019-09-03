<?php

/** 
 * This is the biz model class for table "bill_approve_log". 
 * 
 */ 
class BillApproveLog extends BillApproveLogData
{ 
     public static $status = array('approve' => "通过", 'refuse' => "拒绝", 'cancle' => "取消审核");

    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array(
        		'approver' => array(self::BELONGS_TO, 'User', 'created_by'), //审核人
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
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
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
        $criteria->compare('status',$this->status,true);
        $criteria->compare('created_by',$this->created_by);
        $criteria->compare('created_at',$this->created_at);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
        /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return BillApproveLog the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 

} 