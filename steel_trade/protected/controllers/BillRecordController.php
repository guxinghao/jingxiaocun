<?php

class BillRecordController extends AdminBaseController
{
	
	public function actionCreate($common_id)
	{
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('billRecord/index', array('frm_common_id' => $common_id, 'page' => $fpage));
		
		if (isset($_POST['BillRecord']))
		{
			$bcount = BillRecord::model()->with('baseform')->count("frm_common_id = :frm_common_id AND baseform.form_status <> 'delete'", array(':frm_common_id' => $common_id));
			if ($bcount > 0)
			{
				$msg = "该单已存在运费，无法创建";
			}
			else
			{
				$data = BillRecord::getFormInput($_POST);
				if ($data)
				{
					$form = new BillRecordClass($id);
					if ($form->createForm($data) && $data['common']->submit == 'yes') $form->submitForm();
					$this->redirect($back_url);
				}
			}
		}
		
		$this->pageTitle = "新增运费";
		$model = new BillRecord();
		$model->frm_common_id = $common_id;
		$baseform = new CommonForms();
		
		$relationForm = $model->relationForm;
		$baseform->owned_by = $relationForm->owned_by;
		switch ($relationForm->form_type) 
		{
			case 'CGD': 
				$type = "purchase";
				$purchase = $relationForm->purchase;
				$model->bill_type = 'purchase';
				$model->title_id = $purchase->title_id;
				$model->weight = $purchase->weight;
				$model->is_yidan = $purchase->is_yidan;
				$model->travel = $purchase->transfer_number;
				break;
			case 'XSD': 
				$type = "sales";
				$sales = $relationForm->sales;
				$model->bill_type = 'sales';
				$model->title_id = $sales->title_id;
				$model->weight = $sales->weight;
				$model->is_yidan = $sales->is_yidan;
				$model->travel = $sales->travel;
				break;
		}
		//收益单位
		$company_array = DictCompany::getVendorList('json', "is_logistics"); //物流商
		
		$this->render("create", array(
				'model' => $model, 
				'baseform' => $baseform,
				'company_array' => $company_array, 
				'back_url' => $back_url, 
				'msg' => $msg
		));
	}
	
