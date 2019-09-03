<?php
class ScriptController extends AdminBaseController
{
	public function accessRules()
	{
		return array(
				array('allow',
						'actions'=>array('Purchase','CheckPurchase','Confirm','Sales','CheckSales', 'SetXSYF','SetXSZR','SetPSYF',"test"),
						'users'=>array('*'),
				),
				array('allow',
						'users'=>array('@'),
				),
				array('deny',
						'users'=>array('*'),
				),
		);
	}
	
	//大量生成重复采购单
	//500条55s
	public function actionPurchase(){
		//数据
		$post['CommonForms']['form_type']='CGD';
		$post['CommonForms']['form_time']='2016-05-30';
		$post['CommonForms']['owned_by']=1;
		$post['CommonForms']['comment']='自动生成数据';
		$data['common']=(Object)$post['CommonForms'];
		$post['FrmPurchase']['purchase_type'] = "normal";
		$post['FrmPurchase']['supply_id'] = "889";
		$post['FrmPurchase']['title_id'] = "14";
		$post['FrmPurchase']['contact_id'] = "3728";
		$post['FrmPurchase']['team_id'] = "7";
		$post['FrmPurchase']['transfer_number'] = "沪A12345";
		$post['FrmPurchase']['warehouse_id'] = "1";
		$post['FrmPurchase']['invoice_cost'] = "100";
		$post['FrmPurchase']['pledge_company_id'] = "198";
		$post['FrmPurchase']['amount'] = 100;
		$post['FrmPurchase']['weight'] = 200.6;
		$post['FrmPurchase']['price_amount'] = 200600;
		$data['main']=$post['FrmPurchase'];
		$data['main']=(Object)$data['main'];
		$data['detail']=array();
		$temp=array();
		$temp['product_id']=39;
		$temp['texture_id']=65;
		$temp['brand_id']=29;
		$temp['rank_id']=48;
		$temp['length']=9;
		$temp['price']=1000;
		$temp['amount']=100;
		$temp['weight']=200.6;
		array_push($data['detail'], (Object)$temp);
		
		for($i=0;$i<500;$i++){
			$allform=new Purchase(0);
			$result=$allform->createSubmitForm($data);
		}
	}
	
	//审核采购单.并创建入库,增加运费（基础信息表中不能显式查出采购单是否入库，放到审核后处理尽力避免重复问题）
	//平均100条20s
	public function actionCheckPurchase(){
		$baseform = CommonForms::model()->findAll(array("condition"=>"comment='自动生成数据' and form_type='CGD' and form_status='submited'","order"=>"id ASC","limit"=>10));
		if($baseform){
			foreach ($baseform as $each){
				$allform=new Purchase($each->id);
				$allform->approveForm();
				//创建入库数据
				$post['CommonForms']['form_type']='RKD';
				$post['CommonForms']['form_time']='2016-05-31';
				$post['CommonForms']['owned_by']=1;
				$post['CommonForms']['comment']='自动生成入库单';
				$data['common']=(Object)$post['CommonForms'];
				$post['FrmPurchase']['from'] = "purchase";
				$post['FrmPurchase']['input_status'] = "0";
				$post['FrmPurchase']['input_type'] = "purchase";
				$post['FrmPurchase']['purchase_id'] = $each->id;
				$post['FrmPurchase']['input_date'] = "2016-05-31";
				$post['FrmPurchase']['warehouse_id'] = "1";
				$data['main']=$post['FrmPurchase'];
				$data['main']=(Object)$data['main'];
				$data['detail']=array();
				$temp=array();
				$temp['product_id']=39;
				$temp['texture_id']=65;
				$temp['brand_id']=29;
				$temp['rank_id']=48;
				$temp['length']=9;
				$temp['cost_price']=1000;
				$temp['input_amount']=100;
				$temp['input_weight']=200.6;
				$temp['purchase_detail_id']=$each->purchase->purchaseDetailOne->id;
				$temp['card_id']="ZDCJ".$each->id;
				array_push($data['detail'], (Object)$temp);
				$form=new Input($id,"purchase");
				$form->createForm($data);
				$form->submitForm();
				$form->approveForm();
				//增加运费
				$post1['CommonForms']['form_type']='FYDJ';
				$post1['CommonForms']['form_time']='2016-05-31';
				$post1['CommonForms']['owned_by']=1;
				$post1['CommonForms']['comment']='自动生成采购运费';
				$data1['common']=(Object)$post1['CommonForms'];
				$post1['FrmPurchase']['title_id'] = 14;
				$post1['FrmPurchase']['weight'] = 200.6;
				$post1['FrmPurchase']['price'] = 0.49;
				$post1['FrmPurchase']['frm_common_id'] = $each->id;
				$post1['FrmPurchase']['travel'] = "沪A12345";
				$post1['FrmPurchase']['company_id'] = "889";
				$post1['FrmPurchase']['amount'] = 100;
				$post1['FrmPurchase']['bill_type'] = "purchase";
				$post1['FrmPurchase']['discount'] = "0.00";
				$post1['FrmPurchase']['is_selected'] = "0";
				$data1['main']=$post1['FrmPurchase'];
				$data1['main']=(Object)$data1['main'];
				
				$form1 = new BillRecordClass(0);
				$result = $form1->createForm($data1);
				$form1->submitForm();
				$form2 = new BillRecordClass($result);
				$form2->approveForm();
			}
		}
	}
	
