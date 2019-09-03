<?php
class WarehouseOutputController extends AdminBaseController
{
	/*
	 * 仓库出库单列表
	*/
	public function actionIndex()
	{
		$this->pageTitle = "仓库出库单列表";
		$tableHeader = array(
			array('name'=>'','class' =>"",'width'=>"20px"),
			array('name'=>'操作','class' =>"",'width'=>"80px"),
			array('name'=>'开单日期','class' =>"flex-col",'width'=>"80px"),
			array('name'=>'销售单位','class' =>"flex-col",'width'=>"60px"),
			array('name'=>'出库单号','class' =>"flex-col",'width'=>"120px"),
			array('name'=>'销售单号','class' =>"flex-col",'width'=>"100px"),
			//array('name'=>'购货单位','class' =>"flex-col",'width'=>"100px"),//
			array('name'=>'库存卡号','class' =>"flex-col",'width'=>"130px"),//
			array('name'=>'产地/品名/材质/规格/长度','class' =>"flex-col",'width'=>"220px"),//
			array('name'=>'车船号','class' =>"flex-col",'width'=>"130px"),//
			array('name'=>'件数','class' =>"flex-col text-right",'width'=>"80px"),//
			//array('name'=>'理论重量','class' =>"flex-col",'width'=>"120px"),//
			array('name'=>'重量','class' =>"flex-col text-right",'width'=>"100px"),//
			//array('name'=>'制单人','class' =>"flex-col",'width'=>"100px"),//
			array('name'=>'备注','class' =>"flex-col",'width'=>"120px"),//
		);
		
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		//根据品名，规格，材质，产地来选择商品
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
		}
		$search=updateSearch($search,'search_warehouseoutput_index');
		//获取表单列表
		list($tableData,$pages)=WarehouseOutput::getFormList($search);
		
		$this->render("index",array(
			'backUrl'=>$backurl,
			'users'=>$user_array,
			'vendors'=>$vendor,
			'coms'=>$com,
			'products'=>$products_array,
			'textures'=>$textures_array,
			'brands'=>$brands_array,
			'rands'=>$ranks_array,
			'pages'=>$pages,
			'search'=>$search,
			'tableHeader'=>$tableHeader,
			'tableData'=>$tableData
		));
	}
	
	/*
	 * 处理仓库配送单
	 */
	public function actionUpdate($id)
	{
		$this->pageTitle = "提交仓库出库单";
		$output = WarehouseOutput::model()->findByPk($id);
		$detail = WarehouseOutputDetail::model()->findAll("warehouse_output_id=".$output->id);
		foreach ($detail as $li){
			$li->product = DictGoodsProperty::getProName($li->product_id);
			$li->rank = DictGoodsProperty::getProName($li->rank_id);
			$li->texture = DictGoodsProperty::getProName($li->texture_id);
			$li->brand = DictGoodsProperty::getProName($li->brand_id);
		}
		if($_POST['amount']){
			if($output->status != 0){
				$msg = "表单已经处理，不能保存";
			}else{
				$result = WarehouseOutput::setDetail($id,$_POST);
				if($result>0){
					$this->redirect(yii::app()->createUrl("warehouseOutput/index",array('page'=>$_REQUEST['fpage'])));
				}elseif($result == 0){
					$msg = "对应仓库卡号不存在";
				}else{
					$msg = "保存失败";
				}
			}
		}
		$this->render("update",array(
			'output'=>$output,
			'detail'=>$detail,
			'msg'=>$msg,
		));
	}
	
	/*
	 * 查看仓库配送单视图
	 */
	public function actionDetail($id)
	{
		$this->pageTitle = "查看仓库出库单";
		$output = WarehouseOutput::model()->findByPk($id);
		$detail = WarehouseOutputDetail::model()->findAll("warehouse_output_id=".$output->id);
		foreach ($detail as $li){
			$li->product = DictGoodsProperty::getProName($li->product_id);
			$li->rank = DictGoodsProperty::getProName($li->rank_id);
			$li->texture = DictGoodsProperty::getProName($li->texture_id);
			$li->brand = DictGoodsProperty::getProName($li->brand_id);
		}
		$this->render("detail",array(
				'output'=>$output,
				'detail'=>$detail,
		));
	}
	
	/*
	 * 设置接受的仓库信息为已处理状态
	*/
	public function actionComplete($id)
	{
		$output = WarehouseOutput::model()->findByPk($id);
		if($output){
			if($output->status != 0){
				echo "出库单不是未处理状态，设置失败";
				die;
			}
			$output->status = 1;
			if($output->update()){
				echo "success";die;
			}else{
				echo "更新失败";
				die;
			}
		}else{
			echo "没有找到对应出库单";
			die;
		}
	}
	
	/*
	 * 从接口中心接受数据
	 */
	public function actionGetData()
	{
		$data = $_POST['interface'];
		$data = '{"Verb":"add","Content":{"Tables":[{"Columns":[{"Schema":"warehouse_id"},{"Schema":"form_id"},{"Schema":"output_form_id"},{"Schema":"owner_company"},{"Schema":"created_at"},{"Schema":"output_type"},{"Schema":"buyer_company"},{"Schema":"content"}],"Records":[{"Fields":[{"Text":1},{"Text":18},{"Text":"C1602000019955"},{"Text":"\u745e\u4eae","Value":"1"},{"Text":"1455613496"},{"Text":"normal"},{"Text":"\u7231\u529e","Value":"6"},{"Text":null}],"Details":[{"Columns":[{"Schema":"card_no"},{"Schema":"goods_name"},{"Schema":"goods_company"},{"Schema":"texture"},{"Schema":"rank"},{"Schema":"length"},{"Schema":"amount"},{"Schema":"weight"}],"Records":[{"Fields":[{"Text":"HHH0001"},{"Text":"cr"},{"Text":"lwg"},{"Text":"HRB400"},{"Text":"\u03a620"},{"Text":"9"},{"Text":"5"},{"Text":"10.02000"}]}]}]}]}]},"RevisionTime":1453951567}';
		$result = WarehouseOutput::SetData($data);
		
	}
	
	//仓库转库失败处理
	public function actionTransfer($ware_id){
		$this->pageTitle = "修改仓库转库单";
		$ware_id = $_REQUEST["ware_id"];
		$output = WarehouseOutput::model()->findByPk($ware_id);
		if($_POST['amount']){
			if($output->status != 0){
				$msg = "表单已经处理，不能保存";
			}else{
				$_POST['CommonForms']['owned_by']=$baseform->owned_by;
				$result = FrmOutput::createZKOutput($_POST,$ware_id);
				if($result>0){
					$this->redirect(yii::app()->createUrl("warehouseOutput/index",array('page'=>$_REQUEST['fpage'])));
				}elseif($result == 0){
					$msg = "对应仓库卡号不存在";
				}else{
					$msg = "保存失败";
				}
			}
		}
		
		$detail = $output->warehouseOutputDetails;
		$ware_arr = WarehouseOutput::getNeed($ware_id);
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		
		$this->render("transfer",array(
				'output'=>$output,
				'detail'=>$detail,
				'msg'=>$msg,
				"ware_arr"=>$ware_arr,
				"ware_id"=>$ware_id,
				'product'=>$products_array,
				'texture'=>$textures_array,
				'brand'=>$brands_array,
				'rank'=>$ranks_array,
		));
	}
	
	public function actionTest(){
		$model = WarehouseOutput::model()->findByPk(493);
		FrmSend::setVirtual($model);
	}
}