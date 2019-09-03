<?php

/**
 * This is the biz model class for table "frm_purchase".
 *
 */
class FrmPurchase extends FrmPurchaseData
{
	public $total_weight,$total_price, $total_num,$total_amount,$total_money;
	public $sum_amount,$sum_weight;
	public $sump_weight,$sump_Lweight,$com,$approve_weight;
	public static $type = array('normal' => "库存采购", 'tpcg' => "托盘采购", 'xxhj' => "直销采购", 'dxcg' => "代销采购");

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			"pledge"=>array(self::HAS_ONE,"PledgeInfo","frm_purchase_id"),
			'purchaseDetails'=>array(self::HAS_MANY,'PurchaseDetail','purchase_id'),
			'supply' => array(self::BELONGS_TO, 'DictCompany', 'supply_id'),
			'company' => array(self::BELONGS_TO, 'DictCompany', 'supply_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
			'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="CGD" and baseform.is_deleted=0'),
			'baseform_pur'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform_pur.form_type="CGD" and baseform_pur.is_deleted=0'),
			'contract_baseform'=>array(self::BELONGS_TO,'CommonForms','frm_contract_id'),
			'contact'=>array(self::BELONGS_TO,'CompanyContact','contact_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'salesdetail_pur'=>array(self::HAS_MANY,'SaledetailPurchase','purchase_id'),
			'pledgeRedeem'=>array(self::HAS_MANY,'PledgeRedeemed','purchase_id'),//已赎回
			'frmPledgeRedeem'=>array(self::HAS_MANY,'FrmPledgeRedeemed','purchase_id'),//赎回记录
			'purchaseDetailOne'=>array(self::HAS_ONE,'PurchaseDetail','purchase_id'),//针对自动创建数据，有且仅有一条明细
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'purchase_type' => 'Purchase Type',
			'supply_id' => 'Supply',
			'title_id' => 'Title',
			'team_id' => 'Team',
			'is_yidan' => 'Is Yidan',
			'contack' => 'Contack',
			'phone' => 'Phone',
			'warehouse_id' => 'Warehouse',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'weight_confirm_status' => 'Weight Confirm Status',
			'price_confirm_status' => 'Price Confirm Status',
			'invoice_cost' => 'Invoice Cost',
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
		$criteria->compare('purchase_type',$this->purchase_type,true);
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('contack',$this->contack,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('input_weight',$this->input_weight);
		$criteria->compare('weight_confirm_status',$this->weight_confirm_status);
		$criteria->compare('price_confirm_status',$this->price_confirm_status);
		$criteria->compare('invoice_cost',$this->invoice_cost);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPurchase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 获取采购单详细列表
	 */
	public static function getPurchseList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),
				array('name'=>'单号','class' =>"sort-disabled",'width'=>"80px"),				
				array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"48px"),//				
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"78px"),				
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"60px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'乙单','class' =>"flex-col sort-disabled",'width'=>"36px"),
				array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"48px"),//
				array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"36px"),//
				array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'长度','class' =>"flex-col sort-disabled text-right",'width'=>"36px"),//
				array('name'=>'单价','class' =>"flex-col sort-disabled text-right",'width'=>"50px"),//
				array('name'=>'件数','class' =>"flex-col sort-disabled text-right",'width'=>"50px"),//
				array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//总重量
				array('name'=>'金额','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'发票成本','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//
				array('name'=>'托盘单价','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//托盘单价
// 				array('name'=>'托盘','class' =>"flex-col sort-disabled",'width'=>"50px"),
				array('name'=>'预计到货时间','class' =>"flex-col sort-disabled",'width'=>"160px"),
				array('name'=>'入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//仓库入库件数
				array('name'=>'入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//仓库入库重量
				array('name'=>'核定价格','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//核定价格
				array('name'=>'核定件数','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//核定总件数
				array('name'=>'核定重量','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//核定总重量
				
				array('name'=>'核定金额','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//核定总金额	
				array('name'=>'审单','class' =>"flex-col sort-disabled",'width'=>"55px"),//
				array('name'=>'是否已销票','class' =>"flex-col sort-disabled",'width'=>"70px"),
				array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"60px"),			
				array('name'=>'采购合同','class' =>"flex-col sort-disabled",'width'=>"130px"),
				array('name'=>'托盘公司','class' =>"flex-col sort-disabled",'width'=>"70px"),				
				array('name'=>'托盘金额','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//托盘金额			
				array('name'=>'审核人','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'审核时间','class' =>"flex-col sort-disabled",'width'=>"80px"),//				
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"70px"),//
				
				array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'制单人','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'最后操作人','class' =>"flex-col sort-disabled",'width'=>"70px"),//				
				array('name'=>'备注','class' =>"flex-col sort-disabled",'width'=>"230px"),//		
				
			);
		if($search['form_status']=='delete')
		{
			$reason=array('name'=>'作废原因','class' =>"flex-col sort-disabled",'width'=>"230px");
			array_push($tableHeader, $reason);
			array_splice($tableHeader, 1,1);
		}
		$tableData=array();
		$model=PurchaseView::model();
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			//销售单号处理
			$keywords=trim($search['keywords']);
			if($keywords)
			{
				if(substr($keywords, 0,2)=='XD')
				{
					$sql='select group_concat(id) as id from common_forms where form_sn like "%'.$keywords.'%"';
					$id=CommonForms::model()->findBySql($sql)->id;
					if($id){
						$criteria->addCondition('frm_contract_id in ('.$id.')');
					}else{
						$criteria->addCondition('frm_contract_id in (-1)');
					}
				}else{
					$criteria->addCondition('form_sn like :contno or comment like :contno');
					$criteria->params[':contno']= "%".$keywords."%";
				}				
			}			
			if($search['time_L']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(form_time) >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(form_time) <'.(strtotime($search['time_H'])+86400));
			}
			if($search['reach_time_L'])
			{
				$criteria->addCondition('date_reach >='.strtotime($search['reach_time_L']));
			}
			if($search['reach_time_H'])
			{
				$criteria->addCondition('date_reach <='.strtotime($search['reach_time_H']));
			}
			if($search['company']!='0')
			{
				$criteria->compare('title_id',$search['company']);
			}
			if($search['vendor']!='0')
			{
				$criteria->compare('supply_id',$search['vendor']);
			}
			if($search['form_status']!='0')
			{
				$criteria->compare('form_status',$search['form_status']);
			}else{
				$criteria->compare('is_deleted','0');
			}
// 			if($search['team']!='0')
// 			{
// 				$criteria->compare('team_id',$search['team']);
// 			}
// 			if($search['owned']!='0')
// 			{
// 				$criteria->compare('owned_by',$search['owned']);
// 			}
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('brand_id',$search['brand']);
			}
			if($search['product']!='0')
			{
				$criteria->compare('product_id',$search['product']);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('rank_id',$search['rand']);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('texture_id',$search['texture']);
			}
			if($search['length']>=0)
			{
				$criteria->compare('t.length',$search['length']);
			}
			//审单状态，采购单类型，乙单
			if($search['confirm_status']!='')
			{
				$criteria->compare('weight_confirm_status', $search['confirm_status']);				
			}
			if($search['purchase_type']!='')
			{
				$criteria->compare('purchase_type', $search['purchase_type']);
			}
			if($search['is_yidan']!='')
			{
				$criteria->compare('is_yidan', $search['is_yidan']);
			}
			if($search['contract'])
			{
				$criteria->compare('contract_no', $search['contract'],true);
			}
			if($search['contract_array'])
			{
				$criteria->addInCondition('frm_contract_id',$search['contract_array']);
				$criteria->compare('form_status', 'approve');
			}
		}else{
			$criteria->compare('is_deleted','0');
		}
		$user=Yii::app()->user->userid;
		$criteria->addCondition('owned_by ='.$user.' or created_by ='.$user);
		$criteria->compare('form_type','CGD');
		$newcri=clone $criteria;
		$newcri->select = "sum(detail_amount) as total_amount,sum(detail_weight) as total_weight,sum(detail_weight*detail_price) as total_money,count(*) as total_num";
		$all=PurchaseView::model()->find($newcri);
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		$totaldata["money"] = $all->total_money;
		$totaldata["total_num"] = $all->total_num;
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="created_at DESC,main_id";
// 		
		$details=PurchaseView::model()->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$_type=array('normal'=>"库存采购",'tpcg'=>"托盘采购","xxhj"=>"直销采购","dxcg"=>"代销采购");
			$_time=array('6'=>'00:00-06:00','12'=>'06:00-12:00','18'=>'12:00-18:00','24'=>'18:00-24:00');
			$baseform='';
			$fees=0;
			$i=1;	
			foreach ($details as $each)
			{
				$mark=$i;
				if($each->form_sn!=$baseform)
				{
					$baseform=$each->form_sn;
					$i++;
					$title_sub='';
					if($each->purchase_type=='dxcg')
					{
						$edit_url = Yii::app()->createUrl('purchase/updateDxcg',array('id'=>$each->common_id,'last_update'=>$each->last_update,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'purchase/index','search_url'=>json_encode($search)));
					}else{
						$edit_url = Yii::app()->createUrl('purchase/update',array('id'=>$each->common_id,'last_update'=>$each->last_update,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'purchase/index','search_url'=>json_encode($search)));
					}
					
					if($each->form_status=='unsubmit')
					{
						$type_sub="submit";
						$title_sub="提交";
						$img_url = "/images/tijiao.png";
					}elseif($each->form_status=='submited')
					{
						$type_sub="cancle";
						$title_sub="取消提交";
						$img_url = "/images/qxtj.png";
					}					
					$sto_url = Yii::app()->createUrl('input/create',array('id'=>$each->common_id,'type'=>'purchase','last_update'=>$each->last_update));
					$sub_url =  Yii::app()->createUrl('purchase/submit',array('id'=>$each->common_id,'type'=>$type_sub,'last_update'=>$each->last_update));
					$del_url= Yii::app()->createUrl('purchase/deleteform',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					$checkP_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'pass','last_update'=>$each->last_update));
					$checkD_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'deny','last_update'=>$each->last_update));
					$checkC_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'cancle','last_update'=>$each->last_update));
					$confirm_url=Yii::app()->createUrl('purchase/confirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
					$br_url = Yii::app()->createUrl("billRecord/index", array('frm_common_id' => $each->common_id, "fpage"=>$_REQUEST['page']));
					$fk_url = Yii::app()->createUrl("formBill/create", array('type' => "FKDJ", 'bill_type' => "CGFK", 'common_id' => $each->common_id));					
					$detail_url=Yii::app()->createUrl('purchase/view',array('id'=>$each->common_id,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'search_url'=>json_encode($search),'backUrl'=>'purchase/index'));
					$print_url = Yii::app()->createUrl('print/print', array('id' => $each->common_id));
					$num=0;
					$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
					if (checkOperation("打印")) {
						$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
						$num++;
					}
					//未提交
					if($each->form_status=='unsubmit')
					{
						if(checkOperation("采购单:新增"))
						{
							$num+=3;
							$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span><abc></abc>';//提交
							$operate.='<span class="delete_form" thisid="'.$each->common_id.'" url="'.$del_url.'" title="作废"><span><img src="/images/zuofei.png"></span></span><abc></abc>';
							$operate.='<a class="update_b update_button" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a><abc></abc>';							
						}													
					}					
					//已提交
					if($each->form_status=='submited')
					{
						if(checkOperation('采购单:推送'))
						{
							if($each->can_push)
							{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'close','last_update'=>$each->last_update));
								$title_p='取消可推送';
								$image_p='/images/bkts.png';
							}else{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'open','last_update'=>$each->last_update));
								$title_p='可推送';
								$image_p='/images/kts.png';
							}
							$operate.='<span class="submit_form" url="'.$can_push.'" title="'.$title_p.'"><img src="'.$image_p.'"></span><abc></abc>';
							$num++;
						}
						if(checkOperation("采购单:新增"))
						{
							$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span><abc></abc>';//提交
							$operate.='<a class="update_b update_button" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a><abc></abc>';
							$num+=2;
							if(checkOperation("采购单:审核"))
							{
								$num++;
								$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$each->form_sn.',确定审核通过此采购单吗？"><img src="/images/shenhe.png"></span><abc></abc>';
							}
						}else{
							if(checkOperation("采购单:审核"))
							{					
								$num++;
								$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$each->form_sn.',确定审核通过此采购单吗？"><img src="/images/shenhe.png"></span><abc></abc>';
							}
						}					
					}				
					//已审核	
					if($each->form_status=='approve')
					{
						if(checkOperation('采购单:推送'))
						{
							if($each->can_push)
							{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'close','last_update'=>$each->last_update));
								$title_p='取消可推送';
								$image_p='/images/bkts.png';
							}else{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'open','last_update'=>$each->last_update));
								$title_p='可推送';
								$image_p='/images/kts.png';
							}
							$operate.='<span class="submit_form" url="'.$can_push.'" title="'.$title_p.'"><img src="'.$image_p.'"></span><abc></abc>';
							$num++;
						}
						if(checkOperation("付款登记:新增"))
						{
							$pay_url = Yii::app()->createUrl('formBill/create', array('type' => "FKDJ", 'bill_type' => $each->purchase_type == 'tpcg' ? "DLFK" : "CGFK", 'common_id' => $each->common_id, 'fpage' => $_REQUEST['page']));
							$operate.='<a class="update_b" href="'.$pay_url.'" title="付款"><span><img src="/images/fukuan.png"></span></a><abc></abc>';
							$num++;
						}						
						if(checkOperation("采购运费:新增"))
						{
							$operate.='<a class="update_b" href="'.$br_url.'" title="运费登记"><span><img src="/images/yfdj.png"></span></a><abc></abc>';
							$num++;
						}
						if(checkOperation("采购单:新增"))
						{
							$operate.='<a class="update_b update_button" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'" href="'.$edit_url.'" title="编辑"><span class=""><img src="/images/bianji.png"></span></a><abc></abc>';
							$num++;				
							if($each->purchase_type!='xxhj'&&$each->purchase_type!='dxcg')
							{
								$plan=FrmInputPlan::model()->find('purchase_id='.$each->common_id.' and input_status != 4');
								if($plan)
								{
									$num++;
									$plan_url=Yii::app()->createUrl('inputPlan/index',array('search_dan'=>$each->form_sn));
									$operate.='<span><a class="update_b" href="'.$plan_url.'" title="查看入库计划"><span><img src="/images/rkjh.png"></span></a></span><abc></abc>';
								}elseif($each->weight_confirm_status==0&&$each->main_amount-$each->main_input_amount>0){
									$num++;
									$plan_url=Yii::app()->createUrl('inputPlan/create',array('purchase_common_id'=>$each->common_id));
									$operate.='<span><a class="update_b" href="'.$plan_url.'" title="创建入库计划"><span><img src="/images/rkjh.png"></span></a></span><abc></abc>';
								}								
								//查找船舱入库单
								$ccrk=FrmInput::model()->with('baseform')->find('input_type="ccrk" and purchase_id='.$each->common_id.' and baseform.is_deleted=0');
								if($ccrk)
								{//跳转列表
									$num++;
									$ccrk_url=Yii::app()->createUrl('inputCcrk/index',array('search_dan'=>$each->form_sn));
									$operate.='<span><a class="update_b" href="'.$ccrk_url.'" title="查看船舱入库" ><span><img src="/images/ccrk.png"></span></a></span><abc></abc>';
								}elseif($each->weight_confirm_status==0&&$each->main_amount-$each->main_input_amount>0){
									//跳转新建船舱入库计划
									$num++;
									$ccrk_url=Yii::app()->createUrl('inputPlan/create',array('purchase_common_id'=>$each->common_id,'type'=>'ccrk'));
									$operate.='<span><a class="update_b" href="'.$ccrk_url.'" title="创建船舱入库"><span><img src="/images/ccrk.png"></span></a></span><abc></abc>';
								}
							}
						}						
						if($each->weight_confirm_status==1&&checkOperation("采购单:审单"))
						{
							$num++;
							$confirm_url=Yii::app()->createUrl('purchase/cancelConfirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
							$operate.='<span class="submit_form" url="'.$confirm_url.'" title="取消审单"><img src="/images/qxsd.png"></span><abc></abc>';
						}else{
							if(checkOperation("采购单:审单"))
							{
								$num++;
								$confirm_url=Yii::app()->createUrl('purchase/confirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
								$operate.='<span><a class="update_b confirm_link" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'" href="'.$confirm_url.'" title="审单"><span><img src="/images/shendan.png"></span></a></span><abc></abc>';
							}
							if(checkOperation("采购单:审核"))
							{
								$num++;
								$operate.='<span class="cancelcheck_form" thisid="'.$each->common_id.'" url="'.$checkC_url.'" title="取消审核" str="确定要取消审核采购单'.$each->form_sn.'吗？"><img src="/images/qxsh.png"></span><abc></abc>';
							}							
							if($each->purchase_type!="dxcg"&&$each->main_input_amount<$each->main_amount&&$each->purchase_type!="xxhj"&&checkOperation("入库单:新增"))
							{
								$num++;
								$operate.='<a class="update_b" href="'.$sto_url.'" title="入库"><span><img src="/images/ruku.png"></span></a><abc></abc>';
							}								
						}										
					}
					if($num>4)
					{
						$one=substr($operate,strpos($operate,'<abc></abc>')+11);
						$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
						$two=substr($one,strpos($one,'<abc></abc>')+11);
						$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
						$three=substr($two,strpos($two,'<abc></abc>')+11);
						$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
						$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
								.'<div class="cz_list_btn_more" style="height:70px;width:90px;" num="0">'.$three;
						$operate.='</div></div>';
					}else{
						$operate.='</div>';
					}
				}else{
					$mark='';
					$operate='';
				}
				$da['data']=array($mark,
								$operate,
								'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$each->form_sn.'</a>',
								'<span class="'.($each->form_status!='approve'?'red':'').'">'.$_status[$each->form_status].'</span>',//审批状态
								$each->form_time?$each->form_time:'',								
								'<span title="'.$each->supply_name.'">'.$each->supply_short_name.'</span>',
								$each->title_short_name,
								$each->is_yidan==1?'是':'',
								$each->product_name,
								$each->rank_name,
								str_replace('E', '<span class="red">E</span>',$each->texture_name),
								$each->brand_name,
								$each->length,
								number_format($each->detail_price),
								$each->detail_amount,
								number_format($each->detail_weight,3),//总重量
								'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_price*$each->detail_weight,2).'</span>',
								$each->detail_invoice_price,//发票成本
								$each->purchase_type=="tpcg"?number_format($each->pledge_unit_price):'0',//托盘单价
								($each->date_reach>943891200)?date('Y-m-d',$each->date_reach).'&nbsp;&nbsp;'.$_time[$each->reach_time]:'',
								$each->detail_input_amount,//仓库入库件数
								number_format($each->detail_input_weight,3),//仓库入库重量
								number_format($each->detail_fix_price),
								$each->detail_fix_amount,//核定总件数
								number_format($each->detail_fix_weight,3),//核定总重量
								'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_fix_price*$each->detail_fix_weight,2).'</span>',//核定总金额						
								$each->weight_confirm_status==0?'未审单':'已审单',
								$each->bill_done==0?'否':'是',
								$_type[$each->purchase_type],//类型												
								$each->contract_no,
								$each->purchase_type=="tpcg"?'<span title="'.$each->pledge_name.'">'.$each->pledge_short_name.'</span>':'',//托盘公司					
								$each->purchase_type=="tpcg"?number_format($each->pledge_fee,2):'0.00',//托盘金额
								$each->form_status=='approve'?$each->approved_by_nickname:'',
								$each->form_status=='approve'?date('Y-m-d',$each->approved_at):'',								
								$each->team_name,//业务组
								
								$each->owned_by_nickname,//业务员
								$each->created_by_nickname,//创建人
								$each->last_updated_by?$each->last_updated_by_nickname:$each->created_by_nickname,//修改人
								'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment, 0,18,"utf-8").'</span>',
				);
				if($search['form_status']=='delete'){
					$re='<span title="'.htmlspecialchars($each->delete_reason).'">'.mb_substr($each->delete_reason, 0,15,"utf-8").'</span>';
					array_push($da['data'], $re);
					array_splice($da['data'], 1,1);
				}
				$da['group']=$each->form_sn;
				array_push($tableData,$da);
			}
		}
		return array($tableHeader,$tableData,$pages,$totaldata);
	}
	
	
	/*
	 * 入库相关列表
	 */
	public static function getPurchseListForStore($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),
				array('name'=>'单号','class' =>"sort-disabled",'width'=>"80px"),
				array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"48px"),//
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"78px"),
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"60px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"48px"),//
				array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"36px"),//
				array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'长度','class' =>"flex-col sort-disabled text-right",'width'=>"36px"),//
				array('name'=>'件数','class' =>"flex-col sort-disabled text-right",'width'=>"36px"),//
				array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//总重量
				array('name'=>'预计到货时间','class' =>"flex-col sort-disabled",'width'=>"160px"),
				array('name'=>'计划入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"85px"),//
				array('name'=>'入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//仓库入库件数
				array('name'=>'入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//仓库入库重量
				array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"70px"),
				array('name'=>'审核人','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'审核时间','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"70px"),//

				array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'制单人','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'最后操作人','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'备注','class' =>"flex-col sort-disabled",'width'=>"230px"),//
		);
		if($search['form_status']=='delete')
		{
			$reason=array('name'=>'作废原因','class' =>"flex-col sort-disabled",'width'=>"230px");
			array_push($tableHeader, $reason);
			array_splice($tableHeader, 1,1);
		}
		$tableData=array();
		$model=PurchaseView::model();
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			//销售单号处理
			$keywords=trim($search['keywords']);
			if($keywords)
			{
				if(substr($keywords, 0,2)=='XD')
				{
					$sql='select group_concat(id) as id from common_forms where form_sn like "%'.$keywords.'%"';
					$id=CommonForms::model()->findBySql($sql)->id;
					if($id){
						$criteria->addCondition('frm_contract_id in ('.$id.')');
					}else{
						$criteria->addCondition('frm_contract_id in (-1)');
					}
				}else{
					$criteria->addCondition('form_sn like :contno or comment like :contno');
					$criteria->params[':contno']= "%".$keywords."%";
				}
			}
