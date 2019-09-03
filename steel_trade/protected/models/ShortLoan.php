<?php

/** 
 * This is the biz model class for table "short_loan". 
 * 短期借贷
 */ 
class ShortLoan extends ShortLoanData
{ 
	public $total_price;
	public $total_num;
	
     public static $lendingDirection = array('borrow' => "借入", 'lend' => "借出"); //借贷方向
     public static $hasIous = array('0' => "无借据", '1' => "有借据"); //是否有借据
     public static $performanceStatus = array('0' => "未履约", '1' => "已履约"); //履约状态

    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array(
//         		'baseformDQJK' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformDQJK.form_type = 'DQJK'"), //短期借款
//         		'baseformDQDK' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformDQDK.form_type = 'DQDK'"), //短期贷款
        		'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type IN ('DQJK', 'DQDK')"),
        		'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'), 
        		'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
        		'account' => array(self::BELONGS_TO, 'User', 'account_by'),
        		'loanRecord' => array(self::HAS_MANY, 'LoanRecord', 'short_loan_id'),
        		'oneloanRecord' => array(self::HAS_ONE, 'LoanRecord', 'short_loan_id'),
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
            'lending_direction' => 'Lending Direction',
            'amount' => 'Amount',
            'interest_rate' => 'Interest Rate',
            'accounted_principal' => 'Accounted Principal',
            'accounted_interest' => 'Accounted Interest',
            'out_account_principal' => 'Out Account Principal',
            'out_account_interest' => 'Out Account Interest',
            'balance' => 'Balance',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'has_Ious' => 'Has Ious',
            'performance_status' => 'Performance Status',
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
        $criteria->compare('lending_direction',$this->lending_direction,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('interest_rate',$this->interest_rate,true);
        $criteria->compare('accounted_principal',$this->accounted_principal,true);
        $criteria->compare('accounted_interest',$this->accounted_interest,true);
        $criteria->compare('out_account_principal',$this->out_account_principal,true);
        $criteria->compare('out_account_interest',$this->out_account_interest,true);
        $criteria->compare('balance',$this->balance,true);
        $criteria->compare('start_time',$this->start_time);
        $criteria->compare('end_time',$this->end_time);
        $criteria->compare('has_Ious',$this->has_Ious);
        $criteria->compare('performance_status',$this->performance_status);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
        /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return ShortLoan the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
    
    public static function getInputData($post) 
    {
    	$post['ShortLoan']['amount'] = floatval(numChange($post['ShortLoan']['amount']));
    	$post['ShortLoan']['interest_rate'] = floatval(numChange($post['ShortLoan']['interest_rate']));
    	$post['ShortLoan']['start_time'] = strtotime($post['ShortLoan']['start_time']);
    	$post['ShortLoan']['end_time'] = strtotime($post['ShortLoan']['end_time']);
    	
    	$post['ShortLoan']['accounted_principal'] = floatval(numChange($post['ShortLoan']['accounted_principal']));
    	$post['ShortLoan']['accounted_interest'] = floatval(numChange($post['ShortLoan']['accounted_interest']));
    	$post['ShortLoan']['out_account_principal'] = floatval(numChange($post['ShortLoan']['out_account_principal']));
    	$post['ShortLoan']['out_account_interest'] = floatval(numChange($post['ShortLoan']['out_account_interest']));
    	$post['ShortLoan']['balance'] = floatval(numChange($post['ShortLoan']['balance']));
    	
    	$data['main'] = (Object)$post['ShortLoan'];
    	$data['common'] = (Object)$post['CommonForms'];
    	return $data;
    }
    
    public static function getRecordData($post) 
    {
    	$data = array();
    	for ($i = 0; $i < count($post['LoanRecord']['id']); $i++) 
    	{
    		$loan_record = array(
    				'id' => $post['LoanRecord']['id'][$i], 
    				'account_direction' => $post['LoanRecord']['account_direction'][$i], 
    				'dict_bank_id' => $post['LoanRecord']['dict_bank_id'][$i], 
    				'bank_id' => $post['LoanRecord']['bank_id'][$i],
    				'amount_type' => $post['LoanRecord']['amount_type'][$i],
    				'amount' => floatval(numChange($post['LoanRecord']['amount'][$i])),
    				'has_Ious' => $post['LoanRecord']['has_Ious'][$i],
    				'created_at' => strtotime($post['LoanRecord']['created_at'][$i]),
    				'created_by' => $post['LoanRecord']['created_by'][$i],
                    'reach_at' => strtotime($post['LoanRecord']['reach_at'][$i].' 23:59:59'),
    				'comment' => $post['LoanRecord']['comment'][$i]
    		);
    		$data["detail"][] = (Object)$loan_record;
    		$data['common'] = (Object)$post['CommonForms'];
    	}
    	return $data;
    }
    
    public static function getFormList($search) 
    {
    	$tableData = array();
    	$model = new ShortLoan();
    	$criteria = new CDbCriteria();
    	$criteria->with = array('baseform',"oneloanRecord");
    	if (!empty($search)) 
    	{
    		if ($search['keywords']) 
        	{
        		$criteria->addCondition("baseform.form_sn like :keywords");
        		$criteria->params[':keywords'] = "%".strtoupper($search['keywords'])."%";
        	}
        	if ($search['time_L']) //开始时间
        	{
        		$criteria->addCondition("baseform.form_time >= :time_L");
        		$criteria->params[':time_L'] = $search['time_L'];
        	}
        	if ($search['time_H']) //结束时间
        	{
        		$criteria->addCondition("baseform.form_time <= :time_H");
        		$criteria->params[':time_H'] = $search['time_H'];
        	}
        	if ($search['title_id']) //公司抬头
        	{
        		$criteria->addCondition("t.title_id = :title_id");
        		$criteria->params[':title_id'] = $search['title_id'];
        	}
        	if ($search['company_id']) 
        	{
        		$criteria->addCondition("t.company_id = :company_id");
        		$criteria->params[':company_id'] = $search['company_id'];
        	}
        	if ($search['lending_direction']) //借贷方向
        	{
        		$criteria->addCondition("t.lending_direction = :lending_direction");
        		$criteria->params[':lending_direction'] = $search['lending_direction'];
        	}
        	if ($search['has_Ious'] != -1) //有无借据
        	{
        		$criteria->addCondition("t.has_Ious = :has_Ious");
        		$criteria->params[':has_Ious'] = $search['has_Ious'];
        	}
        	if ($search['owned'])
        	{
        		$criteria->addCondition("baseform.owned_by = :owned");
        		$criteria->params[':owned'] = $search['owned'];
        	}
    	}
    	if ($search['form_status']) //状态
    	{
    		if($search['form_status']=='approving'){
    			$criteria->addCondition("baseform.form_status in ('approved_1','approved_2','approved_3')");
    		}else{
    			$criteria->addCondition("baseform.form_status = :form_status");
    			$criteria->params[':form_status'] = $search['form_status'];
    		}
    	}else{
    		$criteria->addCondition("baseform.form_status <>'delete'");
    	}
    	
    	$criteria->addInCondition("baseform.form_type", array('DQJK', 'DQDK'));
    	//总计
    	$c = clone $criteria;
    	$total = ShortLoan::model()->findAll($c);
    	$totaldata = array();
    	foreach($total as $li){
    		if($li->lending_direction == "borrow"){
    			$totaldata["has"] += $li->amount;
    			$totaldata["is"] += $li->oneloanRecord->amount;
    		}else{
    			$totaldata["has"] -= $li->amount;
    			$totaldata["is"] -= $li->oneloanRecord->amount;
    		}
    	}
    	
    	//分页
    	$pages = new CPagination();
    	$pages->itemCount = $model->count($criteria);
    	$pages->pageSize =intval($_COOKIE['short_loan_list']) ? intval($_COOKIE['short_loan_list']) : Yii::app()->params['pageCount'];
    	$pages->applyLimit($criteria);
    	$criteria->order = "baseform.created_at DESC";
    	
    	$items = $model->findAll($criteria);
    	if (!$items) return array($tableData, $pages, $totaldata);
    	
    	$i = 1;
    	foreach ($items as $item) {
    		$mark = '';
    		$operate = '';
    		$sub_operate = '';
    		$operate_count = 0;
    		$da = array();
    		$baseform = $item->baseform;
    		if ($baseform) 
    		{
    			$view_url = Yii::app()->createUrl('shortLoan/view', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
    			$edit_url = Yii::app()->createUrl('shortLoan/update', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
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
    			$sub_url = Yii::app()->createUrl('shortLoan/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
    			$del_url = Yii::app()->createUrl('shortLoan/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
    			$checkP_url = Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id, 'type' => 'pass', 'last_update' => $baseform->last_update));
    			$checkD_url = Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id, 'type' => 'deny', 'last_update' => $baseform->last_update));
    			$checkC_url = Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
    			$checkA_url = Yii::app()->createUrl('shortLoan/accounted', array('id' => $baseform->id, 'fpage' => $_REQUEST['page'])); //出入账
     			$checkCA_url = Yii::app()->createUrl('shortLoan/cancelAccounted', array('id' => $baseform->id,'last_update' => $baseform->last_update));
     			$print_url = Yii::app()->createUrl('print/print', array('id' => $baseform->id));
    			$operate .= '<div class="cz_list_btn"><input type="hidden" value="'.$baseform->form_sn.'" class="form_sn">';
    			if (checkOperation("打印")) {
    				$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
    				$operate_count++;
    			}
    			//编辑|提交
    			if ($item->performance_status == 0 && in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && checkOperation("短期借贷:新增")) 
    			{
    				switch ($baseform->form_status) 
    				{
    					case 'unsubmit':
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    					case 'submited':
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png" /></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    					default: break;
    				}
    			}
    			$quxiao = checkOperation('短期借贷:取消审核');
    			if ($item->lending_direction == 'lend') 
    			{
    				$chuna=checkOperation('短期借贷:出纳审核');
    				$zongjingli=checkOperation('短期借贷:总经理审核');
    				$caiwuzhuguan=checkOperation('短期借贷:财务主管审核');
    				$yewu=checkOperation('短期借贷:业务经理审核');
    				
    				switch ($baseform->form_status) 
    				{
    					case 'submited':
    						if($yewu){
    							$sub_operate .= '<span title="业务经理审核" id="'.Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id)).'" class="check_form" frm="fdj" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？"  lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						break;
    					case 'approved_1': 
    						if($quxiao){
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						if($caiwuzhuguan){
    							$sub_operate .= '<span title="财务主管审核" id="'.Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id)).'"  frm="fdj" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？"  lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						break;
    					case 'approved_2':
    							if($quxiao){
    								$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
    								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							}
    							if($zongjingli){
    								$sub_operate .= '<span title="总经理审核" id="'.Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id)).'" frm="fdj" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？"  lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    								if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    							}
    							break;
    					case 'approved_3':
    								if($quxiao){
    									$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
    									if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    								}
    								if($chuna){
    									$sub_operate .= '<span title="出纳审核" position="chuna" id="'.Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id)).'"  frm="fdj" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？"  lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    									if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    								}
    								break;
    					case 'approve':
	    					if($quxiao){
	    						$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
	    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
	    					}
	    					break;
    					default: break;
    				}
    			}
    			
    			if ($item->performance_status == 0 && checkOperation("短期借贷:出入账")) 
    			{
    				switch ($baseform->form_status) 
    				{
    					case 'submited': 
    						if ($item->lending_direction != 'borrow') break;
    					case 'approve': 
    						$sub_operate .= '<a class="accounted_form" href="'.$checkA_url.'" title="出/入账"><span><img src="/images/ruzhang.png"/></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    					case 'accounted':
    						$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消出/入账"><img src="/images/qxrz.png"/></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
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
    			}
    			else
    			{
    				$operate .= $sub_operate;
    			}
    			$operate .= '</div>';
    			 
    			$mark = $i;
    			$i++;
    		}
    		if ($baseform->form_type == 'DQDK' && in_array($baseform->form_status, array('submited', 'approved_1', 'approved_2', 'approved_3', 'approve')))
    		{
    			$status = '<span id="'.$baseform->id.'"  form_sn="'.$baseform->form_sn.'" class="status_btn">'.($baseform->form_status == 'submited' ? "审核中" : CommonForms::$formStatus[$baseform->form_status]).'</span>';
    		}
    		else
    		{
    			$status = CommonForms::$formStatus[$baseform->form_status];
    		}
    		$reach_at =$item->oneloanRecord->reach_at;
    		$da['data'] = array($mark, 
    				$operate, 
    				'<a title="查看详情" class="a_view" href="'.$view_url.'">'.$baseform->form_sn.'</a>',
    				$status,
    				//ShortLoan::$performanceStatus[$item->performance_status],
    				$baseform->form_time,
    				'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
    				ShortLoan::$lendingDirection[$item->lending_direction], 
    				$item->lending_direction == "lend"?number_format(-$item->amount, 2):number_format($item->amount, 2),
    				($item->lending_direction == "lend" && $item->oneloanRecord->amount>0)?number_format(-$item->oneloanRecord->amount, 2):number_format($item->oneloanRecord->amount, 2),
//     				number_format($item->interest_rate, 4), 
//     				number_format($item->accounted_principal, 2), 
//     				number_format($item->accounted_interest, 2),
//     				number_format($item->out_account_principal, 2),
//     				number_format($item->out_account_interest, 2),
//     				number_format($item->balance, 2),
//     				date('Y-m-d', $item->start_time), 
    				//date('Y-m-d', $item->end_time), 
    				ShortLoan::$hasIous[$item->has_Ious], 
    				'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
    				$reach_at > 0 ? date('Y-m-d', $reach_at) : '',
    				$baseform->belong->nickname, 
    				$baseform->operator->nickname,
    				//$baseform->approver->nickname, 
    				//$baseform->approved_at>0?date('Y-m-d', $baseform->approved_at):"",
    				'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,"utf-8").'</span>',
    		);
    		if($search['form_status'] == "delete"){
    			array_push($da['data'],'<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason,0,15,"UTF-8").'</span>');
    		}
    		$da['group'] = $baseform->form_sn;
    		array_push($tableData, $da);
    	}
    	return array($tableData, $pages, $totaldata);
    }

