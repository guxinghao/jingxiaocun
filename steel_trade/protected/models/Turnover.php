<?php

/**
 * This is the biz model class for table "turnover".
 *
 */
class Turnover extends TurnoverData
{
	
	public $yue,$total_fee, $total_weight;
	public $start_time;
	public $end_time;
	public $balance; //余额
	public $initial_balance; //期初余额
	public $final_balance; //期末余额
	public $purchase_detail; //采购明细
	public $freight; //运费
	public $purchase_rebate; //采购折让
	public $purchase_return; //采购退货
	public $tray_purchase; //托盘采购
	public $pallet_redemption; //托盘赎回
	public $payment; //付款登记
	public $sales_detail; //销售明细
	public $sales_rebate; //销售折让
	public $sales_return; //销售退货
	public $sales_amount; //销售重量
	public $sales_return_amount; //销售退货重量
	public $receivables; //收款登记
	public $warehouse_rebate; //仓库返利
	public $mills_rebate;//钢厂返利
	public $storage_charge;//仓储费用
	public $gk_detail;//高开明细
	public $already_collection;//已收款
	public $already_paid;//已付款

	public static $turnover_type = array(
			'CGMX' => "采购明细往来", 
// 			'CGHT' => "采购合同往来", 
// 			'HTZX' => "合同执行往来", 
// 			'HTBL' => "履约补录往来", 
			'FYDJ' => "运费", 
			'CGZR' => "采购折让", 
			'CGTH' => "采购退货", 
			'TPCG' => "托盘采购", 
			'TPSH' => "托盘赎回计息", 
			'FKDJ' => "付款登记", 
			'XSMX' => "销售明细", 
			'XSZR' => "销售折让", 
			'XSTH' => "销售退货", 
			'SKDJ' => "收款登记", 
			'CKFL' => "仓库返利", 
			'GCFL' => "钢厂返利", 
			'CCFY' => "仓储费用", 
			'GKMX' => "高开往来"
	);
	public static $bigType = array(
			'purchase' => "采购", 
			'sales' => "销售", 
			'warehouse' => "仓库", 
			'steelmill' => "钢厂", 
			'freight' => "运费",
			'gaokai'=>"高开"
	);
	public static $turnover_direction = array('need_pay' => "应付", 'need_charge' => "应收", 'payed' => "付款", 'charged' => "收款");
	public static $status = array('unsubmit' => "未提交", 'submited' => "已提交", 'accounted' => "入账");
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'target' => array(self::BELONGS_TO, 'DictCompany', 'target_id'),
			'proxyCompany' => array(self::BELONGS_TO, 'DictCompany', 'proxy_company_id'),
			'commonForms' => array(self::BELONGS_TO, 'CommonForms', 'common_forms_id'),
			'creater' => array(self::BELONGS_TO, 'User', 'created_by'),
			'owner' => array(self::BELONGS_TO, 'User', 'ownered_by'),
			'account' => array(self::BELONGS_TO, 'User', 'account_by'),
			'client' => array(self::BELONGS_TO, 'DictCompany', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'turnover_type' => 'Turnover Type',
			'turnover_direction' => 'Turnover Direction',
			'title_id' => 'Title',
			'target_id' => 'Target',
			'proxy_company_id' => 'Proxy Company',
			'description' => 'Description',
			'amount' => 'Amount',
			'price' => 'Price',
			'fee' => 'Fee',
			'common_forms_id' => 'Common Forms',
			'form_detail_id' => 'Form Detail',
			'status' => 'Status',
			'created_at' => 'Created At',
			'ownered_by' => 'Ownered By',
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
		$criteria->compare('turnover_type',$this->turnover_type,true);
		$criteria->compare('turnover_direction',$this->turnover_direction,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('target_id',$this->target_id);
		$criteria->compare('proxy_company_id',$this->proxy_company_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('price',$this->price);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('common_forms_id',$this->common_forms_id);
		$criteria->compare('form_detail_id',$this->form_detail_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('ownered_by',$this->ownered_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Turnover the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/***----------------------公用方法------------------------------****/
	/**
	 * 创建往来
	 * @param 往来类型 turnover_type
	 * @param 往来方向 turnover_direction
	 * @param 公司抬头 title_id
	 * @param 对端公司 target_id
	 * @param 代理付费公司 proxy_company_id
	 * @param 数量 amount
	 * @param 单价 price
	 * @param 往来总金额 fee
	 * @param 对应单据 common_forms_id
	 * @param 对应单据明细id form_detail_id
	 * @param 所属 ownered_by
	 */
	
	public static function createBill($a)
	{
		$cag=new Turnover();
		$cag->unsetAttributes();
		$cag->turnover_type=$a['type'];		
		$cag->turnover_direction=$a['turnover_direction'];
		$cag->title_id=$a['title_id'];
		$cag->target_id=$a['target_id'];
		if($a['client_id']){
			$cag->client_id=$a['client_id'];
		}else{
			$cag->client_id=$a['target_id'];
		}
		$cag->proxy_company_id=$a['proxy_company_id'];		
		$cag->amount=$a['amount'];
		$cag->price=$a['price'];
		if(($a['turnover_direction']=='need_charge')||($a['turnover_direction']=="payed"))
		{
			$cag->fee=(-floatval($a['fee']));
		}else{
			$cag->fee=floatval($a['fee']);
		}		
		$cag->common_forms_id=$a['common_forms_id'];
		$cag->form_detail_id=$a['form_detail_id'];
		if (!$a['status'])
		$cag->status='unsubmit';
		else $cag->status=$a['status'];
		$cag->created_at=$a['created_at']?$a['created_at']:time();
		$cag->ownered_by=$a['ownered_by'];
		$cag->created_by=$a['created_by'];
		$cag->account_by=$a['account_by'];			
		$cag->description=$a['description'];		
		$cag->is_yidan=$a['is_yidan']?$a['is_yidan']:0;
		$cag->big_type=$a['big_type'];
		$cag->confirmed=$a['confirmed'];
		if (!$a['type'] || !$a['turnover_direction'] || !$a['title_id'] || !$a['target_id'])
			return false;
		if($cag->insert())
		{
			return $cag;
		}
		return false;
	}
	
	
	/*
	 * 获取单条数据
	 */
	public static function getOne($id)
	{
		$model=Turnover::model()->findByPk($id);
		if($model)
		{
			return $model;
		}
		return false;
	}
	/*
	 * 修改往来
	 * 传入：
	 * 	1.往来id
	 * 	2,修改的项的键值对数组
	 */
	public static function updateBill($id,$update)
	{
		$model=Turnover::model()->findByPk($id);
		$target_id = $update["target_id"];
		if($model)
		{
			$flag=false;
			if($model->turnover_direction=='need_charge'||$model->turnover_direction=='payed')
			{
				$flag=true;
			}
			foreach ($update as $k=>$v)
			{
				if($k=='fee'&&$flag)
				{
					$model->$k=-$v;
					continue;
				}
				if($k == "client_id" && intval($v) == 0){
					$model->$k=$target_id;
					continue;
				}
				$model->$k=$v;
			}
			
			if($model->update())
			{
				return $model;
			}
		}
		return false;
	}
	
	/*
	 * 根据commonFrom的id查询所有的往来
	 * 传入：
	 * 	1.commonFrom id
	 */
	public static function findBill($id){
		$model=Turnover::model()->findAll("common_forms_id = :id and status <> 'delete' ",array(":id"=>$id));
		if($model){
			return $model;
		}else{
			return false;	
		}
	}
	
	/*
	 * 根据commonFrom的id查询一条往来
	 * 传入：
	 * 	1.commonFrom id
	 */
	public static function findOneBill($id){
		$model=Turnover::model()->find("common_forms_id = :id and status <> 'delete' ",array(":id"=>$id));
		if($model){
			return $model;
		}else{
			return false;
		}
	}
	
	/*
	 * 根据commonFrom的id和明细的id查询明细对应的的往来
	 * 传入：
	 * 	1.commonFrom id
	 * 	2.detail id
	 */
	public static function findDetailBill($id,$detailId){
		$model=Turnover::model()->find("common_forms_id =:id and form_detail_id=:detailId and status <> 'delete'",array(":id"=>$id,":detailId"=>$detailId));
		if($model){
			return $model;
		}else{
			return false;
		}
	}
	/*
	 * 根据明细的id删除对应的的往来
	 * 传入：
	 * 	1. 往来id
	 */
	public static function deleteBill($id){
		$model=Turnover::model()-findByPk($id);
		$model->status = 'delete';
		if($model->update()){
			return true;
		}else{
			return false;
		}
	}

	//获取往来余额
	public static function getBalance($id) 
	{
		$model = Turnover::model()->findByPk($id);
		$criteria = new CDbCriteria();
		$criteria->select = "sum(fee) as 'balance'"; //总金额
		//公司
		$criteria->addCondition("title_id = :title_id");
		$criteria->params[':title_id'] = $model->title_id;
		//结算单位
		$criteria->addCondition("target_id = :target_id");
		$criteria->params[':target_id'] = $model->target_id;
		//日期
		$criteria->addCondition("created_at <= :created_at");
		$criteria->params[':created_at'] = $model->created_at;

		$criteria->addCondition("status in('submited', 'accounted')");
		$criteria->group = "title_id, target_id";

		$balance = Turnover::model()->find($criteria);
		return $balance->balance;
	}
	
	// /**
	//  * 往来明细
	//  */
	// public static function getIndexList_() 
	// {
	// 	$model = new Turnover();
	// 	$model->confirmed = "";
	// 	$model->is_yidan = "";
	// 	$model->status = "";

	// 	if ($_POST['Turnover'] === null) {
	// 		if ($_GET['start_time']) $model->start_time = $_GET['start_time'];
	// 		if ($_GET['end_time']) $model->end_time = $_GET['end_time'];
	// 		if ($_GET['title_id']) $model->title_id = $_GET['title_id'];
	// 		if ($_GET['target_id']) $model->target_id = $_GET['target_id'];
	// 		if ($_GET['is_yidan'] !== null) $model->is_yidan = $_GET['is_yidan'];
	// 	}

	// 	$criteria = new CDbCriteria();
	// 	if ($_POST['Turnover']) 
	// 	{
	// 		$model->attributes = $_POST['Turnover'];
	// 		if ($_POST['Turnover']['start_time']) 
	// 			$model->start_time = $_POST['Turnover']['start_time'];
	// 		if ($_POST['Turnover']['end_time']) 
	// 			$model->end_time = $_POST['Turnover']['end_time'];

	// 		//描述
	// 		if ($model->description) {
	// 			$criteria->addCondition("description like :description");
	// 			$criteria->params[':description'] = '%'.$model->description.'%';
	// 		}
	// 		//托盘公司
	// 		if ($model->proxy_company_id) {
	// 			$criteria->addCondition("proxy_company_id = :proxy_company_id");
	// 			$criteria->params[':proxy_company_id'] = $model->proxy_company_id;
	// 		}
	// 		//类别
	// 		if ($model->big_type) {
	// 			$criteria->addCondition("big_type = :big_type");
	// 			$criteria->params[':big_type'] = $model->big_type;
	// 		}
	// 		//业务类型
	// 		if ($model->turnover_type) {
	// 			$criteria->addCondition("turnover_type = :turnover_type");
	// 			$criteria->params[':turnover_type'] = $model->turnover_type;
	// 		}
	// 		//往来类型
	// 		if ($model->turnover_direction) {
	// 			$criteria->addCondition("turnover_direction = :turnover_direction");
	// 			$criteria->params[':turnover_direction'] = $model->turnover_direction;
	// 		}
	// 		//往来状态
	// 		if ($model->status) {
	// 			$criteria->addCondition("status = :status");
	// 			$criteria->params[':status'] = $model->status;
	// 		}
	// 		//审单状态
	// 		if ($model->confirmed) {
	// 			$criteria->addCondition("confirmed = :confirmed");
	// 			$criteria->params[':confirmed'] = $model->confirmed;
	// 		}
	// 	}
	// 	//开始日期
	// 	if ($model->start_time) {
	// 		$criteria->addCondition("created_at >= :start_time");
	// 		$criteria->params[':start_time'] = strtotime($model->start_time.' 00:00:00');
	// 	}
	// 	//结束日期
	// 	if ($model->end_time) {
	// 		$criteria->addCondition("created_at <= :end_time");
	// 		$criteria->params[':end_time'] = strtotime($model->end_time.' 23:59:59');
	// 	}
	// 	//公司
	// 	if($model->title_id) 
	// 	{
	// 		$criteria->addCondition("title_id = :title_id");
	// 		$criteria->params[':title_id'] = $model->title_id;
	// 	}
	// 	//checkbox筛选
	// 	if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
	// 		$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
	// 		$title_rl_val = DictTitle::getTitleId('瑞亮物资');
	// 		$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
	// 		$title_cx_val = DictTitle::getTitleId('乘翔实业');
	// 		$other = $_POST['other'];

	// 		if (!$other) 
	// 			$criteria->addInCondition('title_id', array($title_rl_val, $title_cx_val));
	// 		if (!$title_cx) 
	// 			$criteria->addNotInCondition('title_id', array($title_cx_val));
	// 		if (!$title_rl) 
	// 			$criteria->addNotInCondition('title_id', array($title_rl_val));
	// 	}
	// 	//结算单位
	// 	if($model->target_id) 
	// 	{
	// 		$criteria->addCondition("target_id = :target_id");
	// 		$criteria->params[':target_id'] = $model->target_id;
	// 	}
	// 	//乙单
	// 	if ($model->is_yidan !== "") {
	// 		if ($model->is_yidan == 1) $criteria->addCondition("is_yidan = 1");
	// 		else $criteria->addCondition("is_yidan <> 1");
	// 	}
	// 	$criteria->addCondition("status in('submited', 'accounted')");
	// 	$criteria->order = "created_at desc";

	// 	//合计
	// 	$criteria_total = clone $criteria;
	// 	$criteria_total->select = "sum(ifnull(amount, 0)) as 'total_weight', sum(ifnull(fee, 0)) as 'total_fee'";
	// 	$total = $model->find($criteria_total);

	// 	$pages = new CPagination();
	// 	$pages->itemCount = $model->count($criteria);
	// 	$pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']):Yii::app()->params['pageCount'];
	// 	$pages->applyLimit($criteria);
		

	// 	$items = $model->findAll($criteria);
	// 	return array($model, $items, $total, $pages);
	// }
	
	public static function getIndexList(){
		$model = new Turnover();
		$cri = new CDbCriteria();
		$search =  new Turnover();
		$search->is_yidan=0;
		$search->confirmed=2;
		$search->status="";
		$_POST['Turnover'] = $_POST['Turnover'] !== null ? $_POST['Turnover'] : array();
		if ($_GET['start_time'] && $_POST['Turnover']['start_time'] === null) 
			$_POST['Turnover']['start_time'] = $_GET['start_time'];

		if ($_GET['end_time'] && $_POST['Turnover']['end_time'] === null) 
			$_POST['Turnover']['end_time'] = $_GET['end_time'];

		if(checkOperation("不看甲乙单"))
		{
			$_POST['Turnover']['is_yidan']=2;
		}else{
			if (isset($_GET['is_yidan'])&&$_GET['is_yidan']!='' && $_POST['Turnover']['is_yidan'] === null)
			{
				if($_GET['is_yidan']==0)
				{
					$_POST['Turnover']['is_yidan']=2;
				}elseif($_GET['is_yidan']==1){
					$_POST['Turnover']['is_yidan']=1;
				}
			}
		}
		
		
		if ($_GET['title_id'] && $_POST['Turnover']['title_id'] === null) 
			$_POST['Turnover']['title_id'] = $_GET['title_id'];
		
		if ($_GET['target_id'] && $_POST['Turnover']['target_id'] === null) 
			$_POST['Turnover']['target_id'] = $_GET['target_id'];
		
		if ($_GET['client_id'] && $_POST['Turnover']['client_id'] === null)
			$_POST['Turnover']['client_id'] = $_GET['client_id'];
		
		if ($_GET['big_type'] && $_POST['Turnover']['big_type'] === null) 
			$_POST['Turnover']['big_type'] = $_GET['big_type'];

		if ($_GET['ownered_by'] && $_POST['Turnover']['created_by'] === null) 
			$_POST['Turnover']['created_by'] = $_GET['ownered_by'];

		if (isset($_GET['confirmed'])&&$_GET['confirmed']!='' && $_POST['Turnover']['confirmed'] === null) 
			$_POST['Turnover']['confirmed'] = $_GET['confirmed'];

		// if ($_GET['target_id'] && $_POST['Turnover']['target_id'] === null) 
		// 	$_POST['Turnover']['target_id'] = $_GET['target_id'];
		$_POST['Turnover']=updateSearch($_POST['Turnover'],'search_turnindex_index');
		if ($_POST['Turnover']) 
		{
			$search->attributes = $_POST['Turnover'];
			if($search->description){
				$cri->params['description'] = "%".$search->description."%";
				$cri->addCondition("description like :description");
			}
			
			if($_POST['Turnover']['end_time']){
				$et = strtotime($_POST['Turnover']['end_time']." 23:59:59");
				$cri->addCondition("created_at <= $et");
			}
			if($search->title_id){
				$search->title_id = intval($search->title_id);
				$cri->addCondition("title_id = $search->title_id");
			}
			//checkbox筛选
			if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
				$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
				$title_rl_val = DictTitle::getTitleId('瑞亮物资');
				$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
				$title_cx_val = DictTitle::getTitleId('乘翔实业');
				$other = $_POST['other'];

				if (!$other)
					$cri->addInCondition('title_id', array($title_rl_val, $title_cx_val));
				if (!$title_cx) 
					$cri->addNotInCondition('title_id', array($title_cx_val));
				if (!$title_rl) 
					$cri->addNotInCondition('title_id', array($title_rl_val));
			}
			if($search->target_id){
				$search->target_id = intval($search->target_id);
				$cri->addCondition("target_id = $search->target_id");
			}
			if($search->client_id){
				$search->client_id = intval($search->client_id);
				$cri->addCondition("client_id = $search->client_id");
			}
			if($search->proxy_company_id){
				$search->proxy_company_id = intval($search->proxy_company_id);
				$cri->addCondition("proxy_company_id = $search->proxy_company_id");
			}
			if($search->turnover_type){
				$cri->params[':turnover_type'] = $search->turnover_type;
				$cri->addCondition("turnover_type = :turnover_type");
			}
			if($search->created_by){
				$cri->params['ownered_by'] = $search->created_by;
				$cri->addCondition("ownered_by = :ownered_by");
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$cri->addCondition("ownered_by = :ownered_by");
					$cri->params[':ownered_by'] =currentUserId();
				}
			}
			if ($search->big_type) {
				$cri->addCondition("big_type = :big_type");
				$cri->params[':big_type'] = $search->big_type;
			}
			if($search->turnover_direction){
				$cri->params[':turnover_direction'] = $search->turnover_direction;
				$cri->addCondition("turnover_direction = :turnover_direction");
			}
			if($search->status){
				$cri->params[':status'] = $search->status;
				$cri->addCondition("status = :status");
			}
			if($search->confirmed!='2')
			{
				$cri->compare('confirmed', $search->confirmed);
			}
			$jyd = intval($search->is_yidan);
			if($jyd==1){//||$jyd==-1
				$cri->addCondition("is_yidan =1");
			}elseif($jyd == 2){
				$cri->addCondition("is_yidan <> 1");
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$cri->addCondition("ownered_by = :ownered_by");
				$cri->params[':ownered_by'] =currentUserId();
			}
		}
		$cri->addCondition("status in('submited', 'accounted')");
		$cri->order = "created_at asc";
		$c =  clone $cri;
		//如果没有设置开始时间，则与系统设置的默认开始时间进行比较
		$turn_time = intval(Yii::app()->params['turn_time']);
		if( $_POST['Turnover']['start_time']){
			$st = strtotime($_POST['Turnover']['start_time']." 00:00:00");
			if($st >= $turn_time){
				$cri->addCondition("created_at >= $st");
				$c->addCondition("created_at < $st");
			}else{
				$_POST['Turnover']['start_time']=date('Y-m-d',$turn_time);
				$cri->addCondition("created_at >=".$turn_time);
				$c->addCondition("created_at < ".$turn_time);
			}
		}else{
			$_POST['Turnover']['start_time']=date('Y-m-d',$turn_time);
			$cri->addCondition("created_at >=".$turn_time);
			$c->addCondition("created_at < ".$turn_time);
		}
		
		//合计
		$criteria_total = clone $cri;
		$criteria_total->select = "sum(ifnull(fee, 0)) as 'total_fee',turnover_direction";
		$criteria_total->group = "turnover_direction";
		$totaldata = $model->findAll($criteria_total);
		$total = array();
		foreach ($totaldata as $item){
			if($item->turnover_direction == "need_pay" || $item->turnover_direction == "need_charge"){
				$total['one'] += $item->total_fee;
			}
			if($item->turnover_direction == "payed" || $item->turnover_direction == "charged"){
				$total['two'] += $item->total_fee;
			}
		}
		
		$pageSize = $_COOKIE['to']? intval($_COOKIE['to']):Yii::app()->params['pageCount'];
		$cri_limit = clone $cri;
		$page = intval($_REQUEST["page"]);
		//如果显示的不是第一页，查询前面的数据，重新计算期初余额
		if($page > 1){
			$cri_limit->limit = ($page - 1)*$pageSize;
			$limit_data = Turnover::model()->findAll($cri_limit);
		}
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $pageSize;
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		
		//计算期初余额
		$arr_ye = array();
		$c->select = "title_id, target_id, ifnull(sum(fee), 0) as yue";
		$qichu =  Turnover::model()->find($c);
		$qichu_y = $qichu->yue;
//		$c->group = "title_id,target_id";
// 		$fees = $model->findAll($c);
// 		foreach($fees as $f){
// 			$k = $f->title_id.",".$f->target_id;
// 			$arr_ye[$k] = $f->yue;
// 		}
		//期初余额加上分页后，前面几页的余额，重新计算期初余额
		if($limit_data){
			foreach($limit_data as $f){
				$qichu_y += $f->fee;
// 				$k = $f->title_id.",".$f->target_id;
// 				$arr_ye[$k] += $f->fee;
//				$total_num += $f->fee;
			}
		}
//		var_dump($total_num);
		return array($model,$search,$pages,$items,$arr_ye, $total,$qichu_y);
	}
	
	//获取对应公司往来
	public static function getSimpleList($search) 
	{
		$tableHeader = array(
				array('name' => "序号", 'class' => "sort-disabled table_cell_first", 'width' => "60px"),
				array('name' => "创建时间", 'class' => "sort-disabled", 'width' => "100px"),
				array('name' => "公司", 'class' => "sort-disabled", 'width' => "110px"),
				array('name' => "结算单位", 'class' => "sort-disabled", 'width' => "110px"),
				array('name' => "往来业务类型", 'class' => "sort-disabled flex-col", 'width' => "150px"),
				array('name' => "往来类型", 'class' => "sort-disabled flex-col", 'width' => "100px"),
				array('name' => "代理付费公司", 'class' => "sort-disabled flex-col", 'width' => "110px"),
				array('name' => "乙单", 'class' => "sort-disabled flex-col", 'width' => "60px"),
				array('name' => "往来描述", 'class' => "sort-disabled flex-col", 'width' => "400px"),
				array('name' => "数量", 'class' => "sort-disabled flex-col  text-right", 'width' => "120px"),
				array('name' => "单价", 'class' => "sort-disabled flex-col  text-right", 'width' => "120px"),
				array('name' => "总金额", 'class' => "sort-disabled flex-col  text-right", 'width' => "120px"),
				array('name' => "余额", 'class' => "sort-disabled flex-col  text-right", 'width' => "120px"),
				array('name' => "往来状态", 'class' => "sort-disabled flex-col", 'width' => "100px"),
				array('name' => "负责人", 'class' => "sort-disabled flex-col", 'width' => "100px"),
				array('name' => "经办人", 'class' => "sort-disabled flex-col", 'width' => "100px"),
				array('name' => "入账人", 'class' => "sort-disabled flex-col", 'width' => "100px"),
		);
		
		$tableData = array();
		$model = new Turnover();
		$criteria = new CDbCriteria();
		
		//搜索
		if (!empty($search)) 
		{
			if ($search['company_id']) 
			{
				$criteria->addCondition("target_id = :target_id");
				$criteria->params[':target_id'] = $search['company_id'];
			}
			if ($search['title_id'])
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
		}
		$criteria->addCondition("status <> 'unsubmit' and status <> 'delete'");
		$criteria->order = "created_at desc";
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['turnover_list']) ? intval($_COOKIE['turnover_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		
		if (!$search['title_id'] || !$search['company_id']) return array($tableHeader, $tableData, $pages);
		$items = $model->findAll($criteria);
		if (!$items) return array($tableHeader, $tableData, $pages);
		$i = 1;
		$da = array();
		
		$arr_ye = array();
		$et = $items[0]->created_at;
		$cri_ = new CDbCriteria();
		$cri_->select = "target_id, title_id, sum(fee) as yue";
		$cri_->addCondition("created_at <= $et");
		$cri_->addCondition("status = 'submited' or status = 'accounted'");
		$cri_->group = "title_id,target_id";
		$fees = $model->findAll($cri_);
		foreach ($fees as $f) 
		{
			$k = $f->title_id.",".$f->target_id;
			$arr_ye[$k] = $f->yue;
		}
		
		foreach ($items as $item) 
		{
			$mark = $i;
			$i++;
			
			$da['data'] = array($mark,
					date("Y-m-d", $item->created_at),
					'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
					'<span title="'.$item->target->name.'">'.$item->target->short_name.'</span>',
					Turnover::$turnover_type[$item->turnover_type] ? Turnover::$turnover_type[$item->turnover_type] : '未知',
					Turnover::$turnover_direction[$item->turnover_direction],
					'<span title="'.$item->proxyCompany->name.'"'.$item->proxyCompany->short_name.'</span>',
					$item->is_yidan == 1 ? '乙单' : '',
					$item->description,
					number_format($item->amount, 3),
					number_format($item->price, 2),
					number_format($item->fee >= 0 ? $item->fee : -$item->fee, 2),
					number_format($arr_ye[$item->title_id.",".$item->target_id], 2),
					Turnover::$status[$item->status] ? Turnover::$status[$item->status] : '未知',
					$item->owner->nickname,
					$item->creater->nickname,
					$item->account->nickname
			);
			$arr_ye[$item->title_id.",".$item->target_id] -= $item->fee;
			
			$da['group'] = $item->id;
			array_push($tableData, $da);
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	//获取对应公司往来余额
	public static function getSalesYu($title_id,$target_id,$own,$type)
	{
		$model = new Turnover();
		$criteria = new CDbCriteria();
		$criteria->addCondition("title_id=".$title_id);
		$criteria->addCondition("target_id=".$target_id);
		if($own){
			$criteria->addCondition("ownered_by=".$own);
		}
		$criteria->addCondition("big_type='".$type."'");
		$criteria->addCondition("status <> 'delete'");
		$criteria->addCondition("status <> 'unsubmit'");
		$model = $model->findAll($criteria);
		$money = 0;
		if($model){
			foreach ($model as $li){
				$money += $li->fee;
			}
		}
		return $money;
	}
	//获取对应公司全部往来余额
	public static function getTurYu($title_id,$target_id)
	{
		$model = new Turnover();
		$criteria = new CDbCriteria();
		$criteria->addCondition("title_id=".$title_id);
		$criteria->addCondition("target_id=".$target_id);
		$turn_time = intval(Yii::app()->params['turn_time']);
		$criteria->addCondition("created_at >=".$turn_time);
		$criteria->addCondition("status <> 'delete'");
		$criteria->addCondition("status <> 'unsubmit'");
		$model = $model->findAll($criteria);
		$money = 0;
		if($model){
			foreach ($model as $li){
				$money += $li->fee;
			}
		}
		return $money;
	}
	//获取对应公司全部往来余额
	public static function getTurYu11($title_id,$target_id)
	{
		$model = new Turnover();
		$criteria = new CDbCriteria();
		$criteria->addCondition("title_id=".$title_id);
		$criteria->addCondition("target_id=".$target_id);
		$criteria->addCondition("status <> 'delete'");
		$criteria->addCondition("status <> 'unsubmit'");
		$criteria->select='sum(fee) as fee' ;
		$model = $model->find($criteria);
		$money = 0;
		if($model){
			$money=$model->fee;
		}
		return $money;
	}
	
	//导入销售单往来
	public static function importSalesTurnover($data){
		$_yidan = array("甲单"=>0,"乙单"=>1);
		$title_id = intval(DictTitle::getTitleId($data['title_name']));
		if($title_id == 0){return -1;}
		$target_id = intval(DictCompany::getCompanyId($data['target_name']));
		if($target_id == 0){return -2;}
		$own_by = intval(User::getUserId($data['own_by']));
		if($own_by == 0){return -3;}
		$model = new Turnover();
		$model->turnover_type = "XSMX";
		$model->turnover_direction = "need_charge";
		$model->title_id = $title_id;
		$model->target_id = $target_id;
		$model->amount = 1;
		$model->price = $data['fee'];
		$model->fee = $data['fee'];
		$model->is_yidan = $_yidan[$data['yidan']];
		$model->status = "submited";
		$model->ownered_by = $own_by;
		$model->created_at = time();
		$model->created_by = 1;
		$model->big_type = "sales";
		$model->description = "结转补录往来";
		if($model->insert()){
			return 1;
		}else{
			return 0;
		}
	}

	/**
	 * 往来汇总
	 * @return array 往来汇总数据
	 */
	public static function getTotalList() 
	{
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";

		$criteria = new CDbCriteria(); //
		$criteria_total = new CDbCriteria(); //总计
		$_POST['Turnover']=updateSearch($_POST['Turnover'],'search_turntotal_index');
		if ($_POST['Turnover']) 
		{
			$model->attributes = $_POST['Turnover'];
			if($_POST['Turnover']['start_time'])
			{
				if(strtotime($_POST['Turnover']['start_time'])<Yii::app()->params['turn_time']){
					$model->start_time=date('Y-m-d H:i:s',Yii::app()->params['turn_time']);
				}else{
					$model->start_time = $_POST['Turnover']['start_time'];
				}
			}else{
				$model->start_time =$model->start_time;
			}
			// $model->start_time = $_POST['Turnover']['start_time'] ? $_POST['Turnover']['start_time'] : $model->start_time;
			$model->end_time = $_POST['Turnover']['end_time'] ? $_POST['Turnover']['end_time'] : $model->end_time;
			$model->confirmed = $_POST['Turnover']['confirmed'];
			if ($model->big_type) {
				$criteria->addCondition("big_type = :big_type");
				$criteria->params[':big_type'] = $model->big_type;
			}
			if ($model->ownered_by) {
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] = $model->ownered_by;
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$criteria->addCondition("ownered_by = :ownered_by");
					$criteria->params[':ownered_by'] =currentUserId();
				}
			}
			if(checkOperation("不看甲乙单"))
			{
				$criteria->addCondition("is_yidan<>1");
			}else{
				if ($model->is_yidan !== "") {
					if ($model->is_yidan == 1) {
						$criteria->addCondition("is_yidan = 1");
					} else {
						$criteria->addCondition("is_yidan <> 1");
					}
				}
			}			
			if ($model->confirmed !== "") {
				$criteria->addCondition("confirmed = :confirmed");
				$criteria->params[':confirmed'] = $model->confirmed;
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] =currentUserId();
			}
			if(checkOperation("不看甲乙单"))
			{
				$criteria->addCondition("is_yidan<>1");
			}
		}
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et) 
			return array($model, array(), $pages, array(), "起始时间不能大于结束时间！");

		$criteria->addInCondition("status", array('submited', 'accounted'));
		$condition = $criteria->condition ? ' AND '.$criteria->condition : '';
		$condition_total = $condition;
//---------------------------------------------------------------------------------------------------
		//期末余额
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." group by title_id, target_id,client_id) fb on fb.title_id = t.title_id and fb.target_id = t.target_id and fb.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//期初余额
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where created_at < :start_time".$condition." group by title_id, target_id,client_id) ib on ib.title_id = t.title_id and ib.target_id = t.target_id and ib.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//日期
//		$criteria->addCondition("created_at between :start_time and :end_time");
		$condition .= " AND (created_at between :start_time and :end_time)";
//---------------------------------------------------------------------------------------------------
		//采购明细		
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'purchase_detail' from turnover where turnover_type = 'CGMX'".$condition." group by title_id, target_id,client_id) cgmx on cgmx.title_id = t.title_id and cgmx.target_id = t.target_id and cgmx.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//运费 
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'freight' from turnover where turnover_type = 'FYDJ'".$condition." group by title_id, target_id,client_id) fydj on fydj.title_id = t.title_id and fydj.target_id = t.target_id and fydj.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//采购折让
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'purchase_rebate' from turnover where turnover_type = 'CGZR'".$condition." group by title_id, target_id,client_id) cgzr on cgzr.title_id = t.title_id and cgzr.target_id = t.target_id and cgzr.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//采购退货
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'purchase_return' from turnover where turnover_type = 'CGTH'".$condition." group by title_id, target_id,client_id) cgth on cgth.title_id = t.title_id and cgth.target_id = t.target_id and cgth.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//托盘采购
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'tray_purchase' from turnover where turnover_type = 'TPCG'".$condition." group by title_id, target_id,client_id) tpcg on tpcg.title_id = t.title_id and tpcg.target_id = t.target_id and tpcg.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//托盘赎回
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'pallet_redemption' from turnover where turnover_type = 'TPSH'".$condition." group by title_id, target_id,client_id) tpsh on tpsh.title_id = t.title_id and tpsh.target_id = t.target_id and tpsh.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//付款登记
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'payment' from turnover where turnover_type = 'FKDJ'".$condition." group by title_id, target_id,client_id) fkdj on fkdj.title_id = t.title_id and fkdj.target_id = t.target_id and fkdj.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//销售明细
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'sales_detail',ifnull(sum(amount), 0) as 'sales_amount' from turnover where turnover_type = 'XSMX'".$condition." group by title_id, target_id,client_id) xsmx on xsmx.title_id = t.title_id and xsmx.target_id = t.target_id and xsmx.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//销售折让
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition." group by title_id, target_id,client_id) xszr on xszr.title_id = t.title_id and xszr.target_id = t.target_id and xszr.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//销售退货
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'sales_return',ifnull(sum(amount), 0) as 'sales_return_amount' from turnover where turnover_type = 'XSTH'".$condition." group by title_id, target_id,client_id) xsth on xsth.title_id = t.title_id and xsth.target_id = t.target_id and xsth.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//收款登记
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'receivables' from turnover where turnover_type = 'SKDJ'".$condition." group by title_id, target_id,client_id) skdj on skdj.title_id = t.title_id and skdj.target_id = t.target_id and skdj.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//仓库返利
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'warehouse_rebate' from turnover where turnover_type = 'CKFL'".$condition." group by title_id, target_id,client_id) ckfl on ckfl.title_id = t.title_id and ckfl.target_id = t.target_id and ckfl.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//钢厂返利
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'mills_rebate' from turnover where turnover_type = 'GCFL'".$condition." group by title_id, target_id,client_id) gcfl on gcfl.title_id = t.title_id and gcfl.target_id = t.target_id and gcfl.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//仓储费用
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'storage_charge' from turnover where turnover_type = 'CCFY'".$condition." group by title_id, target_id,client_id) ccfy on ccfy.title_id = t.title_id and ccfy.target_id = t.target_id and ccfy.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//高开明细
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'gk_detail' from turnover where turnover_type = 'GKMX'".$condition." group by title_id, target_id,client_id) gkmx on gkmx.title_id = t.title_id and gkmx.target_id = t.target_id and gkmx.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//已收款
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition." group by title_id, target_id,client_id) ac on ac.title_id = t.title_id and ac.target_id = t.target_id and ac.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------
		//已付款
		$criteria->join .= " left join (select title_id, target_id,client_id, ifnull(sum(fee), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition." group by title_id, target_id,client_id) ap on ap.title_id = t.title_id and ap.target_id = t.target_id and ap.client_id = t.client_id";
