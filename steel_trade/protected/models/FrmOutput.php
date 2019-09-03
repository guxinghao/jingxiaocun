<?php

/**
 * This is the biz model class for table "frm_output".
 *
 */
class FrmOutput extends FrmOutputData
{
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'outputDetails' => array(self::HAS_MANY, 'OutputDetail', 'frm_output_id'),
			'warehouseOutputs' => array(self::BELONGS_TO, 'WarehouseOutput', 'push_id'),
			'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="CKD"'),
			'frmsales'=>array(self::BELONGS_TO,'FrmSales','frm_sales_id'),
			'outputby'=>array(self::BELONGS_TO, 'User', 'output_by'),
			'frmreturn'=>array(self::BELONGS_TO,'FrmPurchaseReturn','frm_sales_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'output_type' => 'Output Type',
			'sales_detail_id' => 'Sales Detail',
			'frm_sales_id' => 'Frm Sales',
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
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight,true);
		$criteria->compare('output_type',$this->output_type,true);
		$criteria->compare('sales_detail_id',$this->sales_detail_id);
		$criteria->compare('frm_sales_id',$this->frm_sales_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmOutput the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 创建一个出库
	 */
	public static function createOutput($post=array(),$ware_id=0)
	{
		$post['CommonForms']['form_type']='CKD';
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$data['common']=(Object)$post['CommonForms'];
	
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		for($i=0;$i<count($post["amount"]);$i++){
			$temp = array();
			$temp["storage_id"] = $post["card_id"][$i];
			$temp["amount"] = intval($post["amount"][$i]);
			$temp["weight"] = $post["weight"][$i];
			$temp["product_id"] = $post["product"][$i];
			$temp["rank_id"] = $post["rank"][$i];
			$temp["brand_id"] = $post["brand"][$i];
			$temp["texture_id"] = $post["texture"][$i];
			$temp["length"] = $post["length"][$i];
			$temp["sales_detail_id"] = intval($post["sales_detail_id"][$i]);
			
			$amount +=$post["amount"][$i];
			$weight +=$post["weight"][$i];
			array_push($data['detail'],(Object)$temp);
		}
		if($ware_id){
			$post['out']['from'] = "storage";
			$post['out']['push_id'] = $ware_id;
		}else{
			$post['out']['from'] = "purchase";
		}
		$post['out']['amount'] = $amount;
		$post['out']['weight'] = $weight;
		$post['out']['is_return'] = 0;
		$data['main']=$post['out'];
		$data['main']=(Object)$data['main'];
		$allform=new Output($id);
		if($post['submit_type'] == 1){
			$result = $allform->createSubmitOutForm($data);
		}else{
			$result = $allform->createForm($data);
		}
		return $result;
	}
	
	/**
	 * 创建一个转库
	 */
	public static function createZKOutput($post=array(),$id)
	{
		$storage = new MergeStorage();
		$criteria=New CDbCriteria();
		$criteria->addCondition('is_deleted=0');
		$criteria->addCondition('is_transit=0');
		$criteria->addCondition('left_amount>0');
		$storage = $storage->findAll($criteria);
		$message[1]= array();
		$message[2]= array();
		$message[3]= array();
		$message[4]= array();
		foreach ($storage as $li){
			$temp = array();
			$temp[0] = $li->product_id;
			$temp[1] = $li->texture_id;
			$temp[2] = $li->brand_id;
			$temp[3] = $li->rank_id;
			$temp[4] = $li->length;
			$temp[5] = $li->id;
			$warehouse_id = $li->warehouse_id;
			array_push($message[$warehouse_id],$temp);
		}
		
		
		$user = array(1,9,10,11,13,15,16,17,18,19,20,21,22,23,24);//随机获取部分业务员数组
		$title_id = array(11,12,14);//公司抬头id
		$gao = array(7,8);//高开结算单位
		$post['CommonForms']['owned_by']=$user[array_rand($user)];
		$post['CommonForms']['form_time']=date("Y-m-d");
		$post['CommonForms']['form_type']='CKD';
		$data['common']=(Object)$post['CommonForms'];
		$data['main']["title_id"]=$title_id[array_rand($title_id)];
		$data['main']["customer_id"]=mt_rand(1,3759);
		$data['main']["sales_type"]="normal";
		$w_id = mt_rand(1,4);
		$data['main']["warehouse_id"]=$w_id;
		$data['main']["comment"]="随机生成的销售单";
		$data['detail']=array();
		$has_bonus_price = 0;
		$arr = $message[array_rand($message[$w_id])];
		
		$num = mt_rand(1,4);//随机生成数子，确定几条明细
		for($i=0;$i<$num;$i++)
		{
		$arr = $message[$w_id][array_rand($message[$w_id])];
		$temp=array();
		$temp['product_id']=$arr[0];
		$temp['texture_id']=$arr[1];
		$temp['brand_id']=$arr[2];
		$temp['rank_id']=$arr[3];
		$temp['length']=$arr[4];
		$weight = DictGoods::getUnitWeightID($temp);
		if($weight == 0){$weight=2;}
		$amount = mt_rand(1,10);
		$price = mt_rand(1800,2000);
		$temp['amount']=$amount;
			$temp['weight']=$amount*$weight;
					$temp['price']=$price;
					$is_high = mt_rand(1,20);
					if($is_high == 1){
					$temp['bonus_price']=mt_rand(1,100);
					$temp['gk_id'] = $gao[array_rand($gao)];
		}else{
		$temp['bonus_price']=0;
						$temp['gk_id'] = 0;
					}
					if($temp['bonus_price'] >0){$has_bonus_price = 1;}
					$temp['card_id']=$arr[5];
					$temp['total_amount'] = $amount * $weight * $price;
					array_push($data['detail'], (Object)$temp);
		}
							$data['main']["has_bonus_price"]=$has_bonus_price;
									$data['main']=(Object)$data['main'];
									
		$post['CommonForms']['form_type']='CKD';
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$data['common']=(Object)$post['CommonForms'];
	
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		for($i=0;$i<count($post["amount"]);$i++){
			$temp = array();
			$temp["storage_id"] = $post["card_id"][$i];
			$temp["amount"] = intval($post["amount"][$i]);
			$temp["weight"] = $post["weight"][$i];
			$temp["product_id"] = $post["product"][$i];
			$temp["rank_id"] = $post["rank"][$i];
			$temp["brand_id"] = $post["brand"][$i];
			$temp["texture_id"] = $post["texture"][$i];
			$temp["length"] = $post["length"][$i];
			$temp["sales_detail_id"] = 0;
				
			$amount +=$post["amount"][$i];
			$weight +=$post["weight"][$i];
			array_push($data['detail'],(Object)$temp);
		}
		$post['out']['from'] = "storage";
		$post['out']['amount'] = $amount;
		$post['out']['weight'] = $weight;
		$post['out']['is_return'] = 0;
		$post['out']['push_id'] = $id;
		$data['main']=$post['out'];
		$data['main']=(Object)$data['main'];
		$allform=new Output($id);
		$result = $allform->createSubmitOutForm($data);
		return $result;
	}
	/*
	 * 获取出库单列表
	 * id为销售单id，如果为空则查询所有的销售单
	 */
	public static function getFormList($search,$id="")
	{
		$tableData=array();
		$model = new OutputDetail();
		$criteria=New CDbCriteria();
		$withArray = array();	
		$criteria->with = array("frmOutput",'frmOutput.frmsales','frmOutput.frmsales.baseform2'=>array("condition"=>"baseform2.form_type='XSD'"),'frmOutput.baseform'=>array('order'=>'baseform.created_at DESC'),"storage");
// 		$criteria->with = array("frmOutput",'frmOutput.frmsales','frmOutput.baseform'=>array('order'=>'baseform.created_at DESC'),"storage");
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno or storage.card_no like :contno or baseform2.form_sn like :contno');
// 				$criteria->addCondition('baseform.form_sn like :contno or storage.card_no like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
				
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('t.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('t.product_id',$search['product'],false);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('t.rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],false);
			}
			if($search['sales_status'])
			{
				$criteria->compare('frmsales.sales_type',$search['sales_status'],false);
			}
		}
		
		if($search['form_status'])
		{
			$criteria->compare('baseform.form_status',$search['form_status'],false);
		}else{
			$criteria->compare('baseform.is_deleted','0',false);
		}
		if($id){
			$criteria->compare('frmOutput.frm_sales_id',$id,false);
			$criteria->compare('frmOutput.is_return',0,false);
		}
		//$criteria->compare('baseform.form_type','XSPS',true);
