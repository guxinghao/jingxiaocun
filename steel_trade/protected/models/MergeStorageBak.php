<?php

/**
 * This is the biz model class for table "merge_storage_bak".
 *
 */
class MergeStorageBak extends MergeStorageBakData
{	
	public $i_amount;
	public $i_weight;
	public $l_amount;
	public $l_weight;
	public $r_amount;
	public $r_weight;
	public $ll_amount;
	public $ll_weight;
	public $can_amount;
	public $can_weight;
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
			'redeemCompany' => array(self::BELONGS_TO, 'DictCompany', 'redeem_company_id'),
			'storage' => array(self::BELONGS_TO, 'Storage', 'storage_id'),
			'warehouse'=>array(self::BELONGS_TO, 'Warehouse','warehouse_id'),
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('redeem_company_id',$this->redeem_company_id);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('left_amount',$this->left_amount);
		$criteria->compare('left_weight',$this->left_weight,true);
		$criteria->compare('retain_amount',$this->retain_amount);
		$criteria->compare('retain_weight',$this->retain_weight,true);
		$criteria->compare('lock_amount',$this->lock_amount);
		$criteria->compare('lock_weight',$this->lock_weight,true);
		$criteria->compare('pre_input_date',$this->pre_input_date);
		$criteria->compare('pre_input_time',$this->pre_input_time);
		$criteria->compare('is_transit',$this->is_transit);
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('invoice_price',$this->invoice_price,true);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('bak_date',$this->bak_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MergeStorageBak the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 获取销售单库存列表
	 */
	public static function getLockList($search)
	{
		$tableData=array();
		$model = new MergeStorageBak();
		$criteria=New CDbCriteria();
		if(!empty($search)){
			if(!empty($search['bak_date'])){
				$criteria->compare('t.bak_date',$search['bak_date'],false);
			}
			if($search['warehouse_id']!='0')
			{
				$criteria->compare('t.warehouse_id',$search['warehouse_id'],false);
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('t.title_id',$search['title_id'],false);
			}
			if($search['type']==1)
			{
				$criteria->compare('t.is_transit',0,false);
			}
			if($search['type']==2)
			{
				$criteria->compare('t.is_transit',1,false);
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
			if($search['rank']!='0')
			{
				$criteria->compare('t.rank_id',$search['rank'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('t.texture_id',$search['texture'],false);
			}
			if($search['length']>=0)
			{
				$criteria->compare('t.length',$search['length'],false);
			}
			if($search['lock']==1){
				$criteria->addCondition("t.lock_amount>0");
			}else if($search['lock']==2){
				$criteria->addCondition("t.lock_amount=0");
			}
			if($search['retain']==1){
				$criteria->addCondition("t.retain_amount>0");
			}else if($search['retain']==2){
				$criteria->addCondition("t.retain_amount=0");
			}
			if($search['left'])
			{
				if($search['left']==2)
				{
					$criteria->addCondition('t.left_amount=0');
				}else{
					$criteria->addCondition('t.left_amount!=0');
				}				
			}
		}
		
		$criteria->addCondition("t.is_deleted=0");
		$criteria->order = "t.product_id,t.texture_id,t.rank_id,t.pre_input_date ASC";
		$c = clone $criteria;
		
		$c->select = "sum(input_amount) as i_amount,sum(input_weight) as i_weight,sum(lock_amount) as l_amount,sum(retain_amount) as r_amount,sum(retain_weight) as r_weight,"
				."sum(left_amount) as ll_amount,sum(left_weight) as ll_weight,sum(lock_weight) as l_weight,"
				."sum(left_amount-lock_amount-retain_amount) as can_amount,sum(left_weight-lock_weight-retain_weight) as can_weight";
		$alldetail = MergeStorageBak::model()->find($c);
		$totaldata = array();
		$totaldata["i_amount"] = $alldetail->i_amount;
		$totaldata["i_weight"] = $alldetail->i_weight;
		$totaldata["l_amount"] = $alldetail->l_amount;
		$totaldata["l_weight"] = $alldetail->l_weight;
		$totaldata["r_amount"] = $alldetail->r_amount;
		$totaldata["r_weight"] = $alldetail->r_weight;
		$totaldata["ll_amount"] = $alldetail->ll_amount;
		$totaldata["ll_weight"] = $alldetail->ll_weight;
		$totaldata["can_amount"] = $alldetail->can_amount;
		$totaldata["can_weight"] = $alldetail->can_weight;
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['stoagelock_list']) ? intval($_COOKIE['stoagelock_list']) : Yii::app()->params['pageCount'];
		//$pages->pageSize = 2;
		$pages->applyLimit($criteria);
	
		$details=$model->findAll($criteria);
		$_inputTime = array(6=>"00:00~06:00",12=>"06:00~12:00",18=>"12:00~18:00",24=>"18:00~24:00");
		$num = 0;
		if($details)
		{
			$da[] = array();
			foreach ($details as $li){
				//如果是代销，获取每日代销价格
				if($search['sales']){
					$li->cost_price = 0; //暂定为0
				}
				$product_std = $li->product_id;
				$rand_std = $li->rank_id;
				$texture_std = $li->texture_id;
				$brand_std = $li->brand_id;
				$length = $li->length;
				$num++;
				if($li->is_transit > 0)
				{
					$strtime = '<span class="input_date" num=1>'.date("Y-m-d",$li->pre_input_date);
					if($li->pre_input_time > 0)
					{
						$strtime.= "&nbsp;&nbsp;".$_inputTime[$li->pre_input_time].'</span>';
					}
				}else{
					$strtime = '<span class="input_date" num=0 t_id="'.$li->id.'">在库</span>';
				}
				$details_url = Yii::app()->createUrl('FrmSales/locklist',array('storage_id'=>$li->id,"fpage"=>$_REQUEST['page']));
				$str = '<div class="cz_list_btn">'
						.'<a href="'.$details_url.'" class="update_b"><span><img src="/images/detail.png"></span></a>'
						."</div>";
				// if($li->lock_amount>0){
				// 	$lock = '<a href="'.$details_url.'" class="update_b">'.$li->lock_amount.'</a>';
				// }else{
					$lock = $li->lock_amount;
				// }
				$weight = DictGoods::getWeightByStorage($li);
				$can_amount = $li->left_amount-$li->retain_amount-$li->lock_amount;
				$da["data"] = array(
						'<span class="warehouse_name">'.$li->warehouse->name.'</span>',//仓库
						'<span class="brand">'.DictGoodsProperty::getProName($brand_std).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($product_std).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($texture_std)).'</span>',
						'<span class="rand">'.DictGoodsProperty::getProName($rand_std).'</span>',
						'<span class="length">'.$length.'</span>',
						$weight,						
						$can_amount,
						number_format($li->left_weight-$li->lock_weight-$li->retain_weight,3),
						$li->left_amount,
						number_format($li->left_weight,3),
						$lock,
						number_format($li->lock_weight,3),
						$li->retain_amount,
						number_format($li->retain_weight,3),
						$li->input_amount,
						number_format($li->input_weight,3),
						$li->is_transit>0?"是":"",
						$strtime,
						$li->cost_price,
						$li->invoice_price>0?$li->invoice_price:"",
						$li->redeem_company_id>0?"是":"",
						'<span class="title_name">'.$li->title->short_name.'</span>',
						'<span title="'.$li->redeemCompany->name.'">'.$li->redeemCompany->short_name.'</span>',
				);
				$da["group"] = $num;
				array_push($tableData,$da);
			}
		}
	
		return array($tableData,$pages,$totaldata);
	}
}
