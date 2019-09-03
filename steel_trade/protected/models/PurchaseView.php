<?php

/**
 * This is the biz model class for table "purchase_view".
 *
 */
class PurchaseView extends PurchaseViewData
{
	
	public $sum_weight,$sum_fee,$title,$supply,$productName;
	public $ship, $total_rebate,$uninput_amount,$uninput_weight, $total_bill_weight,$total_bill_money, $total_checked_weight, $total_checked_money;
	public $total_amount,$total_weight;
	public $total_money,$total_num;
	public $bprice,$pidweight,$pidfee;
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'baseform'=>array(self::BELONGS_TO,'CommonForms','common_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'detail_id' => 'Detail',
			'detail_price' => 'Detail Price',
			'detail_amount' => 'Detail Amount',
			'detail_weight' => 'Detail Weight',
			'detail_input_amount' => 'Detail Input Amount',
			'detail_input_weight' => 'Detail Input Weight',
			'detail_fix_amount' => 'Detail Fix Amount',
			'detail_fix_weight' => 'Detail Fix Weight',
			'detail_fix_price' => 'Detail Fix Price',
			'detail_cost_price' => 'Detail Cost Price',
			'detail_invoice_price' => 'Detail Invoice Price',
			'length' => 'Length',
			'product_id' => 'Product',
			'product_std' => 'Product Std',
			'product_name' => 'Product Name',
			'product_code' => 'Product Code',
			'texture_id' => 'Texture',
			'texture_std' => 'Texture Std',
			'texture_name' => 'Texture Name',
			'texture_code' => 'Texture Code',
			'brand_id' => 'Brand',
			'brand_std' => 'Brand Std',
			'brand_name' => 'Brand Name',
			'brand_code' => 'Brand Code',
			'rank_id' => 'Rank',
			'rank_std' => 'Rank Std',
			'rank_name' => 'Rank Name',
			'rank_code' => 'Rank Code',
			'main_id' => 'Main',
			'purchase_type' => 'Purchase Type',
			'supply_id' => 'Supply',
			'supply_name' => 'Supply Name',
			'supply_short_name' => 'Supply Short Name',
			'supply_code' => 'Supply Code',
			'title_id' => 'Title',
			'title_name' => 'Title Name',
			'title_code' => 'Title Code',
			'is_yidan' => 'Is Yidan',
			'contact_id' => 'Contact',
			'company_contact_name' => 'Company Contact Name',
			'company_contact_mobile' => 'Company Contact Mobile',
			'warehouse_id' => 'Warehouse',
			'warehouse_name' => 'Warehouse Name',
			'warehouse_code' => 'Warehouse Code',
			'main_amount' => 'Main Amount',
			'main_weight' => 'Main Weight',
			'main_input_amount' => 'Main Input Amount',
			'main_input_weight' => 'Main Input Weight',
			'weight_confirm_status' => 'Weight Confirm Status',
			'price_confirm_status' => 'Price Confirm Status',
			'main_invoice_cost' => 'Main Invoice Cost',
			'main_confirm_amount' => 'Main Confirm Amount',
			'main_confirm_weight' => 'Main Confirm Weight',
			'main_confirm_cost' => 'Main Confirm Cost',
			'team_id' => 'Team',
			'team_name' => 'Team Name',
			'frm_contract_id' => 'Frm Contract',
			'date_reach' => 'Date Reach',
			'transfer_number' => 'Transfer Number',
			'contain_cash' => 'Contain Cash',
			'price_amount' => 'Price Amount',
			'common_id' => 'Common',
			'form_type' => 'Form Type',
			'form_sn' => 'Form Sn',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'created_by_nickname' => 'Created By Nickname',
			'form_time' => 'Form Time',
			'form_status' => 'Form Status',
			'approved_at' => 'Approved At',
			'approved_by' => 'Approved By',
			'approved_by_nickname' => 'Approved By Nickname',
			'owned_by' => 'Owned By',
			'owned_by_nickname' => 'Owned By Nickname',
			'is_deleted' => 'Is Deleted',
			'last_update' => 'Last Update',
			'last_updated_by' => 'Last Updated By',
			'last_updated_by_nickname' => 'Last Updated By Nickname',
			'comment' => 'Comment',
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

		$criteria->compare('detail_id',$this->detail_id);
		$criteria->compare('detail_price',$this->detail_price,true);
		$criteria->compare('detail_amount',$this->detail_amount);
		$criteria->compare('detail_weight',$this->detail_weight,true);
		$criteria->compare('detail_input_amount',$this->detail_input_amount);
		$criteria->compare('detail_input_weight',$this->detail_input_weight,true);
		$criteria->compare('detail_fix_amount',$this->detail_fix_amount);
		$criteria->compare('detail_fix_weight',$this->detail_fix_weight,true);
		$criteria->compare('detail_fix_price',$this->detail_fix_price,true);
		$criteria->compare('detail_cost_price',$this->detail_cost_price,true);
		$criteria->compare('detail_invoice_price',$this->detail_invoice_price,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('product_code',$this->product_code,true);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('texture_name',$this->texture_name,true);
		$criteria->compare('texture_code',$this->texture_code,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('brand_code',$this->brand_code,true);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('rank_std',$this->rank_std,true);
		$criteria->compare('rank_name',$this->rank_name,true);
		$criteria->compare('rank_code',$this->rank_code,true);
		$criteria->compare('main_id',$this->main_id);
		$criteria->compare('purchase_type',$this->purchase_type,true);
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('supply_name',$this->supply_name,true);
		$criteria->compare('supply_short_name',$this->supply_short_name,true);
		$criteria->compare('supply_code',$this->supply_code,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('title_code',$this->title_code,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('company_contact_name',$this->company_contact_name,true);
		$criteria->compare('company_contact_mobile',$this->company_contact_mobile,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('warehouse_name',$this->warehouse_name,true);
		$criteria->compare('warehouse_code',$this->warehouse_code,true);
		$criteria->compare('main_amount',$this->main_amount);
		$criteria->compare('main_weight',$this->main_weight,true);
		$criteria->compare('main_input_amount',$this->main_input_amount);
		$criteria->compare('main_input_weight',$this->main_input_weight,true);
		$criteria->compare('weight_confirm_status',$this->weight_confirm_status);
		$criteria->compare('price_confirm_status',$this->price_confirm_status);
		$criteria->compare('main_invoice_cost',$this->main_invoice_cost,true);
		$criteria->compare('main_confirm_amount',$this->main_confirm_amount);
		$criteria->compare('main_confirm_weight',$this->main_confirm_weight,true);
		$criteria->compare('main_confirm_cost',$this->main_confirm_cost,true);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('team_name',$this->team_name,true);
		$criteria->compare('frm_contract_id',$this->frm_contract_id);
		$criteria->compare('date_reach',$this->date_reach);
		$criteria->compare('transfer_number',$this->transfer_number,true);
		$criteria->compare('contain_cash',$this->contain_cash);
		$criteria->compare('price_amount',$this->price_amount,true);
		$criteria->compare('common_id',$this->common_id);
		$criteria->compare('form_type',$this->form_type,true);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_by_nickname',$this->created_by_nickname,true);
		$criteria->compare('form_time',$this->form_time,true);
		$criteria->compare('form_status',$this->form_status,true);
		$criteria->compare('approved_at',$this->approved_at);
		$criteria->compare('approved_by',$this->approved_by);
		$criteria->compare('approved_by_nickname',$this->approved_by_nickname,true);
		$criteria->compare('owned_by',$this->owned_by);
		$criteria->compare('owned_by_nickname',$this->owned_by_nickname,true);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('last_updated_by',$this->last_updated_by);
		$criteria->compare('last_updated_by_nickname',$this->last_updated_by_nickname,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchaseView the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
