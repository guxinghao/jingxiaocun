<?php

/**
 * This is the biz model class for table "merge_storage".
 *
 */
class MergeStorage extends MergeStorageData
{	
	public $i_amount;
	public $i_weight;
	public $l_amount;
	public $l_weight;
	public $r_amount;
	public $r_weight;
	public $ll_amount;
	public $ll_weight;
	public $can_amount;
	public $can_weight;
	// 钢厂名
	public $brand_name;
	// 品名
	public $product_name;
	// 仓库名
	public $warehouse_name;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'redeemCompany' => array(self::BELONGS_TO, 'DictCompany', 'redeem_company_id'),
			'storage' => array(self::BELONGS_TO, 'Storage', 'storage_id'),
			'warehouse'=>array(self::BELONGS_TO, 'Warehouse','warehouse_id'),
			'dp_brand'=>array(self::BELONGS_TO,'DictGoodsProperty','brand_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'status' => 'Status',
			'cost_price' => 'Cost Price',
			'title_id' => 'Title',
			'redeem_company_id' => 'Redeem Company',
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'left_amount' => 'Left Amount',
			'left_weight' => 'Left Weight',
			'retain_amount' => 'Retain Amount',
			'lock_amount' => 'Lock Amount',
			'is_transit' => 'Is Transit',
			'pre_input_date' => 'Pre Input Date',
			'storage_id' => 'Storage',
			'warehouse_id' => 'Warehouse',
			'invoice_price' => 'Invoice Price',
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
		$criteria->compare('length',$this->length);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('redeem_company_id',$this->redeem_company_id);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('left_amount',$this->left_amount);
		$criteria->compare('left_weight',$this->left_weight,true);
		$criteria->compare('retain_amount',$this->retain_amount);
		$criteria->compare('lock_amount',$this->lock_amount);
		$criteria->compare('is_transit',$this->is_transit);
		$criteria->compare('pre_input_date',$this->pre_input_date);
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('invoice_price',$this->invoice_price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MergeStorage the static model class
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
			if($search['rand']!='0')
			{
				$criteria->compare('t.rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],false);
			}
			if($search['length']>=0)
			{
				$criteria->compare('t.length',$search['length'],false);
			}
		}
		$criteria->addCondition("(t.left_amount-t.retain_amount-t.lock_amount)>0");
		$criteria->addCondition("t.is_deleted=0");
		$criteria->order = "warehouse_id,brand_id,product_id,texture_id,rank_id,length,pre_input_date";
		$totaldata = array();
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['mergestorage_list']) ? intval($_COOKIE['mergestorage_list']) : Yii::app()->params['pageCount'];
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
				$sell = $li->left_amount-$li->retain_amount-$li->lock_amount;
				$str = '<input type="checkbox" value="'.$li->id.'" class="checkit" cost="'.$li->cost_price.'">'
						.'<input type="hidden" class="product_std" value="'.$product_std.'">'
						.'<input type="hidden" class="rand_std" value="'.$rand_std.'">'
						.'<input type="hidden" class="texture_std" value="'.$texture_std.'">'
						//.'<input type="hidden" class="length_std" value="'.$li->inputDetail->length.'">'
						.'<input type="hidden" class="brand_std" value="'.$brand_std.'">'
						.'<input type="hidden" class="title_id" value="'.$li->title_id.'">'
						.'<input type="hidden" class="warehouse_id" value="'.$li->warehouse->id.'">'
						.'<input type="hidden" class="can_surplus" value="'.$sell.'">';
				$type['product'] = $product_std;
				$type['rank'] = $rand_std;
				$type['brand'] = $brand_std;
				$type['texture'] = $texture_std;
				$type['length'] = $length;
				$weight = DictGoods::getUnitWeight($type);
				if($weight == 0){
					$weight = $li->input_weight/$li->input_amount;
				}
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
				if($sell > 100) {$sell = 100;}
				$can_weight = $sell * $weight;
				$da["data"] = array(
						$num,
						$str,
						'<span class="brand">'.DictGoodsProperty::getProName($brand_std).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($product_std).'</span>',
						'<span class="length">'.$length.'</span>',
						'<span class="rand">'.DictGoodsProperty::getProName($rand_std).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($texture_std)).'</span>',
						$strtime,
						'<span class="warehouse_name">'.$li->warehouse->name.'</span>',//仓库
						'<span class="surplus">'.$sell.'</span>',
						'<span class="canweight">'.number_format($can_weight,3).'</span>',
						'<span class="title_name">'.$li->title->short_name.'</span>',
				);
				$da["group"] = $num;
				array_push($tableData,$da);
			}
		}
		
		return array($tableData,$pages,$totaldata);
	}
	
	/*
	 * 获取销售单库存列表
	 */
	public static function getLockList($search)
	{
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
			if($search['lock']==1){
				$criteria->addCondition("t.lock_amount>0");
			}else if($search['lock']==2){
				$criteria->addCondition("t.lock_amount=0");
			}
			if($search['retain']==1){
				$criteria->addCondition("t.retain_amount>0");
			}else if($search['retain']==2){
				$criteria->addCondition("t.retain_amount=0");
			}
			if($search['left'])
			{
				if($search['left']==2)
				{
					$criteria->addCondition('t.left_amount=0');
				}else{
					$criteria->addCondition('t.left_amount!=0');
				}				
			}
		}
		
		$criteria->addCondition("t.is_deleted=0");
		$criteria->order = "t.product_id,t.texture_id,t.rank_id,t.pre_input_date ASC";
		$c = clone $criteria;
		
		$c->select = "sum(input_amount) as i_amount,sum(input_weight) as i_weight,sum(lock_amount) as l_amount,sum(retain_amount) as r_amount,sum(retain_weight) as r_weight,"
				."sum(left_amount) as ll_amount,sum(left_weight) as ll_weight,sum(lock_weight) as l_weight,"
				."sum(left_amount-lock_amount-retain_amount) as can_amount,sum(left_weight-lock_weight-retain_weight) as can_weight";
		$alldetail = MergeStorage::model()->find($c);
		$totaldata = array();
		$totaldata["i_amount"] = $alldetail->i_amount;
		$totaldata["i_weight"] = $alldetail->i_weight;
		$totaldata["l_amount"] = $alldetail->l_amount;
		$totaldata["l_weight"] = $alldetail->l_weight;
		$totaldata["r_amount"] = $alldetail->r_amount;
		$totaldata["r_weight"] = $alldetail->r_weight;
		$totaldata["ll_amount"] = $alldetail->ll_amount;
		$totaldata["ll_weight"] = $alldetail->ll_weight;
		$totaldata["can_amount"] = $alldetail->can_amount;
		$totaldata["can_weight"] = $alldetail->can_weight;
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['stoagelock_list']) ? intval($_COOKIE['stoagelock_list']) : Yii::app()->params['pageCount'];
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
					$strtime = '<span class="input_date" num=0 t_id="'.$li->id.'">在库</span>';
				}
