<?php

/** 
 * This is the biz model class for table "bill_record". 
 * 
 */ 
class BillRecord extends BillRecordData
{
	public $total_weight;
	public $total_price;
	public $total_num;
	public static $billType = array('purchase' => "采购运费", 'sales' => "销售运费");

    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array(
        		'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
        		'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'), 
        		'relationForm' => array(self::BELONGS_TO, 'CommonForms', 'frm_common_id'), 
        		'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type = 'FYDJ'"),
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'company_id' => 'Company',
            'title_id' => 'Title',
            'frm_common_id' => 'Frm Common',
            'price' => 'Price',
            'weight' => 'Weight',
            'amount' => 'Amount',
            'is_yidan' => 'Is Yidan',
            'travel' => 'Travel',
            'discount' => 'Discount',
            'is_selected' => 'Is Selected',
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
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('frm_common_id',$this->frm_common_id);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('weight',$this->weight,true);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('is_yidan',$this->is_yidan);
        $criteria->compare('travel',$this->travel,true);
        $criteria->compare('discount',$this->discount,true);
        $criteria->compare('is_selected',$this->is_selected);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
	/** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return BillRecord the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
    
    /**
     * 整理数据类型
     * @param Array $post
     */
    public static function getFormInput($post) 
    {
    	$post['BillRecord']['weight'] = floatval(numChange($post['BillRecord']['weight']));
    	$post['BillRecord']['price'] = floatval(numChange($post['BillRecord']['price']));
    	$post['BillRecord']['amount'] = floatval(numChange($post['BillRecord']['amount']));
    	
    	$data['common'] = (Object)$post['CommonForms'];
    	$data['main'] = (Object)$post['BillRecord'];
    	return $data;
    }
    
