<?php
class CommonFormsController extends AdminBaseController
{
	
	/*
	 * 在更新表单时，
	 * 判定最后更新时间是否ok
	 */
	public function actionLastUpdate($id)
	{
		$time=$_REQUEST['time'];
		$result=CommonForms::CompareUpdateTime($id, $time);
		echo $result;		
	}
	
	/*
	 * 在关联表单时，
	 * 判定状态是否为已审核
	 */
	public function actionChecked($id)
	{
		$type=$_REQUEST['type'];
		$result=CommonForms::isAvailable($id,$type);
		echo $result;
	}
	
	
	/*
	 * 向接口中心推送
	 * 定时执行脚本
	 */
	public function actionPush()
	{		
		PushList::timingSctipt();
		@session_destroy();
	}
	
	/* ****************************Excel导入*******************************************	 */
	
	public function actionLoad() {
		
		if(isset($_POST['submit'])) {
			$file = CUploadedFile::getInstanceByName('file');//获取上传的文件实例
			if($file) {
				$excelFile = $file->getTempName();
				$phpexcel = new PHPExcel;
				$phpexcel = PHPExcel_IOFactory::load($excelFile)->getSheet(0);
				$total_line = $phpexcel->getHighestRow();
				$total_column = $phpexcel->getHighestColumn();
				$total_column=PHPExcel_Cell::columnIndexFromString($total_column);
				 $d=array();
				for ($row = 2; $row <=$total_line; $row++) {
					$data = array();
					for ($column = 0; $column <= $total_column; $column++) {
						$column_name=PHPExcel_Cell::stringFromColumnIndex($column); 
						$data[] = trim($phpexcel->getCell($column_name.$row) -> getValue());						
					}
					array_push($d, $data);
				}
// 				var_dump($d);
				$d=array_reverse($d);
				$this->backPurchaseData($d);
				die;
			}
		}
		$this->renderPartial('excel');
	}
	
	
	public function actionBackStorage()
	{
		if(isset($_POST['submit'])) {
			$file = CUploadedFile::getInstanceByName('file');//获取上传的文件实例
			if($file) {
				$excelFile = $file->getTempName();
				$phpexcel = new PHPExcel;
				$phpexcel = PHPExcel_IOFactory::load($excelFile)->getSheet(0);
				$total_line = $phpexcel->getHighestRow();
				$total_column = $phpexcel->getHighestColumn();
				$total_column=PHPExcel_Cell::columnIndexFromString($total_column);
				$d=array();
				for ($row = 2; $row <=$total_line; $row++) {
					$data = array();
					for ($column = 0; $column <= $total_column; $column++) {
						$column_name=PHPExcel_Cell::stringFromColumnIndex($column);
						$data[] = trim($phpexcel->getCell($column_name.$row) -> getValue());
					}
					array_push($d, $data);
				}
// 				var_dump($d);
				
				$d=array_reverse($d);
				$this->backStorageData($d);
				die;
			}
		}
		$this->renderPartial('excel');
	}
	
