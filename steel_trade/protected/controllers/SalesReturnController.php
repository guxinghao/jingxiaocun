<?php
class SalesReturnController extends AdminBaseController
{
	/*
	 * 查看
	 */
	public function actionView($id)
	{
		$baseform=CommonForms::model()->with('salesReturn','salesReturn.salesReturnDetails')->findByPk($id);
		if($baseform)
		{
			$this->pageTitle = "查看销售退货单".$baseform->form_sn;
			$salesReturn=$baseform->salesReturn;
			$details=$salesReturn->salesReturnDetails;
		}else{
			return false;
		}
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		$url='view';
		$backUrl=$_REQUEST['backUrl'];
		$arr=array('baseform'=>$baseform,'salesReturn'=>$salesReturn,'details'=>$details,'fpage'=>$fpage,'backUrl'=>$backUrl);
		$this->render($url,$arr);
	}
	
	/*
	 * 列表页
	 */
	public  function actionIndex()
	{
		$this->pageTitle = "销售退货管理";
		$coms=DictTitle::getComs('json');
		$vendor_array=DictCompany::getVendorList('json','is_customer');
		$user_array=User::getUserList();
		$team_array=Team::getTeamList('array');
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array','all');//4规格
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif($_REQUEST['card_no'])
		{
			$search['keywords']=$_REQUEST['card_no'];
		}elseif(isset($_REQUEST['search_url']))
		{
			$search=(Array)json_decode($_REQUEST['search_url']);
		}
		$search=updateSearch($search,'search_salesreturn');
		list($tableHeader,$tableData,$pages,$totalData)=FrmSalesReturn::getReturnList($search);
		$this->render('index',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
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
	 * 创建
	 */
	public function actionCreate()
	{
		$this->pageTitle="创建销售退货单";
		if(isset($_POST['CommonForms']))
		{
			$data=FrmSalesReturn::getInputData($_POST);
			$form=new SalesReturn($id);
			$form->createForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
			}
			$this->redirect('/index.php/salesReturn/index');
		}
		$user_array=User::getUserList();//表单所属人
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$ven_array=DictCompany::getVendorList('json','is_customer');//客户
		$tpcompany=DictCompany::getVendorList('array',"is_pledge");//托盘公司
		$com_array=DictTitle::getComs('json');//采购公司
		$team_array=Team::getTeamList('array');//业务组
		$warehouse_array=Warehouse::getWareList('json');//仓库
		$warehouse_array1=Warehouse::getWareList('array');
		$contacts_array=CompanyContact::getContactList();//联系人
		$gkvendor=DictCompany::getVendorList("json","is_gk");
		//根据品名，规格，材质，产地来选择商品
		$products_array=DictGoodsProperty::getProList('product');		//1品名
		$textures_array=DictGoodsProperty::getProList('texture');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json');//3产地
		$ranks_array=DictGoodsProperty::getProList('rank');//4规格
		$view='create';
		$param=array(
				'users'=>$user_array,
				'vendors'=>$vendor_array,
				'vens'=>$ven_array,
				'coms'=>$com_array,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'ranks'=>$ranks_array,
				'contacts'=>$contacts_array,
				'tpcompanys'=>$tpcompany,
				'fpage'=>$fpage,
				'gkvendor'=>$gkvendor
		);	
		
		$this->render($view,$param);		
	}
	
	/*
	 * 修改
	 */
	public function actionUpdate($id)
	{
		
		$baseform=CommonForms::model()->with('salesReturn','salesReturn.salesReturnDetails')->findByPk($id);
		if($baseform)
		{
			$salesReturn=$baseform->salesReturn;
			$details=$salesReturn->salesReturnDetails;
		}else{
			return false;
		}
		$this->pageTitle = "修改销售退货单".$baseform->form_sn;
		$last_update=$_REQUEST['last_update'];
		if($last_update!=$baseform->last_update)
		{
			echo "<script>alert('您看到的信息不是最新的，请刷新后再试');setTimeout('',500);window.location.href='/index.php/salesReturn/index';</script>";
			die;
		}
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		if(isset($_POST['CommonForms']))
		{
			$data=FrmSalesReturn::getInputData($_POST);
			$form=new SalesReturn($id);
			$form->updateForm($data);
			if($data['common']->submit=="yes")
			{
				$form->submitForm();
			}			
			$this->redirect(Yii::app()->createUrl('salesReturn/index',array('page'=>$fpage)));
		}
		$user_array=User::getUserList();//表单所属人
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$ven_array=DictCompany::getVendorList('json','is_customer');//客户
		$tpcompany=DictCompany::getVendorList('array',"is_pledge");//托盘公司
		$com_array=DictTitle::getComs('json');//采购公司
		$team_array=Team::getTeamList('array');//业务组
		$warehouse_array=Warehouse::getWareList('json');//仓库
		$warehouse_array1=Warehouse::getWareList('array');
		$contacts_array=CompanyContact::getContactList();//联系人
		$gkvendor=DictCompany::getVendorList("json","is_gk");
		//根据品名，规格，材质，产地来选择商品
		$products_array=DictGoodsProperty::getProList('product');		//1品名
		$textures_array=DictGoodsProperty::getProList('texture');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json');//3产地
		$ranks_array=DictGoodsProperty::getProList('rank');//4规格
		if($baseform->form_status=='unsubmit')
		{
			$view='update';
		}elseif($baseform->form_status=='submited'){
			$view='sub_update';
		}else{
			$view='app_update';
		}		
		$param=array(
				'baseform'=>$baseform,
				'salesReturn'=>$salesReturn,
				'details'=>$details,
				'users'=>$user_array,
				'vendors'=>$vendor_array,
				'vens'=>$ven_array,
				'coms'=>$com_array,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'ranks'=>$ranks_array,
				'contacts'=>$contacts_array,
				'tpcompanys'=>$tpcompany,
				'fpage'=>$fpage,
				'gkvendor'=>$gkvendor,
		);
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
		$form=new SalesReturn($id);
		if($type=='submit')
		{
			$result=$form->submitForm();
			if($result!==true){
					echo '数据错误';
			}else{
				echo 1;
			}
		}elseif($type=='cancle')
		{
			$result=$form->cancelSubmitForm();
			if($result===true){
				echo 1;
			}else{
				echo '数据错误';
			}
		}
	}
	
	/*
	 * 作废
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
		$form=new SalesReturn($id);
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
		$form=new SalesReturn($id);
		if($type=='pass')
		{
			if($form->approveForm()){
				echo 1;
			}
		}elseif($type=='cancle')
		{
			//查询是否有入库，有入库的话不能够取消
			$result=FrmInput::model()->with(array('baseform'=>array('condition'=>'is_deleted=0')))->find('purchase_id='.$id);
			if($result)
			{
				echo '已生成入库单，不能取消审核';
			}else{
				if($form->cancelApproveForm()){
					echo 1;
				}
			}
		}elseif($type=='deny')
		{
			if($form->refuseForm()){
				echo 1;
			}
		}
	}
	
	/*
	 * 审单，定价
	 */
	public function actionConfirm($id)
	{
		$baseform=CommonForms::model()->with('salesReturn','salesReturn.salesReturnDetails')->findByPk($id);
		$this->pageTitle = "销售退货单审单".$baseform->form_sn;
		if($baseform)
		{
			$salesReturn=$baseform->salesReturn;
			$details=$salesReturn->salesReturnDetails;
		}else{
			return false;
		}
		$last_update=$_REQUEST['last_update'];
		if($last_update!=$baseform->last_update)
		{
			echo "<script>alert('您看到的信息不是最新的，请刷新后再试');setTimeout('',500);window.location.href='/index.php/salesReturn/index';</script>";
			die;
		}
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		if($_POST['td_weight'])
		{
			$data=FrmSalesReturn::getConfirmData($_POST);
			if($data){
				$allform=new SalesReturn($id);
				$result = $allform->confirmFormInfo($data);
				if($result==='已开票'){
					$msg = $result;
				}elseif($result === true){
					$this->redirect(Yii::app()->createUrl('salesReturn/index',array("page"=>$fpage)));
				}else{
					$msg = '数据错误';
				}
				
			}
		}
		$team_array=Team::getTeamList('array');//业务组
		$contacts_array=CompanyContact::getContactList();//联系人
		$user_array=User::getUserList();//表单所属人
		//查询入库单的到货日期
		$inputdate=FrmInput::getLastInputDate($id);
		$this->render('confirm',array(
				'msg'=>$msg,
				'baseform'=>$baseform,
				'salesReturn'=>$salesReturn,
				'details'=>$details,
				'fpage'=>$fpage,
				'contacts'=>$contacts_array,
				'teams'=>$team_array,
				'users'=>$user_array,
				'date_reach'=>$inputdate,
		));
	
	}
	public function actionCancelConfirm($id)
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
		$form=new SalesReturn($id);
		$result = $form->cancelConfirm();
		if($result ===true){
			echo 1;
		}
	}
	
	
	/* * -----------------前台调取-----------------********* */
	/*
	 * 获取销售退货列表
	 */
	public function actionGetSimpleList()
	{
		$type=$_REQUEST['type'];
		//搜索
		$search=array();
		if(isset($_REQUEST['keywords']))
		{
			$search['keywords']=$_REQUEST['keywords'];
			$search['time_L']=$_REQUEST['time_L'];
			$search['time_H']=$_REQUEST['time_H'];
			$search['title_id']=$_REQUEST['title_id'];
			$search['customer_id']=$_REQUEST['customer_id'];
			$search['owned']=$_REQUEST['owned_by'];
		}
		list($tableHeader,$tableData,$pages)=FrmSalesReturn::getSimpleReturnList($search);
		$this->renderPartial('returnList',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
		));
	}
	
	/*
	 * 获取主体信息
	 */
	public function actionGetMainInfo()
	{
		$id=$_REQUEST['id'];
		$result=FrmSalesReturn::getMainInfo($id);
		echo $result;
	}
	
	/*
	 * 获取明细
	 */
	public function actionGetReturnDetail()
	{
		$id=$_REQUEST['id'];
		$from=$_REQUEST['from'];
		$result=FrmSalesReturn::getDetailData($id);
		$this->renderPartial('simpleDetailList',array(
				'data'=>$result,
				'from'=>$from,
		));
	}
	
	
	public function actionPrint($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$model = $baseform->salesReturn;
		$details = $model->salesReturnDetails;
		
		$this->renderPartial('print', array(
				'baseform' => $baseform, 
				'model' => $model, 
				'details' => $details,
		));
	}
}