	//采购单审单
	//平均200条10s
	public function actionConfirm(){
		//$baseform = CommonForms::model()->findAll(array("condition"=>"comment='自动生成数据' and form_type='CGD' and form_status='approve'","order"=>"id ASC","limit"=>200));
		$baseform = new CommonForms();
		$criteria=New CDbCriteria();
		$criteria->with = array("purchase");
		$criteria->addCondition("t.comment='自动生成数据' and t.form_type='CGD' and t.form_status='approve'");
		$criteria->addCondition("purchase.weight_confirm_status<>1");
		$criteria->order = "t.id ASC";
		$criteria->limit = 200;
		$baseform = $baseform->model()->findAll($criteria);
		if($baseform){
			foreach ($baseform as $each){
				$post['CommonForms']['owned_by']=1;
				$post['CommonForms']['comment']='自动生成数据';
				$data['common']=(Object)$post['CommonForms'];
				$post['FrmPurchase']['contact_id'] = "3728";
				$post['FrmPurchase']['team_id'] = "7";
				$post['FrmPurchase']['date_reach'] = "2016-05-31";
				$post['FrmPurchase']['transfer_number'] = "沪A12345";
				$post['FrmPurchase']['invoice_cost'] = "100";
				$post['FrmPurchase']['shipment'] = "0.49";
				$post['FrmPurchase']['unit_price'] = "";
				$post['FrmPurchase']['fee'] = "";
				$post['FrmPurchase']['advance'] = "";
				$post['FrmPurchase']['confirm_amount'] = 100;
				$post['FrmPurchase']['confirm_weight'] = 200.6;
				$post['FrmPurchase']['confirm_cost'] = 200600;
				
				$data['main']=$post['FrmPurchase'];
				$data['main']=(Object)$data['main'];
				$data['detail']=array();
				$temp=array();
				$temp['id']=$each->purchase->purchaseDetailOne->id;
				$temp['fix_price']=1000;
				$temp['fix_amount']=100;
				$temp['fix_weight']=200.6;
				array_push($data['detail'], (Object)$temp);
				$allform=new Purchase($each->id);
				$allform->confirmFormInfo($data);
			}
		}
	}
	
