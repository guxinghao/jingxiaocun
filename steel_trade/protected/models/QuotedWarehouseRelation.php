<?php

/**
 * This is the biz model class for table "quoted_warehouse_relation".
 *
 */
class QuotedWarehouseRelation extends QuotedWarehouseRelationData
{
	
	public $std;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'quoted' => array(self::BELONGS_TO,'QuotedDetail','quoted_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'quoted_id' => 'Quoted',
			'warehouse_id' => 'Warehouse',
			'price' => 'Price',
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
		$criteria->compare('quoted_id',$this->quoted_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('price',$this->price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotedWarehouseRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function findByHistory($history){
		if(!$history)	return false;
		$today = date("Y-m-d",time());
		$cri = new CDbCriteria();
		$cri->addCondition("area_id = '{$history->area_id}'");
		$cri->addCondition("quoted_id = '{$history->quoted_id}'");
		$cri->addCondition("price_date = '{$today}'");
		return QuotedWarehouseRelation::model()->find($cri);	
	}

}
