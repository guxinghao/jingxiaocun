<?php

/**
 * This is the model class for table "sales_invoice_detail".
 *
 * The followings are the available columns in table 'sales_invoice_detail':
 * @property integer $id
 * @property integer $sales_detail_id
 * @property string $weight
 * @property string $fee
 * @property integer $sales_invoice_id
 * @property integer $frm_sales_id
 * @property integer $frm_sales_detail_id
 * @property string $type
 *
 * The followings are the available model relations:
 * @property FrmSalesInvoice $salesInvoice
 */
class SalesInvoiceDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sales_invoice_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sales_detail_id, sales_invoice_id', 'required'),
			array('sales_detail_id, sales_invoice_id, frm_sales_id, frm_sales_detail_id', 'numerical', 'integerOnly'=>true),
			array('weight', 'length', 'max'=>15),
			array('fee', 'length', 'max'=>11),
			array('type', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sales_detail_id, weight, fee, sales_invoice_id, frm_sales_id, frm_sales_detail_id, type', 'safe', 'on'=>'search'),
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
			'salesInvoice' => array(self::BELONGS_TO, 'FrmSalesInvoice', 'sales_invoice_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sales_detail_id' => 'Sales Detail',
			'weight' => 'Weight',
			'fee' => 'Fee',
			'sales_invoice_id' => 'Sales Invoice',
			'frm_sales_id' => 'Frm Sales',
			'frm_sales_detail_id' => 'Frm Sales Detail',
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
		$criteria->compare('sales_detail_id',$this->sales_detail_id);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('sales_invoice_id',$this->sales_invoice_id);
		$criteria->compare('frm_sales_id',$this->frm_sales_id);
		$criteria->compare('frm_sales_detail_id',$this->frm_sales_detail_id);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalesInvoiceDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
