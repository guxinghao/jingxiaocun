<?php

/**
 * This is the model class for table "company_contact".
 *
 * The followings are the available columns in table 'company_contact':
 * @property integer $id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $last_update_at
 * @property integer $last_update_by
 * @property string $name
 * @property string $mobile
 * @property integer $is_default
 * @property integer $dict_company_id
 *
 * The followings are the available model relations:
 * @property FrmPurchaseReturn[] $frmPurchaseReturns
 * @property FrmSalesReturn[] $frmSalesReturns
 */
class CompanyContactData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company_contact';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, mobile, dict_company_id', 'required'),
			array('created_at, created_by, last_update_at, last_update_by, is_default, dict_company_id', 'numerical', 'integerOnly'=>true),
			array('name, mobile', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, created_at, created_by, last_update_at, last_update_by, name, mobile, is_default, dict_company_id', 'safe', 'on'=>'search'),
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
			'frmPurchaseReturns' => array(self::HAS_MANY, 'FrmPurchaseReturn', 'company_contact_id'),
			'frmSalesReturns' => array(self::HAS_MANY, 'FrmSalesReturn', 'contact_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'last_update_at' => 'Last Update At',
			'last_update_by' => 'Last Update By',
			'name' => 'Name',
			'mobile' => 'Mobile',
			'is_default' => 'Is Default',
			'dict_company_id' => 'Dict Company',
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
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('last_update_at',$this->last_update_at);
		$criteria->compare('last_update_by',$this->last_update_by);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('dict_company_id',$this->dict_company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompanyContact the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
