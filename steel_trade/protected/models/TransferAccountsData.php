<?php

/** 
 * This is the model class for table "transfer_accounts". 
 * 
 * The followings are the available columns in table 'transfer_accounts': 
 * @property integer $id
 * @property integer $title_output_id
 * @property string $title_input_id
 * @property string $type
 * @property string $amount
 * @property string $comment
 * @property integer $output_bank_id
 * @property integer $input_bank_id
 * @property integer $reach_at
 */ 
class TransferAccountsData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'transfer_accounts'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('title_output_id, output_bank_id, input_bank_id, reach_at', 'numerical', 'integerOnly'=>true),
            array('title_input_id, type', 'length', 'max'=>45),
            array('amount', 'length', 'max'=>11),
            array('comment', 'length', 'max'=>255),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, title_output_id, title_input_id, type, amount, comment, output_bank_id, input_bank_id, reach_at', 'safe', 'on'=>'search'), 
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
            'title_output_id' => 'Title Output',
            'title_input_id' => 'Title Input',
            'type' => 'Type',
            'amount' => 'Amount',
            'comment' => 'Comment',
            'output_bank_id' => 'Output Bank',
            'input_bank_id' => 'Input Bank',
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
        $criteria->compare('title_output_id',$this->title_output_id);
        $criteria->compare('title_input_id',$this->title_input_id,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('output_bank_id',$this->output_bank_id);
        $criteria->compare('input_bank_id',$this->input_bank_id);
        $criteria->compare('reach_at', $this->reach_at);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return TransferAccounts the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 
