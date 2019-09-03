<?php

/**
 * This is the biz model class for table "frm_sales".
 *
 */
class FrmSales extends FrmSalesData
{
	public $total_weight;//汇总重量
	public $total_price;//汇总金额
	public $total_zr;//汇总折让金额
	public $total_gk_weight;//汇总高开重量
	public $total_gk_price;//汇总高开金额
	public $total_dsyf;//汇总代售运费
	public $total_output_amount;//汇总出库件数
	public $total_output_weight;//汇总出库重量
	public $total_bill_weight;//汇总应开票重量
	public $total_bill_price;//汇总应开票金额
	public $total_nobill_weight;//汇总未开票重量
	public $total_nobill_price;//汇总未开票金额
	

	public static $reasons=array(
			1=>'修改客户抬头',
			2=>'修改单价',
			3=>'质量问题退货' ,
			4=>'款未到退单',
			5=>'甲乙单变动',
			-1=>'其他'				
	);//退货和取消审核原因

	public static $sales_type = array(
		'normal' => "库存销售", 
		'xxhj' => "先销后进", 
		'dxxs' => "代销销售"
	);
	

	public static $groupby=array(
		'customer_id'=>'结算单位',
		'ownandcustomer'=>'销售员/结算单位',
		'ownandclient'=>'销售员/客户',
		'owned_by'=>'销售员',
		'brand_id'=>'产地',
		'product_id'=>'品名',
		'texture_id'=>'材质',
		'rank_id'=>'规格',
		'good_id'=>'产地/品名/材质/规格/长度'
	);
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'frmOutputs' => array(self::HAS_MANY, 'FrmOutput', 'frm_sales_id','condition'=>'is_return=0'),
			'frmSalesInvoices' => array(self::HAS_MANY, 'FrmSalesInvoice', 'sales_id'),
			'frmSends' => array(self::HAS_MANY, 'FrmSend', 'frm_sales_id'),
			'salesDetails' => array(self::HAS_MANY, 'SalesDetail', 'frm_sales_id'),
			'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="XSD"'),
			'baseform2'=>array(self::HAS_ONE,'CommonForms','form_id'),
			'dictCompany' => array(self::BELONGS_TO, 'DictCompany', 'customer_id'),
			'client' => array(self::BELONGS_TO, 'DictCompany', 'client_id'),
			'dictTitle' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'company' => array(self::BELONGS_TO, 'DictCompany', 'customer_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'dictOwner'=>array(self::BELONGS_TO,'DictCompany','owner_company_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'companycontact'=>array(self::BELONGS_TO, 'CompanyContact', 'company_contact_id'),
			'team'=>array(self::BELONGS_TO,'Team','team_id'),
			'contact'=>array(self::BELONGS_TO,'CompanyContact','company_contact_id'),
			'highopen'=>array(self::HAS_MANY,'HighOpen','sales_id'),
			'highopenone'=>array(self::HAS_ONE,'HighOpen','sales_id'),
			'dictSupply' => array(self::BELONGS_TO, 'DictCompany', 'supply_id'),
			'baseform3'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform3.form_type="XSD"'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sales_type' => 'Sales Type',
			'title_id' => 'Title',
			'customer_id' => 'Customer',
			'owner_company_id' => 'Owner Company',
			'team_id' => 'Team',
			'is_yidan' => 'Is Yidan',
			'company_contact_id' => 'Company Contact',
			'warehouse_id' => 'Warehouse',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'confirm_amount' => 'Confirm Amount',
			'confirm_weight' => 'Comfirm Weight',
			'confirm_status' => 'Confirm Status',
			'has_bonus_price' => 'Has Bonus Price',
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
		$criteria->compare('sales_type',$this->sales_type,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('owner_company_id',$this->owner_company_id);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('company_contact_id',$this->company_contact_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight);
		$criteria->compare('confirm_amount',$this->confirm_amount);
		$criteria->compare('confirm_weight',$this->comfirm_weight);
		$criteria->compare('confirm_status',$this->confirm_status);
		$criteria->compare('has_bonus_price',$this->has_bonus_price);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSales the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 创建一个销售单
	 */
	public static function createSalse($post = array())
	{
		$post['CommonForms']['form_type']='XSD';
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmSales'];
		$data['detail']=array();
		$has_bonus_price = 0;
		for($i=0;$i<count($post['product']);$i++)
		{
			$temp=array();
			$temp['product_id']=$post['product'][$i];
			$temp['texture_id']=$post['material'][$i];
			$temp['brand_id']=$post['place'][$i];
			$temp['rank_id']=$post['type'][$i];
			$temp['length']=$post['length'][$i];
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
				echo "<script>alert('没有此类商品');</script>";
				return false;
			}
			$temp['amount']=$_POST['td_num'][$i];
			$temp['weight']=numChange($_POST['td_total'][$i]);
			$temp['price']=numChange($_POST['money'][$i]);
			$temp['bonus_price']=$_POST['gaok'][$i];
			if($_POST['gaok'][$i] >0){$has_bonus_price = 1;}
			$temp['card_id']=$_POST['card_id'][$i];
			$temp['total_amount'] = $temp['weight']*$temp['price'];//  numChange($_POST['price'][$i]);
			$temp['gk_id'] = $_POST['gk_id'];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']["has_bonus_price"]=$has_bonus_price;
		$data['main']=(Object)$data['main'];
		return $data;
	}
	
	/**
	 * 自动创建先销后进采购单时获取数据
	 */
	public static function getPurchase($id)
	{
		$baseform = CommonForms::model()->findByPk($id);
		$sales = $baseform->sales;
		$detail = $sales->salesDetails;
		
		$purchase  =array();
		$purchase['common']['form_type'] = 'CGD';
		$purchase['common']['owned_by'] = $baseform->owned_by;
		$purchase['common']['form_time'] = $baseform->form_time;
		$purchase['main']['purchase_type']='xxhj';
		$purchase['main']['supply_id']=$sales->supply_id;
		$purchase['main']['title_id']=$sales->title_id;
		$purchase['main']['team_id']=$sales->team_id;
		$purchase['main']['is_yidan']=$sales->is_yidan;
		$purchase['main']['warehouse_id']=$sales->warehouse_id;
		$purchase['main']['amount']=$sales->amount;
		$purchase['main']['weight']=$sales->weight;
		$purchase['main']['contact_id']=$sales->company_contact_id;
		$purchase['main']['transfer_number']=$sales->travel;
		$purchase['main']['date_reach']=$sales->travel;
		$purchase['main']['frm_contract_id']=$id;
		$purchase['common']=(Object)$purchase['common'];
		$purchase['detail']=array();
		if($detail){
			foreach($detail as $li){
				$pur['product_id']=$li->product_id;
				$pur['texture_id']=$li->texture_id;
				$pur['brand_id']=$li->brand_id;
				$pur['rank_id']=$li->rank_id;
				$pur['length']=$li->length;
				$pur['amount']=$li->amount;
				$pur['weight']=$li->weight;
				$pur['id']=$li->id;
				$pur['price']=numChange($li->fix_price);
				if($pur['price'] == 0){
					return false;
				}
				array_push($purchase['detail'], (Object)$pur);
			}
		}
		$purchase['main']=(Object)$purchase['main'];
		return $purchase;
	}
	
	/**
	 * 创建一个先销后进销售单
	 */
	public static function createSalseXS($post = array())
	{
		$post['CommonForms']['form_type']='XSD';
		$data['main']=$post['FrmSales'];
		$data['common']=(Object)$post['CommonForms'];
		$data['detail']=array();
		
		$has_bonus_price = 0;
		for($i=0;$i<count($post['product']);$i++)
		{
			if($post['place'][$i] < 1){continue;}
			$temp=array();
			$pur = array();
			$temp['product_id']=$post['product'][$i];
			$temp['texture_id']=$post['material'][$i];
			$temp['brand_id']=$post['place'][$i];
			$temp['rank_id']=$post['type'][$i];
			$temp['length']=$post['length'][$i];
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
				echo "<script>alert('没有此类商品');</script>";
				return false;
			}
			$temp['amount']=$_POST['td_num'][$i];
			$temp['weight']=numChange($_POST['td_total'][$i]);
			$temp['price']=numChange($_POST['money'][$i]);
			$temp['bonus_price']=$_POST['gaok'][$i];
			if($_POST['gaok'][$i] >0){$has_bonus_price = 1;}
			$temp['card_id']=$_POST['card_id'][$i];
			$temp['total_amount'] = $temp['weight']*$temp['price'];//numChange($_POST['price'][$i]);
			$temp['fix_price'] = numChange($_POST['fix_price'][$i]);
			$temp['gk_id'] = $_POST['gk_id'];
			array_push($data['detail'], (Object)$temp);
		}
		$warehouse = $data['main']['warehouse_name'];
		$warehouse_id = $data['main']['warehouse_id'];
		if(empty($warehouse_id) || $warehouse_id<1){
			$model = Warehouse::model()->find("name='".$warehouse."'");
			if($model){
				$data['main']['warehouse_id'] = $model->id;
			}else{
				$result = Warehouse::SetWarehouse($warehouse);
				if($result){
					$data['main']['warehouse_id'] = $result->id;
				}
			}
		}
		
		$data['main']["has_bonus_price"]=$has_bonus_price;
		$data['main']=(Object)$data['main'];
		return $data;
	}
	
	/*
	 * 获取销售单列表
	 */
	public static function getFormList($search,$type)
	{
		$tableData=array();
		
		$model = new SalesView();
		$criteria=New CDbCriteria();
		$withArray = array();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('t.form_sn like :contno or comment like :contno');
				$criteria->params[':contno']= "%".trim($search['keywords'])."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('t.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('t.form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('t.main_title_id',$search['title_id'],false);
			}
			if($search['is_yidan'] >= 0 ){
				$criteria->compare('t.is_yidan',$search['is_yidan'],false);
			}
			if($search['client_id']!='0')
			{
				$criteria->compare('t.client_id',$search['client_id'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('t.customer_id',$search['customer_id'],false);
			}
			if($search['sales_type']){
				$criteria->addCondition('t.main_type="'.$search['sales_type'].'"');
			}
			if($search['team']!='0')
			{
				$criteria->compare('t.team_id',$search['team'],false);
			}
			if($search['owned']!='0')
			{
				$criteria->compare('t.owned_by',$search['owned'],false);
			}
			if($search['warehouse']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse'],false);
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
			if($search['length'] >=0)
			{
				$criteria->compare('t.detail_length',$search['length'],false);
			}
		}
		if(empty($search)){
			$criteria->addCondition('form_time >="'.date("Y-m-d").'"');
			$criteria->addCondition('form_time <="'.date("Y-m-d").'"');
		}
		if($type == "checkview"){
			if($search['form_status'])
			{
				if($search['form_status'] == "complete"){
					$criteria->compare('confirm_status',1,false);
				}else if($search['form_status'] == "all"){
					//$criteria->compare('confirm_status',1,false);
					$criteria->compare('is_deleted','0',false);
				}elseif($search['form_status']=='preout'){
					//待出库
					$criteria->compare('form_status', "approve");
					$criteria->compare("confirm_status", 0);
					$criteria->compare("can_push", 1);
				}else{
					$criteria->compare('form_status',$search['form_status'],false);
					$criteria->compare('confirm_status',0,false);
				}
			}else{
				$criteria->compare('confirm_status',0,false);
				$criteria->compare('is_deleted','0',false);
			}
			$criteria->order = "locate(form_status,'submited,approve,unsubmit,delete'),created_at DESC,main_id desc";
		}else if($type=="baseview"){
			if($search['form_status'])
			{
				if($search['form_status'] == "complete"){
					$criteria->compare('confirm_status',1,false);
				}else if($search['form_status'] == "all"){
					//$criteria->compare('confirm_status',1,false);
					$criteria->compare('is_deleted','0',false);
				}elseif($search['form_status']=='preout'){
					//待出库
					$criteria->compare('form_status', "approve");
					$criteria->compare("confirm_status", 0);
					$criteria->compare("can_push", 1);
				}else{
					$criteria->compare('form_status',$search['form_status'],false);
					$criteria->compare('confirm_status',0,false);
				}
			}else{
				$criteria->compare('confirm_status',0,false);
				$criteria->compare('is_deleted','0',false);
			}
			$criteria->order = "locate(form_status,'submited,approve,unsubmit,delete'),t.created_at DESC";
		}else if($type == "outview"){
			if($search['form_status'])
			{
				if($search['form_status'] == "complete"){
					$criteria->compare('confirm_status',1,false);
				}else if($search['form_status'] == "all"){
					$criteria->compare('is_deleted','0',false);
					//$criteria->compare('confirm_status',1,false);
				}elseif($search['form_status']=='preout'){
					//待出库
					$criteria->compare('form_status', "approve");
					$criteria->compare("confirm_status", 0);
					$criteria->compare("can_push", 1);
				}else{
					$criteria->compare('form_status',$search['form_status'],false);
					$criteria->compare('confirm_status',0,false);
				}
			}else{
				$criteria->compare('confirm_status',0,false);
				$criteria->compare('is_deleted','0',false);
			}
			$userId = currentUserId();
			$criteria->addCondition("owned_by=".$userId." or created_by=".$userId);
			$criteria->order = "locate(form_status,'submited,approve,unsubmit,delete'),created_at DESC";
		}else{
			if($search['form_status'])
			{
				if($search['form_status'] == "complete"){
					$criteria->compare('confirm_status',1,false);
				}else if($search['form_status'] == "all"){
					$criteria->compare('is_deleted','0',false);
					//$criteria->compare('confirm_status',1,false);
				}elseif($search['form_status']=='preout'){
					//待出库
					$criteria->compare('form_status', "approve");
					$criteria->compare("confirm_status", 0);
					$criteria->compare("can_push", 1);
				}else{
					$criteria->compare('form_status',$search['form_status'],false);
					$criteria->compare('confirm_status',0,false);
				}
			}else{
				if(!$search['total']){
					$criteria->compare('confirm_status',0,false);
				}
				$criteria->compare('is_deleted','0',false);
			}
			$userId = currentUserId();
			$criteria->addCondition("owned_by=".$userId." or created_by=".$userId);
			$criteria->order = "created_at DESC";
		}
		if($type=='baseview')
		{
			$criteria->addCondition('detail_price>100');
// 			$criteria->addCondition('main_type="normal"');
		}
		$criteria->compare('form_type','XSD',false);
		$c = clone $criteria;
		$c_out = clone $criteria;
		$c_base=clone $criteria;
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['sales_list']) ? intval($_COOKIE['sales_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		if($type == "checkview"){
		$criteria->select = "common_id,main_id,form_status,last_update,can_push,detail_output_amount,main_type,form_sn,is_jxc,has_bonus_price,detail_fee,detail_price,weight,
				form_time,client_name,client_short_name,customer_name,customer_short_name,title_name,owned_by_nickname,warehouse_name,brand_name,product_name,texture_name,
				rank_name,detail_length,detail_amount,is_yidan,detail_output_weight,comment,confirm_status,travel,bonus_price,detail_warehouse_amount,detail_warehouse_weight,
				detail_send_amount,delete_reason,main_title_id,client_id,customer_id";
		}
		$details=$model->findAll($criteria);
		$c->select = "sum(detail_amount) as total_amount,sum(weight) as total_weight,sum(detail_fee) as total_price";
		$alldetail = SalesView::model()->find($c);
		$c_out->addCondition("detail_output_amount>0");
		$c_out->select = "sum(detail_output_amount) as total_amount,sum(detail_output_weight) as total_weight,sum(detail_fee) as total_price";
		$all_out = SalesView::model()->find($c_out);
		$totaldata = array();
		$totaldata["amount"] = $alldetail->total_amount;
		$totaldata["weight"] = $alldetail->total_weight;
		$totaldata["price"] = $alldetail->total_price;
		$totaldata["total_num"] = $alldetail->total_num;
		$totaldata["o_amount"] = $all_out->total_amount;
		$totaldata["o_weight"] = $all_out->total_weight;
		$totaldata["o_total_num"] = $all_out->total_price;
		
		if($type=='baseview')
		{
			$c_base->select='sum(ifnull((t.detail_price-q.price)*t.weight,t.detail_price*t.weight))  as total_price,sum(weight) as weight';
			$c_base->join='left join quoted_detail q on q.brand_std=t.brand_std and q.product_std=t.product_std and q.texture_std=t.texture_std 
					and q.rank_std=t.rank_std and q.type="spread"';
			$base_pri=$model->find($c_base);
			$totaldata['baseprice']=$base_pri->total_price;
			$totaldata['baseweight']=$base_pri->weight;
		}
		
		
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
			$yidan = array("否","是");
			$baseform='';
			$i=1;
			$salesId = '';
			$tui_authority=checkOperation("销售单:推送");
			foreach ($details as $each)
			{
				//操作的链接地址
				$mark=$i;
				if($each->form_sn!=$baseform)
				{
					$baseform=$each->form_sn;
					$i++;
					$price = $each->detail_price * $each->weight;
					
					if($each->form_status=='unsubmit')
					{
						$type_sub="submit";
						$title_sub="提交";
						$img_url = "/images/tijiao.png";
					}elseif($each->form_status=='submited')
					{
						$type_sub="cancle";
						$title_sub="取消提交";
						$img_url = "/images/qxtj.png";
					}	
					if($each->main_type == "xxhj"){
						$edit_url = Yii::app()->createUrl('FrmSales/xsupdate',array('id'=>$each->common_id,"fpage"=>$_REQUEST['page']));
					}else if($each->main_type == "dxxs"){
						$edit_url = Yii::app()->createUrl('FrmSales/dxupdate',array('id'=>$each->common_id,"fpage"=>$_REQUEST['page']));
					}else{
						$edit_url = Yii::app()->createUrl('FrmSales/update',array('id'=>$each->common_id,"fpage"=>$_REQUEST['page']));
					}
					$sub_url =  Yii::app()->createUrl('FrmSales/submit',array('id'=>$each->common_id,'type'=>$type_sub,'last_update'=>$each->last_update));
					$del_url= Yii::app()->createUrl('FrmSales/deleteform',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					$checkP_url=Yii::app()->createUrl('FrmSales/check',array('id'=>$each->common_id,'type'=>'pass','last_update'=>$each->last_update));
					$checkC_url=Yii::app()->createUrl('FrmSales/check',array('id'=>$each->common_id,'type'=>'cancle','last_update'=>$each->last_update));
					$checkCApply_url=Yii::app()->createUrl('FrmSales/applyCancleCheck',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					$checkRView_url=Yii::app()->createUrl('FrmSales/reasonView',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					
					
					$complete_url = Yii::app()->createUrl('FrmSales/complete',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					$cancelcomplete_url = Yii::app()->createUrl('FrmSales/cancelcomplete',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					if($each->is_jxc == 1){
						if($each->detail_send_amount == 0){
							$distribution_url = Yii::app()->createUrl('FrmSend/create',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
						}else{
							$distribution_url = Yii::app()->createUrl('FrmSend/index',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
						}
					}else{
						$distribution_url="javascript:void(0);";
					}
					$checkD_url=Yii::app()->createUrl('FrmSales/check',array('id'=>$each->common_id,'type'=>'deny','last_update'=>$each->last_update));
					$br_url = Yii::app()->createUrl("billRecord/index", array('frm_common_id' => $each->common_id, "fpage"=>$_REQUEST['page']));
					// if($each->can_push){
						if($each->detail_output_amount == 0){
							if($each->main_type == "normal"){
								$output_url = Yii::app()->createUrl('FrmOutput/create',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
							}else if($each->main_type == "xxhj"){
								$output_url = Yii::app()->createUrl('FrmOutput/xscreate',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
							}else{
								$output_url = Yii::app()->createUrl('FrmOutput/dxcreate',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
							}
						}else{
							$output_url = Yii::app()->createUrl('FrmOutput/index',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
						}
					// }else{
						// $output_url = "javascript:void(0);";
					// }
					$sk_url = Yii::app()->createUrl("formBill/create", array('type' => "SKDJ", 'bill_type' => "XSSK", 'common_id' => $each->common_id));	
					$detail_url = Yii::app()->createUrl('FrmSales/detail',array('id'=>$each->common_id,"fpage"=>$_REQUEST['page']));
					$purchase_url = Yii::app()->createUrl('purchase/create',array('comm_id'=>$each->common_id,"type"=>"xxhj"));
// 					$highprice = HighOpen::model()->find("sales_id=".$each->main_id);
					$gk_url = Yii::app()->createUrl("formBill/create", array('type' => "FKDJ", 'bill_type' => "GKFK", 'common_id' => $each->common_id));
					$tuisong_url = Yii::app()->createUrl('FrmSales/push',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					$canceltuisong_url = Yii::app()->createUrl('FrmSales/cancelpush',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					$print_url = Yii::app()->createUrl('print/print', array('id' => $each->common_id));
					$preview_url = Yii::app()->createUrl('FrmSales/preview', array('id' => $each->common_id));
					//$owenrT = OwnerTransfer::model()->find("frm_sales_id=".$each->main_id." and input_status<>2");
// 					if($owenrT){
// 						$trans_url = Yii::app()->createUrl('ownerTransfer/index',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
// 					}else{
// 						$trans_url = Yii::app()->createUrl('ownerTransfer/create',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
// 					}
					//未完成
					$operate='';
					if($each->confirm_status == 0){
						//未提交
						if($each->form_status=='unsubmit'){
							$but_num=0;
							if(checkOperation("销售单:新增")){
								$operate='<div class="cz_list_btn"  canpush="'.$each->can_push.'"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">'
										.'<a class="update_b update_true"  salesid="'.$each->main_id.'" sales_type="'.$each->main_type.'" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a><abc></abc>'
										.'<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span><abc></abc>';
										//.'<span class="delete_form" lastdate="'.$each->last_update.'" title="作废" id="/index.php/FrmSales/deleteform/'.$each->common_id.'" salesid="'.$each->main_id.'"><img src="/images/zuofei.png"></span>'
										//.'<span class="more_but" title="更多"><img src="/images/gengduo.png"></span>'
										//.'<div class="cz_list_btn_more" num="0" style="width:120px">';
								$but_num+=2;
//										.'<span class="submit_form" url="'.$print_url.'" title="打印"><img src="/images/dayin.png"></span>';
								//$operate.='</div></div>';
							}
							if(checkOperation("销售单:作废")){
								$operate.='<span class="delete_form" lastdate="'.$each->last_update.'" title="作废" id="/index.php/FrmSales/deleteform/'.$each->common_id.'" salesid="'.$each->main_id.'"><img src="/images/zuofei.png"></span><abc></abc>';
								$but_num++;
							}
							if (checkOperation("打印")) {
									//$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span>';
									$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span><abc></abc>';
									$but_num++;
							}
						}
						//已提交
						if($each->form_status=='submited'){
							$but_num = 0;
							$operate='<div class="cz_list_btn" canpush="'.$each->can_push.'"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
							if(checkOperation("销售单:新增")){
								$but_num += 2;
								$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a><abc></abc>';
								$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span><abc></abc>';
							}
							
							if(checkOperation("销售单:审核")){
								$operate.='<span class="check_form"  lastdate="'.$each->last_update.'" id="/index.php/FrmSales/check/'.$each->common_id.'" title="审核" str="单号:'.$each->form_sn.',确定审核通过此销售单吗？" onclick="setCheck_unrefresh(this);"><img src="/images/shenhe.png"></span><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("配送单:新增")){
									$operate.='<a class="update_b" href="'.$distribution_url.'" title="'.($each->is_jxc==1?"生成配送":"非接入仓库，请传真配送").'"><span><img src="/images/'.($each->is_jxc==1?"":"un").'psd.png"></span></a><abc></abc>';
									$but_num ++;
							}
							if(checkOperation("打印")){
								//$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
								$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span><abc></abc>';
//								'<span class="submit_form" url="'.''.'" title="打印"><img src="/images/dayin.png"></span><abc></abc>';
								$but_num ++;
							}							
						}
						//已审核
						if($each->form_status=='approve'){
							$but_num = 0;
							$operate='<div class="cz_list_btn" canpush="'.$each->can_push.'"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
							if(checkOperation("销售单:新增")){
								$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("配送单:新增")){
								$operate.='<a class="update_b" href="'.$distribution_url.'" title="'.($each->is_jxc==1?"生成配送":"非接入仓库，请传真配送").'"><span><img src="/images/'.($each->is_jxc==1?"":"un").'psd.png"></span></a><abc></abc>';
								$but_num ++;
							}
							if($type == "outview"){
								if(checkOperation("出库单:新增")){
									// $operate.='<a class="update_b" href="'.$output_url.'" title="'.($each->can_push?"出库":"未推送不能出库").'"><span><img src="/images/'.($each->can_push?"":"un").'chuku.png"></span></a><abc></abc>';

									$operate.='<a class="update_b" href="'.$output_url.'" title="出库"><span><img src="/images/chuku.png"></span></a><abc></abc>';
									$but_num ++;
								}
							}
							$haveReason=CanclecheckRecord::model()->exists('common_id='.$each->common_id);
							if($haveReason)
							{								
								if(checkOperation("销售单:审核")){
									$operate.='<span class="submit_form cancelCheck real_cancle" url="'.$checkC_url.'"  common_id="'.$each->common_id.'" sales_type="'.$each->main_type.'"  title="取消审核" salesid="'.$each->main_id.'"><img src="/images/qxsh.png"></span><abc></abc>';
									$but_num ++;
								}
								$operate.='<span class="review_reason" url="'.$checkRView_url.'"  sales_type="'.$each->main_type.'"   title="查看取消审核原因" salesid="'.$each->main_id.'"><img src="/images/CKQXSHSQ.png"></span><abc></abc>';
								$but_num++;
							}else{
								$operate.='<span class="submit_form cancelCheck" url="'.$checkCApply_url.'"  sales_type="'.$each->main_type.'"  title="申请取消审核" salesid="'.$each->main_id.'"><img src="/images/QXSHSQ.png"></span><abc></abc>';
								$but_num ++;
							}			
							
							if(checkOperation("销售单:推送") && $each->can_push == 0){
								$operate.='<span class="submit_form" url="'.$tuisong_url.'" title="可提货"><img src="/images/kts.png"></span><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("销售单:推送") && $each->can_push == 1){
								$operate.='<span class="submit_form" url="'.$canceltuisong_url.'" title="取消提货"><img src="/images/bkts.png"></span><abc></abc>';
								$but_num ++;
							}
							if($each->main_type == "xxhj"){
								if(checkOperation("采购单:新增")){
									$operate.='<a class="update_b" href="'.$purchase_url.'" title="销售采购"><span><img src="/images/caigou.png"></span></a><abc></abc>';
									$but_num ++;
								}
							}
							if(checkOperation("销售运费:新增")){
								$operate.='<a class="update_b" href="'.$br_url.'" title="运费登记"><span><img src="/images/yfdj.png"></span></a><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("收款登记:新增")){
								$operate.='<a class="update_b" href="'.$sk_url.'" title="收款登记"><span><img src="/images/shoukuai.png"></span></a><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("销售单:完成")){
								$operate.='<span class="submit_form" url="'.$complete_url.'" title="完成"><img src="/images/wancheng.png"></span><abc></abc>';
								$but_num ++;
							}
							if($type != "outview"){
								if(checkOperation("出库单:新增")){
									//$operate.='<a class="update_b" href="'.$output_url.'" title="'.($each->can_push?"出库":"未推送不能出库").'"><span><img src="/images/'.($each->can_push?"":"un").'chuku.png"></span></a><abc></abc>';
									$operate.='<a class="update_b" href="'.$output_url.'" title="出库"><span><img src="/images/chuku.png"></span></a><abc></abc>';
									$but_num ++;
								}
							}
// 							if(checkOperation("转库单:新增") && $each->main_type == "normal"){
// 								$operate.='<a class="update_b" href="'.$trans_url.'" title="出库转库"><span><img src="/images/ckzk.png"></span><abc></abc>';
// 								$but_num ++;
// 							}
							if(checkOperation("打印")){
								//$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
								$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span><abc></abc>';
								// '<span class="submit_form" url="'.''.'" title="打印"><img src="/images/dayin.png"></span><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("销售退货:新增")){
								$operate.='<span class="submit_form" url="'.''.'" title="退货"><img src="/images/tuihuo.png"></span><abc></abc>';
								$but_num ++;
							}
							
							if($each->has_bonus_price == 1){
								if(checkOperation("付款登记:新增")){
									$operate.='<a class="update_b" href="'.$gk_url.'" title="高开付款"><span><img src="/images/gkfk.png"></span></a><abc></abc>';
									$but_num ++;
								}
							}		
						}
					}else{
						$but_num = 0;
						$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
						$operate.='<span class="submit_form" url="'.$cancelcomplete_url.'" title="取消完成"><img src="/images/qxwc.png"></span><abc></abc>';
						$but_num++;
					if (checkOperation("打印")) {
						//$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
						$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span><abc></abc>';
						// '<span class="submit_form" url="'.''.'" title="打印"><img src="/images/dayin.png"></span><abc></abc>';
						$but_num++;
					}
						if(checkOperation("销售运费:新增")){
							$operate.='<a class="update_b" href="'.$br_url.'" title="运费登记"><span><img src="/images/yfdj.png"></span></a><abc></abc>';
							$but_num++;
						}
						if(checkOperation("收款登记:新增")){
							$operate.='<a class="update_b" href="'.$sk_url.'" title="收款登记"><span><img src="/images/shoukuai.png"></span></a><abc></abc>';
							$but_num++;
						}
						if($each->has_bonus_price == 1){
							if(checkOperation("付款登记:新增")){
								$operate.='<a class="update_b" href="'.$gk_url.'" title="高开付款"><span><img src="/images/gkfk.png"></span></a><abc></abc>';
								$but_num ++;
							}
						}						
					}
					if($but_num > 4){
						$one=substr($operate,strpos($operate,'<abc></abc>')+11);
						$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
						$two=substr($one,strpos($one,'<abc></abc>')+11);
						$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
						$three=substr($two,strpos($two,'<abc></abc>')+11);
						$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
						$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
								.'<div class="cz_list_btn_more" style="width:120px" num="0">'.$three;
						$operate.='</div></div>';
					}else{
						$operate.='</div>';
					}
					//往来余额
					if($tui_authority)
					{
						$balance=0;
						$sql="select ifnull(sum(fee),0) as fee from turnover where title_id=".$each->main_title_id." and target_id=$each->customer_id and						
						client_id=$each->client_id and big_type='sales' and status  in ('submited','accounted')  and created_at<".strtotime("+1 day",strtotime(date('Y-m-d')));
						if(!empty($search)&&$search['is_yidan']!='-1'){
							$sql.=" and is_yidan=".intval($search['is_yidan']);
						}
						$balance=Turnover::model()->findBySql($sql);
					}										
				}else{
					$mark='';
					$operate='';
				}
				if($each->detail_fee > 0){
					$price=$each->detail_fee;
				}else{
					$price = $each->detail_price * $each->weight;
				}
				if($each->confirm_status==1){
					$cha = $price - $each->detail_price*$each->detail_output_weight;
				}else{
					$cha = 0;
				}
				if($each->is_jxc==1 &&($each->form_status == "approve" || $each->form_status == "submited")){
					$sendamount = '<a class="a_view" href="'.$distribution_url.'">'.$each->detail_send_amount."/".$each->detail_amount.'</a>';
				}else{
					$sendamount = $each->detail_send_amount."/".$each->detail_amount;
				}
				$text="";
				if($each->travel){
					$authtext = $each->travel;
					$authtext = str_replace(" ",",",$authtext);
					$authtext = str_replace("，",",",$authtext);
					$autharr = explode(",",$authtext);
					$newArr = array();
					foreach($autharr as $k=>$v){
						if(!empty($v)){
							array_push($newArr,$v);
						}
					}
					if(count($newArr) > 1){
						$text .= '<div class="car_no" title="'.$each->travel.'">'.$newArr[0].'...</div>';
					}else{
						$text .= '<div class="car_no" title="'.$each->travel.'">'.$newArr[0].'</div>';
					}
				}
				$status=$each->confirm_status==1?"已完成":('<span class="'.($each->form_status!='approve'?'red':'').'" form_sn="'.$each->form_sn.'">'.$_status[$each->form_status].'</span>');
				if($each->confirm_status==0&&$each->can_push==1&&$each->form_status=='approve')$status='待出库';
				if($type == "outview"){
					$da['data']=array($mark,
							$operate,
							'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$each->form_sn.'</a>',
							$status,//销售单状态
							$each->form_time,
							'<span title="'.$each->client_name.'">'.$each->client_short_name.'</span>',
							'<span title="'.$each->customer_name.'">'.$each->customer_short_name.'</span>',
							$each->title_name,
							$each->owned_by_nickname,
							$each->is_yidan == 1?"是":"",
							$sales_type[$each->main_type],
							$each->warehouse_name,
							$each->brand_name,
							$each->product_name,
							str_replace('E', '<span class="red">E</span>',$each->texture_name),
							$each->rank_name,
							$each->detail_length,							
							//'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_price).'</span>',
							$each->detail_amount,
							number_format($each->weight,3),
							//'<span class="'.($each->is_yidan?'red':'').'">'.number_format($price,2).'</span>',
							//number_format($fee,2),//费用
							$sendamount,
							$each->detail_output_amount."/".$each->detail_amount,
							$each->detail_warehouse_output_amount."/".$each->detail_amount,
							$each->detail_output_amount,//核定数量
							number_format($each->detail_output_weight,3),//核定重量
							$each->detail_warehouse_amount,
							number_format($each->detail_warehouse_weight,3),
							//0,//核定单价
							//'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_output_weight*($each->detail_price),2).'</span>',//核定金额
							//number_format(0,2),//收款金额
							//'<span class="'.($each->is_yidan?'red':'').'">'.number_format($cha,2).'</span>',//收款差额
							//number_format(0,2),//已结算金额
							$each->team_name,//业务组
							$each->created_by_nickname,//创建人
							$each->last_updated_by_nickname,//修改人
							$each->approved_by_nickname,
							$each->approved_at >0?date("Y-m-d",$each->approved_at):'',
							'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment,0,15,"UTF-8").'</span>',
							);
				}else if($type == "checkview"){
					$arr1=array($mark,
							$operate,
							'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$each->form_sn.'</a>',
							$status,
							$each->form_time,
							'<span title="'.$each->client_name.'">'.$each->client_short_name.'</span>',
							'<span title="'.$each->customer_name.'">'.$each->customer_short_name.'</span>',
							$each->title_name,
							);
						$arr2=$tui_authority?array(number_format($balance->fee,2),):array();
						$arr3=array(	
							$each->owned_by_nickname,
//							$sales_type[$each->main_type],
							$each->warehouse_name,
							$each->brand_name,
							$each->product_name,
							str_replace('E', '<span class="red">E</span>',$each->texture_name),
							$each->rank_name,
							$each->detail_length,
							$each->detail_amount,
							number_format($each->weight,3),
							'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_price).'</span>',
							'<span class="'.($each->is_yidan?'red':'').'">'.number_format($price,2).'</span>',
							$each->detail_output_amount,//核定数量
							number_format($each->detail_output_weight,3),//核定重量
							'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_output_weight*$each->detail_price,2).'</span>',//核定金额							
							$each->is_yidan == 1?"是":"",
							number_format($each->bonus_price,2),
							$each->detail_warehouse_amount,
							number_format($each->detail_warehouse_weight,3),
							$text,
							'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment,0,15,"UTF-8").'</span>',
					);
					$da['data']=array_merge($arr1,$arr2,$arr3);
				}elseif($type=="baseview"){
					$price_regulate=QuotedDetail::getSpreadPrice($each->brand_id, $each->product_id, $each->texture_id, $each->rank_id);
					$da['data']=array($mark,
							$operate,
							'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$each->form_sn.'</a>',
							$status,
							$each->form_time,
							'<span title="'.$each->customer_name.'">'.$each->customer_short_name.'</span>',
							$each->title_name,
							$each->owned_by_nickname,
							$each->warehouse_name,
							$each->brand_name,
							$each->product_name,
							str_replace('E', '<span class="red">E</span>',$each->texture_name),
							$each->rank_name,
							$each->detail_length,
							$each->detail_amount,
							number_format($each->weight,3),
							'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_price).'</span>',							
							'<span class="'.($each->is_yidan?'red':'').'">'.number_format($price,2).'</span>',
							'<span class="'.(intval($price_regulate)?'font_blue':($each->is_yidan?'red':'')).'">'.number_format($each->detail_price-$price_regulate).'</span>',
							'<span class="'.(intval($price_regulate)?'font_blue':($each->is_yidan?'red':'')).'">'.number_format(($each->detail_price-$price_regulate)*$each->weight,2).'</span>',
							$each->detail_output_amount,//核定数量
							number_format($each->detail_output_weight,3),//核定重量
							'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_output_weight*$each->detail_price,2).'</span>',//核定金额
							$each->is_yidan == 1?"是":"",
							number_format($each->bonus_price,2),
							$text,
							'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment,0,15,"UTF-8").'</span>',
					);
				}else{
					$da['data']=array($mark,
							$operate,
							'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$each->form_sn.'</a>',
							$status,
							$each->form_time,
							'<span title="'.$each->client_name.'">'.$each->client_short_name.'</span>',
							'<span title="'.$each->customer_name.'">'.$each->customer_short_name.'</span>',
							$each->title_name,
							$each->owned_by_nickname,
//							$sales_type[$each->main_type],
							$each->warehouse_name,
							$each->brand_name,
							$each->product_name,
							str_replace('E', '<span class="red">E</span>',$each->texture_name),
							$each->rank_name,
							$each->detail_length,
							$each->detail_amount,
							number_format($each->weight,3),
							'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_price).'</span>',						
							'<span class="'.($each->is_yidan?'red':'').'">'.number_format($price,2).'</span>',							
							$each->is_yidan == 1?"是":"",
							number_format($each->bonus_price,2),
							//number_format($fee,2),//费用
	// 						$sendamount,
	// 						$each->detail_output_amount."/".$each->detail_amount,
	// 						$each->detail_warehouse_output_amount."/".$each->detail_amount,
 							number_format($each->detail_output_weight,3),//核定重量
// 							//0,//核定单价
								'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_output_weight*$each->detail_price,2).'</span>',//核定金额
// 							//number_format(0,2),//收款金额
// 							//'<span class="'.($each->is_yidan?'red':'').'">'.number_format($cha,2).'</span>',//收款差额
// 							//number_format(0,2),//已结算金额
// 							$each->team_name,//业务组
// 							$each->created_by_nickname,//创建人
// 							$each->last_updated_by_nickname,//修改人
// 							$each->approved_by_nickname,
// 							$each->approved_at >0?date("Y-m-d",$each->approved_at):'',
							$each->detail_warehouse_amount,
							number_format($each->detail_warehouse_weight,3),
							'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment,0,15,"UTF-8").'</span>',
					);
				}
				if($search['form_status'] == "delete"){
					array_push($da['data'],'<span title="'.htmlspecialchars($each->delete_reason).'">'.mb_substr($each->delete_reason,0,15,"UTF-8").'</span>');
				}
				$da['group']=$each->form_sn;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	
	}
	
	/*
	 * 获取导出的销售单数据
	 */
	public static function getAllList($search,$type){
		$tableData=array();
		
		$model = new SalesView();
		$criteria=New CDbCriteria();
		$withArray = array();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('form_sn like :contno or comment like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('main_title_id',$search['title_id'],false);
			}
			if($search['is_yidan'] >= 0 ){
				$criteria->compare('is_yidan',$search['is_yidan'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('customer_id',$search['customer_id'],false);
			}
			if($search['client_id']!='0')
			{
				$criteria->compare('client_id',$search['client_id'],false);
			}
			if($search['sales_type']){
				$criteria->addCondition('main_type="'.$search['sales_type'].'"');
			}
			if($search['team']!='0')
			{
				$criteria->compare('team_id',$search['team'],false);
			}
			if($search['owned']!='0')
			{
				$criteria->compare('owned_by',$search['owned'],false);
			}
			if($search['warehouse']!='0')
			{
				$criteria->compare('warehouse_id',$search['warehouse'],false);
			}
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('product_id',$search['product'],false);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('texture_id',$search['texture'],false);
			}
		}
		if($type == "checkview"||$type=='baseview'){
			if($search['form_status'])
			{
				if($search['form_status'] == "complete"){
					$criteria->compare('confirm_status',1,false);
				}else if($search['form_status'] == "all"){
					$criteria->compare('is_deleted','0',false);
					//$criteria->compare('confirm_status',1,false);
				}else{
					$criteria->compare('form_status',$search['form_status'],false);
					$criteria->compare('confirm_status',0,false);
				}
			}else{
				$criteria->compare('confirm_status',0,false);
				$criteria->compare('is_deleted','0',false);
			}
			$criteria->order = "locate(form_status,'submited,approve,unsubmit,delete'),created_at DESC";
		}else if($type == "outview"){
			if($search['form_status'])
			{
				if($search['form_status'] == "complete"){
					$criteria->compare('confirm_status',1,false);
				}else if($search['form_status'] == "all"){
					$criteria->compare('is_deleted','0',false);
					//$criteria->compare('confirm_status',1,false);
				}else{
					$criteria->compare('form_status',$search['form_status'],false);
					$criteria->compare('confirm_status',0,false);
				}
			}else{
				$criteria->compare('confirm_status',0,false);
				$criteria->compare('is_deleted','0',false);
			}
			$userId = currentUserId();
			$criteria->addCondition("owned_by=".$userId." or created_by=".$userId);
			$criteria->order = "locate(form_status,'submited,approve,unsubmit,delete'),created_at DESC";
		}else{
			if($search['form_status'])
			{
				if($search['form_status'] == "complete"){
					$criteria->compare('confirm_status',1,false);
				}else if($search['form_status'] == "all"){
					$criteria->compare('is_deleted','0',false);
					//$criteria->compare('confirm_status',1,false);
				}else{
					$criteria->compare('form_status',$search['form_status'],false);
					$criteria->compare('confirm_status',0,false);
				}
			}else{
				if(!$search['total']){
					$criteria->compare('confirm_status',0,false);
				}
				$criteria->compare('is_deleted','0',false);
			}
			$userId = currentUserId();
			$criteria->addCondition("owned_by=".$userId." or created_by=".$userId);
			$criteria->order = "created_at DESC";
		}
		if($type=='baseview')
		{
			$criteria->addCondition('detail_price>100');
			$criteria->addCondition('main_type="normal"');
		}
		$criteria->compare('form_type','XSD',false);
		$c = clone $criteria;
		$c_base=clone $criteria;
		$details=$model->findAll($criteria);
		//$c->select = "sum(detail_amount) as total_amount,sum(weight) as total_weight,sum(weight*(detail_price)) as total_price,count(*) as total_num";
		$c->select = "sum(detail_amount) as total_amount,sum(weight) as total_weight,sum(detail_fee) as total_price";
		$alldetail = SalesView::model()->find($c);
		$totaldata = array();
		$totaldata[10] = $alldetail->total_amount;
		$totaldata[12] = $alldetail->total_weight;
		if($type=='baseview')
		{
			$totaldata[15] = $alldetail->total_price;
		}else{
			$totaldata[14] = $alldetail->total_price;
		}		
		//$totaldata["total_num"] = $alldetail->total_num;	
		
		$content = array();
		$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
		$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
		$yidan = array("否","是");
		if($details){
			if($type!='baseview')
			{
				foreach ($details as $each){
					$temp = array();
					$temp[0] = $each->form_sn;
					$temp[1] = $each->form_time;
					$temp[2] = $each->owned_by_nickname;
					$temp[3] = $each->customer_short_name;
					$temp[4] = $each->client_short_name;
					$temp[5] = $each->brand_name;
					$temp[6] = $each->product_name;
					$temp[7] = $each->texture_name;
					$temp[8] = $each->rank_name;
					$temp[9] = $each->detail_length;
					$temp[10] = $each->detail_amount;
					$temp[11] = "件";
					$temp[12] = numChange(number_format($each->weight, 3));
					$temp[13] = numChange(number_format($each->detail_price));					
					$temp[14] = numChange(number_format($each->detail_fee, 2));
					$temp[15] = numChange(number_format($each->detail_output_weight, 3));
					$temp[16] = $each->detail_fee == 1 ? 1 : numChange(number_format($each->detail_output_weight * ($each->detail_price), 2));
					$temp[17] = numChange(number_format($each->detail_output_weight, 3));
					$temp[18] = $each->is_yidan == 1 ? "是" : "";
					$temp[19] = $_status[$each->form_status];
					$temp[20] = $each->confirm_status == 1 ? "已审单" : "未审单";
					// $temp[19] = 0;//费用
					$temp[21] = $each->warehouse_name;
					$temp[22] = $sales_type[$each->main_type];
					$temp[23] = $each->team_name;
					$temp[24] = str_replace('&nbsp', ' ', $each->comment);
					$temp[25] = $each->travel;
					$temp[26] = $each->title_name;
					$temp[27] = $each->created_by_nickname;
					$temp[28] = $each->last_updated_by_nickname;
					$temp[29] = $each->approved_by_nickname;
					$temp[30] = $each->approved_at > 0 ? date("Y-m-d",$each->approved_at) : '';
					if($search['form_status'] == "delete") $temp[31] = str_replace('&nbsp', ' ', $each->delete_reason);
					array_push($content,$temp);
				}
			}else{
				foreach ($details as $each){
					$price_regulate=QuotedDetail::getSpreadPrice($each->brand_id, $each->product_id, $each->texture_id, $each->rank_id);
					$temp = array();
					$temp[0] = $each->form_sn;
					$temp[1] = $each->form_time;
					$temp[2] = $each->owned_by_nickname;
					$temp[3] = $each->customer_short_name;
					$temp[4] = $each->client_short_name;
					$temp[5] = $each->brand_name;
					$temp[6] = $each->product_name;
					$temp[7] = $each->texture_name;
					$temp[8] = $each->rank_name;
					$temp[9] = $each->detail_length;
					$temp[10] = $each->detail_amount;
					$temp[11] = "件";
					$temp[12] = numChange(number_format($each->weight, 3));
					$temp[13] = numChange(number_format($each->detail_price));
					$temp[14] = numChange(number_format($each->detail_price-$price_regulate));
					$temp[15] = numChange(number_format($each->detail_fee, 2));
					$temp[16] = numChange(number_format($each->detail_output_weight, 3));
					$temp[17] = $each->detail_fee == 1 ? 1 : numChange(number_format($each->detail_output_weight * ($each->detail_price), 2));
					$temp[18] = numChange(number_format($each->detail_output_weight, 3));
					$temp[19] = $each->is_yidan == 1 ? "是" : "";
					$temp[20] = $_status[$each->form_status];
					$temp[21] = $each->confirm_status == 1 ? "已审单" : "未审单";
					// $temp[19] = 0;//费用
					$temp[22] = $each->warehouse_name;
					$temp[23] = $sales_type[$each->main_type];
					$temp[24] = $each->team_name;
					$temp[25] = str_replace('&nbsp', ' ', $each->comment);
					$temp[26] = $each->travel;
					$temp[27] = $each->title_name;
					$temp[28] = $each->created_by_nickname;
					$temp[29] = $each->last_updated_by_nickname;
					$temp[30] = $each->approved_by_nickname;
					$temp[31] = $each->approved_at > 0 ? date("Y-m-d",$each->approved_at) : '';
					if($search['form_status'] == "delete") $temp[32] = str_replace('&nbsp', ' ', $each->delete_reason);
					array_push($content,$temp);
				}
			}
			
			array_push($content,$totaldata);
		}
		return $content;
	}
	
	/*
	 * 获取销售单列表，出库单关联销售单
	 */
	public static function getFormSimpleList($search,$type){
		$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
		$tableData=array();
		$model=FrmSales::model()->with(array('baseform'));
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('t.title_id',$search['title_id'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('t.customer_id',$search['customer_id'],false);
			}
				
			if($search['team']!='0')
			{
				$criteria->compare('t.team_id',$search['team'],false);
			}
			if($search['warehouse']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse'],false);
			}
		}
		$criteria->addCondition("t.sales_type = '".$type."'");
		// $criteria->addCondition("t.can_push = 1");
		//$criteria->compare('baseform.form_type','XSD',true);
		$criteria->compare('baseform.is_deleted','0',false);
		$criteria->compare('t.confirm_status','0',false);
		$criteria->compare('baseform.form_status','approve',false);
		$criteria->order = "baseform.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['sales_list']) ? intval($_COOKIE['sales_list']) :Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=FrmSales::model()->with(array('baseform'=>array('order'=>'baseform.created_at DESC')))->findAll($criteria);
		//$details=$model->findAll($criteria);
		if($details){
			$da=array();
			$da['data']=array();
			$i=1;
			foreach ($details as $each)
			{
				$baseform=$each->baseform;
				$operate='<input type="radio" name="selected_contract"  class="selected_contract"  value="'.$each->id.'" />';
				$da['data']=array($i,
						$operate,
						$baseform->form_sn,
						$baseform->form_time,
						$each->dictCompany->short_name,
						$each->dictTitle->short_name,
						$each->amount,
						round($each->weight,3),
						$each->team->name,
						$each->warehouse->name,
						$sales_type[$each->sales_type],
				);
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
				$i++;
			}
		}
		return array($tableData,$pages);
	}
	
	/*
	 * 获取销售单列表，配送单关联销售单
	 */
	public static function getSendSalesList($search){
		$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
		$tableData=array();
		$model=new FrmSales();
		$criteria=New CDbCriteria();
		$criteria->with = array('baseform','warehouse');
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('t.title_id',$search['title_id'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('t.customer_id',$search['customer_id'],false);
			}
	
			if($search['team']!='0')
			{
				$criteria->compare('t.team_id',$search['team'],false);
			}
			if($search['warehouse']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse'],false);
			}
		}
		//$criteria->addCondition("t.sales_type = 'normal'");
		//$criteria->compare('baseform.form_type','XSD',true);
		$criteria->compare('baseform.is_deleted','0',false);
		$criteria->compare('t.confirm_status','0',false);
		$criteria->compare('warehouse.is_jxc','1',false);
		$criteria->addCondition('baseform.form_status="approve" or baseform.form_status="submited"');
		//$criteria->compare('baseform.form_status','approve',false);
		$criteria->order = "baseform.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['sendsales_list']) ? intval($_COOKIE['sendsales_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=FrmSales::model()->findAll($criteria);
		//$details=$model->findAll($criteria);
		if($details){
			$da=array();
			$da['data']=array();
			$i=1;
			foreach ($details as $each)
			{
				$baseform=$each->baseform;
				$operate='<input type="radio" name="selected_contract"  class="selected_contract"  value="'.$each->id.'" />';
				$da['data']=array($i,
						$operate,
						'<span class="form_sn">'.$baseform->form_sn.'</span>',
						$baseform->form_time,
						'<span class="custome_name">'.$each->dictCompany->short_name.'</span>',
						'<span class="title_name">'.$each->dictTitle->short_name.'</span>',
						$each->amount,
						round($each->weight,3),
						$each->team->name,
						$each->warehouse->name,
						$sales_type[$each->sales_type],
				);
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
				$i++;
			}
		}
		return array($tableData,$pages);
	}
	
	/*
	 * 修改销售单列表
	 */
	public static function getUpdateData($post=array())
	{
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmSales'];
		if(!$data['main']['is_yidan']){
			$data['main']['is_yidan'] = 0;
		}
		$data['detail']=array();
		$has_bonus_price = 0;
		for($i=0;$i<count($post['product']);$i++)
		{
			$temp=array();
			$temp['id']=$post['details_id'][$i];
			$temp['product_id']=$post['product'][$i];
			$temp['texture_id']=$post['material'][$i];
			$temp['brand_id']=$post['place'][$i];
			$temp['rank_id']=$post['type'][$i];
			$temp['length']=$_POST['length'][$i];
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
				echo "<script>alert('没有此类商品');</script>";
				return false;
			}
			$temp['amount']=$_POST['td_num'][$i];
			$temp['pre_amount']=$_POST['td_num'][$i];
			$temp['weight']=numChange($_POST['td_total'][$i]);
			$temp['price']=numChange($_POST['money'][$i]);
			$temp['old']=$_POST['old'][$i];
			$temp['bonus_price']=$_POST['gaok'][$i];
			if($_POST['gaok'][$i] >0){$has_bonus_price = 1;}
			$temp['card_id']=$_POST['card_id'][$i];
			$temp['total_amount'] = $temp['weight']*$temp['price'];//numChange($_POST['price'][$i]);
			$temp['gk_id'] = $_POST['gk_id'];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']["has_bonus_price"]=$has_bonus_price;
		$data['main']=(Object)$data['main'];
		return $data;
	}	
	
	/*
	 * 修改先销后进销售单
	 */
	public static function getUpdateDataXS($post=array())
	{
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmSales'];
		if(!$data['main']['is_yidan']){
			$data['main']['is_yidan'] = 0;
		}
		$data['detail']=array();
		$has_bonus_price = 0;
		for($i=0;$i<count($post['product']);$i++)
		{
			$temp=array();
			$temp['id']=$post['details_id'][$i];
			$temp['product_id']=$post['product'][$i];
			$temp['texture_id']=$post['material'][$i];
			$temp['brand_id']=$post['place'][$i];
			$temp['rank_id']=$post['type'][$i];
			$temp['length']=$_POST['length'][$i];
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
				echo "<script>alert('没有此类商品');</script>";
				return false;
			}
			$temp['amount']=$_POST['td_num'][$i];
			$temp['pre_amount']=$_POST['td_num'][$i];
			$temp['weight']=numChange($_POST['td_total'][$i]);
			$temp['price']=numChange($_POST['money'][$i]);
			$temp['bonus_price']=$_POST['gaok'][$i];
			if($_POST['gaok'][$i] >0){$has_bonus_price = 1;}
			$temp['card_id']=$_POST['card_id'][$i];
			$temp['total_amount'] = $temp['weight']*$temp['price'];//numChange($_POST['price'][$i]);
			$temp['fix_price'] = numChange($_POST['fix_price'][$i]);
			$temp['gk_id'] = $_POST['gk_id'];
			array_push($data['detail'], (Object)$temp);
		}
		$warehouse = $data['main']['warehouse_name'];
		$warehouse_id = $data['main']['warehouse_id'];
		if(empty($warehouse_id) || $warehouse_id<1){
			$model = Warehouse::model()->find("name='".$warehouse."'");
			if($model){
				$data['main']['warehouse_id'] = $model->id;
			}else{
				$result = Warehouse::SetWarehouse($warehouse);
				if($result){
					$data['main']['warehouse_id'] = $result->id;
				}
			}
		}
		$data['main']["has_bonus_price"]=$has_bonus_price;
		$data['main']=(Object)$data['main'];
		return $data;
	}
	
	/*
	 * 获取先销后进或代销销售 销售单列表
	 * 供采购处使用
	 */
	public static function getSimpleList($search,$type)
	{
		if($type=='xxhj')
		{
			$tableHeader = array(
					array('name'=>'','class' =>"sort-disabled text-center",'width'=>"30px"),
					array('name'=>'操作','class' =>"sort-disabled",'width'=>"60px"),
					array('name'=>'销售单号','class' =>"flex-col sort-disabled",'width'=>"140px"),
					array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
					array('name'=>'客户','class' =>"flex-col sort-disabled",'width'=>"180px"),//
					array('name'=>'销售公司','class' =>"flex-col sort-disabled",'width'=>"180px"),//
					array('name'=>'总重量','class' =>"flex-col sort-disabled text-right",'width'=>"120px"),//
					array('name'=>'总件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
					array('name'=>'未完成重量','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
					array('name'=>'未完成件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
					array('name'=>'已补单件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
					array('name'=>'未补单件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
					array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"60px"),//
					array('name'=>'销售员','class' =>"flex-col sort-disabled",'width'=>"90px"),//
			);
			
			$tableData=array();
			$model=FrmSales::model()->with('baseform');
			$criteria=New CDbCriteria();
			//搜索
			//销售单号 日期上下，采购公司customer_id，业务员owned_by，供应商/销售公司title_id
			if(!empty($search))
			{
				$criteria->together=true;
				$criteria->addCondition('baseform.form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
				if($search['time_L']!='')
				{
					$criteria->addCondition('baseform.created_at >='.strtotime($search['time_L']));
				}
				if($search['time_H']!='')
				{
					$criteria->addCondition('baseform.created_at <='.(strtotime($search['time_H'])+86400));
				}
				if($search['title_id']!='')
				{
					$criteria->compare('t.title_id',$search['title_id']);
				}
				if($search['customer_id']!='')
				{
					$criteria->compare('t.customer_id',$search['customer_id']);
				}
				if($search['owned']!='0')
				{
					$criteria->compare('baseform.owned_by',$search['owned']);
				}
			
			}
			$criteria->compare('baseform.form_type','XSD');
			$criteria->compare('baseform.is_deleted','0');
			$criteria->compare('t.sales_type','xxhj');
			$criteria->compare('t.is_related', '0');
			$criteria->compare('baseform.form_status','approve');
			$pages = new CPagination();
			$pages->itemCount = $model->count($criteria);
			$pages->pageSize =intval($_COOKIE['sales_list']) ? intval($_COOKIE['sales_list']) : Yii::app()->params['pageCount'];
			$pages->applyLimit($criteria);
			$criteria->order="created_at DESC";
			$frmsales=FrmSales::model()->with('baseform')->findAll($criteria);
			if($frmsales)
			{
				$da=array();
				$da['data']=array();
				$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
				$i=1;
				foreach ($frmsales as $each)
				{
					$baseform=$each->baseform;
					$details=$each->salesDetails;
					$edA=0;
					$needA=0;
					foreach ($details as $e)
					{
						$edA+=$e->purchased_amount;
						$needA+=$e->need_purchase_amount;
					}
					$operate='<input type="radio" name="selected_sales"  class="selected_sales"  value="'.$baseform->id.'" />';
					$da['data']=array($i,
							$operate,
							$baseform->form_sn,
							$baseform->form_time,
							'<span title="'.$each->dictCompany->name.'">'.$each->dictCompany->short_name.'</span>',
							$each->dictTitle->short_name,//
							number_format($each->weight,3),
							$each->amount,
							number_format($each->weight-$each->output_weight,3),
							$each->amount-$each->output_amount,
							$edA,//已补单件数
							$needA-$edA,//未补单件数
							$each->team->name,
							$baseform->belong->nickname,
					);
					$da['group']=$baseform->form_sn;
					array_push($tableData,$da);
					$i++;
				}
			}
			
			return array($tableHeader,$tableData,$pages);
		}elseif($type=='dxcg')
		{
			$tableHeader = array(
					array('name'=>'','class' =>"sort-disabled text-center",'width'=>"30px"),
					array('name'=>'操作','class' =>"sort-disabled",'width'=>"100px"),
					array('name'=>'销售单号','class' =>"flex-col sort-disabled",'width'=>"150px"),
					array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
					array('name'=>'销售类型','class' =>"flex-col sort-disabled",'width'=>"80px"),//
					array('name'=>'仓库','class' =>"flex-col sort-disabled",'width'=>"100px"),
					array('name'=>'销售公司','class' =>"flex-col sort-disabled",'width'=>"110px"),
					array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
					array('name'=>'货主单位','class' =>"flex-col sort-disabled",'width'=>"110px"),//
					array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"100px"),//
					array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"60px"),//
					array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"70px"),//
					array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),//
					array('name'=>'件数','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//
					array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"120px"),//
					// array('name'=>'已出库件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
					// array('name'=>'已出库重量','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
					array('name'=>'未补单件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
					array('name'=>'未补单重量','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
					// 				array('name'=>'收款金额','class' =>"flex-col sort-disabled",'width'=>"60px"),//
			// 				array('name'=>'收付状态','class' =>"flex-col sort-disabled",'width'=>"60px"),//
			// 				array('name'=>'销售公司','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				
					array('name'=>'审单状态','class' =>"flex-col sort-disabled",'width'=>"90px"),//
			);
			$tableData=array();
			$model=SalesDetail::model()->with(array('FrmSales','FrmSales.baseform'=>array('order'=>'baseform.created_at DESC')));
			$criteria=New CDbCriteria();
			//搜索
			//销售单号，销售公司，日期，品名，产地，规格，材质，仓库，客户/采购公司，业务组，业务员，审单状态，
			if(!empty($search))
			{
				$criteria->together=true;
				$criteria->addCondition('baseform.form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
				if($search['time_L']!='')
				{
					$criteria->addCondition('baseform.created_at >='.strtotime($search['time_L']));
				}
				if($search['time_H']!='')
				{
					$criteria->addCondition('baseform.created_at <='.(strtotime($search['time_H'])+86400));
				}
				if($search['title_id']!='0')
				{
					$criteria->compare('FrmSales.title_id',$search['title_id']);
				}
				if($search['confirm_status']!='0')
				{
					$criteria->compare('FrmSales.confirm_status',$search['confirm_status']);
				}
				if($search['customer_id']!='0')
				{
					$criteria->compare('FrmSales.owner_company_id',$search['customer_id']);
				}
					
				if($search['team']!='0')
				{
					$criteria->compare('FrmSales.team_id',$search['team']);
				}
				if($search['owned']!='0')
				{
					$criteria->compare('baseform.owned_by',$search['owned']);
				}
				if($search['warehouse']!='0')
				{
					$criteria->compare('FrmSales.warehouse_id',$search['warehouse']);
				}
				//产地,品名，规格,材质
				if($search['brand']!='0')
				{
					$criteria->compare('t.brand_id',$search['brand']);
				}
				if($search['product']!='0')
				{
					$criteria->compare('t.product_id',$search['product']);
				}
				if($search['rand']!='0')
				{
					$criteria->compare('t.rank_id',$search['rand']);
				}
				if($search['texture']!='0')
				{
					$criteria->compare('t.texture_id',$search['texture']);
				}
			
			}
			$criteria->compare('baseform.form_type','XSD');
			$criteria->compare('baseform.form_status','approve');
			$criteria->compare('baseform.is_deleted','0');
			$criteria->compare('FrmSales.sales_type', 'dxxs');
			// $criteria->addCondition('t.is_related in (0,-1)');
			$criteria->addCondition('t.purchased_amount-t.amount<0');
			$pages = new CPagination();
			$pages->itemCount = $model->count($criteria);
			$pages->pageSize =intval($_COOKIE['sales_list']) ? intval($_COOKIE['sales_list']) : Yii::app()->params['pageCount'];
			$pages->applyLimit($criteria);
			
			$details=SalesDetail::model()->with(array('FrmSales','FrmSales.baseform'=>array('order'=>'baseform.created_at DESC')))->findAll($criteria);
			if($details)
			{
				$da=array();
				$da['data']=array();
				$sales_type = array("normal"=>"库存销售","xshj"=>"先销后进","dxxs"=>"代销销售");
					
				$i=1;
				$totaldata = array();
				foreach ($details as $each)
				{
					$operate='<input type="checkbox" '.($each->is_related==0?'':'checked="checked"').' name="selected_sales"   class="selected_sales"  value="'.$each->id.'"/>';
					$baseform=$each->FrmSales->baseform;
					$frmsales=$each->FrmSales;
					$price = ($each->price)*$each->weight;
					$da['data']=array($i,
							$operate,
							$baseform->form_sn,
							$baseform->form_time,
							$sales_type[$frmsales->sales_type],
							$frmsales->warehouse->name,
							$frmsales->dictTitle->short_name,
							'<span title="'.$frmsales->dictCompany->name.'">'.$frmsales->dictCompany->short_name.'</span>',
							'<span title="'.$frmsales->dictOwner->name.'">'.$frmsales->dictOwner->short_name.'</span>',
							DictGoodsProperty::getProName($each->product_id),
							DictGoodsProperty::getProName($each->rank_id),
							str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)),
							DictGoodsProperty::getProName($each->brand_id),
							$each->amount,
							number_format($each->weight,3),
							// $each->output_amount,
							// number_format($each->output_weight,3),
							$each->amount-$each->purchased_amount,
							number_format($each->weight-$each->purchased_weight,3),
							
							$frmsales->confirm_status==0?'未审单':'已审单',
					);
					$da['group']=$baseform->form_sn;
					array_push($tableData,$da);
					$i++;
				}
			}
			return array($tableHeader,$tableData,$pages);
		}
		
	}
	

	/*
	 * 获取先销后进销售单明细
	 */
	public static function getXDetailData($id)
	{
		$model=CommonForms::model()->with('sales','sales.salesDetails')->findByPk($id);
		if($model)
		{
			$details=$model->sales->salesDetails;
			return $details;
		}
		return false;
	}
	/*
	 * 获取销售单主体信息
	 */
	public static function getSaleMainData($id)
	{
		$return=array();
		$model=CommonForms::model()->with('sales')->findByPk($id);
		if($model)
		{
			$sale=$model->sales;
// 			$return['supply_name']=$sale->dictCom->short_name;//供应商
// 			$return['supply_id']=$sale->customer_id;
			$return['title_name']=$sale->dictTitle->short_name;
			$return['title_id']=$sale->title_id;
			$return['contact_id']=$sale->company_contact_id;
			$return['contact_name']=$sale->companycontact->name;
			$return['team_name']=$sale->team->name;
			$return['team_id']=$sale->team_id;
			$return['owned_name']=$model->belong->nickname;
			$return['owned_by']=$model->owned_by;
			$return['mobile']=$sale->companycontact->mobile;
			$return['warehouse_id']=$sale->warehouse_id;
			$return['warehouse']=$sale->warehouse->name;
		
		}
		return json_encode($return);
	}
	
	//销售收款 列表
	public static function getFormBillList($search) 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "4%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "15%"),
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "采购商", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "重量", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "件数", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "乙单", 'class' => "sort-disabled", 'width' => "4%"),
				array('name' => "类型", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "业务组", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "10%"),
		);
		
		$tableData = array();
		$model = new FrmSales();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		
		//搜索
		if(!empty($search))
		{	
			if ($search['company_id'])
			{
				$criteria->addCondition("customer_id = :customer_id");
				$criteria->params[':customer_id'] = $search['company_id'];
			}
			if ($search['title_id'])
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
// 			if ($search['is_yidan']) 
// 			{
// 				$criteria->addCondition("is_yidan = :is_yidan");
// 				$criteria->params[':is_yidan'] = $search['is_yidan'];
// 			}
			if ($search['keywords'])
			{
				$criteria->addCondition("baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if ($search['time_L'])
			{
				$criteria->addCondition("baseform.created_at >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']);
			}
			if ($search['time_H'])
			{
				$criteria->addCondition("baseform.created_at <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']);
			}
			if ($search['owned_by'])
			{
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		$criteria->compare("baseform.form_type", 'XSD', true);
		$criteria->compare("baseform.is_deleted", '0', true);
		$criteria->compare("baseform.form_status", "approve", true);
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['bill_list']) ? intval($_COOKIE['bill_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		
		$bill = $model->findAll($criteria);
		if ($bill) 
		{
			$_type = array('normal' => "库存销售", 'xxhj' => "先销后进", 'dxxs' => "代销销售");
			$i = 1;
			foreach ($bill as $item) 
			{
				$mark = '';
				$operate = '';
				$da = array();
				if ($item->baseform) 
				{
					$baseform = $item->baseform;
					$mark = $i;
					$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill" yidan="'.$item->is_yidan.'" value="'.$baseform->id.'" />';
					$i++;
				} 
				
				$da['data'] = array($mark, 
						$operate, 
						$baseform->form_sn,
						$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
						'<span title="'.$item->dictCompany->name.'">'.$item->dictCompany->short_name.'</span>',
						number_format($item->weight, 3),
						number_format($item->amount, 0),
						$item->is_yidan == 1 ? '是' : '否',
						$_type[$item->sales_type], //类型
						$item->team->name, //业务组
						$baseform->belong->nickname, //业务员
				);
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	//所有销售单 列表
	public static function getCheckList($search) 
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"3%"),
				array('name'=>'操作','class' =>"sort-disabled", 'width' => "4%"),
				array('name'=>'销售单号','class' =>"flex-col sort-disabled", 'width' => "9%"),
				array('name'=>'状态','class' =>"flex-col sort-disabled", 'width' => "5%"),//
				array('name'=>'开单日期','class' =>"flex-col sort-disabled", 'width' => "8%"),
				array('name'=>'销售公司','class' =>"flex-col sort-disabled", 'width' => "8%"),//
				array('name'=>'结算单位','class' =>"flex-col sort-disabled", 'width' => "8%"),//
				array('name'=>'总重量','class' =>"flex-col sort-disabled", 'width' => "7%"),//
				array('name'=>'总件数','class' =>"flex-col sort-disabled", 'width' => "6%"),//
				array('name'=>'未完成重量','class' =>"flex-col sort-disabled", 'width' => "9%"),
				array('name'=>'未完成件数','class' =>"flex-col sort-disabled", 'width' => "9%"),
				array('name'=>'乙单','class' =>"flex-col sort-disabled", 'width' => "4%"),//
				array('name' => "销售类型", 'class' => "flex-col sort-disabled", 'width' => "6%"),
				array('name'=>'销售员','class' =>"flex-col sort-disabled", 'width' => "6%"),//
				array('name'=>'客户','class' =>"flex-col sort-disabled", 'width' => "8%"),//
		);
		
		$tableData = array();
		$model = new FrmSales();
		$criteria = New CDbCriteria();
		$criteria->with = array('baseform');		
		
		if (!empty($search)) 
		{
			if ($search['team_id']) 
			{
				$criteria->addCondition("team_id = :team_id");
				$criteria->params[':team_id'] = $search['team_id'];
			}
			if ($search['sales_title']) 
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['sales_title'];
			}
			if ($search['customer_id'])
			{
				$criteria->addCondition("customer_id = :customer_id");
				$criteria->params[':customer_id'] = $search['customer_id'];
			}
			if ($search['client_id']) 
			{
				$criteria->addCondition("client_id = :client_id");
				$criteria->params['client_id'] = $search['client_id'];
			}			
			if ($search['keywords'])
			{
				$criteria->addCondition("baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = '%'.$search['keywords'].'%';
			}
			if ($search['search_begin']) 
			{
				$criteria->addCondition("baseform.created_at >= :search_begin");
				$criteria->params[':search_begin'] = strtotime($search['search_begin']." 00:00:00");
			}
			if ($search['search_end'])
			{
				$criteria->addCondition("baseform.created_at <= :search_end");
				$criteria->params[':search_end'] = strtotime($search['search_end']." 23:59:59");
			}
			if(checkOperation('销售折让:全部'))
			{
				if ($search['owned_by'])
				{
					$criteria->addCondition("baseform.owned_by = :owned_by");
					$criteria->params['owned_by'] = $search['owned_by'];
				}
			}else{
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params['owned_by'] = currentUserId();
			}			
			if ($search['id']) $rebate_id = intval($search['id']);
		}
		//$criteria->addCondition("is_yidan = 1"); //乙单
		
		$criteria->compare('baseform.form_type', 'XSD', true);
		$criteria->compare('baseform.is_deleted', '0', true);
		//过滤已有折让的销售单
		$sql = "SELECT t.* FROM rebate_relation t, common_forms baseform, frm_rebate rebate WHERE baseform.form_type = 'XSZR' AND baseform.form_status <> 'delete' AND rebate.id = t.rebate_id AND baseform.form_id = t.rebate_id AND rebate.type = 'sale'";
		$rebateRelation = RebateRelation::model()->findAllBySql($sql);
		$sales_id_array = array();
		foreach ($rebateRelation as $rr) 
		{
			if ($rebate_id && $rr->rebate_id == $rebate_id) continue;
			array_push($sales_id_array, $rr->sales_id);
		}
		$criteria->addNotInCondition("baseform.id", $sales_id_array);
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['sales_list']) ? intval($_COOKIE['sales_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		
		$frmsales = $model->findAll($criteria);
		if ($frmsales) 
		{
			$sales_type = array('normal' => "库存销售", 'xxhj' => "先销后进", 'dxxs' => "代销销售");
			$i = 1;
			foreach ($frmsales as $each) 
			{
				$baseform = $each->baseform;
				$details = $each->salesDetails;
				$mark = $i;
				$operate = '<input type="checkbox" name="selected_sales[]" class="selected_sales"  value="'.$baseform->id.'" />';
				$da = array('data' => array());
				$da['data'] = array($mark, 
						$operate,
						$baseform->form_sn,
						$each->confirm_status == 1 ? "已完成" : CommonForms::$formStatus[$baseform->form_status],
						$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
						'<span title="'.$each->dictTitle->name.'">'.$each->dictTitle->short_name.'</span>',
						'<span title="'.$each->dictCompany->name.'" value="'.$each->customer_id.'">'.$each->dictCompany->short_name.'</span>',
						number_format($each->weight, 3),
						number_format($each->amount),
						number_format($each->confirm_status == 1 ? 0 : $each->weight - $each->output_weight, 3),
						number_format($each->confirm_status == 1 ? 0 : $each->amount - $each->output_amount),
						$each->is_yidan == 1?"是":"",
						$sales_type[$each->sales_type],
						'<span  value="'.$baseform->owned_by.'">'.$baseform->belong->nickname.'</span>',						
						'<span title="'.$each->client->name.'" value="'.$each->client_id.'">'.$each->client->short_name.'</span>',
				);
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
				$i++;
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	/*
	 * 根据销售单id获取销售单信息
	 */
	public static function getSalesData($id){
		$model = FrmSales::model()->findByPk($id);
		if($model)
		{
			$return['company_name']=$model->dictCompany->short_name;
			$return['title_name']=$model->dictTitle->short_name;
			$return['warehouse_id']=$model->warehouse_id;
			$return['warehouse']=$model->warehouse->name;
		}
		return json_encode($return);
	}
	
	/*
	 *获取 代销采购所需求的信息
	 */
	public static function giveYouMainInfo($sale_detail_id)
	{
		$return=array();
		$saleDetail=SalesDetail::model()->with('FrmSales')->findByPk($sale_detail_id);
		$frmSales=$saleDetail->FrmSales;
		$storage=Storage::model()->with('inputDetailDx','inputDetailDx.input')->findByPk($saleDetail->card_id);
		$return['title_id']=$frmSales->title_id;
		$return['title_name']=$frmSales->dictTitle->short_name;
		$return['team_id']=$frmSales->team_id;
		$return['team_name']=$frmSales->team->name;
		$return['warehouse_id']=$frmSales->warehouse_id;
		$return['warehouse_name']=$frmSales->warehouse->name;
		if($storage)
		{
			$return['supply_id']=$storage->inputDetailDx->input->supply_id;
			$return['supply_name']=$storage->inputDetailDx->input->supply->short_name;			
		}
		return json_encode($return);
	}
	
	/*
	 *根据船舱转正前的聚合id，合并销售单明细，配送单明细
	 *$id:转正前id，$mid转正后聚合到的仓库信息id
	 */
	public static function setSSDetails($id,$mid){
		//获取所有符合条件的库存销售单
		$model = new FrmSales();
		$criteria = New CDbCriteria();
		$criteria->with = array('baseform',"salesDetails");
		$criteria->addCondition("baseform.is_deleted = 0");
		$criteria->addCondition("t.sales_type='normal'");
		$criteria->addCondition("t.confirm_status=0");
		$criteria->addCondition("salesDetails.card_id=$id");
		$sales = $model->findAll($criteria);
		if($sales){
			foreach($sales as $each){
				$baseformData = $each->baseform;
				$salesDetailsId = 0;
				$ship = false;
				$deleted = false;
				$salesDetails = $each->salesDetails;
				if($salesDetails){
					foreach($salesDetails as $li){
						//销售单卡号id为船舱入库的id，进行处理
						if($li->card_id == $id){
							$ship = $li;
						}
					}
				}
				//销售单中含有船舱入库
				if($ship){
					$salesDetails = SalesDetail::model()->findAll("frm_sales_id=$each->id");
					foreach($salesDetails as $li){
						if($ship->id == $li->id){continue;}
						//找到与船舱入库属性一致的明细
						//此处正式上线风险较大，改了一半暂停，暂时进入到只更改销售单明细阶段
						if( 0 && $li->mergestorage->is_transit == 0 && $li->product_id == $ship->product_id && $ship->rank_id == $li->rank_id && $ship->brand_id == $li->brand_id && $ship->texture_id == $li->texture_id && $ship->length == $li->length){
							$oldJson = $li->datatoJson();
							$li->amount += $ship->amount;
							$li->weight += $ship->weight;
							$li->fee += $ship->fee;
							$li->send_amount += $ship->send_amount;
							$li->send_weight += $ship->send_weight;
							$li->update();
							$mainJson = $li->datatoJson();
							$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$baseform = new BaseForm();
							$baseform->dataLog($dataArray);
							//合并删除配送单
							FrmSend::mergeOutput($each->id,$li->id,$ship->id);
							//修改往来
							$thrnover = Turnover::findDetailBill($baseformData->id,$li->id);
							if($thrnover){
								$amount = $li->weight;
								$price = $li->price;
	 							$fee = $li->fee;
	 							$update=array('fee'=>$fee,'amount'=>$amount,"price"=>$price);
	 							$oldJson=$thrnover->datatoJson();
	 							$result = Turnover::updateBill($thrnover->id, $update);
	 							$mainJson = $result->datatoJson();
	 							$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 							$baseform = new BaseForm();
								$baseform->dataLog($dataArray);
							}
							
							//修改高开信息
							if($li->bonus_price > 0){
								$gk["main"]["price"] = $li->bonus_price;
								$gk["main"]["fee"] = $li->bonus_price*$li->weight;
								$gkdata['main']=(Object)$gk['main'];
								HighOpen::updateLine($each->id,$li->id,$gkdata);
							}
							$mainJson = $li->datatoJson();
							$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$baseform = new BaseForm();
							$baseform->dataLog($dataArray);
							$deleted = true;
							break;
						}
					}
					
					if($deleted){
						//销售单已合并，删除多余数据,处理配送单
						foreach($salesDetails as $li){
							if($li->card_id == $id){
								$oldJson = $li->datatoJson();
								//删除高开信息
								HighOpen::deleteLine($each->id,$li->id);
								//删除往来信息
								$thrnover = Turnover::findDetailBill($baseformData->id,$li->id);
								if($thrnover){
									$oldTh = $thrnover->datatoJson();
									$thrnover->delete();
									$dataArray = array("tableName"=>"Turnover","newValue"=>"","oldValue"=>$oldTh);
									$baseform = new BaseForm();
									$baseform->dataLog($dataArray);
								}
								$li->delete();
								$dataArray = array("tableName"=>"SalesDetail","newValue"=>"","oldValue"=>$oldJson);
								$baseform = new BaseForm();
								$baseform->dataLog($dataArray);
							}
						}
					}else{
						//销售单没有合并，更新原来的船舱入库信息
						foreach($salesDetails as $li){
							if($li->card_id == $id){
								$oldJson = $li->datatoJson();
								$li->card_id = $mid;
								$li->update();
								$mainJson = $li->datatoJson();
								$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
								$baseform = new BaseForm();
								$baseform->dataLog($dataArray);
							}
						}
					}
				}
			}
		}
		return true;
	}
	
	//获取业务员下所有销售单
	public static function getOwnedList($search) 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "4%"),
				array('name' => "销售单号", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "状态", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "开单时间", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "销售公司", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "结算单位", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "乙单", 'class' => "sort-disabled", 'width' => "5%"),
				array('name' => "件数", 'class' => "sort-disabled text-right", 'width' => "7%"),
				array('name' => "重量", 'class' => "sort-disabled text-right", 'width' => "9%"),
				array('name' => "金额", 'class' => "sort-disabled text-right", 'width' => "9%"),
				array('name' => "销售类型", 'class' => "sort-disabled", 'width' => "7%"),
				array('name' => "客户", 'class' => "sort-disabled", 'width' => "9%"),
		);
		
		$tableData = array();
		$model = new FrmSales();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		
		//搜索
		if (!empty($search)) 
		{
			if ($search['owned_by']) 
			{
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		$criteria->compare("baseform.form_type", 'XSD', true);
		$criteria->compare("baseform.is_deleted", '0', true);
		$criteria->compare("baseform.form_status", "approve", true);
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['ownedSales_list']) ? intval($_COOKIE['ownedSales_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		
		if (!$search['owned_by']) return array($tableHeader, $tableData, $pages);
		$ownedBill = $model->findAll($criteria);
		if ($ownedBill)
		{
			$_type = array('normal' => "库存销售", 'xxhj' => "先销后进", 'dxxs' => "代销销售");
			$i = 1;
			foreach ($ownedBill as $item)
			{
				$mark = '';
				$operate = '';
				$da = array();
				if ($item->baseform)
				{
					$baseform = $item->baseform;
					$mark = $i;
					$i++;
				}
				$total_money = 0;
				foreach ($item->salesDetails as $detail) 
				{
					$total_money += ($detail->price) * $detail->weight;
				}
				
				$da['data'] = array($mark,
						'<a target="_blank" href="'.Yii::app()->createUrl('FrmSales/detail', array('id' => $baseform->id, 'fpage' => 1)).'">'.$baseform->form_sn.'</a>',
						$item->confirm_status == 1 ? "已完成" : CommonForms::$formStatus[$baseform->form_status], 
						$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
						'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
						'<span title="'.$item->dictCompany->name.'">'.$item->dictCompany->short_name.'</span>',
						$item->is_yidan == 1 ? '是' : '否',
						number_format($item->amount), 
						number_format($item->weight, 3),
						'<span class="sale_fee" frmsaleid="'.$item->id.'">'.number_format($total_money, 2).'</span>',
						$_type[$item->sales_type],
						'<span title="'.$item->client->name.'">'.$item->client->short_name.'</span>',
				);
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	/*
	 * 销售汇总 
	*/
	public static function getTotal($search){
		$own = 0;
		$tableData=array();
		//查询销售明细，获取基础信息
		$model = new SalesDetail();
		//查询销售单，获取销售折让金额
		$sales = new FrmSales();
		$criteria=New CDbCriteria();
		$c=New CDbCriteria();
		$criteria->with=array('FrmSales','FrmSales.baseform','FrmSales.baseform.Record');//
		$c->with=array("baseform");
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
				$c->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
				$c->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('FrmSales.title_id',$search['title_id'],false);
				$c->compare('t.title_id',$search['title_id'],false);
			}
			if($search['is_yidan'] >= 0 ){
				$criteria->compare('FrmSales.is_yidan',$search['is_yidan'],false);
				$c->compare('t.is_yidan',$search['is_yidan'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('FrmSales.customer_id',$search['customer_id'],false);
				$c->compare('t.customer_id',$search['customer_id'],false);
			}
			if($search['sales_type']){
				$criteria->addCondition('FrmSales.sales_type="'.$search['sales_type'].'"');
			}
			if($search['owned']!='0')
			{
				$criteria->compare('baseform.owned_by',$search['owned'],false);
				$own = $search['owned'];
			}
		}
		
		if($search['form_status'])
		{
			if($search['form_status'] == "complete"){
				$criteria->compare('FrmSales.confirm_status',1,false);
				$c->compare('t.confirm_status',1,false);
			}else{
				$criteria->compare('FrmSales.confirm_status',0,false);
				$c->compare('t.confirm_status',0,false);
				$criteria->compare('baseform.form_status',$search['form_status'],false);
				$c->compare('baseform.form_status',$search['form_status'],false);
			}
		}else{
// 			$criteria->compare('FrmSales.confirm_status',0,false);
// 			$c->compare('t.confirm_status',0,false);
		}
		$criteria->addCondition('baseform.form_status <>"unsubmit"');
		$criteria->compare('baseform.is_deleted','0',false);
		if(!checkOperation("销售汇总:查看全部")){
			$criteria->compare('baseform.owned_by',Yii::app()->user->userid,false);
		}
		//获取运费信息
		$c_bill = clone $criteria;
		$c->addCondition('baseform.form_status <>"unsubmit"');
		$criteria->group='FrmSales.title_id,FrmSales.customer_id';
		$c->compare('baseform.is_deleted','0',false);
		//$c->group='t.title_id,t.customer_id';
		$criteria->order = "baseform.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['salestotal_list']) ? intval($_COOKIE['salestotal_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->select='FrmSales.title_id as title_id,FrmSales.customer_id as customer_id,sum(t.weight*t.price) as total_amount,sum(t.weight) as total_weight,'
				.'sum(t.weight*t.bonus_price) as bonus_money,sum(t.output_amount) as total_out_amount,sum(t.output_weight) as total_out_weight,sum(Record.amount) as total_rebate';
		$details=$model->findAll($criteria);
		$frmsales = $sales->findAll($c);
		//$billlist = $model->findAll($c_bill);
		//var_dump($frmsales);
		$frmarr = array();
		$billarr = array();
		$gkarr = array();
		$invmoney = array();
		$invweight = array();
		$invismoney = array();
		$invisweight = array();
		$can_inv_amount = array();
		$can_inv_weight = array();
		$totaldata = array();
		//设置折让数组，根据公司和销售公司id拼接为数组键值
		if($frmsales){
			foreach($frmsales as $li){
				$key = $li->title_id.$li->customer_id;
				$rebate = $li->baseform->RebateRelation->rebate;
				if($rebate){
					$frmarr[$key] += $rebate->amount;
				}
				if($li->is_yidan == 0){
					if($li->confirm_status == 0){
						$can_inv_amount[$key] += $li->fee;
						$can_inv_weight[$key] += $li->weight;
					}else{
						$can_inv_amount[$key] += $li->fix_fee;
						$can_inv_weight[$key] += $li->confirm_weight;
					}
				}
			}
		}
		//var_dump($frmsales);echo "<br/>";
		//设置运费数组
		if($details){
			foreach($details as $li){
				$c_billnew = clone $c_bill;
				$salesDetail = new SalesDetail();
				$c_billnew->addCondition("FrmSales.title_id=".$li->FrmSales->title_id);
				$c_billnew->addCondition("FrmSales.customer_id=".$li->FrmSales->customer_id);
				$salesDetail = $salesDetail->findAll($c_billnew);
				$key = $li->FrmSales->title_id.$li->FrmSales->customer_id;
				if($salesDetail){
					foreach ($salesDetail as $sd){
// 						$billRecord = $sd->billRecord;
// 						if($billRecord){
// 							foreach($billRecord as $each){
// 								$billarr[$key] += $each->amount;
// 							}
// 						}
						if($sd->bonus_price > 0){
							$gkarr[$key] += $sd->weight;
						}
						$inv = $sd->detailsInvoice;
						if($inv){
							$invmoney[$key] += $inv->money;
							$invweight[$key] += $inv->weight;
							$invismoney[$key] += $inv->checked_money;
							$invisweight[$key] += $inv->checked_weight;
						}
					}
				}
			}
		}
		if($details){
			$da=array();
			$da['data']=array();
			$i=1;
			foreach($details as $each){
				$totaldata["amount"] += $each->total_amount;
				$totaldata["weight"] += $each->total_weight;
				$key = $each->FrmSales->title_id.$each->FrmSales->customer_id;
				$yu = Turnover::getSalesYu($each->FrmSales->title_id,$each->FrmSales->customer_id,$own,"sales"); 
				$invoice_url = Yii::app()->createUrl('salesInvoice/create',array('title_id' => $each->FrmSales->title_id, 'company_id' => $each->FrmSales->customer_id, 'fpage' => $_REQUEST['page']));
				$str = '<div class="cz_list_btn"><span title="查看详情" class="salesview" id="'.$each->FrmSales->title_id.'" cid="'.$each->FrmSales->customer_id.'"><img src="/images/detail.png"><span></div>';
				$da['data']=array(
						$str,
						'<span title="'.$each->FrmSales->dictCompany->name.'">'.$each->FrmSales->dictCompany->short_name.'</span>',
						number_format($yu,2),
						$each->FrmSales->title->short_name,
						number_format($each->total_weight,3),
						number_format($each->total_amount,2),
						number_format($can_inv_weight[$key] - $invisweight[$key],3),
						'<a href="'.$invoice_url.'" title="查看明细" class="a_view">'.number_format($can_inv_amount[$key] - $invismoney[$key],2).'</a>',
						number_format($each->total_out_amount),
						number_format($each->total_out_weight,3),
						number_format($can_inv_weight[$key],3),
						number_format($can_inv_amount[$key],2),
						number_format($frmarr[$key],2),
						number_format($gkarr[$key],3),
						number_format($each->bonus_money,2),
						number_format($each->total_rebate,2),
				);
				$da['group']=$i;
				$i++;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	}
	
	/*
	 * 销售明细汇总
	 */
	public static function getTotalDetails($search)
	{
		$tableData=array();
		//查询销售明细，获取基础信息
		$model = new SalesDetail();
		$criteria=New CDbCriteria();
		$criteria->with=array('FrmSales','FrmSales.baseform');//
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('FrmSales.title_id',$search['title_id'],false);
			}
			if($search['is_yidan'] >= 0 ){
				$criteria->compare('FrmSales.is_yidan',$search['is_yidan'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('FrmSales.customer_id',$search['customer_id'],false);
			}
		}
		
		if($search['form_status'])
		{
			if($search['form_status'] == "complete"){
				$criteria->compare('FrmSales.confirm_status',1,false);
			}else{
				$criteria->compare('FrmSales.confirm_status',0,false);
				$criteria->compare('baseform.form_status',$search['form_status'],false);
			}
		}else{
// 			$criteria->compare('FrmSales.confirm_status',0,false);
		}
		$criteria->addCondition('baseform.form_status <>"unsubmit"');
		$criteria->compare('baseform.is_deleted','0',false);
		$criteria->order = "baseform.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['salestotal_list']) ? intval($_COOKIE['salestotal_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		if($details){
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
			$da=array();
			$da['data']=array();
			$i=1;
			foreach($details as $each){
				$sales = $each->FrmSales;
				$baseform = $sales->baseform;
				$bill = 0;
				$invmoney = 0;
				$invweight = 0;
				$invismoney = 0;
				$invisweight = 0;
				$needmoney = 0;
				$needweight = 0;
				$billRecord = $baseform->Record;
				if($sales->confirm_status == 0){
					$bill = $billRecord->price * $each->weight;
				}else{
					$bill = $billRecord->price * $each->output_weight;
				}
				$inv = $each->detailsInvoice;
				if($inv){
					$invmoney = $inv->money;
					$invweight = $inv->weight;
					$invismoney = $inv->checked_money;
					$invisweight = $inv->checked_weight;
				}
				$money = ($each->price)*$each->weight;
				if($sales->is_yidan == 0){
					if($sales->confirm_status == 0){
						$needmoney = $money - $invismoney;
						$needweight = $each->weight - $invisweight;
					}else{
						$needmoney = ($each->price)*$each->output_weight - $invismoney;
						$needweight = $each->output_weight - $invisweight;
					}
				}
				//$key = $each->FrmSales->title_id.$each->FrmSales->customer_id;
				$da['data']=array(
						$each->FrmSales->title->short_name,
						'<span title="'.$each->FrmSales->dictCompany->name.'">'.$each->FrmSales->dictCompany->short_name.'</span>',
						$baseform->form_sn,
						$sales->confirm_status==1?"已完成":$_status[$baseform->form_status],//销售单状态
						$baseform->form_time,
						$sales->is_yidan>0?"是":"",
						$sales_type[$sales->sales_type],
						DictGoodsProperty::getProName($each->brand_id),
						DictGoodsProperty::getProName($each->product_id),
						DictGoodsProperty::getProName($each->texture_id),
						DictGoodsProperty::getProName($each->rank_id),
						$each->length,
						number_format($each->weight,3),
						number_format($money,2),
						number_format($each->bonus_price,2),
						number_format($bill,2),
						$each->output_amount,
						number_format($each->output_weight,3),
						number_format($needweight,3),
						number_format($needmoney,2),
				);
				$da['group']=$i;
				$i++;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages);
	}
	
	/*
	 * 获取锁定库存销售明细列表
	 */
	public static function getlockList($search,$storage_id)
	{
		$tableData=array();
	
		$model = new SalesDetail();
		$criteria=New CDbCriteria();
		$criteria->with=array('FrmSales','FrmSales.baseform');
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
		}
		$criteria->addCondition('t.card_id='.$storage_id);
		$criteria->addCondition('baseform.form_status<>"unsubmit"');
		$criteria->addCondition('FrmSales.sales_type="normal"');
		$criteria->compare('FrmSales.confirm_status',0,false);
		$criteria->compare('baseform.is_deleted','0',false);
		$criteria->compare('baseform.form_type','XSD',false);
		//$c = clone $criteria;
		$criteria->order = "baseform.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['sales_list']) ? intval($_COOKIE['sales_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
// 		$c->select = "sum(detail_amount) as total_amount,sum(weight) as total_weight,sum(weight*(detail_price)) as total_price,count(*) as total_num";
// 		$alldetail = SalesView::model()->find($c);
// 		$totaldata = array();
// 		$totaldata["amount"] = $alldetail->total_amount;
// 		$totaldata["weight"] = $alldetail->total_weight;
// 		$totaldata["price"] = $alldetail->total_price;
// 		$totaldata["total_num"] = $alldetail->total_num;
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
			$yidan = array("否","是");
			$i=1;
			foreach ($details as $each)
			{
				$sales = $each->FrmSales;
				$baseform = $sales->baseform;
				$price = ($each->price) * $each->weight;
				$da['data']=array(
						$i,
						$baseform->form_sn,
						$_status[$baseform->form_status],//销售单状态
						$baseform->form_time,
						'<span title="'.$sales->dictCompany->name.'">'.$sales->dictCompany->short_name.'</span>',
						$sales->dictTitle->short_name,
						$baseform->belong->nickname,
						$sales->is_yidan == 1?"是":"",
						$sales_type[$sales->sales_type],
						DictGoodsProperty::getProName($each->brand_id),
						DictGoodsProperty::getProName($each->product_id),
						str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)),
						DictGoodsProperty::getProName($each->rank_id),
						$each->length,
						$sales->warehouse->name,
						number_format($each->price),
						$each->amount,
						number_format($each->weight,3),
						'<span class="'.($sales->is_yidan?'red':'').'">'.number_format($price,2).'</span>',
						$each->amount - $each->output_amount,
						$each->send_amount."/".$each->amount,
						$each->output_amount."/".$each->amount,
						$sales->team->name,//业务组
						$baseform->created_by->nickname,//创建人
						$baseform->lastupdate->nickname,//修改人
						$baseform->approver->nickname,
						$baseform->approved_at >0?date("Y-m-d",$baseform->approved_at):'',
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment,0,15,"UTF-8").'</span>',
				);
				$da['group']=$i;
				$i++;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	
	}
	
	//导入保存销售单
	public static function setSales($data){
		$detail = $data['detail'];

		//如果是库存销售，判断库存
		if($data['main']->sales_type == "normal"){
			$ceated = true;
			foreach ($detail as $li){
				$model = new MergeStorage();
				$criteria=New CDbCriteria();
				$criteria->addCondition('product_id='.$li->product_id);
				$criteria->addCondition('brand_id='.$li->brand_id);
				$criteria->addCondition('texture_id='.$li->texture_id);
				$criteria->addCondition('rank_id='.$li->rank_id);
				$criteria->addCondition('length='.intval($li->length));
				//$criteria->addCondition('title_id='.$data['main']->title_id);
				$criteria->addCondition('warehouse_id='.$data['main']->warehouse_id);
				//$criteria->addCondition('is_transit<>1');
				$criteria->addCondition('is_deleted<>1');
				$criteria->order = "is_transit ASC";
				$model = $model->findAll($criteria);
				if($model){
					$have = false;
					foreach ($model as $m){
						$can_num = $m->left_amount-$m->retain_amount-$m->lock_amount;
						if($can_num >= $li->amount){
							$li->card_id = $m->id;
							$have = true;
							break;
						}
					}
					if(!$have){
						$ceated = false;
						break;
					}
				}else{
					return -1;
				}
			}
			if($ceated){
				$form=new Sales(0);
				$result1 = $form->createSubmitForm($data);
				//var_dump($result1);
				return $result1;
			}else{
				return -1;
			}
		}elseif($data['main']->sales_type == "dxxs"){
			$ceated = true;
			foreach ($detail as $li){
				$model = new Storage();
				$cri=New CDbCriteria();
				$cri->with = array("inputDetailDx");
				$cri->addCondition('inputDetailDx.product_id ='.$li->product_id);
				$cri->addCondition('inputDetailDx.brand_id ='.$li->brand_id);
				$cri->addCondition('inputDetailDx.texture_id ='.$li->texture_id);
				$cri->addCondition('inputDetailDx.rank_id ='.$li->rank_id);
				$cri->addCondition('inputDetailDx.length ='.$li->length);
				//$cri->addCondition('t.title_id='.$data['main']->title_id);
				$cri->addCondition('t.warehouse_id='.$data['main']->warehouse_id);
				$cri->addCondition('t.is_dx = 1');
				$cri->addCondition('t.is_deleted = 0');
				$model = $model->findAll($cri);
				
				if($model){
					$have = false;
					foreach ($model as $m){
						$can_num = $m->left_amount-$m->retain_amount-$m->lock_amount;
						if($can_num >= $li->amount){
							$li->card_id = $m->id;
							$have = true;
							break;
						}
					}
					if(!$have){
						$ceated = false;
						break;
					}
				}else{
					return -1;
				}
			}
			if($ceated){
				$form=new Sales(0);
				$result1 = $form->createSubmitForm($data);
				//var_dump($result1);
				return $result1;
			}else{
				return -1;
			}
		}else{
			$form=new Sales(0);
			$result1 = $form->createSubmitForm($data);
			return $result1;
		}
	}
	
	//获取业务员一月的销售重量
	public static function GetUserWeight($year,$month,$k){
		$userId = $k;
		$year = $year;
		$month = $month;
		$date = $year."-".$month;
		$model = new SalesView();
		$criteria=New CDbCriteria();
 		$criteria->addCondition("owned_by=$userId");
 		$criteria->addCondition("form_time like '%".$date."%'");
 		$criteria->addCondition('is_deleted=0');
 		$criteria->select = "sum(weight) as total_weight";
		$model = $model->find($criteria);
		if($model->total_weight){
			$weight = $model->total_weight;
			//获取提成金额
			$xml=readConfig();
			foreach ($xml->sales_percentage->step as $li){
				$step = $li->attributes();
				if($weight>=$step["min"] && $weight<$step["max"]){
					$money = $weight*$li->price;
				}
			}
			return array($weight,$money);
		}else{
			return array(0,0);
		}
	}
	
	//随机创建销售单
	public static function RandSales(){
		
		$user = array(1,9,10,11,13,15,16,17,18,19,20,21,22,23,24);//随机获取部分业务员数组
		$title_id = array(11,12,14);//公司抬头id
		$gao = array(7,8);//高开结算单位
		$commonForm = new CommonForms();
		$commonForm->form_type ="XSD";
		$commonForm->created_by = 1;
		$commonForm->created_at = time();
		$commonForm->form_time = date('Y-m-d');
		$commonForm->form_status = 'submited';
		$commonForm->owned_by = $user[array_rand($user)];
		$commonForm->comment = "随机生成的销售单";
		if($commonForm->insert()){
			$sales = new FrmSales();
			$sales->title_id = $title_id[array_rand($title_id)];
			$sales->customer_id = mt_rand(1,3759);
			$sales->owner_company_id = 1;
			$sales->sales_type = "normal";
			$sales->warehouse_id=mt_rand(1,4);
			$sales->comment="随机生成的销售单";
			$sales->amount = mt_rand(1,10);
			$sales->weight = mt_rand(2,20);
			if($sales->insert()){
				$sn =  "XSD".date("ymd").str_pad($sales->id,4,"0",STR_PAD_LEFT);
				$commonForm->form_id = $sales->id;
				$commonForm->form_sn = $sn;
				$commonForm->update();
				$detail = new SalesDetail();
				$detail->product_id=mt_rand(39,41);
				$detail->texture_id=mt_rand(61,69);
				$detail->brand_id=mt_rand(1,38);
				$detail->rank_id=mt_rand(44,60);
				$detail->length=9;
				$detail->amount=mt_rand(1,10);
				$detail->weight=mt_rand(2,20);
				$detail->price=mt_rand(1900,2000);
				$detail->bonus_price=0;
				$detail->card_id=2;
				$detail->fee = 1;
				$detail->frm_sales_id=$sales->id;
				$detail->insert();
				return true;
			}
		}
		return false;
// 		$data['main']["title_id"]=$title_id[array_rand($title_id)];
// 		$data['main']["customer_id"]=mt_rand(1,3759);
// 		$data['main']["sales_type"]="normal";
// 		$w_id = mt_rand(1,4);
// 		$data['main']["warehouse_id"]=$w_id;
// 		$data['main']["comment"]="随机生成的销售单";
// 		$data['detail']=array();
// 		$has_bonus_price = 0;
// 		$arr = $message[array_rand($message[$w_id])];
		
// 		$num = mt_rand(1,4);//随机生成数子，确定几条明细
// 		for($i=0;$i<$num;$i++)
// 		{
// 			$arr = $message[$w_id][array_rand($message[$w_id])];
// 			$temp=array();
// 			$temp['product_id']=$arr[0];
// 			$temp['texture_id']=$arr[1];
// 			$temp['brand_id']=$arr[2];
// 			$temp['rank_id']=$arr[3];
// 			$temp['length']=$arr[4];
// 			$weight = DictGoods::getUnitWeightID($temp);
// 			if($weight == 0){$weight=2;}
// 			$amount = mt_rand(1,10);
// 			$price = mt_rand(1800,2000);
// 			$temp['amount']=$amount;
// 			$temp['weight']=$amount*$weight;
// 			$temp['price']=$price;
// 			$is_high = mt_rand(1,20);
// 			if($is_high == 1){
// 				$temp['bonus_price']=mt_rand(1,100);
// 				$temp['gk_id'] = $gao[array_rand($gao)];
// 			}else{
// 				$temp['bonus_price']=0;
// 				$temp['gk_id'] = 0;
// 			}
// 			if($temp['bonus_price'] >0){$has_bonus_price = 1;}
// 			$temp['card_id']=$arr[5];
// 			$temp['total_amount'] = $amount * $weight * $price;
// 			array_push($data['detail'], (Object)$temp);
// 		}
// 		$data['main']["has_bonus_price"]=$has_bonus_price;
// 		$data['main']=(Object)$data['main'];
		
// 		$allform=new Sales($id);
// 		$result = $allform->createSubmitForm($data);
// 		if($result){
// 			$allform=new Sales($result);
// 			$result = $allform->approveForm();
// 		}
// 		return $result;
		
	}


	/*
	*	销售排行
	*
	***/
	public static function getSalesRank($search)
	{
		$model=new SalesView();
		$criteria=new CDbCriteria();
		$cri=new CDbCriteria();
		$condition='';
		$condition1='';
		if($search)
		{
			if($search['is_yidan']!='-1'&&$search['is_yidan']!='')
			{
				$criteria->addCondition('is_yidan='.$search['is_yidan']);
				$cri->addCondition('is_yidan='.$search['is_yidan']);
				$condition1.=" and f.is_yidan=".$search['is_yidan'];
			}
			if($search['owned_by'])
			{
				$criteria->addCondition('owned_by='.$search['owned_by']);
				$cri->addCondition('ownered_by='.$search['owned_by']);
				$condition1.=" and c.owned_by=".$search['owned_by'];
			}
			if($search['title_id'])
			{
				
				$criteria->addCondition("main_title_id={$search['title_id']}");
				$cri->addCondition("title_id={$search['title_id']}");
				$condition1.=" and f.title_id=".$search['title_id'];
			}
			if($search['customer_id'])
			{
				$criteria->addCondition('customer_id='.intval($search['customer_id']));
				$cri->addCondition('target_id='.$search['customer_id']);
				$condition1.=" and f.company_id=".$search['customer_id'];
			}
			if($search['client_id'])
			{
				$criteria->addCondition('client_id='.intval($search['client_id']));
				$cri->addCondition('target_id='.$search['client_id']);
				$condition1.=" and f.client_id=".$search['client_id'];
			}
			if($search['time_L'])
			{
				// $criteria->addCondition("UNIX_TIMESTAMP(form_time)>=".strtotime($search['time_L']));
				$time_L=strtotime($search['time_L']);
				$criteria->params['start_time']=$time_L;
				$condition1.=" and UNIX_TIMESTAMP(c.form_time) >=$time_L";
			}
			if($search['time_H'])
			{
				$time_H=strtotime($search['time_H'].'23:59:59');
				$criteria->addCondition("UNIX_TIMESTAMP(form_time)<=".strtotime($search['time_H']));
				$cri->addCondition("created_at<=$time_H");
				$condition1.=" and UNIX_TIMESTAMP(c.form_time) <=$time_H";
			}
		}else{
			$search['group']='customer_id';
		}
		if($time_H)
		{
			$criteria->params['end_time']=$time_H;
		}else{
			$criteria->params['end_time']=time();
		}
		$criteria->addCondition("form_status<>'delete'");
		$condition = $cri->condition ? ' AND '.$cri->condition : '';
		if($search['group'])
		{
			switch ($search['group']) {
				case 'customer_id':
					$group=$search['group'];
					$group_name='customer_short_name';	
					//往来余额
					$criteria->join.=" left join (select target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." and big_type='sales' and status!='delete' group by  target_id) fb on  fb.target_id = t.customer_id";
					//起始时间
					$condition .= " AND (created_at between :start_time and :end_time)";
					//销售明细
					$criteria->join .= " left join (select target_id, ifnull(abs(sum(fee)), 0) as 'sales_detail',ifnull(sum(amount), 0) as 'sales_amount' from turnover where turnover_type = 'XSMX'".$condition." and big_type='sales' and status!='delete' group by target_id) xsmx on  xsmx.target_id = t.customer_id";
					//已收款
					$criteria->join .= " left join (select target_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition."  and big_type='sales' and status<>'delete' and turnover_type='SKDJ' group by target_id) ac on  ac.target_id = t.customer_id";
					//已付款
					$criteria->join .= " left join (select  target_id, ifnull(abs(sum(fee)), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition."  and big_type='sales' and status<>'delete' and turnover_type='FKDJ'  group by  target_id) ap on  ap.target_id = t.customer_id";
					//销售退货
					$criteria->join .= " left join (select  target_id, ifnull(sum(fee), 0) as 'sales_return',ifnull(sum(amount), 0) as 'sales_return_amount' from turnover where turnover_type = 'XSTH'".$condition." and big_type='sales' and status<>'delete'  group by target_id) xsth on  xsth.target_id = t.customer_id";
					//销售折让
					$criteria->join .= " left join (select target_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition."  and big_type='sales' and status<>'delete'  group by  target_id) xszr on  xszr.target_id = t.customer_id";
					break;
				case 'ownandcustomer':
					$group='owned_by,customer_id';
					$group_name='concat_ws("/",owned_by_nickname,customer_short_name)';
										
					//往来余额

					$criteria->join.="left join (select turn.ownered_by,turn.target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover turn where turn.created_at <= :end_time".$condition."   and big_type='sales' and status!='delete'   group by  turn.target_id,turn.ownered_by) fb on fb.ownered_by = t.owned_by and fb.target_id = t.customer_id";
					//起始时间
					$condition .= " AND (created_at between :start_time and :end_time)";
					//销售明细
					$criteria->join .= " left join (select ownered_by,target_id, ifnull(abs(sum(fee)), 0) as 'sales_detail',ifnull(sum(amount), 0) as 'sales_amount' from turnover where turnover_type = 'XSMX'".$condition."  and big_type='sales' and status!='delete'   group by ownered_by, target_id) xsmx on xsmx.ownered_by = t.owned_by and xsmx.target_id = t.customer_id";
					//已收款
					$criteria->join .= " left join (select ownered_by,target_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition."  and big_type='sales' and status<>'delete' and turnover_type='SKDJ' group by target_id,ownered_by) ac on ac.ownered_by=t.owned_by and  ac.target_id = t.customer_id";
					//已付款
					$criteria->join .= " left join (select  ownered_by,target_id, ifnull(abs(sum(fee)), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition."  and big_type='sales' and status<>'delete' and turnover_type='FKDJ' group by  target_id,ownered_by) ap on ap.ownered_by=t.owned_by and  ap.target_id = t.customer_id";
					//销售退货
					$criteria->join .= " left join (select  ownered_by,target_id, ifnull(sum(fee), 0) as 'sales_return',ifnull(sum(amount), 0) as 'sales_return_amount' from turnover where turnover_type = 'XSTH'".$condition." and big_type='sales' and status<>'delete' group by target_id,ownered_by) xsth on xsth.ownered_by=t.owned_by and xsth.target_id = t.customer_id";
					//销售折让
					$criteria->join .= " left join (select ownered_by,target_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition."  and big_type='sales' and status<>'delete'  group by  target_id,ownered_by) xszr on xszr.ownered_by=t.owned_by and xszr.target_id = t.customer_id";
					break;
				case 'ownandclient':
					$group='t.owned_by,t.client_id';
					$group_name='concat_ws("/",owned_by_nickname,client_short_name)';
				
					//往来余额
					$criteria->join.="left join (select turn.ownered_by,turn.client_id, ifnull(sum(fee), 0) as 'final_balance' from turnover turn where turn.created_at <= :end_time".$condition." and big_type='sales' and status!='delete' group by  turn.client_id,turn.ownered_by) fb on fb.ownered_by = t.owned_by and fb.client_id = t.client_id";
					//起始时间
					$condition .= " AND (created_at between :start_time and :end_time)";
					//销售明细
					$criteria->join .= " left join (select ownered_by,client_id, ifnull(abs(sum(fee)), 0) as 'sales_detail',ifnull(sum(amount), 0) as 'sales_amount' from turnover where turnover_type = 'XSMX'".$condition." and big_type='sales' and status!='delete' group by ownered_by, client_id) xsmx on xsmx.ownered_by = t.owned_by and xsmx.client_id = t.client_id";
					//已收款
					$criteria->join .= " left join (select ownered_by,client_id, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition."  and big_type='sales' and status<>'delete' and turnover_type='SKDJ' group by client_id,ownered_by) ac on ac.ownered_by=t.owned_by and  ac.client_id = t.client_id";
					//已付款
					$criteria->join .= " left join (select  ownered_by,client_id, ifnull(abs(sum(fee)), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition."  and big_type='sales' and status<>'delete' and turnover_type='FKDJ' group by  client_id,ownered_by) ap on ap.ownered_by=t.owned_by and  ap.client_id = t.client_id";
					//销售退货
					$criteria->join .= " left join (select  ownered_by,client_id, ifnull(sum(fee), 0) as 'sales_return',ifnull(sum(amount), 0) as 'sales_return_amount' from turnover where turnover_type = 'XSTH'".$condition." and big_type='sales' and status<>'delete' group by client_id,ownered_by) xsth on xsth.ownered_by=t.owned_by and xsth.client_id = t.client_id";
					//销售折让
					$criteria->join .= " left join (select ownered_by,client_id, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition."  and big_type='sales' and status<>'delete'  group by  client_id,ownered_by) xszr on xszr.ownered_by=t.owned_by and xszr.client_id = t.client_id";
					break;
				case 'good_id':
					$group='brand_id,product_id,texture_id,rank_id,detail_length';
					$group_name='concat_ws("/",brand_name,product_name,texture_name,rank_name,detail_length)';
					$criteria->addCondition("UNIX_TIMESTAMP(form_time)>=".strtotime($search['time_L']));					
					//销售退货
					$criteria->join .= " left join (select  d.brand_id as xsthbrand_id,d.product_id as xsthproduct_id,d.texture_id as xsthtexture_id,d.rank_id as xsthrank_id,d.length as xsthlength, sum(case f.weight_confirm_status when 1 then d.fix_price*d.fix_weight else  d.return_price*d.return_weight end) as sales_return,
                        sum(case f.weight_confirm_status when 1 then d.fix_weight else d.return_weight end) as sales_return_amount from sales_return_detail d left join frm_sales_return f on f.id=d.sales_return_id left join common_forms c on c.form_id=f.id and c.form_type='XSTH'
                          where 1=1 ".$condition1." and c.form_status in ('unsubmit','submited','approve') group by d.brand_id,d.product_id,d.texture_id,d.rank_id,d.length) xsth on  xsth.xsthbrand_id = t.brand_id and xsth.xsthproduct_id=t.product_id and xsth.xsthtexture_id=t.texture_id and xsth.xsthrank_id=t.rank_id and xsth.xsthlength=t.detail_length";
                    $cri=null;
                    break;
                case 'owned_by':
                    $group=$search['group'];
                    $group_name='owned_by_nickname';
                    //往来余额
                    $criteria->join.=" left join (select ownered_by,ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." and big_type='sales' and status!='delete' group by  ownered_by) fb on  fb.ownered_by = t.owned_by";
                    //起始时间
                    $condition .= " AND (created_at between :start_time and :end_time)";
                    //销售明细
                    $criteria->join .= " left join (select ownered_by, ifnull(abs(sum(fee)), 0) as 'sales_detail',ifnull(sum(amount), 0) as 'sales_amount' from turnover where turnover_type = 'XSMX'".$condition."  and big_type='sales' and status!='delete'   group by ownered_by) xsmx on xsmx.ownered_by = t.owned_by ";
                    //已收款
                    $criteria->join .= " left join (select ownered_by, ifnull(sum(fee), 0) as 'already_collection' from turnover where turnover_direction = 'charged'".$condition."  and big_type='sales' and status<>'delete' and turnover_type='SKDJ' group by ownered_by) ac on  ac.ownered_by = t.owned_by";
                    //已付款
                    $criteria->join .= " left join (select  ownered_by, ifnull(abs(sum(fee)), 0) as 'already_paid' from turnover where turnover_direction = 'payed'".$condition." and big_type='sales' and status<>'delete' and turnover_type='FKDJ' group by  ownered_by) ap on  ap.ownered_by = t.owned_by";
                    //销售退货
                    $criteria->join .= " left join (select  ownered_by, ifnull(sum(fee), 0) as 'sales_return',ifnull(sum(amount), 0) as 'sales_return_amount' from turnover where turnover_type = 'XSTH'".$condition." and big_type='sales' and status<>'delete' group by ownered_by) xsth on  xsth.ownered_by = t.owned_by";
                    //销售折让
                    $criteria->join .= " left join (select ownered_by, ifnull(sum(fee), 0) as 'sales_rebate' from turnover where turnover_type = 'XSZR'".$condition." and big_type='sales' and status<>'delete' group by  ownered_by) xszr on  xszr.ownered_by = t.owned_by";
                    break;
                case 'brand_id':
                    $group=$search['group'];
                    $group_name='brand_name';
                    // $condition .= " AND (created_at between :start_time and :end_time)";
                    $criteria->addCondition("UNIX_TIMESTAMP(form_time)>=".strtotime($search['time_L']));
                    //销售退货
                    $criteria->join .= " left join (select  d.brand_id as xsthbrand_id, sum(case f.weight_confirm_status when 1 then d.fix_price*d.fix_weight else  d.return_price*d.return_weight end) as sales_return,
                        sum(case f.weight_confirm_status when 1 then d.fix_weight else d.return_weight end) as sales_return_amount from sales_return_detail d left join frm_sales_return f on f.id=d.sales_return_id left join common_forms c on c.form_id=f.id and c.form_type='XSTH'
                          where 1=1 ".$condition1." and c.form_status in ('unsubmit','submited','approve') group by d.brand_id) xsth on  xsth.xsthbrand_id = t.brand_id";
                    $cri=null;
                    break;
                case 'product_id':
                    $group=$search['group'];
                    $group_name='product_name';
                    $condition .= " AND (created_at between :start_time and :end_time)";
                    $criteria->addCondition("UNIX_TIMESTAMP(form_time)>=".strtotime($search['time_L']));
                    //销售退货
                    $criteria->join .= " left join (select  d.product_id as xsthproduct_id, sum(case f.weight_confirm_status when 1 then d.fix_price*d.fix_weight else  d.return_price*d.return_weight end) as sales_return,
                        sum(case f.weight_confirm_status when 1 then d.fix_weight else d.return_weight end) as sales_return_amount from sales_return_detail d left join frm_sales_return f on f.id=d.sales_return_id left join common_forms c on c.form_id=f.id and c.form_type='XSTH'
                          where 1=1 ".$condition1."  and c.form_status in ('unsubmit','submited','approve') group by d.product_id) xsth on  xsth.xsthproduct_id = t.product_id";
                    $cri=null;
                    break;
                case 'texture_id':
                    $group=$search['group'];
                    $group_name='texture_name';
                    $condition .= " AND (created_at between :start_time and :end_time)";
                    $criteria->addCondition("UNIX_TIMESTAMP(form_time)>=".strtotime($search['time_L']));
                    //销售退货
                    $criteria->join .= " left join (select  d.texture_id as xsthtexture_id, sum(case f.weight_confirm_status when 1 then d.fix_price*d.fix_weight else  d.return_price*d.return_weight end) as sales_return,
                        sum(case f.weight_confirm_status when 1 then d.fix_weight else d.return_weight end) as sales_return_amount from sales_return_detail d left join frm_sales_return f on f.id=d.sales_return_id left join common_forms c on c.form_id=f.id and c.form_type='XSTH'
                          where 1=1 ".$condition1."  and c.form_status in ('unsubmit','submited','approve') group by d.texture_id) xsth on  xsth.xsthtexture_id = t.texture_id";
                    $cri=null;
                    break;
                case 'rank_id':
                    $group=$search['group'];
                    $group_name='rank_name';
                    $condition .= " AND (created_at between :start_time and :end_time)";
                    $criteria->addCondition("UNIX_TIMESTAMP(form_time)>=".strtotime($search['time_L']));
                    //销售退货
                    $criteria->join .= " left join (select  d.rank_id as xsthrank_id, sum(case f.weight_confirm_status when 1 then d.fix_price*d.fix_weight else  d.return_price*d.return_weight end) as sales_return,
                        sum(case f.weight_confirm_status when 1 then d.fix_weight else d.return_weight end) as sales_return_amount from sales_return_detail d left join frm_sales_return f on f.id=d.sales_return_id left join common_forms c on c.form_id=f.id and c.form_type='XSTH'
                          where 1=1 ".$condition1." and c.form_status in ('unsubmit','submited','approve') group by d.rank_id) xsth on  xsth.xsthrank_id = t.rank_id";
                    $cri=null;
                    break;					
                default:						
                    break;
            }
			$name=FrmSales::$groupby[$search['group']];
		}		
		$criteria->group=$group;
		if($cri)
		{
			$criteria->select="xsmx.sales_detail,xsmx.sales_amount,$group,$group_name as each_name,
			fb.final_balance,ac.already_collection,ap.already_paid,xsth.sales_return,xsth.sales_return_amount,
			xszr.sales_rebate";			
		}else{
			$criteria->select="sum(weight) as sales_amount,sum(detail_fee) as sales_detail,$group,$group_name as each_name,xsth.sales_return,xsth.sales_return_amount";
			if(substr($_COOKIE['salesrank_order'], 0,3)=='yue')
			{
				setcookie('salesrank_order','nam_sha');
				$_COOKIE['salesrank_order']='nam_sha';
			}
		}
		$all = SalesView::model()->findAll($criteria);
		$total = array();
		if($all){
			foreach ($all as $li){
				$total[1] += $li->sales_amount - $li->sales_return_amount;
				$total[2] += $li->sales_detail;
				$total[3] += $li->sales_return;
				$total[4] += $li->already_collection;
				$total[5] += $li->already_paid;
				$total[6] += $li->sales_rebate;
			}
		}
		$pages=new CPagination();
		$pages->itemCount=$model->count($criteria);
		$pages->pageSize=intval($_COOKIE['salerank_list']) ? intval($_COOKIE['salerank_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		switch ($_COOKIE['salesrank_order']) {
			case 'yue_sha':
				$criteria->order='final_balance asc';		
				break;
			case 'yue_xia':
				$criteria->order='final_balance desc';		
				break;
			case 'wei_sha':
				$criteria->order='sales_amount asc';		
				break;
			case 'wei_xia':
				$criteria->order='sales_amount desc';
				break;
			case 'mon_sha':
				$criteria->order='sales_detail asc';
				break;
			case 'mon_xia':
				$criteria->order= 'sales_detail desc';
				break;
			case 'nam_sha':
				$criteria->order='convert(each_name using gbk) asc';
				break;
			case 'nam_xia':
				$criteria->order='convert(each_name using gbk) desc';
				break;
			default:
				break;
		}
		// $criteria->order='convert(each_name using gbk) asc';
		$datas=$model->findAll($criteria);
		$tableData=array();
		if($datas)
		{   $i=0;
			if($cri)
			{
				foreach ($datas as  $each) {
					switch ($search['group']) {
						case 'customer_id':
							$detail_url=Yii::app()->createUrl('turnover/index', array(
								'target_id' => $each->customer_id, 
								'start_time' => $search['time_L'], 
								'end_time' => $search['time_H'],
								'is_yidan' =>  $search['is_yidan'],
								'big_type'=>'sales',
								'ownered_by'=>$search['owned_by'],
							));
							break;
						case 'ownandcustomer':
							$detail_url=Yii::app()->createUrl('turnover/index', array(
									'target_id' => $each->customer_id, 
									'start_time' => $search['time_L'], 
									'end_time' => $search['time_H'],
									'is_yidan' =>  $search['is_yidan'],
									'big_type'=>'sales',
									'ownered_by'=>$each->owned_by,
								));
							break;
						case 'ownandclient':
							$detail_url=Yii::app()->createUrl('turnover/index', array(
								'client_id' => $each->client_id,
								'start_time' => $search['time_L'],
								'end_time' => $search['time_H'],
								'is_yidan' =>  $search['is_yidan'],
								'big_type'=>'sales',
								'ownered_by'=>$each->owned_by,
							));
							break;
						case 'owned_by':
							$detail_url=Yii::app()->createUrl('turnover/index', array(
									'target_id' => $search['customer_id'], 
									'start_time' => $search['time_L'], 
									'end_time' => $search['time_H'],
									'is_yidan' =>  $search['is_yidan'],
									'big_type'=>'sales',
									'ownered_by'=>$each->owned_by,
								));
							break;
						default:
							break;
					}
					$da['data']=array(
									$each->each_name,
									'<a href="'.$detail_url.'" target="_blank">'.number_format($each->final_balance,2).'</a>',
									$each->sales_amount-$each->sales_return_amount,								
									number_format($each->sales_detail,2),
									number_format($each->sales_return,2),
									number_format($each->already_collection,2),//已收款
									number_format($each->already_paid,2),
									number_format($each->sales_rebate,2)									
					);
					$da['group']=$i;
					array_push($tableData,$da);
					$i++;
				}
			}else{
				foreach ($datas as  $each) {
					$da['data']=array(
									$each->each_name,
									'',
									$each->sales_amount-$each->sales_return_amount,								
									number_format($each->sales_detail,2),
									number_format($each->sales_return,2),
									// '',
									'',//已收款
									'',
									'',									
					);
					$da['group']=$i;
					array_push($tableData,$da);
					$i++;
				}
			}

		}
		$name.='<img src="/images/shang.png" class="order_img" value="nam">';
		$can_order=$cri?'yes':'no';
		$tableHeader=array(
			array('name'=>$name,'class' =>"sort-disabled order_but",'width'=>"200px"),
			array('name'=>'往来余额<img src="/images/shang.png" class="order_img" value="yue" can_order="'.$can_order.'">','class' =>"flex-col sort-disabled order_but text-right",'width'=>"80px"),				
			array('name'=>'净销售重量<img src="/images/shang.png" class="order_img" value="wei">','class' =>"flex-col sort-disabled order_but text-right",'width'=>"80px"),//				
			array('name'=>'销售金额<img src="/images/shang.png" class="order_img" value="mon">','class' =>"flex-col sort-disabled order_but text-right",'width'=>"80px"),
			array('name'=>'退货金额','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),				
			array('name'=>'已收款','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),	
			array('name'=>'已付款','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),	
			array('name'=>'销售折让','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),
		);
		$totalData = array("合计：","",number_format($total[1],3),number_format($total[2],2),number_format($total[3],2),number_format($total[4],2),number_format($total[5],2),number_format($total[6],2),);
		return array($tableHeader,$tableData,$pages,$totalData);
	}
	
	/*
	 *	销售排行 优化
	 *
	 ***/
	public static function getNewSalesRank($search)
	{
		$model=new RankView();
		$criteria=new CDbCriteria();
		$cri=new CDbCriteria();
		$condition='';
		$condition1='';
		if($search)
		{
			if($search['is_yidan']!='-1'&&$search['is_yidan']!='')
			{
				$criteria->addCondition('is_yidan='.$search['is_yidan']);
				$cri->addCondition('is_yidan='.$search['is_yidan']);
				$condition1.=" and f.is_yidan=".$search['is_yidan'];
			}
			if($search['owned_by'])
			{
				$criteria->addCondition('owned_by='.$search['owned_by']);
				$cri->addCondition('ownered_by='.$search['owned_by']);
				$condition1.=" and c.owned_by=".$search['owned_by'];
			}
			if($search['title_id'])
			{
				$criteria->compare('main_title_id',$search['title_id']);
				$cri->addCondition('title_id='.$search['title_id']);
				$condition1.=" and f.title_id=".$search['title_id'];
			}
			if($search['customer_id'])
			{
				$criteria->addCondition('customer_id='.intval($search['customer_id']));
				$cri->addCondition('target_id='.$search['customer_id']);
				$condition1.=" and f.company_id=".$search['customer_id'];
			}
			if($search['client_id'])
			{
				$criteria->addCondition('client_id='.intval($search['client_id']));
				$cri->addCondition('client_id='.$search['client_id']);
				$condition1.=" and f.client_id=".$search['client_id'];
			}
			if($search['time_L'])
			{
				// $criteria->addCondition("UNIX_TIMESTAMP(form_time)>=".strtotime($search['time_L']));
				$time_L=strtotime($search['time_L']);
				//$criteria->params['start_time']=$time_L;
				$condition1.=" and UNIX_TIMESTAMP(c.form_time) >=$time_L";
			}
			if($search['time_H'])
			{
				$time_H=strtotime($search['time_H'].'23:59:59');
				$criteria->addCondition("UNIX_TIMESTAMP(form_time)<=".strtotime($search['time_H']));
				$cri->addCondition("created_at<=$time_H");
				$condition1.=" and UNIX_TIMESTAMP(c.form_time) <=$time_H";
			}
		}else{
			$search['group']='customer_id';
		}
		if($time_H)
		{
			$criteria->params['end_time']=$time_H;
		}else{
			$criteria->params['end_time']=time();
		}
		$criteria->addCondition("form_status<>'delete'");
		$condition = $cri->condition ? ' AND '.$cri->condition : '';
		
		if($search['group'])
		{
			switch ($search['group']) {
				case 'customer_id':
					$group=$search['group'];
					$group_name='customer_short_name';
					//往来余额
					$criteria->join.=" left join (select target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." and big_type='sales' and status!='delete' group by  target_id) fb on  fb.target_id = t.customer_id";
					break;
				case 'ownandcustomer':
					$group='owned_by,customer_id';
					$group_name='concat_ws("/",owned_by_nickname,customer_short_name)';
					//往来余额
                    $criteria->join.="left join (select turn.ownered_by,turn.target_id, ifnull(sum(fee), 0) as 'final_balance' from turnover turn where turn.created_at <= :end_time".$condition."   and big_type='sales' and status!='delete'   group by  turn.target_id,turn.ownered_by) fb on fb.ownered_by = t.owned_by and fb.target_id = t.customer_id";
                    break;
				case 'ownandclient':
                    $group='t.owned_by,t.client_id';
                    $group_name='concat_ws("/",owned_by_nickname,client_short_name)';
                    //往来余额
                    $criteria->join.="left join (select turn.ownered_by,turn.client_id, ifnull(sum(fee), 0) as 'final_balance' from turnover turn where turn.created_at <= :end_time".$condition." and big_type='sales' and status!='delete' group by  turn.client_id,turn.ownered_by) fb on fb.ownered_by = t.owned_by and fb.client_id = t.client_id";
                    break;
				case 'good_id':
                    $group='brand_id,product_id,texture_id,rank_id,detail_length';
                    $group_name='concat_ws("/",brand_name,product_name,texture_name,rank_name,detail_length)';
                    $criteria->addCondition("brand_id<>0 and product_id<>0 and texture_id<>0 and rank_id<>0");
                    $cri=null;
                    break;
				case 'owned_by':
                    $group=$search['group'];
                    $group_name='owned_by_nickname';
                    //往来余额
                    $criteria->join.=" left join (select ownered_by,ifnull(sum(fee), 0) as 'final_balance' from turnover where created_at <= :end_time".$condition." and big_type='sales' and status!='delete' group by  ownered_by) fb on  fb.ownered_by = t.owned_by";
                    break;
				case 'brand_id':
                    	$group=$search['group'];
                    	$group_name='brand_name';
                    	$criteria->addCondition("brand_id<>0");
	                    	$cri=null;
	                    	break;
				case 'product_id':
					$group=$search['group'];
                    $group_name='product_name';
                    $criteria->addCondition("product_id<>0");
                    $cri=null;
	                break;
				case 'texture_id':
                    $group=$search['group'];
                    $group_name='texture_name';
                    $criteria->addCondition("texture_id<>0");
                    $cri=null;
                    break;
				case 'rank_id':
                    $group=$search['group'];
                    $group_name='rank_name';
                    $criteria->addCondition("rank_id<>0");
					$cri=null;
					break;
				default:
					break;
			}
               $name=FrmSales::$groupby[$search['group']];
        }
        $criteria->addCondition("UNIX_TIMESTAMP(form_time)>=".strtotime($search['time_L']));
		$criteria->group=$group;
		
		if($cri)
		{
			$criteria->select="sum(case form_type when 'XSD' then detail_fee end) as sales_detail,sum(weight) as sales_amount,$group,$group_name as each_name,
			fb.final_balance,sum(case form_type when 'SKDJ' then detail_fee end) as already_collection,sum(case form_type when 'FKDJ' then detail_fee end) as already_paid,
			sum(case form_type when 'XSTH' then detail_fee end) as sales_return,sum(case form_type when 'XSZR' then detail_fee end) as sales_rebate";
		}else{
			$criteria->select="sum(weight) as sales_amount,sum(case form_type when 'XSD' then detail_fee end) as sales_detail,$group,$group_name as each_name,sum(case form_type when 'XSTH' then detail_fee end) as sales_return";
			if(substr($_COOKIE['salesrank_order'], 0,3)=='yue')
			{
				setcookie('salesrank_order','nam_sha');
				$_COOKIE['salesrank_order']='nam_sha';
			}
		}
		$all = RankView::model()->findAll($criteria);
		
		$total = array();
		if($all){
			foreach ($all as $li){
				$total[1] += $li->sales_amount;
				$total[2] += $li->sales_detail;
				$total[3] += $li->sales_return;
				$total[4] += $li->already_collection;
				$total[5] += $li->already_paid;
				$total[6] += $li->sales_rebate;
			}
		}
		$pages=new CPagination();
		$pages->itemCount=$model->count($criteria);
		$pages->pageSize=intval($_COOKIE['salerank_list']) ? intval($_COOKIE['salerank_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		switch ($_COOKIE['salesrank_order']) {
				case 'yue_sha':
					$criteria->order='final_balance asc';
					break;
				case 'yue_xia':
					$criteria->order='final_balance desc';
					break;
				case 'wei_sha':
					$criteria->order='sales_amount asc';
					break;
				case 'wei_xia':
					$criteria->order='sales_amount desc';
					break;
				case 'mon_sha':
					$criteria->order='sales_detail asc';
					break;
				case 'mon_xia':
					$criteria->order= 'sales_detail desc';
					break;
				case 'nam_sha':
					$criteria->order='convert(each_name using gbk) asc';
					break;
				case 'nam_xia':
					$criteria->order='convert(each_name using gbk) desc';
					break;
				default:
					break;
		}
		// $criteria->order='convert(each_name using gbk) asc';
		$datas=$model->findAll($criteria);
		$tableData=array();
		if($datas){
			$i=0;
			if($cri){
				foreach ($datas as  $each) {
					switch ($search['group']){
						case 'customer_id':
							$detail_url=Yii::app()->createUrl('turnover/index', array(
									'target_id' => $each->customer_id,
									'start_time' => $search['time_L'],
									'end_time' => $search['time_H'],
									'is_yidan' =>  $search['is_yidan'],
									'big_type'=>'sales',
									'ownered_by'=>$search['owned_by'],
							));
							break;
						case 'ownandcustomer':
							$detail_url=Yii::app()->createUrl('turnover/index', array(
									'target_id' => $each->customer_id,
									'start_time' => $search['time_L'],
									'end_time' => $search['time_H'],
									'is_yidan' =>  $search['is_yidan'],
									'big_type'=>'sales',
									'ownered_by'=>$each->owned_by,
							));
							break;
						case 'ownandclient':
							$detail_url=Yii::app()->createUrl('turnover/index', array(
							'client_id' => $each->client_id,
							'start_time' => $search['time_L'],
							'end_time' => $search['time_H'],
							'is_yidan' =>  $search['is_yidan'],
							'big_type'=>'sales',
							'ownered_by'=>$each->owned_by,
							));
							break;
						case 'owned_by':
							$detail_url=Yii::app()->createUrl('turnover/index', array(
									'target_id' => $search['customer_id'],
									'start_time' => $search['time_L'],
									'end_time' => $search['time_H'],
									'is_yidan' =>  $search['is_yidan'],
									'big_type'=>'sales',
									'ownered_by'=>$each->owned_by,
							));
							break;
						default:
							break;
					}
					$da['data']=array(
							$each->each_name,
							'<a href="'.$detail_url.'" target="_blank">'.number_format($each->final_balance,2).'</a>',
							number_format($each->sales_amount,3),
							number_format($each->sales_detail,2),
							number_format($each->sales_return,2),
							number_format($each->already_collection,2),//已收款
							number_format($each->already_paid,2),
							number_format($each->sales_rebate,2)
					);
					$da['group']=$i;
					array_push($tableData,$da);
					$i++;
				}
			}else{
				foreach ($datas as  $each) {
					$da['data']=array(
							$each->each_name,
								'',
							number_format($each->sales_amount,3),
							number_format($each->sales_detail,2),
							number_format($each->sales_return,2),
							// '',
							'',//已收款
							'',
							'',
					);
					$da['group']=$i;
					array_push($tableData,$da);
					$i++;
				}
			}
	
		}
		$name.='<img src="/images/shang.png" class="order_img" value="nam">';
		$can_order=$cri?'yes':'no';
			$tableHeader=array(
				array('name'=>$name,'class' =>"sort-disabled order_but",'width'=>"200px"),
				array('name'=>'往来余额<img src="/images/shang.png" class="order_img" value="yue" can_order="'.$can_order.'">','class' =>"flex-col sort-disabled order_but text-right",'width'=>"80px"),
				array('name'=>'净销售重量<img src="/images/shang.png" class="order_img" value="wei">','class' =>"flex-col sort-disabled order_but text-right",'width'=>"80px"),//
				array('name'=>'销售金额<img src="/images/shang.png" class="order_img" value="mon">','class' =>"flex-col sort-disabled order_but text-right",'width'=>"80px"),
				array('name'=>'退货金额','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),
				array('name'=>'已收款','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),
				array('name'=>'已付款','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),
				array('name'=>'销售折让','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),
			);
			if($cri){
				$totalData = array("合计：","",number_format($total[1],3),number_format($total[2],2),number_format($total[3],2),number_format($total[4],2),number_format($total[5],2),number_format($total[6],2),);
			}else{
				$totalData = array("合计：","",number_format($total[1],3),number_format($total[2],2),number_format($total[3],2),"","","");
			}
			return array($tableHeader,$tableData,$pages,$totalData);
	}
	
	//检查销售单是否已开票
	public static function CheckInvoice($post,$baseform){
		if($baseform->form_status == "approve"){
			for($i=0;$i<count($post['product']);$i++)
			{
				$price=numChange($post['money'][$i]);
				$old = $post["old"][$i];
				$id=$post['details_id'][$i];
				//销售单已审核且修改了价格，判断可开票信息
				if($price != $old){
					$detailfor = DetailForInvoice::model()->find("form_id={$baseform->id} and detail_id={$id} and checked_money>0");
					if($detailfor){
						return false;
					}else{
						return true;
					}
				}else{
					return true;
				}
			}
		}else{
			return true;	
		}
	}
	
	
	
	
	public static function getButtons($form_sn)	
	{
		$each=SalesView::model()->find('form_sn="'.$form_sn.'"');		
		if(!$each)return;
		$price = $each->detail_price * $each->weight;
				
		if($each->form_status=='unsubmit')
		{
			$type_sub="submit";
			$title_sub="提交";
			$img_url = "/images/tijiao.png";
		}elseif($each->form_status=='submited')
		{
			$type_sub="cancle";
			$title_sub="取消提交";
			$img_url = "/images/qxtj.png";
		}
		if($each->main_type == "xxhj"){
			$edit_url = Yii::app()->createUrl('FrmSales/xsupdate',array('id'=>$each->common_id,"fpage"=>$_REQUEST['page']));
		}else if($each->main_type == "dxxs"){
			$edit_url = Yii::app()->createUrl('FrmSales/dxupdate',array('id'=>$each->common_id,"fpage"=>$_REQUEST['page']));
		}else{
			$edit_url = Yii::app()->createUrl('FrmSales/update',array('id'=>$each->common_id,"fpage"=>$_REQUEST['page']));
		}
		$sub_url =  Yii::app()->createUrl('FrmSales/submit',array('id'=>$each->common_id,'type'=>$type_sub,'last_update'=>$each->last_update));
		$del_url= Yii::app()->createUrl('FrmSales/deleteform',array('id'=>$each->common_id,'last_update'=>$each->last_update));
		$checkP_url=Yii::app()->createUrl('FrmSales/check',array('id'=>$each->common_id,'type'=>'pass','last_update'=>$each->last_update));
		$checkC_url=Yii::app()->createUrl('FrmSales/check',array('id'=>$each->common_id,'type'=>'cancle','last_update'=>$each->last_update));
		$complete_url = Yii::app()->createUrl('FrmSales/complete',array('id'=>$each->common_id,'last_update'=>$each->last_update));
		$cancelcomplete_url = Yii::app()->createUrl('FrmSales/cancelcomplete',array('id'=>$each->common_id,'last_update'=>$each->last_update));
		if($each->is_jxc == 1){
			if($each->detail_send_amount == 0){
				$distribution_url = Yii::app()->createUrl('FrmSend/create',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
			}else{
				$distribution_url = Yii::app()->createUrl('FrmSend/index',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
			}
		}else{
			$distribution_url="javascript:void(0);";
		}
		$checkD_url=Yii::app()->createUrl('FrmSales/check',array('id'=>$each->common_id,'type'=>'deny','last_update'=>$each->last_update));
		$br_url = Yii::app()->createUrl("billRecord/index", array('frm_common_id' => $each->common_id, "fpage"=>$_REQUEST['page']));
		// if($each->can_push){
			if($each->detail_output_amount == 0){
				if($each->main_type == "normal"){
					$output_url = Yii::app()->createUrl('FrmOutput/create',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
				}else if($each->main_type == "xxhj"){
					$output_url = Yii::app()->createUrl('FrmOutput/xscreate',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
				}else{
					$output_url = Yii::app()->createUrl('FrmOutput/dxcreate',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
				}
			}else{
				$output_url = Yii::app()->createUrl('FrmOutput/index',array('id'=>$each->main_id,"fpage"=>$_REQUEST['page']));
			}
		// }else{
			// $output_url = "javascript:void(0);";
		// }
		$sk_url = Yii::app()->createUrl("formBill/create", array('type' => "SKDJ", 'bill_type' => "XSSK", 'common_id' => $each->common_id));
		$detail_url = Yii::app()->createUrl('FrmSales/detail',array('id'=>$each->common_id,"fpage"=>$_REQUEST['page']));
		$purchase_url = Yii::app()->createUrl('purchase/create',array('comm_id'=>$each->common_id,"type"=>"xxhj"));
		$gk_url = Yii::app()->createUrl("formBill/create", array('type' => "FKDJ", 'bill_type' => "GKFK", 'common_id' => $each->common_id));
		$tuisong_url = Yii::app()->createUrl('FrmSales/push',array('id'=>$each->common_id,'last_update'=>$each->last_update));
		$canceltuisong_url = Yii::app()->createUrl('FrmSales/cancelpush',array('id'=>$each->common_id,'last_update'=>$each->last_update));
		$print_url = Yii::app()->createUrl('print/print', array('id' => $each->common_id));
		$preview_url = Yii::app()->createUrl('FrmSales/preview', array('id' => $each->common_id));
			//未完成
		$operate='';
		if($each->confirm_status == 0){
			//未提交
			if($each->form_status=='unsubmit'){
				if(checkOperation("销售单:新增")){
					$operate='<div class="cz_list_btn" canpush="'.$each->can_push.'"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">'
										.'<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>'
										.'<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>'
										.'<span class="delete_form" lastdate="'.$each->last_update.'" title="作废" id="/index.php/FrmSales/deleteform/'.$each->common_id.'" salesid="'.$each->main_id.'"><img src="/images/zuofei.png"></span>'
										.'<span class="more_but" title="更多"><img src="/images/gengduo.png"></span>'
									.'<div class="cz_list_btn_more" num="0" style="width:120px">';
					if (checkOperation("打印")) {
						$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span>';
					}
					$operate.='</div></div>';
				}else{
					$operate='<div class="cz_list_btn">';
						if (checkOperation("打印")) {
							$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span>';
						}
						$operate.='</div>';
				}
			}
			//已提交
			if($each->form_status=='submited'){
				$but_num = 0;
				$operate='<div class="cz_list_btn" canpush="'.$each->can_push.'"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
				if(checkOperation("销售单:新增")){
						$but_num += 2;
						$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a><abc></abc>';
					$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span><abc></abc>';
				}
	
				if(checkOperation("销售单:审核")){
					$operate.='<span class="check_form"   lastdate="'.$each->last_update.'" id="/index.php/FrmSales/check/'.$each->common_id.'" title="审核" str="单号:'.$each->form_sn.',确定审核通过此销售单吗？" onclick="setCheck_unrefresh(this);"><img src="/images/shenhe.png"></span><abc></abc>';
					$but_num ++;
				}
				if(checkOperation("配送单:新增")){
						$operate.='<a class="update_b" href="'.$distribution_url.'" title="'.($each->is_jxc==1?"生成配送":"非接入仓库，请传真配送").'"><span><img src="/images/'.($each->is_jxc==1?"":"un").'psd.png"></span></a><abc></abc>';
						$but_num ++;
				}
				if(checkOperation("打印")){
					$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span><abc></abc>';
					$but_num ++;
				}
							
				if($but_num > 4){
					$one=substr($operate,strpos($operate,'<abc></abc>')+11);
					$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
					$two=substr($one,strpos($one,'<abc></abc>')+11);
					$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
					$three=substr($two,strpos($two,'<abc></abc>')+11);
					$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
					$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
						.'<div class="cz_list_btn_more" style="width:120px" num="0">'.$three;
					$operate.='</div></div>';
				}else{
					$operate.='</div>';
				}
			}
			//已审核
			if($each->form_status=='approve'){
				$but_num = 0;
				$operate='<div class="cz_list_btn" canpush="'.$each->can_push.'"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
				if(checkOperation("销售单:新增")){
						$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a><abc></abc>';
					$but_num ++;
				}
				if(checkOperation("配送单:新增")){
						$operate.='<a class="update_b" href="'.$distribution_url.'" title="'.($each->is_jxc==1?"生成配送":"非接入仓库，请传真配送").'"><span><img src="/images/'.($each->is_jxc==1?"":"un").'psd.png"></span></a><abc></abc>';
								$but_num ++;
				}
				if($type == "outview"){
					if(checkOperation("出库单:新增")){
						// $operate.='<a class="update_b" href="'.$output_url.'" title="'.($each->can_push?"出库":"未推送不能出库").'"><span><img src="/images/'.($each->can_push?"":"un").'chuku.png"></span></a><abc></abc>';

						$operate.='<a class="update_b" href="'.$output_url.'" title="出库"><span><img src="/images/chuku.png"></span></a><abc></abc>';
						$but_num ++;
					}
				}
				
				$checkCApply_url=Yii::app()->createUrl('FrmSales/applyCancleCheck',array('id'=>$each->common_id,'last_update'=>$each->last_update));
				$checkRView_url=Yii::app()->createUrl('FrmSales/reasonView',array('id'=>$each->common_id,'last_update'=>$each->last_update));
				$haveReason=CanclecheckRecord::model()->exists('common_id='.$each->common_id.' and status=0');
				if($haveReason)
				{					
					if(checkOperation("销售单:审核")){
						$operate.='<span class="submit_form cancelCheck real_cancle" url="'.$checkC_url.'"  common_id="'.$each->common_id.'" sales_type="'.$each->main_type.'"  title="取消审核" salesid="'.$each->main_id.'"><img src="/images/qxsh.png"></span><abc></abc>';
						$but_num ++;
					}
					$operate.='<span class="review_reason" url="'.$checkRView_url.'"  sales_type="'.$each->main_type.'"   title="查看取消审核原因" salesid="'.$each->main_id.'"><img src="/images/CKQXSHSQ.png"></span><abc></abc>';
					$but_num++;
				}else{
					$operate.='<span class="submit_form cancelCheck" url="'.$checkCApply_url.'"  sales_type="'.$each->main_type.'"  title="申请取消审核" salesid="'.$each->main_id.'"><img src="/images/QXSHSQ.png"></span><abc></abc>';
					$but_num ++;
				}				
				
// 				if(checkOperation("销售单:审核")){
// 						$operate.='<span class="submit_form cancelCheck" url="'.$checkC_url.'" title="取消审核" salesid="'.$each->main_id.'"><img src="/images/qxsh.png"></span><abc></abc>';
// 						$but_num ++;
// 				}
				if(checkOperation("销售单:推送") && $each->can_push == 0){
						$operate.='<span class="submit_form" url="'.$tuisong_url.'" title="可提货"><img src="/images/kts.png"></span><abc></abc>';
					$but_num ++;
				}
				if(checkOperation("销售单:推送") && $each->can_push == 1){
						$operate.='<span class="submit_form" url="'.$canceltuisong_url.'" title="取消提货"><img src="/images/bkts.png"></span><abc></abc>';
					$but_num ++;
				}
				if($each->main_type == "xxhj"){
					if(checkOperation("采购单:新增")){
						$operate.='<a class="update_b" href="'.$purchase_url.'" title="销售采购"><span><img src="/images/caigou.png"></span></a><abc></abc>';
						$but_num ++;
					}
				}
				if(checkOperation("销售运费:新增")){
						$operate.='<a class="update_b" href="'.$br_url.'" title="运费登记"><span><img src="/images/yfdj.png"></span></a><abc></abc>';
					$but_num ++;
				}
				if(checkOperation("收款登记:新增")){
						$operate.='<a class="update_b" href="'.$sk_url.'" title="收款登记"><span><img src="/images/shoukuai.png"></span></a><abc></abc>';
					$but_num ++;
				}
				if(checkOperation("销售单:完成")){
						$operate.='<span class="submit_form" url="'.$complete_url.'" title="完成"><img src="/images/wancheng.png"></span><abc></abc>';
					$but_num ++;
				}
				if($type != "outview"){
					if(checkOperation("出库单:新增")){
						// $operate.='<a class="update_b" href="'.$output_url.'" title="'.($each->can_push?"出库":"未推送不能出库").'"><span><img src="/images/'.($each->can_push?"":"un").'chuku.png"></span></a><abc></abc>';

						$operate.='<a class="update_b" href="'.$output_url.'" title="出库"><span><img src="/images/chuku.png"></span></a><abc></abc>';
						$but_num ++;
					}
				}
				if(checkOperation("打印")){
						$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span><abc></abc>';
						$but_num ++;
				}
				if(checkOperation("销售退货:新增")){
					$operate.='<span class="submit_form" url="'.''.'" title="退货"><img src="/images/tuihuo.png"></span><abc></abc>';
					$but_num ++;
				}
								
				if($each->has_bonus_price == 1){
					if(checkOperation("付款登记:新增")){
						$operate.='<a class="update_b" href="'.$gk_url.'" title="高开付款"><span><img src="/images/gkfk.png"></span></a><abc></abc>';
						$but_num ++;
					}
				}
				if($but_num > 4){
					$one=substr($operate,strpos($operate,'<abc></abc>')+11);
					$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
					$two=substr($one,strpos($one,'<abc></abc>')+11);
					$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
					$three=substr($two,strpos($two,'<abc></abc>')+11);
					$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
					$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
						.'<div class="cz_list_btn_more" style="width:120px" num="0">'.$three;
					$operate.='</div></div>';
				}else{
					$operate.='</div>';
			}
		}
	}else{
		$but_num = 0;
		$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
		$operate.='<span class="submit_form" url="'.$cancelcomplete_url.'" title="取消完成"><img src="/images/qxwc.png"></span><abc></abc>';
		$but_num++;
		if (checkOperation("打印")) {
				$operate.='<span><a target="_blank" class="update_b" href="'.$preview_url.'" title="打印预览"><img src="/images/dayin.png"></a></span><abc></abc>';
				$but_num++;
		}
		if(checkOperation("销售运费:新增")){
			$operate.='<a class="update_b" href="'.$br_url.'" title="运费登记"><span><img src="/images/yfdj.png"></span></a><abc></abc>';
			$but_num++;
		}
		if(checkOperation("收款登记:新增")){
			$operate.='<a class="update_b" href="'.$sk_url.'" title="收款登记"><span><img src="/images/shoukuai.png"></span></a><abc></abc>';
			$but_num++;
		}
		if($each->has_bonus_price == 1){
			if(checkOperation("付款登记:新增")){
				$operate.='<a class="update_b" href="'.$gk_url.'" title="高开付款"><span><img src="/images/gkfk.png"></span></a><abc></abc>';
				$but_num ++;
			}
		}
		if($but_num > 4){
			$one=substr($operate,strpos($operate,'<abc></abc>')+11);
			$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
			$two=substr($one,strpos($one,'<abc></abc>')+11);
			$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
			$three=substr($two,strpos($two,'<abc></abc>')+11);
			$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
			$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
					.'<div class="cz_list_btn_more" style="width:120px" num="0">'.$three;
			$operate.='</div></div>';
		}else{
			$operate.='</div>';
		}
	}
	return $operate;

}	
}

