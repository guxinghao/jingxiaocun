<?php
class MenuItem{
	var $name;
	var $url;
	var $right_name;
	var $sub_menus = array();

	/**
	 * 获取当前登录用户拥有权限的菜单，
	 * @return [Array] [MenuItem 数组]
	 */
	public static function getValideMenus()
	{
		$allMenus = MenuItem::getGlobalMenu();

		$_result = Array();
		//一级菜单循环
		foreach ($allMenus as $_menu) {
			$_menu->getValideSubMenus();
			if ($_menu->sub_menus) {
				$_result[] = $_menu;
			}
		}
		return $_result;
	}

	/**
	 * 二级菜单循环
	 */
	public function getValideSubMenus()
	{
		$_result = array();

		//二级菜单循环
		foreach ($this->sub_menus as $_menu)
		{
			$_menu->getValideLastMenus();
			if ($_menu->sub_menus)
				$_result[] = $_menu;
		}
		$this->sub_menus = $_result;
	}


	/**
	 * 三级菜单
	 * 获取当前菜单下，有权限的子菜单
	 */
	public function getValideLastMenus(){
		$temp = array();
		foreach ($this->sub_menus as $_item){

			if ($_item->right_name != "" && !$this->getAuth($_item->right_name)){
				continue;
			}
			$_temp[] = $_item;
		}
		$this->sub_menus = $_temp;
	}

	public function getAuth($authitem){
		$auth=Yii::app()->authManager;
		$bool=$auth->checkAccess($authitem,Yii::app()->user->userid);

		return $bool;
	}