    public static function getAllList($search)
    {
    	$tableData = array();
    	$model = new ShortLoan();
    	$criteria = new CDbCriteria();
    	$criteria->with = array('baseform',"oneloanRecord");
    	if (!empty($search))
    	{
    		if ($search['keywords'])
    		{
    			$criteria->addCondition("baseform.form_sn like :keywords");
    			$criteria->params[':keywords'] = "%".strtoupper($search['keywords'])."%";
    		}
    		if ($search['time_L']) //开始时间
    		{
    			$criteria->addCondition("baseform.form_time >= :time_L");
    			$criteria->params[':time_L'] = $search['time_L'];
    		}
    		if ($search['time_H']) //结束时间
    		{
    			$criteria->addCondition("baseform.form_time <= :time_H");
    			$criteria->params[':time_H'] = $search['time_H'];
    		}
    		if ($search['title_id']) //公司抬头
    		{
    			$criteria->addCondition("t.title_id = :title_id");
    			$criteria->params[':title_id'] = $search['title_id'];
    		}
    		if ($search['company_id'])
    		{
    			$criteria->addCondition("t.company_id = :company_id");
    			$criteria->params[':company_id'] = $search['company_id'];
    		}
    		if ($search['lending_direction']) //借贷方向
    		{
    			$criteria->addCondition("t.lending_direction = :lending_direction");
    			$criteria->params[':lending_direction'] = $search['lending_direction'];
    		}
    		if ($search['has_Ious'] != -1) //有无借据
    		{
    			$criteria->addCondition("t.has_Ious = :has_Ious");
    			$criteria->params[':has_Ious'] = $search['has_Ious'];
    		}
    		if ($search['owned'])
    		{
    			$criteria->addCondition("baseform.owned_by = :owned");
    			$criteria->params[':owned'] = $search['owned'];
    		}
    	}
    	if ($search['form_status']) //状态
    	{
    		if($search['form_status']=='approving'){
    			$criteria->addCondition("baseform.form_status in ('approved_1','approved_2','approved_3')");
    		}else{
    			$criteria->addCondition("baseform.form_status = :form_status");
    			$criteria->params[':form_status'] = $search['form_status'];
    		}
    	}else{
    		$criteria->addCondition("baseform.form_status <>'delete'");
    	}
    	 
    	$criteria->addInCondition("baseform.form_type", array('DQJK', 'DQDK'));
    	//总计
    	$c = clone $criteria;
    	$total = ShortLoan::model()->findAll($c);
    	$totaldata = array();
    	foreach($total as $li){
    		if($li->lending_direction == "borrow"){
    			$totaldata[5] += $li->amount;
    			$totaldata[6] += $li->oneloanRecord->amount;
    		}else{
    			$totaldata[5] -= $li->amount;
    			$totaldata[6] -= $li->oneloanRecord->amount;
    		}
    	}
    	$criteria->order = "baseform.created_at DESC";
    	$items = $model->findAll($criteria);
    	if (!$items) return array($tableData, $pages, $totaldata);
    	$content = array();
    	$i = 1;
    	foreach ($items as $item) {
    		$baseform = $item->baseform;
    		if ($baseform->form_type == 'DQDK' && in_array($baseform->form_status, array('submited', 'approved_1', 'approved_2', 'approved_3', 'approve')))
    		{
    			$status = $baseform->form_status == 'submited' ? "审核中" : CommonForms::$formStatus[$baseform->form_status];
    		}
    		else
    		{
    			$status = CommonForms::$formStatus[$baseform->form_status];
    		}
    		$reach_at =$item->oneloanRecord->reach_at;
    		$temp = array();
    		$temp[0] = $baseform->form_sn;
    		$temp[1] = $status;
    		$temp[2] = $baseform->form_time;
    		$temp[3] = $item->company->name;
    		$temp[4] = ShortLoan::$lendingDirection[$item->lending_direction];
    		$temp[5] = $item->lending_direction == "lend"?numChange(number_format(-$item->amount, 2)):numChange(number_format($item->amount, 2));
    		$temp[6] = ($item->lending_direction == "lend" && $item->oneloanRecord->amount>0)?numChange(number_format(-$item->oneloanRecord->amount, 2)):numChange(number_format($item->oneloanRecord->amount, 2));
    		$temp[7] = ShortLoan::$hasIous[$item->has_Ious];
    		$temp[8] = $item->title->short_name;
    		$temp[9] = $reach_at > 0 ? date('Y-m-d', $reach_at) : '';
    		$temp[10] = $baseform->belong->nickname;
    		$temp[11] = $baseform->operator->nickname;
    		$temp[12] = $baseform->approved_by > 0 ? $baseform->approver->nickname : '';
    		$temp[13] = $baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '';
    		$temp[14] = htmlspecialchars($baseform->comment);
    		if ($baseform->form_status == 'delete') $temp[15] = $baseform->delete_reason;
    		array_push($content,$temp);
    	}
    		array_push($content,$totaldata);
    		return $content;
    }
    
    
    public static function getButtons($form_sn)
    {
    	$model = new ShortLoan();
    	$criteria = new CDbCriteria();
    	$criteria->with = array('baseform',"oneloanRecord");    	
    	$criteria->addCondition("baseform.form_sn like :keywords");
    	$criteria->params[':keywords'] = "%".strtoupper($form_sn)."%";    	   	
    	$criteria->addInCondition("baseform.form_type", array('DQJK', 'DQDK'));    
    	$item = $model->find($criteria);    	 
    	if($item) {
    		$operate = '';
    		$sub_operate = '';
    		$operate_count = 0;
    		$baseform = $item->baseform;
    		if ($baseform)
    		{
    			$view_url = Yii::app()->createUrl('shortLoan/view', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
    			$edit_url = Yii::app()->createUrl('shortLoan/update', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
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
    			$sub_url = Yii::app()->createUrl('shortLoan/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
    			$del_url = Yii::app()->createUrl('shortLoan/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
    			$checkP_url = Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id, 'type' => 'pass', 'last_update' => $baseform->last_update));
    			$checkD_url = Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id, 'type' => 'deny', 'last_update' => $baseform->last_update));
    			$checkC_url = Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
    			$checkA_url = Yii::app()->createUrl('shortLoan/accounted', array('id' => $baseform->id, 'fpage' => $_REQUEST['page'])); //出入账
    			$checkCA_url = Yii::app()->createUrl('shortLoan/cancelAccounted', array('id' => $baseform->id,'last_update' => $baseform->last_update));
    			$print_url = Yii::app()->createUrl('print/print', array('id' => $baseform->id));
    			$operate .= '<div class="cz_list_btn"><input type="hidden" value="'.$baseform->form_sn.'" class="form_sn">';
    			if (checkOperation("打印")) {
    				$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
    				$operate_count++;
    			}
    			//编辑|提交
    			if ($item->performance_status == 0 && in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && checkOperation("短期借贷:新增"))
    			{
    				switch ($baseform->form_status)
    				{
    					case 'unsubmit':
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    					case 'submited':
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png" /></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    					default: break;
    				}
    			}
    			$quxiao = checkOperation('短期借贷:取消审核');
    			if ($item->lending_direction == 'lend')
    			{
    				$chuna=checkOperation('短期借贷:出纳审核');
    				$zongjingli=checkOperation('短期借贷:总经理审核');
    				$caiwuzhuguan=checkOperation('短期借贷:财务主管审核');
    				$yewu=checkOperation('短期借贷:业务经理审核');    	
    				switch ($baseform->form_status)
    				{
    					case 'submited':
    						if($yewu){
    							$sub_operate .= '<span title="业务经理审核" id="'.Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id)).'" class="check_form" frm="fdj" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？"  lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						break;
    					case 'approved_1':
    						if($quxiao){
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						if($caiwuzhuguan){
    							$sub_operate .= '<span title="财务主管审核" id="'.Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id)).'"  frm="fdj" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？"  lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						break;
    					case 'approved_2':
    						if($quxiao){
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						if($zongjingli){
    							$sub_operate .= '<span title="总经理审核" id="'.Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id)).'" frm="fdj" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？"  lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						break;
    					case 'approved_3':
    						if($quxiao){
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						if($chuna){
    							$sub_operate .= '<span title="出纳审核"  position="chuna" id="'.Yii::app()->createUrl('shortLoan/check', array('id' => $baseform->id)).'"  frm="fdj" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？"  lastdate="'.$baseform->last_update.'" onclick="setPayCheck_unrefresh(this);"><img src="/images/shenhe.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						break;
    					case 'approve':
    						if($quxiao){
    							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
    							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						}
    						break;
    					default: break;
    				}
    			}
    			 
    			if ($item->performance_status == 0 && checkOperation("短期借贷:出入账"))
    			{
    				switch ($baseform->form_status)
    				{
    					case 'submited':
    						if ($item->lending_direction != 'borrow') break;
    					case 'approve':
    						$sub_operate .= '<a class="accounted_form" href="'.$checkA_url.'" title="出/入账"><span><img src="/images/ruzhang.png"/></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    					case 'accounted':
    						$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消出/入账"><img src="/images/qxrz.png"/></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
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
    		}    			
    	}
    	return $operate;
    }   
    
} 