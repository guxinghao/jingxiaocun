<?php
class FrmSalesController extends AdminBaseController
{
	/*
	 * 销售单列表
	 */
	public function actionIndex()
	{
		$this->pageTitle = "销售管理";
		$this->setHome = 1;//允许设为首页
		$msg = $_REQUEST["msg"];
		$type = $_REQUEST['view'];
		if($type == ''){$type = $_COOKIE['view'];}
		if($type == ''){$type = "index";}
		if(!checkOperation("销售审核视图") && $type=="checkview"){
			$type = "index";
		}
		if(!checkOperation("销售出库视图") && $type=="outview"){
			$type = "index";
		}
		if( $type=="baseview"&&!checkOperation("基价核算视图") ){
			$type='index';
		}
		setcookie("view",$type,time()+3600*24*30,"/");
		if($type=="outview"){
			$tableHeader = array(
					array('name'=>'','class' =>"text-center",'width'=>"20px"),
					array('name'=>'操作','class' =>"",'width'=>"80px"),
					array('name'=>'单号','class' =>"",'width'=>"80px"),
					array('name'=>'状态','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'开单日期','class' =>"flex-col",'width'=>"78px"),
					array('name'=>'客户','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'结算单位','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'销售公司','class' =>"flex-col",'width'=>"60px"),
					array('name'=>'业务员','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'乙单','class' =>"flex-col",'width'=>"38px"),//
					array('name'=>'类型','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'仓库','class' =>"flex-col",'width'=>"58px"),//
					array('name'=>'产地','class' =>"flex-col",'width'=>"58px"),//
					array('name'=>'品名','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'材质','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'规格','class' =>"flex-col",'width'=>"36px"),//
					array('name'=>'长度','class' =>"flex-col text-right",'width'=>"34px"),//					
					//array('name'=>'单价','class' =>"flex-col text-right",'width'=>"100px"),
					array('name'=>'件数','class' =>"flex-col text-right",'width'=>"34px"),//
					array('name'=>'重量','class' =>"flex-col text-right",'width'=>"62px"),//
					//array('name'=>'金额','class' =>"flex-col text-right",'width'=>"100px"),//
					//array('name'=>'费用','class' =>"flex-col",'width'=>"100px"),//
					array('name'=>'配送件数','class' =>"flex-col text-right",'width'=>"64px"),//
					array('name'=>'出库件数','class' =>"flex-col text-right",'width'=>"64px"),//
					array('name'=>'仓库出库件数','class' =>"flex-col text-right",'width'=>"84px"),//
					array('name'=>'核定件数','class' =>"flex-col text-right",'width'=>"60px"),//
					array('name'=>'核定重量','class' =>"flex-col text-right",'width'=>"64px"),//
					array('name'=>'实时出库件数','class' =>"flex-col text-right",'width'=>"84px"),//
					array('name'=>'实时出库重量','class' =>"flex-col text-right",'width'=>"84px"),//
					//array('name'=>'核定单价','class' =>"flex-col",'width'=>"100px"),//
					//array('name'=>'核定金额','class' =>"flex-col text-right",'width'=>"100px"),//
					//array('name'=>'收款金额','class' =>"flex-col",'width'=>"100px"),//
					//array('name'=>'差额','class' =>"flex-col text-right",'width'=>"100px"),//
					//array('name'=>'已结算金额','class' =>"flex-col",'width'=>"100px"),//
					array('name'=>'业务组','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'修改人','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'审核人','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'审核时间','class' =>"flex-col",'width'=>"100px"),
					array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
			);
		}else if($type=="checkview"){
			$tui_authority=checkOperation("销售单:推送");
			$array1= array(
					array('name'=>'','class' =>"text-center",'width'=>"20px"),
					array('name'=>'操作','class' =>"",'width'=>"80px"),
					array('name'=>'单号','class' =>"",'width'=>"80px"),
					array('name'=>'状态','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'开单日期','class' =>"flex-col",'width'=>"78px"),
					array('name'=>'客户','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'结算单位','class' =>"flex-col",'width'=>"60px"),
					array('name'=>'销售公司','class' =>"flex-col",'width'=>"60px"),
				);
			$array2=$tui_authority?array(array('name'=>'往来余额','class' =>"flex-col text-right",'width'=>"100px"),):array();
			$array3=array(
					array('name'=>'业务员','class' =>"flex-col",'width'=>"48px"),//
					//array('name'=>'类型','class' =>"flex-col",'width'=>"80px"),//
					array('name'=>'仓库','class' =>"flex-col",'width'=>"58px"),//
					array('name'=>'产地','class' =>"flex-col",'width'=>"58px"),//
					array('name'=>'品名','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'材质','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'规格','class' =>"flex-col",'width'=>"36px"),//
					array('name'=>'长度','class' =>"flex-col text-right",'width'=>"34px"),//
					array('name'=>'件数','class' =>"flex-col text-right",'width'=>"34px"),//
					array('name'=>'重量','class' =>"flex-col text-right",'width'=>"60px"),//
					array('name'=>'单价','class' =>"flex-col text-right",'width'=>"48px"),					
					array('name'=>'金额','class' =>"flex-col text-right",'width'=>"78px"),//
	 				array('name'=>'核定件数','class' =>"flex-col text-right",'width'=>"60px"),//
					array('name'=>'核定重量','class' =>"flex-col text-right",'width'=>"64px"),//
					array('name'=>'核定金额','class' =>"flex-col text-right",'width'=>"80px"),//					
					array('name'=>'乙单','class' =>"flex-col",'width'=>"38px"),//
					array('name'=>'高开价','class' =>"flex-col text-right",'width'=>"60px"),
					array('name'=>'实时出库件数','class' =>"flex-col text-right",'width'=>"84px"),//
					array('name'=>'实时出库重量','class' =>"flex-col text-right",'width'=>"84px"),//
					array('name'=>'车船号','class' =>"flex-col",'width'=>"120px"),
					array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
			);
			$tableHeader=array_merge($array1,$array2,$array3);
		}elseif($type=="baseview"){
			$tableHeader = array(
					array('name'=>'','class' =>"text-center",'width'=>"20px"),
					array('name'=>'操作','class' =>"",'width'=>"80px"),
					array('name'=>'单号','class' =>"",'width'=>"80px"),
					array('name'=>'状态','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'开单日期','class' =>"flex-col",'width'=>"78px"),
					array('name'=>'客户','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'销售公司','class' =>"flex-col",'width'=>"60px"),
					array('name'=>'业务员','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'仓库','class' =>"flex-col",'width'=>"58px"),//
					array('name'=>'产地','class' =>"flex-col",'width'=>"58px"),//
					array('name'=>'品名','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'材质','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'规格','class' =>"flex-col",'width'=>"36px"),//
					array('name'=>'长度','class' =>"flex-col text-right",'width'=>"34px"),//
					array('name'=>'件数','class' =>"flex-col text-right",'width'=>"34px"),//
					array('name'=>'重量','class' =>"flex-col text-right",'width'=>"60px"),//
					array('name'=>'单价','class' =>"flex-col text-right",'width'=>"48px"),						
					array('name'=>'金额','class' =>"flex-col text-right",'width'=>"78px"),//
					array('name'=>'基价核算','class' =>"flex-col text-right",'width'=>"68px"),
					array('name'=>'基价核算金额','class' =>"flex-col text-right",'width'=>"88px"),
	 				array('name'=>'核定件数','class' =>"flex-col text-right",'width'=>"60px"),//
					array('name'=>'核定重量','class' =>"flex-col text-right",'width'=>"64px"),//
					array('name'=>'核定金额','class' =>"flex-col text-right",'width'=>"80px"),//					
					array('name'=>'乙单','class' =>"flex-col",'width'=>"38px"),//
					array('name'=>'高开价','class' =>"flex-col text-right",'width'=>"60px"),
					array('name'=>'车船号','class' =>"flex-col",'width'=>"120px"),
					array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
			);
		}else{
			$tableHeader = array(
					array('name'=>'','class' =>"text-center",'width'=>"20px"),
					array('name'=>'操作','class' =>"",'width'=>"80px"),
					array('name'=>'单号','class' =>"",'width'=>"80px"),
					array('name'=>'状态','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'开单日期','class' =>"flex-col",'width'=>"78px"),
					array('name'=>'客户','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'结算单位','class' =>"flex-col",'width'=>"60px"),
					array('name'=>'销售公司','class' =>"flex-col",'width'=>"60px"),
					array('name'=>'业务员','class' =>"flex-col",'width'=>"48px"),//
// 					array('name'=>'类型','class' =>"flex-col",'width'=>"80px"),//
					array('name'=>'仓库','class' =>"flex-col",'width'=>"58px"),//
					array('name'=>'产地','class' =>"flex-col",'width'=>"58px"),//
					array('name'=>'品名','class' =>"flex-col",'width'=>"48px"),//
					array('name'=>'材质','class' =>"flex-col",'width'=>"60px"),//
					array('name'=>'规格','class' =>"flex-col",'width'=>"36px"),//
					array('name'=>'长度','class' =>"flex-col text-right",'width'=>"34px"),//
					array('name'=>'件数','class' =>"flex-col text-right",'width'=>"34px"),//
					array('name'=>'重量','class' =>"flex-col text-right",'width'=>"60px"),//
					array('name'=>'单价','class' =>"flex-col text-right",'width'=>"48px"),
					array('name'=>'金额','class' =>"flex-col text-right",'width'=>"78px"),//
					array('name'=>'乙单','class' =>"flex-col",'width'=>"38px"),//
					array('name'=>'高开价','class' =>"flex-col text-right",'width'=>"56px"),
// 					array('name'=>'费用','class' =>"flex-col",'width'=>"100px"),//
// 					array('name'=>'配送件数','class' =>"flex-col text-right",'width'=>"80px"),//
// 					array('name'=>'出库件数','class' =>"flex-col text-right",'width'=>"80px"),//
// 					array('name'=>'仓库出库件数','class' =>"flex-col text-right",'width'=>"110px"),//
					array('name'=>'核定重量','class' =>"flex-col text-right",'width'=>"64px"),//
// 					array('name'=>'核定单价','class' =>"flex-col",'width'=>"100px"),//
					array('name'=>'核定金额','class' =>"flex-col text-right",'width'=>"80px"),//
// 					array('name'=>'收款金额','class' =>"flex-col",'width'=>"100px"),//
// 					array('name'=>'差额','class' =>"flex-col text-right",'width'=>"100px"),//
// 					array('name'=>'已结算金额','class' =>"flex-col",'width'=>"100px"),//
// 					array('name'=>'业务组','class' =>"flex-col",'width'=>"80px"),//
// 					array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
// 					array('name'=>'修改人','class' =>"flex-col",'width'=>"60px"),//
// 					array('name'=>'审核人','class' =>"flex-col",'width'=>"60px"),//
// 					array('name'=>'审核时间','class' =>"flex-col",'width'=>"100px"),
					array('name'=>'实时出库件数','class' =>"flex-col text-right",'width'=>"84px"),//
					array('name'=>'实时出库重量','class' =>"flex-col text-right",'width'=>"84px"),//
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
			);
		}

		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("array");
		//联系人
		$contacts_array=CompanyContact::getContactList();
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
		if($_GET["card_no"]){
			$search['keywords'] = $_GET["card_no"];
		}
		if($_GET['id']){
			$search['title_id'] = $_GET["id"];
		}
		if($_GET['cid']){
			$search['customer_id'] = $_GET["cid"];
		}
		if($_GET['start']){
			$search['time_L'] = $_GET["start"];
		}
		if($_GET['end']){
			$search['time_H'] = $_GET["end"];
		}
		if($_GET['form_status']){
			$search['form_status'] = $_GET["form_status"];
		}
		if($_GET['sales_type']){
			$search['sales_type'] = $_GET["sales_type"];
		}
		if($_GET['yidan']){
			$search['is_yidan'] = $_GET["yidan"];
		}
		if($_GET['total']){
			$search['total'] = $_GET["total"];
		}
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		$search=updateSearch($search,'search_sales_index');
		if($search['form_status'] == "delete"){
			array_push($tableHeader,array('name'=>'作废原因','class' =>"flex-col",'width'=>"240px"));
		}
		list($tableData,$pages,$totaldata)=FrmSales::getFormList($search,$type);
		if($search['form_status'] == "delete"){
			array_push($totaldata,'');
		}

		$this->render('index',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'baseform'=>$baseform,
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendor,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'rands'=>$ranks_array,
				'contacts'=>$contacts_array,
				'pages'=>$pages,
				'search'=>$search,
				"totalData"=>$totaldata,
				"msg"=>$msg,
				'type'=>$type,
		));
	}
	
	/*
	 * 销售单导出
	 */
	public function actionSalesExport(){
		$search=$_REQUEST['search'];
		$name = "销售单".date("Y/m/d");
		$title=array("销售单号","开单日期","销售员","结算单位","客户","产地","品名","材质","规格","长度","件数","单位","重量","单价",
				"金额","核定数量","核定金额","已出库","乙单","审核状态","审单状态","提货仓库","类型","业务组","备注",
				"车船号","销售公司","制单人","修改人","审核人","审核时间"
		);
		if($search['form_status'] == "delete"){
			array_push($title,'作废原因');
		}
		$type = $_COOKIE['view'];
		if($type == ''){$type = "index";}
		if(!checkOperation("销售审核视图") && $type=="checkview"){
			$type = "index";
		}
		if(!checkOperation("销售出库视图") && $type=="outview"){
			$type = "index";
		}
		if(!checkOperation("基价核算视图") && $type=="baseview"){
			$type = "index";
		}
		if($type=='baseview')
			$title=array("销售单号","开单日期","销售员","结算单位","客户","产地","品名","材质","规格","长度","件数","单位","重量","单价","基价核算",
					"金额","核定数量","核定金额","已出库","乙单","审核状态","审单状态","提货仓库","类型","业务组","备注",
					"车船号","销售公司","制单人","修改人","审核人","审核时间"
		);
		$content=FrmSales::getAllList($search,$type);
		PHPExcel::ExcelExport($name,$title,$content);	
	}
	/*
	 * 销售单列表
	 * 入库单关联销售单使用
	 */
	public function actionListForSelect()
	{
		$this->layout="";
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'选择','class' =>"sort-disabled",'width'=>"50px"),
				array('name'=>'销售单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'销售件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'销售重量','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'仓库','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"80px"),//
		);
		
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("array");
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableData,$pages)=FrmSales::getFormSimpleList($search,"normal");
	
		$this->renderPartial('listforselect',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'com'=>$com,
				'search'=>$search,
				'pages'=>$pages,
				'vendor'=>$vendor,
				'teams'=>$team_array,
				'warehouse'=>$warehouse_array,
		));
	}
	
	/*
	 * 销售单列表
	 * 先销后进出库单关联销售单使用
	 */
	public function actionXslist()
	{
		$this->layout="";
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'选择','class' =>"sort-disabled",'width'=>"50px"),
				array('name'=>'销售单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'销售件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'销售重量','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'仓库','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"80px"),//
		);
	
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("array");
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableData,$pages)=FrmSales::getFormSimpleList($search,"xxhj");
	
		$this->renderPartial('_xslist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'com'=>$com,
				'search'=>$search,
				'pages'=>$pages,
				'vendor'=>$vendor,
				'teams'=>$team_array,
				'warehouse'=>$warehouse_array,
		));
	}
	
	/*
	 * 销售单列表
	 * 代销销售出库单关联销售单使用
	 */
	public function actionDxlist()
	{
		$this->layout="";
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'选择','class' =>"sort-disabled",'width'=>"50px"),
				array('name'=>'销售单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'销售件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'销售重量','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'仓库','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"80px"),//
		);
	
		//客户
		$vendor=DictCompany::getVendorList("json","is_customer");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("array");
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableData,$pages)=FrmSales::getFormSimpleList($search,"dxxs");
	
