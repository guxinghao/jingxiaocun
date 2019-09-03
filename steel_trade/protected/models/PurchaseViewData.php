<?php

/**
 * This is the model class for table "purchase_view".
 *
 * The followings are the available columns in table 'purchase_view':
 * @property integer $detail_id
 * @property string $detail_price
 * @property integer $detail_amount
 * @property string $detail_weight
 * @property integer $detail_input_amount
 * @property string $detail_input_weight
 * @property integer $detail_fix_amount
 * @property string $detail_fix_weight
 * @property string $detail_fix_price
 * @property string $detail_cost_price
 * @property string $detail_invoice_price
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
 * @property integer $reach_time
 * @property string $purchase_type
 * @property integer $supply_id
 * @property string $supply_name
 * @property string $supply_short_name
 * @property string $supply_code
 * @property integer $title_id
 * @property string $title_name
 * @property string $title_short_name
 * @property string $title_code
 * @property integer $is_yidan
 * @property integer $contact_id
 * @property string $company_contact_name
 * @property string $company_contact_mobile
 * @property integer $warehouse_id
 * @property string $warehouse_name
 * @property string $warehouse_code
 * @property integer $main_amount
 * @property string $main_weight
 * @property integer $main_input_amount
 * @property string $main_input_weight
 * @property integer $weight_confirm_status
 * @property integer $price_confirm_status
 * @property string $main_invoice_cost
 * @property integer $main_confirm_amount
 * @property string $main_confirm_weight
 * @property string $main_confirm_cost
 * @property integer $team_id
 * @property string $team_name
 * @property string $rebate
 * @property string $ware_rebate
 * @property string $ware_cost
 * @property integer $frm_contract_id
 * @property string $contract_no
 * @property integer $date_reach
 * @property string $transfer_number
 * @property integer $contain_cash
 * @property string $price_amount
 * @property string $shipment
 * @property integer $can_push
 * @property integer $bill_done
 * @property string $pledge_name
 * @property string $pledge_short_name
 * @property string $pledge_fee
 * @property string $pledge_unit_price
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
 * @property string $delete_reason
 */