    public static function getFormList($search, $common_id = 0) 
    {
    	$tableData = array();
    	$model = new BillRecord();
    	$criteria = new CDbCriteria();
    	$criteria->with = array('baseform', 'relationForm');
    	
    	if ($common_id) 
    	{
    		$criteria->addCondition("t.frm_common_id = :common_id");
    		$criteria->params[':common_id'] = $common_id;
    	} 
    	else 
    	{
    		$criteria->addCondition("baseform.form_status != 'unsubmit'");
    	}
    	
    	if (!empty($search)) 
    	{
    		if ($search['keywords']) //单号
    		{
    			$criteria->addCondition("baseform.form_sn LIKE :keywords OR relationForm.form_sn LIKE :keywords");
    			$criteria->params[':keywords'] = '%'.$search['keywords'].'%';
    		}
    		if ($search['time_L']) //开始时间
    		{
    			$criteria->addCondition("baseform.created_at >= :time_L");
    			$criteria->params[':time_L'] = strtotime($search['time_L'].' 00:00:00');
    		}
    		if ($search['time_H']) //结束时间
    		{
    			$criteria->addCondition("baseform.created_at <= :time_H");
    			$criteria->params[':time_H'] = strtotime($search['time_H'].' 23:59:59');
    		}
    		if ($search['bill_type']) 
    		{
    			$criteria->addCondition("t.bill_type = :bill_type");
    			$criteria->params[':bill_type'] = $search['bill_type'];
    		}
    		if ($search['title_id']) //公司
    		{
    			$criteria->addCondition("t.title_id = :title_id");
    			$criteria->params[':title_id'] = $search['title_id'];
    		}
    		if ($search['company_id']) //结算单位
    		{
    			$criteria->addCondition("t.company_id = :company_id");
    			$criteria->params[':company_id'] = $search['company_id'];
    		}
    		if ($search['form_status']) 
    		{
    			$criteria->addCondition("baseform.form_status = :form_status");
    			$criteria->params[':form_status'] = $search['form_status'];
    		}
    	}
    	if (!$search['form_status'] || $search['form_status'] != 'delete')
    		$criteria->compare('baseform.is_deleted', '0', true);
    	
    	//总计
    	$c = clone $criteria;
    	$c->select = "sum(t.weight) as total_weight,sum(t.amount) as total_price, count(*) as total_num";
    	$total = BillRecord::model()->find($c);
    	
    	$totaldata = array();
    	$totaldata['weight'] = $total->total_weight;
    	$totaldata['price'] = $total->total_price;
    	$totaldata['total_num'] = $total->total_num;
    	
    	//分页
    	$pages = new CPagination();
    	$pages->itemCount = $model->count($criteria);
    	$pages->pageSize = intval($_COOKIE['bill_record_list']) ? intval($_COOKIE['bill_record_list']) : Yii::app()->params['pageCount'];
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
    		$relationForm = $item->relationForm;
    		if ($baseform)
    		{
    			$view_url = Yii::app()->createUrl('BillRecord/view', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
    			$edit_url = Yii::app()->createUrl('BillRecord/update', array('id' => $baseform->id, 'fpage' => $_REQUEST['page']));
    			switch ($baseform->form_status)
    			{
    				case 'unsubmit':
    					$type_sub="submit";
    					$title_sub="提交";
    					$img_url = "/images/tijiao.png";
    					break;
    				case 'submited':
    					$type_sub="cancle";
    					$title_sub="取消提交";
    					$img_url = "/images/qxtj.png";
    					break;
    				default:
    					break;
    			}
    			$sub_url = Yii::app()->createUrl('billRecord/submit', array('id' => $baseform->id, 'type' => $type_sub, 'last_update' => $baseform->last_update));
    			$del_url = Yii::app()->createUrl('billRecord/deleteForm', array('id' => $baseform->id, 'last_update' => $baseform->last_update));
    			$checkC_url = Yii::app()->createUrl('billRecord/check', array('id' => $baseform->id, 'type' => 'cancle', 'last_update' => $baseform->last_update));
    			$fk_url = Yii::app()->createUrl("formBill/create", array('type' => "FKDJ", 'bill_type' => "YF", 'common_id' => $baseform->id, 'fpage' => $_REQUEST['page']));
    			
    			$operate = '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
    			if ($common_id && in_array(Yii::app()->user->userid, array($baseform->created_by, $baseform->owned_by)) && ($relationForm->form_type == 'CGD' && checkOperation("采购运费:新增") || $relationForm->form_type == 'XSD' && checkOperation("销售运费:新增")))
    			{
    				switch ($baseform->form_status)
    				{
    					case 'unsubmit':
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
    						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
//     						$sub_operate .= '<span id="'.$baseform->id.'" class="delete_form" url="'.$del_url.'" title="作废"><img src="/images/zuofei.png"/></span>';
    						$sub_operate .= '<span id="'.$del_url.'" data-id="'.$baseform->id.'" class="delete_form" lastdate="'.$baseform->last_update.'" title="作废"><img src="/images/zuofei.png"/></span>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
    						break;
    					case 'submited':
    						$sub_operate .= '<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"/></span></a>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
    						$sub_operate .= '<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
    						break;
    					default: break;
    				}
    			}
    			
    			if ($relationForm->form_type == 'CGD' && checkOperation("采购运费:审核") || $relationForm->form_type == 'XSD' && checkOperation("销售运费:审核"))
    			{
    				switch ($baseform->form_status)
    				{
    					case 'submited':
    						$sub_operate .= '<span id="'.Yii::app()->createUrl('billRecord/check', array('id' => $baseform->id)).'" class="check_form" str="单号:'.$baseform->form_sn.',确定审核通过此运费登记吗？" lastdate="'.$baseform->last_update.'" onclick="setCheck(this);"><img src="/images/shenhe.png" /></span>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
    						break;
    					case 'approve':
    						$sub_operate .= '<span id="'.$baseform->id.'" class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png"></span>';
    						if (++ $operate_count < 4) {
    							$operate .= $sub_operate; $sub_operate = '';
    						}
    						if ($item->is_selected == 0 && checkOperation("付款登记:新增"))
    						{
    							$sub_operate .= '<a class="update_b" href="'.$fk_url.'" title="付款登记"><span><img src="/images/fukuan.png"/></span></a>';
    							if (++ $operate_count < 4) {
    								$operate .= $sub_operate; $sub_operate = '';
    							}
    						}
    						break;
    					default: break;
    				}
    			}
    				
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
    				
    			$mark = $i;
    			$i++;
    		}
    		
    		switch ($item->relationForm->form_type) 
    		{
    			case 'CGD': 
    				$sub_view_url = Yii::app()->createUrl('purchase/view', array('id' => $item->relationForm->id, 'type' => $item->relationForm->purchase->purchase_type));
    				break;
    			case 'XSD': 
    				$sub_view_url = Yii::app()->createUrl('FrmSales/detail', array('id' => $item->relationForm->id));
    				break;
    			default: break;
    		}
    		
    		$da['data'] = array($mark,
    				$operate,
    				$baseform->form_sn,
    				CommonForms::$formStatus[$baseform->form_status],
    				$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
    				'<a target="_blank" class="a_view" href="'.$sub_view_url.'">'.$item->relationForm->form_sn.'</a>',
    				'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
    				'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
    				number_format($item->weight, 3),
    				number_format($item->price, 2),
    				number_format($item->amount, 2), 
    				BillRecord::$billType[$item->bill_type],
    				$item->is_yidan == 1 ? "是" : "否",
    				$item->travel,
    				$baseform->belong->nickname,
    				$baseform->operator->nickname,
    				$baseform->form_status == 'approve' ? $baseform->approver->nickname : '',
    				$baseform->form_status == 'approve' && $baseform->approved_at > 0 ? date('Y-m-d', $baseform->approved_at) : '',
    				"<span title=".htmlentities($baseform->comment).">".mb_substr($baseform->comment,0,15,"utf-8")."</span>"
    		);
    		if ($baseform->form_status == 'delete') $da['data'][] = $baseform->delete_reason;
    		$da['group'] = $baseform->form_sn;
    		array_push($tableData, $da);
    	}
    	return array($tableData, $pages, $totaldata);
    }
    
