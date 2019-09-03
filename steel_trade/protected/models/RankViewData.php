<?php

/**
 * This is the model class for table "rank_view".
 *
 * The followings are the available columns in table 'rank_view':
 * @property integer $detail_id
 * @property string $detail_price
 * @property integer $detail_amount
 * @property string $weight
 * @property integer $detail_output_amount
 * @property string $detail_output_weight
 * @property string $detail_fee
 * @property string $rebate_fee
 * @property integer $detail_length
 * @property integer $product_id
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
 * @property string $title_name
 * @property string $title_code
 * @property integer $customer_id
 * @property string $customer_name
 * @property string $customer_short_name
 * @property string $customer_code
 * @property integer $client_id
 * @property string $client_name
 * @property string $client_short_name
 * @property string $client_code
 * @property integer $is_yidan
 * @property integer $main_amount
 * @property string $main_weight
 * @property integer $confirm_status
 * @property integer $common_id
 * @property string $form_type
 * @property string $form_sn
 * @property integer $created_at
 * @property integer $created_by
 * @property string $form_time
 * @property string $form_status
 * @property integer $owned_by
 * @property string $owned_by_nickname
 * @property integer $is_deleted
 */
class RankViewData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rank_view';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('detail_id, detail_amount, detail_output_amount, detail_length, product_id, texture_id, brand_id, rank_id, main_id, main_title_id, customer_id, client_id, is_yidan, main_amount, confirm_status, common_id, created_at, created_by, owned_by, is_deleted', 'numerical', 'integerOnly'=>true),
			array('detail_price', 'length', 'max'=>11),
			array('weight', 'length', 'max'=>16),
			array('detail_output_weight, main_weight', 'length', 'max'=>15),
			array('detail_fee', 'length', 'max'=>26),
			array('rebate_fee', 'length', 'max'=>30),
			array('product_std, product_name, product_code, texture_std, texture_name, texture_code, brand_std, brand_name, brand_code, rank_std, rank_name, rank_code, main_type, title_name, title_code, customer_short_name, customer_code, client_short_name, client_code, form_type, form_sn, owned_by_nickname', 'length', 'max'=>45),
			array('customer_name, client_name', 'length', 'max'=>50),
			array('form_status', 'length', 'max'=>20),
			array('form_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('detail_id, detail_price, detail_amount, weight, detail_output_amount, detail_output_weight, detail_fee, rebate_fee, detail_length, product_id, product_std, product_name, product_code, texture_id, texture_std, texture_name, texture_code, brand_id, brand_std, brand_name, brand_code, rank_id, rank_std, rank_name, rank_code, main_id, main_type, main_title_id, title_name, title_code, customer_id, customer_name, customer_short_name, customer_code, client_id, client_name, client_short_name, client_code, is_yidan, main_amount, main_weight, confirm_status, common_id, form_type, form_sn, created_at, created_by, form_time, form_status, owned_by, owned_by_nickname, is_deleted', 'safe', 'on'=>'search'),
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
