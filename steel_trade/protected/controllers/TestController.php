<?php
class TestController extends AdminBaseController
{
	public $layout='admin';
	
	
	/**
	 * 1.每天跑脚本之前，先确定要更新的库存数，可以把之前的库存删除掉或者移到别的库存里面
	 * 2.复制库存数据到新的ProfitStorage表中
	 * 3.更新ProfitStorage表中的锁定件数
	 * 4.获取配置项，计算各种预估费用
	 */
	
	/**
	 * 2.复制库存数据到新的ProfitStorage表中,不用管之前数据是否存在
	 */
	public function actionShareStorage()
	{
		$sql = "insert into profit_storage
				(`storage_id`,`card_no`,`purchase_id`,`form_sn`,`purchase_date`,`input_date`,`title_id`,`title_name`,`brand_id`,`product_id`,`texture_id`,`rank_id`,`length`,`left_weight`,`type`,`warehouse_id`)
				select t.id , t.card_no , c.form_id,c.form_sn,UNIX_TIMESTAMP(c.form_time),t.input_date,t.title_id,title.name,detail.brand_id,detail.product_id,detail.texture_id,detail.rank_id,detail.length,t.left_weight,i.input_type,t.warehouse_id
				from storage t left join frm_input i on t.frm_input_id = i.id
				left join common_forms c on i.purchase_id = c.id
				left join dict_title title on t.title_id = title.id
				left join input_detail detail on t.input_detail_id = detail.id
				where t.is_dx =0 and t.left_amount > 0 and t.is_deleted = 0";
		//执行sql
		$connection = Yii::app()->db;  
		$command = $connection->createCommand("TRUNCATE steel_trade.profit_storage")->execute();
        $command = $connection->createCommand($sql)->execute();
        if($command) echo "success";
	}
	
	/**
	 * 3.将聚合表的锁定重量分摊到所有的卡号上,船舱入库可以跟着卡号，直接更新，去掉船舱的，其他的根据时间顺序锁定
	 */
	public function actionShareLock()
	{
// 		echo microtime();
// 		echo "<br/>";
// 		echo memory_get_usage();
// 		echo "<br/>";
		//找到所有的非船舱的
		$mergeStorage = new MergeStorage();
		$c = new CDbCriteria();
		$c->addCondition("is_transit = 0 and left_amount > 0 and lock_weight > 0 and is_deleted = 0");
		$merge = $mergeStorage->findAll($c);
		if ($merge){//找到所有聚合表信息
			foreach ($merge as $item) {
// 				if ($item->is_transit == 1){
// 					$storage = ProfitStorage::model()->find("storage_id = ".$item->storage_id);
// 					if (!$storage) continue;
// 					$storage->lock_weight = $item->lock_weight;
// 					$storage->update();
// 					continue;
// 				}
				
				$lock_weight = $item->lock_weight;//获取聚合表锁定重量
				$storage = ProfitStorage::model()->findAll('product_id='.$item->product_id.' and brand_id='.$item->brand_id.' and texture_id='.$item->texture_id.' and rank_id ='.$item->rank_id.' and length ='.$item->length.' and title_id = '.$item->title_id.' and warehouse_id ='.$item->warehouse_id." order by input_date asc;");
				$area_id = Warehouse::model()->findByPk($item->warehouse_id)->area;//区域
				//品名规格材质产地对应的std
				$product_std = DictGoodsProperty::getStd($item->product_id);
				$brand_std = DictGoodsProperty::getStd($item->brand_id);
				$texture_std = DictGoodsProperty::getStd($item->texture_id);
				$rank_std = DictGoodsProperty::getStd($item->rank_id);
				
				$quoted = QuotedDetail::model()->with(array("relation"=>array("condition"=>"relation.area_id = {$area_id}")))->findByAttributes(array('product_std'=>$product_std,'brand_std'=>$brand_std,'texture_std'=>$texture_std,'rank_std'=>$rank_std,'length'=>$item->length,'type'=>'guidance','price_date'=>date(time())));
				$name = $quoted->relation;
				
				if (!is_array($storage)) continue;
				foreach ($storage as $_item) {//循环更新聚合表数据
					if ($lock_weight <=0) break;//锁定重量小于0，不用继续执行
					if ($lock_weight >= $_item->left_weight){
						$_item->lock_weight = $_item->left_weight;
						$_item->update();
						$lock_weight -= $_item->left_weight;
					}else{
						$_item->lock_weight = $lock_weight;
						$_item->update();
						$lock_weight = 0;
					}
				}
			}
		}
		
		
		//接下来找所有船舱的
		$mergeStorage = new MergeStorage();
		$c = new CDbCriteria();
		$c->addCondition("is_transit =1 and left_amount > 0 and lock_weight > 0 and is_deleted = 0");
		$merge = $mergeStorage->findAll($c);
		if ($merge){//找到所有聚合表信息
			foreach ($merge as $item) {
				$storage = ProfitStorage::model()->find("storage_id = ".$item->storage_id);
				if (!$storage) continue;
				$storage->lock_weight = $item->lock_weight;
				$storage->update();
			}
		}
// 		echo microtime();
// 		echo "<br/>";
// 		echo memory_get_usage();
		echo "success";
	}
	
	/**
	 * 4.获取配置项，计算各种预估费用
	 */
	public function actionCaculate()
	{
		//
	}
	