class PurchaseViewData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_view';
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
			array('detail_id, detail_amount, detail_input_amount, detail_fix_amount, length, product_id, texture_id, brand_id, rank_id, main_id, reach_time, supply_id, title_id, is_yidan, contact_id, warehouse_id, main_amount, main_input_amount, weight_confirm_status, price_confirm_status, main_confirm_amount, team_id, frm_contract_id, date_reach, contain_cash, can_push, bill_done, common_id, created_at, created_by, approved_at, approved_by, owned_by, is_deleted, last_update, last_updated_by', 'numerical', 'integerOnly'=>true),
			array('detail_price, detail_fix_price, detail_cost_price, detail_invoice_price, main_invoice_cost, rebate, ware_rebate, ware_cost, price_amount, shipment, pledge_fee, pledge_unit_price', 'length', 'max'=>11),
			array('detail_weight, detail_input_weight, detail_fix_weight, main_weight, main_input_weight, main_confirm_weight, main_confirm_cost', 'length', 'max'=>15),
			array('product_std, product_name, product_code, texture_std, texture_name, texture_code, brand_std, brand_name, brand_code, rank_std, rank_name, rank_code, purchase_type, supply_short_name, supply_code, title_name, title_short_name, title_code, company_contact_name, company_contact_mobile, warehouse_name, warehouse_code, team_name, transfer_number, pledge_short_name, form_type, form_sn, created_by_nickname, approved_by_nickname, owned_by_nickname, last_updated_by_nickname', 'length', 'max'=>45),
			array('supply_name, pledge_name', 'length', 'max'=>50),
			array('contract_no', 'length', 'max'=>100),
			array('form_status', 'length', 'max'=>20),
			array('comment, delete_reason', 'length', 'max'=>255),
			array('form_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('detail_id, detail_price, detail_amount, detail_weight, detail_input_amount, detail_input_weight, detail_fix_amount, detail_fix_weight, detail_fix_price, detail_cost_price, detail_invoice_price, length, product_id, product_std, product_name, product_code, texture_id, texture_std, texture_name, texture_code, brand_id, brand_std, brand_name, brand_code, rank_id, rank_std, rank_name, rank_code, main_id, reach_time, purchase_type, supply_id, supply_name, supply_short_name, supply_code, title_id, title_name, title_short_name, title_code, is_yidan, contact_id, company_contact_name, company_contact_mobile, warehouse_id, warehouse_name, warehouse_code, main_amount, main_weight, main_input_amount, main_input_weight, weight_confirm_status, price_confirm_status, main_invoice_cost, main_confirm_amount, main_confirm_weight, main_confirm_cost, team_id, team_name, rebate, ware_rebate, ware_cost, frm_contract_id, contract_no, date_reach, transfer_number, contain_cash, price_amount, shipment, can_push, bill_done, pledge_name, pledge_short_name, pledge_fee, pledge_unit_price, common_id, form_type, form_sn, created_at, created_by, created_by_nickname, form_time, form_status, approved_at, approved_by, approved_by_nickname, owned_by, owned_by_nickname, is_deleted, last_update, last_updated_by, last_updated_by_nickname, comment, delete_reason', 'safe', 'on'=>'search'),
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
			'reach_time' => 'Reach Time',
			'purchase_type' => 'Purchase Type',
			'supply_id' => 'Supply',
			'supply_name' => 'Supply Name',
			'supply_short_name' => 'Supply Short Name',
			'supply_code' => 'Supply Code',
			'title_id' => 'Title',
			'title_name' => 'Title Name',
			'title_short_name' => 'Title Short Name',
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
			'rebate' => 'Rebate',
			'ware_rebate' => 'Ware Rebate',
			'ware_cost' => 'Ware Cost',
			'frm_contract_id' => 'Frm Contract',
			'contract_no' => 'Contract No',
			'date_reach' => 'Date Reach',
			'transfer_number' => 'Transfer Number',
			'contain_cash' => 'Contain Cash',
			'price_amount' => 'Price Amount',
			'shipment' => 'Shipment',
			'can_push' => 'Can Push',
			'bill_done' => 'Bill Done',
			'pledge_name' => 'Pledge Name',
			'pledge_short_name' => 'Pledge Short Name',
			'pledge_fee' => 'Pledge Fee',
			'pledge_unit_price' => 'Pledge Unit Price',
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
		$criteria->compare('reach_time',$this->reach_time);
		$criteria->compare('purchase_type',$this->purchase_type,true);
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('supply_name',$this->supply_name,true);
		$criteria->compare('supply_short_name',$this->supply_short_name,true);
		$criteria->compare('supply_code',$this->supply_code,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('title_short_name',$this->title_short_name,true);
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
		$criteria->compare('rebate',$this->rebate,true);
		$criteria->compare('ware_rebate',$this->ware_rebate,true);
		$criteria->compare('ware_cost',$this->ware_cost,true);
		$criteria->compare('frm_contract_id',$this->frm_contract_id);
		$criteria->compare('contract_no',$this->contract_no,true);
		$criteria->compare('date_reach',$this->date_reach);
		$criteria->compare('transfer_number',$this->transfer_number,true);
		$criteria->compare('contain_cash',$this->contain_cash);
		$criteria->compare('price_amount',$this->price_amount,true);
		$criteria->compare('shipment',$this->shipment,true);
		$criteria->compare('can_push',$this->can_push);
		$criteria->compare('bill_done',$this->bill_done);
		$criteria->compare('pledge_name',$this->pledge_name,true);
		$criteria->compare('pledge_short_name',$this->pledge_short_name,true);
		$criteria->compare('pledge_fee',$this->pledge_fee,true);
		$criteria->compare('pledge_unit_price',$this->pledge_unit_price,true);
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
		$criteria->compare('delete_reason',$this->delete_reason,true);

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
