<?php

/**
 * This is the biz model class for table "frm_input_plan".
 *
 */
class FrmInputPlan extends FrmInputPlanData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="RKP" '),
				'inputCompany'=>array(self::BELONGS_TO,'DictTitle','input_company'),
				'ownerCompany'=>array(self::BELONGS_TO,'DictCompany','owner_company'),
				'inputDetailsPlan'=>array(self::HAS_MANY,'InputDetailPlan','input_id'),
				'basepurchase'=>array(self::BELONGS_TO,'CommonForms','purchase_id','condition'=>'basepurchase.form_type="CGD" and basepurchase.is_deleted=0'),
				'frmInput'=>array(self::HAS_ONE,'FrmInput','plan_id'),
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
			'warehouse_id' => 'Warehouse',
			'input_status' => 'Input Status',
			'input_company' => 'Input Company',
			'owner_company' => 'Owner Company',
			'ship_no' => 'Ship No',
			'form_sn' => 'Form Sn',
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
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('input_status',$this->input_status);
		$criteria->compare('input_company',$this->input_company);
		$criteria->compare('owner_company',$this->owner_company);
		$criteria->compare('ship_no',$this->ship_no,true);
		$criteria->compare('form_sn',$this->form_sn,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmInputPlan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getPlanList($search)
	{
		$tableHeader = array(
			array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
			array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),
			array('name'=>'单号','class' =>"sort-disabled",'width'=>"80px"),
			array('name'=>'采购单号','class' =>"flex-col sort-disabled ",'width'=>"100px"),//
			array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"60px"),//
			array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"80px"),			
			array('name'=>'货主单位','class' =>"flex-col sort-disabled",'width'=>"60px"),
			array('name'=>'货权单位','class' =>"flex-col sort-disabled",'width'=>"60px"),//
			array('name'=>'入库类型','class' =>"flex-col sort-disabled",'width'=>"70px"),//
			array('name'=>'入库仓库','class' =>"flex-col sort-disabled",'width'=>"60px"),//
			array('name'=>'车船号','class' =>"flex-col sort-disabled",'width'=>"80px"),//
			array('name'=>'产地/品名/材质/规格/长度','class' =>"flex-col sort-disabled",'width'=>"200px"),//
			array('name'=>'件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
			array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"120px"),//			
			array('name'=>'制单人','class' =>"flex-col sort-disabled",'width'=>"70px"),//
			array('name'=>'最后操作人','class' =>"flex-col sort-disabled",'width'=>"80px"),//
			array('name'=>'备注','class' =>"flex-col sort-disabled",'width'=>"210px"),//
	);
	if($search['status']=='4')
	{
		$reason=array('name'=>'作废原因','class' =>"flex-col sort-disabled",'width'=>"210px");
		array_push($tableHeader, $reason);
		array_splice($tableHeader, 1,1);
	}
	$tableData=array();
	$model=New InputDetailPlan();
	$criteria=New CDbCriteria();
	$criteria->with=array('inputPlan','inputPlan.baseform','inputPlan.basepurchase','inputPlan.basepurchase.purchase');
	//搜索
	if(!empty($search))
	{
		$criteria->together=true;
		$criteria->addCondition('inputPlan.form_sn like :contno or baseform.form_sn like :contno');
		$criteria->params[':contno']= "%".$search['keywords']."%";
		if($search['time_L']!='')
		{
			$criteria->addCondition('baseform.created_at >='.strtotime($search['time_L']));
		}
		if($search['time_H']!='')
		{
			$criteria->addCondition('baseform.created_at <='.(strtotime($search['time_H'])+86400));
		}
		if($search['input_type']!='0')
		{
			$criteria->compare('inputPlan.input_type',$search['input_type']);
		}
		if($search['status']!='')
		{
			$criteria->compare('inputPlan.input_status',$search['status']);
		}else{
			$criteria->addCondition('inputPlan.input_status!=4');
		}
		//产地,品名，规格,材质
		if($search['brand']!='')
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
		$criteria->addCondition('inputPlan.input_status!=4');
	}
	
	$newcri=clone $criteria;
	$newcri->select = "sum(t.input_amount) as total_amount,sum(t.input_weight) as total_weight,count(*) as total_num";
	$all=$model->find($newcri);
	$totaldata = array();
	$totaldata["amount"] = $all->total_amount;
	$totaldata["weight"] = $all->total_weight;
