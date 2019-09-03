<?php
/**
 * 付款登记
 * @author leitao
 *
 */
class FormBillController extends AdminBaseController
{
	public function actionView($id) 
	{	
		$this->pageTitle = "查看";
		$baseform = CommonForms::model()->with('formBill')->findByPk($id);
		if (!$baseform) return false;		
		$model = $baseform->formBill;
		switch ($baseform->form_type) 
		{
			case 'FKDJ': 
				$this->pageTitle .= "付款登记";
				break;
			case 'SKDJ': 
				$this->pageTitle .= "收款登记";
				break;
			default: break;
		}
		$this->pageTitle .= " ".$baseform->form_sn;
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('formBill/index', array('type' => $baseform->form_type, 'page' => $fpage));
		if ($_REQUEST['back_url']) 
			$back_url = Yii::app()->createUrl($_REQUEST['back_url'].'/index', array('page' => $fpage));

		//获取关联信息
		$relations = array();
		foreach ($model->relation as $each)
		{
			$relation = array();
			$relation['id'] = $each->id;
			$relation['common_id'] = $each->common_id;
			$relation['common'] = CommonForms::model()->findByPk($each->common_id);
			$relations[] = (Object)$relation;
		}
		
		$this->render('view', array(
				'model' => $model, 
				'baseform' => $baseform,
				'relations' => $relations,
				'back_url' => $back_url,
		));
	}
	