// 			$criteria->addCondition('t.form_sn like :contno or comment like :contno');
// 			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(form_time) >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(form_time) <'.(strtotime($search['time_H'])+86400));
			}
			if($search['reach_time_L'])
			{
				$criteria->addCondition('t.date_reach >='.strtotime($search['reach_time_L']));
			}
			if($search['reach_time_H'])
			{
				$criteria->addCondition('t.date_reach <='.strtotime($search['reach_time_H']));
			}
			if($search['company']!='0')
			{
				$criteria->compare('t.title_id',$search['company']);
			}
			if($search['vendor']!='0')
			{
				$criteria->compare('t.supply_id',$search['vendor']);
			}
			if($search['form_status']!='0')
			{
				$criteria->compare('t.form_status',$search['form_status']);
			}else{
				$criteria->compare('t.is_deleted','0');
// 				$criteria->compare('form_status', 'approve');
			}
			if($search['owned']!='0')
			{
				$criteria->compare('t.owned_by',$search['owned']);
			}
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('t.brand_id',$search['brand']);
			}
			if($search['product']!='0')
			{
				$criteria->compare('t.product_id',$search['product']);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('t.rank_id',$search['rand']);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture']);
			}
			if($search['length']>=0)
			{
				$criteria->compare('t.length',$search['length']);
			}
			//审单状态，采购单类型，乙单
			if($search['confirm_status']!='')
			{
				$criteria->compare('t.weight_confirm_status', $search['confirm_status']);				
			}
			if($search['purchase_type']!='')
			{
				$criteria->compare('t.purchase_type', $search['purchase_type']);
			}
			if($search['is_yidan']!='')
			{
				$criteria->compare('t.is_yidan', $search['is_yidan']);
			}
			if($search['contract'])
			{
				$criteria->compare('t.contract_no', $search['contract'],true);
			}
			if($search['contract_array'])
			{
				$criteria->addInCondition('t.frm_contract_id',$search['contract_array']);
				$criteria->compare('t.form_status', 'approve');
			}
		}else{
			$criteria->compare('t.is_deleted','0');
// 			$criteria->compare('form_status', 'approve');
		}
// 		$user=Yii::app()->user->userid;
// 		$criteria->addCondition('owned_by ='.$user.' or created_by ='.$user);
		$criteria->compare('t.form_type','CGD');
		$newcri=clone $criteria;
		$newcri->select = "sum(detail_amount) as total_amount,sum(detail_weight) as total_weight,sum(detail_weight*detail_price) as total_money,count(*) as total_num";
		$all=PurchaseView::model()->find($newcri);
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		$totaldata["money"] = $all->total_money;
		$totaldata["total_num"] = $all->total_num;
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="locate(form_status,'submited,approve,unsubmit,,delete'),created_at DESC,main_id";
		$criteria->join='LEFT JOIN input_detail_plan as input on input.purchase_detail_id=t.detail_id';
		$criteria->select='t.*,sum(input.input_amount) as total_num';
		$criteria->group='t.detail_id';