//---------------------------------------------------------------------------------------------------

		$criteria->select = "t.title_id, t.target_id, t.client_id, 
			fb.final_balance, 
			ib.initial_balance, 

			cgmx.purchase_detail, 
			fydj.freight, 
			cgzr.purchase_rebate,
			cgth.purchase_return,
			tpcg.tray_purchase, 
			tpsh.pallet_redemption, 
			fkdj.payment, 
			xsmx.sales_detail,
			xsmx.sales_amount,
			xszr.sales_rebate, 
			xsth.sales_return, 
			xsth.sales_return_amount, 
			skdj.receivables, 
			ckfl.warehouse_rebate, 
			gcfl.mills_rebate, 
			ccfy.storage_charge, 
			gkmx.gk_detail, 

			ac.already_collection, 
			ap.already_paid
		";
		$criteria->params[':start_time'] = $st;
		$criteria->params[':end_time'] = $et;
		if ($model->title_id) { //公司抬头
			$criteria->addCondition("t.title_id = :title_id");
			$condition_total .= " AND (title_id = :title_id)";

			$criteria->params[':title_id'] = $model->title_id;
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];

			if (!$other) {
				$criteria->addInCondition('t.title_id', array($title_rl_val, $title_cx_val));
				$condition_total .= " AND (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$criteria->addNotInCondition('t.title_id', array($title_cx_val));
				$condition_total .= " AND (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$criteria->addNotInCondition('t.title_id', array($title_rl_val));
				$condition_total .= " AND (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$criteria->addCondition("t.target_id = :target_id");
			$condition_total .= " AND (target_id = :target_id)";
			$criteria->params[':target_id'] = $model->target_id;
		}
		if ($model->client_id) { //客户
			$criteria->addCondition("t.client_id = :client_id");
			$condition_total .= " AND (client_id = :client_id)";
			$criteria->params[':client_id'] = $model->client_id;
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		switch ($_REQUEST['final_balance']) 
		{
			case 'wrong': //非0
				$criteria->addCondition("fb.final_balance <> 0");
				// $criteria_total->addCondition("fb.final_balance <> 0");
				break;
			case 'positive': //大于0
				$criteria->addCondition("fb.final_balance > 0");
				// $criteria_total->addCondition("fb.final_balance > 0");
				break;
			case 'negative': //小于0
				$criteria->addCondition("fb.final_balance < 0"); 
				// $criteria_total->addCondition("fb.final_balance < 0"); 
				break;
			default: break;
		}
		$criteria->group = "t.title_id, t.target_id, t.client_id";
		$criteria->order = "fb.final_balance desc";
		$total_items = $model->findAll($criteria);
		
		$final_balance = 0;
		$initial_balance = 0;
		$purchase_detail = 0;
		$freight = 0;
		$purchase_rebate = 0;
		$purchase_return = 0;
		$tray_purchase = 0;
		$pallet_redemption = 0;
		$payment = 0;
		$sales_detail = 0;
		$sales_amount = 0;
		$sales_rebate = 0;
		$sales_return = 0;
		$sales_return_amount = 0;
		$receivables = 0;
		$warehouse_rebate = 0;
		$mills_rebate = 0;
		$storage_charge = 0;
		$gk_detail = 0;
		$already_collection = 0;
		$already_paid = 0;

		foreach ($total_items as $total_item) {
			$final_balance += $total_item->final_balance ? $total_item->final_balance : 0;
			$initial_balance += $total_item->initial_balance ? $total_item->initial_balance : 0;
			$purchase_detail += $total_item->purchase_detail ? $total_item->purchase_detail : 0;
			$freight += $total_item->freight ? $total_item->freight : 0;
			$purchase_rebate += $total_item->purchase_rebate ? $total_item->purchase_rebate : 0;
			$purchase_return += $total_item->purchase_return ? $total_item->purchase_return : 0;
			$tray_purchase += $total_item->tray_purchase ? $total_item->tray_purchase : 0;
			$pallet_redemption += $total_item->pallet_redemption ? $total_item->pallet_redemption : 0;
			$payment += $total_item->payment ? $total_item->payment : 0;
			$sales_detail += $total_item->sales_detail ? $total_item->sales_detail : 0;
			$sales_amount += $total_item->sales_amount ? $total_item->sales_amount : 0;
			$sales_rebate += $total_item->sales_rebate ? $total_item->sales_rebate : 0;
			$sales_return += $total_item->sales_return ? $total_item->sales_return : 0;
			$sales_return_amount += $total_item->sales_return_amount ? $total_item->sales_return_amount : 0;
			$receivables += $total_item->receivables ? $total_item->receivables : 0;
			$warehouse_rebate += $total_item->warehouse_rebate ? $total_item->warehouse_rebate : 0;
			$mills_rebate += $total_item->mills_rebate ? $total_item->mills_rebate : 0;
			$storage_charge += $total_item->storage_charge ? $total_item->storage_charge : 0;
			$gk_detail += $total_item->gk_detail ? $total_item->gk_detail : 0;
			$already_collection += $total_item->already_collection ? $total_item->already_collection : 0;
			$already_paid += $total_item->already_paid ? $total_item->already_paid : 0;
		}

		$totaldata = (Object)array(
			'final_balance' => $final_balance, 
			'initial_balance' => $initial_balance, 
			'purchase_detail' => $purchase_detail, 
			'freight' => $freight, 
			'purchase_rebate' => $purchase_rebate,
			'purchase_return' => $purchase_return,
			'tray_purchase' => $tray_purchase, 
			'pallet_redemption' => $pallet_redemption, 
			'payment' => $payment, 
			'sales_detail' => $sales_detail, 
			'sales_amount' => $sales_amount,
			'sales_rebate' => $sales_rebate, 
			'sales_return' => $sales_return,
			'sales_return_amount' => $sales_return_amount,
			'receivables' => $receivables, 
			'warehouse_rebate' => $warehouse_rebate, 
			'mills_rebate' => $mills_rebate, 
			'storage_charge' => $storage_charge, 
			'gk_detail' => $gk_detail, 
			'already_collection' => $already_collection, 
			'already_paid' => $already_paid, 
		);

		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);

		$items = $model->findAll($criteria);
		return array($model, $items, $pages, $totaldata, "");
	}	


	/**
	*往来统计
	*
	*/
	public static function totalYouWant()
	{
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";

		$criteria = new CDbCriteria(); //
		$criteria_total = new CDbCriteria(); //总计
		$_POST['Turnover']=updateSearch($_POST['Turnover'],'search_turnyouwant_index');
		if ($_POST['Turnover']) 
		{
			$model->attributes = $_POST['Turnover'];
			// $model->start_time = $_POST['Turnover']['start_time'] ? $_POST['Turnover']['start_time'] : $model->start_time;
			if($_POST['Turnover']['start_time'])
			{
				if(strtotime($_POST['Turnover']['start_time'])<Yii::app()->params['turn_time']){
					$model->start_time=date('Y-m-d H:i:s',Yii::app()->params['turn_time']);
				}else{
					$model->start_time = $_POST['Turnover']['start_time'];
				}
			}else{
				$model->start_time =$model->start_time;
			}
			$model->end_time = $_POST['Turnover']['end_time'] ? $_POST['Turnover']['end_time'] : $model->end_time;
			$model->confirmed = $_POST['Turnover']['confirmed'];
			if ($model->big_type) {
				$criteria->addCondition("big_type = :big_type");
				$criteria->params[':big_type'] = $model->big_type;
			}
			if ($model->ownered_by) {
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] = $model->ownered_by;
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$criteria->addCondition("ownered_by = :ownered_by");
					$criteria->params[':ownered_by'] =currentUserId();
				}
			}			
			if(checkOperation("不看甲乙单"))
			{
				$criteria->addCondition("is_yidan<>1");
			}else{
				if ($model->is_yidan !== "") {
					if ($model->is_yidan == 1) {
						$criteria->addCondition("is_yidan = 1");
					} else {
						$criteria->addCondition("is_yidan <> 1");
					}
				}
			}			
			if ($model->confirmed !== "") {
				$criteria->addCondition("confirmed = :confirmed");
				$criteria->params[':confirmed'] = $model->confirmed;
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] =currentUserId();
			}
			if(checkOperation("不看甲乙单"))
			{
				$criteria->addCondition("is_yidan<>1");
			}
		}
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et) 
			return array($model, array(), $pages, array(), "起始时间不能大于结束时间！");

		$criteria->addInCondition("status", array('submited', 'accounted'));
		$condition = $criteria->condition ? ' AND '.$criteria->condition : '';
		$condition_total = $condition;

		$criteria->params[':start_time'] = $st;
		$criteria->params[':end_time'] = $et;
		$more_condition='';
		if ($model->title_id) { //公司抬头
			$criteria->addCondition("t.title_id = :title_id");
			$condition.=' and (title_id='.$model->title_id.')';
			$condition_total .= " AND (title_id = :title_id)";

			$criteria->params[':title_id'] = $model->title_id;
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];

			if (!$other) {
				$criteria->addInCondition('t.title_id', array($title_rl_val, $title_cx_val));
				$condition_total .= " AND (title_id in($title_rl_val, $title_cx_val))";
				$condition.=" and (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$criteria->addNotInCondition('t.title_id', array($title_cx_val));
				$condition_total .= " AND (title_id not in($title_cx_val))";
				$condition.=" and (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$criteria->addNotInCondition('t.title_id', array($title_rl_val));
				$condition_total .= " AND (title_id not in($title_rl_val))";
				$condition.="and (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$criteria->addCondition("t.target_id = :target_id");
			$condition_total .= " AND (target_id = :target_id)";

			$criteria->params[':target_id'] = $model->target_id;
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		switch ($_REQUEST['final_balance']) 
		{
			case 'wrong': //非0
				$criteria->addCondition("fb.final_balance <> 0");
				// $criteria_total->addCondition("fb.final_balance <> 0");
				break;
			case 'positive': //大于0
				$criteria->addCondition("fb.final_balance > 0");
				// $criteria_total->addCondition("fb.final_balance > 0");
				break;
			case 'negative': //小于0
				$criteria->addCondition("fb.final_balance < 0"); 
				// $criteria_total->addCondition("fb.final_balance < 0"); 
				break;
			default: break;
		}
		$criteria->group = " t.target_id";
		$criteria->order = "fb.final_balance desc";

		//---------------------------------------------------------------------------------------------------
		//期末余额
		$criteria->join .= " left join (select target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." group by target_id) fb on  fb.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//期初余额
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where created_at < :start_time".$condition." group by target_id) ib on   ib.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//日期
//		$criteria->addCondition("created_at between :start_time and :end_time");
		$condition .= " AND (created_at between :start_time and :end_time)";
