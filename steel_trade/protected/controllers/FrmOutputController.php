<?php
class FrmOutputController extends AdminBaseController
{
	/*
	 * 出库单列表
	 */
	public function actionIndex()
	{
		$id = intval($_REQUEST["id"]);
		$this->pageTitle = "出库单列表";
		if($_REQUEST['from']=='menu'){
			$this->setHome = 1;//允许设为首页
		}
		$type = $_REQUEST['type'];
		$from = $_REQUEST['from'];
		$page = $_REQUEST['fpage'];
		$fromurl = $_SERVER['HTTP_REFERER'];
		if(stristr($fromurl,"frmOutput")){
			$backurl = $_COOKIE["outputBackUrl"];
		}else{
			$backurl = $fromurl;
			setcookie("outputBackUrl",$fromurl,0,"/");
		}
		$createUrl = Yii::app()->createUrl('frmOutput/create',array("id"=>$id,'from'=>$from));
		if($id){
			$sales = FrmSales::model()->findByPk($id);
			if($sales->sales_type == "xxhj"){
				$createUrl = Yii::app()->createUrl('frmOutput/xscreate',array("id"=>$id,'from'=>$from));
			}else if($sales->sales_type == "dxxs"){
				$createUrl = Yii::app()->createUrl('frmOutput/dxcreate',array("id"=>$id,'from'=>$from));
			}
		}else{
			$sales = "";
		}
		if($type == "return"){
			$sales = FrmPurchaseReturn::model()->findByPk($id);
			$createUrl = Yii::app()->createUrl('frmOutput/rtcreate',array("id"=>$id,'from'=>$from));
		}
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"20px"),
				array('name'=>'操作','class' =>"",'width'=>"80px"),
				array('name'=>'出库单号','class' =>"",'width'=>"90px"),
				array('name'=>'状态','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'日期','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'销售单位','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'购货单位','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'卡号','class' =>"flex-col",'width'=>"130px"),//
				array('name'=>'产地/品名/材质/规格/长度','class' =>"flex-col",'width'=>"220px"),//
				//array('name'=>'提货码/车牌号','class' =>"flex-col",'width'=>"160px"),//
				array('name'=>'件数','class' =>"flex-col text-right",'width'=>"70px"),//
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'单号','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'类型','class' =>"flex-col",'width'=>"70px"),
				array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'出库人','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'出库时间','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
		);
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['form_status'] == "delete"){
				array_push($tableHeader,array('name'=>'作废原因','class' =>"flex-col",'width'=>"240px"));
			}
		}
		//获取表单列表
		$search=updateSearch($search,'search_output_index');
		if($type == "return" && $id){
			list($tableData,$pages,$totaldata)=FrmOutput::getReturnFormList($search,$id);
		}else{
			list($tableData,$pages,$totaldata)=FrmOutput::getFormList($search,$id);
		}
		
		$this->render("index",array(
				'id'=>$id,
				'backUrl'=>$backurl,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'rands'=>$ranks_array,
				'pages'=>$pages,
				'search'=>$search,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'sales'=>$sales,
				'createUrl'=>$createUrl,
				"type"=>$type,
				'from'=>$from,
				'totaldata'=>$totaldata,
		));
	}
	
	/*
	 * 新建出库单
	 */
	public function actionCreate()
	{
		$this->pageTitle = "新增出库单";
		$id = intval($_REQUEST["id"]);
		$from = $_REQUEST['from'];
		$backurl = $_SERVER['HTTP_REFERER'];
		$ware_id = $_REQUEST['ware_id'];
		
		if($_POST['amount']){
			$_POST['CommonForms']['owned_by']=$baseform->owned_by;
			$data = FrmOutput::createOutput($_POST,$ware_id);
			if($data === -2){
				$msg =  "出库件数大于销售件数，不能出库";
			}else if($data === -1){
				$msg =   "出库件数大于卡号可出件数，不能出库";
			}else if($data === -5){
				$msg =   "托盘赎回量不足，不能出库，请先赎回托盘";
			}else if($data){
				if($ware_id){
					$ware = WarehouseOutput::model()->findByPk($ware_id);
					$ware->status = 1;
					$ware->update();
				}
				$this->redirect(yii::app()->createUrl("FrmOutput/index",array("id"=>$id,'from'=>$from)));
			}
		}
		
		if($id){
			$sales = FrmSales::model()->findByPk($id);
			$baseform = $sales->baseform;
		}else{
			$sales = "";
			$baseform = "";
		}
		if(!stristr($backurl,"create")){
			setcookie("outputCreateBackUrl",$backurl,0,"/");
		}else{
			$backurl = $_COOKIE['outputCreateBackUrl'];
		}
		if(!stristr($backurl,"frmOutput")){
			setcookie("outputBackUrl",$backurl,0,"/");
		}
		//从仓库出库单过来的出库信息
		if($ware_id){
			$ware_arr = WarehouseOutput::getNeed($ware_id);
		}
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		$fromurl = $_SERVER['HTTP_REFERER'];
		if(!stristr($fromurl,"frmOutput")){
			setcookie("outputBackUrl",$fromurl,0,"/");
		}
	
		$this->render("create",array(
				'id'=>$id,
				'sales'=>$sales,
				'baseform'=>$baseform,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
				'com'=>$com,
				'vendor'=>$vendor,
				"backurl"=>$backurl,
				'msg'=>$msg,
				'ware_arr'=>$ware_arr,
				'ware_id'=>$ware_id,
		));
	}
	
	/*
	 * 新建先销后进出库单
	 */
	public function actionXscreate()
	{
		$this->pageTitle = "新增先销后进出库单";
		$id = intval($_REQUEST["id"]);
		$from = $_REQUEST['from'];
		$backurl = $_SERVER['HTTP_REFERER'];
		
		if($_POST['amount']){
			$_POST['CommonForms']['owned_by']=$baseform->owned_by;
			$data = FrmOutput::createOutput($_POST);
			if($data === -2){
				$msg =  "出库件数大于销售件数，不能出库";
			}else if($data === -1){
				$msg =   "出库件数大于卡号可出件数，不能出库";
			}else if($data){
				$this->redirect(yii::app()->createUrl("FrmOutput/index",array("id"=>$id,'from'=>$from)));
			}
		}
		
		if($id){
			$sales = FrmSales::model()->findByPk($id);
			$baseform = $sales->baseform;
			$detail = $sales->salesDetails;
		}else{
			$sales = "";
			$baseform = "";
		}
		
		if(!stristr($backurl,"create")){
			setcookie("outputCreateBackUrl",$backurl,0,"/");
		}else{
			$backurl = $_COOKIE['outputCreateBackUrl'];
		}
		if(!stristr($backurl,"frmOutput")){
			setcookie("outputBackUrl",$backurl,0,"/");
		}
		if($detail){
			foreach($detail as $dt){
				//var_dump($dt);die;
				if($dt->card_id){
					$storage = Storage::model()->findByPk($dt->card_id);
					//var_dump($storage);die;
					$dt->cost_price = $storage->cost_price;
					$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
	
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
	
		$this->render("xscreate",array(
				'id'=>$id,
				'sales'=>$sales,
				'baseform'=>$baseform,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
				'com'=>$com,
				'vendor'=>$vendor,
				"detail"=>$detail,
				"backurl"=>$backurl,
		));
	}
	
	/*
	 * 设置先销后进出库单列表
	 */
	public function actionXsdetaillist()
	{
		$id = intval($_REQUEST["id"]);
		if($id){
			$sales = FrmSales::model()->findByPk($id);
			$baseform = $sales->baseform;
			$detail = $sales->salesDetails;
		}
		if($detail){
			foreach($detail as $dt){
				//var_dump($dt);die;
				if($dt->card_id){
					$storage = Storage::model()->findByPk($dt->card_id);
					//var_dump($storage);die;
					$dt->cost_price = $storage->cost_price;
					$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}
		
		$this->renderPartial("_xslist",array(
				'id'=>$id,
				'sales'=>$sales,
				'baseform'=>$baseform,
				"detail"=>$detail,
		));
	}
	/*
	 * 设置代销销售出库单列表
	 */
	public function actionDxdetaillist()
	{
		$id = intval($_REQUEST["id"]);
		if($id){
			$sales = FrmSales::model()->findByPk($id);
			$baseform = $sales->baseform;
			$detail = $sales->salesDetails;
		}
		if($detail){
			foreach($detail as $dt){
				//var_dump($dt);die;
				if($dt->card_id){
					$storage = Storage::model()->findByPk($dt->card_id);
					//var_dump($storage);die;
					$dt->cost_price = $storage->cost_price;
					$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}
	
		$this->renderPartial("_dxlist",array(
				'id'=>$id,
				'sales'=>$sales,
				'baseform'=>$baseform,
				"detail"=>$detail,
		));
	}
	
	/*
	 * 新建代销销售出库单
	 */
	public function actionDxcreate()
	{
		$this->pageTitle = "新增代销销售出库单";
		$id = intval($_REQUEST["id"]);
		$backurl = $_SERVER['HTTP_REFERER'];
		
		if($_POST['amount']){
			$_POST['CommonForms']['owned_by']=$baseform->owned_by;
			$data = FrmOutput::createOutput($_POST);
			if($data === -2){
				$msg =  "出库件数大于销售件数，不能出库";
			}else if($data === -1){
				$msg =   "出库件数大于卡号可出件数，不能出库";
			}else if($data){
				$this->redirect(yii::app()->createUrl("FrmOutput/index",array("id"=>$id)));
			}
		}
		
		if($id){
			$sales = FrmSales::model()->findByPk($id);
			$baseform = $sales->baseform;
		}else{
			$sales = "";
			$baseform = "";
		}
		
		if(!stristr($backurl,"create")){
			setcookie("outputCreateBackUrl",$backurl,0,"/");
		}else{
			$backurl = $_COOKIE['outputCreateBackUrl'];
		}
		if(!stristr($backurl,"frmOutput")){
			setcookie("outputBackUrl",$backurl,0,"/");
		}
		$detail = $sales->salesDetails;
		if($detail){
			foreach($detail as $dt){
				if($dt->card_id){
					$storage = Storage::model()->findByPk($dt->card_id);
					$dt->cost_price = $storage->cost_price;
					$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
	
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
	
		$this->render("dxcreate",array(
				'id'=>$id,
				'sales'=>$sales,
				'baseform'=>$baseform,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
				'com'=>$com,
				'vendor'=>$vendor,
				"detail"=>$detail,
				"backurl"=>$backurl,
		));
	}
	
	/*
	 * 验证保存数据
	 */
	public function actionCheckInput()
	{
		$id = intval($_REQUEST["id"]);
		$warehouse = $_POST["warehouse"];
		$json = $_POST["json"];
		$card = $_POST["card"];
		$amount = $_POST["amount"];
		$isTr = $_POST["isTr"];
		$json = json_decode($json,true);
		$card = json_decode($card,true);
		$amount = json_decode($amount,true);
		$num = count($json);
		//遍历品名规格等信息id组成的json数组，如果发现相同的则合并两个明细的件数，
		//并设置合并过的件数为0;避免出现1和2,3合并后，2又和3合并的情况
		for($i=0;$i<$num-1;$i++){
			for($j=$i+1;$j<$num;$j++){
				if($json[$i] == $json[$j]){
					$amount[$i] += $amount[$j];
					$amount[$j] = 0;
					$json[$j] = 0;
				}
			}
		}
		//重新拼接数组为属性=>件数
		for($k=0;$k<$num;$k++){
			if($json[$k] == 0){continue;}
			$product[$json[$k]]=$amount[$k];
		}
// 		//判断卡号是否存在
// 		for($m=0;$m<$num;$m++){
// 			$result = Storage::model()->find("card_no='".$card[$m]."' and warehouse_id=".$warehouse." and is_deleted=0");
// 			if($result){
// 				continue;
// 			}else{
// 				echo "仓库不存在卡号为".$card[$m]."的产品";
// 				die;
// 			}
// 		}
		$sales = FrmSales::model()->findByPk($id);
 		$salesDetails = $sales->salesDetails;
		$d_num = 0;
		//·判断出库件数是否大于可出库件数
		foreach($salesDetails as $li){
			$length = intval($li->length);
			$key = $li->product_id.$li->rank_id.$li->texture_id.$li->brand_id.$length;
			$name = DictGoodsProperty::getProName($li->brand_id)."/".DictGoodsProperty::getProName($li->product_id)."/".DictGoodsProperty::getProName($li->texture_id)."/".DictGoodsProperty::getProName($li->rank_id)."/".$length;
			$sd_product[$d_num] = $key;
			if(!empty($product[$key])){
				$product[$key] -= ($li->amount - $li->output_amount);
			}
			$d_num++;
		}
		foreach ($product as $key=>$v){
			if($v > 0){
					if($isTr)
					{
						echo "转库件数大于销售单可转库件数";
					}else{
						echo "出库件数大于销售单累积出库件数";
					}
					die;
			}
		}
		//echo "条件通过";die;
		// 判断产品是否在销售单列表详情里
		for($n=0;$n<$num;$n++){
			if($json[$n] == 0){continue;}
			if(!in_array($json[$n],$sd_product)){
				echo "卡号为".$card[$n]."的产品不在销售单详情中";
				die;
			}
		}
		echo "success";
	}
	
	/*
	 * 验证先销后进保存数据
	 */
	public function actionCheckInputXX()
	{
		$id = intval($_REQUEST["id"]);
		$warehouse = $_POST["warehouse"];
		$json = $_POST["json"];
		$card = $_POST["card"];
		$amount = $_POST["amount"];
		$json = json_decode($json,true);
		$card = json_decode($card,true);
		$amount = json_decode($amount,true);
		$num = count($json);
		//遍历品名规格等信息id组成的json数组，如果发现相同的则合并两个明细的件数，
		//并设置合并过的件数为0;避免出现1和2,3合并后，2又和3合并的情况
		for($i=0;$i<$num-1;$i++){
			for($j=$i+1;$j<$num;$j++){
				if($json[$i] == $json[$j]){
					$amount[$i] += $amount[$j];
					$amount[$j] = 0;
					$json[$j] = 0;
				}
			}
		}
		//重新拼接数组为属性=>件数
		for($k=0;$k<$num;$k++){
			if($json[$k] == 0){continue;}
			$product[$json[$k]]=$amount[$k];
		}
		//$sales = FrmSales::model()->findByPk($id);
		$sql = "select *,sum(amount) as t_amount,sum(send_amount) as t_send_amount,sum(output_amount) as t_output_amount from sales_detail where frm_sales_id=".$id." group by product_id,brand_id,texture_id,rank_id,length";
		$cmd = Yii::app()->db->createCommand($sql);
		$salesDetails = $cmd->queryAll($cmd);
		$d_num = 0;
		//·判断出库件数是否大于可出库件数
		foreach($salesDetails as $li){
			$length = intval($li["length"]);
			$key = $li["product_id"].$li["rank_id"].$li["texture_id"].$li["brand_id"].$length;
			$name = DictGoodsProperty::getProName($li["brand_id"])."/".DictGoodsProperty::getProName($li["product_id"])."/".DictGoodsProperty::getProName($li["texture_id"])."/".DictGoodsProperty::getProName($li["rank_id"])."/".$length;
			$sd_product[$d_num] = $key;
			if(!empty($product[$key]) && $product[$key] > ($li["t_amount"] - $li["t_output_amount"])){
				//echo $name."的出库件数大于可出库件数".($li["t_amount"] - $li["t_output_amount"])."件";
				echo "出库件数大于销售单累积出库件数";
				die;
			}
		}
	
		echo "success";
		}
		
	/*
	 * 编辑出库单
	 */
	public function actionUpdate()
	{
		$this->pageTitle = "编辑出库单";
		$id = intval($_REQUEST["id"]);
		$sid = intval($_REQUEST["sid"]);
		$model = FrmOutput::model()->with("frmsales")->findByPk($id);
		$sales = $model->frmsales;
		$baseform = $model->baseform;
		$from = $_REQUEST['from'];
		
		if($_POST['amount']){
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请重试";
			}else{
				if($baseform->form_status!="unsubmit")
				{
					$msg = "表单已经提交，不能编辑";
				}else{
					$data = FrmOutput::updateOutput($_POST);
					$allform=new Output($baseform->id);
					if($_POST['submit_type'] == 1){
						$result = $allform->updateSubmitForm($data);
					}else{
						$result = $allform->updateForm($data);
					}
					if($result === -2){
						$msg =  "出库件数大于销售件数，不能出库";
					}else if($result === -1){
						$msg =   "出库件数大于卡号可出件数，不能出库";
					}else if($result === -5){
						$msg =   "托盘赎回量不足，不能出库，请先赎回托盘";
					}else if($result){
						$this->redirect(yii::app()->createUrl("FrmOutput/index",array('id'=>$sid,'page'=>$_REQUEST['fpage'],'from'=>$from)));
					}
				}
			}
		}
		$details = OutputDetail::model()->findAll("frm_output_id=".$id);
		
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		
		$id_detail = array();
		
		if($details){
			foreach ($details as $dt){
					$dt->product = DictGoodsProperty::getProName($dt->product_id);
					$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
					$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
					$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
					$type['product'] = $dt->product_id;
					$type['rank'] = $dt->rank_id;
					$type['brand'] = $dt->brand_id;
					$type['texture'] = $dt->texture_id;
					$type['length'] = $dt->length;
					$weight = DictGoods::getUnitWeight($type);
					$dt->one_weight = $weight;
					$storage = Storage::model()->findByPk($dt->storage_id);
					if($baseform->form_status == "unsubmit"){
						$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
					}else{
						$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount + $dt->amount;
					}
			}
		}
		
		$this->render("update",array(
				'id'=>$id,
				'model'=>$model,
				'detail'=>$details,
				'sales'=>$sales,
				'baseform'=>$baseform,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
				'msg'=>$msg,
		));
	}
	
	/*
	 * 编辑先销后进出库单
	 */
	public function actionXsupdate()
	{
		$this->pageTitle = "编辑先销后进出库单";
		$id = intval($_REQUEST["id"]);
		$sid = intval($_REQUEST["sid"]);
		$model = FrmOutput::model()->with("frmsales")->findByPk($id);
		$sales = $model->frmsales;
		$details = OutputDetail::model()->findAll("frm_output_id=".$id);
		$baseform = $model->baseform;
		
		if($_POST['amount']){
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请重试";
			}else{
				if($baseform->form_status!="unsubmit")
				{
					$msg = "表单已经提交，不能编辑";
					//die;
				}else{
					$data = FrmOutput::updateOutput($_POST);
					$allform=new Output($baseform->id);
					if($_POST['submit_type'] == 1){
						$result = $allform->updateSubmitForm($data);
					}else{
						$result = $allform->updateForm($data);
					}
					if($result === -2){
						$msg =  "出库件数大于销售件数，不能出库";
					}else if($result === -1){
						$msg =   "出库件数大于卡号可出件数，不能出库";
					}else if($result === -5){
						$msg =   "托盘赎回量不足，不能出库，请先赎回托盘";
					}else if($result){
						$this->redirect(yii::app()->createUrl("FrmOutput/index",array('id'=>$sid,'page'=>$_REQUEST['fpage'],'from'=>$from)));
					}
				}
			}
		}
	
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		
		$id_detail = array();
		$from = $_REQUEST['from'];
		if($details){
			foreach ($details as $dt){
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}
	
		$this->render("xsupdate",array(
				'id'=>$id,
				'model'=>$model,
				'detail'=>$details,
				'sales'=>$sales,
				'baseform'=>$baseform,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
				'msg'=>$msg,
		));
	}
	
	/*
	 * 编辑代销出库单
	 */
	public function actionDxupdate()
	{
		$this->pageTitle = "编辑代销销售出库单";
		$id = intval($_REQUEST["id"]);
		$sid = intval($_REQUEST["sid"]);
		$model = FrmOutput::model()->with("frmsales")->findByPk($id);
		$sales = $model->frmsales;
		$details = OutputDetail::model()->findAll("frm_output_id=".$id);
		$baseform = $model->baseform;
		if($_POST['amount']){
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请重试";
			}else{
				if($baseform->form_status!="unsubmit")
				{
					$msg = "表单已经提交，不能编辑";
					//die;
				}else{
					$data = FrmOutput::updateOutput($_POST);
					$allform=new Output($baseform->id);
					if($_POST['submit_type'] == 1){
						$result = $allform->updateSubmitForm($data);
					}else{
						$result = $allform->updateForm($data);
					}
					if($result === -2){
						$msg =  "出库件数大于销售件数，不能出库";
					}else if($result === -1){
						$msg =   "出库件数大于卡号可出件数，不能出库";
					}else if($result === -5){
						$msg =   "托盘赎回量不足，不能出库，请先赎回托盘";
					}else if($result){
						$this->redirect(yii::app()->createUrl("FrmOutput/index",array('id'=>$sid,'page'=>$_REQUEST['fpage'],'from'=>$from)));
					}
				}
			}
		}
		
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
	
		$id_detail = array();
		$from = $_REQUEST['from'];
		if($details){
			foreach ($details as $dt){
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}
		
		$this->render("dxupdate",array(
				'id'=>$id,
				'model'=>$model,
				'detail'=>$details,
				'sales'=>$sales,
				'baseform'=>$baseform,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
				'msg'=>$msg,
		));
	}
	
	/*
	 * 查看出库单视图
	 */
	public function actionDetail()
	{
		$this->pageTitle = "查看出库单";
		$id = intval($_REQUEST["id"]);
		$model = FrmOutput::model()->with("frmsales")->findByPk($id);
		$sales = $model->frmsales;
		$details = OutputDetail::model()->findAll("frm_output_id=".$id);
		$baseform = $model->baseform;
		$id_detail = array();
	
		if($details){
			foreach ($details as $dt){
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}
	
		$this->render("detail",array(
				'id'=>$id,
				'model'=>$model,
				'detail'=>$details,
				'sales'=>$sales,
				'baseform'=>$baseform,
		));
	}
	
	/*
	 * 提交与取消提交
	 */
	public function actionSubmit($id)
	{
		$type = $_REQUEST["type"];
		$baseform=CommonForms::model()->findByPk($id);
		
		if($baseform)
		{
			$last_update=$_REQUEST['last_update'];
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			return false;
		}
	
		$form=new Output($id);
		if($type=='submit')
		{
			$result = $form->salesSubmitForm();
			if($result === -2){
				echo "出库件数大于销售件数，不能出库";
				die;
			}
			if($result === -1){
				echo "出库件数大于卡号可出件数，不能出库";
				die;
			}
			if($result === -5){
				echo "托盘赎回量不足，不能出库，请先赎回托盘";
				die;
			}
		}elseif($type=='cancle')
		{
			$sales = $baseform->output->frmsales;
			if($sales->confirm_status == 1){
				$frmsales=new Sales($sales->baseform->id);
				$result = $frmsales->cancelcompleteSales();
				if($result==='已开票'){
					echo "已开票";
					die;
				}
				if(!$result){
					echo "取消完成销售单失败";
					die;
				}
			}
			$result = $form->salesCancelSubmitForm();
			if($result === -1){
				echo "产品已开票，不能取消出库";
				die;
			}
			if($result === -3){
				echo "库存已清卡，不能取消出库";
				die;
			}
		}
		echo "success";
	}
	
	/*
	 * 代销提交与取消提交
	 */
	public function actionXssubmit($id)
	{
		$type = $_REQUEST["type"];
		$baseform=CommonForms::model()->findByPk($id);
		if($baseform)
		{
			$last_update=$_REQUEST['last_update'];
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			return false;
		}
	
		$form=new Output($id);
		if($type=='submit')
		{
			$result = $form->submitForm();
			if($result === -2){
				echo "出库件数大于开单件数，不能出库";
				die;
			}
		}elseif($type=='cancle')
		{
			$result = $form->cancelSubmitForm();
			if($result === -1){
				echo "产品已开票，不能取消出库";
				die;
			}
			if($result === -2){
				echo "产品已补采购单，不能取消出库";
				die;
			}
			if($result === -3){
				echo "库存已清卡，不能取消出库";
				die;
			}
		}
		echo "success";
	}
	
	/*
	 * 审核表单
	 *
	 */
	public function actionCheck($id,$type)
	{
		$baseform=CommonForms::model()->findByPk($id);
		if($baseform)
		{
			$last_update=$_REQUEST['last_update'];
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			return false;
		}
		$form=new Output($id);
		if($type=='pass')
		{
			$form->approveForm();
		}elseif($type=='cancle')
		{
			$form->cancelApproveForm();
		}elseif($type=='deny')
		{
			$result = $form->refuseForm();
		}
		echo "true";
	}
	
	/*
	 * 作废表单
	 */
	public function actionDeleteform($id)
	{
		$str = $_REQUEST['str'];
		$baseform=CommonForms::model()->findByPk($id);
		if($baseform)
		{
			$last_update=$_REQUEST['last_update'];
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			return false;
		}
		if($baseform->form_status !="unsubmit"){
	
			echo "表单已经提交，不能作废";
			die;
		}else{
			$form=new Output($id);
			$form->deleteForm($str);
			echo "success";
		}
	}
	
	/*
	 * 出库单列表
	 */
	public function actionRtindex()
	{
		$id = intval($_REQUEST["id"]);
		$this->pageTitle = "退货出库列表";
		if($_REQUEST['type']=='menu'){
			$this->setHome = 1;//允许设为首页
		}
		$type = $_REQUEST['type'];
		$page = $_REQUEST['fpage'];
		$backurl = Yii::app()->createUrl('frmPurchaseReturn/index',array("id"=>$id,"fpage"=>$page));
		$createUrl = Yii::app()->createUrl('frmOutput/rtcreate',array("id"=>$id));
		$return = FrmPurchaseReturn::model()->findByPk($id);
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'操作','class' =>"",'width'=>"120px"),
				array('name'=>'出库单号','class' =>"",'width'=>"140px"),
				array('name'=>'状态','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'日期','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'销售单位','class' =>"flex-col",'width'=>"180px"),
				array('name'=>'供应商','class' =>"flex-col",'width'=>"180px"),//
				array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),//
				array('name'=>'产地/品名/材质/规格/长度','class' =>"flex-col",'width'=>"240px"),//
				//array('name'=>'提货码/车牌号','class' =>"flex-col",'width'=>"160px"),//
				array('name'=>'件数','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"120px"),//
				array('name'=>'退货单号','class' =>"flex-col",'width'=>"140px"),
				array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'出库人','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'出库时间','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
				);
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['form_status'] == "delete"){
				array_push($tableHeader,array('name'=>'作废原因','class' =>"flex-col",'width'=>"240px"));
			}
		}
		//获取表单列表
		list($tableData,$pages,$totaldata)=FrmOutput::getReturnFormList($search,$id);
		$this->render("rtindex",array(
				'id'=>$id,
				'backUrl'=>$backurl,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'rands'=>$ranks_array,
				'pages'=>$pages,
				'search'=>$search,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'return'=>$return,
				'createUrl'=>$createUrl,
				"type"=>$type,
				'totaldata'=>$totaldata,
		));
	}
	
	/*
	 * 新建退货出库单
	 */
	public function actionRtcreate()
	{
		$this->pageTitle = "新增退货出库单";
		$id = intval($_REQUEST["id"]);
		$from = $_REQUEST['from'];
		$backurl = $_SERVER['HTTP_REFERER'];
		if($_POST['amount']){
			$_POST['CommonForms']['owned_by']=$baseform->owned_by;
			$data = FrmOutput::createRtOutput($_POST);
			if($data === -2){
				$msg = "出库件数大于退货件数，不能出库";
			}else if($data === -1){
				$msg = "出库件数大于卡号可退件数，不能出库";
			}else if($data === -5){
				$msg =   "托盘赎回量不足，不能出库，请先赎回托盘";
			}else if($data){
				$this->redirect(yii::app()->createUrl("FrmOutput/index",array("id"=>$id,"type"=>"return",'from'=>$from)));
			}
		}
		
		if($id){
			$return = FrmPurchaseReturn::model()->findByPk($id);
			$baseform = $return->baseform;
		}else{
			$return = "";
			$baseform = "";
		}
		if(!stristr($backurl,"create")){
			setcookie("outputCreateBackUrl",$backurl,0,"/");
		}else{
			$backurl = $_COOKIE['outputCreateBackUrl'];
		}
		if(!stristr($backurl,"frmOutput")){
			setcookie("outputBackUrl",$backurl,0,"/");
		}
		$detail = $return->purchaseReturnDetails;
		
		if($detail){
			foreach($detail as $dt){
				//var_dump($dt);die;
				if($dt->card_no){
					$storage = Storage::model()->findByPk($dt->card_no);
					$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}

		$this->render("rtcreate",array(
				'id'=>$id,
				'return'=>$return,
				'baseform'=>$baseform,
				"detail"=>$detail,
				"backurl"=>$backurl,
		));
	}
	
	/*
	 * 设置退货出库单列表
	 */
	public function actionRtdetaillist()
	{
		$id = intval($_REQUEST["id"]);
		if($id){
			$return = FrmPurchaseReturn::model()->findByPk($id);
			$baseform = $return->baseform;
			$detail = $return->purchaseReturnDetails;
		}
		if($detail){
			foreach($detail as $dt){
				if($dt->card_no){
					$storage = Storage::model()->findByPk($dt->card_no);
					$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}
		$this->renderPartial("_rtlist",array(
				'id'=>$id,
				'sales'=>$sales,
				'baseform'=>$baseform,
				"detail"=>$detail,
		));
	}
	
	/*
	 * 编辑退货出库单
	 */
	public function actionRtupdate()
	{
		$this->pageTitle = "编辑退货出库单";
		$id = intval($_REQUEST["id"]);
		$sid = intval($_REQUEST["sid"]);
		$from = $_REQUEST['from'];
		$model = FrmOutput::model()->with("frmsales")->findByPk($id);
		$return = $model->frmreturn;
		$details = OutputDetail::model()->findAll("frm_output_id=".$id);
		$baseform = $model->baseform;
		if($_POST['amount']){
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请重试";
			}else{
				if($baseform->form_status!="unsubmit")
				{
					$msg = "表单已经提交，不能编辑";
					//die;
				}else{
					$data = FrmOutput::updateOutput($_POST);
					$allform=new Output($baseform->id);
					if($_POST['submit_type'] == 1){
						$result = $allform->updateSubmitForm($data);
					}else{
						$result = $allform->updateForm($data);
					}
					if($result === -2){
						$msg =  "出库件数大于开单件数，不能出库";
					}else if($result === -1){
						$msg =   "出库件数大于卡号可出件数，不能出库";
					}else if($result === -5){
						$msg =   "托盘赎回量不足，不能出库，请先赎回托盘";
					}else if($result){
						$this->redirect(yii::app()->createUrl("FrmOutput/index",array('id'=>$sid,'page'=>$_REQUEST['fpage'],"type"=>"return",'from'=>$from)));
					}
				}
			}
		}
		
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		$id_detail = array();
		if($details){
			foreach ($details as $dt){
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
				$type['product'] = $dt->product_id;
				$type['rank'] = $dt->rank_id;
				$type['brand'] = $dt->brand_id;
				$type['texture'] = $dt->texture_id;
				$type['length'] = $dt->length;
				$weight = DictGoods::getUnitWeight($type);
				$dt->one_weight = $weight;
			}
		}

		$this->render("rtupdate",array(
				'id'=>$id,
				'model'=>$model,
				'detail'=>$details,
				'return'=>$return,
				'baseform'=>$baseform,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
				'msg'=>$msg,
		));
	}
	
	/*------------------------------*/
	//随机生成出库单
	public function actionRandOutput(){
		$storage = new MergeStorage();
		$criteria=New CDbCriteria();
		$criteria->addCondition('is_deleted=0');
		$criteria->addCondition('is_transit=0');
		$criteria->addCondition('left_amount>0');
		$storage = $storage->findAll($criteria);
		$message[1]= array();
		$message[2]= array();
		$message[3]= array();
		$message[4]= array();
		foreach ($storage as $li){
			$temp = array();
			$temp[0] = $li->product_id;
			$temp[1] = $li->texture_id;
			$temp[2] = $li->brand_id;
			$temp[3] = $li->rank_id;
			$temp[4] = $li->length;
			$temp[5] = $li->id;
			$warehouse_id = $li->warehouse_id;
			array_push($message[$warehouse_id],$temp);
		}
		$num = 0;
		while(true){
			$result = FrmOutput::RandOutput($message);
			if($result){
				$num ++;
				if($num>=1000){
					break;
				}
			}
		}
	}
	
	
	/*
	 * 自动出库
	 */
	public function actionAutoOutput()
	{
		$this->pageTitle='自动出库';		
		if($_REQUEST['time_L'])
		{
			set_time_limit(5000);
			$time_L=$_REQUEST['time_L'];
			$time_H=$_REQUEST['time_H'];
			
			ob_end_clean();
			$dg=DictTitle::model()->find('short_name="登钢商贸"')->id;
			$jm=DictTitle::model()->find('short_name="爵淼实业"')->id;
			$criteria=new CDbCriteria();
			$criteria->with=array('sales');
			$criteria->together=true;
			$criteria->compare('form_status', 'approve');
			$criteria->compare('form_type','XSD');
			$criteria->addCondition("form_time>='$time_L' and form_time<='$time_H'");
			$criteria->addInCondition('sales.title_id', array($dg,$jm));
			$criteria->compare('sales.confirm_status','0');
			$criteria->compare('sales.can_push','1');
			$criteria->compare('sales.sales_type', 'normal');
			$criteria->addCondition("sales.output_amount is null or sales.output_amount<sales.amount");			
			$sales=CommonForms::model()->findAll($criteria);			
			if($sales)
			{
				echo count($sales);
				flush();usleep(100000);
				$i=1;
				foreach ($sales as $each)
				{
					//出库操作
					$result=$this->outPut($each);				
					if((in_array($result, array(-1,-2,-5))&&$result!==true)||!$result)
					{
						echo "单据:".$each->form_sn."自动出库出错<br/>";
					}elseif($result){
						echo $i;
					}					
					$i++;
					flush();
					usleep(100000);
				}				
			}else{
				echo 0;
			}			
			die;
		}
		$this->render('autoout');
	}
	
	/*
	 * 出库
	 * 传入commonform对象
	 */
	public  function outPut($comm)
	{
		$sales=$comm->sales;
		$sales_detail=$sales->salesDetails;		
		$other_title=DictTitle::model()->find('short_name in ("登钢商贸","爵淼实业") and id <>'.$sales->title_id)->id;		
		$post=array();			
		$post['CommonForms']['form_type']='CKD';
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$post['CommonForms']['owned_by']=$comm->owned_by;
		$post['CommonForms']['comment']="自动出库";
		$data['common']=(Object)$post['CommonForms'];		
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		foreach ($sales_detail as $each)
		{
			//找库存
			$sql="select s.id from storage s left join input_detail i on s.input_detail_id=i.id where i.brand_id=$each->brand_id and  
			 i.product_id = $each->product_id and i.texture_id=$each->texture_id and i.rank_id = $each->rank_id and  i.length=$each->length 
			 and s.card_status='normal' and s.title_id in ($sales->title_id,$other_title) and s.is_dx=0 and s.is_deleted=0    
			 and (s.left_amount-s.retain_amount-s.lock_amount)>=$each->amount order by locate(s.title_id,'{$sales->title_id},{$other_title}')";//数量
			$storage=Storage::model()->findBySql($sql);
			$temp = array();
			$temp["storage_id"] =$storage->id ;
			$temp["amount"] = $each->amount;
			$temp["weight"] = $each->weight;
			$temp["product_id"] = $each->product_id;
			$temp["rank_id"] = $each->rank_id;
			$temp["brand_id"] = $each->brand_id;
			$temp["texture_id"] = $each->texture_id;
			$temp["length"] = $each->length;
			$temp["sales_detail_id"] = $each->id;			
			$amount +=$each->amount;
			$weight +=$each->weight;
			array_push($data['detail'],(Object)$temp);
		}
		$post['out']['from'] = "purchase";		
		$post['out']['frm_sales_id']=$sales->id;
		$post['out']['amount'] = $amount;
		$post['out']['weight'] = $weight;
		$post['out']['is_return'] = 0;
		$data['main']=(Object)$post['out'];					
		$allform=new Output($id);		
		$result = $allform->createSubmitOutForm($data);		
		return $result;
	}
	
}