	public function actionUpdate($id) 
	{
		$baseform = CommonForms::model()->with('billRecord')->findByPk($id);
		if (!$baseform) return false;
		$model = $baseform->billRecord;
		
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('billRecord/index', array('frm_common_id' => $model->frm_common_id, 'page' => $fpage));
		
		if (isset($_POST['BillRecord']))
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}
			else
			{
				$data = BillRecord::getFormInput($_POST);
				if ($data)
				{
					$form = new BillRecordClass($id);
					if ($form->updateForm($data) && $data['common']->submit == 'yes') $form->submitForm();
					$this->redirect($back_url);
				}
			}
		}
		$this->pageTitle = "修改运费";
		
		//收益单位
		$company_array = DictCompany::getVendorList('json', "is_logistics"); //物流商
		
		$this->render("update", array(
				'model' => $model, 
				'baseform' => $baseform,
				'company_array' => $company_array,
				'back_url' => $back_url,
				'msg' => $msg
		));
	}
	
	public function actionSubmit($id, $type)
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		$form = new BillRecordClass($id);
		switch ($type) 
		{
			case 'submit':
				$result = $form->submitForm();
				break;
			case 'cancle':
				$result = $form->cancelSubmitForm();
				break;
			default: 
				return false;
				break;
		}
		if (!$result) return false;
		echo 'success';
	}
	
	public function actionDeleteForm($id)
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		$reason = $_REQUEST['str'];
		$form = new BillRecordClass($id);
		$result = $form->deleteForm($reason);
		if (!$result) return false;
		echo 'success';
	}
	
	public function actionCheck()
	{
		$id = $_REQUEST['id'];
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$type = $_REQUEST['type'];
		$form = new BillRecordClass($id);
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
	
	public function actionSubmitRecord()
	{
		//提交前要判断最后更新时间，如果不是最新提示刷新再操作
		
		
		
		if ($_POST['frm_common_id'])
			$data = BillRecord::getFormInput($_POST);
		echo 'success';
	}
	
	/**
	 * 费用登记
	 */
	public function actionIndex()
	{	
		//搜索和换页
		$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : array();
		
		$common_id = intval($_REQUEST['frm_common_id']);
		if ($common_id) 
		{
			$model = new BillRecord();
			$model->frm_common_id = $common_id;
			$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
			switch ($model->relationForm->form_type)
			{
				case 'CGD':
					$type = "purchase";
					$backUrl = Yii::app()->createUrl('purchase/index', array('page' => $fpage));
					break;
				case 'XSD':
					$type = "sales";
					$backUrl = Yii::app()->createUrl('frmSales/index', array('page' => $fpage));
					break;
				default: break;
			}
		}
		$type = $type ? $type : $_REQUEST['type'];
		switch ($type)
		{
			case 'purchase':
				$this->pageTitle = "采购运费";
				break;
			case 'sales': 
				$this->pageTitle = "销售运费";
				break;
			default: 
				break;
		}
		$search['bill_type'] = $type;
		
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "30px"),
				array('name' => "操作", 'class' => "sort-disabled", 'width' => "120px"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "150px"),
				array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "100px"),
				array('name' => "登记日期", 'class' => "flex-col sort-disabled", 'width' => "120px"),
				array('name' => "相关单号", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "公司", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "收益单位", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "重量", 'class' => "flex-col sort-disabled text-right", 'width' => "150px"),
				array('name' => "单价", 'class' => "flex-col sort-disabled text-right", 'width' => "140px"),
				array('name' => "金额", 'class' => "flex-col sort-disabled text-right", 'width' => "150px"),
				array('name' => "类型", 'class' => "flex-col sort-disabled", 'width' => "140px"), 
				array('name' => "乙单", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				array('name' => "车船号", 'class' => "flex-col sort-disabled", 'width' => "180px"),
				array('name' => "业务员", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "审核人", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "审核时间", 'class' => "flex-col sort-disabled", 'width' => "120px"),
				array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "330px"),
		);
		if (isset($_POST['search']) && $_POST['search']['form_status'] == 'delete')
			$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "230px");
		
		list($tableData, $pages, $totalData) = BillRecord::getFormList($search, $common_id);
		
		//公司
		$title_array = DictTitle::getComs('json');
		//收益单位
		$logistic_array = DictCompany::getVendorList("json","is_logistics"); //物流商
		
		$this->render('index', array(
				'tableHeader' => $tableHeader, 
				'tableData' => $tableData,
				'totalData' => $totalData,
				'pages' => $pages,
				'search' => $search,
				'title_array' => $title_array,
				'logistic_array' => $logistic_array,
				'common_id' => $common_id,
				'backUrl'=> $backUrl
		));
	}
	
	//判断运费是否已关联付款
	public function actionCheckSelected($id) 
	{
		$baseform = CommonForms::model()->with('billRecord')->findByPK($id);
		if (!$baseform) return false;
		echo $baseform->billRecord->is_selected;
	}
	
	/**
	 * 获取采购单或销售单总重量
	 */
	public function actionGetWeight()
	{
		$frm_common_id = intval($_REQUEST['frm_common_id']);
		$title = intval($_REQUEST['title']);//0表示总的采购销售单
		$type = $_REQUEST['type'];//采购还是销售
		
		if ($type == "purchase"){
			$object = new Purchase($frm_common_id);
			
		}else{
			$object = new Sales($frm_common_id); 
		}
		
		if (!$title)
			$weight = floatval(numChange($object->mainInfo->weight));
		else {
			foreach ($object->details as $item){
				if ($item->id == $title)
					$weight = floatval(numChange($item->weight));
			}
		}
		echo number_format($weight,3,".","");
	}

}
