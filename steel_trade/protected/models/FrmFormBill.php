<?php

/**
 * This is the biz model class for table "frm_form_bill".
 *
 */
class FrmFormBill extends FrmFormBillData
{
	public $total_weight;
	public $total_price;
	public $total_num;
	public static $billTypes = array(
			'CGFK' => "采购付款", 
			'XSSK' => "销售收款", 
			'XSTH' => "销售退货付款", 
			'CGTH' => "采购退货收款", 
			'XSZR' => "销售折让",
			'GKZR' => "高开折让", 
			'GKFK' => "高开付款",
			'DLFK' => "代理付款", 
			'DLSK' => "代理收款", 
			'TPYF' => "托盘预付", 
			'TPSH' => "托盘赎回",
			'YF' => "运费", 
			'CKFL' => "仓库返利", 
			'GCFL' => "钢厂返利", 
			'CCFY' => "仓储费用", 
			'BZJ' => "保证金", 
	);
	public static $payTypes = array(
			'transfer' => "转账", 
			'money' => "现金", 
			'check' => "支票", 
			'cyber' => "网银", 
			'adjust' => "调整账户", 
			'summary' => "银行承兑汇总"
	);
	#:array('money'=>"现金",'cyber'=>'网银');

	public static function getPayTypes()
	{
	    if(checkOperation("收付款方式全部")){
	    	$return=array('transfer' => "转账", 'money' => "现金", 	'check' => "支票",'cyber' => "网银", 
			'adjust' => "调整账户", 
			'summary' => "银行承兑汇总"
			);
	    }else{
	    	$return=array('money'=>"现金",'cyber'=>'网银');
	    }
		return $return;
	}

	
	public static $hasYidan = array('CGFK', 'XSSK');
	
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
			'baseform_fkdj' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform_fkdj.form_type = 'FKDJ'"),
			'baseform_skdj' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform_skdj.form_type = 'SKDJ'"),
			'baseformFKDJ' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformFKDJ.form_type = 'FKDJ'"), 
			'baseformSKDJ' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformSKDJ.form_type = 'SKDJ'"),
			'relation' => array(self::HAS_MANY,'FormBillRelation','bill_id'),
			'pledgeCompany' => array(self::BELONGS_TO, 'DictCompany', 'pledge_company_id'),
			'bankInfo' => array(self::BELONGS_TO, 'BankInfo', 'bank_info_id'),
			'dictBankInfo' => array(self::BELONGS_TO, 'DictBankInfo', 'dict_bank_info_id'),
			'pledgeBankInfo' => array(self::BELONGS_TO, 'BankInfo', 'pledge_bank_info_id'),
			'account' => array(self::BELONGS_TO, 'User', 'account_by'), //入账人
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
			'form_type' => 'Form Type',
			'bill_type' => 'Bill Type',
			'is_yidan' => 'Is Yidan',
			'pay_type' => 'Pay Type',
			'company_id' => 'Company',
			'title_id' => 'Title',
			'fee' => 'Fee',
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
        $criteria->compare('form_type',$this->form_type,true);
        $criteria->compare('bill_type',$this->bill_type,true);
        $criteria->compare('is_yidan',$this->is_yidan);
        $criteria->compare('pay_type',$this->pay_type,true);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('pledge_company_id',$this->pledge_company_id);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('bank_info_id',$this->bank_info_id);
        $criteria->compare('fee',$this->fee,true);
        $criteria->compare('weight',$this->weight,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmFormBill the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function	getInputData($post) 
	{
		$data = array();
		$td_id = $post['td_id'];
		$td_common_id = $post['td_common_id'];
		$discount = $post['discount'] ? $post['discount'] : array();
		
		$post['FrmFormBill']['relation'] = array();
		for ($i = 0; $i < count($td_common_id); $i++)
		{
			$relation_data = array();
			if ($td_id['id'] != "") 
				$relation_data['id'] = intval($td_id[$i]);
			
			$relation_data['common_id'] = intval($td_common_id[$i]);
			$relation_data['discount'] = floatval(numChange($discount[$i]));
			$post['FrmFormBill']['relation'][$i] = (Object)$relation_data;
		}
		
		$post['FrmFormBill']['fee'] = numChange($post['FrmFormBill']['fee']);
		$post['FrmFormBill']['theory_fee'] = numChange($post['FrmFormBill']['theory_fee']);
		$post['FrmFormBill']['weight'] = numChange($post['FrmFormBill']['weight']);
		if ($post['FrmFormBill']['reach_at']) 
			$post['FrmFormBill']['reach_at'] = strtotime($post['FrmFormBill']['reach_at'].' 23:59:59');
		$post['CommonForms']['created_by'] = currentUserId();
		$post['CommonForms']['created_at'] = strtotime($post['CommonForms']['created_at']);
		
		$data['main'] = (Object)$post['FrmFormBill'];
		$data['common'] = (Object)$post['CommonForms'];
		return $data;
	}

	public static function getFormBillList($search, $type) 
	{
		$tableData = array();
		$model = new FrmFormBill();
		$criteria = new CDbCriteria();
		if ($type == "FKDJ") $_baseform = "baseform_fkdj"; 
		elseif ($type == "SKDJ") $_baseform = "baseform_skdj";
		$criteria->with = array('company', 'relation', $_baseform);
		
		if (!empty($search)) 
		{
			if ($search['keywords']) 
			{
				$criteria->addCondition("$_baseform.form_sn like :keywords");
// 				$criteria->addCondition("company.name like :keywords", 'OR');
				$criteria->addCondition("$_baseform.comment like :keywords", 'OR');
				$criteria->params[':keywords'] = "%".strtoupper($search['keywords'])."%";
			}
			if ($search['time_L']) //开始时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP($_baseform.form_time) >= :time_L");
// 				$criteria->addCondition("t.account_at >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']." 00:00:00");
			}
			if ($search['time_H']) //结束时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP($_baseform.form_time) <= :time_H");
// 				$criteria->addCondition("t.account_at <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']." 23:59:59");
			}
			if ($search['account_time_L']) //入账开始时间
			{
				$criteria->addCondition("t.account_at >= :account_time_L");
				$criteria->params[':account_time_L'] = strtotime($search['account_time_L']." 00:00:00");
			}
			if ($search['account_time_H']) //入账结束时间
			{
				$criteria->addCondition("t.account_at <= :account_time_H");
				$criteria->params[':account_time_H'] = strtotime($search['account_time_H']." 23:59:59");
			}
			if ($search['title_id']) //公司抬头
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
			if ($search['company_id']) //结算单位
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
			}
			if ($search['client_id']) //客户
			{
				$criteria->addCondition("client_id = :client_id");
				$criteria->params[':client_id'] = $search['client_id'];
			}
			if ($search['form_status']) //状态
			{
				if($search['form_status']!='unaccount'&&$search['form_status']!='all'&&$search['form_status']!='approving')
				{
					$criteria->addCondition("$_baseform.form_status = :form_status");
					$criteria->params[':form_status'] = $search['form_status'];
				}elseif($search['form_status']=='all'){
					$criteria->addCondition("$_baseform.form_status !='delete'");
				}elseif($search['form_status']=='approving'){
					$criteria->addCondition("$_baseform.form_status in ('submited','approved_1','approved_2','approved_3')");
				}else{
					$criteria->addCondition("$_baseform.form_status in ('unsubmit','submited','approve','approved_1','approved_2','approved_3')");
				}
				
			}
			if ($search['is_account'] != "") 
			{
				if ($search['is_account'] == '0') $criteria->addCondition("account_by = 0");
				elseif ($search['is_account'] == '1') $criteria->addCondition("account_by > 0");
			}
			if ($search['is_yidan'] !== "") //是否乙单
			{
				if ($search['is_yidan'] == 1) $criteria->addCondition("is_yidan = 1");
				else $criteria->addCondition("ISNULL(t.is_yidan) or t.is_yidan=0");
			}
			if ($search['bill_type']) //收付款类型
			{
				$criteria->addCondition("bill_type = :bill_type");
				$criteria->params[':bill_type'] = $search['bill_type'];
			}
			if ($search['pay_type']) //收付款方式
			{
				$criteria->addCondition("pay_type = :pay_type");
				$criteria->params[':pay_type'] = $search['pay_type'];
			}
			if($search['owned']){//业务员
				$criteria->compare("$_baseform.owned_by",$search['owned']);
			}
		}
		$user=Yii::app()->user->userid;
		if($type=='FKDJ'&&$_COOKIE['bill_view']=='belong')
		{
			$ruzhang=checkOperation('付款登记:入账');
			$chuna=checkOperation('付款登记:出纳审核');
			$zongjingli=checkOperation('付款登记:总经理审核');
			$caiwuzhuguan=checkOperation('付款登记:财务主管审核');
			$yewu=checkOperation('付款登记:业务经理审核');
			$condition="";
			if($ruzhang)$condition.="or  $_baseform.form_status ='approve' or $_baseform.form_status='accounted' ";
			if($chuna)$condition.="or  $_baseform.form_status='approve' or $_baseform.form_status='approved_3' ";
			if($zongjingli)$condition.="or  $_baseform.form_status='approved_2' or $_baseform.form_status='approved_3' ";
			if($caiwuzhuguan)$condition.="or  $_baseform.form_status='approved_1' or $_baseform.form_status='approved_2' ";
			if($yewu)$condition.="or $_baseform.form_status='submited' or $_baseform.form_status='approved_1' or $_baseform.form_status='approve' ";
			if($condition)
					$condition='('.substr($condition, 2).") or $_baseform.owned_by=$user or $_baseform.created_by=$user";
				else				
					$condition=" $_baseform.owned_by=$user or $_baseform.created_by=$user";
			$criteria->addCondition($condition);			
		}elseif($type=='SKDJ'&&$_COOKIE['sbill_view']=='belong')
		{
			
			if(!checkOperation('收款登记:入账')){
				$condition="$_baseform.owned_by=$user or $_baseform.created_by=$user";
				$criteria->addCondition($condition);
			}
		}
		$criteria->compare("$_baseform.form_type", $type, true);
		if (!$search['form_status']) 
		{
			$criteria->addCondition("$_baseform.form_status in ('unsubmit','submited','approve','approved_1','approved_2','approved_3')");
		}else{
			if($search['form_status']!='delete')
			{
				$criteria->compare("$_baseform.is_deleted", '0', true);
			}
		}
		
		//总计
		$c = clone $criteria;
		$c->with = array('company',$_baseform);
		$c->select = "sum(t.fee) as total_price, count(*) as total_num";
		$total = FrmFormBill::model()->find($c);
		
		$totaldata = array();
		$totaldata['price'] = $total->total_price;
		$totaldata['total_num'] = $total->total_num;
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['form_bill_list']) ? intval($_COOKIE['form_bill_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "$_baseform.created_at DESC";
		
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
			switch ($type)
			{
				case 'FKDJ': $baseform = $item->baseform_fkdj; break;
				case 'SKDJ': $baseform = $item->baseform_skdj; break;
				default: break;
			}
		
			if ($baseform)
			{
				$view_url = Yii::app()->createUrl("formBill/view", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
				$edit_url = Yii::app()->createUrl("formBill/update", array('id' => $baseform->id, 'last_update'=>$baseform->last_update,'fpage' => $_REQUEST['page']));
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
				$sub_url = Yii::app()->createUrl('formBill/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
// 				$del_url = Yii::app()->createUrl('formBill/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
				$checkC_url = Yii::app()->createUrl('formBill/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
				$checkA_url = Yii::app()->createUrl('formBill/accounted', array('id' => $baseform->id, 'type' => 'accounted', 'fpage' => $_REQUEST['page']));
				$checkCA_url = Yii::app()->createUrl('formBill/accounted', array('id' => $baseform->id, 'type' => 'cancel_accounted', 'last_update' => $baseform->last_update));
					
				$operate .= '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
				if ((in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by))or checkOperation('付款删除权限')) && $type == "FKDJ" && checkOperation("付款登记:新增"))
				{
					switch ($baseform->form_status)
					{
						case 'unsubmit':
							$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png"/></span>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							break;
						case 'submited':
							$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							break;
						default: break;
					}
				}
				elseif ( $type == "SKDJ" &&  (in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by))  && checkOperation("收款登记:新增")))
				{
					switch ($baseform->form_status)
					{
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
						default: break;
					}
				}else if( $type == "SKDJ" &&  checkOperation("收款登记:作废")){
					switch ($baseform->form_status)
					{
						case 'submited':
							$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png"/></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						default: break;
					}
				}
				//审核
				if ($type == "FKDJ") 
				{
					if (checkOperation("付款登记:业务经理审核"))
					{
						switch ($baseform->form_status)
						{
							case 'submited':
								if ($item->bill_type == 'DLFK') $sub_operate .= '<span title="审核" id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'"  frm="fsk"  class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
								else $sub_operate .= '<span title="业务经理审核" id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'" class="check_form"  frm="fsk" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
								if (++ $operate_count < 4) {
									$operate .= $sub_operate; $sub_operate = '';
								}
								break;
							case 'approved_1':
// 								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消业务经理审核"><img src="/images/qxsh.png" /></span>';
								if (checkOperation("付款登记:取消审核")) 
								{
									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
									if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
								}
								break;
							case 'approve': 
								if ($item->bill_type == 'DLFK') 
								{
									if (checkOperation("付款登记:取消审核"))
									{
										$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
										if (++ $operate_count < 4) {
											$operate .= $sub_operate; $sub_operate = '';
										}
									}
								}
								break;
							default: break;
						}
					}
		
					if (checkOperation("付款登记:财务主管审核"))
					{
						switch ($baseform->form_status)
						{
							case 'approved_1':
								$sub_operate .= '<span title="财务主管审核" id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'" class="check_form" frm="fsk" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
								if (++ $operate_count < 4) {
									$operate .= $sub_operate; $sub_operate = '';
								}
								break;
							case 'approved_2':
// 								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
								if (checkOperation("付款登记:取消审核"))
								{
									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
									if (++ $operate_count < 4) {
										$operate .= $sub_operate; $sub_operate = '';
									}
								}
								break;
							case 'approved_3':
								if (in_array($item->bill_type, array('CGFK', 'TPSH')) && $item->fee < 10000 && !checkOperation("付款登记:总经理审核"))
								{
// 									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
									if (checkOperation("付款登记:取消审核"))
									{
										$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
										if (++ $operate_count < 4) {
											$operate .= $sub_operate; $sub_operate = '';
										}
									}
								}
								break;
							default: break;
						}
					}
					
					if (checkOperation("付款登记:总经理审核"))
					{
						switch ($baseform->form_status)
						{
							case 'approved_2':
								$sub_operate .= '<span title="总经理审核" id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'" class="check_form"  frm="fsk"  str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
								break;
							case 'approved_3':
								if (in_array($item->bill_type, array('CGFK', 'TPSH')) && $item->fee < 10000)
								{
// 									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
									if (checkOperation("付款登记:取消审核"))
									{
										$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
										if (++ $operate_count < 4) {
											$operate .= $sub_operate; $sub_operate = '';
										}
									}
								} 
								else 
								{
// 									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消总经理审核"><img src="/images/qxsh.png" /></span>';
									if (checkOperation("付款登记:取消审核"))
									{
										$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
										if (++ $operate_count < 4) {
											$operate .= $sub_operate; $sub_operate = '';
										}
									}
								}
								break;
							default: break;
						}
					}
					
					if (checkOperation("付款登记:出纳审核")) 
					{
						switch ($baseform->form_status) 
						{
							case 'approved_3': 
								$sub_operate .= '<span title="出纳审核" position="chuna"  id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'" class="check_form"  frm="fsk" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
								break;
							case 'approve': 
								if ($item->bill_type != 'DLFK') 
								{
// 									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消出纳审核"><img src="/images/qxsh.png" /></span>';
									if (checkOperation("付款登记:取消审核"))
									{
										$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
										if (++ $operate_count < 4) {
											$operate .= $sub_operate; $sub_operate = '';
										}
									}
								}
								break;
						}
					}
				}
				//入账
				if ($type == "FKDJ" && checkOperation("付款登记:入账"))
				{
					switch ($baseform->form_status)
					{
						case 'approve':
							$sub_operate .= '<span class="accounted_form colorbox" url="'.$checkA_url.'" title="入账"><span><img src="/images/ruzhang.png"/></span></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						case 'accounted':
							$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消入账"><img src="/images/qxrz.png"/></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						default: break;
					}
				}
				elseif ($type == "SKDJ" && checkOperation("收款登记:入账"))
				{
					switch ($baseform->form_status)
					{
						case 'submited':
							$sub_operate .= '<a class="accounted_form" href="'.$checkA_url.'" title="入账"><span><img src="/images/ruzhang.png"/></span></a>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						case 'accounted':
							$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消入账"><img src="/images/qxrz.png"/></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						default: break;
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
					
				$mark = $i;
				$i++;
			}
			
			if ($baseform->form_type == 'FKDJ' && in_array($baseform->form_status, array('submited', 'approved_1', 'approved_2', 'approved_3', 'approve')))
			{
				$status = '<span id="'.$baseform->id.'" form_sn="'.$baseform->form_sn.'"  class="status_btn">'.($baseform->form_status == 'submited' ? "审核中" : CommonForms::$formStatus[$baseform->form_status]).'</span>';
			}	
			else
			{
				$status = CommonForms::$formStatus[$baseform->form_status];
			}
		
			$da['data'] = array($mark,
					$operate,
					'<a title="查看详情" href="'.$view_url.'" class="a_view">'.$baseform->form_sn.'</a>',
					$status,
					$baseform->form_time ? $baseform->form_time: '',
					'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
					'<span title="'.($item->dictBankInfo ? $item->dictBankInfo->dict_name.'('.$item->dictBankInfo->bank_number.')' : '').'">'.($item->dictBankInfo ? $item->dictBankInfo->dict_name : '').'</span>',
					'<span title="'.$item->company->name.'">'.$item->company->name.'</span>',
					'<span title="'.$item->client->name.'">'.$item->client->short_name.'</span>',
					number_format($item->fee, 2),
					);
			if ($type == 'FKDJ' && $_COOKIE["bill_view"]=='all'){
				$yue = Turnover::getTurYu11($item->title_id, $item->company_id);
				$yue = number_format($yue,2);
				if($yue == 0){$yue = 0;}
				$da['data'][]=$yue;
			}
			$da['data'] = array_merge($da['data'], array(
					FrmFormBill::$payTypes[$item->pay_type],
					FrmFormBill::$billTypes[$item->bill_type],
					$item->is_yidan == 1 ? "是" : "",
					$item->reach_at > 0 ? date('Y-m-d', $item->reach_at) : '',
					$baseform->belong->nickname,
					$baseform->operator->nickname,
			));
			if ($baseform->form_type == 'FKDJ')
			{
				// $da['data'][] = $baseform->form_status == 'approve' ? $baseform->approver->nickname : '';
				// $da['data'][] = $baseform->form_status == 'approve' && $baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '';
				$da['data'][] = '<span title="'.htmlspecialchars($item->purpose).'">'.mb_substr($item->purpose, 0,15,"utf-8").'</span>';
			}
			$da['data'] = array_merge($da['data'], array(
					$item->account_by > 0 ? $item->account->nickname : "",
					//$item->account_by > 0 && $item->account_at > 0 ? date('Y-m-d', $item->account_at) : "",
					'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,16,"utf-8").'</span>',
			));
		
			if ($baseform->form_status == 'delete')
				$da['data'][] = '<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason, 0,15,"utf-8").'</span>';
		
			$da['group'] = $baseform->form_sn;
			array_push($tableData, $da);
		}
		return array($tableData, $pages, $totaldata);		
	}
	
	/**
	 * 获取代理付款信息
	 * @param array $search
	 */
	public static function getDlfkList($search)  
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "5%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "15%"), 
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "公司", 'class' => "sort-disabled", 'width' => "11%"),
				array('name' => "结算单位", 'class' => "sort-disabled", 'width' => "11%"),
				array('name' => "总金额", 'class' => "sort-disabled text-right", 'width' => "12%"),
				array('name' => "托盘公司", 'class' => "sort-disabled", 'width' => "11%"),
				array('name' => "类型", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "10%"),
		);
		
		$tableData = array();
		$model = new FrmFormBill();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseformFKDJ');
		$criteria->addCondition("t.bill_type = 'DLFK'");
		
		//搜索
		if (!empty($search)) 
		{
			if ($search['owned_by'])
			{
				$criteria->addCondition("baseformFKDJ.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		$criteria->compare("baseformFKDJ.form_type", 'FKDJ', true);
		$criteria->compare("baseformFKDJ.is_deleted", '0', true);
		$criteria->compare("baseformFKDJ.form_status", "approve", true);
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['dlfk_list']) ? intval($_COOKIE['dlfk_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseformFKDJ.created_at DESC";
		
		$bill =  $model->findAll($criteria);
		if (!$bill) return array($tableHeader, $tableData, $pages);
		$i = 1;
		foreach ($bill as $item) 
		{
			$mark = '';
			$operate = '';
			$da = array();
			$baseform = $item->baseformFKDJ;
			if ($baseform) 
			{
				$mark = $i;
				$i++;
			}
			$da['data'] = array($mark, 
					$baseform->form_sn, 
					$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
					'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
					'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
					number_format($item->fee, 2),
					'<span title="'.$item->pledgeCompany->name.'">'.$item->pledgeCompany->short_name.'</span>',
					FrmFormBill::$billTypes[$item->bill_type],
					$baseform->belong->nickname, //业务员
			);
			$da['group'] = $baseform->form_sn;
			array_push($tableData, $da);
		}
		
		return array($tableHeader, $tableData, $pages);
	}

	/**
	 * 收付款 导出
	 * @param  array $search 搜索条件
	 * @param  string $type   收款|付款
	 * @return array $content 查询结果
	 */
	public static function getAllList($search, $type) 
	{
		$model = new FrmFormBill();
		$criteria = new CDbCriteria();
		
		if ($type == "FKDJ") $_baseform = "baseform_fkdj"; 
		elseif ($type == "SKDJ") $_baseform = "baseform_skdj";
		$criteria->with = array('company', $_baseform);
		
		if (!empty($search)) 
		{
			if ($search['keywords']) 
			{
				$criteria->addCondition("$_baseform.form_sn like :keywords");
				$criteria->addCondition("$_baseform.comment like :keywords", 'OR');
				$criteria->params[':keywords'] = "%".strtoupper($search['keywords'])."%";
			}
			if ($search['time_L']) //开始时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP($_baseform.form_time) >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']." 00:00:00");
			}
			if ($search['time_H']) //结束时间
			{
				$criteria->addCondition("UNIX_TIMESTAMP($_baseform.form_time) <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']." 23:59:59");
			}
			if ($search['account_time_L']) //入账开始时间
			{
				$criteria->addCondition("t.account_at >= :account_time_L");
				$criteria->params[':account_time_L'] = strtotime($search['account_time_L']." 00:00:00");
			}
			if ($search['account_time_H']) //入账结束时间
			{
				$criteria->addCondition("t.account_at <= :account_time_H");
				$criteria->params[':account_time_H'] = strtotime($search['account_time_H']." 23:59:59");
			}
			if ($search['title_id']) //公司抬头
			{
				$criteria->addCondition("title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
			if ($search['company_id']) //结算单位
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
			}
			if ($search['client_id']) //客户
			{
				$criteria->addCondition("client_id = :client_id");
				$criteria->params[':client_id'] = $search['client_id'];
			}
			if($search['form_status']!='unaccount'&&$search['form_status']!='all')
			{
				$criteria->addCondition("$_baseform.form_status = :form_status");
				$criteria->params[':form_status'] = $search['form_status'];
			}elseif($search['form_status']=='all'){
				$criteria->addCondition("$_baseform.form_status !='delete'");
			}else{
				$criteria->addCondition("$_baseform.form_status in ('unsubmit','submited','approve','approved_1','approved_2','approved_3')");
			}
			// if ($search['form_status']) //状态
			// {
			// 	$criteria->addCondition("$_baseform.form_status = :form_status");
			// 	$criteria->params[':form_status'] = $search['form_status'];
			// }
			if ($search['is_account'] != "") 
			{
				if ($search['is_account'] == '0') $criteria->addCondition("account_by = 0");
				elseif ($search['is_account'] == '1') $criteria->addCondition("account_by > 0");
			}
			if ($search['is_yidan'] !== "") //是否乙单
			{
				if ($search['is_yidan'] == 1) $criteria->addCondition("is_yidan = 1");
				else $criteria->addCondition("ISNULL(t.is_yidan) or t.is_yidan=0");
			}
			if ($search['bill_type']) //收付款类型
			{
				$criteria->addCondition("bill_type = :bill_type");
				$criteria->params[':bill_type'] = $search['bill_type'];
			}
			if ($search['pay_type']) //收付款方式
			{
				$criteria->addCondition("pay_type = :pay_type");
				$criteria->params[':pay_type'] = $search['pay_type'];
			}
			if($search['owned']){//业务员
				$criteria->compare("$_baseform.owned_by",$search['owned']);
			}
		}
		$user=Yii::app()->user->userid;
		if($type=='FKDJ'&&$_COOKIE['bill_view']=='belong')
		{
			$ruzhang=checkOperation('付款登记:入账');
			$chuna=checkOperation('付款登记:出纳审核');
			$zongjingli=checkOperation('付款登记:总经理审核');
			$caiwuzhuguan=checkOperation('付款登记:财务主管审核');
			$yewu=checkOperation('付款登记:业务经理审核');
			$condition="";
			if($ruzhang)$condition.="or  $_baseform.form_status ='approve' or $_baseform.form_status='accounted' ";
			if($chuna)$condition.="or  $_baseform.form_status='approve' or $_baseform.form_status='approved_3' ";
			if($zongjingli)$condition.="or  $_baseform.form_status='approved_2' or $_baseform.form_status='approved_3' ";
			if($caiwuzhuguan)$condition.="or  $_baseform.form_status='approved_1' or $_baseform.form_status='approved_2' ";
			if($yewu)$condition.="or $_baseform.form_status='submited' or $_baseform.form_status='approved_1' or $_baseform.form_status='approve' ";
			if($condition)
				$condition='('.substr($condition, 2).") or $_baseform.owned_by=$user or $_baseform.created_by=$user";
				else
					$condition=" $_baseform.owned_by=$user or $_baseform.created_by=$user";
					$criteria->addCondition($condition);
		}elseif($type=='SKDJ'&&$_COOKIE['sbill_view']=='belong')
		{
				
			if(!checkOperation('收款登记:入账')){
				$condition="$_baseform.owned_by=$user or $_baseform.created_by=$user";
				$criteria->addCondition($condition);
			}
		}
		$criteria->compare("$_baseform.form_type", $type, true);
		if (!$search['form_status'])
		{
			$criteria->addCondition("$_baseform.form_status in ('unsubmit','submited','approve','approved_1','approved_2','approved_3')");
		}else{
			if($search['form_status']!='delete')
			{
				$criteria->compare("$_baseform.is_deleted", '0', true);
			}
		}
		
		//总计
		$c = clone $criteria;
		$c->select = "sum(t.fee) as total_price, count(*) as total_num";
		$total = FrmFormBill::model()->find($c);
		$totaldata = array();
		$totaldata[7] = $total->total_price;
		// $totaldata['total_num'] = $total->total_num;
		
		// $pages = new CPagination();
		// $pages->itemCount = $model->count($criteria);
		// $pages->pageSize =intval($_COOKIE['form_bill_list']) ? intval($_COOKIE['form_bill_list']) : Yii::app()->params['pageCount'];
		// $pages->applyLimit($criteria);
		$criteria->order = "$_baseform.created_at DESC";
		
		$items = $model->findAll($criteria);

		$content = array();
		if (!$items) return $content;

		foreach ($items as $item) {
			$relation = "";
			switch ($item->bill_type) {
				case 'CGFK': //采购付款 
					$baseform = $item->baseform_fkdj;
					break;
				case 'XSSK': //销售收款 
					$baseform = $item->baseform_skdj;
					break;
				case 'XSTH': //销售退货付款 
					$baseform = $item->baseform_fkdj;
					$relation = $item->relation;
					break;
				case 'CGTH': //采购退货收款 
					$baseform = $item->baseform_skdj;
					$relation = $item->relation;
					break;
				case 'XSZR': //销售折让
					$baseform = $item->baseform_fkdj;
					$relation = $item->relation;
					break;
				case 'GKFK': //高开付款
					$baseform = $item->baseform_fkdj;
					$relation = $item->relation;
					break;
				case 'DLFK': //代理付款 
					$baseform = $item->baseform_fkdj;
					$relation = $item->relation;
					break;
				case 'DLSK': //代理收款 
					$baseform = $item->baseform_skdj;
					break;
				case 'TPYF': //托盘预付 
					$baseform = $item->baseform_fkdj;
					break;
				case 'TPSH': //托盘赎回
					$baseform = $item->baseform_fkdj;
					$relation = $item->relation;
					break;
				case 'YF': //运费 
					$baseform = $item->baseform_fkdj;
					$relation = $item->relation;
					break;
				case 'CKFL': //仓库返利 
					$baseform = $item->baseform_skdj;
					break;
				case 'GCFL': //钢厂返利 
					$baseform = $item->baseform_skdj;
					break;
				case 'CCFY': //仓储费用 
					$baseform = $item->baseform_fkdj;
					break;
				case 'BZJ': //保证金 
					$baseform = $item->baseform_fkdj;
					break;
				default: 
					continue;
					break;
			}
			$status = ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') ? "审核中" : CommonForms::$formStatus[$baseform->form_status];

			if ($relation) {
				foreach ($relation as $each) {
					$relation_common = CommonForms::model()->findByPK($each->common_id);

					$temp = array();
					switch ($type) {
						case 'FKDJ': 
							$temp = array_merge($temp, array(
								$baseform->form_sn, //单号 0
								$baseform->form_time ? $baseform->form_time : '', //付款日期 1
								$item->title->short_name, //公司 2
								$item->company->name, //结算单位 3
								$item->client->name,
								FrmFormBill::$billTypes[$item->bill_type], //付款类型 4
								FrmFormBill::$payTypes[$item->pay_type], //付款方式 5
								numChange(number_format($item->fee, 2)), //金额 6
								$item->dictBankInfo ? $item->dictBankInfo->dict_name : '', //账户 7
								$item->reach_at > 0 ? date('Y-m-d', $item->reach_at) : '', //到账日期 8
								$baseform->form_status != 'accounted' ? $status : "已审核", //审批状态 9
								$item->is_yidan == 1 ? "是" : "", //乙单 10
								$baseform->form_status == 'accounted' ? "已入账" : "未入账", //入账状态 11
								$baseform->belong->nickname, // 12
								$baseform->operator->nickname, // 13
								$baseform->form_status == 'approve' ? $baseform->approver->nickname : '', // 14
								$baseform->form_status == 'approve' && $baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '', // 15
								$item->purpose, // 16
								$item->account_by > 0 ? $item->account->nickname : "", // 17
								$item->account_at > 0 ? date('Y-m-d', $item->account_at) : "", // 18
								$baseform->comment, // 19
							));
							$temp[21] = $relation_common->form_sn; //单号
							switch ($item->bill_type) {
								case 'XSTH': //销售退货付款 
									$each_data = $relation_common->salesReturn;
									$temp[23] = $each_data->warehouse->name; //仓库
									$temp[29] = $each_data->travel; //车船号
									break;
								case 'XSZR': //销售折让
									$each_data = $relation_common->rebate;
									$temp[27] = number_format($each_data->amount, 2);
									break;
								case 'GKFK': //高开付款
									$each_data = $relation_common->highopen;
									$sales_detail = $each_data->salesDetail;
									$product_info = DictGoodsProperty::getProName($sales_detail->brand_id) . "/" . DictGoodsProperty::getProName($sales_detail->product_id) . "/" . DictGoodsProperty::getProName($sales_detail->texture_id). "/" . DictGoodsProperty::getProName($sales_detail->rank_id) . "/" . $sales_detail->length;
									$temp[24] = $product_info; //产地/品名/材质/规格/长度
									$temp[25] = $sales_detail->amount; //件数
									$temp[26] = number_format($sales_detail->weight, 3); //重量
									break;
								case 'DLFK': //代理付款 
									$each_data = $relation_common->purchase;
									$temp[22] = $each_data->pledge->pledgeCompany->short_name; //托盘公司
									$temp[26] = number_format($each_data->weight, 3); //重量
									$temp[27] = number_format($each_data->price_amount, 2); //金额
									break;
								case 'TPSH': //托盘赎回
									$each_data = $relation_common->pledgeRedeem;
									$product_info = DictGoodsProperty::getProName($each_data->brand_id).($each_data->product_id ? "/".DictGoodsProperty::getProName($each_data->product_id) : '');
									$temp[22] = $each_data->company->short_name; //托盘公司
									$temp[24] = $product_info; //产地/品名
									$temp[27] = number_format($each_data->total_fee, 2); //金额
									$temp[28] = number_format($each_data->interest_fee, 2); //利息
									break;
								case 'YF': //运费 
									$each_data = $relation_common->billRecord;
									$temp[27] = numChange(number_format($each_data->weight, 3)); //重量
    								$temp[28] = numChange(number_format($each_data->amount, 2)); //利息
    								$temp[29] = $each_data->travel; //车船号
									break;
								default: 
									continue;
									break;
							}
							
							if ($baseform->form_status == 'delete') 
								$temp[30] = $baseform->delete_reason;
							break;
						
						case 'SKDJ': 
							$temp = array_merge($temp, array(
								$baseform->form_sn, //收款单号 0
								$baseform->form_time? $baseform->form_time : '', //收款日期 1
								$item->title->short_name, //公司 2
								$item->company->name, //结算单位 3
								$item->client->name,
								FrmFormBill::$billTypes[$item->bill_type], //收款类型 4
								FrmFormBill::$payTypes[$item->pay_type], //收款方式 5
								numChange(number_format($item->fee, 2)), //金额 6
								$item->dictBankInfo ? $item->dictBankInfo->dict_name : '', //账户 7
								$item->reach_at > 0 ? date('Y-m-d', $item->reach_at) : '', //到账日期 8
								$baseform->form_status != 'accounted' ? $status : "已提交", //审批状态 9
								$item->is_yidan == 1 ? "是" : "", //乙单 10
								$baseform->form_status == 'accounted' ? "已入账" : "未入账", //入账状态 11
								$baseform->belong->nickname, // 12
								$baseform->operator->nickname, // 13
								$item->account_by > 0 ? $item->account->nickname : "", // 14
								$item->account_at > 0 ? date('Y-m-d', $item->account_at) : "", // 15
								$baseform->comment, // 16
							));
							$temp[18] = $relation_common->form_sn; //单号

							switch ($item->bill_type) {
								case 'CGTH': //采购退货收款 
									$each_data = $relation_common->purchaseReturn;
									$temp[19] = $each_data->warehouse->name; //仓库
									$temp[20] = $each_data->travel; //车船号
									break;
								default: 
									continue;
									break;
							}

							if ($baseform->form_status == 'delete') 
								$temp[21] = $baseform->delete_reason;
							break;
					}
					array_push($content, $temp);
				}
			} else {
				$temp = array();
				switch ($type) {
					case 'FKDJ': 
						$temp = array_merge($temp, array(
							$baseform->form_sn, //单号 0
							$baseform->form_time ? $baseform->form_time : '', //付款日期 1
							$item->title->short_name, //公司 2
							$item->company->name, //结算单位 3
							$item->client->name,
							FrmFormBill::$billTypes[$item->bill_type], //付款类型 4
							FrmFormBill::$payTypes[$item->pay_type], //付款方式 5
							numChange(number_format($item->fee, 2)), //金额 6
							$item->dictBankInfo ? $item->dictBankInfo->dict_name : '', //账户 7
							$item->reach_at > 0 ? date('Y-m-d', $item->reach_at) : '', //到账日期 8
							$baseform->form_status != 'accounted' ? $status : "已审核", //审批状态 9
							$item->is_yidan == 1 ? "是" : "", //乙单 10
							$baseform->form_status == 'accounted' ? "已入账" : "未入账", //入账状态 11
							$baseform->belong->nickname, // 12
							$baseform->operator->nickname, // 13
							$baseform->form_status == 'approve' ? $baseform->approver->nickname : '', // 14
							$baseform->form_status == 'approve' && $baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '', // 15
							$item->purpose, // 16
							$item->account_by > 0 ? $item->account->nickname : "", // 17
							$item->account_at > 0 ? date('Y-m-d', $item->account_at) : "", // 18
							$baseform->comment, // 19
						));
						
						if ($baseform->form_status == 'delete') 
							$temp[30] = $baseform->delete_reason;
						break;
					
					case 'SKDJ': 
						$temp = array_merge($temp, array(
							$baseform->form_sn, //收款单号 0
							$baseform->form_time ? $baseform->form_time : '', //收款日期 1
							$item->title->short_name, //公司 2
							$item->company->name, //结算单位 3
							$item->client->name,
							FrmFormBill::$billTypes[$item->bill_type], //收款类型 4
							FrmFormBill::$payTypes[$item->pay_type], //收款方式 5
							numChange(number_format($item->fee, 2)), //金额 6
							$item->dictBankInfo ? $item->dictBankInfo->dict_name : '', //账户 7
							$item->reach_at > 0 ? date('Y-m-d', $item->reach_at) : '', //到账日期 8
							$baseform->form_status != 'accounted' ? $status : "已提交", //审批状态 9
							$item->is_yidan == 1 ? "是" : "", //乙单 10
							$baseform->form_status == 'accounted' ? "已入账" : "未入账", //入账状态 11
							$baseform->belong->nickname, // 12
							$baseform->operator->nickname, // 13
							$item->account_by > 0 ? $item->account->nickname : "", // 14
							$item->account_at > 0 ? date('Y-m-d', $item->account_at) : "", // 15
							$baseform->comment, // 16
						));

						if ($baseform->form_status == 'delete') 
							$temp[21] = $baseform->delete_reason;
						break;
				}
				array_push($content, $temp);
			}
		}
		array_push($content, $totaldata);
		return $content;
	}
	

	/*
	 * 获取操作按钮
	 */	
	public function getButtons($form_sn)
	{
// 		$item = new FrmFormBill();
// 		if ($type == "FKDJ") $_baseform = "baseform_fkdj";
// 		elseif ($type == "SKDJ") $_baseform = "baseform_skdj";
// 		$criteria->with = array('company', 'relation', 'baseform_fkdj');
		$type="FKDJ";
		$item=FrmFormBill::model()->with('company', 'relation', 'baseform_fkdj')->find('baseform_fkdj.form_sn="'.$form_sn.'"');
		$mark = '';
		$operate = '';
		$sub_operate = '';
		$operate_count = 0;
		$da = array();
		switch ($type)
		{
			case 'FKDJ': $baseform = $item->baseform_fkdj; break;
			case 'SKDJ': $baseform = $item->baseform_skdj; break;
			default: break;
		}
		
		if ($baseform)
		{
			$view_url = Yii::app()->createUrl("formBill/view", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
			$edit_url = Yii::app()->createUrl("formBill/update", array('id' => $baseform->id, 'last_update'=>$baseform->last_update,'fpage' => $_REQUEST['page']));
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
			$sub_url = Yii::app()->createUrl('formBill/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
			// 				$del_url = Yii::app()->createUrl('formBill/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
			$checkC_url = Yii::app()->createUrl('formBill/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
			$checkA_url = Yii::app()->createUrl('formBill/accounted', array('id' => $baseform->id, 'type' => 'accounted', 'fpage' => $_REQUEST['page']));
			$checkCA_url = Yii::app()->createUrl('formBill/accounted', array('id' => $baseform->id, 'type' => 'cancel_accounted', 'last_update' => $baseform->last_update));
				
			$operate .= '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
			if ((in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by))or checkOperation('付款删除权限')) && $type == "FKDJ" && checkOperation("付款登记:新增"))
			{
				switch ($baseform->form_status)
				{
					case 'unsubmit':
						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
						$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png"/></span>';
						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
						break;
					case 'submited':
						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
						break;
					default: break;
				}
			}
			elseif ( $type == "SKDJ" &&  (in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by))  && checkOperation("收款登记:新增")))
			{
				switch ($baseform->form_status)
				{
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
					default: break;
				}
			}else if( $type == "SKDJ" &&  checkOperation("收款登记:作废")){
				switch ($baseform->form_status)
				{
					case 'submited':
						$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png"/></span>';
						if (++ $operate_count < 4) {
							$operate .= $sub_operate; $sub_operate = '';
						}
						break;
					default: break;
				}
			}
			//审核
			if ($type == "FKDJ")
			{
				if (checkOperation("付款登记:业务经理审核"))
				{
					switch ($baseform->form_status)
					{
						case 'submited':
							if ($item->bill_type == 'DLFK') $sub_operate .= '<span title="审核" id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'"  frm="fsk" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
							else $sub_operate .= '<span title="业务经理审核" id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'" frm="fsk" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						case 'approved_1':
							// 								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消业务经理审核"><img src="/images/qxsh.png" /></span>';
							if (checkOperation("付款登记:取消审核"))
							{
								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							}
							break;
						case 'approve':
							if ($item->bill_type == 'DLFK')
							{
								if (checkOperation("付款登记:取消审核"))
								{
									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
									if (++ $operate_count < 4) {
										$operate .= $sub_operate; $sub_operate = '';
									}
								}
							}
							break;
						default: break;
					}
				}
		
				if (checkOperation("付款登记:财务主管审核"))
				{
					switch ($baseform->form_status)
					{
						case 'approved_1':
							$sub_operate .= '<span title="财务主管审核" id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'"  frm="fsk" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
							if (++ $operate_count < 4) {
								$operate .= $sub_operate; $sub_operate = '';
							}
							break;
						case 'approved_2':
							// 								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
							if (checkOperation("付款登记:取消审核"))
							{
								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
								if (++ $operate_count < 4) {
									$operate .= $sub_operate; $sub_operate = '';
								}
							}
							break;
						case 'approved_3':
							if (in_array($item->bill_type, array('CGFK', 'TPSH')) && $item->fee < 10000 && !checkOperation("付款登记:总经理审核"))
							{
								// 									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
								if (checkOperation("付款登记:取消审核"))
								{
									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
									if (++ $operate_count < 4) {
										$operate .= $sub_operate; $sub_operate = '';
									}
								}
							}
							break;
						default: break;
					}
				}
					
				if (checkOperation("付款登记:总经理审核"))
				{
					switch ($baseform->form_status)
					{
						case 'approved_2':
							$sub_operate .= '<span title="总经理审核" id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'"  frm="fsk" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							break;
						case 'approved_3':
							if (in_array($item->bill_type, array('CGFK', 'TPSH')) && $item->fee < 10000)
							{
								// 									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
								if (checkOperation("付款登记:取消审核"))
								{
									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
									if (++ $operate_count < 4) {
										$operate .= $sub_operate; $sub_operate = '';
									}
								}
							}
							else
							{
								// 									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消总经理审核"><img src="/images/qxsh.png" /></span>';
								if (checkOperation("付款登记:取消审核"))
								{
									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
									if (++ $operate_count < 4) {
										$operate .= $sub_operate; $sub_operate = '';
									}
								}
							}
							break;
						default: break;
					}
				}
					
				if (checkOperation("付款登记:出纳审核"))
				{
					switch ($baseform->form_status)
					{
						case 'approved_3':
							$sub_operate .= '<span title="出纳审核" position="chuna"  id="'.Yii::app()->createUrl('formBill/check', array('id' => $baseform->id)).'" frm="fsk" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此付款单吗？" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							break;
						case 'approve':
							if ($item->bill_type != 'DLFK')
							{
								// 									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消出纳审核"><img src="/images/qxsh.png" /></span>';
								if (checkOperation("付款登记:取消审核"))
								{
									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
									if (++ $operate_count < 4) {
										$operate .= $sub_operate; $sub_operate = '';
									}
								}
							}
							break;
					}
				}
			}
			//入账
			if ($type == "FKDJ" && checkOperation("付款登记:入账"))
			{
				switch ($baseform->form_status)
				{
					case 'approve':
						$sub_operate .= '<span class="accounted_form colorbox" url="'.$checkA_url.'" href="'.$checkA_url.'" title="入账"><span><img src="/images/ruzhang.png"/></span></span>';
						if (++ $operate_count < 4) {
							$operate .= $sub_operate; $sub_operate = '';
						}
						break;
					case 'accounted':
						$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消入账"><img src="/images/qxrz.png"/></span>';
						if (++ $operate_count < 4) {
							$operate .= $sub_operate; $sub_operate = '';
						}
						break;
					default: break;
				}
			}
			elseif ($type == "SKDJ" && checkOperation("收款登记:入账"))
			{
				switch ($baseform->form_status)
				{
					case 'submited':
						$sub_operate .= '<a class="accounted_form" href="'.$checkA_url.'" title="入账"><span><img src="/images/ruzhang.png"/></span></a>';
						if (++ $operate_count < 4) {
							$operate .= $sub_operate; $sub_operate = '';
						}
						break;
					case 'accounted':
						$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消入账"><img src="/images/qxrz.png"/></span>';
						if (++ $operate_count < 4) {
							$operate .= $sub_operate; $sub_operate = '';
						}
						break;
					default: break;
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
				
			$mark = $i;
			$i++;
		}
		
		return $operate;
	}
	
	
	
}
