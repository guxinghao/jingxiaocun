<?php

/**
 * This is the biz model class for table "purprice_date".
 *
 */
class PurpriceDate extends PurpriceDateData
{
	

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
