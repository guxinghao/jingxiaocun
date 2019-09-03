<?php

/**
 * This is the model class for table "frm_sales_return".
 *
 * The followings are the available columns in table 'frm_sales_return':
 * @property integer $id
 * @property integer $company_id
 * @property integer $title_id
 * @property integer $return_date
 * @property integer $team_id
 * @property string $travel
 * @property integer $is_yidan
 * @property string $return_type
 * @property string $tran_type
 * @property integer $warehouse_id
 * @property integer $supply_id
 * @property string $comment
 * @property integer $contact_id
 * @property integer $amount
 * @property string $weight
 * @property integer $input_amount
 * @property string $input_weight
 * @property integer $weight_confirm_status
 * @property integer $confirm_amount
 * @property string $confirm_weight
 * @property string $confirm_cost
 * @property integer $flag
 * @property integer $client_id
 * @property string $gaokai_money
 * @property integer $gaokai_target
 * @property string $back_reason
 *
 * The followings are the available model relations:
 * @property DictCompany $company
 * @property DictTitle $title
 * @property Team $team
 * @property Warehouse $warehouse
 * @property SalesReturnDetail[] $salesReturnDetails
 */
class FrmSalesReturnData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_sales_return';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, title_id, return_date, team_id, is_yidan, warehouse_id, supply_id, contact_id, amount, input_amount, weight_confirm_status, confirm_amount, flag, client_id, gaokai_target', 'numerical', 'integerOnly'=>true),
			array('travel, return_type, tran_type', 'length', 'max'=>45),
			array('comment, back_reason', 'length', 'max'=>255),
			array('weight, input_weight, confirm_weight', 'length', 'max'=>15),
			array('confirm_cost, gaokai_money', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company_id, title_id, return_date, team_id, travel, is_yidan, return_type, tran_type, warehouse_id, supply_id, comment, contact_id, amount, weight, input_amount, input_weight, weight_confirm_status, confirm_amount, confirm_weight, confirm_cost, flag, client_id, gaokai_money, gaokai_target, back_reason', 'safe', 'on'=>'search'),
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
			'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'salesReturnDetails' => array(self::HAS_MANY, 'SalesReturnDetail', 'sales_return_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company_id' => 'Company',
			'title_id' => 'Title',
			'return_date' => 'Return Date',
			'team_id' => 'Team',
			'travel' => 'Travel',
			'is_yidan' => 'Is Yidan',
			'return_type' => 'Return Type',
			'tran_type' => 'Tran Type',
			'warehouse_id' => 'Warehouse',
			'supply_id' => 'Supply',
			'comment' => 'Comment',
			'contact_id' => 'Contact',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'weight_confirm_status' => 'Weight Confirm Status',
			'confirm_amount' => 'Confirm Amount',
			'confirm_weight' => 'Confirm Weight',
			'confirm_cost' => 'Confirm Cost',
			'flag' => 'Flag',
			'client_id' => 'Client',
			'gaokai_money' => 'Gaokai Money',
			'gaokai_target' => 'Gaokai Target',
			'back_reason' => 'Back Reason',
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
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('return_date',$this->return_date);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('travel',$this->travel,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('return_type',$this->return_type,true);
		$criteria->compare('tran_type',$this->tran_type,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('weight_confirm_status',$this->weight_confirm_status);
		$criteria->compare('confirm_amount',$this->confirm_amount);
		$criteria->compare('confirm_weight',$this->confirm_weight,true);
		$criteria->compare('confirm_cost',$this->confirm_cost,true);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('gaokai_money',$this->gaokai_money,true);
		$criteria->compare('gaokai_target',$this->gaokai_target);
		$criteria->compare('back_reason',$this->back_reason,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSalesReturn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
