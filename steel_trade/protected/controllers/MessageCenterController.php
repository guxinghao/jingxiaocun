<?php

class MessageCenterController extends AdminBaseController

{

    public $layout = 'admin';

    public function actionIndex()    
    {
    	$big_type=$_REQUEST['big_type'];
        $this->pageTitle = "消息中心";        
        $model = new MessageContent();        
        $cri = new CDbCriteria();        
        $cri->select = "t.*,b.id as bid,b.status";        
        $cri->join = "left join message_box b on b.message_id = t.id";        
        $cri->order = "b.status,t.created_at desc";        
        $cri->addCondition("b.status>0");
        if($big_type)
        {
        	$cri->addCondition("big_type='".$big_type."'");
        }                
        $cri->addCondition("b.receiver_id = " . Yii::app()->user->userid);        
        $pages = new CPagination();        
        $pages->itemCount = $model->count($cri);        
        $pages->pageSize = $_COOKIE['msg'] ? intval($_COOKIE['msg']) : Yii::app()->params['pageCount'];        
        $pages->applyLimit($cri);        
        $items = $model->findAll($cri);
        $this->render('index', array(            
            'pages' => $pages,            
            'items' => $items
        ));
    }

    /**
     * 设置已读
     */
    public function actionSetRead()
    {
        $ids = $_POST["id"];
        if (ids) {
            $result = MessageContent::model()->setIsRead($ids);
            if ($result) {
                echo "OK";
                die();
            }
        }
        echo "error";
    }

    /**
     * 设置已删
     */
    public function actionSetDelete()
    {
        $ids = $_POST["id"];
        if (ids) {
            $result = MessageContent::model()->deleteMessage($ids);
            if ($result) {
                echo "OK";
                die();
            }
        }
        echo "error";
    }
    
    /**
     * 获取最新消息条数
     * */
    public function actionGetCount()
    {
    	$type=$_REQUEST['type'];
        echo MessageContent::model()->getCount($type);
    }
}