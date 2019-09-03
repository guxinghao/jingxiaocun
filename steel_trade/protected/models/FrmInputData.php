<?php

/**
 * This is the model class for table "frm_input".
 *
 * The followings are the available columns in table 'frm_input':
 * @property integer $id
 * @property string $input_type
 * @property integer $purchase_id
 * @property integer $input_date
 * @property integer $warehouse_id
 * @property string $from
 * @property integer $push_id
 * @property integer $input_status
 * @property integer $plan_id
 * @property integer $input_at
 * @property integer $input_by
 * @property integer $input_time
 * @property integer $goods_status
 *
 * The followings are the available model relations:
 * @property CommonForms $purchase
 * @property InputDetail[] $inputDetails
 */
class FrmInputData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_input';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('purchase_id, input_date, warehouse_id, push_id, input_status, plan_id, input_at, input_by, input_time, goods_status', 'numerical', 'integerOnly'=>true),
			array('input_type, from', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, input_type, purchase_id, input_date, warehouse_id, from, push_id, input_status, plan_id, input_at, input_by, input_time, goods_status', 'safe', 'on'=>'search'),
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
			'purchase' => array(self::BELONGS_TO, 'CommonForms', 'purchase_id'),
			'inputDetails' => array(self::HAS_MANY, 'InputDetail', 'input_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'input_type' => 'Input Type',
			'purchase_id' => 'Purchase',
			'input_date' => 'Input Date',
			'warehouse_id' => 'Warehouse',
			'from' => 'From',
			'push_id' => 'Push',
			'input_status' => 'Input Status',
			'plan_id' => 'Plan',
			'input_at' => 'Input At',
			'input_by' => 'Input By',
			'input_time' => 'Input Time',
			'goods_status' => 'Goods Status',
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
		$criteria->compare('input_type',$this->input_type,true);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('input_date',$this->input_date);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('push_id',$this->push_id);
		$criteria->compare('input_status',$this->input_status);
		$criteria->compare('plan_id',$this->plan_id);
		$criteria->compare('input_at',$this->input_at);
		$criteria->compare('input_by',$this->input_by);
		$criteria->compare('input_time',$this->input_time);
		$criteria->compare('goods_status',$this->goods_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmInput the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
