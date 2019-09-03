<?php
class FrmSendController extends AdminBaseController
{
	public $layout='admin';
	
	/*
	 * 配送单列表
	 */
	public function actionIndex()
	{
		$this->pageTitle = "配送单列表";
		$id = intval($_REQUEST["id"]);
		$type = $_REQUEST["type"];
		$page = $_REQUEST['fpage'];
		$view = $_REQUEST['view'];
		if($view == ''){$view = $_COOKIE['sendview'];}
		if($view == ''){$view = "index";}
		if(!checkOperation("配送审核视图") && $view=="checkview"){
			$view = "index";
		}
		setcookie("sendview",$view,time()+3600*24*30,"/");
		$fromurl = $_SERVER['HTTP_REFERER'];
		if(stristr($fromurl,"frmSend")){
			$backurl = $_COOKIE["salesBackUrl"];
		}else{
			$backurl = $fromurl;
			setcookie("salesBackUrl",$fromurl,0,"/");
		}
		$send = array();
		if($id){
			$sales = FrmSales::model()->findByPk($id);
			$send["amount"] = $sales->amount;
			$send["output_amount"] = intval($sales->output_amount);
			$sadetail = $sales->salesDetails;
			foreach ($sadetail as $li){
				$send["send_amount"] += $li->send_amount;
			}
		}else{
			$sales = "";
		}
		if($id){
			$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"20px"),
				array('name'=>'操作','class' =>"",'width'=>"80px"),
				array('name'=>'单号','class' =>"",'width'=>"90px"),
				//array('name'=>'销售单号','class' =>"flex-col",'width'=>"115px"),
				array('name'=>'状态','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'开单日期','class' =>"flex-col",'width'=>"80px"),
				//array('name'=>'销售单位','class' =>"flex-col",'width'=>"100px"),
				//array('name'=>'购货单位','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'提货码/车船号','class' =>"flex-col",'width'=>"140px"),//
				array('name'=>'客户提货码','class' =>"flex-col",'width'=>"80px"),//
				//array('name'=>'库存卡号','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'品名','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'材质','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'规格','class' =>"flex-col",'width'=>"50px"),//
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"40px"),//
				array('name'=>'仓库','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'件数','class' =>"flex-col text-right",'width'=>"90px"),//
				array('name'=>'实时出库件数','class' =>"flex-col text-right",'width'=>"84px"),//
				array('name'=>'实时出库重量','class' =>"flex-col text-right",'width'=>"84px"),//
				array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'修改人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),//
			);
		}else{
			$tableHeader = array(
				array('name'=>'','class' =>"text-center",'width'=>"20px"),
				array('name'=>'操作','class' =>"",'width'=>"80px"),
				array('name'=>'单号','class' =>"",'width'=>"90px"),
				//array('name'=>'销售单号','class' =>"flex-col",'width'=>"115px"),
				array('name'=>'状态','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'开单日期','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'销售单位','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'购货单位','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'提货码/车船号','class' =>"flex-col",'width'=>"120px"),//
				array('name'=>'客户提货码','class' =>"flex-col",'width'=>"90px"),//
				//array('name'=>'库存卡号','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'品名','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'材质','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'规格','class' =>"flex-col",'width'=>"50px"),//
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"40px"),//
				array('name'=>'仓库','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'件数','class' =>"flex-col text-right",'width'=>"70px"),//
				array('name'=>'实时出库件数','class' =>"flex-col text-right",'width'=>"84px"),//
				array('name'=>'实时出库重量','class' =>"flex-col text-right",'width'=>"84px"),//
				array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'修改人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),//
				);
		}
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		//车牌号
		$car_no = FrmSend::gerCarNum($id);
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['status'] == "deleted"){
				array_push($tableHeader,array('name'=>'作废原因','class' =>"flex-col",'width'=>"240px"));
			}
		}

		//获取表单列表
		if($type == "menu" || $id == 0){
			$search=updateSearch($search,'search_send1_index');
		}else{
			$search=updateSearch($search,'search_send2_index');
		}
		
		list($tableData,$pages)=FrmSend::getFormList($search,$id,$view);
		if($type == "menu" || $id == 0){
			$this->render("index1",array(
					'id'=>$id,
					'car_no'=>$car_no,
					'backUrl'=>$backurl,
					'users'=>$user_array,
					'vendors'=>$vendor,
					'coms'=>$com,
					'products'=>$products_array,
					'textures'=>$textures_array,
					'brands'=>$brands_array,
					'rands'=>$ranks_array,
					'pages'=>$pages,
					'search'=>$search,
					'tableHeader'=>$tableHeader,
					'tableData'=>$tableData,
					'sales'=>$sales,
					'view'=>$view,
			));
		}else{
			$this->render("index",array(
					'id'=>$id,
					'car_no'=>$car_no,
					'backUrl'=>$backurl,
					'users'=>$user_array,
					'vendors'=>$vendor,
					'coms'=>$com,
					'products'=>$products_array,
					'textures'=>$textures_array,
					'brands'=>$brands_array,
					'rands'=>$ranks_array,
					'pages'=>$pages,
					'search'=>$search,
					'tableHeader'=>$tableHeader,
					'tableData'=>$tableData,
					'sales'=>$sales,
					"send"=>$send,
			));
		}
	}
	
	/*
	 * 新建配送单
	 */
	public function actionCreate()
	{
		$this->pageTitle = "新增配送单";
		$id = $_REQUEST["id"];
		if($_POST['send']){
			//$_POST['CommonForms']['owned_by']=$baseform->owned_by;
			//$_POST['send']['frm_sales_id']= $sales->id;
			$data = FrmSend::createSend($_POST);
				
			if($data){
				$this->redirect(yii::app()->createUrl("FrmSend/index",array("id"=>$id,"view"=>$_COOKIE["view"])));
			}
		}
		
		if($id){ 
			$sales = FrmSales::model()->findByPk($id);
			$salesDetails = $sales->salesDetails();
			$baseform = $sales->baseform;
		}
		$backurl = $_SERVER['HTTP_REFERER'];
		if(!stristr($backurl,"frmSend")){
			setcookie("salesBackUrl",$backurl,0,"/");
		}
// 		$sql = "select *,sum(amount) as t_amount,sum(send_amount) as t_send_amount from sales_detail where frm_sales_id=".$sales->id." group by product_id,brand_id,texture_id,rank_id,length";
// 		$cmd = Yii::app()->db->createCommand($sql);
// 		$salesDetails = $cmd->queryAll($cmd);
		
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json");
		
		if($salesDetails){
			foreach($salesDetails as $dt){
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
				if($weight == 0){
					$dt->one_weight = $dt->weight/$dt->amount;
				}
				
			}
// 			for($i=0;$i<count($salesDetails);$i++){
// 				$salesDetails[$i]["product"] = DictGoodsProperty::getProName($salesDetails[$i]["product_id"]);
// 				$salesDetails[$i]["rank"] = DictGoodsProperty::getProName($salesDetails[$i]["rank_id"]);
// 				$salesDetails[$i]["texture"] = DictGoodsProperty::getProName($salesDetails[$i]["texture_id"]);
// 				$salesDetails[$i]["brand"] = DictGoodsProperty::getProName($salesDetails[$i]["brand_id"]);
// 				$type['product'] = $salesDetails[$i]["product_id"];
// 				$type['rank'] = $salesDetails[$i]["rank_id"];
// 				$type['brand'] = $salesDetails[$i]["brand_id"];
// 				$type['texture'] = $salesDetails[$i]["texture_id"];
// 				$type['length'] = $salesDetails[$i]["length"];
// 				$salesDetails[$i]["cost_price"] = DictGoods::getUnitWeight($type);
// 			}
		}
		
		$this->render("create",array(
			'id'=>$id,
			'sales'=>$sales,
			'salesDetails'=>$salesDetails,
			'baseform'=>$baseform,
			'backurl'=>$backurl,
			'com'=>$com,
			'vendor'=>$vendor,
			'teams'=>$team_array,
			'warehouse'=>$warehouse_array,
		));
	}
	
	
	/*
	 * 编辑配送单
	 */
	public function actionUpdate($id)
	{
		$model = FrmSend::model()->with("FrmSales")->findByPk($id);
		$sid = $_REQUEST["sid"];
		$sales = $model->FrmSales;
		$send_detail = $model->sendDetails;
		$baseform = $model->baseform;
		$this->pageTitle = "编辑配送单".$baseform->form_sn;
		if($_POST['send']){
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请重试";
			}else{
				$data = FrmSend::updateSend($_POST);
				$allform=new Frm_Send($baseform->id);
				$allform->updateForm($data);
				$this->redirect(yii::app()->createUrl("FrmSend/index",array('id'=>$sid,'page'=>$_REQUEST['fpage'],"view"=>$_COOKIE["view"])));
			}
		}
		$id_detail = array();
		if($send_detail){
			foreach ($send_detail as $dt){
				array_push($id_detail,$dt->id);
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
			}
		}

		$this->render("update",array(
			'id'=>$id,
			'model'=>$model,
			'detail'=>$send_detail,
			'sales'=>$sales,
			'baseform'=>$baseform,
			'msg'=>$msg,
		));
	}
	
	/*
	 * 查看配送单试图
	 */
	public function actionDetail($id)
	{
		$model = FrmSend::model()->with("FrmSales")->findByPk($id);
		$sales = $model->FrmSales;
		$send_detail = $model->sendDetails;
		$baseform = $model->baseform;
		$this->pageTitle = "查看配送单".$baseform->form_sn;
		$id_detail = array();
		if($send_detail){
			foreach ($send_detail as $dt){
				array_push($id_detail,$dt->id);
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
			}
		}
		$this->render("detail",array(
				'id'=>$id,
				'model'=>$model,
				'detail'=>$send_detail,
				'sales'=>$sales,
				'baseform'=>$baseform,
		));
	}
	
	/*
	 * 配送单列表
	 */
	public function actionTest($id)
	{
		$this->pageTitle = "配送单列表";
		$page = $_REQUEST['fpage'];
		$backurl = yii::app()->createUrl("FrmSales/index",array("page"=>$page));
		//获取表单列表
		list($tableData,$pages)=FrmSend::getFormList($search,$id);
	
		$this->render("test",array(
				'id'=>$id,
				'tableData'=>$tableData
		));
	}
	
	/*
	 * 作废表单
	 */
	public function actionDeleteform($id)
	{
		$str = $_REQUEST['str'];
		$deleted = $_REQUEST['deleted'];
		$baseform=CommonForms::model()->findByPk($id);
		$send = $baseform->send;
		$sales = $send->FrmSales;
		if($baseform)
		{
			$last_update=$_REQUEST['last_update'];
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			return false;
		}
		if($baseform->form_status !="unsubmit"){
			echo "表单状态不对，不能作废";
			die;
		}
		if($send->output_amount > 0){
			echo "表单已经出库，不能作废";
			die;
		}
		if(Yii::app()->params['api_switch'] == 1 && $sales->can_push == 1 && $deleted != "yes"){
			$baseform->delete_reason = $str;
			$baseform->update();
			//接口推送作废
			if($sales->can_push == 1 && $sales->sales_type != "xxhj" && Yii::app()->params['api_switch'] == 1){				
				FrmSend::DeletePush($send->id,"deliveryform","Delete");
			}
		}else{
			$form=new Frm_Send($id);
			$form->deleteForm($str);
		}
		echo "success";
	}
	
	/*
	 * 完成配送单
	 */
	public function actionComplete($id)
	{
		$baseform=CommonForms::model()->findByPk($id);
		$send = $baseform->send;
		if($baseform)
		{
			$last_update=$_REQUEST['last_update'];
			if($send->status == "finished"){
				echo "表单已经完成，无需重复执行";
				die;
			}
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			return false;
		}
		$send->status = 'pushing';
		$send->update();
		//推送接口中心
		$sales = $send->FrmSales;
		if($sales->can_push == 1 && $sales->sales_type == "normal" && Yii::app()->params['api_switch'] == 1){
			FrmSend::FinishPush($send->id,"deliveryform","Finish");
		}else{
	 		$form=new Frm_Send($id);
	 		$result = $form->completeSendForm();
		}
		echo "success";
	}
	
	public function actionLook(){
		$result = WarehouseOutput::model()->findByPk(2649);
		FrmSend::setVirtual($result);
	}
	
	//配送单发送提货码
	public function actionSendMsg(){
		$phone = $_POST['phone'];
		//$str = $_POST['str'];
		$id =  $_POST['id'];
		$send = FrmSend::model()->findByPk($id);
		if(!$send){
			echo "获取配送单信息失败";die;
		}
		$sales = $send->FrmSales;
		//发送信息
		$contentarray["company"]="“".$sales->dictCompany->name."”";
		$contentarray["thm"]="“".$send->auth_code."”";
		$contentarray["code"]="及行驶证原件到".$sales->warehouse->name."提货，共“".$send->amount."”件，仓库地址：".$sales->warehouse->address."，联系电话：".$sales->warehouse->mobile;
		$sendmess = new Sendmessage();
		$sendmess->frm_send_id = $send->id;
		$sendmess->company_id = $sales->customer_id;
		$sendmess->phone = $phone;
		$sendmess->content = json_encode($contentarray);
		$sendmess->status = 0;
		$sendmess->create_at = time();
		$sendmess->module_id = 1080989;
		if($sendmess->insert()){
			echo "success";
		}else{
			echo "fail";
		}
	}
	
	//根据销售单创建配送单
	public function actionRandSend(){
		$num = 0;
		while(true){
			$result = FrmSend::randSend();
			if($result){
				$num ++;
				if($num>=1000){
					break;
				}
			}
		}
	}
}
?>