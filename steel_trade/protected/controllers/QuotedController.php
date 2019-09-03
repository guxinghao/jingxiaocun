<?php

class QuotedController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "件重管理";
		$model = new Quoted();
		
		$cri = new CDbCriteria();
		$cri->select = "t.*,p.name as product_name,b.name as brand_name,tt.name as texture_name,r.name as rank_name";
		$cri->join = "left join dict_goods_property p on p.std=t.product_std
					left join dict_goods_property b on b.std=t.brand_std
					left join dict_goods_property tt on tt.std=t.texture_std
					left join dict_goods_property r on r.std=t.rank_std
		";
		$search =  new Quoted();
		if($_POST['Quoted']){
			$search->attributes = $_POST['Quoted'];
			$search->rank_id = $_POST['Quoted']['rank_id'];
			$search->texture_id = $_POST['Quoted']['texture_id'];
			$search_pro = new DictGoodsProperty();
			if($search->name){
				$cri->addCondition("name like '%{$search->name}%' or short_name like '%{$search->name}%'");
			}
			if($search->product_id){
				$search->product_std = $search_pro->getStd($search->product_id);
				$search->product_name = $search_pro->getProName($search->product_id);
				$cri->addCondition("t.product_std = '{$search->product_std}'");
			}
			if($search->texture_id){
				$search->texture_std = $search_pro->getStd($search->texture_id);
				$search->texture_name = $search_pro->getProName($search->texture_id);
				$cri->addCondition("t.texture_std = '{$search->texture_std}'");
			}
			if($search->rank_id){
//	此处要处理				
//				$search->rank_std = $search_pro->getStd($search->rank_id);
//				$search->rank_name = $search_pro->getProName($search->rank_id);
//				$cri->addCondition("t.rank_std = '{$search->rank_std}'");
			}
			if($search->brand_id){
				$search->brand_std = $search_pro->getStd($search->brand_id);
				$search->brand_name = $search_pro->getProName($search->brand_id);
				$cri->addCondition("t.brand_std = '{$search->brand_std}'");
			}
		}
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['d_goodsd']? intval($_COOKIE['d_goods']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		
		$products = DictGoodsProperty::getProList("product","","");
		$textures = DictGoodsProperty::getProList("texture","","");
		$ranks = DictGoodsProperty::getProList("rank","","");
		$brands = DictGoodsProperty::getProList("brand","","");
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
				'products'=>$products,
				'textures'=>$textures,
				'ranks'=>$ranks,
				'brands'=>$brands
		));
	}
	
}