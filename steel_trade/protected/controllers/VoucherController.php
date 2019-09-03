<?php
class VoucherController extends AdminBaseController
{
	//财务凭证首页
	public function actionIndex(){
		$this->pageTitle = "财务凭证列表";
		$tableHeader = array(
				array('name'=>'','class' =>"text-center",'width'=>"20px"),
				array('name'=>'操作','class' =>"",'width'=>"80px"),
				array('name'=>'凭证字-号','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'摘要','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'科目编码','class' =>"flex-col",'width'=>"85px"),
				array('name'=>'科目名称','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'借方金额','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'贷方金额','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'业务日期','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'制单人','class' =>"flex-col",'width'=>"50px"),//
				array('name'=>'制单日期','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'结算单位','class' =>"flex-col",'width'=>"80px"),//
		);
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		$search=updateSearch($search,'search_voucher_index');
		list($tableData,$pages)=Voucher::getFormList($search);
		
		//获取没有编码的财务系统数据条数
		//用户
		$user = User::model()->findAll("number=''");
		$user_no = count($user);
		//银行账户
		$bank = DictBankInfo::model()->findAll("number=''");
		$bank_no = count($bank);
		//公司抬头
		$title = DictTitle::model()->findAll("in_number=''");
		$title_no = count($title);
		//客户
		$company = DictCompany::model()->findAll("cus_number='' and is_customer=1");
		$company_no = count($company);
		//供应商
		$supply = DictCompany::model()->findAll("sup_number='' and is_supply=1");
		$supply_no = count($supply);
		$no1 = $user_no + $bank_no + $title_no;
		$no2 = $company_no + $supply_no;
		$this->render('index',array(
				"no1"=>$no1,
				"no2"=>$no2,
				"tableData"=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'tableHeader'=>$tableHeader,
		));
	}
	
	//获取没有编码的数据列表
	public function actionGetNoList(){
		$model = $_POST["model"];
		//获取没有编码的财务系统数据条数
		//用户
		$user = User::model()->findAll("number=''");
		//银行账户
		$bank = DictBankInfo::model()->findAll("number=''");
		//公司抬头
		$title = DictTitle::model()->findAll("in_number=''");
		//客户
		$company = DictCompany::model()->findAll("cus_number='' and is_customer=1");
		//供应商
		$supply = DictCompany::model()->findAll("sup_number='' and is_supply=1");
	
		$this->renderPartial('_list',array(
			"model"=>$model,
			"user"=>$user,
			"bank"=>$bank,
			"title"=>$title,
			"company"=>$company,
			"supply"=>$supply,
		));
	}
	
	//列表导出
	public function actionExport(){
		$search=$_REQUEST['search'];
		$name = "pzh".date("YmdHis");
		$title=array("FDate","FYear","FPeriod","FGroupID","FNumber","FAccountNum","FAccountName","FCurrencyNum","FCurrencyName","FAmountFor","FDebit","FCredit","FPreparerID",
				"FCheckerID","FApproveID","FCashierID","FHandler","FSettleTypeID","FSettleNo","FExplanation","FQuantity","FMeasureUnitID","FUnitPrice","FReference",
				"FTransDate","FTransNo","FAttachments","FSerialNum","FObjectName","FParameter","FExchangeRate","FEntryID","FItem","FPosted","FInternalInd","FCashFlow"
		);
		//第一个脚本固定数据
		$sheet = array(
				array("FType","FKey","FFieldName","FCaption","FValueType","FNeedSave","FColIndex","FSrcTableName","FSrcFieldName","FExpFieldName","FImpFieldName","FDefaultVal","FSearch","FItemPageName","FTrueType","FPrecision","FSearchName","FIsShownList","FViewMask","FPage"),
				array("ClassInfo","ClassType"," ","VoucherData"),
				array("ClassInfo","ClassTypeID"	," ","123"),
				array("PageInfo","Page1"," ","Page1"),
				array("FieldInfo","FDate","FDate","凭证日期","DateTime"," ","1"," "," ","FDate","FDate"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FYear","FYear","会计年度","Decimal(28,10)"," ","2"," "," ","FYear","FYear"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FPeriod","FPeriod","会计期间","Decimal(28,10)"," ","3"," "," ","FPeriod","FPeriod"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FGroupID","FGroupID","凭证字","Varchar(80)"," ","4"," "," ","FGroupID","FGroupID"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FNumber","FNumber","凭证号","Decimal(28,10)"," ","5"," "," ","FNumber","FNumber"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FAccountNum","FAccountNum","科目代码","Varchar(40)"," ","7"," "," ","FAccountNum","FAccountNum"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FAccountName","FAccountName","科目名称","Varchar(80)"," ","8"," "," ","FAccountName","FAccountName"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FCurrencyNum","FCurrencyNum","币别代码","Varchar(10)"," ","9"," "," ","FCurrencyNum","FCurrencyNum"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FCurrencyName","FCurrencyName","币别名称","Varchar(40)"," ","10"," "," ","FCurrencyName","FCurrencyName"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FAmountFor","FAmountFor","原币金额","Decimal(28,10)"," ","11"," "," ","FAmountFor","FAmountFor"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FDebit","FDebit","借方","Decimal(28,10)"," ","12"," "," ","FDebit","FDebit"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FCredit","FCredit","贷方","Decimal(28,10)"," ","13"," "," ","FCredit","FCredit"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FPreparerID","FPreparerID","制单","Varchar(255)"," ","14"," "," ","FPreparerID","FPreparerID"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FCheckerID","FCheckerID","审核","Varchar(255)"," ","15"," "," ","FCheckerID","FCheckerID"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FApproveID","FApproveID","核准","Varchar(255)"," ","17"," "," ","FApproveID","FApproveID"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FCashierID","FCashierID","出纳","Varchar(255)"," ","18"," "," ","FCashierID","FCashierID"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FHandler","FHandler","经办","Varchar(50)"," ","19"," "," ","FHandler","FHandler"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FSettleTypeID","FSettleTypeID","结算方式","Varchar(80)"," ","20"," "," ","FSettleTypeID","FSettleTypeID"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FSettleNo","FSettleNo","结算号","Varchar(255)"," ","21"," "," ","FSettleNo","FSettleNo"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FExplanation","FExplanation","凭证摘要","Varchar(255)"," ","22"," "," ","FExplanation","FExplanation"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FQuantity","FQuantity","数量","Decimal(28,10)"," ","23"," "," ","FQuantity","FQuantity"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FMeasureUnitID","FMeasureUnitID","数量单位","Varchar(255)"," ","24"," "," ","FMeasureUnitID","FMeasureUnitID"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FUnitPrice","FUnitPrice","单价","Decimal(28,10)"," ","25"," "," ","FUnitPrice","FUnitPrice"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FReference","FReference","参考信息","Varchar(255)"," ","26"," "," ","FReference","FReference"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FTransDate","FTransDate","业务日期","DateTime"," ","27"," "," ","FTransDate","FTransDate"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FTransNo","FTransNo","往来业务编号","Varchar(40)"," ","28"," "," ","FTransNo","FTransNo"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FAttachments","FAttachments","附件数","Decimal(28,10)"," ","29"," "," ","FAttachments","FAttachments"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FSerialNum","FSerialNum","序号","Decimal(28,10)"," ","30"," "," ","FSerialNum","FSerialNum"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FObjectName","FObjectName","系统模块","Varchar(100)"," ","31"," "," ","FObjectName","FObjectName"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FParameter","FParameter","业务描述","Varchar(100)"," ","32"," "," ","FParameter","FParameter"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FExchangeRate","FExchangeRate","汇率","Decimal(28,10)"," ","33"," "," ","FExchangeRate","FExchangeRate"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FEntryID","FEntryID","分录序号","Decimal(28,10)"," ","34"," "," ","FEntryID","FEntryID"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FItem","FItem","核算项目","Varchar(255)"," ","35"," "," ","FItem","FItem"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FPosted","FPosted","过账","Decimal(28,10)"," ","36"," "," ","FPosted","FPosted"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FInternalInd","FInternalInd","机制凭证","Varchar(10)"," ","37"," "," ","FInternalInd","FInternalInd"," ","0"," "," ","0","0","0","0","1"),
				array("FieldInfo","FCashFlow","FCashFlow","现金流量","Memo"," ","38"," "," ","FCashFlow","FCashFlow"," ","0"," "," ","0","0","0","0","1"),
		);
		
		$content=Voucher::getAllList($search);
		PHPExcel::BusinessExcelExport($name,$title,$content,$sheet);
	}
	
	//新建业务凭证
	public function actionCreateBusiness(){
		$this->pageTitle = "新增业务凭证";
		$tableHeader = array(
				array('name'=>'','class' =>"text-center",'width'=>"20px"),
				array('name'=>'<input type="checkbox" class="checkAll">','class' =>"text-center",'width'=>"30px"),
				array('name'=>'操作','class' =>"",'width'=>"30px"),
				array('name'=>'单号','class' =>"flex-col",'width'=>"90px"),
				array('name'=>'分类','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'开单日期','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'业务员','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'公司','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'结算单位','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'件数','class' =>"flex-col text-right",'width'=>"50px"),//
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'金额','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'乙单','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'制单日期','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'备注','class' =>"flex-col",'width'=>"100px"),//
		);
		//判断页面来源是否是页面本身，如果不是清除cookie
		$form_url =  $_SERVER["HTTP_REFERER"];
		if(!stristr($form_url,"createBusiness")){
			setcookie("check_voucher_list","",0,"/");
		}
		//客户
		$vendor=DictCompany::getAllComs("json");
		//公司
		$title=DictTitle::getComs("json");
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableData,$pages)=Voucher::getBusinessList($search);
		$this->render('createBusiness',array(
				'vendors'=>$vendor,
				'title'=>$title,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
		));
	}
	//处理新建业务凭证数据
	public function actionProcessBusinessData(){
		$result = Voucher::processBusiData();
		echo $result;
	}
	
	//处理新建收付款凭证数据
	public function actionProcessPaymentData(){
		$result = Voucher::processPayData();
		echo $result;
	}
	//新建收付款凭证
	public function actionCreatePayment(){
		$this->pageTitle = "新增收付款凭证";
		$tableHeader = array(
				array('name'=>'','class' =>"text-center",'width'=>"20px"),
				array('name'=>'<input type="checkbox" class="checkAll">','class' =>"text-center",'width'=>"30px"),
				array('name'=>'操作','class' =>"",'width'=>"30px"),
				array('name'=>'单号','class' =>"flex-col",'width'=>"90px"),
				array('name'=>'分类','class' =>"flex-col",'width'=>"60px"),//
				//array('name'=>'支付方式','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'开单日期','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'负责人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'公司','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'结算单位','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'件数','class' =>"flex-col text-right",'width'=>"50px"),//
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'金额','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'乙单','class' =>"flex-col",'width'=>"40px"),//
				array('name'=>'制单日期','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'备注','class' =>"flex-col",'width'=>"150px"),//
		);
		//判断页面来源是否是页面本身，如果不是清除cookie
		$form_url =  $_SERVER["HTTP_REFERER"];
		if(!stristr($form_url,"createPayment")){
			setcookie("check_payment_list","",0,"/");
		}
		//客户
		$vendor=DictCompany::getAllComs("json");
		//公司
		$title=DictTitle::getComs("json");
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableData,$pages)=Voucher::getPaymentList($search);
		
		$this->render('createPayment',array(
				'vendors'=>$vendor,
				'title'=>$title,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
		));
	}
	
	//忽略一条业务凭证
	public function actionIgnoreform(){
		$id = $_REQUEST["id"];
		if($id){
			$baseform = CommonForms::model()->findByPk($id);
			if($baseform){
				$baseform->is_voucher = 2;
				if($baseform->update()){
					$base = new BaseForm();
					$base->operationLog("财务凭证", "忽略",$baseform->form_sn);
					echo "success";
				}else{
					echo "更新信息失败";
				}
			}else{
				echo "获取信息失败";
			}
		}else{
			echo "获取信息失败";
		}
	}
	
	//忽略凭证列表
	public function actionIgnoreList(){
		$this->pageTitle = "已忽略凭证列表";
		$tableHeader = array(
				array('name'=>'','class' =>"flex-col text-center",'width'=>"20px"),
				array('name'=>'操作','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'单号','class' =>"flex-col",'width'=>"90px"),
				array('name'=>'类型','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'业务日期','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'业务员','class' =>"flex-col",'width'=>"50px"),
		);
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//$search=updateSearch($search,'search_voucher_index');
		list($tableData,$pages)=Voucher::getIgnoreList($search);
		
		$this->render('ignorelist',array(
				"tableData"=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'tableHeader'=>$tableHeader,
		));
	}
	
	//作废生成的业务凭证
	public function actionDeleteform($id){
		$transaction=Yii::app()->db->beginTransaction();
		try {
				$voucher = Voucher::model()->findByPk($id);
				$detail = $voucher->voucherDetails;
				$voucher->is_deleted = 1;
				$voucher->update();
				$gkDetails = $voucher->gkDetails;
				$arr=array();
				if($detail){
					foreach ($detail as $li){
						if($li->billother_id)
						{
							if(!in_array($li->billother_id, $arr))
							{
								$billother_detail=BillOtherDetail::model()->findByPk($li->billother_id);
								$billother_detail->is_voucher=0;
								$billother_detail->update();
								array_push($arr, $li->billother_id);								
							}
						}else{
							if($li->common_id > 0){
								$baseform = $li->baseform;
								$baseform->is_voucher = 0;
								$baseform->update();
							}
						}						
					}
				}
				if($gkDetails){
					foreach ($gkDetails as $li){
						if($li->common_id > 0){
							$baseform = $li->baseform;
							$baseform->is_voucher = 0;
							$baseform->update();
						}
					}
				}
				$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			echo "更新失败";
			die;
		}
		echo "success";
	}
	
	//编辑业务凭证
	public function actionUpdate($id){
		if($_POST["Voucher"]){
			$result = Voucher::updateData($_POST["Voucher"],$id);
			if($result==1){
				$this->redirect(yii::app()->createUrl("voucher/index",array('page'=>$_REQUEST['fpage'])));
			}elseif($result == -1){
				$msg = "凭证号已存在";
			}else{
				$msg = "保存失败";
			}
		}
		$model = Voucher::model()->findByPk($id);
		$detail = $model->voucherDetails;
		$this->render('update',array(
				'model'=>$model,
				'detail'=>$detail,
				'msg'=>$msg,
		));
	}
	
	//设置是否已导出
	public function actionChange($id){
		$type = $_REQUEST["type"];
		$model = Voucher::model()->findByPk($id);
		if($type == "is"){
			$model->is_export=1;
			if($model->update()){
				echo "success";
			}else{
				echo "设置失败";
			}
		}
		if($type == "no"){
			$model->is_export=0;
			if($model->update()){
				echo "success";
			}else{
				echo "设置失败";
			}
		}
	}
	
	//恢复忽略的单据
	public function actionCancleIgonre($id){
		$base = CommonForms::model()->findByPk($id);
		$base->is_voucher = 0;
		if($base->update()){
			echo "success";
		}else{
			echo "恢复失败";
		}
	}
	
}
