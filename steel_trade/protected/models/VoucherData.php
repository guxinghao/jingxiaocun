<?php

/**
 * This is the model class for table "voucher".
 *
 * The followings are the available columns in table 'voucher':
 * @property integer $id
 * @property string $voucher_name
 * @property integer $voucher_number
 * @property string $type
 * @property integer $attachment
 * @property integer $created_at
 * @property integer $form_at
 * @property integer $is_deleted
 * @property integer $created_by
 */
class VoucherData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'voucher';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('voucher_name', 'required'),
			array('voucher_number, attachment, created_at, form_at, is_deleted, created_by', 'numerical', 'integerOnly'=>true),
			array('voucher_name, type', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, voucher_name, voucher_number, type, attachment, created_at, form_at, is_deleted, created_by', 'safe', 'on'=>'search'),
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
			'voucher_name' => 'Voucher Name',
			'voucher_number' => 'Voucher Number',
			'type' => 'Type',
			'attachment' => 'Attachment',
			'created_at' => 'Created At',
			'form_at' => 'Form At',
			'is_deleted' => 'Is Deleted',
			'created_by' => 'Created By',
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
		$criteria->compare('voucher_name',$this->voucher_name,true);
		$criteria->compare('voucher_number',$this->voucher_number);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('attachment',$this->attachment);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('form_at',$this->form_at);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_by',$this->created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Voucher the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
