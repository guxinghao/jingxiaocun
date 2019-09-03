<?php

/**
 * This is the model class for table "merge_storage_bak".
 *
 * The followings are the available columns in table 'merge_storage_bak':
 * @property integer $id
 * @property integer $product_id
 * @property integer $brand_id
 * @property integer $texture_id
 * @property integer $rank_id
 * @property string $status
 * @property integer $length
 * @property string $cost_price
 * @property integer $title_id
 * @property integer $redeem_company_id
 * @property string $input_weight
 * @property integer $input_amount
 * @property integer $left_amount
 * @property string $left_weight
 * @property integer $retain_amount
 * @property string $retain_weight
 * @property integer $lock_amount
 * @property string $lock_weight
 * @property integer $pre_input_date
 * @property integer $pre_input_time
 * @property integer $is_transit
 * @property integer $storage_id
 * @property integer $warehouse_id
 * @property string $invoice_price
 * @property integer $is_deleted
 * @property integer $last_update
 * @property string $bak_date
 */
class MergeStorageBakData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'merge_storage_bak';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, brand_id, texture_id, rank_id, title_id', 'required'),
			array('product_id, brand_id, texture_id, rank_id, length, title_id, redeem_company_id, input_amount, left_amount, retain_amount, lock_amount, pre_input_date, pre_input_time, is_transit, storage_id, warehouse_id, is_deleted, last_update', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>45),
			array('cost_price, invoice_price', 'length', 'max'=>11),
			array('input_weight, left_weight, retain_weight, lock_weight', 'length', 'max'=>15),
			array('bak_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_id, brand_id, texture_id, rank_id, status, length, cost_price, title_id, redeem_company_id, input_weight, input_amount, left_amount, left_weight, retain_amount, retain_weight, lock_amount, lock_weight, pre_input_date, pre_input_time, is_transit, storage_id, warehouse_id, invoice_price, is_deleted, last_update, bak_date', 'safe', 'on'=>'search'),
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
			'product_id' => '品名',
			'brand_id' => '产地',
			'texture_id' => '材质',
			'rank_id' => '规格',
			'status' => '库存状态',
			'length' => '长度',
			'cost_price' => '成本单价',
			'title_id' => '销售公司',
			'redeem_company_id' => '托盘公司',
			'input_weight' => '入库重量',
			'input_amount' => '入库件数',
			'left_amount' => '剩余件数',
			'left_weight' => '剩余重量',
			'retain_amount' => '保留件数',
			'retain_weight' => '保留重量',
			'lock_amount' => '锁定件数',
			'lock_weight' => '锁定重量',
			'pre_input_date' => '船舱入库预计到货时间',
			'pre_input_time' => '预计到货时段
6：0-6，
12：6-12，
18：12-18，
24：18-24',
			'is_transit' => '是否船舱入库',
			'storage_id' => '船舱入库对应库存表id',
			'warehouse_id' => '仓库id',
			'invoice_price' => '票价成本',
			'is_deleted' => '是否删除',
			'last_update' => '最后更新时间',
			'bak_date' => '备份时间',
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('redeem_company_id',$this->redeem_company_id);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('left_amount',$this->left_amount);
		$criteria->compare('left_weight',$this->left_weight,true);
		$criteria->compare('retain_amount',$this->retain_amount);
		$criteria->compare('retain_weight',$this->retain_weight,true);
		$criteria->compare('lock_amount',$this->lock_amount);
		$criteria->compare('lock_weight',$this->lock_weight,true);
		$criteria->compare('pre_input_date',$this->pre_input_date);
		$criteria->compare('pre_input_time',$this->pre_input_time);
		$criteria->compare('is_transit',$this->is_transit);
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('invoice_price',$this->invoice_price,true);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('bak_date',$this->bak_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MergeStorageBak the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
