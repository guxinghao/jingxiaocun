<?php

/**
 * This is the model class for table "pushed_storage_detail".
 *
 * The followings are the available columns in table 'pushed_storage_detail':
 * @property integer $id
 * @property integer $pushed_storage_id
 * @property string $card_no
 * @property integer $product_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $brand_id
 * @property integer $length
 * @property integer $amount
 * @property string $weight
 * @property string $content
 * @property integer $original_detail_id
 *
 * The followings are the available model relations:
 * @property PushedStorage $pushedStorage
 */
class PushedStorageDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pushed_storage_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pushed_storage_id, card_no', 'required'),
			array('pushed_storage_id, product_id, texture_id, rank_id, brand_id, length, amount, original_detail_id', 'numerical', 'integerOnly'=>true),
			array('card_no', 'length', 'max'=>75),
			array('weight', 'length', 'max'=>15),
			array('content', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pushed_storage_id, card_no, product_id, texture_id, rank_id, brand_id, length, amount, weight, content, original_detail_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pushedStorage' => array(self::BELONGS_TO, 'PushedStorage', 'pushed_storage_id'),
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
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'brand_id' => 'Brand',
			'length' => 'Length',
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('length',$this->length);
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
