<?php
class FrmSalesReturnController extends Controller
{
	
	/*
	 * 列表
	 */
	public function actionIndex()
	{
		$search=$_REQUEST['search'];
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		
		list($tableHeader,$tableData,$pages)=FrmSalesReturn::getFormBillList($search);
		$view='index';
		$param=array(
				'search'=>$search,
				'pages'=>$pages,
				'coms'=>$coms,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
		);
		$this->render($view,$param);
	}
	
	
	/*
	 * 创建
	 */
	public  function actionCreate()
	{
		
	}
	
	/*
	 * 编辑
	 * 
	 */
	public function actionUpdate()
	{
		
	}
}
?>