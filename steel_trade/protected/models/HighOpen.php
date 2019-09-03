<?php

/**
 * This is the biz model class for table "high_open".
 *
 */
class HighOpen extends HighOpenData
{
	public $total_weight;//销售汇总重量
	public $total_price;//销售汇总金额
	public $total_num;
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type = 'GK'"),
				'sales' => array(self::BELONGS_TO, 'FrmSales', 'sales_id'),
				'salesDetail' => array(self::BELONGS_TO, 'SalesDetail', 'sales_detail_id'),
				'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
				'company' => array(self::BELONGS_TO, 'DictCompany', 'target_id'),
				'client' => array(self::BELONGS_TO, 'DictCompany', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sales_id' => 'Sales',
			'sales_detail_id' => 'Sales Detail',
			'price' => 'Price',
			'real_fee' => 'Real Fee',
			'fee' => 'Fee',
			'is_pay' => 'Is Pay',
			'discount' => 'Discount',
			'title_id' => 'Title',
			'target_id' => 'Target',
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
		$criteria->compare('sales_id',$this->sales_id);
		$criteria->compare('sales_detail_id',$this->sales_detail_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('real_fee',$this->real_fee,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('is_pay',$this->is_pay);
		$criteria->compare('discount',$this->discount,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('target_id',$this->target_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HighOpen the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
public static function getFormBillList($search) 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "2%"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "8%"),
				array('name' => "销售单号", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "产地/品名/材质/规格/长度", 'class' => "sort-disabled", 'width' => "16%"),
				array('name' => "件数", 'class' => "sort-disabled text-right", 'width' => "6%"),
				array('name' => "重量", 'class' => "sort-disabled text-right", 'width' => "8%"),
				array('name' => "高开单价", 'class' => "sort-disabled text-right", 'width' => "8%"),
				array('name' => "高开金额", 'class' => "sort-disabled text-right", 'width' => "8%"),
// 				array('name' => "均摊金额", 'class' => "sort-disabled", 'width' => "7%"),
				array('name' => "付款", 'class' => "sort-disabled", 'width' => "8%"), 
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "8%"),
				array('name' => "客户", 'class' => "sort-disabled", 'width' => "10%"),
		);
		
		$tableData = array();
		$model = new HighOpen();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		
		//搜索
		if (!empty($search)) 
		{
			if ($search['id'] && intval($search['id']) > 0) 
			{
				$bf = CommonForms::model()->findByPK(intval($search['id']));
				$relations = $bf->formBill->relation;
				
				$retain_array = "";
				foreach ($relations as $relation) 
				{
					$retain_array .= ','.$relation->bill_form->form_id;
				}
				$retain_array = substr($retain_array, 1);
			}
			
			if ($retain_array != "") $criteria->addCondition("t.id IN (".$retain_array.") OR t.is_selected = 0");
			else $criteria->addCondition("t.is_selected = 0");
			
			if ($search['company_id'])
			{
				$criteria->addCondition("target_id = :target_id");
				$criteria->params[':target_id'] = $search['company_id'];
			}
			if ($search['client_id'])
			{
				$criteria->addCondition("client_id = :client_id");
				$criteria->params[':client_id'] = $search['client_id'];
			}
			if ($search['client_id'])
			{
				$criteria->addCondition("client_id = :client_id");
				$criteria->params[':client_id'] = $search['client_id'];
			}
			if ($search['title_id'])
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
			if ($search['keywords'])
			{
				$criteria->addCondition("baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if ($search['time_L'])
			{
				$criteria->addCondition("baseform.created_at >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']);
			}
			if ($search['time_H'])
			{
				$criteria->addCondition("baseform.created_at <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']);
			}
			// if ($search['owned_by'])
			// {
			// 	$criteria->addCondition("baseform.owned_by = :owned_by");
			// 	$criteria->params[':owned_by'] = $search['owned_by'];
			// }
		}
		$criteria->compare("baseform.form_type", 'GK', true);
		$criteria->compare("baseform.is_deleted", '0', true);
		$criteria->compare("baseform.form_status", "approve", true);
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['bill_list']) ? intval($_COOKIE['bill_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		
		if (!$search['title_id'] || !$search['company_id']) return array($tableHeader, $tableData, $pages);
		$bill =  $model->findAll($criteria);
		if ($bill) 
		{
// 			$selected_array = array();
// 			$selecteds = HighOpen::model()->findAll("is_selected = 1");
// 			foreach ($selecteds as $each) 
// 			{
// 				if (!$item->baseform) continue;
// 				$selected_array[] = $each->baseform->id;
// 			}
			
			$i = 1;
			foreach ($bill as $item) 
			{
				$da = array();
				$mark = '';
				$operate = '';
				$baseform = $item->baseform;
				if ($baseform != "") 
				{
					$mark = $i;
					$i++;
					$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill" isselected="'.$item->is_selected.'" value="'.$baseform->id.'" />';
				}
				$sales_detail = $item->salesDetail;
				$product_info = DictGoodsProperty::getProName($sales_detail->brand_id) . "/" . 
						DictGoodsProperty::getProName($sales_detail->product_id) . "/" . 
						str_replace('E', '<span class="red">E</span>', DictGoodsProperty::getProName($sales_detail->texture_id)) . "/" . 
						DictGoodsProperty::getProName($sales_detail->rank_id) . "/" . 
				$sales_detail->length;
				
				$da['data'] = array($mark, 
						$operate, 
						$baseform->form_sn, 
						$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '', 
						$item->sales->baseform->form_sn,
						$product_info,
						$sales_detail->amount,
						number_format($sales_detail->weight, 3),
						number_format($item->price),
						'<span class="real_fee">'.number_format($item->real_fee, 2).'</span>'.'<input type="hidden" class="discount" value="'.$item->discount.'" name="discount[]" />',
						$item->is_pay == 1 ? "已付款" : "未付款",
						$baseform->belong->nickname, //业务员
						'<span title="'.$item->client->name.'" value="'.$item->client_id.'">'.$item->client->short_name.'</span>',
				);
				$da['group'] = $baseform->id;
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	/*
	 *新增高开信息
	 */
	public static function createHigh($data,$status=""){
		if($status == ""){$status = "unsubmit";}
		
		$common = $data['common'];
		$main = $data['main'];
		//保存基础信息
		$commonForm = new CommonForms();
		$commonForm->form_type = $common->form_type;
		$commonForm->created_by = currentUserId();
		$commonForm->created_at = time();
		$commonForm->form_time = $common->form_time?$common->form_time:date('Y-m-d',time());
		$commonForm->form_status = $status;
		$commonForm->owned_by = $common->owned_by?$common->owned_by:currentUserId();
		$commonForm->comment = $common->comment;

		$commonForm->save();
		//保存主体信息
		$mainInfo=new HighOpen();
		$mainInfo->sales_id=$main->sales_id;
		$mainInfo->sales_detail_id=$main->sales_detail_id;
		$mainInfo->price=$main->price;
		$mainInfo->fee=$main->fee;
		$mainInfo->weight=$main->weight;
		if($main->is_yidan){
			$mainInfo->real_fee=$main->fee;
		}else{
			$mainInfo->real_fee=$main->fee*0.83;
		}
		$mainInfo->title_id=$main->title_id;
		$mainInfo->target_id=$main->target_id;
		$mainInfo->client_id=$main->client_id;
		if($mainInfo->insert()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"HighOpen","newValue"=>$mainJson,"oldValue"=>"");
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
		}
		//更新基础信息
 		$sn = HighOpen::model()->_generateSN($mainInfo->id);
 		$commonForm->form_sn = $sn;
		$commonForm->form_id = $mainInfo->id;
		if($commonForm->update()){
			//明细日志
			$commonJson = $commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>"");
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
		};
		//保存日志
		if($status == "approve"){$status = "submited";}
		$da=array();
		$da['type']="GKMX";
		$da['turnover_direction']="need_pay";
		$da['title_id']= $mainInfo->title_id;
		$da['target_id']=$mainInfo->target_id;
		$da['client_id']=$mainInfo->client_id;
		$da['proxy_company_id']='';
		$da['amount']=$mainInfo->salesDetail->weight;
		$da['price']=$mainInfo->price;
		$da['fee']=$mainInfo->real_fee;
		$da['common_forms_id']=$commonForm->id;
		$da['ownered_by']=$commonForm->owned_by;
		$da['created_at'] = strtotime($common->form_time);
		$da['created_by'] = currentUserId();
		$da['description'] = "高开付款";
		$da['big_type'] = "gaokai";
		$da['is_yidan'] = 1;
		$da['status'] = $status;
		$result = Turnover::createBill($da);
		//日志
		$mainJson = $result->datatoJson();
		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
		$baseform = new BaseForm();
		$baseform->dataLog($dataArray);
	}
	/*
	 *根据销售单id和详情id获取高开表信息
	 */
	public static function getHighOpen($sales_id,$detail_id){
		$model = HighOpen::model()->find("sales_id=".$sales_id." and sales_detail_id=".$detail_id);
		if($model){
			return $model;
		}else{
			return false;
		}
	}
	/*
	 *根据销售单id和详情id获取高开表信息，修改高开表 
	 */
	public static function updateLine($sales_id,$detail_id,$data){
		$common = $data['common'];
		$main = $data['main'];
		$mainInfo = HighOpen::model()->find("sales_id=".$sales_id." and sales_detail_id=".$detail_id);
		$sales = FrmSales::model()->findByPk($sales_id);
		if($mainInfo){
			$commonForm = $mainInfo->baseform;
			//修改基础信息
			$oldJson=$commonForm->datatoJson();
			$commonForm->comment = $common->comment;
			$commonForm->last_update = time();
			$commonForm->last_updated_by = currentUserId();
			$commonForm->form_time = $common->form_time;
			if($commonForm->update()){
				$commonJson = $commonForm->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}
			//修改主体信息
			$oldJson=$mainInfo->datatoJson();
			$mainInfo->price=$main->price;
			$mainInfo->fee=$main->fee;
			if($sales->is_yidan){
				$mainInfo->real_fee=$main->fee;
			}else{
				$mainInfo->real_fee=$main->fee*0.83;
			}
			$mainInfo->weight=$main->weight;
			if($main->title_id){
				$mainInfo->title_id=$main->title_id;
				$mainInfo->target_id=$main->target_id;
				$mainInfo->client_id=$main->client_id;
			}
			if($mainInfo->update())
			{
				$mainJson = $mainInfo->datatoJson();
				$dataArray = array("tableName"=>"HighOpen","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}
			$thrnover = Turnover::findOneBill($commonForm->id);
			if($thrnover){
				//修改往来信息
				$fee = $mainInfo->real_fee;
				$amount = $mainInfo->salesDetail->weight;
				$price = $mainInfo->price;
				$owned_by = $sales->baseform->owned_by;
				$is_yidan = 1;
				$created_at = strtotime($sales->baseform->form_time);
				if($main->title_id){
					$company_id=$mainInfo->title_id;
					$vendor_id=$mainInfo->target_id;
					$client_id=$mainInfo->client_id;
					$update=array('fee'=>$fee,'title_id'=>$company_id,'target_id'=>$vendor_id,'client_id'=>$client_id,'amount'=>$amount,"price"=>$price,'ownered_by'=>$owned_by,'created_at'=>$created_at,'is_yidan'=>$is_yidan);
				}else{
					$update=array('fee'=>$fee,'amount'=>$amount,"price"=>$price,'ownered_by'=>$owned_by,'created_at'=>$created_at,'is_yidan'=>$is_yidan);
				}
				$oldJson=$thrnover->datatoJson();
				$result = Turnover::updateBill($thrnover->id, $update);
			
				$mainJson = $result->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}else{
				//新增往来信息
				//高开没有明细，不会新增往来
			}
			
		}
	}
	
	/*
	 *根据销售单id和详情id获取高开表信息，作废高开表
	 */
	public static function deleteLine($sales_id,$detail_id){
		$model = HighOpen::model()->find("sales_id=".$sales_id." and sales_detail_id=".$detail_id);
		//作废基础信息表
		$commonForm = $model->baseform;
		if($commonForm){
			$oldJson=$commonForm->datatoJson();
			$commonForm->form_status = 'delete';
			$commonForm->is_deleted = 1;
			$commonForm->last_update = time();
			$commonForm->last_updated_by = currentUserId();
			$commonForm->update();
			$commonJson = $commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
			
			$id=$commonForm->id;
			$thrnover = Turnover::findBill($id);
			$update=array('status'=>'delete');
			if($thrnover){
				foreach($thrnover as $th){
					$thrnoverId = $th->id;
					$oldJson=$th->datatoJson();
					$result = Turnover::updateBill($thrnoverId, $update);
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$baseform = new BaseForm();
					$baseform->dataLog($dataArray);
				}
			}
		}
	}
	
	/*
	 *根据销售单id获取高开表信息，提交高开信息
	 */
	public static function submitHigh($sales_id){
		$model = HighOpen::model()->findAll("sales_id=".$sales_id);
		if($model){
			foreach($model as $li){
				$commonForm = $li->baseform;
				$oldJson=$commonForm->datatoJson();
				if($commonForm->is_deleted == 1){continue;}
				$commonForm->form_status = 'submited';
				$commonForm->update();
				$commonJson = $commonForm->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
				$id=$commonForm->id;
				$thrnover = Turnover::findBill($id);
				$update=array('status'=>'submited');
				if($thrnover){
					foreach($thrnover as $th){
						$thrnoverId = $th->id;
						$oldJson=$th->datatoJson();
						$result = Turnover::updateBill($thrnoverId, $update);
						$mainJson = $result->datatoJson();
						$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$baseform = new BaseForm();
						$baseform->dataLog($dataArray);
					}
				}
			}
		}
	}
	
	/*
	 *根据销售单id和详情id获取高开表信息，取消提交高开信息
	 */
	public static function unsubmitHigh($sales_id){
		$model = HighOpen::model()->findAll("sales_id=".$sales_id);
		if($model){
			foreach($model as $li){
				$commonForm = $li->baseform;
				$oldJson=$commonForm->datatoJson();
				if($commonForm->is_deleted == 1){continue;}
				$commonForm->form_status = 'unsubmit';
				$commonForm->update();
				$commonJson = $commonForm->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
				$id=$commonForm->id;
				$thrnover = Turnover::findBill($id);
				$update=array('status'=>'unsubmit');
				if($thrnover){
					foreach($thrnover as $th){
						$thrnoverId = $th->id;
						$oldJson=$th->datatoJson();
						$result = Turnover::updateBill($thrnoverId, $update);
						$mainJson = $result->datatoJson();
						$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$baseform = new BaseForm();
						$baseform->dataLog($dataArray);
					}
				}
			}
		}
	}
	
	/*
	 *根据销售单id和详情id获取高开表信息，审核高开信息
	 */
	public static function checkHigh($sales_id){
		$model = HighOpen::model()->findAll("sales_id=".$sales_id);
		if($model){
			foreach($model as $li){
				$commonForm = $li->baseform;
				$oldJson=$commonForm->datatoJson();
				if($commonForm->is_deleted == 1){continue;}
				$commonForm->form_status = 'approve';
				$commonForm->update();
				$commonJson = $commonForm->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}
		}
	}
	
	/*
	 *根据销售单id和详情id获取高开表信息，取消审核高开信息
	 */
	public static function uncheckHigh($sales_id){
		$model = HighOpen::model()->findAll("sales_id=".$sales_id);
		if($model){
			foreach($model as $li){
				$commonForm = $li->baseform;
				$oldJson=$commonForm->datatoJson();
				if($commonForm->is_deleted == 1){continue;}
				$commonForm->form_status = 'submited';
				$commonForm->update();
				$commonJson = $commonForm->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}
		}
	}

	/**
	 * 生成统一的序列号
	 */
	protected function _generateSN($id)
	{
		if (!$id) return;	
		$id = $id%10000 == 0 ? 1 : $id%10000;
		$sn =  "GK".date("ymd").str_pad($id,4,"0",STR_PAD_LEFT);
		if (!$sn) return false;
		return $sn;
	}

}
