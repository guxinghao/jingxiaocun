<?php
/**
 * 销售折让
 * @author leitao
 *
 */
class RebateController extends AdminBaseController
{
	public function actionView($id) 
	{
		$this->pageTitle = "查看";
		$baseform = CommonForms::model()->with('rebate')->findByPk($id);
		$model = $baseform->rebate;
		switch ($model->type)
		{
			case 'sale':
				$this->pageTitle .= "销售折让";
				$sales_type = array('normal' => "库存销售", 'xxhj' => "先销后进", 'dxxs' => "代销销售");
				$details = array();
				foreach ($model->rebateRelation as $item)
				{
					$sales_form = $item->sales_form;
					$sales = $item->sales_form->sales;
		
					$detail['id'] = $item->id;
					$detail['sales_id'] = $item->sales_id;
					$detail['form_sn'] = $sales_form->form_sn;
					$detail['created_at'] = $sales_form->created_at;
					$detail['company'] = $sales->dictCompany;
					$detail['title'] = $sales->dictTitle;
					$detail['weight'] = $sales->weight;
					$detail['amount'] = $sales->amount;
					$detail['need_weight'] = $sales->weight - $sales->output_weight;
					$detail['need_amount'] = $sales->amount - $sales->output_amount;
					$detail['sales_type'] = $sales_type[$sales->sales_type];
					$detail['team'] = $sales->team->name;
					$detail['belong'] = $sales_form->belong->nickname;
					$detail['client'] = $sales->client;
					$detail['client_id'] = $model->client_id;
					$details[] = (Object)$detail;
				}
				break;
			case 'high': 
				$this->pageTitle .= "高开折让";
				break;
			case '':
				$this->pageTitle .="采购折让";
			default:
				break;
		}
		$this->pageTitle .= ' '.$baseform->form_sn;

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('rebate/index', array('type' => $model->type, 'page' => $fpage));
		
		$this->render('view', array(
				'model' => $model,
				'baseform' => $baseform,
				'details' => $details,
				'back_url' => $back_url,
		));
	}
	
	public function actionCreate($type)
	{
		$this->pageTitle = "新建";
		$model = new FrmRebate();
		$baseform = new CommonForms();

		switch ($type) 
		{
			case 'sale':
				$this->pageTitle .= "销售折让";
				$baseform->form_type = 'XSZR';
				break;
			case 'high':
				$this->pageTitle .= "高开折让";
				$baseform->form_type = 'GKZR';
				break;
			case 'shipment':
				$this->pageTitle .="采购折让";
				$baseform->form_type='CGZR';
			default: break;
		}
		$model->type = $type;
// 		$baseform->owned_by = currentUserId();
		$back_url = Yii::app()->createUrl('rebate/index', array('type' => $type));
		
		if (isset($_POST['FrmRebate']))
		{
			$data = FrmRebate::getInputData($_POST);
			if ($data)
			{
				$rebate = new Rebate($id, $baseform->form_type);
				$rebate->createForm($data);
				if ($data['common']->submit == 'yes') $rebate->submitForm();
				$this->redirect($back_url);
			}
		}
		
		$logistics_array = DictCompany::getVendorList('json', 'is_logistics'); //物流商
		$vendor_array = DictCompany::getVendorList('json','is_supply');//供应商
		$customer_array = DictCompany::getVendorList('json', 'is_customer'); //客户
		$gk_array = DictCompany::getVendorList('json', 'is_gk'); //高开结算单位
		$title_array = DictTitle::getComs('json'); //公司抬头
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getUserList('array'); //业务员
		
		$this->render('create', array(
				'model' => $model,
				'baseform' => $baseform,
				'title_array' => $title_array,
				'vendor_array' =>$vendor_array,
				'customer_array' => $customer_array,
				'logistics_array' => $logistics_array,
				'gk_array' => $gk_array,
				'team_array' => $team_array,
				'user_array' => $user_array,
				'back_url' => $back_url,
		));
	}

