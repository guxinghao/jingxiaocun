<?php

/** 
 * This is the biz model class for table "transfer_accounts". 
 * 
 */ 
class TransferAccounts extends TransferAccountsData
{ 
	public $total_price;
	public $total_num;
	
	public static $type = array(
			'transfer' => "银行转款", 
			'withdrawals' => "提现", 
			'payment' => "缴现"
	);

    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array( 
        		'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type = 'YHHZ'"),
        		'titleOutput' => array(self::BELONGS_TO, 'DictTitle', 'title_output_id'), //转出公司
        		'titleInput' => array(self::BELONGS_TO, 'DictTitle', 'title_input_id'), //转入公司
        		'outputBank' => array(self::BELONGS_TO, 'DictBankInfo', 'output_bank_id'), //转出账户
        		'inputBank' => array(self::BELONGS_TO, 'DictBankInfo', 'input_bank_id'), //转入账户
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'title_output_id' => 'Title Output',
            'title_input_id' => 'Title Input',
            'type' => 'Type',
            'amount' => 'Amount',
            'comment' => 'Comment',
            'output_bank_id' => 'Output Bank',
            'input_bank_id' => 'Input Bank',
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
        $criteria->compare('title_output_id',$this->title_output_id);
        $criteria->compare('title_input_id',$this->title_input_id,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('output_bank_id',$this->output_bank_id);
        $criteria->compare('input_bank_id',$this->input_bank_id);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
        /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return TransferAccounts the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
    
    public static function getInputData($post) 
    {
    	$data = array();
    	$post['TransferAccounts']['amount'] = numChange($post['TransferAccounts']['amount']);
        if ($post['TransferAccounts']['reach_at']) 
            $post['TransferAccounts']['reach_at'] = strtotime($post['TransferAccounts']['reach_at'].' 23:59:59');
    	$post['CommonForms']['created_by'] = currentUserId();
    	$post['CommonForms']['comment'] = $post['TransferAccounts']['comment'];
    	
    	$data['main'] = (Object)$post['TransferAccounts'];
    	$data['common'] = (Object)$post['CommonForms'];
    	return $data;
    }
    
    public static function getFormList($search) 
    {
    	$tableData = array();
    	$model = new TransferAccounts();
    	$criteria = new CDbCriteria();    	
    	$criteria->with = array('baseform');
    	
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
    		if ($search['title_output_id']) //转出公司
    		{
    			$criteria->addCondition("t.title_output_id = :title_output_id");
    			$criteria->params[':title_output_id'] = $search['title_output_id'];
    		}
    		if ($search['title_input_id']) //转入公司
    		{
    			$criteria->addCondition("t.title_input_id = :title_input_id");
    			$criteria->params[':title_input_id'] = $search['title_input_id'];
    		}
    		if ($search['type']) //类型
    		{
    			$criteria->addCondition("t.type = :type");
    			$criteria->params[':type'] = $search['type'];
    		}
    		if ($search['form_status']) //状态
    		{
    			$criteria->addCondition("baseform.form_status = :form_status");
    			$criteria->params[':form_status'] = $search['form_status'];
    		}
    		if ($search['owned'])
    		{
    			$criteria->addCondition("baseform.owned_by = :owned");
    			$criteria->params[':owned'] = $search['owned'];
    		}
    	}
    	$criteria->compare("baseform.form_type", 'YHHZ', true);
    	if (!$search['form_status'] || $search['form_status'] != 'delete')
    		$criteria->compare("baseform.is_deleted", '0', true);
    	
    	//总计
    	$c = clone $criteria;
    	$c->select = "sum(t.amount) as total_price, count(*) as total_num";
    	$total = TransferAccounts::model()->find($c);
    	
    	$totaldata = array();
    	$totaldata['price'] = $total->total_price;
    	$totaldata['total_num'] = $total->total_num;
    	
    	$pages = new CPagination();
    	$pages->itemCount = $model->count($criteria);
    	$pages->pageSize = intval($_COOKIE['transfer_accounts']) ? intval($_COOKIE['transfer_accounts']) : Yii::app()->params['pageCount'];
    	$pages->applyLimit($criteria);
    	$criteria->order = "baseform.created_at DESC";
    	
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
    			$view_url = Yii::app()->createUrl('transferAccounts/view', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
    			$edit_url = Yii::app()->createUrl('transferAccounts/update', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
//     			switch ($baseform->form_status)
//     			{
//     				case 'unsubmit':
//     					$type_sub = "submit";
//     					$title_sub = "提交";
//     					$img_url = "/images/tijiao.png";
//     					break;
//     				case 'submited':
//     					$type_sub = "cancle";
//     					$title_sub = "取消提交";
//     					$img_url = "/images/qxtj.png";
//     					break;
//     				default:
//     					break;
//     			}
//     			$sub_url = Yii::app()->createUrl('transferAccounts/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
//    			$checkC_url = Yii::app()->createUrl('transferAccounts/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
    			$checkA_url = Yii::app()->createUrl('transferAccounts/accounted', array('id' => $baseform->id, 'type' => "accounted", 'fpage' => $_REQUEST['page']));
    			$checkCA_url = Yii::app()->createUrl('transferAccounts/accounted', array('id' => $baseform->id, 'type' => "cancle", 'last_update' => $baseform->last_update));
    			
    			$operate .= '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
    			if (in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && checkOperation("银行互转:新增")) 
    			{
    				switch ($baseform->form_status) 
    				{
    					case 'unsubmit': 
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
//     						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
//     						if (++ $operate_count < 4) {
//     							$operate .= $sub_operate; $sub_operate = '';
//     						}
    						$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" title="作废" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png" /></span>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
    						break;
//     					case 'submited': 
//     						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
//     						if (++ $operate_count < 4) {
//     							$operate .= $sub_operate; $sub_operate = '';
//     						}
//     						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'" /></span>';
//     						if (++ $operate_count < 4) {
//     							$operate .= $sub_operate; $sub_operate = '';
//     						}
//     						break;
    					default: break;
    				}
    			}
    			
//     			if (checkOperation("银行互转:审核")) 
//     			{
//     				switch ($baseform->form_status) 
//     				{
//     					case 'submited': 
//     						$sub_operate .= '<span id="'.Yii::app()->createUrl('transferAccounts/check', array('id' => $baseform->id)).'" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setCheck(this);"><img src="/images/shenhe.png" /></span>';
//     						if (++ $operate_count < 4) {
//     							$operate .= $sub_operate; $sub_operate = '';
//     						}
//     						break;
//     					case 'approve': 
//     						$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
//     						if (++ $operate_count < 4) {
//     							$operate .= $sub_operate; $sub_operate = '';
//     						}
//     						break;
//     					default: break;
//     				}
//     			}
    			
    			if (checkOperation("银行互转:入账")) 
    			{
    				switch ($baseform->form_status) 
    				{
    					case 'unsubmit': 
    						$sub_operate .= '<a class="update_b" href="'.$checkA_url.'" title="入账"><span><img src="/images/ruzhang.png"></span></a>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
    						break;
    					case 'accounted': 
    						$sub_operate .= '<span class="submit_form" url="'.$checkCA_url.'" title="取消入账"><img src="/images/qxrz.png" /></span>';
    						if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
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
    		
    		$da['data'] = array($mark, 
    				$operate, 
    				'<a title="查看详情" href="'.$view_url.'" class="a_view">'.$baseform->form_sn.'</a>', 
    				CommonForms::$formStatus[$baseform->form_status], 
    				$baseform->form_time, 
    				//'<span title="'.$item->titleOutput->name.'">'.$item->titleOutput->short_name.'</span>',
    				$item->outputBank->dict_name,
    				//'<span title="'.$item->titleInput->name.'">'.$item->titleInput->short_name.'</span>', 
    				$item->inputBank->dict_name,
    				number_format($item->amount, 2), 
    				TransferAccounts::$type[$item->type], 
    				$baseform->belong->nickname,
    				$baseform->operator->nickname,
    				//in_array($baseform->form_status, array('approve', 'accounted')) ? $baseform->approver->nickname : '',
    				$item->reach_at>0?date("Y-m-d",$item->reach_at):"",
    				$baseform->comment
    		);
    		if ($baseform->form_status == 'delete')
    			$da['data'][] = '<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason, 0,15,"utf-8").'</span>';
    		
    		$da['group'] = $baseform->form_sn;
    		array_push($tableData, $da);
    	}
    	return array($tableData, $pages, $totaldata);
    }

    public static function getAllList($search)
    {
    	$tableData = array();
    	$model = new TransferAccounts();
    	$criteria = new CDbCriteria();
    	$criteria->with = array('baseform');
    	 
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
    		if ($search['title_output_id']) //转出公司
    		{
    			$criteria->addCondition("t.title_output_id = :title_output_id");
    			$criteria->params[':title_output_id'] = $search['title_output_id'];
    		}
    		if ($search['title_input_id']) //转入公司
    		{
    			$criteria->addCondition("t.title_input_id = :title_input_id");
    			$criteria->params[':title_input_id'] = $search['title_input_id'];
    		}
    		if ($search['type']) //类型
    		{
    			$criteria->addCondition("t.type = :type");
    			$criteria->params[':type'] = $search['type'];
    		}
    		if ($search['form_status']) //状态
    		{
    			$criteria->addCondition("baseform.form_status = :form_status");
    			$criteria->params[':form_status'] = $search['form_status'];
    		}
    		if ($search['owned'])
    		{
    			$criteria->addCondition("baseform.owned_by = :owned");
    			$criteria->params[':owned'] = $search['owned'];
    		}
    	}
    	$criteria->compare("baseform.form_type", 'YHHZ', true);
    	if (!$search['form_status'] || $search['form_status'] != 'delete')
    		$criteria->compare("baseform.is_deleted", '0', true);
    		 
    		//总计
    		$c = clone $criteria;
    		$c->select = "sum(t.amount) as total_price";
    		$total = TransferAccounts::model()->find($c);
    		$totaldata = array();
    		$totaldata[5] = $total->total_price;
  
    		$criteria->order = "baseform.created_at DESC";
    		$items = $model->findAll($criteria);
    		if (!$items) return array($tableData, $pages, $totaldata);
    		$i = 1;
    		$content = array();
    		foreach ($items as $item)
    		{
    			$baseform = $item->baseform;
    			$temp = array();
    			$temp[0] = $baseform->form_sn;
    			$temp[1] = CommonForms::$formStatus[$baseform->form_status];
    			$temp[2] = $baseform->form_time;
    			$temp[3] = $item->outputBank->dict_name;
    			$temp[4] = $item->inputBank->dict_name;
    			$temp[5] =numChange(number_format($item->amount, 2));
    			$temp[6] = TransferAccounts::$type[$item->type];
    			$temp[7] = $baseform->belong->nickname;
    			$temp[8] = $baseform->operator->nickname;
    			$temp[9] = $item->reach_at>0?date("Y-m-d",$item->reach_at):"";
    			$temp[10] = htmlspecialchars($baseform->comment);
    			if ($baseform->form_status == 'delete') $temp[11] = $baseform->delete_reason;
    			array_push($content,$temp);
    		}
    		array_push($content,$totaldata);
    		return $content;
    }
} 
