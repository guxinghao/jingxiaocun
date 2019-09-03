<?php

/**
 * This is the model class for table "frm_send_detail".
 *
 * The followings are the available columns in table 'frm_send_detail':
 * @property integer $id
 * @property integer $frm_send_id
 * @property integer $sales_detail_id
 * @property integer $amount
 * @property string $weight
 * @property integer $output_amount
 * @property string $output_weight
 * @property integer $product_id
 * @property integer $brand_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property integer $length
 *
 * The followings are the available model relations:
 * @property FrmSend $frmSend
 */
class FrmSendDetailData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_send_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('frm_send_id', 'required'),
			array('frm_send_id, sales_detail_id, amount, output_amount, product_id, brand_id, texture_id, rank_id, length', 'numerical', 'integerOnly'=>true),
			array('weight, output_weight', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, frm_send_id, sales_detail_id, amount, weight, output_amount, output_weight, product_id, brand_id, texture_id, rank_id, length', 'safe', 'on'=>'search'),
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
			'frmSend' => array(self::BELONGS_TO, 'FrmSend', 'frm_send_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'frm_send_id' => 'Frm Send',
			'sales_detail_id' => 'Sales Detail',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
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
		$criteria->compare('frm_send_id',$this->frm_send_id);
		$criteria->compare('sales_detail_id',$this->sales_detail_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSendDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
