<?php
/**
 * 销售开票
 * @author leitao
 *
 */
class SalesInvoiceController extends AdminBaseController
{
	public function actionView($id)
	{
		$baseform = CommonForms::model()->with('salesInvoice')->findByPk($id);
		$model = $baseform->salesInvoice;
		$this->pageTitle = "查看开票记录 ".$baseform->form_sn;
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl("salesInvoice/index", array('page' => $fpage));
		
		//明细
		$details = array();
		foreach ($model->salesInvoiceDetails as $item)
		{
			$detailForInvoice = $item->detailForInvoice;
			switch ($detailForInvoice->type)
			{
				case 'sales': //销售
					$form_mod = $detailForInvoice->relation_form->sales;
					$form_detail = $detailForInvoice->salesDetail;
					break;
				case 'salesreturn': //销售退货
					$form_mod = $detailForInvoice->relation_form->salesReturn;
					$form_detail = $detailForInvoice->salesReturnDetail;
					break;
				case 'rebate': //销售折让
					$form_mod = $detailForInvoice->relation_form->rebate;
					break;
				default:
					break;
			}
			$detail['id'] = $item->id;
			$detail['sales_detail_id'] = $item->sales_detail_id;
			$detail['form_sn'] = $detailForInvoice->relation_form->form_sn;
			$detail['title'] = $form_mod->title;
			$detail['company'] = $form_mod->company;
			$detail['product_name'] = $form_detail ? DictGoodsProperty::getProName($form_detail->product_id) : '';
			$detail['brand'] = $form_detail ? DictGoodsProperty::getProName($form_detail->brand_id) : '';
			$detail['texture'] = $form_detail ? DictGoodsProperty::getProName($form_detail->texture_id) : '';
			$detail['rank'] = $form_detail ? DictGoodsProperty::getProName($form_detail->rank_id) : '';
			$detail['length'] = $form_detail ? $form_detail->length : '';
			$detail['weight'] = $item->weight;
			$detail['fee'] = $item->fee;
			$detail['needWeight'] = floatval($detailForInvoice->weight) - floatval($detailForInvoice->checked_weight);
			$detail['needMoney'] = floatval($detailForInvoice->money) - floatval($detailForInvoice->checked_money);
			$detail['type'] = $detailForInvoice->type;
			$detail['client'] = $model->client;
			$detail['client_id'] = $model->client_id;
			$details[] = (Object)$detail;
		}
		
		$this->render('view', array(
				'model' => $model,
				'details' => $details,
				'back_url' => $back_url,
		));
	}
	
	public function actionCreate()
	{
		$back_url = Yii::app()->createUrl('salesInvoice/index');
		$msg='';
		if (isset($_POST['FrmSalesInvoice']))
		{
			$data = FrmSalesInvoice::getInputData($_POST);
			if ($data)
			{
				$salesInvoice = new SalesInvoice($id);
				$res=$salesInvoice->createForm($data);
				if(!is_numeric($res)){
					$msg=$res;
					goto create; 
				}
				$salesInvoice->submitForm();
		
				$this->redirect($back_url);
			}
		}
		create:
		$this->pageTitle = "新建开票记录";
		$model = new FrmSalesInvoice();
		$baseform = $model->baseform ? $model->baseform : CommonForms::model();
		$baseform->owned_by = $baseform->owned_by ? $baseform->owned_by : currentUserId();
		
		if ($_REQUEST['title_id'] || $_REQUEST['company_id']) 
		{
			$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
			$title_id = intval($_REQUEST['title_id']);
			$company_id = intval($_REQUEST['company_id']);
			
			$model->title_id = $title_id;
			$model->company_id = $company_id;
			
			$back_url = Yii::app()->createUrl('frmSales/totaldata', array('page' => $fpage));
		}
		
		//收票单位
		$company_array = DictCompany::getAllVendorList('json', 'is_customer');
		//开票单位
		$title_array = DictTitle::getComs('json');
		
		//业务组
		$team_array = Team::getTeamList('array');
		//业务员
		$user_array = User::getUserList('array');
		
		$this->render('create', array(
				'model' => $model, 
				'baseform' => $baseform,
				'company_array' => $company_array,
				'title_array' => $title_array,
				'team_array' => $team_array,
				'user_array' => $user_array,
				'back_url' => $back_url,
				'msg'=>$msg
		));
	}

