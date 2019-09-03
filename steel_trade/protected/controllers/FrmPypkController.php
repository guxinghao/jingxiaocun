<?php
class FrmPypkController extends AdminBaseController
{
	/*
	 * 盘盈盘亏列表
	 */
	public function actionIndex(){
		$id = intval($_REQUEST["id"]);
		$this->pageTitle = "盘盈盘亏";
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'操作','class' =>"",'width'=>"60px"),
				array('name'=>'单号','class' =>"",'width'=>"120px"),
				array('name'=>'开单日期','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'销售公司','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'卡号','class' =>"flex-col",'width'=>"150px"),
				array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'品名','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),
				array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"60px"),
				array('name'=>'盘盈盘亏','class' =>"flex-col text-right",'width'=>"100px"),
				//array('name'=>'盈亏件数','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'盈亏重量','class' =>"flex-col text-right",'width'=>"100px"),
// 				array('name'=>'操作人','class' =>"flex-col text-right",'width'=>"60px"),//
// 				array('name'=>'调拨日期','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),//
		);
		$products=DictGoodsProperty::getProList('product');
		$textures=DictGoodsProperty::getProList('texture');
		$ranks=DictGoodsProperty::getProList('rank');
		$brands = DictGoodsProperty::getProList("brand","","");
		$warehouse = Warehouse::getWareList("json");
		$titles = DictTitle::getComs("json");
		if($id){
			$storage = Storage::model()->findByPk($id);
		}
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
			if($search['status'] == 2){
				array_push($tableHeader,array('name'=>'作废原因','class' =>"flex-col",'width'=>"240px"));
			}
		}
		list($tableData,$pages,$totaldata)=FrmPypk::getFormList($search,$id);
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
				'id'=>$id,
				'storage'=>$storage,
		));
	}
	
	/*
	 * 作废调拨
	 */
	public function actionDelete($id)
	{
		$baseform=CommonForms::model()->findByPk($id);
		$str = $_REQUEST['str'];
		if($baseform)
		{
			$last_update=$_REQUEST['last_update'];
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			echo "获取基础信息失败";
			die;
		}
		if($baseform->form_status !="unsubmit"){
			echo "表单已经提交，不能作废";
			die;
		}else{
			$form=new Pypk($id);
			$result = $form->deleteForm($str);
			if($result){
				echo "success";
			}else{
				echo "操作失败";
			}
		}
	}
}