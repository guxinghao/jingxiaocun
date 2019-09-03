<?php

/**
 * This is the biz model class for table "push_list".
 *
 */
class PushList extends PushListData
{
	

	 public static  $type=array('inputformplan'=>'入库计划','deliveryform'=>"配送单",	'customer_company'=>'结算单位','jxc_title'=>'公司','jxc_warehouse'=>'仓库');
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'creater'=>array(self::BELONGS_TO,'User','created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'content' => 'Content',
			'status' => 'Status',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'unid' => 'Unid',
			'form_id' => 'Form',
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
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('unid',$this->unid);
		$criteria->compare('form_id',$this->form_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PushList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 插入数据
	 * 传入$data数组
	 * 参数：
	 * 		$data['type']='inputformplan';
				$data['content']=$jsonData;
				$data['unid']=$unid;
				$data['operate']='Add';
				$data['form_id']=$form_id;
	 */
	public static function createNew($data)
	{
		$model=new PushList();
		if(is_array($data)&&!empty($data))
		{
			$model->type=$data['type'];
			$model->unid=$data['unid'];
			$model->form_id=$data['form_id'];
			$model->status='no';
			$model->created_at=time();
			$model->created_by=currentUserId();
			$model->form_sn=$data['form_sn'];
			$model->times=0;
			$model->operate=$data['operate'];
			$model->next_time=0;
			
			if($model->insert()){
				$api = new api_center();
				$unid = $model->unid;
				$system=$model->type;
				$type=$data['operate'];
				$pushId=$model->id;
				$interface = $api->pushForm($data['content'],$system,$type,$unid,$pushId);
				$model->content=$interface;
				$model->update();
			}		
		}
		return $model;
	}
	
	
	/*
	 * 定时执行脚本
	 */
	public static function timingSctipt()
	{
		$api = new api_center();
		$lists = PushList::model()->findAll("t.status = 'no'"); 
		if ($lists)
		{
			foreach ($lists as $each) 
			{
				if ($each->times >= 5) 
				{
					$each->status = 'fail';
					//消息通知
					switch ($each->type)
					{
						case 'inputformplan':
							$content="您的入库计划单".$each->form_sn."推送失败";		
							//设置入库计划的推送状态为推送失败
							$model=FrmInputPlan::model()->findByPk($each->form_id);
							if($model)
							{
								$model->input_status=-2;
								$model->update();
							}							
							break;
						case 'deliveryform':
							$content="您的配送单".$each->form_sn."推送失败";
							$model=FrmSend::model()->findByPk($each->form_id);
							if($model){
								$model->status='pushfaild';
								$model->update();
							}						
							break;
						case 'customer_company':
							$content="您的结算单位数据".$each->form_sn."向接口中心推送失败";							
							break;
						case 'jxc_title':
							$content="您的公司抬头信息".$each->form_sn."向接口中心推送失败";							
							break;
						case 'jxc_warehouse':
							$content="您的仓库信息".$each->form_sn."向接口中心推送失败";
							break;
					}
					$message = array();
					$message['receivers'] =$each->created_by;
					$message['content'] =$content;
					$message['title'] = "推送通知";
					$message['url'] = Yii::app()->createUrl('interface/failList',array('form_sn'=>$each->form_sn));
					$message['type'] = "推送通知";
					$message['big_type']='ware';
					$res = MessageContent::model()->addMessage($message);					
					
					//短信通知			
					
				} 
				elseif ($each->next_time <= time()) 
				{
					$each->next_time += 10 * $each->times;
					$each->times += 1;
					$result = array();
					$result["interface"] = $each->content;
	// 				$re = requestByCurl("http://api.xun-ao.com/index.php/interface",$result);
					$re = requestByCurl($api->api_center_host.'/index.php/interface/', $result);
					$return = json_decode($re);
					if($return)
					{
						if ($return->result == 'success') 
							$each->status = 'yes';
						else 
							$each->message = $return->message;
					}else{
						$each->message ="未收到返回值或返回值为null";
					}
				}
				$each->update();
			}
		}
		
	}
	
	public static function searchForm($json)
	{
		if (!$json) return false;
		$data = json_decode($json);
		$send_id = $data->SendId;
		$model = PushList::model()->findByPK($send_id);
		return $model->type;
	}
	
	
	/*
	 * 推送失败列表
	 */
	public static function faliList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"100px"),				
				array('name'=>'推送单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"85px"),//
				array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"85px"),//
				array('name'=>'创建时间','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'创建人','class' =>"flex-col sort-disabled",'width'=>"110px"),
				array('name'=>'异常原因','class' =>"flex-col sort-disabled",'width'=>"300px"),//
		);
		$tableData=array();
		$model=PushList::model()->with(array());
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if($search['keywords'])
			{
				$criteria->addCondition('form_sn like :contno ');
				$criteria->params[':contno']= "%".trim($search['keywords'])."%";
			}			
			if($search['time_L']!='')
			{
				$criteria->addCondition('created_at >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('created_at <='.(strtotime($search['time_H'])+86400));
			}
			if($search['type']!='0')
			{
				$criteria->compare('type',$search['type']);
			}
		}
		$criteria->compare('status','fail');
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['push_list']) ? intval($_COOKIE['push_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order='created_at DESC';
		$details=PushList::model()->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			
			$_status=array('yes'=>'已推送','no'=>'未推送','fail'=>'推送失败');
			$baseform='';
			$i=0;
			foreach ($details as $each)
			{
				$i++;
				$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
				$com_url=Yii::app()->createUrl('interface/retry',array('id'=>$each->id));
				$operate.='<span class="submit_form" url="'.$com_url.'" str="确定要重试单据'.$each->form_sn.'吗？" title="重试"><img src="/images/chongtui.png"></span>';
				$operate.='</div >';
				
				switch ($each->type)
				{
					case 'inputformplan':
						$baseform=CommonForms::model()->findByAttributes(array('form_sn'=>$each->form_sn));
						$detail_url=Yii::app()->createUrl('inputPlan/view',array('id'=>$baseform->id,'fpage'=>$_REQUEST['page'],'backUrl'=>'interface/failList','search_url'=>json_encode($search)));
						break;
					case 'deliveryform':
						$detail_url=Yii::app()->createUrl('frmSend/detail',array('id'=>$each->form_id,'fpage'=>$_REQUEST['page'],'backUrl'=>'interface/failList','search_url'=>json_encode($search)));
						break;
					case 'customer_company':
						$detail_url=Yii::app()->createUrl('dictCompany/index',array('name'=>$each->form_sn));
						break;
					case 'jxc_title':
						$detail_url=Yii::app()->createUrl('dictTitle/index',array('name'=>$each->form_sn));
						break;
					case 'jxc_warehouse':
						$detail_url=Yii::app()->createUrl('warehouse/index',array('name'=>$each->form_sn));
						break;
				}
				
				if($each->type=='inputformplan'){
					$baseform=CommonForms::model()->findByAttributes(array('form_sn'=>$each->form_sn));
					$detail_url=Yii::app()->createUrl('inputPlan/view',array('id'=>$baseform->id,'fpage'=>$_REQUEST['page'],'backUrl'=>'interface/failList','search_url'=>json_encode($search)));
				}elseif($each->type=='deliveryform'){
// 					$baseform=CommonForms::model()->findByAttributes(array('form_sn'=>$each->form_sn));
					$detail_url=Yii::app()->createUrl('frmSend/detail',array('id'=>$each->form_id,'fpage'=>$_REQUEST['page'],'backUrl'=>'interface/failList','search_url'=>json_encode($search)));
				}
				
				$da['data']=array($i,
						$operate,
						'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$each->form_sn.'</a>',
						PushList::$type[$each->type],
						$_status[$each->status],
						($each->created_at>943891200)?date('Y-m-d',$each->created_at):'',
						$each->creater->nickname,
						'<span title="'.htmlspecialchars($each->message).'">'.mb_substr($each->message, 0,15,'utf-8').'</span>',
				);
				$da['group']=$each->id;
				array_push($tableData,$da);
			}
		}
		return array($tableHeader,$tableData,$pages);
	}
	
}
