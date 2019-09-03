<?php

/**
 * This is the biz model class for table "pushed_storage".
 *
 */
class PushedStorage extends PushedStorageData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pushedStorageDetails' => array(self::HAS_MANY, 'PushedStorageDetail', 'pushed_storage_id'),
			'inputCompany'=>array(self::BELONGS_TO,'DictTitle','input_company'),
			'ownerCompany'=>array(self::BELONGS_TO,'DictCompany','owner_company'),
			'inputPlan'=>array(self::BELONGS_TO,'FrmInputPlan','frm_input_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'input_form_id' => 'Input Form',
			'input_company' => 'Input Company',
			'owner_company' => 'Owner Company',
			'input_type' => 'Input Type',
			'is_cc' => 'Is Cc',
			'ship_no' => 'Ship No',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
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
		$criteria->compare('input_form_id',$this->input_form_id);
		$criteria->compare('input_company',$this->input_company);
		$criteria->compare('owner_company',$this->owner_company);
		$criteria->compare('input_type',$this->input_type,true);
		$criteria->compare('is_cc',$this->is_cc);
		$criteria->compare('ship_no',$this->ship_no,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('form_sn',$this->form_sn,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PushedStorage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function pushList($search)
	{
	$tableHeader = array(
			array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
			array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),
			array('name'=>'入库计划','class' =>"sort-disabled",'width'=>"100px"),
			array('name'=>'采购单号','class' =>"flex-col sort-disabled",'width'=>"100px"),			
			array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"70px"),//
			array('name'=>'入库日期','class' =>"flex-col sort-disabled",'width'=>"80px"),
			array('name'=>'货主单位','class' =>"flex-col sort-disabled",'width'=>"60px"),
			array('name'=>'货权单位','class' =>"flex-col sort-disabled",'width'=>"60px"),//
			array('name'=>'入库类型','class' =>"flex-col sort-disabled",'width'=>"65px"),//
			array('name'=>'车船号','class' =>"flex-col sort-disabled",'width'=>"85px"),//
			array('name'=>'卡号','class' =>"flex-col sort-disabled",'width'=>"150px"),//
			array('name'=>'产地/品名/材质/规格/长度','class' =>"flex-col sort-disabled",'width'=>"200px"),//
			array('name'=>'件数','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//
			array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
			
	);
	$tableData=array();
	$model=PushedStorageDetail::model();
	$criteria=New CDbCriteria();
	$criteria->with=array('pushedStorage');#,'pushedStorage.inputPlan','pushedStorage.inputPlan.baseform');
	$criteria->join='left join pushed_storage storage on storage.id=t.pushed_storage_id  left join frm_input_plan plan on storage.frm_input_id=plan.id left join common_forms c on c.form_id=plan.id and c.form_type="RKP" and c.is_deleted=0 ';
	//搜索
	if(!empty($search))
	{
		$criteria->together=true;
		if($search['keywords'])
		{
			$criteria->addCondition('plan.form_sn like :contno or c.form_sn like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
		}		
		if($search['time_L']!='')
		{
			$criteria->addCondition('storage.created_at >='.strtotime($search['time_L']));
		}
		if($search['time_H']!='')
		{
			$criteria->addCondition('storage.created_at <='.(strtotime($search['time_H'])+86400));
		}
		if($search['input_type']!='0')
		{
			$criteria->compare('storage.input_type',$search['input_type']);
		}
		if($search['input_status']!='-2')
		{
			$criteria->compare('storage.input_status',$search['input_status']);
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
		if($search['model_id'])
		{
			$criteria->compare('t.pushed_storage_id',$search['model_id']);
		}
	}
	$criteria->select='t.*,storage.*,plan.form_sn as cform_sn,c.form_sn as pform_sn';

	$pages = new CPagination();
	$pages->itemCount = $model->count($criteria);
	$pages->pageSize =intval($_COOKIE['input_list']) ? intval($_COOKIE['input_list']) : Yii::app()->params['pageCount'];
	$pages->applyLimit($criteria);
	$criteria->order='storage.created_at DESC,storage.input_status ASC,storage.id DESC';
	$details=PushedStorageDetail::model()->findAll($criteria);

	// var_dump($details);
	// die;
	if($details)
	{
		$da=array();
		$da['data']=array();
		$_type=array('normal'=>'入库','transfer'=>"转库",'tuopan'=>"托盘");
		$_status=array(0=>'未入库',1=>'已入库',-1=>'关联失败');
		$s_id='';
		$i=1;
		$total_a=0;
		$total_w=0;
		foreach ($details as $each)
		{
			$storage=$each->pushedStorage;
			$mark=$i;
			if($each->pushed_storage_id!= $s_id)
			{
				$s_id=$each->pushed_storage_id;
				$i++;
				$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->cform_sn.'">';
				$detail_url=Yii::app()->createUrl('input/viewPush',array('id'=>$storage->id,'search_url'=>json_encode($search)));
				$operate.='<a class="update_a" href="'.$detail_url.'" title="查看详情"><span><img src="/images/ckxq.png"></span></a>';
				if($storage->input_status==0&&checkOperation("入库单:新增"))
				{
					$url=Yii::app()->createUrl('input/createByPush',array('plan_id'=>$storage->frm_input_id,'push_id'=>$storage->id));
					$operate.='<a class="update_a" href="'.$url.'" title="入库"><span><img src="/images/ruku.png"></span></a>';
				}	
				if($storage->input_status==-1)
				{
					$com_url=Yii::app()->createUrl('input/completePush',array('push_id'=>$storage->id));
					$operate.='<span class="submit_form" url="'.$com_url.'" title="手动完成"><img src="/images/wancheng.png"></span>';
				}
				$operate.='</div >';	
			}else{
				$mark='';
				$operate='';
			}
			$da['data']=array($mark,
					$operate,
					$each->pform_sn,
					$each->cform_sn,
					$_status[$storage->input_status],
					($storage->created_at>943891200)?date('Y-m-d',$storage->created_at):'',
					$storage->inputCompany->short_name,
					$storage->input_type=='normal'?$storage->inputCompany->short_name:$storage->ownerCompany->short_name,
					$_type[$storage->input_type],
					$storage->ship_no,
					$each->card_no,
					DictGoodsProperty::getProName($each->brand_id).'/'.DictGoodsProperty::getProName($each->product_id).'/'.str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)).'/'.DictGoodsProperty::getProName($each->rank_id).'/'.$each->length,
					$each->amount,
					number_format($each->weight,3),					
			);
			$da['group']=$each->pushed_storage_id;
			array_push($tableData,$da);
			$total_a+=$each->amount;
			$total_w+=$each->weight;
		}
	}
	return array($tableHeader,$tableData,$pages);
	}
	
	
	
	/*
	 * 创建
	 */
	public static function createNew($json)
	{
		
		$json=$json->Body->Content;
		$transaction=Yii::app()->db->beginTransaction();
		try {
			if($json->Tables[0]->Records[0]->Fields[1]->Text)
			{
				$baseform=CommonForms::model()->with('inputplan','inputplan.inputDetailsPlan')->findByPk($json->Tables[0]->Records[0]->Fields[1]->Text);
				$plan=$baseform->inputplan;
				$planDetails=$plan->inputDetailsPlan;
				$comeDetails=$json->Tables[0]->Records[0]->Details[0]->Records;
				$comeColumns=$json->Tables[0]->Records[0]->Details[0]->Columns;
				$comeMains=$json->Tables[0]->Records[0]->Fields;
				$comeMainColumns=$json->Tables[0]->Columns;
				if($comeMainColumns)
				{
					foreach ($comeMainColumns as $each)
					{
						switch ($each->Schema)
						{
							case "input_type":
								$input_type=array_search($each, $comeMainColumns);break;
							case "ship_no":
								$ship_no=array_search($each, $comeMainColumns);	break;
							case "created_at":
								$created_at=array_search($each, $comeMainColumns);break;
							default:
								break;
						}
					}
				}
				$model=new PushedStorage();
				$model->unsetAttributes();
				$model->frm_input_id=$plan->id;//入库计划id
				$model->input_company=$plan->input_company;
				$model->owner_company=$plan->owner_company;
				$model->input_type=$comeMains[$input_type]->Text;
				$model->ship_no=$comeMains[$ship_no]->Text;
				$model->input_status=0;
				$model->created_at=$comeMains[$created_at]->Text;
				$model->insert();
				$flag=true;
				//判断品名是否更改
				if(is_array($comeColumns)&&!empty($comeColumns))
				{
					foreach ($comeColumns as $one)
					{
						switch ($one->Schema)
						{
							case "goods_name":
								$proOff=array_search($one, $comeColumns);
								break;
							case "rank":
								$rankOff=array_search($one, $comeColumns);
								break;
							case "texture":
								$textOff=array_search($one, $comeColumns);
								break;
							case "goods_company":
								$brandOff=array_search($one, $comeColumns);
								break;
							case "length":
								$lenOff=array_search($one, $comeColumns);
								break;
							case "card_no":
								$cardOff=array_search($one, $comeColumns);
								break;
							case "amount":
								$amountOff=array_search($one, $comeColumns);
								break;
							case "weight":
								$weightOff=array_search($one, $comeColumns);
								break;
							case "content":
								$contentOff=array_search($one, $comeColumns);
								break;
							case "SN":
								$snOff=array_search($one, $comeColumns);
								break;
						}
					}
				}else{
					$return= '{"result":"error","message":"'.urlencode('传送数据有误').'"}';
					goto end;
				}
				if(is_array($comeDetails)&&!empty($comeDetails))
				{
					$id_array=array();
					foreach ($planDetails as $hh)
					{
						array_push($id_array, $hh->id);
					}
					foreach ($comeDetails as $each)
					{
						if(in_array($each->Fields[$snOff]->Text,$id_array))
						{
							foreach ($planDetails as $ea)
							{
								if($ea->id!=$each->Fields[$snOff]->Text){continue;}
								// 				var_dump($each);
								$product_id=DictGoodPropertyRelation::getJxcId($each->Fields[$proOff]->Text,'product');
								$brand_id=DictGoodPropertyRelation::getJxcId($each->Fields[$brandOff]->Text,'brand');
								$texture_id=DictGoodPropertyRelation::getJxcId($each->Fields[$textOff]->Text,'texture');
								$rank_id=DictGoodPropertyRelation::getJxcId($each->Fields[$rankOff]->Text,'rank');
								$length=$each->Fields[$lenOff]->Text;
								if($ea->brand_id!=$brand_id||$ea->product_id!=$product_id||$ea->texture_id!=$texture_id||$ea->rank_id!=$rank_id||$ea->length!=$length)
								{
									$flag=false;
								}
								if(!$product_id||!$brand_id||!$texture_id||!$rank_id)
								{
									$return= '{"result":"error","message":"'.urlencode('传送数据有误').'"}';
									goto end;
								}
								$detail=new PushedStorageDetail();
								$detail->unsetAttributes();
								$detail->pushed_storage_id=$model->id;
								$detail->card_no=$each->Fields[$cardOff]->Text;
								$detail->amount=$each->Fields[$amountOff]->Text;
								$detail->weight=$each->Fields[$weightOff]->Text;
								$detail->content=$each->Fields[$contentOff]->Text;
								$detail->original_detail_id=$each->Fields[$snOff]->Text;
								$detail->product_id=$product_id;
								$detail->brand_id=$brand_id;
								$detail->rank_id=$rank_id;
								$detail->texture_id=$texture_id;
								$detail->length=$length;
								$detail->insert();
							}
						}else{
							$product_id=DictGoodPropertyRelation::getJxcId($each->Fields[$proOff]->Text,'product');
							$brand_id=DictGoodPropertyRelation::getJxcId($each->Fields[$brandOff]->Text,'brand');
							$texture_id=DictGoodPropertyRelation::getJxcId($each->Fields[$textOff]->Text,'texture');
							$rank_id=DictGoodPropertyRelation::getJxcId($each->Fields[$rankOff]->Text,'rank');
							$length=$each->Fields[$lenOff]->Text;
							$flag=false;
							if(!$product_id||!$brand_id||!$texture_id||!$rank_id)
							{
								$return= '{"result":"error","message":"'.urlencode('传送数据有误').'"}';
								goto end;
							}
							$detail=new PushedStorageDetail();
							$detail->unsetAttributes();
							$detail->pushed_storage_id=$model->id;
							$detail->card_no=$each->Fields[$cardOff]->Text;
							$detail->amount=$each->Fields[$amountOff]->Text;
							$detail->weight=$each->Fields[$weightOff]->Text;
							$detail->content=$each->Fields[$contentOff]->Text;
							$detail->original_detail_id=$each->Fields[$snOff]->Text;
							$detail->product_id=$product_id;
							$detail->brand_id=$brand_id;
							$detail->rank_id=$rank_id;
							$detail->texture_id=$texture_id;
							$detail->length=$length;
							$detail->insert();
						}
					}
				}else{
					$return= '{"result":"error","message":"'.urlencode('传送数据有误').'"}';
					goto end;
				}
			}else{
				//销售退货入库				
				$comeDetails=$json->Tables[0]->Records[0]->Details[0]->Records;
				$comeColumns=$json->Tables[0]->Records[0]->Details[0]->Columns;
				$comeMains=$json->Tables[0]->Records[0]->Fields;
				$comeMainColumns=$json->Tables[0]->Columns;
				if($comeMainColumns)
				{
					foreach ($comeMainColumns as $each)
					{
						switch ($each->Schema)
						{
							case "input_type":
								$input_type=array_search($each, $comeMainColumns);break;
							case "ship_no":
								$ship_no=array_search($each, $comeMainColumns);	break;
							case "created_at":
								$created_at=array_search($each, $comeMainColumns);break;
							case "warehouse_id":
								$warehouse_id=array_search($each, $comeMainColumns);break;
							case "input_company":
								$input_company=array_search($each, $comeMainColumns);break;
							case "owner_company":
								$owner_company=array_search($each, $comeMainColumns);break;
							default:
								break;
						}
					}
				}
				$model=new PushedStorage();
				$model->unsetAttributes();
				$model->frm_input_id=0;//
				$model->input_company=$comeMains[$input_company]->Value;
				$model->owner_company=$comeMains[$owner_company]->Value;
				$model->input_type=$comeMains[$input_type]->Text;
				$model->ship_no=$comeMains[$ship_no]->Text;
				$model->input_status=-1;
				$model->created_at=$comeMains[$created_at]->Text;
				$model->insert();
				$flag=true;
				//判断品名是否更改
				if(is_array($comeColumns)&&!empty($comeColumns))
				{
					foreach ($comeColumns as $one)
					{
						switch ($one->Schema)
						{
							case "goods_name":
								$proOff=array_search($one, $comeColumns);
								break;
							case "rank":
								$rankOff=array_search($one, $comeColumns);
								break;
							case "texture":
								$textOff=array_search($one, $comeColumns);
								break;
							case "goods_company":
								$brandOff=array_search($one, $comeColumns);
								break;
							case "length":
								$lenOff=array_search($one, $comeColumns);
								break;
							case "card_no":
								$cardOff=array_search($one, $comeColumns);
								break;
							case "amount":
								$amountOff=array_search($one, $comeColumns);
								break;
							case "weight":
								$weightOff=array_search($one, $comeColumns);
								break;
							case "content":
								$contentOff=array_search($one, $comeColumns);
								break;
							case "SN":
								$snOff=array_search($one, $comeColumns);
								break;
						}
					}
				}else{
					$return= '{"result":"error","message":"'.urlencode('传送数据有误').'"}';
					goto end;
				}
				if(is_array($comeDetails)&&!empty($comeDetails))
				{
					foreach ($comeDetails as $each)
					{
						$product_id=DictGoodPropertyRelation::getJxcId($each->Fields[$proOff]->Text,'product');
						$brand_id=DictGoodPropertyRelation::getJxcId($each->Fields[$brandOff]->Text,'brand');
						$texture_id=DictGoodPropertyRelation::getJxcId($each->Fields[$textOff]->Text,'texture');
						$rank_id=DictGoodPropertyRelation::getJxcId($each->Fields[$rankOff]->Text,'rank');
						$length=$each->Fields[$lenOff]->Text;
						$flag=false;
						if(!$product_id||!$brand_id||!$texture_id||!$rank_id)
						{
							$return= '{"result":"error","message":"'.urlencode('传送数据有误').'"}';
							goto end;
						}
						$detail=new PushedStorageDetail();
						$detail->unsetAttributes();
						$detail->pushed_storage_id=$model->id;
						$detail->card_no=$each->Fields[$cardOff]->Text;
						$detail->amount=$each->Fields[$amountOff]->Text;
						$detail->weight=$each->Fields[$weightOff]->Text;
						$detail->content=$each->Fields[$contentOff]->Text;
						$detail->original_detail_id=$each->Fields[$snOff]->Text;
						$detail->product_id=$product_id;
						$detail->brand_id=$brand_id;
						$detail->rank_id=$rank_id;
						$detail->texture_id=$texture_id;
						$detail->length=$length?$length:0;
						$detail->insert();
					}
					$content='有一条销售退货单成功入库';
					
				}else{
					$return= '{"result":"error","message":"'.urlencode('传送数据有误').'"}';
					goto end;
				}
			}
			
			$transaction->commit();
		}catch (Exception $e)
		{
			
			$transaction->rollBack();//事务回滚
			$return= '{"result":"error","message":"'.urlencode('数据库操作失败').'"}';
			goto end;
		}		
		if($content=='有一条销售退货单成功入库')
		{
			goto message;
		}
		if(!$flag){
			$model->input_status=-1;
			$model->update();
			//发送消息通知
			$content ="您的入库计划入库时发生异常，异常原因:与原单据不匹配";
		}else{
			$content ="您的入库计划入库已在仓库成功入库，请前去处理";

			//自动入库(卡号重复与否)				//取消自动入库16年4月26日
			// if($plan->input_type=='ccrk')
			// {
			// 	$result=Input::relStoreByPush($plan->id, $model->id);
			// 	if($result===true)
			// 	{
			// 		//发送成功消息
			// 		$content="您的船舱入库单已真实入库";					
			// 	}else{
			// 		//发送失败消息
			// 		$content="您的船舱入库单真实入库失败，$result";
			// 	}
			// }else{
			// 	//普通入库
			// 	$data=FrmInput::pushCreate($plan,$model);
			// 	$form=new Input(0,'purchase');
			// 	$form->createForm($data);
			// 	$model=$form->commonForm;
			// 	$flag=true;
			// 	$input=$model->input;
			// 	$details=$model->input->inputDetails;
			// 	$content='您的入库计划入库时发生异常,异常原因：';
			// 	foreach ($details as $each)
			// 	{
			// 		$result=Storage::getStroageid($each->card_id, $input->warehouse_id);
			// 		if($result)
			// 		{
			// 			$flag=false;
			// 			$content.= '卡号'.$each->card_id.'重复，请修改。';
			// 		}
			// 	}
			// 	if($flag){
			// 		$form->submitForm();
			// 		$form->approveForm();
			// 		$content= "您推送的入库计划已成功入库";
			// 	}
			// }		
		}
		//发送消息通知
		message:
		if($json->Tables[0]->Records[0]->Fields[1]->Text)
		{
			$push=PushList::model()->findByAttributes(array('form_sn'=>$baseform->form_sn));
			$message = array();
			$message['receivers'] =$push->created_by;
			$message['content'] =$content;
			$message['title'] = "推送通知";
			$message['url'] = Yii::app()->createUrl('input/pushedList',array('form_sn'=>$baseform->form_sn));
			$message['type'] = "推送通知";
			$message['big_type']='ware';
			$res = MessageContent::model()->addMessage($message);
		}else{
			$user=User::model()->getOperationList('仓库管理员');
			$message = array();
			$message['receivers'] =$user;
			$message['content'] =$content;
			$message['title'] = "推送通知";
			$message['url'] = Yii::app()->createUrl('input/pushedList',array('form_sn'=>'no','model_id'=>$model->id));
			$message['type'] = "推送通知";
			$message['big_type']='ware';
			$res = MessageContent::model()->addMessage($message);
		}
		$return='{"result":"success","message":"'.urlencode('成功').'"}';
		end:
		return urldecode($return);
	}

}
