<?php

/**
 * This is the model class for table "dict_company".
 *
 * The followings are the available columns in table 'dict_company':
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property integer $is_supply
 * @property integer $is_pledge
 * @property integer $is_customer
 * @property integer $is_logistics
 * @property integer $is_dx
 * @property integer $is_gk
 * @property integer $is_warehouse
 * @property string $code
 * @property integer $created_at
 * @property integer $created_by
 * @property string $fee
 * @property integer $level
 * @property integer $pledge_length
 * @property string $pledge_rate
 * @property integer $priority
 * @property integer $su_priority
 * @property integer $loan_priority
 *
 * The followings are the available model relations:
 * @property BankInfo[] $bankInfos
 * @property FrmFormBill[] $frmFormBills
 * @property FrmPurchase[] $frmPurchases
 * @property FrmPurchaseContract[] $frmPurchaseContracts
 * @property FrmPurchaseInvoice[] $frmPurchaseInvoices
 * @property FrmRebate[] $frmRebates
 * @property FrmSales[] $frmSales
 * @property FrmSalesInvoice[] $frmSalesInvoices
 * @property FrmSalesReturn[] $frmSalesReturns
 * @property OwnerTransfer[] $ownerTransfers
 * @property PledgeRedeemed[] $pledgeRedeemeds
 * @property Turnover[] $turnovers
 */
class DictCompanyData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dict_company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_supply, is_pledge, is_customer, is_logistics, is_dx, is_gk, is_warehouse, created_at, created_by, level, pledge_length, priority, su_priority, loan_priority', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('short_name, code', 'length', 'max'=>45),
			array('fee', 'length', 'max'=>11),
			array('pledge_rate', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, short_name, is_supply, is_pledge, is_customer, is_logistics, is_dx, is_gk, is_warehouse, code, created_at, created_by, fee, level, pledge_length, pledge_rate, priority, su_priority, loan_priority', 'safe', 'on'=>'search'),
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
			'bankInfos' => array(self::HAS_MANY, 'BankInfo', 'dict_company_id'),
			'frmFormBills' => array(self::HAS_MANY, 'FrmFormBill', 'company_id'),
			'frmPurchases' => array(self::HAS_MANY, 'FrmPurchase', 'supply_id'),
			'frmPurchaseContracts' => array(self::HAS_MANY, 'FrmPurchaseContract', 'dict_company_id'),
			'frmPurchaseInvoices' => array(self::HAS_MANY, 'FrmPurchaseInvoice', 'company_id'),
			'frmRebates' => array(self::HAS_MANY, 'FrmRebate', 'company_id'),
			'frmSales' => array(self::HAS_MANY, 'FrmSales', 'customer_id'),
			'frmSalesInvoices' => array(self::HAS_MANY, 'FrmSalesInvoice', 'company_id'),
			'frmSalesReturns' => array(self::HAS_MANY, 'FrmSalesReturn', 'company_id'),
			'ownerTransfers' => array(self::HAS_MANY, 'OwnerTransfer', 'company_id'),
			'pledgeRedeemeds' => array(self::HAS_MANY, 'PledgeRedeemed', 'company_id'),
			'turnovers' => array(self::HAS_MANY, 'Turnover', 'target_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'short_name' => 'Short Name',
			'is_supply' => 'Is Supply',
			'is_pledge' => 'Is Pledge',
			'is_customer' => 'Is Customer',
			'is_logistics' => 'Is Logistics',
			'is_dx' => 'Is Dx',
			'is_gk' => 'Is Gk',
			'is_warehouse' => 'Is Warehouse',
			'code' => 'Code',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'fee' => 'Fee',
			'level' => 'Level',
			'pledge_length' => 'Pledge Length',
			'pledge_rate' => 'Pledge Rate',
			'priority' => 'Priority',
			'su_priority' => 'Su Priority',
			'loan_priority' => 'Loan Priority',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('is_supply',$this->is_supply);
		$criteria->compare('is_pledge',$this->is_pledge);
		$criteria->compare('is_customer',$this->is_customer);
		$criteria->compare('is_logistics',$this->is_logistics);
		$criteria->compare('is_dx',$this->is_dx);
		$criteria->compare('is_gk',$this->is_gk);
		$criteria->compare('is_warehouse',$this->is_warehouse);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('pledge_length',$this->pledge_length);
		$criteria->compare('pledge_rate',$this->pledge_rate,true);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('su_priority',$this->su_priority);
		$criteria->compare('loan_priority',$this->loan_priority);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictCompany the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
