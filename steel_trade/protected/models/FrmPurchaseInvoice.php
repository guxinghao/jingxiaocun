<?php

/**
 * This is the biz model class for table "frm_purchase_invoice".
 *
 */
class FrmPurchaseInvoice extends FrmPurchaseInvoiceData
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
			'purchaseInvoiceDetails' => array(self::HAS_MANY, 'PurchaseInvoiceDetail', 'purchase_invoice_id'),
			'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type = 'CGXP'"),
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
			'weight' => 'Weight',
			'price' => 'Price',
			'fee' => 'Fee',
			'confirm_status' => 'Confirm Status',
			'purchase_id' => 'Purchase',
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
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('confirm_status',$this->confirm_status);
		$criteria->compare('purchase_id',$this->purchase_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPurchaseInvoice the static model class
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
		
		$data['detail'] = array();
		for ($i = 0; $i < count($td_invoice_id); $i++) 
		{
			$detail_data = array();
			if ($td_id[$i] != "")
				$detail_data['id'] = intval($td_id[$i]);
			
			$detail_data['purchase_detail_id'] = intval($td_invoice_id[$i]);
			$detail_data['weight'] = floatval(numChange($weight[$i]));
			$detail_data['fee'] = floatval(numChange($fee[$i]));
			$data['detail'][$i] = (Object)$detail_data;
		}
		$post['FrmPurchaseInvoice']['weight'] = floatval(numChange($post['FrmPurchaseInvoice']['weight']));
		$post['FrmPurchaseInvoice']['fee'] = floatval(numChange($post['FrmPurchaseInvoice']['fee']));
		$post['CommonForms']['created_by'] = currentUserId();
		$data['main'] = (Object)$post['FrmPurchaseInvoice'];
		$data['common'] = (Object)$post['CommonForms'];
		
		return $data;
	}
	
	public static function getInvoiceList($search) 
	{
		$tableData = array();
		$model = new FrmPurchaseInvoice();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		//搜索
		if(!empty($search)) 
		{
			if ($search['keywords']) 
			{
				$criteria->addCondition("baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if ($search['time_L']) //开始时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']." 00:00:00");
			}
			if ($search['time_H']) //结束时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']." 23:59:59");
			}
			if ($search['title_id']) 
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
			if ($search['company_id']) 
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
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
		$criteria->compare('baseform.form_type', 'CGXP', true);
		if (!$search['form_status'] || $search['form_status'] != 'delete') 
			$criteria->compare('baseform.is_deleted', '0', true);
		
		//总计
		$c = clone $criteria;
		$c->select = "sum(t.weight) as total_weight, sum(t.fee) as total_price, count(*) as total_num";
		$total = FrmPurchaseInvoice::model()->find($c);
		
		$totaldata = array();
		$totaldata['weight'] = $total->total_weight;
		$totaldata['price'] = $total->total_price;
		$totaldata['total_num'] = $total->total_num;
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_invoice_list']) ? intval($_COOKIE['purchase_invoice_list']) : Yii::app()->params['pageCount'];
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
				$view_url = Yii::app()->createUrl("purchaseInvoice/view", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
				$edit_url = Yii::app()->createUrl("purchaseInvoice/update", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
// 				$del_url = Yii::app()->createUrl('purchaseInvoice/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
				$checkI_url = Yii::app()->createUrl('purchaseInvoice/capias', array('id' => $baseform->id, 'type' => 'capias', 'fpage' => $_REQUEST['page']));
				$checkIC_url = Yii::app()->createUrl('purchaseInvoice/capias', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
					
				$operate .= '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
				if (checkOperation("采购销票:新增")) 
				{
					switch ($baseform->form_status) 
					{
						case 'unsubmit':
						case 'submited': 
							$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png"/></span>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							break;
						default: 
							break;
					}
				}
				if (checkOperation("采购销票:销票")) 
				{
					switch ($baseform->form_status) 
					{
						case 'submited': 
							$sub_operate .= '<a class="capias_form" href="'.$checkI_url.'" title="销票"><span><img src="/images/xiaopiao.png"/></span></a>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							break;
						case 'capias': 
							$sub_operate .= '<span class="submit_form" url="'.$checkIC_url.'" title="取消销票"><img src="/images/qxxp.png"/></span>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
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
			foreach ($item->purchaseInvoiceDetails as $detail) 
			{
				$mark = $i; $i++;
				if ($num == 0) $num++; 
				else $operate = "";

				$invoice_type = array('purchase' => "采购单", 'sales' => "销售单", 'rebate' => "销售折让");
				$da['data'] = array($mark,
						$operate,
						'<a title="查看详情" href="'.$view_url.'" class="a_view">'.$baseform->form_sn.'</a>',
						CommonForms::$formStatus[$baseform->form_status],
						$baseform->form_time?  $baseform->form_time : '',
						'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
						'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
						number_format($detail->weight, 3),
						number_format($detail->fee, 2),
						number_format($item->weight, 3),
						number_format($item->fee, 2),
						$invoice_type[$detail->detailForInvoice->type],
						$detail->detailForInvoice->relation_form->form_sn,
						$item->confirm_status == 1 ? $item->capias_amount : '',
						$item->confirm_status == 1 ? $item->capias_number : '',
						$item->confirm_status == 1 ? $baseform->form_time : '',
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

	/**
	 * 采购销票 查询所有
	 * @param  array $search 搜索条件
	 * @return array $content 查询结果
	 */
	public static function getAllList($search) 
	{
		$model = new FrmPurchaseInvoice();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		//搜索
		if(!empty($search)) {
			if ($search['keywords']) 
			{
				$criteria->addCondition("baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if ($search['time_L']) //开始时间
			{
				$criteria->addCondition("baseform.created_at >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']." 00:00:00");
			}
			if ($search['time_H']) //结束时间
			{
				$criteria->addCondition("baseform.created_at <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']." 23:59:59");
			}
			if ($search['title_id']) 
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
			if ($search['company_id']) 
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
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
		$criteria->compare('baseform.form_type', 'CGXP', true);
		if (!$search['form_status'] || $search['form_status'] != 'delete') 
			$criteria->compare('baseform.is_deleted', '0', true);
		
		//总计
		$c = clone $criteria;
		$c->select = "sum(t.weight) as total_weight, sum(t.fee) as total_price, count(*) as total_num";
		$total = FrmPurchaseInvoice::model()->find($c);
		
		$totaldata = array();
		$totaldata[6] = $total->total_weight;
		$totaldata[7] = $total->total_price;
		// $totaldata['total_num'] = $total->total_num;
		
		// $pages = new CPagination();
		// $pages->itemCount = $model->count($criteria);
		// $pages->pageSize =intval($_COOKIE['purchase_invoice_list']) ? intval($_COOKIE['purchase_invoice_list']) : Yii::app()->params['pageCount'];
		// $pages->applyLimit($criteria);
		$criteria->order="baseform.created_at DESC";
		$items = $model->findAll($criteria);

		$content = array();
		if (!$items) return $content;

		foreach ($items as $item) {
			$baseform = $item->baseform;
			$num = 0;
			foreach ($item->purchaseInvoiceDetails as $detail) 
			{
				$temp = array(
					$baseform->form_sn, // 0
					$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '', // 1
					$item->title->short_name, // 2
					$item->company->short_name, // 3
					numChange(number_format($detail->weight, 3)), // 4
					numChange(number_format($detail->fee, 2)), // 5
					$num == 0 ? numChange(number_format($item->weight, 3)) : '', // 6
					$num == 0 ? numChange(number_format($item->fee, 2)) : '', // 7
					$baseform->form_status != 'capias' ? CommonForms::$formStatus[$baseform->form_status] : "已提交", // 8
					$baseform->form_status == 'capias' ? "已销票" : "未销票", // 9
					FrmPurchaseInvoice::$invoice_type[$detail->detailForInvoice->type],
					$detail->detailForInvoice->relation_form->form_sn,
					$item->confirm_status == 1 ? $item->capias_amount : '',
					$item->confirm_status == 1 ? $item->capias_number : '',
					$item->confirm_status == 1 ? $baseform->form_time : '',
					$baseform->belong->nickname,
					$baseform->operator->nickname,
					$baseform->comment,
				);
				if ($baseform->form_status == 'delete') 
					array_push($temp, $baseform->delete_reason);
				
				array_push($content, $temp);
				$num++;
			}
		}
		array_push($content, $totaldata);

		return $content;
	}
	
}