		$this->renderPartial('_dxlist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'com'=>$com,
				'search'=>$search,
				'pages'=>$pages,
				'vendor'=>$vendor,
				'teams'=>$team_array,
				'warehouse'=>$warehouse_array,
		));
	}
	
	/*
	 * 销售单列表
	 * 配送单关联销售单使用
	 */
	public function actionSaleslist()
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'选择','class' =>"sort-disabled",'width'=>"50px"),
				array('name'=>'销售单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'客户','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'销售公司','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'销售件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'销售重量','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'仓库','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"80px"),//
		);
		
		if(isset($_REQUEST['page']))
		{
			$search["keywords"]=$_REQUEST['sales'];
			$search["time_L"]=$_REQUEST['mtime_l'];
			$search["time_H"]=$_REQUEST['mtime_h'];
			$search["title_id"]=$_REQUEST['title'];
			$search["customer_id"]=$_REQUEST['custome'];
			$search["team"]=$_REQUEST['team'];
			$search["warehouse"]=$_REQUEST['warehouse'];
		}
		list($tableData,$pages,$totaldata1)=FrmSales::getSendSalesList($search);
		$this->renderPartial('_saleslist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'com'=>$com,
				'search'=>$search,
				'pages'=>$pages,
				'vendor'=>$vendor,
				'teams'=>$team_array,
				'warehouse'=>$warehouse_array,
		));
	}
	
	/*
	 * 根据销售单id，获取明细列表
	 */
	public function actionDetaillist(){
		$id = $_REQUEST["id"];
		$sales = FrmSales::model()->findByPk($id);
		$salesDetails = $sales->salesDetails();
		$baseform = $sales->baseform;
		if($salesDetails){
			foreach($salesDetails as $dt){
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
		$this->renderPartial("sendlist",array(
				'id'=>$id,
				'sales'=>$sales,
				'salesDetails'=>$salesDetails,
				'baseform'=>$baseform,
		));
	}
	/*
	 * 创建表单
	 */
	public function actionCreate()
	{
		if($_POST['CommonForms'])
		{
			$data = FrmSales::createSalse($_POST);
			if($_POST['submit_type'] == 1){
				$form=new Sales($id);
				$result1 = $form->createSubmitForm($data);
				if($result1 === -1){
					$msg = "您刚才提交的销售单库存不足，提交失败，请重新提交";
				}else{
					$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
				}
			}else{
				$allform=new Sales($id);
				$result = $allform->createForm($data);
				if($result){
					$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
				}else{
					$msg = "保存失败";
				}
			}
		}
		$name = $_REQUEST['type'];
		switch ($name) {
			case "normal":
				$name = "新建库存销售单";
				break;
			case "xxhj":
				$name = "新建先销后进销售单";
				break;
			case "dxxs":
				$name = "新建代销销售单";
				break;
			default:
				$name = "";
			break;
		}
		$this->pageTitle = $name;
		$baseform=new CommonForms();
		$baseform->unsetAttributes();
		$contract=new FrmPurchaseContract();
		$contract->unsetAttributes();
	
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendors=DictCompany::getVendorList("json","is_customer");
		$gkvendor=DictCompany::getVendorList("json","is_gk");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json");
		//联系人
		$contacts_array=CompanyContact::getContactList();
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		$user_dt = currentUserId();
		
		$this->render('create',array(
			'baseform'=>$baseform,
			'backUrl'=>'index',
			'users'=>$user_array,
			'vendors'=>$vendors,
			'coms'=>$com,
			'teams'=>$team_array,
			'warehouses'=>$warehouse_array,
			'product'=>$products_array,
			'material'=>$textures_array,
			'brand'=>$brands_array,
			'type'=>$ranks_array,
			'contacts'=>$contacts_array,
			'gkvendor'=>$gkvendor,
			'user_dt'=>$user_dt,
			"msg"=>$msg,
		));
	}
	
	/*
	 * 修改表单
	 */
	public function actionUpdate($id)
	{
		$baseform=CommonForms::model()->with('sales')->findByPk($id);
		$this->pageTitle = "修改销售单 ".$baseform->form_sn;
		
		if($_POST['CommonForms'])
		{
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请重试";
			}else{
				//判断是否已开票
				$noInvoice = FrmSales::CheckInvoice($_POST,$baseform);			
				if($noInvoice){
					$data=FrmSales::getUpdateData($_POST);
					$allform=new Sales($id);
					if($_POST['submit_type'] == 1){
						$result1 = $allform->updateSubmitForm($data);
						if($result1 === -1){
							$msg = "您刚才提交的销售单库存不足，提交失败，请重新提交";
						}elseif($result1=='已开票,不能更改为乙单'){
							$msg='已开票,不能更改为乙单';
						}else{
							$this->redirect(yii::app()->createUrl("FrmSales/index",array('page'=>$_REQUEST['fpage'],"view"=>$_COOKIE["view"])));
						}
					}else{
						$result=$allform->updateForm($data);					
						if($result==='已开票,不能更改为乙单'){						
							$msg=$result;
						}else{
							$this->redirect(yii::app()->createUrl("FrmSales/index",array('page'=>$_REQUEST['fpage'],"view"=>$_COOKIE["view"])));
						}						
					}
				}else{
					$msg = "销售单已开票，不能修改价格";
				}
			}
		}
		//$this->pageTitle = "修改销售单";
		if($baseform)
		{
			$sales=$baseform->sales;
			$details=$sales->salesDetails;
		}else{
			return false;
		}
		if($details){
			foreach($details as $dt){
				//var_dump($dt);die;
				if($dt->card_id){
					$storage = MergeStorage::model()->findByPk($dt->card_id);
					//var_dump($storage);die;
					$dt->cost_price = $storage->cost_price;
					if($baseform->form_status == "unsubmit"){
						$dt->can_surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
					}else{
						$dt->can_surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount + $dt->amount;
					}
					if($dt->can_surplus > 100){
						$dt->surplus = 100;
					}else{
						$dt->surplus = $dt->can_surplus;
					}
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
			}
		}
	
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendors=DictCompany::getVendorList("json","is_customer");
		$gkvendor=DictCompany::getVendorList("json","is_gk");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json");
		//联系人
		$contacts_array=CompanyContact::getContactList($sales->customer_id);
		//根据品名，规格，材质，产地来选择商品
		$id_product= array();
		$id_texture= array();
		$id_brand= array();
		$id_rank= array();
		if($details){
			foreach($details as $li){
				array_push($id_product,$li->product_id);
				array_push($id_texture,$li->texture_id);
				array_push($id_brand,$li->brand_id);
				array_push($id_rank,$li->rank_id);
			}
		}
		//1品名
		$products_array=DictGoodsProperty::getProList('product',"array",$id_product);
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture',"array",$id_texture);
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json",$id_brand);
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank',"array",$id_rank);
		$super = checkOperation("销售单超级权限");
		
		$this->render('update',array(
				'baseform'=>$baseform,
				'sales'=>$sales,
				'details'=>$details,
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendors,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'product'=>$products_array,
				'material'=>$textures_array,
				'brand'=>$brands_array,
				'type'=>$ranks_array,
				'contacts'=>$contacts_array,
				'gkvendor'=>$gkvendor,
				'msg'=>$msg,
				'super'=>$super,
		));
	}
	
	/*
	 * 创建代销表单
	 */
	public function actionDxcreate()
	{
		if($_POST['CommonForms'])
		{
			$data = FrmSales::createSalse($_POST);
			if($_POST['submit_type'] == 1){
				$form=new Sales($id);
				$result1 = $form->createSubmitForm($data);
				if($result1 === -1){
					$msg = "您刚才提交的销售单库存不足，提交失败，请重新提交";
				}else{
					$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
				}
			}else{
				$allform=new Sales($id);
				$result = $allform->createForm($data);
				if($result){
					$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
				}
			}
		}
		$name = $_REQUEST['type'];
		switch ($name) {
			case "normal":
				$name = "新建库存销售单";
				break;
			case "xxhj":
				$name = "新建先销后进销售单";
				break;
			case "dxxs":
				$name = "新建代销销售单";
				break;
			default:
				$name = "";
				break;
		}
		$this->pageTitle = $name;
		$baseform=new CommonForms();
		$baseform->unsetAttributes();
		$contract=new FrmPurchaseContract();
		$contract->unsetAttributes();
	
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendors=DictCompany::getVendorList("json","is_customer");
		$gkvendor=DictCompany::getVendorList("json","is_gk");
		$gys=DictCompany::getVendorList("json","is_supply");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json");
		//联系人
		$contacts_array=CompanyContact::getContactList();
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		$user_dt = currentUserId();
	
		$this->render('dxcreate',array(
				'baseform'=>$baseform,
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendors,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'product'=>$products_array,
				'material'=>$textures_array,
				'brand'=>$brands_array,
				'type'=>$ranks_array,
				'contacts'=>$contacts_array,
				'gkvendor'=>$gkvendor,
				'user_dt'=>$user_dt,
				'gys'=>$gys,
		));
	}
	
	/*
	 * 创建先销后进表单
	 */
	public function actionXscreate()
	{
		if($_POST['CommonForms'])
		{
			$data = FrmSales::createSalseXS($_POST);
			if($_POST['submit_type'] == 1){
				$form=new Sales($id);
				$result1 = $form->createSubmitForm($data);
				if($result1 > 0){
					$purchase = FrmSales::getPurchase($result1);
					if($purchase){
						$pur = new Purchase($id);
// 						$pur_result = $pur->createSubmitForm($purchase);
// 						if($pur_result === "dataerror"){
// 							$msg = "自动创建采购单失败";
// 						}else if($pur_result === "morethanneed"){
// 							$msg = "采购数量超过需要补单的数量";
// 						}else{
// 							$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
// 						}
						$pur_result = $pur->createForm($purchase);
						if($pur_result > 0){
							$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
						}else{
							$msg = "自动创建采购单失败";
						}
					}else{
						$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
					}
				}else{
					$msg = "创建失败";
				}
			}else{
				$allform=new Sales($id);
				$result = $allform->createForm($data);
				if($result){
					$purchase = FrmSales::getPurchase($result);
					if($purchase){
						$pur = new Purchase($id);
						$pur_result = $pur->createForm($purchase);
						if($pur_result > 0){
							$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
						}else{
							$msg = "自动创建采购单失败";
						}
					}else{
						$this->redirect(yii::app()->createUrl("FrmSales/index",array("view"=>$_COOKIE["view"])));
					}
				}
			}
		}
		$name = $_REQUEST['type'];
		switch ($name) {
			case "normal":
				$name = "新建库存销售单";
				break;
			case "xxhj":
				$name = "新建先销后进销售单";
				break;
			case "dxxs":
				$name = "新建代销销售单";
				break;
			default:
				$name = "";
				break;
		}
		$this->pageTitle = $name;
		
		$baseform=new CommonForms();
		$baseform->unsetAttributes();
		$contract=new FrmPurchaseContract();
		$contract->unsetAttributes();
	
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$supply=DictCompany::getVendorList('json');//供应商
		$vendors=DictCompany::getVendorList("json","is_customer");
		$gkvendor=DictCompany::getVendorList("json","is_gk");
		
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json",1);
		//联系人
		$contacts_array=CompanyContact::getContactList();
		//根据品名，规格，材质，产地来选择商品
		//1品名
		$products_array=DictGoodsProperty::getProList('product');
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture');
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank');
		$user_dt = currentUserId();
	
		$this->render('xscreate',array(
				'baseform'=>$baseform,
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendors,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'product'=>$products_array,
				'material'=>$textures_array,
				'brands'=>$brands_array,
				'type'=>$ranks_array,
				'contacts'=>$contacts_array,
				'gkvendor'=>$gkvendor,
				'user_dt'=>$user_dt,
				'supply'=>$supply,
		));
	}
	
	
	/*
	 * 修改代销表单
	 */
	public function actionDxupdate($id)
	{
		$baseform=CommonForms::model()->with('sales')->findByPk($id);
		$this->pageTitle = "修改代销销售单 ".$baseform->form_sn;
		
		if($_POST['CommonForms'])
		{
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请重试";
			}else{
				//判断是否已开票
				$noInvoice = FrmSales::CheckInvoice($_POST,$baseform);
				if($noInvoice){
					$data=FrmSales::getUpdateData($_POST);
					$allform=new Sales($id);
					if($_POST['submit_type'] == 1){
						$result1 = $allform->updateSubmitForm($data);
						if($result1 === -1){
							$msg = "您刚才提交的销售单库存不足，提交失败，请重新提交";
						}elseif($result1=='已开票,不能更改为乙单'){
							$msg='已开票,不能更改为乙单';
						}else{
							$this->redirect(yii::app()->createUrl("FrmSales/index",array('page'=>$_REQUEST['fpage'],"view"=>$_COOKIE["view"])));
						}
					}else{
						$result=$allform->updateForm($data);
						if($result==='已开票,不能更改为乙单'){
							$msg=$result;
						}else{
							$this->redirect(yii::app()->createUrl("FrmSales/index",array('page'=>$_REQUEST['fpage'],"view"=>$_COOKIE["view"])));
						}
					}
				}else{
					$msg = "销售单已开票，不能修改价格";
				}
			}
		}
		if($baseform)
		{
			$sales=$baseform->sales;
			$details=$sales->salesDetails;
		}else{
			return false;
		}
		if($details){
			foreach($details as $dt){
				//var_dump($dt);die;
				if($dt->card_id){
					$storage = Storage::model()->findByPk($dt->card_id);
					//var_dump($storage);die;
					$dt->cost_price = $storage->cost_price;
					if($baseform->form_status == "unsubmit"){
						$dt->can_surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount;
					}else{
						$dt->can_surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount + $dt->amount;
					}
					if($dt->can_surplus > 100){
						$dt->surplus = 100;
					}else{
						$dt->surplus = $dt->can_surplus;
					}
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
			}
		}
	
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$vendors=DictCompany::getVendorList("json","is_customer");
		$gkvendor=DictCompany::getVendorList("json","is_gk");
		$gys=DictCompany::getVendorList("json","is_supply");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json");
		//联系人
		$contacts_array=CompanyContact::getContactList($sales->customer_id);
	
		//根据品名，规格，材质，产地来选择商品
		$id_product= array();
		$id_texture= array();
		$id_brand= array();
		$id_rank= array();
		if($details){
			foreach($details as $li){
				array_push($id_product,$li->product_id);
				array_push($id_texture,$li->texture_id);
				array_push($id_brand,$li->brand_id);
				array_push($id_rank,$li->rank_id);
			}
		}
		//1品名
		$products_array=DictGoodsProperty::getProList('product',"array",$id_product);
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture',"array",$id_texture);
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json",$id_brand);
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank',"array",$id_rank);
		$super = checkOperation("销售单超级权限");
		
		$this->render('dxupdate',array(
				'baseform'=>$baseform,
				'sales'=>$sales,
				'details'=>$details,
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendors,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'product'=>$products_array,
				'material'=>$textures_array,
				'brand'=>$brands_array,
				'type'=>$ranks_array,
				'contacts'=>$contacts_array,
				'gkvendor'=>$gkvendor,
				'gys'=>$gys,
				'msg'=>$msg,
				'super'=>$super,
		));
	}
	
	/*
	 * 修改先销后进表单
	 */
	public function actionXsupdate($id)
	{
		$baseform=CommonForms::model()->with('sales')->findByPk($id);
		$this->pageTitle = "修改销售单 ".$baseform->form_sn;
		if($_POST['CommonForms'])
		{
				
			if($_POST['CommonForms']['last_update']!=$baseform->last_update)
			{
				$msg = "您看到的信息不是最新的，请重试";
			}else{
				$noInvoice = FrmSales::CheckInvoice($_POST,$baseform);
				if($noInvoice){
					$data=FrmSales::getUpdateDataXS($_POST);
					$allform=new Sales($id);
					if($_POST['submit_type'] == 1){
						$result1 = $allform->updateSubmitForm($data);
						if($result1 === -1){
							$msg = "提交失败";
						}elseif($result1=='已开票,不能更改为乙单'){
							$msg='已开票,不能更改为乙单';
						}else{
							$this->redirect(yii::app()->createUrl("FrmSales/index",array('page'=>$_REQUEST['fpage'],"view"=>$_COOKIE["view"])));
						}
					}else{
						$result=$allform->updateForm($data);
						if($result==='已开票,不能更改为乙单'){
							$msg=$result;
						}else{
							$this->redirect(yii::app()->createUrl("FrmSales/index",array('page'=>$_REQUEST['fpage'],"view"=>$_COOKIE["view"])));
						}
					}
				}else{
					$msg = "销售单已开票，不能修改价格";
				}
			}
		}
		$page = intval($_REQUEST['fpage']);
		if($baseform)
		{
			$sales=$baseform->sales;
			$details=$sales->salesDetails;
		}else{
			return false;
		}
		
		//表单所属人
		$user_array=User::getUserList();
		//客户
		$supply=DictCompany::getVendorList('json');//供应商
		$vendors=DictCompany::getVendorList("json","is_customer");
		$gkvendor=DictCompany::getVendorList("json","is_gk");
		//采购公司
		$com=DictTitle::getComs("json");
		//业务组
		$team_array=Team::getTeamList("array");
		//仓库
		$warehouse_array=Warehouse::getWareList("json",1);
		//联系人
		$contacts_array=CompanyContact::getContactList($sales->customer_id);
	
		if($details){
			foreach($details as $dt){
				//var_dump($dt);die;
				if($dt->card_id){
					$storage = Storage::model()->findByPk($dt->card_id);
					//var_dump($storage);die;
					$dt->cost_price = $storage->cost_price;
					$dt->surplus = $storage->left_amount - $storage->retain_amount - $storage->lock_amount + $dt->amount;
				}
				$dt->product = DictGoodsProperty::getProName($dt->product_id);
				$dt->rank = DictGoodsProperty::getProName($dt->rank_id);
				$dt->texture = DictGoodsProperty::getProName($dt->texture_id);
				$dt->brand = DictGoodsProperty::getProName($dt->brand_id);
			}
		}
		
		//根据品名，规格，材质，产地来选择商品
		$id_product= array();
		$id_texture= array();
		$id_brand= array();
		$id_rank= array();
		if($details){
			foreach($details as $li){
				array_push($id_product,$li->product_id);
				array_push($id_texture,$li->texture_id);
				array_push($id_brand,$li->brand_id);
				array_push($id_rank,$li->rank_id);
			}
		}
		//1品名
		$products_array=DictGoodsProperty::getProList('product',"array",$id_product);
		//2材质
		$textures_array=DictGoodsProperty::getProList('texture',"array",$id_texture);
		//3产地
		$brands_array=DictGoodsProperty::getProList('brand',"json",$id_brand);
		//4规格
		$ranks_array=DictGoodsProperty::getProList('rank',"array",$id_rank);
		$super = checkOperation("销售单超级权限");
		
		$this->render('xsupdate',array(
				'baseform'=>$baseform,
				'sales'=>$sales,
				'details'=>$details,
				'backUrl'=>'index',
				'users'=>$user_array,
				'vendors'=>$vendors,
				'coms'=>$com,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'product'=>$products_array,
				'material'=>$textures_array,
				'brands'=>$brands_array,
				'type'=>$ranks_array,
				'contacts'=>$contacts_array,
				'gkvendor'=>$gkvendor,
				'msg'=>$msg,
				'supply'=>$supply,
				'super'=>$super,
		));
	}
	
	/*
	 * 表单详情视图
	 */
	public function actionDetail($id)
	{
		//$this->pageTitle = "修改销售单";
		$baseform=CommonForms::model()->with('sales')->findByPk($id);
		$this->pageTitle = "查看销售单 ".$baseform->form_sn;
		$backurl = Yii::app()->createUrl('frmSales/index',array("page"=>$_GET["fpage"],"view"=>$_COOKIE["view"]));
		if ($_GET['backUrl'] && $_GET['backUrl'] == 'varianceReport') 
			$backurl = Yii::app()->createUrl('varianceReport/index', array('page' => $_GET["fpage"]));
		if ($_GET['backUrl'] && $_GET['backUrl'] == 'cancelCheckRecord') 
			$backurl = Yii::app()->createUrl('frmSales/cancelApproveReason', array('page' => $_GET["fpage"]));

		if($baseform)
		{
			$sales=$baseform->sales;
			$details=$sales->salesDetails;
		}else{
			return false;
		}
		if($details){
			foreach($details as $dt){
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
			}
		}
		$this->render('detail',array(
				'baseform'=>$baseform,
				'sales'=>$sales,
				'details'=>$details,
				'backUrl'=>$backurl,
		));
	}
	/*
	 * 提交与取消提交
	 */
	public function actionSubmit($id,$type)
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

		$form=new Sales($id);
		if($type=='submit')
		{
			$result = $form->submitForm();
			if($result === -1){
				echo "库存不足，不能提交";
				die;
			}
		}elseif($type=='cancle')
		{
			$result = $form->cancelSubmitForm();
			if($result === -2){
				echo "请先作废配送单";
				die;
			}
		}
		echo "success";
	}
	
	/*
	 * 审核表单
	 *
	 */
	public function actionCheck($id)
	{
		$type = $_REQUEST["type"];
		$baseform=CommonForms::model()->findByPk($id);
		$sales = $baseform->sales;
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
		$form=new Sales($id);
		if($type=='pass')
		{
			$form->approveForm();
			if($sales->warehouse->is_jxc != 1){
					$sales->can_push = 1;
					$frmSend = FrmSend::model()->findAll("frm_sales_id=".$sales->id.' and status="unpush"');
					if($frmSend && Yii::app()->params['api_switch'] == 1){
						foreach($frmSend as $li){
							FrmSend::BillPush($li->id,"deliveryform","Add");
						}
					}
					if($sales->update()){
						//设置销售单可开票信息
						// $details = $sales->salesDetails;
						// if($details){
						// 	foreach($details as $each){
					 // 			$std = DictGoodsProperty::getFullProName($each->product_id);
					 // 			if($std == "螺纹钢" && $sales->is_yidan == 0 && $sales->is_import == 0){
					 // 				//设置可开票明细
					 // 				$price = $each->fee;
					 // 				$invoice = DetailForInvoice::setSalesInvoice($each->FrmSales->baseform->id,$each->id,$each->weight,$price,$each->FrmSales->title_id,$each->FrmSales->customer_id,$each->FrmSales->client_id);
					 // 				if(!$invoice){
					 // 					echo "设置可开票信息失败";
					 // 					die;
					 // 				}
					 // 			}
					 // 		}
						// }
					}
			}
		}elseif($type=='cancle')
		{
			if($sales->warehouse->is_jxc != 1 ){
					$has = false;
					$inv = false;
					$details=$baseform->sales->salesDetails;
					foreach($details as $li){
						$invoice = DetailForInvoice::model()->find("form_id=".$li->FrmSales->baseform->id." and detail_id=".$li->id);
						if($invoice){
							if($invoice->checked_weight > 0){
								$inv = true;
								break;
							}
						}
						if($li->send_amount >0 ){
							$has = true;
							break;
						}
					}
					if($has){
						echo "销售单已经开出配送单，不能取消审核，请先作废配送单";
						die;
					}
					if($inv){
						echo "销售单已经开票，不能取消审核，请先作废开票";
						die;
					}
					$sales = $baseform->sales;
					$sales->can_push = 0;
					if($sales->update()){
						//删除可开票明细
						// $details = $sales->salesDetails;
						// if($details){
						// 	foreach($details as $each){
						// 		$std = DictGoodsProperty::getFullProName($each->product_id);
						// 		if($std == "螺纹钢" && $sales->is_yidan == 0 && $sales->is_import == 0){
						// 			//设置可开票明细
						// 			$invoice = DetailForInvoice::model()->find("form_id=".$each->FrmSales->baseform->id." and detail_id=".$each->id);
						// 			if($invoice){
						// 				$oldJson = $invoice->datatoJson();
						// 				if($invoice->checked_weight > 0){
						// 					return -1;
						// 				}else{
						// 					$invoice->delete();
						// 					$dataArray = array("tableName"=>"DetailForInvoice","newValue"=>$mainJson,"oldValue"=>$oldJson);
						// 					$base = new BaseForm();
						// 					$base->dataLog($dataArray);
						// 				}
						// 			}
						// 		}
						// 	}
						// }
					}else{
						echo "取消推送失败";die;
					}
			}
// 			$model=FrmPurchase::model()->find("frm_contract_id=".$baseform->id);
// 			if($model){
// 				echo "销售单已经关联采购单，不能取消审核";
// 				die;
// 			}
			$has = false;
			$out = false;
			$inv = false;
			$details=$baseform->sales->salesDetails;
			foreach($details as $li){
				// $invoice = DetailForInvoice::model()->find("form_id=".$li->FrmSales->baseform->id." and detail_id=".$li->id);
				// if($invoice){
				// 	if($invoice->checked_money > 0){
				// 		$inv = true;
				// 		break;
				// 	}
				// }
// 				if($li->send_amount >0 ){
// 					$has = true;
// 					break;
// 				}
				if($li->output_amount >0)
				{
					$out = true;
					break;
				}
			}
// 			if($has){
// 				echo "销售单已经开出配送单，不能取消审核";
// 				die;
// 			}
			if($out){
				echo "销售单已经出库，不能取消审核";
				die;
			}
			if($inv){
				echo "销售单已经开票，不能取消审核";
				die;
			}
			$form->cancelApproveForm();
			
		}elseif($type=='deny')
		{
			$form->refuseForm();
		}
		echo "success";
	}
	
	
	/*
	 * 申请取消审核
	 */
	public function actionApplyCancleCheck()
	{		
		$model=new CanclecheckRecord();
		$model->common_id=$_REQUEST['id'];
		$model->created_by=currentUserId();
		$model->created_time=time();
		if($_REQUEST['reason_val']=='-1')
		{
			$model->reason=$_REQUEST['other_reason'];
		}else{
			$model->reason=FrmSales::$reasons[$_REQUEST['reason_val']];
		}
		if($model->insert()){
			echo '1';
		}
	}
	
	/*
	 * 查看取消审核原因
	 */
	public function actionReasonView($id)
	{
		$sql="select * from canclecheck_record where common_id=".$id."  and status=0 order by id desc";
		$model=CanclecheckRecord::model()->with('user')->findBySql($sql);
		$str='';
		if($model){
			$str.='取消理由:'.$model->reason.'；申请人:'.$model->user->nickname.'；申请时间:'.date('Y-m-d H:i:s',$model->created_time);
		}
		echo $str;
	}
	
	
	
	
	
	public function  actionCheckPurchase()
	{
		$id=$_REQUEST['id'];
		$res=false;
		$connection=Yii::app()->db;
		$sql="select id from sales_detail where frm_sales_id=".$id;
		$res=$connection->createCommand($sql)->queryAll();
		foreach ($res as $each)
		{
			$res=SaledetailPurchase::model()->exists('sales_detail_id='.$each['id']);
	 		if($res)
	 				break;
		}
		if($res)
			echo "已补采购单，不能取消审核";
		else echo 1;		
	}
	
	/*
	 * 获取销售单此前操作
	 */
	public  function actionGetCurrentButton()
	{
		$form_sn=$_REQUEST['form_sn'];
		$str=FrmSales::getButtons($form_sn);
		echo $str;	
	}
	
	
	/*
	 * 作废表单
	 */
	public function actionDeleteform($id)
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
			$form=new Sales($id);
			$return=$form->deleteForm($str);
			if($return===true)
			echo "success";
			else echo $return;
		}
	}
	
	/*
	 * 完成销售单
	 */
	public function actionComplete($id)
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
		if($baseform->form_status != "approve"){
			echo "表单没有审核，不能完成";
			die;
		}
		$form=new Sales($id);
		$result = $form->completeSales();
		if($result&&$result!='已开票'){
			echo "success";
		}elseif($result=="已开票"){
			echo $result;
		}else{
			echo "销售单没有出库,不能完成！";
		}
	}
	
	/*
	 * 取消完成销售单
	 */
	public function actionCancelcomplete($id)
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
		$form=new Sales($id);
		$result = $form->cancelcompleteSales();
		if($result=='已开票'){
			echo $result;
		}else if($result){
			echo "success";
		}else{
			// echo 0;
		}
	}
	

