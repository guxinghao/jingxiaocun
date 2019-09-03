<?php
class InputController extends AdminBaseController
{
	
	/*
	 * 入库单列表
	 */
	public function actionIndex()
	{
		$type=$_REQUEST['type'];
		$this->setHome = 1;//允许设为首页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['search_url']))
		{
			$search=(Array)json_decode($_REQUEST['search_url']);
		}
		$search=updateSearch($search,'search_input');
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		$warehouse=Warehouse::getWareList('array');
		
		if($type=="dxrk")
		{
			$this->pageTitle="代销入库单列表";
			$view='indexdxrk';
			list($tableHeader,$tableData,$pages,$totalData)=FrmInputDx::getInputList($search);
			$param=array(
					'search'=>$search,
					'type'=>'dxrk',
					'pages'=>$pages,
					'coms'=>$coms,
					'vendors'=>$vendor_array,
					'brands'=>$brands_array,
					'teams'=>$team_array,
					'users'=>$user_array,
					'products'=>$products_array,
					'rands'=>$ranks_array,
					'textures'=>$textures_array,
					'tableHeader'=>$tableHeader,
					'tableData'=>$tableData,
					'totalData'=>$totalData,
			);
		}else{
			if(isset($_REQUEST['input_type']))
			{
				$search['input_type']=$_REQUEST['input_type'];
			}
			list($tableHeader,$tableData,$pages,$totalData)=FrmInput::getInputList($search);
			$this->pageTitle="入库单列表";
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
		}		
		$this->render($view,$param);
	}
	
	/*
	 * 查看
	 */
	public  function actionView($id)
	{
		$type=$_REQUEST['type'];
		$fpage=$_REQUEST['fpage']?$_REQUEST['fpage']:1;
		if($type=="dxrk")
		{		
			$baseform=CommonForms::model()->with('inputdx','inputdx.inputDetailsDx')->findByPk($id);
			$input=$baseform->inputdx;
			$details=$input->inputDetailsDx;
			$this->pageTitle="查看代销入库单".$baseform->form_sn;
			$view="viewdxrk";
			$param=array(
					'baseform'=>$baseform,
					'input'=>$input,
					'details'=>$details,
					'type'=>'dxrk',
					'fpage'=>$fpage,
				);
		}else{
			$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.purchase')->findByPk($id);
			$input=$baseform->input;
			$inputDetails=$baseform->input->inputDetails;
			$baseform_pur=$baseform->input->baseform_pur;
			$purchase=$baseform->input->baseform_pur->purchase;
			$this->pageTitle='查看入库单'.$baseform->form_sn;
			$view="view";
			$param=array(
					'type'=>$type,
					'baseform'=>$baseform,
					'input'=>$input,
					'details'=>$inputDetails,
					'baseform_pur'=>$baseform_pur,
					'purchase'=>$purchase,
					'fpage'=>$fpage,
			);
		}
		$this->render($view,$param);
	}
	
