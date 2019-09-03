<?php

/**
 * This is the model class for table "frm_input_dx".
 *
 * The followings are the available columns in table 'frm_input_dx':
 * @property integer $id
 * @property integer $supply_id
 * @property integer $title_id
 * @property integer $warehouse_id
 * @property integer $team_id
 * @property integer $contact_id
 * @property integer $amount
 * @property string $weight
 */
class FrmInputDxData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'frm_input_dx';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supply_id, title_id, warehouse_id', 'required'),
			array('supply_id, title_id, warehouse_id, team_id, contact_id, amount', 'numerical', 'integerOnly'=>true),
			array('weight', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supply_id, title_id, warehouse_id, team_id, contact_id, amount, weight', 'safe', 'on'=>'search'),
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
			'supply_id' => 'Supply',
			'title_id' => 'Title',
			'warehouse_id' => 'Warehouse',
			'team_id' => 'Team',
			'contact_id' => 'Contact',
			'amount' => 'Amount',
			'weight' => 'Weight',
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
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmInputDx the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
