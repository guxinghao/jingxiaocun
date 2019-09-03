<?php

/**
 * This is the biz model class for table "ps_profit".
 *
 */
class PsProfit extends PsProfitData
{
	

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
