<?php

/**
 * This is the model class for table "detail_for_invoice".
 *
 * The followings are the available columns in table 'detail_for_invoice':
 * @property integer $id
 * @property string $type
 * @property integer $form_id
 * @property integer $detail_id
 * @property string $checked_weight
 * @property string $checked_money
 * @property string $weight
 * @property string $money
 * @property integer $title_id
 * @property integer $company_id
 * @property integer $pledge_id
 * @property integer $client_id
 */
class DetailForInvoiceData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'detail_for_invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, form_id, title_id, company_id, pledge_id', 'required'),
			array('form_id, detail_id, title_id, company_id, pledge_id, client_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>45),
			array('checked_weight, weight', 'length', 'max'=>15),
			array('checked_money, money', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, form_id, detail_id, checked_weight, checked_money, weight, money, title_id, company_id, pledge_id, client_id', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'form_id' => 'Form',
			'detail_id' => 'Detail',
			'checked_weight' => 'Checked Weight',
			'checked_money' => 'Checked Money',
			'weight' => 'Weight',
			'money' => 'Money',
			'title_id' => 'Title',
			'company_id' => 'Company',
			'pledge_id' => 'Pledge',
			'client_id' => 'Client',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('form_id',$this->form_id);
		$criteria->compare('detail_id',$this->detail_id);
		$criteria->compare('checked_weight',$this->checked_weight,true);
		$criteria->compare('checked_money',$this->checked_money,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('pledge_id',$this->pledge_id);
		$criteria->compare('client_id',$this->client_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DetailForInvoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
