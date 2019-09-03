<?php

/**
 * This is the biz model class for table "wx_quoted".
 *
 */
class WxQuoted extends WxQuotedData
{
	

	/**
	 * @return array relational rules.
	 */
	public $product;
	public $texture;
	public $brand;
	public $rank;
	public $prefecture_name;
	public $areaname;
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
			'product_std' => '品名std',
			'texture_std' => '材质std',
			'brand_std' => '产地std',
			'rank_std' => '规格std',
			'length' => '长度',
			'area' => '区域',
			'price' => '价格',
			'created_by' => '创建人',
			'created_at' => '创建时间',
			'last_update' => '最后更新时间',
			'type' => '报价类型
1.net：网价
2.spread价差
3.guidance指导价',
			'price_date' => '报价日期',
			'prefecture' => '专区',
			'send_id' => '推送人',
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
		$criteria->compare('rank_std',$this->rank_std,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('price_date',$this->price_date,true);
		$criteria->compare('prefecture',$this->prefecture);
		$criteria->compare('send_id',$this->send_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WxQuoted the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
