<?php

/**
 * This is the biz model class for table "frm_rebate".
 *
 */
class FrmRebate extends FrmRebateData
{
	public $total_price;
	public $total_num;
	public static $type = array(
			'sale' => "销售折让", 
			'shipment' => "采购运费登记", 
// 			'shipment_sale' => "销售运费登记", 
			'high' => "高开折让"
	);
	
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
			'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
			'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type = 'XSZR'"), //销售折让
			'baseformXSZR' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformXSZR.form_type = 'XSZR'"), //销售折让
			'baseformGKZR' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformGKZR.form_type = 'GKZR'"), //高开折让
			'baseformCGZR' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformCGZR.form_type = 'CGZR'"), //采购折让
			'bankInfo' => array(self::BELONGS_TO, 'BankInfo', 'bank_info_id'),
			'dictBankInfo' => array(self::BELONGS_TO, 'DictBankInfo', 'dict_bank_info_id'),
			'rebateRelation' => array(self::HAS_MANY, 'RebateRelation', 'rebate_id'),
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
			'team_id' => 'Team',
			'title_id' => 'Title',
			'company_id' => 'Company',
			'frm_type' => 'Frm Type',
			'type' => 'Type',
			'amount' => 'Amount',
			'is_yidan' => 'Is Yidan',
			'comment' => 'Comment',
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
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('frm_type',$this->frm_type,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmRebate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getInputData($post) 
	{
		$data = array();
		$td_id = $post['td_id'];
		$td_sales_id = $post['td_sales_id'];
		
		$post['FrmRebate']['relation'] = array();
		switch ($post['FrmRebate']['type']) 
		{
			case 'sale':
				for ($i = 0; $i < count($td_sales_id); $i++)
				{
					$relation_data = array();
					if ($td_id[$i] != "") $relation_data['id'] = intval($td_id[$i]);
					$relation_data['sales_id'] = intval($td_sales_id[$i]);
					$post['FrmRebate']['relation'][$i] = (Object)$relation_data;
				}
				break;
			default: 
				break;
		}
		$post['CommonForms']['created_by'] = currentUserId();
		$post['CommonForms']['comment'] = $post['FrmRebate']['comment'];
		$post['FrmRebate']['amount'] = numChange($post['FrmRebate']['amount']);
// 		$post['FrmRebate']['is_yidan'] = $post['FrmRebate']['is_yidan'] ? 1 : 0;
		$post['FrmRebate']['start_at'] = $post['FrmRebate']['start_at'] ? strtotime($post['FrmRebate']['start_at']." 00:00:00") : '';
		$post['FrmRebate']['end_at'] = $post['FrmRebate']['end_at'] ? strtotime($post['FrmRebate']['end_at']." 23:59:59") : '';
		$data['main'] = (Object)$post['FrmRebate'];
		$data['common'] = (Object)$post['CommonForms'];
		
		return $data;
	}
	
	public static function getRebateList($search, $type) 
	{
		$tableData = array();
		$model = new FrmRebate();
		$criteria = new CDbCriteria();
		switch ($type) 
		{
			case 'sale': 
				$_baseform = 'baseformXSZR';
				break;
			case 'high': 
				$_baseform = 'baseformGKZR';
				break;
			case 'shipment':
				$_baseform = 'baseformCGZR';
				break;
			default: break;
		}
		$criteria->with = array($_baseform);
		//搜索
		if(!empty($search)) 
		{
			if ($search['keywords']) 
			{
				$criteria->addCondition($_baseform.".form_sn like :keywords");
				$criteria->params[':keywords'] = "%".strtoupper($search['keywords'])."%";
			}
			if ($search['time_L']) //开始时间
			{
				$criteria->addCondition($_baseform.".created_at >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']." 00:00:00");
			}
			if ($search['time_H']) //结束时间
			{
				$criteria->addCondition($_baseform.".created_at <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']." 23:59:59");
			}
			if ($search['type']) //折让类型
			{
				$criteria->addCondition("type = :type");
				$criteria->params[':type'] = $search['type'];
			}
			if ($search['title_id']) //公司抬头
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
			if ($search['company_id']) //客户
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
			}
			if ($search['client_id']) //客户
			{
				$criteria->addCondition("client_id = :client_id");
				$criteria->params[':client_id'] = $search['client_id'];
			}
// 			if ($search['is_yidan']) //是否乙单
// 			{
// 				$criteria->addCondition("is_yidan = :is_yidan");
// 				$criteria->params[':is_yidan'] = $search['is_yidan'];
// 			}
// 			if ($search['team_id']) //业务组
// 			{
// 				$criteria->addCondition("team_id = :team_id");
// 				$criteria->params[':team_id'] = $search['team_id'];
// 			}
			if ($search['form_status']) //审核状态
			{
				$criteria->addCondition($_baseform.".form_status = :form_status");
				$criteria->params[':form_status'] = $search['form_status'];
			}
		}
		$criteria->compare($_baseform.".form_type", array('XSZR', 'GKZR','CGZR'), true);
		if (!$search['form_status'] || $search['form_status'] != 'delete') 
			$criteria->compare($_baseform.".is_deleted", '0', true);
		
		//总计
		$c = clone $criteria;
		$c->select = "sum(t.amount) as total_price, count(*) as total_num";
		$total = FrmRebate::model()->find($c);
		
		$totaldata = array();
		$totaldata['price'] = $total->total_price;
		$totaldata['total_num'] = $total->total_num;
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['rebate_list']) ? intval($_COOKIE['rebate_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = $_baseform.".created_at DESC";
		
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
			$baseform = $item->$_baseform;
			if ($baseform)
			{
				$view_url = Yii::app()->createUrl("rebate/view", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
				$edit_url = Yii::app()->createUrl("rebate/update", array('id' => $baseform->id, 'type' => $item->type, 'fpage' => $_REQUEST['page']));
				switch ($baseform->form_status)
				{
					case 'unsubmit':
						$type_sub = "submit";
						$title_sub = "提交";
						$img_url = "/images/tijiao.png";
						break;
					case 'submited':
						$type_sub = "cancle";
						$title_sub = "取消提交";
						$img_url = "/images/qxtj.png";
						break;
					default:
						break;
				}
				$sub_url = Yii::app()->createUrl('rebate/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
// 				$del_url = Yii::app()->createUrl('rebate/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
// 				$checkP_url = Yii::app()->createUrl('rebate/check', array('id' => $baseform->id, 'type' => 'pass', 'last_update' => $baseform->last_update));
// 				$checkD_url = Yii::app()->createUrl('rebate/check', array('id' => $baseform->id, 'type' => 'deny', 'last_update' => $baseform->last_update));
				$checkC_url = Yii::app()->createUrl('rebate/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
// 				$fk_url = Yii::app()->createUrl("formBill/create", array('type' => "FKDJ", 'bill_type' => "XSZR", 'common_id' => $baseform->id, 'is_yidan' => $item->is_yidan, 'company_id' => $item->company_id, 'title_id' => $item->title_id));
					
				$operate .= '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
				if (in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && (($item->type == 'sale' && checkOperation("销售折让:新增")) || ($item->type == 'high' && checkOperation("高开折让:新增"))||($item->type=='shipment'&&checkOperation("采购折让:新增"))))
				{
					switch ($baseform->form_status)
					{
						case 'unsubmit':
							$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png"/></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						case 'submited':
							$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						default:
							break;
					}
				}
				
				if($item->type=='sale')
				{
					if(checkOperation('销售折让:审核')){
						switch ($baseform->form_status)
						{
							case 'submited':
								$sub_operate .= '<span title="审核" id="'.Yii::app()->createUrl('rebate/check', array('id' => $baseform->id)).'" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setCheck(this);"><img src="/images/shenhe.png" /></span>';
								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
								break;
							case 'approved_1':
								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
								break;
							default: break;
						}
					}
					if(checkOperation('销售折让:财务主管审核')){
						switch ($baseform->form_status)
						{
							case 'approved_1':
								$sub_operate .= '<span title="财务主管审核" id="'.Yii::app()->createUrl('rebate/check', array('id' => $baseform->id)).'" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setCheck(this);"><img src="/images/shenhe.png" /></span>';
								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
								break;
							case 'approve':
								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
								break;
							default: break;
						}
					}						
				}elseif(($item->type == 'high' && checkOperation("高开折让:审核"))||($item->type=='shipment'&&checkOperation("采购折让:审核"))){
					switch ($baseform->form_status)
					{
						case 'submited':
							$sub_operate .= '<span title="审核" id="'.Yii::app()->createUrl('rebate/check', array('id' => $baseform->id)).'" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此折让单吗？" lastdate="'.$baseform->last_update.'" onclick="setCheck(this);"><img src="/images/shenhe.png" /></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						case 'approve':
							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						default:
							break;
					}
				}
				
					
// 				if (($item->type == 'sale' && checkOperation("销售折让:审核")) || ($item->type == 'high' && checkOperation("高开折让:审核"))||($item->type=='shipment'&&checkOperation("采购折让:审核")))
// 				{
// 					switch ($baseform->form_status)
// 					{
// 						case 'submited':
// 							$sub_operate .= '<span title="审核" id="'.Yii::app()->createUrl('rebate/check', array('id' => $baseform->id)).'" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此折让单吗？" lastdate="'.$baseform->last_update.'" onclick="setCheck(this);"><img src="/images/shenhe.png" /></span>';
// 							if (++ $operate_count < 4) {
// 								$operate .= $sub_operate; $sub_operate = '';
// 							}
// 							break;
// 						case 'approve':
// 							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
// 							if (++ $operate_count < 4) {
// 								$operate .= $sub_operate; $sub_operate = '';
// 							}
// 							break;
// 						default:
// 							break;
// 					}
// 				}
					
				if ($operate_count > 4)
				{
					$operate .= '<span class="more_but" title="更多"><img src="/images/gengduo.png"/></span>'.
							'<div class="cz_list_btn_more" num="0" style="width:120px">'.
							$sub_operate.
							'</div>';
				}
				else
				{
					$operate .= $sub_operate;
				}
				$operate .= '</div>';
		
				$status='';
				if ($baseform->form_type == 'XSZR' && in_array($baseform->form_status, array('submited', 'approved_1','approve')))
				{
					$status = '<span id="'.$baseform->id.'" class="status_btn">'.($baseform->form_status == 'submited' ? "审核中" : CommonForms::$formStatus[$baseform->form_status]).'</span>';
				}
				
				
				$mark = $i;
				$i++;
			}
			$da['data'] = array($mark);
			if ($item->type != 'high') $da['data'][] = $operate;
			$da['data'] = array_merge($da['data'], array(
					'<a title="查看详情" href="'.$view_url.'" class="a_view">'.$baseform->form_sn.'</a>',
					$status?$status:CommonForms::$formStatus[$baseform->form_status],
					$baseform->form_time,
					'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
					'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
					'<span title="'.$item->client->name.'">'.$item->client->short_name.'</span>',
					number_format($item->amount, 2),
					FrmRebate::$type[$item->type],
					$item->is_yidan?'是':'',
					$baseform->belong->nickname,
					$baseform->belong->team->name,
					$baseform->operator->nickname,
					$baseform->form_status == 'approve' ? $baseform->approver->nickname : '',
					$baseform->form_status == 'approve' && $baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '',
					'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,"utf-8").'</span>',
			));
			if ($baseform->form_status == 'delete')
				$da['data'][] = $baseform->delete_reason;
		
			$da['group'] = $baseform->form_sn;
			array_push($tableData, $da);
		}
		return array($tableData, $pages, $totaldata);
	}
	
	//销售折让 列表
	public static function getFormBillList($search, $type) 
	{
		switch ($type) 
		{
			case 'XSZR': 
				$_baseform = 'baseformXSZR';
				break;
			case 'GKZR': 
				$_baseform = 'baseformGKZR';
				break;
			default: 
				return ;
				break;
		}
		
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "4%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "15%"),
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "11%"),
				array('name' => "客户/物流商/高开结算单位", 'class' => "sort-disabled", 'width' => "18%"),
				array('name' => "金额", 'class' => "sort-disabled text-right", 'width' => "10%"),
				array('name' => "乙单", 'class' => "sort-disabled", 'width' => "5%"),
				array('name' => "折让类型", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "业务组", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "12%"),
		);
		
		$tableData = array();
		$model = new FrmRebate();
		$criteria = new CDbCriteria();
		$criteria->with = array($_baseform);
		
		//搜索
		if (!empty($search))
		{
			if ($search['company_id'])
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
			}
			if ($search['title_id'])
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
// 			if ($search['is_yidan']) 
// 			{
// 				$criteria->addCondition("is_yidan = :is_yidan");
// 				$criteria->params[':is_yidan'] = $search['is_yidan'];
// 			}
			if ($search['keywords'])
			{
				$criteria->addCondition("$_baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if ($search['time_L'])
			{
				$criteria->addCondition("$_baseform.created_at >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']);
			}
			if ($search['time_H'])
			{
				$criteria->addCondition("$_baseform.created_at <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']);
			}
			// if ($search['owned_by'])
			// {
			// 	$criteria->addCondition("$_baseform.owned_by = :owned_by");
			// 	$criteria->params[':owned_by'] = $search['owned_by'];
			// }
		}
		$criteria->compare("$_baseform.form_type", "XSZR", true);
		$criteria->compare("$_baseform.is_deleted", '0', true);
		$criteria->compare("$_baseform.form_status", "approve", true);
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['bill_list']) ? intval($_COOKIE['bill_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "$_baseform.created_at DESC";
		
		if (!$search['title_id'] || !$search['company_id']) return array($tableHeader, $tableData, $pages);
		$bill =  $model->findAll($criteria);
		if (!$bill) return array($tableHeader, $tableData, $pages);
		
		$i = 1;
		foreach ($bill as $item) 
		{
			$mark = '';
			$operate = '';
			$da = array();
			$baseform = $item->$_baseform;
			if ($baseform) 
			{
				$mark = $i;
				$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill" yidan="'.$item->is_yidan.'" value="'.$baseform->id.'" />';
				$i++;
			} 
			
			$da['data'] = array($mark, 
					$operate, 
					$baseform->form_sn,
					$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
					'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
					'<span class="real_fee">'.number_format($item->amount, 2).'</span>',
					$item->is_yidan == 1 ? "是" : "否",
					FrmRebate::$type[$item->type],
					$item->team->name,
					$baseform->belong->nickname, //业务员
			);
			$da['group'] = $baseform->form_sn;
			array_push($tableData, $da);
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	//均摊
	public static function shareEqually($id, $type = '') 
	{
		$baseform = CommonForms::model()->findByPK($id);
		$form = new Rebate($id, $baseform->form_type);
		if ($form->commonForm->form_status != 'approve') return false;
		
		switch ($form->mainInfo->type) 
		{
			case 'sale': //销售折让XSZR
				$rebateRelations = $form->mainInfo->rebateRelation;
				if (!is_array($rebateRelations) || count($rebateRelations) <= 0) break;
				
				//获取总重
				$total_weight = 0.0;
				foreach ($rebateRelations as $each)
				{
					ProfitChange::createNew('sale',$each->sales_id,0);
					$sales = $each->sales_form->sales;
					$total_weight += floatval($sales->confirm_status == 1 ? $sales->confirm_weight : $sales->weight);
				}
				$can_fee = 0;
				$can_weight = 0;
				foreach ($rebateRelations as $each)
				{
					if ($total_weight == 0) continue;
					$sales = $each->sales_form->sales;
					$sales->rebate_fee = floatval($form->mainInfo->amount) / $total_weight; //折让均摊单价
// 					 * floatval($sales->confirm_status == 1 ? $sales->confirm_weight : $sales->weight);
					$sales->update();
					if($sales->is_yidan == 0){
						if($sales->confirm_status == 1){
							$can_weight += $sales->confirm_weight;
							$can_fee += $sales->rebate_fee * $sales->confirm_weight;
						}else{
							$can_weight += $sales->weight;
							$can_fee += $sales->rebate_fee * $sales->weight;
						}
					}
				}
				/*
				//设置可开票明细
				$model = new DetailForInvoice();
				$model->type = 'rebate';
				$model->form_id = $baseform->id;
				$model->checked_weight = 0;
				$model->checked_money = 0;
				$model->weight = $can_weight;
				$model->money = -$can_fee;
				$model->title_id = $form->mainInfo->title_id;
				$model->company_id = $form->mainInfo->company_id;
				$model->insert();
				*/
				break;
			case 'high': //高开折让GKZR
				$criteria = new CDbCriteria();
				$criteria->with = array('baseform_fkdj');
				$criteria->addCondition("t.rebate_form_id = :rebate_form_id");
				$criteria->addCondition("t.bill_type = 'GKFK'");
				$criteria->compare('baseform_fkdj.is_deleted', '0', true);
				$criteria->params[':rebate_form_id'] = $form->commonForm->id;
				$formBill = FrmFormBill::model()->find($criteria);
				if (!is_array($formBill->relation) || count($formBill->relation) <= 0) break;
				
				//获取总重
				$total_weight = 0.0;
				foreach ($formBill->relation as $relation)
				{
					$highopen = $relation->bill_form->highopen;
					if (floatval($highopen->price) <= 0) continue;
					$total_weight += floatval($highopen->salesDetail->weight);
				}
				
				foreach ($formBill->relation as $relation)
				{
					$highopen = $relation->bill_form->highopen;
					if (floatval($highopen->price) <= 0) continue;
					$highopen->discount += floatval($form->mainInfo->amount) / $total_weight; //折让均摊单价
// 					 * floatval($highopen->salesDetail->weight);
					$highopen->update();
				}
				break;
			default: break;
		}
	}
	
	//修改销售折让时间
	public static function setRebateDate($id,$date){
		if($id){
			$baseform = CommonForms::model()->findByPk($id);
			if($baseform){
				$oldJson = $baseform->datatoJson();
				$baseform->form_time = $date;
				$baseform->update();
				$newJson = $baseform->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$newJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}
			$turnover = Turnover::model()->find("common_forms_id=$id");
			if($turnover){
				$oldJson = $turnover->datatoJson();
				$turnover->created_at = strtotime($date);
				$turnover->update();
				$newJson = $turnover->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$newJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}
		}
	}
}
