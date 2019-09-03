<?php

class TurnoverController extends AdminBaseController
{
	public $layout='admin';

	// /**
	//  * 往来明细
	//  */
	// public function actionIndex_() 
	// {
	// 	$this->pageTitle = "往来明细";
	// 	$this->setHome = 1; //允许设为首页
	// 	list($model, $items, $totaldata, $pages) = Turnover::getIndexList_();

	// 	$title_array = DictTitle::getComs('json'); //公司
	// 	$target_array = DictCompany::getComs('json'); //结算单位
	// 	$proxy_array = DictCompany::getVendorList('json', 'is_pledge'); //托盘公司
		
	// 	$this->render('index_', array(
	// 		'model' => $model, 
	// 		'items' => $items, 
	// 		'pages' => $pages, 
	// 		'totaldata' => $totaldata,
	// 		'title_array' => $title_array, 
	// 		'target_array' => $target_array, 
	// 		'proxy_array' => $proxy_array,
	// 	));
	// }

	public function actionIndex()
	{
		$this->pageTitle = "往来明细";
		$this->setHome = 1;//允许设为首页
		list($model,$search,$pages,$items,$arr_ye, $totaldata,$qichu_y) = Turnover::getIndexList();
		
		$titles = DictTitle::getComs("json");
		$targets = DictCompany::getAllComs("json");
		$tps = DictCompany::getAllVendorList("json","is_pledge");
		$user_array = User::getUserList("array");
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
				'titles'=>$titles,
				'targets'=>$targets,
				'tps'=>$tps,
				'arr_ye'=>$arr_ye, 
				'totaldata' => $totaldata,
				'user_array' => $user_array,
				'qichu_y'=>$qichu_y,
		));
	}
	
	/**
	 * 往来汇总
	 */
	public function actionTotal() 
	{
		$this->pageTitle = "往来汇总";
		list($model, $items, $pages, $totaldata, $msg) = Turnover::getTotalList();

		$title_array = DictTitle::getComs('json'); //公司
		$target_array = DictCompany::getAllComs('json'); //结算单位
		$user_array = User::getUserList("array"); 
		$this->render('total', array(
			'model' => $model, 
			'items' => $items, 
			'pages' => $pages, 
			'totaldata' => $totaldata, 
			'title_array' => $title_array, 
			'target_array' => $target_array, 
			'user_array' => $user_array,
			'msg' => $msg 
		));
	}


	/**
	 * 采购往来汇总
	 */
	public function actionPurchaseTotal() 
	{
		$this->pageTitle = "采购往来汇总";
		list($model, $items, $pages, $totaldata, $msg) = Turnover::getPurchaseTotalList();

		$title_array = DictTitle::getComs('json'); //公司
		$target_array = DictCompany::getAllComs('json'); //结算单位
		$user_array = User::getUserList("array"); 
		$this->render('total_purchase', array(
			'model' => $model, 
			'items' => $items, 
			'pages' => $pages, 
			'totaldata' => $totaldata, 
			'title_array' => $title_array, 
			'target_array' => $target_array, 
			'user_array' => $user_array,
			'msg' => $msg 
		));
	}

	/**
	 * 销售往来汇总
	 */
	public function actionSaleTotal() 
	{
		$this->pageTitle = "销售往来汇总";
		list($model, $items, $pages, $totaldata, $msg) = Turnover::getSaleTotalList();

		$title_array = DictTitle::getComs('json'); //公司
		$target_array = DictCompany::getAllComs('json'); //结算单位
		$user_array = User::getUserList("array"); 
		$this->render('total_sale', array(
			'model' => $model, 
			'items' => $items, 
			'pages' => $pages, 
			'totaldata' => $totaldata, 
			'title_array' => $title_array, 
			'target_array' => $target_array, 
			'user_array' => $user_array,
			'msg' => $msg 
		));
	}



	/*
	*往来统计
	*/
	public function actionAnotherTotal()
	{
		$this->pageTitle = "往来统计";
		list($model, $items, $pages, $totaldata, $msg) = Turnover::totalYouWant();

		$title_array = DictTitle::getComs('json'); //公司
		$target_array = DictCompany::getAllComs('json'); //结算单位
		$user_array = User::getUserList("array"); 
		$this->render('anotherTotal', array(
			'model' => $model, 
			'items' => $items, 
			'pages' => $pages, 
			'totaldata' => $totaldata, 
			'title_array' => $title_array, 
			'target_array' => $target_array, 
			'user_array' => $user_array,
			'msg' => $msg 
		));	
	}


	/*
	*往来统计1
	*/
	public function actionAnotherTotal1()
	{
		$this->pageTitle = "往来统计";
		// var_dump($_REQUEST['Turnover']);
		list($tableHeader, $tableData, $pages, $totaldata, $msg,$model) = Turnover::totalYouWant1();

		$title_array = DictTitle::getComs('json'); //公司
		$target_array = DictCompany::getAllComs('json'); //结算单位
		$user_array = User::getUserList("array"); 
		$this->render('anotherTotal1', array(
			'tableHeader' => $tableHeader, 
			'tableData' => $tableData, 
			'pages' => $pages, 
			'totaldata' => $totaldata, 
			'title_array' => $title_array, 
			'target_array' => $target_array, 
			'user_array' => $user_array,
			'msg' => $msg ,
			'model'=>$model
		));	
	}

	/*
	 *往来汇总1
	 */
	public function actionTotalNew()
	{
		$this->pageTitle = "往来汇总";
		// var_dump($_REQUEST['Turnover']);
		list($tableHeader, $tableData, $pages, $totaldata, $msg,$model) = Turnover::totalNew();
	
		$title_array = DictTitle::getComs('json'); //公司
		$target_array = DictCompany::getAllComs('json'); //结算单位
		$user_array = User::getUserList("array");
		$this->render('totalNew', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
				'totaldata' => $totaldata,
				'title_array' => $title_array,
				'target_array' => $target_array,
				'user_array' => $user_array,
				'msg' => $msg ,
				'model'=>$model
		));
	}

	/**
	 * 明细导出
	 */
	public function actionIndexExport() 
	{
		$search = $_REQUEST['Turnover'];
		$name = "往来明细".date("Y/m/d");
		$title = array("发生日期", "公司", "结算单位", "客户", "数量", "单价", "金额小计","收付金额", "余额", "往来描述",  "往来业务类型", "往来类型", "代理付费公司", "乙单", "类别","往来状态", "负责人", "经办人", "入账人");

		$content = Turnover::getAllList($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}

	/**
	 * 汇总导出
	 */
	public function actionTotalExport() 
	{
		$search = $_REQUEST['Turnover'];
		$name = "往来汇总".date("Y/m/d");
		$title = array("公司抬头", "结算单位", "期末余额", "采购明细", "运费", "采购折让", "采购退货", "托盘采购", "托盘赎回计息", "付款登记", "销售明细", "销售折让", "销售退货","净销售重量", "收款登记", "仓库返利", "钢厂返利", "仓储费用", "高开明细", "已收款", "已付款", "期初余额");

		$content = Turnover::getAllTotalList($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}



	/**
	 * 汇总导出
	 */
	public function actionTotalExport_aa() 
	{
		$search = $_REQUEST['Turnover'];
		$name = "往来统计".date("Y/m/d");
		$title = array( "结算单位", "期末余额", "采购明细", "运费", "采购折让", "采购退货", "托盘采购", "托盘赎回计息", "付款登记", "销售明细", "销售折让", "销售退货", "收款登记", "仓库返利", "钢厂返利", "仓储费用", "高开明细", "已收款", "已付款", "期初余额");

		$content = Turnover::getAllTotalList_aa($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}

	/*
	*往来统计:优  导出
	*/
	public function actionTotalExport_you() 
	{
		$search = $_REQUEST['Turnover'];
		$name = "往来统计".date("Y/m/d");
		$title = array( "结算单位", "期末余额", "采购明细", "运费", "采购折让", "采购退货", "托盘采购", "托盘赎回计息", "付款登记", "销售明细", "销售折让", "销售退货", "收款登记", "仓库返利", "钢厂返利", "仓储费用", "高开明细", "期初余额");

		$content = Turnover::getAllTotalList_you($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}

	/*
	 *往来统计:优  导出
	 */
	public function actionTotalNewExport()
	{
		$search = $_REQUEST['Turnover'];
		$name = "往来统计".date("Y/m/d");
		$title = array("公司抬头","结算单位","客户", "期末余额", "采购明细", "运费", "采购折让", "采购退货", "托盘采购", "托盘赎回计息", "付款登记", "销售明细", "销售折让", "销售退货","净销售重量","收款登记", "仓库返利", "钢厂返利", "仓储费用", "高开明细", "期初余额");
	
		$content = Turnover::getNewAllList($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}
	
	//修复往来
	public function actionFix(){
		$tur = Turnover::model()->findAll("turnover_type='XSMX' and confirmed=1 and fee <>1 and price*amount <> -fee");
		if($tur){
			foreach($tur as $li){
					$baseform = CommonForms::model()->findByPk($li->common_forms_id);
					$str .= $baseform->form_sn."=>".$baseform->sales->comment;
					$form=new Sales($li->common_forms_id);
					$result = $form->cancelcompleteSales();
					if($result=='已开票'){
						$str.="已开票";
					}else if(!$result){
						$str.="取消完成失败";
					}
					$form=new Sales($li->common_forms_id);
					$result = $form->completeSales();
					if(!$result){
						$str.="完成失败";
					}
					$str.="<br/>";
			}	
		}
		echo $str;
	}
}