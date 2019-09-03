<?php
/**
 * 银行互转
 * @author leitao
 *
 */
class TransferAccountsController extends Controller
{ 
	public function actionView($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		
		$this->pageTitle = "查看银行互转 ".$baseform->form_sn;
		$model = $baseform->transferAccounts;

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('transferAccounts/index', array('page' => $fpage));
		if ($_REQUEST['back_url']) 
			$back_url = Yii::app()->createUrl($_REQUEST['back_url'].'/index', array('page' => $fpage));
		
		$this->render('view', array(
				'model' => $model, 
				'baseform' => $baseform, 
				'back_url' => $back_url,
		));
	}
	
	public function actionCreate() 
	{
		$back_url = Yii::app()->createUrl('transferAccounts/index');
		
		if (isset($_POST['TransferAccounts']))
		{
			if($_POST['CommonForms']['submit'] == 'yes'){
				$_POST['TransferAccounts']['reach_at'] = $_POST['CommonForms']['form_time'];
			}
			$data = TransferAccounts::getInputData($_POST);
			
			if ($data)
			{
				$form = new TransferAccountsClass($id);
				if ($form->createForm($data))
				{
					if ($data['common']->submit == 'yes') $form->accountedForm();
					$this->redirect($back_url);
				}
			}
		}
		
		$this->pageTitle = "新建银行互转";
		$model = new TransferAccounts();
		$baseform = new CommonForms();
		$baseform->form_type = "YHHZ";
		$baseform->owned_by = currentUserId();
		
		$title_output_array = DictTitle::getComs('json'); //转出公司
		$title_input_array = DictTitle::getComs('json'); //转入公司
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getCWUserList('array'); //业务员
		$bank_json = DictBankInfo::getBankList('array', $id);
		$this->render('create', array(
				'model' => $model, 
				'baseform' => $baseform, 
				'title_output_array' => $title_output_array,
				'title_input_array' => $title_input_array, 
				'team_array' => $team_array, //业务组
				'user_array' => $user_array, //业务员
				'back_url' => $back_url,
				'bank_json' => $bank_json,
		));
	}
	
	public function actionUpdate($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;

		$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
		$back_url = Yii::app()->createUrl('transferAccounts/index', array('page' => $fpage));
		
		if (isset($_POST['TransferAccounts']))
		{
			$last_update = $_POST['last_update'];
			if ($last_update != $baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请刷新后再试";
			}
			else
			{
				if($_POST['CommonForms']['submit'] == 'yes'){
					$_POST['TransferAccounts']['reach_at'] = $_POST['CommonForms']['form_time'];
				}
				$form = new TransferAccountsClass($id);
				$data = TransferAccounts::getInputData($_POST);
				if ($data && $form->updateForm($data))
				{
					if ($data['common']->submit == 'yes') $form->accountedForm();
					$this->redirect($back_url);
				}
			}
		}
		$this->pageTitle = "修改银行互转";
		$model = $baseform->transferAccounts;
		
		$title_output_array = DictTitle::getComs('json'); //转出公司
		$title_input_array = DictTitle::getComs('json'); //转入公司
		$output_bank_array = DictBankInfo::getBankList('json', $model->title_output_id); //转出账户
		$input_bank_array = DictBankInfo::getBankList('json', $model->title_input_id); //转入账户
		$team_array = Team::getTeamList('array'); //业务组
		$user_array = User::getCWUserList('array'); //业务员
		$bank_json = DictBankInfo::getBankList('array', "");
	
		$this->render('update', array(
				'model' => $model,
				'baseform' => $baseform,
				'title_output_array' => $title_output_array,
				'title_input_array' => $title_input_array,
				'output_bank_array' => $output_bank_array,
				'input_bank_array' => $input_bank_array,
				'team_array' => $team_array, //业务组
				'user_array' => $user_array, //业务员
				'back_url' => $back_url, 
				'msg' => $msg,
				'bank_json' => $bank_json,
		));
	}
	
	//提交|取消提交
	public function actionSubmit($id, $type)
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		$last_update = $_REQUEST['last_update'];
		if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
	
		$form = new TransferAccountsClass($id);
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
	
		$form = new TransferAccountsClass($id);
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
	
