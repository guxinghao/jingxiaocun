<?php

/**
 * This is the biz model class for table "storage_bak".
 *
 */
class StorageBak extends StorageBakData
{
    public $product_id;
    public $brand_id;
    public $texture_id;
    public $rank_id;
    public $product_name;
    public $brand_name;
    public $texture_name;
    public $rank_name;
    public $length;
    public $input_type;
    public $input_weight_sum;
    public $input_amount_sum;
    
    public $available_amount;
    public $available_weight;
    public $weight;
    
    public $total_amount;
    public $total_weight;
    public $total_num;
 

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'outputDetails' => array(self::HAS_MANY, 'OutputDetail', 'storage_id'),
			'inputDetail' => array(self::BELONGS_TO, 'InputDetail', 'input_detail_id'),
			'inputDetailDx' => array(self::BELONGS_TO, 'InputDetailDx', 'input_detail_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'redeemCompany' => array(self::BELONGS_TO, 'DictCompany', 'redeem_company_id'),
			'frmInput' => array(self::BELONGS_TO, 'FrmInput', 'frm_input_id'),
			'frmInputDx' => array(self::BELONGS_TO, 'FrmInputDx', 'frm_input_id'),
			'storageChangeLogs' => array(self::HAS_MANY, 'StorageChangeLog', 'storage_id'),
			'warehouse'=>array(self::BELONGS_TO, 'Warehouse','warehouse_id'),
			'mergeStorage'=>array(self::HAS_ONE,'MergeStorage','storage_id','condition'=>'mergeStorage.is_deleted=0'),
			'purchase'=>array(self::BELONGS_TO,'FrmPurchase','purchase_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'storage_id' => 'Storage',
			'card_no' => 'Card No',
			'input_detail_id' => 'Input Detail',
			'title_id' => 'Title',
			'card_status' => 'Card Status',
			'redeem_company_id' => 'Redeem Company',
			'input_weight' => 'Input Weight',
			'input_amount' => 'Input Amount',
			'left_amount' => 'Left Amount',
			'left_weight' => 'Left Weight',
			'retain_amount' => 'Retain Amount',
			'input_date' => 'Input Date',
			'lock_amount' => 'Lock Amount',
			'pre_input_date' => 'Pre Input Date',
			'frm_input_id' => 'Frm Input',
			'cost_price' => 'Cost Price',
			'is_price_confirmed' => 'Is Price Confirmed',
			'invoice_price' => 'Invoice Price',
			'is_pledge' => 'Is Pledge',
			'is_yidan' => 'Is Yidan',
			'is_dx' => 'Is Dx',
			'warehouse_id' => 'Warehouse',
			'is_deleted' => 'Is Deleted',
			'bak_date' => 'Bak Date',
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
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('input_detail_id',$this->input_detail_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('card_status',$this->card_status,true);
		$criteria->compare('redeem_company_id',$this->redeem_company_id);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('input_amount',$this->input_amount,true);
		$criteria->compare('left_amount',$this->left_amount);
		$criteria->compare('left_weight',$this->left_weight,true);
		$criteria->compare('retain_amount',$this->retain_amount);
		$criteria->compare('input_date',$this->input_date);
		$criteria->compare('lock_amount',$this->lock_amount);
		$criteria->compare('pre_input_date',$this->pre_input_date);
		$criteria->compare('frm_input_id',$this->frm_input_id);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('is_price_confirmed',$this->is_price_confirmed);
		$criteria->compare('invoice_price',$this->invoice_price,true);
		$criteria->compare('is_pledge',$this->is_pledge);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('is_dx',$this->is_dx);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('bak_date',$this->bak_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StorageBak the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 获取库存数
	 * @param $et 结束时间戳
	 */
	public function getAmount($et){
	    //非代销
	    $cri = new CDbCriteria();
	    $cri->select = "sum(t.left_amount) as input_amount_sum";
	    $cri->join = "left join input_detail d on t.input_detail_id=d.id
					left join frm_input i on t.frm_input_id = i.id";
	    $cri->addCondition("t.is_deleted = 0");
	    $cri->addCondition("t.is_dx=0");//非代销
	    $cri->addCondition("i.input_type<>'ccrk'");//去除船舱入库
	    $cri->addCondition("i.input_status = 1");//需已入库
	    $cri->addCondition("t.warehouse_id=$this->warehouse_id");
	    $cri->addCondition("d.product_id = $this->product_id");
	    $cri->group = "t.warehouse_id,d.product_id";
	    $cri->addCondition("t.input_date<=$et");
	    $result = Storage::model()->findAll($cri);
	
	    $not_dx = $result[0]->input_amount_sum;
	
	    //		//代销
	    //		$cri_ = new CDbCriteria();
	    //		$cri_->select = "sum(t.input_amount) as input_amount_sum";
	    //		$cri_->join = "left join input_detail_dx d on t.input_detail_id=d.id";
	    //		$cri_->addCondition("t.is_deleted = 0");
	    //		$cri_->addCondition("t.is_dx=1");//代销
	    //		$cri_->addCondition("t.warehouse_id=$this->warehouse_id");
	    //		$cri_->group = "t.warehouse_id,d.product_id";
	    //		$cri_->addCondition("t.input_date<$et");
	    //		$result_ = Storage::model()->findAll($cri_);
	    //
	    //		$is_dx = $result[0]->input_amount_sum;
	    return $not_dx?$not_dx:0;
	    //		return $not_dx+$is_dx;
	
	}
	/**
	* 获取库存重量
	* @param $et 结束时间戳
	*/
	public function getWeight($et){
	//非代销
	$cri = new CDbCriteria();
	$cri->select = "sum(t.left_weight) as input_weight_sum";
	$cri->join = "left join input_detail d on t.input_detail_id=d.id
					left join frm_input i on t.frm_input_id = i.id";
						$cri->addCondition("t.is_deleted = 0");
						$cri->addCondition("t.is_dx=0");//非代销
						$cri->addCondition("i.input_type<>'ccrk'");//去除船舱入库
						$cri->addCondition("i.input_status = 1");//需已入库
						$cri->addCondition("t.warehouse_id=$this->warehouse_id");
						$cri->addCondition("d.product_id = $this->product_id");
		$cri->group = "t.warehouse_id,d.product_id";
			$cri->addCondition("t.input_date<=$et");
			$result = Storage::model()->findAll($cri);
			$not_dx = $result[0]->input_weight_sum;
	
						//		//代销
						//		$cri_ = new CDbCriteria();
						//		$cri_->select = "sum(t.input_weight) as input_weight_sum";
						//		$cri_->join = "left join input_detail_dx d on t.input_detail_id=d.id";
						//		$cri_->addCondition("t.is_deleted = 0");
						//		$cri_->addCondition("t.is_dx=1");//代销
						//		$cri_->addCondition("t.warehouse_id=$this->warehouse_id");
						//		$cri_->group = "t.warehouse_id,d.product_id";
						//		$cri_->addCondition("t.input_date<$et");
						//		$result_ = Storage::model()->findAll($cri_);
						//
	//		$is_dx = $result[0]->input_weight_sum;
			return $not_dx?$not_dx:0;
	//		return $not_dx+$is_dx;
		}
}
