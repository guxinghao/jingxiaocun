<?php
//微信客户管理
class WxUserController extends AdminBaseController
{
	public function actionIndex()
	{
		$model=new WxUser();
		$this->pageTitle = "微信用户管理";
		$search=array();
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		$search=updateSearch($search,'search_wxuser_index');
		list($items,$pages) =$model->getWxUserList($search);
		$user_array=User::getUserList();
		$this->render('index',array('items'=>$items,'search'=>$search,'user_array'=>$user_array,'pages'=>$pages));
	}

	public function actionLink()
	{
		$this->layout='';
		$name=trim($_REQUEST['name']);
		$type=$_REQUEST['type'];
		$id=$_REQUEST['id'];
		list($tableHeader,$tableData,$pages,$selected)=WxUser::getSelectList($name,$type,$id);
		$this->render('link',array(
			'tableHeader'=>$tableHeader,
			'tableData'=>$tableData,
			'pages'=>$pages,
			'type'=>$type,
			'id'=>$id,
			'selected'=>$selected,
		));
	}
	
	public function  actionSaveSelected()
	{
		$id=$_REQUEST['id'];
		$type=$_REQUEST['type'];
		$selected=$_REQUEST['selected'];
		$model=WxUser::model()->findByPk($id);
		if($model)
		{
			$result=$model->saveLink($model,$type,$selected);
			echo $result;
		}else{
			echo '好像哪里出了问题，囧';
		}		
	}
	
	public function actionUpdate($id)
	{
		$model = WxUser::model()->findByPk($_REQUEST['id']);
		if($_POST['WxUser']){
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间
			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updateData($_POST['WxUser']);
				if($success<0){
					$msg = "该登录名已存在";
				}elseif($success){
					//日志
					$base = new BaseForm();
					$base->operationLog('微信用户管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'wxuser',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					$this->redirect('/index.php/wxUser/index');
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$this->pageTitle = "微信用户修改";
		$this->render('update',array(
				'model'=>$model,
				'msg'=>$msg,
		));	
	}
	
	public function actionDelete()
	{
		$id = $_REQUEST['del_id'];
		$model = WxUser::model()->findByPk($id);
		$time = intval($_REQUEST['time']);
		$canDelete = $this->checkUpdateTime($model->tableName(), $time, $id);
		if(!$canDelete){
			echo "updated";
			return;
		}
		if(!$_REQUEST['sure'])
		{
			//make sure that there is no warehouse belongs to this area
			if($this->checkRelation($id))
			{
				echo 'deny';
				return;
			}
		}				
		$mainJson = $model->datatoJson();
		$companys=$model->companys;
		if($companys)
		{
			foreach ($companys as $each)
			{
				$each->delete();
			}
		}
		$model->delete();
		//日志
		$base = new BaseForm();
		$base->operationLog('微信用户管理','删除');		
		$dataArray = array("tableName"=>'wxuser',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
		echo 1;		
	}
	
	public function checkRelation($id)
	{
		$bool=WxUserCompany::model()->exists("user_id=".$id.' and is_deleted=0');
		return $bool;
	}

}
?>