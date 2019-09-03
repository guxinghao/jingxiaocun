<?php

class QuotedDetailController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$type = $_REQUEST['type'];
		$type_name = "";
		switch ($type){
			case "guidance":$type_name="指导价";break;
			case "net":$type_name="采购价";break;
			case "spread":$type_name="基差价";break;
		}
		if($_REQUEST['date_type']=="yes"){
			$this->pageTitle = "当日$type_name - ".date("Y/m/d");
			$this->setHome = 1;//允许设为首页
		}else{
			$this->pageTitle = "历史$type_name";
		}		
		$mo=new QuotedDetail();
		list($model,$items,$search,$pages,$date_type) = $mo->getList1($ac);
		$sql='select last_update as update_time,last_update_by,user.nickname as nickname from quoted_detail q left join user on user.id=q.last_update_by 
				where type="'.$type.'" and last_update=(select max(last_update) from quoted_detail where type="'.$type.'")';
		
		$connection=Yii::app()->db;
		$res=$connection->createCommand($sql)->queryRow();
		
		$prefectures=Prefecture::getAll();
		$products = DictGoodsProperty::getProList("product","","");
		$textures = DictGoodsProperty::getProList("texture","","");
		$ranks = DictGoodsProperty::getProList("rank","","");
		$brands = DictGoodsProperty::getProList("brand","","");
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'tableData'=>$items,
				'tableHeader'=>$model,
				'products'=>$products,
				'textures'=>$textures,
				'ranks'=>$ranks,
				'brands'=>$brands,
				'date_type'=>$date_type,
				'prefectures'=>$prefectures,
				'last_update_user'=>$res['nickname'],
				'update_time'=>$res['update_time'],
		));
	}
	
	
	/**
	 * 
	 * 编辑当日指导价
	 */
	public function actionEdit()
	{	
		$type = $_REQUEST['type'];
		$type_name = "";
		switch ($type){
			case "guidance":
				$type_name="指导价";
				$this->pageTitle = "当日指导价管理 - ".date("Y/m/d");
				break;
			case "net":$type_name="采购价";
			$this->pageTitle = "当日网价管理 - ".date("Y/m/d");break;
			case "spread":$type_name="基差价";
			$this->pageTitle = "基价差管理";
			break;
		}
		// $this->pageTitle = "当日$type_name管理 - ".date("Y/m/d");
		$ac='edit';
		$mo=new QuotedDetail();
		if(!$_COOKIE['saleprice_order'])
		{
			setcookie('saleprice_order','bra_sha');
			$_COOKIE['saleprice_order']='bra_sha';
		}
		list($model,$items,$search,$pages) = $mo->getList1($ac);
		$sql='select last_update as update_time,last_update_by,user.nickname as nickname from quoted_detail q left join user on user.id=q.last_update_by
				where type="'.$type.'" and last_update=(select max(last_update) from quoted_detail where type="'.$type.'")';
		
// 		$sql='select max(last_update) as update_time,last_update_by,user.nickname as nickname from quoted_detail q left join user on user.id=q.last_update_by where type="'.$type.'"';
		$connection=Yii::app()->db;
		$res=$connection->createCommand($sql)->queryRow();
		
		$prefectures=Prefecture::getAll();
		$products = DictGoodsProperty::getProList("product","","");
		$textures = DictGoodsProperty::getProList("texture","","");
		$ranks = DictGoodsProperty::getProList("rank","","");
		$brands = DictGoodsProperty::getProList("brand","","");
		$this->render('edit',array(
				'search'=>$search,
				'pages'=> $pages,
				'tableData'=>$items,
				'tableHeader'=>$model,
				'products'=>$products,
				'textures'=>$textures,
				'ranks'=>$ranks,
				'brands'=>$brands,
				'prefectures'=>$prefectures,
				'type'=>$type,
				'last_update_user'=>$res['nickname'],
				'update_time'=>$res['update_time'],
		));
	}


	/*
	*push price content to weichat table
	*/
	public function actionPushToWeichat()
	{
		$type=$_REQUEST['type'];
		if($type=='all')
		{
			$models=QuotedDetail::model()->with('relation')->findAll('relation.price_date="'.date('Y-m-d').'"');
		}elseif($type=='select'){
			$ids=substr($_REQUEST['ids'],1);
			$ids.='0';
			$models=QuotedDetail::model()->with('relation')->findAll('t.id in ('.$ids.')');
		}		
		if($models)
		{
			$transaction=Yii::app()->db->beginTransaction();
			try{
				foreach ($models as $each) {
					$prices=QuotedWarehouseRelation::model()->findAll('quoted_id='.$each->id.' and price_date="'.date('Y-m-d').'"');
					if($each->pushed)
					{
						$wx=WxQuoted::model()->find('quoted_detail_id='.$each->id);
						if($prices)
						{
							foreach($prices as $each_price)
							{
								$wx_rel=WxQuotedRelation::model()->find('quoted_id='.$wx->id.' and area_id='.$each_price->area_id.' and price_date="'.$each_price->price_date.'"');
								if($wx_rel)
								{
									$wx_rel->price=$each_price->price;
									$wx_rel->update();
								}else{
									$wx_rel=new WxQuotedRelation();
									$wx_rel->quoted_id=$wx->id;
									$wx_rel->area_id=$each_price->area_id;
									$wx_rel->price=$each_price->price;
									$wx_rel->price_date=$each_price->price_date;
									$wx_rel->insert();
								}
							}
						}			
					}else{
						$wx=new WxQuoted();
						$wx->product_std=$each->product_std;
						$wx->texture_std=$each->texture_std;
						$wx->brand_std=$each->brand_std;
						$wx->rank_std=$each->rank_std;
						$wx->length=$each->length;
						$wx->area=$each->area;
						$wx->price=$each->price;
						$wx->created_by=$each->created_by;
						$wx->created_at=$each->created_at;
						$wx->last_update=$each->last_update;
						$wx->type=$each->type;
						$wx->price_date=$each->price_date;
						$wx->prefecture=$each->prefecture;
						$wx->send_id=currentUserId();
						$wx->quoted_detail_id=$each->id;
						$wx->insert();
						if($prices)
						{
							foreach($prices as $each_price)
							{								
								$wx_rel=new WxQuotedRelation();
								$wx_rel->quoted_id=$wx->id;
								$wx_rel->area_id=$each_price->area_id;
								$wx_rel->price=$each_price->price;
								$wx_rel->price_date=$each_price->price_date;
								$wx_rel->insert();
							}
						}	
					}
					$each->pushed=1;
					$each->update();
				}
				$transaction->commit();
			}catch(Exception $e)
			{
				// echo $e;
				echo '操作失败';
				return;
			}
			echo 1;			
		}else{
			echo '没有推送任何价格明细';
		}
	}
	
	/**
	 * 修改价格post处理页
	 */
	public function actionPost_update(){
		$ids = explode(",", $_POST['id']);
		$datas = explode(",",$_POST['data']);
		$type=$_REQUEST['type'];
		$time = $_POST['time'];
		$arr = array();
		for($i=0;$i<count($ids);$i++){
			$model = QuotedDetail::model()->findByPk(intval($ids[$i]));
			$arr[] = $model;
			if($model){
				if($model->last_update>=$time){
					echo "updated";return ;
				}
			}
		}
		if($type=='spread')
		{
			for($i=0;$i<count($ids);$i++){
				$model = QuotedDetail::model()->findByPk(intval($ids[$i]));
				if($model){
					$model->price = $datas[$i];
					$model->last_update = time();
					$model->last_update_by = Yii::app()->user->userid;
					$model->save();
				}
			}
		}else{
			foreach($datas as $each)
			{
				$data=explode('/', $each);
				if($q_id!=$data[0])
				{
					$q_id=$data[0];
					$model=QuotedDetail::model()->findByPk(intval($q_id));
					if($model)
					{
						$model->last_update = time();
						$model->last_update_by = Yii::app()->user->userid;
						$model->save();
					}
				}				
				$r=QuotedWarehouseRelation::model()->findByAttributes(array('quoted_id'=>$data[0],'area_id'=>$data[1],'price_date'=>date('Y-m-d')));
				if($r)
				{
					$r->price=$data[2];
					$r->update();
				}elseif($data[2]){
					$r=new QuotedWarehouseRelation();
					$r->quoted_id=$data[0];
					$r->area_id=$data[1];
					$r->price=$data[2];
					$r->price_date=date('Y-m-d');
					$r->insert();
				}
			}
		}
		echo 1;
		
	}
	
	/**
	 * 获取当日指导价
	 */
	public static function actionGetGuideprice()
	{
		$product = $_POST["product_std"];
		$rand = $_POST["rand_std"];
		$texture = $_POST["texture_std"];
		$brand = $_POST["brand_std"];
		$length = $_POST["length"];
		$date = date("Y-m-d");
		if($length == ''){$length = 0;}
		$model=QuotedDetail::model()->findByAttributes(array(
				'product_std'=>DictGoodsProperty::getStd($product),
				'texture_std'=>DictGoodsProperty::getStd($texture),
				'rank_std'=>DictGoodsProperty::getStd($rand),
				'brand_std'=>DictGoodsProperty::getStd($brand),
				'length'=>$length,
				'price_date'=>$date,
		));
		if($model){
			$result = number_format($model->price);
			echo $result;
		}else{
			echo "false";
		}
	}
	
	
	/*
	 * To provide a simple list of price content,for prefecture manage 
	 */
	public  function actionSimpleList($prefecture)
	{		
		$search=array();
		$type=$_REQUEST['type'];
		$self=$_REQUEST['self'];
		if($prefecture)
		{			
			if($_REQUEST['search'])
			{
				$search=$_REQUEST['search'];
			}else{
				$search['brand_id']=$_REQUEST['brand_id'];
				$search['product_id']=$_REQUEST['product_id'];
				$search['rank_id']=$_REQUEST['rank_id'];
				$search['texture_id']=$_REQUEST['texture_id'];
			}
			$search['prefecture']=$prefecture;
		}else{
			return false;
		}
		if(!$type)
		{
			$search['choosed']='choosed';
			$view='simList';			
		}else{
			$search['choosed']='unchoosed';
			$view='simList1';	
			$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
			$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
			$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
			$ranks_array=sortRank(DictGoodsProperty::getProList('rank','array','all'));//4规格		
		}
		//find all goods' id in the prefecture		
		$ids=DictGoods::getPrefectureGoods($prefecture);
		list($tableHeader,$tableData, $pages,$ids) = DictGoods::getSimList($search,$ids,$type);
		
		$this->renderPartial($view, array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
				'type'=>$type,
				'self'=>$self,
				'prefecture'=>$prefecture,
				'brands'=>$brands_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
				'search'=>$search,
				
		));
	}
	
	/**
	 * 当日详细报表页
	 */
	public function actionReport(){
		list($items,$time) = QuotedDetail::getReportList();
		$this->pageTitle = "今日报价单";
		$this->render('report',array(
			'items'=>$items,
			'time'=>$time
		));
	}
	
	/**
	 * 获取报表所用日期
	 */
	public function getDate($now){
		$notime = false;
		if(!$now){
			$now = time();
			$notime = true;
		}
		
		//年月日
		$date = date("Y.m.d",$now);
		//星期
		$week = date("N",$now); 
		$weekday = "星期";
		switch ($week){
			case 1:$weekday .= "一";break;
			case 2:$weekday .= "二";break;
			case 3:$weekday .= "三";break;
			case 4:$weekday .= "四";break;
			case 5:$weekday .= "五";break;
			case 6:$weekday .= "六";break;
			case 7:$weekday .= "日";break; 
			default:$weekday .= "?";
		}
		//时间
		$time = date("(H:i)",$now);
		if($notime){
			return $date.$weekday;
		}else{
			return $date.$weekday.$time;
		}
		
	}
	
	
	/**
	 * 自动复制每天指导价数据
	 */
	public function actionAutoHistory(){
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$now = time();
			$yesterday = date("Y-m-d",$now-24*3600);
			$this->historyByDate($yesterday);
			$transaction->commit();
		}catch (Exception $e)
		{
// 			echo $e;
			echo "操作失败";
			$transaction->rollBack();//事务回滚
			return;
		}
		@session_destroy();
	}
	
	public function historyByDate($date){
		$now = time();
		$today = date("Y-m-d",$now);
		
		// back up quoted price data
		//delete price data that belongs to the deleted quoted_detail  
		$sql="delete from quoted_warehouse_relation where quoted_id not in (select id from quoted_detail)";
		$connect=Yii::app()->db;
		$re=$connect->createCommand($sql)->execute();		
		$items=QuotedWarehouseRelation::model()->findAll("price_date = '{$date}'");		
		foreach ($items as $each)
		{
			$model=QuotedWarehouseRelation::model()->findByHistory($each);
			if($model)continue;
			$model=new QuotedWarehouseRelation();
			$model->quoted_id=$each->quoted_id;
			$model->area_id=$each->area_id;
			$model->price=$each->price;
			$model->price_date=date('Y-m-d',time());
			$model->insert();
		}	
		
		// back up weichat price data at the same time		
		$items=WxQuotedRelation::model()->findAll("price_date = '{$date}'");
		foreach ($items as $each)
		{
			$model=WxQuotedRelation::model()->findByHistory($each);
			if($model)continue;
			$model=new WxQuotedRelation();
			$model->quoted_id=$each->quoted_id;
			$model->area_id=$each->area_id;
			$model->price=$each->price;
			$model->price_date=date('Y-m-d',time());
			$model->insert();
		}
	}

	/**
	 * 今日报价单 打印页
	 */
	public function actionPrint() 
	{
		list($items, $time) = QuotedDetail::getReportList();		
// 		$model = new QuotedDetail();
// 		$cri = new CDbCriteria();
// 		$cri->select = "t.*,p.name as product_name,b.name as brand_name,tt.name as texture_name,r.name as rank_name";
// 		$cri->join = "left join dict_goods_property p on p.std=t.product_std
// 					left join dict_goods_property b on b.std=t.brand_std
// 					left join dict_goods_property tt on tt.std=t.texture_std
// 					left join dict_goods_property r on r.std=t.rank_std ";	
// 		$cri->addCondition("t.type = 'guidance'");
// 		$cri->order = "t.prefecture,t.texture_std,t.rank_std,t.brand_std,t.product_std";		
// 		$cri->limit = 6;
// 		$cri->offset = 0;
// 		$items = $model->findAll($cri);
// 		$time = 0;
// 		foreach ($items as $i){
// 			if($i->last_update>$time){
// 				$time = $i->last_update;
// 			}
// 		}
		$this->renderPartial('print', array(
			'items' => $items, 
			'time' => $time,
		));
	}
	
	//增加网价信息
	public function actionSetData(){
		$model = new QuotedWarehouseRelation();
		$cri = new CDbCriteria();
		//$cri->with = array("quoted");
		$cri->addCondition("t.price_date='2016-05-31'");
		$model = $model->findAll($cri);
		if($model){
			for($i=30;$i>=1;$i--){
// 				$id_arr = array();
// 				$old_arr = array();
				foreach ($model as $li){
// 					$quoted = $li->quoted;
// 					if(in_array($quoted->id,$id_arr)){
						$relation = new QuotedWarehouseRelation();
						$relation->quoted_id = $li->quoted_id;
						$relation->area_id = $li->area_id;
						$relation->price = 2000;
						if($i<10){
							$relation->price_date = "2016-05-0".$i;
						}else{
							$relation->price_date = "2016-05-".$i;
						}
						$relation->insert();
// 					}else{
// 						$detail = new QuotedDetail();
// 						$detail->product_std = $quoted->product_std;
// 						$detail->texture_std = $quoted->texture_std;
// 						$detail->brand_std = $quoted->brand_std;
// 						$detail->rank_std = $quoted->rank_std;
// 						$detail->length = $quoted->length;
// 						$detail->created_at = $quoted->created_at;
// 						$detail->created_by = $quoted->created_by;
// 						$detail->type = $quoted->type;
// 						if($i<10){
// 							$detail->price_date = "2016-05-0".$i;
// 						}else{
// 							$detail->price_date = "2016-05-".$i;
// 						}
// 						$detail->prefecture = $quoted->prefecture;
// 						if($detail->insert()){
// 							$relation = new QuotedWarehouseRelation();
// 							$relation->quoted_id = $detail->id;
// 							$relation->area_id = $li->area_id;
// 							$relation->price = 2000;
// 							if($i<10){
// 								$relation->price_date = "2016-05-0".$i;
// 							}else{
// 								$relation->price_date = "2016-05-".$i;
// 							}
// 							$relation->insert();
// 							array_push($id_arr,$quoted->id);
// 							$old_arr[$quoted->id] = $detail->id;
// 						}
// 					}
				}
			}
		}
	}
}