	public function actionUpdate($id)
	{
		$baseform = CommonForms::model()->with('salesInvoice')->findByPk($id);
		if (!$baseform) return false;

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('salesInvoice/index', array('page' => $fpage));
		
		if (isset($_POST['FrmSalesInvoice']))
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}
			else
			{
				$data = FrmSalesInvoice::getInputData($_POST);
				if ($data)
				{
					$salesInvoice = new SalesInvoice($id);
					$salesInvoice->cancelSubmitForm();
					$res=$salesInvoice->updateForm($data);
					if($res!==true){
						$msg=$res;
						goto update;
					}
					$salesInvoice->submitForm();
		
					$this->redirect($back_url);
				}
			}
		}
		update:
		$this->pageTitle = "修改开票记录";
		$model = $baseform->salesInvoice;
		//明细
		$details = array();
		foreach ($model->salesInvoiceDetails as $item) 
		{
			$detailForInvoice = $item->detailForInvoice;
			switch ($detailForInvoice->type) 
			{
				case 'sales': //销售
					$form_mod = $detailForInvoice->relation_form->sales;
					$form_detail = $detailForInvoice->salesDetail;
					break;
				case 'salesreturn': //销售退货
					$form_mod = $detailForInvoice->relation_form->salesReturn;
					$form_detail = $detailForInvoice->salesReturnDetail;
					break;
				case 'rebate': //销售折让
					$form_mod = $detailForInvoice->relation_form->rebate;
					break;
				default:
					break;
			}
			$detail['id'] = $item->id;
			$detail['sales_detail_id'] = $item->sales_detail_id;
			$detail['form_sn'] = $detailForInvoice->relation_form->form_sn;
			$detail['title'] = $form_mod->title;
			$detail['company'] = $form_mod->company;
			$detail['product_name'] = $form_detail ? DictGoodsProperty::getProName($form_detail->product_id) : '';
			$detail['brand'] = $form_detail ? DictGoodsProperty::getProName($form_detail->brand_id) : '';
			$detail['texture'] = $form_detail ? DictGoodsProperty::getProName($form_detail->texture_id) : '';
			$detail['rank'] = $form_detail ? DictGoodsProperty::getProName($form_detail->rank_id) : '';
			$detail['length'] = $form_detail ? $form_detail->length : '';
			$detail['weight'] = $item->weight;
			$detail['fee'] = $item->fee;
			$detail['needWeight'] = floatval($detailForInvoice->weight) - floatval($detailForInvoice->checked_weight);
			$detail['needMoney'] = floatval($detailForInvoice->money) - floatval($detailForInvoice->checked_money);
			$detail['belong'] = $detailForInvoice->relation_form->belong;
			$detail['type'] = $detailForInvoice->type;
			$detail['client'] = $model->client;
			$detail['client_id'] = $model->client_id;
			$details[] = (Object)$detail;
		}
		
		//收票单位
		$company_array = DictCompany::getAllVendorList('json', 'is_customer');
		//开票单位
		$title_array = DictTitle::getComs('json');
		
		//业务组
		$team_array = Team::getTeamList('array');
		//业务员
		$user_array = User::getUserList('array');
		
		$this->render('update', array(
				'model' => $model, 
				'baseform' => $baseform, 
				'company_array' => $company_array,
				'title_array' => $title_array,
				'team_array' => $team_array, //业务组
				'user_array' => $user_array, //业务员
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
		
		$form = new SalesInvoice($id);
		$result = $form->cancelSubmitForm();
		$result = $form->deleteForm($str);
		if (!$result) return false;
		echo 'success';
	}
	
	//开票
	public function actionInvoice($id, $type) 
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('salesInvoice/index', array('page' => $fpage));
		
		$form = new SalesInvoice($id);
		switch ($type) 
		{
			case 'invoice': 
				if (isset($_POST['FrmSalesInvoice']))
				{
					$last_update = $_POST['last_update'];
					if ($last_update != $baseform->last_update) 
					{
						$msg = "您看到的信息不是最新的，请刷新后再试";
					} 
					else 
					{
						$data = FrmSalesInvoice::getInputData($_POST);
						if ($data)
						{
							$form->cancelSubmitForm();
							$form->updateForm($data);
							$form->submitForm();
							$form->invoiceForm();
							$this->redirect($back_url);
						}
					}
				}
				$this->pageTitle = "开票";
				$model = $baseform->salesInvoice;
				//明细
				$details = array();
				foreach ($model->salesInvoiceDetails as $item)
				{
					$detailForInvoice = $item->detailForInvoice;
					switch ($detailForInvoice->type)
					{
						case 'sales': //销售
							$type = "销售单";
							$form_mod = $detailForInvoice->relation_form->sales;
							$form_detail = $detailForInvoice->salesDetail;
							break;
						case 'salesreturn': //销售退货
							$type = "销售退货单";
							$form_mod = $detailForInvoice->relation_form->salesReturn;
							$form_detail = $detailForInvoice->salesReturnDetail;
							break;
						case 'rebate': //销售折让
							$type = "销售折让";
							$form_mod = $detailForInvoice->relation_form->rebate;
						default:
							break;
					}
					$detail['id'] = $item->id;
					$detail['sales_detail_id'] = $item->sales_detail_id;
					$detail['form_sn'] = $detailForInvoice->relation_form->form_sn;
					$detail['title'] = $form_mod->title;
					$detail['company'] = $form_mod->company;
					$detail['product_name'] = $form_detail ? DictGoodsProperty::getProName($form_detail->product_id) : '';
					$detail['brand'] = $form_detail ? DictGoodsProperty::getProName($form_detail->brand_id) : '';
					$detail['texture'] = $form_detail ? DictGoodsProperty::getProName($form_detail->texture_id) : '';
					$detail['rank'] = $form_detail ? DictGoodsProperty::getProName($form_detail->rank_id) : '';
					$detail['length'] = $form_detail ? $form_detail->length : '';
					$detail['weight'] = $item->weight;
					$detail['fee'] = $item->fee;
					$detail['needWeight'] = floatval($detailForInvoice->weight) - floatval($detailForInvoice->checked_weight);
					$detail['needMoney'] = floatval($detailForInvoice->money) - floatval($detailForInvoice->checked_money);
					$detail['belong'] = $detailForInvoice->relation_form->belong;
					$detail['type'] = $detailForInvoice->type;
					$detail['client'] = $model->client;
					$detail['client_id'] = $model->client_id;
					$details[] = (Object)$detail;
				}
				
// 				//收票单位
// 				$company_array = DictCompany::getVendorList('json', 'is_customer');
// 				//开票单位
// 				$title_array = DictTitle::getComs('json');
				
				//业务组
				$team_array = Team::getTeamList('array');
				//业务员
				$user_array = User::getUserList('array');
				
				$this->render('invoice', array(
						'model' => $model, 
						'baseform' => $baseform, 
// 						'company_array' => $company_array,
// 						'title_array' => $title_array,
						'team_array' => $team_array, //业务组
						'user_array' => $user_array, //业务员
						'details' => $details,
						'back_url' => $back_url,
						'msg' => $msg
				));
				break;
			case 'cancle':	
				$last_update = $_REQUEST['last_update'];
				if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
				
				$form->cancelInvoiceForm();
				echo 'success';
				break;
			default: 
				return false;
				break;
		}
	}
	
	public function actionIndex()
	{
		$this->pageTitle = "销售开票";
		$search = isset($_POST['search']) ? $_POST['search'] : array();
		
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled text-center", 'width' => "24px"),
				array('name' => "操作", 'class' => "sort-disabled", 'width' => "80px"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "100px"),
				array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "开票日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "收票单位", 'class' => "flex-col sort-disabled", 'width' => "100px"),
				array('name' => "开票单位", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				array('name' => "开票重量", 'class' => "flex-col sort-disabled text-right", 'width' => "70px"),
				array('name' => "开票金额", 'class' => "flex-col sort-disabled text-right", 'width' => "90px"),
				array('name' => "开票总重量", 'class' => "flex-col sort-disabled text-right", 'width' => "75px"),
				array('name' => "开票总金额", 'class' => "flex-col sort-disabled text-right", 'width' => "90px"),				
				array('name' => "开票单据单号", 'class' => "flex-col sort-disabled", 'width' => "100px"),
				array('name' => "开票张数", 'class' => "flex-col sort-disabled text-right", 'width' => "60px"),
				array('name' => "票号", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "客户", 'class' => "flex-col sort-disabled", 'width' => "100px"),
				array('name' => "开票单据类型", 'class' => "flex-col sort-disabled", 'width' => "90px"),
				// array('name' => "开票日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "业务员", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "230px")
		);
		if (isset($_POST['search']) && $_POST['search']['form_status'] == 'delete')
			$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "200px");
			$search=updateSearch($search,'search_salesinvoice_index');
		list($tableData, $pages, $totalData) = FrmSalesInvoice::getInvoiceList($search);
	
		//收票单位
		$company_array = DictCompany::getAllVendorList('json', 'is_customer');
		//开票单位
		$title_array = DictTitle::getComs('json');
		//业务员
		$user_array = User::getUserList('array');
		
		$this->render('index', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'totalData' => $totalData,
				'pages' => $pages,
				'search' => $search,
				'company_array' => $company_array,
				'title_array' => $title_array, 
				'user_array' => $user_array
		));
	}
	
	public function actionGetSimpleList() 
	{
		//搜索
		$search = array(
				'id' => $_REQUEST['id'],
				'company_id' => $_REQUEST['company_id'],
				'client_id' => $_REQUEST['client_id'],
				'title_id' => $_REQUEST['title_id'],
				'keywords' => $_REQUEST['keywords'],
				'search_begin' => $_REQUEST['search_begin'],
				'search_end' => $_REQUEST['search_end'],
				'type' => $_REQUEST['type'], 
				'owned_by' => $_REQUEST['owned_by']
		);
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled"),
				array('name' => "操作", 'class' => "sort-disabled"),
				array('name' => "单号", 'class' => "sort-disabled"),
				array('name' => "公司",'class' => "sort-disabled"),
				array('name' => "结算单位",'class' => "sort-disabled"),
				array('name' => "产地/品名/材质/规格/长度", 'class' => "sort-disabled"),
				array('name' => "已开票重量", 'class' => "sort-disabled text-right"),
				array('name' => "已开票金额", 'class' => "sort-disabled text-right"),
				array('name' => "总重量", 'class' => "sort-disabled text-right"),
				array('name' => "总金额", 'class' => "sort-disabled text-right"),
				array('name' => "业务员", 'class' => "sort-disabled"),
				array('name' => "客户",'class' => "sort-disabled"),
		);
		//获取往来余额
		list($totalData,$tableData, $pages) = DetailForInvoice::getSimpleList($search, array('XSD','XSTH',"XSZR"));		
		if($search['company_id'] > 0 && $search['title_id'] > 0){
			$yu = Turnover::getTurYu($search['title_id'],$search['company_id']);
			$str = '<a target="_blank" href="/index.php/turnover/index?title_id='.$search['title_id'].'&target_id='.$search['company_id'].'&start_time=&end_time=&is_yidan=">'.number_format($yu,2)."</a>";
			$arr_yu = array("data"=>array("","","往来余额：","","","","","","",$str,"",""),"group"=>0);
			array_unshift($tableData,$arr_yu);
		}
		
		$this->renderPartial('formList', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
		));
	}

	public function actionExport() 
	{
		$search = $_REQUEST['search'];
		$name = "销售开票".date("Y/m/d");
		//$title = array('开票单号', '登记日期', '收票单位', '开票单位', '开票重量', '开票金额', '开票总重量', '开票总金额', '审批状态', '开票状态', '开票单据类型', '开票单据单号', '开票张数', '票号', '开票日期', '业务员', '操作员', '备注');
		$title = array('开票日期','开(销)票公司','品名','重量','金额','发票号','结算单位','业务员','签收人');
		if ($search['form_status'] == 'delete') array_push($title, "作废原因");

		$content = FrmSalesInvoice::getAllList($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}
	
	//随机生成销售单开票信息
	public function actionRandSalesInvoice(){
		$num = 0;
//		$result = FrmSales::RandSales();
		while(true){
			$result = FrmSalesInvoice::RandSalesInvoice();
			if($result){
				$num ++;
				if($num>=1000){
					break;
				}
			}
		}
	}
}