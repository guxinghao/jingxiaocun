<?php

/**
 * This is the model class for table "sales_view".
 *
 * The followings are the available columns in table 'sales_view':
 * @property integer $detail_id
 * @property string $detail_price
 * @property string $bonus_price
 * @property string $detail_amount
 * @property string $weight
 * @property string $detail_send_amount
 * @property string $detail_send_weight
 * @property string $detail_output_amount
 * @property string $detail_output_weight
 * @property string $detail_warehouse_output_amount
 * @property string $detail_warehouse_output_weight
 * @property string $detail_card_id
 * @property integer $detail_length
 * @property integer $product_id
 * @property string $detail_fee
 * @property string $product_std
 * @property string $product_name
 * @property string $product_code
 * @property integer $texture_id
 * @property string $texture_std
 * @property string $texture_name
 * @property string $texture_code
 * @property integer $brand_id
 * @property string $brand_std
 * @property string $brand_name
 * @property string $brand_code
 * @property integer $rank_id
 * @property string $rank_std
 * @property string $rank_name
 * @property string $rank_code
 * @property integer $main_id
 * @property string $main_type
 * @property integer $main_title_id
 * @property integer $can_push
 * @property string $title_name
 * @property string $title_code
 * @property integer $customer_id
 * @property string $customer_name
 * @property string $customer_short_name
 * @property string $customer_code
 * @property integer $owner_company_id
 * @property string $owner_company_name
 * @property string $owner_company_short_name
 * @property string $owner_company_code
 * @property integer $team_id
 * @property string $team_name
 * @property integer $is_yidan
 * @property integer $warehouse_id
 * @property string $warehouse_name
 * @property string $warehouse_code
 * @property integer $main_amount
 * @property string $main_weight
 * @property integer $main_output_amount
 * @property string $main_output_weight
 * @property integer $confirm_amount
 * @property string $confirm_weight
 * @property integer $confirm_status
 * @property integer $pre_amount
 * @property string $pre_weight
 * @property integer $has_bonus_price
 * @property string $comment
 * @property integer $date_extract
 * @property string $travel
 * @property integer $company_contact_id
 * @property string $company_contact_name
 * @property string $company_contact_mobile
 * @property integer $common_id
 * @property string $form_type
 * @property string $form_sn
 * @property integer $created_at
 * @property integer $created_by
 * @property string $created_by_nickname
 * @property string $form_time
 * @property string $form_status
 * @property integer $approved_at
 * @property integer $approved_by
 * @property string $approved_by_nickname
 * @property integer $owned_by
 * @property string $owned_by_nickname
 * @property integer $is_deleted
 * @property integer $last_update
 * @property integer $last_updated_by
 * @property string $last_updated_by_nickname
 * @property string $delete_reason
 */
class SalesViewData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sales_view';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('detail_id, detail_length, product_id, texture_id, brand_id, rank_id, main_id, main_title_id, can_push, customer_id, owner_company_id, team_id, is_yidan, warehouse_id, main_amount, main_output_amount, confirm_amount, confirm_status, pre_amount, has_bonus_price, date_extract, company_contact_id, common_id, created_at, created_by, approved_at, approved_by, owned_by, is_deleted, last_update, last_updated_by', 'numerical', 'integerOnly'=>true),
			array('detail_price, bonus_price', 'length', 'max'=>11),
			array('detail_amount, detail_send_amount, detail_output_amount, detail_warehouse_output_amount', 'length', 'max'=>32),
			array('weight, detail_send_weight, detail_output_weight, detail_warehouse_output_weight', 'length', 'max'=>37),
			array('detail_card_id, product_std, product_name, product_code, texture_std, texture_name, texture_code, brand_std, brand_name, brand_code, rank_std, rank_name, rank_code, main_type, title_name, title_code, customer_short_name, customer_code, owner_company_short_name, owner_company_code, team_name, warehouse_name, warehouse_code, company_contact_name, company_contact_mobile, form_type, form_sn, created_by_nickname, approved_by_nickname, owned_by_nickname, last_updated_by_nickname', 'length', 'max'=>45),
			array('detail_fee', 'length', 'max'=>33),
			array('customer_name, owner_company_name', 'length', 'max'=>50),
			array('main_weight, main_output_weight, confirm_weight, pre_weight', 'length', 'max'=>15),
			array('comment, travel, delete_reason', 'length', 'max'=>255),
			array('form_status', 'length', 'max'=>20),
			array('form_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('detail_id, detail_price, bonus_price, detail_amount, weight, detail_send_amount, detail_send_weight, detail_output_amount, detail_output_weight, detail_warehouse_output_amount, detail_warehouse_output_weight, detail_card_id, detail_length, product_id, detail_fee, product_std, product_name, product_code, texture_id, texture_std, texture_name, texture_code, brand_id, brand_std, brand_name, brand_code, rank_id, rank_std, rank_name, rank_code, main_id, main_type, main_title_id, can_push, title_name, title_code, customer_id, customer_name, customer_short_name, customer_code, owner_company_id, owner_company_name, owner_company_short_name, owner_company_code, team_id, team_name, is_yidan, warehouse_id, warehouse_name, warehouse_code, main_amount, main_weight, main_output_amount, main_output_weight, confirm_amount, confirm_weight, confirm_status, pre_amount, pre_weight, has_bonus_price, comment, date_extract, travel, company_contact_id, company_contact_name, company_contact_mobile, common_id, form_type, form_sn, created_at, created_by, created_by_nickname, form_time, form_status, approved_at, approved_by, approved_by_nickname, owned_by, owned_by_nickname, is_deleted, last_update, last_updated_by, last_updated_by_nickname, delete_reason', 'safe', 'on'=>'search'),
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
			'product_id' => 'Product',
			'detail_fee' => 'Detail Fee',
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
			'can_push' => 'Can Push',
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
			'confirm_weight' => 'Confirm Weight',
			'confirm_status' => 'Confirm Status',
			'pre_amount' => 'Pre Amount',
			'pre_weight' => 'Pre Weight',
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
			'delete_reason' => 'Delete Reason',
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
		$criteria->compare('detail_amount',$this->detail_amount,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('detail_send_amount',$this->detail_send_amount,true);
		$criteria->compare('detail_send_weight',$this->detail_send_weight,true);
		$criteria->compare('detail_output_amount',$this->detail_output_amount,true);
		$criteria->compare('detail_output_weight',$this->detail_output_weight,true);
		$criteria->compare('detail_warehouse_output_amount',$this->detail_warehouse_output_amount,true);
		$criteria->compare('detail_warehouse_output_weight',$this->detail_warehouse_output_weight,true);
		$criteria->compare('detail_card_id',$this->detail_card_id,true);
		$criteria->compare('detail_length',$this->detail_length);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('detail_fee',$this->detail_fee,true);
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
		$criteria->compare('can_push',$this->can_push);
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
		$criteria->compare('pre_amount',$this->pre_amount);
		$criteria->compare('pre_weight',$this->pre_weight,true);
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
		$criteria->compare('delete_reason',$this->delete_reason,true);

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