	//创建 付款/收款 登记
	public function actionCreate($type) 
	{
		$back_url = Yii::app()->createUrl('formBill/index', array('type' => $type));
		
		if (isset($_POST['FrmFormBill']))
		{
			$data = FrmFormBill::getInputData($_POST);
			if ($data)
			{
				$form = new FormBill($type, $id);
				switch ($type)
				{
					case 'FKDJ':
						$form->createForm($data);
						if ($data['main']->bill_type == 'GKFK') $rebate_form_id = $this->updateRebate("", $data);
		
						if ($rebate_form_id && $rebate_form_id !== true)
						{
							$data['main']->rebate_form_id = $rebate_form_id;
							$form->updateForm($data);
						}
						if ($data['common']->submit == 'yes')
						{
							$form->submitForm();
							if ($form->mainInfo->bill_type == 'GKFK' && $form->mainInfo->rebate_form_id)
							{
								$rebate = new Rebate($form->mainInfo->rebate_form_id, 'GKZR');
								$result = $rebate->submitForm();
							}
						}
						break;
					case 'SKDJ':
						$form->createForm($data);
						$form->submitForm();
						break;
					default: break;
				}
				$this->redirect(Yii::app()->createUrl('formBill/index', array('type' => $type)));
			}
		}
		
		$this->pageTitle = "新建";
		$model = new FrmFormBill();
		switch ($type) 
		{
			case 'FKDJ':
				$this->pageTitle .= "付款登记";
				$baseform = $model->baseformFKDJ ? $model->baseformFKDJ : CommonForms::model();
				$model->bill_type = $model->bill_type ? $model->bill_type : 'CGFK';
				break;
			case 'SKDJ':
				$this->pageTitle .= "收款登记";
				$baseform = $model->baseformSKDJ ? $model->baseformSKDJ : CommonForms::model();
				$model->bill_type = $model->bill_type ? $model->bill_type : 'XSSK';
				break;
			default: break;
		}
		$baseform->form_type = $type;
		$baseform->owned_by = $baseform->owned_by ? $baseform->owned_by : currentUserId();
		
		if ($_REQUEST['bill_type']) 
		{
			$common_id = intval($_REQUEST['common_id']);
			$model->bill_type = $_REQUEST['bill_type'];
			$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
			$relations = array();
			switch ($model->bill_type) 
			{
				case 'CGFK': //采购付款
					$back_url = Yii::app()->createUrl('purchase/index', array('page' => $fpage));
					
					$details = PurchaseView::model()->findAll("common_id = :common_id", array(':common_id' => $common_id));
					if (!is_array($details) || count($details) <= 0) break;
					$model->company_id = $details[0]->supply_id;
					$model->title_id = $details[0]->title_id;
					$model->fee = 0.0;
					$model->weight = 0.0;
					foreach ($details as $each)
					{
						$model->fee += $each->detail_price * $each->detail_weight;
						$model->weight += $each->detail_weight;
					}
					break;
				case 'XSSK': //销售收款
					$back_url = Yii::app()->createUrl('frmSales/index', array('page' => $fpage));
					
					$details = SalesView::model()->findAll("common_id = :common_id", array(':common_id' => $common_id));
					$model->company_id = $details[0]->customer_id;
					$model->title_id = $details[0]->main_title_id;
 					$model->client_id = $details[0]->client_id;
					$model->fee = 0.0;
					$model->weight = 0.0;
					foreach ($details as $each)
					{
						$model->fee += $each->detail_price * $each->weight;
						$model->weight += $each->weight;
					}
					break;
				case 'XSTH': //销售退货付款
					break;
				case 'CGTH': //采购退货收款
					break;
				case 'XSZR': //销售折让
					$back_url = Yii::app()->createUrl('rebate/index', array('type' => "sale", 'page' => $fpage));
					
					break;
				case 'GKZR': //高开折让
					$back_url = Yii::app()->createUrl('rebate/index', array('type' => "high", 'page' => $fpage));
					
					break;
				case 'GKFK': //高开付款
					$back_url = Yii::app()->createUrl('frmSales/index', array('page' => $fpage));
					
					$sales_form = CommonForms::model()->findByPK($common_id);
					$details = $sales_form->sales->highopen;
					$model->company_id = $details[0]->target_id;
					$model->title_id = $details[0]->title_id;
					$model->client_id = $details[0]->client_id;
					$model->fee = 0.0;
					foreach ($details as $each)
					{
						$model->fee += $each->real_fee;
						if ($each->is_selected == 1) continue;
						$relation = array();
						$relation['common_id'] = $each->baseform->id;
						$relation['common'] = CommonForms::model()->findByPk($relation['common_id']);
						$relations[] = (Object)$relation;
					}
					break;
				case 'DLFK': //代理付款
					$back_url = Yii::app()->createUrl('purchase/index', array('page' => $fpage));
					
					$billRecord_form = CommonForms::model()->findByPK($common_id);
					$detail = $billRecord_form->purchase;
					$model->company_id = $detail->supply_id;
					$model->title_id = $detail->title_id;
					$model->pledge_company_id = $detail->pledge->pledge_company_id;
					$model->fee = $detail->price_amount;
					$model->weight = $detail->weight;
						
					$relation = array();
					$relation['common_id'] = $billRecord_form->id;
					$relation['common'] = $billRecord_form;
					$relations[] = (Object)$relation;
					break;
				case 'TPYF': //托盘预付
					break;
				case 'TPSH': //托盘赎回
					$back_url = Yii::app()->createUrl('pledge/index', array('page' => $fpage));
					
					$billRecord_form = CommonForms::model()->findByPK($common_id);
					$detail = $billRecord_form->pledgeRedeem;
					$model->company_id = $detail->company_id;
					$model->title_id = $detail->title_id;
					$model->pledge_company_id = $detail->pledgeInfo->pledge_company_id;
					$model->fee = $detail->total_fee;
					$model->weight = $detail->weight;
						
					$relation = array();
					$relation['common_id'] = $billRecord_form->id;
					$relation['common'] = $billRecord_form;
					$relations[] = (Object)$relation;
					break;
				case 'YF': //运费
					$billRecord_form = CommonForms::model()->findByPK($common_id);
					$billRecord = $billRecord_form->billRecord;
					$back_url = Yii::app()->createUrl('billRecord/index', array('frm_common_id' => $billRecord->frm_common_id, 'page' => $fpage));
					
					$model->company_id = $billRecord->company_id;
					$model->title_id = $billRecord->title_id;
					$model->fee = $billRecord->amount;
						
					$relation = array();
					$relation['common_id'] = $billRecord_form->id;
					$relation['common'] = $billRecord_form;
					$relations[] = (Object)$relation;
					break;
				case 'CKFL': //仓库返利
					$back_url = Yii::app()->createUrl('billRebate/index', array('type' => "warehouse", 'page' => $fpage));
					
					$billRebate_form = CommonForms::model()->findByPK($common_id);
					$billRebate = $billRebate_form->billRebate;
					$model->company_id = $billRebate->company_id;
					$model->title_id = $billRebate->title_id;
					$model->fee = $billRebate->fee;
					
					break;
				case 'GCFL': //钢厂返利
					$back_url = Yii::app()->createUrl('billRebate/index', array('type' => "supply", 'page' => $fpage));
					
					$billRebate_form = CommonForms::model()->findByPK($common_id);
					$billRebate = $billRebate_form->billRebate;
					$model->company_id = $billRebate->company_id;
					$model->title_id = $billRebate->title_id;
					$model->fee = $billRebate->fee;
					
					break;
				case 'CCFY': //仓储费用
					$back_url = Yii::app()->createUrl('billRebate/index', array('type' => "cost", 'page' => $fpage));
					
					$billRebate_form = CommonForms::model()->findByPK($common_id);
					$billRebate = $billRebate_form->billRebate;
					$model->company_id = $billRebate->company_id;
					$model->title_id = $billRebate->title_id;
					$model->fee = $billRebate->fee;
					break;
				case 'BZJ': //保证金
					break;
				default: break;
			}
		}
		
		//供应商
		$supply_array = DictCompany::getAllVendorList("json", "is_supply"); 
		//物流商
		$logistics_array = DictCompany::getAllVendorList("json", "is_logistics");
		//客户
		$customer_array = DictCompany::getAllVendorList("json", "is_customer"); 
		//高开结算单位
		$gk_array = DictCompany::getAllVendorList("json", "is_gk"); 
		//仓库结算单位
		$warehouse_array = DictCompany::getAllVendorList("json", "is_warehouse"); 
		//托盘公司
		$pledge_array = DictCompany::getAllVendorList('json', "is_pledge");
		//公司抬头
		$title_array = DictTitle::getComs("json");
		
		//业务组
		$team_array = Team::getTeamList('array'); 
		//业务员
		$user_array = User::getUserList('array');
		
		$this->render("create", array(
				'model' => $model, 
				'baseform' => $baseform, 
				'title_array' => $title_array, //公司
				'supply_array' => $supply_array, //供应商
				'logistics_array' => $logistics_array, //物流商
				'customer_array' => $customer_array, //客户
				'warehouse_array' => $warehouse_array, //仓库结算单位
				'gk_array' => $gk_array, //高开结算单位
				'team_array' => $team_array, //业务组
				'user_array' => $user_array, //业务员
				'pledge_array' => $pledge_array, //托盘公司
				'relations' => $relations, //关联信息
				'back_url' => $back_url, 
		));
	}
	
