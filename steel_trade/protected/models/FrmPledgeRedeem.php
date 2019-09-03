<?php

/**
 * This is the biz model class for table "frm_pledge_redeem".
 *
 */
class FrmPledgeRedeem extends FrmPledgeRedeemData
{
	

	public $fee,$interest;
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pledgeInfo' => array(self::BELONGS_TO, 'PledgeInfo', 'pledge_info_id'),
			'purchase' => array(self::BELONGS_TO, 'FrmPurchase', 'purchase_id'),
			'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type = 'TPSH'"),
			'title'=>array(self::BELONGS_TO,'DictTitle','title_id'),
			'company'=>array(self::BELONGS_TO,'DictCompany','company_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pledge_info_id' => 'Pledge Info',
			'purchase_id' => 'Purchase',
			'total_fee' => 'Total Fee',
			'interest_fee' => 'Interest Fee',
			'brand_std' => 'Brand Std',
			'product_std' => 'Product Std',
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
		$criteria->compare('pledge_info_id',$this->pledge_info_id);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('total_fee',$this->total_fee,true);
		$criteria->compare('interest_fee',$this->interest_fee,true);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('product_std',$this->product_std,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPledgeRedeem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//托盘赎回 列表
	public static function getFormBillList($search) 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "4%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "托盘公司", 'class' => "sort-disabled", 'width' => "11%"),
				array('name' => "产地", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "品名", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "托盘金额", 'class' => "sort-disabled text-right", 'width' => "10%"),
				array('name' => "利息", 'class' => "sort-disabled text-right", 'width' => "10%"),
				array('name' => "采购单号", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "10%"),
		);
		
		$tableData = array();
		$model = new FrmPledgeRedeem();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		
		//搜索
		if (!empty($search))
		{
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
			if ($search['owned_by'])
			{
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		$criteria->compare("baseform.form_type", 'TPSH', true);
		$criteria->compare("baseform.is_deleted", 0, true);
		$criteria->compare("baseform.form_status", "approve", true);
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['bill_list']) ? intval($_COOKIE['bill_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		if (!$search['owned_by']) return array($tableHeader, $tableData, $pages);
		$bill = $model->findAll($criteria);
		if ($bill) 
		{
			$i = 1;
			foreach ($bill as $item) 
			{
				$mark = '';
				$operate = '';
				$da = array('data' => array());
				if ($item->baseform)
				{
					$baseform = $item->baseform;
					$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill" value="'.$baseform->id.'" />';
					$mark = $i;
					$i++;
				}
				
				$da['data'] = array($mark, 
						$operate, 
						$baseform->form_sn,
						$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
						'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
						DictGoodsProperty::getProName($item->brand_id), //产地
						DictGoodsProperty::getProName($item->product_id), //品名
						'<span class="real_fee">'.number_format($item->total_fee, 2).'</span>', //金额
						number_format($item->interest_fee, 2), //利息
						$item->purchase->baseform->form_sn, 
						$baseform->belong->nickname, //业务员
				);
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	
	
	
	/****************************************************/
	/*
	 * 托盘管理处的托盘列表
	 */
	public static function getPledgeList($search)
	{
		$tableHeader = array(
				array('name' => "", 'class' =>"sort-disabled", 'width' => "30px"),
				array('name' => "操作", 'class' =>"sort-disabled", 'width' => "150px"),
				array('name' => "单号", 'class' =>"sort-disabled", 'width' => "150px"),
				array('name' => "开单日期", 'class' =>"flex-col  sort-disabled", 'width' => "100px"),
				array('name' => "状态", 'class' =>"flex-col  sort-disabled", 'width' => "80px"),
				array('name' => "采购公司", 'class' =>"flex-col  sort-disabled", 'width' => "110px"),
				array('name' => "托盘公司", 'class' =>"flex-col  sort-disabled", 'width' => "110px"),
				array('name' => "采购单号", 'class' =>"flex-col  sort-disabled", 'width' => "140px"),
				array('name' => "产地", 'class' =>"flex-col  sort-disabled", 'width' => "90px"),
				array('name' => "品名", 'class' =>"flex-col  sort-disabled", 'width' => "90px"),
				array('name' => "托盘重量", 'class' =>"flex-col  sort-disabled", 'width' => "150px"),
				array('name' => "托盘金额", 'class' =>"flex-col  sort-disabled", 'width' => "100px"),
				array('name' => "已赎回重量", 'class' =>"flex-col  sort-disabled", 'width' => "150px"),
				array('name' => "重量", 'class' =>"flex-col  sort-disabled", 'width' => "150px"),
				array('name' => "金额", 'class' =>"flex-col  sort-disabled", 'width' => "150px"),
				array('name' => "利息", 'class' =>"flex-col  sort-disabled", 'width' => "100px"),
				array('name' => "业务员", 'class' =>"flex-col  sort-disabled", 'width' => "100px"),
				
				array('name'=>'审核人','class' =>"flex-col sort-disabled",'width'=>"75px"),//
				array('name'=>'审核时间','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'制单人','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'最后操作人','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'备注','class' =>"flex-col sort-disabled",'width'=>"230px"),//
		);
		if($search['form_status']=='delete')
		{
			$reason=array('name'=>'作废原因','class' =>"flex-col sort-disabled",'width'=>"230px");
			array_push($tableHeader, $reason);
			array_splice($tableHeader, 1,1);
		}
		$tableData = array();
		$model = new FrmPledgeRedeem();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform','purchase.baseform_pur');
		//搜索
		if (!empty($search))
		{
			if($search['keywords']){
				$criteria->addCondition("baseform.form_sn like :keywords or baseform_pur.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if($search['time_L']){
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']);
			}
			if($search['time_H']){
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) < :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H'])+86400;
			}
			if($search['company']){
				$criteria->compare('t.title_id',$search['company']);
			}
			if($search['vendor']){
				$criteria->compare('t.company_id', $search['vendor']);
			}
			if($search['form_status']){
				$criteria->compare('baseform.form_status', $search['form_status']);
			}else{
				$criteria->addCondition('baseform.form_status!="delete"');
			}
			if ($search['owned']){
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned'];
			}
			if($search['brand']){
				$criteria->compare('t.brand_id', $search['brand']);
			}
			if($search['product']){
				$criteria->compare('t.product_id', $search['product']);
			}
		}else{
			$criteria->compare('baseform.is_deleted','0');
		}
		$criteria->compare("baseform.form_type", 'TPSH');
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['bill_list']) ? intval($_COOKIE['bill_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		$bill =  $model->findAll($criteria);
		if($bill)
		{
			$i = 1;
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			foreach ($bill as $each)
			{
				$mark = '';
				$da = array('data' => array());				
				$baseform = $each->baseform;
				$purchase=$each->purchase;
				$pledgeInfo=$each->pledgeInfo;
				$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
				if($baseform->form_status=='unsubmit')
				{
					$type_sub="submit";
					$title_sub="提交";
					$img_url = "/images/tijiao.png";
				}elseif($baseform->form_status=='submited')
				{
					$type_sub="cancle";
					$title_sub="取消提交";
					$img_url = "/images/qxtj.png";
				}		
				$sub_url =  Yii::app()->createUrl('pledge/submit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
				$edit_url = Yii::app()->createUrl('pledge/update',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['page']));
				$del_url= Yii::app()->createUrl('pledge/deleteform',array('id'=>$baseform->id,'last_update'=>$baseform->last_update));
				$checkP_url=Yii::app()->createUrl('pledge/check',array('id'=>$baseform->id,'type'=>'pass','last_update'=>$baseform->last_update));
				$checkD_url=Yii::app()->createUrl('pledge/check',array('id'=>$baseform->id,'type'=>'deny','last_update'=>$baseform->last_update));
				$checkC_url=Yii::app()->createUrl('pledge/check',array('id'=>$baseform->id,'type'=>'cancle','last_update'=>$baseform->last_update));
				if(isset($_REQUEST['search_dan']))
				{
					$detail_url=Yii::app()->createUrl('pledge/view',array('id'=>$baseform->id,'fpage'=>$_REQUEST['page'],'search_url'=>json_encode($search),'search_dan'=>$_REQUEST['search_dan']));
				}else{
					$detail_url=Yii::app()->createUrl('pledge/view',array('id'=>$baseform->id,'fpage'=>$_REQUEST['page'],'search_url'=>json_encode($search)));
				}				
				if($baseform->form_status=='unsubmit')
				{
					if(checkOperation("托盘赎回:新增"))
					{
						$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';//提交
						$operate.='<span class="delete_form" thisid="'.$baseform->id.'" url="'.$del_url.'" title="作废"><span><img src="/images/zuofei.png"></span></span>';
						$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a>';
						$operate.='</div>';
					}else{
						$operate.='</div>';
					}
				}
				//已提交
				if($baseform->form_status=='submited')
				{
					if(checkOperation("托盘赎回:新增"))
					{
						$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';//提交
						$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a>';
						$num+=2;
						if(!checkOperation("托盘赎回:审核"))
						{
							$operate.='</div>';
						}else{
							$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$baseform->form_sn.',确定审核通过此托盘赎回单吗？"><img src="/images/shenhe.png"></span>';
							$operate.='</div>';
						}
					}else{
						if(checkOperation("托盘赎回:审核"))
						{
							$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$baseform->form_sn.',确定审核通过此托盘赎回单吗？"><img src="/images/shenhe.png"></span>';
						}
						$operate.='</div>';
					}
				}
				//已审核
				if($baseform->form_status=='approve')
				{
						if(checkOperation("付款登记:新增"))
						{
							$pay_url = Yii::app()->createUrl('formBill/create', array('type' => "FKDJ", 'bill_type' => "TPSH", 'common_id' => $baseform->id, 'fpage' => $_REQUEST['page']));	
							$operate .= '<a class="update_b" href="'.$pay_url.'" title="付款"><span><img src="/images/fukuan.png"></span></a><abc></abc>';
							$num++;
						}
						if(checkOperation("托盘赎回:审核"))
						{
							$num++;
							$operate.='<span class="cancelcheck_form" thisid="'.$each->id.'" url="'.$checkC_url.'" title="取消审核" str="确定要取消审核托盘赎回单'.$baseform->form_sn.'吗？"><img src="/images/qxsh.png"></span><abc></abc>';
						}
						$operate.='</div>';
				}
				$mark = $i;
				$i++;
				$sql='select sum(weight) as sum_weight from  pledge_redeemed where purchase_id='.$each->purchase_id;
				$ed=PledgeRedeemed::model()->findBySql($sql);
				$da['data'] = array($mark,
						$operate,
						'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$baseform->form_sn.'</a>',
						$baseform->form_time? $baseform->form_time : '',
						'<span class="'.($baseform->form_status!='approve'?'red':'').'">'.$_status[$baseform->form_status].'</span>',
						$each->title->short_name,
						'<span title="'.$each->company->name.'">'.$each->company->short_name.'</span>',
						$each->purchase->baseform->form_sn,
						DictGoodsProperty::getProName($each->brand_id), //产地
						DictGoodsProperty::getProName($each->product_id), //品名
						number_format($purchase->weight,3),
						number_format($pledgeInfo->fee,2),
						number_format($ed->sum_weight,3),
						number_format($each->weight,3),
						number_format($each->total_fee,2),
						number_format($each->interest_fee, 2), //利息
						$baseform->belong->nickname, //业务员
						
						$baseform->approver->nickname,
						$baseform->approved_at>0?date('Y-m-d',$baseform->approved_at):'',
						$baseform->operator->nickname,
						$baseform->lastupdate->nickname,
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,"utf-8").'</span>',
				);
				if($search['form_status']=='delete'){
					$re='<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason, 0,15,"utf-8").'</span>';
					array_push($da['data'], $re);
					array_splice($da['data'], 1,1);
				}
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	
	
	/*
	 * 获取输入的赎回信息
	 */
	public static function getInputData($post)
	{
		$post['CommonForms']['form_type']='TPSH';
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=(Object)$post['FrmPledgeRedeem'];
		return $data;
	}
	
	
	/*
	 * 托盘查询
	 * 获取托盘采购信息
	 */
	public static function getTpcgList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),
				array('name'=>'采购单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'托盘日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'托盘公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'赎回限制','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'托盘金额','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'托盘预付款','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'托盘单价','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'托盘总重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'已赎回总重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),//
				array('name'=>'当前托盘重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'当前已赎回重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"90px"),//
		);
		$tableData=array();
		$model=FrmPurchase::model();
		$criteria=New CDbCriteria();
		$criteria->with=array('baseform','pledge');
		$criteria->together=true;
		//搜索
		if (!empty($search))
		{
			if ($search['keywords']){
				$criteria->addCondition("baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if ($search['time_L']){
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']);
			}
			if ($search['time_H']){
				$criteria->addCondition("UNIX_TIMESTAMP(baseform.form_time) < :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H'])+86400;
			}
			if($search['company']){
				$criteria->compare('t.title_id',$search['company']);
			}
			if($search['vendor']){
				$criteria->compare('pledge.pledge_company_id', $search['vendor']);
			}
			if($search['form_status']){
				$criteria->compare('baseform.form_status', $search['form_status']);
			}else{
				$criteria->addCondition('baseform.form_status!="delete"');
			}
			if ($search['owned']){
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned'];
			}
		}else{
			$criteria->compare('baseform.is_deleted','0');
		}
		$criteria->compare('baseform.form_type','CGD');
		$criteria->compare('baseform.form_status','approve');
		$criteria->addCondition('t.purchase_type="tpcg"');
		
		$newcri=clone $criteria;
		$newcri->select = "sum(t.amount) as total_amount,sum(t.weight) as total_weight,sum(pledge.fee) as total_money,count(*) as total_num";
		$all=FrmPurchase::model()->find($newcri);
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		$totaldata["money"] = $all->total_money;
		$totaldata["total_num"] = $all->total_num;
		
		$criteria->group='t.id';
		$criteria->join='left join pledge_redeemed as p on p.purchase_id=t.id';
		$criteria->select='t.title_id,t.weight,t.purchase_type,sum(p.weight) as sump_weight,sum(p.left_weight) as sump_Lweight';		
		$pages = new CPagination();
		$pages->itemCount = FrmPurchase::model()->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="baseform.created_at DESC";
		$frmpurs=FrmPurchase::model()->findAll($criteria);
		if($frmpurs)
		{
			$mark=1;
			foreach ($frmpurs as $each)
			{
				$baseform=$each->baseform;
				$pledgeInfo=$each->pledge;
				$pledgeR=$each->pledgeRedeem;
				$shu_url=Yii::app()->createUrl('pledge/create',array('purchase_id'=>$baseform->id));
				$search_url=Yii::app()->createUrl('pledge/index',array('search_dan'=>$baseform->form_sn,'fpage'=>$_REQUEST['page']));
				$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
				if(checkOperation("托盘赎回:新增"))
				{
					$operate.='<a class="update_b" href="'.$shu_url.'" title="赎回"><span><img src="/images/shuhui.png"></span></a>';
				}
				if(checkOperation("托盘赎回"))
				{
					$operate.='<a class="update_b" href="'.$search_url.'" title="赎回查询"><span><img src="/images/shcx.png"></span></a>';
				}
				$operate.='</div>';
				$detail_url=Yii::app()->createUrl('purchase/view',array('id'=>$baseform->id,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'pledge/pledgeSearch','search_url'=>json_encode($search)));
				$da['data']=array($mark,
						$operate,
						'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$baseform->form_sn.'</a>',
						$baseform->form_time,
						$each->title->short_name,//
						'<span title="'.$pledgeInfo->pledgeCompany->name.'">'.$pledgeInfo->pledgeCompany->short_name.'</span>',
						$pledgeInfo->r_limit==1?'产地':'产地+品名',
						number_format($pledgeInfo->fee,2),
						number_format($pledgeInfo->advance,2),
						number_format($pledgeInfo->unit_price,2),
						number_format($each->weight,3),
						number_format($each->sump_weight,3),
						'<span style="color:#587ed1;" class="colorbox" url="/index.php/pledge/detailPledged?id='.$pledgeInfo->frm_purchase_id.'">'.number_format($each->weight-($each->sump_weight-$each->sump_Lweight),3).'</span>',
						number_format($each->sump_Lweight,3),
						$baseform->belong->nickname,
				);
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
				$mark++;
			}
		}	
		return array($tableHeader,$tableData,$pages,$totaldata);
	}
	
	
	/*
	 * 获取采购明细赎回
	 */
	public static function getDetailPledged($id)
	{
		$model=FrmPurchase::model()->with('pledge','pledgeRedeem')->findByPk($id);
		if($model)
		{
			$tableData=array();
			$pledge=$model->pledge;
			if($pledge->r_limit=='1')
			{
				$sql='select t.brand_id,sum(t.weight) as sum_weight ,p.weight as checked_weight,p.left_weight as left_weight from purchase_detail as t left join pledge_redeemed as p on p.purchase_id=t.purchase_id';
				$sql.=' and p.brand_id=t.brand_id where t.purchase_id='.$id.' group by brand_id';
				$details=PurchaseDetail::model()->findAllBySql($sql);
				$i=1;
				foreach ($details as $each)
				{
					$tableHeader = array(
							array('name'=>'产地','class' =>"sort-disabled",'width'=>"100px"),
							array('name'=>'托盘总重量','class' =>" sort-disabled text-right",'width'=>"140px"),
							array('name'=>'已赎回总重量','class' =>"sort-disabled text-right",'width'=>"140px"),
							array('name'=>'当前赎回量','class' =>"sort-disabled text-right",'width'=>"140px"),
					);
					$da['data']=array(
							DictGoodsProperty::getProName($each->brand_id),
							number_format($each->sum_weight,3),
							number_format($each->checked_weight,3),
							number_format($each->left_weight,3),
					);
					$da['group']=$i;
					array_push($tableData,$da);
					$i++;
				}				
			}else{
				$sql='select t.brand_id,t.product_id,sum(t.weight) as sum_weight ,p.weight as checked_weight,p.left_weight as left_weight from purchase_detail as t left join pledge_redeemed as p on p.purchase_id=t.purchase_id';
				$sql.=' and p.brand_id=t.brand_id and p.product_id=t.product_id where t.purchase_id='.$id.' group by brand_id,product_id';
				$details=PurchaseDetail::model()->findAllBySql($sql);
				$i=1;
				foreach ($details as $each)
				{
					$tableHeader = array(
							array('name'=>'产地','class' =>"sort-disabled",'width'=>"100px"),
							array('name'=>'品名','class' =>"sort-disabled",'width'=>"100px"),
							array('name'=>'托盘总重量','class' =>" sort-disabled text-right",'width'=>"140px"),
							array('name'=>'已赎回总重量','class' =>"sort-disabled text-right",'width'=>"140px"),
							array('name'=>'当前赎回量','class' =>"sort-disabled text-right",'width'=>"140px"),
					);
					$da['data']=array(
							DictGoodsProperty::getProName($each->brand_id),
							DictGoodsProperty::getProName($each->product_id),
							number_format($each->sum_weight,3),
							number_format($each->checked_weight,3),
							number_format($each->left_weight,3),
					);
					$da['group']=$i;
					array_push($tableData,$da);
					$i++;
				}
			}
			return array($tableHeader,$tableData);
		}
	}
	
	
	public static function gatherData($search)
	{
		$tableHeader = array(
				array('name'=>'采购公司','class' =>"sort-disabled",'width'=>"110px"),//
				array('name'=>'托盘公司','class' =>"sort-disabled",'width'=>"110px"),//
				array('name'=>'托盘件数','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'托盘重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'未赎回重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'已赎回重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'待赎回重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'托盘总金额','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'已付金额','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),//
				array('name'=>'已付利息','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
		);
		$tableData=array();
		$model=FrmPurchase::model();
		$criteria=New CDbCriteria();
		$criteria->with=array('baseform','pledge');
		$criteria->together=true;
		//搜索
		if(!empty($search))
		{
			if($search['company']){
				$criteria->compare('t.title_id',$search['company']);
			}
			if($search['vendor']){
				$criteria->compare('pledge.pledge_company_id', $search['vendor']);
			}
		}
		$criteria->compare('baseform.is_deleted','0');
		$criteria->compare('baseform.form_type','CGD');
		$criteria->addCondition('t.purchase_type="tpcg"');
		$criteria->addCondition('baseform.form_status!="unsubmit"');
		
		$criteria->group='t.title_id,pledge.pledge_company_id';
		$criteria->join='left join pledge_redeemed as p on p.purchase_id=t.id';
		$criteria->select='t.title_id,p.company_id as com ,sum(t.amount) as sum_amount,sum(t.weight) as sum_weight,sum(p.weight) as sump_weight,sum(p.left_weight) as sump_Lweight,sum(pledge.fee) as total_money';
		$pages = new CPagination();
		$pages->itemCount = FrmPurchase::model()->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="baseform.created_at DESC";
		$frmpurs=FrmPurchase::model()->findAll($criteria);
		if($frmpurs)
		{
			$mark=1;
			foreach ($frmpurs as $each)
			{
				$baseform=$each->baseform;
				$pledgeInfo=$each->pledge;
				$pledgeR=$each->pledgeRedeem;
				$cri=new CDbCriteria();
				$cri->compare('baseform.form_status', 'approve');
				$cri->compare('title_id', $each->title_id);
				$cri->compare('company_id', $pledgeInfo->pledge_company_id);
				$cri->group='title_id,company_id';
				$cri->select='sum(total_fee) as fee,sum(interest_fee) as  interest';
				$mo=FrmPledgeRedeem::model()->with('baseform')->find($cri);

				$c=new CDbCriteria();
				$c->compare('baseform.form_status', 'approve');
				$c->compare('t.purchase_type', 'tpcg');
				$c->compare('title_id', $each->title_id);
				$c->compare('pledge.pledge_company_id', $pledgeInfo->pledge_company_id);
				$c->group='title_id,pledge.pledge_company_id';
				$c->select='sum(t.weight) as approve_weight';
				$wait=FrmPurchase::model()->with('baseform','pledge')->find($c);
				
				
				$da['data']=array(
						$each->title->short_name,//
						'<span title="'.$pledgeInfo->pledgeCompany->name.'">'.$pledgeInfo->pledgeCompany->short_name.'</span>',
						$each->sum_amount,
						number_format($each->sum_weight,3),
						number_format($each->sum_weight-$each->sump_weight,3),
						number_format($each->sump_weight,3),
						'<a class="colorbox" style="color:#587ed1;text-decoration:underline;" target="_brank" href="/index.php/pledge/pledgeSearch?title_id='.$each->title_id.'&company_id='.$pledgeInfo->pledge_company_id.'">'.number_format($wait->approve_weight-$each->sump_weight,3).'</a>',
						number_format($each->total_money,2),
						number_format($mo->fee,2),
						number_format($mo->interest,2),
				);
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
				$mark++;
			}
		}
		return array($tableHeader,$tableData,$pages);
	}
	
	/**
	 * 托盘利息均摊
	 * @param integer $id
	 * @param string $type
	 * 均摊利息 = （托盘利息 + 托盘金额 × 剩余重量 / 总重 × 利率 × 天数） / 总重 //有赎回记录
	 * 					利率 × 天数 × 托盘金额 / 总重 //无赎回记录
	 * 托盘利息 = 所有托盘利息
	 * 剩余重量 = 总重 - 所有赎回重量
	 */
	public static function shareEqually($id, $type = '') 
	{
		$form = new Pledge($id);
		if ($form->commonForm->form_status != 'approve') return ;
		
		$pledge = $form->mainInfo; //赎回记录
		$purchase = $pledge->purchase; //采购单
		$pledgeInfo = $pledge->pledgeInfo; //托盘信息
		
		$pledge_rate = floatval($pledgeInfo->pledge_rate) / 1000;//利率
		$pledge_length = intval($pledgeInfo->pledge_length); //天数
		$total_weight = floatval($purchase->weight_confirm_status == 1 ? $purchase->confirm_weight : $purchase->weight); //总重
		
		$pledge_weight = floatval($pledge->weight); //赎回重量
		$pledge_fee = floatval($pledge->pledgeInfo->fee);//托盘总金额
		$interest_fee = floatval($pledge->interest_fee);//托盘利息
		
		if ($total_weight == 0) continue;
		$purchase->pledge_rate = ($interest_fee + $pledge_fee * $pledge_rate * $pledge_length * ($total_weight - $pledge_weight) / $total_weight) / $total_weight;
		$purchase->update();
		ProfitChange::createNew('purchase',$purchase->baseform->id,1);
	}
	
}