// 		if(!$id){
// 			$criteria->compare('frmsales.confirm_status',0,false);
// 		}
		//$criteria->compare('frmOutput.is_return',0,false);
		$c = clone $criteria;
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['frmOutput_list']) ? intval($_COOKIE['frmOutput_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		$c->select = "sum(t.amount) as total_amount,sum(t.weight) as total_weight,count(*) as total_num";
		$alldetail = OutputDetail::model()->find($c);

		$totaldata = array();
		$totaldata["amount"] = $alldetail->total_amount;
		$totaldata["weight"] = $alldetail->total_weight;
		//$totaldata["price"] = $alldetail->total_price;
		$totaldata["total_num"] = $alldetail->total_num;
		$status = array("未出库","已出库","已作废");
		$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
		if($details){
			$da=array();
			$da['data']=array();
			$i=1;
			$baseform='';
			foreach ($details as $each)
			{
				$frmSales = $each->frmOutput->frmsales;
				$frmOutput = $each->frmOutput;
				$mark = $i;
				if($each->frmOutput->baseform != $baseform){
					$baseform = $each->frmOutput->baseform;
					$i++;
					if($frmOutput->input_status == 0)
					{
						$type_sub="submit";
						$title_sub="出库";
						$img_url = "/images/chuku.png";
					}elseif($frmOutput->input_status == 1)
					{
						$type_sub="cancle";
						$title_sub="取消出库";
						$img_url = "/images/qxck.png";
					}
					
					$trash_url = Yii::app()->createUrl('FrmOutput/deleteform',array("id"=>$baseform->id,'last_update'=>$baseform->last_update,"fpage"=>$_REQUEST['page']));
					$detail_url = Yii::app()->createUrl('FrmOutput/detail',array('id'=>$each->frm_output_id,"fpage"=>$_REQUEST['page'],"sid"=>$id));
					
					
					if($frmOutput->is_return == 1){
						$edit_url = Yii::app()->createUrl('FrmOutput/rtupdate',array("id"=>$each->frm_output_id,"fpage"=>$_REQUEST['page'],"sid"=>$id,"from"=>$_REQUEST['from']));
						$sub_url =  Yii::app()->createUrl('FrmOutput/xssubmit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
						$detail_sales = Yii::app()->createUrl('FrmPurchaseReturn/detail',array('id'=>$frmOutput->frmreturn->id));
						$detail_name = $frmOutput->frmreturn->baseform->form_sn;
					}else{
						$detail_sales = Yii::app()->createUrl('FrmSales/detail',array('id'=>$frmSales->baseform->id));
						$detail_name = $frmSales->baseform->form_sn;
						if($frmSales->sales_type == "normal"){
							$edit_url = Yii::app()->createUrl('FrmOutput/update',array("id"=>$each->frm_output_id,"fpage"=>$_REQUEST['page'],"sid"=>$id,"from"=>$_REQUEST['from']));
							$sub_url =  Yii::app()->createUrl('FrmOutput/submit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
						}else if($frmSales->sales_type == "xxhj"){
							$edit_url = Yii::app()->createUrl('FrmOutput/xsupdate',array("id"=>$each->frm_output_id,"fpage"=>$_REQUEST['page'],"sid"=>$id,"from"=>$_REQUEST['from']));
							$sub_url =  Yii::app()->createUrl('FrmOutput/xssubmit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
						}else{
							$edit_url = Yii::app()->createUrl('FrmOutput/dxupdate',array("id"=>$each->frm_output_id,"fpage"=>$_REQUEST['page'],"sid"=>$id,"from"=>$_REQUEST['from']));
							$sub_url =  Yii::app()->createUrl('FrmOutput/xssubmit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
						}
					}
					if($frmOutput->is_return == 1){
						if($frmOutput->frmreturn->confirm_status == 0){
							$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
							if(checkOperation("出库单:新增")){
								if($title_sub){
									$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';
								}
								if($frmOutput->input_status == 0){
									$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
									$operate.='<span class="delete_form" lastdate="'.$baseform->last_update.'" id="/index.php/FrmOutput/deleteform/'.$baseform->id.'" onclick="deleteIt(this);" title="作废"><img src="/images/zuofei.png"></span>';
								}
							}
							$operate.='</div>';
						}else{
							$operate.='';
						}
					}else{
						$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
						if(checkOperation("出库单:新增")){
							if($title_sub){
								$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';
							}
							if($frmOutput->input_status == 0){
								$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
								$operate.='<span class="delete_form" lastdate="'.$baseform->last_update.'" id="/index.php/FrmOutput/deleteform/'.$baseform->id.'" onclick="deleteIt(this);" title="作废"><img src="/images/zuofei.png"></span>';
							}
						}
						$operate.='</div>';
					}
				}else{
					$mark='';
					$operate='';
				}
				$std=DictGoodsProperty::getProName($each->brand_id)."/".DictGoodsProperty::getProName($each->product_id)."/".DictGoodsProperty::getProName($each->texture_id)."/".DictGoodsProperty::getProName($each->rank_id)."/".intval($each->length);
				if($frmOutput->is_return == 1){
					$return =  $frmOutput->frmreturn;
					$da['data']=array(
						$mark,
						$operate,
						'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$baseform->form_sn.'</a>',
						$status[$frmOutput->input_status],
						$baseform->form_time,
						$return->dictTitle->short_name,
						'<span title="'.$return->supply->name.'">'.$return->supply->short_name.'</span>',
						$each->storage->card_no,
						str_replace('E','<span class="red">E</span>',$std),
						//$frmSales->travel,
						$each->amount,
						number_format($each->weight,3),
						'<a href="'.$detail_sales.'" title="查看详情" class="a_view">'.$detail_name.'</a>',
						"采购退货",
						$baseform->operator->nickname,//制单人
						$frmOutput->outputby->nickname,
						$frmOutput->output_at>0?date("Y-m-d",$frmOutput->output_at):"",
						//$baseform->comment,
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment,0,15,"UTF-8").'</span>',
					);
				}else{
					$da['data']=array(
						$mark,
						$operate,
						'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$baseform->form_sn.'</a>',
						$status[$frmOutput->input_status],
						$baseform->form_time,
						$frmSales->dictTitle->short_name,
						'<span title="'.$frmSales->dictCompany->name.'">'.$frmSales->dictCompany->short_name.'</span>',
						$each->storage->card_no,
						str_replace('E','<span class="red">E</span>',$std),
						//$frmSales->travel,
						$each->amount,
						number_format($each->weight,3),
						'<a href="'.$detail_sales.'" title="查看详情" class="a_view">'.$detail_name.'</a>',
						$sales_type[$frmSales->sales_type],
						$baseform->operator->nickname,//制单人
						$frmOutput->outputby->nickname,
						$frmOutput->output_at>0?date("Y-m-d",$frmOutput->output_at):"",
						//$baseform->comment,
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment,0,15,"UTF-8").'</span>',
					);
				}
				if($baseform->form_status == "delete"){
					array_push($da['data'],'<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason,0,15,"UTF-8").'</span>');
				}
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	}
	
	/*
	 * 获取退货出库单列表
	 * id为退货单id，如果为空则查询所有的退货单
	 */
	public static function getReturnFormList($search,$id="")
	{
		$tableData=array();
		$model = new OutputDetail();
		$criteria=New CDbCriteria();
		$withArray = array();
		$criteria->with = array("frmOutput",'frmOutput.frmreturn','frmOutput.baseform'=>array('order'=>'baseform.created_at DESC'),"storage");
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno or storage.card_no like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
	
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('t.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('t.product_id',$search['product'],false);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('t.rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],false);
			}
		}
	
		if($search['form_status'])
		{
			$criteria->compare('baseform.form_status',$search['form_status'],false);
		}else{
			$criteria->compare('baseform.is_deleted','0',false);
		}
		$criteria->compare('frmOutput.frm_sales_id',$id,false);
		$criteria->compare('frmOutput.is_return',1,false);
		$c = clone $criteria;
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['frmOutput_list']) ? intval($_COOKIE['frmOutput_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		$c->select = "sum(t.amount) as total_amount,sum(t.weight) as total_weight,count(*) as total_num";
		$alldetail = OutputDetail::model()->find($c);
		$totaldata = array();
		$totaldata["amount"] = $alldetail->total_amount;
		$totaldata["weight"] = $alldetail->total_weight;
		//$totaldata["price"] = $alldetail->total_price;
		$totaldata["total_num"] = $alldetail->total_num;
		$status = array("未出库","已出库","已作废");
		if($details){
			$da=array();
			$da['data']=array();
			$i=1;
			$baseform='';
			foreach ($details as $each)
			{
				$frmSales = $each->frmOutput->frmreturn;
				$frmOutput = $each->frmOutput;
				$mark = $i;
				if($each->frmOutput->baseform != $baseform){
					$baseform = $each->frmOutput->baseform;
					$i++;
					if($frmOutput->input_status == 0)
					{
						$type_sub="submit";
						$title_sub="出库";
						$img_url = "/images/chuku.png";
					}elseif($frmOutput->input_status == 1)
					{
						$type_sub="cancle";
						$title_sub="取消出库";
						$img_url = "/images/qxck.png";
					}
						
					$trash_url = Yii::app()->createUrl('FrmOutput/deleteform',array("id"=>$baseform->id,'last_update'=>$baseform->last_update,"fpage"=>$_REQUEST['page']));
					$detail_url = Yii::app()->createUrl('FrmOutput/detail',array('id'=>$each->frm_output_id,"fpage"=>$_REQUEST['page'],"sid"=>$id));
					$detail_sales = Yii::app()->createUrl('FrmPurchaseReturn/detail',array('id'=>$frmSales->baseform->id));
					$edit_url = Yii::app()->createUrl('FrmOutput/rtupdate',array("id"=>$each->frm_output_id,"fpage"=>$_REQUEST['page'],"sid"=>$id,"from"=>$_REQUEST['from']));
					$sub_url =  Yii::app()->createUrl('FrmOutput/xssubmit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
					if($frmSales->confirm_status == 0){
						$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
						if(checkOperation("出库单:新增")){
							if($title_sub){
								$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';
							}
							if($frmOutput->input_status == 0){
								$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
								$operate.='<span class="delete_form" lastdate="'.$baseform->last_update.'" id="/index.php/FrmOutput/deleteform/'.$baseform->id.'" onclick="deleteIt(this);" title="作废"><img src="/images/zuofei.png"></span>';
							}
						}
						$operate.='</div>';
					}else{
						$operate='';
					}
				}else{
					$mark='';
					$operate='';
				}
				$std=DictGoodsProperty::getProName($each->brand_id)."/".DictGoodsProperty::getProName($each->product_id)."/".DictGoodsProperty::getProName($each->texture_id)."/".DictGoodsProperty::getProName($each->rank_id)."/".intval($each->length);
				$da['data']=array(
						$mark,
						$operate,
						'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$baseform->form_sn.'</a>',
						$status[$frmOutput->input_status],
						$baseform->form_time,
						$frmSales->dictTitle->short_name,
						'<span title="'.$frmSales->supply->name.'">'.$frmSales->supply->short_name.'</span>',
						$each->storage->card_no,
						str_replace('E','<span class="red">E</span>',$std),
						//$frmSales->travel,
						$each->amount,
						number_format($each->weight,3),
						'<a href="'.$detail_sales.'" title="查看详情" class="a_view">'.$frmSales->baseform->form_sn.'</a>',
						"采购退货",
						$baseform->operator->nickname,//制单人
						$frmOutput->outputby->nickname,
						$frmOutput->output_at>0?date("Y-m-d",$frmOutput->output_at):"",
						//$baseform->comment,
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment,0,15,"UTF-8").'</span>',
				);
				if($baseform->form_status == "delete"){
					array_push($da['data'],'<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason,0,15,"UTF-8").'</span>');
				}
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	}
	
	/**
	 * 修改一个出库
	 */
	public static function updateOutput($post=array())
	{
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$data['common']=(Object)$post['CommonForms'];
	
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		for($i=0;$i<count($post["amount"]);$i++){
			$temp = array();
			$temp["id"] = $post["output_id"][$i];
			$temp["storage_id"] = $post["card_id"][$i];
			$temp["amount"] = intval($post["amount"][$i]);
			$temp["weight"] = $post["weight"][$i];
			$temp["product_id"] = $post["product"][$i];
			$temp["rank_id"] = $post["rank"][$i];
			$temp["brand_id"] = $post["brand"][$i];
			$temp["texture_id"] = $post["texture"][$i];
			$temp["length"] = $post["length"][$i];
			$temp["sales_detail_id"] = intval($post["sales_detail_id"][$i]);
			$amount +=$post["amount"][$i];
			$weight +=$post["weight"][$i];
			array_push($data['detail'],(Object)$temp);
		}
		$post['out']['amount'] = $amount;
		$post['out']['weight'] = $weight;
		$post['out']['is_return'] = 0;
		$data['main']=$post['out'];
		$data['main']=(Object)$data['main'];

		return $data;
	}
	
	/**
	 * 修改一个退货出库
	 */
	public static function updateReturnOutput($post=array())
	{
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$data['common']=(Object)$post['CommonForms'];
	
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		for($i=0;$i<count($post["amount"]);$i++){
			$temp = array();
			$temp["id"] = $post["output_id"][$i];
			$temp["storage_id"] = $post["card_id"][$i];
			$temp["amount"] = intval($post["amount"][$i]);
			$temp["weight"] = $post["weight"][$i];
			$temp["product_id"] = $post["product"][$i];
			$temp["rank_id"] = $post["rank"][$i];
			$temp["brand_id"] = $post["brand"][$i];
			$temp["texture_id"] = $post["texture"][$i];
			$temp["length"] = $post["length"][$i];
			$temp["sales_detail_id"] = intval($post["sales_detail_id"][$i]);
			$amount +=$post["amount"][$i];
			$weight +=$post["weight"][$i];
			array_push($data['detail'],(Object)$temp);
		}
		$post['out']['amount'] = $amount;
		$post['out']['weight'] = $weight;
		$post['out']['is_return'] = 1;
		$data['main']=$post['out'];
		$data['main']=(Object)$data['main'];
		return $data;
	}
	
	/**
	 * 创建退货出库
	 */
	public static function createRtOutput($post=array())
	{
		$post['CommonForms']['form_type']='CKD';
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$data['common']=(Object)$post['CommonForms'];
	
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		for($i=0;$i<count($post["amount"]);$i++){
			$temp = array();
			$temp["storage_id"] = $post["card_id"][$i];
			$temp["amount"] = intval($post["amount"][$i]);
			$temp["weight"] = $post["weight"][$i];
			$temp["product_id"] = $post["product"][$i];
			$temp["rank_id"] = $post["rank"][$i];
			$temp["brand_id"] = $post["brand"][$i];
			$temp["texture_id"] = $post["texture"][$i];
			$temp["length"] = $post["length"][$i];
			$temp["sales_detail_id"] = intval($post["sales_detail_id"][$i]);
				
			$amount +=$post["amount"][$i];
			$weight +=$post["weight"][$i];
			array_push($data['detail'],(Object)$temp);
		}
		$post['out']['from'] = "return";
		$post['out']['amount'] = $amount;
		$post['out']['weight'] = $weight;
		$post['out']['is_return'] = 1;
		$data['main']=$post['out'];
		$data['main']=(Object)$data['main'];
		$allform=new Output($id);
		if($post['submit_type'] == 1){
			$result = $allform->createSubmitOutForm($data);
		}else{
			$result = $allform->createForm($data);
		}
		return $result;
	}
	
	//随机生成出库单
	public static function RandOutput($message){
		$user = array(1,9,10,11,13,15,16,17,18,19,20,21,22,23,24);//随机获取部分业务员数组
		$title_id = array(11,12,14);//公司抬头id
		$gao = array(7,8);//高开结算单位
		$post['CommonForms']['owned_by']=$user[array_rand($user)];
		$post['CommonForms']['form_time']=date("Y-m-d");
		$post['CommonForms']['form_type']='CKD';
		$post['CommonForms']['comment']='随机生成的出库单';
		$data['common']=(Object)$post['CommonForms'];
		$data['main']["from"]="purchase";
		$data['main']["frm_sales_id"]=mt_rand(1,10000);
		$data['main']["is_return"]=0;
		$w_id = mt_rand(1,4);
		$data['detail']=array();
		$arr = $message[array_rand($message[$w_id])];
		$num = mt_rand(1,4);//随机生成数子，确定几条明细
		for($i=0;$i<$num;$i++)
		{
			$arr = $message[$w_id][array_rand($message[$w_id])];
			$temp=array();
			$temp['product_id']=$arr[0];
			$temp['texture_id']=$arr[1];
			$temp['brand_id']=$arr[2];
			$temp['rank_id']=$arr[3];
			$temp['length']=$arr[4];
			$weight = DictGoods::getUnitWeightID($temp);
			if($weight == 0){$weight=2;}
			$amount = mt_rand(1,10);
			$price = mt_rand(1800,2000);
			$temp['amount']=$amount;
			$temp['weight']=$amount*$weight;
			$temp['sales_detail_id']=0;
			$temp['storage_id']=$arr[5];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']=(Object)$data['main'];
		$allform=new Output($id);
		$result = $allform->createForm($data);
		return $result;
	}
}
