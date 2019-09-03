<?php
//船舱入库，单独拿出来
class InputCcrkController extends AdminBaseController
{
	
	/*
	 * 入库单列表
	 */
	public function actionIndex()
	{		
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['search_url'])){
			$search=(Array)json_decode($_REQUEST['search_url']);
		}else{
			if(isset($_REQUEST['search_dan'])&&$_REQUEST['search_dan']!='')
			{
				$search['keywords']=$_REQUEST['search_dan'];
			}
			$search['input_status']='-1';
		}
		
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		$warehouse=Warehouse::getWareList('array');
		
		$search['input_type']='ccrk';
		$search=updateSearch($search,'search_inputccrk');
		
		list($tableHeader,$tableData,$pages,$totalData)=FrmInput::getInputList($search);
		$this->pageTitle="船舱入库单列表";
		$this->setHome = 1;//允许设为首页
		$view='index';
		$param=array(
				'search'=>$search,
				'type'=>'',
				'pages'=>$pages,
				'coms'=>$coms,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
				'warehouse'=>$warehouse,
		);		
		$this->render($view,$param);
	}
	
	/*
	 * 查看
	 */
	public  function actionView($id)
	{
		$type='ccrk';
		$fpage=$_REQUEST['fpage']?$_REQUEST['fpage']:1;
		
		$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.purchase')->findByPk($id);
		$input=$baseform->input;
		$inputDetails=$baseform->input->inputDetails;
		$baseform_pur=$baseform->input->baseform_pur;
		$purchase=$baseform->input->baseform_pur->purchase;
		$this->pageTitle='查看入库单'.$baseform->form_sn;
		$view="view";
		$param=array(
				'input_type'=>$type,
				'baseform'=>$baseform,
				'input'=>$input,
				'details'=>$inputDetails,
				'baseform_pur'=>$baseform_pur,
				'purchase'=>$purchase,
				'fpage'=>$fpage,
		);
		
		$this->render($view,$param);
	}
	
	/*
	 * 创建入库单
	 */
// 	public function actionCreate()
// 	{
// 		$id=$_REQUEST['id'];
// 		$type=$_REQUEST['type'];
// 		$this->pageTitle="创建船舱入库单";
// 		if(isset($_POST['CommonForms']))
// 		{			
// 			$data=FrmInput::getInputData($_POST);
// 			$form=new Input($id,$type);
// 			$form->createForm($data);
// 			if($data['common']->submit=="yes")
// 			{
// 				$form->submitForm();
// 				$form->approveForm();
// 			}
// 			$this->redirect('../index?type='.$type);
// 		}		
// 		$ven_array=DictCompany::getVendorList('json','is_customer');
// 		$vendor_array=DictCompany::getVendorList('json');//供应商
// 		$user_array=User::getUserList();