	//生成销售单
	public function actionSales(){
// 		$storage = new MergeStorage();
// 		$criteria=New CDbCriteria();
// 		$criteria->addCondition("product_id=39");
// 		$criteria->addCondition("brand_id=29");
// 		$criteria->addCondition("texture_id=65");
// 		$criteria->addCondition("rank_id=48");
// 		$criteria->addCondition("length=9");
// 		$criteria->addCondition("is_transit=0");
// 		$criteria->addCondition("is_deleted=0");
// 		$criteria->addCondition("title_id=14");
// 		$criteria->addCondition("warehouse_id=1");
// 		$storage = $storage->model()->find($criteria);
// 		//数据
// 		$post['CommonForms']['form_type']='XSD';
// 		$post['CommonForms']['form_time']='2015-05-31';
// 		$post['CommonForms']['owned_by']=1;
// 		$post['CommonForms']['comment']='自动生成销售单';
// 		$data['common']=(Object)$post['CommonForms'];
// 		$post['FrmPurchase']['sales_type'] = "normal";
// 		$post['FrmPurchase']['customer_id'] = "80";
// 		$post['FrmPurchase']['title_id'] = "14";
// 		$post['FrmPurchase']['company_contact_id'] = "2";
// 		$post['FrmPurchase']['team_id'] = "7";
// 		$post['FrmPurchase']['travel'] = "";
// 		$post['FrmPurchase']['warehouse_id'] = "1";
// 		$post['FrmPurchase']['has_bonus_price'] = 0;
// 		$post['FrmPurchase']['date_extract'] = "";
// 		$post['FrmPurchase']['comment'] = "自动生成销售单";
// 		$data['main']=$post['FrmPurchase'];
// 		$data['main']=(Object)$data['main'];
// 		$data['detail']=array();
// 		$temp=array();
// 		$temp['product_id']=39;
// 		$temp['texture_id']=65;
// 		$temp['brand_id']=29;
// 		$temp['rank_id']=48;
// 		$temp['length']=9;
// 		$temp['price']=2000;
// 		$temp['amount']=10;
// 		$temp['weight']=20.06;
// 		$temp['bonus_price']=0;
// 		$temp['card_id']=$storage->id;
// 		$temp['total_amount']=40120.00;
// 		$temp['gk_id']="";
// 		array_push($data['detail'], (Object)$temp);
		while(true){
			$commonForm = new CommonForms();
			$commonForm->form_type = "XSD";
			$commonForm->created_by = 1;
			$commonForm->created_at = time();
			$commonForm->form_time = "2015-01-01";
			$commonForm->form_status = 'approve';
			$commonForm->owned_by = 1;
			$commonForm->comment = "data";
			if($commonForm->insert()){
				$mainInfo=new FrmSales();
				$mainInfo->sales_type="normal";
				$mainInfo->title_id=14;
				$mainInfo->customer_id=80;
				$mainInfo->team_id=7;
				$mainInfo->is_yidan=0;
				$mainInfo->has_bonus_price=0;
				$mainInfo->company_contact_id=2;
				$mainInfo->warehouse_id=1;
				$mainInfo->confirm_status=1;
				$mainInfo->amount=10;
				$mainInfo->weight=20.6;
				$mainInfo->comment="DATA";
				if($mainInfo->insert()){
					$id = $mainInfo->id;
					$id = $id%10000 == 0 ? 1 : $id%10000;
					$sn =  "XSD".date("ymd").str_pad($id,4,"0",STR_PAD_LEFT);
					$commonForm->form_sn = $sn;
					$commonForm->form_id = $mainInfo->id;
					$commonForm->update();
					$salesDetail=new SalesDetail();
					$salesDetail->price = 2000;
					$salesDetail->fix_price = 2000;
					$salesDetail->bonus_price = 0;
					$salesDetail->frm_sales_id = $mainInfo->id;
					$salesDetail->amount = 10;
					$salesDetail->pre_amount = 10;
					$salesDetail->weight = 20.6;
					$salesDetail->pre_weight = 20.6;
					$salesDetail->product_id = 39;
					$salesDetail->brand_id = 29;
					$salesDetail->texture_id = 65;
					$salesDetail->rank_id = 48;
					$salesDetail->length = 9;
					$salesDetail->card_id = 0;
					$salesDetail->fee = 40120.00;
					$salesDetail->insert();
				}
			}
		}
	}
	
