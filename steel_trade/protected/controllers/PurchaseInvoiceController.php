<?php
/**
 * 采购销票
 * @author leitao
 *
 */
class PurchaseInvoiceController extends AdminBaseController
{
	public function actionView($id)
	{
		$baseform = CommonForms::model()->with('purchaseInvoice')->findByPk($id);
		if (!$baseform) return false;
		$this->pageTitle = "查看销票记录 ".$baseform->form_sn;
		$model = $baseform->purchaseInvoice;

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl("purchaseInvoice/index", array('page' => $fpage));
		
		//明细
		$details = array();
		foreach ($model->purchaseInvoiceDetails as $item)
		{
			$detailForInvoice = $item->detailForInvoice;
			$relation_form=$detailForInvoice->relation_form;
			switch ($relation_form->form_type)
			{
				case 'CGD': //采购
					$form_mod = $relation_form->purchase;
					$form_detail = $detailForInvoice->purchaseDetail;
					break;
				case 'CGTH':
					$form_mod = $relation_form->purchaseReturn;
					$form_detail = $detailForInvoice->purchaseReturnDetail;
					break;
				default:
					break;
			}
			$detail['id'] = $item->id;
			$detail['purchase_detail_id'] = $item->purchase_detail_id;
			$detail['form_sn'] = $relation_form->form_sn;
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
			$details[] = (Object)$detail;
		}
		
		$this->render('view', array(
				'model' => $model, 
				'baseform' => $baseform,
				'details' => $details,
				'back_url' => $back_url,
		));
	}
	
	public function actionCreate()
	{
		$back_url = Yii::app()->createUrl('purchaseInvoice/index');
		
		if (isset($_POST['FrmPurchaseInvoice']))
		{
			$data = FrmPurchaseInvoice::getInputData($_POST);
			if ($data)
			{
				$purchaseInvoice = new PurchaseInvoice($id);
				$purchaseInvoice->createForm($data);
				$purchaseInvoice->submitForm();
		
				$this->redirect($back_url);
			}
		}
		
		$this->pageTitle = "新建销票记录";
		$model = new FrmPurchaseInvoice();
		$baseform = new CommonForms();
		$baseform->owned_by = currentUserId();
		
		$title_array = DictTitle::getComs('json'); //收票单位
		$company_array = DictCompany::getAllComs('json'); //销票单位
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getUserList('array'); //业务员
		
		$this->render('create', array(
				'model' => $model, 
				'baseform' => $baseform, 
				'company_array' => $company_array,
				'title_array' => $title_array,
				'team_array' => $team_array, //业务组
				'user_array' => $user_array, //业务员
				'back_url' => $back_url,
		));
	}

