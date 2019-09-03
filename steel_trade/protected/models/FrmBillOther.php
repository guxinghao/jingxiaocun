<?php

/** 
 * This is the biz model class for table "frm_bill_other". 
 * 
 */ 
class FrmBillOther extends FrmBillOtherData
{ 
	public $total_price;
	public $total_num;

    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array(
        		'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'), //公司
        		'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'), //结算单位
        		'team' => array(self::BELONGS_TO, 'Team', 'team_id'), //业务组
        		'baseformFYBZ' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformFYBZ.form_type = 'FYBZ'"), 
        		'baseformQTSR' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformQTSR.form_type = 'QTSR'"), 
        		'details' => array(self::HAS_MANY, 'BillOtherDetail', 'bill_other_id'), //明细
        		'account' => array(self::BELONGS_TO, 'User', 'account_by'), 
        		'dictBank' => array(self::BELONGS_TO, 'DictBankInfo', 'dict_bank_id'), 
        		'bank' => array(self::BELONGS_TO, 'BankInfo', 'bank_id'),
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'title_id' => 'Title',
            'company_id' => 'Company',
            'team_id' => 'Team',
            'bill_type' => 'Bill Type',
            'type' => 'Type',
            'type_detail' => 'Type Detail',
            'amount' => 'Amount',
            'status' => 'Status',
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
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('team_id',$this->team_id);
        $criteria->compare('bill_type',$this->bill_type,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('type_detail',$this->type_detail);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('comment',$this->comment,true);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
        /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return FrmBillOther the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
    
    public static function getInputData($post) 
    {
    	$data = array();
    	$post['FrmBillOther']['amount'] = floatval(numChange($post['FrmBillOther']['amount']));
    	$post['FrmBillOther']['bill_type'] = $post['CommonForms']['form_type'];
    	//$post['FrmBillOther']['account_at'] = strtotime($post['FrmBillOther']['account_at']);
        if ($post['FrmBillOther']['reach_at']) 
            $post['FrmBillOther']['reach_at'] = strtotime($post['FrmBillOther']['reach_at'].' 23:59:59');
    	
    	$detail_array = array();
    	for ($i = 0; $i < count($post['td_type1']); $i++) 
    	{
    		$detail = array();
    		if ($post['td_id'][$i])  $detail['id'] = $post['td_id'][$i];
    		$detail['type_1'] = $post['td_type1'][$i];
    		$detail['type_2'] = $post['td_type2'][$i];
    		$detail['fee'] = floatval(numChange($post['td_fee'][$i]));
    		$detail_array[] = (Object)$detail;
    	}
    	
    	$data['main'] = (Object)$post['FrmBillOther'];
    	$data['common'] = (Object)$post['CommonForms'];
    	$data['detail'] = $detail_array;
    	return $data;
    }
    
    public static function getFormList($search, $type) 
    {
    	$tableData = array();
    	$model = new BillOtherDetail();
    	$criteria = new CDbCriteria();
    	switch ($type) 
    	{
    		case 'FYBZ': 
    			$_baseform = 'baseformFYBZ';
    			break;
    		case 'QTSR': 
    			$_baseform = 'baseformQTSR';
    			break;
    	}
    	$criteria->with = array('frmBill','frmBill.'.$_baseform);
    	
    	if (!empty($search)) 
    	{
    		if ($search['form_type']) 
    		{
    			$criteria->addCondition("$_baseform.form_type = :form_type");
    			$criteria->params['form_type'] = $search['form_type'];
    		}
    		if ($search['keywords']) 
    		{
    			$criteria->addCondition("$_baseform.form_sn like :keywords");
    			$criteria->params[':keywords'] = '%'.$search['keywords'].'%';
    		}
    		if ($search['title_id']) //公司抬头
    		{
    			$criteria->addCondition("frmBill.title_id = :title_id");
    			$criteria->params[':title_id'] = $search['title_id'];
    		}
    		if ($search['company_id'])
    		{
    			$criteria->addCondition("frmBill.company_id = :company_id");
    			$criteria->params[':company_id'] = $search['company_id'];
    		}
    		if ($search['form_status']) //状态
    		{
    			if($search['form_status']=='approving'){
    					$criteria->addCondition("$_baseform.form_status in ('submited','approved_1','approved_2','approved_3')");
    			}elseif($search['form_status']=='accounting'){
    				$criteria->addCondition("$_baseform.form_status not in ('accounted','delete')");
    			}else{
    				$criteria->addCondition("$_baseform.form_status = :form_status");
    				$criteria->params[':form_status'] = $search['form_status'];
    			}
    		}
    		if ($search['form_type1']) //状态
    		{
    			$criteria->addCondition("t.type_1 = :form_type1");
    			$criteria->params[':form_type1'] = $search['form_type1'];
    		}
    		if ($search['owned']) 
    		{
    			$criteria->addCondition("$_baseform.owned_by = :owned");
    			$criteria->params[':owned'] = $search['owned'];
    		}
    		if ($search['account_by'])
    		{
    			$criteria->addCondition("frmBill.account_by = :account_by");
    			$criteria->params[':account_by'] = $search['account_by'];
    		}
    	}
    	if ($search['time_L']) //开始时间
    	{
    		$criteria->addCondition("$_baseform.form_time >= :time_L");
    		$criteria->params[':time_L'] = $search['time_L'];
    	}else{
    		$criteria->addCondition("$_baseform.form_time >= :time_L");
    		$criteria->params[':time_L'] = date("Y-m-d");
    	}
    	if ($search['time_H']) //结束时间
    	{
    		$criteria->addCondition("$_baseform.form_time <= :time_H");
    		$criteria->params[':time_H'] = $search['time_H'];
    	}else{
    		$criteria->addCondition("$_baseform.form_time <= :time_H");
    		$criteria->params[':time_H'] = date("Y-m-d");
    	}
    	if (!$search['form_status'] || $search['form_status'] != 'delete') 
    		$criteria->compare("$_baseform.is_deleted", '0');    	
    	if($type=='FYBZ'&&$_COOKIE['bz_view']=='belong')
    	{
    		$user=Yii::app()->user->userid;
    		$ruzhang=checkOperation('费用报支:入账');
    		$chuna=checkOperation('费用报支:出纳审核');
    		$zongjingli=checkOperation('费用报支:总经理审核');
    		$caiwuzhuguan=checkOperation('费用报支:财务主管审核');
    		$yewu=checkOperation('费用报支:业务经理审核');
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
    	}   	
    	
    	//总计
    	$c = clone $criteria;
    	$c->select = "sum(t.fee) as total_price";
    	$total1 = BillOtherDetail::model()->find($c);
    	$c->addCondition("$_baseform.form_status = 'accounted'");
    	$total2 = BillOtherDetail::model()->find($c);
    	$totaldata = array();
    	$totaldata['has'] = $total1->total_price;
    	$totaldata['is'] = $total2->total_price;
    	
    	//分页
    	$pages = new CPagination();
    	$pages->itemCount = $model->count($criteria);
    	$pages->pageSize =intval($_COOKIE['bill_other_list']) ? intval($_COOKIE['bill_other_list']) : Yii::app()->params['pageCount'];
    	$pages->applyLimit($criteria);
    	$criteria->order = "$_baseform.created_at DESC";
    	
    	$items = $model->findAll($criteria);
    	if (!$items) return array($tableData, $pages, $totaldata);
    	
    	$i = 1;
    	$sn = "";
    	foreach ($items as $item) 
    	{
    		$mark = '';
    		$operate = '';
    		$sub_operate = '';
    		$operate_count = 0;
    		$da = array();
    		$frmBill = $item->frmBill;
    		$baseform = $frmBill->$_baseform;
    		if ($baseform->form_sn != $sn) 
    		{
    			$sn = $baseform->form_sn;
    			$view_url = Yii::app()->createUrl('billOther/view', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
    			$edit_url = Yii::app()->createUrl('billOther/update', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
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
    			$sub_url = Yii::app()->createUrl('billOther/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
    			$del_url = Yii::app()->createUrl('billOther/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
    			$checkP_url = Yii::app()->createUrl('billOther/check', array('id' => $baseform->id, 'type' => 'pass', 'last_update' => $baseform->last_update));
    			$checkD_url = Yii::app()->createUrl('billOther/check', array('id' => $baseform->id, 'type' => 'deny', 'last_update' => $baseform->last_update));
    			$checkC_url = Yii::app()->createUrl('billOther/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
    			$checkA_url = Yii::app()->createUrl('billOther/accounted', array('id' => $baseform->id, 'type' => 'accounted', 'fpage' => $_REQUEST['page']));
    			$checkCA_url = Yii::app()->createUrl('billOther/accounted', array('id' => $baseform->id, 'type' => 'cancel_accounted', 'last_update' => $baseform->last_update));
    			$print_url = Yii::app()->createUrl('print/print', array('id' => $baseform->id));
    			$operate .= '<div class="cz_list_btn"><input type="hidden" value="'.$baseform->form_sn.'" class="form_sn">';
    			if (checkOperation("打印") && $type == 'FYBZ') {
    				$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
    				$operate_count++;
    			}
    			//新增
    			if (in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && ($type == 'FYBZ' && checkOperation("费用报支:新增") || $type == 'QTSR' && checkOperation("其他收入:新增")))
    			{
    				switch ($baseform->form_status) 
    				{
    					case 'unsubmit': 
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
    						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
    						$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
    						break;
    					case 'submited': 
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png" /></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
    						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
    						break;
    					default: break;
    				}
    			}
    			
    			if ($type == 'FYBZ') 
    			{
    				if (checkOperation("费用报支:业务经理审核")) 
    				{
    					switch ($baseform->form_status) 
    					{
    						case 'submited': 
    							$sub_operate .= '<span title="业务经理审核" id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'" class="check_form" frm="fbz" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							break;
    						case 'approved_1': 
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消业务经理审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							break;
    						default: break;
    					}
    				}
    				if (checkOperation("费用报支:财务主管审核")) 
    				{
    					switch ($baseform->form_status) 
    					{
    						case 'approved_1': 
    							$sub_operate .= '<span title="财务主管审核" id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'" class="check_form" frm="fbz" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							break;
    						case 'approved_2': 
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							break;
    						default: break;
    					}
    				}
    				if (checkOperation("费用报支:总经理审核")) 
    				{
    					switch ($baseform->form_status) 
    					{
    						case 'approved_2': 
    							$sub_operate .= '<span title="总经理审核" id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'" class="check_form"  frm="fbz" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							break;
    						case 'approved_3': 
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消总经理审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							break;
    						default: break;
    					}
    				}
    				if (checkOperation("费用报支:出纳审核")) 
    				{
    					switch ($baseform->form_status) 
    					{
    						case 'approved_3': 
    							$sub_operate .= '<span title="出纳审核"  position="chuna" id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'"  frm="fbz" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							break;
    						case 'approve': 
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消出纳审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							break;
    						default: break;
    					}
    				}
    			} 
    			elseif ($type == 'QTSR' && checkOperation("其他收入:审核")) 
    			{
    				switch ($baseform->form_status) 
    				{
    					case 'submited':
    						$sub_operate .= '<span id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setCheck(this);"><img src="/images/shenhe.png" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    					case 'approve':
    						$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    				}
    			}
    			
    			if ($type == 'FYBZ' && checkOperation("费用报支:入账") || $type == 'QTSR' && checkOperation("其他收入:入账")) 
    			{
    				switch ($baseform->form_status) 
    				{
    					case 'approve': 
    						$sub_operate .= '<a class="accounted_form" href="'.$checkA_url.'" title="入账"><span><img src="/images/ruzhang.png"/></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
    						break;
    					case 'accounted': 
    						$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消入账"><img src="/images/qxrz.png"/></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
    						break;
    					default: break;
    				}
    			}
    			if ($operate_count > 4)
    			{
    				$operate .= '<span title="更多" class="more_but"><img src="/images/gengduo.png"></span>'.
    						'<div class="cz_list_btn_more" num="0" style="width:120px">'.
    						$sub_operate.
    						'</div>';
    			}else{
    				$operate .= $sub_operate;
    			}
    			$operate .= '</div>';    			
    			$mark = $i;
    			$i++;
    		}else{
    			$operate="";
    		}    		
    		if ($baseform->form_type == 'FYBZ' && in_array($baseform->form_status, array('submited', 'approved_1', 'approved_2', 'approved_3', 'approve'))) 
    		{
    			$status = '<span id="'.$baseform->id.'" form_sn="'.$baseform->form_sn.'" class="status_btn">'.($baseform->form_status == 'submited' ? "审核中" : CommonForms::$formStatus[$baseform->form_status]).'</span>';
    		}else{
    			$status = CommonForms::$formStatus[$baseform->form_status];
    		}    		
    		$da['data'] = array($mark, 
    				$operate, 
    				'<a title="查看详情" class="a_view" href="'.$view_url.'">'.$baseform->form_sn.'</a>', 
    				$status, 
    				$baseform->form_time,
    				$item->recordType1->name,
    				$item->recordType2->name,
    				'<span title="'.$frmBill->title->name.'">'.$frmBill->title->short_name.'</span>',
    				$frmBill->dictBank->dict_name,
    				'<span title="'.$frmBill->company->name.'">'.$frmBill->company->short_name.'</span>', 
    				number_format($item->fee, 2), 
    				$baseform->form_status == 'accounted'?$item->fee:"",
    				'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,"utf-8").'</span>',
    				$baseform->belong->nickname,
    				$baseform->operator->nickname,
    				//$baseform->approved_by > 0 ? $baseform->approver->nickname : '',
    				//$baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '',
    				$baseform->form_status == 'accounted' ? $frmBill->account->nickname : '',
    				//$baseform->form_status == 'accounted' && $item->account_at > 0 ? date('Y-m-d', $item->account_at) : '',
                    $baseform->form_status == 'accounted' && $frmBill->reach_at > 0 ? date('Y-m-d', $frmBill->reach_at) : '',
    				
    		);
    		if ($baseform->form_status == 'delete') $da['data'][] = $baseform->delete_reason;
    		
    		$da['group'] = $baseform->form_sn;
    		array_push($tableData, $da);
    	}
    	
    	return array($tableData, $pages, $totaldata);
    }

    public static function getAllList($search, $type)
    {
    	$tableData = array();
    	$model = new BillOtherDetail();
    	$criteria = new CDbCriteria();
    	switch ($type)
    	{
    		case 'FYBZ':
    			$_baseform = 'baseformFYBZ';
    			break;
    		case 'QTSR':
    			$_baseform = 'baseformQTSR';
    			break;
    	}
    	$criteria->with = array('frmBill','frmBill.'.$_baseform);
    	 
    	if (!empty($search))
    	{
    		if ($search['form_type'])
    		{
    			$criteria->addCondition("$_baseform.form_type = :form_type");
    			$criteria->params['form_type'] = $search['form_type'];
    		}
    		if ($search['keywords'])
    		{
    			$criteria->addCondition("$_baseform.form_sn like :keywords");
    			$criteria->params[':keywords'] = '%'.$search['keywords'].'%';
    		}
    		if ($search['title_id']) //公司抬头
    		{
    			$criteria->addCondition("frmBill.title_id = :title_id");
    			$criteria->params[':title_id'] = $search['title_id'];
    		}
    		if ($search['company_id'])
    		{
    			$criteria->addCondition("frmBill.company_id = :company_id");
    			$criteria->params[':company_id'] = $search['company_id'];
    		}
    		if ($search['form_status']) //状态
    		{
    			if($search['form_status']=='approving'){
    				$criteria->addCondition("$_baseform.form_status in ('submited','approved_1','approved_2','approved_3')");
    			}else{
    				$criteria->addCondition("$_baseform.form_status = :form_status");
    				$criteria->params[':form_status'] = $search['form_status'];
    			}
    		}
    		if ($search['form_type1']) //状态
    		{
    			$criteria->addCondition("t.type_1 = :form_type1");
    			$criteria->params[':form_type1'] = $search['form_type1'];
    		}
    		if ($search['owned'])
    		{
    			$criteria->addCondition("$_baseform.owned_by = :owned");
    			$criteria->params[':owned'] = $search['owned'];
    		}
    		if ($search['account_by'])
    		{
    			$criteria->addCondition("frmBill.account_by = :account_by");
    			$criteria->params[':account_by'] = $search['account_by'];
    		}
    	}
    	if ($search['time_L']) //开始时间
    	{
    		$criteria->addCondition("$_baseform.form_time >= :time_L");
    		$criteria->params[':time_L'] = $search['time_L'];
    	}else{
    		$criteria->addCondition("$_baseform.form_time >= :time_L");
    		$criteria->params[':time_L'] = date("Y-m-d");
    	}
    	if ($search['time_H']) //结束时间
    	{
    		$criteria->addCondition("$_baseform.form_time <= :time_H");
    		$criteria->params[':time_H'] = $search['time_H'];
    	}else{
    		$criteria->addCondition("$_baseform.form_time <= :time_H");
    		$criteria->params[':time_H'] = date("Y-m-d");
    	}
    	if (!$search['form_status'] || $search['form_status'] != 'delete')
    		$criteria->compare("$_baseform.is_deleted", '0');
    		if($type=='FYBZ'&&$_COOKIE['bz_view']=='belong')
    		{
    			$user=Yii::app()->user->userid;
    			$ruzhang=checkOperation('费用报支:入账');
    			$chuna=checkOperation('费用报支:出纳审核');
    			$zongjingli=checkOperation('费用报支:总经理审核');
    			$caiwuzhuguan=checkOperation('费用报支:财务主管审核');
    			$yewu=checkOperation('费用报支:业务经理审核');
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
    		}
    		 
    		//总计
    		$c = clone $criteria;
    		$c->select = "sum(t.fee) as total_price";
    		$total1 = BillOtherDetail::model()->find($c);
    		$c->addCondition("$_baseform.form_status = 'accounted'");
    		$total2 = BillOtherDetail::model()->find($c);
    		$totaldata = array();
    		$totaldata[8] = $total1->total_price;
    		$totaldata[9] = $total2->total_price;
    		 
    		$criteria->order = "$_baseform.created_at DESC";
    		$items = $model->findAll($criteria);
    		if (!$items) return array($tableData, $pages, $totaldata);
    		$content = array();
    		$i = 1;
    		$sn = "";
    		foreach ($items as $item)
    		{
    			$da = array();
    			$frmBill = $item->frmBill;
    			$baseform = $frmBill->$_baseform;

    			if ($baseform->form_type == 'FYBZ' && in_array($baseform->form_status, array('submited', 'approved_1', 'approved_2', 'approved_3', 'approve')))
    			{
    				$status = $baseform->form_status == 'submited' ? "审核中" : CommonForms::$formStatus[$baseform->form_status];
    			}
    			else
    			{
    				$status = CommonForms::$formStatus[$baseform->form_status];
    			}
    			$temp = array();
    			$temp[0] = $baseform->form_sn;
    			$temp[1] = $status;
    			$temp[2] = $baseform->form_time;
    			$temp[3] = $item->recordType1->name;
    			$temp[4] = $item->recordType2->name;
    			$temp[5] = $frmBill->title->name;
    			$temp[6] = $frmBill->dictBank->dict_name;
    			$temp[7] = $frmBill->company->name;
    			$temp[8] = numChange(number_format($item->fee, 2));
    			$temp[9] = $baseform->form_status == 'accounted'?$item->fee:"";
    			$temp[10] = htmlspecialchars($baseform->comment);
    			$temp[11] = $baseform->belong->nickname;
    			$temp[12] = $baseform->operator->nickname;
    			$temp[13] = $baseform->approved_by > 0 ? $baseform->approver->nickname : '';
    			$temp[14] = $baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '';
    			$temp[15] = $baseform->form_status == 'accounted' ? $frmBill->account->nickname : '';
    			$temp[16] = $baseform->form_status == 'accounted' && $frmBill->reach_at > 0 ? date('Y-m-d', $frmBill->reach_at) : '';
    			if ($baseform->form_status == 'delete') $temp[17] = $baseform->delete_reason;
    			array_push($content,$temp);
    		}
    		array_push($content,$totaldata);
    		return $content;
    }
    
    
   	public static function getButtons($form_sn)
   	{
   		$tableData = array();
   		$model = new BillOtherDetail();
   		$criteria = new CDbCriteria();   		
   		$type= 'FYBZ';
   		$_baseform = 'baseformFYBZ';   		
   		$criteria->with = array('frmBill','frmBill.'.$_baseform);	
		$criteria->addCondition("$_baseform.form_sn like :keywords");
		$criteria->params[':keywords'] = '%'.$form_sn.'%';
   		$item = $model->find($criteria);
   			$sn = "";
   			$operate = '';
   			$sub_operate = '';
   			$operate_count = 0;
   			$frmBill = $item->frmBill;
   			$baseform = $frmBill->$_baseform;
   				$sn = $baseform->form_sn;
   				$view_url = Yii::app()->createUrl('billOther/view', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
   				$edit_url = Yii::app()->createUrl('billOther/update', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
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
   				$sub_url = Yii::app()->createUrl('billOther/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
   				$del_url = Yii::app()->createUrl('billOther/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
   				$checkP_url = Yii::app()->createUrl('billOther/check', array('id' => $baseform->id, 'type' => 'pass', 'last_update' => $baseform->last_update));
   				$checkD_url = Yii::app()->createUrl('billOther/check', array('id' => $baseform->id, 'type' => 'deny', 'last_update' => $baseform->last_update));
   				$checkC_url = Yii::app()->createUrl('billOther/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
   				$checkA_url = Yii::app()->createUrl('billOther/accounted', array('id' => $baseform->id, 'type' => 'accounted', 'fpage' => $_REQUEST['page']));
   				$checkCA_url = Yii::app()->createUrl('billOther/accounted', array('id' => $baseform->id, 'type' => 'cancel_accounted', 'last_update' => $baseform->last_update));
   				$print_url = Yii::app()->createUrl('print/print', array('id' => $baseform->id));
   				$operate .= '<div class="cz_list_btn"><input type="hidden" value="'.$baseform->form_sn.'" class="form_sn">';
   				if (checkOperation("打印") && $type == 'FYBZ') {
   					$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
   					$operate_count++;
   				}
   				//新增
   				if (in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && ($type == 'FYBZ' && checkOperation("费用报支:新增") || $type == 'QTSR' && checkOperation("其他收入:新增")))
   				{
   					switch ($baseform->form_status)
   					{
   						case 'unsubmit':
   							$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
   							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
   							$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
   							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
   							$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png" /></span>';
   							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
   							break;
   						case 'submited':
   							$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png" /></span></a>';
   							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
   							$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
   							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
   							break;
   						default: break;
   					}
   				}
   				 
   				if ($type == 'FYBZ')
   				{
   					if (checkOperation("费用报支:业务经理审核"))
   					{
   						switch ($baseform->form_status)
   						{
   							case 'submited':
   								$sub_operate .= '<span title="业务经理审核" id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'" class="check_form" frm="fbz" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
   								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
   								break;
   							case 'approved_1':
   								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消业务经理审核"><img src="/images/qxsh.png" /></span>';
   								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
   								break;
   							default: break;
   						}
   					}
   					if (checkOperation("费用报支:财务主管审核"))
   					{
   						switch ($baseform->form_status)
   						{
   							case 'approved_1':
   								$sub_operate .= '<span title="财务主管审核" id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'" class="check_form" frm="fbz" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
   								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
   								break;
   							case 'approved_2':
   								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消财务主管审核"><img src="/images/qxsh.png" /></span>';
   								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
   								break;
   							default: break;
   						}
   					}
   					if (checkOperation("费用报支:总经理审核"))
   					{
   						switch ($baseform->form_status)
   						{
   							case 'approved_2':
   								$sub_operate .= '<span title="总经理审核" id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'" class="check_form"  frm="fbz" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
   								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
   								break;
   							case 'approved_3':
   								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消总经理审核"><img src="/images/qxsh.png" /></span>';
   								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
   								break;
   							default: break;
   						}
   					}
   					if (checkOperation("费用报支:出纳审核"))
   					{
   						switch ($baseform->form_status)
   						{
   							case 'approved_3':
   								$sub_operate .= '<span title="出纳审核"  position="chuna" id="'.Yii::app()->createUrl('billOther/check', array('id' => $baseform->id)).'"  frm="fbz" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
   								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
   								break;
   							case 'approve':
   								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消出纳审核"><img src="/images/qxsh.png" /></span>';
   								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
   								break;
   							default: break;
   						}
   					}
   				}   					 
   				if ($type == 'FYBZ' && checkOperation("费用报支:入账") || $type == 'QTSR' && checkOperation("其他收入:入账"))
   				{
   					switch ($baseform->form_status)
   					{
   						case 'approve':
   							$sub_operate .= '<a class="accounted_form" href="'.$checkA_url.'" title="入账"><span><img src="/images/ruzhang.png"/></span></a>';
   							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
   							break;
   						case 'accounted':
   							$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消入账"><img src="/images/qxrz.png"/></span>';
   							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = '';}
   							break;
   						default: break;
   					}
   				}
   				if ($operate_count > 4)
   				{
   					$operate .= '<span title="更多" class="more_but"><img src="/images/gengduo.png"></span>'.
   							'<div class="cz_list_btn_more" num="0" style="width:120px">'.
   							$sub_operate.
   							'</div>';
   				}else{
   					$operate .= $sub_operate;
   				}
   				$operate .= '</div>';   		
   			return $operate;
   	}
    
    
    
} 