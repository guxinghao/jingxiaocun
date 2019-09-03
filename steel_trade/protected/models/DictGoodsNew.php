<?php

/**
 * This is the biz model class for table "dict_goods_new".
 *
 */
class DictGoodsNew extends DictGoodsNewData
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
			'name' => 'Name',
			'short_name' => 'Short Name',
			'length' => 'Length',
			'last_update' => 'Last Update',
			'unit_weight' => 'Unit Weight',
			'product_std' => 'Product Std',
			'brand_std' => 'Brand Std',
			'texture_std' => 'Texture Std',
			'rank_std' => 'Rank Std',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('unit_weight',$this->unit_weight,true);
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('rank_std',$this->rank_std,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictGoodsNew the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
