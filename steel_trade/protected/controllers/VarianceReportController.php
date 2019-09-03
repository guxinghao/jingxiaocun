<?php

class VarianceReportController extends AdminBaseController
{
	public $layout = 'admin';

	public function actionIndex()
	{
		$this->pageTitle = "差异统计报表";
		$tableHeader = array(
			array('name' => "", 'class' => "", 'width' => "30px"),
			array('name' => "单号", 'class' => "", 'width' => "110px"),
			array('name' => "开单日期", 'class' => "", 'width' => "100px"),
			array('name' => "销售公司", 'class' => "flex-col", 'width' => "110px"),
			array('name' => "客户", 'class' => "flex-col", 'width' => "110px"), 
			array('name' => "产地/品名/材质/规格/长度", 'class' => "flex-col", 'width' => "260px"), 
			array('name' => "开单件数", 'class' => "flex-col text-right", 'width' => "120px"),
			array('name' => "开单重量", 'class' => "flex-col text-right", 'width' => "120px"),
			array('name' => "审单件数", 'class' => "flex-col text-right", 'width' => "120px"),
			array('name' => "审单重量", 'class' => "flex-col text-right", 'width' => "120px"),
			array('name' => "相差件数", 'class' => "flex-col text-right", 'width' => "120px"),
			array('name' => "相差重量", 'class' => "flex-col text-right", 'width' => "120px"),
			array('name' => "类型", 'class' => "flex-col", 'width' => "110px"),
			array('name' => "业务员", 'class' => "flex-col", 'width' => "110px"),
			array('name' => "业务组", 'class' => "flex-col", 'width' => "110px"),
			array('name' => "备注", 'class' => "flex-col", 'width' => "240px"),
		);

		$search = isset($_POST['search']) ? $_POST['search'] : array();
		list($tableData, $pages) = VarianceReport::getIndexList($search);

		$title_array = DictTitle::getComs('json'); //公司
		$customer_array = DictCompany::getVendorList('json', 'is_customer'); //客户
		$user_array = User::getUserList(); //业务员

		$brand_array = DictGoodsProperty::getProList('brand',"json"); //产地
		$product_array = DictGoodsProperty::getProList('product'); //品名
		$texture_array = DictGoodsProperty::getProList('texture'); //材质
		$rank_array = DictGoodsProperty::getProList('rank'); //规格

		$this->render('index', array(
			'search' => $search, 
			'tableHeader' => $tableHeader,
			'tableData' => $tableData, 
			'pages' => $pages, 
			'title_array' => $title_array, 
			'customer_array' => $customer_array, 
			'user_array' => $user_array, 
			'brand_array' => $brand_array, 
			'product_array' => $product_array,
			'texture_array' => $texture_array,
			'rank_array' => $rank_array,
		));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}