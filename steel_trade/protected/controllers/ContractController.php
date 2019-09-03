<?php
class ContractController extends AdminBaseController
{
	/*
	 * 合同表单列表
	 */
	public function actionIndex()
	{
		$this->pageTitle = "采购合同";
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();	//表单所属人	
		$team_array=Team::getTeamList('array');	//业务组	
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['title']))
		{
			$data=json_decode($_REQUEST['search_data']);
			$search['company']=$_REQUEST['title'];
			$search['vendor']=$_REQUEST['company'];
			if($data->time_L)$search['time_L']=$data->time_L;
			if($data->time_H)$search['time_H']=$data->time_H;
		}elseif(isset($_REQUEST['card_no']))
		{
			$search['keywords']=$_REQUEST['card_no'];
		}elseif(isset($_REQUEST['search_url'])){
			$search=(Array)json_decode($_REQUEST['search_url']);
		}
		$search=updateSearch($search,'search_contract');
		//获取表单列表
		list($tableHeader,$tableData,$pages,$totalData)=FrmPurchaseContract::getFormList($search);		
		
		$this->render('index',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
				'totalFee'=>$totalFee,
				'coms'=>$coms,
				'search'=>$search,
				'pages'=>$pages,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'teams'=>$team_array,
				'users'=>$user_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,	
				'textures'=>$textures_array,	
		));
	}
	/*
	 * 合同列表
	 * 采购单处使用
	 */
	public function actionListForSelect()
	{
		$this->layout="";
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"10px"),
				array('name'=>'选择','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'单号','class' =>"flex-col sort-disabled",'width'=>"120px"),
				array('name'=>'合同编号','class' =>"flex-col sort-disabled",'width'=>"150px"),//修
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"110px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'采购重量','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),//
				array('name'=>'采购件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
// 				array('name'=>'已执行量','class' =>"flex-col sort-disabled",'width'=>"80px"),//
// 				array('name'=>'未执行量','class' =>"flex-col sort-disabled",'width'=>"80px"),//
// 				array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				);
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$team_array=Team::getTeamList('json');	//业务组
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		if(isset($_GET['ven_id'])&&$_GET['ven_id']!='')
		{
			$search['vendor']=$_GET['ven_id'];
		}
		if(isset($_GET['title_id'])&&$_GET['title_id']!='')
		{
			$search['company']=$_GET['title_id'];
		}
		//获取表单列表
		list($tableData,$pages)=FrmPurchaseContract::getFormSimpleList($search);
		
		$this->render('listforselect',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'coms'=>$coms,
				'search'=>$search,
				'pages'=>$pages,
				'vendors'=>$vendor_array,
				'teams'=>$team_array,
		));
	}
	
	/*
	 * 查看
	 */
	public function actionView($id)
	{
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;				
		$baseform=CommonForms::model()->with('contract')->findByPk($id);
		if($baseform)
		{
			$contract=$baseform->contract;
			$details=$contract->purchaseContractDetails;
		}else{
			return false;
		}		
		$this->pageTitle = "查看采购合同".$baseform->form_sn;
		$view='view';
		$this->render($view,array(
				'baseform'=>$baseform,
				'contract'=>$contract,
				'details'=>$details,
				'fpage'=>$fpage
		));
	}
	/*
	 * 创建表单
	 */
	public function actionCreate()
	{
		$this->pageTitle = "新建采购合同";
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		if($_POST['CommonForms'])
		{
			$data=FrmPurchaseContract::getInputData($_POST);			
			$allform=new Contract($id);
			$allform->createForm($data);
			if($data['common']->submit=="yes")
			{
				$allform->submitForm();
			}
			$this->redirect(Yii::app()->createUrl('contract/index',array("page"=>$fpage)));
		}
		$user_array=User::getUserList();//表单所属人
		$vendor_array=DictCompany::getVendorList('json');//供应商	
		$com_array=DictTitle::getComs('json');//采购公司
		$team_array=Team::getTeamList('array');//业务组
		
		$warehouse_array=Warehouse::getWareList('json');//仓库
		$contacts_array=CompanyContact::getContactList();//联系人
		//根据品名，规格，材质，产地来选择商品
		$products_array=DictGoodsProperty::getProList('product');		//1品名
		$textures_array=DictGoodsProperty::getProList('texture','json');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json');//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','json');//4规格		
		$this->render('create',array(				
				'users'=>$user_array,
				'type'=>'采购合同',
				'vendors'=>$vendor_array,
				'coms'=>$com_array,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'ranks'=>$ranks_array,
				'contacts'=>$contacts_array,
				'fpage'=>$fpage
		));	
	}
	
	
	/*
	 * 更新表单
	 */
	public function actionUpdate($id)
	{
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		$baseform=CommonForms::model()->with('contract')->findByPk($id);
		$this->pageTitle = "修改采购合同".$baseform->form_sn;
		if($baseform)
		{
			$contract=$baseform->contract;
			$details=$contract->purchaseContractDetails;
		}else{			
			return false;
		}		
// 		$last_update=$_REQUEST['last_update'];
// 		if($last_update!=$baseform->last_update)
// 		{
// 			echo "<script>alert('您看到的信息不是最新的，请刷新后再试');setTimeout('',500);window.location.href='/index.php/contract/index';</script>";
// 			die;
// 		}		
		if($_POST['CommonForms'])
		{
			$data=FrmPurchaseContract::getUpdateData($_POST);
			$allform=new Contract($id);
			$allform->updateForm($data);
			if($data['common']->submit=="yes")
			{
				$allform->submitForm();
			}
			$this->redirect(Yii::app()->createUrl('contract/index',array("page"=>$fpage)));
		}
		
		$user_array=User::getUserList();//表单所属人
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$com_array=DictTitle::getComs('json');//采购公司
		$team_array=Team::getTeamList('array');//业务组
		$warehouse_array=Warehouse::getWareList('json');//仓库
		$contacts_array=CompanyContact::getContactList();//联系人
		//根据品名，规格，材质，产地来选择商品
		list($id_product,$id_texture,$id_brand,$id_rank)=proListId($details);
		$products_array=DictGoodsProperty::getProList('product','array',$id_product);		//1品名
		$brands_array=DictGoodsProperty::getProList('brand','json',$id_brand);//3产地
		if($baseform->form_status=='submited')
		{
			$view='sub_update';
		}elseif($baseform->form_status=='unsubmit')
		{
			$view='update';
		}
		$this->render($view,array(
				'baseform'=>$baseform,
				'contract'=>$contract,
				'details'=>$details,
				'users'=>$user_array,
				'type'=>'采购合同',
				'vendors'=>$vendor_array,
				'coms'=>$com_array,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'brands'=>$brands_array,
				'contacts'=>$contacts_array,
				'fpage'=>$fpage
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
		
		$form=new Contract($id);
		if($type=='submit')
		{
			if($form->submitForm()){
				echo 1;
			}
		}elseif($type=='cancle')
		{
			if($form->cancelSubmitForm()){
				echo 1;
			}
		}
	}
	
	
	/*
	 * 作废表单
	 */
	public function actionDeleteform($id)
	{
		$baseform=CommonForms::model()->findByPk($id);
		$reason=$_REQUEST['str'];
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
		$form=new Contract($id);
		if($form->deleteForm($reason)){
			echo 1;
		}
	}
	
	/*
	 * 审核相关
	 * 
	 */
	public function actionCheck($id,$type)
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
		$form=new Contract($id);
		if($type=='pass')
		{
			if($form->approveForm()){
				echo 1;
			}
		}elseif($type=='cancle')
		{
			$result=FrmPurchase::model()->with('baseform')->findByAttributes(array('frm_contract_id'=>$id,'purchase_type'=>'normal'));			
			if($result)
			{
				echo "已生成采购单，不能取消";
				return;
			}
			if($form->cancelApproveForm()){
				echo 1;
			}
		}elseif($type=='deny')
		{
			if($form->refuseForm()){
				echo 1;
			}
		}
	} 
	
	/*
	 * 履约/取消履约
	 */
	public function actionFinished($id)
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
		$type = $_REQUEST['type'];
		$form=new Contract($id);
		if ($type == "finish"){//履约
			$result =  $form->finished();
			if($result==='3')
			{
				echo '有未审单的采购单,不能履约';
			}elseif($result==='nolink'){
				echo '没有关联此合同的采购单，不能履约';
			}elseif($result){
				echo 1;
			}
			
		}else if ($type == "cancel"){//取消履约
			if($form->cancelfinished()){
				echo 1;
			}
		}
		
	}
	/*
	 * 前台获取数据的借口
	 */
	public function actionGetTeamUser()
	{
		$id=$_REQUEST['team_id'];
		$result=Team::getUsers($id);
		echo  $result;
	}
	public function actionGetUserTeam()
	{
		$owner=$_REQUEST['owner'];
		$team=User::model()->findByPk($owner)->team_id;
		echo $team;
	}	
	public function actionGetVendorCont()
	{
		$id=$_REQUEST['vendor_id'];
		$result=CompanyContact::getConts($id);
		echo $result;
	}
	public function actionGetUnitWeight()
	{
		$result=DictGoods::getUnitWeight($_GET);
		echo $result;
	}
	
	public function actionGetDetailData()
	{
		$id=$_REQUEST['id'];
		$result=FrmPurchaseContract::getDetailData($id);
// 		list($id_product,$id_texture,$id_brand,$id_rank)=proListId($result);
		$products_array=DictGoodsProperty::getProList('product','array','all');		//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		$this->renderPartial('simpleDetailList',array(
				'data'=>$result,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'ranks'=>$ranks_array,
		));
	}
	public function actionGiveContData()
	{
		$id=$_REQUEST['id'];
		$result=FrmPurchaseContract::giveContData($id);
		echo $result;
	}
	
	public function actionHaveContract()
	{
		$title=$_REQUEST['title'];
		$vendor=$_REQUEST['vendor'];
		$model=FrmPurchaseContract::model()->with(array('baseform'=>array('condition'=>'baseform.form_status="approve"')))->findByAttributes(array('dict_title_id'=>$title,'dict_company_id'=>$vendor,'is_finish'=>0));
		if($model)
		{
			echo true;
		}else{
			echo false;
		}
	}
	
	
	
	/*
	 * 合同报表
	 */
	public function actionDataTable()
	{
		
		$this->pageTitle = "合同汇总";
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}		
		//获取表单列表
		list($tableHeader,$tableData,$pages)=FrmPurchaseContract::gatherData($search);
		
		$this->render('data',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'coms'=>$coms,
				'vendors'=>$vendor_array,
		));
	}
	
	//采购合同打印
	public function actionPrint($id)
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$model = $baseform->contract;
		$details = $model->purchaseContractDetails;
	
		$this->renderPartial('print', array(
				'baseform' => $baseform,
				'model' => $model,
				'details' => $details,
		));
	}
	
	
}