//---------------------------------------------------------------------------------------------------
		//采购明细		
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'purchase_detail' from turnover where turnover_type = 'CGMX'".$condition." group by target_id) cgmx on  cgmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//运费 
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'freight' from turnover where turnover_type = 'FYDJ'".$condition." group by  target_id) fydj on  fydj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购折让
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'purchase_rebate' from turnover where turnover_type = 'CGZR'".$condition." group by  target_id) cgzr on  cgzr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购退货
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'purchase_return' from turnover where turnover_type = 'CGTH'".$condition." group by  target_id) cgth on  cgth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘采购
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'tray_purchase' from turnover where turnover_type = 'TPCG'".$condition." group by  target_id) tpcg on  tpcg.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘赎回
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'pallet_redemption' from turnover where turnover_type = 'TPSH'".$condition." group by  target_id) tpsh on  tpsh.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//付款登记
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'payment' from turnover where turnover_type = 'FKDJ'".$condition." group by  target_id) fkdj on  fkdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售明细
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'sales_detail' from turnover where turnover_type = 'XSMX'".$condition." group by  target_id) xsmx on xsmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售折让
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition." group by  target_id) xszr on  xszr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售退货
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'sales_return' from turnover where turnover_type = 'XSTH'".$condition." group by  target_id) xsth on  xsth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//收款登记
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'receivables' from turnover where turnover_type = 'SKDJ'".$condition." group by target_id) skdj on  skdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓库返利
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'warehouse_rebate' from turnover where turnover_type = 'CKFL'".$condition." group by  target_id) ckfl on  ckfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//钢厂返利
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'mills_rebate' from turnover where turnover_type = 'GCFL'".$condition." group by  target_id) gcfl on  gcfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓储费用
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'storage_charge' from turnover where turnover_type = 'CCFY'".$condition." group by  target_id) ccfy on ccfy.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//高开明细
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'gk_detail' from turnover where turnover_type = 'GKMX'".$condition." group by  target_id) gkmx on  gkmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已收款
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition." group by  target_id) ac on  ac.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已付款
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition." group by  target_id) ap on  ap.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------

		$criteria->select = " t.target_id, fb.final_balance, ib.initial_balance, cgmx.purchase_detail, fydj.freight, 
			cgzr.purchase_rebate,cgth.purchase_return,	tpcg.tray_purchase, tpsh.pallet_redemption, fkdj.payment, 
			xsmx.sales_detail, 	xszr.sales_rebate, 	xsth.sales_return, 	skdj.receivables, 	ckfl.warehouse_rebate, 
			gcfl.mills_rebate, 	ccfy.storage_charge, gkmx.gk_detail, 	ac.already_collection, 	ap.already_paid
		";

		$total_items = $model->findAll($criteria);

		$final_balance = 0;
		$initial_balance = 0;
		$purchase_detail = 0;
		$freight = 0;
		$purchase_rebate = 0;
		$purchase_return = 0;
		$tray_purchase = 0;
		$pallet_redemption = 0;
		$payment = 0;
		$sales_detail = 0;
		$sales_rebate = 0;
		$sales_return = 0;
		$receivables = 0;
		$warehouse_rebate = 0;
		$mills_rebate = 0;
		$storage_charge = 0;
		$gk_detail = 0;
		$already_collection = 0;
		$already_paid = 0;

		foreach ($total_items as $total_item) {
			$final_balance += $total_item->final_balance ? $total_item->final_balance : 0;
			$initial_balance += $total_item->initial_balance ? $total_item->initial_balance : 0;
			$purchase_detail += $total_item->purchase_detail ? $total_item->purchase_detail : 0;
			$freight += $total_item->freight ? $total_item->freight : 0;
			$purchase_rebate += $total_item->purchase_rebate ? $total_item->purchase_rebate : 0;
			$purchase_return += $total_item->purchase_return ? $total_item->purchase_return : 0;
			$tray_purchase += $total_item->tray_purchase ? $total_item->tray_purchase : 0;
			$pallet_redemption += $total_item->pallet_redemption ? $total_item->pallet_redemption : 0;
			$payment += $total_item->payment ? $total_item->payment : 0;
			$sales_detail += $total_item->sales_detail ? $total_item->sales_detail : 0;
			$sales_rebate += $total_item->sales_rebate ? $total_item->sales_rebate : 0;
			$sales_return += $total_item->sales_return ? $total_item->sales_return : 0;
			$receivables += $total_item->receivables ? $total_item->receivables : 0;
			$warehouse_rebate += $total_item->warehouse_rebate ? $total_item->warehouse_rebate : 0;
			$mills_rebate += $total_item->mills_rebate ? $total_item->mills_rebate : 0;
			$storage_charge += $total_item->storage_charge ? $total_item->storage_charge : 0;
			$gk_detail += $total_item->gk_detail ? $total_item->gk_detail : 0;
			$already_collection += $total_item->already_collection ? $total_item->already_collection : 0;
			$already_paid += $total_item->already_paid ? $total_item->already_paid : 0;
		}

		$totaldata = (Object)array(
			'final_balance' => $final_balance, 
			'initial_balance' => $initial_balance, 
			'purchase_detail' => $purchase_detail, 
			'freight' => $freight, 
			'purchase_rebate' => $purchase_rebate,
			'purchase_return' => $purchase_return,
			'tray_purchase' => $tray_purchase, 
			'pallet_redemption' => $pallet_redemption, 
			'payment' => $payment, 
			'sales_detail' => $sales_detail, 
			'sales_rebate' => $sales_rebate, 
			'sales_return' => $sales_return, 
			'receivables' => $receivables, 
			'warehouse_rebate' => $warehouse_rebate, 
			'mills_rebate' => $mills_rebate, 
			'storage_charge' => $storage_charge, 
			'gk_detail' => $gk_detail, 
			'already_collection' => $already_collection, 
			'already_paid' => $already_paid, 
		);

		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);

		$items = $model->findAll($criteria);
		return array($model, $items, $pages, $totaldata, "");
	}



	/**
	 * 往来明细 查询所有
	 * @param  array $search 搜索条件
	 * @return array $content 查询结果
	 */
	public static function getAllList($search) 
	{
		$model = new Turnover();
		$cri = new CDbCriteria();
		$search =  new Turnover();
		$search->is_yidan=0;
		$search->confirmed=2;
		$search->status="";
		
		$_POST['Turnover'] = $_POST['Turnover'] !== null ? $_POST['Turnover'] : array();
		if ($_GET['start_time'] && $_POST['Turnover']['start_time'] === null)
			$_POST['Turnover']['start_time'] = $_GET['start_time'];
		
		if ($_GET['end_time'] && $_POST['Turnover']['end_time'] === null)
			$_POST['Turnover']['end_time'] = $_GET['end_time'];
	
		if(checkOperation("不看甲乙单"))
		{
			$_POST['Turnover']['is_yidan']=2;
		}else{
			if ($_GET['is_yidan'] && $_POST['Turnover']['is_yidan'] === null)
				$_POST['Turnover']['is_yidan'] = $_GET['is_yidan'];
		}	

		if ($_GET['title_id'] && $_POST['Turnover']['title_id'] === null)
			$_POST['Turnover']['title_id'] = $_GET['title_id'];

		if ($_GET['target_id'] && $_POST['Turnover']['target_id'] === null)
			$_POST['Turnover']['target_id'] = $_GET['target_id'];

		if ($_POST['Turnover'])
		{
			$search->attributes = $_POST['Turnover'];
			if($search->description){
				$cri->params['description'] = "%".$search->description."%";
				$cri->addCondition("description like :description");
			}
				
			if($_POST['Turnover']['end_time']){
				$et = strtotime($_POST['Turnover']['end_time']." 23:59:59");
				$cri->addCondition("created_at <= $et");
			}
			if($search->title_id){
				$search->title_id = intval($search->title_id);
				$cri->addCondition("title_id = $search->title_id");
			}
			//checkbox筛选
			if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
				$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
				$title_rl_val = DictTitle::getTitleId('瑞亮物资');
				$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
				$title_cx_val = DictTitle::getTitleId('乘翔实业');
				$other = $_POST['other'];

				if (!$other)
					$cri->addInCondition('title_id', array($title_rl_val, $title_cx_val));
				if (!$title_cx)
					$cri->addNotInCondition('title_id', array($title_cx_val));
				if (!$title_rl)
					$cri->addNotInCondition('title_id', array($title_rl_val));
			}
			if($search->target_id){
				$search->target_id = intval($search->target_id);
				$cri->addCondition("target_id = $search->target_id");
			}
			if($search->client_id){
				$search->client_id = intval($search->client_id);
				$cri->addCondition("client_id = $search->client_id");
			}
			if($search->proxy_company_id){
				$search->proxy_company_id = intval($search->proxy_company_id);
				$cri->addCondition("proxy_company_id = $search->proxy_company_id");
			}
			if($search->turnover_type){
				$cri->params[':turnover_type'] = $search->turnover_type;
				$cri->addCondition("turnover_type = :turnover_type");
			}
			if($search->created_by){
				$cri->params['ownered_by'] = $search->created_by;
				$cri->addCondition("ownered_by = :ownered_by");
			}
			if ($search->big_type) {
				$cri->addCondition("big_type = :big_type");
				$cri->params[':big_type'] = $search->big_type;
			}
			if($search->turnover_direction){
				$cri->params[':turnover_direction'] = $search->turnover_direction;
				$cri->addCondition("turnover_direction = :turnover_direction");
			}
			if($search->status){
				$cri->params[':status'] = $search->status;
				$cri->addCondition("status = :status");
			}
			if($search->confirmed!='2')
			{
				$cri->compare('confirmed', $search->confirmed);
			}
			$jyd = intval($search->is_yidan);
			if($jyd==1){//||$jyd==-1
				$cri->addCondition("is_yidan =1");
			}elseif($jyd == 2){
				$cri->addCondition("is_yidan <> 1");
			}
		}
		$cri->addCondition("status in('submited', 'accounted')");
		$cri->order = "created_at asc";
		$c =  clone $cri;
		//如果没有设置开始时间，则与系统设置的默认开始时间进行比较
		$turn_time = intval(Yii::app()->params['turn_time']);
		if( $_POST['Turnover']['start_time']){
			$st = strtotime($_POST['Turnover']['start_time']." 00:00:00");
			if($st >= $turn_time){
				$cri->addCondition("created_at >= $st");
				$c->addCondition("created_at < $st");
			}else{
				$cri->addCondition("created_at >=".$turn_time);
				$c->addCondition("created_at < ".$turn_time);
			}
		}else{
			$cri->addCondition("created_at >=".$turn_time);
			$c->addCondition("created_at < ".$turn_time);
		}

		//合计
		$criteria_total = clone $cri;
		$criteria_total->select = "sum(ifnull(fee, 0)) as 'total_fee',turnover_direction";
		$criteria_total->group = "turnover_direction";
		$total = $model->findAll($criteria_total);
		$totaldata = array();
		$totaldata[0] = "合计：";
		foreach ($total as $item){
			if($item->turnover_direction == "need_pay" || $item->turnover_direction == "need_charge"){
				$totaldata[6] -= $item->total_fee;
			}
			if($item->turnover_direction == "payed" || $item->turnover_direction == "charged"){
				$totaldata[7] += $item->total_fee;
			}
		}

		$details = $model->findAll($cri);

		//计算期初余额
		$arr_ye = array();
		$c->select = "title_id, target_id, ifnull(sum(fee), 0) as yue";
		$qichu =  Turnover::model()->find($c);
		$qichu_y = $qichu->yue;
		$qichu_array = array();
		$qichu_array[1] = "结转";
		$qichu_array[8] = numChange(number_format($qichu_y, 2));

		$content = array();
		// if (!$details) return $content;
		array_push($content, $qichu_array);

		foreach ($details as $item) 
		{
			$qichu_y += $item->fee;
			$temp = array(
				$item->created_at > 0 ? date('Y-m-d H:i:s', $item->created_at) : '', 
				$item->title->short_name,
				$item->target->short_name,
				$item->client->short_name,
				numChange(number_format($item->amount, 3)),
				$item->turnover_type == 'GKMX' ? numChange(number_format($item->price * 0.83, 2)).'('.numChange(number_format($item->price, 2)).')' : numChange(number_format($item->price, 2)),
				($item->turnover_direction == "need_pay" || $item->turnover_direction == "need_charge")?-$item->fee:"",
				($item->turnover_direction == "payed" || $item->turnover_direction == "charged")?$item->fee:"",
				//numChange(number_format(abs($item->fee), 2)),
				$qichu_y,
				$item->description,
				Turnover::$turnover_type[$item->turnover_type],
				Turnover::$turnover_direction[$item->turnover_direction], 
				$item->proxyCompany ? $item->proxyCompany->short_name : '',
				$item->is_yidan == 1 ? '乙单' : '',
				Turnover::$bigType[$item->big_type], 
				Turnover::$status[$item->status], 
				$item->owner->nickname, 
				$item->creater->nickname, 
				$item->account->nickname,
			);
			array_push($content, $temp);
		}
		array_push($content, $totaldata);

		return $content;
	}

	/**
	 * 往来汇总 查询所有
	 * @param  array $search 搜索条件
	 * @return array $content 查询结果
	 */
	public static function getAllTotalList($search) 
	{
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";

		$criteria = new CDbCriteria(); //
		$criteria_total = new CDbCriteria(); //总计
		if ($search) 
		{
			$model->attributes = $search;
			$model->start_time = $search['start_time'] ? $search['start_time'] : $model->start_time;
			$model->end_time = $search['end_time'] ? $search['end_time'] : $model->end_time;

			if ($model->big_type) {
				$criteria->addCondition("big_type = :big_type");
				$criteria->params[':big_type'] = $model->big_type;
			}
			if ($model->ownered_by) {
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] = $model->ownered_by;
			}
			if(checkOperation("不看甲乙单")){
				$criteria->addCondition("is_yidan <> 1");
			}else{
				if ($model->is_yidan !== "") {
					if ($model->is_yidan == 1) {
						$criteria->addCondition("is_yidan = 1");
					} else {
						$criteria->addCondition("is_yidan <> 1");
					}
				}
			}			
			if ($model->confirmed !== "") {
				$criteria->addCondition("confirmed = :confirmed");
				$criteria->params[':confirmed'] = $mdoel->confirmed;
			}
		}else{
			if(checkOperation("不看甲乙单")){
				$criteria->addCondition("is_yidan <> 1");
			}
		}
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et) return "起始时间不能大于结束时间！";

		$criteria->addInCondition("status", array('submited', 'accounted'));
		$condition = $criteria->condition ? ' AND '.$criteria->condition : '';
		$condition_total = $condition;
