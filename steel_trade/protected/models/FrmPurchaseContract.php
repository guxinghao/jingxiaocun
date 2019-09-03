<?php

/**
 * This is the biz model class for table "frm_purchase_contract".
 *
 */
class FrmPurchaseContract extends FrmPurchaseContractData
{
	
	public $sum_weight;
	public $sum_fee;
	public $sum_purchase_weight;
	public $sum_purchase_fee;
	public $sum_feed;
	public $sum_weighted;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'dictCompany' => array(self::BELONGS_TO, 'DictCompany', 'dict_company_id'),
			'dictTitle' => array(self::BELONGS_TO, 'DictTitle', 'dict_title_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'purchaseContractDetails' => array(self::HAS_MANY, 'PurchaseContractDetail', 'purchase_contract_id'),
			'team'=>array(self::BELONGS_TO,'Team','team_id'),
			'contact'=>array(self::BELONGS_TO,'CompanyContact','contact_id'),
			'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="CGHT" and baseform.is_deleted=0'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'contract_no' => 'Contract No',
			'dict_company_id' => 'Dict Company',
			'dict_title_id' => 'Dict Title',
			'team_id' => 'Team',
			'is_yidan' => 'Is Yidan',
			'contact_id' => 'ContackId',
			'warehouse_id' => 'Warehouse',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'purchase_amount' => 'Purchase Amount',
			'purchase_weight' => 'Purchase Weight',
			'is_finish' => 'Is Finish',
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
		$criteria->compare('contract_no',$this->contract_no,true);
		$criteria->compare('dict_company_id',$this->dict_company_id);
		$criteria->compare('dict_title_id',$this->dict_title_id);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('contact_id',$this->contack_id,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('purchase_amount',$this->purchase_amount);
		$criteria->compare('purchase_weight',$this->purchase_weight);
		$criteria->compare('is_finish',$this->is_finish);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPurchaseContract the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/*
	 * 获取表单总数量
	 *
	 */
	public  static function getNum()
	{
		
	}
	
	/*
	 * 获取采购合同列表
	 */
	public static function getFormList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"28px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'单号','class' =>"sort-disabled",'width'=>"150px"),
				array('name'=>'合同编号','class' =>"flex-col sort-disabled",'width'=>"150px"),//修
				array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"120px"),
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"110px"),
				array('name'=>'采购公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"120px"),//
				array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"120px"),//
				array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'单价','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
				array('name'=>'件数','class' =>"flex-col sort-disabled text-right",'width'=>"90px"),//
				array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//
				array('name'=>'金额','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),//	
				array('name'=>'总件数','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),//
				array('name'=>'总重量','class' =>"flex-col sort-disabled text-right",'width'=>"160px"),//
				array('name'=>'总金额','class' =>"flex-col sort-disabled text-right",'width'=>"200px"),//
				
				array('name'=>'履约','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"100px"),//
				
				array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"100px"),//
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
		$tableData=array();		
		$model=ContractView::model();
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			$criteria->addCondition('contract_no like :contno or form_sn like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(form_time) >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(form_time) <='.(strtotime($search['time_H'])+86400));
			}
			if($search['company']!='0')
			{
				$criteria->compare('dict_title_id',$search['company']);
			}
			if($search['vendor']!='0')
			{
				$criteria->compare('dict_company_id',$search['vendor']);
			}
			if($search['form_status']!='0')
			{
				$criteria->compare('form_status',$search['form_status']);
			}else{
				$criteria->compare('is_deleted','0');
			}
			if($search['team']!='0')
			{
				$criteria->compare('team_id',$search['team']);
			}
			if($search['owned']!='0')
			{
				$criteria->compare('owned_by',$search['owned']);
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
		}else{
			$criteria->compare('is_deleted','0');
		}
		$criteria->compare('form_type','CGHT');		
		
		$newcri=clone $criteria;
		$newcri->select = "sum(detail_amount) as total_amount,sum(detail_weight) as total_weight,sum(detail_weight*detail_price) as total_money,count(*) as total_num";
		$all=ContractView::model()->find($newcri);
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		$totaldata["money"] = $all->total_money;
		$totaldata["total_num"] = $all->total_num;		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['contract_list']) ? intval($_COOKIE['contract_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="is_finish ASC,created_at DESC";
		$details=$model->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$baseform='';
			$i=1;
			foreach ($details as $each)
			{
				//操作的链接地址				
				$mark=$i;
				if($each->form_sn!=$baseform)
				{
					$baseform=$each->form_sn;
					$i++;
					$title_sub='';
					$edit_url = Yii::app()->createUrl('contract/update',array('id'=>$each->common_id,'last_update'=>$each->last_update,'fpage'=>$_REQUEST['fpage']));
					if($each->form_status==='unsubmit')
					{
						$type_sub="submit";
						$title_sub="确定要提交采购合同".$each->form_sn.'吗？';
						$title='提交';
						$img_url = "/images/tijiao.png";
					}elseif($each->form_status==='submited')
					{
						$type_sub="cancle";
						$title_sub="确定要取消提交采购合同".$each->form_sn.'吗？';
						$title='取消提交';
						$img_url = "/images/qxtj.png";
					}
					$storeBuy_url=Yii::app()->createUrl('purchase/create',array('id'=>$each->common_id,'type'=>'normal','last_update'=>$each->last_update,'fpage'=>$_REQUEST['page']));
// 					$dxBuy_url=Yii::app()->createUrl('purchase/createDxcgStepOne',array('id'=>$each->common_id,'type'=>'dxcg','last_update'=>$each->last_update));
					$sub_url =  Yii::app()->createUrl('contract/submit',array('id'=>$each->common_id,'type'=>$type_sub,'last_update'=>$each->last_update));
					$del_url= Yii::app()->createUrl('contract/deleteform',array('id'=>$each->common_id,'last_update'=>$each->last_update));
					$checkP_url=Yii::app()->createUrl('contract/check',array('id'=>$each->common_id,'type'=>'pass','last_update'=>$each->last_update));
					$checkD_url=Yii::app()->createUrl('contract/check',array('id'=>$each->common_id,'type'=>'deny','last_update'=>$each->last_update));
					$checkC_url=Yii::app()->createUrl('contract/check',array('id'=>$each->common_id,'type'=>'cancle','last_update'=>$each->last_update));
					$finish_url=Yii::app()->createUrl('contract/finished',array('id'=>$each->common_id,'type'=>"finish",'last_update'=>$each->last_update));				
					$cancelfinish_url=Yii::app()->createUrl('contract/finished',array('id'=>$each->common_id,'type'=>"cancel",'last_update'=>$each->last_update));
					$detail_url=Yii::app()->createUrl('contract/view',array('id'=>$each->common_id,'fpage'=>$_REQUEST['page'],'search_url'=>json_encode($search)));
					$print_url = Yii::app()->createUrl('print/printA4', array('id' => $each->common_id));
					
					$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
					if (checkOperation("打印")) {
						$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span>';
					}
// 					$operate.='<a class="update_b" href="'.$detail_url.'" title="查看详情"><i class="icon icon-file-text-o"></i></a>';					
					//未提交状态
					if($each->form_status=='unsubmit'&&checkOperation("采购合同:新增"))
					{
						$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title.'" name="'.$title_sub.'"><img src="'.$img_url.'"></span>';//提交与取消提交
						$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a>';
						$operate.='<span class="delete_form" url="'.$del_url.'" title="作废"><img src="/images/zuofei.png"></span>';						
					}					
					//已提交
					if($each->form_status=='submited')
					{
						if(checkOperation("采购合同:新增"))
						{
							$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title.'" name="'.$title_sub.'"><img src="'.$img_url.'"></span>';//提交与取消提交
							$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a>';
						}
						if(checkOperation("采购合同:审核"))
						{
							$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$each->form_sn.',确定审核通过此采购合同吗？"><img src="/images/shenhe.png"></span>';
						}
					}					
					//已审核
					if($each->form_status=='approve')
					{
						if(checkOperation("采购单:新增")&&$each->is_finish==0)
						{
							$operate.='<a class="update_b" href="'.$storeBuy_url.'" title="库存采购"><span><img src="/images/caigou.png"></span></a>';
						}						
						if(checkOperation("采购合同:审核"))
						{
							$operate.='<span class="submit_form" url="'.$checkC_url.'" title="取消审核" name="确定要取消审核采购合同'.$each->form_sn.'吗？"><img src="/images/qxsh.png"></span>';
							if ($each->is_finish == 1){
								$operate .= '<span class="submit_form" url="'.$cancelfinish_url.'" title="取消履约" name="确定要取消履约采购合同'.$each->form_sn.'吗？"><img src="/images/qxly.png"></span>';
							}else{  $operate .= '<span class="submit_form" url="'.$finish_url.'" title="履约" name="确定要履约采购合同'.$each->form_sn.'吗？"><img src="/images/lvyue.png"></span>';}
						}
					}
					$operate.='</div>';
					
				}else{
					$mark='';
					$operate='';
				}
				if($search['form_status']=='delete'){
					$operate='';
				}
				$da['data']=array($mark,
						$operate,
						'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$each->form_sn.'</a>',
						$each->contract_no,
						$_status[$each->form_status],//审批状态
						$each->form_time?$each->form_time:'',
						'<span title="'.$each->dict_company_name.'">'.$each->dict_company_short_name.'</span>',
						$each->title_short_name,
						$each->product_name,
						$each->rank_id,
						str_replace('E', '<span class="red">E</span>',$each->texture_id),
						$each->brand_name,
						number_format($each->detail_price),
						$each->detail_amount,
						number_format($each->detail_weight,3),
						number_format($each->detail_weight*$each->detail_price,2),
						$each->main_purchase_amount.'/'.$each->main_amount,
						number_format($each->main_purchase_weight,3).'/'.number_format($each->main_weight,3),
						number_format($each->main_purchase_fee,2).'/'.number_format($each->main_fee,2),						
						
						$each->is_finish==0?"未履约":'已履约',//合同状态
						$each->team_name,//业务组
						$each->owned_by_nickname,//业务员
						$each->created_by_nickname,//创建人
						$each->last_updated_by?$each->last_updated_by_nickname:$each->created_by_nickname,//修改人			
						'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment, 0,15,"utf-8").'</span>',
				);
				if($search['form_status']=='delete'){
					$re='<span title="'.htmlspecialchars($each->delete_reason).'">'.mb_substr($each->delete_reason, 0,15,"utf-8").'</span>';
					array_push($da['data'], $re);
					array_splice($da['data'], 1,1);
				}
				$da['group']=$each->form_sn;
				array_push($tableData,$da);
			}
// 			if($search['form_status']=='delete')
// 			{
// 				$value1['data']=array(
// 						'','总计','','','','','','','','','','',$totaled_a.'/'.$total_a,number_format($totaled_w,3).'/'.number_format($total_w,3),
// 							number_format($total_ed,2).'/'.number_format($total_m,2),'','','','','','','',
// 				);
// 			}else{
// 				$value1['data']=array(
// 						'','总计','','','','','','','','','','','',$totaled_a.'/'.$total_a,number_format($totaled_w,3).'/'.number_format($total_w,3),
// 							number_format($total_ed,2).'/'.number_format($total_m,2),'','','','','','',
// 				);
// 			}
// 			$value1['group']=1;
// 			array_push($tableData, $value1);
		}
		return array($tableHeader,$tableData,$pages,$totaldata);
	
	}
	
	
	/*
	 * 获取简单采购合同列表
	 * 采购单处使用
	 */
	public static function getFormSimpleList($search)
	{
		$tableData=array();
		$model=FrmPurchaseContract::model()->with(array('baseform'));
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			$criteria->addCondition('t.contract_no like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.created_at >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.created_at <='.(strtotime($search['time_H'])+86400));
			}
			if($search['company']!='0')
			{
				$criteria->compare('t.dict_title_id',$search['company']);
			}
			if($search['vendor']!='0')
			{
				$criteria->compare('t.dict_company_id',$search['vendor']);
			}
			if($search['team']!='0')
			{
				$criteria->compare('t.team_id',$search['team']);
			}
		}
		$criteria->compare('t.is_finish','0');
		$criteria->compare('baseform.form_type','CGHT');
		$criteria->compare('baseform.is_deleted','0');
		$criteria->compare('baseform.form_status','approve');
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
// 		$pages->pageSize=2;
		$pages->pageSize =intval($_COOKIE['contract_simlist']) ? intval($_COOKIE['contract_simlist']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
	
		$contracts=FrmPurchaseContract::model()->with(array('baseform'=>array('order'=>'baseform.created_at DESC')))->findAll($criteria);
		if($contracts)
		{
			$da=array();
			$da['data']=array();
			$i=1;
			foreach ($contracts as $each)
			{
				$baseform=$each->baseform;
				$operate='<input type="radio" name="selected_contract"  class="selected_contract"  value="'.$baseform->id.'" />';
				$da['data']=array($i,
						$operate,
						$baseform->form_sn,
						$each->contract_no,
						($baseform->created_at>943891200)?date('Y-m-d',$baseform->created_at):'--',
						$each->dictCompany->short_name,
						$each->dictTitle->short_name,
						number_format($each->purchase_weight,3).'/'.number_format($each->weight,3),
						$each->purchase_amount.'/'.$each->amount,
						//已执行量
// 						number_format($each->weight-$each->purchase_weight,3),//wei
// 						'',//yiruku
// 						'',//weiruku
// 						$each->is_finish==0?"未履约":'已履约',//合同状态
				);
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
				$i++;
			}
		}
		return array($tableData,$pages);
	
	}
	
	
	
	/*
	 * 获取用户输入数据
	 */	
	public static function getInputData($post)
	{
		$post['CommonForms']['form_type']='CGHT';
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmPurchaseContract'];
		$data['detail']=array();
		$contractAmount=0;
		$contractWeight=0;
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
			
			$contractAmount+=$temp['amount'];
			$contractWeight+=$temp['weight'];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']['amount']=$contractAmount;
		$data['main']['weight']=$contractWeight;
		$data['main']=(Object)$data['main'];		
		return $data;
	}
	
	/*
	 * 获取用户更新数据
	 */
	public static function getUpdateData($post)
	{
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmPurchaseContract'];
		$data['detail']=array();
		$contractAmount=0;
		$contractWeight=0;
		//以前的商品
		for($i=0;$i<count($post['old_td_id']);$i++)
		{
			if($post['old_td_brands'][$i]=='')
			{
				continue;
			}
			$temp=array();
			$temp['id']=$post['old_td_id'][$i];
			$temp['product_id']=$post['old_td_products'][$i];
			$temp['texture_id']=$post['old_td_textures'][$i];
			$temp['brand_id']=$post['old_td_brands'][$i];
			$temp['rank_id']=$post['old_td_ranks'][$i];
			$temp['length']=$post['old_td_length'][$i];
			$temp['price']=numChange($post['old_td_price'][$i]);
			$temp['amount']=$post['old_td_amount'][$i];
			$temp['weight']=$post['old_td_weight'][$i];
			$contractAmount+=$temp['amount'];
			$contractWeight+=$temp['weight'];
			array_push($data['detail'], (Object)$temp);
		}
		//新增商品
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
			$contractAmount+=$temp['amount'];
			$contractWeight+=$temp['weight'];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']['amount']=$contractAmount;
		$data['main']['weight']=$contractWeight;
		$data['main']=(Object)$data['main'];
		return $data;
	}
	public static function getDetailData($id)
	{
		$model=CommonForms::model()->with('contract','contract.purchaseContractDetails')->findByPk($id);
		if($model)
		{
			$details=$model->contract->purchaseContractDetails;
			return $details;
		}
		return false;
	}
	
	public  static function giveContData($id)
	{
		$return=array();
		$model=CommonForms::model()->with('contract')->findByPk($id);
		$contract=$model->contract;
		if($model)
		{
			$return['contract_no']=$contract->contract_no;
			$return['dict_company_id']=$contract->dict_company_id;
			$return['company_name']=$contract->dictCompany->short_name;
			$return['dict_title_id']=$contract->dict_title_id;
			$return['title_name']=$contract->dictTitle->short_name;
			$return['team_id']=$contract->team_id;
			$return['team_name']=$contract->team->name;
			$return['warehouse_id']=$contract->warehouse_id;
			$return['warehouse']=$contract->warehouse->name;
			$return['contact_id']=$contract->contact_id;
			$return['contact']=$contract->contact->name;
			$return['mobile']=$contract->contact->mobile;
			$return['owned']=$model->owned_by;
			
		}
		return json_encode($return);
	}
	
	
	/*
	 * 汇总信息
	 */
	public static function gatherData($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"44px"),
				array('name'=>'公司','class' =>"sort-disabled text-left",'width'=>"110px"),//修
				array('name'=>'供应商','class' =>"sort-disabled text-left",'width'=>"110px"),//
				array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),
				array('name'=>'金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'已执行重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),//
				array('name'=>'已执行金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),//
				array('name'=>'未履约重量','class' =>"flex-col sort-disabled text-right",'width'=>"140px"),//
				array('name'=>'未履约金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),//
		);
		
		$tableData=array();
		$model=FrmPurchaseContract::model()->with(array('baseform'));
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if($search['time_L']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(baseform.form_time) >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(baseform.form_time) <='.(strtotime($search['time_H'])+86400));
			}
			if($search['company']!='0')
			{
				$criteria->compare('t.dict_title_id',$search['company']);
			}
			if($search['vendor']!='0')
			{
				$criteria->compare('t.dict_company_id',$search['vendor']);
			}
		}
		$criteria->compare('baseform.form_type','CGHT');
		$criteria->compare('baseform.is_deleted','0');
		$criteria->addCondition('baseform.form_status!="unsubmit"');
		$criteria->group='dict_title_id,dict_company_id';
		$new_cri=clone $criteria;
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['contract_simlist']) ? intval($_COOKIE['contract_simlist']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->select='sum(weight) as sum_weight,sum(fee) as sum_fee,dict_title_id,dict_company_id,sum(purchase_fee) as sum_purchase_fee,sum(purchase_weight) as sum_purchase_weight';
		$contracts=FrmPurchaseContract::model()->with(array('baseform'=>array('order'=>'baseform.created_at DESC')))->findAll($criteria);
		if($contracts)
		{
			$da=array();
			$da['data']=array();
			$i=1;
			foreach ($contracts as $each)
			{
				$contract_url=Yii::app()->createUrl('contract/index',array('search_data'=>json_encode($search),'title'=>$each->dict_title_id,'company'=>$each->dict_company_id));
				$purchase_url=Yii::app()->createUrl('purchase/index',array('search_data'=>json_encode($search),'title'=>$each->dict_title_id,'company'=>$each->dict_company_id));
				$temp=clone $new_cri;
				$temp->compare('dict_title_id', $each->dict_title_id);
				$temp->compare('dict_company_id',$each->dict_company_id);
				$temp->compare('is_finish','0');
				$temp->select='sum(fee) as  sum_feed,sum(weight) as sum_weighted';
				$ed=FrmPurchaseContract::model()->with('baseform')->find($temp);
				$da['data']=array($i,
						$each->dictTitle->short_name,
						'<span title="'.$each->dictCompany->name.'">'.$each->dictCompany->short_name.'</span>',
						'<a target="_blank" href="'.$contract_url.'">'.number_format($each->sum_weight,3).'</a>',
						number_format($each->sum_fee,2),
						'<a target="_blank" href="'.$purchase_url.'">'.number_format($each->sum_purchase_weight,3).'</a>',
						number_format($each->sum_purchase_fee,2),
						number_format($ed->sum_weighted-$each->sum_purchase_weight,3),
						number_format($ed->sum_feed-$each->sum_purchase_fee,2),
				);
				$da['group']=$i;
				array_push($tableData,$da);
				$i++;
			}
		}
		return array($tableHeader,$tableData,$pages);		
	}
	
	/*
	 * 获取采购合同ID数组
	 */
	public static function getIdArray($timeL,$timeH,$title,$company)
	{
		$model=FrmPurchaseContract::model()->with(array('baseform'));
		$criteria=New CDbCriteria();
		//搜索
		$criteria->together=true;
		if($timeL!='')
		{
			$criteria->addCondition('baseform.created_at >='.strtotime($timeL));
		}
		if($timeH!='')
		{
			$criteria->addCondition('baseform.created_at <='.(strtotime($timeH)+86400));
		}
		if($title!='0')
		{
			$criteria->compare('t.dict_title_id',$title);
		}
		if($company!='0')
		{
			$criteria->compare('t.dict_company_id',$company);
		}
		$criteria->compare('baseform.form_type','CGHT');
		$criteria->compare('baseform.is_deleted','0');
		$criteria->addCondition('baseform.form_status!="unsubmit"');		
		$contracts=FrmPurchaseContract::model()->with('baseform')->findAll($criteria);
		$return = array();
		if($contracts)
		{
			foreach ($contracts as $each)
			{
				array_push($return, $each->baseform->id);
			}
		}
		return $return;
	}

}
