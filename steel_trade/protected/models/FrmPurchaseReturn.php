<?php

/**
 * This is the biz model class for table "frm_purchase_return".
 *
 */
class FrmPurchaseReturn extends FrmPurchaseReturnData
{
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'supply' => array(self::BELONGS_TO, 'DictCompany', 'supply_id'),
			'company' => array(self::BELONGS_TO, 'DictCompany', 'supply_id'),
			'dictTitle' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
			'purchaseReturnDetails' => array(self::HAS_MANY, 'PurchaseReturnDetail', 'purchase_return_id'),
			'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type = 'CGTH'"),
			'contact'=>array(self::BELONGS_TO,'CompanyContact','company_contact_id'),
			'frmOutputs' => array(self::HAS_MANY, 'FrmOutput', 'frm_sales_id','condition'=>'is_return=1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'supply_id' => 'Supply',
			'title_id' => 'Title',
			'warehouse_id' => 'Warehouse',
			'team_id' => 'Team',
			'comment' => 'Comment',
			'travel' => 'Travel',
			'return_data' => 'Return Data',
			'company_contact_id' => 'Company Contact',
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
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('travel',$this->travel,true);
		$criteria->compare('return_data',$this->return_data);
		$criteria->compare('company_contact_id',$this->company_contact_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPurchaseReturn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//采购退货收款 列表
	public static function getFormBillList($search) 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "4%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "供应商", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "仓库", 'class' => "sort-disabled", 'width' => "11%"),
				array('name' => "车船号", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "预计退货时间", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "业务组", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "12%"),
		);
		
		$tableData = array();
		$model = new FrmPurchaseReturn();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		
		//搜索
		if (!empty($search))
		{		
			if ($search['company_id'])
			{
				$criteria->addCondition("supply_id = :supply_id");
				$criteria->params[':supply_id'] = $search['company_id'];
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
			// if ($search['owned_by'])
			// {
			// 	$criteria->addCondition("baseform.owned_by = :owned_by");
			// 	$criteria->params[':owned_by'] = $search['owned_by'];
			// }
		}
		$criteria->compare("baseform.form_type", 'CGTH', true);
		$criteria->compare("baseform.is_deleted", '0', true);
		$criteria->compare("baseform.form_status", "approve", true);
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['bill_list']) ? intval($_COOKIE['bill_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		
		if (!$search['title_id'] || !$search['company_id']) return array($tableHeader, $tableData, $pages);
		$bill =  $model->findAll($criteria);
		if ($bill) 
		{
			$i = 1;
			foreach ($bill as $item) 
			{
				$mark = '';
				$operate = '';
				$da = array('data' => array());
				if ($item->baseform != "") 
				{
					$baseform = $item->baseform;
					$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill"  value="'.$baseform->id.'" />';
					$mark = $i;
					$i++;
				} 
				
				$da['data'] = array($mark, 
						$operate, 
						$baseform->form_sn, 
						$baseform->created_at ? date('Y-m-d', $baseform->created_at) : '', 
						'<span title="'.$item->supply->name.'">'.$item->supply->short_name.'</span>', 
						'<span title="'.$item->warehouse->name.'">'.$item->warehouse->name.'</span>', 
						$item->travel, 
						date('Y-m-d', $item->return_data), 
						$item->team->name, //业务组
						$baseform->belong->nickname, //业务员
				);
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	/**
	 * 创建采购退货单
	 */
	public static function createReturn($post)
	{
		$post['CommonForms']['form_type']='CGTH';
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['PurchaseReturn'];
		$data['detail']=array();
		for($i=0;$i<count($post['product']);$i++)
		{
			$temp=array();
			$temp['product_id']=$post['product'][$i];
			$temp['texture_id']=$post['material'][$i];
			$temp['brand_id']=$post['place'][$i];
			$temp['rank_id']=$post['type'][$i];
			$temp['length']=$post['length'][$i];
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
			echo "<script>alert('没有此类商品');</script>";
				return false;
			}
			$temp['return_amount']=$_POST['td_num'][$i];
			$temp['return_weight']=numChange($_POST['td_total'][$i]);
			$temp['return_price']=numChange($_POST['money'][$i]);
			$temp['card_no']=$_POST['card_id'][$i];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']=(Object)$data['main'];
		return $data;
	}
	
	/*
	 * 获取采购退货单列表
	 */
	public static function getFormList($search)
	{
		$tableData=array();
		$model = new PurchaseReturnDetail();
		$criteria=New CDbCriteria();
		$criteria->with = array("purchaseReturn",'purchaseReturn.baseform'=>array('order'=>'baseform.created_at DESC'));
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('purchaseReturn.title_id',$search['title_id'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('purchaseReturn.supply_id',$search['customer_id'],false);
			}
			if($search['team']!='0')
			{
				$criteria->compare('purchaseReturn.team_id',$search['team'],false);
			}
			if($search['owned']!='0')
			{
				$criteria->compare('baseform.owned_by',$search['owned'],false);
			}
			if($search['warehouse']!='0')
			{
				$criteria->compare('purchaseReturn.warehouse_id',$search['warehouse'],false);
			}
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('t.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('t.product_id',$search['product'],false);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('t.rank_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],false);
			}
			if($search['length']>=0)
			{
				$criteria->compare('t.length',$search['length'],false);
			}
		}
		if($search['form_status'])
		{
			if($search['form_status'] == "complete"){
				$criteria->compare('purchaseReturn.confirm_status',1,false);
			}else{
				$criteria->compare('baseform.form_status',$search['form_status'],false);
				$criteria->compare('purchaseReturn.confirm_status',0,false);
			}
		}else{
			$criteria->compare('baseform.is_deleted','0',false);
		}
		$criteria->compare('baseform.form_type','CGTH',false);
		$c = clone $criteria;
		$cri = clone $criteria;
		$criteria->order = "baseform.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['frmpurchasereturn_list']) ? intval($_COOKIE['frmpurchasereturn_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		$c->select = "sum(t.return_amount) as total_amount,sum(t.return_weight) as total_weight,sum(t.return_weight*t.return_price) as total_money";
		$all = PurchaseReturnDetail::model()->find($c);
		$cri->select = "sum(t.output_amount) as total_amount,sum(t.output_weight) as total_weight,sum(t.output_weight*t.return_price) as total_money";
		$cri->addCondition("purchaseReturn.confirm_status=1");
		$confirm = PurchaseReturnDetail::model()->find($cri);
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		$totaldata["money"] = $all->total_money;
		$totaldata["c_amount"] = $confirm->total_amount;
		$totaldata["c_weight"] = $confirm->total_weight;
		$totaldata["c_money"] = $confirm->total_money;
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审单','delete'=>'已作废');
			$baseform='';
			$i=1;
			foreach ($details as $each)
			{
				$mark=$i;
				$return = $each->purchaseReturn;
				if($each->purchaseReturn->baseform != $baseform){
					$baseform = $each->purchaseReturn->baseform;
					$i++;
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
					$edit_url = Yii::app()->createUrl('FrmPurchaseReturn/update',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,"fpage"=>$_REQUEST['page']));
					$sub_url =  Yii::app()->createUrl('FrmPurchaseReturn/submit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
					$del_url= Yii::app()->createUrl('FrmPurchaseReturn/deleteform',array('id'=>$baseform->id,'last_update'=>$baseform->last_update));
					$checkP_url=Yii::app()->createUrl('FrmPurchaseReturn/check',array('id'=>$baseform->id,'type'=>'pass','last_update'=>$baseform->last_update));
					$checkC_url=Yii::app()->createUrl('FrmPurchaseReturn/check',array('id'=>$baseform->id,'type'=>'cancle','last_update'=>$baseform->last_update));
					$complete_url = Yii::app()->createUrl('FrmPurchaseReturn/complete',array('id'=>$baseform->id,'last_update'=>$baseform->last_update));
					$cancelcomplete_url = Yii::app()->createUrl('FrmPurchaseReturn/cancelcomplete',array('id'=>$baseform->id,'last_update'=>$baseform->last_update));
					$distribution_url = Yii::app()->createUrl('FrmSend/index',array('id'=>$each->purchase_return_id,"fpage"=>$_REQUEST['page'],"type"=>"return"));
					$checkD_url=Yii::app()->createUrl('FrmPurchaseReturn/check',array('id'=>$baseform->id,'type'=>'deny','last_update'=>$baseform->last_update));
					$print_url = Yii::app()->createUrl('print/print', array('id' => $baseform->id));
					if($each->output_amount > 0){
						$output_url = Yii::app()->createUrl('FrmOutput/index',array('id'=>$each->purchase_return_id,"fpage"=>$_REQUEST['page'],"type"=>"return"));
					}else{
						$output_url = Yii::app()->createUrl('FrmOutput/rtcreate',array('id'=>$each->purchase_return_id,"fpage"=>$_REQUEST['page'],"type"=>"return"));
					}
					$detail_url = Yii::app()->createUrl('FrmPurchaseReturn/detail',array('id'=>$baseform->id,"fpage"=>$_REQUEST['page']));
					//未完成
					$operate='';
					if($return->confirm_status == 0){
						//未提交
						if($baseform->form_status=='unsubmit'){
							if(checkOperation("采购退货单:新增")){
								$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">'
										.'<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>'
										.'<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>'
										.'<span class="delete_form" lastdate="'.$baseform->last_update.'" title="作废" id="/index.php/FrmPurchaseReturn/deleteform/'.$baseform->id.'" onclick="deleteIt(this);"><img src="/images/zuofei.png"></span>';
									if (checkOperation("打印")) {
										$operate.='<a target="_blank" href="'.$print_url.'" title="打印"><span><img src="/images/dayin.png"></span></a>';
									}
								$operate.='</div>';
							}else{
								$operate='<div class="cz_list_btn">';
								if (checkOperation("打印")) {
									$operate.='<a target="_blank" href="'.$print_url.'" title="打印"><span><img src="/images/dayin.png"></span></a>';
								}
								$operate.='</div>';
							}
						}
						//已提交
						if($baseform->form_status=='submited'){
							if(checkOperation("采购退货单:审核")){
								$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">'
										.'<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>'
										.'<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>'
										.'<span class="check_form" lastdate="'.$baseform->last_update.'" id="/index.php/FrmPurchaseReturn/check/'.$baseform->id.'" title="审核" str="'."退货单号为:".$baseform->form_sn.'" onclick="setCheck(this);"><img src="/images/check.png"></span>';
								if (checkOperation("打印")) {
									$operate.='<a target="_blank" href="'.$print_url.'" title="打印"><span><img src="/images/dayin.png"></span></a>';
								}
								$operate.='</div>';
							}else{
								$operate='<div class="cz_list_btn">';
								if (checkOperation("打印")) {
									$operate.='<a target="_blank" href="'.$print_url.'" title="打印"><span><img src="/images/dayin.png"></span></a>';
								}
								$operate.='</div>';
							}
						}
						//已审核
						if($baseform->form_status=='approve'){
							$but_num = 0;
							$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
							if(checkOperation("采购退货单:新增")){
								$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("采购退货单:审核")){
								$operate.='<span class="submit_form" url="'.$checkC_url.'" title="取消审核"><img src="/images/qxsh.png"></span><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("出库单:新增")){
								$operate.='<a class="update_b" href="'.$output_url.'" title="出库"><span><img src="/images/chuku.png"></span></a><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("采购退货单:审单")){
								$operate.='<span class="submit_form" url="'.$complete_url.'" title="完成"><img src="/images/wancheng.png"></span><abc></abc>';
								$but_num ++;
							}
							if(checkOperation("打印")){
								$operate.='<a target="_blank" href="'.$print_url.'" title="打印"><span><img src="/images/dayin.png"></span></a><abc></abc>';
								$but_num ++;
							}

							if($but_num > 4){
								$one=substr($operate,strpos($operate,'<abc></abc>')+11);
								$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
								$two=substr($one,strpos($one,'<abc></abc>')+11);
								$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
								$three=substr($two,strpos($two,'<abc></abc>')+11);
								$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
								$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
									.'<div class="cz_list_btn_more" style="width:120px" num="0">'.$three;							
								$operate.='</div></div>';
							}else{
								$operate.='</div>';
							}
						}
					}else{
						$but_num = 0;
						$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
						if(checkOperation("采购退货单:审单")){
							$operate.='<span class="submit_form" url="'.$cancelcomplete_url.'" title="取消完成"><img src="/images/qxwc.png"></span><abc></abc>';
							$but_num ++;
						}
						if(checkOperation("打印")){
							$operate.='<a target="_blank" href="'.$print_url.'" title="打印"><span><img src="/images/dayin.png"></span></a><abc></abc>';
							$but_num ++;
						}
						$operate.='</div>';
					}
				}else{
					$mark='';
					$operate='';
				}
				$price = $each->return_weight * $each->return_price;
				$da['data']=array(
						$mark,
						$operate,
						'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$baseform->form_sn.'</a>',
						$baseform->form_time,
						$return->dictTitle->short_name,
						$return->supply->short_name,
						$return->warehouse->name,
						$each->storage->card_no,
						DictGoodsProperty::getProName($each->brand_id),
						DictGoodsProperty::getProName($each->product_id),
						str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)),
						DictGoodsProperty::getProName($each->rank_id),
						$each->length,
						$each->return_amount,
						$each->output_amount,
						number_format($each->return_weight,3),
						'<span class="'.($return->is_yidan?'red':'').'">'.number_format($price,2).'</span>',
						$return->is_yidan == 1?"是":"",
// 						$each->send_amount,
// 						$each->output_amount,
						$_status[$baseform->form_status],
						$baseform->operator->nickname,
						$baseform->lastupdate->nickname,
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment,0,15,"UTF-8").'</span>',
				);
				if($baseform->form_status == "delete"){
					array_push($da['data'],'<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason,0,15,"UTF-8").'</span>');
				}
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	}
	
	/*
	 * 出库单获取采购退货单列表
	 */
	public static function getReturnFormList($search)
	{
		$tableData=array();
		$model = new FrmPurchaseReturn();
		$criteria=New CDbCriteria();
		$criteria->with = array("baseform");
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('t.title_id',$search['title_id'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('t.supply_id',$search['customer_id'],false);
			}
			if($search['team']!='0')
			{
				$criteria->compare('t.team_id',$search['team'],false);
			}
			if($search['warehouse']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse'],false);
			}
		}
		$criteria->compare('t.confirm_status',0,false);
		$criteria->compare('baseform.form_type','CGTH',false);
		$criteria->compare('baseform.form_status','approve',false);
		$criteria->order = "baseform.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['frmpurchasereturn_list']) ? intval($_COOKIE['frmpurchasereturn_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$i=1;
			$totaldata = array();
			foreach ($details as $each)
			{
				$baseform = $each->baseform;
				$da['data']=array(
						$i,
						'<input type="radio" name="selected_contract"  class="selected_contract"  value="'.$each->id.'" />',
						$baseform->form_sn,
						$baseform->form_time,
						$each->dictTitle->short_name,
						$each->supply->short_name,
						$each->team->name,
						$each->warehouse->name,
				);
				$da['group']=$i;
				$i++;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	}
	/**
	 * 修改采购退货单
	 */
	public static function getUpdateData($post)
	{
		$post['CommonForms']['form_type']='CGTH';
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['PurchaseReturn'];
		if(!$data['main']['is_yidan']){
			$data['main']['is_yidan'] = 0;
		}
		$data['detail']=array();
		for($i=0;$i<count($post['product']);$i++)
		{
			$temp=array();
			$temp['product_id']=$post['product'][$i];
			$temp['texture_id']=$post['material'][$i];
			$temp['brand_id']=$post['place'][$i];
			$temp['rank_id']=$post['type'][$i];
			$temp['length']=$post['length'][$i];
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
			echo "<script>alert('没有此类商品');</script>";
					return false;
			}
			$temp['return_amount']=$_POST['td_num'][$i];
			$temp['pre_amount']=$_POST['td_num'][$i];
			$temp['return_weight']=numChange($_POST['td_total'][$i]);
			$temp['return_price']=numChange($_POST['money'][$i]);
			$temp['card_no']=$_POST['card_id'][$i];
			$temp['id']=$_POST['detail_id'][$i];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']=(Object)$data['main'];
		return $data;
	}
	
	/*
	 * 根据退货单id获取退货信息
	 */
	public static function getReturnData($id){
		$model = FrmPurchasereturn::model()->findByPk($id);
		if($model)
		{
			$return['company_name']=$model->supply->short_name;
			$return['title_name']=$model->dictTitle->short_name;
			$return['warehouse_id']=$model->warehouse_id;
			$return['warehouse']=$model->warehouse->name;
		}
		return json_encode($return);
	}
	
	/*
	 * 获取库存锁库采购退货列表
	 */
	public static function getLockList($search,$storage_id)
	{
		$merge = MergeStorage::model()->findByPk($storage_id);
		$storage = new Storage();
		$cri=New CDbCriteria();
		$cri->with = array("inputDetail");
		$cri->addCondition('inputDetail.product_id ='.$merge->product_id);
		$cri->addCondition('inputDetail.brand_id ='.$merge->brand_id);
		$cri->addCondition('inputDetail.texture_id ='.$merge->texture_id);
		$cri->addCondition('inputDetail.rank_id ='.$merge->rank_id);
		$cri->addCondition('inputDetail.length ='.$merge->length);
		$cri->addCondition('t.title_id ='.$merge->title_id);
		$cri->addCondition('is_dx = 0');
		$cri->addCondition('is_deleted = 0');
		$storage = $storage->findAll($cri);
		$idlist="";
		if($storage){
			foreach ($storage as $li){
				$idlist.=$li->id.",";
			}
		}
		if(strlen($idlist)>1){
			$idlist = substr($idlist,0,strlen($idlist)-1);
		}
		$tableData=array();
		$model = new PurchaseReturnDetail();
		$criteria=New CDbCriteria();
		$criteria->with = array("purchaseReturn",'purchaseReturn.baseform'=>array('order'=>'baseform.created_at DESC'));
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
		}
		$criteria->addCondition('baseform.form_status<>"unsubmit"');
		$criteria->addCondition('t.card_no in ('.$idlist.')');
		$criteria->compare('purchaseReturn.confirm_status',0,false);
		$criteria->compare('baseform.is_deleted','0',false);
		$criteria->compare('baseform.form_type','CGTH',false);
		$criteria->order = "baseform.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['frmpurchasereturn_list']) ? intval($_COOKIE['frmpurchasereturn_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审单','delete'=>'已作废');
			$i=1;
			$totaldata = array();
			foreach ($details as $each)
			{
				$return = $each->purchaseReturn;
				$baseform = $return->baseform;
				$price = $each->return_weight * $each->return_price;
				$da['data']=array(
						$i,
						'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$baseform->form_sn.'</a>',
						$baseform->form_time,
						$return->dictTitle->short_name,
						$return->supply->short_name,
						$return->warehouse->name,
						$each->storage->card_no,
						DictGoodsProperty::getProName($each->brand_id),
						DictGoodsProperty::getProName($each->product_id),
						str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)),
						DictGoodsProperty::getProName($each->rank_id),
						$each->length,
						$each->return_amount,
						$each->return_amount - $each->output_amount,
						$each->output_amount,
						$each->return_weight,
						number_format($price,2),
						$_status[$baseform->form_status],
						$baseform->operator->nickname,
						$baseform->lastupdate->nickname,
						$baseform->comment,
				);
				$da['group']=$i;
				$i++;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	}
}