	public function actionUpdate($id, $type)
	{
		$baseform = CommonForms::model()->with('rebate')->findByPk($id);
		if (!$baseform) return false; 

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('rebate/index', array('type' => $type, 'page' => $fpage));
		
		if (isset($_POST['FrmRebate']))
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}
			else
			{
				$data = FrmRebate::getInputData($_POST);
				if ($data)
				{
					$rebate = new Rebate($id, $baseform->form_type);
					$rebate->updateForm($data);
					if ($data['common']->submit == 'yes') $rebate->submitForm();
					$this->redirect($back_url);
				}
			}
		}
		
		$this->pageTitle = "修改";
		$model = $baseform->rebate;
		
		switch ($model->type) 
		{
			case 'sale':
				$this->pageTitle .= "销售折让";
				$sales_type = array('normal' => "库存销售", 'xxhj' => "先销后进", 'dxxs' => "代销销售");
				$details = array();
				foreach ($model->rebateRelation as $item)
				{
					$sales_form = $item->sales_form;
					$sales = $item->sales_form->sales;
						
					$detail['id'] = $item->id;
					$detail['sales_id'] = $item->sales_id;
					$detail['form_sn'] = $sales_form->form_sn;
					$detail['form_status'] = $sales_form->form_status;
					$detail['created_at'] = $sales_form->created_at;
					$detail['company'] = $sales->dictCompany;
					$detail['company_id'] = $model->company_id;
					$detail['title'] = $sales->dictTitle;
					$detail['weight'] = $sales->weight;
					$detail['amount'] = $sales->amount;
					$detail['need_weight'] = $sales->weight - $sales->output_weight;
					$detail['need_amount'] = $sales->amount - $sales->output_amount;
					$detail['sales_type'] = $sales_type[$sales->sales_type];
					$detail['team'] = $sales->team->name;
					$detail['belong'] = $sales_form->belong->nickname;
					$detail['client'] = $sales->client;
					$detail['client_id'] = $model->client_id;
					$details[] = (Object)$detail;
				}				 
				break;
			case 'high':
				$this->pageTitle .= "高开折让";
				break;
			default: 
				break;
		}

		//客户
		$customer_array = DictCompany::getVendorList('json', 'is_customer');
		//物流商
		$logistics_array = DictCompany::getVendorList('json', 'is_logistics'); 
		//高开结算单位
		$gk_array = DictCompany::getVendorList('json', 'is_gk'); 
		//公司抬头
		$title_array = DictTitle::getComs('json');
		
		//业务组
		$team_array = Team::getTeamList('array');
		//业务员
		$user_array = User::getUserList('array');
		
		//结算账户
		$bank_info_array = BankInfo::getBankList("json", $model->company_id);
		//公司账户
		$dict_bank_info_array = DictBankInfo::getBankList("json", $model->title_id);

		switch ($baseform->form_status) {
			case 'unsubmit':
				$view = 'update';
				break;
			case 'submited':
				$view = '__form';
				break;
			default:
				break;
		}

		$this->render($view, array(
				'model' => $model, 
				'baseform' => $baseform,
				'title_array' => $title_array,
				'customer_array' => $customer_array,
				'logistics_array' => $logistics_array,
				'gk_array' => $gk_array,
				'team_array' => $team_array, //业务组
				'user_array' => $user_array, //业务员
				'bank_info_array' => $bank_info_array, //结算账户
				'dict_bank_info_array' => $dict_bank_info_array, //公司账户
				'details' => $details,
				'back_url' => $back_url,
				'msg' => $msg
		));
	}
	
	//作废
	public function actionDeleteForm()
	{
		$id = intval($_REQUEST['id']);
		$str = $_REQUEST['str'];
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		if ($baseform->form_status != 'unsubmit') 
		{
			echo "表单已经提交，不能作废";
			return false;
		}
		$form = new Rebate($id, $baseform->form_type);
		$result = $form->deleteForm($str);
		if (!$result) return false;
		echo 'success';
	}
	
	//提交|取消提交
	public function actionSubmit($id, $type) 
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$form = new Rebate($id, $baseform->form_type);
		switch ($type) 
		{
			case 'submit': 
				$result = $form->submitForm();
				break;
			case 'cancle': 
				$result = $form->cancelSubmitForm();
				break;
			default: 
				break;
		}
		if (!$result) return false;
		echo 'success';
	}
	
	//审核|取消审核|拒绝
	public function actionCheck() 
	{
		$id = intval($_REQUEST['id']);
		$type = $_REQUEST['type'];
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$form = new Rebate($id, $baseform->form_type);
		
		switch ($type) 
		{
			case 'pass':
				$result = $form->approveForm();
				break;
			case 'cancle':
				$result = $form->cancelApproveForm();
				break;
			case 'deny':
				$result = $form->refuseForm();
				break;
			default:
				return false;
				break;
		}
		if (!$result) return false;
		if($result === -1){
			echo "已开票，不能取消审核";
			die;
		}
		echo 'success';
	}
	
	public function actionIndex($type)
	{
		$this->pageTitle = "";
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "30px"),
		);
		switch ($type) 
		{
			case 'sale':
				$this->pageTitle .= "销售折让";
				$tableHeader[] = array('name' => "操作", 'class' => "sort-disabled", 'width' => "100px");
				$company_array = DictCompany::getVendorList('json', 'is_customer'); //客户
				break;
			case 'shipment':
				$this->pageTitle .= "采购折让";
				$company_array = DictCompany::getVendorList('json', 'is_supply'); //供应商
				$tableHeader[] = array('name' => "操作", 'class' => "sort-disabled", 'width' => "100px");
				break;
			case 'shipment_sale':
				$this->pageTitle .= "销售运费折让";
				break;
			case 'high':
				$this->pageTitle .= "高开折让";
				$company_array = DictCompany::getVendorList('json', 'is_gk'); //高开结算单位
				break;
			default:
				break;
		}
		
		$tableHeader = array_merge($tableHeader, array(
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "150px"),
				array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "登记日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "公司", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "结算单位", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "客户", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "折让金额", 'class' => "flex-col sort-disabled text-right", 'width' => "100px"),
				array('name' => "折让类型", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "乙单", 'class' => "flex-col sort-disabled", 'width' => "50px"),
				array('name' => "业务员", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "业务组", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "审核人", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "审核时间", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "230px")
		));
		if (isset($_POST['search']) && $_POST['search']['form_status'] == 'delete')
			$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "200px");
		
		$search = isset($_POST['search']) ? $_POST['search'] : array();
		$search['type'] = $type;
		list($tableData, $pages, $totalData) = FrmRebate::getRebateList($search, $type);
		
		//业务组
		$team_array = Team::getTeamList("json");
		//公司抬头
		$title_array = DictTitle::getComs('json');
		//销售员
		$user_array = User::getUserList("json");
		
		$this->render('index', array(
				'type' => $type,
				'tableHeader' => $tableHeader,
				'tableData' => $tableData, 
				'totalData' => $totalData,
				'pages' => $pages,
				'search' => $search,
				'team_array' => $team_array,
				'title_array' => $title_array,
				'company_array' => $company_array,
				'user_array' => $user_array,
		));
	}

	public function actionGetSimpleList() 
	{
		//搜索
		$search = array(
				'id' => $_REQUEST['id'],
				'team_id' => $_REQUEST['team_id'], 
				'sales_title' => $_REQUEST['sales_title'], 
				'customer_id' => $_REQUEST['customer_id'], 
 				'client_id' => $_REQUEST['client_id'],
				'keywords' => $_REQUEST['keywords'], 
				'search_begin' => $_REQUEST['search_begin'], 
				'search_end' => $_REQUEST['search_end'], 
				'owned_by' => $_REQUEST['owned_by'],
		);
		
		switch ($_GET['type']) 
		{
			case 'sale': 
				list($tableHeader, $tableData, $pages) = FrmSales::getCheckList($search);
				break;
			default: 
				return ;
				break;
		}		
		$this->renderPartial('formList', array(
				'tableHeader' => $tableHeader, 
				'tableData' => $tableData, 
				'pages' => $pages,
		));
	}
}