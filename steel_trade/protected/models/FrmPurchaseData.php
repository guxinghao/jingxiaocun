<?php

/**
 * This is the model class for table "frm_purchase".
 *
 * The followings are the available columns in table 'frm_purchase':
 * @property integer $id
 * @property string $purchase_type
 * @property integer $supply_id
 * @property integer $title_id
 * @property integer $is_yidan
 * @property integer $contact_id
 * @property integer $warehouse_id
 * @property integer $amount
 * @property string $weight
 * @property integer $input_amount
 * @property string $input_weight
 * @property integer $weight_confirm_status
 * @property integer $price_confirm_status
 * @property string $invoice_cost
 * @property integer $confirm_amount
 * @property string $confirm_weight
 * @property string $confirm_cost
 * @property integer $team_id
 * @property integer $frm_contract_id
 * @property integer $date_reach
 * @property integer $reach_time
 * @property string $transfer_number
 * @property integer $contain_cash
 * @property string $price_amount
 * @property integer $plan_amount
 * @property string $plan_weight
 * @property string $shipment
 * @property string $rebate
 * @property string $ware_rebate
 * @property string $ware_cost
 * @property string $pledge_rate
 * @property integer $can_push
 * @property integer $bill_done
 * @property string $e_rebate
 * @property string $e_ware_rebate
 * @property string $e_ware_cost_other
 * @property string $e_ware_cost_lwg
 *
 * The followings are the available model relations:
 * @property FrmPledgeRedeem[] $frmPledgeRedeems
 * @property DictCompany $supply
 * @property DictTitle $title
 * @property Warehouse $warehouse
 * @property Team $team
 * @property PledgeInfo[] $pledgeInfos
 * @property PurchaseDetail[] $purchaseDetails
 */
class FrmPurchaseData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_purchase';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supply_id, title_id, is_yidan, contact_id, warehouse_id, amount, input_amount, weight_confirm_status, price_confirm_status, confirm_amount, team_id, frm_contract_id, date_reach, reach_time, contain_cash, plan_amount, can_push, bill_done', 'numerical', 'integerOnly'=>true),
			array('purchase_type, transfer_number, e_ware_cost_lwg', 'length', 'max'=>45),
			array('weight, input_weight, confirm_weight, confirm_cost, plan_weight, rebate, ware_rebate, ware_cost, pledge_rate', 'length', 'max'=>15),
			array('invoice_cost, price_amount, shipment, e_rebate, e_ware_rebate, e_ware_cost_other', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, purchase_type, supply_id, title_id, is_yidan, contact_id, warehouse_id, amount, weight, input_amount, input_weight, weight_confirm_status, price_confirm_status, invoice_cost, confirm_amount, confirm_weight, confirm_cost, team_id, frm_contract_id, date_reach, reach_time, transfer_number, contain_cash, price_amount, plan_amount, plan_weight, shipment, rebate, ware_rebate, ware_cost, pledge_rate, can_push, bill_done, e_rebate, e_ware_rebate, e_ware_cost_other, e_ware_cost_lwg', 'safe', 'on'=>'search'),
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
			'frmPledgeRedeems' => array(self::HAS_MANY, 'FrmPledgeRedeem', 'purchase_id'),
			'supply' => array(self::BELONGS_TO, 'DictCompany', 'supply_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
			'pledgeInfos' => array(self::HAS_MANY, 'PledgeInfo', 'frm_purchase_id'),
			'purchaseDetails' => array(self::HAS_MANY, 'PurchaseDetail', 'purchase_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'purchase_type' => 'Purchase Type',
			'supply_id' => 'Supply',
			'title_id' => 'Title',
			'is_yidan' => 'Is Yidan',
			'contact_id' => 'Contact',
			'warehouse_id' => 'Warehouse',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'weight_confirm_status' => 'Weight Confirm Status',
			'price_confirm_status' => 'Price Confirm Status',
			'invoice_cost' => 'Invoice Cost',
			'confirm_amount' => 'Confirm Amount',
			'confirm_weight' => 'Confirm Weight',
			'confirm_cost' => 'Confirm Cost',
			'team_id' => 'Team',
			'frm_contract_id' => 'Frm Contract',
			'date_reach' => 'Date Reach',
			'reach_time' => 'Reach Time',
			'transfer_number' => 'Transfer Number',
			'contain_cash' => 'Contain Cash',
			'price_amount' => 'Price Amount',
			'plan_amount' => 'Plan Amount',
			'plan_weight' => 'Plan Weight',
			'shipment' => 'Shipment',
			'rebate' => 'Rebate',
			'ware_rebate' => 'Ware Rebate',
			'ware_cost' => 'Ware Cost',
			'pledge_rate' => 'Pledge Rate',
			'can_push' => 'Can Push',
			'bill_done' => 'Bill Done',
			'e_rebate' => 'E Rebate',
			'e_ware_rebate' => 'E Ware Rebate',
			'e_ware_cost_other' => 'E Ware Cost Other',
			'e_ware_cost_lwg' => 'E Ware Cost Lwg',
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
		$criteria->compare('purchase_type',$this->purchase_type,true);
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('weight_confirm_status',$this->weight_confirm_status);
		$criteria->compare('price_confirm_status',$this->price_confirm_status);
		$criteria->compare('invoice_cost',$this->invoice_cost,true);
		$criteria->compare('confirm_amount',$this->confirm_amount);
		$criteria->compare('confirm_weight',$this->confirm_weight,true);
		$criteria->compare('confirm_cost',$this->confirm_cost,true);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('frm_contract_id',$this->frm_contract_id);
		$criteria->compare('date_reach',$this->date_reach);
		$criteria->compare('reach_time',$this->reach_time);
		$criteria->compare('transfer_number',$this->transfer_number,true);
		$criteria->compare('contain_cash',$this->contain_cash);
		$criteria->compare('price_amount',$this->price_amount,true);
		$criteria->compare('plan_amount',$this->plan_amount);
		$criteria->compare('plan_weight',$this->plan_weight,true);
		$criteria->compare('shipment',$this->shipment,true);
		$criteria->compare('rebate',$this->rebate,true);
		$criteria->compare('ware_rebate',$this->ware_rebate,true);
		$criteria->compare('ware_cost',$this->ware_cost,true);
		$criteria->compare('pledge_rate',$this->pledge_rate,true);
		$criteria->compare('can_push',$this->can_push);
		$criteria->compare('bill_done',$this->bill_done);
		$criteria->compare('e_rebate',$this->e_rebate,true);
		$criteria->compare('e_ware_rebate',$this->e_ware_rebate,true);
		$criteria->compare('e_ware_cost_other',$this->e_ware_cost_other,true);
		$criteria->compare('e_ware_cost_lwg',$this->e_ware_cost_lwg,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPurchase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
