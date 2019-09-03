<?php
class StockTransferController extends AdminBaseController
{
	/*
	 * 调拨列表
	 */
	public function actionIndex(){
		$this->pageTitle = "代销调拨";
		$tableHeader = array(
			array('name'=>'','class' =>"",'width'=>"30px"),
			array('name'=>'操作','class' =>"",'width'=>"60px"),
			array('name'=>'销售公司','class' =>"flex-col",'width'=>"110px"),
			array('name'=>'供应商','class' =>"flex-col",'width'=>"110px"),
			array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),
			array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
			array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),
			array('name'=>'品名','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),
			array('name'=>'长度','class' =>"flex-col text-right",'width'=>"60px"),
			array('name'=>'调拨件数','class' =>"flex-col text-right",'width'=>"100px"),
			array('name'=>'调拨重量','class' =>"flex-col text-right",'width'=>"100px"),
			array('name'=>'操作人','class' =>"flex-col text-right",'width'=>"60px"),//
			array('name'=>'调拨日期','class' =>"flex-col",'width'=>"100px"),//
			array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),//
		);
		$products=DictGoodsProperty::getProList('product');
		$textures=DictGoodsProperty::getProList('texture');
		$ranks=DictGoodsProperty::getProList('rank');
		$brands = DictGoodsProperty::getProList("brand","","");
		$warehouse = Warehouse::getWareList("json");
		$titles = DictTitle::getComs("json");
		//搜索和换页
		$search=array();
		if($_GET['card_no']){
			$search['card_no'] = $_GET['card_no'];
		}
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['status'] == 2){
				array_push($tableHeader,array('name'=>'作废原因','class' =>"flex-col",'width'=>"240px"));
			}
		}
		list($tableData,$pages,$totaldata1)=StockTransfer::getFormList($search);
		$this->render('index',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				"totalData"=>$totaldata,
				"pages"=>$pages,
				'products'=>$products,
				'textures'=>$textures,
				'ranks'=>$ranks,
				'brands'=>$brands,
				'warehouse'=>$warehouse,
				'titles'=>$titles,
				'search'=>$search,
		));
	}

	/*
	 * 作废调拨
	 */
	public function actionDelete($id)
	{
		$str = $_REQUEST["str"];
		$stock = StockTransfer::model()->findByPk($id);
		if($stock->is_deleted == 1){
			echo "调拨已经作废，不能重复作废";
			die;
		}
		$result = StockTransfer::deleted($id,$str);
		if($result){
			echo "success";
		}else{
			echo "操作失败";
		}
	}
}