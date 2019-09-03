<?php
class InputPlanController extends AdminBaseController
{
	/*
	 * 入库计划列表
	 */
	public function actionIndex()
	{
		$this->pageTitle="入库计划列表";
		$this->setHome = 1;//允许设为首页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['search_dan']))
		{
			$search['keywords']=$_REQUEST['search_dan'];
		}elseif (isset($_REQUEST['search_url'])){
			$search=(Array)json_decode($_REQUEST['search_url']);
		}	
		$search=updateSearch($search,'search_inputplan');
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
	
		list($tableHeader,$tableData,$pages,$totalData)=FrmInputPlan::getPlanList($search);
		$view='index';
		$param=array(
				'search'=>$search,
				'type'=>'',
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
		$this->render($view,$param);
	
	}
	/*
	 * 查看
	 */
	public function actionView($id)
	{
		$fpage=$_REQUEST['fpage']?$_REQUEST['fpage']:1;
		$baseform=CommonForms::model()->with('inputplan','inputplan.inputDetailsPlan','inputplan.basepurchase','inputplan.basepurchase.purchase')->findByPk($id);
		$inputPlan=$baseform->inputplan;
		$purchase=$baseform->inputplan->basepurchase->purchase;
		$basePurchase=$inputPlan->basepurchase;
		$details=$inputPlan->inputDetailsPlan;
		$this->pageTitle="查看入库计划".$baseform->form_sn;
		$view='view';
		$backUrl=$_REQUEST['backUrl'];
		$param=array(
				'baseform'=>$baseform,
				'basePurchase'=>$basePurchase,
				'purchase'=>$purchase,
				'inputPlan'=>$inputPlan,
				'details'=>$details,
				'type'=>$type,
				'fpage'=>$fpage,
				'backUrl'=>$backUrl,
		);
		$this->render($view,$param);
	}
	
	/*
	 * 创建入库计划
	 */
	public function actionCreate()
	{
		$this->pageTitle="创建入库计划";
		if($_REQUEST['type']=='ccrk')
		{
			$this->pageTitle="创建船舱入库";
		}
		if(isset($_POST['CommonForms']))
		{
			$data=FrmInputPlan::getInputData($_POST);
			$form=new InputPlan($id);
			$form->createForm($data);
			if($data['main']->input_type=='ccrk')
			{
				$this->redirect('/index.php/inputCcrk/index?input_type=ccrk');
			}else{
				$this->redirect('/index.php/inputPlan/index');
			}			
		}
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		if($_REQUEST['purchase_common_id'])
		{	
			$baseform=CommonForms::model()->with('purchase','purchase.purchaseDetails')->findByPk(intval($_REQUEST['purchase_common_id']));
			$purchase=$baseform->purchase;
			$details=$purchase->purchaseDetails;
			$view="create";
			$param=array(
					'baseform'=>$baseform,
					'purchase'=>$purchase,
					'details'=>$details,
					'vens'=>$ven_array,
					'vendors'=>$vendor_array,
					'users'=>$user_array,
			);
		}else{
			$view="create";
			$param=array(
					'vens'=>$ven_array,
					'vendors'=>$vendor_array,
					'users'=>$user_array,
			);
		}		
		$this->render($view,$param);
	}
	/*
	 * 更改
	 */
	public function actionUpdate($id)
	{
		
		if(isset($_POST['CommonForms']))
		{
			$data=FrmInputPlan::getInputData($_POST);
			$form=new InputPlan($id);
			$form->updateForm($data);
// 			if($data['common']->submit=="yes")
// 			{
				$form->push();
// 			}
			$this->redirect('../index');
		}
		$ven_array=DictCompany::getVendorList('json','is_customer');
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$user_array=User::getUserList();
		$baseform=CommonForms::model()->with('inputplan','inputplan.inputDetailsPlan','inputplan.basepurchase','inputplan.basepurchase.purchase')->findByPk($id);
		$inputPlan=$baseform->inputplan;
		$purchase=$baseform->inputplan->basepurchase->purchase;
		$basePurchase=$inputPlan->basepurchase;
		$details=$inputPlan->inputDetailsPlan;
		$this->pageTitle="修改入库计划".$baseform->form_sn;
		$view='update';
		$param=array(
				'baseform'=>$baseform,
				'basePurchase'=>$basePurchase,
				'purchase'=>$purchase,
				'inputPlan'=>$inputPlan,
				'details'=>$details,
				'type'=>$type,
				'vens'=>$ven_array,
				'vendors'=>$vendor_array,
				'users'=>$user_array,
		);
		$this->render($view,$param);
	}
	
	/*
	 * 作废入库计划
	 */
	public function actionDeletePlan($id)
	{
		$form=new InputPlan($id);
		$reson=$_REQUEST['str'];
		if($form->beforeDeleteForm($reson)){
			echo 1;
			return;
		}
		echo 0;
	
	}
	
	/*
	 * 作废信息返回调用接口
	 */
	public function actionDeleteOrNot()
	{
	
	}
	
	/*
	 * 推送
	 */
	public function actionPush()
	{
	
	}
	
	/*
	 * 完成
	 */
	public function actionFinish($id)
	{
		$form=new InputPlan($id);
		if($form->finish()){
			echo 1;
			return;
		}
		echo 0;
	}
}