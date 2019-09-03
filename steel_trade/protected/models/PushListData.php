<?php

/**
 * This is the model class for table "push_list".
 *
 * The followings are the available columns in table 'push_list':
 * @property integer $id
 * @property string $type
 * @property string $content
 * @property string $status
 * @property integer $created_at
 * @property integer $created_by
 * @property string $unid
 * @property integer $form_id
 * @property integer $times
 * @property integer $next_time
 * @property string $message
 * @property string $form_sn
 * @property string $operate
 */
class PushListData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'push_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at, created_by, form_id, times, next_time', 'numerical', 'integerOnly'=>true),
			array('type, status, form_sn, operate', 'length', 'max'=>45),
			array('unid', 'length', 'max'=>20),
			array('message', 'length', 'max'=>245),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, content, status, created_at, created_by, unid, form_id, times, next_time, message, form_sn, operate', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'content' => 'Content',
			'status' => 'Status',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'unid' => 'Unid',
			'form_id' => 'Form',
			'times' => 'Times',
			'next_time' => 'Next Time',
			'message' => 'Message',
			'form_sn' => 'Form Sn',
			'operate' => 'Operate',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('unid',$this->unid,true);
		$criteria->compare('form_id',$this->form_id);
		$criteria->compare('times',$this->times);
		$criteria->compare('next_time',$this->next_time);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('operate',$this->operate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PushList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
