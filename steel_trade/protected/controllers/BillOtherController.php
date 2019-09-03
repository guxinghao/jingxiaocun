<?php
/**
 * 其他收入/费用报支
 * @author leitao
 *
 */
class BillOtherController extends AdminBaseController 
{
	public function actionView($id) 
	{
		$baseform = CommonForms::model()->with('billOther')->findByPK($id);
		if (!$baseform) return false;

		$this->pageTitle = "查看";
		$model = $baseform->billOther;
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('billOther/index', array('type' => $baseform->form_type, 'page' => $fpage));
		
		if ($_REQUEST['back_url']) 
			$back_url = Yii::app()->createUrl($_REQUEST['back_url'].'/index', array('page' => $fpage));
		
		switch ($baseform->form_type) 
		{
			case 'FYBZ': //费用报支
				$this->pageTitle .= "费用报支";
				break;
			case 'QTSR': //其他收入
				$this->pageTitle .= "其他收入";
				break;
			default: 
				break;
		}
		$this->pageTitle .= " ".$baseform->form_sn;
		
		$this->render('view', array(
				'model' => $model, 
				'baseform' => $baseform,
				'back_url' => $back_url,
		));
	}
	
	public function actionCreate($type) 
	{
		$back_url = Yii::app()->createUrl('billOther/index', array('type' => $type));
		
		if (isset($_POST['FrmBillOther']))
		{
			$data = FrmBillOther::getInputData($_POST);
			if ($data)
			{
				$form = new BillOther($type, $id);
				if ($form->createForm($data) && $data['common']->submit == 'yes') 
					$form->submitForm();
		
				$this->redirect($back_url);
			}
		}
		
		$this->pageTitle = "新建";
		$model = new FrmBillOther();
		$baseform = new CommonForms();
		$baseform->owned_by = currentUserId();
		switch ($type) 
		{
			case 'FYBZ': //费用报支
				$this->pageTitle .= "费用报支";
				$baseform->form_type = 'FYBZ';
				$parent_id = 1;
				break;
			case 'QTSR': //其他收入
				$this->pageTitle .= "其他收入";
				$baseform->form_type = 'QTSR';
				$parent_id = 0;
				break;
			default:
				break;
		}
		
		
		$title_array = DictTitle::getComs('json'); //公司
		$company_array = DictCompany::getAllComs('json'); //结算单位
		$type_array = DictRecordType::getTypeList('array', $parent_id); //费用类别
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getCWUserList('array'); //业务员
		
		$this->render('create', array(
				'model' => $model, 
				'baseform' => $baseform, 
				'title_array' => $title_array,
				'company_array' => $company_array, 
				'team_array' => $team_array, 
				'type_array' => $type_array, 
				'user_array' => $user_array, 
				'back_url' => $back_url
		));
	}
	
