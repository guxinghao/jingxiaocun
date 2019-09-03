<?php

/**
 * This is the model class for table "owner_transfer_detail".
 *
 * The followings are the available columns in table 'owner_transfer_detail':
 * @property integer $id
 * @property integer $owner_transfer_id
 * @property integer $amount
 * @property string $weight
 * @property integer $product_id
 * @property integer $brand_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 * @property integer $top_storage_id
 * @property integer $storage_id
 *
 * The followings are the available model relations:
 * @property OwnerTransfer $ownerTransfer
 */
class OwnerTransferDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'owner_transfer_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_transfer_id, product_id, brand_id, texture_id, rank_id, top_storage_id', 'required'),
			array('owner_transfer_id, amount, product_id, brand_id, texture_id, rank_id, length, top_storage_id, storage_id', 'numerical', 'integerOnly'=>true),
			array('weight', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner_transfer_id, amount, weight, product_id, brand_id, texture_id, rank_id, length, top_storage_id, storage_id', 'safe', 'on'=>'search'),
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
			'ownerTransfer' => array(self::BELONGS_TO, 'OwnerTransfer', 'owner_transfer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner_transfer_id' => 'Owner Transfer',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'top_storage_id' => 'Top Storage',
			'storage_id' => 'Storage',
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
		$criteria->compare('owner_transfer_id',$this->owner_transfer_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('top_storage_id',$this->top_storage_id);
		$criteria->compare('storage_id',$this->storage_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OwnerTransferDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