	//审核销售单，并进行后续操作
	public function actionCheckSales(){
		$baseform = CommonForms::model()->findAll(array("condition"=>"comment='自动生成销售单' and form_type='XSD' and form_status='submited'","order"=>"id ASC","limit"=>200));
		if($baseform){
			foreach ($baseform as $each){
				$form=new Sales($each->id);
				$form->approveForm();
				//创建出库单
				$post['CommonForms']['form_type']='CKD';
				$post['CommonForms']['form_time']='2015-05-31';
				$post['CommonForms']['owned_by']=1;
				$post['CommonForms']['comment']='自动创建出库单';
				$data['common']=(Object)$post['CommonForms'];
				$post['FrmPurchase']['from'] = "purchase";
				$post['FrmPurchase']['frm_sales_id'] = $each->sales->id;
				$post['FrmPurchase']['amount'] = 10;
				$post['FrmPurchase']['weight'] = 20.06;
				$post['FrmPurchase']['is_return'] = 0;
				$data['main']=$post['FrmPurchase'];
				$data['main']=(Object)$data['main'];
				$data['detail']=array();
				$temp=array();
				$storage = Storage::model()->find("card_no like '%ZDCJ%' and left_amount >=10");
				$temp['storage_id']=$storage->id;
				$temp['product_id']=39;
				$temp['texture_id']=65;
				$temp['brand_id']=29;
				$temp['rank_id']=48;
				$temp['length']=9;
				$temp['sales_detail_id']=0;
				$temp['amount']=10;
				$temp['weight']=20.06;
				array_push($data['detail'], (Object)$temp);
				$allform=new Output($id);
				$result = $allform->createSubmitOutForm($data);
				//增加运费
				$post1['CommonForms']['form_type']='FYDJ';
				$post1['CommonForms']['form_time']='2015-05-31';
				$post1['CommonForms']['owned_by']=1;
				$post1['CommonForms']['comment']='自动生成销售运费';
				$data1['common']=(Object)$post1['CommonForms'];
				$post1['FrmPurchase']['title_id'] = 14;
				$post1['FrmPurchase']['weight'] = 20.06;
				$post1['FrmPurchase']['price'] = 4.98;
				$post1['FrmPurchase']['frm_common_id'] = $each->id;
				$post1['FrmPurchase']['travel'] = "";
				$post1['FrmPurchase']['company_id'] = "53";
				$post1['FrmPurchase']['amount'] = 100;
				$post1['FrmPurchase']['bill_type'] = "sales";
				$post1['FrmPurchase']['discount'] = "0.00";
				$post1['FrmPurchase']['is_selected'] = "0";
				$data1['main']=$post1['FrmPurchase'];
				$data1['main']=(Object)$data1['main'];
				$form1 = new BillRecordClass(0);
				$result = $form1->createForm($data1);
				$form1->submitForm();
				$form2 = new BillRecordClass($result);
				$form2->approveForm();
				//销售折让
				$post2['CommonForms']['form_type']='XSZR';
				$post2['CommonForms']['form_time']='2015-05-31';
				$post2['CommonForms']['owned_by']=1;
				$post2['CommonForms']['created_by']=1;
				$post2['CommonForms']['comment']='自动生成销售折让';
				$data2['common']=(Object)$post2['CommonForms'];
				$post2['FrmPurchase']['title_id'] = 14;
				$post2['FrmPurchase']['company_id'] = 80;
				$post2['FrmPurchase']['type'] = "sale";
				$post2['FrmPurchase']['amount'] = 10;
				$post2['FrmPurchase']['comment'] = "自动生成销售折让";
				$post2['FrmPurchase']['start_at'] = "";
				$post2['FrmPurchase']['end_at'] = "";
				$temp1 = array();
				$temp1['sales_id'] = $each->id;
				$post2['FrmPurchase']['relation'][0] =  (Object)$temp1;
				$data2['main']=$post2['FrmPurchase'];
				$data2['main']=(Object)$data2['main'];
				$rebate = new Rebate($id,"XSZR");
				$result2 = $rebate->createForm($data2);
				$rebate->submitForm();
				$rebate = new Rebate($result2,"XSZR");
				$rebate->approveForm();
			}
		}
	}
	