// 	$totaldata["money"] = $all->total_money;
// 	$totaldata["total_num"] = $all->total_num;
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
		$_type=array('purchase'=>'采购入库','ccrk'=>"船舱入库",'tprk'=>"托盘入库");
		$_status=array('-2'=>"推送失败",'-1'=>"推送中",'0'=>'未推送','1'=>"已推送",'2'=>'已完成',3=>'作废中',4=>'已作废',5=>"作废失败");
		$baseform='';
		$i=1;
		foreach ($details as $each)
		{
			$inputPlan=$each->inputPlan;
			$mark=$i;
			if($inputPlan->baseform!=$baseform)
			{
				$purchase=$inputPlan->basepurchase->purchase;
				$baseform=$inputPlan->baseform;
				$i++;			
				$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
				$detail_url=Yii::app()->createUrl('inputPlan/view',array('id'=>$baseform->id,'fpage'=>$_REQUEST['page'],'backUrl'=>'inputPlan/index','search_url'=>json_encode($search)));	
// 				$operate='<a class="update_b" href="'.$detail_url.'" title="查看详情"><i class="icon icon-file-text-o"></i></a>';
				if($inputPlan->input_status!=4&&$inputPlan->input_status!=3&&$inputPlan->input_status!=2&&checkOperation("入库单:新增")){		
					$del_url=Yii::app()->createUrl('inputPlan/deletePlan',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['page']));
					$operate.='<span class="delete_form" url="'.$del_url.'" title="作废"><img src="/images/zuofei.png"></span>';
				}
				if($inputPlan->input_status==0||$inputPlan->input_status==-2)
				{
// 					$push_url=Yii::app()->createUrl('inputPlan/push',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['page']));
// 					$operate.='<span class="submit_form" url="'.$push_url.'" title="推送" str="确认要推送入库计划'.$baseform->form_sn.'吗？"><img src="/images/tuisong.png"></span>';
					$edit_url=Yii::app()->createUrl('inputPlan/update',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['page']));
					$operate.='<a class="update_a" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a>';
				}
				if($inputPlan->input_status==1)
				{
					$edit_url=Yii::app()->createUrl('inputPlan/update',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['page']));
					$operate.='<a class="update_a" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a>';
				}
				if($inputPlan->input_status==1){
					$finish_url=Yii::app()->createUrl('inputPlan/finish',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['page']));
					$operate.='<span class="submit_form" url="'.$finish_url.'" title="完成" str="确定要完成入库计划'.$baseform->form_sn.'吗？"><img src="/images/wancheng.png"></span>';
				}				
