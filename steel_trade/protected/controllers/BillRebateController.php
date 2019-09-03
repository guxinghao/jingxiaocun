<?php
/**
 * 钢厂返利/仓库返利/仓储费用
 * @author leitao
 *
 */
class BillRebateController extends AdminBaseController
{
	public function actionView($id) 
	{	
		$baseform = CommonForms::model()->with('billRebate')->findByPK($id);
		if (!$baseform) return false;
		
		$this->pageTitle = "查看";
		$model = $baseform->billRebate;
		switch ($model->type) 
        {
        	case 'warehouse': $this->pageTitle .= "仓库返利"; break; //仓库返利
        	case 'supply': $this->pageTitle .= "钢厂返利"; break; //钢厂返利
        	case 'cost': $this->pageTitle .= "仓储费用"; break; //仓储费用
        	default: break;
        }
		$this->pageTitle .= ' '.$baseform->form_sn;
		
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('billRebate/index', array('type' => $model->type, 'page' => $fpage));
		
		$this->render('view', array(
				'model' => $model, 
				'baseform' => $baseform,
				'back_url' => $back_url,
		));
	}
	
	public function actionCreate($type) 
	{
		$back_url = Yii::app()->createUrl('billRebate/index', array('type' => $type));
		
		if (isset($_POST['BillRebate'])) 
		{
			$data = BillRebate::getInputData($_POST);
			if ($data) 
			{
				$form = new BillRebateClass($id);
				if ($form->createForm($data) && $data['common']->submit == 'yes') $form->submitForm();
				
				$this->redirect($back_url);
			}
		}
		
		$this->pageTitle = "新建";
		$model = new BillRebate();
		$baseform = new CommonForms();
		switch ($type)
		{
			case 'warehouse':
				$this->pageTitle .= "仓库返利";
				$baseform->form_type = "CKFL";
				$company_array = DictCompany::getVendorList('json', 'is_warehouse'); //仓库结算单位
				$warehouse_array = Warehouse::getWareList('json'); //仓库
				break;
			case 'supply':
				$this->pageTitle .= "钢厂返利";
				$baseform->form_type = "GCFL";
				$company_array = DictCompany::getVendorList('json', 'is_supply'); //供应商
				break;
			case 'cost':
				$this->pageTitle .= "仓储费用";
				$baseform->form_type = "CCFY";
				$company_array = DictCompany::getVendorList('json', 'is_warehouse'); //仓库结算单位
				$warehouse_array = Warehouse::getWareList('json'); //仓库
				break;
			default: break;
		}
		$model->type = $type;
		$baseform->owned_by = currentUserId();
		
		//公司
		$title_array = DictTitle::getComs('json'); 
		//业务组
		$team_array = Team::getTeamList('array');
		//业务员
		$user_array = User::getUserList('array');
		
		$this->render('create', array(
				'model' => $model, 
				'baseform' => $baseform,
				'company_array' => $company_array,
				'warehouse_array' => $warehouse_array,
				'title_array' => $title_array,
				'team_array' => $team_array,
				'user_array' => $user_array,
				'back_url' => $back_url,
		));
	}
	
