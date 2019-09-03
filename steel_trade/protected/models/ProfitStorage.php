<?php

/**
 * This is the biz model class for table "profit_storage".
 *
 */
class ProfitStorage extends ProfitStorageData
{
	
	public $sale_money;
	public  $weight,$fee;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'storage'=>array(self::BELONGS_TO,'Storage','storage_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'storage_id' => 'Storage',
			'card_no' => 'Card No',
			'purchase_id' => 'Purchase',
			'form_sn' => 'Form Sn',
			'purchase_date' => 'Purchase Date',
			'input_date' => 'Input Date',
			'title_id' => 'Title',
			'title_name' => 'Title Name',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'left_weight' => 'Left Weight',
			'lock_weight' => 'Lock Weight',
			'price' => 'Price',
			'type' => 'Type',
			'purchase_price' => 'Purchase Price',
			'purchase_money' => 'Purchase Money',
			'purchase_freight' => 'Purchase Freight',
			'warehouse_fee' => 'Warehouse Fee',
			'warehouse_rebate' => 'Warehouse Rebate',
			'supply_rebate' => 'Supply Rebate',
			'sale_subsidy' => 'Sale Subsidy',
			'invoice' => 'Invoice',
			'pledge_fee' => 'Pledge Fee',
			'edit_at' => 'Edit At',
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
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('purchase_date',$this->purchase_date);
		$criteria->compare('input_date',$this->input_date);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('left_weight',$this->left_weight,true);
		$criteria->compare('lock_weight',$this->lock_weight,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('purchase_price',$this->purchase_price,true);
		$criteria->compare('purchase_money',$this->purchase_money,true);
		$criteria->compare('purchase_freight',$this->purchase_freight,true);
		$criteria->compare('warehouse_fee',$this->warehouse_fee,true);
		$criteria->compare('warehouse_rebate',$this->warehouse_rebate,true);
		$criteria->compare('supply_rebate',$this->supply_rebate,true);
		$criteria->compare('sale_subsidy',$this->sale_subsidy,true);
		$criteria->compare('invoice',$this->invoice,true);
		$criteria->compare('pledge_fee',$this->pledge_fee,true);
		$criteria->compare('edit_at',$this->edit_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfitStorage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/*
	 * 库存利润列表
	 */
	public static function  getStorageProfit($search)
	{
		$tableData=array();
		$tableHeader=array(
				array('name'=>'入库日期','class' =>"sort-disabled text-left",'width'=>"80px"),//修				
				array('name'=>'产地','class' =>"sort-disabled text-left",'width'=>"60px"),//
				array('name'=>'卡号','class' =>"flex-col sort-disabled text-left",'width'=>"110px"),
				array('name'=>'销售公司','class' =>"flex-col sort-disabled text-left",'width'=>"60px"),
				array('name'=>'品名','class' =>"flex-col sort-disabled text-left",'width'=>"70px"),//
				array('name'=>'材质/规格/长度','class' =>"flex-col sort-disabled text-left",'width'=>"110px"),
				array('name'=>'数量','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'预估销售单价','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'预估销售金额','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),
				array('name'=>'预估利润','class' =>"flex-col sort-disabled text-right",'width'=>"85px"),
				array('name'=>'采购/退货单号','class' =>"flex-col sort-disabled text-left",'width'=>"110px"),
				array('name'=>'成本单价','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),
				array('name'=>'成本金额','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),
				array('name'=>'运费','class' =>"flex-col sort-disabled text-right",'width'=>"65px"),
				array('name'=>'发票成本','class' =>"flex-col sort-disabled text-right",'width'=>"85px"),
// 				array('name'=>'仓库费用','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),
				array('name'=>'托盘费用','class' =>"flex-col sort-disabled text-right",'width'=>"70px"),
				array('name'=>'预估钢厂返利','class' =>"flex-col sort-disabled text-right",'width'=>"85px"),
// 				array('name'=>'仓库返利','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),
// 				array('name'=>'预估销售提成','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'仓库','class' =>"flex-col sort-disabled text-left",'width'=>"80px"),
				array('name'=>'备注','class' =>"flex-col sort-disabled text-left",'width'=>"200px"),
		);
		$time=date('Y-m-d');
// 		$dg=DictTitle::model()->find('short_name ="登钢商贸"')->id;
// 		$jm=DictTitle::model()->find('short_name="爵淼实业"')->id;
		$model=new ProfitStorage();
		$criteria=new CDbCriteria();
// 		$criteria->together=true;
// 		$criteria->with=array('inputDetail','inputDetail.input','inputDetail.input.baseform_pur');
		if($search['keywords']){$criteria->addCondition('t.form_sn like "%'.$search['keywords'].'%" ');}
		if($search['company']){$criteria->compare('t.title_id',$search['company']);}
		if($search['product']){$criteria->compare('t.product_id',$search['product']);}
		if($search['rank']){$criteria->compare('t.rank_id',$search['rank']);}
		if($search['texture']){$criteria->compare('t.texture_id',$search['texture']);}
		if($search['brand']){$criteria->compare('t.brand_id',$search['brand']);}
		if($search['time_L']){$criteria->addCondition('t.input_date>='.strtotime($search['time_L']));}
		if($search['time_H']){$criteria->addCondition('t.input_date<='.strtotime($search['time_H']));}	
		if($search['vendor']){$criteria->compare('t.supply_id',$search['vendor']);}
		if($search['warehouse']){$criteria->compare('t.warehouse_id', $search['warehouse']);}
		if($search['comment']){$criteria->addCondition('t.comment like "'.ProfitCollecting::$source_trans[$search['comment']].'"');}
		if($search['pur_confirm']!=''){
			$criteria->with=array('storage');
			if($search['pur_confirm']==0)
			{
				$criteria->addCondition('storage.is_price_confirmed=0 or isnull(storage.is_price_confirmed)');
			}else{
				$criteria->compare('storage.is_price_confirmed', 1);
			}			
		}
// 		if($search['dgjm_title']){$criteria->addInCondition('title_id',array($dg,$jm));}
// 		elseif($search['dg_title']){$criteria->compare('title_id',$dg);}
// 		elseif($search['jm_title']){$criteria->compare('title_id',$jm);}
// 		else{$criteria->addNotInCondition('title_id', array($dg,$jm));}
		$criteria->addCondition('(t.left_weight-t.lock_weight)!=0');
		//汇总
		$newCri=clone $criteria;
		$newCri->select='sum(t.left_weight-t.lock_weight) as weight,sum((t.left_weight-t.lock_weight)*t.price) as fee,sum(t.profit) as  profit
				,sum(t.purchase_money) as purchase_money ,sum(t.purchase_freight) as purchase_freight ,sum(t.invoice) as invoice,
				sum(t.warehouse_fee) as warehouse_fee,sum(t.pledge_fee) as pledge_fee,sum(t.supply_rebate) as supply_rebate,sum(t.warehouse_rebate) as warehouse_rebate,sum(t.sale_subsidy) as sale_subsidy';
		$total=$model->find($newCri);
		$totalData=array();
		
		$totalData['weight']=$total->weight;
		$totalData['fee']=$total->fee;
		$totalData['sales_profit']=$total->profit;
		
		$totalData1=array('合计','','','','','',
				number_format($total->weight,3),'',number_format($total->fee,2),number_format($total->profit,2),'','',number_format($total->purchase_money,2),
				number_format($total->purchase_freight,2),number_format($total->invoice,2),//number_format($total->warehouse_fee,2),
				number_format($total->pledge_fee,2),number_format($total->supply_rebate,2),
// 				number_format($total->warehouse_rebate,2),number_format($total->sale_subsidy,2),
				'',''
		);
		
		
		$pages=new CPagination();
		$pages->itemCount=$model->count($criteria);
		$pages->pageSize =intval($_COOKIE['profit_list']) ? intval($_COOKIE['profit_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order='t.input_date desc';
		$details=$model->findAll($criteria);
		if($details)
		{
			$i=1;
// 			$subsity=SaleSubsidy::getSubsity('第一阶段');
			foreach ($details as $each)
			{	
// 				$price=QuotedDetail::getEstimatePrice($each->product_id,$each->rank_id,$each->texture_id,$each->brand_id,$each->length,$time,$each->warehouse_id);
				$pro_name=DictGoodsProperty::getProName($each->product_id);
				
				$da['data']=array(
						date('Y-m-d',$each->input_date),						
						DictGoodsProperty::getProName($each->brand_id),
						$each->card_no,
						$each->title_name,
						$pro_name,
						DictGoodsProperty::getProName($each->texture_id).'*'.DictGoodsProperty::getProName($each->rank_id).($each->length==0?'':'*'.$each->length),
						number_format($each->left_weight-$each->lock_weight,3),
						$each->price,//预估销售单价
						number_format(($each->left_weight-$each->lock_weight)*$each->price,2),//预估销售金额
						number_format($each->profit,2),
						$each->form_sn,//采购单
						number_format($each->purchase_price,2),//
						number_format($each->purchase_money,2),
						number_format($each->purchase_freight,2),//采购运费
						number_format($each->invoice,2),
// 						number_format($each->warehouse_fee,2),//仓库费用
						number_format($each->pledge_fee,2),//托盘费用
						number_format($each->supply_rebate,2),//预估钢厂返利
// 						number_format($each->warehouse_rebate,2),//仓库返利
// 						number_format($each->sale_subsidy,2),//预估销售提成
						Warehouse::getName($each->warehouse_id),
						'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment, 0,20,"utf-8").'</span>',
				);
				$da['group']=$i;
				array_push($tableData,$da);
				$i++;
			}
			array_push($tableData,array('data'=>$totalData1,'group'=>0));
		}
		return array($tableHeader,$tableData,$pages,$totalData);
	}
	
	
	/*
	 * 获取库存预估利润导出数据
	 */
	public static function  getExportData($search)
	{
		$time=date('Y-m-d');
		$model=new ProfitStorage();
		$criteria=new CDbCriteria();
		// 		$criteria->together=true;	
		if($search['keywords']){$criteria->addCondition('t.form_sn like "%'.$search['keywords'].'%" ');}
		if($search['company']){$criteria->compare('t.title_id',$search['company']);}
		if($search['product']){$criteria->compare('t.product_id',$search['product']);}
		if($search['rank']){$criteria->compare('t.rank_id',$search['rank']);}
		if($search['texture']){$criteria->compare('t.texture_id',$search['texture']);}
		if($search['brand']){$criteria->compare('t.brand_id',$search['brand']);}
		if($search['time_L']){$criteria->addCondition('t.input_date>='.strtotime($search['time_L']));}
		if($search['time_H']){$criteria->addCondition('t.input_date<='.strtotime($search['time_H']));}
		if($search['vendor']){$criteria->compare('t.supply_id',$search['vendor']);}
		if($search['warehouse']){$criteria->compare('t.warehouse_id', $search['warehouse']);}
		if($search['comment']){$criteria->addCondition('t.comment like "'.ProfitCollecting::$source_trans[$search['comment']].'"');}
		if($search['pur_confirm']!=''){
			$criteria->with=array('storage');
			if($search['pur_confirm']==0)
			{
				$criteria->addCondition('storage.is_price_confirmed=0 or isnull(storage.is_price_confirmed)');
			}else{
				$criteria->compare('storage.is_price_confirmed', 1);
			}
		}
		$criteria->addCondition('(t.left_weight-t.lock_weight)!=0');
		//汇总
		$newCri=clone $criteria;
		$newCri->select='sum(t.left_weight-t.lock_weight) as weight,sum((t.left_weight-t.lock_weight)*t.price) as fee,sum(t.profit) as  profit
				,sum(t.purchase_money) as purchase_money ,sum(t.purchase_freight) as purchase_freight ,sum(t.invoice) as invoice,
				sum(t.warehouse_fee) as warehouse_fee,sum(t.pledge_fee) as pledge_fee,sum(t.supply_rebate) as supply_rebate,sum(t.warehouse_rebate) as warehouse_rebate,sum(t.sale_subsidy) as sale_subsidy';
		$total=$model->find($newCri);	
		$totalData1=array('合计','','','','','','','',
				$total->weight,'',$total->fee,$total->profit,'','',$total->purchase_money,
				$total->purchase_freight,$total->invoice,$total->warehouse_fee,
				$total->pledge_fee,$total->supply_rebate,$total->warehouse_rebate,$total->sale_subsidy,'',''
		);	
		$criteria->order='t.input_date desc';
		$details=$model->findAll($criteria);
		$content=array();
		if($details)
		{
			foreach ($details as $each)
			{
				$pro_name=DictGoodsProperty::getProName($each->product_id);	
				$temp=array(
						date('Y-m-d',$each->input_date),
						DictGoodsProperty::getProName($each->brand_id),
						$each->card_no,
						$each->title_name,
						$pro_name,
						DictGoodsProperty::getProName($each->texture_id),
						DictGoodsProperty::getProName($each->rank_id),
						$each->length,
						$each->left_weight-$each->lock_weight,
						$each->price,//预估销售单价
						($each->left_weight-$each->lock_weight)*$each->price,//预估销售金额
						$each->profit,
						$each->form_sn,//采购单
						$each->purchase_price,//
						$each->purchase_money,
						$each->purchase_freight,//采购运费
						$each->invoice,
						$each->warehouse_fee,//仓库费用
						$each->pledge_fee,//托盘费用
						$each->supply_rebate,//预估钢厂返利
						$each->warehouse_rebate,//仓库返利
						$each->sale_subsidy,//预估销售提成
						Warehouse::getName($each->warehouse_id),
						$each->comment,
				);				
				array_push($content,$temp);				
			}
		}
		array_push($content,$totalData1);
		return $content;
	}
	
	
	
	

}
