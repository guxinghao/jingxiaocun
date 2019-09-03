<?php

/**
 * This is the model class for table "frm_pledge_redeem".
 *
 * The followings are the available columns in table 'frm_pledge_redeem':
 * @property integer $id
 * @property integer $title_id
 * @property integer $company_id
 * @property integer $pledge_info_id
 * @property integer $purchase_id
 * @property string $total_fee
 * @property string $interest_fee
 * @property integer $brand_id
 * @property integer $product_id
 * @property string $weight
 *
 * The followings are the available model relations:
 * @property PledgeInfo $pledgeInfo
 * @property FrmPurchase $purchase
 */
class FrmPledgeRedeemData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_pledge_redeem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title_id, company_id, pledge_info_id, purchase_id, brand_id, product_id', 'numerical', 'integerOnly'=>true),
			array('total_fee, interest_fee', 'length', 'max'=>11),
			array('weight', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title_id, company_id, pledge_info_id, purchase_id, total_fee, interest_fee, brand_id, product_id, weight', 'safe', 'on'=>'search'),
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
			'pledgeInfo' => array(self::BELONGS_TO, 'PledgeInfo', 'pledge_info_id'),
			'purchase' => array(self::BELONGS_TO, 'FrmPurchase', 'purchase_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title_id' => 'Title',
			'company_id' => 'Company',
			'pledge_info_id' => 'Pledge Info',
			'purchase_id' => 'Purchase',
			'total_fee' => 'Total Fee',
			'interest_fee' => 'Interest Fee',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'weight' => 'Weight',
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
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('pledge_info_id',$this->pledge_info_id);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('total_fee',$this->total_fee,true);
		$criteria->compare('interest_fee',$this->interest_fee,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('weight',$this->weight,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPledgeRedeem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
