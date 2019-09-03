<?php

/**
 * This is the model class for table "profit_collecting".
 *
 * The followings are the available columns in table 'profit_collecting':
 * @property integer $id
 * @property integer $sales_id
 * @property string $form_sn
 * @property string $sales_date
 * @property integer $title_id
 * @property string $title_name
 * @property integer $company_id
 * @property string $company_name
 * @property integer $brand_id
 * @property integer $product_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property string $sales_profit
 * @property string $weight
 * @property string $price
 * @property string $fee
 * @property string $sales_freight
 * @property string $sales_rebate
 * @property string $purchase_form_sn
 * @property string $purchase_price
 * @property string $purchase_money
 * @property string $purchase_freight
 * @property string $warehouse_fee
 * @property string $warehouse_rebate
 * @property string $supply_rebate
 * @property string $sale_subsidy
 * @property string $invoice
 * @property string $pledge_fee
 * @property string $hight_open
 * @property integer $owner_id
 * @property string $owner_name
 * @property integer $is_yidan
 * @property integer $supply_id
 * @property string $comment
 * @property integer $confirmed
 * @property integer $warehouse_id
 */
class ProfitCollectingData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profit_collecting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sales_id, title_id, company_id, brand_id, product_id, texture_id, rank_id, length, owner_id, is_yidan, supply_id, confirmed, warehouse_id', 'numerical', 'integerOnly'=>true),
			array('form_sn, title_name, company_name, purchase_form_sn, owner_name, comment', 'length', 'max'=>45),
			array('sales_profit, weight, fee, sales_freight, sales_rebate, purchase_money, purchase_freight, warehouse_fee, warehouse_rebate, supply_rebate, sale_subsidy, invoice, pledge_fee, hight_open', 'length', 'max'=>15),
			array('price, purchase_price', 'length', 'max'=>11),
			array('sales_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sales_id, form_sn, sales_date, title_id, title_name, company_id, company_name, brand_id, product_id, texture_id, rank_id, length, sales_profit, weight, price, fee, sales_freight, sales_rebate, purchase_form_sn, purchase_price, purchase_money, purchase_freight, warehouse_fee, warehouse_rebate, supply_rebate, sale_subsidy, invoice, pledge_fee, hight_open, owner_id, owner_name, is_yidan, supply_id, comment, confirmed, warehouse_id', 'safe', 'on'=>'search'),
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
			'sales_id' => 'Sales',
			'form_sn' => 'Form Sn',
			'sales_date' => 'Sales Date',
			'title_id' => 'Title',
			'title_name' => 'Title Name',
			'company_id' => 'Company',
			'company_name' => 'Company Name',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'sales_profit' => 'Sales Profit',
			'weight' => 'Weight',
			'price' => 'Price',
			'fee' => 'Fee',
			'sales_freight' => 'Sales Freight',
			'sales_rebate' => 'Sales Rebate',
			'purchase_form_sn' => 'Purchase Form Sn',
			'purchase_price' => 'Purchase Price',
			'purchase_money' => 'Purchase Money',
			'purchase_freight' => 'Purchase Freight',
			'warehouse_fee' => 'Warehouse Fee',
			'warehouse_rebate' => 'Warehouse Rebate',
			'supply_rebate' => 'Supply Rebate',
			'sale_subsidy' => 'Sale Subsidy',
			'invoice' => 'Invoice',
			'pledge_fee' => 'Pledge Fee',
			'hight_open' => 'Hight Open',
			'owner_id' => 'Owner',
			'owner_name' => 'Owner Name',
			'is_yidan' => 'Is Yidan',
			'supply_id' => 'Supply',
			'comment' => 'Comment',
			'confirmed' => 'Confirmed',
			'warehouse_id' => 'Warehouse',
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
		$criteria->compare('sales_id',$this->sales_id);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('sales_date',$this->sales_date,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('sales_profit',$this->sales_profit,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('sales_freight',$this->sales_freight,true);
		$criteria->compare('sales_rebate',$this->sales_rebate,true);
		$criteria->compare('purchase_form_sn',$this->purchase_form_sn,true);
		$criteria->compare('purchase_price',$this->purchase_price,true);
		$criteria->compare('purchase_money',$this->purchase_money,true);
		$criteria->compare('purchase_freight',$this->purchase_freight,true);
		$criteria->compare('warehouse_fee',$this->warehouse_fee,true);
		$criteria->compare('warehouse_rebate',$this->warehouse_rebate,true);
		$criteria->compare('supply_rebate',$this->supply_rebate,true);
		$criteria->compare('sale_subsidy',$this->sale_subsidy,true);
		$criteria->compare('invoice',$this->invoice,true);
		$criteria->compare('pledge_fee',$this->pledge_fee,true);
		$criteria->compare('hight_open',$this->hight_open,true);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('owner_name',$this->owner_name,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('confirmed',$this->confirmed);
		$criteria->compare('warehouse_id',$this->warehouse_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfitCollecting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
