<?php

/**
 * This is the model class for table "warehouse".
 *
 * The followings are the available columns in table 'warehouse':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $std
 * @property string $area
 * @property integer $is_other
 * @property integer $created_at
 * @property integer $created_by
 * @property string $title
 * @property string $short_name
 * @property string $contact
 * @property string $mobile
 * @property string $fax
 * @property string $address
 * @property string $common
 * @property integer $is_jxc
 *
 * The followings are the available model relations:
 * @property FrmPurchase[] $frmPurchases
 * @property FrmPurchaseContract[] $frmPurchaseContracts
 * @property FrmPurchaseReturn[] $frmPurchaseReturns
 * @property FrmSales[] $frmSales
 * @property FrmSalesReturn[] $frmSalesReturns
 */
class WarehouseData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'warehouse';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('is_other, created_at, created_by, is_jxc', 'numerical', 'integerOnly'=>true),
			array('name, code, std, area, title, short_name, contact, mobile, fax, address, common', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, code, std, area, is_other, created_at, created_by, title, short_name, contact, mobile, fax, address, common, is_jxc', 'safe', 'on'=>'search'),
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
			'frmPurchases' => array(self::HAS_MANY, 'FrmPurchase', 'warehouse_id'),
			'frmPurchaseContracts' => array(self::HAS_MANY, 'FrmPurchaseContract', 'warehouse_id'),
			'frmPurchaseReturns' => array(self::HAS_MANY, 'FrmPurchaseReturn', 'warehouse_id'),
			'frmSales' => array(self::HAS_MANY, 'FrmSales', 'warehouse_id'),
			'frmSalesReturns' => array(self::HAS_MANY, 'FrmSalesReturn', 'warehouse_id'),
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
			'code' => 'Code',
			'std' => 'Std',
			'area' => 'Area',
			'is_other' => 'Is Other',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'title' => 'Title',
			'short_name' => 'Short Name',
			'contact' => 'Contact',
			'mobile' => 'Mobile',
			'fax' => 'Fax',
			'address' => 'Address',
			'common' => 'Common',
			'is_jxc' => 'Is Jxc',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('std',$this->std,true);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('is_other',$this->is_other);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('common',$this->common,true);
		$criteria->compare('is_jxc',$this->is_jxc);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Warehouse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
