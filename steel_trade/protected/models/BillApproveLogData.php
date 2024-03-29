<?php

/** 
 * This is the model class for table "bill_approve_log". 
 * 
 * The followings are the available columns in table 'bill_approve_log': 
 * @property integer $id
 * @property integer $form_id
 * @property string $status
 * @property integer $created_by
 * @property integer $created_at
 * @property string $description
 */ 
class BillApproveLogData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'bill_approve_log'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('form_id, status, created_by, created_at', 'required'),
            array('form_id, created_by, created_at', 'numerical', 'integerOnly'=>true),
            array('status', 'length', 'max'=>45),
            array('description', 'length', 'max'=>255),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, form_id, status, created_by, created_at, description', 'safe', 'on'=>'search'), 
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
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'description' => 'Description',
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
        $criteria->compare('description',$this->description,true);

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