/*
	 * 获取先销后进或代销销售 销售单列表
	 * 供采购处使用
	 */
	public function actionGetSimpleList()
	{
		$type=$_REQUEST['type'];
		//搜索
		$search=array();
		if(isset($_REQUEST['keywords']))
		{
			$search['keywords']=$_REQUEST['keywords'];
			$search['time_L']=$_REQUEST['time_L'];
			$search['time_H']=$_REQUEST['time_H'];
			$search['title_id']=$_REQUEST['title_id'];
			$search['customer_id']=$_REQUEST['customer_id'];
			$search['owned']=$_REQUEST['owned_by'];
		}
		if(isset($_REQUEST['product']))
		{
			$search['confirm_status']=$_REQUEST['confirm_status'];
			$search['team']=$_REQUEST['team'];
			$search['warehouse']=$_REQUEST['warehouse'];
			$search['brand']=$_REQUEST['brand'];
			$search['product']=$_REQUEST['product'];
			$search['rand']=$_REQUEST['rand'];
			$search['texture']=$_REQUEST['texture'];
		}
		list($tableHeader,$tableData,$pages)=FrmSales::getSimpleList($search,$type);
		$this->renderPartial('buyList',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
		));
	}
	
	/*
	 * 获取销售单主体信息
	 */
	public static function actionGetXxhjMainData()
	{
		$id=$_REQUEST['id'];
		$result=FrmSales::getSaleMainData($id);
		echo $result;
	}
	
	/*
	 * 获取先销后进销售单明细
	 */
	public function actionGetXxhjDetail()
	{
		$id=$_REQUEST['id'];
		$result=FrmSales::getXDetailData($id);
		list($id_product,$id_texture,$id_brand,$id_rank)=proListId($result);
		$products_array=DictGoodsProperty::getProList('product','array',$id_product);		//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array',$id_texture);//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json',$id_brand);//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array',$id_rank);//4规格
		$this->renderPartial('simpleDetailList',array(
				'data'=>$result,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'ranks'=>$ranks_array,
		));
	}
	
	/*
	 * 获取代销销售销售单明细的详细内容
	 */
	public function actionGetDxxsDetailCont()
	{
		$id=$_REQUEST['id'];
		$result=SalesDetail::getOne($id);
		$frmSale=$result->FrmSales;
		$detail['product_id']=$result->product_id;
		$detail['texture_id']=$result->texture_id;
		$detail['rank_id']=$result->rank_id;
		$detail['brand_id']=$result->brand_id;
		$detail['length']=$result->length;
		$good_id=DictGoods::getGood($detail);
		$this->renderPartial('salesDetailCont',array(
				'data'=>$result,
				'good_id'=>$good_id,
				'frmSale'=>$frmSale,
		));
	}
	
	/*
	 * 获取代销销售明细的商品id,件数和重量
	 */
	public function actionGetDxxsDetailGoodID()
	{
		$id=$_REQUEST['id'];
		$result=SalesDetail::getOne($id);
		$amount=$result->amount;
		$weight=$result->weight;
		//修改状态为已相关
		$detail['product_id']=$result->product_id;
		$detail['texture_id']=$result->texture_id;
		$detail['rank_id']=$result->rank_id;
		$detail['brand_id']=$result->brand_id;
		$good_id=DictGoods::getGood($detail);
		$str='{good_id:'.$good_id.',amount:'.$amount.',weight:'.$weight.'}';
		echo $str;
	}
	
	/*
	 * 设置代销销售详细的is_related的值
	 */
	public function actionSetDxxsDetailRelate()
	{
		$id=$_REQUEST['id'];
		$value=$_REQUEST['value'];		
		$result=SalesDetail::setIsRelate($id, $value);
		echo $result;	
	}
	
	/*
	 * 根据销售单id获取销售单信息
	 */
	public function actionGetSalesData(){
		$id=$_REQUEST['id'];
		$result=FrmSales::getSalesData($id);
		echo $result;
	}
	
	public function actionTest(){
		FrmSales::setSSDetails(647,648);
	}
	

	/*
	 * 根据销售单id判断销售单是否已经高开登记
	 */
	public function actionGetGaokaiDJ()
	{
		$salesId = $_POST['salesid'];
		$sales = FrmSales::model()->findByPk($salesId);
		$details = $sales->salesDetails;
		$hasHigh = 0;
		$hasyunfei = 0;
		if($details){
			foreach($details as $li){
				if($li->bonus_price > 0){
					$high = HighOpen::model()->find("sales_detail_id=".$li->id);
					if($high)
					{
						$base = $high->baseform;
						$relation = FormBillRelation::model()->find("common_id=".$base->id);
						if($relation){
							$hasHigh = 1;
							break;
						}
					}
				}
			}
		}
		$bill = BillRecord::model()->with("baseform")->findAll("frm_common_id=".$sales->baseform->id." and baseform.form_status<>'delete'");
		if($bill){
			$hasyunfei = 1;
		}
		if($hasHigh == 1)
		{
			echo "hasHigh";
		}else if($hasyunfei == 1){
			echo "hasYf";
		}else{
			echo "no";
		}
	}
	
	

	
	/**
	 * 销售汇总
	 */
	public function actionTotalData(){
		$this->pageTitle = "销售汇总";
		
		$tableHeader = array(
				array('name'=>'操作','class' =>"",'width'=>"60px"),
				array('name'=>'客户','class' =>"",'width'=>"110px"),//
				array('name'=>'销售余额','class' =>"text-right",'width'=>"100px"),//
				array('name'=>'销售公司','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'金额','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'未开票重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'未开票金额','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'已出库件数','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'已出库重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'应开票重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'应开票金额','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'折让金额','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'高开重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'高开金额','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'运费','class' =>"flex-col text-right",'width'=>"100px"),//
			);
		$user_array=User::getUserList();
		$vendor=DictCompany::getVendorList("json","is_customer");
		$coms=DictTitle::getComs("json");
		$brands_array=DictGoodsProperty::getProList('brand',"json");
		
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		
		//获取表单列表
		list($tableData,$pages,$totaldata)=FrmSales::getTotal($search);
	
		$this->render('totaldata',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'coms'=>$coms,
				'vendor'=>$vendor,
				'brands'=>$brands_array,
				'totaldata'=>$totaldata,
				'users'=>$user_array,
		));
	}
	
	/**
	 * 销售明细汇总
	 */
	public function actionTotalDetails(){
		$this->pageTitle = "销售明细";
	
		$tableHeader = array(
				array('name'=>'销售公司','class' =>"",'width'=>"110px"),
				array('name'=>'客户','class' =>"",'width'=>"110px"),//
				array('name'=>'单号','class' =>"",'width'=>"120px"),
				array('name'=>'状态','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'开单日期','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'乙单','class' =>"flex-col",'width'=>"50px"),//
				array('name'=>'类型','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'品名','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"50px"),//
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'金额','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'高开单价','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'运费','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'已出库件数','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'已出库重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'未开票重量','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'未开票金额','class' =>"flex-col text-right",'width'=>"100px"),//
		);
	
		$vendor=DictCompany::getVendorList("json","is_customer");
		$coms=DictTitle::getComs("json");
		$brands_array=DictGoodsProperty::getProList('brand',"json");
	
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableData,$pages)=FrmSales::getTotalDetails($search);
	
		$this->render('totaldetails',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'coms'=>$coms,
				'vendor'=>$vendor,
				'brands'=>$brands_array,
		));
	}
	
	/*
	 * 获取锁定库存销售明细列表
	 */
	public function actionLockList()
	{
		$this->pageTitle = "库存锁定";
		
		$storage_id = $_REQUEST["storage_id"];
		$tableHeader = array(
				array('name'=>'','class' =>"",'width'=>"30px"),
				array('name'=>'单号','class' =>"",'width'=>"105px"),
				array('name'=>'状态','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'开单日期','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'客户','class' =>"flex-col",'width'=>"110px"),//
				array('name'=>'销售公司','class' =>"flex-col",'width'=>"110px"),
				array('name'=>'业务员','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'乙单','class' =>"flex-col",'width'=>"50px"),//
				array('name'=>'类型','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'产地','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'品名','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'材质','class' =>"flex-col",'width'=>"80px"),//
				array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"50px"),//
				array('name'=>'仓库','class' =>"flex-col",'width'=>"100px"),//
				array('name'=>'单价','class' =>"flex-col text-right",'width'=>"100px"),
				array('name'=>'件数','class' =>"flex-col text-right",'width'=>"60px"),//
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'金额','class' =>"flex-col text-right",'width'=>"100px"),//
				array('name'=>'锁定件数','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'配送件数','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'出库件数','class' =>"flex-col text-right",'width'=>"80px"),//
				array('name'=>'业务组','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'制单人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'修改人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'审核人','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'审核时间','class' =>"flex-col",'width'=>"100px"),
				array('name'=>'备注','class' =>"flex-col",'width'=>"240px"),
		);
		
		//搜索和换页
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableData,$pages,$totaldata)=FrmSales::getLockList($search,$storage_id);
		if($search['form_status'] == "delete"){
			array_push($totaldata,'');
		}
		$this->render('locklist',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'baseform'=>$baseform,
				'pages'=>$pages,
				'search'=>$search,
				"totalData"=>$totaldata,
				"msg"=>$msg,
				"storage_id"=>$storage_id,
		));
	}
	
	/*
	 * 设置销售单可推送状态
	 */
	public function actionPush($id)
	{
		$baseform=CommonForms::model()->findByPk($id);
		if($baseform){
			$last_update=$_REQUEST['last_update'];
			if($last_update!=$baseform->last_update)
			{
				echo "您看到的信息不是最新的，请刷新后再试";
				die;
			}
		}else{
			return false;
		}
		$sales = $baseform->sales;
		if($sales->can_push == 1){
			echo "销售单已推送，无需重复推送";
			die;
		}
		$oldJson = $sales->datatoJson();
		$sales->can_push = 1;
		$sales->update();
		$transaction=Yii::app()->db->beginTransaction();
		try {
			
			$frmSend = FrmSend::model()->findAll("frm_sales_id=".$sales->id.' and status="unpush"');
			if($frmSend && Yii::app()->params['api_switch'] == 1){
				foreach($frmSend as $li){
					FrmSend::BillPush($li->id,"deliveryform","Add");
				}
			}
// 			if($sales->update()){
// 				$mainJson = $sales->datatoJson();
// 				$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 				$base = new BaseForm();
// 				$base->dataLog($dataArray);
				//设置销售单可开票信息
				// $details = $sales->salesDetails;
				// if($details){
				// 	foreach($details as $each){
			 // 			$std = DictGoodsProperty::getFullProName($each->product_id);
			 // 			if($std == "螺纹钢" && $sales->is_yidan == 0 && $sales->is_import == 0){
			 // 				//设置可开票明细
			 // 				$price = $each->fee;
			 // 				$invoice = DetailForInvoice::setSalesInvoice($each->FrmSales->baseform->id,$each->id,$each->weight,$price,$each->FrmSales->title_id,$each->FrmSales->customer_id,$each->FrmSales->client_id);
			 // 				if(!$invoice){
			 // 					throw new CException("设置可开票信息失败");
			 // 				}
			 // 			}
			 // 		}
				// }
				
// 			}else{
// 				throw new CException("提交失败");
// 			}
		$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			$sales->can_push=0;
			$sales->update();
			echo $e->message;
			die;
		}
		$mainJson = $sales->datatoJson();
		$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
		$base = new BaseForm();
		$base->dataLog($dataArray);
		echo "success";
	}
	
	/*
	 * 取消推送表单
	 */
	public function actionCancelpush($id)
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
			if($baseform->sales->warehouse_amount>0)
			{
				echo "已经出库,不能取消推送";
				die;
			}
		}else{
			return false;
		}
		
		$transaction=Yii::app()->db->beginTransaction();
		try {
			
			$has = false;
			$inv = false;
			$details=$baseform->sales->salesDetails;
			foreach($details as $li){
				$invoice = DetailForInvoice::model()->find("form_id=".$li->FrmSales->baseform->id." and detail_id=".$li->id);
				if($invoice){
					if($invoice->checked_weight > 0){
						$inv = true;
						break;
					}
				}
				if($li->send_amount >0 ){
					$has = true;
					break;
				}
			}
			if($has){
				throw new CException("销售单已经开出配送单，不能取消推送，请先作废配送单");
			}
			if($inv){
				throw new CException("销售单已经开票，不能取消推送，请先作废开票");
			}
			$sales = $baseform->sales;
			$oldJson = $sales->datatoJson();
			$sales->can_push = 0;
			if($sales->update()){
				$mainJson = $sales->datatoJson();
				$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$base = new BaseForm();
				$base->dataLog($dataArray);
				//删除可开票明细
				// $details = $sales->salesDetails;
				// if($details){
			 // 		foreach($details as $each){
			 // 			$std = DictGoodsProperty::getFullProName($each->product_id);
			 // 			if($std == "螺纹钢" && $sales->is_yidan == 0 && $sales->is_import == 0){
			 // 				//设置可开票明细
			 // 				$invoice = DetailForInvoice::model()->find("form_id=".$each->FrmSales->baseform->id." and detail_id=".$each->id);
			 // 				if($invoice){
			 // 					$oldJson = $invoice->datatoJson();
			 // 					if($invoice->checked_weight > 0){
			 // 						throw new CException("销售单已经开票，不能取消推送，请先作废开票");
			 // 					}else{
			 // 						$invoice->delete();
			 // 						$dataArray = array("tableName"=>"DetailForInvoice","newValue"=>$mainJson,"oldValue"=>$oldJson);
			 // 						$base = new BaseForm();
				// 					$base->dataLog($dataArray);
			 // 					}
			 // 				}
			 // 			}
			 // 		}
				// }
			}else{
				throw new CException("取消推送失败");
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
	

	public function actionPrint($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$model = $baseform->sales;
		$details = $model->salesDetails;
		
		$this->renderPartial('print', array(
				'baseform' => $baseform, 
				'model' => $model, 
				'details' => $details,
		));
	}
	
	//获取业务员一月内的销售总重
	public function actionGetUserWeight(){
		$post = $_POST;
		$result = FrmSales::GetUserWeight($post);
		echo $result;
	}
	
	//打印预览
	public function actionPreview($id){
		$this->pageTitle = "打印预览";
		$baseform = CommonForms::model()->findByPK($id);
		$model = $baseform->sales;
		$details = $model->salesDetails;
		$this->renderPartial('preview', array(
				'baseform' => $baseform,
				'model' => $model,
				'details' => $details,
		));
	}
	
	//随机创建销售单数据
	public function actionRandSales(){
		$num = 0;
		while(true){
			$result = FrmSales::RandSales();
			if($result){
				$num ++;
				if($num>=1000){
					break;
				}
			}
		}
	}
	//获取收款简单信息
	public function actionGetSimIn($id)
	{
		$model=FrmSales::model()->findByPk($id);
		$return=array();
		if($model)
		{
			$return['title_id']=$model->title_id;
			$return['title_name']=DictTitle::getName($model->title_id);
			$return['customer_name']=DictCompany::getName($model->customer_id);
			$return['customer_id']=$model->customer_id;
			$return['client_name']=DictCompany::getName($model->client_id);
			$return['client_id']=$model->client_id;
			echo json_encode($return);
		}		
	}
	
	public function actionCancelApproveReason()
	{
		$this->pageTitle = "取消审核原因";
		$tableHeader=array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"30px"),
				array('name'=>'单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'表单状态','class' =>"flex-col sort-disabled",'width'=>"60px"),
				array('name'=>'取消审核原因','class' =>"flex-col sort-disabled",'width'=>"120px"),
				array('name'=>'当前状态','class' =>"flex-col sort-disabled",'width'=>"60px"),
				array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"60px"),
				array('name'=>'申请人','class' =>"flex-col sort-disabled",'width'=>"60px"),
				array('name'=>'申请时间','class' =>"flex-col sort-disabled",'width'=>"80px")
		);

		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		$search = updateSearch($search, 'search_cancel_record');	
		list($tableData,$pages)=CanclecheckRecord::getCancelList($search);
		$user_array=User::getUserList();

		$this->render('cancelList',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'coms'=>$coms,			
				'vendors'=>$vendor_array,				
				'users'=>$user_array,
				'search'=>$search
		));
	}
}