	/*
	 * 创建入库单
	 */
	public function actionCreate()
	{
		$id=$_REQUEST['id'];
		$type=$_REQUEST['type'];
		$from=$_REQUEST['from'];
		$this->pageTitle="创建入库单";
		if(isset($_POST['CommonForms']))
		{
			if($type=="dxrk")
			{
				$data=FrmInputDx::getInputData($_POST);
			}else{
				$data=FrmInput::getInputData($_POST);
			}
			$form=new Input($id,$type);
			$form->createForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
				$form->approveForm();
			}
			$this->redirect('../index?type='.$type);
		}		
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		if($type=="dxrk")
		{
			$this->pageTitle="创建代销入库单";
			$coms=DictTitle::getComs('json');//下拉菜单数据
			$products_array=DictGoodsProperty::getProList('product');//1品名
			$textures_array=DictGoodsProperty::getProList('texture');//2材质
			$brands_array=DictGoodsProperty::getProList('brand','json');		//3产地
			$ranks_array=DictGoodsProperty::getProList('rank');//4规格
			$contacts_array=CompanyContact::getContactList();//联系人
			$warehouse_array=Warehouse::getWareList('json');//仓库
			$team_array=Team::getTeamList('array');//业务组
			$view="createdxrk";		
			$param=array('type'=>'dxrk','vens'=>$ven_array,'vendors'=>$vendor_array,'users'=>$user_array,'teams'=>$team_array,
					'coms'=>$coms,	'products'=>$products_array,'textures'=>$textures_array,'brands'=>$brands_array,'ranks'=>$ranks_array,
					'contacts'=>$contacts_array,'warehouses'=>$warehouse_array,
			);
		}else{
			$baseform=CommonForms::model()->with('purchase','purchase.purchaseDetails')->findByPk($id);
			$purchase=$baseform->purchase;
			$details=$purchase->purchaseDetails;
			$view="create";
			$param=array('baseform'=>$baseform,'purchase'=>$purchase,'details'=>$details,'type'=>$type,'vens'=>$ven_array,
					'vendors'=>$vendor_array,'users'=>$user_array,);
		}
		$this->render($view,$param);
		
	}
	
	/*
	 * 通过退货单创建
	 */
	public function actionCreateByReturn()
	{
		$id=$_REQUEST['id'];
		$type=$_REQUEST['type'];
		$from=$_REQUEST['from'];
		$this->pageTitle="创建入库单";
		if(isset($_POST['CommonForms']))
		{
			$data=FrmInput::getInputData($_POST);
			$form=new InputForReturn($id);
			$form->createForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
				$form->approveForm();
			}
			$this->redirect('/index.php/input/index');
		}
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$coms=DictTitle::getComs('json');
		$user_array=User::getUserList();
		
		$baseform=CommonForms::model()->with('salesReturn','salesReturn.salesReturnDetails')->findByPk($id);
		$purchase=$baseform->salesReturn;
		$details=$purchase->salesReturnDetails;
		$view='createByReturn';
		$param=array('baseform'=>$baseform,'purchase'=>$purchase,'details'=>$details,'type'=>$type,'vens'=>$ven_array,'vendors'=>$vendor_array,
				'users'=>$user_array,'coms'=>$coms,
		);
		$this->render($view,$param);
	}
	
	/*
	 * 更新通过销售退货单创建的入库单
	 */
	public function actionUpdateByReturn($id)
	{
		$type=$_REQUEST['type'];
		$fpage=$_REQUEST['fpage'];
		if(isset($_REQUEST['CommonForms']))
		{
			$data=FrmInput::getUpdateData($_POST);
			$form=new InputForReturn($id);
			$form->updateForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
				$form->approveForm();
			}
			$this->redirect('/index.php/input/index?page='.$fpage);
		}
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$coms=DictTitle::getComs('json');
		$user_array=User::getUserList();

		$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.salesReturn')->findByPk($id);
		$this->pageTitle='修改入库单'.$baseform->form_sn;
		$input=$baseform->input;
		$inputDetails=$baseform->input->inputDetails;
		$baseform_pur=$baseform->input->baseform_pur;
		$purchase=$baseform->input->baseform_pur->salesReturn;
		$view="updateByReturn";
		$param=array('type'=>$type,'baseform'=>$baseform,'input'=>$input,'details'=>$inputDetails,'baseform_pur'=>$baseform_pur,
				'purchase'=>$purchase,'vens'=>$ven_array,'vendors'=>$vendor_array,'coms'=>$coms,'users'=>$user_array,);
		$this->render($view,$param);
	}
	
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
			if($result===true)
			{
				echo "<script>alert('船舱入库真实入库成功');setTimeout('',100);window.location.href='/index.php/input/pushedList';</script>";
			}else{
				echo "<script>alert('".$result."');setTimeout('',100);window.history.go(-1);</script>";
				die;
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
		$type=$_REQUEST['type'];
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
			$this->redirect('../index?type='.$type);
		}
		
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.purchase')->findByPk($id);
		$input=$baseform->input;
		$this->pageTitle="修改入库单".$baseform->form_sn;
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

