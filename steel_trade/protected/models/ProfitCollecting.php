<?php

/**
 * This is the biz model class for table "profit_collecting".
 *
 */
class ProfitCollecting extends ProfitCollectingData
{
	
	public  static $source=array(
			1=>'采购单已审，价格来自库存成本',
			2=>'代销销售单，价格来自补单总金额/总重量',
			3=>'获取距销售开单时间最近库存成本',
			4=>'价格来自网价',
			5=>'价格来自网价+基价差',
			6=>'价格来自网价均价',
			7=>'价格来自网价均价+基价差',
			8=>'销售退货库存，价格来自库存成本',
			9=>'先销后进销售单，价格来自补单总金额/总重量'			
	);
	public  static $source_1=array(
			1=>'采购单已审，价格来自库存成本',			
			4=>'价格来自网价',
			5=>'价格来自网价+基价差',
			6=>'价格来自网价均价',
			7=>'价格来自网价均价+基价差',
			8=>'销售退货库存，价格来自库存成本',						
	);
	public static $source_trans=array(
			1=>'%采购单已审，价格来自库存成本%',
			2=>'%代销销售单，价格来自补单总金额/总重量%',
			3=>'%获取距销售开单时间最近库存成本%',
			4=>'%网价',
			5=>'%网价+基价差',
			6=>'%网价均价',
			7=>'%网价均价+基价差',
			8=>'%销售退货库存，价格来自库存成本%',
			9=>'%先销后进销售单，价格来自补单总金额/总重量%'
	);

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sales_id' => 'Sales',
			'form_sn' => 'Form Sn',
			'sales_date' => 'Sales Date',
			'title_id' => 'Title',
			'title_name' => 'Title Name',
			'company_id' => 'Company',
			'company_name' => 'Company Name',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'sales_profit' => 'Sales Profit',
			'weight' => 'Weight',
			'price' => 'Price',
			'fee' => 'Fee',
			'sales_freight' => 'Sales Freight',
			'sales_rebate' => 'Sales Rebate',
			'purchase_form_sn' => 'Purchase Form Sn',
			'purchase_price' => 'Purchase Price',
			'purchase_money' => 'Purchase Money',
			'purchase_freight' => 'Purchase Freight',
			'warehouse_fee' => 'Warehouse Fee',
			'warehouse_rebate' => 'Warehouse Rebate',
			'supply_rebate' => 'Supply Rebate',
			'sale_subsidy' => 'Sale Subsidy',
			'invoice' => 'Invoice',
			'pledge_fee' => 'Pledge Fee',
			'hight_open' => 'Hight Open',
			'owner_id' => 'Owner',
			'owner_name' => 'Owner Name',
			'is_yidan' => 'Is Yidan',
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
		$criteria->compare('sales_id',$this->sales_id);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('sales_date',$this->sales_date);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('title_name',$this->title_name,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('sales_profit',$this->sales_profit,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('sales_freight',$this->sales_freight,true);
		$criteria->compare('sales_rebate',$this->sales_rebate,true);
		$criteria->compare('purchase_form_sn',$this->purchase_form_sn,true);
		$criteria->compare('purchase_price',$this->purchase_price,true);
		$criteria->compare('purchase_money',$this->purchase_money,true);
		$criteria->compare('purchase_freight',$this->purchase_freight,true);
		$criteria->compare('warehouse_fee',$this->warehouse_fee,true);
		$criteria->compare('warehouse_rebate',$this->warehouse_rebate,true);
		$criteria->compare('supply_rebate',$this->supply_rebate,true);
		$criteria->compare('sale_subsidy',$this->sale_subsidy,true);
		$criteria->compare('invoice',$this->invoice,true);
		$criteria->compare('pledge_fee',$this->pledge_fee,true);
		$criteria->compare('hight_open',$this->hight_open,true);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('owner_name',$this->owner_name,true);
		$criteria->compare('is_yidan',$this->is_yidan);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfitCollecting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	/*
	 * 采销利润
	 */
	public static  function getProfitData($search)
	{
		$tableData=array();
		$tableHeader=array(
				array('name'=>'销售日期','class' =>"sort-disabled text-left",'width'=>"80px"),//修
				array('name'=>'产地','class' =>"sort-disabled text-left",'width'=>"60px"),//
				array('name'=>'品名','class' =>"flex-col sort-disabled text-left",'width'=>"70px"),//
				array('name'=>'材质/规格/长度','class' =>"flex-col sort-disabled text-left",'width'=>"110px"),
				array('name'=>'销售公司','class' =>"flex-col sort-disabled text-left",'width'=>"60px"),
				array('name'=>'结算单位','class' =>"flex-col sort-disabled text-left",'width'=>"60px"),
				array('name'=>'销售利润','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'吨均利润','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'销售单号','class' =>"flex-col sort-disabled ",'width'=>"100px"),
				array('name'=>'销售数量','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'销售单价','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'销售金额','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),
				array('name'=>'销售运费','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),
				array('name'=>'销售折让','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),
				array('name'=>'采购/退货单号','class' =>"flex-col sort-disabled text-left",'width'=>"110px"),
				array('name'=>'成本单价','class' =>"flex-col sort-disabled text-right",'width'=>"60px"),
				array('name'=>'成本金额','class' =>"flex-col sort-disabled text-right",'width'=>"110px"),
				array('name'=>'采购运费','class' =>"flex-col sort-disabled text-right",'width'=>"85px"),
				array('name'=>'托盘费用','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'钢厂返利','class' =>"flex-col sort-disabled text-right",'width'=>"95px"),
// 				array('name'=>'仓库费用','class' =>"flex-col sort-disabled text-right",'width'=>"95px"),
// 				array('name'=>'仓库返利','class' =>"flex-col sort-disabled text-right",'width'=>"95px"),
				array('name'=>'业务员','class' =>"flex-col sort-disabled ",'width'=>"60px"),
				array('name'=>'是否乙单','class' =>"flex-col sort-disabled ",'width'=>"70px"),
				array('name'=>'发票成本','class' =>"flex-col sort-disabled text-right",'width'=>"95px"),
// 				array('name'=>'销售提成','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'仓库','class' =>"flex-col sort-disabled text-left",'width'=>"80px"),
				array('name'=>'备注','class' =>"flex-col sort-disabled text-left",'width'=>"250px"),
		);
		
// 		$dg=DictTitle::model()->find('short_name ="登钢商贸"')->id;
// 		$jm=DictTitle::model()->find('short_name="爵淼实业"')->id;
		$model=new ProfitCollecting();
		$criteria=new CDbCriteria();
		if($search['keywords']){
			$criteria->addCondition('form_sn like "%'.$search['keywords'].'%" or purchase_form_sn like "%'.$search['keywords'].'%"');
			// $criteria->params[':keyword']='"%'.$search['keywords'].'%"';
		}
		if($search['title']){$criteria->compare('t.title_id',$search['title']);}
		if($search['product']){$criteria->compare('product_id',$search['product']);}
		if($search['rank']){$criteria->compare('rank_id',$search['rank']);}
		if($search['texture']){$criteria->compare('texture_id',$search['texture']);}
		if($search['brand']){$criteria->compare('brand_id',$search['brand']);}
		if($search['time_H']){$criteria->addCondition("unix_timestamp(sales_date)<=".strtotime($search['time_H']));}
		if($search['owned'])$criteria->compare('owner_id', $search['owned']);
		if($search['is_yidan']!='')$criteria->compare('is_yidan', $search['is_yidan']); 
		if($search['company'])$criteria->compare('company_id', $search['company']);
		if($search['warehouse']){$criteria->compare('warehouse_id', $search['warehouse']);}
		if($search['vendor']){$criteria->compare('supply_id', $search['vendor']);}
		if($search['comment']){$criteria->addCondition('comment like "'.ProfitCollecting::$source_trans[$search['comment']].'"');}
		if($search['pur_confirm']!=''){$criteria->compare('confirmed', $search['pur_confirm']);}
// 		if($search['dgjm_title']){$criteria->addInCondition('title_id',array($dg,$jm));}
// 		elseif($search['dg_title']){$criteria->compare('title_id',$dg);}
// 		elseif($search['jm_title']){$criteria->compare('title_id',$jm);}
// 		else{$criteria->addNotInCondition('title_id', array($dg,$jm));}
		
		$criteria->addCondition("unix_timestamp(sales_date) >=".strtotime($search['time_L']));		
		//汇总
		$newCri=clone $criteria;
		$newCri->select='sum(weight) as weight,sum(fee) as fee,sum(sales_profit) as  sales_profit,
				sum(sales_freight) as sales_freight,sum(sales_rebate) as sales_rebate,sum(purchase_money) as purchase_money,
				sum(purchase_freight) as purchase_freight,sum(pledge_fee) as pledge_fee,sum(supply_rebate) as supply_rebate,
				sum(warehouse_fee) as  warehouse_fee,sum(warehouse_rebate) as warehouse_rebate,sum(invoice) as invoice,sum(sale_subsidy) as sale_subsidy';
		$total=$model->find($newCri);
		$totalData=array();
		$totalData['weight']=$total->weight;
		$totalData['fee']=$total->fee;
		$totalData['sales_profit']=$total->sales_profit;
		
		$totalData1=array('合计','','','','','',
				number_format($total->sales_profit,2),'','',number_format($total->weight,3),'',number_format($total->fee,2),number_format($total->sales_freight,2)
				,number_format($total->sales_rebate,2),'','',number_format($total->purchase_money,2),number_format($total->purchase_freight,2),
				number_format($total->pledge_fee,2),number_format($total->supply_rebate,2),//number_format($total->warehouse_fee,2),
// 				number_format($total->warehouse_rebate,2),
				'','',number_format($total->invoice,2),
// 				number_format($total->sale_subsidy,2),
				'',''
		);
		
		$pages=new CPagination();
		$pages->itemCount=$model->count($criteria);
		$pages->pageSize =intval($_COOKIE['profit_list']) ? intval($_COOKIE['profit_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order='form_sn desc';
		$details=$model->findAll($criteria);
		if($details)
		{
			$i=1;
			foreach ($details as $each)
			{
				$da['data']=array(
						$each->sales_date,
						DictGoodsProperty::getProName($each->brand_id),						
						DictGoodsProperty::getProName($each->product_id),
						DictGoodsProperty::getProName($each->texture_id).'*'.DictGoodsProperty::getProName($each->rank_id).($each->length==0?'':'*'.$each->length),
						DictTitle::getName($each->title_id),
						DictCompany::getName($each->company_id),
						number_format($each->sales_profit,2),
						number_format($each->sales_profit/$each->weight,2),
						$each->form_sn,
						number_format($each->weight,3),
						number_format($each->price,2),
						number_format($each->fee,2),
						number_format($each->sales_freight,2),
						number_format($each->sales_rebate,2),
						$each->purchase_form_sn,
						number_format($each->purchase_price,2),
						number_format($each->purchase_money,2),
						number_format($each->purchase_freight,2),
						number_format($each->pledge_fee,2),
						number_format($each->supply_rebate,2),
// 						number_format($each->warehouse_fee,2),
// 						number_format($each->warehouse_rebate,2),
						$each->owner_name,
						$each->is_yidan?'乙单':'',
						number_format($each->invoice,2),
// 						number_format($each->sale_subsidy,2),
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
	*利润汇总
	*/
	public static function totalProfitData($search)
	{
		$data=array();
		
// 		$dg=DictTitle::model()->find('short_name ="登钢商贸"')->id;
// 		$jm=DictTitle::model()->find('short_name="爵淼实业"')->id;
		//采销利润汇总		
		$criteria=new CDbCriteria();
		if($search['product']){$criteria->compare('product_id',$search['product']);}
		if($search['rank']){$criteria->compare('rank_id',$search['rank']);}
		if($search['texture']){$criteria->compare('texture_id',$search['texture']);}
		if($search['brand']){$criteria->compare('brand_id',$search['brand']);}
		if($search['vendor']){$criteria->compare('supply_id',$search['vendor']);}
		if($search['warehouse']){$criteria->compare('warehouse_id', $search['warehouse']);}
		
		//不再筛选登钢爵淼--2016-11-21
// 		if($search['dgjm_title']){$criteria->addInCondition('title_id',array($dg,$jm));}
// 		elseif($search['dg_title']){$criteria->compare('title_id',$dg);}
// 		elseif($search['jm_title']){$criteria->compare('title_id',$jm);}
// 		else{$criteria->addNotInCondition('title_id', array($dg,$jm));}
		$cri=clone $criteria;
		if($search['company'])$criteria->compare('company_id', $search['company']);		
		if(!$search['time_L']||$search['time_L']<date('Y-m-d',Yii::app()->params['turn_time'])){
			$criteria->addCondition("unix_timestamp(sales_date)>=".Yii::app()->params['turn_time']);
		}else{
			$criteria->addCondition("unix_timestamp(sales_date) >=".strtotime($search['time_L']));
		}
		if($search['time_H']){$criteria->addCondition("unix_timestamp(sales_date)<=".strtotime($search['time_H']));}
		if($search['is_yidan']!=''){$criteria->compare('is_yidan',$search['is_yidan']);}
		if($search['owned']){$criteria->compare('owner_id', $search['owned']);}
		
		$criteria->select='sum(sales_profit) as sales_profit,sum(purchase_money) as purchase_money,sum(purchase_freight) as  purchase_freight,
				sum(supply_rebate) as supply_rebate,sum(invoice) as invoice,sum(pledge_fee) as pledge_fee,sum(fee) as fee,
				sum(warehouse_fee) as warehouse_fee,sum(warehouse_rebate) as warehouse_rebate,sum(sale_subsidy) as sale_subsidy,
				sum(sales_rebate) as sales_rebate,sum(hight_open) as hight_open,sum(sales_freight) as sales_freight,sum(weight) as weight';
		$res=ProfitCollecting::model()->find($criteria);
		if($res)
		{
			$data['sales_profit']=$res->sales_profit;
			$data['purchase_money']=$res->purchase_money;
			$data['purchase_freight']=$res->purchase_freight;
			$data['supply_rebate']=$res->supply_rebate;
			$data['invoice']=$res->invoice;
			$data['pledge_fee']=$res->pledge_fee;
			$data['fee']=$res->fee;
			$data['warehouse_fee']=$res->warehouse_fee;
			$data['warehouse_rebate']=$res->warehouse_rebate;
			$data['sale_subsidy']=$res->sale_subsidy;
			$data['sales_rebate']=$res->sales_rebate;
			$data['hight_open']=$res->hight_open;
			$data['sales_freight']=$res->sales_freight;
			$data['weight']=$res->weight;
		}		
		//库存预估汇总	
		if($search['time_L']){$cri->addCondition('input_date>='.strtotime($search['time_L']));}
		if($search['time_H']){$cri->addCondition('input_date<='.strtotime($search['time_H']));}	
		// $details=Storage::model()->findAll($criteria);		
		$cri->select='sum(profit) as profit,sum(purchase_money) as purchase_money,sum(pledge_fee) as pledge_fee,
				sum(supply_rebate) as  supply_rebate,sum((left_weight-lock_weight)*price) as sale_money,sum(left_weight-lock_weight) as weight,sum(invoice) as invoice,
				sum(warehouse_fee) as warehouse_fee,sum(warehouse_rebate) as warehouse_rebate,sum(sale_subsidy) as sale_subsidy,sum(purchase_freight) as purchase_freight';
		if(!$search['company'])
		{
			$res=ProfitStorage::model()->find($cri);
			if($res)
			{
				$data['s_profit']=$res->profit;
				$data['s_purchase_money']=$res->purchase_money;
				$data['s_pledge_fee']=$res->pledge_fee;
				$data['s_supply_rebate']=$res->supply_rebate;
				$data['s_sale_money']=$res->sale_money;
				$data['s_warehouse_fee']=$res->warehouse_fee;
				$data['s_warehouse_rebate']=$res->warehouse_rebate;
				$data['s_sale_subsidy']=$res->sale_subsidy;
				$data['s_purchase_freight']=$res->purchase_freight;
				$data['s_weight']=$res->weight;
				$data['s_invoice']=$res->invoice;
			}
		}		
		return $data;
	}
	
	/*
	 * 采销利润导出获取数据
	 */
	public  static function getExportData($search)
	{
		$model=new ProfitCollecting();
		$criteria=new CDbCriteria();
		if($search['keywords']){
			$criteria->addCondition('form_sn like "%'.$search['keywords'].'%" or purchase_form_sn like "%'.$search['keywords'].'%"');
			// $criteria->params[':keyword']='"%'.$search['keywords'].'%"';
		}
		if($search['title']){$criteria->compare('t.title_id',$search['title']);}
		if($search['product']){$criteria->compare('product_id',$search['product']);}
		if($search['rank']){$criteria->compare('rank_id',$search['rank']);}
		if($search['texture']){$criteria->compare('texture_id',$search['texture']);}
		if($search['brand']){$criteria->compare('brand_id',$search['brand']);}
		if($search['time_H']){$criteria->addCondition("unix_timestamp(sales_date)<=".strtotime($search['time_H']));}
		if($search['owned'])$criteria->compare('owner_id', $search['owned']);
		if($search['is_yidan']!='')$criteria->compare('is_yidan', $search['is_yidan']);
		if($search['company'])$criteria->compare('company_id', $search['company']);
		if($search['warehouse']){$criteria->compare('warehouse_id', $search['warehouse']);}
		if($search['vendor']){$criteria->compare('supply_id', $search['vendor']);}
		if($search['comment']){$criteria->addCondition('comment like "'.ProfitCollecting::$source_trans[$search['comment']].'"');}
		if($search['pur_confirm']!=''){$criteria->compare('confirmed', $search['pur_confirm']);}
		$criteria->addCondition("unix_timestamp(sales_date) >=".strtotime($search['time_L']));
		//汇总
		$newCri=clone $criteria;
		$newCri->select='sum(weight) as weight,sum(fee) as fee,sum(sales_profit) as  sales_profit,
				sum(sales_freight) as sales_freight,sum(sales_rebate) as sales_rebate,sum(purchase_money) as purchase_money,
				sum(purchase_freight) as purchase_freight,sum(pledge_fee) as pledge_fee,sum(supply_rebate) as supply_rebate,
				sum(warehouse_fee) as  warehouse_fee,sum(warehouse_rebate) as warehouse_rebate,sum(invoice) as invoice,sum(sale_subsidy) as sale_subsidy';
		$total=$model->find($newCri);		
		$totalData1=array('合计','','','','','','','',
				$total->sales_profit,'','',$total->weight,'',$total->fee,$total->sales_freight
				,$total->sales_rebate,'','',$total->purchase_money,$total->purchase_freight,
				$total->pledge_fee,$total->supply_rebate,$total->warehouse_fee,
				$total->warehouse_rebate,'','',$total->invoice,$total->sale_subsidy,'',''
		);
		$criteria->order='form_sn desc';
		$i=0;
		$content=array();
		for(;;)
		{
			$cri=clone $criteria;
			$cri->limit=4000;
			$cri->offset=4000*$i;
			$details=$model->findAll($cri);			
			if($details)
			{
				foreach ($details as $each)
				{
					$temp=array(
							$each->sales_date,
							DictGoodsProperty::getProName($each->brand_id),
							DictGoodsProperty::getProName($each->product_id),
							DictGoodsProperty::getProName($each->texture_id),
							DictGoodsProperty::getProName($each->rank_id),
							$each->length,
							DictTitle::getName($each->title_id),
							DictCompany::getName($each->company_id),
							$each->sales_profit,
							$each->sales_profit/$each->weight,
							$each->form_sn,
							$each->weight,
							$each->price,
							$each->fee,
							$each->sales_freight,
							$each->sales_rebate,
							$each->purchase_form_sn,
							$each->purchase_price,
							$each->purchase_money,
							$each->purchase_freight,
							$each->pledge_fee,
							$each->supply_rebate,
							$each->warehouse_fee,
							$each->warehouse_rebate,
							$each->owner_name,
							$each->is_yidan?'乙单':'',
							$each->invoice,
							$each->sale_subsidy,
							Warehouse::getName($each->warehouse_id),
							$each->comment,
					);
					array_push($content,$temp);			
				}				
			}else{
				break;
			}
			$i++;
			$details=null;
			$cri=null;
		}		
		array_push($content,$totalData1);		
		return $content;
	}

}