	public function  backPurchaseData($data)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
		foreach ($data as $each)
		{
			$model=new PurchaseData();
			$model->form_sn=$each[0];
			$model->created_date=$each[1];
			$model->supply=$each[4];//$each[3];//
			$model->product=$each[5];//$each[4];//
			$model->rank=$each[6];//$each[5];//
			$model->length=$each[7];//$each[6];//
			$model->texture=$each[8];//$each[7];//
			$model->brand=$each[9];//$each[8];//
			$model->amount=$each[10];//$each[9];//
			$model->weight=$each[12];//$each[10];//
			$model->price=$each[13];//$each[11];//
			$model->money=$each[14];//$each[12];//
			$model->fix_weight=$each[15];//$each[13];//
			$model->fix_price=$each[16];//$each[14];//
			$model->fix_money=$each[15];//$each[17]
			$model->input_weight=$each[23];//$each[16];//
			$model->yidan=$each[24]=='√'?1:0;;//$each[17];//
			$model->status=$each[25];//$each[18];//
			$model->confirm=$each[26];//$each[19];//
			$model->title=$each[27];//$each[20];//
			$model->flag=1;
			$model->insert();
			
			//基础数据判断
			//供应商
			$supply=DictCompany::model()->findByAttributes(array('short_name'=>$each[4]));
			if(!$supply)
			{
				$supply=new DictCompanyNone();
				$supply->name=$each[4];
				$supply->insert();
			}
			//品名
			$pro_std='';
			$product=DictGoodsProperty::model()->findByAttributes(array('short_name'=>$each[5],'property_type'=>'product'));
			if(!$product)
			{
// 				$pro_link=array('')
				$product=new DictGoodsPropertyNew();
				$product->property_type='product';
				$product->short_name=$each[5];
				$product->insert();
			}else{
				$pro_std=$product->std;
			}
			//产地
			$bra_std='';
			$brand=DictGoodsProperty::model()->findByAttributes(array('short_name'=>$each[9],'property_type'=>'brand'));
			if(!$brand)
			{
				$bra_link=array(''=>'');
				$brand=DictGoodsProperty::model()->findByAttributes(array('short_name'=>$bra_link[$each[9]],'property_type'=>'brand'));
				if(!$brand)
				{
					$brand=new DictGoodsPropertyNew();
					$brand->property_type='brand';
					$brand->short_name=$each[9];
					$brand->insert();
				}else{
					$bra_std=$brand->std;
				}			
			}else{
				$bra_std=$brand->std;
			}
			//材质
			$tex_std='';
			$texture=DictGoodsProperty::model()->findByAttributes(array('short_name'=>$each[8],'property_type'=>'texture'));
			if(!$texture)
			{
				$texture=new DictGoodsPropertyNew();
				$texture->property_type='texture';
				$texture->short_name=$each[8];
				$texture->insert();
			}else{
				$tex_std=$texture->std;
			}
			//规格
			$rank_std='';
			$rank=DictGoodsProperty::model()->findByAttributes(array('short_name'=>$each[6],'property_type'=>'rank'));
			if(!$rank)
			{
				$rank=new DictGoodsPropertyNew();
				$rank->property_type='rank';
				$rank->short_name=$each[6];
				$rank->insert();
			}else{
				$rank_std=$rank->std;
			}
			//goods
			if($bra_std&&$rank_std&&$pro_std&&$tex_std)
			{
				if(!$each[7]){$each[7]=0;}
				$goods=DictGoods::model()->findByAttributes(array('product_std'=>$pro_std,'brand_std'=>$bra_std,'texture_std'=>$tex_std,'rank_std'=>$rank_std,'length'=>$each[7]));
				if(!$goods)
				{
					$goods=new DictGoodsNew();
					$goods->name=$rank_std.'*'.$each[7].'*'.$tex_std;
					$goods->short_name=$rank_std.'*'.$each[7].'*'.$tex_std;
					$goods->product_std=$pro_std;
					$goods->brand_std=$bra_std;
					$goods->rank_std=$rank_std;
					$goods->texture_std=$tex_std;
					$goods->length=$each[7];
					if($each[10]){
						$goods->unit_weight=$each[12]/$each[10];
					}else{
						$goods->unit_weight=2;
					}
					$goods->insert();
				}
			}else{
					$goods=new DictGoodsNew();
					$goods->name=$rank_std.'*'.$each[7].'*'.$tex_std;
					$goods->short_name=$rank_std.'*'.$each[7].'*'.$tex_std;
					$goods->product_std=($pro_std?$pro_std:$each[5]);
					$goods->brand_std=($bra_std?$bra_std:$each[9]);
					$goods->rank_std=($rank_std?$rank_std:$each[6]);
					$goods->texture_std=($tex_std?$tex_std:$each[8]);
					$goods->length=$each[7];
					if($each[10]){
						$goods->unit_weight=$each[12]/$each[10];
					}else{
						$goods->unit_weight=2;
					}
					$goods->insert();
			}				
		}

		$transaction->commit();
		}catch (Exception $e)
		{
			echo "操作失败";
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
	}
	
	public function backStorageData($data)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
// 		$purchase=array();
		foreach ($data as $each)
		{
			$model=new BackstorageData();
			$model->warehouse=$each[0];
			$model->input_date=$each[1];
			$model->comment=$each[2];
			$model->card_no=$each[3];
			$model->rank=substr($each[4], strpos($each[4], "Ф"));//$each[4];
			$model->product=substr($each[4], 0,strpos($each[4], "Ф"));//$each[5];
			$model->texture=$each[5];//$each[6];
			$model->brand=$each[6];//$each[7];
			$model->length=$each[7];//$each[8];
			$model->unit_weight=$each[8];//$each[9];
			$model->amount=$each[9];//$each[10];
			$model->weight=$each[11];
			$model->cost_price=$each[16];
// 			$model->cost_money=$each[15];
			$model->supply=$each[21];//$each[16];
			$model->cgd_sn=$each[22];//$each[17];
			$model->dx=$each[24];//$each[19];
			$model->title=$each[25];//$each[20];
			$model->flag=1;
			
			$model->insert();
			
// 			if(!in_array($each[17], $purchase))
// 			{
// 				array_push($purchase, $each[17]);
// 			}
			
		}