// 		$baseform=CommonForms::model()->with('purchase','purchase.purchaseDetails')->findByPk($id);
// 		$purchase=$baseform->purchase;
// 		$details=$purchase->purchaseDetails;
// 		$view="create";
// 		$param=array(
// 				'baseform'=>$baseform,
// 				'purchase'=>$purchase,
// 				'details'=>$details,
// 				'type'=>$type,
// 				'vens'=>$ven_array,
// 				'vendors'=>$vendor_array,
// 				'users'=>$user_array,
// 		);		
// 		$this->render($view,$param);
// 	}
	
	
	/*
	 * 通过仓库推送信息创建入库单
	 */
	public function actionCreateByPush($plan_id,$push_id)
	{
		$type='purchase';
		$plan=FrmInputPlan::model()->with('basepurchase','basepurchase.purchase','basepurchase.purchase.purchaseDetails')->findByPk($plan_id);
		if($plan->input_type=='ccrk')
		{
			//是船舱入库的话，修改之前的入库计划和入库单
			$result=Input::relStoreByPush($plan_id, $push_id);
			if($result)
			{
				echo "<script>alert('船舱入库真实入库成功');setTimeout('',100);window.location.href='/index.php/input/pushedList';</script>";
			}else{
				echo "<script>alert('入库失败');setTimeout('',100);window.location.href='/index.php/input/pushedList';</script>";
			}
		}
		if(isset($_POST['CommonForms']))
		{
			$data=FrmInput::getPushInputData($_POST);
			$form=new Input($id,$type);
			$form->createForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
				$form->approveForm();
			}
			$this->redirect('index?type='.$type);
		}
		$comm=$plan->basepurchase;
		$purchase=$comm->purchase;
		$details=$purchase->purchaseDetails;
		$pushedStorage=PushedStorage::model()->with('pushedStorageDetails')->findByPk($push_id);
		$pushDetails=$pushedStorage->pushedStorageDetails;
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		$view="createByPush";
		$param=array(
				'baseform'=>$comm,
				'purchase'=>$purchase,
				'details'=>$pushDetails,
				'storage'=>$pushedStorage,
				'vens'=>$ven_array,
				'vendors'=>$vendor_array,
				'users'=>$user_array,
				'push_id'=>$push_id,
		);
		$this->render($view,$param);
	}
	
	/*
	 * 更新通过仓库推送信息创建的入库单
	 */
	public function actionUpdateByPush($id)
	{
		$type='ccrk';
		if(isset($_REQUEST['CommonForms']))
		{
			$data=FrmInput::getUpdateData($_POST);
			$form=new Input($id,$type);
			$form->updateForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
				$form->approveForm();
			}
			$this->redirect('../index?input_type='.$type);
		}
		
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.purchase')->findByPk($id);
		$this->pageTitle="修改入库单".$baseform->form_sn;
		$input=$baseform->input;
		$inputDetails=$baseform->input->inputDetails;
		$baseform_pur=$baseform->input->baseform_pur;
		$purchase=$baseform->input->baseform_pur->purchase;
		$view="updateByPush";
		$param=array(
				'type'=>$type,
				'baseform'=>$baseform,
				'input'=>$input,
				'details'=>$inputDetails,
				'baseform_pur'=>$baseform_pur,
				'purchase'=>$purchase,
				'vens'=>$ven_array,
				'vendors'=>$vendor_array,
				'users'=>$user_array,
		);
		$this->render($view,$param);
	}
	

	
	public  function actionUpdate($id)
	{
		$type='ccrk';
		if(isset($_REQUEST['CommonForms']))
		{
			$_POST['FrmInput']['is_cc']=1;
			$data=FrmInput::getUpdateData($_POST);			
			$form=new Input($id,$type);
			$form->updateForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
				$form->approveForm();
			}
			$this->redirect('../index?input_type='.$type);
		}
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		
		$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.purchase')->findByPk($id);
		$this->pageTitle='修改船舱入库单'.$baseform->form_sn;
		$input=$baseform->input;
		$inputDetails=$baseform->input->inputDetails;
		$baseform_pur=$baseform->input->baseform_pur;
		$purchase=$baseform->input->baseform_pur->purchase;
		$view="update";
		if($baseform->form_status=='approve')
		{
			$view='inputed_update';
		}
		$param=array(
			'input_type'=>$type,
			'baseform'=>$baseform,
			'input'=>$input,
			'details'=>$inputDetails,
			'baseform_pur'=>$baseform_pur,
			'purchase'=>$purchase,		
			'vens'=>$ven_array,
			'vendors'=>$vendor_array,
			'users'=>$user_array,
		);
		
		$this->render($view,$param);
	}
	
	/*
	 * 船舱入库真实入库
	 */
	public function actionRelStore($id)
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
		$type='ccrk';
		if(isset($_REQUEST['CommonForms']))
		{
			$data=FrmInput::getRelStoreData($_POST);
			$form=new Input($id,$type);
			$form->relStore($data);
			$this->redirect('../index?input_type='.$type);
		}
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		
		$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.purchase')->findByPk($id);
		$input=$baseform->input;
		$this->pageTitle="真实入库".$baseform->form_sn;
		$inputDetails=$baseform->input->inputDetails;
		$baseform_pur=$baseform->input->baseform_pur;
		$purchase=$baseform->input->baseform_pur->purchase;
		$view="relstore";
		$param=array(
				'baseform'=>$baseform,
				'input'=>$input,
				'details'=>$inputDetails,
				'baseform_pur'=>$baseform_pur,
				'purchase'=>$purchase,
				'vens'=>$ven_array,
				'vendors'=>$vendor_array,
				'users'=>$user_array,
		);
		$this->render($view,$param);		
	}
	
		
	/*
	 * 提交与取消提交
	 */
	public function actionSubmit($id,$type)
	{
		$ty=$_REQUEST['ty'];
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
		$form=new Input($id,$ty);
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
		$ty=$_REQUEST['ty'];
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
		$form=new Input($id,$ty);
		//
		if($form->commonForm->form_status=='submited')
		{
			$form->cancelSubmitForm();
		}
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
		$ty=$_REQUEST['ty'];
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
		$form=new Input($id,$ty);
		if($type=='pass')
		{
			if($form->commonForm->form_status=='unsubmit')
			{
				$form->submitForm();
			}
			if($form->approveForm()){
				echo 1;
			}
		}elseif($type=='cancle')
		{
			$result=$form->cancelApproveForm();
			if($result==='sale')
			{
				echo '已开过销售单,不能取消';
			}elseif($result==='bill')
			{
				echo '已开票，不能取消';
			}elseif($result){
				$form->cancelSubmitForm();
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
	 * 查看仓库推送数据
	 */
	public function actionViewPush($id)
	{
		$fpage=$_REQUEST['fpage']?$_REQUEST['fpage']:1;
		$push=PushedStorage::model()->with('pushedStorageDetails','inputCompany','ownerCompany','inputPlan')->findByPk($id);
		$details=$push->pushedStorageDetails;
		$this->pageTitle='查看推送单';
		$view="viewpush";
		$param=array(
				'push'=>$push,
				'details'=>$details,
				'fpage'=>$fpage,
		);		
		$this->render($view,$param);
	}

	
}