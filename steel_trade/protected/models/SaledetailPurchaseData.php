<?php

/**
 * This is the model class for table "saledetail_purchase".
 *
 * The followings are the available columns in table 'saledetail_purchase':
 * @property integer $id
 * @property integer $sales_detail_id
 * @property integer $purchase_id
 * @property integer $purchase_detail_id
 * @property integer $amount
 * @property string $weight
 * @property integer $good_id
 * @property string $form_sn
 */
class SaledetailPurchaseData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'saledetail_purchase';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sales_detail_id, purchase_id, purchase_detail_id', 'required'),
			array('sales_detail_id, purchase_id, purchase_detail_id, amount, good_id', 'numerical', 'integerOnly'=>true),
			array('weight', 'length', 'max'=>11),
			array('form_sn', 'length', 'max'=>125),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sales_detail_id, purchase_id, purchase_detail_id, amount, weight, good_id, form_sn', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'sales_detail_id' => 'Sales Detail',
			'purchase_id' => 'Purchase',
			'purchase_detail_id' => 'Purchase Detail',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'good_id' => 'Good',
			'form_sn' => 'Form Sn',
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
		$criteria->compare('sales_detail_id',$this->sales_detail_id);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('purchase_detail_id',$this->purchase_detail_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('good_id',$this->good_id);
		$criteria->compare('form_sn',$this->form_sn,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SaledetailPurchase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
