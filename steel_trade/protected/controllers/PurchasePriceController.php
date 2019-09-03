<?php
class PurchasePriceController extends AdminBaseController{
	public $layout='admin';
	
	public function actionIndex()
	{
		$type = $_REQUEST['type'];
		if($_REQUEST['date_type']=="yes"){
			$this->pageTitle = "当日网价 - ".date("Y/m/d");
			$this->setHome = 1;//允许设为首页
		}else{
			$this->pageTitle = "历史网价";
		}
		$mo=new PurchasePrice();
		list($tableHeader,$tableData,$search,$date_type,$pages) = $mo->getAllList($ac);
		$sql='select edit_at as update_time,edit_by,user.nickname as nickname from purprice_date p left join user on user.id=p.edit_by 
				where edit_at=(select max(edit_at)  from purprice_date)';
		$connection=Yii::app()->db;
		$res=$connection->createCommand($sql)->queryRow();
				
		$products = DictGoodsProperty::getProList("product","","");
		$textures = DictGoodsProperty::getProList("texture","","");
		$ranks = DictGoodsProperty::getProList("rank","","");
		$brands = DictGoodsProperty::getProList("brand","","");
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'tableData'=>$tableData,
				'tableHeader'=>$tableHeader,
				'products'=>$products,
				'textures'=>$textures,
				'ranks'=>$ranks,
				'brands'=>$brands,
				'date_type'=>$date_type,		
				'last_update_user'=>$res['nickname'],
				'update_time'=>$res['update_time'],
		));	
	}
	
	public function actionUpdate()
	{		
		$time=$_REQUEST['PurchasePrice']['time'];		
		if(!$time||$time==date('Y-m-d'))
			$this->pageTitle = "当日网价管理 - ".date("Y/m/d");
		else
			$this->pageTitle = "往日网价管理 - ".str_replace('-', '/', $time);
		
		$ac='edit';
		$mo=new PurchasePrice();
		list($tableHeader,$tableData,$search,$date_type) = $mo->getAllList($ac);
		$sql='select edit_at as update_time,edit_by,user.nickname as nickname from purprice_date p left join user on user.id=p.edit_by 
				where edit_at=(select max(edit_at)  from purprice_date)';
		$connection=Yii::app()->db;
		$res=$connection->createCommand($sql)->queryRow();
		
		$products = DictGoodsProperty::getProList("product","","");
		$textures = DictGoodsProperty::getProList("texture","","");
		$ranks = DictGoodsProperty::getProList("rank","","");
		$brands = DictGoodsProperty::getProList("brand","","");
		$this->render('edit',array(
				'search'=>$search,
				'pages'=> $pages,
				'tableData'=>$tableData,
				'tableHeader'=>$tableHeader,
				'products'=>$products,
				'textures'=>$textures,
				'ranks'=>$ranks,
				'brands'=>$brands,
				'date_type'=>$date_type,
				'last_update_user'=>$res['nickname'],
				'update_time'=>$res['update_time'],
		));		
	}		
	/**
	 * 修改价格post处理页
	 */
	public function actionPost_update(){
		$ids = explode(",", $_POST['id']);
		$datas = explode(",",$_POST['data']);
		$time = $_POST['time'];
		$price_time=$_REQUEST['price_time'];
		for($i=0;$i<count($ids);$i++){
			$model = PurpriceDate::model()->findByAttributes(array('price_id'=>intval($ids[$i]),'price_date'=>$price_time));
			if($model){
				if($model->edit_at>=$time){
					echo "updated";return ;
				}
			}
		}	
		for($i=0;$i<count($ids);$i++){
			if(!intval($ids[$i]))continue;
			$model = PurpriceDate::model()->findByAttributes(array('price_id'=>intval($ids[$i]),'price_date'=>$price_time));
			if($model){
				$model->price = $datas[$i];
				$model->edit_at = time();
				$model->edit_by = Yii::app()->user->userid;
				$model->save();
			}elseif($datas[$i]){
				$model=new PurpriceDate();
				$model->price_id=intval($ids[$i]);
				$model->price = $datas[$i];
				$model->price_date=$price_time;
				$model->edit_at = time();
				$model->edit_by = Yii::app()->user->userid;
				$model->insert();
			}
		}
		echo 1;	
	}
	
	public function actionReport(){
		list($items,$time) = PurchasePrice::getReportList();
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
	
}