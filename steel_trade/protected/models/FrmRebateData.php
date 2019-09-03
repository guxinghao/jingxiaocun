<?php

/** 
 * This is the model class for table "frm_rebate". 
 * 
 * The followings are the available columns in table 'frm_rebate': 
 * @property integer $id
 * @property integer $team_id
 * @property integer $title_id
 * @property integer $company_id
 * @property string $type
 * @property string $amount
 * @property integer $is_yidan
 * @property string $comment
 * @property integer $start_at
 * @property integer $end_at
 * 
 * The followings are the available model relations: 
 * @property DictCompany $company
 * @property DictTitle $title
 * @property Team $team
 */ 
class FrmRebateData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'frm_rebate'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('team_id, title_id, company_id, is_yidan, start_at, end_at', 'numerical', 'integerOnly'=>true),
            array('type', 'length', 'max'=>45),
            array('amount', 'length', 'max'=>11),
            array('comment', 'length', 'max'=>255),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, team_id, title_id, company_id, type, amount, is_yidan, comment, start_at, end_at', 'safe', 'on'=>'search'), 
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
            'team_id' => 'Team',
            'title_id' => 'Title',
            'company_id' => 'Company',
            'type' => 'Type',
            'amount' => 'Amount',
            'is_yidan' => 'Is Yidan',
            'comment' => 'Comment',
            'start_at' => 'Start At',
            'end_at' => 'End At',
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
        $criteria->compare('team_id',$this->team_id);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('is_yidan',$this->is_yidan);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('start_at',$this->start_at);
        $criteria->compare('end_at',$this->end_at);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return FrmRebate the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 