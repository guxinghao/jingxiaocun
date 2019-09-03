<?php

/**
 * This is the model class for table "ps_profit".
 *
 * The followings are the available columns in table 'ps_profit':
 * @property integer $id
 * @property integer $output_detail_id
 * @property string $sale_time
 * @property integer $brand_id
 * @property integer $product_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property integer $title_id
 * @property integer $company_id
 * @property string $profit
 * @property string $sale_form_sn
 * @property string $weight
 * @property string $sale_price
 * @property string $sale_money
 * @property string $sale_ship
 * @property string $sale_rebate
 * @property string $other_form_sn
 * @property string $cost_price
 * @property string $cost_money
 * @property string $pur_ship
 * @property string $pledge_money
 * @property string $gcfl
 * @property string $ckfy
 * @property string $ckfl
 * @property integer $owned_by
 * @property integer $is_yidan
 * @property string $cost_invoice
 * @property string $sale_bonus
 * @property integer $confirm_status
 */
class PsProfitData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_profit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('output_detail_id, sale_time, title_id, company_id', 'required'),
			array('output_detail_id, brand_id, product_id, texture_id, rank_id, length, title_id, company_id, owned_by, is_yidan, confirm_status', 'numerical', 'integerOnly'=>true),
			array('profit, sale_money, cost_money, pur_ship, pledge_money, gcfl, ckfy, ckfl, cost_invoice', 'length', 'max'=>11),
			array('sale_form_sn, other_form_sn', 'length', 'max'=>45),
			array('weight', 'length', 'max'=>12),
			array('sale_price, sale_ship, sale_rebate, sale_bonus', 'length', 'max'=>10),
			array('cost_price', 'length', 'max'=>9),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, output_detail_id, sale_time, brand_id, product_id, texture_id, rank_id, length, title_id, company_id, profit, sale_form_sn, weight, sale_price, sale_money, sale_ship, sale_rebate, other_form_sn, cost_price, cost_money, pur_ship, pledge_money, gcfl, ckfy, ckfl, owned_by, is_yidan, cost_invoice, sale_bonus, confirm_status', 'safe', 'on'=>'search'),
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
			'output_detail_id' => 'Output Detail',
			'sale_time' => 'Sale Time',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'title_id' => 'Title',
			'company_id' => 'Company',
			'profit' => 'Profit',
			'sale_form_sn' => 'Sale Form Sn',
			'weight' => 'Weight',
			'sale_price' => 'Sale Price',
			'sale_money' => 'Sale Money',
			'sale_ship' => 'Sale Ship',
			'sale_rebate' => 'Sale Rebate',
			'other_form_sn' => 'Other Form Sn',
			'cost_price' => 'Cost Price',
			'cost_money' => 'Cost Money',
			'pur_ship' => 'Pur Ship',
			'pledge_money' => 'Pledge Money',
			'gcfl' => 'Gcfl',
			'ckfy' => 'Ckfy',
			'ckfl' => 'Ckfl',
			'owned_by' => 'Owned By',
			'is_yidan' => 'Is Yidan',
			'cost_invoice' => 'Cost Invoice',
			'sale_bonus' => 'Sale Bonus',
			'confirm_status' => 'Confirm Status',
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
		$criteria->compare('output_detail_id',$this->output_detail_id);
		$criteria->compare('sale_time',$this->sale_time,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('profit',$this->profit,true);
		$criteria->compare('sale_form_sn',$this->sale_form_sn,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('sale_price',$this->sale_price,true);
		$criteria->compare('sale_money',$this->sale_money,true);
		$criteria->compare('sale_ship',$this->sale_ship,true);
		$criteria->compare('sale_rebate',$this->sale_rebate,true);
		$criteria->compare('other_form_sn',$this->other_form_sn,true);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('cost_money',$this->cost_money,true);
		$criteria->compare('pur_ship',$this->pur_ship,true);
		$criteria->compare('pledge_money',$this->pledge_money,true);
		$criteria->compare('gcfl',$this->gcfl,true);
		$criteria->compare('ckfy',$this->ckfy,true);
		$criteria->compare('ckfl',$this->ckfl,true);
		$criteria->compare('owned_by',$this->owned_by);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('cost_invoice',$this->cost_invoice,true);
		$criteria->compare('sale_bonus',$this->sale_bonus,true);
		$criteria->compare('confirm_status',$this->confirm_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PsProfit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
