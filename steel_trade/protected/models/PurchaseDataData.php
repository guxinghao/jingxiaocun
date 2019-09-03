<?php

/**
 * This is the model class for table "purchase_data".
 *
 * The followings are the available columns in table 'purchase_data':
 * @property integer $id
 * @property string $form_sn
 * @property string $created_date
 * @property string $supply
 * @property string $product
 * @property string $rank
 * @property string $length
 * @property string $texture
 * @property string $brand
 * @property string $amount
 * @property string $weight
 * @property string $price
 * @property string $money
 * @property string $fix_weight
 * @property string $fix_price
 * @property string $fix_money
 * @property string $input_weight
 * @property string $yidan
 * @property string $status
 * @property string $confirm
 * @property string $title
 * @property integer $flag
 */
class PurchaseDataData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('flag', 'numerical', 'integerOnly'=>true),
			array('form_sn, created_date, supply, product, rank, length, texture, brand, amount, weight, price, money, fix_weight, fix_price, fix_money, input_weight, yidan, status, confirm, title', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, form_sn, created_date, supply, product, rank, length, texture, brand, amount, weight, price, money, fix_weight, fix_price, fix_money, input_weight, yidan, status, confirm, title, flag', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'form_sn' => 'Form Sn',
			'created_date' => 'Created Date',
			'supply' => 'Supply',
			'product' => 'Product',
			'rank' => 'Rank',
			'length' => 'Length',
			'texture' => 'Texture',
			'brand' => 'Brand',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'price' => 'Price',
			'money' => 'Money',
			'fix_weight' => 'Fix Weight',
			'fix_price' => 'Fix Price',
			'fix_money' => 'Fix Money',
			'input_weight' => 'Input Weight',
			'yidan' => 'Yidan',
			'status' => 'Status',
			'confirm' => 'Confirm',
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
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('supply',$this->supply,true);
		$criteria->compare('product',$this->product,true);
		$criteria->compare('rank',$this->rank,true);
		$criteria->compare('length',$this->length,true);
		$criteria->compare('texture',$this->texture,true);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('fix_weight',$this->fix_weight,true);
		$criteria->compare('fix_price',$this->fix_price,true);
		$criteria->compare('fix_money',$this->fix_money,true);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('yidan',$this->yidan,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('confirm',$this->confirm,true);
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
	 * @return PurchaseData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
