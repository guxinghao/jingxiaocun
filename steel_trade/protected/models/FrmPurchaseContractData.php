<?php

/**
 * This is the model class for table "frm_purchase_contract".
 *
 * The followings are the available columns in table 'frm_purchase_contract':
 * @property integer $id
 * @property string $contract_no
 * @property integer $dict_company_id
 * @property integer $dict_title_id
 * @property integer $team_id
 * @property integer $is_yidan
 * @property integer $contact_id
 * @property integer $warehouse_id
 * @property integer $amount
 * @property string $weight
 * @property string $fee
 * @property integer $purchase_amount
 * @property string $purchase_weight
 * @property string $purchase_fee
 * @property integer $is_finish
 *
 * The followings are the available model relations:
 * @property DictCompany $dictCompany
 * @property DictTitle $dictTitle
 * @property Warehouse $warehouse
 * @property PurchaseContractDetail[] $purchaseContractDetails
 */
class FrmPurchaseContractData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_purchase_contract';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dict_company_id, dict_title_id, team_id, is_yidan, contact_id, warehouse_id, amount, purchase_amount, is_finish', 'numerical', 'integerOnly'=>true),
			array('contract_no', 'length', 'max'=>100),
			array('weight, purchase_weight', 'length', 'max'=>15),
			array('fee, purchase_fee', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, contract_no, dict_company_id, dict_title_id, team_id, is_yidan, contact_id, warehouse_id, amount, weight, fee, purchase_amount, purchase_weight, purchase_fee, is_finish', 'safe', 'on'=>'search'),
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
			'dictCompany' => array(self::BELONGS_TO, 'DictCompany', 'dict_company_id'),
			'dictTitle' => array(self::BELONGS_TO, 'DictTitle', 'dict_title_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'purchaseContractDetails' => array(self::HAS_MANY, 'PurchaseContractDetail', 'purchase_contract_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'contract_no' => 'Contract No',
			'dict_company_id' => 'Dict Company',
			'dict_title_id' => 'Dict Title',
			'team_id' => 'Team',
			'is_yidan' => 'Is Yidan',
			'contact_id' => 'Contact',
			'warehouse_id' => 'Warehouse',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'fee' => 'Fee',
			'purchase_amount' => 'Purchase Amount',
			'purchase_weight' => 'Purchase Weight',
			'purchase_fee' => 'Purchase Fee',
			'is_finish' => 'Is Finish',
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
		$criteria->compare('contract_no',$this->contract_no,true);
		$criteria->compare('dict_company_id',$this->dict_company_id);
		$criteria->compare('dict_title_id',$this->dict_title_id);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('purchase_amount',$this->purchase_amount);
		$criteria->compare('purchase_weight',$this->purchase_weight,true);
		$criteria->compare('purchase_fee',$this->purchase_fee,true);
		$criteria->compare('is_finish',$this->is_finish);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPurchaseContract the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
