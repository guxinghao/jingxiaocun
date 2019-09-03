<?php

/**
 * This is the model class for table "turnover".
 *
 * The followings are the available columns in table 'turnover':
 * @property integer $id
 * @property string $turnover_type
 * @property string $turnover_direction
 * @property integer $title_id
 * @property integer $target_id
 * @property integer $proxy_company_id
 * @property string $description
 * @property string $amount
 * @property string $price
 * @property string $fee
 * @property integer $common_forms_id
 * @property integer $form_detail_id
 * @property string $status
 * @property integer $created_at
 * @property integer $ownered_by
 * @property integer $created_by
 * @property integer $account_by
 * @property integer $is_yidan
 * @property string $big_type
 * @property integer $confirmed
 * @property integer $client_id
 *
 * The followings are the available model relations:
 * @property DictCompany $target
 * @property DictTitle $title
 */
class TurnoverData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'turnover';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('turnover_type, turnover_direction, title_id, target_id', 'required'),
			array('title_id, target_id, proxy_company_id, common_forms_id, form_detail_id, created_at, ownered_by, created_by, account_by, is_yidan, confirmed, client_id', 'numerical', 'integerOnly'=>true),
			array('turnover_type', 'length', 'max'=>50),
			array('turnover_direction, status, big_type', 'length', 'max'=>45),
			array('amount', 'length', 'max'=>15),
			array('price, fee', 'length', 'max'=>12),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, turnover_type, turnover_direction, title_id, target_id, proxy_company_id, description, amount, price, fee, common_forms_id, form_detail_id, status, created_at, ownered_by, created_by, account_by, is_yidan, big_type, confirmed, client_id', 'safe', 'on'=>'search'),
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
			'target' => array(self::BELONGS_TO, 'DictCompany', 'target_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'turnover_type' => 'Turnover Type',
			'turnover_direction' => 'Turnover Direction',
			'title_id' => 'Title',
			'target_id' => 'Target',
			'proxy_company_id' => 'Proxy Company',
			'description' => 'Description',
			'amount' => 'Amount',
			'price' => 'Price',
			'fee' => 'Fee',
			'common_forms_id' => 'Common Forms',
			'form_detail_id' => 'Form Detail',
			'status' => 'Status',
			'created_at' => 'Created At',
			'ownered_by' => 'Ownered By',
			'created_by' => 'Created By',
			'account_by' => 'Account By',
			'is_yidan' => 'Is Yidan',
			'big_type' => 'Big Type',
			'confirmed' => 'Confirmed',
			'client_id' => 'Client',
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
		$criteria->compare('turnover_type',$this->turnover_type,true);
		$criteria->compare('turnover_direction',$this->turnover_direction,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('target_id',$this->target_id);
		$criteria->compare('proxy_company_id',$this->proxy_company_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('common_forms_id',$this->common_forms_id);
		$criteria->compare('form_detail_id',$this->form_detail_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('ownered_by',$this->ownered_by);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('account_by',$this->account_by);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('big_type',$this->big_type,true);
		$criteria->compare('confirmed',$this->confirmed);
		$criteria->compare('client_id',$this->client_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Turnover the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
