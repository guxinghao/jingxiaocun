<?php

/**
 * This is the model class for table "pledge_info".
 *
 * The followings are the available columns in table 'pledge_info':
 * @property integer $id
 * @property integer $frm_purchase_id
 * @property string $fee
 * @property integer $begin_date
 * @property integer $pledge_company_id
 * @property string $advance
 * @property string $unit_price
 * @property integer $r_limit
 * @property integer $pledge_length
 * @property string $pledge_rate
 *
 * The followings are the available model relations:
 * @property FrmPledgeRedeem[] $frmPledgeRedeems
 * @property FrmPurchase $frmPurchase
 * @property PledgeInterest[] $pledgeInterests
 */
class PledgeInfoData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pledge_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('frm_purchase_id, pledge_company_id', 'required'),
			array('frm_purchase_id, begin_date, pledge_company_id, r_limit, pledge_length', 'numerical', 'integerOnly'=>true),
			array('fee, advance, unit_price', 'length', 'max'=>11),
			array('pledge_rate', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, frm_purchase_id, fee, begin_date, pledge_company_id, advance, unit_price, r_limit, pledge_length, pledge_rate', 'safe', 'on'=>'search'),
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
			'frmPledgeRedeems' => array(self::HAS_MANY, 'FrmPledgeRedeem', 'pledge_info_id'),
			'frmPurchase' => array(self::BELONGS_TO, 'FrmPurchase', 'frm_purchase_id'),
			'pledgeInterests' => array(self::HAS_MANY, 'PledgeInterest', 'pledge_info_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'frm_purchase_id' => 'Frm Purchase',
			'fee' => 'Fee',
			'begin_date' => 'Begin Date',
			'pledge_company_id' => 'Pledge Company',
			'advance' => 'Advance',
			'unit_price' => 'Unit Price',
			'r_limit' => 'R Limit',
			'pledge_length' => 'Pledge Length',
			'pledge_rate' => 'Pledge Rate',
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
		$criteria->compare('frm_purchase_id',$this->frm_purchase_id);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('begin_date',$this->begin_date);
		$criteria->compare('pledge_company_id',$this->pledge_company_id);
		$criteria->compare('advance',$this->advance,true);
		$criteria->compare('unit_price',$this->unit_price,true);
		$criteria->compare('r_limit',$this->r_limit);
		$criteria->compare('pledge_length',$this->pledge_length);
		$criteria->compare('pledge_rate',$this->pledge_rate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PledgeInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
