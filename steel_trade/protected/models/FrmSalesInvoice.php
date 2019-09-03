<?php

/**
 * This is the biz model class for table "frm_sales_invoice".
 *
 */
class FrmSalesInvoice extends FrmSalesInvoiceData
{
	public $total_weight;
	public $total_price;
	public $total_num;

	public static $invoice_type = array('purchase' => "采购单", 'sales' => "销售单", 'rebate' => "销售折让");
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'salesInvoiceDetails' => array(self::HAS_MANY, 'SalesInvoiceDetail', 'sales_invoice_id'),
			'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type = 'XSKP'"),
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
			'invoice_type' => 'Invoice Type',
			'company_id' => 'Company',
			'title_id' => 'Title',
			'price' => 'Price',
			'weight' => 'Weight',
			'fee' => 'Fee',
			'confirm_status' => 'Confirm Status',
			'sales_id' => 'Sales',
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
		$criteria->compare('invoice_type',$this->invoice_type,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('confirm_status',$this->confirm_status);
		$criteria->compare('sales_id',$this->sales_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSalesInvoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getInputData($post) 
	{
		$data = array();
		$td_id = $post['td_id'];
		$td_invoice_id = $post['td_invoice_id'];
		$weight = $post['weight'];
		$fee = $post['fee'];
		$client_id = $post['client_id'];
		$total_wei=0;
		$total_fee=0;
		
		for ($i = 0; $i < count($td_invoice_id); $i++) 
		{
			$detail_data = array();
			if ($td_id[$i] != "")
				$detail_data['id'] = intval($td_id[$i]);
			
			$detail_data['sales_detail_id'] = intval($td_invoice_id[$i]);
			$detail_data['weight'] = floatval(numChange($weight[$i]));
			$detail_data['fee'] = floatval(numChange($fee[$i]));
			$data['detail'][$i] = (Object)$detail_data;

			$total_wei+=$detail_data['weight'];
			$total_fee+=$detail_data['fee'];

		}
		// $post['FrmSalesInvoice']['weight'] = floatval(numChange($post['FrmSalesInvoice']['weight']));
		// $post['FrmSalesInvoice']['fee'] = floatval(numChange($post['FrmSalesInvoice']['fee']));

		$post['FrmSalesInvoice']['weight']=$total_wei;
		$post['FrmSalesInvoice']['fee']=$total_fee;
		$post['CommonForms']['created_by'] = currentUserId();
		$data['main'] = (Object)$post['FrmSalesInvoice'];
		$data['common'] = (Object)$post['CommonForms'];
		
		return $data;
	}
	
	public static function getInvoiceList($search) 
	{
		$tableData = array();
		$model = new FrmSalesInvoice();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		//搜索
		if(!empty($search)) 
		{
			if ($search['keywords'])
			{
				//增加销售单号查询
				$idlist = array(0);
				$s_base = CommonForms::model()->findAll("form_type='XSD' and form_status <>'delete' and form_sn like '%".strtoupper($search['keywords'])."%'");
				if($s_base){
						foreach ($s_base as $li){
								$sales_id = $li->sales->id;
								$in_detail = SalesInvoiceDetail::model()->findAll("frm_sales_id={$sales_id}");
								if($in_detail){
									foreach ($in_detail  as $dt){
										array_push($idlist,$dt->salesInvoice->id);
									}
								}
						}
				}
				$id_list = implode(",",$idlist);
				$criteria->addCondition("baseform.form_sn like :keywords or t.id in({$id_list})");
				$criteria->params[':keywords'] = "%".strtoupper($search['keywords'])."%";
			}
			if ($search['time_L']) //开始时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']." 00:00:00");
			}
			if ($search['time_H']) //结束时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) < :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']." 23:59:59");
			}
			if ($search['company_id']) //收票单位
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
			}
			if ($search['client_id']) //客户
			{
				$criteria->addCondition("client_id = :client_id");
				$criteria->params[':client_id'] = $search['client_id'];
			}
			if ($search['title_id']) //开票单位
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
			if ($search['form_status']) 
			{
				$criteria->addCondition("baseform.form_status = :form_status");
				$criteria->params[':form_status'] = $search['form_status'];
			}
			if ($search['owned_by']) 
			{
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		$criteria->compare('baseform.form_type', 'XSKP', true);
		if (!$search['form_status'] || $search['form_status'] != 'delete') 
			$criteria->compare('baseform.is_deleted', '0', true);
		
		//总计
		$c = clone $criteria;
		$c->select = "sum(t.weight) as total_weight, sum(t.fee) as total_price, count(*) as total_num";
		$total = FrmSalesInvoice::model()->find($c);
		
		$totaldata = array();
		$totaldata['weight'] = $total->total_weight;
		$totaldata['price'] = $total->total_price;
		$totaldata['total_num'] = $total->total_num;
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['sales_invoice_list']) ? intval($_COOKIE['sales_invoice_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="baseform.created_at DESC";
		
		$items = $model->findAll($criteria);
		if (!$items) return array($tableData, $pages, $totaldata);
		$i = 1;
		foreach ($items as $item)
		{
			$mark = '';
			$operate = '';
			$sub_operate = '';
			$operate_count = 0;
			$da = array();
			$baseform = $item->baseform;
			if ($baseform)
			{
				$view_url = Yii::app()->createUrl("salesInvoice/view", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
				$edit_url = Yii::app()->createUrl("salesInvoice/update", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
// 				$del_url = Yii::app()->createUrl('salesInvoice/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
				$checkI_url = Yii::app()->createUrl('salesInvoice/invoice', array('id' => $baseform->id, 'type' => 'invoice', 'fpage' => $_REQUEST['page']));
				$checkIC_url = Yii::app()->createUrl('salesInvoice/invoice', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
					
				$operate .= '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
				if (in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && checkOperation("销售开票:新增"))
				{
					switch ($baseform->form_status)
					{
						case 'unsubmit':
						case 'submited':
							$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png"/></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						default:
							break;
					}
				}
				if (checkOperation("销售开票:开票"))
				{
					switch ($baseform->form_status)
					{
						case 'submited':
							$sub_operate .= '<a class="invoice_form" href="'.$checkI_url.'" title="开票"><span><img src="/images/kaipiao.png"/></span></a>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						case 'invoice':
							$sub_operate .= '<span class="submit_form" url="'.$checkIC_url.'" title="取消开票"><img src="/images/qxkp.png"/></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						default:
							break;
					}
				}
				if ($operate_count > 4)
				{
					$operate .= '<span class="more_but" title="更多"><img src="/images/gengduo.png"/></span>'.
							'<div class="cz_list_btn_more" num="0" style="width:120px">'.$sub_operate.'</div>';
				}
				else
				{
					$operate .= $sub_operate;
				}
				$operate .= '</div>';
			}
		
			$num = 0;
			foreach ($item->salesInvoiceDetails as $detail)
			{
				$mark = $i; $i++;
				if ($num == 0) $num++;
				else $operate = "";
					
				$invoice_type = array('purchase' => "采购单", 'sales' => "销售单", 'rebate' => "销售折让");
				$da['data'] = array($mark,
						$operate,
						'<a title="查看详情" href="'.$view_url.'" class="a_view">'.$baseform->form_sn.'</a>',
						CommonForms::$formStatus[$baseform->form_status],
						$baseform->form_time,
						'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
						'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
						number_format($detail->weight, 3),
						number_format($detail->fee, 2),
						number_format($item->weight, 3),
						number_format($item->fee, 2),						
						$detail->detailForInvoice->relation_form->form_sn,
						$item->confirm_status == 1 ? number_format($item->invoice_amount) : '',
						$item->confirm_status == 1 ? $item->invoice_number : '',
						'<span title="'.$item->client->name.'">'.$item->client->short_name.'</span>',
						$invoice_type[$detail->detailForInvoice->type],
						// $item->confirm_status == 1 ? $baseform->form_time : '',
						$baseform->belong->nickname,
						$baseform->operator->nickname,
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,"utf-8").'</span>',
				);
				if ($baseform->form_status == 'delete')
					$da['data'][] = $baseform->delete_reason;
					
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
			}
		}
		return array($tableData, $pages, $totaldata);
	}

	public static function getAllList($search) 
	{
		$model = new FrmSalesInvoice();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		//搜索
		if(!empty($search)) {
			if ($search['keywords'])
			{
				$criteria->addCondition("baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".strtoupper($search['keywords'])."%";
			}
			if ($search['time_L']) //开始时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']." 00:00:00");
			}
			if ($search['time_H']) //结束时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) < :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']." 23:59:59");
			}
			if ($search['company_id']) //收票单位
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
			}
			if ($search['title_id']) //开票单位
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
			if ($search['form_status']) 
			{
				$criteria->addCondition("baseform.form_status = :form_status");
				$criteria->params[':form_status'] = $search['form_status'];
			}
			if ($search['owned_by']) 
			{
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		$criteria->compare('baseform.form_type', 'XSKP');
		if (!$search['form_status'] || $search['form_status'] != 'delete') 
			$criteria->compare('baseform.is_deleted', '0');
		
// 		//总计
// 		$c = clone $criteria;
// 		$c->select = "sum(t.weight) as total_weight, sum(t.fee) as total_price, count(*) as total_num";
// 		$total = FrmSalesInvoice::model()->find($c);
		
// 		$totaldata = array();
// 		$totaldata[6] = $total->total_weight;
// 		$totaldata[7] = $total->total_price;
		// $totaldata['total_num'] = $total->total_num;
		
		// $pages = new CPagination();
		// $pages->itemCount = $model->count($criteria);
		// $pages->pageSize =intval($_COOKIE['sales_invoice_list']) ? intval($_COOKIE['sales_invoice_list']) : Yii::app()->params['pageCount'];
		// $pages->applyLimit($criteria);
		$criteria->order="baseform.created_at DESC,title_id,baseform.owned_by";
		
		$items = $model->findAll($criteria);

		$content = array();
		if (!$items) return $content;

		foreach ($items as $item) {
			$baseform = $item->baseform;
			$num = 0;
			foreach ($item->salesInvoiceDetails as $detail) {
				if($detail->frm_sales_detail_id == 0){
					$product = "";
				}else{
					if($detail->fee < 0 ){
						$product = DictGoodsProperty::getProName($detail->salesReturnDetail->product_id);
					}else{
						$product = DictGoodsProperty::getProName($detail->salesDetail->product_id);
					}
				}
				$temp = array(
					//$baseform->form_sn, // 0
					$baseform->form_time ?$baseform->form_time : '', // 1
					$item->title->short_name, // 2
					$product,
					numChange(number_format($detail->weight, 3)), // 4
					numChange(number_format($detail->fee, 2)), // 5
// 					$num == 0 ? numChange(number_format($item->weight, 3)) : '', // 6
// 					$num == 0 ? numChange(number_format($item->fee, 2)) : '', // 7
// 					$baseform->form_status != 'invoice' ? CommonForms::$formStatus[$baseform->form_status] : "已提交", // 8
// 					$baseform->form_status == 'invoice' ? "已开票" : "未开票", // 9
// 					FrmSalesInvoice::$invoice_type[$detail->detailForInvoice->type],
//					$detail->detailForInvoice->relation_form->form_sn,
//					$item->confirm_status == 1 ? $item->invoice_amount : '',
					$item->confirm_status == 1 ? $item->invoice_number : '',
					$item->company->short_name, // 3
//					$item->confirm_status == 1 ? $baseform->form_time : '',
					$baseform->belong->nickname,
//					$baseform->operator->nickname,
//					$baseform->comment,
					""
				);
				if ($baseform->form_status == 'delete') 
					array_push($temp, $baseform->delete_reason);

				array_push($content, $temp);
				$num++;
			}
		}
		//array_push($content, $totaldata);

		//array sort
		$sort=array();
		foreach ($content as $hh) {
			$sort[]=$hh[5];
		}
		array_multisort($sort,SORT_ASC,$content);	
		return $content;
	}
		
	//随机生成销售单开票信息
	public static function RandSalesInvoice(){
		$user = array(1,9,10,11,13,15,16,17,18,19,20,21,22,23,24);//随机获取部分业务员数组
		$title_id = array(11,12,14);//公司抬头id
		$gao = array(7,8);//高开结算单位
		$commonForm = new CommonForms();
		$commonForm->form_type ="XSKP";
		$commonForm->created_by = currentUserId();
		$commonForm->created_at = time();
		$commonForm->form_time = date('Y-m-d');
		$commonForm->form_status = 'unsubmit';
		$commonForm->owned_by = 1;
		$commonForm->comment = "随机生成的可开票明细";
		if($commonForm->insert()){
				$salesInvoice = new FrmSalesInvoice();
				$salesInvoice->title_id=$title_id[array_rand($title_id)];
				$salesInvoice->company_id=mt_rand(1,3759);
				$salesInvoice->invoice_type="sales";
				$salesInvoice->weight=mt_rand(1,100);
				$salesInvoice->fee=mt_rand(1000,2000);
				if($salesInvoice->insert()){
					$sn =  "XSKP".date("ymd").str_pad($salesInvoice->id,4,"0",STR_PAD_LEFT);
					$commonForm->form_id = $salesInvoice->id;
					$commonForm->form_sn = $sn;
					$commonForm->update();
					$detail = new SalesInvoiceDetail();
					$detail->sales_detail_id=mt_rand(1,5000);
					$detail->sales_invoice_id=$salesInvoice->id;
					$detail->frm_sales_id=mt_rand(1,5000);
					$detail->frm_sales_detail_id=mt_rand(1,5000);
					$detail->type="sales";
					$amount = mt_rand(2,20);
					$detail->weight=$amount;
					$detail->fee=mt_rand(1000,2000);
					$detail->insert();
					return true;
				}

		return false;
	}
	return false;
}


}