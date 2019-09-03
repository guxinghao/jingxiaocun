<?php

/**
 * This is the biz model class for table "frm_sales_return".
 *
 */
class FrmSalesReturn extends FrmSalesReturnData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'supply'=>array(self::BELONGS_TO,'DictCompany','supply_id'),
			'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
			'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
			'salesReturnDetails' => array(self::HAS_MANY, 'SalesReturnDetail', 'sales_return_id'),
			'baseform' => array(self::HAS_ONE, 'CommonForms', 'form_id', 'condition' => "baseform.form_type='XSTH'"),
			'contact'=>array(self::BELONGS_TO,'CompanyContact','contact_id'),
			'client' => array(self::BELONGS_TO, 'DictCompany', 'client_id'),
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
			'return_date' => 'Return Date',
			'team_id' => 'Team',
			'travel' => 'Travel',
			'is_yidan' => 'Is Yidan',
			'return_type' => 'Return Type',
			'tran_type' => 'Tran Type',
			'warehouse_id' => 'Warehouse',
			'supply_id' => 'Supply',
			'comment' => 'Comment',
			'contact_id' => 'Contact',
			'total_weight' => 'Total Weight',
			'total_amount' => 'Total Amount',
			'residual_weight' => 'Residual Weight',
			'residual_amount' => 'Residual Amount',
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
		$criteria->compare('return_date',$this->return_date);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('travel',$this->travel,true);
		$criteria->compare('is_yidan',$this->is_yidan);
		$criteria->compare('return_type',$this->return_type,true);
		$criteria->compare('tran_type',$this->tran_type,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('supply_id',$this->supply_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('total_weight',$this->total_weight,true);
		$criteria->compare('total_amount',$this->total_amount,true);
		$criteria->compare('residual_weight',$this->residual_weight,true);
		$criteria->compare('residual_amount',$this->residual_amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmSalesReturn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//销售退货付款 列表
	public static function getFormBillList($search) 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "4%"),
				array('name' => "单号", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "开单日期", 'class' => "sort-disabled", 'width' => "8%"),
				array('name' => "结算单位", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "金额", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "仓库", 'class' => "sort-disabled", 'width' => "7%"),
				array('name' => "车船号", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "预计退货时间", 'class' => "sort-disabled", 'width' => "10%"),
				array('name' => "业务组", 'class' => "sort-disabled", 'width' => "7%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "7%"),
				array('name' => "客户", 'class' => "sort-disabled", 'width' => "10%"),
		);
		
		$tableData = array();
		$model = new FrmSalesReturn();
		$criteria = new CDbCriteria();
		$criteria->with = array('baseform');
		
		//搜索
		if (!empty($search))
		{
			if ($search['company_id'])
			{
				$criteria->addCondition("company_id = :company_id");
				$criteria->params[':company_id'] = $search['company_id'];
			}
			if ($search['client_id'])
			{
				$criteria->addCondition("client_id = :client_id");
				$criteria->params[':client_id'] = $search['client_id'];
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
		$criteria->compare("baseform.form_type", 'XSTH', true);
		$criteria->compare("baseform.is_deleted", '0', true);
		$criteria->compare("baseform.form_status", "approve", true);
		$criteria->compare('t.weight_confirm_status', 1);
		
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
				$da = array();
				if ($item->baseform)
				{
					$baseform = $item->baseform;
					$mark = $i;
					$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill" yidan="'.$item->is_yidan.'" value="'.$baseform->id.'" />';
					$i++;
				}
				$total=0;
				$details=$item->salesReturnDetails;
				foreach ($details as $each)
				{
					$total+=$each->fix_weight*$each->fix_price;
				}
				$da['data'] = array($mark,
						$operate,
						$baseform->form_sn,
						$baseform->created_at > 0 ? date('Y-m-d', $baseform->created_at) : '',
						'<span title="'.$item->company->name.'">'.$item->company->short_name.'</span>',
						'<span class="pick_money">'.number_format($total,2).'</span>',
						'<span title="'.$item->warehouse->name.'">'.$item->warehouse->name.'</span>',
						$item->travel,
						$item->return_date > 0 ? date('Y-m-d', $item->return_date) : '',
						$item->team->name, //业务组
						$baseform->belong->nickname, //业务员
						'<span title="'.$item->client->name.'">'.$item->client->short_name.'</span>',
				);
				$da['group'] = $baseform->form_sn;
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	
	

	/*
	 * 销售退货列表页
	 */
	public static function getReturnList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),	
				array('name' => "退货单号", 'class' => "sort-disabled", 'width' => "80px"),
				array('name' => "状态", 'class' => "flex-col sort-disabled", 'width' => "48px"),
				array('name' => "开单日期", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "客户", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "结算单位", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "公司", 'class' => " flex-col sort-disabled", 'width' => "60px"),
				array('name' => "产地/品名/材质/规格/长度", 'class' => "flex-col sort-disabled", 'width' => "200px"),
				array('name' => "件数", 'class' => "flex-col sort-disabled text-right", 'width' => "40px"),
				array('name' => "重量", 'class' => "flex-col sort-disabled text-right", 'width' => "60px"),
				array('name' => "单价", 'class' => "flex-col sort-disabled text-right", 'width' => "50px"),
				array('name' => "金额", 'class' => "flex-col sort-disabled text-right", 'width' => "80px"),
				array('name'=>'入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//仓库入库件数
				array('name'=>'入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"65px"),//仓库入库重量
				array('name'=>'核定价格','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//核定价格
				array('name'=>'核定件数','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),//核定总件数
				array('name'=>'核定重量','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//核定总重量
				array('name'=>'核定金额','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//核定总金额
				array('name' => "退货方式", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "审单状态", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "仓库", 'class' => "flex-col sort-disabled", 'width' => "60px"),
				array('name' => "车船号", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "预计退货时间", 'class' => "flex-col sort-disabled", 'width' => "100px"),
				array('name' => "业务组", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				array('name' => "业务员", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				array('name' => "制单人", 'class' => "flex-col sort-disabled", 'width' => "70px"),
				array('name' => "最后操作人", 'class' => "flex-col sort-disabled", 'width' => "80px"),
				array('name' => "退货原因", 'class' => "flex-col sort-disabled", 'width' => "150px"),
				array('name' => "备注", 'class' => "flex-col sort-disabled", 'width' => "230px"),
		);
		if($search['form_status']=='delete')
		{
			$reason=array('name'=>'作废原因','class' =>"flex-col sort-disabled",'width'=>"230px");
			array_push($tableHeader, $reason);
			array_splice($tableHeader, 1,1);
		}		
		$tableData = array();
		$criteria = new CDbCriteria();
		$model=new SalesReturnDetail();
		$criteria->with = array('salesReturn','salesReturn.baseform');
		
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
				$criteria->addCondition("baseform.form_time >= :time_L");
				$criteria->params[':time_L'] = $search['time_L'];
			}
			if ($search['time_H'])
			{
				$criteria->addCondition("baseform.form_time <= :time_H");
				$criteria->params[':time_H'] = $search['time_H'];#)+86400;
			}
			if ($search['vendor'])
			{
				$criteria->addCondition("salesReturn.company_id = :company_id");
				$criteria->params[':company_id'] = $search['vendor'];
			}
			if ($search['client'])
			{
				$criteria->addCondition("salesReturn.client_id = :client_id");
				$criteria->params[':client_id'] = $search['client'];
			}
			if ($search['company'])
			{
				$criteria->addCondition("salesReturn.title_id = :title_id");
				$criteria->params[':title_id'] = $search['company'];
			}
			if($search['form_status']!='0')
			{
				$criteria->compare('baseform.form_status',$search['form_status']);
			}else{
				$criteria->compare('baseform.is_deleted','0');
			}
			if($search['team']!='0')
			{
				$criteria->compare('salesReturn.team_id',$search['team']);
			}
			if($search['owned']!='0')
			{
				$criteria->compare('baseform.owned_by',$search['owned']);
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
			if($search['length'] >=0)
			{
				$criteria->compare('length',$search['length']);
			}
			//审单状态，乙单
			if($search['confirm_status']!='')
			{
				$criteria->compare('weight_confirm_status', $search['confirm_status']);
			}
			if($search['is_yidan'])
			{
				$criteria->compare('is_yidan', $search['is_yidan']);
			}
		}else{
			$criteria->compare('baseform.is_deleted','0');
		}
		$criteria->compare("baseform.form_type", 'XSTH');
		
		$newcri=clone $criteria;
		$newcri->select = "sum(return_amount) as total_amount,sum(return_weight) as total_weight,sum(return_weight*return_price) as total_money,count(*) as total_num";
		$all=SalesReturnDetail::model()->find($newcri);
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		$totaldata["money"] = $all->total_money;
		$totaldata["total_num"] = $all->total_num;
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['salereturn_list']) ? intval($_COOKIE['salereturn_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order = "baseform.created_at DESC";
		
		$details= $model->findAll($criteria);
		if ($details)
		{
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$_return_type=array('warehouse'=>'仓库','supply'=>'供应商');
			$i = 1;
			foreach ($details as $each)
			{
				$salesReturn=$each->salesReturn;
				$da = array('data' => array());
				if($salesReturn->baseform !=$baseform)
				{
					$baseform = $salesReturn->baseform;
					$mark = $i;					
					$i++;
					
					$title_sub='';
					$edit_url = Yii::app()->createUrl('salesReturn/update',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['page']));
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
					$sto_url = Yii::app()->createUrl('input/createByReturn',array('id'=>$baseform->id,'from'=>'salesReturn','last_update'=>$baseform->last_update));
					$sub_url =  Yii::app()->createUrl('salesReturn/submit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
					$del_url= Yii::app()->createUrl('salesReturn/deleteform',array('id'=>$baseform->id,'last_update'=>$baseform->last_update));
					$checkP_url=Yii::app()->createUrl('salesReturn/check',array('id'=>$baseform->id,'type'=>'pass','last_update'=>$baseform->last_update));
					$checkD_url=Yii::app()->createUrl('salesReturn/check',array('id'=>$baseform->id,'type'=>'deny','last_update'=>$baseform->last_update));
					$checkC_url=Yii::app()->createUrl('salesReturn/check',array('id'=>$baseform->id,'type'=>'cancle','last_update'=>$baseform->last_update));
					$confirm_url=Yii::app()->createUrl('salesReturn/confirm',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,"fpage"=>$_REQUEST['page']));
					$print_url = Yii::app()->createUrl('print/print', array('id' => $baseform->id));
// 					$br_url = Yii::app()->createUrl("billRecord/index", array('frm_common_id' => $baseform->id, 'bill_type' => "purchase" ,"fpage"=>$_REQUEST['page']));
// 					$fk_url = Yii::app()->createUrl("formBill/create", array('type' => "FKDJ", 'bill_type' => "CGFK", 'common_id' => $each->common_id, 'is_yidan' => $each->is_yidan, 'company_id' => $each->supply_id, 'title_id' => $each->title_id));
					$detail_url=Yii::app()->createUrl('salesReturn/view',array('id'=>$baseform->id,'fpage'=>$_REQUEST['page'],'backUrl'=>'salesReturn/index','search_url'=>json_encode($search)));
					$num=1;
					$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
				if (checkOperation("打印")) {
					$operate.='<span><a target="_blank" class="update_b" href="'.$print_url.'" title="打印"><img src="/images/dayin.png"></a></span><abc></abc>';
				}
					//未提交
					if($baseform->form_status=='unsubmit')
					{
						if(checkOperation("销售退货:新增"))
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
						if(checkOperation("销售退货:新增"))
						{
							$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';//提交
							$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a>';
							$num+=2;
							if(!checkOperation("销售退货:审核"))
							{
								$operate.='</div>';
							}else{
								$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$baseform->form_sn.',确定审核通过此销售退货单吗？"><img src="/images/shenhe.png"></span>';
								$operate.='</div>';
							}
						}else{
							if(checkOperation("销售退货:审核"))
							{
								$operate.='<span class="check_form" url="'.$checkP_url.'" url_deny="'.$checkD_url.'" title="审核" str="单号'.$baseform->form_sn.',确定审核通过此销售退货单吗？"><img src="/images/shenhe.png"></span>';
							}
							$operate.='</div>';
						}
					}
					//已审核
					if($baseform->form_status=='approve')
					{
// 						if(checkOperation("采购运费:新增"))
// 						{
// 							$operate.='<a class="update_b" href="'.$br_url.'" title="运费登记"><span><img src="/images/yfdj.png"></span></a><abc></abc>';
// 							$num++;
// // 						}
						if(checkOperation("销售退货:新增"))
						{
							$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span class="margintop1"><img src="/images/bianji.png"></span></a><abc></abc>';
							$num++;
						}
						if($salesReturn->weight_confirm_status==1&&checkOperation("销售退货:审单"))
						{
							$num++;
							$confirm_url=Yii::app()->createUrl('salesReturn/cancelConfirm',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,"fpage"=>$_REQUEST['page']));
							$operate.='<span class="submit_form" url="'.$confirm_url.'" title="取消审单"><img src="/images/qxsd.png"></span><abc></abc>';
						}else{
							if($salesReturn->weight_confirm_status=='0'&&checkOperation("销售退货:审单"))
							{
								$num++;
								$confirm_url=Yii::app()->createUrl('salesReturn/confirm',array('id'=>$baseform->id,'last_update'=>$baseform->last_update,"fpage"=>$_REQUEST['page']));
								$operate.='<span><a class="update_b confirm_link" thisid="'.$baseform->id.'" href="'.$confirm_url.'" title="审单"><span><img src="/images/lvyue.png"></span></a></span><abc></abc>';
							}
							if(checkOperation("销售退货:审核"))
							{
								$num++;
								$operate.='<span class="cancelcheck_form" thisid="'.$baseform->id.'" url="'.$checkC_url.'" title="取消审核" str="确定要取消审核销售退货单'.$baseform->form_sn.'吗？"><img src="/images/qxsh.png"></span><abc></abc>';
							}
							if($salesReturn->flag==1&&checkOperation("入库单:新增"))
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
				}else{
					$mark = '';
					$operate = '';
				}		
				$da['data'] = array($mark,
						$operate,
						'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$baseform->form_sn.'</a>',
						'<span class="'.($baseform->form_status!='approve'?'red':'').'">'.$_status[$baseform->form_status].'</span>',
						$baseform->form_time ?  $baseform->form_time : '',
						'<span title="'.$salesReturn->client->name.'">'.$salesReturn->client->short_name.'</span>',
						'<span title="'.$salesReturn->company->name.'">'.$salesReturn->company->short_name.'</span>',
						$salesReturn->title->short_name,
						DictGoodsProperty::getProName($each->brand_id).'/'.DictGoodsProperty::getProName($each->product_id).'/'.str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)).'/'.DictGoodsProperty::getProName($each->rank_id).'/'.$each->length,
						$each->return_amount,
						number_format($each->return_weight,3),
						number_format($each->return_price),
						number_format($each->return_price*$each->return_weight,2),
						$each->input_amount,
						number_format($each->input_weight,3),
						number_format($each->fix_price),
						$each->fix_amount,
						number_format($each->fix_weight,3),						
						number_format($each->fix_price*$each->fix_weight,2),
						$_return_type[$salesReturn->return_type],
						$salesReturn->weight_confirm_status==1?'已审单':'未审单',
						$salesReturn->warehouse->name,
						$salesReturn->travel,
						$salesReturn->return_date>0?date('Y-m-d', $salesReturn->return_date):'',
						$salesReturn->team->name, //业务组
						$baseform->belong->nickname, //业务员
						$baseform->operator->nickname,
						$baseform->lastupdate->nickname,
						'<span title="'.htmlspecialchars($salesReturn->back_reason).'">'.mb_substr($salesReturn->back_reason, 0,10,"utf-8").'</span>',
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
		return array($tableHeader, $tableData, $pages,$totaldata);
	}	
	
	/*
	 * 销售退货简单列表
	 * 供创建入库单处使用
	 */	
	public static function getSimpleReturnList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"20px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"50px"),
				array('name'=>'退货单号','class' =>"flex-col sort-disabled",'width'=>"150px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'客户','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'总重量','class' =>"flex-col sort-disabled text-right",'width'=>"120px"),//
				array('name'=>'总件数','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//
				array('name'=>'已入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'已入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'未入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'业务组','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'业务员','class' =>"flex-col sort-disabled",'width'=>"90px"),//
		);
		$tableData=array();
		$model=FrmSalesReturn::model()->with('baseform');
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
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='')
			{
				$criteria->compare('t.title_id',$search['title_id']);
			}
			if($search['customer_id']!='')
			{
				$criteria->compare('t.company_id',$search['customer_id']);
			}
			if($search['owned']!='0')
			{
				$criteria->compare('baseform.owned_by',$search['owned']);
			}
		}
		$criteria->compare('baseform.form_type','XSTH');
		$criteria->compare('baseform.is_deleted','0');
		$criteria->compare('baseform.form_status','approve');
		$criteria->compare('t.weight_confirm_status','0');
		$criteria->addCondition('t.flag=1');
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['return_list']) ? intval($_COOKIE['return_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="baseform.created_at DESC";
		$frmpur=FrmSalesReturn::model()->with('baseform')->findAll($criteria);
		if($frmpur)
		{
			$da=array();
			$da['data']=array();
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
						'<span title="'.$each->company->name.'">'.$each->company->short_name.'</span>',
						number_format($each->weight,3),
						$each->amount,
						number_format($each->input_weight,3),
						$each->input_amount,
						$each->amount-$each->input_amount,
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
			$post['CommonForms']['form_type']='XSTH';
			$data['common']=(Object)$post['CommonForms'];
			$data['main']=$post['FrmSalesReturn'];
			$data['detail']=array();
			$contractAmount=0;
			$contractWeight=0;
			$total_money=0;
			for($i=0;$i<count($post['td_products']);$i++)
			{
				if($post['td_brands'][$i]==''){continue;}
				$temp=array();
				$temp['product_id']=$post['td_products'][$i];
				$temp['texture_id']=$post['td_textures'][$i];
				$temp['brand_id']=$post['td_brands'][$i];
				$temp['rank_id']=$post['td_ranks'][$i];
				$temp['length']=$post['td_length'][$i];
				$good_get=DictGoods::getGood($temp);
				if(!$good_get){
					echo "<script>alert('没有此类商品');</script>";
					return ;
				}
				$temp['id']=$post['td_id'][$i];
				$temp['card_no']=$post['td_card'][$i];
				$temp['return_price']=numChange($post['td_price'][$i]);
				$temp['return_amount']=$post['td_amount'][$i];
				$temp['return_weight']=$post['td_weight'][$i];
				$contractAmount+=$temp['return_amount'];
				$contractWeight+=$temp['return_weight'];
				$total_money+=$temp['return_weight']*$temp['return_price'];
				array_push($data['detail'], (Object)$temp);
			}
			$data['main']['amount']=$contractAmount;
			$data['main']['weight']=$contractWeight;
			$data['main']['price_amount']=$total_money;
			$data['main']=(Object)$data['main'];
			return $data;	
	}
	
	public static function getConfirmData($post)
	{
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmSalesReturn'];
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
	
	/*
	 * 获取主体信息
	 */
	public static function getMainInfo($id)
	{
		$model=CommonForms::model()->with('salesReturn')->findByPk($id);
		$return=array();
		if($model)
		{
			$purchase=$model->salesReturn;
			$return['title']=$purchase->title_id;
			$return['title_name']=$purchase->title->short_name;
			$return['company_id']=$purchase->company_id;
			$return['company_name']=$purchase->company->short_name;
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
			$return['travel']=$purchase->travel;
		}
		return json_encode($return);
	}
	
	/*
	 * 获取明细信息
	 */
	public static function getDetailData($id)
	{
		$model=CommonForms::model()->with('salesReturn','salesReturn.salesReturnDetails')->findByPk($id);
		if($model)
		{
			$details=$model->salesReturn->salesReturnDetails;
			return $details;
		}
		return false;
	}
	
}
