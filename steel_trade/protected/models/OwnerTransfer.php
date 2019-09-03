<?php

/**
 * This is the biz model class for table "owner_transfer".
 *
 */
class OwnerTransfer extends OwnerTransferData
{
	

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
			'team' => array(self::BELONGS_TO, 'Team', 'team_id'),
			'ownerTransferDetails' => array(self::HAS_MANY, 'OwnerTransferDetail', 'owner_transfer_id'),
			'frmsales'=>array(self::BELONGS_TO, 'FrmSales', 'frm_sales_id'),
			'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="XSZK"'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title_id' => 'Title',
			'company_id' => 'Company',
			'team_id' => 'Team',
			'comment' => 'Comment',
			'frm_sale_id' => 'Frm Sale',
			'company_name' => 'Company Name',
			'warehouse_id' => 'Warehouse',
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
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('frm_sale_id',$this->frm_sale_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OwnerTransfer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 创建一个转库
	 */
	public static function createOutput($post=array())
	{
		$post['CommonForms']['form_type']='XSZK';
		$post['CommonForms']['form_time']=date("Y-m-d H:i:s");
		$data['common']=(Object)$post['CommonForms'];
	
		$data['detail']=array();
		$amount = 0;
		$weight = 0;
		for($i=0;$i<count($post["amount"]);$i++){
			$temp = array();
			$temp["storage_id"] = $post["card_id"][$i];
			$temp["amount"] = intval($post["amount"][$i]);
			$temp["weight"] = $post["weight"][$i];
			$temp["product_id"] = $post["product"][$i];
			$temp["rank_id"] = $post["rank"][$i];
			$temp["brand_id"] = $post["brand"][$i];
			$temp["texture_id"] = $post["texture"][$i];
			$temp["length"] = $post["length"][$i];
				
			$amount +=$post["amount"][$i];
			$weight +=$post["weight"][$i];
			array_push($data['detail'],(Object)$temp);
		}
		$data['main']=$post['out'];
		$data['main']=(Object)$data['main'];
		$allform=new Transfer($id);
		if($post['submit_type'] == 1){
			$result = $allform->createSubmitForm($data);
		}else{
			$result = $allform->createForm($data);
		}
		return $result;
	}
	
	/*
	 * 获取出库单列表
	 * id为销售单id，如果为空则查询所有的销售单
	 */
	public static function getFormList($search,$id="")
	{
		$tableData=array();
		$model = new OwnerTransferDetail();
		$criteria=New CDbCriteria();
		$withArray = array();
		$criteria->with = array("ownerTransfer",'ownerTransfer.frmsales','ownerTransfer.frmsales.baseform2'=>array("condition"=>"baseform2.form_type='XSD'"),'ownerTransfer.baseform'=>array('order'=>'baseform.created_at DESC'),"storage");
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('baseform.form_sn like :contno or storage.card_no like :contno or baseform2.form_sn like :contno');
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
				$criteria->compare('t.rand_id',$search['rand'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],false);
			}
			if($search['sales_status'])
			{
				$criteria->compare('frmsales.sales_type',$search['sales_status'],false);
			}
		}
	
			if($search['form_status'])
			{
				$criteria->compare('baseform.form_status',$search['form_status'],false);
			}else{
				$criteria->compare('baseform.is_deleted','0',false);
			}
			if($id){
				$criteria->compare('ownerTransfer.frm_sales_id',$id,false);
			}
		
			$c = clone $criteria;
			$pages = new CPagination();
			$pages->itemCount = $model->count($criteria);
			$pages->pageSize =intval($_COOKIE['ownerTransfer_list']) ? intval($_COOKIE['ownerTransfer_list']) : Yii::app()->params['pageCount'];
			$pages->applyLimit($criteria);
			$details=$model->findAll($criteria);
			$c->select = "sum(t.amount) as total_amount,sum(t.weight) as total_weight,count(*) as total_num";
			$alldetail = OwnerTransferDetail::model()->find($c);
			
