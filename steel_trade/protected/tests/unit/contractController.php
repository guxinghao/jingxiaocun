<?php
class ContractController extends CDbTestCase
{
	public $fixtures = array(
		'user'=>'User',
		"dict_title"=>"DictTitle",
		"dict_company"=>"DictCompany",
		"company_contact"=>"CompanyContact",
		"dict_goods_property"=>"DictGoodsProperty",
		"dict_goods"=>"DictGoods",
		"team"=>"Team",
		"warehouse"=>"Warehouse",
		"common_forms"=>"CommonForms",
		"frm_purchase_contract"=>"FrmPurchaseContract",
		"purchase_contract_detail"=>"PurchaseContractDetail",
		"turnover"=>"Turnover"
	);

	/**
	 * 采购合同
	 */
	public function testContractProcess()
	{
		//--------------------------------测试创建-----------------------------------------------
		$FrmPurchaseContract = array(
				"dict_title_id"=>1,//采购公司
				"dict_company_id"=>"3",//供应商
				"contact_id"=>"1",//联系人
				"contract_no"=>"no201511210001",//合同编号
				"team_id"=>1,//业务组
				"warehouse_id"=>1,//入库仓库
				"is_yidan"=>1//乙单状态
		);
		$CommonForms = array(
				"form_time"=>"2015-11-21",//表单创建时间
				"owned_by"=>2,//表单所属
				"comment"=>"test备注信息"//备注
		);
		$td_products = array("lwg","lwg");//品名
		$td_ranks = array("Φ6.5","Φ8");//规格
		$td_textures = array("HPB300","HPB300");//材质
		$td_brands = array("fs","fs");//产地
		$td_length = array(11,12);//长度
		$td_amount = array(10,12);//件数
		$td_weight = array(20.1111111,36.3333333);//重量
		$td_price = array("100.00","105.00");//价格
		$td_totalMoney = array("2000.00","3780");//总价格
		$post = compact("FrmPurchaseContract","CommonForms","td_products","td_ranks","td_textures","td_brands","td_length","td_amount","td_weight","td_price","td_totalMoney");
		
		$data = FrmPurchaseContract::getInputData($post);
		
		$common = $data['common'];
		$main = $data['main'];
		$detail = $data['detail'];
		$this->assertEquals("2015-11-21",$common->form_time);
		$this->assertEquals($main->dict_title_id,1);
		$this->assertEquals(2,count($detail));
		
		$contract=new Contract(0);//调用采购合同子类，创建采购合同
		$contract->createForm($data);
		
		//判断数据是否正确
		$contract_view = ContractView::model()->findAll("common_id = 1");//视图
		$count = count($contract_view);
		$this->assertNotNull($contract_view);//断言非空
		$this->assertNotEquals(0,$count);//断言非空
		$sn = "CHT".date("ymd")."0001";//编号
		$item = $contract_view[0];
		$this->assertEquals(56.444444,$item->main_weight);
		$this->assertEquals(22,$item->main_amount);
		$this->assertEquals("unsubmit",$item->form_status);
		$this->assertEquals("CGHT",$item->form_type);
		$this->assertEquals($sn,$item->form_sn);
		
		//判断往来是否正确
		$turnover = Turnover::model()->findByPk(1);//找到对应往来
		$this->assertEquals("CGHT",$turnover->turnover_type);
		$this->assertEquals("need_pay",$turnover->turnover_direction);
		$this->assertEquals("1",$turnover->title_id);
		$this->assertEquals("3",$turnover->target_id);
		$this->assertEquals(0,$turnover->proxy_company_id);
		$this->assertEquals(56.444444,$turnover->amount);
		$this->assertEquals(0,$turnover->price);
		$this->assertEquals(5826.11,$turnover->fee);
		$this->assertEquals(1,$turnover->common_forms_id);
		$this->assertEquals(0,$turnover->form_detail_id);
		$this->assertEquals("unsubmit",$turnover->status);
		
		//日志判断
		
		//--------------------------------创建成功-----------------------------------------------
		
// 		$commonForm = CommonForms::model()->findByPk(1);
// 		$json = $commonForm->datatoJson();
		
// 		var_dump($json);die;
		
		//--------------------------------测试提交-----------------------------------------------
		$contract->submitForm();
		
		//判断状态为已提交，往来也为已提交
		$commonForm = CommonForms::model()->findByPk(1);
		$this->assertEquals("submited",$commonForm->form_status);
		$turnover = Turnover::model()->findByPk(1);//找到对应往来
		$this->assertEquals("submited",$turnover->status);
		
		$contract->cancelSubmitForm();
		//判断状态为已提交，往来也为已提交
		$commonForm = CommonForms::model()->findByPk(1);
		$this->assertEquals("unsubmit",$commonForm->form_status);
		$turnover = Turnover::model()->findByPk(1);//找到对应往来
		$this->assertEquals("unsubmit",$turnover->status);
		$contract->submitForm();
		//日志判断
		
		//--------------------------------提交成功-----------------------------------------------
		
		
		
		
		
		//--------------------------------测试修改-----------------------------------------------
		$common->comment = "test修改备注";
		$main->dict_company_id = 4;//供应商没有被改掉，在已提交的情况下主体和明细不能修改
		$detail[0]->price = 102.05;
		$detail[0]->weight = 21.22222;
		$detail[0]->length = 15;
		$data['common'] = $common;
		$data['main'] = $main;
		$data['detail'] = $detail;
		$dateail3 = array("product_std"=>"lwg","texture_std"=>"HPB300","brand_std"=>"fs","rand_std"=>"Φ8","price"=>105.00,"amount"=>12,"weight"=>36.3333333,"length"=>12);
		array_push($data['detail'], (Object)$dateail3);
		
		$contract->updateForm($data);
		$contract_view = ContractView::model()->findAll("common_id = 1");//视图
		$count = count($contract_view);
		$this->assertEquals(2,$count);
		
		$this->assertEquals("test修改备注",$contract_view[0]->comment);
		$this->assertEquals(3,$contract_view[0]->dict_company_id);
		$this->assertEquals(100,$contract_view[0]->detail_price);
		
		$contract->cancelSubmitForm();
		$contract->updateForm($data);
		$contract_view = ContractView::model()->findAll("common_id = 1");//视图
		$count = count($contract_view);
		$this->assertEquals(3,$count);
		$this->assertEquals("test修改备注",$contract_view[0]->comment);
		$this->assertEquals(4,$contract_view[0]->dict_company_id);
		$this->assertEquals(102.05,$contract_view[0]->detail_price);
		$this->assertEquals(21.22222,$contract_view[0]->detail_weight);
		$this->assertEquals(15,$contract_view[0]->length);
		
		//修改后往来修改
		$turnover = Turnover::model()->findByPk(1);//找到对应往来
		$this->assertEquals("CGHT",$turnover->turnover_type);
		$this->assertEquals("need_pay",$turnover->turnover_direction);
		$this->assertEquals("1",$turnover->title_id);
		$this->assertEquals("4",$turnover->target_id);
		$this->assertEquals(0,$turnover->proxy_company_id);
		$this->assertEquals(93.888886,$turnover->amount);
		$this->assertEquals(0,$turnover->price);
		$this->assertEquals(9795.73,$turnover->fee);
		$this->assertEquals(1,$turnover->common_forms_id);
		$this->assertEquals(0,$turnover->form_detail_id);
		$this->assertEquals("unsubmit",$turnover->status);
		
		//日志判断
		
		//--------------------------------修改成功-----------------------------------------------
		
		
		
		//--------------------------------测试审核-----------------------------------------------
		$contract->approveForm();
		$contract_view = ContractView::model()->findAll("common_id = 1");//视图
		$this->assertEquals("unsubmit",$contract_view[0]->form_status);
		
		$contract->submitForm();
		$contract->approveForm();
		$contract_view = ContractView::model()->findAll("common_id = 1");//视图
		$this->assertEquals("approve",$contract_view[0]->form_status);
		
		$contract->cancelApproveForm();
		$contract_view = ContractView::model()->findAll("common_id = 1");//视图
		$this->assertEquals("submited",$contract_view[0]->form_status);
		
		
		//--------------------------------审核成功-----------------------------------------------
		
		
		
		//--------------------------------测试履约(需要有对应的采购单关联，现在无法测试，在采购单处测试)-----------------------------------------------
		$contract->finished();
		$contract_view = ContractView::model()->findAll("common_id = 1");//视图
		$this->assertEquals("submited",$contract_view[0]->form_status);
		
		$contract->approveForm();
		$contract->finished();
		$contract_view = ContractView::model()->findAll("common_id = 1");//视图
		$this->assertEquals("approve",$contract_view[0]->form_status);
		
		
		//未关联采购合同不能履约
		
		$contract->cancelApproveForm();
		
		//--------------------------------履约成功-----------------------------------------------
		
		
		
		
		//--------------------------------测试作废-----------------------------------------------
		$contract->deleteForm();//提交状态下不能作废
		
		//判断状态为已提交，往来也为已提交
		$commonForm = CommonForms::model()->findByPk(1);
		$this->assertEquals("submited",$commonForm->form_status);
		$this->assertEquals(0,$commonForm->is_deleted);
		$turnover = Turnover::model()->findByPk(1);//找到对应往来
		$this->assertEquals("submited",$turnover->status);
		
// 		//日志判断
		
		$contract->cancelSubmitForm();
		$contract->deleteForm();//提交状态下不能作废
		
// 		//判断状态为已提交，往来也为已提交
		$commonForm = CommonForms::model()->findByPk(1);
		$this->assertEquals(1,$commonForm->is_deleted);
		$this->assertEquals("delete",$commonForm->form_status);
		$turnover = Turnover::model()->findByPk(1);//找到对应往来
		$this->assertEquals("delete",$turnover->status);
		
		
		//--------------------------------作废成功-----------------------------------------------
		
		
		
		
		
		$this->assertEquals(1,1);
	}
	
}