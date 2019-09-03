<?php

/**
 * This is the model class for table "common_forms".
 *
 * The followings are the available columns in table 'common_forms':
 * @property integer $id
 * @property string $form_type
 * @property string $form_sn
 * @property integer $created_by
 * @property integer $created_at
 * @property string $form_time
 * @property string $form_status
 * @property integer $approved_at
 * @property integer $approved_by
 * @property integer $owned_by
 * @property integer $is_deleted
 * @property string $comment
 * @property integer $last_update
 * @property integer $last_updated_by
 * @property integer $form_id
 *
 * The followings are the available model relations:
 * @property Turnover[] $turnovers
 */
class CommonFormsData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'common_forms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('form_type, created_by, created_at', 'required'),
			array('created_by, created_at, approved_at, approved_by, owned_by, is_deleted, last_update, last_updated_by, form_id', 'numerical', 'integerOnly'=>true),
			array('form_type, form_sn, comment', 'length', 'max'=>45),
			array('form_status', 'length', 'max'=>20),
			array('form_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, form_type, form_sn, created_by, created_at, form_time, form_status, approved_at, approved_by, owned_by, is_deleted, comment, last_update, last_updated_by, form_id', 'safe', 'on'=>'search'),
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
			'turnovers' => array(self::HAS_MANY, 'Turnover', 'common_forms_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'form_type' => 'Form Type',
			'form_sn' => 'Form Sn',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
			'form_time' => 'Form Time',
			'form_status' => 'Form Status',
			'approved_at' => 'Approved At',
			'approved_by' => 'Approved By',
			'owned_by' => 'Owned By',
			'is_deleted' => 'Is Deleted',
			'comment' => 'Comment',
			'last_update' => 'Last Update',
			'last_updated_by' => 'Last Updated By',
			'form_id' => 'Form',
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
		$criteria->compare('form_type',$this->form_type,true);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('form_time',$this->form_time,true);
		$criteria->compare('form_status',$this->form_status,true);
		$criteria->compare('approved_at',$this->approved_at);
		$criteria->compare('approved_by',$this->approved_by);
		$criteria->compare('owned_by',$this->owned_by);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('last_updated_by',$this->last_updated_by);
		$criteria->compare('form_id',$this->form_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommonForms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
