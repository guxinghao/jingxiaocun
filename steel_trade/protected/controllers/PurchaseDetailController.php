<?php
/**
 * 采购明细
 * @author yy_prince
 *
 */
class PurchaseDetailController extends AdminBaseController
{
    
	/*
	 *采购明细列表
	 */	
	public function actionIndex()
    {
    	$this->pageTitle = "采购明细";
    	$vendor_array=DictCompany::getVendorList('json');
    	$coms=DictTitle::getComs("json");
    	$user_array=User::getUserList();
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
    	//获取表单列表
        $search=updateSearch($search,'search_purchasedetail');
    	list($tableHeader,$tableData,$pages) = PurchaseDetail::getIndexList($search);
  
    	
    	$this->render('index',array(
    			'tableHeader'=>$tableHeader,
    			'tableData'=>$tableData,
    			'pages'=>$pages,
    			'search'=>$search,
    			'coms'=>$coms,
    			'vendor'=>$vendor_array,
    			'brands'=>$brands_array,
    			'users'=>$user_array,
    			'products'=>$products_array,
    			'rands'=>$ranks_array,
    			'textures'=>$textures_array,
    	));

    }
}