<?php

/**
 * This is the biz model class for table "pledge_redeemed".
 *
 */
class PledgeRedeemed extends PledgeRedeemedData
{
	

	public $sum_weight;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
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
			'company_id' => 'Company',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'weight' => 'Weight',
			'purchase_id' => 'Purchase',
			'frm_pledge_id' => 'Frm Pledge',
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
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('frm_pledge_id',$this->frm_pledge_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PledgeRedeemed the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
