<?php

class DictGoodsController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "件重管理";
		list($model, $pages, $items) = DictGoods::getIndexList();
		
		$products = DictGoodsProperty::getProList("product","","");
		$textures = DictGoodsProperty::getProList("texture","","");
		$ranks = DictGoodsProperty::getProList("rank","","");
		$brands = DictGoodsProperty::getProList("brand","","");
		$this->render('index', array(
				'model' => $model, 
				'items' => $items, 
				'pages' => $pages, 
				'products' => $products,
				'textures' => $textures,
				'ranks' => $ranks,
				'brands' => $brands
		));
	}
	
	
	/*
	 * 获取商品当天的价格
	 * 传入各种std,
	 */
	public function actionGetGoodsPrice()
	{
		$result=DictGoods::getGoodPrice($_REQUEST);
		echo $result;
	}
	
	
	/*
	 * 获得商品id
	 */
	public function actionGetGoodId()
	{
		$result=DictGoods::getGood($_REQUEST);
		echo  $result;
	}
	
	
}