<?php

/**
 * This is the model class for table "contract_view".
 *
 * The followings are the available columns in table 'contract_view':
 * @property integer $detail_id
 * @property string $detail_price
 * @property integer $detail_amount
 * @property string $detail_weight
 * @property integer $detail_purchased_amount
 * @property string $detail_purchased_weight
 * @property integer $length
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
 * @property string $contract_no
 * @property integer $dict_company_id
 * @property string $dict_company_name
 * @property string $dict_company_short_name
 * @property string $dict_company_code
 * @property integer $dict_title_id
 * @property string $title_name
 * @property string $title_code
 * @property integer $team_id
 * @property string $team_name
 * @property integer $is_yidan
 * @property integer $contact_id
 * @property string $company_contact_name
 * @property string $company_contact_mobile
 * @property integer $warehouse_id
 * @property string $warehouse_name
 * @property string $warehouse_code
 * @property integer $main_amount
 * @property string $main_weight
 * @property integer $main_purchase_amount
 * @property string $main_purchase_weight
 * @property integer $is_finish
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
 * @property string $comment
 */
class ContractViewData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contract_view';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, texture_id, brand_id, rank_id', 'required'),
			array('detail_id, detail_amount, detail_purchased_amount, length, product_id, texture_id, brand_id, rank_id, main_id, dict_company_id, dict_title_id, team_id, is_yidan, contact_id, warehouse_id, main_amount, main_purchase_amount, is_finish, common_id, created_at, created_by, approved_at, approved_by, owned_by, is_deleted, last_update, last_updated_by', 'numerical', 'integerOnly'=>true),
			array('detail_price', 'length', 'max'=>11),
			array('detail_weight, detail_purchased_weight, main_weight, main_purchase_weight', 'length', 'max'=>15),
			array('product_std, product_name, product_code, texture_std, texture_name, texture_code, brand_std, brand_name, brand_code, rank_std, rank_name, rank_code, dict_company_short_name, dict_company_code, title_name, title_code, team_name, company_contact_name, company_contact_mobile, warehouse_name, warehouse_code, form_type, form_sn, created_by_nickname, approved_by_nickname, owned_by_nickname, last_updated_by_nickname', 'length', 'max'=>45),
			array('contract_no', 'length', 'max'=>100),
			array('dict_company_name', 'length', 'max'=>50),
			array('form_status', 'length', 'max'=>20),
			array('comment', 'length', 'max'=>255),
			array('form_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('detail_id, detail_price, detail_amount, detail_weight, detail_purchased_amount, detail_purchased_weight, length, product_id, product_std, product_name, product_code, texture_id, texture_std, texture_name, texture_code, brand_id, brand_std, brand_name, brand_code, rank_id, rank_std, rank_name, rank_code, main_id, contract_no, dict_company_id, dict_company_name, dict_company_short_name, dict_company_code, dict_title_id, title_name, title_code, team_id, team_name, is_yidan, contact_id, company_contact_name, company_contact_mobile, warehouse_id, warehouse_name, warehouse_code, main_amount, main_weight, main_purchase_amount, main_purchase_weight, is_finish, common_id, form_type, form_sn, created_at, created_by, created_by_nickname, form_time, form_status, approved_at, approved_by, approved_by_nickname, owned_by, owned_by_nickname, is_deleted, last_update, last_updated_by, last_updated_by_nickname, comment', 'safe', 'on'=>'search'),
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
