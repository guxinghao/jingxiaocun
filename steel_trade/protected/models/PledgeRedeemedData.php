<?php

/**
 * This is the model class for table "pledge_redeemed".
 *
 * The followings are the available columns in table 'pledge_redeemed':
 * @property integer $id
 * @property integer $title_id
 * @property integer $company_id
 * @property integer $product_id
 * @property integer $brand_id
 * @property string $weight
 * @property string $left_weight
 * @property integer $purchase_id
 *
 * The followings are the available model relations:
 * @property DictCompany $company
 * @property DictTitle $title
 */
class PledgeRedeemedData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pledge_redeemed';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title_id, company_id', 'required'),
			array('title_id, company_id, product_id, brand_id, purchase_id', 'numerical', 'integerOnly'=>true),
			array('weight, left_weight', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title_id, company_id, product_id, brand_id, weight, left_weight, purchase_id', 'safe', 'on'=>'search'),
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
			'left_weight' => 'Left Weight',
			'purchase_id' => 'Purchase',
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
		$criteria->compare('left_weight',$this->left_weight,true);
		$criteria->compare('purchase_id',$this->purchase_id);

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
