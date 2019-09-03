<?php

/**
 * This is the biz model class for table "detail_for_invoice".
 *
 */
class DetailForInvoice extends DetailForInvoiceData
{
	public $total_money;
	public $total_weight;
	public $total_checked_money;
	public $total_checked_weight;
	public $owned_by;
	public $title_short_name,$supply_name,$supply_short_name;
	public $uncheck;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'salesInvoiceDetail' => array(self::HAS_ONE, 'SalesInvoiceDetail', 'sales_detail_id'),
				'relation_form' => array(self::BELONGS_TO, 'CommonForms', 'form_id'),
				'purchaseDetail' => array(self::BELONGS_TO, 'PurchaseDetail', 'detail_id'),
				'salesDetail' => array(self::BELONGS_TO, 'SalesDetail', 'detail_id'),
				'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
				'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
				'purchaseReturnDetail' => array(self::BELONGS_TO, 'PurchaseReturnDetail', 'detail_id'),
				'salesReturnDetail' => array(self::BELONGS_TO, 'SalesReturnDetail', 'detail_id'),
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
			'type' => 'Type',
			'form_id' => 'Form',
			'detail_id' => 'Detail',
			'checked_weight' => 'Checked Weight',
			'checked_money' => 'Checked Money',
			'weight' => 'Weight',
			'money' => 'Money',
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
		$criteria->compare('form_id',$this->form_id);
		$criteria->compare('detail_id',$this->detail_id);
		$criteria->compare('checked_weight',$this->checked_weight,true);
		$criteria->compare('checked_money',$this->checked_money,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('money',$this->money,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DetailForInvoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getSimpleList($search, $form_type = array()) 
	{
		$totalData=array();
		$tableData = array();
		$model = new DetailForInvoice();
		$criteria = new CDbCriteria();
		$criteria->with = array('relation_form');
		
		$type_array = array();
		foreach ($form_type as $type_each) 
		{
			switch ($type_each) 
			{
				case 'CGD': 
					$type_array[] = 'purchase';
					break;
				case 'XSD':
					$type_array[0] = 'sales';
					$type_array[1] = 'rebate';
					$type_array[2] = 'salesreturn';
					break;
				default: 
					break;
			}
		}
		$criteria->addInCondition("t.type", $type_array);
		//搜索
		if (!empty($search)) 
		{
			if ($search['id'] && intval($search['id']) > 0) 
			{
				$bf = CommonForms::model()->findByPK(intval($search['id']));
				switch ($bf->form_type) 
				{
					case 'XSKP': $invoiceDetails = $bf->salesInvoice->salesInvoiceDetails; break;
					case 'CGXP': $invoiceDetails = $bf->purchaseInvoice->purchaseInvoiceDetails; break;
					default: break;
				}
				$retain_array = "";
				foreach ($invoiceDetails as $detail) 
				{
					$retain_array .= ','.$detail->detailForInvoice->id;
				}
				$retain_array = substr($retain_array, 1);
			}
			if ($retain_array != "") $criteria->addCondition("t.id IN (".$retain_array.") OR (t.weight - t.checked_weight) <> 0");
			else $criteria->addCondition("(t.weight - t.checked_weight) <> 0");
			$form_id_array = array();
			foreach ($form_type as $type_each) 
			{
				switch ($type_each) 
				{
					case 'CGD': 
						$cf_model = CommonForms::model();
						$cf_criteria = new CDbCriteria();
						$cf_criteria->with = array('purchase');
						$cf_criteria->addCondition("t.form_type = 'CGD'");
						$cf_criteria->join = "LEFT JOIN pledge_info pledge ON pledge.frm_purchase_id = t.form_id";
						if ($search['company_id'])
						{
							$cf_criteria->addCondition("purchase.supply_id = :supply_id OR pledge.pledge_company_id = :supply_id");
							$cf_criteria->params[':supply_id'] = $search['company_id'];
						}
						if ($search['title_id'])
						{
							$cf_criteria->addCondition("purchase.title_id = :title_id");
							$cf_criteria->params[':title_id'] = $search['title_id'];
						}
						$cf_criteria->select='group_concat(t.id) as id';
						$form_array = $cf_model->find($cf_criteria);
						$ids=explode(',', $form_array->id);
						$form_id_array=array_merge($form_id_array,$ids);
// 						foreach ($form_array as $form) 
// 						{
// 							$form_id_array[] = $form->id;
// 						}
						$th_model = CommonForms::model();
						$th_criteria = new CDbCriteria();
						$th_criteria->with = array('purchaseReturn');
						$th_criteria->addCondition("t.form_type = 'CGTH'");
						if ($search['company_id'])
						{
							$th_criteria->addCondition("purchaseReturn.supply_id = :supply_id");
							$th_criteria->params[':supply_id'] = $search['company_id'];
						}
						if ($search['title_id'])
						{
							$th_criteria->addCondition("purchaseReturn.title_id = :title_id");
							$th_criteria->params[':title_id'] = $search['title_id'];
						}
						$th_criteria->select='group_concat(t.id) as id';
						$th_array = $th_model->find($th_criteria);
						$ids=explode(',', $th_array->id);
						$form_id_array=array_merge($form_id_array,$ids);
// 						if($th_array){
// 							foreach ($th_array as $form)
// 							{
// 								array_push($form_id_array,$form->id);
// 							}
// 						}
						break;
					case 'XSD':
						//获取销售单列表
						$cf_model = CommonForms::model();
						$condition = "t.form_type = 'XSD'";
						$params = array();
						if ($search['company_id']) 
						{
							$condition .= " AND sales.customer_id = ".$search['company_id'];
// 							$params[':customer_id'] = $search['company_id'];
						}
						if ($search['title_id']) 
						{
							$condition .= " AND sales.title_id =".intval($search['title_id']);
// 							$params[':title_id'] = $search['title_id'];
						}
						if ($search['client_id'])
						{
							$condition .= " AND sales.client_id = ".$search['client_id'];
// 							$params[':client_id'] = $search['client_id'];
						}
						$sql='select group_concat(t.id) as id from common_forms t left join frm_sales sales on t.form_id=sales.id where '.$condition;
						$form_array = $cf_model->findBySql($sql);
						$ids=explode(',',$form_array->id);
						$form_id_array=array_merge($form_id_array,$ids);
// 						foreach ($form_array as $form)
// 						{
// 							$form_id_array[] = $form->id;
// 						}						
						//获取销售退货单列表
						$th_model = CommonForms::model();
						$th_condition = "form_type = 'XSTH'";
						$params = array();
						if ($search['company_id'])
						{
							$th_condition .= " AND salesReturn.company_id = ".$search['company_id'];
// 							$params[':company_id'] = $search['company_id'];
						}
						if ($search['client_id'])
						{
							$th_condition .= " AND salesReturn.client_id = ".$search['client_id'];
// 							$params[':client_id'] = $search['client_id'];
						}
						if ($search['title_id'])
						{
							$th_condition .= " AND salesReturn.title_id = ".$search['title_id'];
// 							$params[':title_id'] = $search['title_id'];
						}
						$sql='select group_concat(t.id)  as id from common_forms t left join frm_sales_return salesReturn on t.form_id=salesReturn.id where '.$th_condition;
						$form_array = $th_model->findBySql($sql);
						$ids=explode(',',$form_array->id);
						$form_id_array=array_merge($form_id_array,$ids);
// 						foreach ($form_array as $form)
// 						{
// 							array_push($form_id_array,$form->id);
// 						}						
						//获取销售折让列表
						$zr_model = CommonForms::model();
						$zr_condition = "form_type = 'XSZR'";
						$params = array();
						if ($search['company_id']) 
						{
							$zr_condition .= " AND rebate.company_id =".$search['company_id'];
// 							$params[':company_id'] = $search['company_id'];
						}
						if ($search['client_id'])
						{
							$zr_condition .= " AND rebate.client_id = ".$search['client_id'];
// 							$params[':client_id'] = $search['client_id'];
						}
						if ($search['title_id']) 
						{
							$zr_condition .= " AND rebate.title_id = ".$search['title_id'];
// 							$params[':title_id'] = $search['title_id'];
						}
						$sql='select group_concat(t.id) as id from  common_forms t left join frm_rebate rebate  on t.form_id = rebate.id where '.$zr_condition;
						$form_array = $zr_model->findBySql($sql);
						$ids=explode(',',$form_array->id);
						$form_id_array=array_merge($form_id_array,$ids);
// 						foreach ($form_array as $form)
// 						{
// 							array_push($form_id_array,$form->id);
// 						}						
						break;
					default: 
						break;
				}
			}
// 			var_dump($form_id_array);	
			if ($search['company_id'] || $search['title_id'] || $search['client_id']){
				$criteria->addInCondition("relation_form.id", $form_id_array);
			}		
			
			if ($search['keywords']) 
			{
				$criteria->addCondition("relation_form.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if ($search['search_begin']) 
			{
				$criteria->addCondition("unix_timestamp(relation_form.form_time) >= :search_begin");
				$criteria->params[':search_begin'] = strtotime($search['search_begin']." 00:00:00");
			}
			if ($search['search_end']) 
			{
				$criteria->addCondition("unix_timestamp(relation_form.form_time) <= :search_end");
				$criteria->params[':search_end'] = strtotime($search['search_end']." 23:59:59");
			}
			if ($search['owned_by']) 
			{
				$criteria->addCondition("relation_form.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		$criteria->addInCondition("relation_form.form_type", $form_type);
		$criteria->compare('relation_form.form_status', 'approve');
		$criteria->compare('relation_form.is_deleted', '0');
		
		$cri_total=clone $criteria;
		$cri_total->select="sum(weight) as weight,sum(money) as money,sum(checked_weight) as checked_weight,sum(checked_money) as checked_money";
		$total=DetailForInvoice::model()->find($cri_total);
		$totalData['weight']=$total->weight;
		$totalData['money']=$total->money;
		$totalData['uncheck_weight']=$total->weight-$total->checked_weight;
		$totalData['uncheck_money']=$total->money-$total->checked_money;
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['invoice_list']) ? intval($_COOKIE['invoice_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "relation_form.created_at DESC,t.detail_id desc";
		
		$detailForInvoices = $model->findAll($criteria);
		if ($detailForInvoices) {
			$i = 1;
			foreach ($detailForInvoices as $each) 
			{
// 				if ($each->money - $each->checked_money <= 0) continue;				
				$baseform = $each->relation_form;
				switch ($each->type) 
				{
					case 'purchase': //采购
						$type = "采购单";
						if($baseform->form_type == "CGTH"){
							$form_mod = $each->relation_form->purchaseReturn;
							$form_detail = $each->purchaseReturnDetail;
						}else{
							$form_mod = $each->relation_form->purchase;
							$form_detail = $each->purchaseDetail;
						}
						break;
					case 'sales': //销售
						$type = "销售单";
						if($baseform->form_type == "XSD"){
							$form_mod = $each->relation_form->sales;
							$form_detail = $each->salesDetail;
						}else{
							$form_mod = $each->relation_form->salesReturn;
							$form_detail = $each->salesReturnDetail;
						}
						break;
					case 'rebate': //销售折让
						$type = "销售折让";
						$form_mod = $each->relation_form->rebate;
						$form_detail = "";
						break;
					case 'salesreturn': //销售退货
						$type = "销售退货单";
						$form_mod = $each->relation_form->salesReturn;
						$form_detail = $each->salesReturnDetail;
						break;
					default: 
						break;
				}
				$mark = $i;
				$operate = '<input type="checkbox" name="selected_invoice[]" class="selected_invoice"  value="'.$each->id.'" />';
				$da = array();
				$product_info = "";
				if ($form_detail) 
				{
					$product_info .= DictGoodsProperty::getProName($form_detail->brand_id).'/'.
						DictGoodsProperty::getProName($form_detail->product_id).'/'.
						str_replace('E', '<span class="red">E</span>', DictGoodsProperty::getProName($form_detail->texture_id)).'/'.
						DictGoodsProperty::getProName($form_detail->rank_id).'/'.
						$form_detail->length;
				}
				
				$da['data'] = array($mark, 
						$operate, 
						$baseform->form_sn.'<input type="hidden" class="in_type" value="'.$each->type.'">',
						'<span title="'.$form_mod->title->name.'">'.$form_mod->title->short_name.'</span>',
						'<span  title="'.$form_mod->company->name.'">'.$form_mod->company->name.'</span>',
				);
				if ($each->type == 'purchase' && $baseform->form_type == "CGD") $da['data'][] = '<span title="'.$form_mod->pledge->pledgeCompany->name.'">'.$form_mod->pledge->pledgeCompany->short_name.'</span>';
					elseif($baseform->form_type == "CGTH") $da['data'][]="";
				$da['data'] = array_merge($da['data'], array(
						$product_info,
						number_format($each->checked_weight, 3),
						number_format($each->checked_money, 2),
						number_format($each->weight, 3),
						number_format($each->money, 2),
						$baseform->belong->nickname,
// 						'<span  title="'.$form_mod->client->name.'">'.$form_mod->client->name.'</span><input type="hidden" class="client_id" value="'.$form_mod->client_id.'">',
				));
				if($each->type!='purchase')
				{
					$da['data']=array_merge($da['data'],array(
							'<span  title="'.$form_mod->client->name.'">'.$form_mod->client->name.'</span><input type="hidden" class="client_id" value="'.$form_mod->client_id.'">',
					));
				}
				$da['group'] = $each->id;
				array_push($tableData, $da);
				$i++;
			}
		}
		return array($totalData,$tableData, $pages);
	}

	/*
	 * 出库完成后生成销售单开票明细 
	 */
	public static function setSalesInvoice($form_id,$detail_id,$weight,$money,$title_id=0,$company_id=0,$client_id,$is_return=0){
		$model = DetailForInvoice::model()->find("form_id=".$form_id." and detail_id=".$detail_id);
		if($model){
			$oldJson = $model->datatoJson();
			//减少可开票件数时，结果小于零，产品已开票不能取消出库
			if($model->weight + $weight < 0){
				return false;
			}else{
				$model->weight +=$weight;
				$model->money +=$money;
				$model->update();
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>"DetailForInvoice","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$base = new BaseForm();
				$base->dataLog($dataArray);
				return true;
			}
		}else{
			$invoice = new DetailForInvoice();
			$invoice->form_id = $form_id;
			$invoice->detail_id = $detail_id;
			$invoice->weight = $weight;
			$invoice->money = $money;
			if($is_return){
				$invoice->type="salesreturn";
			}else{
				$invoice->type="sales";
			}
			$invoice->title_id = $title_id;
			$invoice->company_id = $company_id;
			$invoice->client_id = $client_id;
			$invoice->pledge_id = 0;
			$invoice->insert();
			$mainJson = $invoice->datatoJson();
			$dataArray = array("tableName"=>"DetailForInvoice","newValue"=>$mainJson,"oldValue"=>"");
			$base = new BaseForm();
			$base->dataLog($dataArray);
			return true;
		}
	}
	
	//采购退货出库后生成可开票明细
	public static function setPurReInvoice($form_id,$detail_id,$weight,$money,$title_id=0,$company_id=0){
		$model = DetailForInvoice::model()->find("form_id=".$form_id." and detail_id=".$detail_id);
		if($model){
			$oldJson = $model->datatoJson();
			//减少可开票件数时，结果小于零，产品已开票不能取消出库
			if($model->weight + $weight < 0){
				return false;
			}else{
				$model->weight +=$weight;
				$model->money +=$money;
				$model->update();
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>"DetailForInvoice","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$base = new BaseForm();
				$base->dataLog($dataArray);
				return true;
			}
		}else{
			$invoice = new DetailForInvoice();
			$invoice->form_id = $form_id;
			$invoice->detail_id = $detail_id;
			$invoice->weight = $weight;
			$invoice->money = $money;
			$invoice->type="purchase";
			$invoice->title_id = $title_id;
			$invoice->company_id = $company_id;
			$invoice->pledge_id = 0;
			$invoice->insert();
			$mainJson = $invoice->datatoJson();
			$dataArray = array("tableName"=>"DetailForInvoice","newValue"=>$mainJson,"oldValue"=>"");
			$base = new BaseForm();
			$base->dataLog($dataArray);
			return true;
		}
	}
	
	//随机生成可开票信息
	public static function RandInvoice(){
		$title_id = array(11,12,14);//公司抬头id
		$invoice = new DetailForInvoice();
		$invoice->form_id = mt_rand(1,10000);
		$invoice->detail_id = mt_rand(1,10000);
		$weight = mt_rand(2,20);
		$money = $weight * 2000;
		$invoice->weight = $weight;
		$invoice->money = $money;
		$invoice->type="sales";
		$invoice->title_id = $title_id[array_rand($title_id)];
		$invoice->company_id = mt_rand(1,5000);
		$invoice->pledge_id = 0;
		if($invoice->insert()){
			return true;
		}else{
			return false;
		}
	} 
}
