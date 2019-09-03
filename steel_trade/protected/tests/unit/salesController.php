<?php
class salesController extends CDbTestCase
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
	 * 销售单
	 */
	public function testSalesProcess()
	{
		
	}
	
	
}