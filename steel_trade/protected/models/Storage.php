<?php

/**
 * This is the biz model class for table "storage".
 *
 */
class Storage extends StorageData
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
	public $i_amount;
	public $i_weight;
	public $l_amount;
	public $l_weight;
	public $r_amount;
	public $ll_amount;
	public $ll_weight;
	public $can_amount;
	public $can_weight;
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
			'card_no' => 'Card No',
			'input_detail_id' => 'Input Detail',
			'card_status' => 'Card Status',
			'title_id' => 'Title',
			'redeem_company_id' => 'Redeem Company',
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'left_amount' => 'Left Amount',
			'left_weight' => 'Left Weight',
			'retain_amount' => 'Retain Amount',
			'input_date' => 'Input Date',
			'frm_input_id' => 'Frm Input',
			'cost_price' => 'Cost Price',
			'is_price_confirmed' => 'Is Price Confirmed',
			'invoice_price' => 'Invoice Price',
			'is_yidan' => 'Is Yidan',
			'is_pledge' => 'Is Pledge',
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
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('input_detail_id',$this->input_detail_id);
		$criteria->compare('card_status',$this->card_status,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('redeem_company_id',$this->redeem_company_id);
		$criteria->compare('input_amount',$this->input_amount,true);
		$criteria->compare('input_weight',$this->input_weight);
		$criteria->compare('left_amount',$this->left_amount);
		$criteria->compare('left_weight',$this->left_weight);
		$criteria->compare('retain_amount',$this->retain_amount);
		$criteria->compare('input_date',$this->input_date);
		$criteria->compare('frm_input_id',$this->frm_input_id);
		$criteria->compare('cost_price',$this->cost_price);
		$criteria->compare('is_price_confirmed',$this->is_price_confirmed);
		$criteria->compare('invoice_price',$this->invoice_price);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('is_pledge',$this->is_pledge);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Storage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * 获取销售单库存列表
	 */
	public static function getFormList($search)
	{
		$tableData=array();
		$model = new Storage();
		$criteria=New CDbCriteria();
		$criteria->with = array('inputDetail');
		if(!empty($search)){
			$criteria->compare('t.is_dx',0,false);
			if($search['card_no']!='')
			{
				$criteria->addCondition("t.card_no like '%".$search['card_no']."%'");
			}
			if($search['warehouse_id']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse_id'],false);
			}

			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('inputDetail.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('inputDetail.product_id',$search['product'],false);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('inputDetail.rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('inputDetail.texture_id',$search['texture'],false);
			}
		}
		$criteria->addCondition("t.is_deleted=0");
		$totaldata = array();
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['storage_list']) ? intval($_COOKIE['storage_list']) : Yii::app()->params['pageCount'];
		//$pages->pageSize = 2;
		$pages->applyLimit($criteria);
				
		$details=$model->findAll($criteria);
		$num = 0;
		$yidan = array("否","是");
		if($details)
		{
			$da[] = array();
			foreach ($details as $li){
				//如果是代销，获取每日代销价格
				if($search['sales']){
					$li->cost_price = 0; //暂定为0
				}
				$product_std = $li->inputDetail->product_id;
				$rand_std = $li->inputDetail->rank_id;
				$texture_std = $li->inputDetail->texture_id;
				$brand_std = $li->inputDetail->brand_id;
				$length = $li->inputDetail->length;
				$str = '<input type="checkbox" card_id="'.$li->id.'" value="'.$li->card_no.'" class="checkit" cost="'.$li->cost_price.'">'
						.'<input type="hidden" class="product_std" value="'.$product_std.'">'
						.'<input type="hidden" class="rand_std" value="'.$rand_std.'">'
						.'<input type="hidden" class="texture_std" value="'.$texture_std.'">'
						//.'<input type="hidden" class="length_std" value="'.$li->inputDetail->length.'">'
						.'<input type="hidden" class="brand_std" value="'.$brand_std.'">'
						.'<input type="hidden" class="title_id" value="'.$li->title_id.'">'
						.'<input type="hidden" class="warehouse_id" value="'.$li->warehouse->id.'">';
				$sell = $li->left_amount-$li->retain_amount-$li->lock_amount;
				if($sell <= 0){continue;}
				$type['product'] = $product_std;
				$type['rank'] = $rand_std;
				$type['brand'] = $brand_std;
				$type['texture'] = $texture_std;
				$type['length'] = $length;
				$weight = DictGoods::getUnitWeight($type);
				
				$can_weight = $sell * $weight;
				$num++;
				$da["data"] = array(
						$num,
						$str,
						'<span class="title_name">'.$li->title->short_name.'</span>',
						$li->card_no,
						'<span class="warehouse_name">'.$li->warehouse->name.'</span>',//仓库
						'<span class="brand">'.DictGoodsProperty::getProName($brand_std).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($product_std).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($texture_std)).'</span>',
						'<span class="rand">'.DictGoodsProperty::getProName($rand_std).'</span>',
						'<span class="length">'.$length.'</span>',
						'<span class="surplus">'.$sell.'</span>',
						$li->input_amount,
						'<span class="canweight">'.number_format($can_weight,3).'</span>',
						$yidan[$li->is_yidan],
						$li->pre_input_date?date("Y-m-d",$li->pre_input_date):'',
						$li->input_date?date("Y-m-d",$li->input_date):'',
						);
				
				$da["group"] = 1;
				array_push($tableData,$da);
			}
		}
		
		return array($tableData,$pages,$totaldata);
	}

	/*
	 * 获取代销销售单库存列表
	 */
	public static function getDxFormList($search)
	{
		$tableData=array();
		$model = new Storage();
		$criteria=New CDbCriteria();
		$criteria->with = array('inputDetailDx','inputDetailDx.input');
		$criteria->compare('t.title_id',$search['title_id'],false);
		if(!empty($search)){
			$criteria->compare('t.is_dx',1,false);
			if($search['card_no']!='')
			{
				$criteria->addCondition("t.card_no like '%".$search['card_no']."%'");
			}
			if($search['warehouse_id']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse'],false);
			}
	
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('inputDetailDx.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('inputDetailDx.product_id',$search['product'],false);
			}
			if($search['supply']!='0')
			{
				$criteria->compare('input.supply_id',$search['supply'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('inputDetailDx.texture_id',$search['texture'],false);
			}
			if($search['rank']!='0')
			{
				$criteria->compare('inputDetailDx.rank_id',$search['rank'],false);
			}
			if($search['length']>=0)
			{
				$criteria->compare('inputDetailDx.length',$search['length'],false);
			}
		}
		$criteria->addCondition("(t.left_amount-t.retain_amount-t.lock_amount)>0");
		$criteria->addCondition("t.is_deleted=0");
		$criteria->order = "t.warehouse_id,inputDetailDx.brand_id,inputDetailDx.product_id,inputDetailDx.texture_id,inputDetailDx.rank_id,inputDetailDx.length,t.pre_input_date";
		$totaldata = array();
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['storage_list']) ? intval($_COOKIE['storage_list']) : Yii::app()->params['pageCount'];
		//$pages->pageSize = 2;
		$pages->applyLimit($criteria);
	
		$details=$model->findAll($criteria);
		$num = 0;
		$yidan = array("否","是");
		if($details)
		{
			$da[] = array();
			foreach ($details as $li){
				//如果是代销，获取每日代销价格
				if($search['sales']){
					$li->cost_price = 0; //暂定为0
				}
				$sell = $li->left_amount-$li->retain_amount-$li->lock_amount;
				if($sell <= 0){continue;}
				$product_std = $li->inputDetailDx->product_id;
				$rand_std = $li->inputDetailDx->rank_id;
				$texture_std = $li->inputDetailDx->texture_id;
				$brand_std = $li->inputDetailDx->brand_id;
				$length = $li->inputDetailDx->length;
				$str = '<input type="checkbox" card_id="'.$li->id.'" value="'.$li->card_no.'" class="checkit" cost="'.$li->cost_price.'">'
						.'<input type="hidden" class="storage_id" value="'.$li->id.'">'
						.'<input type="hidden" class="product_std" value="'.$product_std.'">'
						.'<input type="hidden" class="rand_std" value="'.$rand_std.'">'
						.'<input type="hidden" class="texture_std" value="'.$texture_std.'">'
						//.'<input type="hidden" class="length_std" value="'.$li->inputDetail->length.'">'
						.'<input type="hidden" class="brand_std" value="'.$brand_std.'">'
						.'<input type="hidden" class="supply_id" value="'.$li->inputDetailDx->input->supply_id.'">'
						.'<input type="hidden" class="title_id" value="'.$li->title_id.'">'
						.'<input type="hidden" class="warehouse_id" value="'.$li->warehouse->id.'">'
						.'<input type="hidden" class="can_surplus" value="'.$sell.'">';
				$num++;
				$type['product'] = $product_std;
				$type['rank'] = $rand_std;
				$type['brand'] = $brand_std;
				$type['texture'] = $texture_std;
				$type['length'] = $length;
				$weight = DictGoods::getUnitWeight($type);
				if($weight == 0){
					$weight = $li->input_weight/$li->input_amount;
				}
				if($sell > 100) {$sell = 100;}
				$can_weight = $sell * $weight;
				$da["data"] = array(
						$num,
						$str,
						'<span class="title_name">'.$li->title->short_name.'</span>',
						'<span class="supply_name" title="'.$li->inputDetailDx->input->supply->name.'">'.$li->inputDetailDx->input->supply->short_name.'</span>',
						$li->card_no,
						'<span class="warehouse_name">'.$li->warehouse->name.'</span>',//仓库
						'<span class="brand">'.DictGoodsProperty::getProName($brand_std).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($product_std).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($texture_std)).'</span>',
						'<span class="rand">'.DictGoodsProperty::getProName($rand_std).'</span>',
						'<span class="length">'.$length.'</span>',
						'<span class="surplus">'.$sell.'</span>',
						'<span class="canweight">'.number_format($can_weight,3).'</span>',
// 						$li->pre_input_date?date("Y-m-d",$li->pre_input_date):'',
// 						$li->input_date?date("Y-m-d",$li->input_date):'',
				);
				$da["group"] = $num;
				array_push($tableData,$da);
			}
		}
	
		return array($tableData,$pages,$totaldata);
	}
	
	/*
	 * 获取采购退货库存列表
	 */
	public static function getPReturnFormList($search)
	{
		$tableData=array();
		$model = new Storage();
		$criteria=New CDbCriteria();
		$criteria->with = array('inputDetail','inputDetail.input');
		$criteria->compare('t.is_dx',0,false);
		if(!empty($search)){
			if($search['warehouse_id']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse_id'],false);
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('t.title_id',$search['title_id'],false);
			}
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('inputDetail.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('inputDetail.product_id',$search['product'],false);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('inputDetail.rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('inputDetail.texture_id',$search['texture'],false);
			}
			if($search['length']>=0)
			{
				$criteria->compare('inputDetail.length',$search['length'],false);
			}
		}
		$criteria->addCondition("(t.left_amount-t.retain_amount-t.lock_amount)>0");
		$criteria->addCondition("t.is_deleted=0");
		$criteria->addCondition("t.is_dx=0");
		$criteria->addCondition("input.input_type!='ccrk'");
		$criteria->order = "inputDetail.product_id,inputDetail.texture_id,inputDetail.rank_id,inputDetail.length";
		$totaldata = array();
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['preturnstorage_list']) ? intval($_COOKIE['preturnstorage_list']) : Yii::app()->params['pageCount'];
		//$pages->pageSize = 2;
		$pages->applyLimit($criteria);
	
		$details=$model->findAll($criteria);
		$num = 0;
		if($details)
		{
			$da[] = array();
			foreach ($details as $li){
				//如果是代销，获取每日代销价格
				if($search['sales']){
					$li->cost_price = 0; //暂定为0
				}
				$product_std = $li->inputDetail->product_id;
				$rand_std = $li->inputDetail->rank_id;
				$texture_std = $li->inputDetail->texture_id;
				$brand_std = $li->inputDetail->brand_id;
				$length = $li->inputDetail->length;
				$type['product'] = $product_std;
				$type['rank'] = $rand_std;
				$type['brand'] = $brand_std;
				$type['texture'] = $texture_std;
				$type['length'] = $length;
				$weight = DictGoods::getUnitWeight($type);
				if($weight == 0){
					$weight = $li->input_weight/$li->input_amount;
				}
				$str = '<input type="checkbox" card_id="'.$li->id.'" value="'.$li->card_no.'" class="checkit">'
						.'<input type="hidden" class="storage_id" value="'.$li->id.'">'
						.'<input type="hidden" class="product_std" value="'.$product_std.'">'
						.'<input type="hidden" class="rand_std" value="'.$rand_std.'">'
						.'<input type="hidden" class="texture_std" value="'.$texture_std.'">'
						//.'<input type="hidden" class="length_std" value="'.$li->inputDetail->length.'">'
						.'<input type="hidden" class="brand_std" value="'.$brand_std.'">'
						.'<input type="hidden" class="supply_id" value="'.$li->inputDetail->purchaseDetail->frmPurchase->supply_id.'">'
						.'<input type="hidden" class="title_id" value="'.$li->title_id.'">'
						.'<input type="hidden" class="weight" value="'.$weight.'">'
						.'<input type="hidden" class="warehouse_id" value="'.$li->warehouse->id.'">';
				
				$sell = $li->left_amount-$li->retain_amount-$li->lock_amount;
				if($sell <= 0){continue;}
				$can_weight = $sell * $weight;
				$num++;
				$da["data"] = array(
						$num,
						$str,
						'<span class="title_name">'.$li->title->short_name.'</span>',
						'<span class="supply_name">'.$li->inputDetail->purchaseDetail->frmPurchase->supply->short_name.'</span>',
						$li->card_no,
						'<span class="warehouse_name">'.$li->warehouse->name.'</span>',//仓库
						'<span class="brand">'.DictGoodsProperty::getProName($brand_std).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($product_std).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($texture_std)).'</span>',
						'<span class="rand">'.DictGoodsProperty::getProName($rand_std).'</span>',
						'<span class="length">'.$length.'</span>',
						'<span class="surplus">'.$sell.'</span>',
						'<span class="canweight">'.number_format($can_weight,3).'</span>',
						date("Y-m-d",$li->input_date)
				);
				
				$da["group"] = $num;
				array_push($tableData,$da);
			}
		}
	
		return array($tableData,$pages,$totaldata);
	}
	
	/*
	 * 获取出库单库存列表
	 */
	public static function getCkFormList($search,$card_id)
	{
		$tableData=array();
		$model = new Storage();
		$criteria=New CDbCriteria();
		$criteria->with = array('inputDetail');
		if($card_id==""){
			$criteria->addCondition("t.id in (0)");
		}else if($card_id == "all"){
			//$criteria->addCondition("t.id in (".$card_id.")");
		}else{
			$criteria->addCondition("t.id in (".$card_id.")");
		}
		if(!empty($search)){
			if($search['card_no']!='')
			{
				$criteria->addCondition("t.card_no like '%".$search['card_no']."%'");
			}
	
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('inputDetail.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('inputDetail.product_id',$search['product'],false);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('inputDetail.rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('inputDetail.texture_id',$search['texture'],false);
			}
		}
		$criteria->addCondition("(t.left_amount-t.retain_amount-t.lock_amount)>0");
		$criteria->order = "inputDetail.product_id,inputDetail.texture_id,inputDetail.rank_id,inputDetail.length";
		$totaldata = array();
// 		$pages = new CPagination();
// 		$pages->itemCount = $model->count($criteria);
// 		$pages->pageSize = intval($_COOKIE['storage_list']) ? intval($_COOKIE['storage_list']) : Yii::app()->params['pageCount'];
// 		//$pages->pageSize = 2;
// 		$pages->applyLimit($criteria);
	
		$details=$model->findAll($criteria);
		$num = 0;
		$yidan = array("否","是");
		if($details)
		{
			$da[] = array();
			foreach ($details as $li){
				//如果是代销，获取每日代销价格
				if($search['sales']){
					$li->cost_price = 0; //暂定为0
				}
				$product_std = $li->inputDetail->product_id;
				$rand_std = $li->inputDetail->rank_id;
				$texture_std = $li->inputDetail->texture_id;
				$brand_std = $li->inputDetail->brand_id;
				$length = $li->inputDetail->length;
				$type['product'] = $product_std;
				$type['rank'] = $rand_std;
				$type['brand'] = $brand_std;
				$type['texture'] = $texture_std;
				$type['length'] = $length;
				$weight = DictGoods::getUnitWeight($type);
				if($weight == 0){
					$weight = $li->input_weight/$li->input_amount;
				}
				$str = '<input type="checkbox" value="'.$li->id.'" class="checkit" cost="'.$li->cost_price.'">'
						.'<input type="hidden" class="storage_id" value="'.$li->id.'">'
						.'<input type="hidden" class="product_std" value="'.$product_std.'">'
						.'<input type="hidden" class="rand_std" value="'.$rand_std.'">'
						.'<input type="hidden" class="texture_std" value="'.$texture_std.'">'
						.'<input type="hidden" class="weight" value="'.$weight.'">'
						//.'<input type="hidden" class="length_std" value="'.$li->inputDetail->length.'">'
						.'<input type="hidden" class="brand_std" value="'.$brand_std.'">';
				$sell = $li->left_amount-$li->retain_amount-$li->lock_amount;
				if($sell <= 0){continue;}
				$can_weight = $sell * $weight;
				$num++;
				$da["data"] = array(
						$num,
						$str,
						'<span class="card_no">'.$li->card_no.'</span>',
						$li->warehouse->name,//仓库
						'<span class="brand">'.DictGoodsProperty::getProName($brand_std).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($product_std).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($texture_std)).'</span>',
						'<span class="rand">'.DictGoodsProperty::getProName($rand_std).'</span>',
						'<span class="length">'.$length.'</span>',
						'<span class="surplus">'.$sell.'</span>',
						//$li->input_amount,
						'<span class="canweight">'.number_format($can_weight,3).'</span>',
						//$yidan[$li->is_yidan],
						//$li->pre_input_date?date("Y-m-d",$li->pre_input_date):'',
						$li->input_date?date("Y-m-d",$li->input_date):'',
				);
				$da["group"] = $num;
				array_push($tableData,$da);
			}
		}
	
		return array($tableData,$pages,$totaldata);
	}
	
	/*
	 * 插入新库存
	 */
	public static function createNew($data)
	{
		$model=new Storage();
		foreach ($data as $k=>$v)
		{
			$model->$k=$v;
		}
		if($model->insert())
		{
			return $model;
		}
		return false;
	}
	
	/*
	 * 根据卡号和仓库id获取卡号id
	 */
	public static function getStroageid($no,$warehouse_id){
		$storage = Storage::model()->find("card_no='".$no."' and warehouse_id=".$warehouse_id." and is_deleted = 0");
		$id = $storage->id;
		return $id;
	}
	
	/**
	 * 库存列表数据获取
	 */
	public static function getIndexList(){
		$model = new Storage();
		$cri = new CDbCriteria();
		$cri->select = "t.*,d.product_id as product_id,
						d.rank_id as rank_id,
						d.texture_id as texture_id,
						d.brand_id as brand_id,
						d.length as length,
						f.input_type as input_type";
		$cri->join = "left join input_detail d on t.input_detail_id = d.id 
					left join frm_input f on t.frm_input_id = f.id";
		
		$cri->addCondition("t.is_deleted = '0' ");
		$cri->order = "t.input_date desc";
		$search = new Storage();
		$search->card_status = "normal";
		if($_REQUEST["type"] == "retain")
		{
			$search->retain_amount = 1;
		}
		
		if($_REQUEST['Storage']){
			$search->product_id = $_REQUEST['Storage']['product_id'];
			$search->rank_id = $_REQUEST['Storage']['rank_id'];
			$search->texture_id = $_REQUEST['Storage']['texture_id'];
			$search->brand_id = $_REQUEST['Storage']['brand_id'];
			$search->warehouse_id = $_REQUEST['Storage']['warehouse_id'];
			$search->card_status = $_REQUEST['Storage']['card_status'];
			$search->title_id = $_REQUEST['Storage']['title_id'];
			$search->input_type = $_REQUEST['Storage']['input_type'];
			$search->card_no = $_REQUEST['Storage']['card_no'];
			$search->retain_amount = $_REQUEST['Storage']['retain_amount'];
			$search->left_amount = $_REQUEST['Storage']['left_amount'];
			$search->length = $_REQUEST['Storage']['length'];
			if($search->product_id){
				$cri->addCondition("d.product_id = ".intval($search->product_id));
				$search->product_name = DictGoodsProperty::getProName($search->product_id);
			}
			if($search->texture_id){
				$cri->addCondition("d.texture_id = ".intval($search->texture_id));
				$search->texture_name = DictGoodsProperty::getProName($search->texture_id);
			}
			if($search->rank_id){
				$cri->addCondition("d.rank_id = ".intval($search->rank_id));
				$search->rank_name = DictGoodsProperty::getProName($search->rank_id);
			}
			if($search->brand_id){
				$cri->addCondition("d.brand_id = ".intval($search->brand_id));
				$search->brand_name = DictGoodsProperty::getProName($search->brand_id);
			}
			if($search->length >=0){
				$cri->addCondition("d.length = ".intval($search->length));
			}
			if($search->warehouse_id){
				$cri->addCondition("t.warehouse_id = ".intval($search->warehouse_id));
			}
			if($search->title_id){
				$cri->addCondition("t.title_id = ".intval($search->title_id));
			}
			if($search->input_type){
				$cri->params[':input_type'] = $search->input_type;
				$cri->addCondition("f.input_type = :input_type");
			}
			if($search->card_no){
				$cri->params[':card_no'] = "%".$search->card_no."%";
				$cri->addCondition("t.card_no like :card_no");
			}
		}else{
			if($_REQUEST['card_no']){
				$search->card_no=$_REQUEST['card_no'];
				$cri->params[':card_no'] = "%".$search->card_no."%";
				$cri->addCondition("t.card_no like :card_no");
			}
		}
		if($search->retain_amount == 1){
			$cri->addCondition("t.retain_amount>0");
		}else if($search->retain_amount == 2){
			$cri->addCondition("t.retain_amount=0");
		}
		if($search->left_amount == 1){
			$cri->addCondition("t.left_amount=0");
		}else if($search->left_amount == 2){
			$cri->addCondition("t.left_amount>0");
		}
		$cri->addCondition("t.card_status = '{$search->card_status}'");
		$cri->addCondition("t.is_dx<>1");
		
		$c = clone $cri;
		$cri->order = "t.warehouse_id,d.product_id,d.texture_id,d.rank_id,d.length";
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['kc']?intval($_COOKIE['kc']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		
		$c->select = "sum(t.left_amount) as total_amount,sum(t.left_weight) as total_weight";
		$all=Storage::model()->find($c);
		
		
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		
		return array($model,$pages,$items,$search,$totaldata);
	}




	/**
	库存查询新方法
	*/

	public static function storageSearch($search)
	{

		$tableHeader = array(
				//array('name'=>'操作','class' =>"",'width'=>"50px"),
				array('name'=>'仓库','class' =>"",'width'=>"100px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'品名','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"50px"),
				array('name'=>'件重','class' =>"flex-col text-right",'width'=>"70px"),
				// array('name'=>'入库件数','class' =>"flex-col text-right",'width'=>"80px"),
				// array('name'=>'入库重量','class' =>"flex-col text-right",'width'=>"120px"),
				array('name'=>'可用件数','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'可用重量','class' =>"flex-col text-right",'width'=>"110px"),
				// array('name'=>'剩余件数','class' =>"flex-col text-right",'width'=>"80px"),
				// array('name'=>'剩余重量','class' =>"flex-col text-right",'width'=>"120px"),
				// array('name'=>'锁定件数','class' =>"flex-col text-right",'width'=>"80px"),
				// array('name'=>'保留件数','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'是否船舱','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'预计到货日期','class' =>"flex-col",'width'=>"190px"),//
				// array('name'=>'成本单价','class' =>"flex-col text-right",'width'=>"100px"),
				// array('name'=>'采购发票成本','class' =>"flex-col text-right",'width'=>"120px"),
				// array('name'=>'是否托盘','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'公司','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'最后更新时间','class' =>"flex-col",'width'=>"160px"),
		);

		$tableData=array();
		$model = new MergeStorage();
		$criteria=New CDbCriteria();
		
		if(!empty($search)){
			if($search['warehouse_id']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse_id'],false);
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('t.title_id',$search['title_id'],false);
			}
			if($search['type']==1)
			{
				$criteria->compare('t.is_transit',0,false);
			}
			if($search['type']==2)
			{
				$criteria->compare('t.is_transit',1,false);
			}
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('t.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('t.product_id',$search['product'],false);
			}
			if($search['rank']!='0')
			{
				$criteria->compare('t.rank_id',$search['rank'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],false);
			}
			if($search['length']>=0)
			{
				$criteria->compare('t.length',$search['length'],false);
			}
			// if($search['lock']==1){
			// 	$criteria->addCondition("t.lock_amount>0");
			// }else if($search['lock']==2){
			// 	$criteria->addCondition("t.lock_amount=0");
			// }
			// if($search['retain']==1){
			// 	$criteria->addCondition("t.retain_amount>0");
			// }else if($search['retain']==2){
			// 	$criteria->addCondition("t.retain_amount=0");
			// }			
		}
		
		$criteria->addCondition("t.is_deleted=0");
		if(empty($search)||$search['order_by'])
		{
			$criteria->order = "t.last_update DESC,t.brand_id,t.product_id,t.texture_id,t.rank_id,t.length,t.pre_input_date ASC";
		}else{
			$criteria->order = "t.brand_id,t.product_id,t.texture_id,t.rank_id,t.length,t.pre_input_date ASC";
		}		
		$criteria->addCondition('(t.left_amount-t.retain_amount-t.lock_amount) >0');
		// $c = clone $criteria;
		
		// $c->select = "sum(input_amount) as i_amount,sum(input_weight) as i_weight,sum(lock_amount) as l_amount,sum(retain_amount) as r_amount,"
		// 		."sum(left_amount) as ll_amount,sum(left_weight) as ll_weight,"
		// 		."sum(left_amount-lock_amount-retain_amount) as can_amount,sum((left_amount-lock_amount-retain_amount)*input_weight/input_amount) as can_weight";
		// $alldetail = MergeStorage::model()->find($c);
		// $totaldata = array();
		// $totaldata["i_amount"] = $alldetail->i_amount;
		// $totaldata["i_weight"] = $alldetail->i_weight;
		// $totaldata["l_amount"] = $alldetail->l_amount;
		// $totaldata["r_amount"] = $alldetail->r_amount;
		// $totaldata["ll_amount"] = $alldetail->ll_amount;
		// $totaldata["ll_weight"] = $alldetail->ll_weight;
		// $totaldata["can_amount"] = $alldetail->can_amount;
		// $totaldata["can_weight"] = 0;#$alldetail->can_weight;
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['stoage_list']) ? intval($_COOKIE['stoage_list']) : Yii::app()->params['pageCount'];
		//$pages->pageSize = 2;
		$pages->applyLimit($criteria);
	
		$details=$model->findAll($criteria);
		$_inputTime = array(6=>"00:00~06:00",12=>"06:00~12:00",18=>"12:00~18:00",24=>"18:00~24:00");
		$num = 0;
		if($details)
		{
			$da[] = array();
			foreach ($details as $li){
				//如果是代销，获取每日代销价格
				if($search['sales']){
					$li->cost_price = 0; //暂定为0
				}
				$product_std = $li->product_id;
				$rand_std = $li->rank_id;
				$texture_std = $li->texture_id;
				$brand_std = $li->brand_id;
				$length = $li->length;
// 				$sell = $li->left_amount-$li->retain_amount-$li->lock_amount;
// 				$type['product'] = $product_std;
// 				$type['rank'] = $rand_std;
// 				$type['brand'] = $brand_std;
// 				$type['texture'] = $texture_std;
// 				$type['length'] = $length;
// 				$weight = DictGoods::getUnitWeight($type);
// 				if($weight == 0){
// 					$weight = $li->input_weight/$li->input_amount;
// 				}
				$num++;
				if($li->is_transit > 0)
				{
					$strtime = '<span class="input_date" num=1>'.date("Y-m-d",$li->pre_input_date);
					if($li->pre_input_time > 0)
					{
						$strtime.= "&nbsp;&nbsp;".$_inputTime[$li->pre_input_time].'</span>';
					}
				}else{
					$strtime = '<span class="input_date" num=0>在库</span>';
				}
// 				if($sell > 100) {$sell = 100;}
// 				$can_weight = $sell * $weight;
				// $details_url = Yii::app()->createUrl('FrmSales/locklist',array('storage_id'=>$li->id,"fpage"=>$_REQUEST['page']));
				// $str = '<div class="cz_list_btn">'
				// 		.'<a href="'.$details_url.'" class="update_b"><span><img src="/images/detail.png"></span></a>'
				// 		."</div>";
				// if($li->lock_amount>0){
				// 	$lock = '<a href="'.$details_url.'" class="update_b">'.$li->lock_amount.'</a>';
				// }else{
				// 	$lock = $li->lock_amount;
				// }
				$weight = DictGoods::getWeightByStorage($li);
				$can_amount = $li->left_amount-$li->retain_amount-$li->lock_amount;
				if($can_amount>100)
				{
					// $totaldata['can_amount']=$totaldata['can_amount']-$can_amount+100;
					$can_amount=100;				
				}
				// $totaldata['can_weight']+=$can_amount*$weight;
				$da["data"] = array(
						'<span class="warehouse_name">'.$li->warehouse->name.'</span>',//仓库
						'<span class="brand">'.DictGoodsProperty::getProName($brand_std).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($product_std).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($texture_std)).'</span>',
						'<span class="rand">'.DictGoodsProperty::getProName($rand_std).'</span>',
						'<span class="length">'.$length.'</span>',
						$weight,
						// $li->input_amount,
						// number_format($li->input_weight,3),
						$can_amount,
						$can_amount*$weight,
						// $li->left_amount,
						// number_format($li->left_weight,3),
						// $lock,
						// $li->retain_amount,
						$li->is_transit>0?"是":"",
						$strtime,
						// $li->cost_price,
						// $li->invoice_price>0?$li->invoice_price:"",
						// $li->redeem_company_id>0?"是":"",
						'<span class="title_name">'.$li->title->short_name.'</span>',
						// '<span title="'.$li->redeemCompany->name.'">'.$li->redeemCompany->short_name.'</span>',
						$li->last_update?date('Y-m-d H:i:s',$li->last_update):'',
				);
				$da["group"] = $num;
				array_push($tableData,$da);
			}
		}
		return array($tableHeader,$tableData,$pages);
	}



	/**
	 * 代销库存列表数据获取
	 */
	public static function getDXList(){
		$model = new Storage();
		$cri = new CDbCriteria();
		$cri->select = "t.*,d.product_id as product_id,
						d.rank_id as rank_id,
						d.texture_id as texture_id,
						d.brand_id as brand_id,
						d.length as length,
						f.input_type as input_type";
		$cri->join = "left join input_detail_dx d on t.input_detail_id = d.id 
					left join frm_input f on t.frm_input_id = f.id";
		
		$cri->addCondition("t.is_deleted = '0' ");
		$cri->order = "t.input_date desc";
		$search = new Storage();
		$search->card_status = "normal";
		if($_REQUEST['Storage']){
			$search->product_id = $_REQUEST['Storage']['product_id'];
			$search->rank_id = $_REQUEST['Storage']['rank_id'];
			$search->texture_id = $_REQUEST['Storage']['texture_id'];
			$search->brand_id = $_REQUEST['Storage']['brand_id'];
			$search->warehouse_id = $_REQUEST['Storage']['warehouse_id'];
			$search->card_status = $_REQUEST['Storage']['card_status'];
			$search->title_id = $_REQUEST['Storage']['title_id'];
			$search->input_type = $_REQUEST['Storage']['input_type'];
			$search->length = $_REQUEST['Storage']['length'];
			
			if($search->product_id){
				$cri->addCondition("d.product_id = ".intval($search->product_id));
				$search->product_name = DictGoodsProperty::getProName($search->product_id);
			}
			if($search->texture_id){
				$cri->addCondition("d.texture_id = ".intval($search->texture_id));
				$search->texture_name = DictGoodsProperty::getProName($search->texture_id);
			}
			if($search->rank_id){
				$cri->addCondition("d.rank_id = ".intval($search->rank_id));
				$search->rank_name = DictGoodsProperty::getProName($search->rank_id);
			}
			if($search->brand_id){
				$cri->addCondition("d.brand_id = ".intval($search->brand_id));
				$search->brand_name = DictGoodsProperty::getProName($search->brand_id);
			}
			if($search->length >=0){
				$cri->addCondition("d.length = ".intval($search->length));
			}
			if($search->warehouse_id){
				$cri->addCondition("t.warehouse_id = ".intval($search->warehouse_id));
			}
			if($search->title_id){
				$cri->addCondition("t.title_id = ".intval($search->title_id));
			}
			if($search->input_type){
				$cri->params[':input_type'] = $search->input_type;
				$cri->addCondition("f.input_type = :input_type");
			}
		}
		$cri->addCondition("t.card_status = '{$search->card_status}'");
		$cri->addCondition("t.is_dx=1");
		$c = clone $cri;
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['kc']?intval($_COOKIE['kc']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		//$c->select = "sum(t.left_amount) as total_amount,sum(t.left_weight) as total_weight,count(*) as total_num";
		$c->select = "sum(t.input_amount) as i_amount,sum(t.input_weight) as i_weight,sum(t.lock_amount) as l_amount,sum(t.retain_amount) as r_amount,"
				."sum(t.left_amount) as ll_amount,sum(t.left_weight) as ll_weight,sum(t.lock_weight) as l_weight,"
				."sum(t.left_amount-t.lock_amount-t.retain_amount) as can_amount,sum(t.left_weight-t.lock_weight-t.retain_amount*t.input_weight/t.input_amount) as can_weight";
		$all=Storage::model()->find($c);
		$totaldata = array();
		$totaldata["i_amount"] = $all->i_amount;
		$totaldata["i_weight"] = $all->i_weight;
		$totaldata["l_amount"] = $all->l_amount;
		$totaldata["l_weight"] = $all->l_weight;
		$totaldata["r_amount"] = $all->r_amount;
		$totaldata["ll_amount"] = $all->ll_amount;
		$totaldata["ll_weight"] = $all->ll_weight;
		$totaldata["can_amount"] = $all->can_amount;
		$totaldata["can_weight"] = $all->can_weight;
		return array($model,$pages,$items,$search,$totaldata);
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
	
	public static function getTotalList($search){
		$tableData=array();
		$tableHeader=array(
				array('name'=>'仓库','class' =>"sort-disabled text-left",'width'=>"100px"),//修
				array('name'=>'品名','class' =>"sort-disabled text-left",'width'=>"100px"),//
				array('name'=>'甲乙单','class' =>"sort-disabled text-left",'width'=>"60px"),//
				array('name'=>'类型','class' =>"sort-disabled text-left",'width'=>"80px"),//
				array('name'=>'期初库存','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),//
				array('name'=>'出库重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'盘盈盘亏','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'转库重量','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'期末库存','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
		);
		$criteria=new CDbCriteria();
		if($search['warehouse_id']!=''){$criteria->compare('t.warehouse_id',$search['warehouse_id']);}
		if($search['product_id']){$criteria->compare('t.product_id',$search['product_id']);}
		if($search['is_yidan']!='-1'){$criteria->compare('t.is_yidan',$search['is_yidan']);}
		if($search['type']){$criteria->compare('t.type',$search['type']);}		
		if($search['start_time']!='')	{$start_time=$search['start_time'];	}
		if($search['end_time']!=''){	
			$end_time=$search['end_time'];
		}else{
			$end_time=date('Y-m-d',time());			
		}
		$criteria->compare('date', $end_time);
		
		//找到搜索条件末的期末库存切片
		$details_end=StorageDateLog::model()->findAll($criteria);
		if($details_end)
		{
			$_type=array('purchase'=>'采购入库','thrk'=>'销售退货');
			$i=1;
			$t_start_weight=0;
			$t_input_weight=0;
			$t_output_weight=0;
			$t_pypk_weight=0;
			$t_end_weight=0;
			foreach ($details_end as $each)
			{
				if($start_time){
					$detail_start=StorageDateLog::model()->findByAttributes(array('warehouse_id'=>$each->warehouse_id,'product_id'=>$each->product_id,'date'=>$start_time));
				}else{
					$detail_start=new StorageDateLog();
					$detail_start->unsetAttributes();
				}
				$da['data']=array(
						$each->warehouse->name,
						DictGoodsProperty::getProName($each->product_id),
						$each->is_yidan?'乙单':'甲单',
						$_type[$each->type],
						number_format($detail_start->weight,3),
						number_format($each->total_input_weight-$detail_start->total_input_weight,3),
						number_format($each->total_output_weight-$detail_start->total_output_weight,3),
						number_format($each->total_pypk_weight-$detail_start->total_pypk_weight,3),
// 						number_format($each->total_transfer_weight-$detail_start->total_transfer_weight,3),
						number_format($each->weight,3),
				);
				$da['group']=$i;
				array_push($tableData,$da);
				$i++;	
				$t_start_weight+=$detail_start->weight;
				$t_input_weight+=($each->total_input_weight-$detail_start->total_input_weight);
				$t_output_weight+=($each->total_output_weight-$detail_start->total_output_weight);
				$t_pypk_weight+=($each->total_pypk_weight-$detail_start->total_pypk_weight);
				$t_end_weight+=$each->weight;

				
			}
			//合计
			$temp['data'] = array('合计','','','', number_format($t_start_weight,3),number_format($t_input_weight,3),
						number_format($t_output_weight,3),number_format($t_pypk_weight,3),number_format($t_end_weight,3));
			$temp['group']=0;
			array_push($tableData, $temp);

		}
		return array($tableHeader,$tableData);		
				
// 		$st_date = $_REQUEST['start_time']?$_REQUEST['start_time']:(date("Y-m-d",time()-24*3600*7));
// 		$et_date = $_REQUEST['end_time']?$_REQUEST['end_time']:date("Y-m-d",time()-24*3600);
// 		$st = strtotime($st_date." 00:00:00");
// 		$et = strtotime($et_date." 23:59:59");
// 		$today = strtotime(date("Y-m-d")." 00:00:00");
// 		if($et>=$today){
// 			$msg="结束日期最大为昨日！";
// 			$st_date = date("Y-m-d",time()-24*3600*7);
// 			$et_date = date("Y-m-d",time()-24*3600);
// 		}elseif($st>=$et){
// 			$msg="结束日期必须小于起始日期";
// 			$st_date = date("Y-m-d",time()-24*3600*7);
// 			$et_date = date("Y-m-d",time()-24*3600);
// 		}
// 		$array_warehouse = array();
// 		$model = new StorageDateLog();
// 		$search = new Storage();
// 		$cri_st = new CDbCriteria();
// 		$cri_et = new CDbCriteria();
// 		if($_REQUEST['Storage']['product_id']){
// 			$cri_st->params[':pid'] = $_REQUEST['Storage']['product_id'];
// 			$cri_st->addCondition("product_id = :pid");
// 			$cri_et->params[':pid'] = $_REQUEST['Storage']['product_id'];
// 			$cri_et->addCondition("product_id = :pid");
// 			$search->product_id = $_REQUEST['Storage']['product_id'];
// 		}
// 		if($_REQUEST['Storage']['warehouse_id']){
// 			$cri_st->params[':wid'] = $_REQUEST['Storage']['warehouse_id'];
// 			$cri_st->addCondition("warehouse_id = :wid");
// 			$cri_et->params[':wid'] = $_REQUEST['Storage']['warehouse_id'];
// 			$cri_et->addCondition("warehouse_id = :wid");
// 			$search->warehouse_id = $_REQUEST['Storage']['warehouse_id'];
// 		}
// 		$cri_st->order = "warehouse_id,product_id";
// 		$cri_st->params[':st'] =$st_date;
// 		$cri_st->addCondition("date = :st");
// 		$items_st = $model->findAll($cri_st);
		
// 		$cri_et->order = "warehouse_id,product_id";
// 		$cri_et->params[':et'] =$et_date;
// 		$cri_et->addCondition("date = :et");
// 		$items_et = $model->findAll($cri_et);
		
		
// 		$items = array();
// 		for($i=0;$i<count($items_et);$i++){
// 			$item = new StorageDateLog();
// 			if(!$items_st[$i]){
// 				$items_st[$i] = new StorageDateLog();
// 			}
// 			$item->warehouse_id = $items_et[$i]->warehouse_id;
// 			$item->product_id = $items_et[$i]->product_id;
			
// 			$item->start = $items_st[$i]->weight?number_format($items_st[$i]->weight,3):"0.000";
// 			$item->end = $items_et[$i]->weight?number_format($items_et[$i]->weight,3):"0.000";
// 			$item->input = number_format(floatval($items_et[$i]->total_input_weight)-floatval($items_st[$i]->total_input_weight),3);
// 			$item->output = number_format(floatval($items_et[$i]->total_output_weight)-floatval($items_st[$i]->total_output_weight),3);
// 			$item->pypk = number_format(floatval($items_et[$i]->total_pypk_weight)-floatval($items_st[$i]->total_pypk_weight),3);
// 			$item->transfer = number_format(floatval($items_et[$i]->total_transfer_weight)-floatval($items_st[$i]->total_transfer_weight),3);
			
			
// 			$items[] = $item;
// 		}
		
// 		return array($model,$search,$items,$msg,$st_date,$et_date);
	}
	
	
	/*
	 * 设置盘盈盘亏 
	*/
	public static function setPypk($post){
		$id = $post['id'];
		//$amount = $post['amount'];
		$weight = $post['weight'];
		$comment = $post['comment'];
		$data['common']['form_type']='PYPK';
		$data['common']=(Object)$data['common'];
		$data['main']['storage_id'] = $id;
		//$data['main']['amount'] = $amount;
		$data['main']['weight'] = $weight;
		$data['main']['comment'] = $comment;
		if($amount == 0){
			if($weight > 0){
				$data['main']['type'] = 1;
			}else{
				$data['main']['type'] = 0;
			}
		}else if($amount > 0){
			$data['main']['type'] = 1;
		}else{
			$data['main']['type'] = 0;
		}
		$data['main']=(Object)$data['main'];
		
		$allform=new Pypk($id);
		$result = $allform->createForm($data);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	 * 设置清卡
	 */
	public static function setQingka($id)
	{
		$storage = Storage::model()->findByPk($id);
		$oldJson=$storage->datatoJson();
		if($storage){
			$weight = $storage->left_weight;
			$storage->left_amount = 0;
			$storage->left_weight = 0;
			$storage->card_status = 'clear';
			if($storage->update()){
				$mainJson = $storage->datatoJson();
				$dataArray = array("tableName"=>"storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}
			if($weight > 0){
				$model = new MergeStorage();
				$criteria=New CDbCriteria();
				$criteria->addCondition('product_id ='.$storage->inputDetail->product_id);
				$criteria->addCondition('brand_id ='.$storage->inputDetail->brand_id);
				$criteria->addCondition('texture_id ='.$storage->inputDetail->texture_id);
				$criteria->addCondition('rank_id ='.$storage->inputDetail->rank_id);
				$criteria->addCondition('length ='.$storage->inputDetail->length);
				$criteria->addCondition('title_id ='.$storage->title_id);
				$criteria->addCondition('warehouse_id='.$storage->warehouse_id);
				$criteria->addCondition('is_transit = 0');
				$criteria->addCondition('is_deleted = 0');
				$merge = $model->find($criteria);
				if($merge){
					$oldJson=$merge->datatoJson();
					$merge->left_weight -= $weight;
					if($merge->update()){
						$mainJson = $merge->datatoJson();
						$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$baseform = new BaseForm();
						$baseform->dataLog($dataArray);
						return true;
					}
				}
			}
		}
		return true;
	}
	
	//设置库存锁定重量脚本
	public static function SetLockWeight(){
		$str="";
		$merge = MergeStorage::model()->findAll("is_deleted=0 and left_amount>0 and lock_amount>0");
		foreach ($merge as $me){
			$details = new SalesDetail();
			$criteria=New CDbCriteria();
			$criteria->with = array("FrmSales","FrmSales.baseform");
			$criteria->addCondition("t.card_id=".$me->id);
			$criteria->addCondition(" FrmSales.sales_type='normal'");
			$criteria->addCondition(" FrmSales.confirm_status=0");
			$criteria->addCondition(" baseform.form_status='submited' or baseform.form_status='approve'");
			$details = $details->findAll($criteria);
			if($details){
				$t_amount = 0;
				$t_weight = 0;
				foreach ($details as $dt){
					$t_amount += $dt->amount - $dt->output_amount;
					$t_weight += $dt->weight - $dt->output_amount;
				}
				if($t_amount == $me->lock_amount){
					$me->lock_weight = $t_weight;
					$me->update();
				}else{
					$str.="聚合库存id".$me->id."：锁定件数{$me->lock_amount}->{$t_amount},锁定库存{$me->lock_weight}->{$t_weight}<br/>";
					$me->lock_amount = $t_amount;
					$me->lock_weight = $t_weight;
					$me->update();
				}
			}
		}
		$storage = Storage::model()->findAll("is_deleted=0 and left_amount>0 and lock_amount>0 and is_dx=1");
		foreach ($storage as $st){
			$details = new SalesDetail();
			$criteria=New CDbCriteria();
			$criteria->with = array("FrmSales","FrmSales.baseform");
			$criteria->addCondition("t.card_id=".$st->id);
			$criteria->addCondition("FrmSales.sales_type='dxxs'");
			$criteria->addCondition("FrmSales.confirm_status=0");
			$criteria->addCondition("baseform.form_status='submited' or baseform.form_status='approve'");
			$details = $details->findAll($criteria);
			if($details){
				$t_amount = 0;
				$t_weight = 0;
				foreach ($details as $dt){
					$t_amount += $dt->amount - $dt->output_amount;
					$t_weight += $dt->weight - $dt->output_amount;
				}
				if($t_amount == $st->lock_amount){
					$st->lock_weight = $t_weight;
					$st->update();
				}else{
					$str.="代销库存id".$st->id."：锁定件数{$st->lock_amount}->{$t_amount},锁定库存{$st->lock_weight}->{$t_weight}<br/>";
					$st->lock_amount = $t_amount;
					$st->lock_weight = $t_weight;
					$st->update();
				}
			}
			
		}
		return $str;
	}
	
	//获取错误的库存锁定重量脚本
	public static function GetWrongLockWeight(){
		$str="";
		$merge = MergeStorage::model()->findAll("is_deleted=0 and left_amount>0 and lock_amount>0");
		foreach ($merge as $me){
			$details = new SalesDetail();
			$criteria=New CDbCriteria();
			$criteria->with = array("FrmSales","FrmSales.baseform");
			$criteria->addCondition("t.card_id=".$me->id);
			$criteria->addCondition(" FrmSales.sales_type='normal'");
			$criteria->addCondition(" FrmSales.confirm_status=0");
			$criteria->addCondition(" baseform.form_status='submited' or baseform.form_status='approve'");
			$details = $details->findAll($criteria);
			if($details){
				$t_amount = 0;
				$t_weight = 0;
				foreach ($details as $dt){
					$t_amount += $dt->amount - $dt->output_amount;
					$t_weight += $dt->weight - $dt->output_amount;
				}
				if($t_amount != $me->lock_amount){
					$str.="聚合库存id".$me->id."：锁定件数{$me->lock_amount}->{$t_amount},锁定库存{$me->lock_weight}->{$t_weight}\n";
				}
			}
		}
		$storage = Storage::model()->findAll("is_deleted=0 and left_amount>0 and lock_amount>0 and is_dx=1");
		foreach ($storage as $st){
			$details = new SalesDetail();
			$criteria=New CDbCriteria();
			$criteria->with = array("FrmSales","FrmSales.baseform");
			$criteria->addCondition("t.card_id=".$st->id);
			$criteria->addCondition("FrmSales.sales_type='dxxs'");
			$criteria->addCondition("FrmSales.confirm_status=0");
			$criteria->addCondition("baseform.form_status='submited' or baseform.form_status='approve'");
			$details = $details->findAll($criteria);
			if($details){
				$t_amount = 0;
				$t_weight = 0;
				foreach ($details as $dt){
					$t_amount += $dt->amount - $dt->output_amount;
					$t_weight += $dt->weight - $dt->output_amount;
				}
				if($t_amount != $st->lock_amount){
					$str.="代销库存id".$st->id."：锁定件数{$st->lock_amount}->{$t_amount},锁定库存{$st->lock_weight}->{$t_weight}\n";
				}
			}
				
		}
		return $str;
	}
}