// 	/*
// 	 * 在入库计划处创建入库单
// 	 */
// 	public function actionCreateByPlan($plan_id)
// 	{
// 		$type='purchase';
// 		$plan=FrmInputPlan::model()->with('basepurchase','basepurchase.purchase','inputDetailsPlan','inputDetailsPlan.purchaseDetail')->findByPk($plan_id);
// 		if(isset($_POST['CommonForms']))
// 		{
// 			$data=FrmInput::getPushInputData($_POST);
// 			$form=new Input($id,$type);
// 			$form->createForm($data);
// 			if($data['common']->submit=="yes")
// 			{
// 				$form->submitForm();
// 				$form->approveForm();
// 			}
// 			$this->redirect('index?type='.$type);
// 		}
// 		$comm=$plan->basepurchase;
// 		$purchase=$comm->purchase;
// 		$details=$plan->inputDetailsPlan;
// 		$ven_array=DictCompany::getVendorList('json','is_customer');
// 		$vendor_array=DictCompany::getVendorList('json');//供应商
// 		$user_array=User::getUserList();
// 		$view="createByPlan";
// 		$param=array(
// 				'baseform'=>$comm,
// 				'purchase'=>$purchase,
// 				'details'=>$details,
// 				'vens'=>$ven_array,
// 				'vendors'=>$vendor_array,
// 				'users'=>$user_array,
// 				'push_id'=>$push_id,
// 		);
// 		$this->render($view,$param);
// 	}
	
	
	
	public  function actionUpdate($id)
	{
		$type=$_REQUEST['type'];
		if(isset($_REQUEST['CommonForms']))
		{
			if($type=="dxrk")
			{
				$data=FrmInputDx::getUpdateData($_POST);
			}else{
				$data=FrmInput::getUpdateData($_POST);
			}
			$form=new Input($id,$type);
			$form->updateForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
				$form->approveForm();
			}
			$this->redirect('../index?type='.$type);
		}
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		if($type=="dxrk")
		{
			$baseform=CommonForms::model()->with('inputdx','inputdx.inputDetailsDx')->findByPk($id);
			$this->pageTitle="修改代销入库单".$baseform->form_sn;
			$input=$baseform->inputdx;
			$details=$input->inputDetailsDx;
			$coms=DictTitle::getComs('json');//下拉菜单数据
			list($id_product,$id_texture,$id_brand,$id_rank)=proListId($details);
			$products_array=DictGoodsProperty::getProList('product','array',$id_product);		//1品名
			$textures_array=DictGoodsProperty::getProList('texture','array',$id_texture);//2材质
			$brands_array=DictGoodsProperty::getProList('brand','json',$id_brand);//3产地
			$ranks_array=DictGoodsProperty::getProList('rank','array',$id_rank);//4规格
			$contacts_array=CompanyContact::getContactList();//联系人
			$warehouse_array=Warehouse::getWareList('json');//仓库
			$team_array=Team::getTeamList('array');//业务组
			$view="updatedxrk";
			$param=array(
					'baseform'=>$baseform,
					'input'=>$input,
					'details'=>$details,
					'type'=>'dxrk',
					'vens'=>$ven_array,
					'vendors'=>$vendor_array,
					'users'=>$user_array,
					'teams'=>$team_array,
					'coms'=>$coms,
					'products'=>$products_array,
					'textures'=>$textures_array,
					'brands'=>$brands_array,
					'ranks'=>$ranks_array,
					'contacts'=>$contacts_array,
					'warehouses'=>$warehouse_array,
			);
		}else{
			$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.purchase')->findByPk($id);
			$this->pageTitle='修改入库单'.$baseform->form_sn;
			$input=$baseform->input;
			$inputDetails=$baseform->input->inputDetails;
			$baseform_pur=$baseform->input->baseform_pur;
			$purchase=$baseform->input->baseform_pur->purchase;
			$view="update";
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
		}
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
		$type=$_REQUEST['type'];
		if(isset($_REQUEST['CommonForms']))
		{
			$data=FrmInput::getRelStoreData($_POST);
			$form=new Input($id,$type);
			$form->relStore($data);
			$this->redirect('../index?type='.$type);
		}
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		
		$baseform=CommonForms::model()->with('input','input.baseform_pur','input.inputDetails','input.baseform_pur.purchase')->findByPk($id);
		$input=$baseform->input;
		$this->pageTitle='真实入库'.$baseform->form_sn;
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
		$baseform=CommonForms::model()->with('input')->findByPk($id);
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
		if($baseform->input->input_type=='thrk')
		{
			$form=new InputForReturn($id);
		}else{
			$form=new Input($id,$ty);
		}		
		if($type=='pass')
		{
			if($form->commonForm->form_status=='unsubmit')
			{
				$form->submitForm();
			}
			$res=$form->approveForm();
			if($res===true){
				echo 1;
			}else{
				echo $res; 
			}
		}elseif($type=='cancle')
		{
			//判断是否已审单
			$main=$form->mainInfo;
			if(isset($main->purchase_id))
			{
				if($main->input_type=='thrk')
				{
					$rel=$main->baseform_pur->salesReturn;
				}elseif($main->input_type=='purchase'){
					$rel=$main->baseform_pur->purchase;
				}
				if($rel->weight_confirm_status==1)
				{
					echo '已经审单，不能取消入库';
					return;
				}
			}
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
	 * 入库前看仓库有没有同样的卡号
	 */
	public function actionBeforeCheck($id)
	{
		$model=CommonForms::model()->with()->findByPk($id);		
		$type=$_REQUEST['type'];
		if($model)
		{
			$flag=true;
			if($type=='dx')
			{
				$input=$model->inputdx;
				$details=$model->inputdx->inputDetailsDx;
			}else{
				$input=$model->input;
				if($input->input_type=='thrk'){goto flag;}
				$details=$model->input->inputDetails;				
			}			
			foreach ($details as $each)
			{
				$result=Storage::getStroageid($each->card_id, $input->warehouse_id);
				if($result)
				{
					$flag=false;
					echo '卡号'.$each->card_id.'重复，请修改';
					break;
				}
			}
			flag:
			if($flag){echo 1;}
		}else{
			echo 'error';
		}
	}
	/*
	 * 仓库推送数据
	 */
	public function actionPushedList()
	{
		$this->pageTitle='仓库入库列表';
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=sortRank(DictGoodsProperty::getProList('rank','array','all'));//4规格
		$contacts_array=CompanyContact::getContactList();//联系人
		$warehouse_array=Warehouse::getWareList('json');//仓库
		$team_array=Team::getTeamList('json');//业务组
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['search_url'])){
			$search=(Array)json_decode($_REQUEST['search_url']);
		}elseif(isset($_REQUEST['form_sn'])){
			if($_REQUEST['form_sn']=='no')
			{
				$search['model_id']=$_REQUEST['model_id'];
			}else{
				$search['keywords']=$_REQUEST['form_sn'];
			}			
		}
		list($tableHeader,$tableData,$pages)=PushedStorage::pushList($search);
		$this->render('pushedlist',array(
				'vendors'=>$vendor_array,
				'users'=>$user_array,
				'teams'=>$team_array,
				'coms'=>$coms,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'rands'=>$ranks_array,
				'contacts'=>$contacts_array,
				'warehouses'=>$warehouse_array,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
		));
	}
	
	
	/*
	 * 手动完成推送数据
	 */
	public function actionCompletePush($push_id)
	{
		$model=PushedStorage::model()->findByPk($push_id);
		if($model)
		{
			$model->input_status=1;
			if($model->update())
			{
				echo 1;
			}else{
				echo 0;
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