<?php

/**
 * This is the biz model class for table "rank_view".
 *
 */
class RankView extends RankViewData
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
			'detail_amount' => 'Detail Amount',
			'weight' => 'Weight',
			'detail_output_amount' => 'Detail Output Amount',
			'detail_output_weight' => 'Detail Output Weight',
			'detail_fee' => 'Detail Fee',
			'rebate_fee' => 'Rebate Fee',
			'detail_length' => 'Detail Length',
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
			'main_type' => 'Main Type',
			'main_title_id' => 'Main Title',
			'title_name' => 'Title Name',
			'title_code' => 'Title Code',
			'customer_id' => 'Customer',
			'customer_name' => 'Customer Name',
			'customer_short_name' => 'Customer Short Name',
			'customer_code' => 'Customer Code',
			'client_id' => 'Client',
			'client_name' => 'Client Name',
			'client_short_name' => 'Client Short Name',
			'client_code' => 'Client Code',
			'is_yidan' => 'Is Yidan',
			'main_amount' => 'Main Amount',
			'main_weight' => 'Main Weight',
			'confirm_status' => 'Confirm Status',
			'common_id' => 'Common',
			'form_type' => 'Form Type',
			'form_sn' => 'Form Sn',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'form_time' => 'Form Time',
			'form_status' => 'Form Status',
			'owned_by' => 'Owned By',
			'owned_by_nickname' => 'Owned By Nickname',
			'is_deleted' => 'Is Deleted',
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
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('detail_output_amount',$this->detail_output_amount);
		$criteria->compare('detail_output_weight',$this->detail_output_weight,true);
		$criteria->compare('detail_fee',$this->detail_fee,true);
		$criteria->compare('rebate_fee',$this->rebate_fee,true);
		$criteria->compare('detail_length',$this->detail_length);
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
		$criteria->compare('main_type',$this->main_type,true);
		$criteria->compare('main_title_id',$this->main_title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('title_code',$this->title_code,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_short_name',$this->customer_short_name,true);
		$criteria->compare('customer_code',$this->customer_code,true);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('client_name',$this->client_name,true);
		$criteria->compare('client_short_name',$this->client_short_name,true);
		$criteria->compare('client_code',$this->client_code,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('main_amount',$this->main_amount);
		$criteria->compare('main_weight',$this->main_weight,true);
		$criteria->compare('confirm_status',$this->confirm_status);
		$criteria->compare('common_id',$this->common_id);
		$criteria->compare('form_type',$this->form_type,true);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('form_time',$this->form_time,true);
		$criteria->compare('form_status',$this->form_status,true);
		$criteria->compare('owned_by',$this->owned_by);
		$criteria->compare('owned_by_nickname',$this->owned_by_nickname,true);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RankView the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