// 		
		$details=PurchaseView::model()->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$_type=array('normal'=>"库存采购",'tpcg'=>"托盘采购","xxhj"=>"直销采购","dxcg"=>"代销采购");
			$_time=array('6'=>'00:00-06:00','12'=>'06:00-12:00','18'=>'12:00-18:00','24'=>'18:00-24:00');
			$baseform='';
			$fees=0;
			$i=1;	
			foreach ($details as $each)
			{
				$mark=$i;
				if($each->form_sn!=$baseform)
				{
					$baseform=$each->form_sn;
					$i++;
					$title_sub='';
					if($each->purchase_type=='dxcg')
					{
						$edit_url = Yii::app()->createUrl('purchase/updateDxcg',array('id'=>$each->common_id,'last_update'=>$each->last_update,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'purchase/indexForStore','search_url'=>json_encode($search)));
					}else{
						$edit_url = Yii::app()->createUrl('purchase/update',array('id'=>$each->common_id,'last_update'=>$each->last_update,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'purchase/indexForStore','search_url'=>json_encode($search)));
					}
					
					if($each->form_status=='unsubmit')
					{
						$type_sub="submit";
						$title_sub="提交";
						$img_url = "/images/tijiao.png";
					}elseif($each->form_status=='submited')
					{
						$type_sub="cancle";
						$title_sub="取消提交";
						$img_url = "/images/qxtj.png";
					}					
					$sto_url = Yii::app()->createUrl('input/create',array('id'=>$each->common_id,'type'=>'purchase','last_update'=>$each->last_update));
					$sub_url =  Yii::app()->createUrl('purchase/submit',array('id'=>$each->common_id,'type'=>$type_sub,'last_update'=>$each->last_update));
					$del_url= Yii::app()->createUrl('purchase/deleteform',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					$checkP_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'pass','last_update'=>$each->last_update));
					$checkD_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'deny','last_update'=>$each->last_update));
					$checkC_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'cancle','last_update'=>$each->last_update));
					$confirm_url=Yii::app()->createUrl('purchase/confirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
					$br_url = Yii::app()->createUrl("billRecord/index", array('frm_common_id' => $each->common_id, "fpage"=>$_REQUEST['page']));
					$fk_url = Yii::app()->createUrl("formBill/create", array('type' => "FKDJ", 'bill_type' => "CGFK", 'common_id' => $each->common_id));					
					$detail_url=Yii::app()->createUrl('purchase/view',array('id'=>$each->common_id,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'purchase/indexForStore','search_url'=>json_encode($search)));
					$num=0;
					$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
// 										.'<span><a class="update_b" href="'.'" title="打印"><img src="/images/dayin.png"></a></span>'			

					//已审核
					if($each->form_status=='approve')
					{
						if(checkOperation('采购单:推送'))
						{
							if($each->can_push)
							{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'close','last_update'=>$each->last_update));
								$title_p='取消可推送';
								$image_p='/images/bkts.png';
							}else{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'open','last_update'=>$each->last_update));
								$title_p='可推送';
								$image_p='/images/kts.png';
							}
							$operate.='<span class="submit_form" url="'.$can_push.'" title="'.$title_p.'"><img src="'.$image_p.'"></span><abc></abc>';
							$num++;
						}						
						if(checkOperation("付款登记:新增"))
						{
							$pay_url = Yii::app()->createUrl('formBill/create', array('type' => "FKDJ", 'bill_type' => $each->purchase_type == 'tpcg' ? "DLFK" : "CGFK", 'common_id' => $each->common_id, 'fpage' => $_REQUEST['page']));
							$operate.='<a class="update_b" href="'.$pay_url.'" title="付款"><span><img src="/images/fukuan.png"></span></a><abc></abc>';
							$num++;
						}
						if(checkOperation("采购运费:新增"))
						{
							$operate.='<a class="update_b" href="'.$br_url.'" title="运费登记"><span><img src="/images/yfdj.png"></span></a><abc></abc>';
							$num++;
						}
						if(checkOperation("采购单:新增"))
						{
							if($each->purchase_type!='xxhj'&&$each->purchase_type!='dxcg')
							{
								$plan=FrmInputPlan::model()->find('purchase_id='.$each->common_id.' and input_status != 4');
								if($plan)
								{
									$num++;
									$plan_url=Yii::app()->createUrl('inputPlan/index',array('search_dan'=>$each->form_sn));
									$operate.='<span><a class="update_b" href="'.$plan_url.'" title="查看入库计划"><span><img src="/images/rkjh.png"></span></a></span><abc></abc>';
								}elseif($each->weight_confirm_status==0&&$each->main_amount-$each->main_input_amount>0){
									$num++;
									$plan_url=Yii::app()->createUrl('inputPlan/create',array('purchase_common_id'=>$each->common_id));
									$operate.='<span><a class="update_b" href="'.$plan_url.'" title="创建入库计划"><span><img src="/images/rkjh.png"></span></a></span><abc></abc>';
								}
								//查找船舱入库单
								$ccrk=FrmInput::model()->with('baseform')->find('input_type="ccrk" and purchase_id='.$each->common_id.' and baseform.is_deleted=0');
								if($ccrk)
								{//跳转列表
									$num++;
									$ccrk_url=Yii::app()->createUrl('inputCcrk/index',array('search_dan'=>$each->form_sn));
									$operate.='<span><a class="update_b" href="'.$ccrk_url.'" title="查看船舱入库" ><span><img src="/images/ccrk.png"></span></a></span><abc></abc>';
								}elseif($each->weight_confirm_status==0&&$each->main_amount-$each->main_input_amount>0){
									//跳转新建船舱入库计划
									$num++;
									$ccrk_url=Yii::app()->createUrl('inputPlan/create',array('purchase_common_id'=>$each->common_id,'type'=>'ccrk'));
									$operate.='<span><a class="update_b" href="'.$ccrk_url.'" title="创建船舱入库"><span><img src="/images/ccrk.png"></span></a></span><abc></abc>';
								}
							}
							$operate.='<a class="update_b update_button" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'"  href="'.$edit_url.'" title="编辑"><span class=""><img src="/images/bianji.png"></span></a><abc></abc>';
							$num++;
							
						}
						if($each->weight_confirm_status==1&&checkOperation("采购单:审单"))
						{
							$num++;
							$confirm_url=Yii::app()->createUrl('purchase/cancelConfirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
							$operate.='<span class="submit_form" url="'.$confirm_url.'" title="取消审单"><img src="/images/qxsd.png"></span><abc></abc>';
						}else{
							if(checkOperation("采购单:审单"))
							{
								$num++;
								$confirm_url=Yii::app()->createUrl('purchase/confirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
								$operate.='<span><a class="update_b confirm_link" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'" href="'.$confirm_url.'" title="审单"><span><img src="/images/shendan.png"></span></a></span><abc></abc>';
							}
							if(checkOperation("采购单:审核"))
							{
								$num++;
								$operate.='<span class="cancelcheck_form" thisid="'.$each->common_id.'" url="'.$checkC_url.'" title="取消审核" str="确定要取消审核采购单'.$each->form_sn.'吗？"><img src="/images/qxsh.png"></span><abc></abc>';
							}
							if($each->purchase_type!="dxcg"&&$each->main_input_amount<$each->main_amount&&$each->purchase_type!="xxhj"&&checkOperation("入库单:新增"))
							{
								$num++;
								$operate.='<a class="update_b" href="'.$sto_url.'" title="入库"><span><img src="/images/ruku.png"></span></a><abc></abc>';
							}
						}
						if($num>4)
						{
							$one=substr($operate,strpos($operate,'<abc></abc>')+11);
							$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
							$two=substr($one,strpos($one,'<abc></abc>')+11);
							$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
							$three=substr($two,strpos($two,'<abc></abc>')+11);
							$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
							$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
									.'<div class="cz_list_btn_more" style="height:45px;width:90px;" num="0">'.$three;
							$operate.='</div></div>';
						}else{
							$operate.='</div>';
						}
					}
					//未提交
					if($each->form_status=='unsubmit')
					{
						if(checkOperation("采购单:新增"))
						{
							$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';//提交
							$operate.='<span class="delete_form" thisid="'.$each->common_id.'" url="'.$del_url.'" title="作废"><span><img src="/images/zuofei.png"></span></span>';
							$operate.='<a class="update_b update_button" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'"  href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a>';							
							$operate.='</div>';
						}else{
							$operate.='</div>';
						}														
					}					
					//已提交
					if($each->form_status=='submited')
					{
						if(checkOperation('采购单:推送'))
						{
							if($each->can_push)
							{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'close','last_update'=>$each->last_update));
								$title_p='取消可推送';
								$image_p='/images/bkts.png';
							}else{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'open','last_update'=>$each->last_update));
								$title_p='可推送';
								$image_p='/images/kts.png';
							}
							$operate.='<span class="submit_form" url="'.$can_push.'" title="'.$title_p.'"><img src="'.$image_p.'"></span><abc></abc>';
							$num++;
						}						
						if($each->purchase_type!='xxhj'&&$each->purchase_type!='dxcg')
						{
							$plan=FrmInputPlan::model()->find('purchase_id='.$each->common_id.' and input_status != 4');
							if($plan)
							{
								$num++;
								$plan_url=Yii::app()->createUrl('inputPlan/index',array('search_dan'=>$each->form_sn));
								$operate.='<span><a class="update_b" href="'.$plan_url.'" title="查看入库计划"><span><img src="/images/rkjh.png"></span></a></span><abc></abc>';
							}elseif($each->weight_confirm_status==0&&$each->main_amount-$each->main_input_amount>0){
								$num++;
								$plan_url=Yii::app()->createUrl('inputPlan/create',array('purchase_common_id'=>$each->common_id));
								$operate.='<span><a class="update_b" href="'.$plan_url.'" title="创建入库计划"><span><img src="/images/rkjh.png"></span></a></span><abc></abc>';
							}
							//查找船舱入库单
							$ccrk=FrmInput::model()->with('baseform')->find('input_type="ccrk" and purchase_id='.$each->common_id.' and baseform.is_deleted=0');
							if($ccrk)
							{//跳转列表
								$num++;
								$ccrk_url=Yii::app()->createUrl('inputCcrk/index',array('search_dan'=>$each->form_sn));
								$operate.='<span><a class="update_b" href="'.$ccrk_url.'" title="查看船舱入库" ><span><img src="/images/ccrk.png"></span></a></span><abc></abc>';
							}elseif($each->weight_confirm_status==0&&$each->main_amount-$each->main_input_amount>0){
								//跳转新建船舱入库计划
								$num++;
								$ccrk_url=Yii::app()->createUrl('inputPlan/create',array('purchase_common_id'=>$each->common_id,'type'=>'ccrk'));
								$operate.='<span><a class="update_b" href="'.$ccrk_url.'" title="创建船舱入库"><span><img src="/images/ccrk.png"></span></a></span><abc></abc>';
							}
						}
						
						if(checkOperation("采购单:新增"))
						{
							$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span><abc></abc>';//提交
							$operate.='<a class="update_b update_button" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'"  href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a><abc></abc>';
							$num+=2;
							if(!checkOperation("采购单:审核"))
							{
// 								$operate.='</div>';
							}else{
								$num++;
									$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$each->form_sn.',确定审核通过此采购单吗？"><img src="/images/shenhe.png"></span><abc></abc>';
// 									$operate.='</div>';
							}
						}else{
							if(checkOperation("采购单:审核"))
							{					
								$num++;
								$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$each->form_sn.',确定审核通过此采购单吗？"><img src="/images/shenhe.png"></span><abc></abc>';
							}
// 							$operate.='</div>';
						}
						if($num>4)
						{
							$one=substr($operate,strpos($operate,'<abc></abc>')+11);
							$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
							$two=substr($one,strpos($one,'<abc></abc>')+11);
							$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
							$three=substr($two,strpos($two,'<abc></abc>')+11);
							$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
							$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
									.'<div class="cz_list_btn_more" style="height:45px;width:90px;" num="0">'.$three;
							$operate.='</div></div>';
						}else{
							$operate.='</div>';
						}					
					}				
					
				}else{
					$mark='';
					$operate='';
				}
							$da['data']=array($mark,
									$operate,
									'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$each->form_sn.'</a>',
									'<span class="'.($each->form_status!='approve'?'red':'').'">'.$_status[$each->form_status].'</span>',//审批状态
									$each->form_time?$each->form_time:'',
									'<span title="'.$each->supply_name.'">'.$each->supply_short_name.'</span>',
									$each->title_short_name,
									$each->product_name,
									$each->rank_name,
									str_replace('E', '<span class="red">E</span>',$each->texture_name),
									$each->brand_name,
									$each->length,
									$each->detail_amount,
									number_format($each->detail_weight,3),//总重量
									($each->date_reach>943891200)?date('Y-m-d',$each->date_reach).'&nbsp;&nbsp;'.$_time[$each->reach_time]:'',
									$each->total_num?$each->total_num:0,
									$each->detail_input_amount,//仓库入库件数
									number_format($each->detail_input_weight,3),//仓库入库重量
									$_type[$each->purchase_type],//类型
									$each->form_status=='approve'?$each->approved_by_nickname:'',
									$each->form_status=='approve'?date('Y-m-d',$each->approved_at):'',
									$each->team_name,//业务组
	
									$each->owned_by_nickname,//业务员
									$each->created_by_nickname,//创建人
									$each->last_updated_by?$each->last_updated_by_nickname:$each->created_by_nickname,//修改人
									'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment, 0,18,"utf-8").'</span>',
					);
					if($search['form_status']=='delete'){
						$re='<span title="'.htmlspecialchars($each->delete_reason).'">'.mb_substr($each->delete_reason, 0,15,"utf-8").'</span>';
						array_push($da['data'], $re);
						array_splice($da['data'], 1,1);
					}
					$da['group']=$each->form_sn;
					array_push($tableData,$da);
				}
			}
			return array($tableHeader,$tableData,$pages,$totaldata);
		}
	
		/*
		 * 审核列表
		 */
		public static function getPurchseListForCheck($search)
		{
			$tableHeader = array(
					array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
					array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),
					array('name'=>'单号','class' =>"sort-disabled",'width'=>"80px"),
					array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"48px"),//
					array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"78px"),
					array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"60px"),
					array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"60px"),//
					array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"60px"),//
					array('name'=>'乙单','class' =>"flex-col sort-disabled",'width'=>"38px"),
					array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"48px"),//
					array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"36px"),//
					array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"60px"),//
					array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"58px"),//
					array('name'=>'长度','class' =>"flex-col sort-disabled text-right",'width'=>"36px"),//
					array('name'=>'单价','class' =>"flex-col sort-disabled text-right",'width'=>"48px"),//
					array('name'=>'件数','class' =>"flex-col sort-disabled text-right",'width'=>"50px"),//
					array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//总重量
					array('name'=>'金额','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//		
					array('name'=>'发票成本','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//
					array('name'=>'预计到货时间','class' =>"flex-col sort-disabled",'width'=>"160px"),
					array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"60px"),
					array('name'=>'审单','class' =>"flex-col sort-disabled",'width'=>"60px"),//
					array('name'=>'采购合同','class' =>"flex-col sort-disabled",'width'=>"130px"),
					array('name'=>'托盘公司','class' =>"flex-col sort-disabled",'width'=>"70px"),
					array('name'=>'托盘单价','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//托盘单价
					array('name'=>'托盘金额','class' =>"flex-col sort-disabled text-right",'width'=>"95px"),//托盘金额
					
					array('name'=>'入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//仓库入库件数
					array('name'=>'入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//仓库入库重量
					array('name'=>'核定价格','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//核定价格
					array('name'=>'核定件数','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//核定总件数
					array('name'=>'核定重量','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//核定总重量
					array('name'=>'核定金额','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//核定总金额
					
					array('name'=>'审核人','class' =>"flex-col sort-disabled",'width'=>"55px"),//
					array('name'=>'审核时间','class' =>"flex-col sort-disabled",'width'=>"80px"),//
					array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"60px"),//					
					array('name'=>'制单人','class' =>"flex-col sort-disabled",'width'=>"60px"),//
					array('name'=>'最后操作人','class' =>"flex-col sort-disabled",'width'=>"70px"),//
					array('name'=>'备注','class' =>"flex-col sort-disabled",'width'=>"230px"),//
				);
			if($search['form_status']=='delete')
			{
				$reason=array('name'=>'作废原因','class' =>"flex-col sort-disabled",'width'=>"230px");
				array_push($tableHeader, $reason);
				array_splice($tableHeader, 1,1);
			}
			$tableData=array();
			$model=PurchaseView::model();
			$criteria=New CDbCriteria();
			//搜索
			if(!empty($search))
			{
				$criteria->together=true;
				//销售单号处理
				$keywords=trim($search['keywords']);
				if($keywords)
				{
					if(substr($keywords, 0,2)=='XD')
					{
						$sql='select group_concat(id) as id from common_forms where form_sn like "%'.$keywords.'%"';
						$id=CommonForms::model()->findBySql($sql)->id;
						if($id){
							$criteria->addCondition('frm_contract_id in ('.$id.')');
						}else{
							$criteria->addCondition('frm_contract_id in (-1)');
						}
					}else{
						$criteria->addCondition('form_sn like :contno or comment like :contno');
						$criteria->params[':contno']= "%".$keywords."%";
					}
				}
// 				$criteria->addCondition('form_sn like :contno or comment like :contno');
// 				$criteria->params[':contno']= "%".$search['keywords']."%";
				if($search['time_L']!='')
				{
					$criteria->addCondition('UNIX_TIMESTAMP(form_time) >='.strtotime($search['time_L']));
				}
				if($search['time_H']!='')
				{
					$criteria->addCondition('UNIX_TIMESTAMP(form_time) <'.(strtotime($search['time_H'])+86400));
				}
				if($search['reach_time_L'])
				{
					$criteria->addCondition('date_reach >='.strtotime($search['reach_time_L']));
				}
				if($search['reach_time_H'])
				{
					$criteria->addCondition('date_reach <='.strtotime($search['reach_time_H']));
				}
				if($search['company']!='0')
				{
					$criteria->compare('title_id',$search['company']);
				}
				if($search['vendor']!='0')
				{
					$criteria->compare('supply_id',$search['vendor']);
				}
				if($search['form_status']!='0')
				{
					$criteria->compare('form_status',$search['form_status']);
				}else{
					$criteria->compare('is_deleted','0');
				}
							//产地,品名，规格,材质
				if($search['brand']!='0')
				{
					$criteria->compare('brand_id',$search['brand']);
				}
				if($search['product']!='0')
				{
					$criteria->compare('product_id',$search['product']);
				}
				if($search['rand']!='0')
				{
					$criteria->compare('rank_id',$search['rand']);
				}
				if($search['texture']!='0')
				{
					$criteria->compare('texture_id',$search['texture']);
				}
				if($search['length']>=0)
				{
					$criteria->compare('length',$search['length']);
				}
				//审单状态，采购单类型，乙单
				if($search['confirm_status']!='')
				{
					$criteria->compare('weight_confirm_status', $search['confirm_status']);
				}
				if($search['purchase_type']!='')
				{
					$criteria->compare('purchase_type', $search['purchase_type']);
				}
				if($search['is_yidan']!='')
				{
					$criteria->compare('is_yidan', $search['is_yidan']);
				}
				if($search['contract'])
				{
					$criteria->compare('contract_no', $search['contract'],true);
				}
				if($search['contract_array'])
				{
					$criteria->addInCondition('frm_contract_id',$search['contract_array']);
					$criteria->compare('form_status', 'approve');
				}
			}else{
				$criteria->compare('is_deleted','0');
			}
// 			$user=Yii::app()->user->userid;
// 			$criteria->addCondition('owned_by ='.$user.' or created_by ='.$user);
			$criteria->compare('form_type','CGD');
			$newcri=clone $criteria;
			$newcri->select = "sum(detail_amount) as total_amount,sum(detail_weight) as total_weight,sum(detail_weight*detail_price) as total_money,count(*) as total_num";
			$all=PurchaseView::model()->find($newcri);
			$totaldata = array();
			$totaldata["amount"] = $all->total_amount;
			$totaldata["weight"] = $all->total_weight;
			$totaldata["money"] = $all->total_money;
			$totaldata["total_num"] = $all->total_num;
			
			$pages = new CPagination();
			$pages->itemCount = $model->count($criteria);
			$pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
			$pages->applyLimit($criteria);
			$criteria->order="locate(form_status,'submited,unsubmit,approve,delete'),created_at DESC,main_id";
// 			$criteria->order="created_at DESC";
			$details=PurchaseView::model()->findAll($criteria);
			if($details)
			{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$_type=array('normal'=>"库存采购",'tpcg'=>"托盘采购","xxhj"=>"直销采购","dxcg"=>"代销采购");
			$_time=array('6'=>'00:00-06:00','12'=>'06:00-12:00','18'=>'12:00-18:00','24'=>'18:00-24:00');
			$baseform='';
			$fees=0;
			$i=1;
			foreach ($details as $each)
			{
				$mark=$i;
				if($each->form_sn!=$baseform)
				{
				$baseform=$each->form_sn;
				$i++;
				$title_sub='';
				if($each->purchase_type=='dxcg')
				{
				$edit_url = Yii::app()->createUrl('purchase/updateDxcg',array('id'=>$each->common_id,'last_update'=>$each->last_update,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page']));
				}else{
				$edit_url = Yii::app()->createUrl('purchase/update',array('id'=>$each->common_id,'last_update'=>$each->last_update,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'purchase/indexForCheck','search_url'=>json_encode($search)));
				}
					
				if($each->form_status=='unsubmit')
				{
					$type_sub="submit";
					$title_sub="提交";
					$img_url = "/images/tijiao.png";
				}elseif($each->form_status=='submited')
				{
					$type_sub="cancle";
					$title_sub="取消提交";
					$img_url = "/images/qxtj.png";
				}
				$sto_url = Yii::app()->createUrl('input/create',array('id'=>$each->common_id,'type'=>'purchase','last_update'=>$each->last_update));
				$sub_url =  Yii::app()->createUrl('purchase/submit',array('id'=>$each->common_id,'type'=>$type_sub,'last_update'=>$each->last_update));
				$del_url= Yii::app()->createUrl('purchase/deleteform',array('id'=>$each->common_id,'last_update'=>$each->last_update));
				$checkP_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'pass','last_update'=>$each->last_update));
				$checkD_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'deny','last_update'=>$each->last_update));
				$checkC_url=Yii::app()->createUrl('purchase/check',array('id'=>$each->common_id,'type'=>'cancle','last_update'=>$each->last_update));
				$confirm_url=Yii::app()->createUrl('purchase/confirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
				$br_url = Yii::app()->createUrl("billRecord/index", array('frm_common_id' => $each->common_id, "fpage"=>$_REQUEST['page']));
				$fk_url = Yii::app()->createUrl("formBill/create", array('type' => "FKDJ", 'bill_type' => "CGFK", 'common_id' => $each->common_id));
				$detail_url=Yii::app()->createUrl('purchase/view',array('id'=>$each->common_id,'type'=>$each->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'purchase/indexForCheck','search_url'=>json_encode($search)));
				$print_url = Yii::app()->createUrl('print/print', array('id' => $each->common_id));
				$num=0;
				$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
					if (checkOperation("打印")) {
						$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
						$num++;
					}
					//未提交
					if($each->form_status=='unsubmit')
					{
						if(checkOperation("采购单:新增"))
						{
							$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';//提交
							$operate.='<span class="delete_form" thisid="'.$each->common_id.'" url="'.$del_url.'" title="作废"><span><img src="/images/zuofei.png"></span></span><abc></abc>';
							$operate.='<a class="update_b update_button"  lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'"  href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a><abc></abc>';
							// $operate.='</div>';
						}else{
							// $operate.='</div>';
						}
					}
												//已提交
					if($each->form_status=='submited')
					{
						if(checkOperation('采购单:推送'))
						{
							if($each->can_push)
							{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'close','last_update'=>$each->last_update));
								$title_p='取消可推送';
								$image_p='/images/bkts.png';
							}else{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'open','last_update'=>$each->last_update));
								$title_p='可推送';
								$image_p='/images/kts.png';
							}
							$operate.='<span class="submit_form" url="'.$can_push.'" title="'.$title_p.'"><img src="'.$image_p.'"></span><abc></abc>';
							$num++;
						}
						if(checkOperation("采购单:新增"))
						{
							$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span><abc></abc>';//提交
							$operate.='<a class="update_b update_button"  lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'"  href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a><abc></abc>';
							$num+=2;
							if(!checkOperation("采购单:审核"))
							{
								// $operate.='</div>';
							}else{
								$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$each->form_sn.',确定审核通过此采购单吗？"><img src="/images/shenhe.png"></span><abc></abc>';
								$num++;
								// $operate.='</div>';
							}
						}else{
							if(checkOperation("采购单:审核"))
							{
									$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$each->form_sn.',确定审核通过此采购单吗？"><img src="/images/shenhe.png"></span><abc></abc>';
									$num++;
							}
							// $operate.='</div>';
						}
					}
					//已审核
					if($each->form_status=='approve')
					{
						if(checkOperation('采购单:推送'))
						{
							if($each->can_push)
							{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'close','last_update'=>$each->last_update));
								$title_p='取消可推送';
								$image_p='/images/bkts.png';
							}else{
								$can_push=Yii::app()->createUrl('purchase/canPush',array('id'=>$each->common_id,'type'=>'open','last_update'=>$each->last_update));
								$title_p='可推送';
								$image_p='/images/kts.png';
							}
							$operate.='<span class="submit_form" url="'.$can_push.'" title="'.$title_p.'"><img src="'.$image_p.'"></span><abc></abc>';
							$num++;
						}
						if(checkOperation("付款登记:新增"))
						{
							$pay_url = Yii::app()->createUrl('formBill/create', array('type' => "FKDJ", 'bill_type' => $each->purchase_type == 'tpcg' ? "DLFK" : "CGFK", 'common_id' => $each->common_id, 'fpage' => $_REQUEST['page']));
							$operate.='<a class="update_b" href="'.$pay_url.'" title="付款"><span><img src="/images/fukuan.png"></span></a><abc></abc>';
							$num++;
						}
						if(checkOperation("采购运费:新增"))
						{
							$operate.='<a class="update_b" href="'.$br_url.'" title="运费登记"><span><img src="/images/yfdj.png"></span></a><abc></abc>';
							$num++;
						}
						if(checkOperation("采购单:新增"))
						{
							$operate.='<a class="update_b update_button"  lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'"  href="'.$edit_url.'" title="编辑"><span class=""><img src="/images/bianji.png"></span></a><abc></abc>';
							$num++;
							if($each->purchase_type!='xxhj'&&$each->purchase_type!='dxcg')
							{
								$plan=FrmInputPlan::model()->find('purchase_id='.$each->common_id.' and input_status != 4');
								if($plan)
								{
									$num++;
									$plan_url=Yii::app()->createUrl('inputPlan/index',array('search_dan'=>$each->form_sn));
									$operate.='<span><a class="update_b" href="'.$plan_url.'" title="查看入库计划"><span><img src="/images/rkjh.png"></span></a></span><abc></abc>';
								}elseif($each->weight_confirm_status==0&&$each->main_amount-$each->main_input_amount>0){
									$num++;
									$plan_url=Yii::app()->createUrl('inputPlan/create',array('purchase_common_id'=>$each->common_id));
									$operate.='<span><a class="update_b" href="'.$plan_url.'" title="创建入库计划"><span><img src="/images/rkjh.png"></span></a></span><abc></abc>';
								}
								//查找船舱入库单
								$ccrk=FrmInput::model()->with('baseform')->find('input_type="ccrk" and purchase_id='.$each->common_id.' and baseform.is_deleted=0');
								if($ccrk)
								{//跳转列表
									$num++;
									$ccrk_url=Yii::app()->createUrl('inputCcrk/index',array('search_dan'=>$each->form_sn));
									$operate.='<span><a class="update_b" href="'.$ccrk_url.'" title="查看船舱入库" ><span><img src="/images/ccrk.png"></span></a></span><abc></abc>';
								}elseif($each->weight_confirm_status==0&&$each->main_amount-$each->main_input_amount>0){
									//跳转新建船舱入库计划
									$num++;
									$ccrk_url=Yii::app()->createUrl('inputPlan/create',array('purchase_common_id'=>$each->common_id,'type'=>'ccrk'));
								$operate.='<span><a class="update_b" href="'.$ccrk_url.'" title="创建船舱入库"><span><img src="/images/ccrk.png"></span></a></span><abc></abc>';
								}
							}
						}
						if($each->weight_confirm_status==1&&checkOperation("采购单:审单"))
						{
							$num++;
							$confirm_url=Yii::app()->createUrl('purchase/cancelConfirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
							$operate.='<span class="submit_form" url="'.$confirm_url.'" title="取消审单"><img src="/images/qxsd.png"></span><abc></abc>';
						}else{
							if(checkOperation("采购单:审单"))
							{
								$num++;
								$confirm_url=Yii::app()->createUrl('purchase/confirm',array('id'=>$each->common_id,'last_update'=>$each->last_update,"fpage"=>$_REQUEST['page']));
								$operate.='<span><a class="update_b confirm_link" lastupdate="'.$each->last_update.'" thisid="'.$each->common_id.'" href="'.$confirm_url.'" title="审单"><span><img src="/images/shendan.png"></span></a></span><abc></abc>';
							}
							if(checkOperation("采购单:审核"))
							{
								$num++;
								$operate.='<span class="cancelcheck_form" thisid="'.$each->common_id.'" url="'.$checkC_url.'" title="取消审核" str="确定要取消审核采购单'.$each->form_sn.'吗？"><img src="/images/qxsh.png"></span><abc></abc>';
							}
							if($each->purchase_type!="dxcg"&&$each->main_input_amount<$each->main_amount&&$each->purchase_type!="xxhj"&&checkOperation("入库单:新增"))
							{
								$num++;
								$operate.='<a class="update_b" href="'.$sto_url.'" title="入库"><span><img src="/images/ruku.png"></span></a><abc></abc>';
							}
						}						
					}
					if($num>4)
						{
							$one=substr($operate,strpos($operate,'<abc></abc>')+11);
							$one_left=substr($operate,0,strpos($operate,'<abc></abc>')+11);
							$two=substr($one,strpos($one,'<abc></abc>')+11);
							$two_left=substr($one,0,strpos($one,'<abc></abc>')+11);
							$three=substr($two,strpos($two,'<abc></abc>')+11);
							$three_left=substr($two,0,strpos($two,'<abc></abc>')+11);
							$operate=$one_left.$two_left.$three_left.'<span class="more_but" title="更多"><span><i class="icon icon-ellipsis-h"></i></span></span>'
							.'<div class="cz_list_btn_more" style="height:70px;width:90px;" num="0">'.$three;
							$operate.='</div></div>';
						}else{
							$operate.='</div>';
						}
				}else{
					$mark='';
					$operate='';
				}
				$da['data']=array($mark,
						$operate,
						'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$each->form_sn.'</a>',
						'<span class="'.($each->form_status!='approve'?'red':'').'">'.$_status[$each->form_status].'</span>',//审批状态
						$each->form_time?$each->form_time:'',
						'<span title="'.$each->supply_name.'">'.$each->supply_short_name.'</span>',
						$each->title_short_name,
						$each->owned_by_nickname,//业务员
						$each->is_yidan==1?'是':'',
						$each->product_name,
						$each->rank_name,
						str_replace('E', '<span class="red">E</span>',$each->texture_name),
						$each->brand_name,
						$each->length,
						number_format($each->detail_price),
						$each->detail_amount,
						number_format($each->detail_weight,3),//总重量
						'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_price*$each->detail_weight,2).'</span>',
						$each->detail_invoice_price,
						($each->date_reach>943891200)?date('Y-m-d',$each->date_reach).'&nbsp;&nbsp;'.$_time[$each->reach_time]:'',
						$_type[$each->purchase_type],//类型
						$each->weight_confirm_status==0?'未审单':'已审单',
						$each->contract_no,
						$each->purchase_type=="tpcg"?'<span title="'.$each->pledge_name.'">'.$each->pledge_short_name.'</span>':'',//托盘公司
						$each->purchase_type=="tpcg"?number_format($each->pledge_unit_price):'0',//托盘单价
						$each->purchase_type=="tpcg"?number_format($each->pledge_fee,2):'0.00',//托盘金额
						$each->detail_input_amount,//仓库入库件数
						number_format($each->detail_input_weight,3),//仓库入库重量
						number_format($each->detail_fix_price),
						$each->detail_fix_amount,//核定总件数
						number_format($each->detail_fix_weight,3),//核定总重量
						'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_fix_price*$each->detail_fix_weight,2).'</span>',//核定总金额
						$each->form_status=='approve'?$each->approved_by_nickname:'',
						$each->form_status=='approve'?date('Y-m-d',$each->approved_at):'',
						$each->team_name,//业务组
						$each->created_by_nickname,//创建人
						$each->last_updated_by?$each->last_updated_by_nickname:$each->created_by_nickname,//修改人
						'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment, 0,18,"utf-8").'</span>',
					);
					if($search['form_status']=='delete'){
						$re='<span title="'.htmlspecialchars($each->delete_reason).'">'.mb_substr($each->delete_reason, 0,15,"utf-8").'</span>';
						array_push($da['data'], $re);
						array_splice($da['data'], 1,1);
					}
					$da['group']=$each->form_sn;
					array_push($tableData,$da);
			}
		}
		return array($tableHeader,$tableData,$pages,$totaldata);
	}
		
		
		
		
	/*
	 * 获取采购单列表
	 */
	public static function getSimplePurchaseList($search,$type)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"30px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"50px"),
				array('name'=>'采购单号','class' =>"flex-col sort-disabled",'width'=>"150px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'销售公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'总重量','class' =>"flex-col sort-disabled text-right",'width'=>"120px"),//
				array('name'=>'总件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'已入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'已入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'未入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'类型','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"90px"),//
		);
		$tableData=array();
		$model=FrmPurchase::model()->with('baseform');
		$criteria=New CDbCriteria();
		//搜索
		//单号 日期上下，采购公司customer_id即我们，业务员owned_by，供应商/销售公司title_id即他们
		if(!empty($search))
		{
			$criteria->together=true;
			$criteria->addCondition('baseform.form_sn like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');#+86400));
			}
			if($search['title_id']!='')
			{
				$criteria->compare('t.supply_id',$search['title_id']);
			}
			if($search['customer_id']!='')
			{
				$criteria->compare('t.title_id',$search['customer_id']);
			}
			if($search['owned']!='0')
			{
				$criteria->compare('baseform.owned_by',$search['owned']);
			}
		}
		$criteria->compare('baseform.form_type','CGD');
		$criteria->compare('baseform.is_deleted','0');
		if($type=='plan')
		{
			$criteria->addCondition('baseform.form_status!="unsubmit"');
		}else{
			$criteria->compare('baseform.form_status','approve');
		}		
		$criteria->addCondition('t.purchase_type="normal" or t.purchase_type="tpcg"');		
		$criteria->compare('t.weight_confirm_status','0');
		$criteria->addCondition('t.amount-t.input_amount>0');
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="baseform.created_at DESC";
		$frmpur=FrmPurchase::model()->with('baseform')->findAll($criteria);
		if($frmpur)
		{
			$da=array();
			$da['data']=array();
			$pur_type = array("normal"=>"库存采购","tpcg"=>"托盘采购","xxhj"=>"直销采购",'dxcg'=>'代销采购');
			$i=1;
			foreach ($frmpur as $each)
			{
				$baseform=$each->baseform;
				$operate='<input type="radio" name="selected_sales"  class="selected_sales"  value="'.$baseform->id.'" />';
				$da['data']=array($i,
						$operate,
						$baseform->form_sn,
						$baseform->form_time,
						$each->title->short_name,//
						'<span title="'.$each->supply->name.'">'.$each->supply->short_name.'</span>',
						number_format($each->weight,3),
						$each->amount,
						number_format($each->input_weight,3),
						$each->input_amount,
						$each->amount-$each->input_amount,
						$pur_type[$each->purchase_type],
						$each->team->name,
						$baseform->belong->nickname,
				);
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
				$i++;
			}
		}
		return array($tableHeader,$tableData,$pages);
	}
	
	/*
	 * 获取用户输入数据
	 */
	public static function getInputData($post)
	{
		if(!empty($post['CommonForms']))
		{
			$post['CommonForms']['form_type']='CGD';
			$data['common']=(Object)$post['CommonForms'];
			$data['main']=$post['FrmPurchase'];
			if($data['main']['is_tp']==1)
			{
				$data['main']['purchase_type']='tpcg';
			}
			$data['detail']=array();
			$contractAmount=0;
			$contractWeight=0;
			$total_money=0;
			for($i=0;$i<count($post['td_products']);$i++)
			{
				if($post['td_brands'][$i]=='')
				{
					continue;
				}
				$temp=array();
				$temp['product_id']=$post['td_products'][$i];
				$temp['texture_id']=$post['td_textures'][$i];
				$temp['brand_id']=$post['td_brands'][$i];
				$temp['rank_id']=$post['td_ranks'][$i];
				$temp['length']=$post['td_length'][$i];
				
				$temp['price']=numChange($post['td_price'][$i]);
				$temp['amount']=$post['td_amount'][$i];
				$temp['weight']=$post['td_weight'][$i];
				$temp['invoice_price']=$post['td_invoice'][$i];
				if($data['main']['purchase_type']=='dxcg')
				{
					$temp['salesdetail_ids']=$post['td_id'][$i];
					$temp['sales_details_array']=$data['main']['sales_details_array'];
				}elseif($data['main']['purchase_type']=='xxhj'){
					$temp['id']=$post['td_id'][$i];
				}
				$contractAmount+=$temp['amount'];
				$contractWeight+=$temp['weight'];
				$total_money+=$temp['weight']*$temp['price'];
				array_push($data['detail'], (Object)$temp);
			}
			$data['main']['amount']=$contractAmount;
			$data['main']['weight']=$contractWeight;
			$data['main']['price_amount']=$total_money;
			$data['main']=(Object)$data['main'];
			return $data;
		}else{
			$good_array=array();
			$stack=array();
			$detail_array=array();
			$detail_ids='';
			$zipped_array=array();
			for($i=0;$i<count($post['td_products']);$i++)
			{
				$temp=array();
				$temp['product_id']=$post['td_products'][$i];
				$temp['texture_id']=$post['td_textures'][$i];
				$temp['brand_id']=$post['td_brands'][$i];
				$temp['rank_id']=$post['td_ranks'][$i];
				$temp['length']=$post['td_length'][$i];
				$temp['price']=floatval(numChange($post['td_price'][$i]));	
				$temp['invoice_price']=floatval(numChange($post['td_invoice'][$i]));			
				$str=implode(',', $temp);
				
				$temp['detail_id']=$post['td_id'][$i];
				$id=$post['good_id'][$i];
				$temp['good_id']=$id;
				$temp['form_sn']=$post['td_form_sn'][$i];
				$temp['title_name']=$post['td_sell_name'][$i];
				$temp['title_id']=$post['td_sell'][$i];
				$temp['owner_name']=$post['td_owner_name'][$i];
				$temp['owner_company_id']=$post['td_owner'][$i];			
				$temp['amount']=$post['td_amount'][$i];
				$temp['max_amount']=$post['td_max_amount'][$i];
				$temp['weight']=$post['td_weight'][$i];
				$temp['product_name']=$post['td_products_name'][$i];
				$temp['rand_name']=$post['td_ranks_name'][$i];
				$temp['texture_name']=$post['td_textures_name'][$i];
				$temp['brand_name']=$post['td_brands_name'][$i];
				$temp['totalMoney']=numChange($post['td_totalMoney'][$i]);
				array_push($detail_array, $temp);
				$detail_ids.=','.$temp['detail_id'];
				
				if(in_array($str, $stack))
				{
					$pos=array_search($str, $stack);
					$zipped_array[$pos]['detail_id'].=','.$post['td_id'][$i];
					$zipped_array[$pos]['amount']+=$temp['amount'];
					$zipped_array[$pos]['weight']+=$temp['weight'];
					$zipped_array[$pos]['totalMoney']+=$temp['totalMoney'];
				}else{
					array_push($stack, $str);
					$zipped_array[]=$temp;
				}
// 				if(in_array($id,$good_array))
// 				{
// 					//累加
// 					$pos=array_search($id,$good_array);
// 					$zipped_array[$pos]['detail_id'].=','.$post['td_id'][$i];
// 					$zipped_array[$pos]['amount']+=$temp['amount'];
// 					$zipped_array[$pos]['weight']+=$temp['weight'];
// 					$zipped_array[$pos]['totalMoney']+=$temp['totalMoney'];
// 				}else{
// 					//新压入
// 					$good_array[]=$id;
// 					$zipped_array[]=$temp;
// 				}
			}			
			$detail_array=json_encode($detail_array);
			return array($detail_array,$detail_ids,$zipped_array);
		}
	}
	
	/*
	 * 获取用户更新数据
	 */
	public static function getUpdateData($post)
	{
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmPurchase'];
		if($data['main']['purchase_type']!='xxhj')
		{
			if($data['main']['is_tp']==1)
			{
				$data['main']['purchase_type']='tpcg';
			}else{
				$data['main']['purchase_type']='normal';
			}
		}		
		$data['detail']=array();
		$contractAmount=0;
		$contractWeight=0;
		$total_money=0;
		//以前的商品
		for($i=0;$i<count($post['old_td_id']);$i++)
		{
			$temp=array();
			if($data['main']['purchase_type']=='xxhj')
			{
				$temp['old_id']=$post['old_td_id'][$i];
			}else{
				$temp['id']=$post['old_td_id'][$i];
			}			
			$temp['product_id']=$post['old_td_products'][$i];
			$temp['texture_id']=$post['old_td_textures'][$i];
			$temp['brand_id']=$post['old_td_brands'][$i];
			$temp['rank_id']=$post['old_td_ranks'][$i];
			$temp['length']=$post['old_td_length'][$i];			
			$temp['price']=numChange($post['old_td_price'][$i]);
			$temp['amount']=$post['old_td_amount'][$i];
			$temp['weight']=$post['old_td_weight'][$i];
			$temp['invoice_price']=$post['old_td_invoice'][$i];
			$contractAmount+=$temp['amount'];
			$contractWeight+=$temp['weight'];
			$total_money+=$temp['weight']*$temp['price'];
			array_push($data['detail'], (Object)$temp);
		}
		//新增商品
		for($i=0;$i<count($post['td_products']);$i++)
		{
				$temp=array();
				$temp['product_id']=$post['td_products'][$i];
				$temp['texture_id']=$post['td_textures'][$i];
				$temp['brand_id']=$post['td_brands'][$i];
				$temp['rank_id']=$post['td_ranks'][$i];
				$temp['length']=$post['td_length'][$i];				
				$temp['price']=numChange($post['td_price'][$i]);
				$temp['amount']=$post['td_amount'][$i];
				$temp['weight']=$post['td_weight'][$i];
				$temp['invoice_price']=$post['td_invoice'][$i];
				if($data['main']['purchase_type']=='xxhj')
				{
					$temp['id']=$post['td_id'][$i];
				}
				$contractAmount+=$temp['amount'];
				$contractWeight+=$temp['weight'];
				$total_money+=$temp['weight']*$temp['price'];
				array_push($data['detail'], (Object)$temp);
		}
		$data['main']['amount']=$contractAmount;
		$data['main']['weight']=$contractWeight;
		$data['main']['price_amount']=$total_money;
		$data['main']=(Object)$data['main'];
		return $data;
	}
	
	/*
	 * 获取用户更新数据
	 */
	public static function getDxcgUpdateData($post)
	{
		if(!empty($post['CommonForms']))
		{
			$data['common']=(Object)$post['CommonForms'];
			$data['main']=$post['FrmPurchase'];			
			$data['detail']=array();
			$contractAmount=0;
			$contractWeight=0;
			$total_money=0;
			for($i=0;$i<count($post['td_products']);$i++)
			{
				$temp=array();
				$temp['product_id']=$post['td_products'][$i];
				$temp['texture_id']=$post['td_textures'][$i];
				$temp['brand_id']=$post['td_brands'][$i];
				$temp['rank_id']=$post['td_ranks'][$i];
				$temp['length']=$post['td_length'][$i];			
				$temp['price']=numChange($post['td_price'][$i]);
				$temp['amount']=$post['td_amount'][$i];
				$temp['weight']=$post['td_weight'][$i];
				$temp['invoice_price']=$post['td_invoice'][$i];
				if($data['main']['purchase_type']=='dxcg')
				{
					$temp['salesdetail_ids']=$post['td_id'][$i];
					$temp['sales_details_array']=$data['main']['sales_details_array'];
				}
				$contractAmount+=$temp['amount'];
				$contractWeight+=$temp['weight'];
				$total_money+=$temp['weight']*$temp['price'];
				array_push($data['detail'], (Object)$temp);
			}
			$data['main']['amount']=$contractAmount;
			$data['main']['weight']=$contractWeight;
			$data['main']['price_amount']=$total_money;
			$data['main']=(Object)$data['main'];
			return $data;
		}else{
			$good_array=array();
			$stack=array();
			$detail_array=array();
			$detail_ids='';
			$zipped_array=array();
			for($i=0;$i<count($post['td_products']);$i++)
			{
				$temp=array();
				$temp['product_id']=$post['td_products'][$i];
				$temp['texture_id']=$post['td_textures'][$i];
				$temp['brand_id']=$post['td_brands'][$i];
				$temp['rank_id']=$post['td_ranks'][$i];
				$temp['length']=$post['td_length'][$i];
				$temp['price']=floatval(numChange($post['td_price'][$i]));
				$temp['invoice_price']=floatval(numChange($post['td_invoice'][$i]));
				$str=implode(',', $temp);
							
				$temp['detail_id']=$post['td_id'][$i];
				$id=$post['good_id'][$i];
				$temp['good_id']=$id;
				$temp['form_sn']=$post['td_form_sn'][$i];
				$temp['title_name']=$post['td_sell_name'][$i];
				$temp['title_id']=$post['td_sell'][$i];
				$temp['owner_name']=$post['td_owner_name'][$i];
				$temp['owner_company_id']=$post['td_owner'][$i];
				$temp['amount']=$post['td_amount'][$i];
				$temp['max_amount']=$post['td_max_amount'][$i];
				$temp['weight']=$post['td_weight'][$i];
				$temp['product_name']=$post['td_products_name'][$i];
				$temp['rand_name']=$post['td_ranks_name'][$i];
				$temp['texture_name']=$post['td_textures_name'][$i];
				$temp['brand_name']=$post['td_brands_name'][$i];
				$temp['totalMoney']=numChange($post['td_totalMoney'][$i]);
				array_push($detail_array, $temp);
				$detail_ids.=','.$temp['detail_id'];
				
				if(in_array($str, $stack))
				{
					$pos=array_search($str, $stack);
					$zipped_array[$pos]['detail_id'].=','.$post['td_id'][$i];
					$zipped_array[$pos]['amount']+=$temp['amount'];
					$zipped_array[$pos]['weight']+=$temp['weight'];
					$zipped_array[$pos]['totalMoney']+=$temp['totalMoney'];
				}else{
					array_push($stack, $str);
					$zipped_array[]=$temp;
				}
				
// 				if(in_array($id,$good_array))
// 				{
// 					//累加
// 					$pos=array_search($id,$good_array);
// 					$zipped_array[$pos]['detail_id'].=','.$post['td_id'][$i];
// 					$zipped_array[$pos]['amount']+=$temp['amount'];
// 					$zipped_array[$pos]['weight']+=$temp['weight'];
// 					$zipped_array[$pos]['totalMoney']+=$temp['totalMoney'];
// 				}else{
// 					//新压入
// 					$good_array[]=$id;
// 					$zipped_array[]=$temp;
// 				}
			}
			$detail_array=json_encode($detail_array);
			return array($detail_array,$detail_ids,$zipped_array);
		}
		
	}
	
	
	
	/*
	 * 获取核定数据
	 */
	public static function getConfirmData($post)
	{
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmPurchase'];
		$data['detail']=array();
		$confirmAmount=0;
		$confirmWeight=0;
		$comfirmMoney=0;
		for($i=0;$i<count($post['td_id']);$i++)
		{
			$temp=array();
			$temp['id']=$post['td_id'][$i];
			$temp['fix_price']=numChange($post['td_price'][$i]);
			$temp['fix_amount']=$post['td_amount'][$i];
			$temp['fix_weight']=$post['td_weight'][$i];
			$confirmAmount+=$temp['fix_amount'];
			$confirmWeight+=$temp['fix_weight'];
			$comfirmMoney+=$temp['fix_weight']*$temp['fix_price'];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']['confirm_amount']=$confirmAmount;
		$data['main']['confirm_weight']=$confirmWeight;
		$data['main']['confirm_cost']=$comfirmMoney;
		$data['main']=(Object)$data['main'];
		return $data;
	}
	

	//采购付款 列表
	public static function getFormBillList($search, $purchase_type = "") 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "12%"), //flex-col 
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "供应商", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "重量", 'class' => "sort-disabled text-right", 'width' => "9%"),
				array('name' => "金额", 'class' => "sort-disabled text-right", 'width' => "9%"),
				array('name' => "乙单", 'class' => "sort-disabled", 'width' => "5%"),
				array('name' => "托盘公司", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "类型", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "业务组", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "9%"),
		);
		
		$tableData = array();
		$model = new FrmPurchase();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform', 'pledge');
		
		if ($purchase_type) // == "tpcg" 
		{
			$criteria->addCondition("purchase_type = :purchase_type");
			$criteria->params[':purchase_type'] = $purchase_type;
		}
		
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
			if ($purchase_type == "tpcg" && $search['pledge_company_id']) 
			{
				$criteria->addCondition("pledge.pledge_company_id = :pledge_company_id");
				$criteria->params[':pledge_company_id'] = $search['pledge_company_id'];
			}
