<?php

/**
 * This is the model class for table "owner_transfer".
 *
 * The followings are the available columns in table 'owner_transfer':
 * @property integer $id
 * @property integer $title_id
 * @property integer $company_id
 * @property integer $team_id
 * @property string $comment
 * @property integer $frm_sales_id
 * @property string $company_name
 * @property integer $warehouse_id
 * @property integer $input_status
 *
 * The followings are the available model relations:
 * @property DictCompany $company
 * @property DictTitle $title
 * @property Team $team
 * @property OwnerTransferDetail[] $ownerTransferDetails
 */
class OwnerTransferData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'owner_transfer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title_id, company_id, frm_sales_id', 'required'),
			array('title_id, company_id, team_id, frm_sales_id, warehouse_id, input_status', 'numerical', 'integerOnly'=>true),
			array('comment', 'length', 'max'=>255),
			array('company_name', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title_id, company_id, team_id, comment, frm_sales_id, company_name, warehouse_id, input_status', 'safe', 'on'=>'search'),
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
			'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
			'ownerTransferDetails' => array(self::HAS_MANY, 'OwnerTransferDetail', 'owner_transfer_id'),
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
			'team_id' => 'Team',
			'comment' => 'Comment',
			'frm_sales_id' => 'Frm Sales',
			'company_name' => 'Company Name',
			'warehouse_id' => 'Warehouse',
			'input_status' => 'Input Status',
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
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('frm_sales_id',$this->frm_sales_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('input_status',$this->input_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OwnerTransfer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
