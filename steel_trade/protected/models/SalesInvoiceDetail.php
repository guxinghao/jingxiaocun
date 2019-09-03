<?php

/**
 * This is the biz model class for table "sales_invoice_detail".
 *
 */
class SalesInvoiceDetail extends SalesInvoiceDetailData
{
	public $checked_weight;
	public $checked_price;

	public $title_id,$company_id,$client_id;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'salesInvoice' => array(self::BELONGS_TO, 'FrmSalesInvoice', 'sales_invoice_id'),
			'detailForInvoice' => array(self::BELONGS_TO, 'DetailForInvoice', 'sales_detail_id'),
			'sales' => array(self::BELONGS_TO, 'FrmSales', 'frm_sales_id'), 
			'salesDetail' => array(self::BELONGS_TO, 'SalesDetail', 'frm_sales_detail_id'),
			'salesReturnDetail' => array(self::BELONGS_TO, 'SalesReturnDetail', 'frm_sales_detail_id'),
			'rebate' => array(self::BELONGS_TO, 'FrmRebate', 'frm_sales_id'),
			'client' => array(self::BELONGS_TO, 'DictCompany', 'client_id'),
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