	//增加已存在的销售单运费
	//3025条数据大概执行7分16秒（436s）,平均一条0.144s
	public function actionSetXSYF(){
		$baseform = new CommonForms();
		$criteria=New CDbCriteria();
		$criteria->with = array("sales");
		$criteria->addCondition("t.form_type='XSD'");
		$criteria->addCondition("t.form_status='approve'");
		$criteria->addCondition("t.owned_by<>16");
		$criteria->addCondition("t.comment is null");
		$baseform = $baseform->model()->findAll($criteria);
		
		if($baseform){
			foreach($baseform as $each){
				$sales = $each->sales;
				//增加运费
				$post1['CommonForms']['form_type']='FYDJ';
				$post1['CommonForms']['form_time']='2016-05-31';
				$post1['CommonForms']['owned_by']=1;
				$post1['CommonForms']['comment']='自动生成销售运费';
				$data1['common']=(Object)$post1['CommonForms'];
				$post1['FrmPurchase']['title_id'] = $sales->title_id;
				$post1['FrmPurchase']['weight'] = $sales->weight;
				$post1['FrmPurchase']['price'] = 100/$sales->weight;
				$post1['FrmPurchase']['frm_common_id'] = $each->id;
				$post1['FrmPurchase']['travel'] = "";
				$post1['FrmPurchase']['company_id'] = $sales->customer_id;
				$post1['FrmPurchase']['amount'] = 100;
				$post1['FrmPurchase']['bill_type'] = "sales";
				$post1['FrmPurchase']['discount'] = "0.00";
				$post1['FrmPurchase']['is_selected'] = "0";
				$data1['main']=$post1['FrmPurchase'];
				$data1['main']=(Object)$data1['main'];
				$form1 = new BillRecordClass(0);
				$result = $form1->createForm($data1);
				$form1->submitForm();
				$form2 = new BillRecordClass($result);
				$form2->approveForm();
			}
		}
	}
	
	//增加已存在的销售折让
	//3506条数据大概执行9分30秒（570s）,平均一条0.1626s
	public function actionSetXSZR(){
		$baseform = new CommonForms();
		$criteria=New CDbCriteria();
		$criteria->with = array("sales");
		$criteria->addCondition("t.form_type='XSD'");
		$criteria->addCondition("t.form_status='approve'");
		$criteria->addCondition("t.comment is null");
		$baseform = $baseform->model()->findAll($criteria);
		$form_sn = array("XD1605185238","XD1605104383","XD1605134832");
		if($baseform){
			foreach($baseform as $each){
				if(in_array($each->form_sn,$form_sn)){
					continue;
				}
				$sales = $each->sales;
				$post2['CommonForms']['form_type']='XSZR';
				$post2['CommonForms']['form_time']='2016-05-31';
				$post2['CommonForms']['owned_by']=1;
				$post2['CommonForms']['created_by']=1;
				$post2['CommonForms']['comment']='自动生成销售折让';
				$data2['common']=(Object)$post2['CommonForms'];
				$post2['FrmPurchase']['title_id'] = $sales->title_id;
				$post2['FrmPurchase']['company_id'] =$sales->customer_id;
				$post2['FrmPurchase']['type'] = "sale";
				$post2['FrmPurchase']['amount'] = 10;
				$post2['FrmPurchase']['comment'] = "自动生成销售折让";
				$post2['FrmPurchase']['start_at'] = "";
				$post2['FrmPurchase']['end_at'] = "";
				$temp1 = array();
				$temp1['sales_id'] = $each->id;
				$post2['FrmPurchase']['relation'][0] =  (Object)$temp1;
				$data2['main']=$post2['FrmPurchase'];
				$data2['main']=(Object)$data2['main'];
				$rebate = new Rebate($id,"XSZR");
				$result2 = $rebate->createForm($data2);
				$rebate->submitForm();
				$rebate = new Rebate($result2,"XSZR");
				$rebate->approveForm();
			}
		}
	}
	
