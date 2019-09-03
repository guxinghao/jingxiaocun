<?php

/**
 * This is the model class for table "profit_change".
 *
 * The followings are the available columns in table 'profit_change':
 * @property integer $id
 * @property string $type
 * @property integer $common_id
 * @property integer $created_at
 * @property integer $run_time
 * @property integer $disposed
 */
class ProfitChangeData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profit_change';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('run_time', 'required'),
			array('common_id, created_at, run_time, disposed', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, common_id, created_at, run_time, disposed', 'safe', 'on'=>'search'),
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
			'common_id' => 'Common',
			'created_at' => 'Created At',
			'run_time' => 'Run Time',
			'disposed' => 'Disposed',
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
		$criteria->compare('common_id',$this->common_id);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('run_time',$this->run_time);
		$criteria->compare('disposed',$this->disposed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfitChange the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
