<?php
class FrmPurchaseReturnController extends AdminBaseController
{
	/*
	 * 采购退货单列表
	 */
	public function actionIndex()
	{
		$this->pageTitle = "采购退货管理";
		$tableHeader = array(
			array('name'=>'','class' =>"",'width'=>"30px"),
			array('name'=>'操作','class' =>"",'width'=>"100px"),
			array('name'=>'退货单号','class' =>"flex-col",'width'=>"110px"),
			array('name'=>'创建日期','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'销售公司','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'供应商','class' =>"flex-col",'width'=>"100px"),//
			array('name'=>'仓库','class' =>"flex-col",'width'=>"80px"),//
			array('name'=>'库存卡号','class' =>"flex-col",'width'=>"140px"),
			array('name'=>'产地','class' =>"flex-col",'width'=>"60px"),//
			array('name'=>'品名','class' =>"flex-col",'width'=>"60px"),//
			array('name'=>'材质','class' =>"flex-col",'width'=>"70px"),//
			array('name'=>'规格','class' =>"flex-col",'width'=>"50px"),//
			array('name'=>'长度','class' =>"flex-col",'width'=>"50px"),//
			array('name'=>'退货件数','class' =>"flex-col",'width'=>"60px"),//
			array('name'=>'出库件数','class' =>"flex-col",'width'=>"60px"),//
			array('name'=>'退货重量','class' =>"flex-col",'width'=>"80px"),//
			array('name'=>'退货金额','class' =>"flex-col",'width'=>"80px"),//
			array('name'=>'乙单','class' =>"flex-col",'width'=>"40px"),
 			//array('name'=>'费用','class' =>"flex-col",'width'=>"100px"),//
// 			array('name'=>'配送件数','class' =>"flex-col",'width'=>"100px"),//
// 			array('name'=>'出库件数','class' =>"flex-col",'width'=>"100px"),//
			array('name'=>'审核状态','class' =>"flex-col",'width'=>"60px"),//
			array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
			array('name'=>'修改人','class' =>"flex-col",'width'=>"60px"),//
			array('name'=>'备注','class' =>"flex-col",'width'=>"180px"),
		);
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendor=DictCompany::getVendorList("json","is_supply");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("array");
		//联系人
		$contacts_array=CompanyContact::getContactList();
		//根据品名，规格，材质，产地来选择商品
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
		if($_GET["card_no"]){
			$search['keywords'] = $_GET["card_no"];
		}
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['form_status'] == "delete"){
				array_push($tableHeader,array('name'=>'作废原因','class' =>"flex-col",'width'=>"240px"));
			}
		}
		//获取表单列表
		list($tableData,$pages,$totaldata)=FrmPurchaseReturn::getFormList($search);
		
		$this->render('index',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'baseform'=>$baseform,
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendor,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'rands'=>$ranks_array,
				'contacts'=>$contacts_array,
				'pages'=>$pages,
				'search'=>$search,
				"totalData"=>$totaldata,
		));
	}
	
	/*
	 * 创建表单
	 */
	public function actionCreate()
	{
		if($_POST['CommonForms'])
		{
			$data = FrmPurchaseReturn::createReturn($_POST);
			if($_POST['submit_type'] == 1){
				$allform=new PurchaseReturn($id);
				$result1 = $allform->createSubmitForm($data);
				if($result1 === -1){
					$msg = "库存不足，提交失败，请重新操作";
				}else{
					$this->redirect(yii::app()->createUrl("FrmPurchaseReturn/index"));
				}
			}else{
				$allform=new PurchaseReturn($id);
				$result = $allform->createForm($data);
				if($result){
					$this->redirect(yii::app()->createUrl("FrmPurchaseReturn/index"));
				}else{
					$msg = "保存失败";
				}
			}
		}
		$this->pageTitle="新建采购退货单";
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendors=DictCompany::getVendorList("json","is_supply");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json");
		//联系人
		$contacts_array=CompanyContact::getContactList();
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		
		$user_dt = currentUserId();
		$this->render('create',array(
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendors,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'product'=>$products_array,
				'material'=>$textures_array,
				'brand'=>$brands_array,
				'type'=>$ranks_array,
				'contacts'=>$contacts_array,
				'gkvendor'=>$gkvendor,
				'user_dt'=>$user_dt,
				'msg'=>$msg,
				'user_dt'=>$user_dt,
		));
	}
	
	/*
	 * 修改表单
	 */
	public function actionUpdate($id)
	{
		$baseform=CommonForms::model()->with('sales')->findByPk($id);
		$this->pageTitle = "修改采购配货单 ".$baseform->form_sn;
		if($_POST['CommonForms'])
		{
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}else{
				$data=FrmPurchaseReturn::getUpdateData($_POST);
				$allform=new PurchaseReturn($id);
				if($_POST['submit_type'] == 1){
					$result1 = $allform->updateSubmitForm($data);
					if($result1 === -1){
						$msg = "库存不足，提交失败，请重新操作";
					}else{
						$this->redirect(yii::app()->createUrl("FrmPurchaseReturn/index",array('page'=>$_REQUEST['fpage'])));
					}
				}else{
					$allform->updateForm($data);
					$this->redirect(yii::app()->createUrl("FrmPurchaseReturn/index",array('page'=>$_REQUEST['fpage'])));
				}
			}
		}
		
		if($baseform)
		{
			$return=$baseform->purchaseReturn;
			$details=$return->purchaseReturnDetails;
		}else{
			return false;
		}
		
		if($details){
			foreach($details as $dt){
				if($dt->card_no){
					$storage = Storage::model()->findByPk($dt->card_no);
					if($baseform->form_status == "unsubmit"){
						$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
					}else{
						$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount + $dt->return_amount;
					}
				}
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->weight = $weight;
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
			}
		}
	
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendors=DictCompany::getVendorList("json","is_supply");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json");
		//联系人
		$contacts_array=CompanyContact::getContactList($sales->customer_id);
		//根据品名，规格，材质，产地来选择商品
		$id_product= array();
		$id_texture= array();
		$id_brand= array();
		$id_rank= array();
		if($details){
			foreach($details as $li){
				array_push($id_product,$li->product_id);
				array_push($id_texture,$li->texture_id);
				array_push($id_brand,$li->brand_id);
				array_push($id_rank,$li->rank_id);
			}
		}
		//1品名
		$products_array=DictGoodsProperty::getProList('product',"array",$id_product);
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture',"array",$id_texture);
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json",$id_brand);
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank',"array",$id_rank);
	
		$this->render('update',array(
				'baseform'=>$baseform,
				'return'=>$return,
				'details'=>$details,
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendors,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'product'=>$products_array,
				'material'=>$textures_array,
				'brand'=>$brands_array,
				'type'=>$ranks_array,
				'contacts'=>$contacts_array,
				'msg'=>$msg,
		));
	}
	
	/*
	 * 修改表单
	 */
	public function actionDetail($id)
	{
		$baseform=CommonForms::model()->with('purchaseReturn')->findByPk($id);
		$this->pageTitle = "查看采购退货单".$baseform->form_sn;
		if($baseform)
		{
			$return=$baseform->purchaseReturn;
			$details=$return->purchaseReturnDetails;
		}else{
			return false;
		}
		if($details){
			foreach($details as $dt){
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->weight = $weight;
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
			}
		}
		
		$this->render('detail',array(
				'baseform'=>$baseform,
				'return'=>$return,
				'details'=>$details,
				'backUrl'=>'index',
				'msg'=>$msg,
		));
	}
	
	/*
	 * 提交与取消提交
	 */
	public function actionSubmit($id,$type)
	{
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
	
		$form=new PurchaseReturn($id);
		if($type=='submit')
		{
			$result = $form->submitForm();
			if($result === -1){
				echo "库存不足，不能提交";
				die;
			}
		}elseif($type=='cancle')
		{
			$form->cancelSubmitForm();
		}
		echo "success";
	}
	
	/*
	 * 作废表单
	 */
	public function actionDeleteform($id)
	{
		$baseform=CommonForms::model()->findByPk($id);
		$str = $_REQUEST['str'];
		if($baseform)
		{
			$last_update=$_REQUEST['last_update'];
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			echo "获取基础信息失败";
			die;
		}
		if($baseform->form_status !="unsubmit"){
			echo "表单已经提交，不能作废";
			die;
		}else{
			$form=new PurchaseReturn($id);
			$form->deleteForm($str);
			echo "success";
		}
	}
	
	/*
	 * 审核表单
	 *
	 */
	public function actionCheck($id)
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
		$form=new PurchaseReturn($id);
		if($type=='pass')
		{
			$form->approveForm();
		}elseif($type=='cancle')
		{
			$detail = $baseform->purchaseReturn->purchaseReturnDetails;
			if($detail){
				foreach($detail as $li){
					if($li->output_amount >0){
						echo "采购退货已出库，不能取消!";die;
					}
				}
			}
			$result = $form->cancelApproveForm();
			if($result){
				echo "success";die;
			}else{
				echo "采购退货已开票，不能取消!";die;
			}
		}elseif($type=='deny')
		{
			$form->refuseForm();
		}
		echo "success";
	}
	
	/*
	 * 完成销售单
	 */
	public function actionComplete($id)
	{
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
		if($baseform->form_status != "approve"){
			echo "表单没有审核，不能完成";
			die;
		}
		$form=new PurchaseReturn($id);
		$result = $form->completeSales();
		// var_dump($result);
		// die;
		if($result==='已销票'){
			echo '已销票';
		}elseif($result){
			echo "success";
		}else{
			echo 0;
		}
	}
	
	/*
	 * 取消完成销售单
	 */
	public function actionCancelcomplete($id)
	{
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
		$form=new PurchaseReturn($id);
		$result = $form->cancelcompleteSales();
		if($result==='已销票'){
			echo "已销票";
		}elseif($result){
			echo "success";
		}else{
			echo 0;
		}
	}
	
	/*
	 * 获取退货单表单列表
	 */
	public function actionReturnlist()
	{
		$this->layout="";
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'选择','class' =>"sort-disabled",'width'=>"50px"),
				array('name'=>'退货单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'仓库','class' =>"flex-col sort-disabled",'width'=>"80px"),//
		);
		
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("array");
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableData,$pages)=FrmPurchaseReturn::getReturnFormList($search);
		
		$this->renderPartial('_relist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'com'=>$com,
				'search'=>$search,
				'pages'=>$pages,
				'vendor'=>$vendor,
				'teams'=>$team_array,
				'warehouse'=>$warehouse_array,
		));
	}
	
	public function actionGetReturnData()
	{
		$id=$_REQUEST['id'];
		$result=FrmPurchaseReturn::getReturnData($id);
		echo $result;
	}
	
	/*
	 * 获取库存锁库采购退货列表
	 */
	public function actionlocklist()
	{
		$this->pageTitle = "库存锁定";
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"40px"),
				array('name'=>'退货单号','class' =>"",'width'=>"140px"),
				array('name'=>'创建日期','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'销售公司','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'供应商','class' =>"flex-col",'width'=>"110px"),//
				array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'库存卡号','class' =>"flex-col",'width'=>"140px"),
				array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'品名','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'长度','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'退货件数','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'锁定件数','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'出库件数','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'退货重量','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'退货金额','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'审核状态','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'制单人','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'修改人','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
		);
		
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		$storage_id = $_REQUEST["storage_id"];
		//获取表单列表
		list($tableData,$pages,$totaldata1)=FrmPurchaseReturn::getLockList($search,$storage_id);
	
		$this->render('locklist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'baseform'=>$baseform,
				'backUrl'=>'index',
				'pages'=>$pages,
				'search'=>$search,
				"totalData"=>$totaldata,
				"storage_id"=>$storage_id,
		));
	}

	public function actionPrint($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$model = $baseform->purchaseReturn;
		$details = $model->purchaseReturnDetails;
		
		$this->renderPartial('print', array(
				'baseform' => $baseform, 
				'model' => $model, 
				'details' => $details,
		));
	}
}