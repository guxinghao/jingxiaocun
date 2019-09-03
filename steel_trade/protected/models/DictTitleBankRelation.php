<?php

/**
 * This is the biz model class for table "dict_title_bank_relation".
 *
 */
class DictTitleBankRelation extends DictTitleBankRelationData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'bank' => array(self::BELONGS_TO, 'DictBankInfo', 'bank_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title_id' => 'Title',
			'bank_id' => 'Bank',
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
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('bank_id',$this->bank_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictTitleBankRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * 获取公司id
	 * @param  integer $bank_id 
	 * @return array $result
	 */
	public static function findTitleId($bank_id) 
	{
		$result = array();
		$items = DictTitleBankRelation::model()->findAll("bank_id = :bank_id", array(':bank_id' => $bank_id));
		if (!$items) return $result;
		foreach ($items as $item) {
			array_push($result, $item->title_id);
		}
		return $result;
	}

	/**
	 * 获取账户id
	 * @param  integer $title_id
	 * @return array $result
	 */
	public static function findBankId($title_id) 
	{
		$result = array();
		$items = DictTitleBankRelation::model()->findAll("title_id = :title_id", array(':title_id' => $title_id));
		if (!$items) return $result;
		foreach ($items as $item) {
			array_push($result, $item->bank_id);
		}
		return $result;
	}
}