// 				if($sell > 100) {$sell = 100;}
// 				$can_weight = $sell * $weight;
				$details_url = Yii::app()->createUrl('FrmSales/locklist',array('storage_id'=>$li->id,"fpage"=>$_REQUEST['page']));
				$str = '<div class="cz_list_btn">'
						.'<a href="'.$details_url.'" class="update_b"><span><img src="/images/detail.png"></span></a>'
						."</div>";
				if($li->lock_amount>0){
					$lock = '<a href="'.$details_url.'" class="update_b">'.$li->lock_amount.'</a>';
				}else{
					$lock = $li->lock_amount;
				}
				$weight = DictGoods::getWeightByStorage($li);
				$can_amount = $li->left_amount-$li->retain_amount-$li->lock_amount;
				$da["data"] = array(
						'<span class="warehouse_name">'.$li->warehouse->name.'</span>',//仓库
						'<span class="brand">'.DictGoodsProperty::getProName($brand_std).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($product_std).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($texture_std)).'</span>',
						'<span class="rand">'.DictGoodsProperty::getProName($rand_std).'</span>',
						'<span class="length">'.$length.'</span>',
						$weight,						
						$can_amount,
						number_format($li->left_weight-$li->lock_weight-$li->retain_weight,3),
						$li->left_amount,
						number_format($li->left_weight,3),
						$lock,
						number_format($li->lock_weight,3),
						$li->retain_amount,
						number_format($li->retain_weight,3),
						$li->input_amount,
						number_format($li->input_weight,3),
						$li->is_transit>0?"是":"",
						$strtime,
						$li->cost_price,
						$li->invoice_price>0?$li->invoice_price:"",
						$li->redeem_company_id>0?"是":"",
						'<span class="title_name">'.$li->title->short_name.'</span>',
						'<span title="'.$li->redeemCompany->name.'">'.$li->redeemCompany->short_name.'</span>',
				);
				$da["group"] = $num;
				array_push($tableData,$da);
			}
		}
	
		return array($tableData,$pages,$totaldata);
	}

	/**
	 * 查询所有
	 */
	public static function getAllList($search) 
	{
		$model = new MergeStorage();
		$criteria = New CDbCriteria();
		if (!empty($search)) {
			if ($search['warehouse_id'] != '0') 
				$criteria->compare('t.warehouse_id', $search['warehouse_id'], false);
			
			if ($search['title_id'] != '0') 
				$criteria->compare('t.title_id', $search['title_id'], false);

			if($search['type'] == 1) 
				$criteria->compare('t.is_transit', 0, false);
			
			if($search['type'] == 2) 
				$criteria->compare('t.is_transit', 1, false);
			
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('t.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('t.product_id',$search['product'],false);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('t.rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],false);
			}
			if($search['lock']==1){
				$criteria->addCondition("t.lock_amount>0");
			}else if($search['lock']==2){
				$criteria->addCondition("t.lock_amount=0");
			}
			if($search['retain']==1){
				$criteria->addCondition("t.retain_amount>0");
			}else if($search['retain']==2){
				$criteria->addCondition("t.retain_amount=0");
			}
		}
		$criteria->addCondition("t.is_deleted = 0");
		$criteria->order = "pre_input_date ASC,t.product_id,t.texture_id,t.rank_id";
		
		//合计
		$c = clone $criteria;
		$c->select = "sum(input_amount) as i_amount,sum(input_weight) as i_weight,sum(lock_amount) as l_amount,sum(lock_weight) as l_weight,sum(retain_amount) as r_amount,sum(left_amount) as ll_amount,sum(left_weight) as ll_weight";
		$alldetail = MergeStorage::model()->find($c);
		$totaldata = array();
		$totaldata[7] = numChange(number_format($alldetail->i_amount));
		$totaldata[8] = numChange(number_format($alldetail->i_weight, 3));
		$totaldata[13] = numChange(number_format($alldetail->l_amount));
		$totaldata[14] = numChange(number_format($alldetail->l_weight,3));
		$totaldata[15] = numChange(number_format($alldetail->r_amount));
		$totaldata[11] = numChange(number_format($alldetail->ll_amount));
		$totaldata[12] = numChange(number_format($alldetail->ll_weight, 3));
		
		// $pages = new CPagination();
		// $pages->itemCount = $model->count($criteria);
		// $pages->pageSize = intval($_COOKIE['stoagelock_list']) ? intval($_COOKIE['stoagelock_list']) : Yii::app()->params['pageCount'];
		// //$pages->pageSize = 2;
		// $pages->applyLimit($criteria);
	
		$details = $model->findAll($criteria);
		$content = array();
		if (!$details) return $content;
		
		foreach ($details as $li) {
			//如果是代销，获取每日代销价格
			if($search['sales']) $li->cost_price = 0; //暂定为0
			$product_std = $li->product_id;
			$rand_std = $li->rank_id;
			$texture_std = $li->texture_id;
			$brand_std = $li->brand_id;
			$length = $li->length;
			
			// $sell = $li->left_amount-$li->retain_amount-$li->lock_amount;
			// $type['product'] = $product_std;
			// $type['rank'] = $rand_std;
			// $type['brand'] = $brand_std;
			// $type['texture'] = $texture_std;
			// $type['length'] = $length;
			// $weight = DictGoods::getUnitWeight($type);
			// if($weight == 0) $weight = $li->input_weight/$li->input_amount;

			if ($li->is_transit > 0) 
				$strtime = date("Y-m-d", $li->pre_input_date).($li->pre_input_time > 0 ? " ".$_inputTime[$li->pre_input_time] : ""); 
			else 
				$strtime = "在库";

// 			if($sell > 100) $sell = 100;
// 			$can_weight = $sell * $weight;
			
			// $details_url = Yii::app()->createUrl('FrmSales/locklist',array('storage_id'=>$li->id,"fpage"=>$_REQUEST['page']));
			// $str = '<div class="cz_list_btn">'
			// 		.'<a href="'.$details_url.'" class="update_b"><span><img src="/images/detail.png"></span></a>'
			// 		."</div>";
			
			$lock = $li->lock_amount;
			$weight = DictGoods::getWeightByStorage($li);
			$can_amount = $li->left_amount - $li->retain_amount -$li->lock_amount ;
			$can_weight = $li->left_weight - $li->retain_amount*$weight -$li->lock_weight ;
			$temp = array(
				$li->warehouse->name, 
				DictGoodsProperty::getProName($brand_std), 
				DictGoodsProperty::getProName($product_std),
				DictGoodsProperty::getProName($texture_std), 
				DictGoodsProperty::getProName($rand_std), 
				numChange(number_format($length)), //5
				numChange(number_format($weight, 3)), //6
				numChange(number_format($li->input_amount)), //7
				numChange(number_format($li->input_weight, 3)), //8
				numChange(number_format($can_amount)), //9
				numChange(number_format($can_weight, 3)), //10
				numChange(number_format($li->left_amount)), //11
				numChange(number_format($li->left_weight, 3)), //12
				numChange(number_format($lock)), //13
				$li->lock_weight,
				numChange(number_format($li->retain_amount)), //14
				$li->is_transit > 0 ? "是" : "",
				$strtime,
				numChange(number_format($li->cost_price, 2)),
				$li->invoice_price > 0 ? numChange(number_format($li->invoice_price, 2)) : "",
				$li->redeem_company_id > 0 ? "是" : "", 
				$li->title->short_name, 
				$li->redeemCompany->short_name,
			);
			array_push($content, $temp);
		}
		array_push($content, $totaldata);
		return $content;
	}
	
	/*
	 * 获取首页以钢厂分类库存信息
	 */
	public function getBrandList(){
		$model = MergeStorage::model();
		$criteria = New CDbCriteria();
		$criteria->select = 't.brand_id,t.product_id,m.name as brand_name,n.name as product_name,sum(t.left_weight) as left_weight,sum(t.retain_weight) as retain_weight,sum(t.lock_weight) as lock_weight';
		$criteria->join = 'left join dict_goods_property as m on t.brand_id=m.id left join dict_goods_property as n on t.product_id=n.id';
		$criteria->addCondition('t.is_deleted=0');
		$criteria->group = 't.brand_id,t.product_id';
		$criteria->order = 't.brand_id,t.product_id asc';
		$result = $model->findAll($criteria);
		$data = array();
		$total = array();
		$brand_name = array();
		$product_name = array();
		if($result){
			foreach($result as $v){
				// 钢厂名
				if(!in_array($v['brand_name'], $brand_name))
					$brand_name[] = $v['brand_name'];
				// 品名
				if(!in_array($v['product_name'], $product_name))
					$product_name[] = $v['product_name'];
				// 合计数据
				$name = $v['brand_name'];
				$weight = (float)($v['left_weight']-$v['retain_weight']-$v['lock_weight']);
				$total[$name] += $weight;
			}
			// 数组反转
			$brand_name1 = array_flip($brand_name);
			$product_name1 = array_flip($product_name);
			// 赋值
			foreach($result as $v){
				$weight = (float)($v['left_weight']-$v['retain_weight']-$v['lock_weight']);
				$data[$product_name1[$v['product_name']]]['data'][1+$brand_name1[$v['brand_name']]] = $weight ? number_format($weight, 3) : 0;
			}
			// 填充0
			for($i=0; $i<count($product_name); $i++){
				// 第一列品名
				$data[$i]['data'][0] = $product_name[$i];
				$data[$i]['group'] = $i+1;
				for($j=1; $j<=count($brand_name); $j++){
					if(empty($data[$i]['data'][$j]))
						$data[$i]['data'][$j] = 0;
				}
			}
			// 排序
			ksort($data);
			for($i=0; $i<count($data); $i++){
				ksort($data[$i]['data']);
			}
		}
		return array('tableHeader'=>$brand_name,'tableData'=>$data,'totalData'=>$total);
	}
	
	/*
	 * 获取首页以仓库分类库存信息
	 */
	public function getWarehouseList(){
		$model = MergeStorage::model();
		$criteria = New CDbCriteria();
		$criteria->select = 't.product_id,t.warehouse_id,m.name as warehouse_name,n.name as product_name,sum(t.left_weight) as left_weight,sum(t.retain_weight) as retain_weight,sum(t.lock_weight) as lock_weight';
		$criteria->join = 'left join warehouse as m on t.warehouse_id=m.id left join dict_goods_property as n on t.product_id=n.id';
		$criteria->addCondition('t.is_deleted=0');
		$criteria->group = 't.warehouse_id,t.product_id';
		$criteria->order = 't.warehouse_id,t.product_id asc';
		$result = $model->findAll($criteria);
		$data = array();
		$total = array();
		$warehouse_name = array();
		$product_name = array();
		if($result){
			foreach($result as $v){
				// 仓库名
				if(!in_array($v['warehouse_name'], $warehouse_name))
					$warehouse_name[] = $v['warehouse_name'];
				// 品名
				if(!in_array($v['product_name'], $product_name))
					$product_name[] = $v['product_name'];
				// 合计数据
				$name = $v['warehouse_name'];
				$weight = (float)($v['left_weight']-$v['retain_weight']-$v['lock_weight']);
				$total[$name] += $weight;
			}
			// 数组反转
			$warehouse_name1 = array_flip($warehouse_name);
			$product_name1 = array_flip($product_name);
			// 赋值
			foreach($result as $v){
				$weight = (float)($v['left_weight']-$v['retain_weight']-$v['lock_weight']);
				$data[$product_name1[$v['product_name']]]['data'][1+$warehouse_name1[$v['warehouse_name']]] = $weight ? number_format($weight, 3) : 0;
			}
			// 填充0
			for($i=0; $i<count($product_name); $i++){
				$data[$i]['data'][0] = $product_name[$i];
				$data[$i]['group'] = $i+1;
				for($j=1; $j<=count($warehouse_name); $j++){
					if(empty($data[$i]['data'][$j]))
						$data[$i]['data'][$j] = 0;
				}
			}
			// 排序
			ksort($data);
			for($i=0; $i<count($data); $i++){
				ksort($data[$i]['data']);
			}
		}
		return array('tableHeader'=>$warehouse_name,'tableData'=>$data,'totalData'=>$total);
	}
}
