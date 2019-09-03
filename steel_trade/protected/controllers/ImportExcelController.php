<?php 
header("Content-type: text/html; charset=utf-8"); 
/**
* 导入Excel
*/
class ImportExcelController extends AdminBaseController
{
	public $layout = 'admin';

	public function actionIndex() 
	{
		$this->pageTitle = "导入Excel";

		if ($_FILES['import']['tmp_name']) 
		{
			$error_array = $this->importSK($_FILES);
			$msg = "";
			if (count($error_array) > 0) {
				foreach ($error_array as $error) {
					$msg .= "; ".$error->message.", 单号：".$error->form_sn;
				}
				$msg = "导入失败：".substr($msg, 1);
			} else {
				$msg .= "导入成功";
			}
		}

		$this->render('index', array(
			'msg' => $msg,
		));
	}

	public function importSK($files) 
	{
		set_time_limit(0);
		$objExcel = new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$filename = $files['import']['tmp_name'];
		$objPHPExcel = $objReader->load($filename);
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		//获取用户
		$users = User::getUserList('array');
		//获取公司抬头
		$titles = DictTitle::getComs('array');
		//获取结算单位
		$companys = DictCompany::getComs('array'); 

		$error_array = array();
		for ($i = 2; $i <= $highestRow; $i++) 
		{ 
			$baseform = new CommonForms();
			$model = new FrmFormBill();

			//基础数据
			$baseform->form_type = "SKDJ";
			$baseform->form_sn = $sheet->getCell("A".$i)->getValue(); //单号
			$baseform->created_at = strtotime($sheet->getCell("B".$i)->getValue()); //日期
			$baseform->created_by = array_search("邢本勇", $users); //制单人
			switch ($sheet->getCell("K".$i)->getValue()) 
			{
				case '已审批':
					$baseform->form_status = 'submited'; //收款无需审批
					$baseform->approved_at = $baseform->created_at; //审核时间
					$baseform->approved_by = $baseform->created_by; //审核人
				default: break;
			}
			switch ($sheet->getCell("M".$i)->getValue()) //入账状态
			{
				case '已审单': 
					$baseform->form_status = 'accounted';
					$model->account_at = $baseform->created_at;
					$model->account_by = $baseform->created_by;
					$model->reach_at = $baseform->created_at; //到账日期
					break;
				default: break;
			}
			$baseform->last_update = $baseform->created_at;
			$baseform->last_updated_by = $baseform->created_by;
			$baseform->owned_by = array_search($sheet->getCell("P".$i)->getValue(), $users);
			$baseform->is_deleted = 0;
			$baseform->comment = $sheet->getCell("R".$i)->getValue();
			if (!$baseform->insert()) {
				$error = array('form_sn' => $baseform->form_sn, 'message' => "基础数据保存失败");
				$error_array[] = (Object)$error;
				continue;
			}

			//主单数据
			$model->form_id = $baseform->id;
			$model->form_type = "sales";
			$model->bill_type = array_search($sheet->getCell("D".$i)->getValue(), FrmFormBill::$billTypes); //登记类型
			$model->is_yidan = $sheet->getCell("L".$i)->getValue() == '√' ? 1 : null; //乙单
			$model->pay_type = array_search($sheet->getCell("E".$i)->getValue(), FrmFormBill::$payTypes); //收款类型

			//公司抬头
			$model->title_id = array_search($sheet->getCell("N".$i)->getValue(), $titles); 
			if (!$model->title_id) {
				$error = array(
					'form_sn' => $baseform->form_sn, 
					'message' => "公司“".$sheet->getCell("N".$i)->getValue()."”不存在"
				);
				$error_array[] = (Object)$error;
				continue;
			}
			//获取公司账户
			$title_banks = DictBankInfo::getComs('array', $model->title_id);
			//公司账户
			switch ($sheet->getCell("N".$i)->getValue()) {
				case '瑞亮物资':
					$dict_name = "农行上海五角场支行（瑞亮）-";
					if ($model->pay_type == 'money') $dict_name = "小陈农行";
					break;
				case '乘翔实业': 
					$dict_name = "农行上海五角场支行（乘翔）-";
					if ($model->pay_type == 'money') $dict_name = "小陈农行";
					break;
				default: break;
			}
			$model->dict_bank_info_id = array_search($dict_name, $title_banks);
			// $model->dict_bank_info_id = array_search();($sheet->getCell("U".$i)->getValue(), $title_banks) ? array_search($sheet->getCell("U".$i)->getValue(), $title_banks) : 0; 
			if (!$model->dict_bank_info_id) {
				$error = array(
					'form_sn' => $baseform->form_sn, 
					'message' => "公司账户不存在"
				);
				$error_array[] = (Object)$error;
				continue;
			}
			//结算单位
			$model->company_id = array_search($sheet->getCell("C".$i)->getValue(), $companys); 
			if (!$model->company_id) {
				$error = array(
					'form_sn' => $baseform->form_sn, 
					'message' => "结算单位“".$sheet->getCell("C".$i)->getValue()."”不存在"
				);
				$error_array[] = (Object)$error;
				continue;
			}
			$model->fee = floatval($sheet->getCell("F".$i)->getValue()); //开单金额
			if (!$model->insert()) {
				$error_array[] = $baseform->form_sn;
				continue;
			}
			$baseform->form_id = $model->id;
			$baseform->update();

			//往来
			$type = $baseform->form_type;
			$turnover_direction = "charged"; //收款
			$title_id = $model->title_id; //公司抬头
			$target_id = $model->company_id; //结算单位
			// $amount = $model->weight > 0 ? $model->weight : 1; //重量
			$fee = $model->fee; //总金额
			// $price = $fee; //单价
			$is_yidan = $model->is_yidan; //乙单
			$common_forms_id = $baseform->id; //对应单据id
			$created_by = $baseform->created_by;
			$ownered_by = $baseform->owned_by;
			$proxy_company_id = "";
			$status = 'submited';
			switch ($model->bill_type) 
			{
				case 'CGFK': //采购付款
					$description = "采购付款";
					$big_type = "purchase";
					break;
				case 'XSSK': //销售收款
					$description = "销售收款";
					$big_type = "sales";
					break;
				case 'XSTH': //销售退货付款
					$description = "销售退货付款";
					$big_type = 'sales';
					break;
				case 'CGTH': //采购退货收款
					$description = "采购退货收款";
					$big_type = "purchase";
					break;
				case 'XSZR': //销售折让
					$description = "折让付款";
					$big_type = "sales";
					break;
				case 'GKZR': //高开折让
					$description = "高开折让";
					$big_type = 'sales';
					break;
				case 'GKFK': //高开付款
					$description = "高开付款";
					$big_type = 'sales';
					break;
				case 'DLFK': //代理付款
					$description = "代理付款";
					$proxy_company_id = $model->pledge_company_id; //托盘公司：代理付费公司
					$big_type = "purchase";
					break;
				case 'DLSK': //代理收款
					$description = "代理收款";
					$proxy_company_id = $model->pledge_company_id; //托盘公司：代理收费公司
					$big_type = "sales";
					break;
				case 'TPYF': //托盘预付
					$description = "托盘预付";
					$big_type = "purchase";
					break;
				case 'TPSH': //托盘赎回
					$description = "赎回付款";
					$proxy_company_id = $target_id;
					$big_type = "purchase";
					break;
				case 'YF': //运费
					$description = "运费付款";
					$big_type = 'freight';
					break;
				case 'CKFL': //仓库返利
					$description = "仓库返利";
					$big_type = 'warehouse';
					break;
				case 'GCFL': //钢厂返利
					$description = "钢厂返利";
					$big_type = 'steelmill';
					break;
				case 'CCFY': //仓储费用
					$description = "仓储费用";
					$big_type = 'warehouse';
					break;
				case 'BZJ': //保证金
					$description = "保证金"; 
					$big_type = 'purchase';
					break;
				default: 
					$description = "";
					break;
			}
			//往来
			$turnarray = compact('type', 'turnover_direction', 'big_type', 'title_id', 'target_id', 'proxy_company_id', 'amount', 'price', 'fee', 'is_yidan', 'common_forms_id', 'created_by', 'ownered_by', 'status', 'description');
			$result = Turnover::createBill($turnarray);

			if ($baseform->form_status != 'accounted') continue;
			//入账日志
			$billLog = new FrmBillLog();
			$billLog->form_id = $baseform->id;
			$billLog->form_sn = $baseform->form_sn;
			$billLog->title_id = $model->title_id;
			$billLog->dict_bank_id = $model->dict_bank_info_id;
			$billLog->company_id = $model->company_id;
			$billLog->bank_id = $model->bank_info_id;

			$billLog->account_type = "in"; //入账
			$billLog->bill_type = 1;
			if ($model->pay_type != 'adjust') 
			{
				// //结算账户
				// $bankInfo = BankInfo::model()->findByPK($model->bank_info_id);
				// $bankInfo->money -= $model->fee;
				// $bankInfo->update();
				//公司账户
				$dictBankInfo = DictBankInfo::model()->findByPK($model->dict_bank_info_id);
				$dictBankInfo->money += $model->fee;
				$dictBankInfo->update();
			}
			$billLog->fee = $model->fee;
			$billLog->pay_type = $model->pay_type;
			$billLog->account_by = $model->account_by;
			$billLog->created_at = time();
			$billLog->insert();	

			//修改付款登记往来
			$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $baseform->id));
			$created_by = $baseform->created_by;
			$ownered_by = $baseform->owned_by;
			$account_by = $model->account_by;
			$status = "accounted";
			
			$turnarray = compact('created_by', 'ownered_by', 'account_by', 'status');
			$result = Turnover::updateBill($turnover->id, $turnarray);	
		}
		return $error_array;
	}
}
?>