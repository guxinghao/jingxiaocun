<?php
//区域管理
class PrefectureController extends AdminBaseController
{
	public $layout='admin';	
	public function actionIndex()
	{
		$this->pageTitle = "专区管理";
		$model = new Prefecture();		
		$cri = new CDbCriteria();
		$search =  new Prefecture();		
		if($_POST['search']){			
			$search->attributes = $_POST['search'];
			if($search->name){
				$cri->params[':search'] = "%".$search->name."%";
				$cri->addCondition("name like :search");
			}
		}		
		$items = $model->findAll($cri);	
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
		));
	}
	
	
	public function actionCreate()
	{
		$model = new Prefecture();
		$this->pageTitle = "新建区域";
		if($_POST['Prefecture']){
			$success = $model->createPre($_POST['Prefecture']);
			if($success<0){
				$msg = "该名称已存在";
			}elseif($success){
				//日志
				$base = new BaseForm();
				$base->operationLog('专区管理','新增');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'prefecture',"newValue"=>$mainJson,"oldValue"=>"");
				$base->dataLog($dataArray);
				$this->returnListPage();
			}
		}		
		$this->render('create',array(
				'model'=>$model,
				'msg'=>$msg,
				'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionUpdate()
	{
		$model = Prefecture::model()->findByPk($_REQUEST['id']);
		if($_POST['Prefecture']){
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间
			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updatePre($_POST['Prefecture']);
				if($success<0){
					$msg = "该名称已存在";
				}elseif($success){
					//日志
					$base = new BaseForm();
					$base->operationLog('专区管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'prefecture',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					$this->returnListPage();
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$this->pageTitle = "专区修改";
		$this->render('update',array(
			'model'=>$model,
			'msg'=>$msg,
			'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionDelete(){
		$id = $_REQUEST['del_id'];
		$model = Prefecture::model()->findByPk($id);
		$time = intval($_REQUEST['time']);
		$canDelete = $this->checkUpdateTime($model->tableName(), $time, $id);
		if(!$canDelete){
			echo "updated";
			return;
		}
		//make sure that there is no warehouse belongs to this area
		if($this->checkRelation($id))
		{
			echo 'deny';
			return;
		}
		
		$mainJson = $model->datatoJson();
		$model->delete();
		//日志
		$base = new BaseForm();
		$base->operationLog('专区管理','删除');
		
		$dataArray = array("tableName"=>'prefecture',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
		
	}
	
	
	/*
	 * 专区管理，增加删除产品
	 */
	public  function  actionManage($id)
	{
		$model=Prefecture::model()->findByPk($id);	
		$old_ids=DictGoods::getPrefectureGoods($id);
		$this->pageTitle='管理'.$model->name;
		$products_array=DictGoodsProperty::getProList('product','array','all');//1品名
		$textures_array=DictGoodsProperty::getProList('texture','array','all');//2材质
		$brands_array=DictGoodsProperty::getProList('brand','json','all');		//3产地
		$ranks_array=sortRank(DictGoodsProperty::getProList('rank','array','all'));//4规格
		$this->render('manage',array(
				'brands'=>$brands_array,
				'products'=>$products_array,
				'rands'=>$ranks_array,
				'textures'=>$textures_array,
				'old_ids'=>$old_ids,
		));
	}
	
	/*
	 * save changes that was made at the  prefecture manage page
	 */
	public function actionSaveChange()
	{
		$prefecture=$_REQUEST['prefecture'];
		$deleted_ids=$_REQUEST['deleted_ids']?$_REQUEST['deleted_ids']:'';
// 		$deleted_ids=substr($deleted_ids,1,strlen($deleted_ids)-2);
		$new_ids=substr($_REQUEST['new_ids'],1,strlen($_REQUEST['new_ids'])-2);

		$transaction=Yii::app()->db->beginTransaction();
		try{
			if($deleted_ids)
			{
				$cri=new CDbCriteria();				
				$cri->join="left join dict_goods g on g.product_std=quoted_detail.product_std and g.texture_std=quoted_detail.texture_std 
					and g.brand_std=quoted_detail.brand_std and g.rank_std=quoted_detail.rank_std and g.length=quoted_detail.length and quoted_detail.type='guidance'";
				$cri->addCondition('g.id in ('.$deleted_ids.')');
				$cri->addCondition('prefecture='.$prefecture);
// 				$cri->addCondition('price_date="'.date('Y-m-d',time()).'"');
				$models=QuotedDetail::model()->deleteAll($cri);
			}
			$new_id_array=explode(',',$new_ids);
			if(!empty($new_id_array))
			{
				foreach($new_id_array as $each)
				{
					$good=DictGoods::model()->findByPk($each);
					if($good)
					{
						$quote=new QuotedDetail();
						$quote->product_std=$good->product_std;
						$quote->texture_std=$good->texture_std;
						$quote->brand_std=$good->brand_std;
						$quote->rank_std=$good->rank_std;
						$quote->length=$good->length;
						$quote->created_at=time();
						$quote->created_by=currentUserId();
						$quote->type='guidance';
						$quote->price_date=date('Y-m-d',time());
						$quote->prefecture=$prefecture;
						$quote->insert();
					}
				}
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			// echo '数据错误';
			echo $e;
			return;
		}
		echo 1;		
	}
	
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("prefecture/index",array('page'=>$_REQUEST['page']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}
	
	public function checkRelation($id)
	{
		$bool=QuotedDetail::model()->exists("prefecture=".$id);
		return $bool;
	}
	
}

?>