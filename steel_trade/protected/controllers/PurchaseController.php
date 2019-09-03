<?php
class PurchaseController extends AdminBaseController
{
	private $v = array(
		'index' => "采购普通视图", 
		'indexForStore' => "采购配送视图", 
		'indexForCheck' => "采购审核视图"
	);

	public function actionView($id) 
	{
		$baseform=CommonForms::model()->with('purchase','purchase.purchaseDetails','purchase.purchaseDetails.salesDetailXxhj')->findByPk($id);
		if($baseform)
		{
			$this->pageTitle = "查看采购单".$baseform->form_sn;
			$purchase=$baseform->purchase;
			$details=$purchase->purchaseDetails;
		}else{
			return false;
		}
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		$backUrl=$_REQUEST['backUrl'];
		$arr=array('baseform'=>$baseform,'purchase'=>$purchase,'details'=>$details,'fpage'=>$fpage,'backUrl'=>$backUrl);
		if($_REQUEST['type']=='normal'||$_REQUEST['type']=='tpcg')
		{
			$url='view';
		}else{
			$url='viewxxhj';
		}
		$this->render($url,$arr);
	}
	
	public function actionIndex()
	{
		if(!checkOperation("采购普通视图")&&!checkOperation("采购配送视图")&&!checkOperation("采购审核视图"))
		{
			return false;
		}else{
			$v=array('index'=>'采购普通视图','indexForStore'=>'采购配送视图','indexForCheck'=>'采购审核视图');
			if(!$_REQUEST['exact']&&$_COOKIE['purchase_view']!='index'&&$_COOKIE['purchase_view']&&checkOperation($v[$_COOKIE['purchase_view']]))
			{
				$this->redirect(Yii::app()->createUrl('purchase/'.$_COOKIE['purchase_view']));
			}else{
				if(!checkOperation("采购普通视图"))
				{
					if(checkOperation("采购配送视图"))
					{
						$this->redirect(Yii::app()->createUrl('purchase/indexForStore'));
					}else{
						$this->redirect(Yii::app()->createUrl('purchase/indexForCheck'));
					}
				}
			}
		}		
		$this->pageTitle = "采购管理";
		$this->setHome = 1;//允许设为首页
		$coms=DictTitle::getComs('json');
		$vendor_array=DictCompany::getVendorList('json');		
		$user_array=User::getUserList();
		$team_array=Team::getTeamList('array');		
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=sortRank(DictGoodsProperty::getProList('rank','array','all'));//4规格
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['title']))
		{
			$data=json_decode($_REQUEST['search_data']);
			$search['company']=$_REQUEST['title'];
			$search['vendor']=$_REQUEST['company'];
			//获取合同列表
			$arr=FrmPurchaseContract::getIdArray($data->time_L,$data->time_H,$search['company'],$search['vendor']);
			$search['contract_array']=$arr;
		}elseif(isset($_REQUEST['card_no'])){
			$search['keywords']=$_REQUEST['card_no'];
		}elseif(isset($_REQUEST['search_url']))
		{
			$search=(Array)json_decode($_REQUEST['search_url']);
		}
		$search=updateSearch($search,'search_purchase_index');
		list($tableHeader,$tableData,$pages,$totalData)=FrmPurchase::getPurchseList($search);					
		$this->render('index',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
				'coms'=>$coms,
				'search'=>$search,
				'page'=>$page,
				'pages'=>$pages,
				'records'=>$records,
				'pageCount'=>$pageCount,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'teams'=>$team_array,
				'users'=>$user_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,		
				'textures'=>$textures_array,
		));
	}	
	
	/*
	 * 入库相关列表
	 */
	public function actionIndexForStore()
	{
		if(!checkOperation("采购配送视图")){
			return false;
		}
		$this->pageTitle = "采购管理";
		$this->setHome = 1;//允许设为首页
		$coms=DictTitle::getComs('json');
		$vendor_array=DictCompany::getVendorList('json');
		$user_array=User::getUserList();
		$team_array=Team::getTeamList('array');
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=sortRank(DictGoodsProperty::getProList('rank','array','all'));//4规格
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];			
		}elseif(isset($_REQUEST['title']))
		{
			$data=json_decode($_REQUEST['search_data']);
			$search['company']=$_REQUEST['title'];
			$search['vendor']=$_REQUEST['company'];
			//获取合同列表
			$arr=FrmPurchaseContract::getIdArray($data->time_L,$data->time_H,$search['company'],$search['vendor']);
			$search['contract_array']=$arr;
		}elseif(isset($_REQUEST['card_no'])){
			$search['keywords']=$_REQUEST['card_no'];
		}elseif(isset($_REQUEST['search_url']))
		{
			$search=(Array)json_decode($_REQUEST['search_url']);
		}
		$search=updateSearch($search,'search_purchase_store');
		list($tableHeader,$tableData,$pages,$totalData)=FrmPurchase::getPurchseListForStore($search);
		$this->render('index_store',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
				'coms'=>$coms,
				'search'=>$search,
				'page'=>$page,
				'pages'=>$pages,
				'records'=>$records,
				'pageCount'=>$pageCount,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'teams'=>$team_array,
				'users'=>$user_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
		));
	}
	
	/*
	 * 审核相关列表
	 */
	public function actionIndexForCheck()
	{
		if(!checkOperation("采购审核视图")){
			return false;
		}
		$this->pageTitle = "采购管理";
		$this->setHome = 1;//允许设为首页
		$coms=DictTitle::getComs('json');
		$vendor_array=DictCompany::getVendorList('json');
		$user_array=User::getUserList();
		$team_array=Team::getTeamList('array');
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=sortRank(DictGoodsProperty::getProList('rank','array','all'));//4规格
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['title']))
		{
			$data=json_decode($_REQUEST['search_data']);
			$search['company']=$_REQUEST['title'];
			$search['vendor']=$_REQUEST['company'];
			//获取合同列表
			$arr=FrmPurchaseContract::getIdArray($data->time_L,$data->time_H,$search['company'],$search['vendor']);
			$search['contract_array']=$arr;
		}elseif(isset($_REQUEST['card_no'])){
			$search['keywords']=$_REQUEST['card_no'];
		}elseif(isset($_REQUEST['search_url']))
		{
			$search=(Array)json_decode($_REQUEST['search_url']);
		}
		$search=updateSearch($search,'search_purchase_check');
		list($tableHeader,$tableData,$pages,$totalData)=FrmPurchase::getPurchseListForCheck($search);
		$this->render('index_check',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
				'coms'=>$coms,
				'search'=>$search,
				'page'=>$page,
				'pages'=>$pages,
				'records'=>$records,
				'pageCount'=>$pageCount,
				'vendors'=>$vendor_array,
				'brands'=>$brands_array,
				'teams'=>$team_array,
				'users'=>$user_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
		));
	}
	
	/*
	 * 创建采购表单
	 */
	public function actionCreate()
	{
		$name = $_REQUEST['type'];
		switch ($name) {
			case "normal":
				$name = "新建库存采购单";
				break;
			case "xxhj":
				$name = "新建直销采购单";
				break;
			case "dxcg":
				$name = "新建代销采购单";
				break;
			default:
				$name = "";
				break;
		}
		$this->pageTitle = $name;		
		$msg='';
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;		
		if($_POST['CommonForms'])
		{
			$search_url=$_REQUEST['search_url'];
			$data=FrmPurchase::getInputData($_POST);
			if($data){
				//判断状态
				$allform=new Purchase($id);
				if($data['common']->submit=="yes")
				{
					$result=$allform->createSubmitForm($data);
					if($result!==true){
						if($result=='dataerror')
						{
							$msg= '数据错误';
						}elseif($result=='morethanneed'){
							$msg= '采购数量超过需要补单的数量';
						}elseif($result=='billchange'){
							$msg='关联的销售单有所变更';
						}else{
							$msg= '未知错误';
						}
					}else{
						$this->redirect(Yii::app()->createUrl('purchase/'.($_COOKIE['purchase_view']?$_COOKIE['purchase_view']:'index'),array("page"=>$fpage)));
					}			
				}else{
					$allform->createForm($data);
					$this->redirect(Yii::app()->createUrl('purchase/'.($_COOKIE['purchase_view']?$_COOKIE['purchase_view']:'index'),array("page"=>$fpage)));
				}				
			}
		}		
		$user_array=User::getUserList();//表单所属人
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$ven_array=DictCompany::getVendorList('json','is_customer');//先销后进采购公司		
		$tpcompany=DictCompany::getVendorList('array',"is_pledge");//托盘公司
		$com_array=DictTitle::getComs('json');//采购公司
		$team_array=Team::getTeamList('array');//业务组
		$warehouse_array=Warehouse::getWareList('json');//仓库
		$warehouse_array1=Warehouse::getWareList('array');
		//$contacts_array=CompanyContact::getContactList();//联系人
		//根据品名，规格，材质，产地来选择商品
		$products_array=DictGoodsProperty::getProList('product');		//1品名
		$textures_array=DictGoodsProperty::getProList('texture');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json');//3产地
		$ranks_array=DictGoodsProperty::getProList('rank');//4规格
		
		//获取开票成本
// 		$invoice_cost=SysConfig::getConfigValue('invoice');
		
		if($_REQUEST['type']=='normal'||$_REQUEST['type']=='tpcg')
		{
			$id=$_REQUEST['id'];
			$baseform=CommonForms::model()->with('contract','contract.purchaseContractDetails')->findByPk($id);
			$contract=$baseform->contract;
			$contractDetails=$contract->purchaseContractDetails;
			$url='create';
			$arr=array(
					'baseform'=>$baseform,
					'contract'=>$contract,
					'details'=>$contractDetails,
					'users'=>$user_array,
					'vendors'=>$vendor_array,
					'coms'=>$com_array,
					'teams'=>$team_array,
					'warehouses'=>$warehouse_array,
					'products'=>$products_array,
					'textures'=>$textures_array,
					'brands'=>$brands_array,
					'ranks'=>$ranks_array,
					'contacts'=>$contacts_array,
					'tpcompanys'=>$tpcompany,
					'invoice_cost'=>$invoice_cost,
					'fpage'=>$fpage,
					'msg'=>$msg
			);
		}elseif($_REQUEST['type']=='xxhj'){
			$comm_id=$_REQUEST['comm_id'];
			$comm_sn=CommonForms::model()->findByPk($comm_id)->form_sn;
			$url='createxxhj';
			$warehouse_array=Warehouse::getWareList('json','1');//仓库
			$arr=array(
					'users'=>$user_array,
					'vendors'=>$vendor_array,
					'coms'=>$com_array,
					'teams'=>$team_array,
					'warehouses'=>$warehouse_array,
					'products'=>$products_array,
					'textures'=>$textures_array,
					'brands'=>$brands_array,
					'ranks'=>$ranks_array,
					'contacts'=>$contacts_array,
					'vens'=>$ven_array,
					'fpage'=>$fpage,
					'comm_id'=>$comm_id,
					'comm_sn'=>$comm_sn,
					'invoice_cost'=>$invoice_cost,
					'msg'=>$msg
			);
		}
		$this->render($url,$arr);
	}
	
	/*
	 * 创建代销采购第一步
	 */
	public function actionCreateDxcgStepOne()
	{
		$this->pageTitle = "新建代销采购单";		
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;		
		$user_array=User::getUserList();//表单所属人
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$ven_array=DictCompany::getVendorList('json','is_customer');//先销后进采购公司
		$tpcompany=DictCompany::getVendorList('array',"is_pledge");//托盘公司
		$com_array=DictTitle::getComs('json');//采购公司
		$team_array=Team::getTeamList('array');//业务组
		$warehouse_array=Warehouse::getWareList('json');//仓库
		$warehouse_array1=Warehouse::getWareList('array');
// 		$contacts_array=CompanyContact::getContactList();//联系人
		//根据品名，规格，材质，产地来选择商品
		$products_array=DictGoodsProperty::getProList('product');		//1品名
		$textures_array=DictGoodsProperty::getProList('texture');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json');//3产地
		$ranks_array=sortRank(DictGoodsProperty::getProList('rank'));//4规格
		$msg='';
		if(isset($_POST['td_products']))
		{
			if($_POST['CommonForms'])
			{
				$data=FrmPurchase::getInputData($_POST);
				if($data){
					$allform=new Purchase($id);
					if($data['common']->submit=="yes")
					{
						$result=$allform->createSubmitForm($data);
						if($result!==true){
							if($result=='dataerror')
							{
								$msg= '数据错误';
							}elseif($result=='morethanneed'){
								$msg= '采购数量超过需要补单的数量';
							}else{
								$msg= '未知错误';
							}
						}else{
							$this->redirect(Yii::app()->createUrl('purchase/'.$_COOKIE['purchase_view'],array("page"=>$fpage)));
						}
					}else{
						$allform->createForm($data);
						$this->redirect(Yii::app()->createUrl('purchase/'.$_COOKIE['purchase_view'],array("page"=>$fpage)));
					}					
				}	
			}else{
				//数据整合
				list($details_array,$detail_ids,$zipped_array)=FrmPurchase::getInputData($_POST);
				$arr=json_decode($details_array);
				$mainInfo=FrmSales::giveYouMainInfo($arr[0]->detail_id);
			}
			if($msg)goto again;
			//获取开票成本
// 			$invoice_cost=SysConfig::getConfigValue('invoice');
			//然后跳转下一步			
			$this->render('createdxcg2',array(
					'type'=>'dxcg',
					'mainInfo'=>$mainInfo,
					'details_array'=>$details_array,
					'detail_ids'=>$detail_ids,
					'zipped_array'=>$zipped_array,
					'users'=>$user_array,
					'vendors'=>$vendor_array,
					'coms'=>$com_array,
					'teams'=>$team_array,
					'warehouses'=>$warehouse_array,					
					'contacts'=>$contacts_array,
					'vens'=>$ven_array,
					"fpage"=>$fpage,
					'invoice_cost'=>$invoice_cost,
					'msg'=>$msg
			));
			die;
		}
		again :
		$this->render('createdxcg1',array(
				'users'=>$user_array,
				'vendors'=>$vendor_array,
				'coms'=>$com_array,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'ranks'=>$ranks_array,
				'contacts'=>$contacts_array,
				'vens'=>$ven_array,
				'warehouses1'=>$warehouse_array1,
				"fpage"=>$fpage,
				'msg'=>$msg
		));
	}
	/*
	 * 更新采购单
	 */
	public function actionUpdate($id)
	{
		$baseform=CommonForms::model()->with('purchase','purchase.purchaseDetails','purchase.purchaseDetails.salesDetailXxhj')->findByPk($id);
		if($baseform)
		{
			$purchase=$baseform->purchase;
			$details=$purchase->purchaseDetails;
		}else{
			return false;
		}
		$this->pageTitle = "修改采购单".$baseform->form_sn;
		$last_update=$_REQUEST['last_update'];
		if($last_update!=$baseform->last_update)
		{
			echo "<script>alert('您看到的信息不是最新的，请刷新后再试');setTimeout('',500);window.location.href='/index.php/purchase/index';</script>";
			die;
		}
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		$msg='';
		if($_POST['CommonForms'])
		{
			$data=FrmPurchase::getUpdateData($_POST);
			if($data){
				$allform=new Purchase($id);
				if($data['common']->submit=="yes")
				{
					$result=$allform->updateSubmitForm($data);
					if($result!==true){
						if($result=='dataerror')
						{
							$msg= '数据错误';
						}elseif($result=='morethanneed'){
							$msg= '采购数量超过需要补单的数量';
						}else{
							$msg= '未知错误';
						}
					}else{
						$this->redirect(Yii::app()->createUrl('purchase/'.$_COOKIE['purchase_view'],array("page"=>$fpage)));
					}
				}else{
					$allform->updateForm($data);
					$this->redirect(Yii::app()->createUrl('purchase/'.$_COOKIE['purchase_view'],array("page"=>$fpage)));
				}							
			}
		}
		$backUrl=$_REQUEST['backUrl'];
		$user_array=User::getUserList();//表单所属人
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$ven_array=DictCompany::getVendorList('json','is_customer');//先销后进采购公司
		$tpcompany=DictCompany::getVendorList('array',"is_pledge");//托盘公司
		$com_array=DictTitle::getComs('json');//采购公司
		$team_array=Team::getTeamList('array');//业务组
		$warehouse_array=Warehouse::getWareList('json');//仓库
// 		$contacts_array=CompanyContact::getContactList();//联系人
		//根据品名，规格，材质，产地来选择商品
		list($id_product,$id_texture,$id_brand,$id_rank)=proListId($details);
		$products_array=DictGoodsProperty::getProList('product','array',$id_product);		//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array',$id_texture);//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json',$id_brand);//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array',$id_rank);//4规格
		
		
		if($_REQUEST['type']=='normal'||$_REQUEST['type']=='tpcg')
		{
			if($baseform->form_status=='unsubmit'){
				$url='update';
			}elseif($baseform->form_status=='submited'){
				$url='sub_update';
			}elseif($baseform->form_status=='approve'){
				$url='app_update';
			}			
			$arr=array('users'=>$user_array,'vendors'=>$vendor_array,'coms'=>$com_array,'teams'=>$team_array,'warehouses'=>$warehouse_array,
					'products'=>$products_array,'textures'=>$textures_array,'brands'=>$brands_array,'ranks'=>$ranks_array,'contacts'=>$contacts_array,
					'tpcompanys'=>$tpcompany,'baseform'=>$baseform,'purchase'=>$purchase,'details'=>$details,'fpage'=>$fpage,'backUrl'=>$backUrl);
		}elseif($_REQUEST['type']=='xxhj'){			
			if($baseform->form_status=='unsubmit'){
				$url='updatexxhj';
			}elseif($baseform->form_status=='submited'){
				$url='sub_updatexxhj';
			}elseif($baseform->form_status=='approve'){
				$url='app_updatexxhj';
			}
			$warehouse_array=Warehouse::getWareList('json','1');//仓库
			$arr=array(
					'users'=>$user_array,
					'vendors'=>$vendor_array,
					'coms'=>$com_array,
					'teams'=>$team_array,
					'backUrl'=>$backUrl,
					'warehouses'=>$warehouse_array,
					'products'=>$products_array,
					'textures'=>$textures_array,
					'brands'=>$brands_array,
					'ranks'=>$ranks_array,
					'contacts'=>$contacts_array,
					'vens'=>$ven_array,
					'baseform'=>$baseform,
					'purchase'=>$purchase,
					'details'=>$details,
					'fpage'=>$fpage,
					'msg'=>$msg
			);
		}
		$this->render($url,$arr);
	}
	
	/*
	 * 更新代销采购
	 */
	public function actionUpdateDxcg($id)
	{
		$baseform=CommonForms::model()->with('purchase')->findByPk($id);
		$this->pageTitle = "修改代销采购单".$baseform->form_sn;
		if($baseform)
		{
			$purchase=$baseform->purchase;
			$details=$purchase->purchaseDetails;
			$sales_array=$purchase->salesdetail_pur;
		}else{
			return false;
		}
		$last_update=$_REQUEST['last_update'];
		if($last_update!=$baseform->last_update)
		{
			echo "<script>alert('您看到的信息不是最新的，请刷新后再试');setTimeout('',500);window.location.href='/index.php/purchase/index';</script>";
			die;
		}
		$user_array=User::getUserList();//表单所属人
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$tpcompany=DictCompany::getVendorList('array',"is_pledge");//托盘公司
		$com_array=DictTitle::getComs('json');//采购公司
		$team_array=Team::getTeamList('json');//业务组
		$team1_array=Team::getTeamList('array');//业务组
		$warehouse_array=Warehouse::getWareList('json');//仓库
// 		$contacts_array=CompanyContact::getContactList();//联系人
		//根据品名，规格，材质，产地来选择商品
		list($id_product,$id_texture,$id_brand,$id_rank)=proListId($details);
		$products_array=DictGoodsProperty::getProList('product','array',$id_product);		//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array',$id_texture);//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json',$id_brand);//3产地
		$ranks_array=DictGoodsProperty::getProList('rank','array',$id_rank);//4规格
		$ven_array=DictCompany::getVendorList('json','is_customer');//先销后进采购公司
		$warehouse_array1=Warehouse::getWareList('array');
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		$msg='';
		if(isset($_POST['td_products']))
		{
			if($_POST['CommonForms'])
			{
				$data=FrmPurchase::getDxcgUpdateData($_POST);
				if($data){
					$allform=new Purchase($id);
					if($data['common']->submit=="yes")
					{
						$result=$allform->updateSubmitForm($data);
						if($result!==true){
							if($result=='dataerror')
							{
								$msg= '数据错误';
							}elseif($result=='morethanneed'){
								$msg= '采购数量超过需要补单的数量';
							}else{
								$msg= '未知错误';
							}
						}else{
							$this->redirect(Yii::app()->createUrl('purchase/'.$_COOKIE['purchase_view'],array("page"=>$fpage)));
						}
					}else{
						$allform->updateForm($data);
						$this->redirect(Yii::app()->createUrl('purchase/'.$_COOKIE['purchase_view'],array("page"=>$fpage)));
					}					
				}
			}else{
				//数据整合
				list($details_array,$detail_ids,$zipped_array)=FrmPurchase::getDxcgUpdateData($_POST);
			}
			if($msg)goto again;
			//然后跳转下一步
			$this->render('updatedxcg2',array(
					'type'=>'dxcg',
					'baseform'=>$baseform,
					'purchase'=>$purchase,
					'details_array'=>$details_array,
					'detail_ids'=>$detail_ids,
					'zipped_array'=>$zipped_array,
					'users'=>$user_array,
					'vendors'=>$vendor_array,
					'coms'=>$com_array,
					'teams'=>$team1_array,
					'warehouses'=>$warehouse_array,
					'contacts'=>$contacts_array,
					'vens'=>$ven_array,
					'warehouses1'=>$warehouse_array1,
					'fpage'=>$fpage,
			));die;
		}
		again:
		if($baseform->form_status=='submited')
		{
			$this->render('sub_updatedxcg2',array(
					'type'=>'dxcg',
					'baseform'=>$baseform,
					'purchase'=>$purchase,
					'zipped_array'=>$details,
					'users'=>$user_array,
					'teams'=>$team1_array,
					'warehouses'=>$warehouse_array,
					'contacts'=>$contacts_array,
					'fpage'=>$fpage
			));die;
		}elseif($baseform->form_status=='approve'){
			$this->render('app_updatedxcg2',array(
					'type'=>'dxcg',
					'baseform'=>$baseform,
					'purchase'=>$purchase,
					'zipped_array'=>$details,
					'users'=>$user_array,
					'teams'=>$team1_array,
					'warehouses'=>$warehouse_array,
					'contacts'=>$contacts_array,
					'fpage'=>$fpage
			));die;
		}		
		$detail_ids='';
		foreach ($sales_array as $each)
		{
			$detail_ids.= ','.$each->sales_detail_id;
		}
		$this->render('updatedxcg1',array(
				'detail_ids'=>$detail_ids,
				'purchase'=>$purchase,
				'baseform'=>$baseform,
				'details'=>$details,
				'users'=>$user_array,
				'vendors'=>$vendor_array,
				'coms'=>$com_array,
				'teams'=>$team_array,
				'warehouses'=>$warehouse_array,
				'products'=>$products_array,
				'textures'=>$textures_array,
				'brands'=>$brands_array,
				'ranks'=>$ranks_array,
				'contacts'=>$contacts_array,
				'vens'=>$ven_array,
				'warehouses1'=>$warehouse_array1,
				'fpage'=>$fpage,
				'msg'=>$msg
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
	
		$form=new Purchase($id);
		if($type=='submit')
		{
			$result=$form->submitForm();
			if($result!==true){
				if($result=='dataerror')
				{
					echo '数据错误';
				}elseif($result=='morethanneed'){
					echo '采购数量超过需要补单的数量';
				}elseif($result=='billchange'){
					echo '对应销售单已取消审核';
				}
			}else{
				echo 1;
			}
		}elseif($type=='cancle')
		{
			$result=$form->cancelSubmitForm();
			if($result===true){
				echo 1;
			}elseif($result=='dataerror'){
				echo '数据错误';
			}elseif($result=='billchange'){
				echo '对应销售单已取消审核';
			}
		}
	}
	
	/*
	 * 作废表单
	 */
	public function actionDeleteform($id)
	{
		$baseform=CommonForms::model()->findByPk($id);
		$reason=$_REQUEST['str'];
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
		$form=new Purchase($id);
		if($form->deleteForm($reason)){
			echo 1;
		}
	}
	
	/*
	 * 审核相关
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
		$form=new Purchase($id);
		if($type=='pass')
		{
			if($form->approveForm()){
				echo 1;
			}
		}elseif($type=='cancle')
		{
			//查询是否有入库，有入库的话不能够取消
			$result=FrmInput::model()->with(array('baseform'=>array('condition'=>'is_deleted=0')))->find('purchase_id='.$id);			
			if($result)
			{
				echo '已生成入库单，不能取消审核';
			}else{
				if($form->cancelApproveForm()){
					echo 1;
				}
			}			
		}elseif($type=='deny')
		{
			if($form->refuseForm()){
				echo 1;
			}
		}
	}
	//是否有费用登记
	public function actionHaveBill($id)
	{
		$bill=BillRecord::model()->with(array('baseform'=>array('condition'=>'baseform.form_status!="delete"')))->find('frm_common_id='.$id.' and bill_type="purchase"');//费用登记
		if($bill)
		{
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/*
	 * 是否还有入库计划
	 */
	public function actionHavePlan($id)
	{
		$plan=FrmInputPlan::model()->with(array('baseform'=>array('condition'=>'baseform.form_status!="delete"')))->find('purchase_id='.$id);
		if($plan)
		{
			echo 1;
		}else{
			echo 0;
		}
	}
	
	/*
	 * 是否已开票
	 */
	public function actionHaveKaiPiao($id)
	{
		$model=DetailForInvoice::model()->findAll('form_id='.$id);
		if($model)
		{
			$flag=false;
			foreach ($model as $each)
			{
				if($each->checked_weight>0)
				{
					$flag=true;
					break;
				}
			}
			if($flag)
			{
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}
	
	/*
	 * 是否赎回
	 */
	public function actionHavePledge($id)
	{
		$base=CommonForms::model()->with('purchase')->findByPk($id);
		if($base)
		{
			$purchase=$base->purchase;
			if($purchase->purchase_type=='tpcg')
			{
				$model=FrmPledgeRedeem::model()->with('baseform')->find('baseform.is_deleted=0 and purchase_id='.$purchase->id);
				if($model)
				{
					echo 1;
				}
			}
		}
	}
	
	/*
	 * 是否可推送
	 */
	public function actionCanPush($id,$type)
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
		$form=new Purchase($id);
		if($type=='open')
		{
			if($form->canPush()){
				echo 1;
			}
		}elseif($type=='close')
		{
			if($form->cannotPush()){
				echo 1;
			}
		}
	}
	
	/*
	 * 是否还有没有入库的入库单
	 */
	public function actionHaveInputingForm($id)
	{	
		//普通入库单
		$inputing=FrmInput::model()->with(array('baseform'=>array('condition'=>'baseform.form_status!="delete"')))->find('purchase_id='.$id.' and input_type="purchase" and input_status=0');
		if($inputing)
		{
			echo 'normal';
		}else{
			//船舱入库单
			$inputing_c=FrmInput::model()->with(array('baseform'=>array('condition'=>'baseform.form_status!="delete"')))->find('purchase_id='.$id.' and input_type="ccrk" ');
			if($inputing_c){
				echo 'ccrk';
			}else{
				echo 'youcan';
			}
		}
	}
	
	/*
	 * 审单，定价
	 */
	public function actionConfirm($id)
	{
		$baseform=CommonForms::model()->with('purchase')->findByPk($id);
		$this->pageTitle = "采购单审单".$baseform->form_sn;
		if($baseform)
		{
			$purchase=$baseform->purchase;
			$details=$purchase->purchaseDetails;
		}else{
			return false;
		}
		$last_update=$_REQUEST['last_update'];
		if($last_update!=$baseform->last_update)
		{
			echo "<script>alert('您看到的信息不是最新的，请刷新后再试');setTimeout('',500);window.location.href='/index.php/purchase/index';</script>";
			die;
		}		
		$fpage = $_REQUEST['fpage'] ? intval($_REQUEST['fpage']) : 1;
		
		if($_POST['td_weight'])
		{
			$data=FrmPurchase::getConfirmData($_POST);
			if($data){
				$allform=new Purchase($id);
				$result=$allform->confirmFormInfo($data);
				if($result===true)
					$this->redirect(Yii::app()->createUrl('purchase/'.$_COOKIE['purchase_view'],array("page"=>$fpage)));
				else 
					$msg=$result;
			}
		}
		$team_array=Team::getTeamList('array');//业务组
		$warehouse_array=Warehouse::getWareList('json');//仓库
		$warehouse_array1=Warehouse::getWareList('array');
		$contacts_array=CompanyContact::getContactList();//联系人
		$user_array=User::getUserList();//表单所属人
		//查询入库单的到货日期
		$inputdate=FrmInput::getLastInputDate($id);
		$this->render('confirm',array(
				'baseform'=>$baseform,
				'purchase'=>$purchase,
				'details'=>$details,
				'fpage'=>$fpage,
				'contacts'=>$contacts_array,
				'teams'=>$team_array,
				'users'=>$user_array,
				'date_reach'=>$inputdate,
				'msg'=>$msg
		));

	}
	public function actionCancelConfirm($id)
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
		$form=new Purchase($id);
		$res=$form->cancelConfirm();
		if($res===true){
			echo 1;
		}elseif($res==='已销票'){
			echo "已销票";
		}else{
			echo "操作失败";
		}
	}
	
	/*
	 * 获取列表，供入库选择关联
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
		list($tableHeader,$tableData,$pages)=FrmPurchase::getSimplePurchaseList($search,$type);
		$this->renderPartial('buyList',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
		));
	}
	
	/*
	 * 获取采购单详细
	 * 
	 */
	public function actionGetPurchaseDetail()
	{
		$id=$_REQUEST['id'];
		$from=$_REQUEST['from'];
		$result=FrmPurchase::getDetailData($id);
		$this->renderPartial('simpleDetailList',array(
				'data'=>$result,
				'from'=>$from,
		));
	}
	/*
	 * 获取采购单主体信息
	 */
	public function actionGetMainInfo()
	{
		$id=$_REQUEST['id'];
		$result=FrmPurchase::getMainInfo($id);
		echo $result;
	}
	
	public function actionGetUserPhone()
	{
		$return='';
		$id=$_REQUEST['contact_id'];
		$result=CompanyContact::model()->findByPk($id);
		if($result)
		{
			$return=$result->mobile;
		}
		echo $return;
	}
	
	public function actionPurchaseData()
	{
		$this->pageTitle = "采购汇总";
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		$brands=DictGoodsProperty::getProList('brand','json');
		$user_array=User::getUserList();
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableHeader,$tableData,$pages)=FrmPurchase::gatherData($search);
		
		$this->render('data',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'coms'=>$coms,
				'vendors'=>$vendor_array,
				'brands'=>$brands,
				'users'=>$user_array,
		));
	}
	
	//获取托盘数量
	public function actionGetPledgeInfo()
	{
		$brand_id=$_REQUEST['brand_id'];
		$product_id=$_REQUEST['product_id'];
		$form_id=$_REQUEST['form_id'];
		$model=CommonForms::model()->findByPk($form_id);
		if($product_id)
		{
			$sql='select sum(weight) as sum_weight ,sum(weight*price) as sum_fee from purchase_detail where purchase_id='.$model->form_id.' and brand_id='.$brand_id.' and product_id='.$product_id.' group by brand_id,product_id';
			$sql1='select weight from  pledge_redeemed where purchase_id='.$model->form_id.' and brand_id='.$brand_id.' and product_id='.$product_id;
		}else{
			$sql='select sum(weight) as sum_weight ,sum(weight*price) as sum_fee from purchase_detail where purchase_id='.$model->form_id.' and brand_id='.$brand_id.' group by brand_id';
			$sql1='select weight from  pledge_redeemed where purchase_id='.$model->form_id.' and brand_id='.$brand_id;
		}
		$return= array();
		$detail=PurchaseDetail::model()->findBySql($sql);
		$ed=PledgeRedeemed::model()->findBySql($sql1);
		if($detail)
		{
			$return['weight']=number_format($detail->sum_weight,3);
			$return['fee']=$detail->sum_fee;			
			$return['unweight']=number_format($detail->sum_weight-$ed->weight,3);
		}
		
		echo json_encode($return);
	}
	
	/*
	 * 获取品名列表
	 */
	public function actionGetProductL()
	{
		$brand_id=$_REQUEST['brand_id'];
		$form_id=$_REQUEST['form_id'];
		$model=CommonForms::model()->findByPk($form_id);
		$sql='select product_id,good.short_name as productName from purchase_detail inner join dict_goods_property as good on product_id=good.id and purchase_id='.$model->form_id.' and brand_id ='.$brand_id.' group by brand_id';
		$details=PurchaseDetail::model()->findAllBySql($sql);
		$str='';
		if($details)
		{
			foreach ($details as $each)
			{
				$str.='<option value="'.$each->product_id.'">'.$each->productName.'</option>';
			}
		}
		echo $str;
	}
	
	
	/*
	 * 获取托盘采购单列表
	 * 供托盘赎回处选择
	 */
	public function actionGetTpcgList()
	{
		//搜索
		$search=array();
		if(isset($_REQUEST['keywords']))
		{
			$search['keywords']=$_REQUEST['keywords'];
			$search['time_L']=$_REQUEST['time_L'];
			$search['time_H']=$_REQUEST['time_H'];
			$search['title_id']=$_REQUEST['title_id'];
			$search['company_id']=$_REQUEST['customer_id'];
			$search['owned']=$_REQUEST['owned_by'];
		}
		list($tableHeader,$tableData,$pages)=FrmPurchase::getTpcgList($search);
		$this->renderPartial('buyList',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
		));
	}
	
	/*
	 * 
	 */
	public function actionGetShipment($id)
	{
		$result=DictCompany::model()->findByPk($id);
		if($result)
		{
			echo $result->fee;
		}
	}
	
	/*
	 * 托盘公司，采购公司，供应商之间的往来统计
	 */
	public function actionBAT()
	{
		$this->pageTitle = "托盘报表";
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json','is_supply');//供应商
		$pledge_array=DictCompany::getVendorList('json','is_pledge');
		
		//搜索
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($tableHeader,$tableData,$pages)=FrmPurchase::BAT($search);
		
		$this->render('bat',array(
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'pages'=>$pages,
				'search'=>$search,
				'coms'=>$coms,
				'vendors'=>$vendor_array,
				'pledges'=>$pledge_array,
		));
	}
	
	/**
	 * 打印页面
	 * @param unknown_type $id
	 */
	public function actionPrint($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		$model = $baseform->purchase;
		$details = $model->purchaseDetails;
		
		$this->renderPartial('print', array(
				'baseform' => $baseform, 
				'model' => $model, 
				'details' => $details,
		));
	}
	
	
	public function actionTest($tt='array',$ta='ss',$t='')
	{
// 		$str='';
// 		var_dump( substr($str, 0,strlen($str)-1));
// 		die;
		$connection=Yii::app()->db;
			
		$sql00="show variables like '%query_cache_type%'";
		$res=$connection->createCommand($sql00)->queryAll();
		var_dump($res);
		echo "<hr/>";
		$sql1="set profiling=1";
		$res=$connection->createCommand($sql1)->execute();
		
		$connection->createCommand("flush query cache")->execute();
		
		$sql="select sql_no_cache id from sales_detail where frm_sales_id=3 limit 1";
		$res=$connection->createCommand($sql)->queryAll();
		var_dump($res);
		echo "<hr>";
		
		$connection->createCommand("flush query cache")->execute();
		
		$sql2="SELECT sql_no_cache id  from sales_detail where frm_sales_id=3 limit 1";
		$res=$connection->createCommand($sql2)->queryAll();
		var_dump($res);
		echo "<hr>";
		$sql_end="show profiles";
		$ress=$connection->createCommand($sql_end)->queryAll();;
		var_dump($ress);
		die;
// 		$sql="select id from sales_detail where frm_sales_id=9788";
// 		$res=$connection->createCommand($sql)->queryAll();
// 		var_dump($res);
// 		die;
// 			$transaction=Yii::app()->db->beginTransaction();
// 			try {
// 				$model=PurchaseDetail::model()->findByPk(17);

// 				$transaction->commit();
// 			}catch (Exception $e)
// 			{
// 				$transaction->rollBack();
// 				return;
// 			}
		$xml=readConfig();
		var_dump( floatval($xml->freight->steelworks->price));
// 		$xml->freight->steelworks->price=41;
// 		$path=Yii::app()->basePath.'/../public/';
// 		echo $path; 
// 		$xml->asXML($path.'config.xml');
// 			$attr=$ea->attributes();
// 			var_dump($attr);
// 			echo $attr['name'];
			echo '<hr/>';
		
		die;
		$c=array_diff($b, $a);
		var_dump($c);
		die;
		echo memory_get_usage(),'<br/>';
		echo microtime(),'<br>';
		$sql='select id from output_detail ';
// 		$sql='select 1,2,3 union  select 2,2,4 ';
		$connection=Yii::app()->db;
		$command=$connection->createCommand($sql);
		$re=$command->queryAll();
		$old_ids=array();
		for($i=0;$i<200;$i++){
			foreach ($re as $each)
			{
				array_push($old_ids,$each['id']);
				
			}			
		}		
// 		$re=OutputDetail::model()->findAllBySql($sql);
// 		var_dump($re);
// 		$a= $re;
		echo microtime(),'<br/>';
		echo memory_get_usage();
// 		echo isset(Yii::app()->user->userid)?11:00;
	}
	public static  function t($aa)
	{
		
		// $tran=Yii::app()->db->beginTransaction();
		// try {
		// $sql='select * from purchase_view ';
		// $model=PurchaseDetail::model()->findBySql($sql);
		// $tran->commit();
		// }catch (Exception $e)
		// {
		// 	echo "<script>alert('11')</script>";
		// 	echo $e;
		// 	$tran->rollBack();
		// 	return;
		// }
	} 
	
	public function actionGetInvoice($id)
	{
		$price=FrmPurchase::getInvoice($id);
		echo $price;		
	}

	/**
	 * 导出
	 */
	public function actionExport() 
	{	//echo memory_get_usage(),'<br>';
		$search = $_REQUEST['search'];
		$name = "采购单".date("Y/m/d");
		$title = array('采购单号', '开单日期', 
			'采购类型', '供应商', '采购公司', 
			'乙单', '品名', '规格', '材质', '产地', '长度', 
			'单价', '件数', '重量', '金额', 
			'托盘公司', '托盘单价', '托盘金额', 
			'预计到货时间', '备注', 
			'入库件数', '入库重量', 
			'核定价格', '核定件数', '核定重量', '核定金额', 
			'审单状态', '是否已销票', '采购合同', 
			'审批状态', '审核人', '审核时间', '业务员', '业务组', '制单人', '最后操作人');
		if ($search['form_status'] == 'delete') array_push($title, '作废原因');

		$type = $_COOKIE['purchase_view'] ? $_COOKIE['purchase_view'] : 'index';
		if ($type != 'index' && !checkOperation($this->v[$_COOKIE['purchase_view']])) 
			$type = 'index';

		$content = FrmPurchase::getAllList($search, $type);
		PHPExcel::ExcelExport($name, $title, $content);
		// echo memory_get_usage();
	}

	public function actionBATExport() 
	{
		$search = $_REQUEST['search'];
		$name = "托盘报表".date("Y/m/d");
		$title = array('采购公司', '供应商', '托盘公司', '采购商-托盘公司', '供应商-托盘公司');

		// FrmPurchase::BAT($search);
		$content = FrmPurchase::getAllBATList($search);
		PHPExcel::ExcelExport($name, $title, $content);
	}

}
