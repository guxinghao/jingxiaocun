<?php
//统计用
class TableController extends AdminBaseController
{

	//销售排行
	public function actionSalesRank()
	{
		$this->pageTitle='销售排行';
		
		$team_array=Team::getTeamList('array');		
		$user_array=User::getUserList();
		$search=array();
		if(isset($_REQUEST['search'])){
			$search=$_REQUEST['search'];
		}else{
			$search['group']='customer_id';
		}
		if(!$search['time_L']||strtotime($search['time_L'])<strtotime(Yii::app()->params['turn_time']))
		{
			$search['time_L']=date('Y-m-d',Yii::app()->params['turn_time']);
		}
		if(!$_COOKIE['salesrank_order'])
		{
			setcookie('salesrank_order','nam_sha');
			$_COOKIE['salesrank_order']='nam_sha';
		}
		list($tableHeader,$tableData,$pages,$totalData)=FrmSales::getSalesRank($search);
		
		$titles = DictTitle::getComs("json");
		$targets = DictCompany::getAllComs("json");
		$this->render('salesrank',array(
			'titles'=>$titles,
			'targets'=>$targets,
			'teams'=>$team_array,
			'users'=>$user_array,
			'tableHeader'=>$tableHeader,
			'tableData'=>$tableData,
			'pages'=>$pages,
			'search'=>$search,
			'totalData'=>$totalData,
		));
	}


	//销售排行 优化版
	public function actionNewSalesRank()
	{
		$this->pageTitle='销售排行';
	
		$team_array=Team::getTeamList('array');
		$user_array=User::getUserList();
		$search=array();
		if(isset($_REQUEST['search'])){
			$search=$_REQUEST['search'];
		}else{
			$search['group']='customer_id';
		}
		if(!$search['time_L']||strtotime($search['time_L'])<strtotime(Yii::app()->params['turn_time']))
		{
			$search['time_L']=date('Y-m-d',Yii::app()->params['turn_time']);
		}
		if(!$_COOKIE['salesrank_order'])
		{
			setcookie('salesrank_order','nam_sha');
			$_COOKIE['salesrank_order']='nam_sha';
		}
		list($tableHeader,$tableData,$pages,$totalData)=FrmSales::getNewSalesRank($search);
	
		$titles = DictTitle::getComs("json");
		$targets = DictCompany::getAllComs("json");
		$this->render('newsalesrank',array(
				'titles'=>$titles,
				'targets'=>$targets,
				'teams'=>$team_array,
				'users'=>$user_array,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'totalData'=>$totalData,
		));
	}
	
