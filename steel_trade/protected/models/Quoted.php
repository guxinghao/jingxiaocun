<?php

/**
 * This is the biz model class for table "quoted".
 *
 */
class Quoted extends QuotedData
{
	public $product_name;
	public $brand_name;
	public $texture_name;
	public $rank_name;
	public $product_id;
	public $brand_id;
	public $texture_id;
	public $rank_id;

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
			'product_std' => 'Product Std',
			'texture_std' => 'Texture Std',
			'brand_std' => 'Brand Std',
			'rank_std_range' => 'Rank Std Range',
			'area' => 'Area',
			'price' => 'Price',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'last_update' => 'Last Update',
			'type' => 'Type',
			'price_date' => 'Price Date',
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
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('rank_std_range',$this->rank_std_range,true);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('price_date',$this->price_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Quoted the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