//---------------------------------------------------------------------------------------------------
		//期末余额
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." group by title_id, target_id) fb on fb.title_id = t.title_id and fb.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//期初余额
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where created_at < :start_time".$condition." group by title_id, target_id) ib on ib.title_id = t.title_id and ib.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//日期
		//$criteria->addCondition("created_at between :start_time and :end_time");
		$condition .= " AND (created_at between :start_time and :end_time)";
//---------------------------------------------------------------------------------------------------
		//采购明细		
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_detail' from turnover where turnover_type = 'CGMX'".$condition." group by title_id, target_id) cgmx on cgmx.title_id = t.title_id and cgmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//运费 
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'freight' from turnover where turnover_type = 'FYDJ'".$condition." group by title_id, target_id) fydj on fydj.title_id = t.title_id and fydj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购折让
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_rebate' from turnover where turnover_type = 'CGZR'".$condition." group by title_id, target_id) cgzr on cgzr.title_id = t.title_id and cgzr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购退货
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_return' from turnover where turnover_type = 'CGTH'".$condition." group by title_id, target_id) cgth on cgth.title_id = t.title_id and cgth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘采购
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'tray_purchase' from turnover where turnover_type = 'TPCG'".$condition." group by title_id, target_id) tpcg on tpcg.title_id = t.title_id and tpcg.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘赎回
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'pallet_redemption' from turnover where turnover_type = 'TPSH'".$condition." group by title_id, target_id) tpsh on tpsh.title_id = t.title_id and tpsh.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//付款登记
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'payment' from turnover where turnover_type = 'FKDJ'".$condition." group by title_id, target_id) fkdj on fkdj.title_id = t.title_id and fkdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售明细
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_detail',ifnull(sum(amount), 0) as 'sales_amount' from turnover where turnover_type = 'XSMX'".$condition." group by title_id, target_id) xsmx on xsmx.title_id = t.title_id and xsmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售折让
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition." group by title_id, target_id) xszr on xszr.title_id = t.title_id and xszr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售退货
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_return',ifnull(sum(amount), 0) as 'sales_return_amount' from turnover where turnover_type = 'XSTH'".$condition." group by title_id, target_id) xsth on xsth.title_id = t.title_id and xsth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//收款登记
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'receivables' from turnover where turnover_type = 'SKDJ'".$condition." group by title_id, target_id) skdj on skdj.title_id = t.title_id and skdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓库返利
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'warehouse_rebate' from turnover where turnover_type = 'CKFL'".$condition." group by title_id, target_id) ckfl on ckfl.title_id = t.title_id and ckfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//钢厂返利
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'mills_rebate' from turnover where turnover_type = 'GCFL'".$condition." group by title_id, target_id) gcfl on gcfl.title_id = t.title_id and gcfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓储费用
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'storage_charge' from turnover where turnover_type = 'CCFY'".$condition." group by title_id, target_id) ccfy on ccfy.title_id = t.title_id and ccfy.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//高开明细
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'gk_detail' from turnover where turnover_type = 'GKMX'".$condition." group by title_id, target_id) gkmx on gkmx.title_id = t.title_id and gkmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已收款
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition." group by title_id, target_id) ac on ac.title_id = t.title_id and ac.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已付款
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition." group by title_id, target_id) ap on ap.title_id = t.title_id and ap.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------

		$criteria->select = "t.title_id, t.target_id, 
			fb.final_balance, 
			ib.initial_balance, 

			cgmx.purchase_detail, 
			fydj.freight, 
			cgzr.purchase_rebate,
			cgth.purchase_return,
			tpcg.tray_purchase, 
			tpsh.pallet_redemption, 
			fkdj.payment, 
			xsmx.sales_detail,
			xsmx.sales_amount,
			xszr.sales_rebate, 
			xsth.sales_return, 
			xsth.sales_return_amount,
			skdj.receivables, 
			ckfl.warehouse_rebate, 
			gcfl.mills_rebate, 
			ccfy.storage_charge, 
			gkmx.gk_detail, 

			ac.already_collection, 
			ap.already_paid
		";
		$criteria->params[':start_time'] = $st;
		$criteria->params[':end_time'] = $et;
		if ($model->title_id) { //公司抬头
			$criteria->addCondition("t.title_id = :title_id");
			$condition_total .= " AND (title_id = :title_id)";

			$criteria->params[':title_id'] = $model->title_id;
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];

			if (!$other) {
				$criteria->addInCondition('t.title_id', array($title_rl_val, $title_cx_val));
				$condition_total .= " AND (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$criteria->addNotInCondition('t.title_id', array($title_cx_val));
				$condition_total .= " AND (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$criteria->addNotInCondition('t.title_id', array($title_rl_val));
				$condition_total .= " AND (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$criteria->addCondition("t.target_id = :target_id");
			$condition_total .= " AND (target_id = :target_id)";

			$criteria->params[':target_id'] = $model->target_id;
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		switch ($_REQUEST['final_balance']) 
		{
			case 'wrong': //非0
				$criteria->addCondition("fb.final_balance <> 0");
				break;
			case 'positive': //大于0
				$criteria->addCondition("fb.final_balance > 0");
				break;
			case 'negative': //小于0
				$criteria->addCondition("fb.final_balance < 0"); 
				break;
			default: break;
		}
		$criteria->group = "t.title_id, t.target_id";
		$criteria->order = "fb.final_balance desc";

	//总计
		// $pages = new CPagination();
		// $pages->itemCount = $model->count($criteria);
		// $pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
		// $pages->applyLimit($criteria);
		
		$details = $model->findAll($criteria);
		$content = array();
		if (!$details) return $content;

		$final_balance = 0;
		$initial_balance = 0;
		$purchase_detail = 0;
		$freight = 0;
		$purchase_rebate = 0;
		$purchase_return = 0;
		$tray_purchase = 0;
		$pallet_redemption = 0;
		$payment = 0;
		$sales_detail = 0;
		$sales_amount = 0;
		$sales_rebate = 0;
		$sales_return = 0;
		$sales_return_amount = 0;
		$receivables = 0;
		$warehouse_rebate = 0;
		$mills_rebate = 0;
		$storage_charge = 0;
		$gk_detail = 0;
		$already_collection = 0;
		$already_paid = 0;

		foreach ($details as $item) {
			$temp = array(
				$item->title->short_name, 
				$item->target->short_name, 
				numChange(number_format($item->final_balance, 2)), 
				numChange(number_format($item->purchase_detail, 2)), 
				numChange(number_format($item->freight, 2)), 
				numChange(number_format($item->purchase_rebate, 2)), 
				numChange(number_format($item->purchase_return, 2)), 
				numChange(number_format($item->tray_purchase, 2)), 
				numChange(number_format($item->pallet_redemption, 2)), 
				numChange(number_format($item->payment, 2)), 
				numChange(number_format($item->sales_detail, 2)), 
				numChange(number_format($item->sales_rebate, 2)), 
				numChange(number_format($item->sales_return, 2)), 
				numChange(number_format($item->sales_amount-$item->sales_return_amount, 2)),
				numChange(number_format($item->receivables, 2)), 
				numChange(number_format($item->warehouse_rebate, 2)), 
				numChange(number_format($item->mills_rebate, 2)), 
				numChange(number_format($item->storage_charge, 2)), 
				numChange(number_format($item->gk_detail, 2)), 
				numChange(number_format($item->already_collection, 2)), 
				numChange(number_format($item->already_paid, 2)), 
				numChange(number_format($item->initial_balance, 2)),
			);

			$final_balance += $item->final_balance ? $item->final_balance : 0;
			$initial_balance += $item->initial_balance ? $item->initial_balance : 0;
			$purchase_detail += $item->purchase_detail ? $item->purchase_detail : 0;
			$freight += $item->freight ? $item->freight : 0;
			$purchase_rebate += $item->purchase_rebate ? $item->purchase_rebate : 0;
			$purchase_return += $item->purchase_return ? $item->purchase_return : 0;
			$tray_purchase += $item->tray_purchase ? $item->tray_purchase : 0;
			$pallet_redemption += $item->pallet_redemption ? $item->pallet_redemption : 0;
			$payment += $item->payment ? $item->payment : 0;
			$sales_detail += $item->sales_detail ? $item->sales_detail : 0;
			$sales_rebate += $item->sales_rebate ? $item->sales_rebate : 0;
			$sales_return += $item->sales_return ? $item->sales_return : 0;
			$sales_amount += $item->sales_amount - $item->sales_return_amount;
			$receivables += $item->receivables ? $item->receivables : 0;
			$warehouse_rebate += $item->warehouse_rebate ? $item->warehouse_rebate : 0;
			$mills_rebate += $item->mills_rebate ? $item->mills_rebate : 0;
			$storage_charge += $item->storage_charge ? $item->storage_charge : 0;
			$gk_detail += $item->gk_detail ? $item->gk_detail : 0;
			$already_collection += $item->already_collection ? $item->already_collection : 0;
			$already_paid += $item->already_paid ? $item->already_paid : 0;

			array_push($content, $temp);
		}
		$totaldata = array('', '', 
			$final_balance, 
			$purchase_detail, 
			$freight, 
			$purchase_rebate, 
			$purchase_return, 
			$tray_purchase, 
			$pallet_redemption, 
			$payment, 
			$sales_detail, 
			$sales_rebate, 
			$sales_return,
			$sales_amount,
			$receivables, 
			$warehouse_rebate, 
			$mills_rebate, 
			$storage_charge, 
			$gk_detail, 
			$already_collection, 
			$already_paid, 
			$initial_balance
		);
		array_push($content, $totaldata);
		
		return $content;
	}




		/**
	 * 往来统计 查询所
	 * @param  array $search 搜索条件
	 * @return array $content 查询结果
	 */
	public static function getAllTotalList_aa($search) 
	{
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";

		$criteria = new CDbCriteria(); //
		$criteria_total = new CDbCriteria(); //总计
		if ($search) 
		{
			$model->attributes = $search;
			$model->start_time = $search['start_time'] ? $search['start_time'] : $model->start_time;
			$model->end_time = $search['end_time'] ? $search['end_time'] : $model->end_time;

			if ($model->big_type) {
				$criteria->addCondition("big_type = :big_type");
				$criteria->params[':big_type'] = $model->big_type;
			}
			if ($model->ownered_by) {
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] = $model->ownered_by;
			}
			if(checkOperation("不看甲乙单")){
				$criteria->addCondition("is_yidan <> 1");
			}else{
				if ($model->is_yidan !== "") {
					if ($model->is_yidan == 1) {
						$criteria->addCondition("is_yidan = 1");
					} else {
						$criteria->addCondition("is_yidan <> 1");
					}
				}
			}
			
			if ($model->confirmed !== "") {
				$criteria->addCondition("confirmed = :confirmed");
				$criteria->params[':confirmed'] = $mdoel->confirmed;
			}
		}else{
			if(checkOperation("不看甲乙单")){
				$criteria->addCondition("is_yidan <> 1");
			}
		}
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et) return "起始时间不能大于结束时间！";

		$criteria->addInCondition("status", array('submited', 'accounted'));
		$condition = $criteria->condition ? ' AND '.$criteria->condition : '';
		$condition_total = $condition;

		$criteria->params[':start_time'] = $st;
		$criteria->params[':end_time'] = $et;
		if ($model->title_id) { //公司抬头
			$criteria->addCondition("t.title_id = :title_id");
			$condition_total .= " AND (title_id = :title_id)";
			$condition.=' and (title_id='.$model->title_id.')';

			$criteria->params[':title_id'] = $model->title_id;
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];

			if (!$other) {
				$criteria->addInCondition('t.title_id', array($title_rl_val, $title_cx_val));
				$condition_total .= " AND (title_id in($title_rl_val, $title_cx_val))";
				$condition.=" and (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$criteria->addNotInCondition('t.title_id', array($title_cx_val));
				$condition_total .= " AND (title_id not in($title_cx_val))";
				$condition.=" and (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$criteria->addNotInCondition('t.title_id', array($title_rl_val));
				$condition_total .= " AND (title_id not in($title_rl_val))";
				$condition.="and (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$criteria->addCondition("t.target_id = :target_id");
			$condition_total .= " AND (target_id = :target_id)";

			$criteria->params[':target_id'] = $model->target_id;
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		switch ($_REQUEST['final_balance']) 
		{
			case 'wrong': //非0
				$criteria->addCondition("fb.final_balance <> 0");
				break;
			case 'positive': //大于0
				$criteria->addCondition("fb.final_balance > 0");
				break;
			case 'negative': //小于0
				$criteria->addCondition("fb.final_balance < 0"); 
				break;
			default: break;
		}
		$criteria->group = "t.target_id";
		$criteria->order = "fb.final_balance desc";

		//---------------------------------------------------------------------------------------------------
		//期末余额
		$criteria->join .= " left join (select target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." group by target_id) fb on  fb.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//期初余额
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where created_at < :start_time".$condition." group by target_id) ib on   ib.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//日期
//		$criteria->addCondition("created_at between :start_time and :end_time");
		$condition .= " AND (created_at between :start_time and :end_time)";