// 			if ($search['is_yidan'] !== "") //是否乙单
// 			{
// 				if ($search['is_yidan'] == 1) $criteria->addCondition("is_yidan = 1");
// 				else $criteria->addCondition("ISNULL(t.is_yidan)");
// 			}
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
// 			if ($search['team_id'])
// 			{
// 				$criteria->addCondition("team_id = :team_id");
// 				$criteria->params[':team_id'] = $search['team_id'];
// 			}
			// if ($search['owned_by'])
			// {
			// 	$criteria->addCondition("baseform.owned_by = :owned_by");
			// 	$criteria->params[':owned_by'] = $search['owned_by'];
			// }
		}
		$criteria->compare("baseform.form_type", 'CGD', true);
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
			$_type = array('normal' => "库存采购", 'tpcg' => "托盘采购", 'xxhj' => "直销采购", 'dxcg' => "代销采购");
			$i = 1;
			foreach ($bill as $item) 
			{
				$mark = '';
				$operate = '';
				$da = array('data' => array());
				if ($item->baseform) 
				{
					$baseform = $item->baseform;
					$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill" yidan="'.$item->is_yidan.'" value="'.$baseform->id.'" />';
					$mark = $i;
					$i++;
// 					pledge.pledge_company_id
				} 
				
				$da['data'] = array($mark, 
						$operate, 
						$baseform->form_sn,
						$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
						'<span title="'.$item->supply->name.'">'.$item->supply->short_name.'</span>',
						number_format($item->weight, 3),
						'<span class="real_fee">'.number_format($item->price_amount, 2).'</span>', 
						$item->is_yidan == 1 ? '是' : '否',
						'<span class="pledge_company" title="'.$item->pledge->pledgeCompany->name.'">'.$item->pledge->pledgeCompany->short_name.'</span>',
						$_type[$item->purchase_type], //类型
						$item->team->name, //业务组
						$baseform->belong->nickname, //业务员
				);
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	public static function getSalverPurchaseList($search, $purchase_type = "")
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "11%"), //flex-col
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "8%"),
				array('name' => "供应商", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "重量", 'class' => "sort-disabled text-right", 'width' => "8%"),
				array('name' => "金额", 'class' => "sort-disabled text-right", 'width' => "8%"),
				array('name' => "预付款金额", 'class' => "sort-disabled text-right", 'width' => "8%"),
				array('name' => "乙单", 'class' => "sort-disabled", 'width' => "5%"),
				array('name' => "托盘公司", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "类型", 'class' => "sort-disabled", 'width' => "8%"),
				array('name' => "业务组", 'class' => "sort-disabled", 'width' => "9%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "9%"),
		);
	
		$tableData = array();
		$model = new FrmPurchase();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform', 'pledge');
	
		if ($purchase_type) // == "tpcg"
		{
			$criteria->addCondition("purchase_type = :purchase_type");
			$criteria->params[':purchase_type'] = $purchase_type;
		}
	
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
			if ($purchase_type == "tpcg" && $search['pledge_company_id'])
			{
				$criteria->addCondition("pledge.pledge_company_id = :pledge_company_id");
				$criteria->params[':pledge_company_id'] = $search['pledge_company_id'];
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
			if ($search['owned_by'])
			{
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		$criteria->compare("baseform.form_type", 'CGD', true);
		$criteria->compare("baseform.is_deleted", '0', true);
		$criteria->compare("baseform.form_status", "approve", true);
	
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['salverPurchase_list']) ? intval($_COOKIE['salverPurchase_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		
		$bill = $model->findAll($criteria);
		if (!$bill) return array($tableHeader, $tableData, $pages);
		$i = 1;
		foreach ($bill as $item)
		{
			$mark = '';
			$da = array();
			$baseform = $item->baseform;
			if ($baseform)
			{
				$mark = $i;
				$i++;
			}
			
			$da['data'] = array($mark,
					$baseform->form_sn,
					$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
					'<span title="'.$item->supply->name.'">'.$item->supply->short_name.'</span>',
					'<span class="weight">'.number_format($item->weight, 3).'</span>',
					number_format($item->price_amount, 2),
					'<span class="advance">'.number_format($item->pledge->advance, 2).'</span>',
					$item->is_yidan == 1 ? '是' : '否',
					'<span class="pledge_company" title="'.$item->pledge->pledgeCompany->name.'">'.$item->pledge->pledgeCompany->short_name.'</span>',
					FrmPurchase::$type[$item->purchase_type], //类型
					$item->team->name, //业务组
					$baseform->belong->nickname, //业务员
			);
			$da['group'] = $baseform->form_sn;
			array_push($tableData, $da);
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	/*
	 * 获取采购详细信息
	 */
	public static function getDetailData($id)
	{
		$model=CommonForms::model()->with('purchase','purchase.purchaseDetails')->findByPk($id);
		if($model)
		{
			$details=$model->purchase->purchaseDetails;
			return $details;
		}
		return false;
	}
	
	/*
	 * 获取主体信息
	 */
	public static function getMainInfo($id)
	{
		$model=CommonForms::model()->with('purchase')->findByPk($id);
		$return=array();
		if($model)
		{
			$purchase=$model->purchase;
			$return['title']=$purchase->title_id;
			$return['title_name']=$purchase->title->short_name;
			$return['supply_id']=$purchase->supply_id;
			$return['supply_name']=$purchase->supply->short_name;
			$return['contact']=$purchase->contact_id;
			$return['contact_name']=$purchase->contact->name;
			$return['mobile']=$purchase->contact->mobile;
			$return['form_sn']=$model->form_sn;
			$return['form_time']=$model->form_time;
			$return['team_id']=$purchase->team_id;
			$return['team']=$purchase->team->name;
			$return['owned_by']=$model->owned_by;
			$return['owned']=$model->belong->nickname;
			$return['warehouse_id']=$purchase->warehouse_id;
			$return['warehouse']=$purchase->warehouse->name;
			$return['transfer_number']=$purchase->transfer_number;
			$return['date_reach']=$purchase->date_reach?date('Y-m-d',$purchase->date_reach):date('Y-m-d',time());
			$return['reach_time']=$purchase->reach_time;
			if($purchase->purchase_type=='tpcg')
			{
				$pledge=$purchase->pledge;
				$return['fee']=$pledge->fee;
				$return['pledge_company_id']=$pledge->pledge_company_id;
				$return['advance']=$pledge->advance;
				$return['unit_price']=$pledge->unit_price;
				$return['r_limit_name']=$pledge->r_limit==1?'产地':'产地+品名';
				$return['r_limit']=$pledge->r_limit;
				$return['pledge_info_id']=$pledge->id;
				$return['purchase_id']=$purchase->id;
				
				$brands=array();
				$str='';
				$details=$purchase->purchaseDetails;
				foreach ($details as $each)
				{
					if(!in_array($each->brand_id, $brands))
					{
						array_push($brands, $each->brand_id);
						$str.='<option value="'.$each->brand_id.'">'.DictGoodsProperty::getProName($each->brand_id).'</option>';
					}
				}
				$return['brand_str']=$str;
				
			}
		}
		return json_encode($return);
	}
	
	/*
	 * 采购汇总信息
	 */
	public static function gatherData($search)
	{
		$tableHeader = array(
// 				array('name'=>'序号','class' =>"sort-disabled",'width'=>"44px"),
				array('name'=>'公司简称','class' =>"sort-disabled text-left",'width'=>"110px"),//修
				array('name'=>'钢厂/供应商','class' =>"sort-disabled text-left",'width'=>"110px"),//
				array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'运费','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),//
				array('name'=>'钢厂返利','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),//
				array('name'=>'未入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'未入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),//
				array('name'=>'应销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'应销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),//
				array('name'=>'已销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'已销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'未销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'未销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
		);
		$tableData=array();
		$model=PurchaseView::model();
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if($search['time_L']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(t.form_time) >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(t.form_time) <='.(strtotime($search['time_H'])+86400));
			}
			if($search['company']!='0')
			{
				$criteria->compare('t.title_id',$search['company']);
			}
			if($search['vendor']!='0')
			{
				$criteria->compare('t.supply_id',$search['vendor']);
			}
			if($search['brand']!='')
			{
				$criteria->compare('t.brand_id',$search['brand']);
			}
			if($search['confirm']!='2')
			{
				$criteria->compare('t.weight_confirm_status',$search['confirm']);
			}
			if($search['yidan']!='2')
			{
				$criteria->compare('t.is_yidan',$search['yidan']);
			}
			if($search['owned'])
			{
				$user=$search['owned'];
				$criteria->addCondition('owned_by ='.$user.' or created_by ='.$user);
			}
		}else{
			if(!checkOperation('采购汇总:查看全部'))
			{
				$user=Yii::app()->user->userid;
				$criteria->addCondition('owned_by ='.$user.' or created_by ='.$user);
			}
		}
		$criteria->compare('t.form_type','CGD');
		$criteria->compare('t.is_deleted','0');
		$criteria->addCondition('t.form_status!="unsubmit"');
		
		$criteria->group='t.title_id,t.supply_id';
		$new_cri=clone $criteria;
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_simlist']) ? intval($_COOKIE['purchase_simlist']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->select='t.title_id,t.title_short_name,t.supply_id,t.supply_name,t.supply_short_name,sum(t.detail_weight) as sum_weight,sum(t.detail_weight*t.detail_price) as sum_fee,sum(t.rebate*t.detail_weight) as total_rebate,'
				.'sum(t.detail_amount-t.detail_input_amount) as uninput_amount,sum(t.detail_weight-t.detail_input_weight) as  uninput_weight'
				.', sum(pid.weight) as total_checked_weight,sum(pid.fee) as total_checked_money,sum(b.price*t.detail_weight) as ship';
		$criteria->join='LEFT JOIN bill_record as b on t.common_id=b.frm_common_id LEFT JOIN purchase_invoice_detail as pid on pid.frm_purchase_detail_id=t.detail_id';
		$details=PurchaseView::model()->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$i=1;
			foreach ($details as $each)
			{
				$temp=clone $new_cri;
				$temp->compare('t.title_id', $each->title_id);
				$temp->compare('t.supply_id',$each->supply_id);
				$temp->compare('t.is_yidan', 0);
// 				$temp->select='sum(t.detail_weight) as  total_weight,sum(t.detail_weight*t.detail_price) as total_money';
// 				$ed=PurchaseView::model()->find($temp);
				$temp0=clone $temp;
				$temp->compare('t.weight_confirm_status',1);
				$temp->select='sum(t.detail_fix_weight) as  total_weight,sum(t.detail_fix_weight*t.detail_fix_price) as total_money';
				$ed=PurchaseView::model()->find($temp);
				$temp0->compare('t.weight_confirm_status',0);
				$temp0->select='sum(t.detail_weight) as  total_weight,sum(t.detail_weight*t.detail_price) as total_money';
				$ed0=PurchaseView::model()->find($temp0);
				$da['data']=array(
						$each->title_short_name,
						'<span title="'.$each->supply_name.'">'.$each->supply_short_name.'</span>',
						number_format($each->sum_weight,3),
						number_format($each->sum_fee,2),
						number_format($each->ship,2),
						number_format($each->total_rebate,2),
						$each->uninput_amount,
						number_format($each->uninput_weight,3),
						number_format($ed->total_weight+$ed0->total_weight,3),
						number_format($ed->total_money+$ed0->total_money,2),
						number_format($each->total_checked_weight,3),
						number_format($each->total_checked_money,2),
						number_format($ed->total_weight+$ed0->total_weight-$each->total_checked_weight,3),
						number_format($ed->total_money+$ed0->total_money-$each->total_checked_money,2),
				);
				$da['group']=$i;
				array_push($tableData,$da);
				$i++;
			}
		}
		return array($tableHeader,$tableData,$pages);
	}
	
	
	/*
	 * 获取托盘采购列表
	 * 供托盘赎回出使用
	 */
	public static function getTpcgList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"50px"),
				array('name'=>'采购单号','class' =>"flex-col sort-disabled",'width'=>"130px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'托盘公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'总重量','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
				array('name'=>'总件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'托盘金额','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'预付款','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'托盘单价','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'托盘限制等级','class' =>"flex-col sort-disabled ",'width'=>"110px"),//
				array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"90px"),//
		);
		$tableData=array();
		$model=FrmPurchase::model();
		$criteria=New CDbCriteria();
		$criteria->with=array('baseform','pledge');
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			$criteria->addCondition('baseform.form_sn like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']){
				$criteria->addCondition('baseform.created_at >='.strtotime($search['time_L']));
			}
			if($search['time_H']){
				$criteria->addCondition('baseform.created_at <='.(strtotime($search['time_H'])+86400));
			}
			if($search['title_id']){
				$criteria->compare('t.title_id',$search['title_id']);
			}
			if($search['company_id'])	{
				$criteria->compare('pledge.pledge_company_id',$search['company_id']);
			}
			if($search['owned'])	{
				$criteria->compare('baseform.owned_by',$search['owned']);
			}
		}
		$criteria->compare('baseform.form_type','CGD');
		$criteria->compare('baseform.is_deleted','0');
		$criteria->compare('baseform.form_status','approve');
		$criteria->addCondition('t.purchase_type="tpcg"');
		$criteria->join='left join pledge_redeemed as p on t.id=p.purchase_id ';
		$criteria->group='t.id';
		$criteria->having='t.weight>sum(p.weight) or isnull(sum(p.weight))';
		$criteria->select='t.*';
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="baseform.created_at DESC";
		$frmpur=$model->findAll($criteria);
		if($frmpur)
		{
			$da=array();
			$da['data']=array();
			$i=1;
			foreach ($frmpur as $each)
			{
				$baseform=$each->baseform;
				$pledge=$each->pledge;
				$operate='<input type="radio" name="selected_sales"  class="selected_sales"  value="'.$baseform->id.'" />';
				$da['data']=array($i,
						$operate,
						$baseform->form_sn,
						$baseform->form_time,
						$each->title->short_name,//
						'<span title="'.$pledge->pledgeCompany->name.'">'.$pledge->pledgeCompany->short_name.'</span>',
						number_format($each->weight,3),
						$each->amount,
						number_format($pledge->fee,2),
						number_format($pledge->advance,2),
						number_format($pledge->unit_price),
						$pledge->r_limit==1?'产地':'产地+品名',
						$baseform->belong->nickname,
				);
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
				$i++;
			}
		}
		return array($tableHeader,$tableData,$pages);
	}
	
	
	/*
	 * 托盘公司，采购公司，供应商之间的一个往来统计
	 */
	public static function BAT($search)
	{
		$tableHeader = array(
				array('name'=>'采购公司','class' =>"sort-disabled text-left",'width'=>"160px"),//
				array('name'=>'供应商','class' =>"sort-disabled text-left",'width'=>"160px"),//
				array('name'=>'托盘公司','class' =>"sort-disabled text-left",'width'=>"160px"),//
				array('name'=>'采购公司-托盘公司','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'供应商-托盘公司','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'采购重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'采购金额','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'托盘应付','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'托盘已付','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'托盘利息','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'托盘未付','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'代理应付','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'代理已付','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
// 				array('name'=>'代理未付','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
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
				$criteria->compare('t.supply_id', $search['vendor']);
			}
			if($search['pledge']){
				$criteria->compare('pledge.pledge_company_id', $search['pledge']);
			}
		}
		$criteria->compare('baseform.is_deleted','0');
		$criteria->compare('baseform.form_type','CGD');
		$criteria->addCondition('t.purchase_type="tpcg"');
		$criteria->addCondition('baseform.form_status!="unsubmit"');
		$criteria->group='t.title_id,t.supply_id,pledge.pledge_company_id';
		$criteria->join='left join common_forms as c on c.form_id=t.id and c.form_type="CGD" LEFT JOIN pledge_info as p on p.frm_purchase_id=t.id '
					.' LEFT JOIN turnover as turn on turn.common_forms_id=c.id and turn.target_id=p.pledge_company_id';
		$criteria->select='t.title_id,t.supply_id,pledge.pledge_company_id as com,sum(turn.fee) as total_money';
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
				$cri1=new CDbCriteria();
				$cri1->compare('status', 'submited');
				$cri1->addCondition('proxy_company_id="'.$each->com.'"');
				$cri1->compare('title_id', $each->title_id);
				$cri1->compare('target_id', $each->supply_id);
				$cri1->group='title_id,target_id';
				$cri1->select='sum(fee) as total_fee';
				$mo1=Turnover::model()->find($cri1);
				$da['data']=array(
						$each->title->short_name,//
						'<span title="'.$each->supply->name.'">'.$each->supply->short_name.'</span>',
						'<span title="'.$pledgeInfo->pledgeCompany->name.'">'.$pledgeInfo->pledgeCompany->short_name.'</span>',
						$each->total_money,
						$mo1->total_fee,
				);
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
				$mark++;
			}
		}
		return array($tableHeader,$tableData,$pages);
	}
	//判断采购单是否已销票
	public static function isBillDone($id) 
	{
		$purchase = FrmPurchase::model()->findByPK($id);
		$bill_done = 1;
		foreach ($purchase->purchaseDetails as $detail) 
		{
			if ($detail->bill_done == 0) { $bill_done = 0; break; }
		}
		$purchase->bill_done = $bill_done;
		$purchase->update();
		return $bill_done;
	}

	public static function getAllList($search, $type) 
	{
		$tableData = array();
		$model = new PurchaseView();
		$criteria = new CDbCriteria();

		//搜索
		if(!empty($search))
		{
			// $criteria->together = true;
			$criteria->addCondition('form_sn like :contno or comment like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']!='')
			{
				$criteria->addCondition('form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('form_time <="'.$search['time_H'].'"');#+86400));
			}
			if($search['reach_time_L'])
			{
				$criteria->addCondition('date_reach >='.strtotime($search['reach_time_L']));
			}
			if($search['reach_time_H'])
			{
				$criteria->addCondition('date_reach <='.strtotime($search['reach_time_H']));
			}
			if($search['company']!='0')
			{
				$criteria->compare('title_id',$search['company']);
			}
			if($search['vendor']!='0')
			{
				$criteria->compare('supply_id',$search['vendor']);
			}
			if($search['form_status']!='0')
			{
				$criteria->compare('form_status',$search['form_status']);
			}else{
				$criteria->compare('is_deleted','0');
			}
			// if($search['team'] != '0')
			// {
			// 	$criteria->compare('team_id',$search['team']);
			// }
			// if($search['owned']!='0')
			// {
			// 	$criteria->compare('owned_by',$search['owned']);
			// }
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('brand_id',$search['brand']);
			}
			if($search['product']!='0')
			{
				$criteria->compare('product_id',$search['product']);
			}
			if($search['rand']!='0')
			{
				$criteria->compare('rank_id',$search['rand']);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('texture_id',$search['texture']);
			}
		
			//审单状态，采购单类型，乙单
			if($search['confirm_status']!='')
			{
				$criteria->compare('weight_confirm_status', $search['confirm_status']);				
			}
			if($search['purchase_type']!='')
			{
				$criteria->compare('purchase_type', $search['purchase_type']);
			}
			if($search['is_yidan'])
			{
				$criteria->compare('is_yidan', $search['is_yidan']);
			}
			if($search['contract'])
			{
				$criteria->compare('contract_no', $search['contract'],true);
			}
			if($search['contract_array'])
			{
				$criteria->addInCondition('frm_contract_id',$search['contract_array']);
				$criteria->compare('form_status', 'approve');
			}
		}else{
			$criteria->compare('is_deleted','0');
		}

		switch ($type) {
			case 'index': 				
				$user = Yii::app()->user->userid;
				$criteria->addCondition('owned_by ='.$user.' or created_by ='.$user);
				break;
			case 'indexForStore': 
				if($search['owned']!='0')
				{
					$criteria->compare('t.owned_by',$search['owned']);
				} else {
					$criteria->compare('t.is_deleted','0');
		// 			$criteria->compare('form_status', 'approve');
				}
				break;
			case 'indexForCheck': 
				break;
			default: 
				return false;
				break;
		}
		// $criteria->compare('form_type','CGD');

		$newcri = clone $criteria;
		$newcri->select = "sum(detail_amount) as total_amount,sum(detail_weight) as total_weight,sum(detail_weight*detail_price) as total_money,count(*) as total_num";
		$all = $model->find($newcri);
		$totaldata = array();
		$totaldata[12] = $all->total_amount;
		$totaldata[13] = $all->total_weight;
		$totaldata[14] = $all->total_money;
		$totaldata[15] = $all->total_num;

		switch ($type) {
			case 'index': 				
				$criteria->order="created_at DESC";
				break;
			case 'indexForStore': 
				$criteria->order="locate(form_status,'submited,approve,unsubmit,,delete'),created_at DESC";
				// $criteria->join='LEFT JOIN input_detail_plan as input on input.purchase_detail_id=t.detail_id';
				// $criteria->select='t.*,sum(input.input_amount) as total_num';
				// $criteria->group='t.detail_id';
				break;
			case 'indexForCheck': 
				$criteria->order="locate(form_status,'submited,unsubmit,approve,delete'),created_at DESC";
	// 			$criteria->order="created_at DESC";
				break;
			default: 
				return false;
				break;
		}
		// $criteria->limit = 10;
		// $criteria->offset = 0;

		$details = $model->findAll($criteria);

		$content = array();
		if (!$details) return $content;
		
		foreach ($details as $each) {
			$temp = array(
				$each->form_sn, 
				$each->form_time ? $each->form_time : '', 
				FrmPurchase::$type[$each->purchase_type], 
				$each->supply_short_name, 
				$each->title_short_name, 
				$each->is_yidan == 1 ? '是' : '', 
				$each->product_name,
				$each->rank_name,
				$each->texture_name,
				$each->brand_name,
				$each->length, 
				numChange(number_format($each->detail_price)),
				$each->detail_amount, //12
				numChange(number_format($each->detail_weight, 3)), 
				numChange(number_format($each->detail_price * $each->detail_weight, 2)),
				$each->purchase_type == "tpcg" ? $each->pledge_short_name : '', 
				$each->purchase_type == "tpcg" ? numChange(number_format($each->pledge_unit_price)) : '0', 
				$each->purchase_type == "tpcg" ? numChange(number_format($each->pledge_fee, 2)) : '0.00', 
				$each->date_reach > 0 ? date('Y-m-d', $each->date_reach).'  '.$_time[$each->reach_time] : '',
				$each->comment, 
				$each->detail_input_amount, 
				numChange(number_format($each->detail_input_weight, 3)), 
				numChange(number_format($each->detail_fix_price)),
				$each->detail_fix_amount, 
				numChange(number_format($each->detail_fix_weight, 3)), 
				numChange(number_format($each->detail_fix_price * $each->detail_fix_weight, 2)), 
				$each->weight_confirm_status == 0 ? '未审单' : '已审单', 
				$each->bill_done == 0 ? '否' : '是', 
				$each->contract_no, 
				CommonForms::$formStatus[$each->form_status], 
				$each->form_status == 'approve' ? $each->approved_by_nickname : '',
				$each->form_status == 'approve' && $each->approved_at > 0 ? date('Y-m-d', $each->approved_at) : '', 
				$each->owned_by_nickname, 
				$each->team_name, 
				$each->created_by_nickname, 
				$each->last_updated_by ? $each->last_updated_by_nickname : $each->created_by_nickname
			);
			if ($search['form_status'] == 'delete') array_push($temp, $each->delete_reason);
			
			array_push($content, $temp);
		}
		array_push($content, $totaldata);

		return $content;
	}

	public static function getAllBATList($search) 
	{
		$model = new FrmPurchase();

		$criteria = new CDbCriteria();
		$criteria->with = array('baseform', 'pledge');
		$criteria->together = true;

		//搜索
		if (!empty($search)) {
			if ($search['company']) 
				$criteria->compare('t.title_id', $search['company']);
			if ($search['vendor']) 
				$criteria->compare('t.supply_id', $search['vendor']);
			if ($search['pledge']) 
				$criteria->compare('pledge.pledge_company_id', $search['pledge']);
		}
		$criteria->compare('baseform.is_deleted', '0');
		$criteria->compare('baseform.form_type', 'CGD');
		$criteria->addCondition("t.purchase_type = 'tpcg'");
		$criteria->addCondition("baseform.form_status <> 'unsubmit'");

		$criteria->group = "t.title_id, t.supply_id, pledge.pledge_company_id";
		$criteria->join = "LEFT JOIN common_forms c ON c.form_id = t.id AND c.form_type = 'CGD'"
		." LEFT JOIN pledge_info p ON p.frm_purchase_id = t.id"
		." LEFT JOIN turnover as turn ON turn.common_forms_id = c.id AND turn.target_id = p.pledge_company_id";
		$criteria->select = "t.title_id, t.supply_id, pledge.pledge_company_id as com, sum(turn.fee) as total_money";

		// $pages = new CPagination();
		// $pages->itemCount = FrmPurchase::model()->count($criteria);
		// $pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
		// $pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		
		$frmpurs = FrmPurchase::model()->findAll($criteria);
		$content = array();
		if (!$frmpurs) return $content;

		$total_money = 0; 
		$total_fee = 0;
		foreach ($frmpurs as $each) 
		{
			$baseform = $each->baseform;
			$pledgeInfo = $each->pledge;

			$cri1 = new CDbCriteria();
			$cri1->compare('status', "submited");
			$cri1->compare('title_id', $each->title_id);
			$cri1->compare('target_id', $each->supply_id);
			$cri1->addCondition("proxy_company_id = :proxy_company_id");
			$cri1->params[':proxy_company_id'] = $each->com;
			$cri1->group = "title_id, target_id";
			$cri1->select = "sum(fee) as 'total_fee'";
			$mo1 = Turnover::model()->find($cri1);

			$temp = array(
				$each->title->short_name, 
				$each->supply->short_name, 
				$pledgeInfo->pledgeCompany->short_name, 
				numChange(number_format($each->total_money, 2)), 
				numChange(number_format($mo1->total_fee, 2)), 
			);
			$total_money += $each->total_money ? numChange(number_format($each->total_money, 2)) : 0;
			$total_fee += $mo1->total_fee ? numChange(number_format($mo1->total_fee, 2)) : 0;
			array_push($content, $temp);
		}
		array_push($content, array('', '', '', 
			$total_money, 
			$total_fee,
		));

		return $content;
	}
	
	
	public static  function getInvoice($id)
	{
		$price='';
		$brand=DictGoodsProperty::model()->findByPk($id);
		if($brand){			
			$xml=readConfig();
			foreach ($xml->invoice->cost as $each)
			{
				if($each->brand==$brand->short_name)
				{
					$price=$each->price;
					break;
				}
			}
			echo $price;
		}
	}
	
}
