<?php

/**
 * This is the model class for table "backstorage_data".
 *
 * The followings are the available columns in table 'backstorage_data':
 * @property integer $id
 * @property string $warehouse
 * @property string $input_date
 * @property string $comment
 * @property string $card_no
 * @property string $rank
 * @property string $product
 * @property string $texture
 * @property string $brand
 * @property string $length
 * @property string $unit_weight
 * @property string $amount
 * @property string $weight
 * @property string $cost_price
 * @property string $cost_money
 * @property string $supply
 * @property string $cgd_sn
 * @property string $dx
 * @property string $title
 * @property integer $flag
 */
class BackstorageDataData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'backstorage_data';
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
			array('warehouse, input_date, comment, card_no, rank, product, texture, brand, length, unit_weight, amount, weight, cost_price, cost_money, supply, cgd_sn, dx, title', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, warehouse, input_date, comment, card_no, rank, product, texture, brand, length, unit_weight, amount, weight, cost_price, cost_money, supply, cgd_sn, dx, title, flag', 'safe', 'on'=>'search'),
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