	public function actionUpdate($id) 
	{
		$baseform = CommonForms::model()->with('billRebate')->findByPK($id);
		if (!$baseform) return false;
		$model = $baseform->billRebate;

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('billRebate/index', array('type' => $model->type, 'page' => $fpage));
		
		if (isset($_POST['BillRebate']))
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}
			else
			{
				$data = BillRebate::getInputData($_POST);
				if ($data)
				{
					$form = new BillRebateClass($id);
					if ($form->updateForm($data) && $data['common']->submit == 'yes') 
						$form->submitForm();
					
					$this->redirect($back_url);
				}
			}
		}
		
		$this->pageTitle = "修改";
		switch ($model->type)
		{
			case 'warehouse':
				$this->pageTitle .= "仓库返利";
				$company_array = DictCompany::getVendorList('json', 'is_warehouse'); //仓库结算单位
				$warehouse_array = Warehouse::getWareList('json'); //仓库
				break;
			case 'supply':
				$this->pageTitle .= "钢厂返利";
				$company_array = DictCompany::getVendorList('json', 'is_supply'); //供应商
				break;
			case 'cost':
				$this->pageTitle .= "仓储费用";
				$company_array = DictCompany::getVendorList('json', 'is_warehouse'); //仓库结算单位
				$warehouse_array = Warehouse::getWareList('json'); //仓库
				break;
			default:
				break;
		}
		
		$title_array = DictTitle::getComs('json'); //公司
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getUserList('array'); //业务员
		
		$this->render('update', array(
				'model' => $model,
				'baseform' => $baseform, 
				'company_array' => $company_array,
				'warehouse_array' => $warehouse_array,
				'title_array' => $title_array,
				'team_array' => $team_array,
				'user_array' => $user_array,
				'back_url' => $back_url,
				'msg' => $msg,
		));
	}
	
	//提交|取消提交
	public function actionSubmit($id, $type) 
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$form = new BillRebateClass($id);
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
		
		$form = new BillRebateClass($id);
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
		echo 'success';
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
		$form = new BillRebateClass($id);
		$result = $form->deleteForm($str);
		if (!$result) return false;
		echo 'success';
	}
	
	public function actionIndex($type) 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "30px"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "120px"),
        		array('name' => "单号", 'class' => "sort-disabled", 'width' => "150px"),
				array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "60px"),
        		array('name' => "登记日期", 'class' => "flex-col sort-disabled", 'width' => "140px"),
        		array('name' => "公司", 'class' => "flex-col sort-disabled", 'width' => "110px"),
		);
        switch ($type) 
        {
        	case 'warehouse': //仓库返利
        		$this->pageTitle = "仓库返利";
        		$tableHeader = array_merge($tableHeader, array(
        				array('name' => "仓库结算单位", 'class' => "flex-col sort-disabled", 'width' => "110px"),
        				array('name' => "仓库", 'class' => "flex-col sort-disabled", 'width' => "110px"),
        		));
        		$company_array = DictCompany::getVendorList('json', 'is_warehouse'); //仓库结算单位
        		break;
        	case 'supply': //钢厂返利
        		$this->pageTitle = "钢厂返利";
        		$tableHeader = array_merge($tableHeader, array(
        				array('name' => "供应商", 'class' => "flex-col sort-disabled", 'width' => "110px"),
        		));
        		$company_array = DictCompany::getVendorList('json', 'is_supply'); //供应商
        		break;
        	case 'cost': //仓储费用
        		$this->pageTitle = "仓储费用";
        		$tableHeader = array_merge($tableHeader, array(
        				array('name' => "仓库结算单位", 'class' => "flex-col sort-disabled", 'width' => "110px"),
        				array('name' => "仓库", 'class' => "flex-col sort-disabled", 'width' => "110px"),
        		));
        		$company_array = DictCompany::getVendorList('json', 'is_warehouse'); //仓库结算单位
        		break;
        	default: 
        		return false;
        		break;
        }
		$tableHeader = array_merge($tableHeader, array(
				array('name' => "金额", 'class' => "flex-col sort-disabled text-right", 'width' => "150px"),
				array('name' => "开始日期", 'class' => "flex-col sort-disabled", 'width' => "140px"),
				array('name' => "结束日期", 'class' => "flex-col sort-disabled", 'width' => "140px"),
        		//array('name' => "业务员", 'class' => "flex-col sort-disabled", 'width' => "140px"), 
        		array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "140px"),
				array('name' => "审核人", 'class' => "flex-col sort-disabled", 'width' => "140px"),
				array('name' => "审核时间", 'class' => "flex-col sort-disabled", 'width' => "140px"),
				array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "230px"),//
        ));
		if (isset($_POST['search']) && $_POST['search']['form_status'] == 'delete')
			$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "200px");
		
        $search = isset($_POST['search']) ? $_POST['search'] : array();
        list($tableData, $pages) = BillRebate::getFormList($search, $type);
        
        //公司抬头
        $title_array = DictTitle::getComs('json');
        
        $this->render('index', array(
        		'type' => $type, 
        		'tableHeader' => $tableHeader, 
        		'tableData' => $tableData, 
        		'pages' => $pages, 
        		'search' => $search,
        		'title_array' => $title_array,
        		'company_array' => $company_array
        ));
	}
	
	//获取时间段内未审核的采购单个数
	public function actionGetPurchaseCount() 
	{
		$type = $_REQUEST['type'];
		$title_id = intval($_REQUEST['title_id']);
		$company_id = intval($_REQUEST['company_id']);
		$warehouse_id = intval($_REQUEST['warehouse_id']);
		$start_time = strtotime($_REQUEST['start_time'].' 00:00:00');
		$end_time = strtotime($_REQUEST['end_time'].' 23:59:59');
		
		$model = new FrmPurchase();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		$criteria->select = "count(*) as total_num";
		
		$criteria->addCondition('title_id = :title_id');
		$criteria->params[':title_id'] = $title_id;
		switch ($type)
		{
			case 'warehouse': //仓库返利
				$criteria->addCondition('warehouse_id = :warehouse_id');
				$criteria->params[':warehouse_id'] = $warehouse_id;
				$equally_name = 'ware_rebate';
				break;
			case 'supply': //钢厂返利
				$criteria->addCondition('supply_id = :supply_id');
				$criteria->params[':supply_id'] = $company_id;
				$equally_name = 'rebate';
				break;
			case 'cost': //仓储费用
				$criteria->addCondition('warehouse_id = :warehouse_id');
				$criteria->params[':warehouse_id'] = $warehouse_id;
				$equally_name = 'ware_cost';
				break;
			default: 
				return false;
				break;
		}
		$criteria->addCondition('baseform.created_at >= :start_time && baseform.created_at <= :end_time');
		$criteria->params[':start_time'] = $start_time;
		$criteria->params[':end_time'] = $end_time;
		$criteria->compare("baseform.form_status", array('unsubmit', 'submited'), true);
		$total = $model->find($criteria);
		echo $total->total_num;
	}
	
	//获取最大结束时间
	public function actionGetMaxTime($type) 
	{
		$title_id = intval($_REQUEST['title_id']);
		$company_id = intval($_REQUEST['company_id']);
		
		$model = new BillRebate();
		$criteria = new CDbCriteria();
		$criteria->select = "max(end_time) as end_time";
		switch ($type) 
        {
        	case 'warehouse': //仓库返利
        		$form_type = "CKFL";
        		$criteria->with = array('baseformCKFL');
        		$_baseform = "baseformCKFL";
        		break;
        	case 'supply': //钢厂返利
        		$form_type = "GCFL";
        		$criteria->with = array('baseformGCFL');
        		$_baseform = "baseformGCFL";
        		break;
        	case 'cost': //仓储费用
        		$form_type = "CCFY";
        		$criteria->with = array('baseformCCFY');
        		$_baseform = "baseformCCFY";
        		break;
        	default: 
        		break;
        }
        $criteria->addCondition("t.title_id = :title_id");
        $criteria->params[':title_id'] = $title_id;
        $criteria->addCondition("t.company_id = :company_id");
        $criteria->params[':company_id'] = $company_id;
        
        $criteria->addCondition("t.type = :type");
        $criteria->params[':type'] = $type;
        $criteria->compare("$_baseform.form_type", $form_type, true);
        $criteria->compare("$_baseform.is_deleted", '0', true);
        
        $max = $model->find($criteria);
        echo $max ? date('Y-m-d', $max->end_time) : '';
	}
	
	//获取仓库信息
	public function actionGetWareData(){
		$id = $_GET['company_id'];
		$company = DictCompany::model()->findByPk($id);
		if($company){
			$warehouse_id = $company->warehouse_id;
			$warehouse = Warehouse::model()->findByPk($warehouse_id);
			if($warehouse_id){
				echo $warehouse->name.",".$warehouse_id;
			}else{
				echo ",0";
			}
		}else{
			echo ",0";
		}
	}
}