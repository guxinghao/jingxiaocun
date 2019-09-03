<?php

/**
 * This is the model class for table "sales_commission".
 *
 * The followings are the available columns in table 'sales_commission':
 * @property integer $id
 * @property string $date
 * @property integer $owned_by
 * @property string $weight
 * @property string $money
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $status
 */
class SalesCommissionData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sales_commission';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, owned_by', 'required'),
			array('owned_by, created_at, created_by, status', 'numerical', 'integerOnly'=>true),
			array('date', 'length', 'max'=>45),
			array('weight', 'length', 'max'=>15),
			array('money', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, owned_by, weight, money, created_at, created_by, status', 'safe', 'on'=>'search'),
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
			'date' => 'Date',
			'owned_by' => 'Owned By',
			'weight' => 'Weight',
			'money' => 'Money',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'status' => 'Status',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('owned_by',$this->owned_by);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalesCommission the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
