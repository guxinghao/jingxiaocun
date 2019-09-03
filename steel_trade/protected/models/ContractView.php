<?php

/**
 * This is the biz model class for table "contract_view".
 *
 */
class ContractView extends ContractViewData
{
	
	public $total_amount;
	public $total_weight;
	public $total_money;
	public $total_num;

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
			'detail_amount' => 'Detail Amount',
			'detail_weight' => 'Detail Weight',
			'detail_purchased_amount' => 'Detail Purchased Amount',
			'detail_purchased_weight' => 'Detail Purchased Weight',
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
			'contract_no' => 'Contract No',
			'dict_company_id' => 'Dict Company',
			'dict_company_name' => 'Dict Company Name',
			'dict_company_short_name' => 'Dict Company Short Name',
			'dict_company_code' => 'Dict Company Code',
			'dict_title_id' => 'Dict Title',
			'title_name' => 'Title Name',
			'title_code' => 'Title Code',
			'team_id' => 'Team',
			'team_name' => 'Team Name',
			'is_yidan' => 'Is Yidan',
			'contact_id' => 'Contact',
			'company_contact_name' => 'Company Contact Name',
			'company_contact_mobile' => 'Company Contact Mobile',
			'warehouse_id' => 'Warehouse',
			'warehouse_name' => 'Warehouse Name',
			'warehouse_code' => 'Warehouse Code',
			'main_amount' => 'Main Amount',
			'main_weight' => 'Main Weight',
			'main_purchase_amount' => 'Main Purchase Amount',
			'main_purchase_weight' => 'Main Purchase Weight',
			'is_finish' => 'Is Finish',
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
		$criteria->compare('detail_purchased_amount',$this->detail_purchased_amount);
		$criteria->compare('detail_purchased_weight',$this->detail_purchased_weight,true);
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
		$criteria->compare('contract_no',$this->contract_no,true);
		$criteria->compare('dict_company_id',$this->dict_company_id);
		$criteria->compare('dict_company_name',$this->dict_company_name,true);
		$criteria->compare('dict_company_short_name',$this->dict_company_short_name,true);
		$criteria->compare('dict_company_code',$this->dict_company_code,true);
		$criteria->compare('dict_title_id',$this->dict_title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('title_code',$this->title_code,true);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('team_name',$this->team_name,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('company_contact_name',$this->company_contact_name,true);
		$criteria->compare('company_contact_mobile',$this->company_contact_mobile,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('warehouse_name',$this->warehouse_name,true);
		$criteria->compare('warehouse_code',$this->warehouse_code,true);
		$criteria->compare('main_amount',$this->main_amount);
		$criteria->compare('main_weight',$this->main_weight,true);
		$criteria->compare('main_purchase_amount',$this->main_purchase_amount);
		$criteria->compare('main_purchase_weight',$this->main_purchase_weight,true);
		$criteria->compare('is_finish',$this->is_finish);
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
	 * @return ContractView the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
