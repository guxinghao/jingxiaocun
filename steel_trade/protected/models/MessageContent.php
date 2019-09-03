<?php

/**
 * This is the biz model class for table "message_content".
 *
 */
class MessageContent extends MessageContentData
{

    public $bid;

    public $status;

    /**
     *
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'messageBoxes' => array(
                self::HAS_MANY,
                'MessageBox',
                'message_id'
            )
        );
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'type' => 'Type',
            'url' => 'Url'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     *         based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria();
        
        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('url', $this->url, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className
     *            active record class name.
     * @return MessageContent the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /*
     * 消息新增
     * pamras array数组，
     * 1、title 选填值
     * 2、content必填值
     * 3、type 选填值
     * 4、url 选填值
     * 5、receviers 收件人（多个收件人请以,分割）
     */
    public function addMessage($array)
    {
    	$big_type='';
        $message = new MessageContent();
        foreach ($array as $key => $val) {
            switch ($key) {
                case "title":
                    $message->title = $val;
                    break;
                case "content":
                    $message->content = $val;
                    break;
                case "type":
                    $message->type = $val;
                    break;
                case "url":
                    $message->url = $val;
                    break;
                case "receivers":
                    $receivers = $val;
                    break;
                case "big_type":
                	$big_type=$val;
                default:
                    break;
            }
        }
        if (! trim($message->content) || ! $receivers) {
            return false;
        }
        $message->created_at = time();
        $message->created_by = isset(Yii::app()->user->userid)?Yii::app()->user->userid:0;
        if ($message->save()) {
            if ($receivers) {
                $receiverarray = explode(",", $receivers);
                for ($i = 0; $i < count($receiverarray); $i ++) {
                    $messagebox = new MessageBox();
                    $messagebox->message_id = $message->id;
                    $messagebox->receiver_id = $receiverarray[$i];
                    $messagebox->send_time = time();
                    $messagebox->big_type=$big_type;
                    $messagebox->status = 1;
                    $messagebox->save();
                }
                return true;
            }
        }
        return false;
    }

    /*
     * 消息设置已读
     */
    public function setIsRead($messageIds)
    {
        return $this->changeStatus($messageIds, 2);
    }

    /*
     * 消息删除
     */
    public function deleteMessage($messageIds)
    {
        return $this->changeStatus($messageIds, 0);
    }

    private function changeStatus($messageIds, $status)
    {
        if (! $messageIds) {
            return false;
        }
        $count = MessageBox::model()->updateAll(array(
            "status" => $status
        ), "id in ({$messageIds})");
        if ($count > 0) {
            return true;
        }
        return false;
    }

    /**
     * 获取最新消息条数
     */
    public function getCount($type='')
    {
//         $cri = new CDbCriteria();        
//         $cri->select = "t.*,b.id as bid,b.status";        
//         $cri->join = "left join message_box b on b.message_id = t.id";        
//         $cri->order = "b.status,t.created_at desc";        
//         $cri->addCondition("b.status=1");        
//         $cri->addCondition("b.receiver_id = " . Yii::app()->user->userid);
//         $count = $this->count($cri);
//         return $count;
        
    	//优化,不读message_content
//     	$sql="select count(*) as count from (select id  from message_box where status=1 and receiver_id=".Yii::app()->user->userid." group by receiver_id,message_id) temp";
	
    	if($type=='all')
    	{
    		$sql="select count(*) as count  from message_box where status=1 and receiver_id=".Yii::app()->user->userid;
    		$connection=Yii::app()->db;
    		$res=$connection->createCommand($sql)->queryRow();
    		return $res['count']?$res['count']:0;
    	}elseif($type){
    			$sql="select count(*) as count  from message_box where status=1 and receiver_id=".Yii::app()->user->userid." and big_type='".$type."'";
    			$connection=Yii::app()->db;
    			$res=$connection->createCommand($sql)->queryRow();
    			return $res['count']?$res['count']:0;    		
    	}else{
    		$array=array('purchase'=>'0','sale'=>'0','money'=>'0','ware'=>'0');
    		$connection=Yii::app()->db;
    		$sql="select count(*) as count ,big_type from message_box where status=1 and receiver_id=".Yii::app()->user->userid." group by big_type";    		
    		$res=$connection->createCommand($sql)->queryAll();
    		if($res)
    		{
    			foreach ($res as $each)
    			{
    				switch ($each['big_type'])
    				{
    					case 'purchase':
    						$array['purchase']=$each['count'];
    						break;
    					case 'sale':
    						$array['sale']=$each['count'];
    						break;
    					case 'money':
    						$array['money']=$each['count'];
    						break;
    					case 'ware':
    						$array['ware']=$each['count'];
    						break;
    					default:
    						break;    					
    				}
    			}
    		}
    		return json_encode($array);    		    		
    	}
    }
}