//---------------------------------------------------------------------------------------------------
		//采购明细		
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'purchase_detail' from turnover where turnover_type = 'CGMX'".$condition." group by target_id) cgmx on  cgmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//运费 
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'freight' from turnover where turnover_type = 'FYDJ'".$condition." group by  target_id) fydj on  fydj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购折让
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'purchase_rebate' from turnover where turnover_type = 'CGZR'".$condition." group by  target_id) cgzr on  cgzr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购退货
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'purchase_return' from turnover where turnover_type = 'CGTH'".$condition." group by  target_id) cgth on  cgth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘采购
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'tray_purchase' from turnover where turnover_type = 'TPCG'".$condition." group by  target_id) tpcg on  tpcg.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘赎回
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'pallet_redemption' from turnover where turnover_type = 'TPSH'".$condition." group by  target_id) tpsh on  tpsh.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//付款登记
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'payment' from turnover where turnover_type = 'FKDJ'".$condition." group by  target_id) fkdj on  fkdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售明细
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'sales_detail' from turnover where turnover_type = 'XSMX'".$condition." group by  target_id) xsmx on xsmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售折让
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition." group by  target_id) xszr on  xszr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售退货
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'sales_return' from turnover where turnover_type = 'XSTH'".$condition." group by  target_id) xsth on  xsth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//收款登记
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'receivables' from turnover where turnover_type = 'SKDJ'".$condition." group by target_id) skdj on  skdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓库返利
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'warehouse_rebate' from turnover where turnover_type = 'CKFL'".$condition." group by  target_id) ckfl on  ckfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//钢厂返利
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'mills_rebate' from turnover where turnover_type = 'GCFL'".$condition." group by  target_id) gcfl on  gcfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓储费用
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'storage_charge' from turnover where turnover_type = 'CCFY'".$condition." group by  target_id) ccfy on ccfy.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//高开明细
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'gk_detail' from turnover where turnover_type = 'GKMX'".$condition." group by  target_id) gkmx on  gkmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已收款
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition." group by  target_id) ac on  ac.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已付款
		$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition." group by  target_id) ap on  ap.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------

		$criteria->select = " t.target_id, fb.final_balance, ib.initial_balance, cgmx.purchase_detail, fydj.freight, 
			cgzr.purchase_rebate,cgth.purchase_return,	tpcg.tray_purchase, tpsh.pallet_redemption, fkdj.payment, 
			xsmx.sales_detail, 	xszr.sales_rebate, 	xsth.sales_return, 	skdj.receivables, 	ckfl.warehouse_rebate, 
			gcfl.mills_rebate, 	ccfy.storage_charge, gkmx.gk_detail, 	ac.already_collection, 	ap.already_paid
		";



	//总计
		// $pages = new CPagination();
		// $pages->itemCount = $model->count($criteria);
		// $pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
		// $pages->applyLimit($criteria);
		
		$details = $model->findAll($criteria);
		$content = array();
		if (!$details) return $content;

		$final_balance = 0;
		$initial_balance = 0;
		$purchase_detail = 0;
		$freight = 0;
		$purchase_rebate = 0;
		$purchase_return = 0;
		$tray_purchase = 0;
		$pallet_redemption = 0;
		$payment = 0;
		$sales_detail = 0;
		$sales_rebate = 0;
		$sales_return = 0;
		$receivables = 0;
		$warehouse_rebate = 0;
		$mills_rebate = 0;
		$storage_charge = 0;
		$gk_detail = 0;
		$already_collection = 0;
		$already_paid = 0;

		foreach ($details as $item) {
			$temp = array(
				// $item->title->short_name, 
				$item->target->short_name, 
				numChange(number_format($item->final_balance, 2)), 
				numChange(number_format($item->purchase_detail, 2)), 
				numChange(number_format($item->freight, 2)), 
				numChange(number_format($item->purchase_rebate, 2)), 
				numChange(number_format($item->purchase_return, 2)), 
				numChange(number_format($item->tray_purchase, 2)), 
				numChange(number_format($item->pallet_redemption, 2)), 
				numChange(number_format($item->payment, 2)), 
				numChange(number_format($item->sales_detail, 2)), 
				numChange(number_format($item->sales_rebate, 2)), 
				numChange(number_format($item->sales_return, 2)), 
				numChange(number_format($item->receivables, 2)), 
				numChange(number_format($item->warehouse_rebate, 2)), 
				numChange(number_format($item->mills_rebate, 2)), 
				numChange(number_format($item->storage_charge, 2)), 
				numChange(number_format($item->gk_detail, 2)), 
				numChange(number_format($item->already_collection, 2)), 
				numChange(number_format($item->already_paid, 2)), 
				numChange(number_format($item->initial_balance, 2)),
			);

			$final_balance += $item->final_balance ? $item->final_balance : 0;
			$initial_balance += $item->initial_balance ? $item->initial_balance : 0;
			$purchase_detail += $item->purchase_detail ? $item->purchase_detail : 0;
			$freight += $item->freight ? $item->freight : 0;
			$purchase_rebate += $item->purchase_rebate ? $item->purchase_rebate : 0;
			$purchase_return += $item->purchase_return ? $item->purchase_return : 0;
			$tray_purchase += $item->tray_purchase ? $item->tray_purchase : 0;
			$pallet_redemption += $item->pallet_redemption ? $item->pallet_redemption : 0;
			$payment += $item->payment ? $item->payment : 0;
			$sales_detail += $item->sales_detail ? $item->sales_detail : 0;
			$sales_rebate += $item->sales_rebate ? $item->sales_rebate : 0;
			$sales_return += $item->sales_return ? $item->sales_return : 0;
			$receivables += $item->receivables ? $item->receivables : 0;
			$warehouse_rebate += $item->warehouse_rebate ? $item->warehouse_rebate : 0;
			$mills_rebate += $item->mills_rebate ? $item->mills_rebate : 0;
			$storage_charge += $item->storage_charge ? $item->storage_charge : 0;
			$gk_detail += $item->gk_detail ? $item->gk_detail : 0;
			$already_collection += $item->already_collection ? $item->already_collection : 0;
			$already_paid += $item->already_paid ? $item->already_paid : 0;

			array_push($content, $temp);
		}
		$totaldata = array( '', 
			$final_balance, 
			$purchase_detail, 
			$freight, 
			$purchase_rebate, 
			$purchase_return, 
			$tray_purchase, 
			$pallet_redemption, 
			$payment, 
			$sales_detail, 
			$sales_rebate, 
			$sales_return, 
			$receivables, 
			$warehouse_rebate, 
			$mills_rebate, 
			$storage_charge, 
			$gk_detail, 
			$already_collection, 
			$already_paid, 
			$initial_balance
		);
		array_push($content, $totaldata);
		
		return $content;
	}

	/**
	*往来统计--优化版
	*
	*/
	public static function totalYouWant1()
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
				array('name'=>'结算单位','class' =>"sort-disabled",'width'=>"60px"),
				array('name'=>'期末余额','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				array('name'=>'采购明细','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),
				array('name'=>'运费','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),//
				array('name'=>'采购折让','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
				array('name'=>'采购退货','class' =>"flex-col sort-disabled text-right ",'width'=>"110px"),//
				array('name'=>'托盘采购','class' =>"flex-col sort-disabled text-right ",'width'=>"90px"),//
				array('name'=>'托盘赎回计息','class' =>"flex-col sort-disabled  text-right ",'width'=>"90px"),//
				array('name'=>'付款登记','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),//
				array('name'=>'销售明细','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),//
				array('name'=>'销售折让','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
				array('name'=>'销售退货','class' =>"flex-col sort-disabled  text-right",'width'=>"90px"),//
				array('name'=>'收款登记','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
				array('name'=>'仓库返利','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'钢厂返利','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'仓储费用','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'高开明细','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'期初余额','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//

		);
		$tableData=array();
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '1970-01-01';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";
		$condition='';

		$_POST['Turnover']=updateSearch($_POST['Turnover'],'search_turnyouwant_index');
		if ($_POST['Turnover']) 
		{
			$model->attributes = $_POST['Turnover'];
			if($_POST['Turnover']['start_time'])
			{
				if(strtotime($_POST['Turnover']['start_time'])<Yii::app()->params['turn_time']){
					$model->start_time=date('Y-m-d H:i:s',Yii::app()->params['turn_time']);
				}else{
					$model->start_time = $_POST['Turnover']['start_time'];
				}
			}
			$model->end_time = $_POST['Turnover']['end_time'] ? $_POST['Turnover']['end_time'] : $model->end_time;
			$model->confirmed = $_POST['Turnover']['confirmed'];
			if ($model->big_type) {
				$condition.=' and big_type="'.$model->big_type.'"';
			}
			if ($model->ownered_by) {
				$condition.=' and ownered_by='.$model->ownered_by;
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$user=currentUserId();
					$condition.=' and ownered_by='.$user;
				}
			}
			if(checkOperation("不看甲乙单"))
			{
				$condition.=" and is_yidan<>1";
			}else{
				if ($model->is_yidan !== "") {
					if ($model->is_yidan == 1) {
						$condition.=' and is_yidan=1';
					} else {
						$condition.=' and is_yidan<>1';
					}
				}
			}
			
			if ($model->confirmed !== "") {
				$condition.=' and confirmed='.$model->confirmed;
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$user=currentUserId();
				$condition.=' and ownered_by='.$user;
			}
			if(checkOperation("不看甲乙单"))
			{
				$condition.=" and is_yidan<>1";
			}
		}
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et) 
			return array($model, array(), $pages, array(), "起始时间不能大于结束时间！");
		$condition.=' and status in ("submited","accounted")';
		$more_condition='';
		if ($model->title_id) { //公司抬头
			$condition.=' and (title_id='.$model->title_id.')';
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];

			if (!$other) {
				$condition.=" and (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$condition.=" and (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$condition.="and (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$condition.=" and (target_id=$model->target_id)";
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		$condition_qimo=$condition." and created_at <=$et";
		$condition_qichu=$condition." and created_at <$st";
		$condition.=" AND (created_at  between $st and $et)";		
		$connection = Yii::app()->db;
		$sql_qimo="select target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where 1=1".$condition_qimo." group by target_id";		
		switch ($_REQUEST['final_balance']) 
		{
			case 'wrong': //非0
				$sql_qimo.=' having final_balance<>0';
				break;
			case 'positive': //大于0
				$sql_qimo.=' having final_balance>0';
				break;
			case 'negative': //小于0
				$sql_qimo.=' having final_balance<0';
				break;
			default: break;
		}
		$sql_all=$sql_qimo;
		//求总
		$final_balance = 0;$initial_balance = 0;$purchase_detail = 0;$freight = 0;$purchase_rebate=0;$purchase_return = 0;
		$tray_purchase = 0;	$pallet_redemption = 0;$payment = 0;$sales_detail = 0;$sales_rebate = 0;$sales_return = 0;
		$receivables = 0;$warehouse_rebate = 0;	$mills_rebate = 0;$storage_charge = 0;$gk_detail = 0;$already_collection = 0;$already_paid = 0;

		$targets='(';
		$result_all=$connection->createCommand($sql_all)->queryAll();
		foreach ($result_all as $ea) {
			$final_balance+=$ea['final_balance'];
			$targets.=$ea['target_id'].',';
		}
		$targets.='0)';
		$condition_qichu_all=$condition_qichu.' and target_id in '.$targets;
		
		//期初
		$sql_qichu_all="select target_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where 1=1".$condition_qichu_all;
		$result_qichu_all=$connection->createCommand($sql_qichu_all)->queryRow();
		$initial_balance=$result_qichu_all['initial_balance'];

		$condition_main_all=$condition.' and target_id in '.$targets;
		$sql_main_all='select
					target_id, 
					sum(case turnover_type when "CGMX" then fee end) as purchase_detail, 
					sum(case turnover_type when "FYDJ" then fee end) as freight,
					sum(case turnover_type when "CGZR" then fee end) as purchase_rebate,
					sum(case turnover_type when "CGTH" then fee end) as purchase_return,
					sum(case turnover_type when "TPCG" then fee end) as tray_purchase,
					sum(case turnover_type when "TPSH" then fee end) as pallet_redemption,
					sum(case turnover_type when "FKDJ" then fee end) as payment,
					sum(case turnover_type when "XSMX" then fee end) as sales_detail,
					sum(case turnover_type when "XSZR" then fee end) as sales_rebate,
					sum(case turnover_type when "XSTH" then fee end) as sales_return,
					sum(case turnover_type when "SKDJ" then fee end) as receivables,
					sum(case turnover_type when "CKFL" then fee end) as warehouse_rebate,
					sum(case turnover_type when "GCFL" then fee end) as mills_rebate,
					sum(case turnover_type when "CCFY" then fee end) as storage_charge,
					sum(case turnover_type when "GKMX" then fee end) as gk_detail
 
					from turnover  where 1=1 '.$condition_main_all;
		$result_main_all=$connection->createCommand($sql_main_all)->queryRow();
		$purchase_detail = $result_main_all['purchase_detail'];
		$freight = $result_main_all['freight'];
		$purchase_rebate = $result_main_all['purchase_rebate'];
		$purchase_return = $result_main_all['purchase_return'];
		$tray_purchase = $result_main_all['tray_purchase'];
		$pallet_redemption = $result_main_all['pallet_redemption'];
		$payment = $result_main_all['payment'];
		$sales_detail = $result_main_all['sales_detail'];
		$sales_rebate = $result_main_all['sales_rebate'];
		$sales_return = $result_main_all['sales_return'];
		$receivables = $result_main_all['receivables'];
		$warehouse_rebate = $result_main_all['warehouse_rebate'];
		$mills_rebate = $result_main_all['mills_rebate'];
		$storage_charge = $result_main_all['storage_charge'];
		$gk_detail = $result_main_all['gk_detail'];


		$sql_count='select count(*) as count from  ('.$sql_qimo.') as temp_table1';
        $command = $connection->createCommand($sql_count);
        $result_count = $command->queryRow();

		$pages = new CPagination();
		$pages->itemCount = $result_count['count'];
		$pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
		$sql_qimo.=' order by final_balance desc';
		$sql_qimo.=' limit '.$pages->pageSize*($_REQUEST['page']?($_REQUEST['page']-1):0).','.$pages->pageSize*($_REQUEST['page']?$_REQUEST['page']:1);

		$command_qimo = $connection->createCommand($sql_qimo);
		$result_qimo=$command_qimo->queryAll();

		$da=array();
		$da['data']=array();
		$i=1;
		
		foreach ($result_qimo as $e)
		{
			$condition_qichu_t=$condition_qichu;
			$condition_qichu_t.=" and target_id=".$e['target_id'];
			$sql_qichu="select target_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where 1=1".$condition_qichu_t." group by target_id";
			$result_qichu=$connection->createCommand($sql_qichu)->queryRow();

			$condition_t=$condition;
			$condition_t.=" and target_id=".$e['target_id'];

			$sql='select
					target_id, 
					sum(case turnover_type when "CGMX" then fee end) as purchase_detail, 
					sum(case turnover_type when "FYDJ" then fee end) as freight,
					sum(case turnover_type when "CGZR" then fee end) as purchase_rebate,
					sum(case turnover_type when "CGTH" then fee end) as purchase_return,
					sum(case turnover_type when "TPCG" then fee end) as tray_purchase,
					sum(case turnover_type when "TPSH" then fee end) as pallet_redemption,
					sum(case turnover_type when "FKDJ" then fee end) as payment,
					sum(case turnover_type when "XSMX" then fee end) as sales_detail,
					sum(case turnover_type when "XSZR" then fee end) as sales_rebate,
					sum(case turnover_type when "XSTH" then fee end) as sales_return,
					sum(case turnover_type when "SKDJ" then fee end) as receivables,
					sum(case turnover_type when "CKFL" then fee end) as warehouse_rebate,
					sum(case turnover_type when "GCFL" then fee end) as mills_rebate,
					sum(case turnover_type when "CCFY" then fee end) as storage_charge,
					sum(case turnover_type when "GKMX" then fee end) as gk_detail
 
					from turnover  where 1=1 '.$condition_t.' group by target_id';
			$each=$connection->createCommand($sql)->queryRow();

			$detail_url = Yii::app()->createUrl('turnover/index', array(
					'title_id'=>$model->title_id,
					'target_id' => $e['target_id'],
					'start_time' => $model->start_time,
					'end_time' => $model->end_time,
					'is_yidan' =>  $model->is_yidan,
					'big_type'=>$model->big_type,
					'ownered_by'=>$model->ownered_by,
					'title_rl'=>$_REQUEST['title_rl']?'checked':'',
					'title_cx'=>$_REQUEST['title_cx']?'checked':'',
					'other'=>$_REQUEST['other']?'checked':'',
					'confirmed'=>$model->confirmed,
			));
			$da['data']=array($i,
					'<span title="'.DictCompany::getLongName($e['target_id']).'">'.DictCompany::getName($e['target_id']).'</span>',
					'<a href="'.$detail_url.'">'.number_format($e['final_balance'],2).'</a>',
					number_format($each['purchase_detail'],2),
					number_format($each['freight'],2),
					number_format($each['purchase_rebate'],2),
					number_format($each['purchase_return'],2),
					number_format($each['tray_purchase'],2),
					number_format($each['pallet_redemption'],2),
					number_format($each['payment'],2),
					number_format($each['sales_detail'],2),
					number_format($each['sales_rebate'],2),
					number_format($each['sales_return'],2),
					number_format($each['receivables'],2),
					number_format($each['warehouse_rebate'],2),
					number_format($each['mills_rebate'],2),
					number_format($each['storage_charge'],2),
					number_format($each['gk_detail'],2),
					number_format($result_qichu['initial_balance'],2),
			);
			$da['group']=$i;
			array_push($tableData,$da);
			$i++;		
		}
		$totaldata = array(
			'总计',
			'',
			number_format($final_balance,2), 			
			number_format($purchase_detail,2), 
			number_format($freight,2), 
			number_format($purchase_rebate,2),
			number_format($purchase_return,2),
			number_format($tray_purchase,2), 
			number_format($pallet_redemption,2), 
			number_format($payment,2), 
			number_format($sales_detail,2), 
			number_format($sales_rebate,2), 
			number_format($sales_return,2), 
			number_format($receivables,2), 
			number_format($warehouse_rebate,2), 
			number_format($mills_rebate,2), 
			number_format($storage_charge,2), 
			number_format($gk_detail,2), 
			number_format($initial_balance,2), 
		);
		array_push($tableData,array('data'=>$totaldata,'group'=>'ss'));

		return array($tableHeader, $tableData, $pages, $totaldata, "",$model);
	}

	/**
	 *往来汇总--优化版
	 *
	 */
	public static function totalNew()
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"40px"),
				array('name'=>'公司抬头','class' =>"sort-disabled",'width'=>"60px"),
				array('name'=>'结算单位','class' =>"sort-disabled",'width'=>"60px"),
				array('name'=>'客户','class' =>"sort-disabled",'width'=>"60px"),
				array('name'=>'期末余额','class' =>"sort-disabled text-right",'width'=>"100px"),
				array('name'=>'采购明细','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),
				array('name'=>'运费','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),//
				array('name'=>'采购折让','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
				array('name'=>'采购退货','class' =>"flex-col sort-disabled text-right ",'width'=>"110px"),//
				array('name'=>'托盘采购','class' =>"flex-col sort-disabled text-right ",'width'=>"90px"),//
				array('name'=>'托盘赎回计息','class' =>"flex-col sort-disabled  text-right ",'width'=>"90px"),//
				array('name'=>'付款登记','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),//
				array('name'=>'销售明细','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),//
				array('name'=>'销售折让','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
				array('name'=>'销售退货','class' =>"flex-col sort-disabled  text-right",'width'=>"90px"),//
				array('name'=>'净销售重量','class' =>"flex-col sort-disabled  text-right",'width'=>"110px"),//
				array('name'=>'收款登记','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
				array('name'=>'仓库返利','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'钢厂返利','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'仓储费用','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'高开明细','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'期初余额','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
		);
		$tableData=array();
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '1970-01-01';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";
		$condition='';
	
		$_POST['Turnover']=updateSearch($_POST['Turnover'],'search_totalnew_index');//存储搜索条件
		if ($_POST['Turnover'])
		{
			$model->attributes = $_POST['Turnover'];
			if($_POST['Turnover']['start_time'])
			{
				if(strtotime($_POST['Turnover']['start_time'])<Yii::app()->params['turn_time']){
					$model->start_time=date('Y-m-d H:i:s',Yii::app()->params['turn_time']);
				}else{
					$model->start_time = $_POST['Turnover']['start_time'];
				}
			}
			$model->end_time = $_POST['Turnover']['end_time'] ? $_POST['Turnover']['end_time'] : $model->end_time;
			$model->confirmed = $_POST['Turnover']['confirmed'];
			if ($model->big_type) {
				$condition.=' and big_type="'.$model->big_type.'"';
			}
			if ($model->ownered_by) {
				$condition.=' and ownered_by='.$model->ownered_by;
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$user=currentUserId();
					$condition.=' and ownered_by='.$user;
				}
			}
			if(checkOperation("不看甲乙单"))
			{
				$condition.=" and  is_yidan<>1";
			}else{
				if ($model->is_yidan !== "") {
					if ($model->is_yidan == 1) {
						$condition.=' and is_yidan=1';
					} else {
						$condition.=' and is_yidan<>1';
					}
				}
			}
			
			if ($model->confirmed !== "") {
				$condition.=' and confirmed='.$model->confirmed;
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$user=currentUserId();
				$condition.=' and ownered_by='.$user;
			}
			if(checkOperation("不看甲乙单"))
			{
				$condition.=" and is_yidan<>1";
			}
		}
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et)
			return array($model, array(), $pages, array(), "起始时间不能大于结束时间！");
		$condition.=' and status in ("submited","accounted")';
		$more_condition='';
		if ($model->title_id) { //公司抬头
			$condition.=' and (title_id='.$model->title_id.')';
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];
	
			if (!$other) {
				$condition.=" and (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$condition.=" and (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$condition.="and (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$condition.=" and (target_id=$model->target_id)";
		}
		if($model->client_id) { //客户
			$condition.=" and (client_id=$model->client_id)";
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		$condition_qimo=$condition." and created_at <=$et";
		$condition_qichu=$condition." and created_at <$st";
		$condition.=" AND (created_at  between $st and $et)";
		$connection = Yii::app()->db;
		$sql_qimo="select title_id,target_id,client_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where 1=1".$condition_qimo." group by title_id,target_id,client_id";
		switch ($_REQUEST['final_balance'])
		{
				case 'wrong': //非0
				$sql_qimo.=' having final_balance<>0';
				break;
		case 'positive': //大于0
				$sql_qimo.=' having final_balance>0';
				break;
				case 'negative': //小于0
						$sql_qimo.=' having final_balance<0';
				break;
				default: break;
		}
		$sql_all=$sql_qimo;
		//求总
		$final_balance = 0;$initial_balance = 0;$purchase_detail = 0;$freight = 0;$purchase_rebate=0;$purchase_return = 0;
		$tray_purchase = 0;	$pallet_redemption = 0;$payment = 0;$sales_detail = 0;$sales_rebate = 0;$sales_return = 0;
		$receivables = 0;$warehouse_rebate = 0;	$mills_rebate = 0;$storage_charge = 0;$gk_detail = 0;$already_collection = 0;$already_paid = 0;
		$sales_amount = $return_amount = 0;
		$id_arr = array();
		$targets='(';
		
		$result_all=$connection->createCommand($sql_all)->queryAll();
		foreach ($result_all as $ea) {
			$final_balance+=$ea['final_balance'];
			$targets.=$ea['target_id'].',';
			array_push($id_arr,array($ea['title_id'],$ea['target_id'],$ea['client_id']));
		}
		$targets.='0)';
		$condition_qichu_all=$condition_qichu;
	
		//期初
		$sql_qichu_all="select title_id,target_id,client_id,fee from turnover where 1=1".$condition_qichu_all;
		$result_qichu_all=$connection->createCommand($sql_qichu_all)->queryAll();
		foreach ($result_qichu_all as $k=>$v){
			if(in_array(array($v["title_id"],$v["target_id"],$v["client_id"]),$id_arr)){
				$initial_balance += $v["fee"];
			}
		}
		//$initial_balance=$result_qichu_all['initial_balance'];
	
		//获取合计数据
		$condition_main_all=$condition;
		$sql_main_all='select title_id,target_id,client_id,amount,fee,turnover_type from turnover  where 1=1 '.$condition_main_all;
		
		$result_main_all=$connection->createCommand($sql_main_all)->queryAll();
		foreach ($result_main_all as $key=>$val){
			if(in_array(array($val["title_id"],$val["target_id"],$val["client_id"]),$id_arr)){
				switch ($val["turnover_type"]){
					case "CGMX":
						$purchase_detail += $val["fee"];
						break;
					case "FYDJ":
						$freight += $val["fee"];
						break;
					case "CGZR":
						$purchase_rebate += $val["fee"];
						break;
					case "CGTH":
						$purchase_return += $val["fee"];
						break;
					case "TPCG":
						$tray_purchase += $val["fee"];
						break;
					case "TPSH":
						$pallet_redemption += $val["fee"];
						break;
					case "FKDJ":
						$payment += $val["fee"];
						break;
					case "XSMX":
						$sales_detail += $val["fee"];
						$sales_amount += $val["amount"];
						break;
					case "XSZR":
						$sales_rebate += $val["fee"];
						break;
					case "XSTH":
						$sales_return += $val["fee"];
						$return_amount += $val["amount"];
						break;
					case "SKDJ":
						$receivables += $val["fee"];
						break;
					case "CKFL":
						$warehouse_rebate += $val["fee"];
						break;
					case "GCFL":
						$mills_rebate += $val["fee"];
						break;
					case "CCFY":
						$storage_charge += $val["fee"];
						break;
					case "GKMX":
						$gk_detail += $val["fee"];
						break;
					default:
						break;
				}
			}
		}

		//获取总数，设置分页
		$sql_count='select count(*) as count from  ('.$sql_qimo.') as temp_table1';
		$command = $connection->createCommand($sql_count);
		$result_count = $command->queryRow();
		$pages = new CPagination();
		$pages->itemCount = $result_count['count'];
		$pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
		$sql_qimo.=' order by final_balance desc';
		$sql_qimo.=' limit '.$pages->pageSize*($_REQUEST['page']?($_REQUEST['page']-1):0).','.$pages->pageSize*($_REQUEST['page']?$_REQUEST['page']:1);
	
		$command_qimo = $connection->createCommand($sql_qimo);
		$result_qimo=$command_qimo->queryAll();
	
		$da=array();
		$da['data']=array();
		$i=1;
	
		foreach ($result_qimo as $e)
		{
			$condition_qichu_t=$condition_qichu;
			$condition_qichu_t.=" and title_id={$e['title_id']} and target_id={$e['target_id']} and client_id={$e['client_id']}";
			$sql_qichu="select title_id,target_id,client_id,ifnull(sum(fee), 0) as 'initial_balance' from turnover where 1=1".$condition_qichu_t." group by title_id,target_id,client_id";
			$result_qichu=$connection->createCommand($sql_qichu)->queryRow();
	
			$condition_t=$condition;
			$condition_t.=" and title_id={$e['title_id']} and target_id={$e['target_id']} and client_id={$e['client_id']}";
	
			$sql='select title_id,target_id,client_id,
					sum(case turnover_type when "CGMX" then fee end) as purchase_detail,
					sum(case turnover_type when "FYDJ" then fee end) as freight,
					sum(case turnover_type when "CGZR" then fee end) as purchase_rebate,
					sum(case turnover_type when "CGTH" then fee end) as purchase_return,
					sum(case turnover_type when "TPCG" then fee end) as tray_purchase,
					sum(case turnover_type when "TPSH" then fee end) as pallet_redemption,
					sum(case turnover_type when "FKDJ" then fee end) as payment,
					sum(case turnover_type when "XSMX" then fee end) as sales_detail,
					sum(case turnover_type when "XSMX" then amount end) as sales_amount,
					sum(case turnover_type when "XSZR" then fee end) as sales_rebate,
					sum(case turnover_type when "XSTH" then fee end) as sales_return,
					sum(case turnover_type when "XSTH" then amount end) as return_amount,
					sum(case turnover_type when "SKDJ" then fee end) as receivables,
					sum(case turnover_type when "CKFL" then fee end) as warehouse_rebate,
					sum(case turnover_type when "GCFL" then fee end) as mills_rebate,
					sum(case turnover_type when "CCFY" then fee end) as storage_charge,
					sum(case turnover_type when "GKMX" then fee end) as gk_detail
					from turnover  where 1=1 '.$condition_t.' group by title_id,target_id,client_id';
			$each=$connection->createCommand($sql)->queryRow();
	
			$detail_url = Yii::app()->createUrl('turnover/index', array(
					'title_id'=>$model->title_id,
					'target_id' => $e['target_id'],
					'client_id' => $e['client_id'],
					'start_time' => $model->start_time,
					'end_time' => $model->end_time,
					'is_yidan' =>  $model->is_yidan,
					'big_type'=>$model->big_type,
					'ownered_by'=>$model->ownered_by,
					'title_rl'=>$_REQUEST['title_rl']?'checked':'',
					'title_cx'=>$_REQUEST['title_cx']?'checked':'',
					'other'=>$_REQUEST['other']?'checked':'',
					'confirmed'=>$model->confirmed,
			));
			$da['data']=array($i,
					'<span">'.DictTitle::getName($e['title_id']).'</span>',
					'<span title="'.DictCompany::getLongName($e['target_id']).'">'.DictCompany::getName($e['target_id']).'</span>',
					'<span title="'.DictCompany::getLongName($e['client_id']).'">'.DictCompany::getName($e['client_id']).'</span>',
					'<a href="'.$detail_url.'">'.number_format($e['final_balance'],2).'</a>',
					number_format($each['purchase_detail'],2),
					number_format($each['freight'],2),
					number_format($each['purchase_rebate'],2),
					number_format($each['purchase_return'],2),
					number_format($each['tray_purchase'],2),
					number_format($each['pallet_redemption'],2),
					number_format($each['payment'],2),
					number_format($each['sales_detail'],2),
					number_format($each['sales_rebate'],2),
					number_format($each['sales_return'],2),
					number_format($each['sales_amount']-$each['return_amount'],3),
					number_format($each['receivables'],2),
					number_format($each['warehouse_rebate'],2),
					number_format($each['mills_rebate'],2),
					number_format($each['storage_charge'],2),
					number_format($each['gk_detail'],2),
					number_format($result_qichu['initial_balance'],2),
			);
			$da['group']=$i;
			array_push($tableData,$da);
			$i++;
		}
		$totaldata = array(
				'总计',
				'','','',
				number_format($final_balance,2),
				number_format($purchase_detail,2),
				number_format($freight,2),
				number_format($purchase_rebate,2),
				number_format($purchase_return,2),
				number_format($tray_purchase,2),
				number_format($pallet_redemption,2),
				number_format($payment,2),
				number_format($sales_detail,2),
				number_format($sales_rebate,2),
				number_format($sales_return,2),
				number_format($sales_amount-$return_amount,3),
				number_format($receivables,2),
				number_format($warehouse_rebate,2),
				number_format($mills_rebate,2),
				number_format($storage_charge,2),
				number_format($gk_detail,2),
				number_format($initial_balance,2),
		);
		array_push($tableData,array('data'=>$totaldata,'group'=>'ss'));
		return array($tableHeader, $tableData, $pages, $totaldata, "",$model);
	}

/*
*往来统计导出--优化
*/
	public static function getAllTotalList_you($search) 
	{
		$tableData=array();
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '1970-01-01';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";
		$condition='';
		if ($_POST['Turnover']) 
		{
			$model->attributes = $_POST['Turnover'];
			if($_POST['Turnover']['start_time'])
			{
				if(strtotime($_POST['Turnover']['start_time'])<Yii::app()->params['turn_time']){
					$model->start_time=date('Y-m-d H:i:s',Yii::app()->params['turn_time']);
				}else{
					$model->start_time = $_POST['Turnover']['start_time'];
				}
			}
			$model->end_time = $_POST['Turnover']['end_time'] ? $_POST['Turnover']['end_time'] : $model->end_time;
			$model->confirmed = $_POST['Turnover']['confirmed'];
			if ($model->big_type) {
				$condition.=' and big_type="'.$model->big_type.'"';
			}
			if ($model->ownered_by) {
				$condition.=' and ownered_by='.$model->ownered_by;
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$user=currentUserId();
					$condition.=' and ownered_by='.$user;
				}
			}
			if(checkOperation("不看甲乙单")){
				$condition.=' and is_yidan<>1';
			}else{
				if ($model->is_yidan !== "") {
					if ($model->is_yidan == 1) {
						$conditon.=' and is_yidan=1';
					} else {
						$condition.=' and is_yidan<>1';
					}
				}
			}
			
			if ($model->confirmed !== "") {
				$condition.=' and confirmed='.$model->confirmed;
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$user=currentUserId();
				$condition.=' and ownered_by='.$user;
			}
			if(checkOperation('不看甲乙单')){
				$condition.=' and is_yidan <>1';
			}
		}
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et) 
			return array($model, array(), $pages, array(), "起始时间不能大于结束时间！");
		$condition.=' and status in ("submited","accounted")';
		$more_condition='';
		if ($model->title_id) { //公司抬头
			$condition.=' and (title_id='.$model->title_id.')';
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];

			if (!$other) {
				$condition.=" and (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$condition.=" and (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$condition.="and (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$condition.=" and (target_id=$model->target_id)";
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		$condition_qimo=$condition." and created_at <=$et";
		$condition_qichu=$condition." and created_at <$st";
		$condition.=" AND (created_at  between $st and $et)";		
		$connection = Yii::app()->db;
		$sql_qimo="select target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where 1=1".$condition_qimo." group by target_id";		
		switch ($_REQUEST['final_balance']) 
		{
			case 'wrong': //非0
				$sql_qimo.=' having final_balance<>0';
				break;
			case 'positive': //大于0
				$sql_qimo.=' having final_balance>0';
				break;
			case 'negative': //小于0
				$sql_qimo.=' having final_balance<0';
				break;
			default: break;
		}
		$sql_all=$sql_qimo;
		$final_balance = 0;$initial_balance = 0;$purchase_detail = 0;$freight = 0;$purchase_rebate=0;$purchase_return = 0;
		$tray_purchase = 0;	$pallet_redemption = 0;$payment = 0;$sales_detail = 0;$sales_rebate = 0;$sales_return = 0;
		$receivables = 0;$warehouse_rebate = 0;	$mills_rebate = 0;$storage_charge = 0;$gk_detail = 0;$already_collection = 0;$already_paid = 0;

		$sql_qimo.=' order by final_balance desc';
		$command_qimo = $connection->createCommand($sql_qimo);
		$result_qimo=$command_qimo->queryAll();

		$content=array();
		foreach ($result_qimo as $e)
		{
			$condition_qichu_t=$condition_qichu;
			$condition_qichu_t.=" and target_id=".$e['target_id'];
			$sql_qichu="select target_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where 1=1".$condition_qichu_t." group by target_id";
			$result_qichu=$connection->createCommand($sql_qichu)->queryRow();
			$condition_t=$condition;
			$condition_t.=" and target_id=".$e['target_id'];

			$sql='select
					target_id, 
					sum(case turnover_type when "CGMX" then fee end) as purchase_detail, 
					sum(case turnover_type when "FYDJ" then fee end) as freight,
					sum(case turnover_type when "CGZR" then fee end) as purchase_rebate,
					sum(case turnover_type when "CGTH" then fee end) as purchase_return,
					sum(case turnover_type when "TPCG" then fee end) as tray_purchase,
					sum(case turnover_type when "TPSH" then fee end) as pallet_redemption,
					sum(case turnover_type when "FKDJ" then fee end) as payment,
					sum(case turnover_type when "XSMX" then fee end) as sales_detail,
					sum(case turnover_type when "XSZR" then fee end) as sales_rebate,
					sum(case turnover_type when "XSTH" then fee end) as sales_return,
					sum(case turnover_type when "SKDJ" then fee end) as receivables,
					sum(case turnover_type when "CKFL" then fee end) as warehouse_rebate,
					sum(case turnover_type when "GCFL" then fee end) as mills_rebate,
					sum(case turnover_type when "CCFY" then fee end) as storage_charge,
					sum(case turnover_type when "GKMX" then fee end) as gk_detail
 
					from turnover  where 1=1 '.$condition_t.' group by target_id';
			$each=$connection->createCommand($sql)->queryRow();

			$temp=array(
					DictCompany::getName($e['target_id']),
					$e['final_balance']?$e['final_balance']:0.00,
					$each['purchase_detail']?$each['purchase_detail']:0.00,
					$each['freight']?$each['freight']:0,
					$each['purchase_rebate']?$each['purchase_rebate']:0,
					$each['purchase_return']?$each['purchase_return']:0,
					$each['tray_purchase']?$each['tray_purchase']:0,
					$each['pallet_redemption']?$each['pallet_redemption']:0,
					$each['payment']?$each['payment']:0,
					$each['sales_detail']?$each['sales_detail']:0,
					$each['sales_rebate']?$each['sales_rebate']:0,
					$each['sales_return']?$each['sales_return']:0,
					$each['receivables']?$each['receivables']:0,
					$each['warehouse_rebate']?$each['warehouse_rebate']:0,
					$each['mills_rebate']?$each['mills_rebate']:0,
					$each['storage_charge']?$each['storage_charge']:0,
					$each['gk_detail']?$each['gk_detail']:0,
					$result_qichu['initial_balance']?$result_qichu['initial_balance']:0,
			);
			array_push($content,$temp);

			$final_balance += $e['final_balance']?$e['final_balance']:0;
			$initial_balance += $result_qichu['initial_balance']?$result_qichu['initial_balance']:0;
			$purchase_detail += $each['purchase_detail']?$each['purchase_detail']:0;
			$freight += $each['freight']?$each['freight']:0;
			$purchase_rebate += $each['purchase_rebate'];
			$purchase_return += $each['purchase_return'];
			$tray_purchase += $each['tray_purchase'];
			$pallet_redemption += $each['pallet_redemption'];
			$payment += $each['payment'];
			$sales_detail += $each['sales_detail'];
			$sales_rebate += $each['sales_rebate'];
			$sales_return += $each['sales_return'];
			$receivables += $each['receivables'];
			$warehouse_rebate += $each['warehouse_rebate'];
			$mills_rebate += $each['mills_rebate'];
			$storage_charge += $each['storage_charge'];
			$gk_detail += $each['gk_detail'];
		}
		$totaldata = array(
			'',
			$final_balance, 			
			$purchase_detail, 
			$freight, 
			$purchase_rebate,
			$purchase_return,
			$tray_purchase, 
			$pallet_redemption, 
			$payment, 
			$sales_detail, 
			$sales_rebate, 
			$sales_return, 
			$receivables, 
			$warehouse_rebate, 
			$mills_rebate, 
			$storage_charge, 
			$gk_detail, 
			$initial_balance, 
		);		
		array_push($content, $totaldata);
		
		return $content;
	}

	//往来汇总导出 新的优化
	public static function getNewAllList($search){
		$tableData=array();
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '1970-01-01';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";
		$condition='';
		
		$_POST['Turnover']=updateSearch($_POST['Turnover'],'search_totalnew_index');//存储搜索条件
		if ($_POST['Turnover'])
		{
			$model->attributes = $_POST['Turnover'];
			if($_POST['Turnover']['start_time'])
			{
				if(strtotime($_POST['Turnover']['start_time'])<Yii::app()->params['turn_time']){
					$model->start_time=date('Y-m-d H:i:s',Yii::app()->params['turn_time']);
				}else{
					$model->start_time = $_POST['Turnover']['start_time'];
				}
			}
			$model->end_time = $_POST['Turnover']['end_time'] ? $_POST['Turnover']['end_time'] : $model->end_time;
			$model->confirmed = $_POST['Turnover']['confirmed'];
			if ($model->big_type) {
				$condition.=' and big_type="'.$model->big_type.'"';
			}
			if ($model->ownered_by) {
				$condition.=' and ownered_by='.$model->ownered_by;
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$user=currentUserId();
					$condition.=' and ownered_by='.$user;
				}
			}
			if(checkOperation("不看甲乙单"))
			{
				$condition.="  and is_yidan<>1";
			}else{
				if ($model->is_yidan !== "") {
					if ($model->is_yidan == 1) {
						$condition.=' and is_yidan=1';
					} else {
						$condition.=' and is_yidan<>1';
					}
				}
			}
			
			if ($model->confirmed !== "") {
				$condition.=' and confirmed='.$model->confirmed;
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$user=currentUserId();
				$condition.=' and ownered_by='.$user;
			}
			if(checkOperation("不看甲乙单")){
				$condition.=" and is_yidan<>1";
			}
		}
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et)
			return array($model, array(), $pages, array(), "起始时间不能大于结束时间！");
			$condition.=' and status in ("submited","accounted")';
			$more_condition='';
			if ($model->title_id) { //公司抬头
				$condition.=' and (title_id='.$model->title_id.')';
			}
			//checkbox筛选
			if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
				$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
				$title_rl_val = DictTitle::getTitleId('瑞亮物资');
				$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
				$title_cx_val = DictTitle::getTitleId('乘翔实业');
				$other = $_POST['other'];
		
				if (!$other) {
					$condition.=" and (title_id in($title_rl_val, $title_cx_val))";
				}
				if (!$title_cx) {
					$condition.=" and (title_id not in($title_cx_val))";
				}
				if (!$title_rl) {
					$condition.="and (title_id not in($title_rl_val))";
				}
			}
			if ($model->target_id) { //结算单位
				$condition.=" and (target_id=$model->target_id)";
			}
			if($model->client_id) { //客户
				$condition.=" and (client_id=$model->client_id)";
			}
			$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
			$condition_qimo=$condition." and created_at <=$et";
			$condition_qichu=$condition." and created_at <$st";
			$condition.=" AND (created_at  between $st and $et)";
			$connection = Yii::app()->db;
			$sql_qimo="select title_id,target_id,client_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where 1=1".$condition_qimo." group by title_id,target_id,client_id";
			switch ($_REQUEST['final_balance'])
			{
				case 'wrong': //非0
					$sql_qimo.=' having final_balance<>0';
					break;
				case 'positive': //大于0
					$sql_qimo.=' having final_balance>0';
					break;
				case 'negative': //小于0
					$sql_qimo.=' having final_balance<0';
					break;
				default: break;
			}
			$sql_all=$sql_qimo;
			//求总
			$final_balance = 0;$initial_balance = 0;$purchase_detail = 0;$freight = 0;$purchase_rebate=0;$purchase_return = 0;
			$tray_purchase = 0;	$pallet_redemption = 0;$payment = 0;$sales_detail = 0;$sales_rebate = 0;$sales_return = 0;
			$receivables = 0;$warehouse_rebate = 0;	$mills_rebate = 0;$storage_charge = 0;$gk_detail = 0;$already_collection = 0;$already_paid = 0;
			$sales_amount = $return_amount = 0;
			$id_arr = array();
			$targets='(';
		
			$result_all=$connection->createCommand($sql_all)->queryAll();
			foreach ($result_all as $ea) {
				$final_balance+=$ea['final_balance'];
				$targets.=$ea['target_id'].',';
				array_push($id_arr,array($ea['title_id'],$ea['target_id'],$ea['client_id']));
			}
			$targets.='0)';
			$condition_qichu_all=$condition_qichu;
		
			//期初
			$sql_qichu_all="select title_id,target_id,client_id,fee from turnover where 1=1".$condition_qichu_all;
			$result_qichu_all=$connection->createCommand($sql_qichu_all)->queryAll();
			foreach ($result_qichu_all as $k=>$v){
				if(in_array(array($v["title_id"],$v["target_id"],$v["client_id"]),$id_arr)){
					$initial_balance += $v["fee"];
				}
			}
			//$initial_balance=$result_qichu_all['initial_balance'];
		
			//获取合计数据
			$condition_main_all=$condition;
			$sql_main_all='select title_id,target_id,client_id,amount,fee,turnover_type from turnover  where 1=1 '.$condition_main_all;
		
			$result_main_all=$connection->createCommand($sql_main_all)->queryAll();
			foreach ($result_main_all as $key=>$val){
				if(in_array(array($val["title_id"],$val["target_id"],$val["client_id"]),$id_arr)){
					switch ($val["turnover_type"]){
						case "CGMX":
							$purchase_detail += $val["fee"];
							break;
						case "FYDJ":
							$freight += $val["fee"];
							break;
						case "CGZR":
							$purchase_rebate += $val["fee"];
							break;
						case "CGTH":
							$purchase_return += $val["fee"];
							break;
						case "TPCG":
							$tray_purchase += $val["fee"];
							break;
						case "TPSH":
							$pallet_redemption += $val["fee"];
							break;
						case "FKDJ":
							$payment += $val["fee"];
							break;
						case "XSMX":
							$sales_detail += $val["fee"];
							$sales_amount += $val["amount"];
							break;
						case "XSZR":
							$sales_rebate += $val["fee"];
							break;
						case "XSTH":
							$sales_return += $val["fee"];
							$return_amount += $val["amount"];
							break;
						case "SKDJ":
							$receivables += $val["fee"];
							break;
						case "CKFL":
							$warehouse_rebate += $val["fee"];
							break;
						case "GCFL":
							$mills_rebate += $val["fee"];
							break;
						case "CCFY":
							$storage_charge += $val["fee"];
							break;
						case "GKMX":
							$gk_detail += $val["fee"];
							break;
						default:
							break;
					}
				}
			}
		
			//获取总数，设置分页
// 			$sql_count='select count(*) as count from  ('.$sql_qimo.') as temp_table1';
// 			$command = $connection->createCommand($sql_count);
// 			$result_count = $command->queryRow();
// 			$pages = new CPagination();
// 			$pages->itemCount = $result_count['count'];
// 			$pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
			$sql_qimo.=' order by final_balance desc';
//			$sql_qimo.=' limit '.$pages->pageSize*($_REQUEST['page']?($_REQUEST['page']-1):0).','.$pages->pageSize*($_REQUEST['page']?$_REQUEST['page']:1);
		
			$command_qimo = $connection->createCommand($sql_qimo);
			$result_qimo=$command_qimo->queryAll();
			$da=array();
			$da['data']=array();
			$i=1;
			$content = array();
			foreach ($result_qimo as $e)
			{
				$condition_qichu_t=$condition_qichu;
				$condition_qichu_t.=" and title_id={$e['title_id']} and target_id={$e['target_id']} and client_id={$e['client_id']}";
				$sql_qichu="select title_id,target_id,client_id,ifnull(sum(fee), 0) as 'initial_balance' from turnover where 1=1".$condition_qichu_t." group by title_id,target_id,client_id";
				$result_qichu=$connection->createCommand($sql_qichu)->queryRow();
		
				$condition_t=$condition;
				$condition_t.=" and title_id={$e['title_id']} and target_id={$e['target_id']} and client_id={$e['client_id']}";
		
				$sql='select title_id,target_id,client_id,
					sum(case turnover_type when "CGMX" then fee end) as purchase_detail,
					sum(case turnover_type when "FYDJ" then fee end) as freight,
					sum(case turnover_type when "CGZR" then fee end) as purchase_rebate,
					sum(case turnover_type when "CGTH" then fee end) as purchase_return,
					sum(case turnover_type when "TPCG" then fee end) as tray_purchase,
					sum(case turnover_type when "TPSH" then fee end) as pallet_redemption,
					sum(case turnover_type when "FKDJ" then fee end) as payment,
					sum(case turnover_type when "XSMX" then fee end) as sales_detail,
					sum(case turnover_type when "XSMX" then amount end) as sales_amount,
					sum(case turnover_type when "XSZR" then fee end) as sales_rebate,
					sum(case turnover_type when "XSTH" then fee end) as sales_return,
					sum(case turnover_type when "XSTH" then amount end) as return_amount,
					sum(case turnover_type when "SKDJ" then fee end) as receivables,
					sum(case turnover_type when "CKFL" then fee end) as warehouse_rebate,
					sum(case turnover_type when "GCFL" then fee end) as mills_rebate,
					sum(case turnover_type when "CCFY" then fee end) as storage_charge,
					sum(case turnover_type when "GKMX" then fee end) as gk_detail
					from turnover  where 1=1 '.$condition_t.' group by title_id,target_id,client_id';
				$each=$connection->createCommand($sql)->queryRow();
				$temp=array(
						DictCompany::getName($e['target_id']),
						DictCompany::getName($e['target_id']),
						DictCompany::getName($e['client_id']),
						$e['final_balance']?$e['final_balance']:0.00,
						$each['purchase_detail']?$each['purchase_detail']:0,
						$each['freight']?$each['freight']:0,
						$each['purchase_rebate']?$each['purchase_rebate']:0,
						$each['purchase_return']?$each['purchase_return']:0,
						$each['tray_purchase']?$each['tray_purchase']:0,
						$each['pallet_redemption']?$each['pallet_redemption']:0,
						$each['payment']?$each['payment']:0,
						$each['sales_detail']?$each['sales_detail']:0,
						$each['sales_rebate']?$each['sales_rebate']:0,
						$each['sales_return']?$each['sales_return']:0,
						$each['sales_amount']-$each['return_amount'],
						$each['receivables']?$each['receivables']:0,
						$each['warehouse_rebate']?$each['warehouse_rebate']:0,
						$each['mills_rebate']?$each['mills_rebate']:0,
						$each['storage_charge']?$each['storage_charge']:0,
						$each['gk_detail']?$each['gk_detail']:0,
						$result_qichu['initial_balance']?$result_qichu['initial_balance']:0,
				);
				array_push($content,$temp);
				$i++;
			}
			$totaldata = array(
					'总计',
					'','',
					$final_balance,
					$purchase_detail,
					$freight,
					$purchase_rebate,
					$purchase_return,
					$tray_purchase,
					$pallet_redemption,
					$payment,
					$sales_detail,
					$sales_rebate,
					$sales_return,
					$sales_amount-$return_amount,
					$receivables,
					$warehouse_rebate,
					$mills_rebate,
					$storage_charge,
					$gk_detail,
					$initial_balance,
			);
			array_push($content,$totaldata);
			return  $content;
	}

/*
*
*采购汇总数据
**/

	public static function getPurchaseTotalList() 
	{
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";
		$model->big_type='purchase';

		$criteria = new CDbCriteria(); //
		$criteria_total = new CDbCriteria(); //总计

		$_POST['Turnover']=updateSearch($_POST['Turnover'],'search_turntotal_purchase');
		if ($_POST['Turnover']) 
		{
			$model->attributes = $_POST['Turnover'];
			if($_POST['Turnover']['start_time'])
			{
				if(strtotime($_POST['Turnover']['start_time'])<Yii::app()->params['turn_time']){
					$model->start_time=date('Y-m-d H:i:s',Yii::app()->params['turn_time']);
				}else{
					$model->start_time = $_POST['Turnover']['start_time'];
				}
			}else{
				$model->start_time =$model->start_time;
			}
			// $model->start_time = $_POST['Turnover']['start_time'] ? $_POST['Turnover']['start_time'] : $model->start_time;
			$model->end_time = $_POST['Turnover']['end_time'] ? $_POST['Turnover']['end_time'] : $model->end_time;
			$model->confirmed = $_POST['Turnover']['confirmed'];
			$model->big_type='purchase';
			if ($model->big_type) {
				$criteria->addCondition("big_type = :big_type");
				$criteria->params[':big_type'] = $model->big_type;
			}
			if ($model->ownered_by) {
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] = $model->ownered_by;
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$criteria->addCondition("ownered_by = :ownered_by");
					$criteria->params[':ownered_by'] =currentUserId();
				}
			}
			if ($model->is_yidan !== "") {
				if ($model->is_yidan == 1) {
					$criteria->addCondition("is_yidan = 1");
				} else {
					$criteria->addCondition("is_yidan <> 1");
				} 	
			}
			if ($model->confirmed !== "") {
				$criteria->addCondition("confirmed = :confirmed");
				$criteria->params[':confirmed'] = $model->confirmed;
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] =currentUserId();
			}
		}

		$model->big_type='purchase';
		if ($model->big_type) {
			$criteria->addCondition("big_type = :big_type");
			$criteria->params[':big_type'] = $model->big_type;
		}

		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et) 
			return array($model, array(), $pages, array(), "起始时间不能大于结束时间！");

		$criteria->addInCondition("status", array('submited', 'accounted'));
		$condition = $criteria->condition ? ' AND '.$criteria->condition : '';
		$condition_total = $condition;
//---------------------------------------------------------------------------------------------------
		//期末余额
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." group by title_id, target_id) fb on fb.title_id = t.title_id and fb.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//期初余额
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where created_at < :start_time".$condition." group by title_id, target_id) ib on ib.title_id = t.title_id and ib.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//日期
//		$criteria->addCondition("created_at between :start_time and :end_time");
		$condition .= " AND (created_at between :start_time and :end_time)";
//---------------------------------------------------------------------------------------------------
		//采购明细		
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_detail' from turnover where turnover_type = 'CGMX'".$condition." group by title_id, target_id) cgmx on cgmx.title_id = t.title_id and cgmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//运费 
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'freight' from turnover where turnover_type = 'FYDJ'".$condition." group by title_id, target_id) fydj on fydj.title_id = t.title_id and fydj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购折让
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_rebate' from turnover where turnover_type = 'CGZR'".$condition." group by title_id, target_id) cgzr on cgzr.title_id = t.title_id and cgzr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购退货
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_return' from turnover where turnover_type = 'CGTH'".$condition." group by title_id, target_id) cgth on cgth.title_id = t.title_id and cgth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘采购
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'tray_purchase' from turnover where turnover_type = 'TPCG'".$condition." group by title_id, target_id) tpcg on tpcg.title_id = t.title_id and tpcg.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘赎回
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'pallet_redemption' from turnover where turnover_type = 'TPSH'".$condition." group by title_id, target_id) tpsh on tpsh.title_id = t.title_id and tpsh.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//付款登记
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'payment' from turnover where turnover_type = 'FKDJ'".$condition." group by title_id, target_id) fkdj on fkdj.title_id = t.title_id and fkdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售明细
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_detail',ifnull(sum(amount), 0) as 'sales_amount' from turnover where turnover_type = 'XSMX'".$condition." group by title_id, target_id) xsmx on xsmx.title_id = t.title_id and xsmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售折让
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition." group by title_id, target_id) xszr on xszr.title_id = t.title_id and xszr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售退货
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_return',ifnull(sum(amount), 0) as 'sales_return_amount' from turnover where turnover_type = 'XSTH'".$condition." group by title_id, target_id) xsth on xsth.title_id = t.title_id and xsth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//收款登记
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'receivables' from turnover where turnover_type = 'SKDJ'".$condition." group by title_id, target_id) skdj on skdj.title_id = t.title_id and skdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓库返利
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'warehouse_rebate' from turnover where turnover_type = 'CKFL'".$condition." group by title_id, target_id) ckfl on ckfl.title_id = t.title_id and ckfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//钢厂返利
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'mills_rebate' from turnover where turnover_type = 'GCFL'".$condition." group by title_id, target_id) gcfl on gcfl.title_id = t.title_id and gcfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓储费用
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'storage_charge' from turnover where turnover_type = 'CCFY'".$condition." group by title_id, target_id) ccfy on ccfy.title_id = t.title_id and ccfy.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//高开明细
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'gk_detail' from turnover where turnover_type = 'GKMX'".$condition." group by title_id, target_id) gkmx on gkmx.title_id = t.title_id and gkmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已收款
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition." group by title_id, target_id) ac on ac.title_id = t.title_id and ac.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已付款
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition." group by title_id, target_id) ap on ap.title_id = t.title_id and ap.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------

		$criteria->select = "t.title_id, t.target_id, 
			fb.final_balance, 
			ib.initial_balance, 

			cgmx.purchase_detail, 
			fydj.freight, 
			cgzr.purchase_rebate,
			cgth.purchase_return,
			tpcg.tray_purchase, 
			tpsh.pallet_redemption, 
			ckfl.warehouse_rebate, 
			gcfl.mills_rebate, 
			ccfy.storage_charge, 

			ac.already_collection, 
			ap.already_paid
		";
		$criteria->params[':start_time'] = $st;
		$criteria->params[':end_time'] = $et;
		if ($model->title_id) { //公司抬头
			$criteria->addCondition("t.title_id = :title_id");
			$condition_total .= " AND (title_id = :title_id)";

			$criteria->params[':title_id'] = $model->title_id;
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];

			if (!$other) {
				$criteria->addInCondition('t.title_id', array($title_rl_val, $title_cx_val));
				$condition_total .= " AND (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$criteria->addNotInCondition('t.title_id', array($title_cx_val));
				$condition_total .= " AND (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$criteria->addNotInCondition('t.title_id', array($title_rl_val));
				$condition_total .= " AND (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$criteria->addCondition("t.target_id = :target_id");
			$condition_total .= " AND (target_id = :target_id)";
			$criteria->params[':target_id'] = $model->target_id;
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		switch ($_REQUEST['final_balance']) 
		{
			case 'wrong': //非0
				$criteria->addCondition("fb.final_balance <> 0");
				// $criteria_total->addCondition("fb.final_balance <> 0");
				break;
			case 'positive': //大于0
				$criteria->addCondition("fb.final_balance > 0");
				// $criteria_total->addCondition("fb.final_balance > 0");
				break;
			case 'negative': //小于0
				$criteria->addCondition("fb.final_balance < 0"); 
				// $criteria_total->addCondition("fb.final_balance < 0"); 
				break;
			default: break;
		}
		$criteria->group = "t.title_id, t.target_id";
		$criteria->order = "fb.final_balance desc";
		$total_items = $model->findAll($criteria);
		
		$final_balance = 0;
		$initial_balance = 0;
		$purchase_detail = 0;
		$freight = 0;
		$purchase_rebate = 0;
		$purchase_return = 0;
		$tray_purchase = 0;
		$pallet_redemption = 0;
		$payment = 0;
		$sales_detail = 0;
		$sales_amount = 0;
		$sales_rebate = 0;
		$sales_return = 0;
		$sales_return_amount = 0;
		$receivables = 0;
		$warehouse_rebate = 0;
		$mills_rebate = 0;
		$storage_charge = 0;
		$gk_detail = 0;
		$already_collection = 0;
		$already_paid = 0;

		foreach ($total_items as $total_item) {
			$final_balance += $total_item->final_balance ? $total_item->final_balance : 0;
			$initial_balance += $total_item->initial_balance ? $total_item->initial_balance : 0;
			$purchase_detail += $total_item->purchase_detail ? $total_item->purchase_detail : 0;
			$freight += $total_item->freight ? $total_item->freight : 0;
			$purchase_rebate += $total_item->purchase_rebate ? $total_item->purchase_rebate : 0;
			$purchase_return += $total_item->purchase_return ? $total_item->purchase_return : 0;
			$tray_purchase += $total_item->tray_purchase ? $total_item->tray_purchase : 0;
			$pallet_redemption += $total_item->pallet_redemption ? $total_item->pallet_redemption : 0;
			$payment += $total_item->payment ? $total_item->payment : 0;
			$sales_detail += $total_item->sales_detail ? $total_item->sales_detail : 0;
			$sales_amount += $total_item->sales_amount ? $total_item->sales_amount : 0;
			$sales_rebate += $total_item->sales_rebate ? $total_item->sales_rebate : 0;
			$sales_return += $total_item->sales_return ? $total_item->sales_return : 0;
			$sales_return_amount += $total_item->sales_return_amount ? $total_item->sales_return_amount : 0;
			$receivables += $total_item->receivables ? $total_item->receivables : 0;
			$warehouse_rebate += $total_item->warehouse_rebate ? $total_item->warehouse_rebate : 0;
			$mills_rebate += $total_item->mills_rebate ? $total_item->mills_rebate : 0;
			$storage_charge += $total_item->storage_charge ? $total_item->storage_charge : 0;
			$gk_detail += $total_item->gk_detail ? $total_item->gk_detail : 0;
			$already_collection += $total_item->already_collection ? $total_item->already_collection : 0;
			$already_paid += $total_item->already_paid ? $total_item->already_paid : 0;
		}

		$totaldata = (Object)array(
			'final_balance' => $final_balance, 
			'initial_balance' => $initial_balance, 
			'purchase_detail' => $purchase_detail, 
			'freight' => $freight, 
			'purchase_rebate' => $purchase_rebate,
			'purchase_return' => $purchase_return,
			'tray_purchase' => $tray_purchase, 
			'pallet_redemption' => $pallet_redemption, 
			'payment' => $payment, 
			'sales_detail' => $sales_detail, 
			'sales_amount' => $sales_amount,
			'sales_rebate' => $sales_rebate, 
			'sales_return' => $sales_return,
			'sales_return_amount' => $sales_return_amount,
			'receivables' => $receivables, 
			'warehouse_rebate' => $warehouse_rebate, 
			'mills_rebate' => $mills_rebate, 
			'storage_charge' => $storage_charge, 
			'gk_detail' => $gk_detail, 
			'already_collection' => $already_collection, 
			'already_paid' => $already_paid, 
		);

		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);

		$items = $model->findAll($criteria);
		return array($model, $items, $pages, $totaldata, "");
	}	