	public static function getGlobalMenu()
	{
		global $g_menu ;
		if ($g_menu ) return $g_menu;
		$g_menu = array();

		//采购管理主菜单
		$buyManager = new MenuItem;
		$buyManager->name = "采购";
		$buyManager->right_name = "采购";
		$buyManager->url="#";
		$g_menu[] = $buyManager;
			//采购单
			$buy1Manager = new MenuItem;
			$buy1Manager->name = "采购单";
			$buy1Manager->right_name = "采购单";
			$buy1Manager->url=Yii::app()->createUrl("purchase");
			//采购合同
			$buy2Manager = new MenuItem;
			$buy2Manager->name = "采购合同";
			$buy2Manager->right_name = "采购合同";
			$buy2Manager->url=Yii::app()->createUrl("contract");
			//采购退货
			$buy8Manager = new MenuItem;
			$buy8Manager->name = "采购退货";
			$buy8Manager->right_name = "采购退货";
			$buy8Manager->url=Yii::app()->createUrl("frmPurchaseReturn/index");
			//托盘管理
			$buy3Manager = new MenuItem;
			$buy3Manager->name = "托盘查询";
			$buy3Manager->right_name = "托盘管理";
			$buy3Manager->url = Yii::app()->createUrl("pledge/pledgeSearch");
			//托盘赎回
			$buy4Manager = new MenuItem;
			$buy4Manager->name = "托盘赎回";
			$buy4Manager->right_name = "托盘赎回";
			$buy4Manager->url = Yii::app()->createUrl("pledge");
			//仓库返利
			$buy5Manager = new MenuItem;
			$buy5Manager->name = "仓库返利";
			$buy5Manager->right_name = "仓库返利";
			$buy5Manager->url = Yii::app()->createUrl("billRebate/index", array('type' => "warehouse"));
			//钢厂返利
			$buy6Manager = new MenuItem;
			$buy6Manager->name = "钢厂返利";
			$buy6Manager->right_name = "钢厂返利";
			$buy6Manager->url = Yii::app()->createUrl("billRebate/index", array('type' => "supply"));
			//仓储费用
			$buy7Manager = new MenuItem;
			$buy7Manager->name = "仓库费用";
			$buy7Manager->right_name = "仓储费用";
			$buy7Manager->url = Yii::app()->createUrl("billRebate/index", array('type' => "cost"));
			//运费登记
			$buy9Manager = new MenuItem;
			$buy9Manager->name = "采购运费";
			$buy9Manager->right_name = "采购运费";
			$buy9Manager->url = Yii::app()->createUrl("billRecord/index", array('type' => "purchase"));

			//采购折让
			$buy10Manager = new MenuItem;
			$buy10Manager->name = "采购折让";
			$buy10Manager->right_name = "采购折让";
			$buy10Manager->url = Yii::app()->createUrl("rebate/index", array('type' => "shipment"));
		//销售管理主菜单
		$shopManager = new MenuItem;
		$shopManager->name = "销售";
		$shopManager->right_name = "销售";
		$shopManager->url="#";
		$g_menu[] = $shopManager;
			//销售单
			$shop1Manager = new MenuItem;
			$shop1Manager->name = "销售单";
			$shop1Manager->right_name = "销售单";
			$shop1Manager->url=Yii::app()->createUrl("frmSales");
			//配送单
			$shop2Manager = new MenuItem();
			$shop2Manager->name = "配送单";
			$shop2Manager->right_name = "配送单";
			$shop2Manager->url=Yii::app()->createUrl("frmSend/index",array("type"=>"menu"));
			//销售折让
			$shop3Manager = new MenuItem();
			$shop3Manager->name = "销售折让";
			$shop3Manager->right_name = "销售折让";
			$shop3Manager->url=Yii::app()->createUrl("rebate/index", array('type' => "sale"));
			//高开折让
			$shop4Manager = new MenuItem();
			$shop4Manager->name = "高开折让";
			$shop4Manager->right_name = "高开折让";
			$shop4Manager->url=Yii::app()->createUrl("rebate/index", array('type' => "high"));
			//销售退货
			$shop5Manager = new MenuItem();
			$shop5Manager->name = "销售退货";
			$shop5Manager->right_name = "销售退货";
			$shop5Manager->url=Yii::app()->createUrl("salesReturn/index");
			//运费登记
			$shop6Manager = new MenuItem;
			$shop6Manager->name = "销售运费";
			$shop6Manager->right_name = "销售运费";
			$shop6Manager->url = Yii::app()->createUrl("billRecord/index", array('type' => "sales"));
			//申请记录
			$shop7Manager = new MenuItem;
			$shop7Manager->name = "申请记录";
			$shop7Manager->right_name = "取消申请记录";
			$shop7Manager->url = Yii::app()->createUrl("frmSales/cancelApproveReason");
		//库存管理主菜单
		$stockManager = new MenuItem;
		$stockManager->name = "库存";
		$stockManager->right_name = "库存";
		$stockManager->url="#";
		$g_menu[] = $stockManager;
			//库存查看
			$stock0Manager = new MenuItem;
			$stock0Manager->name = "库存管理";
			$stock0Manager->right_name = "库存管理";
			$stock0Manager->url=Yii::app()->createUrl("mergeStorage/locklist");
			//代销库存
			$stock8Manager = new MenuItem;
			$stock8Manager->name = "代销库存";
			$stock8Manager->right_name = "代销库存管理";
			$stock8Manager->url=Yii::app()->createUrl("storage/dx");
			//出库管理
			$stock1Manager = new MenuItem;
			$stock1Manager->name = "出库管理";
			$stock1Manager->right_name = "出库管理";
			$stock1Manager->url=Yii::app()->createUrl("frmOutput/index",array("from"=>"menu"));
			//仓库出库
			$stock2Manager = new MenuItem;
			$stock2Manager->name = "仓库出库";
			$stock2Manager->right_name = "仓库出库";
			$stock2Manager->url=Yii::app()->createUrl("warehouseOutput");
			//入库计划
			$stock3Manager = new MenuItem;
			$stock3Manager->name = "入库计划";
			$stock3Manager->right_name = "入库计划";
			$stock3Manager->url=Yii::app()->createUrl("inputPlan/index");
			//入库管理
			$stock4Manager = new MenuItem;
			$stock4Manager->name = "入库管理";
			$stock4Manager->right_name = "入库管理";
			$stock4Manager->url=Yii::app()->createUrl('input');
			//仓库入库
			$stock5Manager = new MenuItem;
			$stock5Manager->name = "仓库入库";
			$stock5Manager->right_name = "仓库入库";
			$stock5Manager->url=Yii::app()->createUrl("input/pushedList");

			//仓库入库
			$stock5Manager = new MenuItem;
			$stock5Manager->name = "仓库入库";
			$stock5Manager->right_name = "仓库入库";
			$stock5Manager->url=Yii::app()->createUrl("input/pushedList");

			//代销入库
			$stock6Manager = new MenuItem;
			$stock6Manager->name = "代销入库";
			$stock6Manager->right_name = "代销入库";
			$stock6Manager->url=Yii::app()->createUrl("input/index",array('type'=>"dxrk"));
			$stock7Manager = new MenuItem;
			$stock7Manager->name = "船舱入库";
			$stock7Manager->right_name = "船舱入库";
			$stock7Manager->url=Yii::app()->createUrl("inputCcrk/index",array('input_type'=>'ccrk'));
			//保留库存
			$stock9Manager = new MenuItem;
			$stock9Manager->name = "保留库存";
			$stock9Manager->right_name = "保留库存";
			$stock9Manager->url=Yii::app()->createUrl("storage/index",array('type'=>'retain'));
			//盘盈盘亏
			$stock10Manager = new MenuItem;
			$stock10Manager->name = "盘盈盘亏";
			$stock10Manager->right_name = "盘盈盘亏";
			$stock10Manager->url=Yii::app()->createUrl("frmPypk/index");
			//代销调拨
			$stock11Manager = new MenuItem;
			$stock11Manager->name = "代销调拨";
			$stock11Manager->right_name = "代销调拨";
			$stock11Manager->url=Yii::app()->createUrl("stockTransfer");
			//库存转库
			$stock12Manager = new MenuItem;
			$stock12Manager->name = "库存转库";
			$stock12Manager->right_name = "库存转库";
			$stock12Manager->url=Yii::app()->createUrl("#");

			$stock13Manager = new MenuItem;
			$stock13Manager->name = "库存查询";
			$stock13Manager->right_name = "库存查询";
			$stock13Manager->url=Yii::app()->createUrl("storage/search");

			$stock14Manager = new MenuItem;
			$stock14Manager->name = "卡号管理";
			$stock14Manager->right_name = "卡号管理";
			$stock14Manager->url=Yii::app()->createUrl("storage/index");

			$stock15Manager = new MenuItem;
			$stock15Manager->name = "异常列表";
			$stock15Manager->right_name = "异常列表";
			$stock15Manager->url=Yii::app()->createUrl("interface/failList");

			$stock16Manager = new MenuItem;
			$stock16Manager->name = "自动出库";
			$stock16Manager->right_name = "自动出库";
			$stock16Manager->url=Yii::app()->createUrl("frmOutput/autoOutput");
		//仓库主菜单
		$whouseManager = new MenuItem;
		$whouseManager->name = "仓库";
		$whouseManager->right_name = "仓库";
		$whouseManager->url="#";
		$g_menu[] = $whouseManager;
			//接口异常
			$whouse1Manager = new MenuItem;
			$whouse1Manager->name = "接口异常";
			$whouse1Manager->right_name = "接口异常";
			$whouse1Manager->url = Yii::app()->createUrl("#");
		//财务管理主菜单
		$financeManager = new MenuItem;
		$financeManager->name = "财务";
		$financeManager->right_name = "财务";
		$financeManager->url="#";
		$g_menu[] = $financeManager;
			//付款
			$finance1Manager = new MenuItem;
			$finance1Manager->name = "付款";
			$finance1Manager->right_name = "付款";
			$finance1Manager->url = Yii::app()->createUrl("formBill/index", array('type' => "FKDJ"));
			//收款
			$finance2Manager = new MenuItem;
			$finance2Manager->name = "收款";
			$finance2Manager->right_name = "收款";
			$finance2Manager->url = Yii::app()->createUrl("formBill/index", array('type' => "SKDJ"));
			//其他收入
			$finance3Manager = new MenuItem;
			$finance3Manager->name = "其他收入";
			$finance3Manager->right_name = "其他收入";
			$finance3Manager->url = Yii::app()->createUrl("billOther/index",array("type"=>"QTSR"));
			//费用报支
			$finance4Manager = new MenuItem;
			$finance4Manager->name = "费用报支";
			$finance4Manager->right_name = "费用报支";
			$finance4Manager->url = Yii::app()->createUrl("billOther/index",array("type"=>"FYBZ"));
			//费用报支
			$finance10Manager = new MenuItem;
			$finance10Manager->name = "销售提成";
			$finance10Manager->right_name = "销售提成";
			$finance10Manager->url = Yii::app()->createUrl("salesCommission/index");
			//采购销票
			$finance5Manager = new MenuItem;
			$finance5Manager->name = "采购销票";
			$finance5Manager->right_name = "采购销票";
			$finance5Manager->url = Yii::app()->createUrl("purchaseInvoice/index");
			//销售开票
			$finance6Manager = new MenuItem;
			$finance6Manager->name = "销售开票";
			$finance6Manager->right_name = "销售开票";
			$finance6Manager->url = Yii::app()->createUrl("salesInvoice/index");
			//短期借贷
			$finance7Manager = new MenuItem;
			$finance7Manager->name = "短期借贷";
			$finance7Manager->right_name = "短期借贷";
			$finance7Manager->url = Yii::app()->createUrl("shortLoan/index");
			//银行互转
			$finance8Manager = new MenuItem;
			$finance8Manager->name = "银行互转";
			$finance8Manager->right_name = "银行互转";
			$finance8Manager->url = Yii::app()->createUrl("transferAccounts/index");
			//凭证列表
			$finance9Manager = new MenuItem;
			$finance9Manager->name = "凭证列表";
			$finance9Manager->right_name = "凭证列表";
			$finance9Manager->url = Yii::app()->createUrl("voucher/index");
			//已忽略凭证
			$finance11Manager = new MenuItem;
			$finance11Manager->name = "已忽略凭证";
			$finance11Manager->right_name = "已忽略凭证";
			$finance11Manager->url = Yii::app()->createUrl("voucher/ignoreList");

			//板卷付款
			$finance12Manager = new MenuItem;
			$finance12Manager->name = "板卷付款";
			$finance12Manager->right_name = "板卷付款";
			$finance12Manager->url = Yii::app()->params['parallel_url']."/index.php/formBill/index?type=FKDJ";

			//板卷收款
			$finance13Manager = new MenuItem;
			$finance13Manager->name = "板卷收款";
			$finance13Manager->right_name = "板卷收款";
			$finance13Manager->url = Yii::app()->params['parallel_url']."/index.php/formBill/index?type=SKDJ";


		//价格管理
		$priceManager = new MenuItem;
		$priceManager->name = "价格";
		$priceManager->right_name = "价格";
		$priceManager->url="#";
		$g_menu[] = $priceManager;
			//当日指导价
			$price1Manager = new MenuItem;
			$price1Manager->name = "当日指导价";
			$price1Manager->right_name = "当日指导价";
			$price1Manager->url = Yii::app()->createUrl("quotedDetail/index",array("date_type"=>"yes",'type'=>'guidance'));
			//指导价管理
			$price2Manager = new MenuItem;
			$price2Manager->name = "指导价管理";
			$price2Manager->right_name = "指导价管理";
			$price2Manager->url = Yii::app()->createUrl("quotedDetail/edit",array('type'=>'guidance','date_type'=>'yes'));
			//历史指导价
			$price3Manager = new MenuItem;
			$price3Manager->name = "历史指导价";
			$price3Manager->right_name = "历史指导价";
			$price3Manager->url = Yii::app()->createUrl("quotedDetail/index",array('type'=>'guidance'));
			//当日采购价
			$price4Manager = new MenuItem;
			$price4Manager->name = "当日网价";
			$price4Manager->right_name = "当日采购价";
			$price4Manager->url =  Yii::app()->createUrl("purchasePrice/index",array("date_type"=>"yes"));
			//采购价管理
			$price5Manager = new MenuItem;
			$price5Manager->name = "网价管理";
			$price5Manager->right_name = "采购价管理";
			$price5Manager->url = Yii::app()->createUrl("purchasePrice/update",array('date_type'=>'yes'));
			//历史采购价
			$price6Manager = new MenuItem;
			$price6Manager->name = "历史网价";
			$price6Manager->right_name = "历史采购价";
			$price6Manager->url = Yii::app()->createUrl("purchasePrice/index",array('type'=>'net'));
			//基价差管理
			$price7Manager = new MenuItem;
			$price7Manager->name = "基价差管理";
			$price7Manager->right_name = "基价差管理";
			$price7Manager->url = Yii::app()->createUrl("quotedDetail/edit",array('type'=>'spread'));

			//专区管理
			$price8Manager = new MenuItem;
			$price8Manager->name = "专区管理";
			$price8Manager->right_name = "专区管理";
			$price8Manager->url=Yii::app()->createUrl("prefecture/index");
		//报表管理主菜单
		$fromManager = new MenuItem;
		$fromManager->name = "报表";
		$fromManager->right_name = "报表";
		$fromManager->url="#";
		$g_menu[] = $fromManager;
			//利润统计
			$from1Manager = new MenuItem;
			$from1Manager->name = "利润汇总";
			$from1Manager->right_name = "利润汇总";
			$from1Manager->url=Yii::app()->createUrl("table/totalProfit");
			//采销利润明细
			$from2Manager = new MenuItem;
			$from2Manager->name = "采销利润";
			$from2Manager->right_name = "采销利润";
			$from2Manager->url=Yii::app()->createUrl("table/bsProfit");
			//库存预估利润明细
			$from3Manager = new MenuItem;
			$from3Manager->name = "库存预估";
			$from3Manager->right_name = "库存预估";
			$from3Manager->url=Yii::app()->createUrl("table/storageProfit");
			//差异统计
			$from4Manager = new MenuItem;
			$from4Manager->name = "差异统计";
			$from4Manager->right_name = "差异统计";
			$from4Manager->url=Yii::app()->createUrl("#");
			//托盘统计
			$from5Manager = new MenuItem;
			$from5Manager->name = "托盘统计";
			$from5Manager->right_name = "托盘统计";
			$from5Manager->url=Yii::app()->createUrl("pledge/dataTable");
			//销售汇总
			$from6Manager = new MenuItem;
			$from6Manager->name = "销售汇总";
			$from6Manager->right_name = "销售汇总";
			$from6Manager->url=Yii::app()->createUrl("frmSales/totaldata");
			//销售明细
			$from7Manager = new MenuItem;
			$from7Manager->name = "销售明细";
			$from7Manager->right_name = "销售明细";
			$from7Manager->url=Yii::app()->createUrl("frmSales/totaldetails");
			//采购汇总
			$from8Manager = new MenuItem;
			$from8Manager->name = "采购汇总";
			$from8Manager->right_name = "采购汇总";
			$from8Manager->url=Yii::app()->createUrl("purchase/purchaseData");
			//采购明细
			$from9Manager = new MenuItem;
			$from9Manager->name = "采购明细";
			$from9Manager->right_name = "采购明细";
			$from9Manager->url=Yii::app()->createUrl("purchaseDetail/index");
			//库存汇总
			$from10Manager = new MenuItem;
			$from10Manager->name = "库存汇总";
			$from10Manager->right_name = "库存汇总";
			$from10Manager->url=Yii::app()->createUrl("storage/total");
			//代销库存统计
			$from11Manager = new MenuItem;
			$from11Manager->name = "代销库存";
			$from11Manager->right_name = "代销库存";
			$from11Manager->url=Yii::app()->createUrl("#");
			//库存流水
			$from12Manager = new MenuItem;
			$from12Manager->name = "库存流水";
			$from12Manager->right_name = "库存明细";
			$from12Manager->url=Yii::app()->createUrl("storageTurnover/index");
			//往来汇总
			$from13Manager = new MenuItem;
			$from13Manager->name = "往来汇总";
			$from13Manager->right_name = "往来汇总";
			$from13Manager->url=Yii::app()->createUrl("turnover/total");
			//往来明细
			$from14Manager = new MenuItem;
			$from14Manager->name = "往来明细";
			$from14Manager->right_name = "往来明细";
			$from14Manager->url=Yii::app()->createUrl("turnover/index");
			//资金账户
			$from15Manager = new MenuItem;
			$from15Manager->name = "资金汇总";
			$from15Manager->right_name = "资金汇总";
			$from15Manager->url=Yii::app()->createUrl("frmBillLog/total");
			//发票汇总
			$from16Manager = new MenuItem;
			$from16Manager->name = "开票汇总";
			$from16Manager->right_name = "开票汇总";
			$from16Manager->url=Yii::app()->createUrl("detailForInvoice/index");
			//销票汇总
			$from20Manager = new MenuItem;
			$from20Manager->name = "销票汇总";
			$from20Manager->right_name = "销票汇总";
			$from20Manager->url=Yii::app()->createUrl("detailForInvoice/index1");
			//合同报表
			$from17Manager = new MenuItem;
			$from17Manager->name = "合同汇总";
			$from17Manager->right_name = "合同报表";
			$from17Manager->url=Yii::app()->createUrl("contract/dataTable");
			//锁库存报表
			$from18Manager = new MenuItem;
			$from18Manager->name = "库存锁定";
			$from18Manager->right_name = "库存锁定";
			$from18Manager->url=Yii::app()->createUrl("mergeStorage/locklist");
			//资金账户明细
			$from19Manager = new MenuItem;
			$from19Manager->name = "资金明细";
			$from19Manager->right_name = "资金明细";
			$from19Manager->url=Yii::app()->createUrl("frmBillLog/index");
			//托盘报表
			$from21Manager = new MenuItem;
			$from21Manager->name = "托盘报表";
			$from21Manager->right_name = "托盘报表";
			$from21Manager->url=Yii::app()->createUrl("purchase/BAT");

			//往来统计
			$from22Manager = new MenuItem;
			$from22Manager->name = "往来统计";
			$from22Manager->right_name = "往来统计";
			$from22Manager->url=Yii::app()->createUrl("turnover/anotherTotal1");

			//销售排行
			$from23Manager = new MenuItem;
			$from23Manager->name = "销售排行";
			$from23Manager->right_name = "销售排行";
			$from23Manager->url=Yii::app()->createUrl("table/salesRank");

			//采购往来汇总
			$from24Manager = new MenuItem;
			$from24Manager->name = "采购往来汇总";
			$from24Manager->right_name = "采购往来汇总";
			$from24Manager->url=Yii::app()->createUrl("turnover/purchaseTotal");

			//销售往来汇总
			$from25Manager = new MenuItem;
			$from25Manager->name = "销售往来汇总";
			$from25Manager->right_name = "销售往来汇总";
			$from25Manager->url=Yii::app()->createUrl("turnover/saleTotal");

			//往来统计
			$from26Manager = new MenuItem;
			$from26Manager->name = "往来统计:优";
			$from26Manager->right_name = "往来统计:优";
			$from26Manager->url=Yii::app()->createUrl("turnover/anotherTotal1");
			//往来汇总
			$from27Manager = new MenuItem;
			$from27Manager->name = "往来汇总:优";
			$from27Manager->right_name = "往来汇总:优";
			$from27Manager->url=Yii::app()->createUrl("turnover/totalNew");
			//销售排行
			$from28Manager = new MenuItem;
			$from28Manager->name = "销售排行:优";
			$from28Manager->right_name = "销售排行:优";
			$from28Manager->url=Yii::app()->createUrl("table/newSalesRank");

		//设置
		$setManager = new MenuItem;
		$setManager->name = "设置";
		$setManager->right_name = "设置";
		$setManager->url="#";
		$g_menu[] = $setManager;
			//公司管理
			$titleManager = new MenuItem;
			$titleManager->name = "公司管理";
			$titleManager->right_name = "公司管理";
			$titleManager->url=Yii::app()->createUrl("dictTitle");
			//结算单位
			$set1Manager = new MenuItem;
			$set1Manager->name = "结算单位";
			$set1Manager->right_name = "结算单位";
			$set1Manager->url=Yii::app()->createUrl("dictCompany");
			//产地管理
			$set2Manager = new MenuItem;
			$set2Manager->name = "产地管理";
			$set2Manager->right_name = "产地管理";
			$set2Manager->url=Yii::app()->createUrl("dictGoodsProperty/index",array("property_type"=>"brand"));
			//品名管理
			$set3Manager = new MenuItem;
			$set3Manager->name = "品名管理";
			$set3Manager->right_name = "品名管理";
			$set3Manager->url=Yii::app()->createUrl("dictGoodsProperty/index",array("property_type"=>"product"));
			//材质管理
			$set4Manager = new MenuItem;
			$set4Manager->name = "材质管理";
			$set4Manager->right_name = "材质管理";
			$set4Manager->url=Yii::app()->createUrl("dictGoodsProperty/index",array("property_type"=>"texture"));
			//规格管理
			$set5Manager = new MenuItem;
			$set5Manager->name = "规格管理";
			$set5Manager->right_name = "规格管理";
			$set5Manager->url=Yii::app()->createUrl("dictGoodsProperty/index",array("property_type"=>"rank"));
			//件重管理
			$set6Manager = new MenuItem;
			$set6Manager->name = "件重管理";
			$set6Manager->right_name = "件重管理";
			$set6Manager->url=Yii::app()->createUrl("dictGoods");
			//仓库管理
			$set7Manager = new MenuItem;
			$set7Manager->name = "仓库管理";
			$set7Manager->right_name = "仓库管理";
			$set7Manager->url=Yii::app()->createUrl("warehouse");
			//业务组
			$set8Manager = new MenuItem;
			$set8Manager->name = "业务组";
			$set8Manager->right_name = "业务组";
			$set8Manager->url=Yii::app()->createUrl("team");
			//银行管理
			$set9Manager = new MenuItem;
			$set9Manager->name = "公司账户";
			$set9Manager->right_name = "公司银行管理";
			$set9Manager->url=Yii::app()->createUrl("dictBankInfo");
			//银行账号管理
			$set10Manager = new MenuItem;
			$set10Manager->name = "结算账户";
			$set10Manager->right_name = "结算单位银行管理";
			$set10Manager->url=Yii::app()->createUrl("bankInfo");
			//结算单位联系人管理
			$set11Manager = new MenuItem;
			$set11Manager->name = "结算联系人";
			$set11Manager->right_name = "结算联系人";
			$set11Manager->url=Yii::app()->createUrl("companyContact/index");

			//区域管理
			$set12Manager = new MenuItem;
			$set12Manager->name = "区域管理";
			$set12Manager->right_name = "区域管理";
			$set12Manager->url=Yii::app()->createUrl("area/index");


		//系统管理
		$systemManager = new MenuItem;
		$systemManager->name = "系统";
		$systemManager->right_name = "系统";
		$systemManager->url="#";
		$g_menu[] = $systemManager;
			//操作管理
			$operationManager = new MenuItem;
			$operationManager->name = "操作管理";
			$operationManager->right_name = "操作管理";
			$operationManager->url=Yii::app()->createUrl("operation");
			//任务管理
			$taskManager = new MenuItem;
			$taskManager->name = "任务管理";
			$taskManager->right_name = "任务管理";
			$taskManager->url=Yii::app()->createUrl("task");
			//角色管理
			$roleManager = new MenuItem;
			$roleManager->name = "角色管理";
			$roleManager->right_name = "角色管理";
			$roleManager->url=Yii::app()->createUrl("role");
			//用户管理
			$system1Manager = new MenuItem;
			$system1Manager->name = "用户管理";
			$system1Manager->right_name = "用户管理";
			$system1Manager->url=Yii::app()->createUrl("user");
			//操作日志
			$system2Manager = new MenuItem;
			$system2Manager->name = "操作日志";
			$system2Manager->right_name = "操作日志";
			$system2Manager->url=Yii::app()->createUrl("log");
			//数据日志
			$system3Manager = new MenuItem;
			$system3Manager->name = "数据日志";
			$system3Manager->right_name = "数据日志";
			$system3Manager->url=Yii::app()->createUrl("logDetail");
			//参数配置
			$system4Manager = new MenuItem;
			$system4Manager->name = "参数配置";
			$system4Manager->right_name = "参数配置";
			$system4Manager->url = Yii::app()->createUrl("sysConfig");
			//修改密码
			$system5Manager = new MenuItem;
			$system5Manager->name = "修改密码";
			$system5Manager->right_name = "修改密码";
			$system5Manager->url = Yii::app()->params["api_url"]."/index.php/users/changePwd";

			//微信用户管理
			$system6Manager = new MenuItem;
			$system6Manager->name = "微信客户";
			$system6Manager->right_name = "微信客户";
			$system6Manager->url=Yii::app()->createUrl("wxUser/index");

		//采购单-----------------------------------------------------------------
			$mid1Manager = new MenuItem;
			$mid1Manager->name = "单";
			$mid1Manager->right_name = "middle";
			$mid1Manager->url="#";

			$mid2Manager = new MenuItem;
			$mid2Manager->name = "货";
			$mid2Manager->right_name = "middle";
			$mid2Manager->url="#";

			$mid3Manager = new MenuItem;
			$mid3Manager->name = "款";
			$mid3Manager->right_name = "middle";
			$mid3Manager->url="#";

			$mid4Manager = new MenuItem;
			$mid4Manager->name = "票";
			$mid4Manager->right_name = "middle";
			$mid4Manager->url="#";
			//单
			$mid1Manager->sub_menus[] = $buy1Manager;//采购单
			$mid1Manager->sub_menus[] = $buy2Manager;//采购合同
			$mid1Manager->sub_menus[] = $buy8Manager;//采购退货
			$mid1Manager->sub_menus[] = $buy9Manager;//采购运费
			$mid1Manager->sub_menus[] = $buy6Manager;//钢厂返利
			$mid1Manager->sub_menus[] = $buy5Manager;//仓库返利
			$mid1Manager->sub_menus[] = $buy7Manager;//仓储费用
			$mid1Manager->sub_menus[] = $buy10Manager;//采购折让
			//货
			$mid2Manager->sub_menus[] = $stock3Manager;//入库计划
			$mid2Manager->sub_menus[] = $stock4Manager;//入库管理
			$mid2Manager->sub_menus[] = $stock7Manager;//船舱入库
			$mid2Manager->sub_menus[] = $stock5Manager;//仓库入库
			//款
			$mid3Manager->sub_menus[] = $finance1Manager;//付款登记

			//票
			$mid4Manager->sub_menus[] = $finance5Manager;//采购销票

			$buyManager->sub_menus[] = $mid1Manager;
			$buyManager->sub_menus[] = $mid2Manager;
			$buyManager->sub_menus[] = $mid3Manager;
			$buyManager->sub_menus[] = $mid4Manager;

		//销售-------------------------------------------
			$mid1Manager = new MenuItem;
			$mid1Manager->name = "单";
			$mid1Manager->right_name = "middle";
			$mid1Manager->url="#";

			$mid2Manager = new MenuItem;
			$mid2Manager->name = "货";
			$mid2Manager->right_name = "middle";
			$mid2Manager->url="#";

			$mid3Manager = new MenuItem;
			$mid3Manager->name = "款";
			$mid3Manager->right_name = "middle";
			$mid3Manager->url="#";

			$mid4Manager = new MenuItem;
			$mid4Manager->name = "票";
			$mid4Manager->right_name = "middle";
			$mid4Manager->url="#";

			//单
			$mid1Manager->sub_menus[] = $shop1Manager;//销售单
			$mid1Manager->sub_menus[] = $shop7Manager;//申请记录
			$mid1Manager->sub_menus[] = $shop5Manager;//销售退货
			$mid1Manager->sub_menus[] = $shop6Manager;//销售运费
			//货
			$mid2Manager->sub_menus[] = $shop2Manager;//配送单
			$mid2Manager->sub_menus[] = $stock1Manager;//出库管理
			$mid2Manager->sub_menus[] = $stock2Manager;//仓库出库
			//款
			$mid3Manager->sub_menus[] = $finance2Manager;//收款登记
			$mid3Manager->sub_menus[] = $shop3Manager;//销售折让
			$mid3Manager->sub_menus[] = $shop4Manager;//高开折让
			//票
			$mid4Manager->sub_menus[] = $finance6Manager;//销售开票

			$shopManager->sub_menus[] = $mid1Manager;
			$shopManager->sub_menus[] = $mid2Manager;
			$shopManager->sub_menus[] = $mid3Manager;
			$shopManager->sub_menus[] = $mid4Manager;

			//库存-----------------------------------------------
			$mid1Manager = new MenuItem;
			$mid1Manager->name = "库存";
			$mid1Manager->right_name = "middle";
			$mid1Manager->url="#";

			$mid2Manager = new MenuItem;
			$mid2Manager->name = "出库";
			$mid2Manager->right_name = "middle";
			$mid2Manager->url="#";

			$mid3Manager = new MenuItem;
			$mid3Manager->name = "入库";
			$mid3Manager->right_name = "middle";
			$mid3Manager->url="#";

			$mid4Manager = new MenuItem;
			$mid4Manager->name = "托盘";
			$mid4Manager->right_name = "middle";
			$mid4Manager->url="#";

			//库存
			$mid1Manager->sub_menus[] = $stock0Manager;//库存管理
			$mid1Manager->sub_menus[] = $stock8Manager;//代销库存
			$mid1Manager->sub_menus[] = $stock10Manager;//盘盈盘亏
			$mid1Manager->sub_menus[] = $stock9Manager;//保留库存
			$mid1Manager->sub_menus[] = $stock13Manager;//库存查询
			$mid1Manager->sub_menus[] = $stock14Manager;//卡号管理
			$mid1Manager->sub_menus[] = $stock15Manager;//异常列表

			$mid2Manager->sub_menus[] = $stock1Manager;//出库管理
			$mid2Manager->sub_menus[] = $stock11Manager;//库存调拨
			$mid2Manager->sub_menus[] = $stock16Manager;//自动出库

			$mid3Manager->sub_menus[] = $stock4Manager;//入库管理
			$mid3Manager->sub_menus[] = $stock6Manager;//代销入库
			$mid3Manager->sub_menus[] = $stock7Manager;//船舱入库
			$mid3Manager->sub_menus[] = $stock5Manager;//仓库入库

			$mid4Manager->sub_menus[] = $buy3Manager;//托盘管理
			$mid4Manager->sub_menus[] = $buy4Manager;//托盘赎回

			$stockManager->sub_menus[] = $mid1Manager;
			$stockManager->sub_menus[] = $mid2Manager;
			$stockManager->sub_menus[] = $mid3Manager;
			$stockManager->sub_menus[] = $mid4Manager;

			$mid2Manager = new MenuItem;
			$mid2Manager->name = "全部";
			$mid2Manager->right_name = "middle";
			$mid2Manager->url="#";
			//仓库
			$mid2Manager->sub_menus[] = $shop2Manager;//配送单
			$mid2Manager->sub_menus[] = $stock3Manager;//入库计划
			$mid2Manager->sub_menus[] = $stock5Manager;//仓库入库
			$mid2Manager->sub_menus[] = $stock2Manager;//仓库出库
			$mid2Manager->sub_menus[] = $whouse1Manager;//接口异常
// 			$whouseManager->sub_menus[] = $mid2Manager;

			//财务-----------------------------------------------
			$mid1Manager = new MenuItem;
			$mid1Manager->name = "款";
			$mid1Manager->right_name = "middle";
			$mid1Manager->url="#";

			$mid2Manager = new MenuItem;
			$mid2Manager->name = "票";
			$mid2Manager->right_name = "middle";
			$mid2Manager->url="#";

			$mid3Manager = new MenuItem;
			$mid3Manager->name = "其他";
			$mid3Manager->right_name = "middle";
			$mid3Manager->url="#";
			//款
			$mid1Manager->sub_menus[] = $finance1Manager;//付款登记
			$mid1Manager->sub_menus[] = $finance2Manager;//收款登记
//			$mid1Manager->sub_menus[] = $finance3Manager;//其他收入
			$mid1Manager->sub_menus[] = $finance4Manager;//费用报支
			$mid1Manager->sub_menus[] = $finance10Manager;//销售提成
			$mid1Manager->sub_menus[] = $finance12Manager;//板卷付款
			$mid1Manager->sub_menus[] = $finance13Manager;//板卷收款
			//票
			$mid2Manager->sub_menus[] = $finance6Manager;//销售开票
			$mid2Manager->sub_menus[] = $finance5Manager;//采购销票
			//其他
			$mid3Manager->sub_menus[] = $finance7Manager;//短期借贷
			$mid3Manager->sub_menus[] = $finance8Manager;//银行互转
			$mid3Manager->sub_menus[] = $finance9Manager;//凭证列表
			$mid3Manager->sub_menus[] = $finance11Manager;//已忽略凭证

			$financeManager->sub_menus[] = $mid1Manager;
			$financeManager->sub_menus[] = $mid2Manager;
			$financeManager->sub_menus[] = $mid3Manager;

			//价格管理------------------------------------------------
			$mid5Manager = new MenuItem;
			$mid5Manager->name = "销售";
			$mid5Manager->right_name = "middle";
			$mid5Manager->url="#";

			$mid6Manager = new MenuItem;
			$mid6Manager->name = "采购";
			$mid6Manager->right_name = "middle";
			$mid6Manager->url="#";

			$mid5Manager->sub_menus[] = $price1Manager;//当日指导价
			$mid5Manager->sub_menus[] = $price2Manager;//指导价管理
			$mid5Manager->sub_menus[] = $price3Manager;//历史指导价
			$mid5Manager->sub_menus[] = $price8Manager;//专区管理

// 			$mid6Manager->sub_menus[] = $price4Manager;//当日采购价
			$mid6Manager->sub_menus[] = $price5Manager;//采购价管理
			$mid6Manager->sub_menus[] = $price6Manager;//历史采购价
			$mid6Manager->sub_menus[] = $price7Manager;//基价差管理

			$priceManager->sub_menus[] = $mid5Manager;
			$priceManager->sub_menus[] = $mid6Manager;

			//报表管理主菜单----------------------------------------------
			$mid1Manager = new MenuItem;
			$mid1Manager->name = "管理";
			$mid1Manager->right_name = "middle";
			$mid1Manager->url="#";

			$mid2Manager = new MenuItem;
			$mid2Manager->name = "采/库";
			$mid2Manager->right_name = "middle";
			$mid2Manager->url="#";

			$mid3Manager = new MenuItem;
			$mid3Manager->name = "销售";
			$mid3Manager->right_name = "middle";
			$mid3Manager->url="#";

			$mid4Manager = new MenuItem;
			$mid4Manager->name = "款/票";
			$mid4Manager->right_name = "middle";
			$mid4Manager->url="#";
			//管理
			$mid1Manager->sub_menus[] = $from1Manager;//利润统计
			$mid1Manager->sub_menus[] = $from2Manager;//采销利润明细
			$mid1Manager->sub_menus[] = $from3Manager;//库存预估利润明细

			//采购/库存
			$mid2Manager->sub_menus[] = $from17Manager;//合同报表
			$mid2Manager->sub_menus[] = $from8Manager;//采购汇总
			$mid2Manager->sub_menus[] = $from9Manager;//采购明细
			$mid2Manager->sub_menus[] = $from10Manager;//库存汇总
			$mid2Manager->sub_menus[] = $from12Manager;//库存流水
// 			$mid2Manager->sub_menus[] = $from11Manager;//代销库存统计
 			$mid2Manager->sub_menus[] = $from18Manager;//库存锁定
			$mid2Manager->sub_menus[] = $from5Manager;//托盘统计
			//销售
			$mid3Manager->sub_menus[] = $from6Manager;//销售汇总
			$mid3Manager->sub_menus[] = $from7Manager;//销售明细
			$mid3Manager->sub_menus[] = $from23Manager;//销售排行
			$mid3Manager->sub_menus[] = $from28Manager;//销售排行
			//款/票
			$mid4Manager->sub_menus[] = $from13Manager;//往来汇总
			$mid4Manager->sub_menus[] = $from27Manager;//往来汇总
			$mid4Manager->sub_menus[] = $from26Manager;//往来统计优化
			$mid4Manager->sub_menus[] = $from14Manager;//往来明细
			$mid4Manager->sub_menus[] = $from15Manager;//资金账户
			$mid4Manager->sub_menus[] = $from19Manager;//资金明细
			$mid4Manager->sub_menus[] = $from16Manager;//开票汇总
			$mid4Manager->sub_menus[] = $from20Manager;//销票汇总
			$mid4Manager->sub_menus[] = $from21Manager;//托盘报表
			$mid4Manager->sub_menus[] = $from22Manager;//托盘统计
			$mid4Manager->sub_menus[] = $from24Manager;//采购汇总
			$mid4Manager->sub_menus[] = $from25Manager;//销售汇总



			$fromManager->sub_menus[] = $mid1Manager;
			$fromManager->sub_menus[] = $mid2Manager;
			$fromManager->sub_menus[] = $mid3Manager;
			$fromManager->sub_menus[] = $mid4Manager;

		//设置---------------------------------------------
			$mid5Manager = new MenuItem;
			$mid5Manager->name = "公司";
			$mid5Manager->right_name = "middle";
			$mid5Manager->url="#";
			$mid6Manager = new MenuItem;
			$mid6Manager->name = "产品";
			$mid6Manager->right_name = "middle";
			$mid6Manager->url="#";
			$mid5Manager->sub_menus[] = $titleManager;//公司管理
			$mid5Manager->sub_menus[] = $set9Manager;//公司账户
			$mid5Manager->sub_menus[] = $set1Manager;//结算单位
			$mid5Manager->sub_menus[] = $set10Manager;//结算单位账户
			$mid5Manager->sub_menus[] = $set8Manager;//业务组
			$mid5Manager->sub_menus[] = $set11Manager;//联系人
			$mid5Manager->sub_menus[] = $set7Manager;//仓库管理
			$mid5Manager->sub_menus[] = $set12Manager;//区域管理

			$mid6Manager->sub_menus[] = $set2Manager;//产地管理
			$mid6Manager->sub_menus[] = $set3Manager;//品名管理
			$mid6Manager->sub_menus[] = $set4Manager;//材质管理
			$mid6Manager->sub_menus[] = $set5Manager;//规格管理
			$mid6Manager->sub_menus[] = $set6Manager;//件重管理

			$setManager->sub_menus[] = $mid5Manager;
			$setManager->sub_menus[] = $mid6Manager;
		//系统管理-----------------------------------------------
			$mid5Manager = new MenuItem;
			$mid5Manager->name = "全部";
			$mid5Manager->right_name = "middle";
			$mid5Manager->url="#";

			$mid5Manager->sub_menus[] = $operationManager;//操作管理
			$mid5Manager->sub_menus[] = $taskManager;//任务管理
			$mid5Manager->sub_menus[] = $roleManager;//角色管理
			$mid5Manager->sub_menus[] = $system1Manager;//用户管理
			$mid5Manager->sub_menus[] = $system2Manager;//操作日志
			$mid5Manager->sub_menus[] = $system3Manager;//数据日志
			$mid5Manager->sub_menus[] = $system4Manager; //参数配置
			$mid5Manager->sub_menus[] = $system5Manager; //修改密码
			$mid5Manager->sub_menus[] = $system6Manager; //微信客户

			$systemManager->sub_menus[] = $mid5Manager;

		return $g_menu;

	}
}
