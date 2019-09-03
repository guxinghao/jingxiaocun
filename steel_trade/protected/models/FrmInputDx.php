<?php

/**
 * This is the biz model class for table "frm_input_dx".
 *
 */
class FrmInputDx extends FrmInputDxData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="DXRK"'),
				'inputDetailsDx'=>array(self::HAS_MANY,'InputDetailDx','input_id'),
				'supply'=>array(self::BELONGS_TO,'DictCompany','supply_id'),
				'title'=>array(self::BELONGS_TO,'DictTitle','title_id'),
				'warehouse'=>array(self::BELONGS_TO,'Warehouse','warehouse_id'),
				'team'=>array(self::BELONGS_TO,'Team','team_id'),
				'contact'=>array(self::BELONGS_TO,'CompanyContact','contact_id'),
				'storages' => array(self::HAS_MANY, 'Storage', 'frm_input_id','condition'=>'storages.is_dx=1'),
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
			'contact_id' => 'Contact',
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
		$criteria->compare('contact_id',$this->contact_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmInputDx the static model class
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
				array('name'=>'入库单号','class' =>"sort-disabled",'width'=>"90px"),
				array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"80px"),
				array('name'=>'供应商','class' =>"flex-col sort-disabled",'width'=>"60px"),
				array('name'=>'公司','class' =>"flex-col sort-disabled",'width'=>"60px"),//
				array('name'=>'卡号','class' =>"flex-col sort-disabled",'width'=>"150px"),//
				array('name'=>'产地/品名/材质/规格/长度','class' =>"flex-col sort-disabled",'width'=>"200px"),//
				array('name'=>'件数','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),//
				array('name'=>'重量','class' =>"flex-col sort-disabled text-right",'width'=>"80px"),//				
				array('name'=>'制单人','class' =>"flex-col sort-disabled",'width'=>"70px"),//
				array('name'=>'入库人','class' =>"flex-col sort-disabled",'width'=>"70px"),//
				array('name'=>'入库时间','class' =>"flex-col sort-disabled",'width'=>"90px"),//
				array('name'=>'备注','class' =>"flex-col sort-disabled",'width'=>"230px"),//
		);
		if($search['form_status']=='delete')
		{
			$reason=array('name'=>'作废原因','class' =>"flex-col sort-disabled",'width'=>"230px");
			array_push($tableHeader, $reason);
			array_splice($tableHeader, 1,1);
		}
		$tableData=array();	
		$model=InputDetailDx::model()->with(array('input','input.baseform'));
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			$criteria->addCondition('baseform.form_sn like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(baseform.form_time) >='.strtotime($search['time_L']));
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(baseform.form_time) <='.(strtotime($search['time_H'])+86400));
			}
			if($search['vendor']!='0')
			{
				$criteria->compare('input.supply_id',$search['vendor']);
			}
			if($search['form_status']!='0')
			{
				$criteria->compare('baseform.form_status',$search['form_status']);
			}else{
				$criteria->compare('baseform.is_deleted','0');
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
		}
		$criteria->compare('baseform.form_type','DXRK');		
		
		$newc=clone $criteria;