/*
*销售往来汇总
*/
	public static function getSaleTotalList() 
	{
		$model = new Turnover();
		$model->start_time = Yii::app()->params['turn_time'] ? date('Y-m-d H:i:s', Yii::app()->params['turn_time']) : '';
		$model->end_time = date('Y-m-d');
		$model->confirmed = "";
		$model->is_yidan = "";
		$model->status = "";
		$model->big_type='sales';

		$criteria = new CDbCriteria(); //
		$criteria_total = new CDbCriteria(); //总计
		$_POST['Turnover']=updateSearch($_POST['Turnover'],'search_turntotal_sales');
		if ($_POST['Turnover']) 
		{
			$model->attributes = $_POST['Turnover'];
			if($_POST['Turnover']['start_time'])
			{
				if(strtotime($_POST['Turnover']['start_time'])<Yii::app()->params['turn_time']){
					$model->start_time=date('Y-m-d H:i:s',Yii::app()->params['turn_time']);
				}else{
					$model->start_time = $_POST['Turnover']['start_time'];
				}
			}else{
				$model->start_time =$model->start_time;
			}
			// $model->start_time = $_POST['Turnover']['start_time'] ? $_POST['Turnover']['start_time'] : $model->start_time;
			$model->end_time = $_POST['Turnover']['end_time'] ? $_POST['Turnover']['end_time'] : $model->end_time;
			$model->confirmed = $_POST['Turnover']['confirmed'];
			$model->big_type='sales';
			if ($model->big_type) {
				$criteria->addCondition("big_type = :big_type");
				$criteria->params[':big_type'] = 'sales';
			}
			if ($model->ownered_by) {
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] = $model->ownered_by;
			}else{
				if(!checkOperation('全部往来汇总'))
				{
					$criteria->addCondition("ownered_by = :ownered_by");
					$criteria->params[':ownered_by'] =currentUserId();
				}
			}
			if ($model->is_yidan !== "") {
				if ($model->is_yidan == 1) {
					$criteria->addCondition("is_yidan = 1");
				} else {
					$criteria->addCondition("is_yidan <> 1");
				} 	
			}
			if ($model->confirmed !== "") {
				$criteria->addCondition("confirmed = :confirmed");
				$criteria->params[':confirmed'] = $model->confirmed;
			}
		}else{
			if(!checkOperation('全部往来汇总'))
			{
				$criteria->addCondition("ownered_by = :ownered_by");
				$criteria->params[':ownered_by'] =currentUserId();
			}
		}
		$criteria->addCondition("big_type = :big_type");
		$criteria->params[':big_type'] = 'sales';
		$st = strtotime($model->start_time);
		$et = strtotime($model->end_time.' 23:59:59');
		if ($st > $et) 
			return array($model, array(), $pages, array(), "起始时间不能大于结束时间！");

		$criteria->addInCondition("status", array('submited', 'accounted'));
		$condition = $criteria->condition ? ' AND '.$criteria->condition : '';
		$condition_total = $condition;
