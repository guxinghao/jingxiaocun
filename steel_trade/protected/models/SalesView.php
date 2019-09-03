<?php

/**
 * This is the biz model class for table "sales_view".
 *
 */
class SalesView extends SalesViewData
{
	public $total_amount;
	public $total_weight;
	public $total_price;
	public $total_num;

	public $variance_amount; //差异件数
	public $variance_weight; //差异重量

	public $sum_weight,$sum_fee,$each_name;
	public $final_balance,$already_collection,$already_paid,$sales_return,$sales_return_amount,$sales_rebate,$sales_amount,$sales_detail;
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
			'detail_id' => 'Detail',
			'detail_price' => 'Detail Price',
			'bonus_price' => 'Bonus Price',
			'detail_amount' => 'Detail Amount',
			'weight' => 'Weight',
			'detail_send_amount' => 'Detail Send Amount',
			'detail_send_weight' => 'Detail Send Weight',
			'detail_output_amount' => 'Detail Output Amount',
			'detail_output_weight' => 'Detail Output Weight',
			'detail_warehouse_output_amount' => 'Detail Warehouse Output Amount',
			'detail_warehouse_output_weight' => 'Detail Warehouse Output Weight',
			'detail_card_id' => 'Detail Card',
			'detail_length' => 'Detail Length',
			'product_std' => 'Product Std',
			'product_name' => 'Product Name',
			'product_code' => 'Product Code',
			'texture_std' => 'Texture Std',
			'texture_name' => 'Texture Name',
			'texture_code' => 'Texture Code',
			'brand_std' => 'Brand Std',
			'brand_name' => 'Brand Name',
			'brand_code' => 'Brand Code',
			'rand_std' => 'Rand Std',
			'rand_name' => 'Rand Name',
			'rand_code' => 'Rand Code',
			'main_id' => 'Main',
			'main_type' => 'Main Type',
			'main_title_id' => 'Main Title',
			'title_name' => 'Title Name',
			'title_code' => 'Title Code',
			'customer_id' => 'Customer',
			'customer_name' => 'Customer Name',
			'customer_short_name' => 'Customer Short Name',
			'customer_code' => 'Customer Code',
			'owner_company_id' => 'Owner Company',
			'owner_company_name' => 'Owner Company Name',
			'owner_company_short_name' => 'Owner Company Short Name',
			'owner_company_code' => 'Owner Company Code',
			'team_id' => 'Team',
			'team_name' => 'Team Name',
			'is_yidan' => 'Is Yidan',
			'warehouse_id' => 'Warehouse',
			'warehouse_name' => 'Warehouse Name',
			'warehouse_code' => 'Warehouse Code',
			'main_amount' => 'Main Amount',
			'main_weight' => 'Main Weight',
			'main_output_amount' => 'Main Output Amount',
			'main_output_weight' => 'Main Output Weight',
			'confirm_amount' => 'Confirm Amount',
			'confirm_weight' => 'Comfirm Weight',
			'confirm_status' => 'Confirm Status',
			'has_bonus_price' => 'Has Bonus Price',
			'comment' => 'Comment',
			'date_extract' => 'Date Extract',
			'travel' => 'Travel',
			'company_contact_id' => 'Company Contact',
			'company_contact_name' => 'Company Contact Name',
			'company_contact_mobile' => 'Company Contact Mobile',
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
		$criteria->compare('bonus_price',$this->bonus_price,true);
		$criteria->compare('detail_amount',$this->detail_amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('detail_send_amount',$this->detail_send_amount);
		$criteria->compare('detail_send_weight',$this->detail_send_weight,true);
		$criteria->compare('detail_output_amount',$this->detail_output_amount);
		$criteria->compare('detail_output_weight',$this->detail_output_weight,true);
		$criteria->compare('detail_warehouse_output_amount',$this->detail_warehouse_output_amount);
		$criteria->compare('detail_warehouse_output_weight',$this->detail_warehouse_output_weight,true);
		$criteria->compare('detail_card_id',$this->detail_card_id,true);
		$criteria->compare('detail_length',$this->detail_length);
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('product_code',$this->product_code,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('texture_name',$this->texture_name,true);
		$criteria->compare('texture_code',$this->texture_code,true);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('brand_code',$this->brand_code,true);
		$criteria->compare('rand_std',$this->rand_std,true);
		$criteria->compare('rand_name',$this->rand_name,true);
		$criteria->compare('rand_code',$this->rand_code,true);
		$criteria->compare('main_id',$this->main_id);
		$criteria->compare('main_type',$this->main_type,true);
		$criteria->compare('main_title_id',$this->main_title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('title_code',$this->title_code,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_short_name',$this->customer_short_name,true);
		$criteria->compare('customer_code',$this->customer_code,true);
		$criteria->compare('owner_company_id',$this->owner_company_id);
		$criteria->compare('owner_company_name',$this->owner_company_name,true);
		$criteria->compare('owner_company_short_name',$this->owner_company_short_name,true);
		$criteria->compare('owner_company_code',$this->owner_company_code,true);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('team_name',$this->team_name,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('warehouse_name',$this->warehouse_name,true);
		$criteria->compare('warehouse_code',$this->warehouse_code,true);
		$criteria->compare('main_amount',$this->main_amount);
		$criteria->compare('main_weight',$this->main_weight,true);
		$criteria->compare('main_output_amount',$this->main_output_amount);
		$criteria->compare('main_output_weight',$this->main_output_weight,true);
		$criteria->compare('confirm_amount',$this->confirm_amount);
		$criteria->compare('confirm_weight',$this->confirm_weight,true);
		$criteria->compare('confirm_status',$this->confirm_status);
		$criteria->compare('has_bonus_price',$this->has_bonus_price);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('date_extract',$this->date_extract);
		$criteria->compare('travel',$this->travel,true);
		$criteria->compare('company_contact_id',$this->company_contact_id);
		$criteria->compare('company_contact_name',$this->company_contact_name,true);
		$criteria->compare('company_contact_mobile',$this->company_contact_mobile,true);
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalesView the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