	/*
	 * 库存预估利润
	 */
	public function actionStorageProfit()
	{
		$this->pageTitle='库存预估利润';
		// $coms=DictCompany::getComs('json','is_customer');
		// $vendor_array=DictCompany::getVendorList('json');
		$titles=DictTitle::getComs('json');
		$vendor_array=DictCompany::getVendorList('json');
		$user_array=User::getUserList();
		$ware=Warehouse::getWareList('array',1);
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['time_H']==''){
				$search['time_H']=date('Y-m-d');
			}
		}else{
			if(isset($_REQUEST['search_url']))
				$search=(Array)json_decode($_REQUEST['search_url']);
			if(!$search['time_H'])
				$search['time_H']=date('Y-m-d');
		}
		list($tableHeader,$tableData,$pages,$totalData)=ProfitStorage::getStorageProfit($search);
	
		$this->render('storageprofit',array(
				'coms'=>$coms,
				'titles'=>$titles,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'search'=>$search,
				'pages'=>$pages,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'users'=>$user_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
				'pages'=>$pages,
				'totalData'=>$totalData,
				'warehouses'=>$ware,
		));
	}

	//采销利润
	public function actionBSprofit()
	{
		$this->pageTitle='采销利润';
		$titles=DictTitle::getComs('json');		
		$user_array=User::getUserList();
		$coms=DictCompany::getComs('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');
		$ware=Warehouse::getWareList('array',1);
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['time_L']==''||$search['time_L']<date('Y-m-d',Yii::app()->params['turn_time'])){
				$search['time_L']=date('Y-m-d',Yii::app()->params['turn_time']);
			}
		}else{
			if(isset($_REQUEST['search_url']))
				$search=(Array)json_decode($_REQUEST['search_url']);
			if(!$search['time_L'])
			{
				$search['time_L']=date('Y-m-d',Yii::app()->params['turn_time']);
			}			
		}
		list($tableHeader,$tableData,$pages,$totalData)=ProfitCollecting::getProfitData($search);
		
		$this->render('bsprofit',array(
				'coms'=>$coms,
				'titles'=>$titles,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'search'=>$search,
				'pages'=>$pages,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'users'=>$user_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
				'pages'=>$pages,
				'totalData'=>$totalData,
				'warehouses'=>$ware,
		));
	}
		
	/*
	 * 利润汇总
	 */
	public  function actionTotalProfit()
	{
		
		$this->pageTitle='利润统计';
		$coms=DictCompany::getComs('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');
		$user_array=User::getUserList();
		$team_array=Team::getTeamList('array');
		$ware=Warehouse::getWareList('array',1);
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		$data=ProfitCollecting::totalProfitData($search);
		$this->render('totalprofit',array(
				'data'=>$data,
				'coms'=>$coms,
				'search'=>$search,
				'pages'=>$pages,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'teams'=>$team_array,
				'users'=>$user_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
				'warehouses'=>$ware,
		));
	}
	
	
	/*
	 * buy-sale profit initial script 
	 */
	public function  actionInitBsprofit()
	{
		//read confit data
		set_time_limit(5000);
		echo microtime(),'<br>';
		$connection=Yii::app()->db;
		$sql="TRUNCATE steel_trade.profit_collecting";
		$connection->createCommand($sql)->execute();
		$xml=readConfig();
		$this->confirmedSales($xml);
		$this->unConfirmedSales($xml);
		$this->xxhjSales($xml);
		$this->dxxsSales($xml);
		echo microtime(),'<br>';
		
		@session_destroy();
	}
	
	public function confirmedSales($xml,$id='')
	{
		$p_subsidy=0;
		foreach ($xml->sales_percentage->step as $e)
		{
				$p_subsidy=$e->price;
				break;
		}
		//已审单，按出库明细
		$connection=Yii::app()->db;
		// 		$new_ids=array();
		$turn_time=Yii::app()->params['turn_time']?Yii::app()->params['turn_time']:0;
		$sql='select d.*,fs.id as fs_id,fs.is_yidan,fs.shipment,fs.rebate_fee,fs.title_id,fs.customer_id,fs.warehouse_id,fs.has_bonus_price,fs.subsidy,c.id as cid,c.form_status,
				c.form_sn,c.form_time,c.owned_by,sto.purchase_id,sto.cost_price,sto.input_detail_id,sto.frm_input_id,sto.is_price_confirmed,sto.is_yidan as sto_isyidan,sto.invoice_price as sto_invoice_price from output_detail d
				 left join frm_output f on f.id=d.frm_output_id left join common_forms oc on oc.form_id=f.id and oc.form_type="CKD"
				left join frm_sales fs on fs.id=f.frm_sales_id left join common_forms c on c.form_id=fs.id and c.form_type="XSD"
				left join storage sto on d.storage_id=sto.id  
				left join dict_goods_property dp_brand on dp_brand.id=d.brand_id 
				 where  fs.sales_type="normal"   and  c.form_status="approve"  and dp_brand.short_name<>"贵航" and oc.form_status="submited" and fs.confirm_status=1 and UNIX_TIMESTAMp(c.form_time)>='.$turn_time;
		if($id)$sql.=' and fs.id='.$id;
		$command=$connection->createCommand($sql);
		$res=$command->queryAll();
		foreach ($res as $each)
		{
			//ffffffffffffind the related sale_deetail !!!!
			$sale_detail=SalesDetail::model()->findByAttributes(array('brand_id'=>$each['brand_id'],'product_id'=>$each['product_id'],
					'texture_id'=>$each['texture_id'],'rank_id'=>$each['rank_id'],'length'=>$each['length'],'frm_sales_id'=>$each['fs_id']));
			if(floatval($sale_detail->price)<=100)continue;
// 			if($each['warehouse_id']==3&&($each['brand_id']==6||$each['brand_id']==17))continue;
			//look for cost information
			if($each['purchase_id'])
			{
				$purchase=FrmPurchase::model()->with('baseform')->findByPk($each['purchase_id']);
				$pur_base=$purchase->baseform;
			}else{
				$frm_input=FrmInput::model()->findByPk($each['frm_input_id']);
				$pur_base=CommonForms::model()->findByPk($frm_input->purchase_id);
				$purchase='';
			}
			$model=new ProfitCollecting();
			$model->confirmed=$each['is_price_confirmed'];
			$model->sales_id =$each['fs_id'];
			$model->form_sn =$each['form_sn'];
			$model->sales_date = $each['form_time'];
			$model->title_id = $each['title_id'];
			$model->title_name = DictTitle::getName($each['title_id']);
			$model->company_id =$each['customer_id'];
			$model->company_name =DictCompany::getName($each['customer_id']);
			$model->brand_id =$each['brand_id'];
			$model->supply_id=$purchase->supply_id;
			$model->warehouse_id=$each['warehouse_id'];
				
			$brand_name=DictGoodsProperty::getProName($each['brand_id']);
			
			$model->product_id =$each['product_id'];
			$model->texture_id =$each['texture_id'];
			$model->rank_id =$each['rank_id'];
			$model->length =$each['length'];
			$model->weight =$each['weight'];
			$model->price =$sale_detail->price;//销售单价
			$model->fee=$each['weight']*$model->price;//销售金额
			$model->sales_freight =$each['shipment']*$each['weight'];//销售运费
			$model->sales_rebate = $each['rebate_fee']*$each['weight'];//销售折让
			$model->purchase_form_sn =$pur_base->form_sn;
			$model->purchase_price =$each['cost_price'];			
			$model->purchase_money =$each['weight']*$each['cost_price'];
			
			/*
			if(floatval($each['subsidy']))
			{
				$subsidy=$each['subsidy'];
			}else{
				$subsidy=$p_subsidy;
			}
			$model->sale_subsidy =$each['weight']*$subsidy;
			*/ //销售提成不再计算--2016-11-21
			$model->sale_subsidy=0;
			if($purchase)
			{
				$ven_name=DictCompany::getName($purchase->supply_id);
				//采购运费
				if(floatval($purchase->shipment))
				{
					$model->purchase_freight =$each['weight']*$purchase->shipment;//采购运费
				}else{			
					$ship=0;		
					foreach ($xml->freight->steelworks as $ea_ship)
					{
						if($ea_ship->name==$ven_name)
						{
							$ship=$ea_ship->price;
							break;
						}
					}
					$model->purchase_freight=$each['weight']*$ship;
				}
				
				//钢厂返利
				if(floatval($purchase->rebate))
				{
					$model->supply_rebate =$each['weight']*$purchase->rebate;//钢厂返利
				}else{
					$model->supply_rebate =$each['weight']*$purchase->e_rebate;//预估钢厂返利
				}
				
				//仓储费用
				$model->warehouse_fee=0;
				$model->warehouse_rebate=0;
				/*****仓库成本不再计算在内---2016-11-21
				if(floatval($purchase->ware_cost))
				{
					$model->warehouse_fee =$each['weight']*$purchase->ware_cost;//仓储费用
				}else{					
					$pro_name=DictGoodsProperty::getProName($each['product_id']);
					if($pro_name=='螺纹钢')
					{
						$model->warehouse_fee =$each['weight']*$purchase->e_ware_cost_lwg;
					}else{
						$model->warehouse_fee =$each['weight']*$purchase->e_ware_cost_other;
					}
				}				
				//仓库返利
				if(floatval($purchase->ware_rebate))
				{
					$model->warehouse_rebate =$each['weight']*$purchase->ware_rebate;//仓库返利
				}else{
					$model->warehouse_rebate =$each['weight']*$purchase->e_ware_rebate;
				}				
				*/
				//价格
				if($purchase->weight_confirm_status!=1)
				{
// 					list($price,$comment)=$this->getPurchasePrice('net',$each['brand_id'], $each['product_id'], $each['texture_id'], $each['rank_id'], $each['length'],$purchase->date_reach);
					$comment='';
// 					$model->purchase_price=$price;
// 					$model->purchase_money=$price*$model->weight;
					$model->comment=$comment;
				}else{
					$model->comment="采购单已审，价格来自库存成本";					
					if($model->purchase_price==0){$model->comment.="，库存成本单价为0";}
				}				
			}else{
				$model->comment="销售退货库存，价格来自库存成本";
				if($model->purchase_price==0){$model->comment.="，库存成本单价为0";}
				$model->purchase_freight=0;//采购运费
				$model->supply_rebate=0;//钢厂返利
				$model->warehouse_fee =0;//仓储费用
				$model->warehouse_rebate =0;//仓库返利
			}
				
			$invoice=$each['sto_invoice_price'];//发票成本读取库存
// 			foreach ($xml->invoice->cost as $ea)
// 			{
// 				if($ea->brand==$brand_name)
// 				{
// 					$invoice=$ea->price;
// 					break;
// 				}
// 			}
			if($each['is_yidan'])
			{//乙单
				if(!$each['sto_isyidan']){
					$model->invoice=-$each['weight']*$invoice;//发票成本
				}
			}elseif($each['sto_isyidan']){//甲单
				$model->invoice=$each['weight']*$invoice;//发票成本
			}			
			if($purchase->purchase_type=='tpcg')
			{
				$pledge=$purchase->pledge;
				$sql_p="select sum(weight) as weight,sum(interest_fee) as interest_fee from frm_pledge_redeem where purchase_id=".$purchase->id;
				$pledge_redeem=FrmPledgeRedeem::model()->findBySql($sql_p);				
				//算
				$days=ceil((time()-strtotime($pur_base->form_time))/86400);
				if($days<=$pledge->pledge_length)
				{
					$model->pledge_fee=$pledge_redeem->interest_fee+$pledge->min_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*$pledge->pledge_length;
				}elseif($days<=$pledge->violation_date)
				{
					$model->pledge_fee=$pledge_redeem->interest_fee+$pledge->min_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*$pledge->pledge_length
					+$pledge->pledge_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*($days-$pledge->pledge_length);
				}else{
					$model->pledge_fee=$pledge_redeem->interest_fee+$pledge->min_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*$pledge->pledge_length
					+$pledge->pledge_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*($pledge->violation_date-$pledge->pledge_length)
					+$pledge->over_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*($days-$pledge->violation_date);
				}
				if($purchase->weight_confirm_status==1)
				{
					$model->pledge_fee=$model->pledge_fee*$each['weight']/$purchase->confirm_weight;
				}else{
					$model->pledge_fee=$model->pledge_fee*$each['weight']/$purchase->weight;
				}
			}else{
				$model->pledge_fee =0;//托盘利息
			}
			if($each['has_bonus_price'])
			{
				$gao=HighOpen::model()->find('sales_detail_id='.$sale_detail->id);
				$model->hight_open=$gao->real_fee*$each['weight']/$sale_detail->weight;
			}else{
				$model->hight_open =0;//高开
			}
			$model->owner_id=$each['owned_by'];//业务员
			$model->owner_name =User::getUserName($each['owned_by']);
			$model->is_yidan =$each['is_yidan'];
		
			$model->sales_profit =$model->fee-$model->sales_freight-$model->sales_rebate-$model->purchase_money-$model->purchase_freight
			-$model->warehouse_fee+$model->warehouse_rebate+$model->supply_rebate-$model->sale_subsidy-$model->invoice-$model->pledge_fee
			- $model->hight_open;
			$model->insert();
		}
	}
	
	//未审单，按销售单 ,没有仓库费用和返利
	public function unConfirmedSales($xml,$id='')
	{		
		$p_subsidy=0;
		foreach ($xml->sales_percentage->step as $e)
		{
				$p_subsidy=$e->price;
				break;
		}
		$connection=Yii::app()->db;	
		$turn_time=Yii::app()->params['turn_time']?Yii::app()->params['turn_time']:0;
		$sql='select d.*,fs.id as fs_id,fs.is_yidan,fs.shipment,fs.rebate_fee,fs.title_id,fs.customer_id,fs.warehouse_id,fs.has_bonus_price,fs.subsidy,c.id as cid,c.form_status,c.form_sn,c.form_time,c.owned_by from sales_detail d
				left join frm_sales fs on fs.id=d.frm_sales_id 
				left join common_forms c on c.form_id=fs.id and c.form_type="XSD" 
				left join dict_goods_property dp_brand on dp_brand.id=d.brand_id 				
				 where  fs.sales_type="normal"    and  (c.form_status="approve"  or c.form_status="submited")  and dp_brand.short_name<>"贵航" and fs.confirm_status=0 and UNIX_TIMESTAMp(c.form_time)>='.$turn_time;
		if($id)$sql.=' and fs.id='.$id;
		$command=$connection->createCommand($sql);
		$res=$command->queryAll();
		foreach ($res as $each)
		{
			if(floatval($each['price'])<=100)continue;
// 			if($each['warehouse_id']==3&&($each['brand_id']==6||$each['brand_id']==17))continue;
			$model=new ProfitCollecting();
			$model->sales_id =$each['fs_id'];
			$model->form_sn =$each['form_sn'];
			$model->sales_date = $each['form_time'];
			$model->title_id = $each['title_id'];
			$model->title_name = DictTitle::getName($each['title_id']);
			$model->company_id =$each['customer_id'];
			$model->company_name =DictCompany::getName($each['customer_id']);
			$model->brand_id =$each['brand_id'];
			$model->warehouse_id=$each['warehouse_id'];
		
			$brand_name=DictGoodsProperty::getProName($each['brand_id']);
			
			$model->product_id =$each['product_id'];
			$model->texture_id =$each['texture_id'];
			$model->rank_id =$each['rank_id'];
			$model->length =$each['length'];
			$model->weight =$each['weight'];
			$model->price =$each['price'];//销售单价
			$model->fee=$each['fee'];//销售金额
			$model->sales_freight =$each['shipment']*$each['weight'];//销售运费
			$model->sales_rebate = $each['rebate_fee']*$each['weight'];//销售折让
			$model->purchase_form_sn ='';
			
			//获取价格			
			list($price,$comment,$is_yidan)=$this->getPurchasePrice('storage',$each['brand_id'], $each['product_id'], $each['texture_id'], $each['rank_id'], $each['length'],$each['form_time']);
			
			$model->purchase_price =$price;//采购价
			$model->comment=$comment;
			$model->purchase_money =$each['weight']*$model->purchase_price;
			/*
			$subsidy=0;
			if(floatval($each['subsidy']))
			{
				$subsidy=$each['subsidy'];
			}else{
				$subsidy=$p_subsidy;
			}
			$model->sale_subsidy =$each['weight']*$subsidy;
			*/
			$model->sale_subsidy=0;
			$model->purchase_freight=0;//采购运费
			
			$model->warehouse_fee =0;//仓储费用
			$model->warehouse_rebate =0;//仓库返利
			$model->supply_rebate =0;//钢厂返利
		
			$invoice=0;
			foreach ($xml->invoice->cost as $ea)
			{
				if($ea->brand==$brand_name)
				{
					$invoice=$ea->price;
					break;
				}
			}
			if($each['is_yidan'])
			{//乙单
				if($is_yidan==0){
					$model->invoice=-$each['weight']*$invoice;//发票成本
				}
			}elseif($is_yidan){//甲单
				$model->invoice=$each['weight']*$invoice;//发票成本
			}			
			$model->pledge_fee =0;//托盘利息
			if($each['has_bonus_price'])
			{
				$gao=HighOpen::model()->find('sales_detail_id='.$each['id']);
				$model->hight_open=$gao->real_fee;
			}else{
				$model->hight_open =0;//高开
			}
			$model->owner_id=$each['owned_by'];//业务员
			$model->owner_name =User::getUserName($each['owned_by']);
			$model->is_yidan =$each['is_yidan'];
		
			$model->sales_profit =$model->fee-$model->sales_freight-$model->sales_rebate-$model->purchase_money-$model->purchase_freight
			-$model->warehouse_fee+$model->warehouse_rebate+$model->supply_rebate-$model->sale_subsidy-$model->invoice-$model->pledge_fee
			- $model->hight_open;
			$model->insert();
		}
	}
	
	public  function getPurchasePrice($type,$brand,$product,$texture,$rank,$length,$time='')
	{
		$comment='';
		if($type=='storage')
		{
			$bak_time=$time;
			$time=strtotime($time);
			$condition="  is_dx=0 and is_deleted=0  and s.input_date <=".$time." and
			i.brand_id={$brand} and i.product_id={$product} and i.texture_id={$texture} and i.rank_id={$rank} and i.length ={$length}";
			$sql="select s.input_date,s.cost_price,s.is_yidan from storage s left join input_detail i on i.id=s.input_detail_id 
			where {$condition} and input_date=(select max(s.input_date) from storage s left join input_detail i on i.id=s.input_detail_id  where {$condition}  order by is_price_confirmed desc)";
			$storage=Storage::model()->findBySql($sql);
			if($storage->cost_price!=null)
			{
				$price=$storage->cost_price;
				if($price==0)$comment="获取距销售开单时间最近库存成本为0";
				else $comment="获取距销售开单时间最近库存成本".date('Y-m-d',$storage->input_date).'日库存成本';
				$is_yidan=$storage->is_yidan;
				if(!$is_yidan)$is_yidan=0;
			}else{
				//take todays net price to fill it
				$brand_name=DictGoodsProperty::getProName($brand);
				list($price,$comment)=PurchasePrice::getNetPrice($bak_time,$brand_name,$brand,$product,$texture,$rank,$length);
			}
		}else{
			//take todays net price to fill it
			if(!$time)$time=date('Y-m-d');
			$brand_name=DictGoodsProperty::getProName($brand);
			list($price,$comment)=PurchasePrice::getNetPrice($time,$brand_name,$brand,$product,$texture,$rank,$length);			
// 			if(!$price)
// 			{		
// 				$condition=" is_price_confirmed=1 and is_dx=0 and is_deleted=0 and card_status='normal'  and s.input_date >=".$time." and
// 				i.brand_id={$brand} and i.product_id={$product} and i.texture_id={$texture} and i.rank_id={$rank} and i.length ={$length}";
// 				$sql="select s.input_date,s.cost_price from storage s left join input_detail i on i.id=s.input_detail_id
// 				where {$condition} and input_date=(select min(s.input_date) from storage s left join input_detail i on i.id=s.input_detail_id  where {$condition})";
// 				$storage=Storage::model()->findBySql($sql);
				
// 				$price=$storage->cost_price;
// 				if(!$price)$comment="获取最近两个月最早库存成本为0";
// 				else $comment="获取最近两个月最早库存成本".date('Y-m-d',$storage->input_date).'日库存成本';
// 			}
		}		
		return array($price,$comment,$is_yidan);
		
	}
	
	//先销后进
	public function xxhjSales($xml,$id='')
	{		
		$p_subsidy=0;
		foreach ($xml->sales_percentage->step as $e)
		{
				$p_subsidy=$e->price;
				break;
		}
		$connection=Yii::app()->db;
		$turn_time=Yii::app()->params['turn_time']?Yii::app()->params['turn_time']:0;
		$sql='select d.*,fs.id as fs_id,fs.is_yidan,fs.shipment,fs.rebate_fee,fs.title_id,fs.customer_id,fs.warehouse_id,fs.has_bonus_price,c.id as cid,c.form_status,c.form_sn,c.form_time,c.owned_by from sales_detail d
				left join frm_sales fs on fs.id=d.frm_sales_id 
				left join common_forms c on c.form_id=fs.id and c.form_type="XSD" 
				left join dict_goods_property dp_brand on dp_brand.id=d.brand_id 
				 where  fs.sales_type="xxhj"    and  (c.form_status="approve"  or c.form_status="submited") and dp_brand.short_name<>"贵航"  and UNIX_TIMESTAMp(c.form_time)>='.$turn_time;
		if($id)$sql.=' and fs.id='.$id;
		$command=$connection->createCommand($sql);
		$res=$command->queryAll();
		foreach ($res as $each)
		{
			if(floatval($each['price'])<=100)continue;
// 			if($each['warehouse_id']==3&&($each['brand_id']==6||$each['brand_id']==17))continue;
			$model=new ProfitCollecting();
			$model->sales_id =$each['fs_id'];
			$model->form_sn =$each['form_sn'];
			$model->sales_date = $each['form_time'];
			$model->title_id = $each['title_id'];
			$model->title_name = DictTitle::getName($each['title_id']);
			$model->company_id =$each['customer_id'];
			$model->company_name =DictCompany::getName($each['customer_id']);
			$model->brand_id =$each['brand_id'];
			$model->warehouse_id=$each['warehouse_id'];
		
			$brand_name=DictGoodsProperty::getProName($each['brand_id']);
			
			$model->product_id =$each['product_id'];
			$model->texture_id =$each['texture_id'];
			$model->rank_id =$each['rank_id'];
			$model->length =$each['length'];
			$model->weight =$each['weight'];
			$model->price =$each['price'];//销售单价
			$model->fee=$each['fee'];//销售金额
			$model->sales_freight =$each['shipment']*$each['weight'];//销售运费
			$model->sales_rebate = $each['rebate_fee']*$each['weight'];//销售折让
			
			//find the related purchase_detail
			$rel_dets=SaledetailPurchase::model()->with('purdetailCont','purdetailCont.frmPurchase.baseform')->findAll('sales_detail_id='.$each['id'].' and baseform.form_status not in ("delete","unsubmit")');
			$confirmed=0;
			if($rel_dets) 
			{
				$confirmed=1;
				$total_fee=0;
				$total_wei=0;
				$total_tran=0;
				$total_rebate=0;
				$total_warecost=0;
				$total_warerebate=0;
				$total_invoice_wei=0;
				foreach ($rel_dets as $ea_re)
				{		
					$fix_weight=0;
					$pur_d=$ea_re->purdetailCont;
					$invoice=$pur_d->invoice_price;
					$pur_m=$pur_d->frmPurchase;
// 					var_dump($pur_d->fix_weight);
					if($pur_d->fix_weight>0)
					{
						$fix_weight=$pur_d->fix_weight;
						$total_fee+=$pur_d->fix_weight*$pur_d->fix_price;
						$total_wei+=$pur_d->fix_weight;
// 						$total_tran+=$pur_d->fix_weight*$pur_m->shipment;
					}else{
						$confirmed=0;
						$fix_weight=$pur_d->weight;
						$total_fee+=$pur_d->weight*$pur_d->price;
						$total_wei+=$pur_d->weight;
// 						$total_tran+=$pur_d->weight*$pur_m->shipment;						
					}
					if($each['is_yidan']){//乙单
						if(!$pur_m->is_yidan){
							$total_invoice_wei-=$fix_weight;
						}
					}elseif($pur_m->is_yidan){//甲单
						$total_invoice_wei+=$fix_weight;
					}
// 					//钢厂返利
// 					if($pur_m->rebate)
// 					{
// 						$total_rebate+=$fix_weight*$pur_m->rebate;//钢厂返利
// 					}else{
// 						$total_rebate+=$fix_weight*$pur_m->e_rebate;//预估钢厂返利
// 					}
					
// 					//仓储费用
// 					if($pur_m->ware_cost)
// 					{
// 						$total_warecost+=$fix_weight*$pur_m->ware_cost;//仓储费用
// 					}else{
// 						$pro_name=DictGoodsProperty::getProName($each['product_id']);
// 						if($pro_name=='螺纹钢')
// 						{
// 							$total_warecost+=$fix_weight*$pur_m->e_ware_cost_lwg;
// 						}else{
// 							$total_warecost+=$fix_weight*$pur_m->e_ware_cost_other;
// 						}
// 					}
// 					//仓库返利
// 					if($pur_m->ware_rebate)
// 					{
// 						$total_warerebate+=$fix_weight*$pur_m->ware_rebate;//仓库返利
// 					}else{
// 						$total_warerebate+=$fix_weight*$pur_m->e_ware_rebate;
// 					}
					
				}
				$price=$total_fee/$total_wei;
				$total_fee=$price*$each['weight'];
				if($price==0)$comment="先销后进销售单，补的采购单金额为0";
				else $comment="先销后进销售单，价格来自补单总金额/总重量";
			}else{
				list($price,$comment,$is_yidan)=$this->getPurchasePrice('storage',$each['brand_id'], $each['product_id'], $each['texture_id'], $each['rank_id'], $each['length'],$each['form_time']);
// 				list($price,$comment)=PurchasePrice::getNetPrice(date('Y-m-d'),$brand_name,$each['brand_id'],$each['product_id'],$each['texture_id'],$each['rank_id'],$each['length']);
				$total_fee=$price*$each['weight'];
				$total_tran=0;
				$total_rebate=0;
				$total_warecost=0;
				$total_warerebate=0;
				
				$invoice=0;
				foreach ($xml->invoice->cost as $ea)
				{
					if($ea->brand==$brand_name)
					{
						$invoice=$ea->price;break;
					}
				}
// 				$price=0;
// 				$comment="先销后进销售单，未补采购单";
			}			
			$model->purchase_form_sn ='';				
			//获取价格
				
			$model->purchase_price =$price;//采购价
			$model->comment=$comment;
			$model->purchase_money =$total_fee;
			/*
			$subsidy=0;
			if(floatval($each['subsidy']))
			{
				$subsidy=$each['subsidy'];
			}else{
				$subsidy=$p_subsidy;
			}			
			$model->sale_subsidy =$subsidy*$each['weight'];
			*/
			$model->sale_subsidy=0;
			$model->purchase_freight=$total_tran;//采购运费
			$model->confirmed=$confirmed;
				
			$model->warehouse_fee =0;//$total_warecost;//仓储费用
			$model->warehouse_rebate =0;//$total_warerebate;//仓库返利
			$model->supply_rebate =$total_rebate;//钢厂返利
		
			
			$model->invoice=$total_invoice_wei*$invoice;//发票成本
			$model->pledge_fee =0;//托盘利息
			if($each['has_bonus_price'])
			{
				$gao=HighOpen::model()->find('sales_detail_id='.$each['id']);
				$model->hight_open=$gao->real_fee;
			}else{
				$model->hight_open =0;//高开
			}
			$model->owner_id=$each['owned_by'];//业务员
			$model->owner_name =User::getUserName($each['owned_by']);
			$model->is_yidan =$each['is_yidan'];
		
			$model->sales_profit =$model->fee-$model->sales_freight-$model->sales_rebate-$model->purchase_money-$model->purchase_freight
			-$model->warehouse_fee+$model->warehouse_rebate+$model->supply_rebate-$model->sale_subsidy-$model->invoice-$model->pledge_fee
			- $model->hight_open;
			$model->insert();
		}
	} 
	
	//代销销售
	public function dxxsSales($xml,$id='')
	{
		$p_subsidy=0;
		foreach ($xml->sales_percentage->step as $e)
		{
				$p_subsidy=$e->price;
				break;
		}
		$connection=Yii::app()->db;
		$turn_time=Yii::app()->params['turn_time']?Yii::app()->params['turn_time']:0;
		$sql='select d.*,fs.id as fs_id,fs.is_yidan,fs.shipment,fs.rebate_fee,fs.title_id,fs.customer_id,fs.warehouse_id,fs.has_bonus_price,c.id as cid,c.form_status,c.form_sn,c.form_time,c.owned_by from sales_detail d
				left join frm_sales fs on fs.id=d.frm_sales_id 
				left join common_forms c on c.form_id=fs.id and c.form_type="XSD"  
				left join dict_goods_property dp_brand on dp_brand.id=d.brand_id
				 where  fs.sales_type="dxxs"    and  (c.form_status="approve"  or c.form_status="submited")  and dp_brand.short_name<>"贵航"  and UNIX_TIMESTAMp(c.form_time)>='.$turn_time;
		if($id)$sql.=' and fs.id='.$id;
		$command=$connection->createCommand($sql);
		$res=$command->queryAll();
		foreach ($res as $each)
		{
			if(floatval($each['price'])<=100)continue;
// 			if($each['warehouse_id']==3&&($each['brand_id']==6||$each['brand_id']==17))continue;
			$model=new ProfitCollecting();
			$model->sales_id =$each['fs_id'];
			$model->form_sn =$each['form_sn'];
			$model->sales_date = $each['form_time'];
			$model->title_id = $each['title_id'];
			$model->title_name = DictTitle::getName($each['title_id']);
			$model->company_id =$each['customer_id'];
			$model->company_name =DictCompany::getName($each['customer_id']);
			$model->brand_id =$each['brand_id'];
			$model->warehouse_id=$each['warehouse_id'];
		
			$brand_name=DictGoodsProperty::getProName($each['brand_id']);
		
			$model->product_id =$each['product_id'];
			$model->texture_id =$each['texture_id'];
			$model->rank_id =$each['rank_id'];
			$model->length =$each['length'];
			$model->weight =$each['weight'];
			$model->price =$each['price'];//销售单价
			$model->fee=$each['fee'];//销售金额
			$model->sales_freight =$each['shipment']*$each['weight'];//销售运费
			$model->sales_rebate = $each['rebate_fee']*$each['weight'];//销售折让
			$model->purchase_form_sn ='';
				
			//find the related purchase_detail
			$rel_dets=SaledetailPurchase::model()->with('purdetailCont','purdetailCont.frmPurchase.baseform')->findAll('sales_detail_id='.$each['id'].' and baseform.form_status not in ("delete","unsubmit")');
			$confirmed=0;
			if($rel_dets)
			{
				$confirmed=1;
				$total_fee=0;
				$total_wei=0;
				$total_tran=0;
				$total_rebate=0;
				$total_warecost=0;
				$total_warerebate=0;
				$total_invoice_wei=0;
				foreach ($rel_dets as $ea_re)
				{				
					$fix_weight=0;		
					$pur_d=$ea_re->purdetailCont;
					$invoice=$pur_d->invoice_price;
					$pur_m=$pur_d->frmPurchase;
					if($pur_d->fix_weight>0)
					{
						$fix_weight=$pur_d->fix_weight;
						$total_fee+=$pur_d->fix_weight*$pur_d->fix_price;
						$total_wei+=$pur_d->fix_weight;
// 						$total_tran+=$pur_d->fix_weight*$pur_m->shipment;
					}else{
						$confirmed=0;
						$fix_weight=$pur_d->weight;
						$total_fee+=$pur_d->weight*$pur_d->price;
						$total_wei+=$pur_d->weight;
// 						$total_tran+=$pur_d->weight*$pur_m->shipment;
					}					
					if($each['is_yidan']){//乙单
						if(!$pur_m->is_yidan){
							$total_invoice_wei-=$fix_weight;
						}
					}elseif($pur_m->is_yidan){//甲单
						$total_invoice_wei+=$fix_weight;
					}
// 					//钢厂返利
// 					if($pur_m->rebate)
// 					{
// 						$total_rebate+=$fix_weight*$pur_m->rebate;//钢厂返利
// 					}else{
// 						$total_rebate+=$fix_weight*$pur_m->e_rebate;//预估钢厂返利
// 					}
						
// 					//仓储费用
// 					if($pur_m->ware_cost)
// 					{
// 						$total_warecost+=$fix_weight*$pur_m->ware_cost;//仓储费用
// 					}else{
// 						$pro_name=DictGoodsProperty::getProName($each['product_id']);
// 						if($pro_name=='螺纹钢')
// 						{
// 							$total_warecost+=$fix_weight*$pur_m->e_ware_cost_lwg;
// 						}else{
// 							$total_warecost+=$fix_weight*$pur_m->e_ware_cost_other;
// 						}
// 					}
// 					//仓库返利
// 					if($pur_m->ware_rebate)
// 					{
// 						$total_warerebate+=$fix_weight*$pur_m->ware_rebate;//仓库返利
// 					}else{
// 						$total_warerebate+=$fix_weight*$pur_m->e_ware_rebate;
// 					}									
				}
				$price=$total_fee/$total_wei;
				$total_fee=$price*$each['weight'];
				if($price==0)$comment="代销销售单，补的采购单金额为0";
				else $comment="代销销售单，价格来自补单总金额/总重量";
			}else{
				list($price,$comment,$is_yidan)=$this->getPurchasePrice('storage',$each['brand_id'], $each['product_id'], $each['texture_id'], $each['rank_id'], $each['length'],$each['form_time']);
// 				list($price,$comment)=PurchasePrice::getNetPrice(date('Y-m-d'),$brand_name,$each['brand_id'],$each['product_id'],$each['texture_id'],$each['rank_id'],$each['length']);
// 				$price=0;
				$total_fee=$price*$each['weight'];
				$total_tran=0;
				$total_rebate=0;
				$total_warecost=0;
				$total_warerebate=0;
				$invoice=0;
				foreach ($xml->invoice->cost as $ea)
				{
					if($ea->brand==$brand_name)
					{
						$invoice=$ea->price;
						break;
					}
				}
// 				$comment="代销销售单，未补采购单";
			}
			//获取价格
				
			$model->purchase_price =$price;//采购价
			$model->comment=$comment;
			$model->purchase_money =$total_fee;
			/*
			$subsidy=0;
			if(floatval($each['subsidy']))
			{
				$subsidy=$each['subsidy'];
			}else{
				$subsidy=$p_subsidy;
			}
			$model->sale_subsidy =$subsidy*$each['weight'];//销售提成--暂缓等潘修改
			*/
			$model->sale_subsidy=0;
			$model->confirmed=$confirmed;			
			$model->purchase_freight=$total_tran;//采购运费
				
			$model->warehouse_fee =0;//$total_warecost;//仓储费用
			$model->warehouse_rebate =0;//$total_warerebate;//仓库返利
			$model->supply_rebate =$total_rebate;//钢厂返利
		
			$model->invoice=$total_invoice_wei*$invoice;//发票成本
			$model->pledge_fee =0;//托盘利息
			if($each['has_bonus_price'])
			{
				$gao=HighOpen::model()->find('sales_detail_id='.$each['id']);
				$model->hight_open=$gao->real_fee;
			}else{
				$model->hight_open =0;//高开
			}
			$model->owner_id=$each['owned_by'];//业务员
			$model->owner_name =User::getUserName($each['owned_by']);
			$model->is_yidan =$each['is_yidan'];
		
			$model->sales_profit =$model->fee-$model->sales_freight-$model->sales_rebate-$model->purchase_money-$model->purchase_freight
			-$model->warehouse_fee+$model->warehouse_rebate+$model->supply_rebate-$model->sale_subsidy-$model->invoice-$model->pledge_fee
			- $model->hight_open;
			$model->insert();
		}
	}
		
	public function actionUpdateShareMain()
	{
		$time_start=$_REQUEST['time_start'];
		$temp=strtotime(date('Y-m'));
		if($time_start){
			if($time_start<'2015-03-01')
			{
				$time_start='2015-03-01';
			}
			$time_start=strtotime($time_start);
		}else{			
			$time_start=strtotime('-3 month',$temp);
		}		
		$time_end=strtotime('+1 month',$temp);
		$time_L=$time_start;
		for(;;)
		{
			$time_H=strtotime('+1 month',$time_L);
			if($time_H>$time_end)break;
			$this->updateShare($time_L, $time_H);
			$time_L=$time_H;
		}
		@session_destroy();
	}
	
	
	
	/*
	 * a automatic script to update rebate,warecost,warerebate
	 */
	public function updateShare($time_start,$time_end)
	{
		$put_array=array();
		$xml=readConfig();
		$connection=Yii::app()->db;

		$transaction=Yii::app()->db->beginTransaction();
		try{
		//钢厂返利
// 		$time=strtotime(date('Y-m'));
		// $time=strtotime('2016-05');		
		foreach ($xml->steel_rebate->steelworks as $each)
		{
			$attr=$each->attributes();
			$name=$attr['name'];
			$ven_id=DictCompany::model()->find('short_name="'.$name.'"')->id;
			if($ven_id)
			{				
				$sql="select sum(case weight_confirm_status when 1 then confirm_weight else weight end) as sum_weight,group_concat(f.id) as ids from frm_purchase f 
						 left join common_forms c on c.form_id=f.id and c.form_type='CGD' 
						 where rebate<=0 and c.form_status='approve'  and f.purchase_type='normal' and  UNIX_TIMESTAMP(c.form_time)<".$time_end."  and  UNIX_TIMESTAMP(c.form_time)>=".$time_start." and supply_id=".$ven_id;
				$res=$connection->createCommand($sql)->queryRow();
				if($res)
				{
					foreach ($each->step as $ea)
					{
						$step=$ea->attributes();
						if($step['max'])
						{
							if(($res['sum_weight']>$step['min'])&&($res['sum_weight']<=$step['max'])){
								$price=$ea->price;
								//make sure that the rebate rate need change
								$needChange_sql="select group_concat(id) as total_id from frm_purchase where e_rebate!=".$price." and id in (".$res['ids'].")";
								$needChange=$connection->createCommand($needChange_sql)->queryRow();
								if($needChange['total_id'])
								{
									$update_sql="update frm_purchase set e_rebate=".$price.' where id in ('.$needChange['total_id'].')';
									$connection->createCommand($update_sql)->execute();
								}							
								break;
							}
						}elseif($res['sum_weight']>$step['min']){
							$price=$ea->price;
							//make sure that the rebate rate need change
							$needChange_sql="select group_concat(id) as total_id from frm_purchase where e_rebate!=".$price." and id in (".$res['ids'].")";
							$needChange1=$connection->createCommand($needChange_sql)->queryRow();
							if($needChange1['total_id'])
							{
								$update_sql="update frm_purchase set e_rebate=".$price.' where id in ('.$needChange1['total_id'].')';
								$connection->createCommand($update_sql)->execute();
							}						
							break;
						}
					}
				}
			}	
		}
			
		/*
		//仓库返利
		foreach ($xml->warehouse_rebate->warehouse as $ea_wr)
		{
			$attr=$ea_wr->attributes();
			$name=$attr['name'];
			$ware=Warehouse::model()->find('short_name="'.$name.'"')->id;
			if($ware)
			{
				$sql="select sum(case weight_confirm_status when 1 then confirm_weight else weight end) as sum_weight,group_concat(f.id) as ids from frm_purchase f
						 left join common_forms c on c.form_id=f.id and c.form_type='CGD'
						 where ware_rebate<=0 and c.form_status='approve'  and f.purchase_type='normal'  and   UNIX_TIMESTAMP(c.form_time)<".$time_end."  and   UNIX_TIMESTAMP(c.form_time)>=".$time_start." and warehouse_id=".$ware;
				$res=$connection->createCommand($sql)->queryRow();
				if($res)
				{
					foreach ($ea_wr->step as $ea)
					{
						$step=$ea->attributes();
						if($step['max'])
						{
							if(($res['sum_weight']>$step['min'])&&($res['sum_weight']<=$step['max'])){
								$price=$ea->price;
								//make sure that the rebate rate need change
								$needChange_sql="select group_concat(id) as total_id from frm_purchase where e_ware_rebate!=".$price." and id in (".$res['ids'].")";
								$needChange2=$connection->createCommand($needChange_sql)->queryRow();
								if($needChange2['total_id'])
								{
									$update_sql="update frm_purchase set e_ware_rebate=".$price.' where id in ('.$needChange2['total_id'].')';
									$connection->createCommand($update_sql)->execute();
								}					
								break;
							}
						}elseif($res['sum_weight']>$step['min']){
							$price=$ea->price;
							//make sure that the rebate rate need change
							$needChange_sql="select group_concat(id) as total_id from frm_purchase where e_ware_rebate!=".$price." and id in (".$res['ids'].")";
							$needChange3=$connection->createCommand($needChange_sql)->queryRow();
							if($needChange3['total_id'])
							{
								$update_sql="update frm_purchase set e_ware_rebate=".$price.' where id in ('.$needChange3['total_id'].')';
								$connection->createCommand($update_sql)->execute();									
							}							
							break;
						}
					}
				}
			}
		}
		
		
		//仓库费用
		foreach ($xml->warehouse_fee->warehouse as $each)
		{
			$attr=$each->attributes();
			$name=$attr['name'];
			$ware=Warehouse::model()->find('short_name="'.$name.'"')->id;
			if($ware)
			{
				//螺纹钢
				$sql="select sum(case weight_confirm_status when 1 then  detail_fix_weight else detail_weight end) as sum_weight,group_concat(main_id) as ids from purchase_view 
						 where ware_cost<=0 and product_name='螺纹钢' and form_status='approve' and purchase_type='normal'   and  UNIX_TIMESTAMP(form_time)<".$time_end."   and UNIX_TIMESTAMP(form_time) >=".$time_start." and warehouse_id =".$ware;
				$res=$connection->createCommand($sql)->queryRow();
				if($res)
				{
					foreach ($each->lwg->step as $ea_wc)
					{
						$step=$ea_wc->attributes();
						if($step['max'])
						{
							if($res['sum_weight']>$step['min']&&$res['sum_weight']<=$step['max'])
							{
								$price=$ea_wc->price;
								//make sure that the rebate rate need change
								$needChange_sql="select group_concat(id) as total_id from frm_purchase where e_ware_cost_lwg!=".$price." and id in (".$res['ids'].")";
								$needChange4=$connection->createCommand($needChange_sql)->queryRow();
								if($needChange4['total_id'])
								{
									$update_sql="update frm_purchase set e_ware_cost_lwg=".$price.' where id in ('.$needChange4['total_id'].')';
									$connection->createCommand($update_sql)->execute();
								}
								break;
							}
						}elseif($res['sum_weight']>$step['min']){
							$price=$ea_wc->price;
							//make sure that the rebate rate need change
							$needChange_sql="select group_concat(id) as total_id from frm_purchase where e_ware_cost_lwg!=".$price." and id in (".$res['ids'].")";
							$needChange5=$connection->createCommand($needChange_sql)->queryRow();
							if($needChange5['total_id'])
							{
								$update_sql="update frm_purchase set e_ware_cost_lwg=".$price.' where id in ('.$needChange5['total_id'].')';
								$connection->createCommand($update_sql)->execute();
							}
							break;
						}
					}
				}		
				
				//其他
				$sql="select sum(case weight_confirm_status when 1 then  detail_fix_weight else detail_weight end) as sum_weight,group_concat(main_id) as ids from purchase_view 
						 where ware_cost<=0 and product_name!='螺纹钢' and form_status='approve' and purchase_type='normal'  and   UNIX_TIMESTAMP(form_time)<".$time_end."  and   UNIX_TIMESTAMP(form_time) >=".$time_start." and warehouse_id =".$ware;
				$res=$connection->createCommand($sql)->queryRow();
				if($res)
				{
					foreach ($each->other->step as $ea_wc)
					{
						$step=$ea_wc->attributes();
						if($step['max'])
						{
							if($res['sum_weight']>$step['min']&&$res['sum_weight']<=$step['max'])
							{
								$price=$ea_wc->price;
								//make sure that the rebate rate need change
								$needChange_sql="select group_concat(id) as total_id from frm_purchase where e_ware_cost_other!=".$price." and id in (".$res['ids'].")";
								$needChange6=$connection->createCommand($needChange_sql)->queryRow();
								if($needChange6['total_id'])
								{
									$update_sql="update frm_purchase set e_ware_cost_other=".$price.' where id in ('.$needChange6['total_id'].')';
									$connection->createCommand($update_sql)->execute();
								}
								break;
							}
						}elseif($res['sum_weight']>$step['min']){
							$price=$ea_wc->price;
							//make sure that the rebate rate need change
							$needChange_sql="select group_concat(id) as total_id from frm_purchase where e_ware_cost_other!=".$price." and id in (".$res['ids'].")";
							$needChange7=$connection->createCommand($needChange_sql)->queryRow();
							if($needChange7['total_id'])
							{
								$update_sql="update frm_purchase set e_ware_cost_other=".$price.' where id in ('.$needChange7['total_id'].')';
								$connection->createCommand($update_sql)->execute();
							}
							break;
						}
					}
				}		
			}
		}
		*/
		//put these ids in array
		$need=explode(',',$needChange['total_id']);
		$need1=explode(',',$needChange1['total_id']);
		/*
		$need2=explode(',',$needChange2['total_id']);
		$need3=explode(',',$needChange3['total_id']);
		$need4=explode(',',$needChange4['total_id']);
		$need5=explode(',',$needChange5['total_id']);
		$need6=explode(',',$needChange6['total_id']);
		$need7=explode(',',$needChange7['total_id']);
		$new_array=array_unique(array_merge($need,$need1,$need2,$need3,$need4,$need5,$need6,$need7));
		*/
		$new_array=array_unique(array_merge($need,$need1));

		$this->midnightProfit($new_array);
		$transaction->commit();
		}catch(Exception $e){
			echo $e;
			$transaction->rollback();
			return;
		}	
	}


	/*
	*采销利润更新
	*根据最新变化表
	* 时间间隔每1小时执行一次
	**/
	public function actionUpdateProfit()
	{
		$sql="select common_id,group_concat(id) as id from profit_change where type='sale' and disposed=0 group by common_id";
		$connection=Yii::app()->db;
		$changes=$connection->createCommand($sql)->queryAll();	
		$ids='';	
		foreach ($changes as $each) {								
				$this->updateEach($each['common_id']);
				$ids.=$each['id'].',';
		}
		$ids.='0';
		//设置为已处理
		$sql="update profit_change set disposed=1 where id in(".$ids.")";
		$connection->createCommand($sql)->execute();
	}

	//
	public function updateEach($common_id)
	{
		$baseform=CommonForms::model()->with('sales')->findByPk($common_id);
		$sale=$baseform->sales;
		//删除旧数据
		ProfitCollecting::model()->deleteAll('sales_id='.$sale->id);

		//插入新数据
		$xml=readConfig();
		switch ($sale->sales_type) {
			case 'normal':
				if($sale->confirm_status==0){								
					$this->unConfirmedSales($xml,$sale->id);
				}elseif($sale->confirm_status==1){
					$this->confirmedSales($xml,$sale->id);
				}
				break;
			case 'xxhj':
				$this->xxhjSales($xml,$sale->id);
				break;
			case 'dxxs':
				$this->dxxsSales($xml,$sale->id);
				break;
			default:						
				break;
		}
	}

	/**
	**利润更新，每天凌晨随钢厂返利脚本更新执行
	**/
	public function midnightProfit($other_array)
	{
		//remember that these purchase form were updated when gcfl was registered
		$sql="select common_id,group_concat(t.id) as id,c.form_id from profit_change t left join common_forms c on c.id=t.common_id where type='purchase' and disposed=0 group by common_id";
		$connection=Yii::app()->db;
		$changes=$connection->createCommand($sql)->queryAll();
		$id_array=array();
		$ids='';
		foreach ($changes as $each) {
			array_push($id_array,$each['form_id']);
			$ids.=$each['id'].',';
		}
		$ids.='0';
		//设置为已处理
		$sql="update profit_change set disposed=1 where id in(".$ids.")";
		$connection->createCommand($sql)->execute();
		$new_id_array=array_unique(array_merge($id_array,$other_array));
		$this->updateProfitByPurchase($new_id_array);

	}


	/**
	**根据采购信息更新利润
	**
	**/
	public function updateProfitByPurchase($pur_id_array)
	{
		$connection=Yii::app()->db;
		$sale_array=array();
		foreach ($pur_id_array as $each) {
			if(!$each)continue;
			$purchase=FrmPurchase::model()->findByPk($each);
			if($purchase->purchase_type=='xxhj'){				
				array_push($sale_array,$purchase->frm_contract_id);
			}elseif($purchase->purchase_type=='dxcg'){
				$ress=SaledetailPurchase::model()->with('saledetailCont','saledetailCont.FrmSales','saledetailCont.FrmSales.baseform')->findAll('purchase_id='.$each);
				if($ress)
				{
					foreach ($ress as $each) {
						$base=$each->saledetailCont->FrmSales->baseform;
						array_push($sale_array,$base->id);
					}
				}
			}else{
				$sql='select c.id from storage s left join output_detail od on od.storage_id=s.id left join frm_output fo on fo.id=od.frm_output_id 
					left join common_forms oc on oc.form_id=fo.id and oc.form_type="CKD" left join frm_sales fs on fo.frm_sales_id=fs.id 
					left join common_forms c on c.form_id=fs.id and c.form_type="XSD" where oc.is_deleted=0 and fs.confirm_status=1 and c.form_status="approve"
					 and s.is_deleted=0 and  s.purchase_id='.$each;
				$res=$connection->createCommand($sql)->queryAll();
				foreach ($res as $val) {
					array_push($sale_array,$val);
				}
			}
		}

		//加上销售提成的修改的common_id?或者不用加？
		$unique_array=array_unique($sale_array);
		foreach($unique_array as $each)
		{
			$this->updateEach($each);
		}
	}


	/*
	*	库存预估利润汇总脚本，每晚执行*	*
	**/
	public function actionResetStorageProfit()
	{
		set_time_limit(5000);
		$connection=Yii::app()->db;		
		$transaction=Yii::app()->db->beginTransaction();
		try{	
			$sql="TRUNCATE steel_trade.profit_storage";
			$connection->createCommand($sql)->execute();
	
			$sql = "insert into profit_storage
					(`storage_id`,`card_no`,`purchase_id`,`form_sn`,`purchase_date`,`input_date`,`title_id`,`title_name`,`brand_id`,`product_id`,`texture_id`,
					`rank_id`,`length`,`left_weight`,`type`,`purchase_price`,`purchase_money`,`warehouse_id`)
					select t.id , t.card_no , c.form_id,c.form_sn,UNIX_TIMESTAMP(c.form_time),t.input_date,t.title_id,title.short_name,detail.brand_id,detail.product_id,
					detail.texture_id,detail.rank_id,detail.length,t.left_weight,i.input_type,t.cost_price,t.cost_price*t.left_weight,t.warehouse_id
					from storage t left join frm_input i on t.frm_input_id = i.id
					left join common_forms c on i.purchase_id = c.id
					left join dict_title title on t.title_id = title.id					
					left join input_detail detail on t.input_detail_id = detail.id 
					left join dict_goods_property pg on pg.id=detail.product_id  
					left join dict_goods_property  dp_brand on dp_brand.id=detail.brand_id 
					where t.is_dx =0 and t.left_amount > 0  and dp_brand.short_name<>'贵航'  and t.is_deleted = 0 and t.card_status<>'clear'";
	        $connection->createCommand($sql)->execute();		
			
// // 	        更新锁定重量
	        $merges=MergeStorage::model()->with("dp_brand")->findAll('is_deleted=0 and lock_amount!=0 and dp_brand.short_name<>"贵航"');
	        if($merges)
	        {
	        	foreach ($merges as $each)
	        	{
	        		if($each->is_transit==1)
	        		{
	        			$storage=ProfitStorage::model()->find('storage_id='.$each->storage_id);
	        			if($storage)
	        			{
	        				$storage->lock_weight=$each->lock_weight;
	        				$storage->update();
	        			}
	        		}else{
	        			$this->lockAmountRec($each->product_id, $each->brand_id, $each->texture_id, $each->rank_id,$each->length, $each->title_id, $each->warehouse_id, $each->lock_weight);
	        		}
	        	}
	        }
	        
	        //更新未锁定的返利费用等	        
	      	$list=ProfitStorage::model()->findAll();
	      	if($list)
	      	{
	      		//read the config file
	      		$xml=readConfig();
	      		$subsidy=0;
	      		foreach ($xml->sales_percentage->step as $e)
	      		{
	      				$subsidy=$e->price;
	      				break;
	      		}
	      		foreach ($list as $ea)
	      		{
	      			$price=QuotedDetail::getEstimatePrice($ea->product_id,$ea->rank_id,$ea->texture_id,$ea->brand_id,$ea->length,date('Y-m-d'),$ea->warehouse_id);
	      			$left=$ea->left_weight-$ea->lock_weight;
	      			$sto=Storage::model()->findByPk($ea->storage_id);
	      			$brand_name=DictGoodsProperty::getProName($ea->brand_id);
	      			$invoice=$sto->invoice_price;//发票成本读取库存
// 	      			foreach ($xml->invoice->cost as $ea_cost)
// 	      			{
// 	      				if($ea_cost->brand==$brand_name)
// 	      				{
// 	      					$invoice=$ea_cost->price;
// 	      					break;
// 	      				}
// 	      			}
	      			if($ea->type=='purchase'||$ea->type=='ccrk')
	      			{
	      				$pro_name=DictGoodsProperty::getProName($ea->product_id);
	      				
	      				$purchase=FrmPurchase::model()->findByPk($ea->purchase_id);
	      				$pur_base=$purchase->baseform;
	      				$ea->price=$price;
	      				//采购价
	      				if($purchase->weight_confirm_status!=1)
	      				{//获取网价
// 	      					list($pur_price,$comment)=PurchasePrice::getNetPrice($pur_base->form_time,$brand_name,$ea->brand_id,$ea->product_id,$ea->texture_id,$ea->rank_id,$ea->length);
//       						$ea->comment=$comment;
// 	      					$ea->purchase_price=$pur_price;
	      				}elseif($ea->purchase_price==0){
	      					$ea->comment='采购单已审，价格来自库存成本，库存的成本单价为0';
	      				}else{
	      					$ea->comment="采购单已审，价格来自库存成本";
	      				}
	      				$ea->purchase_money=$left*$ea->purchase_price;
	      				//采购运费
	      				if(floatval($purchase->shipment))
	      				{
	      					$ea->purchase_freight=$left*$purchase->shipment;
	      				}else{
	      					$freight=0;
	      					$supply_name=DictCompany::getName($purchase->supply_id);
	      					foreach ($xml->freight->steelworks as $ea_fre)
	      					{
	      						if($ea_fre->name==$supply_name){
	      							$freight=$ea_fre->price;break;
	      						}	      					
	      					}
	      					$ea->purchase_freight=$left*$freight;
	      				}      				
	      				$ea->warehouse_fee=0;//(floatval($purchase->ware_cost)?$purchase->ware_cost:($pro_name=='螺纹钢'?$purchase->e_ware_cost_lwg:$purchase->e_ware_cost_other))*$left;
	      				$ea->warehouse_rebate=0;//(floatval($purchase->ware_rebate)?$purchase->ware_rebate:$purchase->e_ware_rebate)*$left;
	      				$sr=floatval($purchase->rebate)?$purchase->rebate:$purchase->e_rebate;
	      				$ea->supply_rebate=$sr*$left;	     
	      				if($sto->is_yidan)$ea->invoice=$left*$invoice;
	      				
	      				//托盘
	      				if($purchase->purchase_type=='tpcg')
	      				{
	      					$pledge=$purchase->pledge;
	      					$sql_p="select sum(weight) as weight,sum(interest_fee) as interest_fee from frm_pledge_redeem where purchase_id=".$purchase->id;
	      					$pledge_redeem=FrmPledgeRedeem::model()->findBySql($sql_p);	      					
	      					//算
	      					$days=ceil((time()-strtotime($pur_base->form_time))/86400);
	      					if($days<=$pledge->pledge_length)
	      					{
	      						$ea->pledge_fee=$pledge_redeem->interest_fee+$pledge->min_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*$pledge->pledge_length;
	      					}elseif($days<=$pledge->violation_date)
	      					{
	      						$ea->pledge_fee=$pledge_redeem->interest_fee+$pledge->min_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*$pledge->pledge_length
	      						+$pledge->pledge_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*($days-$pledge->pledge_length);
	      					}else{
	      						$ea->pledge_fee=$pledge_redeem->interest_fee+$pledge->min_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*$pledge->pledge_length
	      						+$pledge->pledge_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*($pledge->violation_date-$pledge->pledge_length)
	      						+$pledge->over_rate*($pledge->fee-$pledge->unit_price*$pledge_redeem->weight)*($days-$pledge->violation_date);
	      					}
	      					if($purchase->weight_confirm_status==1)
	      					{
	      						$ea->pledge_fee=$ea->pledge_fee*$left/$purchase->confirm_weight;
	      					}else{
	      						$ea->pledge_fee=$ea->pledge_fee*$left/$purchase->weight;
	      					}
	      				}else{
	      					$ea->pledge_fee =0;//托盘利息
	      				}	      				
	      				$ea->sale_subsidy=0;//$left*$subsidy;
	      				$ea->supply_id=$purchase->supply_id;
	      				$ea->supply_name=DictCompany::getLongName($ea->supply_id);
	      				//利润
						$ea->profit=$left*$price-$ea->purchase_money-$ea->purchase_freight-$ea->warehouse_fee+$ea->warehouse_rebate+$ea->supply_rebate-$ea->invoice-$ea->pledge_fee-$ea->sale_subsidy;
	      				$ea->update();
	      			}else{
	      				if($ea->purchase_price==0){
	      					$ea->comment='销售退货库存，价格来自库存成本，成本单价为0';
	      				}else{
	      					$ea->comment="销售退货库存，价格来自库存成本";
	      				}
	      				$ea->price=$price;
	      				$ea->purchase_money=$left*$ea->purchase_price;
	      				$ea->sale_subsidy=0;//$left*$subsidy;
	      				$ea->profit=$left*$price-$ea->purchase_money-$ea->sale_subsidy;
	      				if($sto->is_yidan)$ea->invoice=$left*$invoice;
	      				$ea->update();
	      			}
	      		}
	      	}        
        $transaction->commit();       
        }catch (Exception $e)
        {
        		echo $e;
        		$transaction->rollback();
        		return;
        }
        @session_destroy();
	}
	
	
	/*
	 * 锁定件数递归
	 */