// 		$purData=PurchaseData::model()->findAll();
// 		foreach ($purData as $e)
// 		{
// 			if(!in_array($e->form_sn, $purchase))
// 			{
// 				$e->delete();//没有库存记录
// 			}			
// 		}
		
		$transaction->commit();
		}catch (Exception $e)
		{
			echo "操作失败";
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
	}
	
	
	public function company($data)
	{
		foreach ($data as $each)
		{
			$model=new DictCompany();
			$model->unsetAttributes();
			$model->name=$each[0];
			$model->short_name=$each[0];
			$model->is_supply=1;
			$model->created_at=time();
			$model->code=pinyin1($each[0]);
			$model->insert();
			$contact=new CompanyContact();
			$contact->created_at=time();
			$contact->name=$each[1]?$each[1]:'anonymous'.$model->id;
			$contact->mobile=$each[2]?$each[2]:'00000000000';
			$contact->is_default=1;
			$contact->dict_company_id=$model->id;
			$contact->insert();
			if($each[3])
			{
				$contact1=new CompanyContact();
				$contact1->created_at=time();
				$contact1->name=$each[3];
				$contact1->mobile=$each[4];
				$contact1->is_default=0;
				$contact1->dict_company_id=$model->id;
				$contact1->insert();
			}
		}
	}
	
	public function actionPurchase()
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
		$data=PurchaseData::model()->findAll();
		foreach ($data as $each)
		{
			$sn=$each->form_sn;
			$common=CommonForms::model()->with('purchase')->findByAttributes(array('form_sn'=>$sn));
			$confirmed=0;
			if($common)
			{
				$purchase=$common->purchase;
				$purchase->amount+=$each->amount;
				$purchase->weight+=$each->weight;
				$purchase->input_weight+=$each->input_weight;
				if($each->confirm=='已审单')
				{
					$confirmed=1;
					$purchase->confirm_weight+=$each->fix_weight;
					$purchase->confirm_cost+=$each->fix_money;
					$purchase->price_amount+=$each->fix_money;
				}else{
					$purchase->price_amount+=$each->weight*$each->price;
				}
				$purchase->update();				
				$detail=new PurchaseDetail();
				$detail->unsetAttributes();
				$detail->price=$each->price;
				$detail->amount=$each->amount;
				$detail->weight=$each->weight;
				$detail->input_weight=$each->input_weight;
				$detail->purchase_id=$purchase->id;
				$amount=$each->weight;
				$price=$each->price;
				if($each->confirm=='已审单')
				{
					$detail->fix_weight=$each->fix_weight;
					$detail->fix_price=$each->fix_price;
					$amount=$each->fix_weight;
					$price=$each->fix_price;
				}
				$detail->product_id=DictGoodsProperty::getIdByName($each->product);
				$brand_id=DictGoodsProperty::getIdByName($each->brand);
				if(!$brand_id)
				{
					$bra_link=array(''=>'');
					$brand_id=DictGoodsProperty::getIdByName($bra_link[$each->brand]);
				}
				$detail->brand_id=$brand_id;
				$detail->texture_id=DictGoodsProperty::getIdByName($each->texture);
				$detail->rank_id=DictGoodsProperty::getIdByName($each->rank);
				$detail->length=($each->length?$each->length:0);
				$detail->insert();
				//创建往来
				$description='单号：'.$common->form_sn.','.$each->brand.'|'.$each->product.'|'.$each->rank.'*'.$detail->length.'*'.$each->texture;
				$turnarray = array("type"=>'CGMX',"turnover_direction"=>'need_pay',"title_id"=>$purchase->title_id,'big_type'=>'purchase','status'=>'submited','confirmed'=>$confirmed,
						"target_id"=>$purchase->supply_id,"amount"=>$amount,"price"=>$price,"fee"=>$amount*$price,"common_forms_id"=>$common->id,'created_at'=>$common->created_at,
						"form_detail_id"=>$detail->id,"ownered_by"=>$common->owned_by,'created_by'=>$common->created_by,'description'=>$description,'is_yidan'=>$purchase->is_yidan
				);
				Turnover::createBill($turnarray);
				
			}else{
				$common=new CommonForms();
				$common->unsetAttributes();
				$common->form_type='CGD';
				$common->form_sn=$sn;
				$common->created_at=strtotime($each->created_date);
				$common->created_by=1;
				$common->form_time=date('Y-m-d',$common->created_at);
				$common->form_status='approve';
				$common->approved_at=$common->created_at+43200;
				$common->approved_by=1;
				$common->owned_by=1;
				$common->is_deleted=0;
				
				$purchase=new FrmPurchase();
				$purchase->unsetAttributes();
				$purchase->purchase_type='normal';
				
				$supply=DictCompany::model()->findByAttributes(array('short_name'=>$each->supply));
				if($supply)
				{
					$purchase->supply_id=$supply->id;
					$contact=CompanyContact::model()->findByAttributes(array('dict_company_id'=>$supply->id));
				}else{
					$link=array(''=>'',);
					$supply=DictCompany::model()->findByAttributes(array('short_name'=>$link[$each->supply]));
					if($supply)
					{
						$purchase->supply_id=$supply->id;
						$contact=CompanyContact::model()->findByAttributes(array('dict_company_id'=>$supply->id));
					}else{
						$purchase->supply_id=10000;
						$contact=CompanyContact::model()->findByAttributes(array('dict_company_id'=>10000));
					}					
				}								
				$title=DictTitle::model()->findByAttributes(array('short_name'=>$each->title));
				$purchase->title_id=$title->id;				
				$purchase->is_yidan=$each->yidan?1:0;				
				$purchase->contact_id=$contact->id;
// 				$purchase->warehouse_id=;
				$purchase->amount=$each->amount;
				$purchase->weight=$each->weight;
				
				$purchase->team_id=1;
// 				$purchase->input_amount=;
				$purchase->input_weight=$each->input_weight;
// 				$purchase->invoice_cost=0;
				$amount=$each->weight;
				if($each->confirm=='已审单')
				{
					$confirmed=1;
					$purchase->weight_confirm_status=1;
					$purchase->price_confirm_status=1;
// 					$purchase->confirm_amount=;
					$purchase->confirm_weight=$each->fix_weight;
					$purchase->confirm_cost=$each->fix_money;
					$purchase->price_amount=$each->fix_money;
					
				}else{
					$purchase->weight_confirm_status=0;
					$purchase->price_confirm_status=0;
					$purchase->confirm_amount=0;
					$purchase->confirm_weight=0;
					$purchase->confirm_cost=0;
					$purchase->price_amount=$purchase->weight*$each->price;
				}
				$purchase->insert();
				$common->form_id=$purchase->id;
				$common->insert();
				$detail=new PurchaseDetail();
				$detail->unsetAttributes();
				$detail->price=$each->price;
				$detail->amount=$each->amount;
				$detail->weight=$each->weight;
// 				$detail->input_amount=$each[];
				$detail->input_weight=$each->input_weight;
				$detail->purchase_id=$purchase->id;
				$price=$each->price;
				if($each->confirm=='已审单')
				{
// 					$detail->fix_amount=$each;
					$detail->fix_weight=$each->fix_weight;
					$detail->fix_price=$each->fix_price;
					$amount=$each->fix_weight;
					$price=$each->fix_price;
				}				
// 				$detail->cost_price=;
// 				$detail->invoice_price=;
				$detail->product_id=DictGoodsProperty::getIdByName($each->product);
				$brand_id=DictGoodsProperty::getIdByName($each->brand);
				if(!$brand_id)
				{
					$bra_link=array(''=>'');
					$brand_id=DictGoodsProperty::getIdByName($bra_link[$each->brand]);
				}
				$detail->brand_id=$brand_id;
				$detail->texture_id=DictGoodsProperty::getIdByName($each->texture);
// 				var_dump($each[5]);
				$detail->rank_id=DictGoodsProperty::getIdByName($each->rank);
// 				var_dump($detail->rank_id);
// 				die;
				$detail->length=($each->length?$each->length:0);
				$detail->insert();

				//创建往来
				$description='单号：'.$common->form_sn.','.$each->brand.'|'.$each->product.'|'.$each->rank.'*'.$detail->length.'*'.$each->texture;
				$turnarray = array("type"=>'CGMX',"turnover_direction"=>'need_pay',"title_id"=>$purchase->title_id,'big_type'=>'purchase','status'=>'submited','confirmed'=>$confirmed,
						"target_id"=>$purchase->supply_id,"amount"=>$amount,"price"=>$price,"fee"=>$amount*$price,"common_forms_id"=>$common->id,'created_at'=>$common->created_at,
						"form_detail_id"=>$detail->id,"ownered_by"=>$common->owned_by,'created_by'=>$common->created_by,'description'=>$description,'is_yidan'=>$purchase->is_yidan
				);
				Turnover::createBill($turnarray);				
			}
		}
		
		$transaction->commit();
		}catch (Exception $e)
		{
			echo "操作失败";
			echo $e;
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
	}
	
	//导入库存数据
	public function actionStorage()
	{
		$transaction=Yii::app()->db->beginTransaction();
		try {
		
		$data=BackstorageData::model()->findAll();		
		foreach ($data as $each)
		{
			if($each->dx=='代销')
			{
				$mainInfo=new FrmInputDx();
				$supply=DictCompany::model()->findByAttributes(array('short_name'=>$each->supply));
				$mainInfo->supply_id=$supply->id;//
				$title=DictTitle::model()->findByAttributes(array('short_name'=>$each->title));
				$mainInfo->title_id=$title->id;//
				$warehouse=Warehouse::model()->findByAttributes(array('short_name'=>$each->warehouse));
				$mainInfo->warehouse_id=$warehouse->id;//仓库
				$mainInfo->team_id=1;
				$mainInfo->contact_id=1;
				$mainInfo->amount=$each->amount;
				$mainInfo->weight=$each->weight;
				$mainInfo->insert();
				
				$common=new CommonForms();
				$common->unsetAttributes();
				$common->form_type='DXRK';
				$common->created_at=strtotime($each->input_date);
				$common->created_by=1;
				$common->form_time=date('Y-m-d',$common->created_at);
				$common->form_status='approve';
				$common->approved_at=$common->created_at+43200;
				$common->approved_by=1;
				$common->owned_by=1;
				$common->is_deleted=0;
				$common->form_id=$mainInfo->id;
				
				$base=new BaseForm();
				$sn=$base->_generateSN('DXRK',$mainInfo->id);
				$common->form_sn=$sn;
				$common->insert();
				$input=new InputDetailDx();
				$input->input_id=$mainInfo->id;
				$input->input_amount=$each->amount;
				$input->input_weight=$each->weight;
				$input->product_id=DictGoodsProperty::getIdByName($each->product);
				$input->rank_id =DictGoodsProperty::getIdByName($each->rank);
				$input->texture_id=DictGoodsProperty::getIdByName($each->texture);
				$brand_id=DictGoodsProperty::getIdByName($each->brand);
				if(!$brand_id)
				{
					$bra_link=array(''=>'');
					$brand_id=DictGoodsProperty::getIdByName($bra_link[$each->brand]);
				}
				$input->brand_id=$brand_id;
				$input->length=($each->length?$each->length:0);
				$input->card_id=$each->card_no;
				$input->insert();
				
				$db=array();
				$db['card_no']=$each->card_no;//卡号
				$db['input_detail_id']=$input->id;
				$db['card_status']='normal';  //状态
				$db['title_id']=$mainInfo->title_id;
				$db['input_amount']=$each->amount;
				$db['input_weight']=$each->weight;
				$db['left_amount']=$each->amount;
				$db['left_weight']=$each->weight;//剩余重量
				$db['retain_amount']=0;//保留件数
				$db['lock_amount']=0;//锁定件数
				$db['input_date']=strtotime($each->input_date);
				$db['pre_input_date']=$db['input_date'];//到货日期
				$db['frm_input_id']=$mainInfo->id;
				$db['cost_price']=0;
				$db['is_price_confirmed']='';//是否采购单价已确定
				$db['invoice_price']=0;//采购发票成本
				$db['is_yidan']=0;
				$db['is_pledge']=0;
				$db['is_dx']=1;//是否代销
				$db['warehouse_id']=$mainInfo->warehouse_id;
				$storage=Storage::createNew($db);				
			}elseif($each->cgd_sn){
				$common=new CommonForms();
				$common->unsetAttributes();
				$common->form_type='RKD';
				$common->created_at=strtotime($each->input_date);
				$common->created_by=1;
				$common->form_time=date('Y-m-d',$common->created_at);
				$common->form_status='approve';
				$common->approved_at=$common->created_at+43200;
				$common->approved_by=1;
				$common->owned_by=1;
				$common->is_deleted=0;
				
				$basepurchase=CommonForms::model()->with('purchase','purchase.purchaseDetails')->findByAttributes(array('form_sn'=>$each->cgd_sn));
				$purchase=$basepurchase->purchase;
				$pur_details=$purchase->purchaseDetails;
				$input=new FrmInput();
				$input->input_type='purchase';
				$input->purchase_id=$basepurchase->id;
				$input->input_date=$common->created_at;
				$warehouse=Warehouse::model()->findByAttributes(array('short_name'=>$each->warehouse));
				$input->warehouse_id=$warehouse->id;
				$input->from='purchase';
				$input->input_status=1;
				$input->input_at=$common->created_at;
				$input->input_by=1;
				$input->insert();
				
				$base=new BaseForm();
				$sn=$base->_generateSN('RKD',$input->id);
				$common->form_sn=$sn;
				$common->form_id=$input->id;
				$common->insert();
				
				$detail=new InputDetail();
				$detail->input_id=$input->id;
				$detail->input_amount=$each->amount;
				$detail->input_weight=$each->weight;
				$detail->cost_price=$each->cost_price;
				$detail->product_id=DictGoodsProperty::getIdByName($each->product);
				$brand_id=DictGoodsProperty::getIdByName($each->brand);
				if(!$brand_id)
				{
					$bra_link=array(''=>'');
					$brand_id=DictGoodsProperty::getIdByName($bra_link[$each->brand]);
				}
				$detail->brand_id=$brand_id;
				$detail->texture_id=DictGoodsProperty::getIdByName($each->texture);
				$detail->rank_id=DictGoodsProperty::getIdByName($each->rank);
				$detail->length=($each->length?$each->length:0);
				$detail->card_id=$each->card_no;
				$detail->remain_amount=0;
				
				if(is_array($pur_details)&&!empty($pur_details))
				{
					foreach ($pur_details as $ea_pud)
					{
						if($ea_pud->brand_id==$detail->brand_id&&$ea_pud->product_id==$detail->product_id&&$ea_pud->texture_id==$detail->texture_id&&$ea_pud->rank_id==$detail->rank_id&&$ea_pud->length==$detail->length)
						{
							$detail->purchase_detail_id=$ea_pud->id;
							$ea_pud->input_amount+=$detail->input_amount;
							$ea_pud->update();
							break;
						}
					}
				}
				
				// 				$detail->purchase_detail_id=;
				$detail->from='purchase';
				$detail->insert();
				//更新采购单已入库量
				$purchase->input_amount+=$each->amount;
				$purchase->update();
				
				
				$da['card_no']=$each->card_no;//卡号
				$da['input_detail_id']=$detail->id;
				$da['card_status']='normal';  //状态
				$da['title_id']=$purchase->title_id;//
				$da['input_amount']=$each->amount;
				$da['input_weight']=$each->weight;
				$da['left_amount']=$each->amount;
				$da['left_weight']=$each->weight;//剩余重量
				$da['retain_amount']=0;//保留件数
				$da['lock_amount']=0;//锁定件数
				$da['input_date']=$common->created_at;
				$da['pre_input_date']=$da['input_date'];//预计到货日期
				
				$da['frm_input_id']=$input->id;
				$da['cost_price']=$each->cost_price;
				$da['is_price_confirmed']=$purchase->price_confirm_status;//是否采购单价已确定
				$da['invoice_price']='';//采购发票成本
				$da['is_yidan']=$purchase->is_yidan;
				$da['is_pledge']=0;
				$da['purchase_id']=$purchase->id;//采购单主体信息id
				$da['is_dx']=0;//是否代销
				$da['warehouse_id']=$warehouse->id;
				$storage=Storage::createNew($da);
				
				//复制库存到merge_storage
				$merge=MergeStorage::model()->findByAttributes(array('product_id'=>$detail->product_id,'brand_id'=>$detail->brand_id,
						'texture_id'=>$detail->texture_id,'rank_id'=>$detail->rank_id,'length'=>$detail->length,'title_id'=>$da['title_id'],
						'warehouse_id'=>$warehouse->id,'is_deleted'=>'0','is_transit'=>'0'));
				if($merge)
				{//累加
				$merge->input_amount+=$each->amount;
				$merge->input_weight+=$each->weight;
				$merge->left_amount+=$each->amount;
				$merge->left_weight+=$each->weight;
				$merge->update();
				}else{
					//新建一条
					$merge=new MergeStorage();
					$merge->unsetAttributes();
					$merge->product_id=$detail->product_id;
					$merge->brand_id=$detail->brand_id;
					$merge->texture_id=$detail->texture_id;
					$merge->rank_id=$detail->rank_id;
					$merge->length=$detail->length;
					$merge->status='normal';
					$merge->cost_price=$each->cost_price;
					$merge->title_id=$da['title_id'];
					$merge->input_amount=$each->amount;
					$merge->input_weight=$each->weight;
					$merge->left_amount=$each->amount;
					$merge->left_weight=$each->weight;
					$merge->retain_amount=0;//保留件数
					$merge->lock_amount=0;//锁定件数
					$merge->is_transit=0;//是否船舱
					$merge->pre_input_date=0;//船舱入库预计到货时间
					$merge->pre_input_time=0;
					$merge->storage_id=0;//船舱入库对应库存表
					$merge->warehouse_id=$warehouse->id;//仓库id
					$merge->is_deleted=0;
					$merge->insert();
				}
							
			}else{
				//销售退货
				$this->createSaleReturn($each);
			}
		}
		
		$transaction->commit();
		}catch (Exception $e)
		{
			echo "操作失败";
			echo $e;
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
	}
	
	public function createSaleReturn($model)
	{
		
		//保存销售退货		
		$common=new CommonForms();
		$common->unsetAttributes();
		$common->form_type='XSTH';
// 		$common->form_sn=$sn;
		$common->created_at=strtotime($model->input_date);
		$common->created_by=1;
		$common->form_time=$model->input_date;
		$common->form_status='approve';
		$common->approved_at=$common->created_at;
		$common->approved_by=1;
		$common->owned_by=1;
		$common->is_deleted=0;
		
		$salesReturn = new FrmSalesReturn();
		$salesReturn->company_id = 2;
		$salesReturn->title_id = DictTitle::model()->find('short_name ="'.$model->title.'"')->id;
		$salesReturn->return_date = strtotime($model->input_date);
		$salesReturn->team_id = 7;
		$salesReturn->travel="沪A0002";
		$salesReturn->is_yidan = 0;
		$salesReturn->return_type ="warehouse";
		$salesReturn->tran_type ="get";
		$salesReturn->warehouse_id = Warehouse::model()->find('short_name="'.$model->warehouse.'"')->id;
		$salesReturn->amount=$model->amount;
		$salesReturn->weight=$model->weight;
		$salesReturn->input_amount=$model->amount;
		$salesReturn->input_weight=$model->weight;
		$salesReturn->flag=0;
	
		$salesReturn->contact_id = 2;
		$salesReturn->insert();
		$common->form_id=$salesReturn->id;
		$base=new BaseForm();
		$sn = $base->_generateSN($common->form_type,$salesReturn->id);
		$common->form_sn=$sn;
		$common->insert();

		$salesReturn_detail = new SalesReturnDetail();
		$salesReturn_detail->return_amount = $model->amount;
		$salesReturn_detail->return_weight = $model->weight;
		$salesReturn_detail->input_amount=$model->amount;
		$salesReturn_detail->input_weight=$model->weight;
		$salesReturn_detail->return_price =$model->cost_price;
		$salesReturn_detail->sales_return_id =$salesReturn->id;
		$salesReturn_detail->product_id = DictGoodsProperty::getIdByName($model->product);//品名std
		$salesReturn_detail->brand_id = DictGoodsProperty::getIdByName($model->brand);//产地
		$salesReturn_detail->texture_id = DictGoodsProperty::getIdByName($model->texture);//材质
		$salesReturn_detail->rank_id = DictGoodsProperty::getIdByName($model->rank);//规格
		$salesReturn_detail->card_no=$model->card_no;
		if(empty($model->length))
		{
			$salesReturn_detail->length =0;
		}else{
			$salesReturn_detail->length = $model->length;//长度
		}
		$salesReturn_detail->insert();
		
		//往来
		$type = "XSTH";//类型
		$turnover_direction = "need_pay";//应付
		$title_id = $salesReturn->title_id;//公司抬头
		$target_id =$salesReturn->company_id;//往来对端公司
		$amount = $salesReturn_detail->return_weight;//重量
		$price = $salesReturn_detail->return_price;//单价
		$fee = $price*$amount;
		$common_forms_id = $common->id;
		$form_detail_id = $salesReturn_detail->id;
		$ownered_by = $common->owned_by;
		$created_by=$common->created_by;
		$is_yidan=$salesReturn->is_yidan;
		$created_at=strtotime($common->form_time);
		$big_type='sales';
		$status='submited';
		$description='单号：'.$common->form_sn.','.$model->brand.'|'.$model->product.'|'.$model->rank.'*'.$salesReturn_detail->length.'*'.$each->texture;
		$turnarray = compact("type","turnover_direction","title_id","target_id","amount","price","fee","status",
				"common_forms_id",'big_type',"form_detail_id","ownered_by",'created_by','description','is_yidan','created_at'
		);
		$result = Turnover::createBill($turnarray);		
		
		if($model->product == "螺纹钢" ){
			//设置可开票明细
			$fe = 0 - $fee;
			$invoice = DetailForInvoice::setSalesInvoice($common->id,$salesReturn_detail->id,$salesReturn_detail->return_weight,$fe,$salesReturn->title_id,$salesReturn->company_id,$salesReturn->client_id);
			if(!$invoice){
				return -1;
			}
		}
		
		$common1=new CommonForms();
		$common1->unsetAttributes();
		$common1->form_type='RKD';
		$common1->created_at=strtotime($model->input_date);
		$common1->created_by=1;
		$common1->form_time=$model->input_date;
		$common1->form_status='approve';
		$common1->approved_at=$common1->created_at;
		$common1->approved_by=1;
		$common1->owned_by=1;
		$common1->is_deleted=0;
		
		$input=new FrmInput();
		$input->input_type='thrk';
		$input->purchase_id=$common->id;
		$input->input_date=$common1->created_at;
		$input->warehouse_id=$salesReturn->warehouse_id;
		$input->from='return';
		$input->input_status=1;
		$input->input_at=$common1->created_at;
		$input->input_by=1;
		$input->insert();
		
		$base=new BaseForm();
		$sn=$base->_generateSN('RKD',$input->id);
		$common1->form_sn=$sn;
		$common1->form_id=$input->id;
		$common1->insert();
		
		$detail=new InputDetail();
		$detail->input_id=$input->id;
		$detail->input_amount=$model->amount;
		$detail->input_weight=$model->weight;
		$detail->cost_price=$model->cost_price;
		$detail->product_id=$salesReturn_detail->product_id;
		$brand_id=$salesReturn_detail->brand_id;
		if(!$brand_id)
		{
			$bra_link=array(''=>'');
			$brand_id=DictGoodsProperty::getIdByName($bra_link[$model->brand]);
		}
		$detail->brand_id=$brand_id;
		$detail->texture_id=$salesReturn_detail->texture_id;
		$detail->rank_id=$salesReturn_detail->rank_id;;
		$detail->length=$salesReturn_detail->length;
		$detail->card_id=$salesReturn_detail->card_no;
		$detail->remain_amount=0;
		$detail->purchase_detail_id=$salesReturn_detail->id;
		$detail->from='return';
		$detail->insert();
		
		$da['card_no']=$salesReturn_detail->card_no;//卡号
		$da['input_detail_id']=$detail->id;
		$da['card_status']='normal';  //状态
		$da['title_id']=$salesReturn->title_id;//
		$da['input_amount']=$model->amount;
		$da['input_weight']=$model->weight;
		$da['left_amount']=$model->amount;
		$da['left_weight']=$model->weight;//剩余重量
		$da['retain_amount']=0;//保留件数
		$da['lock_amount']=0;//锁定件数
		$da['input_date']=$common1->created_at;
		$da['pre_input_date']=$da['input_date'];//预计到货日期
		
		$da['frm_input_id']=$input->id;
		$da['cost_price']=$model->cost_price;
		$da['is_price_confirmed']=0;//
		$da['invoice_price']='';//采购发票成本
		$da['is_yidan']=0;
		$da['is_pledge']=0;
		$da['purchase_id']=0;//采购单主体信息id
		$da['is_dx']=0;//是否代销
		$da['warehouse_id']=$salesReturn->warehouse_id;
		$storage=Storage::createNew($da);
		
		//复制库存到merge_storage
		$merge=MergeStorage::model()->findByAttributes(array('product_id'=>$detail->product_id,'brand_id'=>$detail->brand_id,
				'texture_id'=>$detail->texture_id,'rank_id'=>$detail->rank_id,'length'=>$detail->length,'title_id'=>$da['title_id'],
				'warehouse_id'=>$salesReturn->warehouse_id,'is_deleted'=>'0','is_transit'=>'0'));
		if($merge)
		{//累加
		$merge->input_amount+=$model->amount;
		$merge->input_weight+=$model->weight;
		$merge->left_amount+=$model->amount;
		$merge->left_weight+=$model->weight;
		$merge->update();
		}else{
			//新建一条
			$merge=new MergeStorage();
			$merge->unsetAttributes();
			$merge->product_id=$detail->product_id;
			$merge->brand_id=$detail->brand_id;
			$merge->texture_id=$detail->texture_id;
			$merge->rank_id=$detail->rank_id;
			$merge->length=$detail->length;
			$merge->status='normal';
			$merge->cost_price=$model->cost_price;
			$merge->title_id=$da['title_id'];
			$merge->input_amount=$model->amount;
			$merge->input_weight=$model->weight;
			$merge->left_amount=$model->amount;
			$merge->left_weight=$model->weight;
			$merge->retain_amount=0;//保留件数
			$merge->lock_amount=0;//锁定件数
			$merge->is_transit=0;//是否船舱
			$merge->pre_input_date=0;//船舱入库预计到货时间
			$merge->pre_input_time=0;
			$merge->storage_id=0;//船舱入库对应库存表
			$merge->warehouse_id=$salesReturn->warehouse_id;//仓库id
			$merge->is_deleted=0;
			$merge->insert();
		}	
	}
	
	
	public function actionRunTogether()
	{
		set_time_limit(5000);
		$type=$_REQUEST['type'];
		if($type=='purchase')
		{
			$sql='select b.id as brand_id,p.id as product_id,t.id as texture_id,r.id as rank_id,length,unit_weight from dict_goods d  '
					.' left join dict_goods_property b on b.std=d.brand_std'
					.' left join dict_goods_property p on p.std=d.product_std'
							.'  left join dict_goods_property t on t.std= d.texture_std'
									.' left join dict_goods_property r on r.std=d.rank_std';
			$goods=DictGoods::model()->findAllBySql($sql);
			$goods_array=array();
			if($goods)
			{
				foreach ($goods as $each)
				{
					$temp=array();
					$temp['brand_id']=$each->brand_id;
					$temp['product_id']=$each->product_id;
					$temp['texture_id']=$each->texture_id;
					$temp['rank_id']=$each->rank_id;
					$temp['length']=$each->length;
					$temp['unit_weight']=$each->unit_weight?$each->unit_weight:2;
					array_push($goods_array,(Object)$temp);
				}
			}
			for ($i=0;$i<3000;$i++)
			{
				CommonForms::bigData($type,$goods_array);
			}
		}elseif($type=='input'){
			for($i=0;$i<1000;$i++)
			{
				CommonForms::bigData($type,$goods_array);
			}
			
		}elseif($type=='skdj'){
			for ($i=0;$i<500;$i++)
			{
				CommonForms::bigData($type,$goods_array);
			}
		}elseif($type=='fkdj'){
			for ($i=0;$i<100;$i++)
			{
			CommonForms::bigData($type,$goods_array);
			}
		}
		
	}
	
	
}