//---------------------------------------------------------------------------------------------------
		//期末余额
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." group by title_id, target_id) fb on fb.title_id = t.title_id and fb.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//期初余额
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'initial_balance' from turnover where created_at < :start_time".$condition." group by title_id, target_id) ib on ib.title_id = t.title_id and ib.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//日期
//		$criteria->addCondition("created_at between :start_time and :end_time");
		$condition .= " AND (created_at between :start_time and :end_time)";
//---------------------------------------------------------------------------------------------------
		//采购明细		
		// $criteria->join .= " left/ join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_detail' from turnover where turnover_type = 'CGMX'".$condition." group by title_id, target_id) cgmx on cgmx.title_id = t.title_id and cgmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//运费 
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'freight' from turnover where turnover_type = 'FYDJ'".$condition." group by title_id, target_id) fydj on fydj.title_id = t.title_id and fydj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购折让
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_rebate' from turnover where turnover_type = 'CGZR'".$condition." group by title_id, target_id) cgzr on cgzr.title_id = t.title_id and cgzr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//采购退货
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'purchase_return' from turnover where turnover_type = 'CGTH'".$condition." group by title_id, target_id) cgth on cgth.title_id = t.title_id and cgth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘采购
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'tray_purchase' from turnover where turnover_type = 'TPCG'".$condition." group by title_id, target_id) tpcg on tpcg.title_id = t.title_id and tpcg.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//托盘赎回
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'pallet_redemption' from turnover where turnover_type = 'TPSH'".$condition." group by title_id, target_id) tpsh on tpsh.title_id = t.title_id and tpsh.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//付款登记
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'payment' from turnover where turnover_type = 'FKDJ'".$condition." group by title_id, target_id) fkdj on fkdj.title_id = t.title_id and fkdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售明细
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_detail',ifnull(sum(amount), 0) as 'sales_amount' from turnover where turnover_type = 'XSMX'".$condition." group by title_id, target_id) xsmx on xsmx.title_id = t.title_id and xsmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售折让
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition." group by title_id, target_id) xszr on xszr.title_id = t.title_id and xszr.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//销售退货
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'sales_return',ifnull(sum(amount), 0) as 'sales_return_amount' from turnover where turnover_type = 'XSTH'".$condition." group by title_id, target_id) xsth on xsth.title_id = t.title_id and xsth.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//收款登记
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'receivables' from turnover where turnover_type = 'SKDJ'".$condition." group by title_id, target_id) skdj on skdj.title_id = t.title_id and skdj.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓库返利
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'warehouse_rebate' from turnover where turnover_type = 'CKFL'".$condition." group by title_id, target_id) ckfl on ckfl.title_id = t.title_id and ckfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//钢厂返利
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'mills_rebate' from turnover where turnover_type = 'GCFL'".$condition." group by title_id, target_id) gcfl on gcfl.title_id = t.title_id and gcfl.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//仓储费用
		// $criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'storage_charge' from turnover where turnover_type = 'CCFY'".$condition." group by title_id, target_id) ccfy on ccfy.title_id = t.title_id and ccfy.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//高开明细
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'gk_detail' from turnover where turnover_type = 'GKMX'".$condition." group by title_id, target_id) gkmx on gkmx.title_id = t.title_id and gkmx.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已收款
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition." group by title_id, target_id) ac on ac.title_id = t.title_id and ac.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------
		//已付款
		$criteria->join .= " left join (select title_id, target_id, ifnull(sum(fee), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition." group by title_id, target_id) ap on ap.title_id = t.title_id and ap.target_id = t.target_id";