	public function actionUpdate($id) 
	{
		$baseform = CommonForms::model()->with('formBill')->findByPk($id);
		if (!$baseform) return false;
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('formBill/index', array('type' => $baseform->form_type, 'page' => $fpage));
		
		if (isset($_POST['FrmFormBill']))
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}
			else
			{
				$data = FrmFormBill::getInputData($_POST);
				if ($data)
				{
					$form = new FormBill($baseform->form_type, $id);
					switch ($baseform->form_type)
					{
						case 'FKDJ':
							if ($form->updateForm($data) && $form->mainInfo->bill_type == 'GKFK')
							{
								if ($form->mainInfo->bill_type == 'GKFK') $rebate_form_id = $this->updateRebate($form->mainInfo->rebate_form_id, $data);
								if ($rebate_form_id && $rebate_form_id !== true)
								{
									$data['main']->rebate_form_id = $rebate_form_id;
									$form->updateForm($data);
								}
							}
							if ($data['common']->submit == 'yes' && $form->submitForm() && $form->mainInfo->bill_type == 'GKFK')
							{
								$rebate = new Rebate($form->mainInfo->rebate_form_id, 'GKZR');
								$result = $rebate->submitForm();
							}
							break;
						case 'SKDJ':
							$form->cancelSubmitForm();
							$form->updateForm($data);
							$form->submitForm();
							break;
						default: break;
					}
					$this->redirect($back_url);
				}
			}
		}
		
		$this->pageTitle = "修改";
		$model = $baseform->formBill;
		switch ($baseform->form_type) 
		{
			case 'FKDJ': 
				$this->pageTitle .= "付款登记";
				break;
			case 'SKDJ': 
				$this->pageTitle .= "收款登记";
				break;
			default: break;
		}
		
		//获取关联信息
		$relations = array();
		foreach ($model->relation as $each) 
		{
			$relation = array();
			$relation['id'] = $each->id;
			$relation['common_id'] = $each->common_id;
			$relation['common'] = CommonForms::model()->findByPk($each->common_id);
			$relations[] = (Object)$relation;
		}
		
		//供应商
		$supply_array = DictCompany::getAllVendorList("json", "is_supply"); 
		//物流商
		$logistics_array = DictCompany::getAllVendorList("json", "is_logistics");
		//客户
		$customer_array = DictCompany::getAllVendorList("json", "is_customer"); 
		//高开结算单位
		$gk_array = DictCompany::getAllVendorList("json", "is_gk"); 
		//仓库结算单位
		$warehouse_array = DictCompany::getAllVendorList("json", "is_warehouse");
		//托盘公司
		$pledge_array = DictCompany::getAllVendorList('json', "is_pledge"); //托盘公司
		//公司抬头
		$title_array = DictTitle::getComs("json");
		
		//业务组
		$team_array = Team::getTeamList('array');
		//业务员
		$user_array = User::getUserList('array');
		
		//结算账户
		$bank_info_array = BankInfo::getBankList("json", $model->company_id);
		//公司账户
		$dict_bank_info_array = DictBankInfo::getBankList("json", $model->title_id);
		//托盘账户
		$pledge_bank_info_array = $model->pledge_company_id ? BankInfo::getBankList("json", $model->pledge_company_id) : '';
		
		$this->render("update", array(
				'model' => $model,
				'baseform' => $baseform,
				'supply_array' => $supply_array, //供应商
				'logistics_array' => $logistics_array, //物流商
				'customer_array' => $customer_array, //客户
				'gk_array' => $gk_array, //高开结算单位
				'warehouse_array' => $warehouse_array, //仓库结算单位
				'pledge_array' => $pledge_array, //托盘公司
				'title_array' => $title_array, //公司抬头
				'team_array' => $team_array, //业务组
				'user_array' => $user_array, //业务员
				'bank_info_array' => $bank_info_array, //结算账户
				'dict_bank_info_array' => $dict_bank_info_array, //公司账户
				'pledge_bank_info_array' => $pledge_bank_info_array, //托盘账户
				'relations' => $relations,
				'back_url' => $back_url, //返回路径
				'msg' => $msg,
		));
	}
	
	public function actionDeleteForm() 
	{
		$id = intval($_REQUEST['id']);
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$str = $_REQUEST['str'];
		$form = new FormBill($baseform->form_type, $id);
		switch ($baseform->form_type) 
		{
			case 'FKDJ': 
				$result = $form->deleteForm($str);
				if ($form->mainInfo->bill_type == 'GKFK') 
				{
					if($form->mainInfo->rebate_form_id){
						$rebate = new Rebate($form->mainInfo->rebate_form_id,'GKZR');
						$result = $rebate->deleteForm("");
					}
				}
				break;
			case 'SKDJ': 
				$result = $form->cancelSubmitForm();
				$result = $form->deleteForm($str);
				break;
			default: break;
		}
		if (!$result) return false;
		echo 'success';
	}

	public function actionCheck() 
	{
		$id = intval($_REQUEST['id']);
		$type = $_REQUEST['type'];
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$form = new FormBill($baseform->form_type, $id);
		switch ($type)
		{
			case 'pass':
				$result = $form->approveForm();
				if ($result && $form->mainInfo->bill_type == 'GKFK' && $form->commonForm->form_status == 'approve') 
				{
					$rebate = new Rebate($form->mainInfo->rebate_form_id, 'GKZR');
					$rebate->approveForm();
				}
				break;
			case 'cancle':
				$result = $form->cancelApproveForm();
				if ($result && $form->mainInfo->bill_type == 'GKFK' && $form->commonForm->form_status != 'approve')
				{
					$rebate = new Rebate($form->mainInfo->rebate_form_id, 'GKZR');
					if ($rebate->commonForm->form_status == 'approve') $rebate->cancelApproveForm();
				}
				break;
			case 'deny':
				$result = $form->refuseForm();
				if ($result && $form->mainInfo->bill_type == 'GKFK')
				{
					$rebate = new Rebate($form->mainInfo->rebate_form_id, 'GKZR');
					$rebate->refuseForm();
				}
				break;
			default:
				return false;
				break;
		}
		if (!$result) return 'fail';
		echo 'success';
	}
	
	public function actionSubmit($id, $type) 
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$form = new FormBill($baseform->form_type, $id);
		switch ($type)
		{
			case 'submit': 
				$result = $form->submitForm(); 
				if ($form->mainInfo->bill_type == 'GKFK') 
				{
					$rebate = new Rebate($form->mainInfo->rebate_form_id, 'GKZR');
					$rebate->submitForm();
				}
				break;
			case 'cancle': 
				$result = $form->cancelSubmitForm(); 
				if ($form->mainInfo->bill_type == 'GKFK')
				{
					$rebate = new Rebate($form->mainInfo->rebate_form_id, 'GKZR');
					$rebate->cancelSubmitForm();
				}
				break;
			default: 
				return false; 
				break;
		}
		if (!$result) return 'fail';
		echo 'success';
	}

	public function actionAccounted($id, $type)
	{		
		$view='account';
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		switch ($baseform->form_type) 
		{
			case 'FKDJ': 
				$this->pageTitle = "付款登记";
				$this->layout='';
				$view='account_FK';
				break;
			case 'SKDJ': 
				$this->pageTitle = "收款登记";				
				break;
			default: break;
		}
		$this->pageTitle .= "入账";
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('formBill/index', array('type' => $baseform->form_type, 'page' => $fpage));
		
		$form = new FormBill($baseform->form_type, $id);
		switch ($type)
		{
			case 'accounted':
				if (isset($_POST['FrmFormBill']))
				{
					$last_update = $_POST['last_update'];
					if ($last_update != $baseform->last_update)
					{
						$msg = "您看到的信息不是最新的，请刷新后再试";
					}
					else
					{
						if($baseform->form_status == "accounted"){
							$msg = "该单据已入账";
						}else{
							$data = FrmFormBill::getInputData($_POST);
							if ($data)
							{
								switch ($baseform->form_type) 
								{
									case 'FKDJ':
										$form->updateForm($data);
										$res=$form->accountedForm($data);
										if($res)echo "done";
										else echo "fail";
										die;										
										break;
									case 'SKDJ': 
										$form->cancelSubmitForm();
										$form->updateForm($data);
										$form->submitForm();
										$form->accountedForm($data);
										$this->redirect($back_url);
										
										break;
									default: break;
								}
							}
						}
					}
				}
				$model = $baseform->formBill;
				//获取关联信息
				$relations = array();
				foreach ($model->relation as $each)
				{
					$relation = array();
					$relation['id'] = $each->id;
					$relation['common_id'] = $each->common_id;
					$relation['common'] = CommonForms::model()->findByPk($each->common_id);
					$relations[] = (Object)$relation;
				}
				//供应商
				$supply_array = DictCompany::getVendorList("json", "is_supply");
				//物流商
				$logistics_array = DictCompany::getVendorList("json", "is_logistics");
				//客户
				$customer_array = DictCompany::getVendorList("json", "is_customer");
				//高开结算单位
				$gk_array = DictCompany::getVendorList("json", "is_gk");
				//托盘公司
				$pledge_array = DictCompany::getVendorList('json', "is_pledge"); //托盘公司
				//公司抬头
				$title_array = DictTitle::getComs("json");
				
				//业务组
				$team_array = Team::getTeamList('array');
				//业务员
				$user_array = User::getUserList('array');
				
				//结算账户
				$bank_info_array = BankInfo::getBankList("json", $model->company_id);
				//公司账户
				$dict_bank_info_array = DictBankInfo::getBankList("json", $model->title_id);
				//托盘账户
				$pledge_bank_info_array = $model->pledge_company_id ? BankInfo::getBankList("json", $model->pledge_company_id) : '';
				
				$this->render($view, array(
						'model' => $model,
						'baseform' => $baseform,
						'supply_array' => $supply_array, //供应商
						'logistics_array' => $logistics_array, //物流商
						'customer_array' => $customer_array, //客户
						'gk_array' => $gk_array, //高开结算单位
						'pledge_array' => $pledge_array, //托盘公司
						'title_array' => $title_array, //公司抬头
						'team_array' => $team_array, //业务组
						'user_array' => $user_array, //业务员
						'bank_info_array' => $bank_info_array, //结算账户
						'dict_bank_info_array' => $dict_bank_info_array, //公司账户
						'pledge_bank_info_array' => $pledge_bank_info_array, //托盘账户
						'relations' => $relations,
						'back_url' => $back_url, //返回路径
						'msg' => $msg,
				));
				break;
			case 'cancel_accounted':
				$last_update = $_REQUEST['last_update'];
				if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
				
				$form->cancelAccountedForm();
				echo 'success';
				break;
			default:
				break;
		}
	}
	