// 		$newcri->with=array('input','input.baseform');
		$newc->select = "sum(input_amount) as total_amount,sum(input_weight) as total_weight,count(*) as total_num";
		$all=InputDetailDx::model()->find($newc);
		$totaldata = array();
		$totaldata["amount"] = $all->total_amount;
		$totaldata["weight"] = $all->total_weight;
		$totaldata["total_num"] = $all->total_num;
		
		$pages = new CPagination();
		$pages->itemCount =InputDetailDx::model()->with(array('input','input.baseform'))->count($criteria);
		$pages->pageSize =intval($_COOKIE['input_list']) ? intval($_COOKIE['input_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=InputDetailDx::model()->with(array('input','input.baseform'=>array('order'=>'baseform.created_at DESC')))->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未入库','submited'=>'未入库','approve'=>'已入库','delete'=>'已作废');
			$baseform='';
			$i=1;
			foreach ($details as $each)
			{
				$input=$each->input;
				$mark=$i;
				if($each->input->baseform!=$baseform)
				{
					$baseform=$each->input->baseform;
					$i++;
					$edit_url = Yii::app()->createUrl('input/update',array('id'=>$baseform->id,'type'=>'dxrk','last_update'=>$baseform->last_update,'fpage'=>$_REQUEST['fpage']));
					$sub_url =  Yii::app()->createUrl('input/submit',array('id'=>$baseform->id,'type'=>$type_sub,'ty'=>'dxrk','last_update'=>$baseform->last_update));
					$del_url= Yii::app()->createUrl('input/deleteform',array('id'=>$baseform->id,'ty'=>'dxrk','last_update'=>$baseform->last_update));
					$checkP_url=Yii::app()->createUrl('input/check',array('id'=>$baseform->id,'ty'=>'dxrk','type'=>'pass','last_update'=>$baseform->last_update));
					$checkD_url=Yii::app()->createUrl('input/check',array('id'=>$baseform->id,'ty'=>'dxrk','type'=>'deny','last_update'=>$baseform->last_update));
					$checkC_url=Yii::app()->createUrl('input/check',array('id'=>$baseform->id,'ty'=>'dxrk','type'=>'cancle','last_update'=>$baseform->last_update));
					$detail_url=Yii::app()->createUrl('input/view',array('id'=>$baseform->id,'type'=>'dxrk','fpage'=>$_REQUEST['page'],'search_url'=>json_encode($search)));
					$operate='<div class="cz_list_btn"><input type="hidden" thisid="'.$baseform->id.'" class="form_sn" value="'.$baseform->form_sn.'">';
// 					$operate='<a class="update_b" href="'.$detail_url.'" title="查看详情"><i class="icon icon-file-text-o"></i></a>';
					if ($baseform->form_status=='unsubmit'&&checkOperation("代销入库单:新增")){
						$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
					}
					if ($baseform->form_status=='submited'||$baseform->form_status=='unsubmit'&&checkOperation("代销入库单:新增")){
						$operate.='<span class="delete_form" url="'.$del_url.'" title="作废"><img src="/images/zuofei.png"></span>';
					}
					if($baseform->form_status=='submited'||$baseform->form_status=='unsubmit'&&checkOperation("代销入库单:入库"))
					{
						$operate.='<span class="submit_form" url="'.$checkP_url.'" title="入库" is_input="input" str="确定要入库代销入库单'.$baseform->form_sn.'吗？"><img src="/images/ruku.png"></span>';
					}elseif($baseform->form_status=='approve'&&checkOperation("代销入库单:入库"))
					{
						$operate.='<span class="submit_form" url="'.$checkC_url.'" title="取消入库" str="确定要取消入库代销入库单'.$baseform->form_sn.'吗？"><img src="/images/qxrk.png"></span>';
					}
					$operate.='</div>';
				}else{
					$mark='';
					$operate='';
				}
				$da['data']=array($mark,
						$operate,
						'<a title="查看详情" href="'.$detail_url.'" class="a_view">'.$baseform->form_sn.'</a>',
						$_status[$baseform->form_status],//审批状态
						$baseform->form_time?$baseform->form_time:'',
						'<span title="'.$input->supply->name.'">'.$input->supply->short_name.'</span>',
						$input->title->short_name,
						$each->card_id,
						DictGoodsProperty::getProName($each->brand_id).'/'.DictGoodsProperty::getProName($each->product_id).'/'.str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id)).'/'.DictGoodsProperty::getProName($each->rank_id).'/'.$each->length,
						$each->input_amount,
						number_format($each->input_weight,3),						
						$baseform->operator->nickname,
						$baseform->approver->nickname,
						$baseform->approved_at>943891200?date('Y-m-d',$baseform->approved_at):'',
						'<span title="'.htmlspecialchars($baseform->comment).'">'.mb_substr($baseform->comment, 0,15,'utf-8').'</span>',
				);
				if($search['form_status']=='delete'){
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
		$post['CommonForms']['form_type']='DXRK';
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmInput'];
		$totalA=0;
		$totalW=0;
		$data['detail']=array();
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
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
				echo "<script>alert('您选择了不存在的商品');</script>";
				return ;
			}
// 			$temp['cost_price']=$post['td_price'][$i];
			$temp['input_amount']=$post['td_amount'][$i];
			$temp['input_weight']=$post['td_weight'][$i];
			$temp['purchase_detail_id']=$post['td_id'][$i];
			$temp['card_id']=$post['td_card_id'][$i];
			$totalA+=$temp['input_amount'];
			$totalW+=$temp['input_weight'];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']['amount']=$totalA;
		$data['main']['weight']=$totalW;
		$data['main']=(Object)$data['main'];
		return $data;
	}
	
	public static function getUpdateData($post)
	{
		$data['common']=(Object)$post['CommonForms'];
		$data['main']=$post['FrmInput'];
		$totalA=0;
		$totalW=0;
		$data['detail']=array();
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
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
				echo "<script>alert('您选择了不存在的商品');</script>";
				return ;
			}
			$temp['input_amount']=$post['old_td_amount'][$i];
			$temp['input_weight']=$post['old_td_weight'][$i];
			$temp['card_id']=$post['old_td_card_id'][$i];
			$totalA+=$temp['input_amount'];
			$totalW+=$temp['input_weight'];
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
			$good_get=DictGoods::getGood($temp);
			if(!$good_get){
				echo "<script>alert('您选择了不存在的商品');</script>";
				return ;
			}
			$temp['input_amount']=$post['td_amount'][$i];
			$temp['input_weight']=$post['td_weight'][$i];
			$temp['card_id']=$post['td_card_id'][$i];
			$totalA+=$temp['input_amount'];
			$totalW+=$temp['input_weight'];
			array_push($data['detail'], (Object)$temp);
		}
		$data['main']['amount']=$totalA;
		$data['main']['weight']=$totalW;
		$data['main']=(Object)$data['main'];
		return $data;
	}
}
