<?php

/**
 * This is the biz model class for table "pushed_storage_detail".
 *
 */
class PushedStorageDetail extends PushedStorageDetailData
{
	
	public $cform_sn;
	public $pform_sn;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pushedStorage' => array(self::BELONGS_TO, 'PushedStorage', 'pushed_storage_id'),
			'plandetail'=>array(self::BELONGS_TO,'InputDetailPlan','original_detail_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pushed_storage_id' => 'Pushed Storage',
			'card_no' => 'Card No',
			'prodcut_id' => 'Prodcut',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'brand_id' => 'Brand',
			'length' => 'Length',
			'unit_weight' => 'Unit Weight',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'content' => 'Content',
			'original_detail_id' => 'Original Detail',
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
		$criteria->compare('pushed_storage_id',$this->pushed_storage_id);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('prodcut_id',$this->prodcut_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('unit_weight',$this->unit_weight,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('original_detail_id',$this->original_detail_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PushedStorageDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
