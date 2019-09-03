<?php

/**
 * This is the biz model class for table "warehouse_output".
 *
 */
class WarehouseOutput extends WarehouseOutputData
{
		public $t_amount;	
		public $t_weight;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'warehouseOutputDetails' => array(self::HAS_MANY, 'WarehouseOutputDetail', 'warehouse_output_id'),
			'dictCompany' => array(self::BELONGS_TO, 'DictCompany', 'customer_id'),
			'dictCom'=>array(self::BELONGS_TO,'DictCompany','title_id'),
			'dictTitle' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'frmsend' => array(self::BELONGS_TO, 'FrmSend', 'frm_send_id'),
			'warehouse'=>array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'frm_send_id' => 'Frm Send',
			'output_no' => 'Output No',
			'car_no' => 'Car No',
			'title_id' => 'Title',
			'customer_id' => 'Customer',
			'output_type' => 'Output Type',
			'fee_type' => 'Fee Type',
			'remark' => 'Remark',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
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
		$criteria->compare('frm_send_id',$this->frm_send_id);
		$criteria->compare('output_no',$this->output_no,true);
		$criteria->compare('car_no',$this->car_no,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('output_type',$this->output_type,true);
		$criteria->compare('fee_type',$this->fee_type,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WarehouseOutput the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * 获取仓库出库单列表
	 */
	public static function getFormList($search)
	{
		$tableData=array();
		$model = new WarehouseOutputDetail();
		$criteria=New CDbCriteria();
		$criteria->with = array("warehouseOutput");
		//搜索
		if(!empty($search)){
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('warehouseOutput.output_no like :contno or warehouseOutput.sales_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('warehouseOutput.created_at >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('warehouseOutput.created_at <='.(strtotime($search['time_H'])+86400));
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('warehouseOutput.title_id',$search['title_id'],true);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('warehouseOutput.customer_id',$search['customer_id'],true);
			}
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('t.brand_id',$search['brand'],true);
			}
			if($search['product']!='0')
			{
				$criteria->compare('t.product_id',$search['product'],true);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('t.rank_id',$search['rand'],true);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],true);
			}
			//制单人
			if($search['owned_by']!='0')
			{
				$criteria->compare('warehouseOutput.created_by',$search['created_by'],true);
			}
		}
		$criteria->addCondition('warehouseOutput.status>=0');
		$criteria->order = "status ASC,created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['whoutput_list']) ? intval($_COOKIE['whoutput_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		if($details){
			$da=array();
			$da['data']=array();
			$i=1;
			$output_id = '';
			foreach ($details as $each){
				$whoutput = $each->warehouseOutput;
				if($each->warehouse_output_id != $output_id){
					$mark = $i;
					$i++;
					$output_id = $each->warehouse_output_id;
					if($whoutput->output_type == "transfer"){
						$complete_url = Yii::app()->createUrl('warehouseOutput/transfer',array("ware_id"=>$whoutput->id,"fpage"=>$_REQUEST['page']));
					}else{
						$complete_url = Yii::app()->createUrl('frmOutput/create',array("id"=>$whoutput->frmsend->FrmSales->id,"fpage"=>$_REQUEST['page'],"ware_id"=>$whoutput->id));
					}
					$detail_url = Yii::app()->createUrl('warehouseOutput/detail',array('id'=>$whoutput->id,"fpage"=>$_REQUEST['page']));
					$wancheng_url = Yii::app()->createUrl('warehouseOutput/complete',array('id'=>$whoutput->id,"fpage"=>$_REQUEST['page']));
					if($whoutput->status == 0){
					$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$whoutput->output_no.'">'
							.'<a href="'.$complete_url.'" title="出库"><span><img src="/images/chuku.png"></span></a>'
							.'<a class="update_b" href="'.$detail_url.'" title="查看详情"><span><i class="icon icon-file-text-o"></i></span></a>'
							.'<span class="submit_form" url="'.$wancheng_url.'" title="已出库"><img src="/images/wancheng.png"></span>'
							.'</div>';
					}else{
						$operate='<a class="update_b" href="'.$detail_url.'" title="查看详情"><span><i class="icon icon-file-text-o"></i></span></a>';
					}
				}else{
					$mark='';
					$operate='';
				}
				$std=DictGoodsProperty::getProName($each->brand_id)."/".DictGoodsProperty::getProName($each->product_id)."/".DictGoodsProperty::getProName($each->texture_id)."/".DictGoodsProperty::getProName($each->rank_id)."/".$each->length;
				$da['data']=array(
						$mark,
						$operate,
						date("Y-m-d",$whoutput->created_at),
						$whoutput->dictTitle->short_name,
						$whoutput->output_no,
						$whoutput->sales_sn,
						//'<span title="'.$whoutput->dictCompany->name.'">'.$whoutput->dictCompany->short_name.'</span>',
						$each->card_no,
						$std,
						$whoutput->car_no,
						$each->amount,
						$each->weight,
						//$each->real_weight,
						//User::getUserName($whoutput->created_by),//制单人
						$each->remark,
				);
				$da['group']=$output_id;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages);
	}
	
	/*
	 * 仓库出库单保存，id为入库单id
	 */
	public static function setDetail($id,$post=array()){
		$output = WarehouseOutput::model()->findByPk($id);
		$oldJson=$output->datatoJson();
		$data['common']['form_type']='CKD';
		$data['common']['form_time']=date("Y-m-d H:i:s");
		$data['common']['commmet']=$post["output"]["remark"];
		$data['common']=(Object)$data['common'];
		
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		for($i=0;$i<count($post['amount']);$i++){
			$temp = array();
			//$temp["storage_id"] = $post["card_no"][$i];
			$temp["storage_id"] = Storage::getStroageid($post["card_no"][$i],$output->frmsend->FrmSales->warehouse_id);
			if(empty($temp["storage_id"])){return 0;}
			$temp["amount"] = $post["amount"][$i];
			$temp["weight"] = $post["weight"][$i];
			$temp["product_id"] = $post["product_id"][$i];
			$temp["rank_id"] = $post["rank_id"][$i];
			$temp["brand_id"] = $post["brand_id"][$i];
			$temp["texture_id"] = $post["texture_id"][$i];
			$temp["length"] = $post["length"][$i];
				
			$amount +=$post["amount"][$i];
			$weight +=$post["weight"][$i];
			array_push($data['detail'],(Object)$temp);
		}
		$post['out']['from'] = "storage";
		$post['out']['frm_sales_id'] = $output->frmsend->frm_sales_id;
		$post['out']['push_id'] = $output->id;
		$post['out']['amount'] = $amount;
		$post['out']['weight'] = $weight;
		$data['main']=$post['out'];
		$data['main']=(Object)$data['main'];
		
		$allform=new Output(0);
		if($post["submit_type"] == 1){
			$allform->createSubmitOutForm($data);
		}else{
			$allform->createForm($data);
		}
		$output->status = 1;
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"WarehouseOutput","newValue"=>$mainJson,"oldValue"=>$oldJson);
		$baseform = new BaseForm();
		$baseform->dataLog($dataArray);
		return true;
	}
	
	/*
	 * 仓库出库单保存，id为入库单id
	 */
	public static function ZdSetDetail($id){
		$output = WarehouseOutput::model()->findByPk($id);
		$oldJson=$output->datatoJson();
		$data['common']['form_type']='CKD';
		$data['common']['form_time']=date("Y-m-d H:i:s");
		$data['common']['commmet']=$post["output"]["remark"];
		$data['common']=(Object)$data['common'];
	
		$data['detail']=array();
		$detail = $output->warehouseOutputDetails;
		$amount = 0;
		$weight = 0;
		foreach($detail as $li){
			$temp = array();
			$temp["storage_id"] = Storage::getStroageid($li->card_no,$output->frmsend->FrmSales->warehouse_id);
			if(empty($temp["storage_id"])){return false;}
			$temp["amount"] =  $li->amount;
			$temp["weight"] =  $li->weight;
			$temp["product_id"] =  $li->product_id;
			$temp["rank_id"] =  $li->rank_id;
			$temp["brand_id"] =  $li->brand_id;
			$temp["texture_id"] =  $li->texture_id;
			$temp["length"] =  $li->length;
	
			$amount += $li->amount;
			$weight += $li->weight;
			array_push($data['detail'],(Object)$temp);
		}
		$post['out']['from'] = "storage";
		$post['out']['frm_sales_id'] = $output->frmsend->frm_sales_id;
		$post['out']['push_id'] = $output->id;
		$post['out']['amount'] = $amount;
		$post['out']['weight'] = $weight;
		$post['out']['output_at'] = time();
		$post['out']['is_return'] = 0;
		$data['main']=$post['out'];
		$data['main']=(Object)$data['main'];
		
		$allform=new Output(0);
		$result = $allform->createSubmitOutForm($data);
		
		if($result > 0){
			$output->status = 1;
			$output->update();
			$mainJson = $output->datatoJson();
			$dataArray = array("tableName"=>"WarehouseOutput","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
			return true;
		}else{
			return false;
		}
	}
	
	//自动处理转库数据
	public static function ZdSetTransfer($id){
		$output = WarehouseOutput::model()->findByPk($id);
		$oldJson = $output->datatoJson();
		$detail = $output->warehouseOutputDetails;
		$transaction=Yii::app()->db->beginTransaction();
		try {
			foreach($detail as $li){
				//更新聚合库存表
				$model = new MergeStorage();
				$criteria=New CDbCriteria();
				$criteria->addCondition('warehouse_id ='.$output->warehouse_id);
				$criteria->addCondition('product_id ='.$li->product_id);
				$criteria->addCondition('brand_id ='.$li->brand_id);
				$criteria->addCondition('texture_id ='.$li->texture_id);
				$criteria->addCondition('rank_id ='.$li->rank_id);
				$criteria->addCondition('length ='.$li->length);
				$criteria->addCondition('title_id ='.$output->title_id);
				$criteria->addCondition('is_transit = 0');
				$criteria->addCondition('is_deleted = 0');
				$merge = $model->find($criteria);
				if($merge){
					$oldJson=$merge->datatoJson();
					$merge->left_amount -= $each->amount;
					$merge->left_weight -= $each->weight;
					if($merge->update()){
						$mainJson = $merge->datatoJson();
						$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$baseform = new BaseForm();
						$baseform->dataLog($dataArray);
					}
				}
				$temp = array();
				$storage = Storage::find("warehouse_id=$output->warehouse_id and card_no=$li->card_no");
				if(!$storage){return false;}
				$amount = $li->amount;
				$weight = $li->weight;
				if($storage->left_amount < $amount){return false;}
				$storage->left_amount -=$amount;
				$storage->left_weight -=$weight;
				if(!$storage->update()){return false;}
			}
			$output->status = 1;
			$output->update();
			$mainJson = $output->datatoJson();
			$dataArray = array("tableName"=>"WarehouseOutput","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);

			$transaction->commit();
		}catch (Exception $e)
		{
			echo "操作失败";
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		
	}
	/*
	 * 处理推送数据
	 */
	public static function SetData($data)
	{
		$res = '{"result":"success","message":"'.urlencode('成功').'"}';
		if (!$data)
		{
			$res = '{"result":"error","message":"'.urlencode('数据不存在').'"}';
			return urldecode($res);
		}
		$data = $data->Body;
		$output['main']  =array();
		$output['detail']  =array();
		//$data = json_decode($data);
		$date = $data->RevisionTime;
		$type = $data->Verb;
		$table = $data->Content->Tables[0];
		$mainColumns = $table->Columns;
		$mainFields = $table->Records[0]->Fields;
		//var_dump($data);
		foreach($mainColumns as $key=>$value){
			$schema = $value->Schema;
			switch ($schema){
				case "form_id":
					$output['main']['frm_send_id'] = $mainFields[$key]->Text;
					break;
				case "warehouse_id":
					$output['main']['warehouse_id'] = $mainFields[$key]->Text;
					break;
				case "output_form_id":
					$output['main']['output_no'] = $mainFields[$key]->Text;
					break;
				case "owner_company":
					$output['main']['title_id'] = $mainFields[$key]->Value;
					break;
				case "buyer_company":
					$output['main']['customer_id'] = $mainFields[$key]->Value;
					break;
				case "created_at":
					$output['main']['created_at'] = $mainFields[$key]->Text;
					break;
				case "output_type":
					$output['main']['output_type'] = $mainFields[$key]->Text;
					break;
				case "auth_text":
					$output['main']['car_no'] = $mainFields[$key]->Text;
					break;
				case "content":
					$output['main']['remark'] = $mainFields[$key]->Text;
					break;
				default:
					break;
			}
		}
		$detailColumns = $table->Records[0]->Details[0]->Columns;
		$detailRecords = $table->Records[0]->Details[0]->Records;
		foreach($detailRecords as $li){
			$detailarr = array();
			$fields = $li->Fields;
			foreach($fields as $key=>$value){
				$schema = $detailColumns[$key]->Schema;
				switch ($schema){
					case "card_no":
						$detailarr['card_no'] = $value->Text;
						break;
					case "goods_company":
						$detailarr['brand_id'] = DictGoodPropertyRelation::getJxcId($value->Text,"brand");
						break;
					case "goods_name":
						$detailarr['product_id'] = DictGoodPropertyRelation::getJxcId($value->Text,"product");
						break;
					case "texture":
						$detailarr['texture_id'] = DictGoodPropertyRelation::getJxcId($value->Text,"texture");
						break;
					case "rank":
						$detailarr['rank_id'] = DictGoodPropertyRelation::getJxcId($value->Text,"rank");
						break;
					case "length":
						$detailarr['length'] = $value->Text;
						break;
					case "amount":
						$detailarr['amount'] = $value->Text;
						break;
					case "weight":
						$detailarr['weight'] = $value->Text;
						break;
					default:
						break;
				}
			}
			array_push($output['detail'],$detailarr);
		}
		//var_dump($output);die;
		$transaction=Yii::app()->db->beginTransaction();
		try {
				$result = WarehouseOutput::CreateOut($output);
				//更新虚拟出库信息
				FrmSend::setVirtual($result);
				$transaction->commit();
		}catch (Exception $e)
		{
			//echo "操作失败";
			$res = '{"result":"error","message":"'.urlencode('数据保存失败').'"}';
			$transaction->rollBack();//事务回滚
			return urldecode($res);
		}
		if($result){
			if($result->output_type == "transfer"){
				//WarehouseOutput::ZdSetTransfer($result->id);
			}else{
				//$zd_r = WarehouseOutput::ZdSetDetail($result->id);
				$frm_send_id = $output['main']['frm_send_id'];
				$frmSend = FrmSend::model()->findByPk($frm_send_id);
				$xiaohui_id = User::getUserId("蔡小辉");
				if($zd_r){
					//发送消息
					$message = array();
					if($frmSend->baseform->created_by != $xiaohui_id){
						$message['receivers'] = $frmSend->baseform->created_by.",".$xiaohui_id;
					}else{
						$message['receivers'] = $frmSend->baseform->created_by;
					}
					$message['content'] = "您的配送单：".$frmSend->baseform->form_sn."已经出库。";
					$message['title'] = "出库通知";
					//$message['url'] = "";
					$message['type'] = "配送单";
					$message['big_type']='ware';
					MessageContent::model()->addMessage($message);
				}else{
					//发送消息
					$message = array();
					if($frmSend->baseform->created_by != $xiaohui_id){
						$message['receivers'] = $frmSend->baseform->created_by.",".$xiaohui_id;
					}else{
						$message['receivers'] = $frmSend->baseform->created_by;
					}
					$message['content'] = "您的配送单：".$frmSend->baseform->form_sn."已经出库。";
					$message['title'] = "出库通知";
					//$message['url'] = "";
					$message['type'] = "配送单";
					$message['big_type']='ware';
					MessageContent::model()->addMessage($message);
				}
			}
		}
		return urldecode($res);
	}
	
	public static function CreateOut($output)
	{
		$main = $output['main'];
		$send = FrmSend::model()->findByPk($main['frm_send_id']);
		$sales = $send->FrmSales;
		$detail = $output['detail'];
		$warehouse = new WarehouseOutput();
		$warehouse->frm_send_id = $main['frm_send_id'];
		$warehouse->warehouse_id = $main['warehouse_id'];
		$warehouse->output_no = $main['output_no'];
		$warehouse->title_id = $main['title_id'];
		$warehouse->customer_id = $main['customer_id'];
		$warehouse->created_at = $main['created_at'];
		$warehouse->output_type = $main['output_type'];
		$warehouse->car_no = $main['car_no'];
		$warehouse->remark = $main['remark'];
		$warehouse->sales_id = $sales->id;
		$warehouse->sales_sn = $sales->baseform->form_sn;
		$warehouse->insert();
		$warehousename = $sales->warehouse->name;
		$i = 1;
		$showamount = 0;
		if($detail){
			foreach ($detail as $li){
				if($i == 1){
					$showgc_name = DictGoodsProperty::getProName($li['brand_id']);
					$goodsname = DictGoodsProperty::getProName($li['product_id']);
					$i++;
				}
				$waredetail = new WarehouseOutputDetail();
				$waredetail->warehouse_output_id = $warehouse->id;
				$waredetail->card_no = $li['card_no'];
				$waredetail->brand_id = $li['brand_id'];
				$waredetail->product_id = $li['product_id'];
				$waredetail->texture_id = $li['texture_id'];
				$waredetail->rank_id = $li['rank_id'];
				$waredetail->length = intval($li['length']);
				$waredetail->amount = $li['amount'];
				$waredetail->weight = $li['weight'];
				$waredetail->insert();
				$showamount  +=$li['amount'];
			}
		}
		//给联系人发送短信
		$contentarray["company"]=$sales->dictCompany->name;
		$contentarray["code"]= "您的“" . $showgc_name . " " . $goodsname . "”的货物，共“" . $showamount . "”件";
		$contentarray["warehouse"] = $warehousename;
		$sendmess = new Sendmessage();
		$sendmess->frm_send_id = $send->id;
		$sendmess->company_id = $sales->customer_id;
		$sendmess->phone = $sales->companycontact->mobile;
		$sendmess->content = json_encode($contentarray);
		$sendmess->status = 0;
		$sendmess->create_at = time();
		$sendmess->module_id = 1080933;
		$sendmess->insert();
		
		return $warehouse;
	}
	
	//获取仓库出库单信息数组，供出库使用
	public static function getNeed($id){
		$sql = "select product_id,brand_id,texture_id,rank_id,length,sum(amount) as t_amount,sum(weight) as t_weight from warehouse_output_detail where warehouse_output_id=".$id." group by product_id,brand_id,texture_id,rank_id,length";
		$cmd = Yii::app()->db->createCommand($sql);
		$waredetail = $cmd->queryAll($cmd);
		$array = array();
		if($waredetail){
			foreach ($waredetail as $each){
				$temp = array();
				$length = intval($each["length"]);
				$std=DictGoodsProperty::getProName($each["brand_id"])."/".DictGoodsProperty::getProName($each["product_id"])."/"
						.DictGoodsProperty::getProName($each["texture_id"])."/".DictGoodsProperty::getProName($each["rank_id"])."/".$length;
				$key =$each["brand_id"]. $each["product_id"].$each["texture_id"].$each["rank_id"].$length;
				$temp["key"] =  $key;
				$temp['std']= $std;
				$temp["amount"] = $each["t_amount"];
				$temp["weight"] = $each["t_weight"];
				array_push($array,$temp);
			}
		}
		return $array;
	}
	
}