//---------------------------------------------------------------------------------------------------

		$criteria->select = "t.title_id, t.target_id, 
			fb.final_balance, 
			ib.initial_balance, 

			xsmx.sales_detail,
			xsmx.sales_amount,
			xszr.sales_rebate, 
			xsth.sales_return, 
			xsth.sales_return_amount, 
			skdj.receivables, 
			gkmx.gk_detail, 

			ac.already_collection, 
			ap.already_paid
		";
		$criteria->params[':start_time'] = $st;
		$criteria->params[':end_time'] = $et;
		if ($model->title_id) { //公司抬头
			$criteria->addCondition("t.title_id = :title_id");
			$condition_total .= " AND (title_id = :title_id)";

			$criteria->params[':title_id'] = $model->title_id;
		}
		//checkbox筛选
		if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
			$title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
			$title_rl_val = DictTitle::getTitleId('瑞亮物资');
			$title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
			$title_cx_val = DictTitle::getTitleId('乘翔实业');
			$other = $_POST['other'];

			if (!$other) {
				$criteria->addInCondition('t.title_id', array($title_rl_val, $title_cx_val));
				$condition_total .= " AND (title_id in($title_rl_val, $title_cx_val))";
			}
			if (!$title_cx) {
				$criteria->addNotInCondition('t.title_id', array($title_cx_val));
				$condition_total .= " AND (title_id not in($title_cx_val))";
			}
			if (!$title_rl) {
				$criteria->addNotInCondition('t.title_id', array($title_rl_val));
				$condition_total .= " AND (title_id not in($title_rl_val))";
			}
		}
		if ($model->target_id) { //结算单位
			$criteria->addCondition("t.target_id = :target_id");
			$condition_total .= " AND (target_id = :target_id)";
			$criteria->params[':target_id'] = $model->target_id;
		}
		$_REQUEST['final_balance'] = $_REQUEST['final_balance'] ? $_REQUEST['final_balance'] : 'wrong';
		switch ($_REQUEST['final_balance']) 
		{
			case 'wrong': //非0
				$criteria->addCondition("fb.final_balance <> 0");
				// $criteria_total->addCondition("fb.final_balance <> 0");
				break;
			case 'positive': //大于0
				$criteria->addCondition("fb.final_balance > 0");
				// $criteria_total->addCondition("fb.final_balance > 0");
				break;
			case 'negative': //小于0
				$criteria->addCondition("fb.final_balance < 0"); 
				// $criteria_total->addCondition("fb.final_balance < 0"); 
				break;
			default: break;
		}
		$criteria->group = "t.title_id, t.target_id";
		$criteria->order = "fb.final_balance desc";
		$total_items = $model->findAll($criteria);
		
		$final_balance = 0;
		$initial_balance = 0;
		$purchase_detail = 0;
		$freight = 0;
		$purchase_rebate = 0;
		$purchase_return = 0;
		$tray_purchase = 0;
		$pallet_redemption = 0;
		$payment = 0;
		$sales_detail = 0;
		$sales_amount = 0;
		$sales_rebate = 0;
		$sales_return = 0;
		$sales_return_amount = 0;
		$receivables = 0;
		$warehouse_rebate = 0;
		$mills_rebate = 0;
		$storage_charge = 0;
		$gk_detail = 0;
		$already_collection = 0;
		$already_paid = 0;

		foreach ($total_items as $total_item) {
			$final_balance += $total_item->final_balance ? $total_item->final_balance : 0;
			$initial_balance += $total_item->initial_balance ? $total_item->initial_balance : 0;
			$purchase_detail += $total_item->purchase_detail ? $total_item->purchase_detail : 0;
			$freight += $total_item->freight ? $total_item->freight : 0;
			$purchase_rebate += $total_item->purchase_rebate ? $total_item->purchase_rebate : 0;
			$purchase_return += $total_item->purchase_return ? $total_item->purchase_return : 0;
			$tray_purchase += $total_item->tray_purchase ? $total_item->tray_purchase : 0;
			$pallet_redemption += $total_item->pallet_redemption ? $total_item->pallet_redemption : 0;
			$payment += $total_item->payment ? $total_item->payment : 0;
			$sales_detail += $total_item->sales_detail ? $total_item->sales_detail : 0;
			$sales_amount += $total_item->sales_amount ? $total_item->sales_amount : 0;
			$sales_rebate += $total_item->sales_rebate ? $total_item->sales_rebate : 0;
			$sales_return += $total_item->sales_return ? $total_item->sales_return : 0;
			$sales_return_amount += $total_item->sales_return_amount ? $total_item->sales_return_amount : 0;
			$receivables += $total_item->receivables ? $total_item->receivables : 0;
			$warehouse_rebate += $total_item->warehouse_rebate ? $total_item->warehouse_rebate : 0;
			$mills_rebate += $total_item->mills_rebate ? $total_item->mills_rebate : 0;
			$storage_charge += $total_item->storage_charge ? $total_item->storage_charge : 0;
			$gk_detail += $total_item->gk_detail ? $total_item->gk_detail : 0;
			$already_collection += $total_item->already_collection ? $total_item->already_collection : 0;
			$already_paid += $total_item->already_paid ? $total_item->already_paid : 0;
		}

		$totaldata = (Object)array(
			'final_balance' => $final_balance, 
			'initial_balance' => $initial_balance, 
			'pallet_redemption' => $pallet_redemption, 
			'sales_detail' => $sales_detail, 
			'sales_amount' => $sales_amount,
			'sales_rebate' => $sales_rebate, 
			'sales_return' => $sales_return,
			'sales_return_amount' => $sales_return_amount,
			'receivables' => $receivables, 
			'warehouse_rebate' => $warehouse_rebate, 
			'mills_rebate' => $mills_rebate, 
			'storage_charge' => $storage_charge, 
			'gk_detail' => $gk_detail, 
			'already_collection' => $already_collection, 
			'already_paid' => $already_paid, 
		);

		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = $_COOKIE['to']? intval($_COOKIE['to']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);

		$items = $model->findAll($criteria);
		return array($model, $items, $pages, $totaldata, "");
	}	









}
