<?php 
class OwnerTransferController extends AdminBaseController
{
	
	/*
	 * 转库单列表
	 */
	public function actionIndex()
	{
		$id = intval($_REQUEST["id"]);
		$this->pageTitle = "转库单列表";
		
		$page = $_REQUEST['fpage'];
		$fromurl = $_SERVER['HTTP_REFERER'];
		if(stristr($fromurl,"ownerTransfer")){
			$backurl = $_COOKIE["transferCreateBackUrl"];
		}else{
			$backurl = $fromurl;
			setcookie("transferCreateBackUrl",$fromurl,0,"/");
		}
		$createUrl = Yii::app()->createUrl('ownerTransfer/create',array("id"=>$id,'from'=>$from));
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'操作','class' =>"",'width'=>"120px"),
				array('name'=>'转库单号','class' =>"",'width'=>"140px"),
				array('name'=>'状态','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'日期','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'转出单位','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'转入单位','class' =>"flex-col",'width'=>"110px"),//
				array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),//
				array('name'=>'产地/品名/材质/规格/长度','class' =>"flex-col",'width'=>"240px"),//
				//array('name'=>'提货码/车牌号','class' =>"flex-col",'width'=>"160px"),//
				array('name'=>'件数','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"120px"),//
				array('name'=>'单号','class' =>"flex-col",'width'=>"140px"),
				array('name'=>'类型','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
				);
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['form_status'] == "delete"){
				array_push($tableHeader,array('name'=>'作废原因','class' =>"flex-col",'width'=>"240px"));
			}
		}
		//获取表单列表
		list($tableData,$pages,$totaldata)=OwnerTransfer::getFormList($search,$id);
	
		$this->render("index",array(
				'id'=>$id,
				'backUrl'=>$backurl,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'rands'=>$ranks_array,
				'pages'=>$pages,
				'search'=>$search,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'sales'=>$sales,
				'createUrl'=>$createUrl,
				"type"=>$type,
				'from'=>$from,
				'totaldata'=>$totaldata,
		));
	}
	
	/*
	 * 销售转库新建
	 */
	public function actionCreate()
	{
		$this->pageTitle = "新增转库单";
		$id = intval($_REQUEST["id"]);
		if($id){
			$sales = FrmSales::model()->findByPk($id);
			$baseform = $sales->baseform;
		}else{
			$sales = "";
			$baseform = "";
		}
		$from = $_REQUEST['from'];
		$backurl = $_SERVER['HTTP_REFERER'];
		if(!stristr($backurl,"create")){
			setcookie("transferCreateBackUrl",$backurl,0,"/");
		}else{
			$backurl = $_COOKIE['transferCreateBackUrl'];
		}
		if(!stristr($backurl,"ownerTransfer")){
			setcookie("transferBackUrl",$backurl,0,"/");
		}
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		
		if($_POST['amount']){
			$_POST['CommonForms']['owned_by']=$baseform->owned_by;
			$data = OwnerTransfer::createOutput($_POST);
			if($data === -2){
				$msg =  "转库件数大于销售件数，不能转库";
			}else if($data === -1){
				$msg =   "转库件数大于卡号可出件数，不能转库";
			}else if($data === -5){
				$msg =   "托盘赎回量不足，不能转库，请先赎回托盘";
			}else if($data){
				$this->redirect(yii::app()->createUrl("ownerTransfer/index",array("id"=>$id,'from'=>$from)));
			}
		}
		
		$this->render("create",array(
				'id'=>$id,
				'sales'=>$sales,
				'baseform'=>$baseform,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
				'com'=>$com,
				'vendor'=>$vendor,
				"backurl"=>$backurl,
				'msg'=>$msg,
		));
	}
	
	/*
	 * 提交与取消提交
	 */
	public function actionSubmit($id)
	{
		$type = $_REQUEST["type"];
		$baseform=CommonForms::model()->findByPk($id);
	
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
	
		$form=new Transfer($id);
		if($type=='submit')
		{
			$result = $form->SubmitForm();
			if($result === -2){
				echo "转库件数大于销售件数，不能出库";
				die;
			}
			if($result === -1){
				echo "转库件数大于卡号可转出件数，不能出库";
				die;
			}
			if($result === -5){
				echo "托盘赎回量不足，不能转库，请先赎回托盘";
				die;
			}
		}elseif($type=='cancle')
		{
			$sales = $baseform->output->frmsales;
			if($sales->confirm_status == 1){
				$frmsales=new Sales($sales->baseform->id);
				$result = $frmsales->cancelcompleteSales();
				if(!$result){
					echo "取消完成销售单失败";
					die;
				}
			}
			$result = $form->salesCancelSubmitForm();
			if($result === -1){
				echo "产品已开票，不能取消出库";
				die;
			}
			if($result === -3){
				echo "库存已清卡，不能取消出库";
				die;
			}
		}
		echo "success";
	}
	
	/*
	 * 作废表单
	 */
	public function actionDeleteform($id)
	{
		$str = $_REQUEST['str'];
		$baseform=CommonForms::model()->findByPk($id);
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
	
			echo "表单已经提交，不能作废";
			die;
		}else{
			$form=new Transfer($id);
			$form->deleteForm($str);
			echo "success";
		}
	}
}