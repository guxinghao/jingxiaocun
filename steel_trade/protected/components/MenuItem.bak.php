<?php
class MenuItembak{
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
		$allMenus = MenuItem.bak::getGlobalMenu();
		
		$_result = Array();
		foreach ($allMenus as $_menu) {
// 			if ($_menu->right_name != "" && ! $_menu->getAuth($_menu->right_name)){
// 				continue;
// 			}
			$_menu->getValideSubMenus();
			if ($_menu->sub_menus) {
				$_result[] = $_menu;
			} 
		}
		return $_result;
	}

	/**
	 * 获取当前菜单下，有权限的子菜单
	 * @return [type] [description]
	 */
	public function getValideSubMenus()
	{
		$auth=Yii::app()->authManager;
		$_result = array();
		foreach ($this->sub_menus as $_key=>$_menu)
		{
			if (intval($_key)){//只有一级菜单
				if ($_menu->right_name != "" && !$this->getAuth($_menu->right_name)){
					continue;
				}
				$_result[] = $_menu;
			}else{//多级菜单
				$temp = array();
				foreach ($_menu as $_name => $_item){
					if ($_name !="name" && $_item->right_name != "" && !$this->getAuth($_item->right_name)){
						continue;
					}
					$_temp[$_key][$_name] = $_item;
				}
				$_result[] = $_temp;
			}
			
		}
		$this->sub_menus = $_result;
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
		$buyManager = new MenuItem.bak;
		$buyManager->name = "采购";
		$buyManager->right_name = "采购";
		$buyManager->url="#";
		$g_menu[] = $buyManager;
			//采购单
			$buy1Manager = new MenuItem.bak;
			$buy1Manager->name = "采购单";
			$buy1Manager->right_name = "采购单";
			$buy1Manager->url=Yii::app()->createUrl("purchase");
			//采购合同
			$buy2Manager = new MenuItem.bak;
			$buy2Manager->name = "采购合同";
			$buy2Manager->right_name = "采购合同";
			$buy2Manager->url=Yii::app()->createUrl("contract");
			//采购退货
			$buy8Manager = new MenuItem.bak;
			$buy8Manager->name = "采购退货";
			$buy8Manager->right_name = "采购退货";
			$buy8Manager->url=Yii::app()->createUrl("#");
			//托盘管理
			$buy3Manager = new MenuItem.bak;
			$buy3Manager->name = "托盘管理";
			$buy3Manager->right_name = "托盘管理";
			$buy3Manager->url = Yii::app()->createUrl("#");
			//托盘赎回
			$buy4Manager = new MenuItem.bak;
			$buy4Manager->name = "托盘赎回";
			$buy4Manager->right_name = "托盘赎回";
			$buy4Manager->url = Yii::app()->createUrl("#");
			//仓库返利
			$buy5Manager = new MenuItem.bak;
			$buy5Manager->name = "仓库返利";
			$buy5Manager->right_name = "仓库返利";
			$buy5Manager->url = Yii::app()->createUrl("billRebate/index", array('type' => "warehouse"));
			//钢厂返利
			$buy6Manager = new MenuItem.bak;
			$buy6Manager->name = "钢厂返利";
			$buy6Manager->right_name = "钢厂返利";
			$buy6Manager->url = Yii::app()->createUrl("billRebate/index", array('type' => "supply"));
			//仓储费用
			$buy7Manager = new MenuItem.bak;
			$buy7Manager->name = "仓储费用";
			$buy7Manager->right_name = "仓储费用";
			$buy7Manager->url = Yii::app()->createUrl("billRebate/index", array('type' => "cost"));
		//销售管理主菜单
		$shopManager = new MenuItem.bak;
		$shopManager->name = "销售";
		$shopManager->right_name = "销售";
		$shopManager->url="#";
		$g_menu[] = $shopManager;
			//销售单
			$shop1Manager = new MenuItem.bak;
			$shop1Manager->name = "销售单";
			$shop1Manager->right_name = "销售单";
			$shop1Manager->url=Yii::app()->createUrl("frmSales");
			//配送单
			$shop2Manager = new MenuItem.bak();
			$shop2Manager->name = "配送单";
			$shop2Manager->right_name = "配送单";
			$shop2Manager->url=Yii::app()->createUrl("frmSend/index",array("type"=>"menu"));
			//销售折让
			$shop3Manager = new MenuItem.bak();
			$shop3Manager->name = "销售折让";
			$shop3Manager->right_name = "销售折让";
			$shop3Manager->url=Yii::app()->createUrl("rebate/index", array('type' => "sale"));
			//高开折让
			$shop4Manager = new MenuItem.bak();
			$shop4Manager->name = "高开折让";
			$shop4Manager->right_name = "高开折让";
			$shop4Manager->url=Yii::app()->createUrl("rebate/index", array('type' => "high"));
			//销售退货
			$shop5Manager = new MenuItem.bak();
			$shop5Manager->name = "销售退货";
			$shop5Manager->right_name = "销售退货";
			$shop5Manager->url=Yii::app()->createUrl("#");
		//库存管理主菜单
		$stockManager = new MenuItem.bak;
		$stockManager->name = "库存";
		$stockManager->right_name = "库存";
		$stockManager->url="#";
		$g_menu[] = $stockManager;
			//库存查看
			$stock0Manager = new MenuItem.bak;
			$stock0Manager->name = "库存管理";
			$stock0Manager->right_name = "库存管理";
			$stock0Manager->url=Yii::app()->createUrl("storage/index");
			//代销库存
			$stock8Manager = new MenuItem.bak;
			$stock8Manager->name = "代销库存";
			$stock8Manager->right_name = "代销库存管理";
			$stock8Manager->url=Yii::app()->createUrl("storage/dx");
			//出库管理
			$stock1Manager = new MenuItem.bak;
			$stock1Manager->name = "出库管理";
			$stock1Manager->right_name = "出库管理";
			$stock1Manager->url=Yii::app()->createUrl("frmOutput/index",array("type"=>"menu"));
			//仓库出库
			$stock2Manager = new MenuItem.bak;
			$stock2Manager->name = "仓库出库";
			$stock2Manager->right_name = "仓库出库";
			$stock2Manager->url=Yii::app()->createUrl("warehouseOutput");
			//入库计划
			$stock3Manager = new MenuItem.bak;
			$stock3Manager->name = "入库计划";
			$stock3Manager->right_name = "入库计划";
			$stock3Manager->url=Yii::app()->createUrl("inputPlan/index");
			//入库管理
			$stock4Manager = new MenuItem.bak;
			$stock4Manager->name = "入库管理";
			$stock4Manager->right_name = "入库管理";
			$stock4Manager->url=Yii::app()->createUrl('input');
			//仓库入库
			$stock5Manager = new MenuItem.bak;
			$stock5Manager->name = "仓库入库";
			$stock5Manager->right_name = "仓库入库";
			$stock5Manager->url=Yii::app()->createUrl("input/pushedList");
			//代销入库
			$stock6Manager = new MenuItem.bak;
			$stock6Manager->name = "代销入库";
			$stock6Manager->right_name = "代销入库";
			$stock6Manager->url=Yii::app()->createUrl("input/index",array('type'=>"dxrk"));
			$stock7Manager = new MenuItem.bak;
			$stock7Manager->name = "船舱入库";
			$stock7Manager->right_name = "船舱入库";
			$stock7Manager->url=Yii::app()->createUrl("inputCcrk/index",array('input_type'=>'ccrk'));
// 		//仓库主菜单
// 		$whouseManager = new MenuItem;
// 		$whouseManager->name = "仓库";
// 		$whouseManager->right_name = "仓库";
// 		$whouseManager->url="#";
// 		$g_menu[] = $whouseManager;
			//接口异常
			$whouse1Manager = new MenuItem.bak;
			$whouse1Manager->name = "接口异常";
			$whouse1Manager->right_name = "接口异常";
			$whouse1Manager->url = Yii::app()->createUrl("#");
		//财务管理主菜单
		$financeManager = new MenuItem.bak;
		$financeManager->name = "财务";
		$financeManager->right_name = "财务";
		$financeManager->url="#";
		$g_menu[] = $financeManager;
			//付款
			$finance1Manager = new MenuItem.bak;
			$finance1Manager->name = "付款";
			$finance1Manager->right_name = "付款";
			$finance1Manager->url = Yii::app()->createUrl("formBill/index", array('type' => "FKDJ"));
			//收款
			$finance2Manager = new MenuItem.bak;
			$finance2Manager->name = "收款";
			$finance2Manager->right_name = "收款";
			$finance2Manager->url = Yii::app()->createUrl("formBill/index", array('type' => "SKDJ"));
			//其他收入
			$finance3Manager = new MenuItem.bak;
			$finance3Manager->name = "其他收入";
			$finance3Manager->right_name = "其他收入";
			$finance3Manager->url = Yii::app()->createUrl("#");
			//费用报支
			$finance4Manager = new MenuItem.bak;
			$finance4Manager->name = "费用报支";
			$finance4Manager->right_name = "费用报支";
			$finance4Manager->url = Yii::app()->createUrl("#");
			//采购销票
			$finance5Manager = new MenuItem.bak;
			$finance5Manager->name = "采购销票";
			$finance5Manager->right_name = "采购销票";
			$finance5Manager->url = Yii::app()->createUrl("purchaseInvoice/index");
			//销售开票
			$finance6Manager = new MenuItem.bak;
			$finance6Manager->name = "销售开票";
			$finance6Manager->right_name = "销售开票";
			$finance6Manager->url = Yii::app()->createUrl("salesInvoice/index");
			//短期借贷
			$finance7Manager = new MenuItem.bak;
			$finance7Manager->name = "短期借贷";
			$finance7Manager->right_name = "短期借贷";
			$finance7Manager->url = Yii::app()->createUrl("#");
			//银行互转
			$finance8Manager = new MenuItem.bak;
			$finance8Manager->name = "银行互转";
			$finance8Manager->right_name = "银行互转";
			$finance8Manager->url = Yii::app()->createUrl("#");
			//销售提成
			$finance9Manager = new MenuItem.bak;
			$finance9Manager->name = "销售提成";
			$finance9Manager->right_name = "销售提成";
			$finance9Manager->url = Yii::app()->createUrl("#");
		//价格管理
		$priceManager = new MenuItem.bak;
		$priceManager->name = "价格";
		$priceManager->right_name = "价格";
		$priceManager->url="#";
		$g_menu[] = $priceManager;
			//当日指导价
			$price1Manager = new MenuItem.bak;
			$price1Manager->name = "当日指导价";
			$price1Manager->right_name = "当日指导价";
			$price1Manager->url = Yii::app()->createUrl("quotedDetail/index",array("date_type"=>"yes",'type'=>'guidance'));
			//指导价管理
			$price2Manager = new MenuItem.bak;
			$price2Manager->name = "指导价管理";
			$price2Manager->right_name = "指导价管理";
			$price2Manager->url = Yii::app()->createUrl("quotedDetail/edit",array('type'=>'guidance'));
			//历史指导价
			$price3Manager = new MenuItem.bak;
			$price3Manager->name = "历史指导价";
			$price3Manager->right_name = "历史指导价";
			$price3Manager->url = Yii::app()->createUrl("quotedDetail/index");
			//当日采购价
			$price4Manager = new MenuItem.bak;
			$price4Manager->name = "当日采购价";
			$price4Manager->right_name = "当日采购价";
			$price4Manager->url = Yii::app()->createUrl("#");
			//采购价管理
			$price5Manager = new MenuItem.bak;
			$price5Manager->name = "采购价管理";
			$price5Manager->right_name = "采购价管理";
			$price5Manager->url = Yii::app()->createUrl("#");
			//历史采购价
			$price6Manager = new MenuItem.bak;
			$price6Manager->name = "历史采购价";
			$price6Manager->right_name = "历史采购价";
			$price6Manager->url = Yii::app()->createUrl("#");
			//基价差管理
			$price7Manager = new MenuItem.bak;
			$price7Manager->name = "基价差管理";
			$price7Manager->right_name = "基价差管理";
			$price7Manager->url = Yii::app()->createUrl("#");
		//报表管理主菜单
		$fromManager = new MenuItem.bak;
		$fromManager->name = "报表";
		$fromManager->right_name = "报表";
		$fromManager->url="#";
		$g_menu[] = $fromManager;
			//利润统计
			$from1Manager = new MenuItem.bak;
			$from1Manager->name = "利润统计";
			$from1Manager->right_name = "利润统计";
			$from1Manager->url=Yii::app()->createUrl("#");
			//采销利润明细
			$from2Manager = new MenuItem.bak;
			$from2Manager->name = "利润明细";
			$from2Manager->right_name = "利润明细";
			$from2Manager->url=Yii::app()->createUrl("#");
			//库存预估利润明细
			$from3Manager = new MenuItem.bak;
			$from3Manager->name = "库存利润";
			$from3Manager->right_name = "库存利润";
			$from3Manager->url=Yii::app()->createUrl("#");
			//差异统计
			$from4Manager = new MenuItem.bak;
			$from4Manager->name = "差异统计";
			$from4Manager->right_name = "差异统计";
			$from4Manager->url=Yii::app()->createUrl("#");
			//托盘统计
			$from5Manager = new MenuItem.bak;
			$from5Manager->name = "托盘统计";
			$from5Manager->right_name = "托盘统计";
			$from5Manager->url=Yii::app()->createUrl("#");
			//销售汇总
			$from6Manager = new MenuItem.bak;
			$from6Manager->name = "销售汇总";
			$from6Manager->right_name = "销售汇总";
			$from6Manager->url=Yii::app()->createUrl("#");
			//销售明细
			$from7Manager = new MenuItem.bak;
			$from7Manager->name = "销售明细";
			$from7Manager->right_name = "销售明细";
			$from7Manager->url=Yii::app()->createUrl("#");
			//采购汇总
			$from8Manager = new MenuItem.bak;
			$from8Manager->name = "采购汇总";
			$from8Manager->right_name = "采购汇总";
			$from8Manager->url=Yii::app()->createUrl("#");
			//采购明细
			$from9Manager = new MenuItem.bak;
			$from9Manager->name = "采购明细";
			$from9Manager->right_name = "采购明细";
			$from9Manager->url=Yii::app()->createUrl("#");
			//库存汇总
			$from10Manager = new MenuItem.bak;
			$from10Manager->name = "库存汇总";
			$from10Manager->right_name = "库存汇总";
			$from10Manager->url=Yii::app()->createUrl("storage/total");
			//代销库存统计
			$from11Manager = new MenuItem.bak;
			$from11Manager->name = "代销库存";
			$from11Manager->right_name = "代销库存";
			$from11Manager->url=Yii::app()->createUrl("#");
			//库存明细
			$from12Manager = new MenuItem.bak;
			$from12Manager->name = "库存明细";
			$from12Manager->right_name = "库存明细";
			$from12Manager->url=Yii::app()->createUrl("storage/index");
			//往来汇总
			$from13Manager = new MenuItem.bak;
			$from13Manager->name = "往来汇总";
			$from13Manager->right_name = "往来汇总";
			$from13Manager->url=Yii::app()->createUrl("turnover/total");
			//往来明细
			$from14Manager = new MenuItem.bak;
			$from14Manager->name = "往来明细";
			$from14Manager->right_name = "往来明细";
			$from14Manager->url=Yii::app()->createUrl("turnover/index");
			//资金账户
			$from15Manager = new MenuItem.bak;
			$from15Manager->name = "资金账户";
			$from15Manager->right_name = "资金账户";
			$from15Manager->url=Yii::app()->createUrl("#");
			//发票汇总
			$from16Manager = new MenuItem.bak;
			$from16Manager->name = "发票汇总";
			$from16Manager->right_name = "发票汇总";
			$from16Manager->url=Yii::app()->createUrl("#");
		//设置
		$setManager = new MenuItem.bak;
		$setManager->name = "设置";
		$setManager->right_name = "设置";
		$setManager->url="#";
		$g_menu[] = $setManager;
			//公司管理
			$roleManager = new MenuItem.bak;
			$roleManager->name = "公司管理";
			$roleManager->right_name = "公司管理";
			$roleManager->url=Yii::app()->createUrl("dictTitle");
			//结算单位
			$set1Manager = new MenuItem.bak;
			$set1Manager->name = "结算单位";
			$set1Manager->right_name = "结算单位";
			$set1Manager->url=Yii::app()->createUrl("dictCompany");
			//产地管理
			$set2Manager = new MenuItem.bak;
			$set2Manager->name = "产地管理";
			$set2Manager->right_name = "产地管理";
			$set2Manager->url=Yii::app()->createUrl("dictGoodsProperty/index",array("property_type"=>"brand"));
			//品名管理
			$set3Manager = new MenuItem.bak;
			$set3Manager->name = "品名管理";
			$set3Manager->right_name = "品名管理";
			$set3Manager->url=Yii::app()->createUrl("dictGoodsProperty/index",array("property_type"=>"product"));
			//材质管理
			$set4Manager = new MenuItem.bak;
			$set4Manager->name = "材质管理";
			$set4Manager->right_name = "材质管理";
			$set4Manager->url=Yii::app()->createUrl("dictGoodsProperty/index",array("property_type"=>"texture"));
			//规格管理
			$set5Manager = new MenuItem.bak;
			$set5Manager->name = "规格管理";
			$set5Manager->right_name = "规格管理";
			$set5Manager->url=Yii::app()->createUrl("dictGoodsProperty/index",array("property_type"=>"rank"));
			//件重管理
			$set6Manager = new MenuItem.bak;
			$set6Manager->name = "件重管理";
			$set6Manager->right_name = "件重管理";
			$set6Manager->url=Yii::app()->createUrl("dictGoods");
			//仓库管理
			$set7Manager = new MenuItem.bak;
			$set7Manager->name = "仓库管理";
			$set7Manager->right_name = "仓库管理";
			$set7Manager->url=Yii::app()->createUrl("warehouse");
			//业务组
			$set8Manager = new MenuItem.bak;
			$set8Manager->name = "业务组";
			$set8Manager->right_name = "业务组";
			$set8Manager->url=Yii::app()->createUrl("team");
			//银行管理
			$set9Manager = new MenuItem.bak;
			$set9Manager->name = "公司账户";
			$set9Manager->right_name = "公司银行管理";
			$set9Manager->url=Yii::app()->createUrl("dictBankInfo");
			//银行账号管理
			$set10Manager = new MenuItem.bak;
			$set10Manager->name = "结算账户";
			$set10Manager->right_name = "结算单位银行管理";
			$set10Manager->url=Yii::app()->createUrl("bankInfo");
		//系统管理
		$systemManager = new MenuItem.bak;
		$systemManager->name = "系统";
		$systemManager->right_name = "系统";
		$systemManager->url="#";
		$g_menu[] = $systemManager;
			//操作管理
			$operationManager = new MenuItem.bak;
			$operationManager->name = "操作管理";
			$operationManager->right_name = "操作管理";
			$operationManager->url=Yii::app()->createUrl("operation");
			//任务管理
			$taskManager = new MenuItem.bak;
			$taskManager->name = "任务管理";
			$taskManager->right_name = "任务管理";
			$taskManager->url=Yii::app()->createUrl("task");
			//角色管理
			$roleManager = new MenuItem.bak;
			$roleManager->name = "角色管理";
			$roleManager->right_name = "角色管理";
			$roleManager->url=Yii::app()->createUrl("role");
			//用户管理
			$system1Manager = new MenuItem.bak;
			$system1Manager->name = "用户管理";
			$system1Manager->right_name = "用户管理";
			$system1Manager->url=Yii::app()->createUrl("user");
			//操作日志
			$system2Manager = new MenuItem.bak;
			$system2Manager->name = "操作日志";
			$system2Manager->right_name = "操作日志";
			$system2Manager->url=Yii::app()->createUrl("log");
			//数据日志
			$system3Manager = new MenuItem.bak;
			$system3Manager->name = "数据日志";
			$system3Manager->right_name = "数据日志";
			$system3Manager->url=Yii::app()->createUrl("logDetail");
			//采购单-----------------------------------------------------------------
			//单
			$buyManager->sub_menus['form']['name'] = "单";
			$buyManager->sub_menus['form'][] = $buy1Manager;//采购单
			$buyManager->sub_menus['form'][] = $buy2Manager;//采购合同
			$buyManager->sub_menus['form'][] = $buy8Manager;//采购退货
			//货
			$buyManager->sub_menus['goods']['name'] = '货';
			$buyManager->sub_menus['goods'][] = $stock3Manager;//入库计划
			$buyManager->sub_menus['goods'][] = $stock4Manager;//入库管理
			$buyManager->sub_menus['goods'][] = $stock7Manager;//船舱入库
			$buyManager->sub_menus['goods'][] = $stock5Manager;//仓库入库
			//款
			$buyManager->sub_menus['cash']['name'] = '款';
			$buyManager->sub_menus['cash'][] = $finance1Manager;//付款登记
			$buyManager->sub_menus['cash'][] = $buy6Manager;//钢厂返利
			$buyManager->sub_menus['cash'][] = $buy5Manager;//仓库返利
			$buyManager->sub_menus['cash'][] = $buy7Manager;//仓储费用
			//票
			$buyManager->sub_menus['invoice']['name'] = '票';
			$buyManager->sub_menus['invoice'][] = $finance5Manager;//采购销票
			//销售-------------------------------------------
			//单
			$shopManager->sub_menus['form']['name'] = '单';
			$shopManager->sub_menus['form'][] = $shop1Manager;//销售单
			$shopManager->sub_menus['form'][] = $shop5Manager;//销售退货
			//货
			$shopManager->sub_menus['goods']['name'] = "货";
			$shopManager->sub_menus['goods'][] = $shop2Manager;//配送单
			$shopManager->sub_menus['goods'][] = $stock1Manager;//出库管理
			$shopManager->sub_menus['goods'][] = $stock2Manager;//仓库出库
			//款
			$shopManager->sub_menus['cash']['name'] = "款";
			$shopManager->sub_menus['cash'][] = $finance2Manager;//收款登记
			$shopManager->sub_menus['cash'][] = $shop3Manager;//销售折让
			$shopManager->sub_menus['cash'][] = $shop4Manager;//高开折让
			//票
			$shopManager->sub_menus['invoice'][] = "票";
			$shopManager->sub_menus['invoice'][] = $finance6Manager;//销售开票
			//库存-----------------------------------------------
			//库存
			$stockManager->sub_menus['storage']['name'] = '库存';
			$stockManager->sub_menus['storage'][] = $stock0Manager;//库存管理
			$stockManager->sub_menus['storage'][] = $stock1Manager;//出库管理
			$stockManager->sub_menus['storage'][] = $stock4Manager;//入库管理
			$stockManager->sub_menus['storage'][] = $stock8Manager;//代销库存
			$stockManager->sub_menus['storage'][] = $stock6Manager;//代销入库
			$stockManager->sub_menus['storage'][] = $stock7Manager;//船舱入库
			$stockManager->sub_menus['storage'][] = $buy3Manager;//托盘管理
			$stockManager->sub_menus['storage'][] = $buy4Manager;//托盘赎回
			//仓库
			$stockManager->sub_menus['whouse']['name'] = '仓库';
			$stockManager->sub_menus['whouse'][] = $shop2Manager;//配送单
			$stockManager->sub_menus['whouse'][] = $stock3Manager;//入库计划
			$stockManager->sub_menus['whouse'][] = $stock5Manager;//仓库入库
			$stockManager->sub_menus['whouse'][] = $stock2Manager;//仓库出库
			$stockManager->sub_menus['whouse'][] = $whouse1Manager;//接口异常
			//财务-----------------------------------------------
			//款
			$financeManager->sub_menus['cash']['name'] = '款';
			$financeManager->sub_menus['cash'][] = $finance1Manager;//付款登记
			$financeManager->sub_menus['cash'][] = $finance2Manager;//收款登记
			$financeManager->sub_menus['cash'][] = $finance9Manager;//销售提成
			$financeManager->sub_menus['cash'][] = $finance3Manager;//其他收入
			$financeManager->sub_menus['cash'][] = $finance4Manager;//费用报支
			//票
			$financeManager->sub_menus['invoice']['name'] = '款';
			$financeManager->sub_menus['invoice'][] = $finance6Manager;//销售开票
			$financeManager->sub_menus['invoice'][] = $finance5Manager;//采购销票
			//其他
			$financeManager->sub_menus['other']['name'] = '其他';
			$financeManager->sub_menus['other'][] = $finance7Manager;//短期借贷
			$financeManager->sub_menus['other'][] = $finance8Manager;//银行互转
			//价格管理------------------------------------------------
			$priceManager->sub_menus[] = $price1Manager;//当日指导价
			$priceManager->sub_menus[] = $price2Manager;//指导价管理
			$priceManager->sub_menus[] = $price3Manager;//历史指导价
			$priceManager->sub_menus[] = $price4Manager;//当日采购价
			$priceManager->sub_menus[] = $price5Manager;//采购价管理
			$priceManager->sub_menus[] = $price6Manager;//历史采购价
			$priceManager->sub_menus[] = $price7Manager;//基价差管理
			//报表管理主菜单----------------------------------------------
			//管理
			$fromManager->sub_menus['manager']['name'] = '管理';
			$fromManager->sub_menus['manager'][] = $from1Manager;//利润统计
			$fromManager->sub_menus['manager'][] = $from2Manager;//采销利润明细
			$fromManager->sub_menus['manager'][] = $from3Manager;//库存预估利润明细
			//采购/库存
			$fromManager->sub_menus['purchase']['name'] = '采购/库存';
			$fromManager->sub_menus['purchase'][] = $from8Manager;//采购汇总
			$fromManager->sub_menus['purchase'][] = $from9Manager;//采购明细
			$fromManager->sub_menus['purchase'][] = $from10Manager;//库存汇总
			$fromManager->sub_menus['purchase'][] = $from12Manager;//库存明细
			$fromManager->sub_menus['purchase'][] = $from11Manager;//代销库存统计
			//销售
			$fromManager->sub_menus['sales']['name'] = '销售';
			$fromManager->sub_menus['sales'][] = $from6Manager;//销售汇总
			$fromManager->sub_menus['sales'][] = $from7Manager;//销售明细
			//款/票
			$fromManager->sub_menus['invoice']['name'] = '款/票';
			$fromManager->sub_menus['invoice'][] = $from13Manager;//往来汇总
			$fromManager->sub_menus['invoice'][] = $from14Manager;//往来明细
			$fromManager->sub_menus['invoice'][] = $from15Manager;//资金账户
			$fromManager->sub_menus['invoice'][] = $from16Manager;//发票汇总
			$fromManager->sub_menus['invoice'][] = $from4Manager;//差异统计
			$fromManager->sub_menus['invoice'][] = $from5Manager;//托盘统计
			//设置
			$setManager->sub_menus[] = $roleManager;//公司管理
			$setManager->sub_menus[] = $set1Manager;//结算单位
			$setManager->sub_menus[] = $set2Manager;//产地管理
			$setManager->sub_menus[] = $set3Manager;//品名管理
			$setManager->sub_menus[] = $set4Manager;//材质管理
			$setManager->sub_menus[] = $set5Manager;//规格管理
			$setManager->sub_menus[] = $set6Manager;//件重管理
			$setManager->sub_menus[] = $set7Manager;//仓库管理
			$setManager->sub_menus[] = $set8Manager;//业务组
			$setManager->sub_menus[] = $set9Manager;//银行管理
			$setManager->sub_menus[] = $set10Manager;//银行账号管理
			//系统管理
			$systemManager->sub_menus[] = $operationManager;//操作管理
			$systemManager->sub_menus[] = $taskManager;//任务管理
			$systemManager->sub_menus[] = $roleManager;//角色管理
			$systemManager->sub_menus[] = $system1Manager;//用户管理
			$systemManager->sub_menus[] = $system2Manager;//操作日志
			$systemManager->sub_menus[] = $system3Manager;//数据日志
			
		return $g_menu;
	
	}
}