// 				if(checkOperation("入库单:新增"))
// 				{
// 					//查找入库单
// 					$url = Yii::app()->createUrl('input/create',array('id'=>$inputPlan->purchase_id,'type'=>'purchase','last_update'=>$baseform->last_update));
// // 					$url=Yii::app()->createUrl('input/createByPlan',array('plan_id'=>$inputPlan->id));
// 					$operate.='<a class="update_b" href="'.$url.'" title="入库"><span><img src="/images/kccg.png"></span></a>';
// // 					$operate.='<a class="update_a" href="'.$url.'" title="入库"><span><i class="icon icon-stack"></i></span></a>';
// 				}
				
				$operate.='</div >';
			}else{
				$mark='';
				$operate='';
			}			
			$da['data']=array($mark,
					$operate,
					'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$baseform->form_sn.'</a>',
					$inputPlan->form_sn,
					$_status[$inputPlan->input_status],
					($baseform->created_at>943891200)?date('Y-m-d',$baseform->created_at):'',					
					 '<span title="'.$inputPlan->inputCompany->name.'">'.$inputPlan->inputCompany->short_name.'</span>',
					//$inputPlan->owner_company? '<span title="'.$inputPlan->ownerCompany->name.'">'.$inputPlan->ownerCompany->short_name.'</span>': '<span title="'.$inputPlan->inputCompany->name.'">'.$inputPlan->inputCompany->short_name.'</span>',
					$purchase->purchase_type=='tpcg'?'<span title="'.$inputPlan->ownerCompany->name.'">'.$inputPlan->ownerCompany->short_name.'</span>':$inputPlan->inputCompany->short_name,
					$_type[$inputPlan->input_type],
					$inputPlan->warehouse->short_name,
					$inputPlan->ship_no,
					DictGoodsProperty::getProName($each->brand_id).'/'.DictGoodsProperty::getProName($each->product_id).'/'.str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)).'/'.DictGoodsProperty::getProName($each->rank_id).'/'.$each->length,
					$each->real_amount.'/'.$each->input_amount,
					number_format($each->real_weight,3).'/'.number_format($each->input_weight,3),					
					$baseform->operator->nickname,
					$baseform->lastupdate->nickname,
					'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,'utf-8').'</span>',
			);
			if($search['status']=='4'){
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
	
	/*
	 * 获取用户输入
	 */
	public static function getInputData($post)
	{
		$post['CommonForms']['form_type']='RKP';
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
			$temp['input_amount']=$post['td_amount'][$i];
			$temp['input_weight']=$post['td_weight'][$i];
			$temp['remain_amount']=$post['td_remain_amount'][$i];
			$temp['remain_weight']=$post['td_remain_weight'][$i];
			$temp['price']=$post['td_price'][$i];
			$temp['length']=$post['td_length'][$i];
			$temp['purchase_detail_id']=$post['td_id'][$i];
			array_push($data['detail'], (Object)$temp);
		}
		return $data;
	}
	
	
	
	//推送此采购单的入库计划
	public static function pushAll($id)
	{
		$plans=FrmInputPlan::model()->findAll('purchase_id='.$id.' and input_status=0');
		if($plans)
		{
			foreach ( $plans as $each)
			{
				$each->input_status=-1;
				$each->update();
				//获取信息，推送
				$jsonData=FrmInputPlan::getJsonData($each);
				//插入推送数据
				$data=array();
				$data['type']='inputformplan';
				$data['content']=$jsonData;
				$data['unid']= Yii::app()->user->unid;
				$data['operate']='Add';
				$data['form_id']=$each->id;
				$data['form_sn']=$each->baseform->form_sn;
				PushList::createNew($data);
			}
		}
	}
	//删除信息
	public static function deletePush($main)
	{
		//获取信息，推送
		$jsonData=FrmInputPlan::getJsonData($main);
		//插入推送数据
		$data=array();
		$data['type']='inputformplan';
		$data['content']=$jsonData;
		$data['unid']= Yii::app()->user->unid;
		$data['operate']='Delete';
		$data['form_id']=$main->id;
		$res=json_decode($jsonData);
		$data['form_sn']=$res->Records[0]->Fields[0]->Text;
		PushList::createNew($data);
	}
	
	//编辑信息
	public static function editPush($main)
	{
		//获取信息，推送
		$jsonData=FrmInputPlan::getJsonData($main);
		//插入推送数据
		$data=array();
		$data['type']='inputformplan';
		$data['content']=$jsonData;
		$data['unid']= Yii::app()->user->unid;
		$data['operate']='Edit';
		$data['form_id']=$main->id;
		$res=json_decode($jsonData);
		$data['form_sn']=$res->Records[0]->Fields[0]->Text;
		PushList::createNew($data);
	}
	
	
	//获取入库计划的数据
	public static function getJsonData($plan)
	{
		$baseform=$plan->baseform;
		$details=$plan->inputDetailsPlan;
		$purchase=$plan->basepurchase->purchase;
		$deArr = array();
		if($details){
			foreach($details as $each){
				$newArr['Fields'] = array();
				$product_id = DictGoodPropertyRelation::getApiId($each->product_id);
				$texture_id = DictGoodPropertyRelation::getApiId($each->texture_id);
				$rank_id = DictGoodPropertyRelation::getApiId($each->rank_id);
				$brand_id = DictGoodPropertyRelation::getApiId($each->brand_id);
				$product_name = DictGoodsProperty::getFullProName($each->product_id);
				$texture_name = DictGoodsProperty::getFullProName($each->texture_id);
				$rank_name = DictGoodsProperty::getFullProName($each->rank_id);
				$brand_name = DictGoodsProperty::getFullProName($each->brand_id);

				$de=array('brand'=>$each->brand_id,'product'=>$each->product_id,'texture'=>$each->texture_id,'rank'=>$each->rank_id,'length'=>$each->length);
				$unit_weight=DictGoods::getUnitWeight($de);
				array_push(
						$newArr['Fields'],
						array("Text"=>$baseform->id, "Value"=>'',"Standard"=>""),
						array("Text"=>$brand_name, "Value"=>$brand_id,"Standard"=>""),
						array("Text"=>$product_name, "Value"=>$product_id,"Standard"=>""),
						array("Text"=>$texture_name, "Value"=>$texture_id,"Standard"=>""),
						array("Text"=>$rank_name, "Value"=>$rank_id,"Standard"=>""),
						array("Text"=>$each->length, "Value"=>'',"Standard"=>""),
						array("Text"=>$each->input_amount, "Value"=>'',"Standard"=>""),
						array("Text"=>$each->input_weight, "Value"=>'',"Standard"=>""),
						array("Text"=>'', "Value"=>'',"Standard"=>""),
						array("Text"=>$each->id,"Value"=>'',"Standard"=>""),
						array("Text"=>$unit_weight,'Value'=>'',"Standard"=>"")					
				);
				array_push($deArr,$newArr);
			}
		}
		$table = array(
				'Columns'=>array(
						array("Text"=>'入库计划单ID','Schema'=>'form_id'),
						array("Text"=>'货主单位','Schema'=>'input_company'),
						array("Text"=>'货权单位','Schema'=>'owner_company'),
						array("Text"=>'入库类型','Schema'=>'input_type'),
						array("Text"=>'仓库ID','Schema'=>'warehouse_id'),
						array("Text"=>'车船号','Schema'=>'ship_no'),
						array("Text"=>'制单人','Schema'=>'created_by'),
						array("Text"=>'创建时间','Schema'=>'created_at'),
						array("Text"=>'备注','Schema'=>'content')
				),
				'Records'=>array(
						array(
								'Fields'=>array(
										array("Text"=>$baseform->form_sn, "Value"=>$baseform->id,"Standard"=>""),
										array("Text"=>$plan->inputCompany->short_name, "Value"=>$plan->input_company,"Standard"=>""),
										array("Text"=>$purchase->purchase_type=='tpcg'?$plan->ownerCompany->short_name:$plan->inputCompany->short_name, "Value"=>$purchase->purchase_type=='tpcg'?$plan->owner_company:$plan->input_company,"Standard"=>""),
										array("Text"=>$plan->input_type, "Value"=>'',"Standard"=>""),
										array("Text"=>$plan->warehouse_id, "Value"=>'',"Standard"=>""),
										array("Text"=>$plan->ship_no, "Value"=>'',"Standard"=>""),
										array("Text"=>$baseform->operator->nickname, "Value"=>$baseform->created_by,"Standard"=>""),
										array("Text"=>$baseform->created_at, "Value"=>'',"Standard"=>""),
										array("Text"=>$baseform->comment, "Value"=>'',"Standard"=>"")
								),
								'Details'=>array(
										array(
												'Columns'=>array(
														array("Text"=>'入库计划单ID','Schema'=>'input_form_id'),
														array("Text"=>'产地','Schema'=>'goods_company'),
														array("Text"=>'品名','Schema'=>'goods_name'),
														array("Text"=>'材质','Schema'=>'texture'),
														array("Text"=>'规格','Schema'=>'rank'),
														array("Text"=>'长度','Schema'=>'length'),
														array("Text"=>'件数','Schema'=>'amount'),
														array("Text"=>'重量','Schema'=>'weight'),
														array("Text"=>'备注','Schema'=>'content'),
														array("Text"=>'SN','Schema'=>'SN'),
														array("Text"=>'件重','Schema'=>'unit_weight')
												),
												'Records'=>$deArr,
										),
								)
						),
				)
		);
		$str = json_encode($table);
		return $str;
	}
	
	
	/*
	 * 推送返回结果处理
	 */
	public static function response($json)
	{
		$json=json_decode($json);
		$push=PushList::model()->findByPk($json->SendId);		
		$plan=FrmInputPlan::model()->findByPk($push->form_id);		
		if($plan)
		{
			$content=json_decode($push->content);
			$type=$content->Body->Verb;
			
			if($type=='Add')
			{
				if($json->Result=='success')
				{
					if($plan->input_status!=1){
						$plan->input_status=1;
						$plan->update();
					}
				}elseif($json->Result=='error'){
					if($plan->input_status!=-2){
						$plan->input_status=-2;
						$plan->update();
					}
				}
				$return='{"result":"success","message":"'.urlencode('成功').'"}';
			}elseif($type=='Delete'){
				if($json->Result=='success')
				{
					if($plan->input_status!=4){
						$baseform=$plan->baseform;
						$obj=new InputPlan($baseform->id);
						$obj->afterDeleteForm();						
					}
				}elseif($json->Result=='error'){
					if($plan->input_status!=5){
						$plan->input_status=5;
						$plan->update();
						//是否发送通知给用户						
					}
				}
				$return='{"result":"success","message":"'.urlencode('成功').'"}';
			}elseif($type=='Edit'){
				if($json->Result=='success')
				{
					if($plan->input_status!=1){
						$plan->input_status=1;
						$plan->update();
					}
				}elseif($json->Result=='error'){
					if($plan->input_status!=-2){
						$plan->input_status=-2;
						$plan->update();
					}
				}
				$return='{"result":"success","message":"'.urlencode('成功').'"}';
			}else{
				$return='{"result":"error","message":"'.urlencode('没有找到操作类型').'"}';				
			}			
		}else{
			$return='{"result":"error","message":"'.urlencode('没有找到推送数据').'"}';
		}
		return urldecode($return);
	}
	
	
	

}
