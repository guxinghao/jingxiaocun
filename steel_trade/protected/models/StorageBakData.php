<?php

/**
 * This is the model class for table "storage_bak".
 *
 * The followings are the available columns in table 'storage_bak':
 * @property integer $id
 * @property integer $storage_id
 * @property string $card_no
 * @property integer $input_detail_id
 * @property string $card_status
 * @property integer $title_id
 * @property integer $redeem_company_id
 * @property integer $input_amount
 * @property string $input_weight
 * @property integer $left_amount
 * @property string $left_weight
 * @property integer $retain_amount
 * @property integer $input_date
 * @property integer $pre_input_date
 * @property integer $lock_amount
 * @property integer $frm_input_id
 * @property string $cost_price
 * @property integer $is_price_confirmed
 * @property string $invoice_price
 * @property integer $is_pledge
 * @property integer $is_dx
 * @property integer $is_yidan
 * @property integer $warehouse_id
 * @property integer $is_deleted
 * @property string $bak_date
 * @property integer $purchase_id
 */
class StorageBakData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'storage_bak';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('storage_id, card_no, input_detail_id, title_id, frm_input_id', 'required'),
			array('storage_id, input_detail_id, title_id, redeem_company_id, input_amount, left_amount, retain_amount, input_date, pre_input_date, lock_amount, frm_input_id, is_price_confirmed, is_pledge, is_dx, is_yidan, warehouse_id, is_deleted, purchase_id', 'numerical', 'integerOnly'=>true),
			array('card_no, card_status', 'length', 'max'=>45),
			array('input_weight, left_weight', 'length', 'max'=>15),
			array('cost_price, invoice_price', 'length', 'max'=>11),
			array('bak_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, storage_id, card_no, input_detail_id, card_status, title_id, redeem_company_id, input_amount, input_weight, left_amount, left_weight, retain_amount, input_date, pre_input_date, lock_amount, frm_input_id, cost_price, is_price_confirmed, invoice_price, is_pledge, is_dx, is_yidan, warehouse_id, is_deleted, bak_date, purchase_id', 'safe', 'on'=>'search'),
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
			'storage_id' => 'Storage',
			'card_no' => 'Card No',
			'input_detail_id' => 'Input Detail',
			'card_status' => 'Card Status',
			'title_id' => 'Title',
			'redeem_company_id' => 'Redeem Company',
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'left_amount' => 'Left Amount',
			'left_weight' => 'Left Weight',
			'retain_amount' => 'Retain Amount',
			'input_date' => 'Input Date',
			'pre_input_date' => 'Pre Input Date',
			'lock_amount' => 'Lock Amount',
			'frm_input_id' => 'Frm Input',
			'cost_price' => 'Cost Price',
			'is_price_confirmed' => 'Is Price Confirmed',
			'invoice_price' => 'Invoice Price',
			'is_pledge' => 'Is Pledge',
			'is_dx' => 'Is Dx',
			'is_yidan' => 'Is Yidan',
			'warehouse_id' => 'Warehouse',
			'is_deleted' => 'Is Deleted',
			'bak_date' => 'Bak Date',
			'purchase_id' => 'Purchase',
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
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('input_detail_id',$this->input_detail_id);
		$criteria->compare('card_status',$this->card_status,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('redeem_company_id',$this->redeem_company_id);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('left_amount',$this->left_amount);
		$criteria->compare('left_weight',$this->left_weight,true);
		$criteria->compare('retain_amount',$this->retain_amount);
		$criteria->compare('input_date',$this->input_date);
		$criteria->compare('pre_input_date',$this->pre_input_date);
		$criteria->compare('lock_amount',$this->lock_amount);
		$criteria->compare('frm_input_id',$this->frm_input_id);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('is_price_confirmed',$this->is_price_confirmed);
		$criteria->compare('invoice_price',$this->invoice_price,true);
		$criteria->compare('is_pledge',$this->is_pledge);
		$criteria->compare('is_dx',$this->is_dx);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('bak_date',$this->bak_date,true);
		$criteria->compare('purchase_id',$this->purchase_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StorageBak the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
