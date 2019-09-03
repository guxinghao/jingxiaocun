<?php
class StorageController extends AdminBaseController
{
	/*
	 *销售单选择的列表 
	 */
	public function actionSalist(){
		$tableHeader = array(
			array('name'=>'','class' =>"",'width'=>"30px"),
			array('name'=>'','class' =>"",'width'=>"30px"),
			array('name'=>'公司','class' =>"flex-col",'width'=>"110px"),
			array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),
			array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
			array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),
			array('name'=>'品名','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),
			array('name'=>'长度','class' =>"flex-col text-right",'width'=>"60px"),
			array('name'=>'可供件数','class' =>"flex-col text-right",'width'=>"100px"),
			array('name'=>'库存件数','class' =>"flex-col text-right",'width'=>"100px"),
			array('name'=>'可供重量','class' =>"flex-col text-right",'width'=>"100px"),//
			array('name'=>'乙单','class' =>"flex-col",'width'=>"60px"),//
			array('name'=>'预计到货日期','class' =>"flex-col",'width'=>"130px"),//
			array('name'=>'入库时间','class' =>"flex-col",'width'=>"120px"),//
		);
		
		//搜索和换页
		$search=array();
		
		if(isset($_REQUEST['page']))
		{
			$search["card_no"]=$_REQUEST['card_no'];
			$search["rand"]=$_REQUEST['rand_std'];
			$search["product"]=$_REQUEST['product_std'];
			$search["texture"]=$_REQUEST['texture_std'];
			$search["brand"]=$_REQUEST['brand_std'];
			$search["warehouse_id"]=$_REQUEST['warehouse_id'];
		}
		list($tableData,$pages,$totaldata1)=Storage::getFormList($search);
		$this->renderPartial('_salist',array(
			'tableHeader'=>$tableHeader,
			'tableData'=>$tableData,
			"totalData"=>$totaldata,
			"pages"=>$pages,
		));
	}
	
	/*
	 *代销销售单选择的列表
	 */
	public function actionDxlist(){
		$tableHeader = array(
			array('name'=>'','class' =>"",'width'=>"30px"),
			array('name'=>'','class' =>"",'width'=>"30px"),
			array('name'=>'采购公司','class' =>"flex-col",'width'=>"110px"),//
			array('name'=>'供应商','class' =>"flex-col",'width'=>"110px"),
			array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),
			array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
			array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),
			array('name'=>'品名','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),
			array('name'=>'长度','class' =>"flex-col",'width'=>"60px"),
			array('name'=>'可供件数','class' =>"flex-col",'width'=>"100px"),
			array('name'=>'可供重量','class' =>"flex-col",'width'=>"100px"),//
// 			array('name'=>'预计到货日期','class' =>"flex-col",'width'=>"140px"),//
// 			array('name'=>'入库时间','class' =>"flex-col",'width'=>"130px"),//
		);
	
		//搜索和换页
		$search=array();
	
		if(isset($_REQUEST['page']))
		{
			$search["card_no"]=$_REQUEST['card_no'];
			$search["supply"]=$_REQUEST['supply'];
			$search["product"]=$_REQUEST['product_std'];
			$search["warehouse"]=$_REQUEST['warehouse'];
			$search["brand"]=$_REQUEST['brand_std'];
			$search["title_id"]=$_REQUEST['title_id'];
			$search["texture"]=$_REQUEST['texture_std'];
			$search["rank"]=$_REQUEST['rand_std'];
			$search["length"]=$_REQUEST['length'];
		}
		
		list($tableData,$pages,$totaldata1)=Storage::getDxFormList($search);
		$this->renderPartial('_salist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				"totalData"=>$totaldata,
				"pages"=>$pages,
		));
	}
	
	/*
	 *采购退货选择的列表
	 */
	public function actionPreturnlist(){
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'公司','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'供应商','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),
				array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'品名','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"60px"),
				array('name'=>'可退件数','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'可退重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'入库时间','class' =>"flex-col",'width'=>"120px"),//
		);
	
		//搜索和换页
		$search=array();
	
		if(isset($_REQUEST['page']))
		{
			$search["card_no"]=$_REQUEST['card_no'];
			$search["rand"]=$_REQUEST['rand_std'];
			$search["product"]=$_REQUEST['product_std'];
			$search["texture"]=$_REQUEST['texture_std'];
			$search["brand"]=$_REQUEST['brand_std'];
			$search["warehouse_id"]=$_REQUEST['warehouse_id'];
			$search["title_id"]=$_REQUEST['title_id'];
			$search["length"]=$_REQUEST['length'];
		}
		list($tableData,$pages,$totaldata1)=Storage::getPreturnFormList($search);
		$this->renderPartial('_preturnlist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				"totalData"=>$totaldata,
				"pages"=>$pages,
		));
	}
	
	/*
	 *出库单选择的列表
	 */
	public function actionCklist(){
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),
				array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'品名','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'材质','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'规格','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'可供件数','class' =>"flex-col text-right",'width'=>"100px"),
				//array('name'=>'库存件数','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'可供重量','class' =>"flex-col text-right",'width'=>"100px"),//
				//array('name'=>'预计到货日期','class' =>"flex-col",'width'=>"140px"),//
				array('name'=>'入库时间','class' =>"flex-col",'width'=>"100px"),//
		);
	
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['page']))
		{
			$search["card_no"]=$_REQUEST['card_no'];
			$search["rand"]=$_REQUEST['rand_std'];
			$search["product"]=$_REQUEST['product_std'];
			$search["texture"]=$_REQUEST['texture_std'];
			$search["brand"]=$_REQUEST['brand_std'];
		}
		$is_dx = $_REQUEST['sales'];
		$sales_id = $_REQUEST['sales_id'];
		$warehouse_id = $_REQUEST['warehouse_id'];
		$ware_id = $_REQUEST['ware_id'];
		if($ware_id){
			$sql = "select product_id,brand_id,texture_id,rank_id,length from warehouse_output_detail where warehouse_output_id=".$ware_id." group by product_id,brand_id,texture_id,rank_id,length";
		}else{
			$sql = "select * from sales_detail where frm_sales_id=".$sales_id." group by product_id,brand_id,texture_id,rank_id,length";
		}
		$cmd = Yii::app()->db->createCommand($sql);
		$salesDetails = $cmd->queryAll($cmd);
		$sales = FrmSales::model()->findByPk($sales_id);
		$card = array();
		foreach ($salesDetails as $li){
			if($is_dx == 0){
				$mstorage = MergeStorage::model()->findByPk($li['card_id']);
				if($mstorage->is_transit == 1){
					echo '<div style="line-height:50px;font-size:14px;width:100%;text-align:center;">销售单中含有船舱入库产品，不能出库</div>';
					die;
				}
			}
			$model = new Storage();
			$criteria=New CDbCriteria();
			$criteria->with = array('inputDetail','frmInput');
			$criteria->addCondition("t.warehouse_id=".$warehouse_id);
			//$criteria->addCondition("t.title_id=".$sales->title_id);
			$criteria->addCondition("t.is_dx=".$is_dx);
			$criteria->addCondition("t.is_deleted=0");
			$criteria->addCondition("inputDetail.brand_id=".$li["brand_id"]);
			$criteria->addCondition("inputDetail.product_id=".$li["product_id"]);
			$criteria->addCondition("inputDetail.rank_id=".$li["rank_id"]);
			$criteria->addCondition("inputDetail.texture_id=".$li["texture_id"]);
			$criteria->addCondition("inputDetail.length=".intval($li["length"]));
			$criteria->addCondition("frmInput.input_type<>'ccrk'");
			$details=$model->findAll($criteria);
			foreach($details as $each){
				array_push($card,$each->id);
			}
		}
		$card_id = implode(",",$card);
		list($tableData,$pages,$totaldata1)=Storage::getCkFormList($search,$card_id);
		$this->renderPartial('_cklist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				"totalData"=>$totaldata,
				"pages"=>$pages,
				'ware_id'=>$ware_id,
		));
	}
	
	/*
	 *转库单选择的列表
	 */
	public function actionZklist(){
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),
				array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'品名','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'材质','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'规格','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'可供件数','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'可供重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'入库时间','class' =>"flex-col",'width'=>"100px"),//
		);
	
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['page']))
		{
			$search["card_no"]=$_REQUEST['card_no'];
			$search["rand"]=$_REQUEST['rand_std'];
			$search["product"]=$_REQUEST['product_std'];
			$search["texture"]=$_REQUEST['texture_std'];
			$search["brand"]=$_REQUEST['brand_std'];
		}
		$warehouse_id = $_REQUEST['warehouse_id'];
		$ware_id = $_REQUEST['ware_id'];
		if($ware_id){
			$sql = "select product_id,brand_id,texture_id,rank_id,length from warehouse_output_detail where warehouse_output_id=".$ware_id." group by product_id,brand_id,texture_id,rank_id,length";
		}
		$cmd = Yii::app()->db->createCommand($sql);
		$salesDetails = $cmd->queryAll($cmd);
		$card = array();
		foreach ($salesDetails as $li){
			
			$model = new Storage();
			$criteria=New CDbCriteria();
			$criteria->with = array('inputDetail');
			$criteria->addCondition("t.warehouse_id=".$warehouse_id);
			$criteria->addCondition("t.is_dx=0");
			$criteria->addCondition("t.is_deleted=0");
			$criteria->addCondition("inputDetail.brand_id=".$li["brand_id"]);
			$criteria->addCondition("inputDetail.product_id=".$li["product_id"]);
			$criteria->addCondition("inputDetail.rank_id=".$li["rank_id"]);
			$criteria->addCondition("inputDetail.texture_id=".$li["texture_id"]);
			$criteria->addCondition("inputDetail.length=".intval($li["length"]));
			$details=$model->findAll($criteria);
			foreach($details as $each){
				array_push($card,$each->id);
			}
		}
		$card_id = implode(",",$card);
		list($tableData,$pages,$totaldata1)=Storage::getCkFormList($search,$card_id);
		$this->renderPartial('_zklist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				"totalData"=>$totaldata,
				"pages"=>$pages,
				'ware_id'=>$ware_id,
		));
	}
	
	/*
	 * 查看某仓库是否有某卡号的商品
	 */
	public function actionHaveOrNot()
	{
		$warehouse_id=$_REQUEST['warehouse_id'];
		$card_no=$_REQUEST['card_no'];
		$detail_id=$_REQUEST['detail_id'];
		$type=$_REQUEST['type'];
		if($type=='dxrk')
		{
			$input_detail=InputDetailDx::model()->findByPk($detail_id);
		}else{
			$input_detail=InputDetail::model()->findByPk($detail_id);
		}		
		if($input_detail->card_id==$card_no)
		{
			$result=false;
		}else{
			$result=Storage::getStroageid($card_no, $warehouse_id);
		}		
		echo $result;
	}
	
	/**
	 * 库存列表
	 */
	public function actionIndex(){
		list($model,$pages,$items,$search,$totaldata) = Storage::getIndexList();
		$type=$_REQUEST['type'];
		$this->pageTitle = "卡号管理";
		if($type == "retain"){
			$this->pageTitle = "保留库存";
		}
//		$access = Yii::app()->authmanager->checkAccess("管理员",Yii::app()->user->userid);
		foreach ($items as $i){
			$weight = DictGoods::getWeightByStorage($i);
			$i->weight = $weight;
			$i->available_amount = $i->left_amount;
			$i->available_weight = $i->left_weight;
			$i->available_amount = $i->available_amount - $i->retain_amount - $i->lock_amount;
			$i->available_weight = $i->left_weight - $i->retain_amount*$weight - $i->lock_amount*$weight;
		}
		
		$products = DictGoodsProperty::getProList("product","","");
		$textures = DictGoodsProperty::getProList("texture","","");
		$ranks = DictGoodsProperty::getProList("rank","","");
		$brands = DictGoodsProperty::getProList("brand","","");
		$warehouse = Warehouse::getWareList("json");
		$titles = DictTitle::getComs("json");
		$this->render('index',array(
			"model"=>$model,
			"items"=>$items,
			"pages"=>$pages,
			'search'=>$search,
			'products'=>$products,
			'textures'=>$textures,
			'ranks'=>$ranks,
			'brands'=>$brands,
			'warehouse'=>$warehouse,
			'titles'=>$titles,
//			'access'=>$access,
			'totaldata'=>$totaldata
		));
	}
	
	public function actionSearch(){
		$this->pageTitle = "库存查询";

		$com=DictTitle::getComs("json");
		$warehouse_array=Warehouse::getWareList("json");
		$products_array=DictGoodsProperty::getProList('product');
		$textures_array=DictGoodsProperty::getProList('texture');
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		$ranks_array=DictGoodsProperty::getProList('rank');
		
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}		
		list($tableHeader,$tableData,$pages,$totaldata)=Storage::storageSearch($search);
		$totalData = array("合计：","","","","","","",#number_format($totaldata["i_amount"]),number_format($totaldata["i_weight"],3),
					number_format($totaldata["can_amount"]),number_format($totaldata["can_weight"],3),
					#number_format($totaldata["ll_amount"]),number_format($totaldata["ll_weight"],3),
					#number_format($totaldata["l_amount"]),number_format($totaldata["r_amount"]),
					"","",
					// "","","","",
					"");
		$this->render('search',array(
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










		// list($model,$pages,$items,$search,$totaldata) = Storage::getIndexList();		
		// foreach ($items as $i){
		// 	$weight = DictGoods::getWeightByStorage($i);
		// 	if($weight == 0){
		// 		$i->weight = "--";
		// 		$weight = $i->input_weight/$i->input_amount;
		// 	}else{
		// 		$i->weight = $weight;
		// 	}
		// 	$i->available_amount = $i->left_amount;
		// 	$i->available_weight = $i->left_weight;
		// 	$i->available_amount = $i->available_amount - $i->retain_amount - $i->lock_amount;
		// 	$i->available_weight = $i->left_weight - $i->retain_amount*$weight - $i->lock_amount*$weight;
		// 	if($i->available_amount>100){
		// 		$i->available_amount = 100;
		// 		$i->available_weight = 100*$weight;
		// 	}
		// }
		
		// $products = DictGoodsProperty::getProList("product","","");
		// $textures = DictGoodsProperty::getProList("texture","","");
		// $ranks = DictGoodsProperty::getProList("rank","","");
		// $brands = DictGoodsProperty::getProList("brand","","");
		// $warehouse = Warehouse::getWareList("json");
		// $titles = DictTitle::getComs("json");
		// $this->render('search',array(
		// 	"model"=>$model,
		// 	"items"=>$items,
		// 	"pages"=>$pages,
		// 	'search'=>$search,
		// 	'products'=>$products,
		// 	'textures'=>$textures,
		// 	'ranks'=>$ranks,
		// 	'brands'=>$brands,
		// 	'warehouse'=>$warehouse,
		// 	'titles'=>$titles,
		// 	'totaldata'=>$totaldata
		// ));
	}
	
	/**
	 * 代销库存列表
	 */
	public function actionDx(){
		list($model,$pages,$items,$search,$totaldata) = Storage::getDXList();
		$this->pageTitle = "代销库存管理";
//		$access = Yii::app()->authmanager->checkAccess("管理员",Yii::app()->user->userid);
		$access = true;
		if($access){
			foreach ($items as $i){
				$weight = DictGoods::getWeightByStorage($i);
				$i->left_amount -= $i->retain_amount;
				$i->left_weight = $i->left_weight - $i->retain_amount*$weight - $i->lock_amount*$weight;
				$i->can_weight = $i->left_weight - $i->lock_weight - $i->retain_amount*$weight;
			}
		}
		
		$products = DictGoodsProperty::getProList("product","","");
		$textures = DictGoodsProperty::getProList("texture","","");
		$ranks = DictGoodsProperty::getProList("rank","","");
		$brands = DictGoodsProperty::getProList("brand","","");
		$warehouse = Warehouse::getWareList("json");
		$titles = DictTitle::getComs("json");
		$this->render('dx',array(
			"model"=>$model,
			"items"=>$items,
			"pages"=>$pages,
			'search'=>$search,
			'products'=>$products,
			'textures'=>$textures,
			'ranks'=>$ranks,
			'brands'=>$brands,
			'warehouse'=>$warehouse,
			'titles'=>$titles,
			'access'=>$access,
			'totaldata'=>$totaldata
		));
	}
	
	/**
	 * 库存汇总
	 */
	public function actionTotal(){
		
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}else{
			$search['start_time']=date('Y-m-d',time()-7*24*3600);
			$search['end_time']=date('Y-m-d',time());
		}
		list($tableHeader,$tableData)=Storage::getTotalList($search);
		$this->pageTitle = "库存汇总";
		$this->setHome = 1;//允许设为首页
		$warehouse = Warehouse::getWareList("json");
		$products = DictGoodsProperty::getProList("product","","");
		$this->render('total',array(
			'search'=>$search,
			'products'=>$products,
			'warehouse'=>$warehouse,
			'tableHeader'=>$tableHeader,
			'tableData'=>$tableData,
		));
	}
	

	/**
	 * 设置保留件数
	 */
	public function actionSetRetain()
	{
		$id = $_POST["id"];
		$num = $_POST["num"];
		$weight = $_POST["weight"];
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$storage = Storage::model()->findByPk($id);
			if($storage)
			{
				$oldJson=$storage->datatoJson();
				$cha = $storage->retain_amount - $num;
				$cha_weight = $storage->retain_weight - $weight;
				$storage->retain_amount = $num;
				$storage->retain_weight = $weight;
				if($storage->left_amount-$storage->lock_amount-$storage->retain_amount < 0){
					throw new CException("little");
				}
				if($storage->update()){
					$mainJson = $storage->datatoJson();
					$dataArray = array("tableName"=>"storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$baseform = new BaseForm();
					$baseform->dataLog($dataArray);
					$model = new MergeStorage();
					$criteria=New CDbCriteria();
					$criteria->addCondition('product_id ='.$storage->inputDetail->product_id);
					$criteria->addCondition('brand_id ='.$storage->inputDetail->brand_id);
					$criteria->addCondition('texture_id ='.$storage->inputDetail->texture_id);
					$criteria->addCondition('rank_id ='.$storage->inputDetail->rank_id);
					$criteria->addCondition('length ='.$storage->inputDetail->length);
					$criteria->addCondition('title_id ='.$storage->title_id);
					if($storage->inputDetail->input->input_type == "ccrk"){
						$criteria->addCondition('is_transit = 1');
					}else{
						$criteria->addCondition('is_transit = 0');
					}
					$criteria->addCondition('warehouse_id = '.$storage->warehouse_id);
					$criteria->addCondition('is_deleted = 0');
					$merge = $model->find($criteria);
				
					if($merge){
						$oldJson=$merge->datatoJson();
						$merge->retain_amount -= $cha;
						if($merge->left_amount-$merge->lock_amount-$merge->retain_amount < 0){
							throw new CException("little");
						}
						$merge->retain_weight -= $cha_weight;
						if($merge->update()){
							$mainJson = $merge->datatoJson();
							$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$baseform = new BaseForm();
							$baseform->dataLog($dataArray);
						}
					}
				}else{
					throw new CException("wrong");
				}
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			echo $e->message;
			die;
		}
		echo "success";
	}
	
	/**
	 * 设置调拨件数
	 */
	public function actionSetdiaobo()
	{
		$id = $_POST["id"];
		$amount = $_POST["amount"];
		$weight = $_POST["weight"];
		$comment = $_POST["comment"];
		$storage = Storage::model()->findByPk($id);
		if($storage)
		{
			$oldJson=$storage->datatoJson();
			$storage->left_amount -= $amount;
			$storage->left_weight -= $weight;
			if($storage->left_amount-$storage->lock_amount-$storage->retain_amount < 0){
				echo "little";
				die;
			}
			if($storage->update()){
				$mainJson = $storage->datatoJson();
				$dataArray = array("tableName"=>"storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
				$result = StockTransfer::createStock($_POST);
				if($result){
					echo "success";
				}else{
					echo "wrong";
				}
			}else{
				echo "wrong";
			}
		}
	}
	
	/**
	 * 设置盘盈盘亏
	 */
	public function actionSetPypk()
	{
		$id = $_POST["id"];
		$storage = Storage::model()->findByPk($id);
		if($storage)
		{
			$result = Storage::setPypk($_POST);
			if($result){
				echo "success";
				die;
			}else{
				echo "操作失败";
				die;
			}
		}else{
			echo "获取库存失败";
			die;
		}
	}
	
	/**
	 * 设置清卡
	 */
	public function actionQingka()
	{
		$id = $_REQUEST["id"];
		$storage = Storage::model()->findByPk($id);
		if($storage)
		{
			if($storage->card_status == "clear"){
				echo "库存已被清卡，无须重复提交";die;
			}
			if($storage->card_status == "deleted"){
				echo "库存已被删除，不能清卡";die;
			}
			if($storage->left_amount > 0){
				echo "产品剩余件数大于0，不能清卡";die;
			}
			if(abs($storage->left_weight)>=0.001){
				echo "剩余重量不为0，不能清卡";die;
			}
			$result = Storage::setQingka($id);
			if($result){
				echo "success";
				die;
			}else{
				echo "操作失败";
				die;
			}
		}
	}
	
	/**
	 * 库存历史自动更新
	 */
	public function actionAutoHistory(){
// 		$transaction=Yii::app()->db->beginTransaction();
// 		try {
		    $now = time();
		    $aDay = 24*3600;
		    $today = date("Y-m-d");
		    //备份
		    $storage_yesterdays = Storage::model()->findAll();
		    foreach ($storage_yesterdays as $s){
		        $b = StorageBak::model()->find("storage_id=$s->id and bak_date='{$today}'");
		        if(!$b){
		            $b = new StorageBak();
		        }
		        $b->bak_date = $today;
		        $b->storage_id = $s->id;
		        $b->card_no = $s->card_no;
		        $b->input_detail_id = $s->input_detail_id;
		        $b->card_status = $s->card_status;
		        $b->title_id = $s->title_id;
		        $b->redeem_company_id = $s->redeem_company_id;
		        $b->input_amount = $s->input_amount;
		        $b->input_weight = $s->input_weight;
		        $b->left_amount = $s->left_amount;
		        $b->left_weight = $s->left_weight;
		        $b->retain_amount = $s->retain_amount;
		        $b->lock_amount = $s->lock_amount;
		        $b->input_date = $s->input_date;
		        $b->pre_input_date = $s->pre_input_date;
		        $b->frm_input_id = $s->frm_input_id;
		        $b->cost_price = $s->cost_price;
		        $b->is_price_confirmed = $s->is_price_confirmed;
		        $b->invoice_price = $s->invoice_price;
		        	
		        $b->is_yidan = $s->is_yidan;
		        $b->is_pledge = $s->is_pledge;
		        $b->is_dx = $s->is_dx;
		        $b->warehouse_id = $s->warehouse_id;
		        $b->is_deleted = $s->is_deleted;
		        $b->purchase_id=$s->purchase_id;
		        if($b->id){
		            $b->update();
		        }else{
		            $b->insert();
		        }
		    }
			
			for($i=1;$i<=7;$i++){
				$date = date("Y-m-d",$now-($i-1)*$aDay);
				$this->deleteByDate($date);
				$this->historyByDate($date);
			}
// 			$transaction->commit();
// 		}catch (Exception $e)
// 		{
// 			echo $e;
// //			echo "操作失败";
// 			$transaction->rollBack();//事务回滚
// 			return;
// 		}
		@session_destroy();
	}
	/**
	 *根据日期删除老数据 
	 */
	public function deleteByDate($date){
		StorageDateLog::model()->deleteAll("date='{$date}'");
	}
	
	/**
	 * 库存历史自动更新
	 */
	public function historyByDate($date){
		$st = strtotime($date." 00:00:00");
		$et = strtotime($date." 23:59:59");
		
		//非代销入库
		$cri = new CDbCriteria();
		$cri->select = "t.warehouse_id,d.product_id as product_id,t.is_yidan,i.input_type as input_type ,sum(t.left_amount) as total_amount,sum(t.left_weight) as total_weight,sum(t.input_weight) as input_weight_sum,sum(t.input_amount) as input_amount_sum"; 
		$cri->join = "left join input_detail d on t.input_detail_id=d.id
					left join frm_input i on t.frm_input_id = i.id";
		$cri->addCondition("t.is_dx=0");//非代销
		$cri->addCondition("i.input_type<>'ccrk'");//去除船舱入库
		$cri->addCondition("i.input_status = 1");//需已入库
		$cri->addCondition("t.is_deleted = 0");
		$cri->group = "t.warehouse_id,d.product_id,t.is_yidan,i.input_type";
		$cri->addCondition("t.bak_date='{$date}'");
		$items =StorageBak::model()->findAll($cri);
		foreach ($items as $i){
			if(!$i->is_yidan)$i->is_yidan=0;
			$temp = StorageDateLog::model()->find("warehouse_id=$i->warehouse_id and product_id=$i->product_id and is_yidan=$i->is_yidan and type='".$i->input_type."' and date='{$date}'");
			if(!$temp){
				$temp = new StorageDateLog();
				$temp->warehouse_id = $i->warehouse_id;
				$temp->product_id = $i->product_id;
				$temp->is_yidan=$i->is_yidan;
				$temp->type=$i->input_type;
				$temp->date = $date;
			}		
			$temp->total_input_weight=$i->input_weight_sum;
			$temp->total_input_amount=$i->input_amount_sum;
			$temp->amount = $i->total_amount;
			$temp->weight = $i->total_weight;
			
			//查出库
			$cri2 = new CDbCriteria();
			$cri2->select = "t.*,s.warehouse_id as warehouse_id, sum(t.amount) as amount_sum,sum(t.weight) as weight_sum";
			$cri2->join = "left join frm_output o on t.frm_output_id = o.id
						left join frm_sales s on o.frm_sales_id = s.id";
			$cri2->addCondition("o.input_status=1");
			$cri2->addCondition("o.output_at<=".$et);
			$cri2->compare('warehouse_id', $i->warehouse_id);
			$cri2->compare('product_id',$i->product_id);
			$cri2->group = "warehouse_id,t.product_id";
			$items2 = OutputDetail::model()->find($cri2);
			$temp->total_output_amount =$items2->amount_sum;
			$temp->total_output_weight =$items2->weight_sum;
			
			//查盘盈盘亏
			$cri3 = new CDbCriteria();
			$cri3->select = "t.*,s.warehouse_id as warehouse_id,d.product_id as product_id ,sum(t.amount) as total_amount,sum(t.weight) as total_weight";
			$cri3->join = "left join storage s on s.id=t.storage_id
		              left join input_detail d on d.id=s.input_detail_id
						left join common_forms f on t.id = f.form_id";
			$cri3->addCondition("f.form_type='PYPK'");
			$cri3->addCondition(" s.input_date<=$et");
			$cri3->group = "warehouse_id,d.product_id";
			$cri3->addCondition("f.is_deleted<>1");
			$cri3->compare('warehouse_id', $i->warehouse_id);
			$cri3->compare('product_id',$i->product_id);
			$items3 = FrmPypk::model()->find($cri3);
			$temp->total_pypk_amount =$items3->total_amount;
			$temp->total_pypk_weight=$items3->total_weight;
			
			if($temp->id){
				$temp->update();
			}else{
				$temp->insert();
			}
		}

// 		//出库
// 		$cri2 = new CDbCriteria();
// 		$cri2->select = "t.*,s.warehouse_id as warehouse_id, sum(t.amount) as amount_sum,sum(t.weight) as weight_sum";
// 		$cri2->join = "left join frm_output o on t.frm_output_id = o.id
// 						left join frm_sales s on o.frm_sales_id = s.id";
// 		$cri2->addCondition("o.input_status=1");
// 		$cri2->addCondition("o.output_at<=".$et);
// 		$cri2->group = "warehouse_id,t.product_id";
// 		$items2 = OutputDetail::model()->findAll($cri2);
// 		foreach ($items2 as $i){
// 			$temp = StorageDateLog::model()->find("warehouse_id=$i->warehouse_id and product_id=$i->product_id and date='{$date}'");
// 			if(!$temp){
// 				$temp = new StorageDateLog();
// 				$temp->warehouse_id = $i->warehouse_id;
// 				$temp->product_id = $i->product_id;
// 				$temp->date = $date;
// 			}
// 			$temp->total_output_amount =$i->amount_sum;
// 			$temp->total_output_weight =$i->weight_sum;
// 			if($temp->id){
// 				$temp->update();
// 			}else{
// 				$temp->insert();
// 			}
// 		}
		//盘盈盘亏
// 		$cri3 = new CDbCriteria();
// 		$cri3->select = "t.*,s.warehouse_id as warehouse_id,d.product_id as product_id ,sum(t.amount) as total_amount,sum(t.weight) as total_weight";
// 		$cri3->join = "left join storage s on s.id=t.storage_id
// 		              left join input_detail d on d.id=s.input_detail_id 
// 						left join common_forms f on t.id = f.form_id";
// 		$cri3->addCondition("f.form_type='PYPK'");
// 		$cri3->addCondition(" s.input_date<=$et");
// 		$cri3->group = "warehouse_id,d.product_id";
// 		$cri3->addCondition("f.is_deleted<>1");
// 		$items3 = FrmPypk::model()->findAll($cri3);
// 		foreach ($items3 as $i){
// 		    $temp = StorageDateLog::model()->find("warehouse_id=$i->warehouse_id and product_id=$i->product_id and date='{$date}'");
// 		    if(!$temp){
// 		        $temp = new StorageDateLog();
// 		        $temp->warehouse_id = $i->warehouse_id;
// 		        $temp->product_id = $i->product_id;
// 		        $temp->date = $date;
// 		    }
// 		    $temp->total_pypk_amount =$i->total_amount;
// 		    $temp->total_pypk_weight=$i->total_weight;
// 		    if($temp->id){
// 		        $temp->update();
// 		    }else{
// 		        $temp->insert();
// 		    }
// 		}
	}
	
	//设置库存锁定重量脚本
	public function actionSetLockWeight(){
		$result = Storage::SetLockWeight();
		echo $result;
	}
	
	//设置库存锁定重量脚本
	public function actionGetWrongLockWeight(){
		$result = Storage::GetWrongLockWeight();
		if($result){
			//$myfile = fopen("/website/steel_trade/steel_trade/lockWeightLog.txt", "w") or die("Unable to open file!");
			$myfile = fopen("/var/www/steel/steel_trade/lockWeightLog.txt", "a+") or die("Unable to open file!");
			fwrite($myfile, $result);
			fclose($myfile);
		}
		echo $result;
	}
	

	//获取库存信息，设置盘盈盘亏
	public function actionGetPypkData(){
		$id = $_POST["id"];
		$model = Storage::model()->findByPk($id);
		
		$this->renderPartial('_pypk',array(
				'model'=>$model,
		));
	}
}
?>
