<?php

class DictGoodsPropertyController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "产品属性";
		list($model,$search,$pages,$items) = DictGoodsProperty::getIndexList();
		
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
		));
	}
	
	
	
	/*
	 * 选择产地，品名,材质，规格级联变换
	 */
	public function actionPropertySelect()
	{		
		$type=$_REQUEST['type'];
		switch ($type)
		{
			case 'brand':
				$data=DictGoodsProperty::brandSet($_REQUEST);
				break;
			case 'product':
				$data=DictGoodsProperty::productSet($_REQUEST);
				break;
			case 'texture':
				$data=DictGoodsProperty::textureSet($_REQUEST);
				break;
			case 'rank':
				$data=DictGoodsProperty::rankSet($_REQUEST);
				break;					
		}
		echo  $data;
		
	}
	
	//获取属性名称
	public function actionGetProName()
	{
		$id=$_REQUEST['id'];
		$return=DictGoodsProperty::getProName($id);
		echo $return;
	}
	
	
}