<?php
class PledgeController extends AdminBaseController
{
	public function actionView($id)
	{
		$baseform=CommonForms::model()->with('frmPledge')->findByPk($id);
		if($baseform)
		{
			$this->pageTitle = "查看托盘赎回单".$baseform->form_sn;
			$frmPledge=$baseform->frmPledge;
		}else{
			return false;
		}
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		$url='view';
		$arr=array('baseform'=>$baseform,'frmPledge'=>$frmPledge,'fpage'=>$fpage);
		$this->render($url,$arr);
	}
	/*
	 * 赎回列表
	 */
	public function actionIndex()
	{
		$this->pageTitle = "托盘管理";
		$coms=DictTitle::getComs('json');
		$vendor_array=DictCompany::getVendorList('json','is_pledge');
		$user_array=User::getUserList();
		$team_array=Team::getTeamList('array');
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		//搜索
		$search=array();
		$backUrl=false;
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['search_dan']))
		{
			$search['keywords']=$_REQUEST['search_dan'];
			$backUrl=Yii::app()->createUrl('pledge/pledgeSearch',array('page'=>$_REQUEST['fpage']));
		}elseif($_REQUEST['card_no'])
		{
			$search['keywords']=$_REQUEST['card_no'];
		}elseif (isset($_REQUEST['search_url'])){
			$search=(Array)json_decode($_REQUEST['search_url']);
		}
		list($tableHeader,$tableData,$pages,$totalData)=FrmPledgeRedeem::getPledgeList($search);
		$this->render('index',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
				'coms'=>$coms,
				'search'=>$search,
				'pages'=>$pages,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'users'=>$user_array,
				'products'=>$products_array,
				'backUrl'=>$backUrl,
		));
	}

	/*
	 * 创建赎回
	 */
	public function actionCreate()
	{
		$this->pageTitle='创建托盘赎回';
		if($_POST['CommonForms'])
		{
			$data=FrmPledgeRedeem::getInputData($_POST);
			if($data){
				$allform=new Pledge($id);
				$allform->createForm($data);
				if($data['common']->submit=="yes")
				{
					$allform->submitForm();
				}
				$this->redirect(Yii::app()->createUrl('pledge/index',array("page"=>$fpage)));
			}
		}
		$user_array=User::getUserList();//表单所属人
		$tpcompany=DictCompany::getVendorList('array',"is_pledge");//托盘公司
		$com_array=DictTitle::getComs('array');//采购公司
		$view='create';
		$param=array('tpcompanys'=>$tpcompany,'titles'=>$com_array,'users'=>$user_array);
		$this->render($view,$param);
	}

	/*
	 * 更新赎回
	 */
	public function actionUpdate($id)
	{
		$baseform=CommonForms::model()->with('frmPledge','frmPledge.purchase')->findByPk($id);
		if($baseform)
		{
			$frmPledge=$baseform->frmPledge;
			$purchase=$frmPledge->purchase;
		}else{
			return false;
		}
		$fpage=$_REQUEST['fpage'];
		$this->pageTitle='修改托盘赎回'.$baseform->form_sn;
		if($_POST['CommonForms'])
		{
			$data=FrmPledgeRedeem::getInputData($_POST);
			if($data){
				$allform=new Pledge($id);
				$allform->updateForm($data);
				if($data['common']->submit=="yes")
				{
					$allform->submitForm();
				}
				$this->redirect(Yii::app()->createUrl('pledge/index',array("page"=>$fpage)));
			}
		}
		$user_array=User::getUserList();//表单所属人
		$tpcompany=DictCompany::getVendorList('array',"is_pledge");//托盘公司
		$com_array=DictTitle::getComs('array');//采购公司
		$view='update';
		$param=array('tpcompanys'=>$tpcompany,'titles'=>$com_array,'users'=>$user_array,'baseform'=>$baseform,'frmPledge'=>$frmPledge,'purchase'=>$purchase);
		$this->render($view,$param);
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
		$form=new Pledge($id);
		if($type=='submit')
		{
			$result=$form->submitForm();
			if($result!==true){
				if($result=='dataerror')
				{
					echo '数据错误';
				}
			}else{
				echo 1;
			}
		}elseif($type=='cancle')
		{
			$result=$form->cancelSubmitForm();
			if($result===true){
				echo 1;
			}elseif($result=='dataerror'){
				echo '数据错误';
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
		$form=new Pledge($id);
		if($form->deleteForm($reason)){
			echo 1;
		}
	}


	/*
	 * 审核相关
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
		$form=new Pledge($id);
		if($type=='pass')
		{
			if($form->approveForm()){
				echo 1;
			}
		}elseif($type=='cancle')
		{
			$result=$form->cancelApproveForm();
			if($result===true)
			{
				echo 1;
			}else{
				echo $result;
			}
		}elseif($type=='deny')
		{
			if($form->refuseForm()){
				echo 1;
			}
		}
	}

	public function actionHaveOut($id)
	{
		$model=FrmPledgeRedeem::model()->with('pledgeInfo')->findByPk($id);
		$pledgeInfo=$model->pledgeInfo;
		if($pledgeInfo->r_limit==1)
		{
			$sql='select * from pledge_redeemed where purchase_id='.$model->purchase_id.' and brand_id='.$model->brand_id;
		}elseif ($pledgeInfo->r_limit==2)
		{
			$sql='select * from pledge_redeemed where purchase_id='.$model->purchase_id.' and brand_id='.$model->brand_id.' and product_id='.$model->product_id;
		}
		$pledge=PledgeRedeemed::model()->findBySql($sql);
		if($pledge)
		{
			$pledge->left_weight-=$model->weight;
			if($pledge->left_weight<0)
			{
				echo 1;
			}else{
				echo 0;
			}
		}
	}


	/*************************/
	/*
	 * 托盘查询
	 * 显示所有托盘采购单信息
	 */
	public function actionPledgeSearch()
	{
		$this->pageTitle = "托盘查询";
		$coms=DictTitle::getComs('json');
		$vendor_array=DictCompany::getVendorList('json','is_pledge');
		$user_array=User::getUserList();
		$team_array=Team::getTeamList('array');
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['search_url'])){
			$search=(Array)json_decode($_REQUEST['search_url']);
		}else{
			if(isset($_REQUEST['title_id']))
			{
				$search['company']=$_REQUEST['title_id'];
				$search['vendor']=$_REQUEST['company_id'];
			}
		}

		list($tableHeader,$tableData,$pages,$totalData)=FrmPledgeRedeem::getTpcgList($search);
		$this->render('tpcgList',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
				'coms'=>$coms,
				'search'=>$search,
				'pages'=>$pages,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'users'=>$user_array,
				'products'=>$products_array,
		));
	}

	/*
	 * 查看采购明细赎回
	 */
	public  function actionDetailPledged()
	{
		$id=$_REQUEST['id'];
		$this->layout='';
		list($tableHeader,$tableData)=FrmPledgeRedeem::getDetailPledged($id);
		$this->render('moreinfo',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
		));
	}


	/*
	 * 托盘汇总
	 */
	public function actionDataTable()
	{
		$this->pageTitle = "托盘汇总";
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json','is_pledge');//供应商

		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		var_dump(FrmPledgeRedeem::gatherData($search));die();
		//获取表单列表
		list($tableHeader,$tableData,$pages)=FrmPledgeRedeem::gatherData($search);

		$this->render('data',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'coms'=>$coms,
				'vendors'=>$vendor_array,
		));
	}



}
