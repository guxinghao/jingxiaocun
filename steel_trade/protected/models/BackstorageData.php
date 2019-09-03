<?php

/**
 * This is the biz model class for table "backstorage_data".
 *
 */
class BackstorageData extends BackstorageDataData
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
			'warehouse' => 'Warehouse',
			'input_date' => 'Input Date',
			'comment' => 'Comment',
			'card_no' => 'Card No',
			'rank' => 'Rank',
			'product' => 'Product',
			'texture' => 'Texture',
			'brand' => 'Brand',
			'length' => 'Length',
			'unit_weight' => 'Unit Weight',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'cost_price' => 'Cost Price',
			'cost_money' => 'Cost Money',
			'supply' => 'Supply',
			'cgd_sn' => 'Cgd Sn',
			'dx' => 'Dx',
			'title' => 'Title',
			'flag' => 'Flag',
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
		$criteria->compare('warehouse',$this->warehouse,true);
		$criteria->compare('input_date',$this->input_date,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('rank',$this->rank,true);
		$criteria->compare('product',$this->product,true);
		$criteria->compare('texture',$this->texture,true);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('length',$this->length,true);
		$criteria->compare('unit_weight',$this->unit_weight,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('cost_money',$this->cost_money,true);
		$criteria->compare('supply',$this->supply,true);
		$criteria->compare('cgd_sn',$this->cgd_sn,true);
		$criteria->compare('dx',$this->dx,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('flag',$this->flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BackstorageData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
