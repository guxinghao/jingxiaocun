<?php

/**
 * This is the biz model class for table "frm_input".
 *
 */
class FrmInput extends FrmInputData
{
	
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'baseform_pur' => array(self::BELONGS_TO, 'CommonForms', 'purchase_id'),
			'inputDetails' => array(self::HAS_MANY, 'InputDetail', 'input_id'),
			'storages' => array(self::HAS_MANY, 'Storage', 'frm_input_id','condition'=>'storages.is_dx=0 and storages.is_deleted=0'),
			'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="RKD" '),
			'push'=>array(self::BELONGS_TO,'PushedStorage','push_id'),
			'warehouse'=>array(self::BELONGS_TO,'Warehouse','warehouse_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'input_type' => 'Input Type',
			'purchase_id' => 'Purchase',
			'input_date' => 'Input Date',
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
		$criteria->compare('input_type',$this->input_type,true);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('input_date',$this->input_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmInput the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 获取入库单列表
	 */
	public static function getInputList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),
				array('name'=>'入库单号','class' =>" sort-disabled",'width'=>"80px"),
				array('name'=>'采购/退货单号','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"78px"),
				array('name'=>'供应商/客户','class' =>"flex-col sort-disabled",'width'=>"80px"),
				array('name'=>'公司','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'入库仓库','class' =>"flex-col sort-disabled ",'width'=>"60px"),//
				array('name'=>'卡号','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'产地/品名/材质/规格/长度','class' =>"flex-col sort-disabled",'width'=>"210px"),//
// 				array('name'=>'已入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
				array('name'=>'入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//
				array('name'=>'入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'入库类型','class' =>"flex-col sort-disabled",'width'=>"70px"),//
				array('name'=>'乙单','class' =>"flex-col sort-disabled",'width'=>"40px"),//				
				array('name'=>'制单人','class' =>"flex-col sort-disabled",'width'=>"70px"),//
				array('name'=>'入库人','class' =>"flex-col sort-disabled",'width'=>"70px"),//
				array('name'=>'入库时间','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'备注','class' =>"flex-col sort-disabled",'width'=>"210px"),//
				);
		if($search['input_status']=='delete')
		{
			$reason=array('name'=>'作废原因','class' =>"flex-col sort-disabled",'width'=>"210px");
			array_push($tableHeader, $reason);
			array_splice($tableHeader, 1,1);
		}
		if($search['input_type']=='ccrk')
		{
			$copy=$tableHeader;
			$arr=array(
					array('name'=>'预计到货时间','class' =>"flex-col sort-disabled",'width'=>"190px"),
					array('name'=>'货物状态','class' =>"flex-col sort-disabled",'width'=>"100px")
			);
			$first=array_splice($tableHeader,0,15);
			$last=array_splice($copy, 15);
			$tableHeader=array_merge($first,$arr,$last);
		}
		$tableData=array();		
		$model=InputDetail::model();
		$criteria=New CDbCriteria();
		//搜索
		$criteria->with=array('input','input.baseform','input.baseform_pur.purchase','input.baseform_pur.salesReturn');
		
		if(!empty($search))
		{
			$criteria->together=true;
			$criteria->addCondition('baseform.form_sn like :contno or baseform_pur.form_sn like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.created_at >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.created_at <='.(strtotime($search['time_H'])+86400));
			} 			
			if($search['vendor']!='')
			{
				$criteria->compare('purchase.supply_id',$search['vendor']);
				$criteria->addCondition('input.input_type!="thrk"');
			}
			if($search['input_status']!='-1')
			{
				if($search['input_status']=='delete')
				{					
					$criteria->compare('baseform.is_deleted',1);
				}else{
					$criteria->compare('input.input_status',$search['input_status']);
				}				
			}else{
				$criteria->compare('baseform.is_deleted','0');
			}
			if($search['input_type']!='0')
			{
				$criteria->compare('input.input_type',$search['input_type']);
			}else{
				$criteria->addCondition('input.input_type!="ccrk"' );
			}
			if($search['warehouse'])
			{
				$criteria->compare('input.warehouse_id',$search['warehouse']);
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
		}else{
			$criteria->compare('baseform.is_deleted','0');
			$criteria->addCondition('input.input_type!="ccrk"' );			
		}
		$newcri=clone $criteria;
// 		$newcri->with=array('input','input.baseform');
		$newcri->select = "sum(t.input_amount) as total_amount,sum(t.input_weight) as total_weight,count(*) as total_num";
		$all=InputDetail::model()->find($newcri);
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		$totaldata["total_num"] = $all->total_num;
		$pages = new CPagination();
		$pages->itemCount =$model->count($criteria);
		$pages->pageSize =intval($_COOKIE['input_list']) ? intval($_COOKIE['input_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order='baseform.created_at DESC';
		$details=$model->findAll($criteria);

		
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$_type=array('purchase'=>'采购入库','thrk'=>"销售退货",'ccrk'=>"船舱入库","dxrk"=>"代销入库",'qt'=>'其他');
			$_time=array('6'=>'00:00-06:00','12'=>'06:00-12:00','18'=>'12:00-18:00','24'=>'18:00-24:00');
			$baseform='';
			$i=1;
			if($search['input_type']=='ccrk')
			{
				$controller='inputCcrk';
			}else{
				$controller='input';
			}
			foreach ($details as $each)
			{
				$input=$each->input;
				if($input->input_type=='thrk')
				{
					$purchase=$input->baseform_pur->salesReturn;
					$purchasedetail=$each->returnDetail;
				}else{
					$purchase=$input->baseform_pur->purchase;
					$purchasedetail=$each->purchaseDetail;
				}
				
				$mark=$i;
				if($each->input->baseform!=$baseform)
				{
					$baseform=$each->input->baseform;
					$i++;
					if($input->from!="storage")
					{
						if($input->input_type=='thrk')
						{
							$edit_url = Yii::app()->createUrl($controller.'/updateByReturn',array('id'=>$baseform->id,'type'=>$input->input_type,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['fpage']));
						}else{
							$edit_url = Yii::app()->createUrl($controller.'/update',array('id'=>$baseform->id,'type'=>$input->input_type,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['fpage']));
						}						
					}else{
						$edit_url = Yii::app()->createUrl($controller.'/updateByPush',array('id'=>$baseform->id,'type'=>$input->input_type,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['fpage']));
					}
					$relstore_url = Yii::app()->createUrl($controller.'/relStore',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['fpage']));
					$del_url= Yii::app()->createUrl($controller.'/deleteform',array('id'=>$baseform->id,'last_update'=>$baseform->last_update));
					$checkP_url=Yii::app()->createUrl($controller.'/check',array('id'=>$baseform->id,'type'=>'pass','last_update'=>$baseform->last_update));
					$checkD_url=Yii::app()->createUrl($controller.'/check',array('id'=>$baseform->id,'type'=>'deny','last_update'=>$baseform->last_update));
					$checkC_url=Yii::app()->createUrl($controller.'/check',array('id'=>$baseform->id,'type'=>'cancle','last_update'=>$baseform->last_update));
					$detail_url=Yii::app()->createUrl($controller.'/view',array('id'=>$baseform->id,'type'=>$input->input_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'input/index','search_url'=>json_encode($search)));
					
					if($input->input_type=='thrk')
					{
						$purchasedetail_url=Yii::app()->createUrl('salesReturn/view',array('id'=>$input->purchase_id,'fpage'=>$_REQUEST['page'],'backUrl'=>'input/index','search_url'=>json_encode($search)));
					}else{
						$purchasedetail_url=Yii::app()->createUrl('purchase/view',array('id'=>$input->purchase_id,'type'=>$purchase->purchase_type,'fpage'=>$_REQUEST['page'],'backUrl'=>'input/index','search_url'=>json_encode($search)));
					}					
					$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" thisid="'.$baseform->id.'" value="'.$baseform->form_sn.'">';
// 					$operate='<a class="update_b" href="'.$detail_url.'" title="查看详情"><i class="icon icon-file-text-o"></i></a>';
					if($input->input_type=="ccrk"&&$input->input_status=='1'&&checkOperation("入库单:入库"))
					{
						$operate.='<a class="update_b"  href="'.$edit_url.'" title="编辑"><span class="margintop1" ><img src="/images/bianji.png"></span></a>';
						$operate.='<a class="update_b" href="'.$relstore_url.'" title="真实入库"><span><img src="/images/ruku.png"></span></a>';
					}
					if ($baseform->form_status=='submited'||$baseform->form_status=='unsubmit'&&checkOperation("入库单:新增")){
						$operate.='<span class="delete_form" url="'.$del_url.'" title="作废"><span><img src="/images/zuofei.png"></span></span>';
					}
					if($baseform->form_status=='submited'||$baseform->form_status=='unsubmit')
					{
						if(checkOperation("入库单:新增"))
						{
							$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span class="margintop1" ><img src="/images/bianji.png"></span></a>';
						}
						if(checkOperation("入库单:入库"))
						{
							$operate.='<span class="submit_form" url="'.$checkP_url.'" title="入库" str="input"><img src="/images/ccrk.png"></span>';
						}						
					}elseif($baseform->form_status=='approve'&&checkOperation("入库单:入库"))
					{
						$operate.='<span class="submit_form" url="'.$checkC_url.'"   title="取消入库"><img src="/images/qxrk.png"></span>';
					}
					$operate.='</div>';
				}else{
					$mark='';
					$operate='';
				}
				$da['data']=array($mark,
						$operate,
						'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$baseform->form_sn.'</a>',
						'<a title="查看单据详情" href="'.$purchasedetail_url.'" class="a_view">'.$input->baseform_pur->form_sn.'</a>',
						$input->input_status==0?'未入库':'已入库',//审批状态
						($baseform->created_at>943891200)?date('Y-m-d',$baseform->created_at):'--',
						$input->input_type=='thrk'?'<span title="'.$purchase->company->name.'">'.$purchase->company->short_name.'</span>':'<span title="'.$purchase->supply->name.'">'.$purchase->supply->short_name.'</span>',
						$purchase->title->short_name,
						$input->warehouse->name,
						$each->card_id,
						DictGoodsProperty::getProName($each->brand_id).'/'.DictGoodsProperty::getProName($each->product_id).'/'.str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)).'/'.DictGoodsProperty::getProName($each->rank_id).'/'.$each->length,
// 						$purchasedetail->input_amount,
// 						number_format($purchasedetail->input_weight,3),
						$each->input_amount,
						number_format($each->input_weight,3),
						$_type[$input->input_type],
						$purchase->is_yidan==1?"是":"",						
						$baseform->operator->nickname,
						$baseform->approver->nickname,
						$baseform->approved_at>943891200?date('Y-m-d',$baseform->approved_at):'',
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,'utf-8').'</span>',
				);
				$inputdate=date('Y-m-d',$input->input_date).' '.$_time[$input->input_time];				
				if($search['input_type']=='ccrk')
				{
// 					$goods_status=$input->goods_status==1?'在厂里等装货':'在厂里在装货';
					array_splice($da['data'],15,0,$inputdate);
// 					array_splice($da['data'],16,0,$goods_status);
				}
				if($search['input_status']=='delete'){
					$re='<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason, 0,15,'utf-8').'</span>';
					array_push($da['data'], $re);
					array_splice($da['data'], 1,1);
				}
				$da['group']=$baseform->form_sn;
				array_push($tableData,$da);
			}
		}
		return array($tableHeader,$tableData,$pages,$totaldata);
	}
	
	public static function getInputData($post)
	{
		$post['CommonForms']['form_type']='RKD';
		$data['common']=(Object)$post['CommonForms'];
		if($post['FrmInput']['is_cc']==1)
		{
			$post['FrmInput']['input_type']="ccrk";
		}
		$data['main']=(Object)$post['FrmInput'];
		$data['detail']=array();
		for($i=0;$i<count($post['td_products']);$i++)
		{
			$temp=array();
			$temp['product_id']=$post['td_products'][$i];
			$temp['texture_id']=$post['td_textures'][$i];
			$temp['brand_id']=$post['td_brands'][$i];
			$temp['rank_id']=$post['td_ranks'][$i];
			$temp['cost_price']=numChange($post['td_price'][$i]);
			$temp['input_amount']=$post['td_amount'][$i];
			$temp['input_weight']=$post['td_weight'][$i];
			$temp['length']=$post['td_length'][$i];
			$temp['purchase_detail_id']=$post['td_id'][$i];
			$temp['card_id']=$post['td_card_id'][$i];
			array_push($data['detail'], (Object)$temp);
		}
		return $data;
	}
	
	/*
	 * 获取仓库推送信息
	 */
	public static function getPushInputData($post)
	{
		$post['CommonForms']['form_type']='RKD';
		$data['common']=(Object)$post['CommonForms'];
		if($post['FrmInput']['is_cc']==1)
		{
			$post['FrmInput']['input_type']="ccrk";
		}
		$data['main']=(Object)$post['FrmInput'];
		$data['detail']=array();
		for($i=0;$i<count($post['td_products']);$i++)
		{
			$temp=array();
			$temp['product_id']=$post['td_products'][$i];
			$temp['texture_id']=$post['td_textures'][$i];
			$temp['brand_id']=$post['td_brands'][$i];
			$temp['rank_id']=$post['td_ranks'][$i];
			$temp['cost_price']=numChange($post['td_price'][$i]);
			$temp['input_amount']=$post['td_amount'][$i];
			$temp['input_weight']=$post['td_weight'][$i];
			$temp['length']=$post['td_length'][$i];
			$temp['card_id']=$post['td_card_id'][$i];
			$temp['push_detail_id']=$post['td_id'][$i];
			$temp['original_detail_id']=$post['td_plan_id'][$i];
			array_push($data['detail'], (Object)$temp);
		}
		return $data;
	}
	
	public static function getUpdateData($post)
	{
		$data['common']=(Object)$post['CommonForms'];
		if($post['FrmInput']['is_cc']==1)
		{
			$post['FrmInput']['input_type']="ccrk";
		}else{
			$post['FrmInput']['input_type']='purchase';
		}
		$data['main']=(Object)$post['FrmInput'];
		$data['detail']=array();
		for($i=0;$i<count($post['td_products']);$i++)
		{
			$temp=array();
			$temp['product_id']=$post['td_products'][$i];
			$temp['texture_id']=$post['td_textures'][$i];
			$temp['brand_id']=$post['td_brands'][$i];
			$temp['rank_id']=$post['td_ranks'][$i];
			$temp['cost_price']=numChange($post['td_price'][$i]);
			$temp['input_amount']=$post['td_amount'][$i];
			$temp['input_weight']=$post['td_weight'][$i];
			$temp['remain_amount']=$post['td_remain_amount'][$i];
			$temp['remain_weight']=$post['td_remain_weight'][$i];
			$temp['length']=$post['td_length'][$i];
			$temp['purchase_detail_id']=$post['td_id'][$i];
			$temp['card_id']=$post['td_card_id'][$i];
			array_push($data['detail'], (Object)$temp);
		}
		return $data;
	}
	
	
	public static function getRelStoreData($post)
	{
		$data=array();
		$data['input_date']=$post['FrmInput']['input_date'];
		$data['data']=array();
		for($i=0;$i<count($post['td_id']);$i++)
		{
			$temp=array();
			$temp['cost_price']=numChange($post['td_price'][$i]);
			$temp['input_amount']=$post['td_amount'][$i];
			$temp['input_weight']=$post['td_weight'][$i];
			$temp['purchase_detail_id']=$post['td_id'][$i];
			$temp['card_id']=$post['td_card_id'][$i];
			$temp['id']=$post['td_indet_id'][$i];
			array_push($data['data'], (Object)$temp);
		}
		return $data;
	}
	
	//获取最后一个入库单的入库日期
	public static function getLastInputDate($purchase_id)
	{
		$return='';
		$model=FrmInput::model()->with(array('baseform'=>array('condition'=>'baseform.form_status!="delete"','order'=>'t.input_date DESC')))->find('purchase_id='.$purchase_id);
		if($model)
		{
			$return= date('Y-m-d',$model->input_date);
		}
		return $return;
	}
	
	
	public static function pushCreate($plan,$push)
	{
		$plan=FrmInputPlan::model()->with('basepurchase','basepurchase.purchase','basepurchase.purchase.purchaseDetails')->findByPk($plan->id);
		$common=array(
			'form_type'=>'RKD',
			'owned_by'=>$plan->basepurchase->owned_by,
		);
		$main=array(
			'input_type'=>'purchase',
			'from'=>'storage',
			'input_status'=>0,
			'push_id'=>$push->id,
			'purchase_id'=>$plan->basepurchase->id,
			'input_date'=>date('Y-m-d',$push->created_at),
			'warehouse_id'=>$plan->basepurchase->purchase->warehouse_id,
			'plan_id'=>$plan->id
		);
		$detail=array();
		$pushDetails=$push->pushedStorageDetails;
		foreach ($pushDetails as $each) {
			$temp=array();
			$temp['product_id']=$each->product_id;
			$temp['texture_id']=$each->texture_id;
			$temp['brand_id']=$each->brand_id;
			$temp['rank_id']=$each->rank_id;
			$temp['cost_price']=$each->plandetail->purchaseDetail->price;
			$temp['input_amount']=$each->amount;
			$temp['input_weight']=$each->weight;
			$temp['length']=$each->length;
			$temp['card_id']=$each->card_no;
			$temp['push_detail_id']=$each->id;
			$temp['original_detail_id']=$each->original_detail_id;
			array_push($detail, (Object)$temp);
		}
		$data['common']=(Object)$common;
		$data['main']=(Object)$main;
		$data['detail']=$detail;
		return $data; 
	}
	
	

}
