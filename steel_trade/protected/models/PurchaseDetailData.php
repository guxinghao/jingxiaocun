<?php

/** 
 * This is the model class for table "purchase_detail". 
 * 
 * The followings are the available columns in table 'purchase_detail': 
 * @property integer $id
 * @property string $price
 * @property integer $amount
 * @property string $weight
 * @property integer $input_amount
 * @property string $input_weight
 * @property integer $purchase_id
 * @property integer $fix_amount
 * @property string $fix_weight
 * @property string $fix_price
 * @property string $cost_price
 * @property string $invoice_price
 * @property integer $product_id
 * @property integer $brand_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property integer $plan_amount
 * @property string $plan_weight
 * @property integer $bill_done
 * 
 * The followings are the available model relations: 
 * @property FrmPurchase $purchase
 */ 
class PurchaseDetailData extends CActiveRecord
{ 
    /** 
     * @return string the associated database table name 
     */ 
    public function tableName() 
    { 
        return 'purchase_detail'; 
    } 

    /** 
     * @return array validation rules for model attributes. 
     */ 
    public function rules() 
    { 
        // NOTE: you should only define rules for those attributes that 
        // will receive user inputs. 
        return array( 
            array('purchase_id, product_id, brand_id, texture_id, rank_id', 'required'),
            array('amount, input_amount, purchase_id, fix_amount, product_id, brand_id, texture_id, rank_id, length, plan_amount, bill_done', 'numerical', 'integerOnly'=>true),
            array('price, fix_price, cost_price, invoice_price', 'length', 'max'=>11),
            array('weight, input_weight, fix_weight, plan_weight', 'length', 'max'=>15),
            // The following rule is used by search(). 
            // @todo Please remove those attributes that should not be searched. 
            array('id, price, amount, weight, input_amount, input_weight, purchase_id, fix_amount, fix_weight, fix_price, cost_price, invoice_price, product_id, brand_id, texture_id, rank_id, length, plan_amount, plan_weight, bill_done', 'safe', 'on'=>'search'),
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
            'purchase' => array(self::BELONGS_TO, 'FrmPurchase', 'purchase_id'),
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'price' => 'Price',
            'amount' => 'Amount',
            'weight' => 'Weight',
            'input_amount' => 'Input Amount',
            'input_weight' => 'Input Weight',
            'purchase_id' => 'Purchase',
            'fix_amount' => 'Fix Amount',
            'fix_weight' => 'Fix Weight',
            'fix_price' => 'Fix Price',
            'cost_price' => 'Cost Price',
            'invoice_price' => 'Invoice Price',
            'product_id' => 'Product',
            'brand_id' => 'Brand',
            'texture_id' => 'Texture',
            'rank_id' => 'Rank',
            'length' => 'Length',
            'plan_amount' => 'Plan Amount',
            'plan_weight' => 'Plan Weight',
            'bill_done' => 'Bill Done',
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
        $criteria->compare('price',$this->price,true);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('weight',$this->weight,true);
        $criteria->compare('input_amount',$this->input_amount);
        $criteria->compare('input_weight',$this->input_weight,true);
        $criteria->compare('purchase_id',$this->purchase_id);
        $criteria->compare('fix_amount',$this->fix_amount);
        $criteria->compare('fix_weight',$this->fix_weight,true);
        $criteria->compare('fix_price',$this->fix_price,true);
        $criteria->compare('cost_price',$this->cost_price,true);
        $criteria->compare('invoice_price',$this->invoice_price,true);
        $criteria->compare('product_id',$this->product_id);
        $criteria->compare('brand_id',$this->brand_id);
        $criteria->compare('texture_id',$this->texture_id);
        $criteria->compare('rank_id',$this->rank_id);
        $criteria->compare('length',$this->length);
        $criteria->compare('plan_amount',$this->plan_amount);
        $criteria->compare('plan_weight',$this->plan_weight,true);
        $criteria->compare('bill_done',$this->bill_done);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 

    /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return PurchaseDetail the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
} 