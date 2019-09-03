<?php

/**
 * 短期借贷
 * @author leitao
 *
 */
class ShortLoanController extends AdminBaseController 
{
	public $layout = 'admin';
	
	/**
	 * 详细
	 * @param integer $id
	 */
	public function actionView($id) 
	{
		$baseform = CommonForms::model()->with('shortLoan')->findByPK($id);
		if (!$baseform) return false;

		$this->pageTitle = "查看短期借贷 ".$baseform->form_sn;
		$model = $baseform->shortLoan;
		$loanRecord = $model->loanRecord;

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('shortLoan/index', array('page' => $fpage));
		if ($_REQUEST['back_url']) 
			$back_url = Yii::app()->createUrl($_REQUEST['back_url'].'/index', array('page' => $fpage));
		
		$this->render('view', array(
				'model' => $model,
				'baseform' => $baseform,
				'loanRecord' => $loanRecord,
				'back_url' => $back_url
		));
	}
	
	/**
	 * 新增
	 */
	public function actionCreate() 
	{
		$back_url = Yii::app()->createUrl('shortLoan/index');
		
		if (isset($_POST['ShortLoan'])) 
		{
			$data = ShortLoan::getInputData($_POST);
			if ($data) 
			{
				$form = new ShortLoanClass($id);
				if ($form->createForm($data) && $data['common']->submit == 'yes') $form->submitForm();
				
				$this->redirect($back_url);
			}
		}
		
		$this->pageTitle = "新建短期借贷";
		$model = new ShortLoan();
		$model->unsetAttributes();
		$baseform = new CommonForms();
		$baseform->unsetAttributes();
		$baseform->owned_by = currentUserId(); //业务员默认为登录用户
		
		$title_array = DictTitle::getComs('json'); //公司抬头
		$company_array = DictCompany::getAllComsForDJ('json'); //借贷公司
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getCWUserList('array'); //业务员
		
		$this->render('create', array(
				'model' => $model, 
				'baseform' => $baseform, 
				'title_array' => $title_array, 
				'company_array' => $company_array, 
				'team_array' => $team_array, 
				'user_array' => $user_array, 
				'back_url' => $back_url
		));
	}
	
	/**
	 * 修改
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('shortLoan/index', array('page' => $fpage));
		
		if (isset($_POST['ShortLoan'])) 
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			} 
			else 
			{
				$data = ShortLoan::getInputData($_POST);
				if ($data) 
				{
					$form = new ShortLoanClass($id);
					if ($form->updateForm($data) && $data['common']->submit == 'yes') $form->submitForm();
					
					$this->redirect($back_url);
				}
			}
		}
		$this->pageTitle = "修改短期借贷 ".$baseform->form_sn;
		$model = $baseform->shortLoan;
		
		$title_array = DictTitle::getComs('json'); //公司抬头
		$company_array = DictCompany::getAllComsForDJ('json'); //借贷公司
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getCWUserList('array'); //业务员
		
		$this->render('update', array(
				'model' => $model, 
				'baseform' => $baseform, 
				'title_array' => $title_array,
				'company_array' => $company_array,
				'team_array' => $team_array,
				'user_array' => $user_array,
				'back_url' => $back_url, 
				'msg' => $msg
		));
	}
	
	/**
	 * 提交|取消提交
	 * @param integer $id
	 * @param string $type
	 */
	public function actionSubmit($id, $type) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$form = new ShortLoanClass($id);
		switch ($type) 
		{
			case 'submit': 
				$result = $form->submitForm();
				break;
			case 'cancle': 
				$result = $form->cancelSubmitForm();
				break;
			default: break;
		}
		if (!$result) return false;
		echo 'success';
	}
	
	/**
	 * 审核|取消审核|拒绝
	 */
	public function actionCheck() 
	{
		$id = intval($_REQUEST['id']);
		$type = $_REQUEST['type'];
		
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		//if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$form = new ShortLoanClass($id);
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
			default: break;
		}
		if (!$result) return false;
		echo 'success';
	}
	
	/**
	 * 出入账
	 * @param integer $id
	 * @param string $type
	 */
	public function actionAccounted($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('shortLoan/index', array('page' => $fpage));
		
		if (isset($_POST['ShortLoan']))
		{
			//var_dump($_POST);die;
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
					$data = ShortLoan::getRecordData($_POST);
					if (is_array($data))
					{
						$form = new ShortLoanClass($id);
						$form->accountedForm($data); //出入账
							
						$this->redirect($back_url);
					}
				}
			}
		}
		$this->pageTitle = "短期借贷出/入账";
		$model = $baseform->shortLoan;
		$loanRecord = $model->loanRecord;
		
