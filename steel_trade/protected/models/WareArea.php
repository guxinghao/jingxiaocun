<?php

/**
 * This is the biz model class for table "ware_area".
 *
 */
class WareArea extends WareAreaData
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WareArea the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function createArea($post)
	{
		$this->attributes = $post;
		$bool = WareArea::model()->exists("name='{$this->name}'");
		if($bool){
			return -1;
		}else{
			return $this->save();
		}
	}
	
	public function updateArea($post){
		$this->attributes = $post;
		$bool = WareArea::model()->exists("id<>$this->id and name='{$this->name}'");
		if($bool){
			return -1;
		}else{
			return $this->save();
		}
	}
	
	public static function getName($id)
	{
		$model=WareArea::model()->findByPk($id);
		return $model->name;
	}
	
	public static function getList()
	{
		$all=WareArea::model()->findAll();
		$areas=array();
		$areas[0]='未知';
		if($all)
		{
			foreach ($all as $each)
			{
				$areas[$each->id]=$each->name;
			}
		}
		return $areas;
	}
	

}
