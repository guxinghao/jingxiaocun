<?php
/**
 * 参数配置
 * @author leitao
 *
 */
class SysConfigController extends Controller
{ 
	/**
	 * 列表
	 */
    public function actionIndex() 
    { 
    	$this->pageTitle = "参数配置";
    	$model = new SysConfig();
    	$criteria = new CDbCriteria();
    	
    	if (isset($_POST['SysConfig'])) 
    	{
    		$model->attributes = $_POST['SysConfig'];
    		if ($model->sys_name) 
    		{
    			$criteria->addCondition("t.sys_name LIKE :sys_name");
    			$criteria->params[':sys_name'] = '%'.$model->sys_name.'%';
    		}
    	}
    	
    	$pages = new CPagination();
    	$pages->itemCount = $model->count($criteria);
    	$pages->pageSize = intval($_COOKIE['sys_config']) ? intval($_COOKIE['sys_config']) : Yii::app()->params['pageCount'];
    	$pages->applyLimit($criteria);
    	$criteria->order = "id DESC";
    	
    	$items = $model->findAll($criteria);
    	
        $this->render('index', array(
        		'model' => $model, 
        		'items' => $items, 
        		'pages' => $pages,
        )); 
    } 
    
    /**
     * 修改值
     */
    public function actionUpdateValue()
    {
    	$id = intval($_POST['id']);
    	$value = $_POST['value'];
    	$model = SysConfig::model()->findByPK($id);
    	$model->value = $value;
    	$model->updated_at = time();
    	$model->updated_by = currentUserId();
    	if ($model->update()) echo true;
    }
    
    public function actionPostUpdate() 
    {
    	$ids = explode(",", $_POST['ids']);
    	$values = explode(",", $_POST['values']);
    	for ($i = 0; $i < count($ids); $i++) 
    	{
    		$id = intval($ids[$i]);
    		$value = $values[$i];
    		$model = SysConfig::model()->findByPK($id);
    		$model->value = $value;
    		$model->updated_at = time();
    		$model->updated_by = currentUserId();
    	}
    	echo 1;
    }

    // Uncomment the following methods and override them if needed 
    /* 
    public function filters() 
    { 
        // return the filter configuration for this controller, e.g.: 
        return array( 
            'inlineFilterName', 
            array( 
                'class'=>'path.to.FilterClass', 
                'propertyName'=>'propertyValue', 
            ), 
        ); 
    } 

    public function actions() 
    { 
        // return external action classes, e.g.: 
        return array( 
            'action1'=>'path.to.ActionClass', 
            'action2'=>array( 
                'class'=>'path.to.AnotherActionClass', 
                'propertyName'=>'propertyValue', 
            ), 
        ); 
    } 
    */ 
}
