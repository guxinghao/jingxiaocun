<?php

/**
 * This is the model class for table "sale_subsidy".
 *
 * The followings are the available columns in table 'sale_subsidy':
 * @property string $grade
 * @property string $sale_weight
 * @property double $per_money
 */
class SaleSubsidyData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sale_subsidy';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('grade', 'required'),
			array('per_money', 'numerical'),
			array('grade', 'length', 'max'=>45),
			array('sale_weight', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('grade, sale_weight, per_money', 'safe', 'on'=>'search'),
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
			'grade' => 'Grade',
			'sale_weight' => 'Sale Weight',
			'per_money' => 'Per Money',
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

		$criteria->compare('grade',$this->grade,true);
		$criteria->compare('sale_weight',$this->sale_weight,true);
		$criteria->compare('per_money',$this->per_money);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SaleSubsidy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