    /**
     *	根据表单id查询所有的费用
     *  id 表单id
     *  type 表单类型
     */
    public static function getTotalFee($id,$type)
    {
    	$form = new BillRecord();
    	$c = new CDbCriteria();
    	$c->with = array("baseform");
    	$c->addCondition("baseform.is_deleted = 0 and baseform.form_status != 'unsubmit'");
    	$c->addCondition("t.frm_common_id = :fid");
    	$c->params[':fid'] = $id;
    	$c->addCondition("t.bill_type = :ftype");
    	$c->params[':ftype'] = $type;
    
    	$list = $form->findAll($c);
    
    	$fee = 0;
    	if($list){
    		foreach ($list as $li){
    			$fee += $li->amount;
    		}
    	}
    	return $fee;
    }
    
    //运费 列表
    public static function getFormBillList($search)
    {
    	$tableHeader = array(
    			array('name' => "", 'class' => "sort-disabled", 'width' => "2%"),
    			array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
    			array('name' => "单号", 'class' => "sort-disabled", 'width' => "10%"),
    			array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "8%"),
    			array('name' => "公司", 'class' => "sort-disabled", 'width' => "8%"),
    			array('name' => "收益单位", 'class' => "sort-disabled", 'width' => "8%"),
    			array('name' => "重量", 'class' => "sort-disabled text-right", 'width' => "9%"),
    			array('name' => "单价", 'class' => "sort-disabled text-right", 'width' => "7%"),
    			array('name' => "金额", 'class' => "sort-disabled text-right", 'width' => "9%"),
    			array('name' => "车船号", 'class' => "sort-disabled", 'width' => "9%"),
    			array('name' => "业务员", 'class' => "sort-disabled", 'width' => "8%"),
    	);
    
    	$tableData = array();
    	$model = new BillRecord();
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
    			$criteria->addCondition("company_id = :company_id");
    			$criteria->params[':company_id'] = $search['company_id'];
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
    	}
    	$criteria->compare("baseform.form_type", 'FYDJ', true);
    	$criteria->compare("baseform.is_deleted", '0', true);
    	$criteria->compare("baseform.form_status", "approve", true);
    
    	$pages = new CPagination();
    	$pages->itemCount = $model->count($criteria);
    	$pages->pageSize =intval($_COOKIE['bill_list']) ? intval($_COOKIE['bill_list']) : Yii::app()->params['pageCount'];
    	$pages->applyLimit($criteria);
    	$criteria->order = "baseform.created_at DESC";
    
    	$bill =  $model->findAll($criteria);
    	if (!$bill) return array($tableHeader, $tableData, $pages);
    	$i = 1;
    	foreach ($bill as $item)
    	{
    		$da = array();
    		$mark = '';
    		$operate = '';
    		$baseform = $item->baseform;
    		if ($baseform)
    		{
    			$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill" yidan="'.$item->is_yidan.'" value="'.$baseform->id.'" />';
    			$mark = $i;
    			$i++;
    		}
    			
    		$da['data'] = array($mark,
    				$operate,
    				$baseform->form_sn,
    				$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
    				'<span title="'.$item->title->name.'">'.$item->title->short_name.'</span>',
    				'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
    				number_format($item->weight, 3),
    				number_format($item->price, 2),
    				'<span class="real_fee">'.number_format($item->amount, 2).'</span>',
    				$item->travel,
    				$baseform->belong->nickname, //业务员
    		);
    		$da['group'] = $baseform->form_sn;
    		array_push($tableData, $da);
    	}
    	return array($tableHeader, $tableData, $pages);
    }
} 