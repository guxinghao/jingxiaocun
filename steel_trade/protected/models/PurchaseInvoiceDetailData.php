<?php

/**
 * This is the model class for table "purchase_invoice_detail".
 *
 * The followings are the available columns in table 'purchase_invoice_detail':
 * @property integer $id
 * @property string $weight
 * @property string $fee
 * @property integer $purchase_invoice_id
 * @property integer $purchase_detail_id
 * @property integer $frm_purchase_id
 * @property integer $frm_purchase_detail_id
 * @property string $type
 *
 * The followings are the available model relations:
 * @property FrmPurchaseInvoice $purchaseInvoice
 */
class PurchaseInvoiceDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_invoice_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('purchase_invoice_id, purchase_detail_id', 'required'),
			array('purchase_invoice_id, purchase_detail_id, frm_purchase_id, frm_purchase_detail_id', 'numerical', 'integerOnly'=>true),
			array('weight', 'length', 'max'=>15),
			array('fee', 'length', 'max'=>11),
			array('type', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weight, fee, purchase_invoice_id, purchase_detail_id, frm_purchase_id, frm_purchase_detail_id, type', 'safe', 'on'=>'search'),
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
			'purchaseInvoice' => array(self::BELONGS_TO, 'FrmPurchaseInvoice', 'purchase_invoice_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'weight' => 'Weight',
			'fee' => 'Fee',
			'purchase_invoice_id' => 'Purchase Invoice',
			'purchase_detail_id' => 'Purchase Detail',
			'frm_purchase_id' => 'Frm Purchase',
			'frm_purchase_detail_id' => 'Frm Purchase Detail',
			'type' => 'Type',
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
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('purchase_invoice_id',$this->purchase_invoice_id);
		$criteria->compare('purchase_detail_id',$this->purchase_detail_id);
		$criteria->compare('frm_purchase_id',$this->frm_purchase_id);
		$criteria->compare('frm_purchase_detail_id',$this->frm_purchase_detail_id);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchaseInvoiceDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