	public function actionUpdate($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('billOther/index', array('type' => $baseform->form_type, 'page' => $fpage));
		
		if (isset($_POST['FrmBillOther']))
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}
			else
			{
				$data = FrmBillOther::getInputData($_POST);
				if ($data)
				{
					$form = new BillOther($baseform->form_type, $id);
					if ($form->updateForm($data) && $data['common']->submit == 'yes') $form->submitForm();
						
					$this->redirect($back_url);
				}
			}
		}
		
		$this->pageTitle = "修改";
		$model = $baseform->billOther;
		switch ($baseform->form_type)
		{
			case 'FYBZ': //费用报支
				$this->pageTitle .= "费用报支";
				$parent_id = 1;
				break;
			case 'QTSR': //其他收入
				$this->pageTitle .= "其他收入";
				$parent_id = 0;
				break;
			default: 
				break;
		}
		$this->pageTitle .= " ".$baseform->form_sn;
		
		//公司
		$title_array = DictTitle::getComs('json'); 
		//结算单位
		$company_array = DictCompany::getAllComs('json'); 
		//费用类别
		$type_array = DictRecordType::getTypeList('array', $parent_id);
		//公司账户
		$dict_bank_array = DictBankInfo::getBankList('json', $model->dict_bank_id);
		//结算账户
		$bank_array = BankInfo::getBankList('json', $model->bank_id);
		
		//业务组
		$team_array = Team::getTeamList('array');
		//业务员
		$user_array = User::getCWUserList('array');
		
		$this->render('update', array(
				'model' => $model, 
				'baseform' => $baseform,
				'title_array' => $title_array,
				'company_array' => $company_array, 
				'dict_bank_array' => $dict_bank_array, 
				'bank_array' => $bank_array, 
				'type_array' => $type_array,
				'team_array' => $team_array, 
				'user_array' => $user_array, 
				'back_url' => $back_url, 
				'msg' => $msg
		));
	}
	
	public function actionSubmit($id, $type)
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$form = new BillOther($baseform->form_type, $id);
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
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
		$form = new BillOther($baseform->form_type, $id);
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
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
	
	public function actionDeleteForm() 
	{
		$id = intval($_REQUEST['id']);
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$form = new BillOther($baseform->form_type, $id);
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
		
		$str = $_REQUEST['str'];
		$result = $form->deleteForm($str);
		if (!$result) return 'fail';
		echo 'success';
	}
	
	//入账
	public function actionAccounted($id, $type) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$form = new BillOther($baseform->form_type, $id);
		switch ($type) 
		{
			case 'accounted': 
				switch ($baseform->form_type)
				{
					case 'FYBZ':
						$this->pageTitle = "费用报支";
						$parent_id = 1;
						break;
					case 'QTSR':
						$this->pageTitle = "其他收入";
						$parent_id = 0;
						break;
					default: break;
				}
				$this->pageTitle .= "入账";
				$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
				$back_url = Yii::app()->createUrl('billOther/index', array('type' => $baseform->form_type, 'page' => $fpage));
				
				if (isset($_POST['FrmBillOther'])) 
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
							$data = FrmBillOther::getInputData($_POST);
							if ($data)
							{
								$form = new BillOther($baseform->form_type, $id);
								$form->updateForm($data);
								$form->accountedForm();
								
								$this->redirect($back_url);
							}
						}
					}
				}
				$model = $form->mainInfo;
				//公司
				$title_array = DictTitle::getComs('json');
				//结算单位
				$company_array = DictCompany::getComs('json');
				//费用类别
				$type_array = DictRecordType::getTypeList('array', $parent_id);
				
				//公司账户
				$dict_bank_array = DictBankInfo::getBankList('json', $model->title_id);
				//结算账户
				$bank_array = BankInfo::getBankList('json', $model->company_id);
				
				//业务组
				$team_array = Team::getTeamList('array');
				//业务员
				$user_array = User::getCWUserList('array');
				
				$this->render('accounted', array(
						'model' => $model, 
						'baseform' => $baseform,
						'title_array' => $title_array,
						'company_array' => $company_array,
						'dict_bank_array' => $dict_bank_array, 
						'bank_array' => $bank_array,
						'type_array' => $type_array,
						'team_array' => $team_array,
						'user_array' => $user_array,
						'back_url' => $back_url,
						'msg' => $msg
				));
				
				break;
			case 'cancel_accounted':
				$last_update = $_REQUEST['last_update'];
				if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
				$result = $form->cancelAccountedForm();
				if (!$result) return false;
				echo 'success';
				break;
			default: break;
		}
		
		
	}
	
	public function actionIndex($type) 
	{
		switch ($type)
		{
			case 'FYBZ': //费用报支
				$this->pageTitle = "费用报支";
				$parent_id = 1;
				break;
			case 'QTSR': //其他收入
				$this->pageTitle = "其他收入";
				$parent_id = 0;
				break;
			default:
				break;
		}
		
		if($type=='FYBZ')
		{
			if(!checkOperation("报支普通视图")&&!checkOperation("报支审核视图")){
				return false;
			}else{
				$v=array('belong'=>'报支普通视图','all'=>'报支审核视图');
				if($_COOKIE['bz_view']&&checkOperation($v[$_COOKIE['bz_view']])){
					setcookie('bz_view',$_COOKIE['bz_view'],time()+60*60*24,'/');
				}else{
					if(!checkOperation("报支普通视图")){
						setcookie('bz_view','all',time()+60*60*24,'/');
						$_COOKIE['bz_view']='all';
					}else{
						setcookie('bz_view','belong',time()+60*60*24,'/');
						$_COOKIE['bz_view']='belong';
					}
						
				}
			}
		}	
		
		$search = isset($_POST['search']) ? $_POST['search'] : array();
		$search=updateSearch($search,'search_billother_index');
		$search['form_type'] = $type;
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled text-center", 'width' => "20px"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "80px"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "80px"),
				array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "登记日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "类型大类", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "类型小类", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "公司", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "公司账户", 'class' => "flex-col sort-disabled", 'width' => "100px"),
				array('name' => "结算单位", 'class' => "flex-col sort-disabled", 'width' => "130px"),
				array('name' => "金额", 'class' => "flex-col sort-disabled text-right", 'width' => "120px"),
				array('name' => "入账金额", 'class' => "flex-col sort-disabled text-right", 'width' => "120px"),
				array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "210px"),//
				array('name' => "负责人", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				//array('name' => "审核人", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				//array('name' => "审核时间", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "入账人", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				//array('name' => "入账时间", 'class' => "flex-col sort-disabled", 'width' => "140px"),
				array('name' => "到账日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				
		);
		if ($search['form_status'] && $search['form_status'] == 'delete') 
			$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "200px");
		
		list($tableData, $pages,$totaldata) = FrmBillOther::getFormList($search, $type);
		$totalData = array("","合计：","","","","","","","","",number_format($totaldata["has"],2),number_format($totaldata["is"],2),"","","","","");
		//公司
		$title_array = DictTitle::getComs('json');
		//结算单位
		$company_array = DictCompany::getAllComs('json');
		$user_array=User::getCWUserList();
		$type_array = DictRecordType::getTypeList('array', $parent_id); //费用类别
		$this->render('index', array(
				'tableHeader' => $tableHeader,
				'tableData' => $tableData,
				'pages' => $pages,
				'search' => $search,
				'type' => $type,
				'title_array' => $title_array,
				'company_array' => $company_array,
				"totalData"=>$totalData,
				"users"=>$user_array,
				"type_array"=>$type_array,
		));
	}
	
	/*
	 * 导出
	 */
	public function actionBillExport($type){
		$search=$_REQUEST['search'];
		$name = "费用报支".date("Y/m/d");
		$title=array("单号","状态","登记日期","类型大类","类型小类","公司","公司账户","结算单位","金额","入账金额","备注","负责人","操作员",
				"审核人","审核时间","入账人","到账日期"
		);
		if($search['form_status'] == "delete"){
			array_push($title,'作废原因');
		}
		
		$content=FrmBillOther::getAllList($search,$type);
		PHPExcel::ExcelExport($name,$title,$content);
	}
	
	//根据一级分类id获取二级分类
	public function actionGetType(){
		$id = $_POST['id'];
		$first_type = DictRecordType::model()->findByPk($id);
		if(!$first_type || $first_type->value == 0){
			echo "fail";die;
		}
		$second_type = DictRecordType::model()->findAll("parent_value=$first_type->value");
		if($second_type){
			$str = '<select class="form-control chosen-select td_type2">';
			$k = 0;
			foreach($second_type as $li){
				if($k == 0){$value = $li->id;$k++;}
				$str.='<option value="'.$li->id.'">'.$li->name.'</option>';
			}
			$str.='</select>';
			$str.='<input type="hidden" class="td_type_val2" value="'.$value.'" name="td_type2[]" />';
			echo $str;
		}else{
			echo "fail";
		}
	}
	
	/**
	 * 打印页面
	 * @param unknown_type $id
	 */
	public function actionPrint($id)
	{
		$baseform = CommonForms::model()->findByPK($id);
		$model = $baseform->billOther;
		$details = $model->details;
		$this->renderPartial('print', array(
				'baseform' => $baseform,
				'model' => $model,
				'details' => $details,
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
		$str=FrmBillOther::getButtons($form_sn);
		echo $str;
	}
	
	
	
}