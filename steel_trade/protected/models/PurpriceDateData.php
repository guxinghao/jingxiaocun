<?php

/**
 * This is the model class for table "purprice_date".
 *
 * The followings are the available columns in table 'purprice_date':
 * @property integer $id
 * @property integer $price_id
 * @property string $price
 * @property string $price_date
 * @property integer $edit_at
 * @property integer $edit_by
 */
class PurpriceDateData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purprice_date';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('price_id, edit_at, edit_by', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>11),
			array('price_date', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, price_id, price, price_date, edit_at, edit_by', 'safe', 'on'=>'search'),
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
			'price_id' => 'Price',
			'price' => 'Price',
			'price_date' => 'Price Date',
			'edit_at' => 'Edit At',
			'edit_by' => 'Edit By',
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
		$criteria->compare('price_id',$this->price_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('price_date',$this->price_date,true);
		$criteria->compare('edit_at',$this->edit_at);
		$criteria->compare('edit_by',$this->edit_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurpriceDate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
