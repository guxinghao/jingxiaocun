<?php

/**
 * This is the model class for table "dict_title".
 *
 * The followings are the available columns in table 'dict_title':
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property string $code
 * @property string $in_number
 * @property string $out_number
 *
 * The followings are the available model relations:
 * @property FrmBillOther[] $frmBillOthers
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
 * @property Storage[] $storages
 * @property Turnover[] $turnovers
 */
class DictTitleData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dict_title';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, short_name, code, in_number, out_number', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, short_name, code, in_number, out_number', 'safe', 'on'=>'search'),
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
			'frmBillOthers' => array(self::HAS_MANY, 'FrmBillOther', 'title_id'),
			'frmFormBills' => array(self::HAS_MANY, 'FrmFormBill', 'title_id'),
			'frmPurchases' => array(self::HAS_MANY, 'FrmPurchase', 'title_id'),
			'frmPurchaseContracts' => array(self::HAS_MANY, 'FrmPurchaseContract', 'dict_title_id'),
			'frmPurchaseInvoices' => array(self::HAS_MANY, 'FrmPurchaseInvoice', 'title_id'),
			'frmRebates' => array(self::HAS_MANY, 'FrmRebate', 'title_id'),
			'frmSales' => array(self::HAS_MANY, 'FrmSales', 'title_id'),
			'frmSalesInvoices' => array(self::HAS_MANY, 'FrmSalesInvoice', 'title_id'),
			'frmSalesReturns' => array(self::HAS_MANY, 'FrmSalesReturn', 'title_id'),
			'ownerTransfers' => array(self::HAS_MANY, 'OwnerTransfer', 'title_id'),
			'pledgeRedeemeds' => array(self::HAS_MANY, 'PledgeRedeemed', 'title_id'),
			'storages' => array(self::HAS_MANY, 'Storage', 'title_id'),
			'turnovers' => array(self::HAS_MANY, 'Turnover', 'title_id'),
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
			'code' => 'Code',
			'in_number' => 'In Number',
			'out_number' => 'Out Number',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('in_number',$this->in_number,true);
		$criteria->compare('out_number',$this->out_number,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictTitle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