	/**
	 * 初始化销售指导价
	 * 品名	规格	长度	材质	价格	产地	区域	专区
	 */
	public function actionImportGuidence()
	{
		Yii::$enableIncludePath = false;
		$this->pageTitle = "销售价格初始化";
		if($_FILES['import']['tmp_name']){
			$objExcel = new PHPExcel();
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$filename=$_FILES['import']['tmp_name'];
			$objPHPExcel = $objReader->load($filename);
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			for($i=2;$i<=$highestRow;$i++){
				$product_std = trim($sheet->getCell("A".$i)->getValue());
				$rank_std = trim($sheet->getCell("B".$i)->getValue());
				$length = trim($sheet->getCell("C".$i)->getValue());
				$texture_std = trim($sheet->getCell("D".$i)->getValue());
				$price = trim($sheet->getCell("E".$i)->getValue());
				$brand_std = trim($sheet->getCell("F".$i)->getValue());
				$area_id = trim($sheet->getCell("G".$i)->getValue());
				$prefecture_id = trim($sheet->getCell("H".$i)->getValue());
				
				if (!$product_std || !$rank_std || !$texture_std || !$brand_std) continue;
				
				//先去找一下，看能否找到对应的价格信息
				$quoted = QuotedDetail::model()->find("product_std = '{$product_std}' and rank_std = '{$rank_std}' and texture_std = '{$texture_std}' and brand_std = '{$brand_std}' and length = {$length} and prefecture = {$prefecture_id} and type = 'guidence'");
// 				var_dump($product_std,$rank_std,$texture_std,$brand_std,$length,$prefecture_id,$quoted);die;
				if ($quoted){
					$id = $quoted->id;
					$quotedRelation = new QuotedWarehouseRelation();//关联表更新
					$quotedRelation->quoted_id = $id;
					$quotedRelation->area_id = $area_id;
					$quotedRelation->price = $price;
					$quotedRelation->price_date = date("Y-m-d",time());
					$quotedRelation->insert();
				}else{
					$quoted = new QuotedDetail();
					$quoted->product_std = $product_std;
					$quoted->rank_std = $rank_std;
					$quoted->length = $length;
					$quoted->texture_std = $texture_std;
					$quoted->brand_std = $brand_std;
					$quoted->created_by = 1;
					$quoted->created_at = time();
					$quoted->type = 'guidance';
					$quoted->price_date = date("Y-m-d",time());
					$quoted->prefecture = $prefecture_id;
					if ($quoted->insert()){
						$id = $quoted->id;
						$quotedRelation = new QuotedWarehouseRelation();//关联表更新
						$quotedRelation->quoted_id = $id;
						$quotedRelation->area_id = $area_id;
						$quotedRelation->price = $price;
						$quotedRelation->price_date = date("Y-m-d",time());
						$quotedRelation->insert();
					}
				}
			}
			die("导入完成");
		}
		
		$this->render("importGuidence",array());
	}
	
	/**
	 * 初始化已有用户邀请码
	 */
	public function actionInitalUser()
	{
		$user = User::model()->findAll("is_deleted = 0");
		
		foreach ($user as $item) {
			$item->invit_code = str_pad($item->id,4,"0",STR_PAD_LEFT);
			$item->update();
		}
	}
	
	
	/**
	 * 更新之前初始化库存中已审单的成本价
	 */
	public function actionInitalStorageCost()
	{
		$sql = 'SELECT s.*,p.id as pid,p.fix_price FROM steel_trade.storage s left join input_detail i on i.id = s.input_detail_id left join purchase_detail p on i.purchase_detail_id = p.id  where  s.is_price_confirmed = 1 and s.cost_price = 0;';
		
		$connection = Yii::app()->db;
		$result = $connection->createCommand($sql)->queryAll();
		
		foreach ($result as $item) {
			$id = $item['id'];
			$model = Storage::model()->findByPk($id);
			$model->cost_price = $item['fix_price'];
			$model->update();
		}
		
	}
	
	
	/**
	 * 删除销售开票和折让对应的消息通知
	 */
	public function actionDelMsg()
	{
		$content = MessageContent::model()->with("messageBoxes")->findAll("type='高开折让' or type = 'XSKP'");
		foreach ($content as $item){
			$model = $item->messageBoxes;
			foreach ($model as $val) {
				$val->delete();
			}
			$item->delete();
		}
	}
	
	
	/**
	 * 初始化消息，新增大类
	 */
	public function actionInitalMessage()
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(5000);
// 		$msg = MessageBox::model()->with("message")->findAll("status=1");       
        
		$content = MessageContent::model()->findAll();
		
        foreach ($content as $val){
        	switch ($val->type){
        		case "付款":
        		case "付款登记":
        		case "收款登记":
        		case "收付款":
        		case "短期借贷":
        		case "费用报支":
        		case "银行互转":
        			$big_type = "money";
        			break;
        		case "推送通知":
        		case "配送单":
        		case "钢厂返利":
        		case "仓库返利":
        		case "仓储费用":
       			case "运费":
       				$big_type = "ware";
       				break;
       			case "托盘赎回单":
       			case "采购单":
       			case "采购退货":
       			case "采购折让":
       			case "采购合同":
       				$big_type = "purchase";
       				break;
       			case "销售单":
       			case "销售退货单":
       			case "销售折让":
       				$big_type = "sale";
       				break;
       			default:
       				$big_type = "unknow";
       				break;
        	}
        	$model = $val->messageBoxes;
        	foreach ($model as $item){
        		if($item->status != 1) continue;
        		$item->big_type = $big_type;
        		$item->update();
        	}
        }
	}
}