<?php

/**
 * This is the model class for table "dict_goods_property".
 *
 * The followings are the available columns in table 'dict_goods_property':
 * @property integer $id
 * @property string $property_type
 * @property string $name
 * @property string $short_name
 * @property string $code
 * @property string $std
 * @property integer $last_update
 * @property string $reserve
 * @property integer $is_available
 * @property integer $priority
 */
class DictGoodsPropertyData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dict_goods_property';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('last_update, is_available, priority', 'numerical', 'integerOnly'=>true),
			array('property_type, name, short_name, code, std, reserve', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, property_type, name, short_name, code, std, last_update, reserve, is_available, priority', 'safe', 'on'=>'search'),
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
			'property_type' => '类型： product:品名  texture:材质 brand:产地  rank: 规格',
			'name' => '产地名称',
			'short_name' => '简称',
			'code' => '拼音',
			'std' => '标准码',
			'last_update' => '最后更新时间',
			'reserve' => '保留字段，可以用于记录不同品名的记重方式',
			'is_available' => '是否可用，1为新的数据
		0为老的数据',
			'priority' => '优先级',
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
		$criteria->compare('property_type',$this->property_type,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('std',$this->std,true);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('reserve',$this->reserve,true);
		$criteria->compare('is_available',$this->is_available);
		$criteria->compare('priority',$this->priority);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictGoodsProperty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
