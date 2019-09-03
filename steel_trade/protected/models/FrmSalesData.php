<?php

/**
 * This is the model class for table "frm_sales".
 *
 * The followings are the available columns in table 'frm_sales':
 * @property integer $id
 * @property string $sales_type
 * @property integer $title_id
 * @property integer $customer_id
 * @property integer $owner_company_id
 * @property integer $team_id
 * @property integer $is_yidan
 * @property integer $warehouse_id
 * @property integer $amount
 * @property string $weight
 * @property integer $output_amount
 * @property string $output_weight
 * @property integer $confirm_amount
 * @property string $comfirm_weight
 * @property integer $confirm_status
 * @property integer $has_bonus_price
 * @property string $comment
 * @property integer $date_extract
 * @property string $travel
 * @property integer $company_contact_id
 * @property integer $is_related
 * @property integer $pre_amount
 * @property string $pre_weight
 * @property string $fee
 *
 * The followings are the available model relations:
 * @property DictCompany $customer
 * @property DictCompany $ownerCompany
 * @property DictTitle $title
 * @property Warehouse $warehouse
 */
class FrmSalesData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_sales';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title_id, customer_id, owner_company_id, team_id, is_yidan, warehouse_id, amount, output_amount, confirm_amount, confirm_status, has_bonus_price, date_extract, company_contact_id, is_related, pre_amount', 'numerical', 'integerOnly'=>true),
			array('sales_type', 'length', 'max'=>45),
			array('weight, output_weight, confirm_weight, pre_weight', 'length', 'max'=>15),
			array('comment, travel', 'length', 'max'=>255),
			array('fee', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sales_type, title_id, customer_id, owner_company_id, team_id, is_yidan, warehouse_id, amount, weight, output_amount, output_weight, confirm_amount, confirm_weight, confirm_status, has_bonus_price, comment, date_extract, travel, company_contact_id, is_related, pre_amount, pre_weight, fee', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'DictCompany', 'customer_id'),
			'ownerCompany' => array(self::BELONGS_TO, 'DictCompany', 'owner_company_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sales_type' => 'Sales Type',
			'title_id' => 'Title',
			'customer_id' => 'Customer',
			'owner_company_id' => 'Owner Company',
			'team_id' => 'Team',
			'is_yidan' => 'Is Yidan',
			'warehouse_id' => 'Warehouse',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'confirm_amount' => 'Confirm Amount',
			'confirm_weight' => 'Comfirm Weight',
			'confirm_status' => 'Confirm Status',
			'has_bonus_price' => 'Has Bonus Price',
			'comment' => 'Comment',
			'date_extract' => 'Date Extract',
			'travel' => 'Travel',
			'company_contact_id' => 'Company Contact',
			'is_related' => 'Is Related',
			'pre_amount' => 'Pre Amount',
			'pre_weight' => 'Pre Weight',
			'fee' => 'Fee',
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
		$criteria->compare('sales_type',$this->sales_type,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('owner_company_id',$this->owner_company_id);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight,true);
		$criteria->compare('confirm_amount',$this->confirm_amount);
		$criteria->compare('confirm_weight',$this->comfirm_weight,true);
		$criteria->compare('confirm_status',$this->confirm_status);
		$criteria->compare('has_bonus_price',$this->has_bonus_price);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('date_extract',$this->date_extract);
		$criteria->compare('travel',$this->travel,true);
		$criteria->compare('company_contact_id',$this->company_contact_id);
		$criteria->compare('is_related',$this->is_related);
		$criteria->compare('pre_amount',$this->pre_amount);
		$criteria->compare('pre_weight',$this->pre_weight);
		$criteria->compare('fee',$this->fee,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSales the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
