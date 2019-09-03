<?php

/**
 * This is the model class for table "pushed_storage".
 *
 * The followings are the available columns in table 'pushed_storage':
 * @property integer $id
 * @property integer $frm_input_id
 * @property integer $input_company
 * @property integer $owner_company
 * @property string $input_type
 * @property string $ship_no
 * @property integer $created_at
 * @property integer $input_status
 *
 * The followings are the available model relations:
 * @property PushedStorageDetail[] $pushedStorageDetails
 */
class PushedStorageData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pushed_storage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('input_company, owner_company', 'required'),
			array('frm_input_id, input_company, owner_company, created_at, input_status', 'numerical', 'integerOnly'=>true),
			array('input_type, ship_no', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, frm_input_id, input_company, owner_company, input_type, ship_no, created_at, input_status', 'safe', 'on'=>'search'),
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
			'pushedStorageDetails' => array(self::HAS_MANY, 'PushedStorageDetail', 'pushed_storage_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'frm_input_id' => 'Frm Input',
			'input_company' => 'Input Company',
			'owner_company' => 'Owner Company',
			'input_type' => 'Input Type',
			'ship_no' => 'Ship No',
			'created_at' => 'Created At',
			'input_status' => 'Input Status',
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
		$criteria->compare('frm_input_id',$this->frm_input_id);
		$criteria->compare('input_company',$this->input_company);
		$criteria->compare('owner_company',$this->owner_company);
		$criteria->compare('input_type',$this->input_type,true);
		$criteria->compare('ship_no',$this->ship_no,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('input_status',$this->input_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PushedStorage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