// 		$title_array = DictTitle::getComs('json'); //公司抬头
// 		$company_array = DictCompany::getComs('json'); //借贷公司
		$dict_bank_array = DictBankInfo::getBankList('json', $model->title_id);
		$bank_array = BankInfo::getBankList('json', $model->company_id);
		
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getCWUserList('array'); //业务员
		
		$this->render('account', array(
				'model' => $model,
				'baseform' => $baseform,
				'loanRecord' => $loanRecord,
// 				'title_array' => $title_array,
// 				'company_array' => $company_array,
				'team_array' => $team_array,
				'dict_bank_array' => $dict_bank_array, 
				'bank_array' => $bank_array,
				'user_array' => $user_array,
				'back_url' => $back_url,
				'msg' => $msg
		));
	}
	
	//取消出入帐
	public function actionCancelAccounted($id){
		$baseform = CommonForms::model()->findByPK($id);
		if($baseform){
			$last_update = $_REQUEST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			return false;
		}
		
		$form = new ShortLoanClass($id);
		$result = $form->cancelAccountedForm(); //出入账
		if($result){
			echo "success";
		}else{
			echo "取消入账失败";
		}
	}
	/**
	 * 履约
	 */
	public function actionPerformance($id, $type)
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
	
		$form = new ShortLoanClass($id);
		switch ($type)
		{
			case 'performance':
				$result = $form->performanceForm();
				break;
			case 'cancle':
				$result = $form->cancelPerformanceForm();
				break;
			default: break;
		}
		if (!$result) return false;
		echo 'success';
	}
	
	/**
	 * 作废
	 */
	public function actionDeleteForm() 
	{
		$id = intval($_REQUEST['id']);
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$form = new ShortLoanClass($id);
		$str = $_REQUEST['str'];
		$result = $form->deleteForm($str);
		if (!$result) return 'fail';
		echo 'success';
	}
	
	/**
	 * 列表
	 */
	public function actionIndex() 
	{
		$this->pageTitle = "短期借贷";
		if($_POST['search']){
			$search = $_POST['search'];
			$search['has_Ious'] = intval($search['has_Ious']);
		}
		$search=updateSearch($search,'search_shortloan_index');
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled text-center", 'width' => "20px"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "80px"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "80px"),
				array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				//array('name' => "履约状态", 'class' => "flex-col sort-disabled", 'width' => "120px"),
				array('name' => "登记日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "借贷公司", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "借贷方向", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "合约金额", 'class' => "flex-col sort-disabled text-right", 'width' => "110px"),
				array('name' => "出入账金额", 'class' => "flex-col sort-disabled text-right", 'width' => "110px"),
				//array('name' => "利率", 'class' => "flex-col sort-disabled text-right", 'width' => "110px"),
// 				array('name' => "累计入账本金", 'class' => "flex-col sort-disabled text-right", 'width' => "110px"),
// 				array('name' => "累计入账利息", 'class' => "flex-col sort-disabled text-right", 'width' => "110px"),
// 				array('name' => "累计出账本金", 'class' => "flex-col sort-disabled text-right", 'width' => "110px"),
// 				array('name' => "累计出账利息", 'class' => "flex-col sort-disabled text-right", 'width' => "110px"),
// 				array('name' => "本金余额", 'class' => "flex-col sort-disabled text-right", 'width' => "110px"),
// 				array('name' => "开始日期", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				//array('name' => "结束日期", 'class' => "flex-col sort-disabled", 'width' => "110px"),
				array('name' => "借据", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				array('name' => "公司", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "入账日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "负责人", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				//array('name' => "审核人", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				//array('name' => "审核时间", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "230px"),//
		);
		
		if ($search['form_status'] && $search['form_status'] == 'delete') 
			$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "200px");
		
		list($tableData, $pages, $totalData) = ShortLoan::getFormList($search);
		$totaldata = array("","合计：","","","","","",number_format($totalData["has"],2),number_format($totalData["is"],2),"","","","","","");
		$title_array = DictTitle::getComs('json'); //公司抬头
		$company_array = DictCompany::getAllComsForDJ('json'); //借贷公司
		$user_array=User::getCWUserList();
		$this->render('index', array(
				'tableHeader' => $tableHeader, 
				'tableData' => $tableData, 
				'pages' => $pages, 
				'totalData' => $totaldata, 
				'title_array' => $title_array, 
				'company_array' => $company_array,
				'search'=>$search,
				"users"=>$user_array,
		));
	}
	
	/*
	 * 导出
	 */
	public function actionLoanExport(){
		$search=$_REQUEST['search'];
		$name = "短期借贷".date("Y/m/d");
		$title=array("单号","状态","登记日期","借贷公司","借贷方向","合约金额","出入账金额","借据","公司","入账日期","负责人","操作员",
				"审核人","审核时间","备注"
		);
		if($search['form_status'] == "delete"){
			array_push($title,'作废原因');
		}
	
		$content=ShortLoan::getAllList($search);
		PHPExcel::ExcelExport($name,$title,$content);
	}
	
	/**
	 * 打印页面
	 * @param unknown_type $id
	 */
	public function actionPrint($id)
	{
		$baseform = CommonForms::model()->findByPK($id);
		$model = $baseform->shortLoan;
		
		$this->renderPartial('print', array(
				'baseform' => $baseform,
				'model' => $model,
		));
	}
	
	
	/*
	 * 获取此前操作
	 */
	public  function actionGetCurrentButton()
	{
		$form_sn=$_REQUEST['form_sn'];
		if(!$form_sn){
			$form_id=$_REQUEST['form_id'];
			$form_sn=CommonForms::model()->findByPk($form_id)->form_sn;
			if(!$form_sn)return false;
		}
		$str=ShortLoan::getButtons($form_sn);
		echo $str;
	}
	
	
	
	
}