	/**
	 * 入账|取消入账
	 * @param integer $id
	 * @param string $type
	 */
	public function actionAccounted($id, $type) 
	{
		$baseform = CommonForms::model()->findByPk($id);
		if (!$baseform) return false;
		switch ($type) 
		{
			case 'accounted': 
				$fpage = intval($_REQUEST['fpage']) ? intval($_REQUEST['fpage']) : 1;
				$back_url = Yii::app()->createUrl('transferAccounts/index', array('page' => $fpage));
				
				if (isset($_POST['TransferAccounts'])) 
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
							$data = TransferAccounts::getInputData($_POST);
							if ($data) 
							{
								$form = new TransferAccountsClass($id);
								$form->updateForm($data);
								$form->accountedForm();
								
								$this->redirect($back_url);
							}
						}
					}
				}
				$this->pageTitle = "银行互转入账";
				$model = $baseform->transferAccounts;
				
				//转出公司
				$title_output_array = DictTitle::getComs('json');
				//转入公司
				$title_input_array = DictTitle::getComs('json');
				//转出账户
				$output_bank_array = DictBankInfo::getBankList('array');
				//转入账户
				$input_bank_array = DictBankInfo::getBankList('array');
				//业务组
				$team_array = Team::getTeamList('array');
				//业务员
				$user_array = User::getCWUserList('array');
				
				$this->render('account', array(
						'baseform' => $baseform, 
						'model' => $model, 
						'title_output_array' => $title_output_array,
						'title_input_array' => $title_input_array,
						'output_bank_array' => $output_bank_array,
						'input_bank_array' => $input_bank_array,
						'team_array' => $team_array, //业务组
						'user_array' => $user_array, //业务员
						'back_url' => $back_url,
						'msg' => $msg,
				));
				break;
			case 'cancle': 
				$last_update = $_REQUEST['last_update'];
				if ($last_update != $baseform->last_update) die("您看到的信息不是最新的，请刷新后再试");
				
				$form = new TransferAccountsClass($id);
				$result = $form->cancelAccountedForm();
				if (!$result) return false;
				echo 'success';
				break;
			default: break;
		}
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
		$form = new TransferAccountsClass($id);
		$result = $form->deleteForm($str);
		if (!$result) return false;
		echo 'success';
	}
	
    public function actionIndex() 
    { 
    	$this->pageTitle = "银行互转";
    	$search = isset($_POST['search']) ? $_POST['search'] : array();
    	
    	$tableHeader = array(
    			array('name' => "", 'class' => "sort-disabled text-center", 'width' => "20px"),
    			array('name' => "", 'class' => "sort-disabled", 'width' => "80px"),
    			array('name' => "单号", 'class' => "sort-disabled", 'width' => "80px"),
    			array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "60px"),
    			array('name' => "登记日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
    			//array('name' => "转出公司", 'class' => "flex-col sort-disabled", 'width' => "110px"),
    			array('name' => "转出帐户", 'class' => "flex-col sort-disabled", 'width' => "180px"),
    			//array('name' => "转入公司", 'class' => "flex-col sort-disabled", 'width' => "110px"),
    			array('name' => "转入帐户", 'class' => "flex-col sort-disabled", 'width' => "180px"),
    			array('name' => "金额", 'class' => "flex-col sort-disabled text-right", 'width' => "120px"),
    			array('name' => "类型", 'class' => "flex-col sort-disabled", 'width' => "80px"),
    			array('name' => "负责人", 'class' => "flex-col sort-disabled", 'width' => "60px"),
    			array('name' => "操作员", 'class' => "flex-col sort-disabled", 'width' => "60px"),
    			//array('name' => "审核人", 'class' => "flex-col sort-disabled", 'width' => "140px"),
    			array('name' => "入账时间", 'class' => "flex-col sort-disabled", 'width' => "80px"),
    			array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "230px"),//
    	);
    	if ($search['form_status'] == 'delete') 
    		$tableHeader[] = array('name' => "作废原因", 'class' => "flex-col sort-disabled", 'width' => "200px");
    		$search=updateSearch($search,'search_transfer_index');
    	list($tableData, $pages, $totalData) = TransferAccounts::getFormList($search);
    	
    	//转出公司
    	$title_output_array = DictTitle::getComs('json');
    	//转入公司
    	$title_input_array = DictTitle::getComs('json');
    	$user_array=User::getCWUserList();
        $this->render('index', array(
        		'tableHeader' => $tableHeader, 
        		'tableData' => $tableData, 
        		'pages' => $pages, 
        		'totalData' => $totalData, 
        		'title_output_array' => $title_output_array, 
        		'title_input_array' => $title_input_array,
        		'search'=>$search,
        		"users"=>$user_array,
        )); 
    } 

    /*
     * 导出
     */
    public function actionTranExport($type){
    	$search=$_REQUEST['search'];
    	$name = "费用报支".date("Y/m/d");
    	$title=array("单号","状态","登记日期","转出帐户","转入帐户","金额","类型","负责人","操作员","入账时间","备注");
    	if($search['form_status'] == "delete"){
    		array_push($title,'作废原因');
    	}
    
    	$content=TransferAccounts::getAllList($search,$type);
    	PHPExcel::ExcelExport($name,$title,$content);
    }
    
    // Uncomment the following methods and override them if needed 
    /* 
    public function filters() 
    { 
        // return the filter configuration for this controller, e.g.: 
        return array( 
            'inlineFilterName', 
            array( 
                'class'=>'path.to.FilterClass', 
                'propertyName'=>'propertyValue', 
            ), 
        ); 
    } 

    public function actions() 
    { 
        // return external action classes, e.g.: 
        return array( 
            'action1'=>'path.to.ActionClass', 
            'action2'=>array( 
                'class'=>'path.to.AnotherActionClass', 
                'propertyName'=>'propertyValue', 
            ), 
        ); 
    } 
    */ 
}
