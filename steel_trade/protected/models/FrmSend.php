<?php

/**
 * This is the biz model class for table "frm_send".
 *
 */
class FrmSend extends FrmSendData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'FrmSales'=>array(self::BELONGS_TO,'FrmSales','frm_sales_id'),
			'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="XSPS"'),
			'sendDetails' => array(self::HAS_MANY, 'FrmSendDetail', 'frm_send_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'auth_type' => 'Auth Type',
			'auth_text' => 'Auth Text',
			'is_complete' => 'Is Complete',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'amount' => 'Amount',
			'weight' => 'Weight',
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
		$criteria->compare('auth_type',$this->auth_type,true);
		$criteria->compare('auth_text',$this->auth_text,true);
		$criteria->compare('is_complete',$this->is_complete);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('frm_sales_id',$this->frm_sales_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 创建一个配送单
	 */
	public static function createSend($post=array())
	{
		$post['CommonForms']['form_type']='XSPS';
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$data['common']=(Object)$post['CommonForms'];
		
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		for($i=0;$i<count($post["amount"]);$i++){
			$temp = array();
			$temp["amount"] = $post["amount"][$i];
			$temp["weight"] = $post["weight"][$i];
			$temp["product_id"] = $post["product"][$i];
			$temp["rank_id"] = $post["rank"][$i];
			$temp["brand_id"] = $post["brand"][$i];
			$temp["texture_id"] = $post["texture"][$i];
			$temp["length"] = $post["length"][$i];
			$temp["sales_detail_id"] = $post["sales_detail_id"][$i];
			$temp["start_time"] = $post["start_time"][$i];
			$temp["end_time"] = $post["end_time"][$i];
			$amount +=$post["amount"][$i];
			$weight +=$post["weight"][$i];
			array_push($data['detail'],(Object)$temp);
		}
		$post['send']['amount'] = $amount;
		$post['send']['weight'] = $weight;
		$data['main']=$post['send'];
		$data['main']=(Object)$data['main'];
	
		$allform=new Frm_Send($id);
		$allform->createForm($data);
		return $allform;
	}
	
	/**
	 * 根据销售单id查询所有此订单下的配送车牌号
	 */
	public static function gerCarNum($id)
	{
		$model = new FrmSend();
		$criteria=New CDbCriteria();
		
		$criteria->addCondition("t.auth_type ='car'");
		$criteria->addCondition("t.frm_sales_id=".$id);
		$criteria->group = 't.auth_text';
		
		$result=$model->findAll($criteria);
		return $result;
	}
	
	/*
	 * 获取配送单列表
	 * id为销售单id，如果为空则查询所有的销售单
	 */
	public static function getFormList($search,$id="",$view)
	{
		$tableData=array();
		$model = new FrmSendDetail();
		$criteria=New CDbCriteria();
		$withArray = array();
		$status = array("unpush"=>"未推送","pushing"=>"推送中","pushfaild"=>"推送失败","pushed"=>"待出库","output"=>"已出库","出库失败"=>"outputfaild","finished"=>"已完成","deleted"=>"已作废","deleting"=>"作废中","deletfaild"=>"作废失败");

		$criteria->with = array("frmSend",'frmSend.FrmSales','frmSend.FrmSales.baseform3','frmSend.baseform'=>array('order'=>'baseform.created_at DESC'));
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno or baseform3.form_sn like :contno');
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
			if($search['title_id']!='0')
			{
				$criteria->compare('FrmSales.title_id',$search['title_id'],true);
			}
				
			if($search['customer_id']!='0')
			{
				$criteria->compare('FrmSales.customer_id',$search['customer_id'],true);
			}
				
			if($search['team']!='0')
			{
				$criteria->compare('FrmSales.team_id',$search['team'],true);
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
			if($search['length']>=0)
			{
				$criteria->compare('t.length',$search['length'],false);
			}
			//制单人
			if($search['owned_by']!='0')
			{
				$criteria->compare('baseform.owned_by',$search['owned_by'],false);
			}
			//车牌号或提货单号
			if($search['auth_text']!=''){
				$criteria->compare('frmSend.auth_text',$search['auth_text'],true);
			}
			if(trim($search['text']) != ""){
				$criteria->compare('frmSend.auth_code',$search['text'],true);
			}
		}
		
		if($search['status'])
		{
			$criteria->compare('frmSend.status',$search['status'],false);
		}else{
			//$criteria->addCondition("frmSend.status != 'deleted'");
			//$criteria->addCondition("frmSend.is_complete=0");
		}
		
		if($id){
			$criteria->compare('frmSend.frm_sales_id',$id,false);
		}else if($view != "checkview"){
			$userId = currentUserId();
			$criteria->addCondition("baseform.owned_by=".$userId." or baseform.created_by=".$userId);
		}
		//$criteria->compare('baseform.form_type','XSPS',true);
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['frmsend_list']) ? intval($_COOKIE['frmsend_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		
		if($details){
			$da=array();
			$da['data']=array();
			$i=1;
			$baseform='';
			foreach ($details as $each)
			{
				//$salesDetails = $each->salesDetails;
				$frmSales = $each->frmSend->FrmSales;
				$frmSend = $each->frmSend;
				$mark = $i;
				$operate="";
				if($each->frmSend->baseform != $baseform){
					$baseform = $each->frmSend->baseform;
					$i++;
					$trash_url = Yii::app()->createUrl('FrmSend/deleteform',array("id"=>$baseform->id,'last_update'=>$baseform->last_update,"fpage"=>$_REQUEST['page']));
					$edit_url = Yii::app()->createUrl('FrmSend/update',array("id"=>$each->frm_send_id,"fpage"=>$_REQUEST['page'],"sid"=>$id));
					$detail_url = Yii::app()->createUrl('FrmSend/detail',array('id'=>$each->frm_send_id,"fpage"=>$_REQUEST['page'],"sid"=>$id));
					$complete_url = Yii::app()->createUrl('FrmSend/complete',array("id"=>$baseform->id,'last_update'=>$baseform->last_update,"sendId"=>$frmSend->id));
					$sales_url = Yii::app()->createUrl('FrmSales/index',array('card_no'=>$frmSales->baseform->form_sn,"start"=>""));
					$but_num = 0;
					$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
					//$operate .='<span class="delete_form" lastdate="'.$baseform->last_update.'" title="作废" id="'.$baseform->id.'" deleted=""><img src="/images/zuofei.png"></span><abc></abc>';
					$operate.='<a class="update_b" href="'.$sales_url.'" title="查看销售单"><span><img src="/images/detail.png"></span></a><abc></abc>';
					$but_num ++;
					if($frmSend->status == "unpush" || $frmSend->status == "pushed"){
						if(checkOperation("配送单:新增")){
							$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a><abc></abc>';
							$operate.='<span class="delete_form" lastdate="'.$baseform->last_update.'" title="作废" id="'.$baseform->id.'" deleted=""><img src="/images/zuofei.png"></span><abc></abc>';
// 							$operate.='<span class="submit_form" url="'.$complete_url.'" title="完成"><img src="/images/wancheng.png"></span><abc></abc>';
							$but_num =$but_num + 2;
							if($frmSend->status == "pushed"){
								$str = "请“".$frmSales->dictCompany->name."”凭提货码“".$frmSend->auth_code."”及行驶证原件到".$frmSales->warehouse->name."提货，共“".$frmSend->amount
								."”件，仓库地址：".$frmSales->warehouse->address."，联系电话：".$frmSales->warehouse->mobile;
								$operate.='<span class="send_message" tel="'.$frmSales->companycontact->mobile.'" title="短信重发" str="'.$str.'" sendid="'.$frmSend->id.'"><img src="/images/dxcf.png"></span><abc></abc>';
								$but_num ++;
							}
						}

					}elseif($frmSend->status == "pushfaild"){
// 						if(checkOperation("配送单:新增")){
							$operate.='<span class="delete_form" lastdate="'.$baseform->last_update.'" title="作废" id="'.$baseform->id.'" deleted="yes"><img src="/images/zuofei.png"></span><abc></abc>';
							$but_num ++;
// 						}
					}
					
					if($but_num > 4){
						$one=substr($operate,strpos($operate,'<abc></abc>')+11);
						$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
						$two=substr($one,strpos($one,'<abc></abc>')+11);
						$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
						$three=substr($two,strpos($two,'<abc></abc>')+11);
						$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
						$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
								.'<div class="cz_list_btn_more" style="width:120px" num="0">'.$three;
								$operate.='</div></div>';
					}else{
						$operate.='</div>';
					}
					
				}else{
					$mark='';
					$operate='';
				}
				$text = '';
				if($frmSend->auth_type == "car"){
					$authtext = $frmSend->auth_text;
					$authtext = str_replace(" ",",",$authtext);
					$authtext = str_replace("，",",",$authtext);
					$autharr = explode(",",$authtext);
					$newArr = array();
					foreach($autharr as $k=>$v){
						if(!empty($v)){
							array_push($newArr,$v);
						}
					}
					if(count($newArr) > 1){
						$text .= '<div class="car_no" title="'.$frmSend->auth_text.'">'.$newArr[0].'...</div>';
					}else{
						$text .= '<div class="car_no" title="'.$frmSend->auth_text.'">'.$newArr[0].'</div>';
					}
				}else{
					$text = $frmSend->auth_text;
				}
				if($id){
					$da['data']=array(
							$mark,
							$operate,
							'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$baseform->form_sn.'</a>',
							//$frmSales->baseform->form_sn,
							$status[$frmSend->status],
							$baseform->form_time,
							//$frmSales->dictTitle->short_name,
							//$frmSales->dictCompany->short_name,
							$text,
							$frmSend->auth_code,
							//$salesDetails->storage->card_no,
							DictGoodsProperty::getProName($each->brand_id),
							DictGoodsProperty::getProName($each->product_id),
							DictGoodsProperty::getProName($each->texture_id),
							DictGoodsProperty::getProName($each->rank_id),
							$each->length,
							$frmSales->warehouse->name,
							$each->output_amount."/".$each->amount,
							$each->warehouse_amount,
							number_format($each->warehouse_weight,3),
							$baseform->belong->nickname,//制单人
							$baseform->lastupdate->nickname,
							'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment,0,15,"UTF-8").'</span>',
					);
				}else{
					$da['data']=array(
							$mark,
							$operate,
							'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$baseform->form_sn.'</a>',
							//$frmSales->baseform->form_sn,
							$status[$frmSend->status],
							$baseform->form_time,
							$frmSales->dictTitle->short_name,
							'<span title="'.$frmSales->dictCompany->name.'">'.$frmSales->dictCompany->short_name.'</span>',
							$text,
							$frmSend->auth_code,
							//$salesDetails->storage->card_no,
							DictGoodsProperty::getProName($each->brand_id),
							DictGoodsProperty::getProName($each->product_id),
							str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)),
							DictGoodsProperty::getProName($each->rank_id),
							$each->length,
							$frmSales->warehouse->name,
							$each->output_amount."/".$each->amount,
							$each->warehouse_amount,
							number_format($each->warehouse_weight,3),
							$baseform->belong->nickname,//制单人
							$baseform->lastupdate->nickname,
							'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment,0,15,"UTF-8").'</span>',	
					);
				}
				if($search['status']  == "deleted"){
					array_push($da['data'],'<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason,0,15,"UTF-8").'</span>');
				}
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages);
	}

	/**
	 * 修改配送单
	 */
	public static function updateSend($post=array())
	{
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$data['common']=(Object)$post['CommonForms'];
	
		$data['main']=$post['send'];
		$data['main']=(Object)$data['main'];
	
		return $data;
	}

	public static function setCode(){
		$go = true;
		while($go){
			$code = rand(100000,999999);
			$send = FrmSend::model()->findAll("is_complete=0 and auth_code=".$code);
			if($send){
				continue;
			}else{
				$go = false;
				break;
			}
		}
		return $code;
	}
	
	/**
	 * 船舱入库转正合并配送单
	 * $sid：销售单id，id：合并目标id，mid：待合并的id
	 */
	public static function mergeOutput($sid,$id,$mid){
		$sales = FrmSales::model()->findByPk($sid);
		$send = $sales->frmSends;
		if($send){
			foreach($send as $each){
				if($each->status == "deleted"){continue;}
				$is_id = false; 	//是否存在合并目的详细
				$is_mid = false;	//是否存在需合并详细
				$sendDetail = $each->sendDetails;
				if($sendDetail){
					foreach($sendDetail as $li){
						if($li->sales_detail_id == $id){$is_id = true;$detail = $li;}
						if($li->sales_detail_id == $mid){$is_mid = true;$mdetail = $li;}
					}
					//如果存在船舱入库的出库明细
					if($is_mid){
						//如果存在合并目的明细
						if($is_id){
							$oldJson = $detail->datatoJson();
							$oldJson1 = $mdetail->datatoJson();
							$detail->amount +=$mdetail->amount;
							$detail->weight +=$mdetail->weight;
							$detail->update();
							$mainJson = $detail->datatoJson();
							$dataArray = array("tableName"=>"FrnSendDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$baseform = new BaseForm();
							$baseform->dataLog($dataArray);
							$mdetail->delete();
							$dataArray = array("tableName"=>"FrnSendDetail","newValue"=>"","oldValue"=>$oldJson1);
							$baseform = new BaseForm();
							$baseform->dataLog($dataArray);
						}
					}
				}
			}
		}
	}
	
	/**
	 * 推送配送单
	 */
	public static function BillPush($id,$system,$type)
	{
		$send = FrmSend::model()->findByPk($id);
		$detail = $send->sendDetails;
		$baseform = $send->baseform;
		$sales = $send->FrmSales;
		$jsonArr = array();
		//$jsonArr['Verb']='Add';
		
		$table['Columns'] = array();
		array_push(
			$table['Columns'],
			(object)array("Text"=>'配送单号','Schema'=>'form_id'),
			(object)array("Text"=>'货主单位','Schema'=>'owner_company'),
			(object)array("Text"=>'购货单位','Schema'=>'buyer_company'),
			(object)array("Text"=>'凭证','Schema'=>'voucher'),
			(object)array("Text"=>'车牌/提货单号','Schema'=>'auth_text'),
			(object)array("Text"=>'件数','Schema'=>'total_amount'),
			(object)array("Text"=>'提货重量','Schema'=>'total_weight'),
			(object)array("Text"=>'提货码','Schema'=>'apply_thm'),
			(object)array("Text"=>'仓库ID','Schema'=>'warehouse_id'),
			(object)array("Text"=>'制单人','Schema'=>'created_by'),
			(object)array("Text"=>'创建时间','Schema'=>'created_at'),
			(object)array("Text"=>'备注','Schema'=>'content'),
			(object)array("Text"=>'销售单号','Schema'=>'frm_sales_id')
		);
		$Records['Fields'] = array();
		array_push(
			$Records['Fields'],
			(object)array("Text"=>$baseform->form_sn, "Value"=>$send->id,"Standard"=>""),
			(object)array("Text"=>$sales->dictTitle->name, "Value"=>$sales->title_id,"Standard"=>""),
			(object)array("Text"=>$sales->dictCompany->name, "Value"=>$sales->customer_id,"Standard"=>""),
			(object)array("Text"=>$send->auth_type, "Value"=>'',"Standard"=>""),
			(object)array("Text"=>$send->auth_text, "Value"=>'',"Standard"=>""),
			(object)array("Text"=>$send->amount, "Value"=>'',"Standard"=>""),
			(object)array("Text"=>$send->weight, "Value"=>'',"Standard"=>""),
			(object)array("Text"=>$send->auth_code, "Value"=>"","Standard"=>""),
			(object)array("Text"=>$sales->warehouse_id, "Value"=>'',"Standard"=>""),
			(object)array("Text"=>$baseform->operator->nickname, "Value"=>$baseform->created_by,"Standard"=>""),
			(object)array("Text"=>$baseform->created_at, "Value"=>'',"Standard"=>""),
			(object)array("Text"=>$baseform->comment, "Value"=>'',"Standard"=>""),
			(object)array("Text"=>$sales->baseform->form_sn, "Value"=>$sales->id,"Standard"=>"")
		);

		$Details['Columns'] = array();
		array_push(
			$Details['Columns'],
			(object)array("Text"=>'产地','Schema'=>'goods_company'),
			(object)array("Text"=>'品名','Schema'=>'goods_name'),
			(object)array("Text"=>'材质','Schema'=>'texture'),
			(object)array("Text"=>'规格','Schema'=>'rank'),
			(object)array("Text"=>'长度','Schema'=>'length'),
			(object)array("Text"=>'提货件数','Schema'=>'amount'),
			(object)array("Text"=>'提货重量','Schema'=>'weight'),
			(object)array("Text"=>'是否船舱','Schema'=>'is_ship')
		);
		$deArr = array();
		if($detail){
			foreach($detail as $li){
				$newArr['Fields'] = array();
				$product_id = DictGoodPropertyRelation::getApiId($li->product_id);
				$texture_id = DictGoodPropertyRelation::getApiId($li->texture_id);
				$rank_id = DictGoodPropertyRelation::getApiId($li->rank_id);
				$brand_id = DictGoodPropertyRelation::getApiId($li->brand_id);
				$product_name = DictGoodsProperty::getFullProName($li->product_id);
				$texture_name = DictGoodsProperty::getFullProName($li->texture_id);
				$rank_name = DictGoodsProperty::getFullProName($li->rank_id);
				$brand_name = DictGoodsProperty::getFullProName($li->brand_id);
				array_push(
						$newArr['Fields'],
						(object)array("Text"=>$brand_name, "Value"=>$brand_id,"Standard"=>""),
						(object)array("Text"=>$product_name, "Value"=>$product_id,"Standard"=>""),
						(object)array("Text"=>$texture_name, "Value"=>$texture_id,"Standard"=>""),
						(object)array("Text"=>$rank_name, "Value"=>$rank_id,"Standard"=>""),
						(object)array("Text"=>$li->length, "Value"=>'',"Standard"=>""),
						(object)array("Text"=>$li->amount, "Value"=>'',"Standard"=>""),
						(object)array("Text"=>$li->weight, "Value"=>'',"Standard"=>""),
						(object)array("Text"=>$li->salesDetail->mergestorage->is_transit, "Value"=>'',"Standard"=>"")
				);
				array_push($deArr,$newArr);
			}
		}
		$Details['Records'] = $deArr;
		$Records['Details'][] = (object)$Details;
		$table['Records'][] = $Records;
		$jsonArr = (object)$table;
		$str = json_encode($jsonArr);
		$data=array();
		$data['type']=$system;
		$data['content']=$str;
		$data['unid']= Yii::app()->user->unid;
		$data['operate']=$type;
		$data['form_id']=$id;
		$data['form_sn']=$baseform->form_sn;
		PushList::createNew($data);
		$oldJson = $send->datatoJson();
		$send->status = "pushing";
		$send->update();
		$mainJson = $send->datatoJson();
		$dataArray = array("tableName"=>"FrmSend","newValue"=>$mainJson,"oldValue"=>$oldJson);
		$base = new BaseForm();
		$base->dataLog($dataArray);
		return true;
	}
	
	/**
	 * 获取配送单推送信息json串
	 */
	public static function getPushJson($model)
	{
		$send = FrmSend::model()->findByPk($model->form_id);
		//配送单已作废
		if($send->baseform->form_status == "delete"){
			$model->status='no';
			$model->update();
			return -1;
		}
		$detail = $send->sendDetails;
		$baseform = $send->baseform;
		$sales = $send->FrmSales;
		$jsonArr = array();
		//$jsonArr['Verb']='Add';
	
		$table['Columns'] = array();
		array_push(
				$table['Columns'],
				(object)array("Text"=>'配送单号','Schema'=>'form_id'),
				(object)array("Text"=>'货主单位','Schema'=>'owner_company'),
				(object)array("Text"=>'购货单位','Schema'=>'buyer_company'),
				(object)array("Text"=>'凭证','Schema'=>'voucher'),
				(object)array("Text"=>'车牌/提货单号','Schema'=>'auth_text'),
				(object)array("Text"=>'件数','Schema'=>'total_amount'),
				(object)array("Text"=>'提货重量','Schema'=>'total_weight'),
				(object)array("Text"=>'提货码','Schema'=>'apply_thm'),
				(object)array("Text"=>'仓库ID','Schema'=>'warehouse_id'),
				(object)array("Text"=>'制单人','Schema'=>'created_by'),
				(object)array("Text"=>'创建时间','Schema'=>'created_at'),
				(object)array("Text"=>'备注','Schema'=>'content'),
				(object)array("Text"=>'销售单号','Schema'=>'frm_sales_id')
				);
		$Records['Fields'] = array();
		array_push(
				$Records['Fields'],
				(object)array("Text"=>$baseform->form_sn, "Value"=>$send->id,"Standard"=>""),
				(object)array("Text"=>$sales->dictTitle->name, "Value"=>$sales->title_id,"Standard"=>""),
				(object)array("Text"=>$sales->dictCompany->name, "Value"=>$sales->customer_id,"Standard"=>""),
				(object)array("Text"=>$send->auth_type, "Value"=>'',"Standard"=>""),
				(object)array("Text"=>$send->auth_text, "Value"=>'',"Standard"=>""),
				(object)array("Text"=>$send->amount, "Value"=>'',"Standard"=>""),
				(object)array("Text"=>$send->weight, "Value"=>'',"Standard"=>""),
				(object)array("Text"=>$send->auth_code, "Value"=>"","Standard"=>""),
				(object)array("Text"=>$sales->warehouse_id, "Value"=>'',"Standard"=>""),
				(object)array("Text"=>$baseform->operator->nickname, "Value"=>$baseform->created_by,"Standard"=>""),
				(object)array("Text"=>$baseform->created_at, "Value"=>'',"Standard"=>""),
				(object)array("Text"=>$baseform->comment, "Value"=>'',"Standard"=>""),
				(object)array("Text"=>$sales->baseform->form_sn, "Value"=>$sales->id,"Standard"=>"")
				);
	
		$Details['Columns'] = array();
		array_push(
				$Details['Columns'],
				(object)array("Text"=>'产地','Schema'=>'goods_company'),
				(object)array("Text"=>'品名','Schema'=>'goods_name'),
				(object)array("Text"=>'材质','Schema'=>'texture'),
				(object)array("Text"=>'规格','Schema'=>'rank'),
				(object)array("Text"=>'长度','Schema'=>'length'),
				(object)array("Text"=>'提货件数','Schema'=>'amount'),
				(object)array("Text"=>'提货重量','Schema'=>'weight'),
				(object)array("Text"=>'是否船舱','Schema'=>'is_ship')
				);
		$deArr = array();
		if($detail){
			foreach($detail as $li){
				$newArr['Fields'] = array();
				$product_id = DictGoodPropertyRelation::getApiId($li->product_id);
				$texture_id = DictGoodPropertyRelation::getApiId($li->texture_id);
				$rank_id = DictGoodPropertyRelation::getApiId($li->rank_id);
				$brand_id = DictGoodPropertyRelation::getApiId($li->brand_id);
				$product_name = DictGoodsProperty::getFullProName($li->product_id);
				$texture_name = DictGoodsProperty::getFullProName($li->texture_id);
				$rank_name = DictGoodsProperty::getFullProName($li->rank_id);
				$brand_name = DictGoodsProperty::getFullProName($li->brand_id);
				array_push(
						$newArr['Fields'],
						(object)array("Text"=>$brand_name, "Value"=>$brand_id,"Standard"=>""),
						(object)array("Text"=>$product_name, "Value"=>$product_id,"Standard"=>""),
						(object)array("Text"=>$texture_name, "Value"=>$texture_id,"Standard"=>""),
						(object)array("Text"=>$rank_name, "Value"=>$rank_id,"Standard"=>""),
						(object)array("Text"=>$li->length, "Value"=>'',"Standard"=>""),
						(object)array("Text"=>$li->amount, "Value"=>'',"Standard"=>""),
						(object)array("Text"=>$li->weight, "Value"=>'',"Standard"=>""),
						(object)array("Text"=>$li->salesDetail->mergestorage->is_transit, "Value"=>'',"Standard"=>"")
						);
				array_push($deArr,$newArr);
			}
		}
		$Details['Records'] = $deArr;
		$Records['Details'][] = (object)$Details;
		$table['Records'][] = $Records;
		$jsonArr = (object)$table;
		$str = json_encode($jsonArr);
		//获取发送内容
		$api = new api_center();
		$unid = $model->unid;
		$system=$model->type;
		$type=$model->operate;
		$pushId=$model->id;
		$interface = $api->pushForm($str,$system,$type,$unid,$pushId);
		$model->content=$interface;
		$model->status='no';
		$model->times=0;
		$model->next_time=0;
			
		if($model->update()){
			$send->status = "pushing";
			$send->update();
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 作废推送配送单
	 */
	public static function DeletePush($id,$system,$type){
		$send = FrmSend::model()->findByPk($id);
		$detail = $send->sendDetails;
		$baseform = $send->baseform;
		$sales = $send->FrmSales;
		
		$table['Columns'] = array();
		array_push(
				$table['Columns'],
				(object)array("Text"=>'配送单号','Schema'=>'form_id'),
				(object)array("Text"=>'仓库ID','Schema'=>'warehouse_id')
		);
		$Records['Fields'] = array();
		array_push(
				$Records['Fields'],
				(object)array("Text"=>$baseform->form_sn, "Value"=>$send->id,"Standard"=>""),
				(object)array("Text"=>$sales->warehouse_id, "Value"=>'',"Standard"=>"")
		);
		$table['Records'][] = $Records;
		$jsonArr = (object)$table;
		$str = json_encode($jsonArr);
		$data=array();
		$data['type']=$system;
		$data['content']=$str;
		$data['unid']= Yii::app()->user->unid;
		$data['operate']=$type;
		$data['form_id']=$id;
		$data['form_sn']=$baseform->form_sn;
		PushList::createNew($data);
		$send->status = "deleting";
		$send->update();
	}
	/**
	 * 完成推送配送单
	 */
	public static function FinishPush($id,$system,$type){
		$send = FrmSend::model()->findByPk($id);
		$detail = $send->sendDetails;
		$baseform = $send->baseform;
		$sales = $send->FrmSales;
	
		$table['Columns'] = array();
		array_push(
				$table['Columns'],
				(object)array("Text"=>'配送单号','Schema'=>'form_id'),
				(object)array("Text"=>'仓库ID','Schema'=>'warehouse_id'),
				(object)array("Text"=>'已提件数','Schema'=>'taken_amount')
		);
		$Records['Fields'] = array();
		array_push(
				$Records['Fields'],
				(object)array("Text"=>$baseform->form_sn, "Value"=>$send->id,"Standard"=>""),
				(object)array("Text"=>$sales->warehouse_id, "Value"=>'',"Standard"=>""),
				(object)array("Text"=>$send->output_amount, "Value"=>'',"Standard"=>"")
		);
		$table['Records'][] = $Records;
		$jsonArr = (object)$table;
		$str = json_encode($jsonArr);
		$data=array();
		$data['type']=$system;
		$data['content']=$str;
		$data['unid']= Yii::app()->user->unid;
		$data['operate']=$type;
		$data['form_id']=$id;
		$data['form_sn']=$baseform->form_sn;
		PushList::createNew($data);
		$send->status = "pushing";
		$send->update();
		//var_dump($re);
	}
	
	/*
	 * 推送返回结果处理
	 */
	public static function setPush($json)
	{
		$json=json_decode($json);
		$push=PushList::model()->findByPk($json->SendId);
		$send=FrmSend::model()->findByPk($push->form_id);
		$sales = $send->FrmSales;
		if($send){
			$content=json_decode($push->content);
			$type=$content->Body->Verb;
			if($type=="Add" || $type=='Edit'){
				if($json->Result=='success'){
					if($type=="Add" && $send->status != "pushed"){
						//发送信息
						$contentarray["company"]="“".$sales->dictCompany->name."”";
						$contentarray["thm"]="“".$send->auth_code."”";
						$contentarray["code"]="及行驶证原件到".$sales->warehouse->name."提货，共“".$send->amount."”件，仓库地址：".$sales->warehouse->address."，联系电话：".$sales->warehouse->mobile;
						$sendmess = new Sendmessage();
						$sendmess->frm_send_id = $send->id;
						$sendmess->company_id = $sales->customer_id;
						$sendmess->phone = $sales->companycontact->mobile;
						$sendmess->content = json_encode($contentarray);
						$sendmess->status = 0;
						$sendmess->create_at = time();
						$sendmess->module_id = 1080989;
						$sendmess->insert();
					}
					$send->status = "pushed";
					$send->update();
				}elseif($json->Result=='error'){
					$send->status = "pushfaild";
					$send->update();
					$push->status = "fail";
					$push->message = $json->Message;
					$push->update();
				}
				$return='{"result":"success","Message":"'.urlencode('成功').'"}';
			}elseif($type=='Finish'){
				if($json->Result=='success'){
					if($send->is_complete != 1){
						$send->status = "finished";
						$send->update();
						$form=new Frm_Send($send->baseform->id);
						$return = $form->completeSendForm();
					}
				}elseif($json->Result=='error'){
					$send->status = "pushfaild";
					$send->update();
					$push->status = "fail";
					$push->message = $json->Message;
					$push->update();
				}
				$return='{"result":"success","Message":"'.urlencode('成功').'"}';
			}elseif($type=='Delete'){
				if($json->Result=='success'){
					$sendbase=$send->baseform;
					if($sendbase->form_status != "delete"){
						$sendbase->form_status='delete';
						$sendbase->update();
						$form=new Frm_Send($send->baseform->id);
						$result = $form->deleteForm();
						if($result){
							$send->status = "deleted";
							$send->update();
						}else{
							$sendbase->form_status='deleting';
							$sendbase->update();
							$send->status = "deletfaild";
							$send->update();
							$push->status = "fail";
							$push->message = $json->Message;
							$push->update();
						}
					}	
				}elseif($json->Result=='error'){
					$send->status = "deletfaild";
					$send->update();
					$push->status = "fail";
					$push->message = $json->Message;
					$push->update();
				}
				
				$return='{"result":"success","Message":"'.urlencode('成功').'"}';
			}else{
				$return='{"result":"success","Message":"'.urlencode('没有找到操作类型').'"}';
			}
		}else{
			$return='{"result":"success","Message":"'.urlencode('没有找到推送数据').'"}';
		}
		return urldecode($return);
	}
	
	//根据随机生成的销售单，创建配送单
	public static function randSend(){
		
		$user = array(1,9,10,11,13,15,16,17,18,19,20,21,22,23,24);//随机获取部分业务员数组
		$title_id = array(11,12,14);//公司抬头id
		$gao = array(7,8);//高开结算单位
		$commonForm = new CommonForms();
		$commonForm->form_type ="XSPS";
		$commonForm->created_by = 1;
		$commonForm->created_at = time();
		$commonForm->form_time = date('Y-m-d');
		$commonForm->form_status = 'unsubmit';
		$commonForm->owned_by = $user[array_rand($user)];
		$commonForm->comment = "随机生成的配送单";
		if($commonForm->insert()){
			$sales = new FrmSend();
			$sales->frm_sales_id = 0;
			$sales->auth_type = "car";
			$sales->auth_text = "沪A".mt_rand(10000,99999);
			$sales->start_time = date("Y-m-d");
			$sales->end_time = date("Y-m-d",time()+3600*24*30);
			$sales->amount = $amount;
			$sales->weight = $weight;
			
			if($sales->insert()){
				$sn =  "XSPS".date("ymd").str_pad($sales->id,4,"0",STR_PAD_LEFT);
				$commonForm->form_id = $sales->id;
				$commonForm->form_sn = $sn;
				$commonForm->update();
				$detail = new FrmSendDetail();
				$detail->product_id=mt_rand(39,41);
				$detail->texture_id=mt_rand(61,69);
				$detail->brand_id=mt_rand(1,38);
				$detail->rank_id=mt_rand(44,60);
				$detail->length=9;
				$detail->amount=mt_rand(1,10);
				$detail->weight=mt_rand(2,20);
				$detail->frm_send_id=$sales->id;
				$detail->insert();
				return true;
			}
		}
		return false;
	}
	
	//更新虚拟出库信息
	public static function setVirtual($model){
			if($model->frm_send_id){
				$waredetails = $model->warehouseOutputDetails;
				$send = $model->frmsend;
				$sales = $send->FrmSales;
				$t_amount = 0;
				$t_weight = 0;
				if($waredetails){
					foreach ($waredetails as $li){
						$t_amount += $li->amount;
						$t_weight += $li->weight;
						//更新配送单虚拟出库件数
						$sendDetail = new FrmSendDetail();
						$criteria=New CDbCriteria();
						$criteria->compare('frm_send_id',$model->frm_send_id,false);
						$criteria->compare('product_id',$li->product_id,false);
						$criteria->compare('brand_id',$li->brand_id,false);
						$criteria->compare('texture_id',$li->texture_id,false);
						$criteria->compare('rank_id',$li->rank_id,false);
						$criteria->compare('length',intval($li->length),false);
						$sendDetail=$sendDetail->findAll($criteria);
						if($sendDetail){
							$cc_amount = $li->amount;
							$cc_weight = $li->weight;
							foreach ($sendDetail as $sd){
								$oldJson=$sd->datatoJson();
								//获取此条明细可以增加的最大数量
								$can_amount = $sd->amount - $sd->warehouse_amount;
								$can_weight = $sd->weight - $sd->warehouse_weight;
								if($can_amount >=$cc_amount){
									//配送明细可以完全增加出库明细数量
									$sd->warehouse_amount +=$cc_amount;
									$sd->warehouse_weight +=$cc_weight;
									//更新销售单虚拟出库信息
									$salesDetail = $sd->salesDetail;
									$oldDetail=$salesDetail->datatoJson();
									$salesDetail->warehouse_amount += $cc_amount;
									$salesDetail->warehouse_weight += $cc_weight;
									$salesDetail->update();
									$mainDetail = $salesDetail->datatoJson();
									$dataArray = array("tableName"=>"FrmSalesDetail","newValue"=>$mainDetail,"oldValue"=>$oldDetail);
									$base = new BaseForm();
									$base->dataLog($dataArray);
									$cc_amount = 0;
									$cc_weight = 0;
								}else{
									//配送明细不足以吃掉出库出库件数
									$sd->warehouse_amount +=$can_amount;
									$sd->warehouse_weight +=$can_weight;
									$cc_amount -= $can_amount;
									$cc_weight -= $can_weight;
									//更新销售单虚拟出库信息
									$salesDetail = $sd->salesDetail;
									$oldDetail=$salesDetail->datatoJson();
									$salesDetail->warehouse_amount += $can_amount;
									$salesDetail->warehouse_weight += $can_weight;
									$salesDetail->update();
									$mainDetail = $salesDetail->datatoJson();
									$dataArray = array("tableName"=>"FrmSalesDetail","newValue"=>$mainDetail,"oldValue"=>$oldDetail);
									$base = new BaseForm();
									$base->dataLog($dataArray);
								}
								$sd->update();
								$mainJson = $sd->datatoJson();
								$dataArray = array("tableName"=>"FrmSendDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
								$base = new BaseForm();
								$base->dataLog($dataArray);
							}
						}
					}
					$send->warehouse_amount += $t_amount;
					$send->warehouse_weight += $t_weight;
					$send->update();
					$sales->warehouse_amount += $t_amount;
					$sales->warehouse_weight += $t_weight;
					$sales->update();
				}
			}
	}
}