	public function actionUpdate($id)
	{
		$baseform = CommonForms::model()->with('purchaseInvoice')->findByPk($id);
		if (!$baseform) return false;

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('purchaseInvoice/index', array('page' => $fpage));
		
		if (isset($_POST['FrmPurchaseInvoice']))
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}
			else
			{
				$data = FrmPurchaseInvoice::getInputData($_POST);
				if ($data)
				{
					$purchaseInvoice = new PurchaseInvoice($id);
					$purchaseInvoice->cancelSubmitForm();
					$purchaseInvoice->updateForm($data);
					$purchaseInvoice->submitForm();
		
					$this->redirect($back_url);
				}
			}
		}
		
		$this->pageTitle = "修改销票记录";
		$model = $baseform->purchaseInvoice;
		//明细
		$details = array();
		foreach ($model->purchaseInvoiceDetails as $item) 
		{
			$detailForInvoice = $item->detailForInvoice;
			$relation_form=$detailForInvoice->relation_form;
			switch ($relation_form->form_type) 
			{
				case 'CGD': //采购
					$type = "采购单";
					$form_mod = $relation_form->purchase;
					$form_detail = $detailForInvoice->purchaseDetail;
					break;
				case 'CGTH':
					$form_mod = $relation_form->purchaseReturn;
					$form_detail = $detailForInvoice->purchaseReturnDetail;
					break;
				default:
					break;
			}
			$detail['id'] = $item->id;
			$detail['purchase_detail_id'] = $item->purchase_detail_id;
			$detail['form_sn'] = $relation_form->form_sn;
			$detail['title'] = $form_mod->title;
			$detail['company'] = $form_mod->company;
			$detail['pledgeCompany'] = $relation_form->form_type=="CGD"?$form_mod->pledge->pledgeCompany:'';
			$detail['product_name'] = $form_detail ? DictGoodsProperty::getProName($form_detail->product_id) : '';
			$detail['brand'] = $form_detail ? DictGoodsProperty::getProName($form_detail->brand_id) : '';
			$detail['texture'] = $form_detail ? DictGoodsProperty::getProName($form_detail->texture_id) : '';
			$detail['rank'] = $form_detail ? DictGoodsProperty::getProName($form_detail->rank_id) : '';
			$detail['length'] = $form_detail ? $form_detail->length : '';
			$detail['weight'] = $item->weight;
			$detail['fee'] = $item->fee;
			$detail['needWeight'] = floatval($detailForInvoice->weight) - floatval($detailForInvoice->checked_weight);
			$detail['needMoney'] = floatval($detailForInvoice->money) - floatval($detailForInvoice->checked_money);
			$detail['belong'] = $relation_form->belong;
			$detail['type'] = $type;
			$details[] = (Object)$detail;
		}
		
		//收票单位
		$title_array = DictTitle::getComs('json');
		//销票单位
		$company_array = DictCompany::getAllComs('json');
		
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
		
		$form = new PurchaseInvoice($id);
		$result = $form->cancelSubmitForm();
		$result = $form->deleteForm($str);
		if (!$result) return false;
		echo 'success';
	}
	
	//销票
	public function actionCapias($id, $type) 
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('purchaseInvoice/index', array('page' => $fpage));
		
		$form = new PurchaseInvoice($id);
		switch ($type) 
		{
			case 'capias':
				if (isset($_POST['FrmPurchaseInvoice']))
				{
					$last_update = $_POST['last_update'];
					if ($last_update != $baseform->last_update) 
					{
						$msg = "您看到的信息不是最新的，请刷新后再试";
					} 
					else 
					{
						$data = FrmPurchaseInvoice::getInputData($_POST);
						if ($data)
						{
							$form->cancelSubmitForm();
							$form->updateForm($data);
							$form->submitForm();
							$form->capiasForm();
							$this->redirect($back_url);
						}
					}
				}
				$this->pageTitle = "销票";
				$model = $baseform->purchaseInvoice;
				//明细
				$details = array();
				foreach ($model->purchaseInvoiceDetails as $item)
				{
					$detailForInvoice = $item->detailForInvoice;
					$relation_form=$detailForInvoice->relation_form;
					switch ($relation_form->form_type)
					{
						case 'CGD': //采购
							$type = "采购单";
							$form_mod = $relation_form->purchase;
							$form_detail = $detailForInvoice->purchaseDetail;
							break;
						case 'CGTH':
							$form_mod = $relation_form->purchaseReturn;
							$form_detail = $detailForInvoice->purchaseReturnDetail;
							break;
						default:
							break;
					}
					$detail['id'] = $item->id;
					$detail['purchase_detail_id'] = $item->purchase_detail_id;
					$detail['form_sn'] = $relation_form->form_sn;
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
					$detail['belong'] = $relation_form->belong;
					$detail['type'] = $type;
					$details[] = (Object)$detail;
				}
// 				//收票单位
// 				$company_array = DictCompany::getVendorList('json', 'is_supply');
// 				//销票单位
// 				$title_array = DictTitle::getComs('json');
				
				//业务组
				$team_array = Team::getTeamList('array');
				//业务员
				$user_array = User::getUserList('array');
				
				$this->render('capias', array(
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
				
				$form->cancelCapiasForm(); 
				echo 'success';
				break;
			default:
				return false;
				break;
		}
	}
	
	public function actionIndex()
	{
		$this->pageTitle = "采购销票";
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "30px"),
				array('name' => "操作", 'class' => "sort-disabled", 'width' => "120px"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "150px"),
				array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "120px"),
				array('name' => "登记日期", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "收票单位", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "销票单位", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "销票重量", 'class' => "flex-col sort-disabled text-right", 'width' => "120px"),
				array('name' => "销票金额", 'class' => "flex-col sort-disabled text-right", 'width' => "120px"),
				array('name' => "销票总重量", 'class' => "flex-col sort-disabled text-right", 'width' => "120px"),
				array('name' => "销票总金额", 'class' => "flex-col sort-disabled text-right", 'width' => "120px"),
				array('name' => "销票单据类型", 'class' => "flex-col sort-disabled", 'width' => "120px"),
				array('name' => "销票单据单号", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "销票张数", 'class' => "flex-col sort-disabled text-right", 'width' => "120px"),
				array('name' => "票号", 'class' => "flex-col sort-disabled", 'width' => "120px"),
				array('name' => "销票日期", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "业务员", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "230px")
		);	
		if (isset($_POST['search']) && $_POST['search']['form_status'] == 'delete')
			$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "200px");
			
		
		$search = isset($_POST['search']) ? $_POST['search'] : array();
		list($tableData, $pages, $totalData) = FrmPurchaseInvoice::getInvoiceList($search);
	
		//收票单位
		$company_array = DictCompany::getAllVendorList('json', 'is_supply');
		//销票单位
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
	
	public function actionGetSimpleList() {
		//搜索
		$search = array(
				'id' => $_REQUEST['id'],
				'company_id' => $_REQUEST['company_id'],
				'title_id' => $_REQUEST['title_id'],
				'keywords' => $_REQUEST['keywords'],
				'search_begin' => $_REQUEST['search_begin'],
				'search_end' => $_REQUEST['search_end'],
				'type' => $_REQUEST['type'],
// 				'owned_by' => $_REQUEST['owned_by']
		);
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "操作", 'class' => "sort-disabled", 'width' => "4%"),
				array('name' => "单号", 'class' => "flex-col sort-disabled", 'width' => "9%"),
				array('name' => "公司",'class' => "flex-col sort-disabled", 'width' => "8%"),
				array('name' => "供应商",'class' => "flex-col sort-disabled", 'width' => "8%"),
				array('name' => "托盘公司",'class' => "flex-col sort-disabled", 'width' => "8%"),
				array('name' => "产地/品名/材质/规格/长度", 'class' => "sort-disabled", 'width' => "16%"),
				array('name' => "已销票重量", 'class' => "flex-col sort-disabled text-right",'width' => "9%"),
				array('name' => "已销票金额", 'class' => "flex-col sort-disabled text-right",'width' => "9%"),
				array('name' => "总重量", 'class' => "flex-col sort-disabled text-right",'width'=>"9%"),
				array('name' => "金额", 'class' => "flex-col sort-disabled text-right", 'width'=>"9%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "8%"),
		);
		
		list($totalData,$tableData, $pages) = DetailForInvoice::getSimpleList($search, array('CGTH','CGD'));
		if($search['company_id'] > 0 && $search['title_id'] > 0){
			$yu = Turnover::getTurYu($search['title_id'],$search['company_id']);
			$str = '<a target="_blank" href="/index.php/turnover/index?title_id='.$search['title_id'].'&target_id='.$search['company_id'].'&start_time=&end_time=&is_yidan=">'.$yu."</a>";
			$arr_yu = array("data"=>array("","","往来余额：","","","","","","","",$str,""),"group"=>0);
			array_unshift($tableData,$arr_yu);
		}
		$this->renderPartial('formList', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'totalData'=>$totalData,
				'pages' => $pages,
		));
	}

	public function actionExport() 
	{
		$search = $_REQUEST['search'];
		$name = "采购销票".date("Y/m/d");
		$title = array('销票单号', '登记日期', '收票单位', '销票单位', '销票重量', '销票金额', '销票总重量', '销票总金额', '审批状态', '销票状态', '销票单据类型', '销票单据单号', '销票张数', '票号', '销票日期', '业务员', '操作员', '备注');
		if ($search['form_status'] == 'delete') array_push($title, "作废原因");
		
		$content = FrmPurchaseInvoice::getAllList($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}
	
}