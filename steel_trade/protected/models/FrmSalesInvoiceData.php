<?php

/** 
 * This is the model class for table "frm_sales_invoice". 
 * 
 * The followings are the available columns in table 'frm_sales_invoice': 
 * @property integer $id
 * @property string $invoice_type
 * @property integer $company_id
 * @property integer $title_id
 * @property string $price
 * @property string $weight
 * @property string $fee
 * @property integer $confirm_status
 * @property integer $sales_id
 * @property integer $invoice_amount
 * @property string $invoice_number
 * 
 * The followings are the available model relations: 
 * @property DictCompany $company
 * @property DictTitle $title
 * @property SalesInvoiceDetail[] $salesInvoiceDetails
 */ 
class FrmSalesInvoiceData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'frm_sales_invoice'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('company_id, title_id, confirm_status, sales_id, invoice_amount', 'numerical', 'integerOnly'=>true),
            array('invoice_type, invoice_number', 'length', 'max'=>45),
            array('price, fee', 'length', 'max'=>11),
            array('weight', 'length', 'max'=>15),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, invoice_type, company_id, title_id, price, weight, fee, confirm_status, sales_id, invoice_amount, invoice_number', 'safe', 'on'=>'search'),
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
            'salesInvoiceDetails' => array(self::HAS_MANY, 'SalesInvoiceDetail', 'sales_invoice_id'),
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'invoice_type' => 'Invoice Type',
            'company_id' => 'Company',
            'title_id' => 'Title',
            'price' => 'Price',
            'weight' => 'Weight',
            'fee' => 'Fee',
            'confirm_status' => 'Confirm Status',
            'sales_id' => 'Sales',
            'invoice_amount' => 'Invoice Amount',
            'invoice_number' => 'Invoice Number',
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
        $criteria->compare('invoice_type',$this->invoice_type,true);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('weight',$this->weight,true);
        $criteria->compare('fee',$this->fee,true);
        $criteria->compare('confirm_status',$this->confirm_status);
        $criteria->compare('sales_id',$this->sales_id);
        $criteria->compare('invoice_amount',$this->invoice_amount);
        $criteria->compare('invoice_number',$this->invoice_number,true);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return FrmSalesInvoice the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 