			$totaldata = array();
			$totaldata["amount"] = $alldetail->total_amount;
			$totaldata["weight"] = $alldetail->total_weight;
			//$totaldata["price"] = $alldetail->total_price;
			$totaldata["total_num"] = $alldetail->total_num;
			$status = array("未转库","已转库","已作废");
			$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
			if($details){
				$da=array();
				$da['data']=array();
				$i=1;
				$baseform='';
				foreach ($details as $each)
				{
					$frmSales = $each->ownerTransfer->frmsales;
					$frmOutput = $each->ownerTransfer;
					$mark = $i;
					if($each->ownerTransfer->baseform != $baseform){
						$baseform = $each->ownerTransfer->baseform;
						$i++;
						if($frmOutput->input_status == 0)
						{
							$type_sub="submit";
							$title_sub="转库并推送";
							$img_url = "/images/chuku.png";
						}elseif($frmOutput->input_status == 1)
						{
							$type_sub="cancle";
							$title_sub="取消转库";
							$img_url = "/images/qxck.png";
						}
							
						$trash_url = Yii::app()->createUrl('ownerTransfer/deleteform',array("id"=>$baseform->id,'last_update'=>$baseform->last_update,"fpage"=>$_REQUEST['page']));
						$detail_url = Yii::app()->createUrl('ownerTransfer/detail',array('id'=>$each->owner_transfer_id,"fpage"=>$_REQUEST['page'],"sid"=>$id));
							
						$detail_sales = Yii::app()->createUrl('FrmSales/detail',array('id'=>$frmSales->baseform->id));
						$detail_name = $frmSales->baseform->form_sn;
						$edit_url = Yii::app()->createUrl('ownerTransfer/update',array("id"=>$each->owner_transfer_id,"fpage"=>$_REQUEST['page'],"sid"=>$id,"from"=>$_REQUEST['from']));
						$sub_url =  Yii::app()->createUrl('ownerTransfer/submit',array('id'=>$baseform->id,'type'=>$type_sub,'last_update'=>$baseform->last_update));
						$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$baseform->form_sn.'">';
						if(checkOperation("转库单:出库") && $frmOutput->input_status == 0){
							if($title_sub){
								$operate.='<span class="submit_form" url="'.$sub_url.'" title="'.$title_sub.'"><img src="'.$img_url.'"></span>';
							}
						}
						if(checkOperation("转库单:新增")){
							if($frmOutput->input_status == 0){
								$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
								$operate.='<span class="delete_form" lastdate="'.$baseform->last_update.'" id="/index.php/ownerTransfer/deleteform/'.$baseform->id.'" onclick="deleteIt(this);" title="作废"><img src="/images/zuofei.png"></span>';
							}
						}
						$operate.='</div>';
					}else{
						$mark='';
						$operate='';
					}
					$std=DictGoodsProperty::getProName($each->brand_id)."/".DictGoodsProperty::getProName($each->product_id)."/".DictGoodsProperty::getProName($each->texture_id)."/".DictGoodsProperty::getProName($each->rank_id)."/".intval($each->length);
					
					$da['data']=array(
							$mark,
							$operate,
							'<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$baseform->form_sn.'</a>',
							$status[$frmOutput->input_status],
							$baseform->form_time,
							$frmOutput->title->short_name,
							'<span title="'.$frmOutput->company->name.'">'.$frmOutput->company->short_name.'</span>',
							$each->storage->card_no,
							str_replace('E','<span class="red">E</span>',$std),
							//$frmSales->travel,
							$each->amount,
							number_format($each->weight,3),
							'<a href="'.$detail_sales.'" title="查看详情" class="a_view">'.$detail_name.'</a>',
							$sales_type[$frmSales->sales_type],
 							$baseform->operator->nickname,//制单人
// 							$frmOutput->outputby->nickname,
//							$frmOutput->output_at>0?date("Y-m-d",$frmOutput->output_at):"",
							//$baseform->comment,
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
}