// 	public function lockAmountRec($product,$brand,$texture,$rank,$length,$title,$warehouse,$weight)
// 	{
// 		static $ids='0';
// 		$condition="product_id={$product} and brand_id={$brand} and texture_id={$texture} and rank_id={$rank}  and length={$length} and title_id={$title} 
// 		and warehouse_id={$warehouse} and id not in ({$ids}) ";
// 		$sql="select min(input_date),id,left_weight,lock_weight,purchase_price,product_id,purchase_id from profit_storage 
// 		where input_date=(select min(input_date) from profit_storage where {$condition}) and {$condition}";
// 		$model=ProfitStorage::model()->findBySql($sql);
// 		if($model->id)
// 		{
// 			if($weight-$model->left_weight>0){
// 				$model->lock_weight=$model->left_weight;
// 				$model->update();
// 				$ids.=','.$model->id;				
// 				$this->lockAmountRec($product, $brand, $texture, $rank, $length,$title, $warehouse, $weight-$model->left_weight);
// 			}elseif($weight>0){
// 				$model->lock_weight=$weight;				
// 				$model->update();
// 				$ids='0';
// 			}else{
// 				$ids='0';
// 			}
// 		}else{
// 			$ids='0';
// 		}
// 	}

	public function lockAmountRec($product,$brand,$texture,$rank,$length,$title,$warehouse,$weight)
	{
		$condition="product_id={$product} and brand_id={$brand} and texture_id={$texture} and rank_id={$rank}  and length={$length} and title_id={$title}
		and warehouse_id={$warehouse} ";
		$sql="select input_date,id,left_weight,lock_weight,purchase_price,product_id,purchase_id from profit_storage
		where  {$condition} order by input_date asc";
		$model=ProfitStorage::model()->findAllBySql($sql);
		if($model)
		{	
			$num=count($model);
			$i=1;
			foreach ($model as $each)
			{
				if($weight-$each->left_weight>0)
				{
					if($i<$num)
					{
						$each->lock_weight=$each->left_weight;
						$weight=$weight-$each->left_weight;
					}else{
						$each->lock_weight=$weight;						
					}
					$each->update();
				}else{
					$each->lock_weight=$weight;
					$each->update();
					break;
				}
				$i++;
			}			
		}
	}
		
	
	
	//采销利润导出
	public function actionBsExport()
	{	//echo memory_get_usage(),'<br>';
// 		ini_set('memory_limit','1024M');
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['time_L']==''||$search['time_L']<date('Y-m-d',Yii::app()->params['turn_time']))
				$search['time_L']=date('Y-m-d',Yii::app()->params['turn_time']);			
		}else{		
			if(!$search['time_L'])
				$search['time_L']=date('Y-m-d',Yii::app()->params['turn_time']);			
		}		
		$name = "采销利润".date("Y/m/d");
		$title = array('销售日期', '产地',
				'品名','材质', '规格', '长度',
				'销售公司', '结算单位', '销售利润', '吨均利润',
				'销售单号', '销售数量', '销售单价',
				'销售金额', '销售运费',
				'销售折让', '采购/退货单号',
				'成本单价', '成本金额', '采购运费', '托盘费用',
				'钢厂返利', '仓库费用',
				'仓库返利', '业务员', '是否乙单', '发票成本', '销售提成', '仓库', '备注');					
		$content = ProfitCollecting::getExportData($search);		
		PHPExcel::ExcelExport($name, $title, $content);
		// echo memory_get_usage();
	}
	
	//库存预估利润导出
	public function actionStorageProfitExport()
	{	//echo memory_get_usage(),'<br>';
		$search=array();		
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['time_H']==''){
				$search['time_H']=date('Y-m-d');
			}
		}else{		
			$search['time_H']=date('Y-m-d');
		}
		$name = "库存预估利润".date("Y/m/d");
		$title = array('入库日期', '产地','卡号','销售公司',
				'品名','材质', '规格', '长度',
				'数量', '预估销售单价', '预估销售金额', '预估利润',				
				 '采购/退货单号',
				'成本单价', '成本金额', '运费',  '发票成本', '仓库费用','托盘费用',
				'预估钢厂返利',
				'仓库返利',  '预估销售提成', '仓库', '备注');
		$content = ProfitStorage::getExportData($search);
		PHPExcel::ExcelExport($name, $title, $content);
		// echo memory_get_usage();
	}
	
	
	public function actionTestPrice()
	{
		list($price,$comment)=PurchasePrice::getNetPrice('2016-07-06','华宏','11','40','63','46',0);
		echo $price,$comment;
	}
	
	
	
	
	
	
}

?>