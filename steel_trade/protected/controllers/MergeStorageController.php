<?php
class MergeStorageController extends AdminBaseController
{
	/*
	 *销售单选择的列表
	 */
	public function actionSalist(){
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'品名','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"60px"),
				array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'预计到货日期','class' =>"flex-col",'width'=>"180px"),//
				array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'可供件数','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'可供重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'公司','class' =>"flex-col",'width'=>"110px"),
		);
		
		//搜索和换页
		$search=array();
	
		if(isset($_REQUEST['page']))
		{
			$search["type"]=$_REQUEST['type'];
			$search["title_id"]=$_REQUEST['title_id'];
			$search["card_no"]=$_REQUEST['card_no'];
			$search["rand"]=$_REQUEST['rand_std'];
			$search["product"]=$_REQUEST['product_std'];
			$search["texture"]=$_REQUEST['texture_std'];
			$search["brand"]=$_REQUEST['brand_std'];
			$search["warehouse_id"]=$_REQUEST['warehouse_id'];
			$search["length"]=$_REQUEST['length'];
		}
		list($tableData,$pages,$totaldata1)=MergeStorage::getFormList($search);
		$this->renderPartial('_salist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				"totalData"=>$totaldata,
				"pages"=>$pages,
		));
	}
	
	/*
	 *锁定库存列表
	 */
	public function actionLocklist()
	{
		$this->pageTitle = "库存管理";
		$tableHeader = array(
				//array('name'=>'操作','class' =>"",'width'=>"50px"),
				array('name'=>'仓库','class' =>"",'width'=>"70px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'品名','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'材质','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'规格','class' =>"flex-col",'width'=>"40px"),
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"40px"),
				array('name'=>'件重','class' =>"flex-col text-right",'width'=>"43px"),				
				array('name'=>'可用件数','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'可用重量','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'剩余件数','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'剩余重量','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'锁定件数','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'锁定重量','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'保留件数','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'保留重量','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'入库件数','class' =>"flex-col text-right",'width'=>"80px"),
				array('name'=>'入库重量','class' =>"flex-col text-right",'width'=>"120px"),
				array('name'=>'是否船舱入库','class' =>"flex-col",'width'=>"90px"),
				array('name'=>'预计到货日期','class' =>"flex-col",'width'=>"110px"),//
				array('name'=>'成本单价','class' =>"flex-col text-right",'width'=>"70px"),
				array('name'=>'采购发票成本','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'是否托盘','class' =>"flex-col",'width'=>"70px"),
				array('name'=>'公司','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'托盘公司','class' =>"flex-col",'width'=>"60px"),
		);
		
		$com=DictTitle::getComs("json");
		$warehouse_array=Warehouse::getWareList("json");
		$products_array=DictGoodsProperty::getProList('product');
		$textures_array=DictGoodsProperty::getProList('texture');
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		$ranks_array=DictGoodsProperty::getProList('rank');
		
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}else{
			$search['left']=1;
		}
		// 搜索日起小于今天查询备份纪录，否则显示当前纪录
		if(!empty($search['bak_date']) && $search['bak_date']<date('Y-m-d')){
			list($tableData,$pages,$totaldata)=MergeStorageBak::getLockList($search);
		}else{
			unset($search['bak_date']);
			list($tableData,$pages,$totaldata)=MergeStorage::getLockList($search);
		}
		$totalData = array("合计：","","","","","","",number_format($totaldata["can_amount"]),
					number_format($totaldata["can_weight"],3),number_format($totaldata["ll_amount"]),number_format($totaldata["ll_weight"],3),
					number_format($totaldata["l_amount"]),number_format($totaldata["l_weight"],3),number_format($totaldata["r_amount"]),
					number_format($totaldata["r_weight"],3),number_format($totaldata["i_amount"]),number_format($totaldata["i_weight"],3),"","","","","","","");
		$this->render('locklist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				//"totalData"=>$totaldata,
				"totalData1"=>$totalData,
				"pages"=>$pages,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'ranks'=>$ranks_array,
				'search'=>$search
		));
	}

	public function actionExport() 
	{
		$search = $_REQUEST['search'];
		$name = "仓库库存".date("Y/m/d");
		$title = array('仓库', '产地', '品名', '材质', '规格', '长度', '件重', '入库件数', '入库重量', '可用件数', '可用重量', '剩余件数', '剩余重量', '锁定件数','锁定重量', '保留件数', '是否船舱入库', '预计到货日期', '成本单价', '采购发票成本', '是否托盘', '公司', '托盘公司');

		$content = MergeStorage::getAllList($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}
	
	/**
	 * 备份
	 */
	public function actionAutoHistory(){
// 		$transaction=Yii::app()->db->beginTransaction();
// 		try {
		    $now = time();
		    $aDay = 24*3600;
		    $today = date("Y-m-d");
		    $yet = date("Y-m-d",strtotime("-1 day"));
		    //备份
		    //查找数据是否已经存在  
		    $res = MergeStorageBak::model()->find("bak_date = '{$yet}'");
		    if($res) die("数据已经存在");
		    $storage_yesterdays = MergeStorage::model()->findAll();
		    foreach ($storage_yesterdays as $s){
		        $b = new MergeStorageBak();
		        $b->product_id = $s->product_id;
		        $b->brand_id = $s->brand_id;
		        $b->texture_id = $s->texture_id;
		        $b->rank_id = $s->rank_id;
		        $b->status = $s->status;
		        $b->length = $s->length;
		        $b->cost_price = $s->cost_price;
		        $b->title_id = $s->title_id;
		        $b->redeem_company_id = $s->redeem_company_id;
		        $b->input_weight = $s->input_weight;
		        $b->input_amount = $s->input_amount;
		        $b->left_amount = $s->left_amount;
		        $b->left_weight = $s->left_weight;
		        $b->retain_amount = $s->retain_amount;
		        $b->retain_weight = $s->retain_weight;
		        $b->lock_amount = $s->lock_amount;
		        $b->lock_weight = $s->lock_weight;
		        $b->pre_input_date = $s->pre_input_date;
		        $b->pre_input_time = $s->pre_input_time;
		        $b->is_transit = $s->is_transit;
		        $b->storage_id = $s->storage_id;
		        $b->warehouse_id = $s->warehouse_id;
		        $b->invoice_price = $s->invoice_price;
		        $b->is_deleted = $s->is_deleted;
		        $b->last_update = $s->last_update;
		        $b->bak_date = $yet;
		        $b->insert();
		    }
		    echo 'ok';
// 			$transaction->commit();
// 		}catch (Exception $e){
// 			echo $e;
// 			$transaction->rollBack();//事务回滚
// 			return;
// 		}
		    @session_destroy();
	}
}