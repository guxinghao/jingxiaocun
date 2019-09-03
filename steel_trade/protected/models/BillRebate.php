<?php

/** 
 * This is the biz model class for table "bill_rebate". 
 * 
 */ 
class BillRebate extends BillRebateData
{
	public static $type = array('warehouse' => "仓库返利", 'supply' => "钢厂返利", 'cost' => "仓储费用");
     

    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array(
            'baseformGCFL' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformGCFL.form_type = 'GCFL'"), //钢厂返利
            'baseformCKFL' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformCKFL.form_type = 'CKFL'"), //仓库返利
            'baseformCCFY' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseformCCFY.form_type = 'CCFY'"), //仓储费用
            'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
            'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
        	'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
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
            'warehouse_id' => 'Warehouse',
            'company_id' => 'Company',
            'title_id' => 'Title',
            'fee' => 'Fee',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
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
        $criteria->compare('warehouse_id',$this->warehouse_id);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('fee',$this->fee,true);
        $criteria->compare('start_time',$this->start_time);
        $criteria->compare('end_time',$this->end_time);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
        /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return BillRebate the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 

    public static function getInputData($post) 
    {
    	$data = array();
    	
    	$post['CommonForms']['created_by'] = currentUserId();
    	$post['BillRebate']['fee'] = numChange($post['BillRebate']['fee']);
    	$post['BillRebate']['start_time'] = strtotime($post['BillRebate']['start_time']." 00:00:00");
    	$post['BillRebate']['end_time'] = strtotime($post['BillRebate']['end_time']." 23:59:59");
    	$data['main'] = (Object)$post['BillRebate'];
    	$data['common'] = (Object)$post['CommonForms'];
    	
    	return $data;
    }
    
    public static function getFormList($search, $type) 
    {
        $tableData = array();
        $model = new BillRebate();
        $criteria = new CDbCriteria();
        
        switch ($type) 
        {
        	case 'warehouse': //仓库返利
        		$form_type = "CKFL";
        		$criteria->with = array('baseformCKFL');
        		$_baseform = "baseformCKFL";
        		break;
        	case 'supply': //钢厂返利
        		$form_type = "GCFL";
        		$criteria->with = array('baseformGCFL');
        		$_baseform = "baseformGCFL";
        		break;
        	case 'cost': //仓储费用
        		$form_type = "CCFY";
        		$criteria->with = array('baseformCCFY');
        		$_baseform = "baseformCCFY";
        		break;
        	default: 
        		break;
        }
        $criteria->addCondition("t.type = :type"); 
        $criteria->params[':type'] = $type;
        
        //搜索
        if (!empty($search)) 
        {
        	if ($search['keywords']) 
        	{
        		$criteria->addCondition("$_baseform.form_sn like :keywords");
        		$criteria->params[':keywords'] = "%".strtoupper($search['keywords'])."%";
        	}
        	if ($search['time_L']) //开始时间
        	{
        		$criteria->addCondition("$_baseform.created_at >= :time_L");
        		$criteria->params[':time_L'] = strtotime($search['time_L']." 00:00:00");
        	}
        	if ($search['time_H']) //结束时间
        	{
        		$criteria->addCondition("$_baseform.created_at <= :time_H");
        		$criteria->params[':time_H'] = strtotime($search['time_H']." 23:59:59");
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
        	if ($search['form_status']) //状态
        	{
        		$criteria->addCondition("$_baseform.form_status = :form_status");
        		$criteria->params[':form_status'] = $search['form_status'];
        	}
        }
        $criteria->compare("$_baseform.form_type", $form_type, true);
        if (!$search['form_status'] || $search['form_status'] != 'delete') 
        	$criteria->compare("$_baseform.is_deleted", '0', true);
        
        $pages = new CPagination();
        $pages->itemCount = $model->count($criteria);
        $pages->pageSize = intval($_COOKIE['bill_rebate_list']) ? intval($_COOKIE['bill_rebate_list']) : Yii::app()->params['pageCount'];
        $pages->applyLimit($criteria);
        $criteria->order="$_baseform.created_at DESC";
        
        $items = $model->findAll($criteria);
        if (!$items) return array($tableData, $pages);
        
        $i = 1;
        foreach ($items as $item) 
        {
            $mark = '';
            $operate = '';
            $sub_operate = '';
            $operate_count = 0;
            $da = array();
            $baseform = $item->$_baseform; 
            if ($baseform) {
                $view_url = Yii::app()->createUrl("billRebate/view", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
                $edit_url = Yii::app()->createUrl("billRebate/update", array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
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
                $sub_url = Yii::app()->createUrl('billRebate/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
				$del_url = Yii::app()->createUrl('billRebate/deleteform', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
				$checkP_url = Yii::app()->createUrl('billRebate/check', array('id' => $baseform->id, 'type' => 'pass', 'last_update' => $baseform->last_update));
				$checkD_url = Yii::app()->createUrl('billRebate/check', array('id' => $baseform->id, 'type' => 'deny', 'last_update' => $baseform->last_update));
                $checkC_url = Yii::app()->createUrl('billRebate/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
                
                $operate .= '<div class="cz_list_btn"><input type="hidden" value="'.$baseform->form_sn.'" class="form_sn">';
                if (in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && (($item->type == 'warehouse' && checkOperation("仓库返利:新增")) || ($item->type == 'supply' && checkOperation("钢厂返利:新增")) || ($item->type == 'cost' && checkOperation("仓储费用:新增")))) 
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
                		default:
                			break;
                	}
                }
                if (($item->type == 'warehouse' && checkOperation("仓库返利:审核")) || ($item->type == 'supply' && checkOperation("钢厂返利:审核")) || ($item->type == 'cost' && checkOperation("仓储费用:审核"))) 
                {
                	switch ($baseform->form_status) 
                	{
                		case 'submited':
                			$sub_operate .= '<span id="'.Yii::app()->createUrl('billRebate/check', array('id' => $baseform->id)).'" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此单吗？" title="审核" lastdate="'.$baseform->last_update.'" onclick="setCheck(this);"><img src="/images/shenhe.png" /></span>';
                			if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
                			break;
						case 'approve': 
							$sub_operate .= '<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png" /></span>';
							if (++ $operate_count < 4) { $operate .= $sub_operate; $sub_operate = ''; }
							
							if ($item->type == 'warehouse' && checkOperation("收款登记:新增")) //仓库返利
							{
								$sk_url = Yii::app()->createUrl('formBill/create', array('type' => "SKDJ", 'bill_type' => "CKFL", 'common_id' => $baseform->id));
								$sub_operate .= '<a class="update_b" href="'.$sk_url.'" title="收款登记"><span><img src="/images/fukuan.png"/></span></a>';
								if (++ $operate_count < 4) {
									$operate .= $sub_operate; $sub_operate = '';
								}
							}
							elseif ($item->type == 'supply' && checkOperation("收款登记:新增")) //钢厂返利
							{
								$sk_url = Yii::app()->createUrl('formBill/create', array('type' => "SKDJ", 'bill_type' => "GCFL", 'common_id' => $baseform->id));
								$sub_operate .= '<a class="update_b" href="'.$sk_url.'" title="收款登记"><span><img src="/images/fukuan.png"/></span></a>';
								if (++ $operate_count < 4) {
									$operate .= $sub_operate; $sub_operate = '';
								}
							}
							elseif ($item->type == 'cost' && checkOperation("付款登记:新增")) //仓储费用
							{
								$fk_url = Yii::app()->createUrl('formBill/create', array('type' => "FKDJ", 'bill_type' => "CCFY", 'common_id' => $baseform->id));
								$sub_operate .= '<a class="update_b" href="'.$fk_url.'" title="付款登记"><span><img src="/images/fukuan.png"/></span></a>';
								if (++ $operate_count < 4) {
									$operate .= $sub_operate; $sub_operate = '';
								}
							}
							break;
						default: 
							break;
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
            
            $da['data'] = array($mark, 
            		$operate, 
            		'<a title="查看详情" href="'.$view_url.'" class="a_view">'.$baseform->form_sn.'</a>',
            		CommonForms::$formStatus[$baseform->form_status],
            		$baseform->form_time,
            		'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>', 
            		'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
            );
            if ($item->type == 'cost' || $item->type == 'warehouse') $da['data'][] = '<span title="'.$item->warehouse->name.'">'.$item->warehouse->name.'</span>';
            $da['data'] = array_merge($da['data'], array(
            		number_format($item->fee, 2),
            		$item->start_time > 0 ? date('Y-m-d', $item->start_time) : '',
            		$item->end_time > 0 ? date('Y-m-d', $item->end_time) : '',
            		//$baseform->belong->nickname,
            		$baseform->operator->nickname,
            		$baseform->form_status == 'approve' ? $baseform->approver->nickname : '',
            		$baseform->form_status == 'approve' && $baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '',
            		'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,"utf-8").'</span>',
            ));
            if ($baseform->form_status == 'delete') $da['data'][] = $baseform->delete_reason;
            
            $da['group'] = $baseform->form_sn;
            array_push($tableData, $da);
        }
        return array($tableData, $pages);
    }
    
    public static function getBillRebateList($search, $type) 
    {
    	$tableHeader = array(
    			array('name' => "", 'class' => "sort-disabled", 'width' => "4%"),
    			array('name' => "单号", 'class' => "sort-disabled", 'width' => "12%"),
    			array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "10%"),
    			array('name' => "公司", 'class' => "sort-disabled", 'width' => "11%"),
    			array('name' => "仓库结算/钢厂", 'class' => "sort-disabled", 'width' => "11%"),
    			array('name' => "金额", 'class' => "sort-disabled text-right", 'width' => "12%"),
    			array('name' => "开始时间", 'class' => "sort-disabled", 'width' => "10%"),
    			array('name' => "结束时间", 'class' => "sort-disabled", 'width' => "10%"),
    			array('name' => "类型", 'class' => "sort-disabled", 'width' => "10%"),
    			array('name' => "仓库", 'class' => "sort-disabled", 'width' => "10%"),
    	);
    	
    	$tableData = array();
    	$model = new BillRebate();
    	$criteria = new CDbCriteria();
    	switch ($type) 
    	{
    		case 'GCFL': 
    			$_baseform = "baseformGCFL";
    			break;
    		case 'CKFL': 
    			$_baseform = "baseformCKFL";
    			break;
    		case 'CCFY': 
    			$_baseform = "baseformCCFY";
    			break;
    		default: break;
    	}
    	$criteria->with = array($_baseform);
    	
    	//搜索
    	if (!empty($search)) 
    	{
    		
    	}
    	$criteria->compare("$_baseform.form_type", $type, true);
    	$criteria->compare("$_baseform.is_deleted", '0', true);
    	$criteria->compare("$_baseform.form_status", "approve", true);
    	
    	$pages = new CPagination();
    	$pages->itemCount = $model->count($criteria);
    	$pages->pageSize =intval($_COOKIE['billRebate_list']) ? intval($_COOKIE['billRebate_list']) : Yii::app()->params['pageCount'];
    	$pages->applyLimit($criteria);
    	$criteria->order = "$_baseform.created_at DESC";
    	
    	$billRebates = $model->findAll($criteria);
    	if (!$billRebates) return array($tableHeader, $tableData, $pages);
    	
    	$i = 1;
    	foreach ($billRebates as $item) 
    	{
    		$mark = '';
    		$operate = '';
    		$da = array();
    		$baseform = $item->$_baseform;
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
    				$item->start_time > 0 ? date('Y-m-d', $item->start_time) : '', 
    				$item->end_time > 0 ? date('Y-m-d', $item->end_time) : '', 
    				BillRebate::$type[$item->type], 
    				$item->warehouse_id > 0 ? $item->warehouse->name : '',
    		);
    		$da['group'] = $baseform->form_sn;
    		array_push($tableData, $da);
    	}
    	return array($tableHeader, $tableData, $pages);
    }
    
    //均摊
    public static function shareEqually($id, $type=0) 
    {
    	$form = new BillRebateClass($id);
    	//if ($form->commonForm->form_status != 'approve') return false;
    	
    	$model = new FrmPurchase();
    	$criteria = new CDbCriteria();
    	$criteria->with = array('baseform');
    	
    	$criteria->addCondition('title_id = :title_id');
    	$criteria->params[':title_id'] = $form->mainInfo->title_id;
    	switch ($form->mainInfo->type) 
		{
			case 'warehouse': //仓库返利
				$criteria->addCondition('t.warehouse_id = :warehouse_id');
				$criteria->params[':warehouse_id'] = $form->mainInfo->warehouse_id;
				$equally_name = 'ware_rebate';
				break;
			case 'supply': //钢厂返利
				$criteria->addCondition('t.supply_id = :supply_id');
				$criteria->params[':supply_id'] = $form->mainInfo->company_id;
				$equally_name = 'rebate';
				break;
			case 'cost': //仓储费用
				$criteria->addCondition('t.warehouse_id = :warehouse_id');
				$criteria->params[':warehouse_id'] = $form->mainInfo->warehouse_id;
				$equally_name = 'ware_cost';
				break;
			default: break;
		}
		$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) >= :start_time && UNIX_TIMESTAMP(baseform.form_time) <= :end_time");
		$criteria->params[':start_time'] = $form->mainInfo->start_time;
		$criteria->params[':end_time'] = $form->mainInfo->end_time;
		$criteria->addNotInCondition('baseform.form_status', array('unsubmit', 'delete'));
// 		$criteria->compare("baseform.form_status", array('submited', 'approve'), true);
		
		$total_weight = 0.0;
		//未审单总重
		$c1 = clone $criteria;
		$c1->select = "sum(t.weight) as total_weight, count(*) as total_num";
		$c1->addCondition("t.weight_confirm_status = 0");
		$total1 = FrmPurchase::model()->find($c1);
		$total_weight += $total1 ? floatval($total1->total_weight) : 0;
		
		//已审单总重
		$c2 = clone $criteria;
		$c2->select = "sum(t.confirm_weight) as total_weight, count(*) as total_num";
		$c2->addCondition("t.weight_confirm_status = 1");
		$total2 = FrmPurchase::model()->find($c2);
		$total_weight += $total2 ? floatval($total2->total_weight) : 0;
		
		$purchases = $model->findAll($criteria);
		foreach ($purchases as $purchase)
		{
			if ($total_weight == 0) continue;
			if($type == 0){
				$purchase->$equally_name = floatval($form->mainInfo->fee) / $total_weight; //均摊返利单价
			}else{
				$purchase->$equally_name = 0; //均摊返利单价
			}
// 			 * ($purchase->weight_confirm_status == 1 ? floatval($purchase->confirm_weight) : floatval($purchase->weight));
			$purchase->update();
            ProfitChange::createNew('purchase',$purchase->baseform->id,1);//
		}
    }
    
} 