//---------------------------------------- 单据查询 ----------------------------------------
	
	//付款/收款 登记列表
	public function actionIndex($type)
	{

		$this->pageTitle = ($type == "FKDJ" ? "付款" : "收款")."登记";
		if($type=='FKDJ'||$type=='SKDJ')
		{
			if($type=='FKDJ'){$normal_view='付款普通视图';$check_view='付款审核视图';$coo='bill_view';$name='search_bill_fk';}else{
				$normal_view='收款普通视图';$check_view='收款审核视图';$coo='sbill_view';$name='search_bill_sk';
			}
			if(!checkOperation($normal_view)&&!checkOperation($check_view)){
				echo '没有权限';
				return;
			}else{
				$v=array('belong'=>$normal_view,'all'=>$check_view);
				if($_COOKIE[$coo]&&checkOperation($v[$_COOKIE[$coo]])){
					setcookie($coo,$_COOKIE[$coo],time()+60*60*24,'/');
				}else{
					if(!checkOperation($normal_view)){
						setcookie($coo,'all',time()+60*60*24,'/');
						$_COOKIE[$coo]='all';
					}else{
						setcookie($coo,'belong',time()+60*60*24,'/');
						$_COOKIE[$coo]='belong';
					}
				}
			}
		}
		
		
		$tableHeader = array();
		$tableHeader[] = array('name' => "", 'class' => "sort-disabled text-center", 'width' => "20px");
		$tableHeader[] = array('name' => "操作", 'class' => "sort-disabled", 'width' => "80px");
		$tableHeader[] = array('name' => "单号", 'class' => "sort-disabled", 'width' => "80px");
		$tableHeader[] = array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "60px");
		$tableHeader[] = array('name' => "登记日期", 'class' => "flex-col sort-disabled", 'width' => "80px");
		$tableHeader[] = array('name' => "公司", 'class' => "flex-col sort-disabled", 'width' => "60px");
		$tableHeader[] = array('name' => "公司账户", 'class' => "flex-col sort-disabled", 'width' => "120px");
		$tableHeader[] = array('name' => "结算单位", 'class' => "flex-col sort-disabled", 'width' => "150px");
		$tableHeader[] = array('name' => "客户", 'class' => "flex-col sort-disabled", 'width' => "60px");
		$tableHeader[] = array('name' => "总金额", 'class' => "flex-col sort-disabled text-right", 'width' => "100px");
		switch ($type) 
		{
			case 'FKDJ': //付款登记
				if($_COOKIE[$coo]=='all'){
					$tableHeader[] = array('name' => "余额", 'class' => "flex-col sort-disabled text-right", 'width' => "100px");
				}
				$tableHeader[] = array('name' => "付款方式", 'class' => "flex-col sort-disabled", 'width' => "65px");
				$tableHeader[] = array('name' => "类型", 'class' => "flex-col sort-disabled", 'width' => "85px");
				$tableHeader[] = array('name' => "乙单", 'class' => "flex-col sort-disabled", 'width' => "40px");
				$tableHeader[] = array('name' => "到账日期", 'class' => "flex-col sort-disabled", 'width' => "80px");
				$tableHeader[] = array('name' => "业务员", 'class' => "flex-col sort-disabled", 'width' => "70px");
				$tableHeader[] = array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "70px");
				// $tableHeader[] = array('name' => "审核人", 'class' => "flex-col sort-disabled", 'width' => "70px");
				// $tableHeader[] = array('name' => "审核时间", 'class' => "flex-col sort-disabled", 'width' => "90px");
				$tableHeader[] = array('name' => "用途", 'class' => "flex-col sort-disabled", 'width' => "200px");
				break;
			case 'SKDJ': //收款登记
				$tableHeader[] = array('name' => "收款方式", 'class' => "flex-col sort-disabled", 'width' => "65px");
				$tableHeader[] = array('name' => "类型", 'class' => "flex-col sort-disabled", 'width' => "85px");
				$tableHeader[] = array('name' => "乙单", 'class' => "flex-col sort-disabled", 'width' => "40px");
				$tableHeader[] = array('name' => "到账日期", 'class' => "flex-col sort-disabled", 'width' => "80px");
				$tableHeader[] = array('name' => "业务员", 'class' => "flex-col sort-disabled", 'width' => "70px");
				$tableHeader[] = array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "70px");
				break;
			default: 
				return;
				break;
		}
		$tableHeader[] = array('name' => "入账人", 'class' => "flex-col sort-disabled", 'width' => "70px");
		// $tableHeader[] = array('name' => "入账日期", 'class' => "flex-col sort-disabled", 'width' => "150px");
		$tableHeader[] = array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "230px");

		if (isset($_POST['search']) && $_POST['search']['form_status'] == 'delete') 
			$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "200px");
		
		$search = isset($_POST['search']) ? $_POST['search'] : array();
		$search=updateSearch($search,$name.$_COOKIE[$coo]);
		list($tableData, $pages, $totalData) = FrmFormBill::getFormBillList($search, $type);
		
		$title_array = DictTitle::getComs('json'); //公司抬头
		$company_array = DictCompany::getAllComs('json'); //结算单位
		$user_array=User::getUserList();	//表单所属人

		$this->render("index", array(
				'tableHeader' => $tableHeader, 
				'tableData' => $tableData, 
				'totalData' => $totalData,
				'pages' => $pages,
				'company_array' => $company_array,
				'title_array' => $title_array,
				'type' => $type,
				'search' => $search,
				'users'=>$user_array,
		));
	}
	
	//获取单据信息
	public function actionGetBillSimpleList() 
	{
		$bill_type = $_REQUEST['type'];
		//搜索
		$search = array(
				'id' => $_REQUEST['id'], 
				'company_id' => $_REQUEST['company_id'],
				'client_id' => $_REQUEST['client_id'],
				'title_id' => $_REQUEST['title_id'], 
				'is_yidan' => $_REQUEST['is_yidan'],
				'keywords' => trim($_REQUEST['keywords']), 
				'time_L' => $_REQUEST['time_L'], 
				'time_H' => $_REQUEST['time_H'], 
				'team_id' => $_REQUEST['team_id'], 
				'owned_by' => $_REQUEST['owned_by'], 
				'pledge_company_id' => $_REQUEST['pledge_company_id']
		);
		
		switch ($bill_type) 
		{
			case 'CGFK': //采购付款
// 				list($tableHeader, $tableData, $pages) = FrmPurchase::getFormBillList($search);
				break;
			case 'TPSH': //托盘赎回
				list($tableHeader, $tableData, $pages) = FrmPledgeRedeem::getFormBillList($search);
				break;
			case 'CGTH': //采购退货收款
				list($tableHeader, $tableData, $pages) = FrmPurchaseReturn::getFormBillList($search);
				break;
			case 'XSSK': //销售收款
// 				list($tableHeader, $tableData, $pages) = FrmSales::getFormBillList($search);
				break;
			case 'XSTH': //销售退货付款
				list($tableHeader, $tableData, $pages) = FrmSalesReturn::getFormBillList($search);
				break;
			case 'XSZR': //销售折让
				list($tableHeader, $tableData, $pages) = FrmRebate::getFormBillList($search, 'XSZR');
				break;
			case 'GKZR': //高开折让
				list($tableHeader, $tableData, $pages) = FrmRebate::getFormBillList($search, 'GKZR');
				break;
			case 'DLFK': //代理付款
				list($tableHeader, $tableData, $pages) = FrmPurchase::getFormBillList($search, 'tpcg');
				break;
			case 'GKFK': //高开付款
				list($tableHeader, $tableData, $pages) = HighOpen::getFormBillList($search);
				break;
// 			case 'CKFL': break;//仓库返利
// 			case 'GCFL': break;//钢厂返利
// 			case 'CCFY': break;//仓储费用
			case 'YF': //运费
				list($tableHeader, $tableData, $pages) = BillRecord::getFormBillList($search);
				break;
			case 'BZJ': //保证金
				break;
			default: 
				return false;
				break;
		}
		
		$this->renderPartial("bill_main", array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
		));
	}
	
	//获取相关往来
	public function actionGetTurnoverList() 
	{
		//搜索
		$search = array();
		$search['company_id'] = $_REQUEST['company_id'];
		$search['title_id'] = $_REQUEST['title_id'];
		list($tableHeader, $tableData, $pages) = Turnover::getSimpleList($search);
		$this->renderPartial('turnoverMain', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
		));
	}
	
	//获取业务员相关销售单
	public function actionGetSalesList() 
	{
		//搜索
		$search = array();
		$search['owned_by'] = $_REQUEST['owned_by'];
		
		list($tableHeader, $tableData, $pages) = FrmSales::getOwnedList($search);
		$this->renderPartial('ownedSalesMain', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
		));
	}
	
	//获取返利信息
	public function actionGetBillRebateList($type) 
	{
		//搜索
		$search = array();
		
		list($tableHeader, $tableData, $pages) = BillRebate::getBillRebateList($search, $type);
		$this->renderPartial('billRebateMain', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
		));
	}
	
	public function actionGetSalverPurchaseList() 
	{
		//搜索
		$search = array();
		$search['owned_by'] = $_REQUEST['owned_by'];
		
		list($tableHeader, $tableData, $pages) = FrmPurchase::getSalverPurchaseList($search, 'tpcg');
		$this->renderPartial('salverPurchaseMain', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
		));
	}
	
	public function actionGetDlfkList() 
	{
		//搜索
		$search = array();
		$search['owned_by'] = $_REQUEST['owned_by'];
		
		list($tableHeader, $tableData, $pages) = FrmFormBill::getDlfkList($search);
		$this->renderPartial('dlfkMain', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
		));
	}
	
	//新增高开折让
	private function updateRebate($id, $data) 
	{
		if (floatval($data['main']->theory_fee) == floatval($data['main']->fee)) return true;
		$rebate_post = array();
		$frmRebate = array(
				'company_id' => $data['main']->company_id, 
				'title_id' => $data['main']->title_id, 
				'client_id'=>$data['main']->client_id,
				'type' => "high", 
				'amount' => floatval($data['main']->theory_fee) - floatval($data['main']->fee), 
				'comment' => ''
		);
		$rebate_post['FrmRebate'] = $frmRebate;
		
		$commonForms = array(
				'owned_by' => $data['common']->owned_by, 
				'form_type' => "GKZR", 
				'form_time' => $data['common']->form_time, 
				'comment' => "", 
				'last_update' => ""
		);
		$rebate_post['CommonForms'] = $commonForms;
		
		$rebate_data = FrmRebate::getInputData($rebate_post);
		if (!$rebate_data) return false;
		$rebate = new Rebate($id, 'GKZR');
		if ($id) return $rebate->updateForm($rebate_data);
		if ($rebate->createForm($rebate_data)) return $rebate->commonForm->id;
	}

	public function actionExport($type) 
	{
		$search = $_REQUEST['search'];
		switch ($type) {
			case 'FKDJ': //付款登记
				$name = "付款".date("Y/m/d");
				$title = array("付款单号", "付款日期", 
					"公司", "结算单位","客户", "付款类型", "付款方式", 
					"总金额", "我方账号", "到账日期", "审批状态", "乙单", "入账状态", 
					"业务员", "操作员", "审核人", "审核时间", 
					"用途", "入账人", "入账日期", "备注", 
					"关联单号", 
					"托盘公司", 
					"仓库", 
					"产地/品名/材质/规格/长度", 
					"件数", 
					"重量", 
					"金额", 
					"利息", 
					"车船号", 
				);

				break;
			case 'SKDJ': //收款登记
				$name = "收款".date("Y/m/d");
				$title = array("收款单号", "收款日期", 
					"公司", "结算单位","客户", "收款类型", "收款方式", 
					"总金额", "我方账号", "到账日期", "审批状态", "乙单", "入账状态", 
					"业务员", "操作员", "入账人", "入账日期", "备注", 
					"关联单号", 
					"仓库", 
					"车船号", 
				);
				break;
			default: break;
		}
		if ($search['form_status'] == 'delete') array_push($title, "作废原因");
		$content = FrmFormBill::getAllList($search, $type);
		PHPExcel::ExcelExport($name, $title, $content);
		
	}
	
	/*
	 * 获取付款单此前操作
	 */
	public  function actionGetCurrentButton()
	{
		$form_sn=$_REQUEST['form_sn'];
		if(!$form_sn){
			$form_id=$_REQUEST['form_id'];
			$form_sn=CommonForms::model()->findByPk($form_id)->form_sn;
			if(!$form_sn)return false;
		}
		$str=FrmFormBill::getButtons($form_sn);
		echo $str;
	}
	
	
	
	
}