	//增加已存在的采购单单运费
	//3471条数据大概执行8分20秒（500s）,平均一条0.144s
	public function actionSetPSYF(){
		$baseform = new CommonForms();
		$criteria=New CDbCriteria();
		$criteria->with = array("purchase");
		$criteria->addCondition("t.form_type='CGD'");
		$criteria->addCondition("t.form_status='approve'");
		$criteria->addCondition("t.comment is null or t.comment<>'自动生成数据'");
		$baseform = $baseform->model()->findAll($criteria);
		$form_sn = array("CD1604182156","CD1604152143","CD1604282231","CD1604292242","CD1605042271","CD1605092299","CD1605132342","CD1605162358");
		if($baseform){
			foreach($baseform as $each){
				if(in_array($each->form_sn,$form_sn)){
					continue;
				}
				$purchase = $each->purchase;
				$post1['CommonForms']['form_type']='FYDJ';
				$post1['CommonForms']['form_time']='2016-05-31';
				$post1['CommonForms']['owned_by']=1;
				$post1['CommonForms']['comment']='自动生成采购运费';
				$data1['common']=(Object)$post1['CommonForms'];
				$post1['FrmPurchase']['title_id'] = $purchase->title_id;
				$post1['FrmPurchase']['weight'] = $purchase->weight;
				$post1['FrmPurchase']['price'] = 100/$purchase->weight;
				$post1['FrmPurchase']['frm_common_id'] = $each->id;
				$post1['FrmPurchase']['travel'] = "沪A12345";
				$post1['FrmPurchase']['company_id'] = $purchase->supply_id;
				$post1['FrmPurchase']['amount'] = 100;
				$post1['FrmPurchase']['bill_type'] = "purchase";
				$post1['FrmPurchase']['discount'] = "0.00";
				$post1['FrmPurchase']['is_selected'] = "0";
				$data1['main']=$post1['FrmPurchase'];
				$data1['main']=(Object)$data1['main'];
				
				$form1 = new BillRecordClass(0);
				$result = $form1->createForm($data1);
				$form1->submitForm();
				$form2 = new BillRecordClass($result);
				$form2->approveForm();
			}
		}
	}
	
