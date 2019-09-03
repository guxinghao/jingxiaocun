<?php
/**
 * 消息接收
 * @author leitao
 *
 */
class InterfaceController extends AdminBaseController
{
	public function actionMessages() 
	{
		@session_destroy();
		$json = $_POST['json'];
		// $json = '{"Body":{"Verb":"Message", "No":317}}';
// 		$json = '{"Body":{"Verb":"Resend", "SendId":81, "Result":"error", "Message":"测试失败"}}';
		$value = json_decode($json);
		if ($value->Body->Verb == 'Resend') 
		{
			$data = json_encode($value->Body);
			$System = PushList::searchForm($data);
		} 
		elseif ($value->Body->Verb == 'Message')
		{
			$api = new api_center();			
			$datajson = $api->searchForm(json_encode($value->Body));			
			$data = json_decode($datajson);
			if ($data->Result == 'error')
			{
				$return = '{"result":"error","message":"'.urlencode($data->Message).'"}';
				echo urldecode($return); exit();
			}
			$System = $data->System;
		}
		// echo $datajson;
		// die;
		switch ($System)
		{
			case 'inputformplan': //入库计划
				$result = FrmInputPlan::response($data);
				break;
			case 'inputform': //入库单
				$result=PushedStorage::createNew($data);
				break;
			case 'deliveryform': //配送单
				$result = FrmSend::setPush($data);
				break;
			case 'outputform': //出库单
				$result = WarehouseOutput::SetData($data);
				break;
			case 'textureform': //材质推送
				$result = DictGoodsProperty::synchronization($data);
				break;
			case 'goodsnameform': //品名推送
				$result = DictGoodsProperty::synchronization($data);
				break;
			case 'rankform': //规格推送
				$result = DictGoodsProperty::synchronization($data);
				break;
			case 'goodscompanyform': //产地推送
				$result = DictGoodsProperty::synchronization($data);
				break;
			case 'goodsform': //件重推送
				$result = DictGoods::synchronization($data);
				break;
			case 'usersform': //用户类型
				$result = User::synchronization($data);
				break;
			default: break;
		}
		echo $result;
	}
	
	/*
	 * 异常列表
	 */
	public function actionFailList()
	{
		$this->pageTitle="异常列表";
		$this->setHome = 1;//允许设为首页
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}elseif(isset($_REQUEST['search_url'])){
			$search=(Array)json_decode($_REQUEST['search_url']);
		}elseif(isset($_REQUEST['form_sn'])){
			$search['keywords']=$_REQUEST['form_sn'];
		}
		$coms=DictTitle::getComs('json');//下拉菜单数据
		$vendor_array=DictCompany::getVendorList('json');//供应商
		
		list($tableHeader,$tableData,$pages,$totalData)=PushList::faliList($search);
		$view='index';
		$param=array(
				'search'=>$search,
				'type'=>'',
				'pages'=>$pages,
				'coms'=>$coms,
				'vendors'=>$vendor_array,
				'users'=>$user_array,
				'tableHeader'=>$tableHeader,
				'tableData'=>$tableData,
				'totalData'=>$totalData,
		);
		$this->render($view,$param);
		
	}
	
	/*
	 * 重试
	 */
	public function actionRetry($id)
	{
		$model=PushList::model()->findByPk($id);
		if($model)
		{
			
			switch ($model->type)
			{
				case 'inputformplan':
					$plan=FrmInputPlan::model()->findByPk($model->form_id);
					if($plan)
					{
						$plan->input_status=-2;
						$plan->update();
						$model->times=0;
						$model->status='no';
						if($model->update()){
							$result = 1;
						}else{
							$result = 0;
						}
					}
					break;
				case 'deliveryform':
					$result=FrmSend::getPushJson($model);
					break;
				default:
					$model->times=0;
					$model->status='no';
					if($model->update()){
						$result = 1;
					}else{
						$result = 0;
					}
					break;
			}
			if($result === -1){
				echo "配送单已作废";
			}else if($result){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo "未找到数据";
		}
	}
	
}
