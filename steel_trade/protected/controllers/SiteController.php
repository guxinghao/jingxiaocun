<?php
set_time_limit(1800);
class SiteController extends AdminBaseController
{
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// $all_menus = MenuItem::getValideMenus();
		$this->setHome = 1;
		// 根据权限跳转首页
		if(checkOperation('首页')){
			$model = MergeStorage::model();
			$this->pageTitle = "首页";

			// 钢厂数据
			$data = $model->getBrandList();
			$tableHeader[0] = array('name'=>'','class' =>"",'width'=>"20px");
			foreach($data['tableHeader'] as $v){
				$tableHeader[] = array('name'=>$v,'class'=>"flex-col",'width'=>"70px");
			}
			$tableData = $data['tableData'];
			// 合计、饼图数据
			$chart = array();
			$totalData = array("合计：");
			$detail = $data['totalData'];
			$sum = array_sum($detail);
			foreach($detail as $k => $v){
				$chart[] = array('name'=>$k,'y'=>round($v/$sum*100,2));
				$totalData[] = (float)$v ? number_format($v, 3) : 0;
			}

			// 仓库数据
			$data1 = $model->getWarehouseList();
			$tableHeader1[0] = array('name'=>'','class' =>"",'width'=>"20px");
			foreach($data1['tableHeader'] as $v){
				$tableHeader1[] = array('name'=>$v,'class'=>"flex-col",'width'=>"70px");
			}
			$tableData1 = $data1['tableData'];
			// 合计数据
			$totalData1 = array("合计：");
			foreach($data1['totalData'] as $v){
				$totalData1[] = (float)$v ? number_format($v, 3) : 0;
			}

			$this->render('home', array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
				'tableHeader1'=>$tableHeader1,
				'tableData1'=>$tableData1,
				'totalData1'=>$totalData1,
				'chart'=>json_encode($chart),
				'detail'=>$detail
			));
		}else{
			$this->render('index');
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			if ($_POST ['rmb'] == "on") {
				setcookie ( 'username', $model->username, time () + 60 * 60 * 24 * 30 * 100 );
			} else {
				setcookie ( "username" );
			}
			if($model->validate() && $model->login()){
				$api_center = new api_center();
				$result = $api_center->loginAuthorization($model->username, $model->password);
				$result = json_decode($result);
				if ($result->result == 'error') {
					$msg = $result->message;
				} else {
					$user = User::model()->findByPk(Yii::app()->user->userid);
					$user->last_login_at = time();
					$user->last_login_ip = Yii::app()->request->userHostAddress;
					$user->update();
					if(Yii::app()->user->returnUrl=='/index.php'||Yii::app()->user->returnUrl=='/'||Yii::app()->user->returnUrl=='/index.php/'){
						$home = PageSet::model()->find("user_id=".Yii::app()->user->userid);
						if ($home)
							$this->redirect($home->page_url);
						else $this->redirect(Yii::app()->user->returnUrl);
					}else{
						$this->redirect(Yii::app()->user->returnUrl);
					}
				}
			}
		}else if($_REQUEST['is_another']=='yes'){
			$model->attributes=$_GET;
			if ($_GET['rmb'] == "on") {
				setcookie ( 'username', $model->username, time () + 60 * 60 * 24 * 30 * 100 );
			} else {
				setcookie ( "username" );
			}
			if($model->validate() && $model->login()){
				$api_center = new api_center();
				$result = $api_center->loginAuthorization($model->username, $model->password);
				$result = json_decode($result);
				if ($result->result == 'error') {
					$msg = $result->message;
				} else {
					$user = User::model()->findByPk(Yii::app()->user->userid);
					$user->last_login_at = time();
					$user->last_login_ip = Yii::app()->request->userHostAddress;
					$user->update();
				}
			}
			return;
		}
		if(isset($_COOKIE['username'])){
			$model->username = $_COOKIE['username'];
		}
		$this->renderPartial('login', array('model'=>$model, 'msg' => $msg));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


	public function actionDelete()
	{
		$id = intval($_REQUEST['id']);//删除id
		$model = $_REQUEST['tableclass'];
		$model = new $model;
		$model = $model->findByPk($id);
		$model->is_deleted = 1;
		if ($model->update())
			echo "1";
	}

	/**
	 * 编辑优先级
	 */
	public function actionEditPriority()
	{

		$id_str=explode("|",$_POST['id_str']);
		$priority_str=explode("|",$_POST['priority_str']);
		$name = $_REQUEST['search'];
		$type = $_POST['post_type'];
		for($i=count($id_str)-1;$i>=0;$i--)
		{
			if (!$id_str[$i])
				continue;
			if($priority_str[$i]==""){$priority_str[$i]="100";}
			$table = new $_POST['db_table'];
			if ($name == "name")
				$model = $table->find("name = '{$id_str[$i]}'");
			else
				$model = $table->find("id = {$id_str[$i]}");
			if ($type=="edit_priority")
				$model->priority = $priority_str[$i];
			else
				$model->priority = "100";
			$model->update();
		}
	}


	/**
	 * 分页存取cookie
	 */
	public function actionWriteCookie() {
		$name = $_REQUEST ['name'];
		$limit = $_REQUEST ['limit'];
		// 首先新建cookie
		$cookie = new CHttpCookie ( $name, $limit );
		// 定义cookie的有效期
		$cookie->expire = time () + 60 * 60 * 24 * 30; // 有限期30天
		                                     // 把cookie写入cookies使其生效
		Yii::app ()->request->cookies [$name] = $cookie;
		echo 'ok';
		return true;
	}


	public function actionPageTest()
	{
		$forms = new CommonForms();

		$c = new CDbCriteria();
		$c->addCondition("is_deleted >= 0");
		$page =new CPagination();
		$page->itemCount = $forms->count($c);
		$page->pageSize = 10;
		$cookie =Yii::app()->request->getCookies();
		if( $cookie['contract_page']->value){
// 			var_dump($cookie['contract_page']->value);die;
			$page->pageSize = $cookie['contract_page']->value;
		}
		$page->applyLimit($c);
		$model = $forms->findAll($c);
		$this->render("pageTest",array("model"=>$model,"page"=>$page));

	}



	/**
	 * 根据物流商计算运费
	 */
	public function actionGetTransfer()
	{
		$title = intval($_REQUEST['title']);
		$result = DictCompany::getPrice($title);
		echo $result;
	}


	public function actionTest()
	{
		$id = 1;
		$name = "test";
		$age = 24;
		$array = compact("id","name","age");
		var_dump($array);die;
	}

	/**
	 * 跨平台登录接口
	 * 例：http://localhost:86/index.php/site/login_api?result=success&unid=1
	 */
	public function actionLogin_api()
	{
		$unid = $_REQUEST['unid'];
		$result = $_REQUEST['result'];
		if($result!="success"){
			//此处做失败操作
			echo "得到结果为失败!";
			return;
		}
		$user = User::model()->find("unid='{$unid}'");

		if(!$user||$user->is_deleted==1){
			//此处做失败操作
			echo "无效用户！";
			return;
		}
		$model=new LoginForm;
		$model->username = $user->loginname;
		$model->password = $user->password;


		if($model->validate() && $model->login()){
			//登录成功
			echo "登陆成功";
//			$this->redirect('site/index');
		}else{
			//此处做失败操作
			echo "登录失败!";
		}
	}

	/**
	 * 错误信息页面
	 * $_REQUESR['url']为跳转地址，如果没有默认js回退一页
	 */
	public function actionShowError(){
		$this->pageTitle="错误";
		$this->render("show_error");
	}

	/**
	 * 用户设置主页
	 * 成功返回"updated"
	 */
	public function actionSetHome(){
		$url = $_POST['url'];
		$userid = Yii::app()->user->userid;
		$model = PageSet::model()->find("user_id='{$userid}'");
		if(!$model){
			$model = new PageSet();
			$model->user_id = $userid;
		}
		$model->page_url = $url;
		$model->created_at = time();
		if($model->id){
			if($model->update()){
				echo "updated";
			}
		}else{
			if($model->insert()){
				echo "updated";
			}
		}
	}

	public function actionGetDate($format)
	{
		$timestamp = $_REQUEST['timestamp'] ? $_REQUEST['timestamp'] : time();
		echo date($format, $timestamp);
	}

	public function actionGetStrtotime($time)
	{
		if ($_REQUEST['now']) $now = $_REQUEST['now'];
		echo strtotime($time, $now);
	}

	//均摊
	public function actionShareEqually()
	{
		$start_time = strtotime(date('Y-m-d 00:00:00', strtotime("- 30 day")));
		$end_time = strtotime(date('Y-m-d 23:59:59'));

		$model = new CommonForms();
		$criteria = new CDbCriteria();
		$criteria->addCondition("UNIX_TIMESTAMP(t.form_time) >= :start_time");
		$criteria->addCondition("UNIX_TIMESTAMP(t.form_time) <= :end_time");
		$criteria->params[':start_time'] = $start_time;
		$criteria->params[':end_time'] = $end_time;
		$criteria->addInCondition("t.form_type", array('GCFL', 'CKFL', 'CCFY', 'XSZR', 'TPSH'));
		$criteria->compare("t.form_status", 'approve');

		$baseforms = $model->findAll($criteria);
		foreach ($baseforms as $each)
		{
			if (in_array($each->form_type, array('GCFL', 'CKFL', 'CCFY'))) { BillRebate::shareEqually($each->id); }
			elseif ($each->form_type == 'XSZR') { FrmRebate::shareEqually($each->id); }
			elseif ($each->form_type == 'TPSH') { FrmPledgeRedeem::shareEqually($each->id); }
		}
		@session_destroy();
	}

	//品名材质规格产地数据导入
	public function actionGoodsImport() {
		Yii::$enableIncludePath = false;
// 		Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
		$this->pageTitle = "品名、材质、规格、产地导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$created_at = time();
			for($i=2;$i<=$highestRow;$i++)
			{
				$data = array();
				$api_id = $sheet->getCell("A".$i)->getValue();
				$data['name'] = str_replace('Φ','Φ',$sheet->getCell("B".$i)->getValue());
				$data['short_name'] = str_replace('Φ','Φ',$sheet->getCell("C".$i)->getValue());
				$data['code'] = str_replace('Φ','Φ',$sheet->getCell("D".$i)->getValue());
				$data['std'] = str_replace('Φ','Φ',$sheet->getCell("E".$i)->getValue());
				$data['is_available'] = $sheet->getCell("F".$i)->getValue();
				$data['property_type'] = $sheet->getCell("G".$i)->getValue();
				$result = DictGoodsProperty::setGoods($data);
				if($result > 0){
					//更新进销存与接口中心商品属性对应关系表
					$relation = new DictGoodPropertyRelation();
					$relation->jxc_property_id = $result;
					$relation->api_property_id = $api_id;
					$relation->property_type = $data['property_type'];
					if(!$relation->insert()){
						$str.="std为".$data['std']."的数据更新与接口中心对应关系失败！<br/>";
					}
				}elseif($result == -1){
					$str.="std为".$data['std']."的数据导入失败！<br/>";
				}elseif($result == 0){
					$str.="std为".$data['std']."的数据重复！<br/>";
				}
			}


		}

		$this->render("importgoods",array(
			'str'=>$str,
		));
	}

	//公司抬头导入
	public function actionTitleImport() {
		Yii::$enableIncludePath = false;
		$this->pageTitle = "公司抬头导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$created_at = time();
			for($i=2;$i<=$highestRow;$i++)
			{
				$data = array();
				$data['name'] = $sheet->getCell("A".$i)->getValue();
				$data['short_name'] = $sheet->getCell("B".$i)->getValue();
				$data['code'] = $sheet->getCell("C".$i)->getValue();
				$result = DictTitle::importTitle($data);
				if($result > 0){

				}elseif($result == -1){
					$str.=$data['name']."的数据导入失败！<br/>";
				}elseif($result == 0){
						$str.=$data['name']."的数据重复！<br/>";
				}
			}
		}

		$this->render("importtitle",array(
			'str'=>$str,
		));
	}

	//公司账户导入
	public function actionBankImport() {
		Yii::$enableIncludePath = false;
		$this->pageTitle = "公司账户导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$created_at = time();
			for($i=2;$i<=$highestRow;$i++)
			{
				$data = array();
				$data['bank_name'] = $sheet->getCell("A".$i)->getValue();
				$data['dict_name'] = $sheet->getCell("B".$i)->getValue();
				$data['code'] = $sheet->getCell("C".$i)->getValue();
				$data['bank_number'] = $sheet->getCell("E".$i)->getValue();
				$data['title_name'] = $sheet->getCell("F".$i)->getValue();
				$result = DictBankInfo::importBank($data);
				if($result == -2){
					$str.=$data['dict_name']."/".$data['title_name']."的关联失败！<br/>";
				}elseif($result == -1){
					$str.=$data['dict_name']."的数据导入失败！<br/>";
				}elseif($result == 0){
					$str.=$data['title_name']."的抬头不存在！<br/>";
				}
			}
		}

		$this->render("importtitle",array(
			'str'=>$str,
		));
	}

	//公司账户余额导入
	public function actionMoneyImport() {
		Yii::$enableIncludePath = false;
		$this->pageTitle = "公司账户余额导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$created_at = time();
			for($i=2;$i<=$highestRow;$i++)
			{
				//$title_name = $sheet->getCell("A".$i)->getValue();
				$dict_name = $sheet->getCell("A".$i)->getValue();
				$money = $sheet->getCell("B".$i)->getValue();
// 				$title_id = intval(DictTitle::getTitleId($title_name));
// 				if($title_id == 0){
// 					$str.=$title_name."的抬头不存在！<br/>";
// 					continue;
// 				}
				$model = DictBankInfo::model()->find("dict_name='".$dict_name."'");
				if($model){
					if ($model->money != 0){//如果金额不等于0，需要补平
						$chayi = $money - $model->money;
						$model->money = $money;
						if(!$model->update()){
							$str.=$title_name."/".$dict_name."的数据导入失败！<br/>";
						}else{
							$log = new FrmBillLog();
							$log->form_id = 0;
							$log->title_id = 0;
							$log->dict_bank_id =$model->id;
							$log->company_id = 0;
							$log->account_type = "in";
							$log->fee = $chayi;
							$log->account_by = 1;
							$log->created_at = time();
							$log->reach_at = time();
							$log->comment = "资金记录结转补录记录-0320";
							if(!$log->insert()){
								$str.=$title_name."/".$dict_name."的资金记录导入失败！<br/>";
							}
						}
					}else{
						$model->money = $money;
						if(!$model->update()){
							$str.=$title_name."/".$dict_name."的数据导入失败！<br/>";
						}else{
							$log = new FrmBillLog();
							$log->form_id = 0;
							$log->title_id = 0;
							$log->dict_bank_id =$model->id;
							$log->company_id = 0;
							$log->account_type = "in";
							$log->fee = $money;
							$log->account_by = 1;
							$log->created_at = time();
							$log->reach_at = time();
							$log->comment = "资金记录结转补录记录-0320";
							if(!$log->insert()){
								$str.=$title_name."/".$dict_name."的资金记录导入失败！<br/>";
							}
						}
					}
				}else{
					$str.=$dict_name."的账户信息没找到！<br/>";
				}
			}
		}

		$this->render("importtitle",array(
			'str'=>$str,
		));
	}

	//销售往来导入
	public function actionSalesImport() {
		Yii::$enableIncludePath = false;
		$this->pageTitle = "销售往来导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$created_at = time();
			for($i=2;$i<=$highestRow;$i++)
			{
				$data = array();
				$data['title_name'] = $sheet->getCell("A".$i)->getValue();
				$data['target_name'] = $sheet->getCell("B".$i)->getValue();
				$data['fee'] = $sheet->getCell("C".$i)->getValue();
				$data['yidan'] = $sheet->getCell("D".$i)->getValue();
				$data['own_by'] = $sheet->getCell("E".$i)->getValue();
				$result = Turnover::importSalesTurnover($data);
				if($result == -3){
					$str.=$data['own_by']."业务员不存在！<br/>";
				}else if($result == -2){
					$str.=$data['target_name']."的客户不存在！<br/>";
				}else if($result == -1){
					$str.=$data['title_name']."的抬头不存在！<br/>";
				}else if($result == 0){
					$str.=$data['title_name']."/".$data['target_name']."的保存失败！<br/>";
				}

			}
		}

		$this->render("importtitle",array(
			'str'=>$str,
		));
	}

	//销售往来修改导入
	public function actionSalesXXImport() {
		Yii::$enableIncludePath = false;
		$this->pageTitle = "销售往来修改导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$created_at = time();
			$data = array();
			for($i=2;$i<=$highestRow;$i++)
			{
				$temp = array();
				$title_name = $sheet->getCell("A".$i)->getValue();
				$supply_name = $sheet->getCell("B".$i)->getValue();
				$money = $sheet->getCell("C".$i)->getValue();
				$yidan = $sheet->getCell("D".$i)->getValue();
				$own = $sheet->getCell("E".$i)->getValue();
				if($yidan == "乙单"){
					$yidan = 1;
				}else{
					$yidan = 0;
				}
				$title_id = intval(DictTitle::getTitleId($title_name));
				if($title_id == 0){
					$str.=$title_name."的抬头不存在！";
					$title_id = 14;//默认为瑞亮
					$str.="已设置默认！<br/>";
					//continue;
				}
				$supply_id = intval(DictCompany::getCompanyId($supply_name));
				if($supply_id == 0){
					$str.=$supply_name."的供应商不存在！<br/>";
					continue;
				}
				$own_by = intval(User::getUserId($own));
				if($own_by == 0){
					$str.=$own."业务员不存在！<br/>";
				}
				$temp['title_id'] = $title_id;
				$temp['supply_id'] = $supply_id;
				$temp['money'] = $money;
				$temp['yidan'] = $yidan;
				$temp['own_by'] = $own_by;
				array_push($data,$temp);
				if($yidan == 1){
					$sql = "select *,sum(fee) as money from turnover where target_id=".$supply_id." and is_yidan=".$yidan." and ownered_by=".$own_by." and big_type='sales' and (status='submited' or status='accounted')";
				}else{
					$sql = "select *,sum(fee) as money from turnover where target_id=".$supply_id." and title_id=".$title_id." and is_yidan=".$yidan." and ownered_by=".$own_by." and big_type='sales' and (status='submited' or status='accounted')";
				}
				$cmd = Yii::app()->db->createCommand($sql);
				$turn = $cmd->queryRow($cmd);
				if($turn['money'] != $money){
					$cha = $money - $turn['money'];
					$model = new Turnover();
					$model->turnover_type = "XSMX";
					$model->turnover_direction = "need_charge";
					$model->title_id = $title_id;
					$model->target_id = $supply_id;
					$model->description = "补录初始金额";
					$model->amount = 1;
					$model->price = $cha;
					$model->fee = $cha;
					$model->status = 'submited';
					$model->ownered_by = $own_by;
					$model->created_by = 1;
					$model->created_at = time();
					$model->is_yidan = $yidan;
					$model->big_type = "sales";
					if(!$model->insert()){
						$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
					}
				}
			}

			//处理excel中不存在的数据，使初始化余额为0
			$sql="select *,sum(fee) as money from turnover where big_type='sales' and is_yidan <>1 and (status='submited' or status='accounted') group by title_id,target_id,is_yidan,ownered_by";
			$cmd = Yii::app()->db->createCommand($sql);
			$turnlist = $cmd->queryAll($cmd);
			foreach($turnlist as $li){
				//余额为零的不处理
				if($li['money'] != 0){
					//判断数据是否在excel中，如果不在，则初始化余额为0；
					$has = true;
					foreach ($data as $da){
						if($da['title_id'] == $li['title_id'] && $da['supply_id'] == $li['target_id'] && $da['yidan'] == $li['is_yidan']&&$da['own_by'] == $li['ownered_by']){
							$has = false;
							break;
						}
					}
					if($has){
						$model = new Turnover();
						$model->turnover_type = "XSMX";
						$model->turnover_direction = "need_charge";
						$model->title_id = $li['title_id'];
						$model->target_id = $li['target_id'];
						$model->description = "数据不在excel，补录甲单";
						$model->amount = 1;
						$model->price = 0-$li['money'];
						$model->fee = 0-$li['money'];
						$model->status = 'submited';
						$model->ownered_by =$li['ownered_by'];
						$model->created_by = 1;
						$model->created_at = time();
						$model->is_yidan = $li['is_yidan'];
						$model->big_type = "sales";
						if(!$model->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
					}
				}
			}
			//处理excel中不存在的乙单数据，使初始化余额为0
			$sql="select *,sum(fee) as money from turnover where big_type='sales' and is_yidan=1 and (status='submited' or status='accounted') group by target_id,is_yidan,ownered_by";
			$cmd = Yii::app()->db->createCommand($sql);
			$turnlist = $cmd->queryAll($cmd);
			foreach($turnlist as $li){
				//余额为零的不处理
				if($li['money'] != 0){
					//判断数据是否在excel中，如果不在，则初始化余额为0；
					$has = true;
					foreach ($data as $da){
						if($da['supply_id'] == $li['target_id'] && $da['yidan'] == $li['is_yidan']&&$da['own_by'] == $li['ownered_by']){
							$has = false;
							break;
						}
					}
					if($has){
						$model = new Turnover();
						$model->turnover_type = "XSMX";
						$model->turnover_direction = "need_charge";
						$model->title_id = $li['title_id'];
						$model->target_id = $li['target_id'];
						$model->description = "数据不在excel，补录乙单";
						$model->amount = 1;
						$model->price = 0-$li['money'];
						$model->fee = 0-$li['money'];
						$model->status = 'submited';
						$model->ownered_by = $li['ownered_by'];
						$model->created_by = 1;
						$model->created_at = time();
						$model->is_yidan = $li['is_yidan'];
						$model->big_type = "sales";
						if(!$model->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
					}
				}
			}
		}

		$this->render("importtitle",array(
				'str'=>$str,
		));
	}

	//结算单位导入
	public function actionCompanyImport() {
		Yii::$enableIncludePath = false;
		$this->pageTitle = "结算单位导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			for($i=2;$i<=$highestRow;$i++){
				$data = array();
				$data['name'] = $sheet->getCell("A".$i)->getValue();
				$data['short_name'] = $sheet->getCell("B".$i)->getValue();
				$data['code'] = $sheet->getCell("C".$i)->getValue();
				$data['is_logistics'] = $sheet->getCell("E".$i)->getValue();
				$data['is_customer'] = $sheet->getCell("F".$i)->getValue();
				$data['is_supply'] = $sheet->getCell("G".$i)->getValue();
				$data['username'] = $sheet->getCell("J".$i)->getValue();
				$data['mobile'] = $sheet->getCell("K".$i)->getValue();
				$result = DictCompany::importCompany($data);
				if($result == -1){
					$str.=$data['name']."保存失败！<br/>";
				}else if($result == -2){
					$str.=$data['name']."的联系人保存失败！<br/>";
				}else if($result == -3){
					$str.=$data['name']."：货主平台没有找到！<br/>";
				}
			}
			//判断货主平台有，但西本没有的结算单位
			$sql="select id,name from customer_company";
			$cmd = Yii::app()->db->createCommand($sql);
			$company = $cmd->queryAll($cmd);
			$DCompany = DictCompany::model()->findAll();
			foreach($company as $li){
				$has = false;
				foreach($DCompany as $d){
					if(trim($d->name) == trim($li["name"])){
						$has = true;
						break;
					}
				}
				//没找到对应结算单位
				if(!$has){
					$str.=$li["name"]."：货主平台有西本没有！<br/>";
				}
			}
		}
		$this->render("importtitle",array(
			'str'=>$str,
		));
	}
	//联系人导入，只能导入已经存在的结算单位
	public function actionContactImport(){
		Yii::$enableIncludePath = false;
		$this->pageTitle = "联系人导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			for($i=2;$i<=$highestRow;$i++){
				$com_name = $sheet->getCell("A".$i)->getValue();
				$name = $sheet->getCell("B".$i)->getValue();
				$phone = $sheet->getCell("C".$i)->getValue();
				$com_id = intval(DictCompany::getCompanyId($com_name,1));
				if($com_id == 0){
					$str.=$com_name."：结算单位没找到！<br/>";
					continue;
				}
				$contact=new CompanyContact();
				$contact->created_at=time();
				$contact->created_by=1;
				$contact->name=$name;
				$contact->mobile=$phone;
				$contact->is_default=0;
				$contact->dict_company_id=$com_id;
				if(!$contact->insert()){
					$str.=$com_name."：联系人".$name."保存失败！<br/>";
				}
			}

		}
		$this->render("importtitle",array(
				'str'=>$str,
		));
	}

	//采购往来结转补录
	public function actionPurchaseImport(){
		Yii::$enableIncludePath = false;
		$this->pageTitle = "采购往来结转补录导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$data = array();
			for($i=2;$i<=$highestRow;$i++){
				$temp = array();
				$title_name = $sheet->getCell("A".$i)->getValue();
				$supply_name = $sheet->getCell("B".$i)->getValue();
				$money = $sheet->getCell("C".$i)->getValue();
				$yidan = $sheet->getCell("D".$i)->getValue();
				if($yidan == "乙单"){
					$yidan = 1;
				}else{
					$yidan = 0;
				}
				$title_id = intval(DictTitle::getTitleId($title_name));
				if($title_id == 0){
					$str.=$title_name."的抬头不存在！<br/>";
					continue;
				}
				$supply_id = intval(DictCompany::getCompanyId($supply_name));
				if($supply_id == 0){
					$str.=$supply_name."的供应商不存在！<br/>";
					continue;
				}
				$temp['title_id'] = $title_id;
				$temp['supply_id'] = $supply_id;
				$temp['money'] = $money;
				$temp['yidan'] = $yidan;
				array_push($data,$temp);
				$sql = "select *,sum(fee) as money from turnover where target_id=".$supply_id." and title_id=".$title_id." and is_yidan=".$yidan." and big_type='purchase' and status!='delete' group by target_id";
				$cmd = Yii::app()->db->createCommand($sql);
				$turn = $cmd->queryRow($cmd);
				if($turn['money'] != $money){
					$cha = $money - $turn['money'];
					$model = new Turnover();
					$model->turnover_type = "CGMX";
					$model->turnover_direction = "need_pay";
					$model->title_id = $title_id;
					$model->target_id = $supply_id;
					$model->description = "结转补录往来";
					$model->amount = 1;
					$model->price = $cha;
					$model->fee = $cha;
					$model->status = 'submited';
					$model->ownered_by = 1;
					$model->created_by = 1;
					$model->is_yidan = $yidan;
					$model->big_type = "purchase";
					if(!$model->insert()){
						$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
					}
				}
			}
			//处理excel中不存在的数据，使初始化余额为0
			$sql="select *,sum(fee) as money from turnover where big_type='purchase' group by title_id,target_id,is_yidan";
			$cmd = Yii::app()->db->createCommand($sql);
			$turnlist = $cmd->queryAll($cmd);
			foreach($turnlist as $li){
				//余额为零的不处理
				if($li['money'] != 0){
					//判断数据是否在excel中，如果不在，则初始化余额为0；
					$has = true;
					foreach ($data as $da){
						if($da['title_id'] == $li['title_id'] && $da['supply_id'] == $li['target_id'] && $da['yidan'] == $li['is_yidan']){
							$has = false;
							break;
						}
					}
					if($has){
						$model = new Turnover();
						$model->turnover_type = "CGMX";
						$model->turnover_direction = "need_pay";
						$model->title_id = $li['title_id'];
						$model->target_id = $li['target_id'];
						$model->description = "结转补录往来";
						$model->amount = 1;
						$model->price = 0-$li['money'];
						$model->fee = 0-$li['money'];
						$model->status = 'submited';
						$model->ownered_by = 1;
						$model->created_by = 1;
						$model->is_yidan = $li['is_yidan'];
						$model->big_type = "purchase";
						if(!$model->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
					}
				}
			}
		}
		$this->render("importtitle",array(
				'str'=>$str,
		));
	}

	//用户数据MD5加密
	public function actionUserMD5()
	{
		$user = User::model()->findAll();
		$i = 1;
		foreach ($user as $item){
			$item->password = md5($item->password);
			$item->update();
			echo "第".$i."个执行成功</br>";
			$i++;
		}
	}

	//报价导入execl
	public function actionQuotedImport()
	{
		Yii::$enableIncludePath = false;
		$this->pageTitle = "报价导入";
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			for($i=1;$i<=$highestRow;$i++)
			{
				$A = $sheet->getCell("A".$i)->getValue();//品名名称
				$B = $sheet->getCell("B".$i)->getValue();//规格名称
				$C = $sheet->getCell("C".$i)->getValue();//长度
				$D = $sheet->getCell("D".$i)->getValue();//材质
				$E = $sheet->getCell("E".$i)->getValue();//件重
				$F = $sheet->getCell("F".$i)->getValue();//价格
				$G = $sheet->getCell("G".$i)->getValue();//华兴特钢--产地
				$H = $sheet->getCell("H".$i)->getValue();//仓库名称
				$I = 11;//$sheet->getCell("I".$i)->getValue();//乘翔实业11,瑞亮14

				$A = DictGoodsProperty::getStdByName($A);
				$B = DictGoodsProperty::getStdByName($B);
				$D = DictGoodsProperty::getStdByName($D);


				//获取仓库id
				$H = trim($H);
				$H = Warehouse::model()->find("name = '{$H}'")->id;

				$area = "盘螺、线材专区";//"盘螺、线材专区";//"盘螺、线材专区";//"海安正大专区";//"溧阳三元专区";//"安徽贵航专区";//"安徽贵航专区";//"新三洲专区";//"新沂华兴专区";//
				$brand = "goods_company_1481";//"goods_company_1499";//"goods_company_1496";//"goods_company_1508";//"goods_company_1486";//"goods_company_1473";//"goods_company_1472";//"goods_company_1496";//"goods_company_1481";//

				$model = new QuotedDetail();
				$model->product_std = $A;
				$model->texture_std = $D;
				$model->brand_std = $brand;
				$model->rank_std = $B;
				$model->length = $C;
				$model->area = $G;
				$model->price = $F;
				$model->created_at = time();
				$model->created_by = 1;
				$model->type = "guidance";
				$model->price_date = "2016-02-24";
				$model->prefecture = $area;
				$model->warehouse_id = $H;
				// 				var_dump($A);die;
				if ($model->insert())
					echo $i."插入成功</br>";
					else echo $i."插入失败</br>";
			}
			die;
		}
		$this->render("importquoted");
	}


	/**
	 * 往来汇总脚本修改0307重新初始化往来
	 */
	public function actionTurnOverInital() {
		Yii::$enableIncludePath = false;
		$this->pageTitle = "0307采购往来初始化";
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$data = array();
			for($i=1;$i<=$highestRow;$i++)
			{
				$title_name = $sheet->getCell("A".$i)->getValue();//公司名称
				$supply_name = $sheet->getCell("B".$i)->getValue();//结算单位
				$money = $sheet->getCell("C".$i)->getValue();//金额
				$yidan = $sheet->getCell("D".$i)->getValue();//乙单

				if($yidan == "乙单"){
					$yidan = 1;
				}else{
					$yidan = 0;
				}
				$title_id = intval(DictTitle::getTitleId($title_name));
				if($title_id == 0){
					$str.=$title_name."的抬头不存在！<br/>";
					continue;
				}
				$supply_id = intval(DictCompany::getCompanyId($supply_name));
				if($supply_id == 0){
					$str.=$supply_name."的供应商不存在！<br/>";
					continue;
				}
				$temp['title_id'] = $title_id;
				$temp['supply_id'] = $supply_id;
				$temp['money'] = $money;
				$temp['yidan'] = $yidan;
				array_push($data,$temp);

				// 				if ($title_name =="瑞亮物资" && $supply_name == "贵航特钢"){
				// 					var_dump($title_name,$supply_name,$money,$yidan);
				// 					if ($yidan){
				// 						$sql = "select sum(fee) as money from turnover where target_id=".$supply_id." and is_yidan=".$yidan." and big_type='purchase'
				// 								and (status = 'submited' or status='accounted')";
				// 					}else{
				// 						$sql = "select sum(fee) as money from turnover where target_id=".$supply_id." and title_id=".$title_id." and is_yidan=".$yidan."
				// 								and big_type='purchase' and (status = 'submited' or status='accounted')";
				// 					}
				// 					$cmd = Yii::app()->db->createCommand($sql);
				// 					$turn = $cmd->queryRow($cmd);
				// 				}else{
				// 					echo "continue<br>";
				// 					continue;
				// 				}

				//区分甲乙单，甲单需要区分公司抬头，乙单只用区分结算单位，公司默认用瑞亮的
				if ($yidan){
					$sql = "select sum(fee) as money from turnover where target_id=".$supply_id." and is_yidan=".$yidan." and big_type='purchase'
                            and (status = 'submited' or status='accounted')";
				}else{
					$sql = "select sum(fee) as money from turnover where target_id=".$supply_id." and title_id=".$title_id." and is_yidan=".$yidan."
                            and big_type='purchase' and (status = 'submited' or status='accounted')";
				}
				$cmd = Yii::app()->db->createCommand($sql);
				$turn = $cmd->queryRow($cmd);

				if($turn['money'] != $money){

					$cha = $money - $turn['money'];
					$model = new Turnover();
					$model->turnover_type = "CGMX";
					$model->turnover_direction = "need_pay";
					$model->title_id = $title_id;
					$model->target_id = $supply_id;
					$model->description = "结转补录往来-金额不正确，补单修复金额";
					$model->amount = 1;
					$model->price = $cha;
					$model->fee = $cha;
					$model->status = 'submited';
					$model->ownered_by = 1;
					$model->created_by = 1;
					$model->is_yidan = $yidan;
					$model->big_type = "purchase";
					if(!$model->insert()){
						$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
					}
				}
			}
			//处理excel中不存在的数据，使初始化余额为0
			//甲单根据抬头和结算单位聚合
			$sql="select *,sum(fee) as money from turnover where big_type='purchase' and is_yidan = 0 and (status = 'submited' or status='accounted') group by title_id,target_id";
			$cmd = Yii::app()->db->createCommand($sql);
			$turnlist = $cmd->queryAll($cmd);

			foreach($turnlist as $li){
				//余额为零的不处理
				if($li['money'] != 0){
					//判断数据是否在excel中，如果不在，则初始化余额为0；
					$has = true;
					foreach ($data as $da){
						if($da['title_id'] == $li['title_id'] && $da['supply_id'] == $li['target_id'] && $da['yidan'] == $li['is_yidan']){
							$has = false;
							break;
						}
					}
					if($has){
						$model = new Turnover();
						$model->turnover_type = "CGMX";
						$model->turnover_direction = "need_pay";
						$model->title_id = $li['title_id'];
						$model->target_id = $li['target_id'];
						$model->description = "结转补录往来-不在给的表中的甲单补平往来";
						$model->amount = 1;
						$model->price = 0-$li['money'];
						$model->fee = 0-$li['money'];
						$model->status = 'submited';
						$model->ownered_by = 1;
						$model->created_by = 1;
						$model->is_yidan = $li['is_yidan'];
						$model->big_type = "purchase";
						if(!$model->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
					}
				}
			}

			//乙单根据结算单位聚合
			$sql2="select *,sum(fee) as money from turnover where big_type='purchase' and is_yidan = 1 and (status = 'submited' or status='accounted') group by target_id";
			$cmd2 = Yii::app()->db->createCommand($sql2);
			$turnlist2 = $cmd->queryAll($cmd2);

			foreach($turnlist2 as $li){
				//余额为零的不处理
				if($li['money'] != 0){
					//判断数据是否在excel中，如果不在，则初始化余额为0；
					$has = true;
					foreach ($data as $da){
						if($da['supply_id'] == $li['target_id'] && $da['yidan'] == $li['is_yidan']){
							$has = false;
							break;
						}
					}
					if($has){
						$model = new Turnover();
						$model->turnover_type = "CGMX";
						$model->turnover_direction = "need_pay";
						$model->title_id = 14;//瑞亮id
						$model->target_id = $li['target_id'];
						$model->description = "结转补录往来-不在给的表中的乙单补平往来";
						$model->amount = 1;
						$model->price = 0-$li['money'];
						$model->fee = 0-$li['money'];
						$model->status = 'submited';
						$model->ownered_by = 1;
						$model->created_by = 1;
						$model->is_yidan = $li['is_yidan'];
						$model->big_type = "purchase";
						if(!$model->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
					}
				}
			}
		}
		$this->render("turnoverInital",array('str'=>$str));
	}

	//销售单导入
	public function actionSalesDetailImport()
	{
		Yii::$enableIncludePath = false;
		$this->pageTitle = "销售单导入";
		if($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$data = array();
			$data['detail'] = array();
			for($i=2;$i<=$highestRow;$i++){
				$checkstatus = $sheet->getCell("W".$i)->getValue();
				//如果是已审单，则不处理；
				if($checkstatus == "已审单" && $highestRow != $i){continue;}
				$fn = $sheet->getCell("A".$i)->getValue();//西本销售单号
				$form_time = $sheet->getCell("B".$i)->getValue();
				$status = $sheet->getCell("AA".$i)->getValue();
				$pro = $sheet->getCell("E".$i)->getValue();
				if($pro == "高线"){
					$pro = "线材";
				}
				$brand_name = $sheet->getCell("H".$i)->getValue();
				$temp=array();
				$temp['product_id']=DictGoodsProperty::getIdByName($pro);
				$temp['texture_id']=DictGoodsProperty::getIdByName($sheet->getCell("I".$i)->getValue());
				$temp['brand_id']=DictGoodsProperty::getIdByName($brand_name);
				$temp['rank_id']=DictGoodsProperty::getIdByName($sheet->getCell("F".$i)->getValue());
				$temp['length']=$sheet->getCell("G".$i)->getValue();
				$temp['amount']=$sheet->getCell("J".$i)->getValue();
				$temp['weight']=$sheet->getCell("L".$i)->getValue();
				$temp['price']=$sheet->getCell("M".$i)->getValue();
				$temp['card_id']=0;
				$temp['total_amount'] = $sheet->getCell("N".$i)->getValue();
				if($fn != $sales['comment']){
					//保存上一条销售单数据,并初始化数组
					if($sales['comment']){
						$result = FrmSales::setSales($data);
						if($result === -1){
							$str.=$sales['comment']."的库存不足！<br/>";
						}
						if($status=="已审批" && $result > 0){
							$form=new Sales($result);
							$form->approveForm();
						}
					}
					$sales=array();
					$data = array();
					$com = array();
					$type= $sheet->getCell("Z".$i)->getValue();
					$data['detail'] = array();
					array_push($data['detail'], (Object)$temp);
					$sales['comment'] = $fn;
					$com['form_time'] = date("Y-m-d",strtotime($form_time));
					$com['owned_by'] = User::getUserId($sheet->getCell("C".$i)->getValue());
					if($com['owned_by'] == 0){
						$str.=$fn."的业务员不存在！<br/>";
						continue;
					}
					$com['form_type']='XSD';
					$sales['customer_id'] = intval(DictCompany::getCompanyId($sheet->getCell("D".$i)->getValue()));
					if($sales['customer_id'] == 0){
						$str.=$fn."的采购商不存在！<br/>";
						continue;
					}
					$sales['title_id'] = intval(DictTitle::getTitleId($sheet->getCell("AH".$i)->getValue()));
					if($sales['title_id'] == 0){
						$str.=$fn."的抬头不存在！<br/>";
						continue;
					}
					$sales['warehouse_id'] = Warehouse::getWarehouseId($sheet->getCell("Y".$i)->getValue());
					if(!$sales['warehouse_id'] && $type=="库存销售"){
						$str.=$fn."的仓库不存在！<br/>";
						continue;
					}
					$sales['is_import'] = 1;
					$yidan = $sheet->getCell("V".$i)->getValue();
					if($yidan){
						$sales['is_yidan'] = 1;
					}else{
						$sales['is_yidan'] = 0;
					}

					if($type == "先销后进"){
						$sales['sales_type'] = 'xxhj';
						//设置先销后进仓库
						$sales['warehouse_id']=5;
					}
// 					elseif($brand_name == "溧阳三元"){
// 						$sales['sales_type'] = 'dxxs';
// 					}
					else{
						$sales['sales_type'] = 'normal';
					}
					$data['common']=(Object)$com;
					$data['main']=(Object)$sales;
				}else{
					$has = false;
					//循环判断是否有相同明细
					foreach ($data['detail'] as $li){
						if($li->product_id==$temp["product_id"]&&$li->texture_id==$temp["texture_id"]&&$li->brand_id==$temp["brand_id"]&&$li->rank_id==$temp["rank_id"]&&$li->length==$temp["length"]){
							$li->amount += $temp["amount"];
							$li->weight += $temp["weight"];
							$li->total_amount +=$temp['total_amount'];
							$has = true;
						}
					}
					if(!$has){
						array_push($data['detail'], (Object)$temp);
					}
				}
				 if($highestRow == $i && $checkstatus != "已审单"){
				 	$result = FrmSales::setSales($data);
				 	if($result === -1){
				 		$str.=$fn."的库存不足！<br/>";
				 	}
				 	if($status=="已审批" && $result > 0){
				 		$form=new Sales($result);
				 		$form->approveForm();
				 	}
				 }
			}
		}

		$this->render("importtitle",array(
				'str'=>$str,
		));
	}

	//更新结算单位类型
	public function actionSetType(){
		Yii::$enableIncludePath = false;
		$this->pageTitle = "结算单位类型更新";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			for($i=2;$i<=$highestRow;$i++){
				$name = $sheet->getCell("A".$i)->getValue();
				$is_logistics = $sheet->getCell("B".$i)->getValue();
				$is_customer = $sheet->getCell("C".$i)->getValue();
				$is_supply = $sheet->getCell("D".$i)->getValue();
				if($is_customer){$is_customer=1;}else{$is_customer=0;}
				if($is_logistics){$is_logistics=1;}else{$is_logistics=0;}
				if($is_supply){$is_supply=1;}else{$is_supply=0;}
				$company = DictCompany::model()->find("name='".$name."'");
				if($company){
						$company->is_logistics=$is_logistics;
						$company->is_customer=$is_customer;
						$company->is_supply=$is_supply;
						$company->update();
				}else{
					$str.=$name."：没有找到！<br/>";
				}
			}
		}
		$this->render("importtitle",array(
				'str'=>$str,
		));
	}

	//根据西本销售单号查找进销存单号
	public function actionFindSalesSn(){
		Yii::$enableIncludePath = false;
		$this->pageTitle = "查找进销存单号";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$str = "";
// 			$str1 = "已完成销售单：<br/>";
// 			$str2 = "提货为0的销售单：<br/>";
// 			$str3 = "没有配送的销售单：<br/>";
			$num = 0;
			for($i=2;$i<=$highestRow;$i++){
				$sn = $sheet->getCell("A".$i)->getValue();
				$num = $sheet->getCell("I".$i)->getValue();
				$sn = trim($sn);
				//$sales = FrmSales::model()->find("comment like '%$sn%'");
				$sales = FrmSales::model()->with("baseform")->find("baseform.form_sn='".$sn."'");
				if($sales){
					if($sales->confirm_status == 1) {continue;}
					$str .= $sn."=>".$sales->baseform->belong->nickname."<br/>";
// 					if($num == 0){
// 						$str2.=$sales->baseform->form_sn.",".$sales->comment."<br/>";
// 					}
// 					if($sales->confirm_status == 1){
// 						$str1.=$sales->baseform->form_sn.",".$sales->comment."<br/>";
// 					}else{
// 						if($num == 0){
// 							$str2.=$sales->baseform->form_sn.",".$sales->comment."<br/>";
// 						}else{
// 							$send = FrmSend::model()->find("frm_sales_id=".$sales->id);
// 							if($send){
// 								//$str.=$sales->baseform->form_sn.",".$sales->comment."<br/>";
// 								$str .="$sn<br/>";
// 							}else{
// 								//$str3.=$sales->baseform->form_sn.",".$sales->comment."<br/>";
// 							}
// 						}
// 					}
				}else{
					echo 1;
				}
			}
		}
		$this->render("importtitle",array(
				'str'=>$str,
				'str1'=>$str1,
				'str2'=>$str2,
				'str3'=>$str3,
		));
	}



	//采购往来结转补录
	public function actionPurchaseImportForConfirmed(){
		Yii::$enableIncludePath = false;
		$this->pageTitle = "采购往来结转补录导入";
		$str = '';
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$data = array();
			for($i=2;$i<=$highestRow;$i++){
				$temp = array();
				$title_name = $sheet->getCell("A".$i)->getValue();
				$supply_name = $sheet->getCell("B".$i)->getValue();
				$money = $sheet->getCell("C".$i)->getValue();
				$yidan = $sheet->getCell("D".$i)->getValue();
				if($yidan == "乙单"){
					$yidan = 1;
				}else{
					$yidan = 0;
				}
				$title_id = intval(DictTitle::getTitleId($title_name));
				if($title_id == 0){
					$str.=$title_name."的抬头不存在！<br/>";
					continue;
				}
				$supply_id = intval(DictCompany::getCompanyId($supply_name));
				if($supply_id == 0){
					$str.=$supply_name."的供应商不存在！<br/>";
					continue;
				}
				$temp['title_id'] = $title_id;
				$temp['supply_id'] = $supply_id;
				$temp['money'] = $money;
				$temp['yidan'] = $yidan;
				array_push($data,$temp);
				if($yidan)
				{
					$sql = "select *,sum(fee) as money from turnover where target_id=".$supply_id."  and is_yidan=".$yidan." and big_type='purchase' and status!='delete' and confirmed=1 and created_at<1461945600 group by target_id";
				}else{
					$sql = "select *,sum(fee) as money from turnover where target_id=".$supply_id." and title_id=".$title_id." and is_yidan=".$yidan." and big_type='purchase' and status!='delete' and confirmed=1 and created_at<1461945600 group by target_id";
				}
				$cmd = Yii::app()->db->createCommand($sql);
				$turn = $cmd->queryRow($cmd);
				if($turn['money'] != $money){
					$cha = $money - $turn['money'];
					$model = new Turnover();
					$model->turnover_type = "CGMX";
					$model->turnover_direction = "need_pay";
					$model->title_id = $title_id;
					$model->target_id = $supply_id;
					$model->description = "结转补录往来";
					$model->amount = 1;
					$model->price = $cha;
					$model->fee = $cha;
					$model->status = 'submited';
					$model->ownered_by = 1;
					$model->created_by = 1;
					$model->is_yidan = $yidan;
					$model->confirmed=1;
					$model->big_type = "purchase";
					if(!$model->insert()){
						$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
					}

					//补条相反的往来

					$model1 = new Turnover();
					$model1->turnover_type = "CGMX";
					$model1->turnover_direction = "need_pay";
					$model1->title_id = $title_id;
					$model1->target_id = $supply_id;
					$model1->description = "结转补录往来";
					$model1->amount = 1;
					$model1->price = $cha;
					$model1->fee = -$cha;
					$model1->status = 'submited';
					$model1->ownered_by = 1;
					$model1->created_by = 1;
					$model1->is_yidan = $yidan;
					$model1->confirmed=0;
					$model1->big_type = "purchase";
					if(!$model1->insert()){
						$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
					}
				}
			}
			//处理excel中不存在的数据，使初始化余额为0
			// $sql = "select *,sum(fee) as money from turnover where target_id=".$supply_id." and title_id=".$title_id." and is_yidan=".$yidan." and big_type='purchase' and status!='delete' and confirmed=1 and created_at<1461859200 group by target_id";
			$sql="select *,sum(fee) as money from turnover where big_type='purchase' and status!='delete' and confirmed=1 and is_yidan=0 and created_at<1461945600 group by title_id,target_id,is_yidan";
			$cmd = Yii::app()->db->createCommand($sql);
			$turnlist = $cmd->queryAll($cmd);
			foreach($turnlist as $li){
				//余额为零的不处理
				if($li['money'] != 0){
					//判断数据是否在excel中，如果不在，则初始化余额为0；
					$has = true;
					foreach ($data as $da){

						if($da['title_id'] == $li['title_id'] && $da['supply_id'] == $li['target_id'] && $da['yidan'] == $li['is_yidan']&&$da['yidan']==0){
							$has = false;
							break;
						}
					}

					if($has){
						$model = new Turnover();
						$model->turnover_type = "CGMX";
						$model->turnover_direction = "need_pay";
						$model->title_id = $li['title_id'];
						$model->target_id = $li['target_id'];
						$model->description = "结转补录往来";
						$model->amount = 1;
						$model->price = 0-$li['money'];
						$model->fee = 0-$li['money'];
						$model->status = 'submited';
						$model->ownered_by = 1;
						$model->created_by = 1;
						$model->confirmed=1;
						$model->is_yidan = $li['is_yidan'];
						$model->big_type = "purchase";
						if(!$model->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
						//补条相反的往来
						$model1 = new Turnover();
						$model1->turnover_type = "CGMX";
						$model1->turnover_direction = "need_pay";
						$model1->title_id = $li['title_id'];
						$model1->target_id = $li['target_id'];
						$model1->description = "结转补录往来";
						$model1->amount = 1;
						$model1->price = $li['money'];
						$model1->fee = $li['money'];
						$model1->status = 'submited';
						$model1->ownered_by = 1;
						$model1->created_by = 1;
						$model1->confirmed=0;
						$model1->is_yidan = $li['is_yidan'];
						$model1->big_type = "purchase";
						if(!$model1->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
					}
				}
			}


			$sql="select *,sum(fee) as money from turnover where big_type='purchase' and status!='delete' and confirmed=1 and is_yidan=1 and created_at<1461945600 group by target_id,is_yidan";
			$cmd = Yii::app()->db->createCommand($sql);
			$turnlist = $cmd->queryAll($cmd);
			foreach($turnlist as $li){
				//余额为零的不处理
				if($li['money'] != 0){
					//判断数据是否在excel中，如果不在，则初始化余额为0；
					$has = true;
					foreach ($data as $da){
						if($da['supply_id'] == $li['target_id'] && $da['yidan'] == $li['is_yidan']&&$da['yidan']==1){
							$has = false;
							break;
						}
					}

					if($has){
						$model = new Turnover();
						$model->turnover_type = "CGMX";
						$model->turnover_direction = "need_pay";
						$model->title_id = $li['title_id'];
						$model->target_id = $li['target_id'];
						$model->description = "结转补录往来";
						$model->amount = 1;
						$model->price = 0-$li['money'];
						$model->fee = 0-$li['money'];
						$model->status = 'submited';
						$model->ownered_by = 1;
						$model->created_by = 1;
						$model->confirmed=1;
						$model->is_yidan = $li['is_yidan'];
						$model->big_type = "purchase";
						if(!$model->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
						//补条相反的往来
						$model1 = new Turnover();
						$model1->turnover_type = "CGMX";
						$model1->turnover_direction = "need_pay";
						$model1->title_id = $li['title_id'];
						$model1->target_id = $li['target_id'];
						$model1->description = "结转补录往来";
						$model1->amount = 1;
						$model1->price = $li['money'];
						$model1->fee = $li['money'];
						$model1->status = 'submited';
						$model1->ownered_by = 1;
						$model1->created_by = 1;
						$model1->confirmed=0;
						$model1->is_yidan = $li['is_yidan'];
						$model1->big_type = "purchase";
						if(!$model1->insert()){
							$str.=$title_name."/".$supply_name."的数据插入失败！<br/>";
						}
					}
				}
			}



		}
		$this->render("importtitle",array(
				'str'=>$str,
		));
	}

	//更新结算单位财务编码
	public function actionSetCusCaiwuCode(){
		Yii::$enableIncludePath = false;
		$this->pageTitle = "结算单位更新财务编码";
		$name1 = "结算单位编码修改结果".date("Y/m/d");
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$str = "";
			for($i=2;$i<=$highestRow;$i++){
				$code = $sheet->getCell("A".$i)->getValue();
				$name = $sheet->getCell("C".$i)->getValue();
				$company = DictCompany::model()->find("name='".$name."'");
				if($company){
					$company->cus_number=$code;
					$company->update();
					$str .="{$code}%{$name}/{$company->name}*成功<br/>";
				}else{
					$str .="{$code}%{$name}/no*失败<br/>";
				}
			}
		}
		$this->render("importtitle",array(
				'str'=>$str,
		));
	}


	//更新供应商财务编码
	public function actionSetSupCaiwuCode(){
		Yii::$enableIncludePath = false;
		$this->pageTitle = "供应商更新财务编码";
		if ($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$str = "";
			for($i=2;$i<=$highestRow;$i++){
				$code = $sheet->getCell("A".$i)->getValue();
				$name = $sheet->getCell("C".$i)->getValue();
				$company = DictCompany::model()->find("name='".$name."'");
				if($company){
					$company->sup_number=$code;
					$company->update();
					$str .="{$code}%{$name}/{$company->name}*成功<br/>";
				}else{
					$str .="{$code}%{$name}/no*失败<br/>";
				}
			}
		}
		$this->render("importtitle",array(
				'str'=>$str,
		));
	}
}