	//导入采购指导价
	public function actionExportPurPrice(){
		Yii::$enableIncludePath = false;
		$this->pageTitle = "导入采购指导价";
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
			$num = 0;
			$data = array();
			for($i=2;$i<=$highestRow;$i++){
				$product_name = $sheet->getCell("A".$i)->getValue();
				$texture_name = $sheet->getCell("B".$i)->getValue();
				$rank_name = $sheet->getCell("C".$i)->getValue();
				$brand_name = $sheet->getCell("D".$i)->getValue();
				$price_list = $sheet->getCell("E".$i)->getValue();
				$temp = array();
				$temp[0] = DictGoodsProperty::getStdByName($product_name);
				$temp[1] = DictGoodsProperty::getStdByName($texture_name);
				$temp[2] = substr($rank_name,2);
				$temp[3] = DictGoodsProperty::getStdByName($brand_name);
				$temp[4] = explode(",",$price_list);
				array_push($data,$temp);
			}
			for($j=1;$j<=31;$j++){
				foreach ($data as $k=>$v){
					$purprice = new PurchasePrice();
					$purprice->product_std = $v[0];
					$purprice->texture_std = $v[1];
					$purprice->rank_range = $v[2];
					$purprice->brand_std = $v[3];
					$purprice->price = 1000;
					if($j<10){
						$date = "2016-05-0".$j;
					}else{
						$date = "2016-05-".$j;
					}
					$a = date("w",strtotime($date));
					if($a =="0" || $a=="6"){
						continue;
					}
					$purprice->price_date = $date;
					if($purprice->insert()){
						foreach($v[4] as $key=>$val){
							$purrel = new PurpriceRankRel();
							$rank = DictGoodsProperty::model()->find("name='Φ{$val}'");
							$purrel->rank_std = $rank->std;
							$purrel->rank_id = $rank->id;
							$purrel->price_id = $purprice->id;
							$purrel->insert();
						}
					}
				}
			}
		}
		$this->render("importtitle",array(
				'str'=>$str,
		));
	}
		
	public function actionTest(){
		$baseform = CommonForms::model()->findAll(array("condition"=>"comment='自动生成销售单' and form_type='XSD' and form_status='submited'","order"=>"id ASC","limit"=>100));
		if($baseform){
			foreach ($baseform as $each){
				
				$form=new Sales($each->id);
				$form->approveForm();
				//创建出库单
				$post['CommonForms']['form_type']='CKD';
				$post['CommonForms']['form_time']='2016-05-31';
				$post['CommonForms']['owned_by']=1;
				$post['CommonForms']['comment']='自动创建出库单';
				$data['common']=(Object)$post['CommonForms'];
				$post['FrmPurchase']['from'] = "purchase";
				$post['FrmPurchase']['frm_sales_id'] = $each->sales->id;
				$post['FrmPurchase']['amount'] = 10;
				$post['FrmPurchase']['weight'] = 20.06;
				$post['FrmPurchase']['is_return'] = 0;
				$data['main']=$post['FrmPurchase'];
				$data['main']=(Object)$data['main'];
				$data['detail']=array();
				$temp=array();
				$storage = Storage::model()->find("card_no like '%ZDCJ%' and left_amount >=10");
				$temp['storage_id']=$storage->id;
				$temp['product_id']=39;
				$temp['texture_id']=65;
				$temp['brand_id']=29;
				$temp['rank_id']=48;
				$temp['length']=9;
				$temp['sales_detail_id']=0;
				$temp['amount']=10;
				$temp['weight']=20.06;
				array_push($data['detail'], (Object)$temp);
				$allform=new Output($id);
				$result = $allform->createSubmitOutForm($data);
				var_dump($result);
				//增加运费
				$post1['CommonForms']['form_type']='FYDJ';
				$post1['CommonForms']['form_time']='2016-05-31';
				$post1['CommonForms']['owned_by']=1;
				$post1['CommonForms']['comment']='自动生成销售运费';
				$data1['common']=(Object)$post1['CommonForms'];
				$post1['FrmPurchase']['title_id'] = 14;
				$post1['FrmPurchase']['weight'] = 20.06;
				$post1['FrmPurchase']['price'] = 4.98;
				$post1['FrmPurchase']['frm_common_id'] = $each->id;
				$post1['FrmPurchase']['travel'] = "";
				$post1['FrmPurchase']['company_id'] = "53";
				$post1['FrmPurchase']['amount'] = 100;
				$post1['FrmPurchase']['bill_type'] = "sales";
				$post1['FrmPurchase']['discount'] = "0.00";
				$post1['FrmPurchase']['is_selected'] = "0";
				$data1['main']=$post1['FrmPurchase'];
				$data1['main']=(Object)$data1['main'];
				$form1 = new BillRecordClass(0);
				$result = $form1->createForm($data1);
				$form1->submitForm();
				$form2 = new BillRecordClass($result);
				$form2->approveForm();
				//销售折让
				$post2['CommonForms']['form_type']='XSZR';
				$post2['CommonForms']['form_time']='2016-05-31';
				$post2['CommonForms']['owned_by']=1;
				$post2['CommonForms']['created_by']=1;
				$post2['CommonForms']['comment']='自动生成销售折让';
				$data2['common']=(Object)$post2['CommonForms'];
				$post2['FrmPurchase']['title_id'] = 14;
				$post2['FrmPurchase']['company_id'] = 80;
				$post2['FrmPurchase']['type'] = "sale";
				$post2['FrmPurchase']['amount'] = 10;
				$post2['FrmPurchase']['comment'] = "自动生成销售折让";
				$post2['FrmPurchase']['start_at'] = "";
				$post2['FrmPurchase']['end_at'] = "";
				$temp1 = array();
				$temp1['sales_id'] = $each->id;
				$post2['FrmPurchase']['relation'][0] =  (Object)$temp1;
				$data2['main']=$post2['FrmPurchase'];
				$data2['main']=(Object)$data2['main'];
				$rebate = new Rebate($id,"XSZR");
				$result2 = $rebate->createForm($data2);
				$rebate->submitForm();
				$rebate = new Rebate($result2,"XSZR");
				$rebate->approveForm();
			}
		}
	}
	
	//更新高开客户为销售单客户
	public function actionSetGk(){
		//更新高开信息
		$model = HighOpen::model()->findAll("client_id=0 or client_id is null");
		if($model){
			foreach ($model as $li){
				$li->client_id = $li->sales->client_id;
				$li->update();
			}
		}
	}
}