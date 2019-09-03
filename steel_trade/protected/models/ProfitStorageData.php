<?php

/**
 * This is the model class for table "profit_storage".
 *
 * The followings are the available columns in table 'profit_storage':
 * @property integer $id
 * @property integer $storage_id
 * @property string $card_no
 * @property integer $purchase_id
 * @property string $form_sn
 * @property integer $purchase_date
 * @property integer $input_date
 * @property integer $title_id
 * @property string $title_name
 * @property integer $supply_id
 * @property string $supply_name
 * @property integer $brand_id
 * @property integer $product_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property string $left_weight
 * @property string $lock_weight
 * @property string $price
 * @property string $type
 * @property string $purchase_price
 * @property string $purchase_money
 * @property string $purchase_freight
 * @property integer $warehouse_id
 * @property string $warehouse_fee
 * @property string $warehouse_rebate
 * @property string $supply_rebate
 * @property string $sale_subsidy
 * @property string $invoice
 * @property string $pledge_fee
 * @property integer $edit_at
 * @property string $profit
 */
class ProfitStorageData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profit_storage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('storage_id, purchase_id, purchase_date, input_date, title_id, supply_id, brand_id, product_id, texture_id, rank_id, length, warehouse_id, edit_at', 'numerical', 'integerOnly'=>true),
			array('card_no, form_sn, title_name, supply_name, type', 'length', 'max'=>45),
			array('left_weight, lock_weight, purchase_money, purchase_freight, warehouse_fee, warehouse_rebate, supply_rebate, sale_subsidy, invoice, pledge_fee, profit', 'length', 'max'=>15),
			array('price, purchase_price', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, storage_id, card_no, purchase_id, form_sn, purchase_date, input_date, title_id, title_name, supply_id, supply_name, brand_id, product_id, texture_id, rank_id, length, left_weight, lock_weight, price, type, purchase_price, purchase_money, purchase_freight, warehouse_id, warehouse_fee, warehouse_rebate, supply_rebate, sale_subsidy, invoice, pledge_fee, edit_at, profit', 'safe', 'on'=>'search'),
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
			'storage_id' => 'Storage',
			'card_no' => 'Card No',
			'purchase_id' => 'Purchase',
			'form_sn' => 'Form Sn',
			'purchase_date' => 'Purchase Date',
			'input_date' => 'Input Date',
			'title_id' => 'Title',
			'title_name' => 'Title Name',
			'supply_id' => 'Supply',
			'supply_name' => 'Supply Name',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'left_weight' => 'Left Weight',
			'lock_weight' => 'Lock Weight',
			'price' => 'Price',
			'type' => 'Type',
			'purchase_price' => 'Purchase Price',
			'purchase_money' => 'Purchase Money',
			'purchase_freight' => 'Purchase Freight',
			'warehouse_id' => 'Warehouse',
			'warehouse_fee' => 'Warehouse Fee',
			'warehouse_rebate' => 'Warehouse Rebate',
			'supply_rebate' => 'Supply Rebate',
			'sale_subsidy' => 'Sale Subsidy',
			'invoice' => 'Invoice',
			'pledge_fee' => 'Pledge Fee',
			'edit_at' => 'Edit At',
			'profit' => 'Profit',
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
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('purchase_date',$this->purchase_date);
		$criteria->compare('input_date',$this->input_date);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('supply_name',$this->supply_name,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('left_weight',$this->left_weight,true);
		$criteria->compare('lock_weight',$this->lock_weight,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('purchase_price',$this->purchase_price,true);
		$criteria->compare('purchase_money',$this->purchase_money,true);
		$criteria->compare('purchase_freight',$this->purchase_freight,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('warehouse_fee',$this->warehouse_fee,true);
		$criteria->compare('warehouse_rebate',$this->warehouse_rebate,true);
		$criteria->compare('supply_rebate',$this->supply_rebate,true);
		$criteria->compare('sale_subsidy',$this->sale_subsidy,true);
		$criteria->compare('invoice',$this->invoice,true);
		$criteria->compare('pledge_fee',$this->pledge_fee,true);
		$criteria->compare('edit_at',$this->edit_at);
		$criteria->compare('profit',$this->profit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfitStorage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
